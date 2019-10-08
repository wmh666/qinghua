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

        return  self::findlist($user->tel,$user->role);
    }

    //个人信息
    public static function findlist($tel,$role){
        $user = DB::table('userinfo')->where('tel',$tel)->where('role',$role)->select('id','tel','role','addtime','name')->first();
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
    public static function uprole($data){
        $res = DB::table('userinfo')->where('id',$data['id'])->update(['role'=>$data['roleid']]);
        return $res;
    }

    //权限列表
    public static function rolelist(){
        $list = DB::table('role')->where('id','<>',1)->get();
        return $list;
    }

    //分配账号
    public static function useradd($data){
        date_default_timezone_set ('PRC'); 
        $userfind = DB::table('userinfo')->where('name',$data['name'])->orWhere('tel',$data['tel'])->first();
        $userfind = json($userfind);
        if(empty($userfind)){
            $data['password'] = MD5(123456);
            $data['addtime'] = date('Y-m-d H:i:s');
            $res = DB::table('userinfo')->insert($data);
            return $res;
        }else{
            return '-1';
        }
    }

    //找回密码验证信息
    public static function relist($data){
        $res = DB::table('userinfo')->where('tel',$data['tel'])->where('name',$data['name'])->first();
        if(empty($res)){
            return 1;
        }
    }
    //找回密码
    public static function repwd ($data){
        $res = DB::table('userinfo')->where('tel',$data['tel'])->where('name',$data['name'])->update(['password'=>$data['new_pwd']]);
        return $res;
    }

}