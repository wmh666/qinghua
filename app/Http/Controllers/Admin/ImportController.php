<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\Import;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\DB;
class ImportController extends Controller {

    public function import(Request $requests){
        set_time_limit(0);
        $file = $requests->file('file');
        $filePath = $file->getRealPath();
            excel::load($filePath, function($reader) {
            $data = $reader->all();
            $data = json($data);
            unset($data[0]);
            $item = [];
            foreach($data as $k=>$v){
                $list = DB::table('case')
                    ->where([
                                'name'=>$v[1],
                                'extername'=>$v[2],
                                'country'=>$v[3],
                                'industry'=>$v[4],
                                'describe'=>$v[5],
                                'problem'=>$v[6],
                                'operate'=>$v[7],
                                'measures'=>$v[8],
                                'type'=>$v[9],
                                'improve'=>$v[10],
                                'deteriorate'=>$v[11],
                                'self'=>$v[12],
                                'theory'=>$v[13],
                                'invention'=>$v[14],
                                'separate'=>$v[15],
                                'rule'=>$v[16],
                                'use'=>$v[17],
                                'impletime'=>$v[18],
                                'effect'=>$v[19],
                                'negative'=>$v[20],
                                'offlinetime'=>$v[21],
                                'reason'=>$v[22],
                                'replace'=>$v[23],
                                'source'=>$v[24]
                            ])
                    ->first();

                    if(!empty($list)){
                        continue;
                    }else{
                        $arr = [];
                        $arr['name']=$v[1];
                        $arr['extername']=$v[2];
                        $arr['country']=$v[3];
                        $arr['industry']=$v[4];
                        $arr['describe']=$v[5];
                        $arr['problem']=$v[6];
                        $arr['operate']=$v[7];
                        $arr['measures']=$v[8];
                        $arr['type']=$v[9];
                        $arr['improve']=$v[10];
                        $arr['deteriorate']=$v[11];
                        $arr['self']=$v[12];
                        $arr['theory']=$v[13];
                        $arr['invention']=$v[14];
                        $arr['separate']=$v[15];
                        $arr['rule']=$v[16];
                        $arr['use']=$v[17];
                        $arr['impletime']=$v[18];
                        $arr['effect']=$v[19];
                        $arr['negative']=$v[20];
                        $arr['offlinetime']=$v[21];
                        $arr['reason']=$v[22];
                        $arr['replace']=$v[23];
                        $arr['source']=$v[24];
                        $item[] = $arr;
                    }
            }
            DB::table('case')->insert($item);
            rData(successcode()['1']['code'],successcode()['1']['msg']);
        });
    }

    //案例列表
    public function listimport(Request $requests){
        $data = $requests->all();
        $list = Import::listimport($data);
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }

    //上传文件
    public function importfile(Request $requests){
        $data = $requests->all();
        if(empty($data)){
            rData(errorcode()['6']['code'],errorcode()['6']['msg']);
        }
        $res = DB::table('case')->where('id',$data['id'])->update(['img'=>$data['img']]);
        if($res)
            rData(successcode()['1']['code'],successcode()['1']['msg']);
        else
            rData(errorcode()['8']['code'],errorcode()['8']['msg']);
    }

    //1 企业名称,2企业国家所属,3 自我矛盾的服务技术参数,4 发明原理,5 分离原理,6 进化原则,7使能技术
    //搜索添加
    public function importtype(Request $requests){
        $data = $requests->all();
        if(empty($data['name']) || empty($data['type']) ){
            rData(errorcode()['6']['code'],errorcode()['6']['msg']);
        }
        $res =  DB::table('import_type')->insert($data);
        if($res)
            rData(successcode()['1']['code'],successcode()['1']['msg']);
        else
            rData(errorcode()['8']['code'],errorcode()['8']['msg']);
    }

    //所属行业
    public function gbhy(){
        $list =  DB::table('gbhy')->select('hydm','hymc')->get();
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }

    //国家
    public function country(){
        $list =  DB::table('country')->select('zh_name as country')->get();
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }

    //省市
    public function province(Request $request){
        $id = $request->input('cityid');
        $list = DB::table('city');
        if(!empty($id)){
            $list = $list->where('pid',$id)->where('type',2);
        }else{
            $list = $list->where('type',1);
        }
        $list = $list->select('id as cityid','cityname')->get();
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }

    //搜索哟条件
    public function typelist(Request $request){
        $id = $request->input('id');
        $list = DB::table('import_type')->where('type',$id)->get();
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }

    public function aa(Request $requests){
       echo  $_SERVER["REMOTE_ADDR"];
    }

    //map,swot分析
    public function map(Request $requests){
        date_default_timezone_set ('PRC'); 
        $data = $requests->all();
        if(empty($data['img']) || empty($data['type']) || empty($data['uid'])){
            rData(errorcode()['6']['code'],errorcode()['6']['msg']);
        }
        $data['addtime'] = date('Y-m-d H:i:s');
        $res = DB::table('map')->insert($data);
        if($res)
            rData(successcode()['1']['code'],successcode()['1']['msg']);
        else
            rData(errorcode()['8']['code'],errorcode()['8']['msg']);

    }
    //历史map,swot 服务
    public function mapHisory (Request $requests){
        $data = $requests->all();
        $list = DB::table('map')->where('type',$data['type'])->where('uid',$data['uid'])->select('addtime')->get();
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }

    public function mapPic(Request $requests){
        $id = $requests->input('id');
        $list = DB::table('map')->where('id',$id)->select('img')->get();
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }
}