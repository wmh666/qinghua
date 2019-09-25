<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Import extends Model
{
    public static function listimport($data){

        $list = DB::table('case');
        if(!empty($data['extername'])){
            $list = $list->where('extername',$data['extername']);
        }else if(!empty($data['country'])){
            $list = $list->where('country',$data['country']);
        }else if(!empty($data['self'])){
            $list = $list->where('self',$data['self']);
        }else if(!empty($data['invention'])){
            $list = $list->where('invention',$data['invention']);
        }else if(!empty($data['separate'])){
            $list = $list->where('separate',$data['separate']);
        }else if(!empty($data['rule'])){
            $list = $list->where('rule',$data['rule']);
        }else if(!empty($data['use'])){
            $list = $list->where('use',$data['use']);
        }else if(!empty($data['industry'])){
            $like = $data['industry'];
            $list = $list->where('industry','like',"'%$like%'");
        }
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
}
