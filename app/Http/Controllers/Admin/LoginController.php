<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
class LoginController extends Controller {
    //登录
    public function login(Request $Request){
        $data = $Request->all();
        if(empty($data['tel']) || empty($data['password']) || !is_numeric($data['type']) || empty($data['key'])){
            rData(errorcode()['6']['code'],errorcode()['6']['msg']);
        }
        $res = Login::login($data);
        if(is_numeric($res))
            if($res == 1){
                rData(errorcode()['3']['code'],errorcode()['3']['msg']);
            }else if($res == 2){
                rData(errorcode()['10']['code'],errorcode()['10']['msg']);
            }else{
                rData(errorcode()['5']['code'],errorcode()['5']['msg']);
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
    public function uprole(Request $Request){
        $data = $Request->all();
        if(empty($data['id']) || empty($data['roleid'])){
            rData(errorcode()['6']['code'],errorcode()['6']['msg']);
        }
        $res = Login::uprole($data);
        if($res)
            rData(successcode()['1']['code'],successcode()['1']['msg']);
        else
            rData(errorcode()['8']['code'],errorcode()['8']['msg']);
    }

    //权限列表
    public function rolelist(){
        $list = Login::rolelist();
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }

    //分配账号
    public function useradd(Request $Request){
        $data = $Request->all();
        if(empty($data['name']) || empty($data['tel']) || empty($data['role']) || empty($data['email'])){
            rData(errorcode()['6']['code'],errorcode()['6']['msg']);
        }
        $Mobile = isMobile($data['tel']);
        if(!$Mobile){
            rData(errorcode()['18']['code'],errorcode()['18']['msg']);
        }
        $res = Login::useradd($data);
        if($res != -1)
            rData(successcode()['1']['code'],successcode()['1']['msg']);
        else
            rData(errorcode()['19']['code'],errorcode()['19']['msg']);
    }


    //找回密码
    public function repwd(Request $Request){

        $data = $Request->all();
        if(empty($data['pwd']) || empty($data['new_pwd']) || empty($data['oldpwd']) || empty($data['tel']) || empty($data['email'])  || empty($data['code'])){
            rData(errorcode()['6']['code'],errorcode()['6']['msg']);
        }
        $find = DB::table('user_code')->where('email',$data['email'])->first();
        $find = json($find);
        if($data['code'] != $find['code']){
            rData(errorcode()['15']['code'],errorcode()['15']['msg']);
        }
        if(time()>$find['addtime']){
            rData(errorcode()['20']['code'],errorcode()['20']['msg']);
        }
        if($data['pwd'] != $data['new_pwd']){
            rData(errorcode()['16']['code'],errorcode()['16']['msg']);
        }

        $Mobile = isMobile($data['tel']);
        if(!$Mobile){
            rData(errorcode()['18']['code'],errorcode()['18']['msg']);
        }
        $res = Login::relist($data);
        if($res == 1){
            rData(errorcode()['3']['code'],errorcode()['3']['msg']);
        }
        $res = Login::repwd($data);
        if($res)
            rData(successcode()['1']['code'],successcode()['1']['msg']);
        else
            rData(errorcode()['8']['code'],errorcode()['8']['msg']);      
    }

    //邮箱预约
    public  function email(Request $request){
        $mobile = $request->input('mobile');
        $username = $request->input('username');
        $email = 'cfm1993@163.com';
        $content =  '新用户预约 预约人：'.$username.'  预约电话：'.$mobile; 
        $subject = '新用户预约注册';
        Mail::raw($content,function ($message) use($email, $subject) { 
                $message->to($email)->subject($subject); 
            }
        );
        rData(successcode()['1']['code'],successcode()['1']['msg']);
    }
    
}