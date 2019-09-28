<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\Import;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\input;
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

    //所属行业大类
    public function gbhy(){
        $where = ['A','B','C','D','E'];
        $list =  DB::table('gbhy')->select('code','type')->whereIn('code',$where)->get();
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

    public function aa(Request $request){
        
    }
    //0 swot分析 , 1 共情图 ，2 服务 3 QFD 4商业模式
    public function map(Request $request){
        date_default_timezone_set ('PRC'); 
        $data=Input::all();
        if(empty($data['img']) || !is_numeric($data['type']) || empty($data['uid'])){
            rData(errorcode()['6']['code'],errorcode()['6']['msg']);
        }
        $date = date('Y-m-d H:i:s');
        $data['addtime'] = "$date";
        $res = DB::table('map')->insert($data);
        if($res)
            rData(successcode()['1']['code'],successcode()['1']['msg']);
        else
            rData(errorcode()['8']['code'],errorcode()['8']['msg']);

    }
    //历史 0 swot分析 , 1 共情图 ，2 服务 3 QFD 4 商业模式
    public function mapHisory (Request $requests){
        $data = $requests->all();
        $list = DB::table('map')->where('type',$data['type'])->where('uid',$data['uid'])->select('addtime','id')->get();
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }

    public function mapPic(Request $requests){
        $id = $requests->input('id');
        $list = DB::table('map')->where('id',$id)->select('img')->get();
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }

    //表格导出
    public function export(Request $request){
        $data = $request->all();
        $header = $data['header'];
        $count = count($data['arr']);
        for($i=1; $i<=ceil($count); $i++){
            $start = ($i*2-2+1)-1;
            $end = 2;        
            $article = array_slice($data['arr'],$start,$end);
            csv_export($article,$header,'test.csv',$i);
        }
    }

    //QFD 1html
    public function qfd(Request $request){
        date_default_timezone_set ('PRC'); 
        $data = $request->all();
        $data['addtime'] = date('Y-m-d H:i:s');
        $res = DB::table('html')->insertGetId($data);
        if($res)
            rData(successcode()['1']['code'],successcode()['1']['msg'],$res);
        else
            rData(errorcode()['8']['code'],errorcode()['8']['msg']);

    }

     //QFD 交叉关系
     public function qfdover(Request $request){
        $data = $request->all();
        $res = DB::table('over')->insertGetId($data);
        if($res)
            rData(successcode()['1']['code'],successcode()['1']['msg']);
        else
            rData(errorcode()['8']['code'],errorcode()['8']['msg']);
     }

     //QFD 交叉关系 临时html 关系
     public function qfdlist(Request $request){ 
        $data = $request->all();
        $list['html'] = DB::table('html')->where('id',$data['html_id'])->where('uid',$data['uid'])->select('html')->get();
        if(!empty($data['title'])){
            $keyword = $data['title'];
            $list['over'] = DB::table('over')
                    ->select('title','fraction','relationship')
                    ->where('uid',$data['uid'])
                    ->where(function ($query) use ($keyword){
                        $query->where('title',$keyword)->orWhere('relationship',$keyword);
                    })->get();
        }
         rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
     }

     public function gbhydata(Request $request){
        $code = $request->input('code');
        $list = DB::table('gbhy')->select('code','type')->where('pid_code',$code)->get();
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
     }

     //案例tsing_case
     public function casecount(Request $request){
        $data = $request->all();
        $list = Import::casecount($data);
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
     }

     //发明原理
     public function principle(Request $request){
         $data = $request->all();
         $list = Import::principle($data);
         rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
        
     }

}