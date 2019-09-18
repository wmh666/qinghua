<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Login extends Model{

    public static function login($data){
        $user = DB::table('userinfo');
        if($data['type'] == 0){
            $user = $user->where('role',1);
        }else{
            $user = $user->where('role','<>',1); 
        }
        $user = $user->where('tel',$data['tel'])->first();
        if(empty($user)){
            return 1;
        }
        $key = md5($user->tel.$user->password.'tsinghua');

        if($user->password != $data['password']){
            return 2;
        }

        if($key != $data['key']){
            return 3;
        }

        return  self::findlist($user->id,$user->role);
    }

    //个人信息
    public static function findlist($id,$role){
        $user = DB::table('userinfo')->where('id',$id)->where('role',$role)->select('id','tel','role','addtime','name')->first();
        return $user;
    }

    //用户列表
    public static function userlist(){
        $list = DB::table('userinfo')
            ->join('role', 'userinfo.role', '=', 'role.id')
            ->where('role','<>',1)
            ->select('userinfo.id','tel','addtime','userinfo.name','role.rolename','name')
            ->paginate(10);
        return $list;
    }

    //修改权限
    
}