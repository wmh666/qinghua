<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Ver extends Model{
    public static function VerificationIns($data){
        $res = DB::table('verification')->insert($data);
        return $res;
    }

    public static function VerificationList(){
        $res = DB::table('verification')->get();
        $res = self::Listpid($res,0);
        return $res;
    }

    public static function Listpid($arr,$pid){
        $tree = [];
        $arr = json($arr);
        foreach ($arr as $k=>$v){
            if ($v['pid'] == $pid){
                $v['son'] = self::Listpid($arr,$v['id']);
                $tree[] = $v;
           }
       }
       return $tree;
    }

    
}