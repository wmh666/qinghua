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
        $res = DB::table('verification')->select('name as label','pid','id as value')->get();
        $res = self::Listpid($res,0);
        return $res;
    }

    public static function Listpid($arr,$pid){
        $tree = [];
        $arr = json($arr);
        foreach ($arr as $k=>$v){
            if ($v['pid'] == $pid){
                $v['children'] = self::Listpid($arr,$v['value']);
                if(empty($v['children'])){
                    unset($v['children']);
                }
                $tree[] = $v;
           }
       }
       return $tree;
    }

    public static function information($data){
        $data['addtime'] = date('Y-m-d H:i:s');
        $res = DB::table('information')->insert($data);
        return $res;
    }

    public static function downloadfile($id){
        $list = DB::table('information')->where('id',$id)->value('path');
        return $list;
    }

    //列表
    public static function inflist($data){
        $list = DB::table('information');
        if(!empty($data['service'])){
            $list = $list->where('service','like',"%$data[service]%");
        }
        $list = $list->select('id','ordername','type','scene','server','addtime')->paginate(6);
        return $list;
    }

    
}