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
        if(!empty($data['vid1']) || !empty($data['vid2']) || !empty($data['vid3'])){
           $list = $list->where('vid1',$data['vid1'])->where('vid2',$data['vid2'])->where('vid3',$data['vid3']);
        }
        $list = $list->select('id','ordername','type','scene','server','addtime')->paginate(6);
        return $list;
    }

    
}