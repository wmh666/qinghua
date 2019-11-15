<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\Import;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\Mail;
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
        $data = $requests->input('img');
        if(empty($data)){
            rData(errorcode()['6']['code'],errorcode()['6']['msg']);
        }
        $res = DB::table('case_img')->insert($data);
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
        $data = $this->Listpid($list,0);
        rData(successcode()['1']['code'],successcode()['1']['msg'],$data);
    }

    public function Listpid($list,$pid){
        $tree = [];
        $list = json($list);
        foreach ($list as $k=>$v){
            if ($v['pid_code'] === (string) $pid){
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
    //0 swot分析 , 1 共情图 ，2 服务 3 QFD 4商业模式
    public function map(Request $request){
        date_default_timezone_set ('PRC'); 
        $data = $request->all();
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
        $list = DB::table('map')->where('type',$data['type'])->where('uid',$data['uid'])->select('addtime','id')->orderBy('addtime','desc')->get();
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
        $data = $request->input('data');
        $null = [];
        foreach($data as $k=>$v){
            if(empty($v['fraction'])){
                $datanull = [];
                $datanull['relationship'] = $v['relationship'];
                $datanull['title'] = $v['title'];
                $datanull['fraction'] = $v['fraction'];
                $datanull['uid'] = $v['uid'];
                $datanull['htmlid'] = $v['htmlid'];
                $null[] = $datanull;
                unset($data[$k]);
            }
            
        }
        foreach($null as $nk =>$nv){
            foreach($data as $k=>$v){
                if($nv['title'] == $v['title'] && $nv['relationship'] == $v['relationship']){
                    $null[$nk]['fraction'] = $v['fraction'];
                }

                if($nv['title'] == $v['relationship'] && $nv['relationship'] == $v['title']){
                    $null[$nk]['fraction'] = $v['fraction'];
                }
            }
        }
        $array = array_merge($null,$data);
        $res = DB::table('over')->insert($array);
        if($res)
            rData(successcode()['1']['code'],successcode()['1']['msg']);
        else
            rData(errorcode()['8']['code'],errorcode()['8']['msg']);
     }

     //QFD 交叉关系 临时html 关系
     public function qfdlist(Request $request){ 
        $data = $request->all();
        $list['html'] = DB::table('html')->where('id',$data['htmlid'])->where('uid',$data['uid'])->select('html','id as htmlid')->get();
        if(!empty($data['title'])){
            $keyword = $data['title'];
            $list['over'] = DB::table('over')
                    ->select('title','fraction','relationship')
                    ->where('uid',$data['uid'])
                    ->where('htmlid',$data['htmlid'])
                    ->where(function ($query) use ($keyword){
                        $query->where('title',$keyword);
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

    //案例修改接口
    public function upcase_img(Request $request){
        $data = $request->all();
        $res = DB::table('case_img')->where('id',$data['id'])->update(['img'=>$data['img']]);
        if($res)
            rData(successcode()['1']['code'],successcode()['1']['msg']);
        else
            rData(errorcode()['8']['code'],errorcode()['8']['msg']);
    }

    //导出统计
    public function exportcount(Request $request){
        $data = $request->all();
        $list = Import::exportcount($data);
        $datalist = $this->countdata($list);
        $header = ['年份','数量'];
        $count = count($list);
        for($i=1; $i<=ceil($count); $i++){
            $start = ($i*2-2+1)-1;
            $end = 2;        
            $article = array_slice($datalist,$start,$end);
            csv_export($article,$header,'test.csv',$i);
        }
    }

    public function countdata($list){
        $item = [];
        foreach($list as $k=>$v){
            $data = [];
            $data['year'] = $v['impletime'];
            $data['count'] = $v['count'];
            $item[] = $data;
        }
        return  $item;
    }  

    
    public function test(){
    }

    public function mt(Request $request){
        $data = $request->all();
        if(!empty($data['deteriorate']) && !empty($data['improve'])){
            $a = DB::table('a')->where('title',$data['improve'])->where('left',$data['deteriorate'])->value('fen');
            $b = DB::table('a')->where('title',$data['deteriorate'])->where('left',$data['improve'])->value('fen');
            $a = trim($a," ");
            $b = trim($b," ");
            $idsa = explode(',',$a);
            $idsb = explode(',',$b);
            $ids = array_merge($idsa,$idsb);
           foreach($ids as $k=>$v){
                $item[] = (int) $v;
           }
            $t = DB::table('t')->whereIn('sn',$item)->select('name')->get();
            rData(successcode()['1']['code'],successcode()['1']['msg'],$t);
        }
       
    }

    public function htmldata(Request $request){
        $data = $request->all();
        $t = DB::table('htmldata')->insertGetId(['htmldata'=>$data['title'],'uid'=>$data['uid']]);
        rData(successcode()['1']['code'],successcode()['1']['msg'],$t);

    }

    public function htmldatalist(Request $request){
        $data = $request->all();
        $t = DB::table('htmldata')->where('id',$data['id'])->where('uid',$data['uid'])->first();
        $list = json($t);
        $item[] = explode(',',$list['htmldata']);
        rData(successcode()['1']['code'],successcode()['1']['msg'],$item);

    }

    public  function email(Request $request){
        $email = $request->input('email');
        $code = rand(111111,999999);
        $content =  '您的验证码为:'.$code.'请在5分钟内使用'; 
        $subject = '邮箱验证';
        Mail::raw($content,function ($message) use($email, $subject) { 
                $message->to($email)->subject($subject); 
            }
        );
        $find = DB::table('user_code')->where('email',$email)->first();
        $res = json($find);
        if(!empty($res['code'])){
            DB::table('user_code')->where('email',$email)->update(['email'=>$email,'code'=>$code,'addtime'=>time()+300]);
        }else{
            DB::table('user_code')->insert(['email'=>$email,'code'=>$code,'addtime'=>time()+300]);
        }
        rData(successcode()['1']['code'],successcode()['1']['msg'],$code);

    }

    //删除生成图片
    public function delimg(Request $request){
        $id = $request->input('id');
        $res = DB::table('map')->where('id',$id)->delete();
        rData(successcode()['1']['code'],successcode()['1']['msg'],$res);

    }
}