<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
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
            $res =  DB::table('case')->insert($item);
            rData(successcode()['1']['code'],successcode()['1']['msg']);
        });
    }

    //案例列表
    public function listimport(Request $requests){
        
    }
}