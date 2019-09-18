<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\Login;
use Illuminate\Http\Request;
class LoginController extends Controller {
    //登录
    public function login(Request $Request){
        $data = $Request->all();
        if(empty($data['tel']) || empty($data['password']) || !is_numeric($data['type']) || empty($data['key'])){
            rData(errorcode()['6']['code'],errorcode()['6']['msg'],[]);
        }
        $res = Login::login($data);
        if(is_numeric($res))
            if($res == 1){
                rData(errorcode()['3']['code'],errorcode()['3']['msg'],[]);
            }else if($res == 2){
                rData(errorcode()['10']['code'],errorcode()['10']['msg'],[]);
            }else{
                rData(errorcode()['5']['code'],errorcode()['5']['msg'],[]);
            }
        else
            rData(successcode()['1']['code'],successcode()['1']['msg'],$res);
    }

    //用户列表
    public function userlist(){
        $list = Login::userlist();
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }

    //修改权限
    
}