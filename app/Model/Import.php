<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Import extends Model
{
    public static function listimport(){
        $list = DB::table('case')->paginate(10);
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
