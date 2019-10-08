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

    //1 创新理论 2矛盾类型 3发明原理 4分离原理 5进化法则6使能技术 7改善/恶化(技术参数)
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
        $list =  DB::table('gbhy')
        ->whereraw('length(pid_code)<=3')
        ->select('code','type as label','pid_code','type as value')->get();
        $data = $this->Listpid($list);
        rData(successcode()['1']['code'],successcode()['1']['msg'],$data);
    }

    public function Listpid($list,$pid=0){
        $tree = [];
        $list = json($list);
        foreach ($list as $k=>$v){
            if ($v['pid_code'] == $pid){
                $v['children'] = $this->Listpid($list,$v['code']);
                if(empty($v['children'])){
                    unset($v['children']);
                }
                $tree[] = $v;
            }
       }
       return $tree;
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


    public function typelist(){
        $list = DB::table('import_type')->get();
        $list = json($list);
      
        foreach($list as $k=>$v){
            if($v['type'] == 1){
                $data['theory'][$v['id']] = $v;
            } else if($v['type'] == 2){
                $data['type'][$v['id']] = $v;
            }else if($v['type'] == 3){
                $data['invention'][$v['id']] = $v;
            }else if($v['type'] == 4){
                $data['separate'][$v['id']] = $v;
            }else if($v['type'] == 5){
                $data['rule'][$v['id']] = $v;
            }else if($v['type'] == 6){
                $data['use'][$v['id']] = $v;
            }else{
                $data['server'][$v['id']] = $v;
            }
        }
        rData(successcode()['1']['code'],successcode()['1']['msg'],$data);
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

     //发明原理/分离原理/进化法则
     public function principle(Request $request){
         $data = $request->all();
         $list = Import::principle($data);
         rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
     }

     //案例详情
     public function principlelist(Request $request){
        $id = $request->input('id');
        $list = Import::principlelist($id);
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
     }

     //表格导出
    public function principleExport(Request $request){
        $id = $request->input('id');
        $header = [
            '编号',
            '姓名',
            '企业名称',
            '所属国家',
            '所属行业',
            '案例描述',
            '遇到问题',
            '原理运营方式',
            '运营措施',
            '矛盾类型',
            '改善的服务技术参数',
            '恶化的服务技术参数',
            '自我矛盾的服务技术参数',
            '创新理论',
            '发明原理',
            '分离原理',
            '进化规则',
            '使用技能',
            '实施时间',
            '实施积极效果',
            '实施负面效果',
            '下线时间',
            '下线原因',
            '下线代替实施',
            '案例来源'
        ];
        $list = DB::table('case')->where('id',$id)->get();
        $list = json($list);
        csv_export($list,$header,'test.csv',0);
    }

}