<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Import extends Model
{
    public static function listimport($data){

        $list = DB::table('case');
        $list = $list ->paginate(10);
        $list = json($list);
        foreach($list['data'] as $k=>$v){
            if(!empty($v['img'])){
                $list['data'][$k]['img'] = explode(',',$v['img']);
            }else{
                $list['data'][$k]['img'] = [];
            }
        }
        return $list;
    }

    //案例统计
    public static function casecount($data){
        //行业类型
        $list = DB::table('case')->select(DB::raw('count(1) as count, impletime'));
        if(!empty($data['industry'])){
            $in = $data['industry'];
            $list = $list->whereraw('substr(industry,1,3) in '."('$in')");
        }else if(!empty($data['country'])){
            $list = $list->where('country',$data['country']);
        }else if(!empty($data['invention'])){
            $list = $list->where('invention',$data['invention']);
        }else if(!empty($data['separate'])){
            $list = $list->where('separate',$data['separate']);
        }else if(!empty($data['rule'])){
            $list = $list->where('rule',$data['rule']);
        }else if(!empty($data['use'])){
            $list = $list->where('use',$data['use']);
        }
        $list = $list->groupby('impletime')->get();
        $list = json($list);
        $data = [];
        foreach($list as $k=>$v){
            $data[$v['impletime']] = $v['count'];
        }
        return $data;
    }

    public static function principle($data){

        //$data['data'] = ['抽取','合并（需求属性合并）','合并（需求属性合并）']; 
        $list = DB::table('case')
            ->whereIn($data['surface'],$data['data'])
            ->select('describe','impletime','effect','id'); //默认搜索 /分离原理 /进化法则
            if(!empty($data['industry'])){
                $in = $data['industry'];//所属行业
                $list = $list->whereraw('substr(industry,1,3) in '."('$in')");
            }else if(!empty($data['country'])){
                $list = $list->where('country',$data['country']); //国家
            }else if(!empty($data['use'])){
                $list = $list->where('use',$data['use']); //使用技能
            }else if(!empty($data['self'])){
                $list = $list->where('self',$data['self']); //自我矛盾的服务技术参数
            }
            else if(!empty($data['keyword'])){
                $keyword = $data['keyword'];
                $list = $list ->where(function ($query) use ($keyword){
                    $query->where('describe',$keyword)->orWhere('effect',$keyword);
                })->get();
            }
            $list = $list ->paginate(10);
        return $list;
    }

    public static function principlelist($id){
        $list = DB::table('case')->where('id',$id)->select('self','invention','separate','rule','use','id')->first();
        return $list;
    }
}
