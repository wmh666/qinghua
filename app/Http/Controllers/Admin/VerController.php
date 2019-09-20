<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\Ver;
use Illuminate\Http\Request;
class VerController extends Controller {
    //验证
    public function VerificationList(){
        $list = Ver::VerificationList();
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }

    public function VerificationIns(Request $Request){
        $data = $Request->all();
        if(empty($data['name']) || !is_numeric($data['pid'])){
            rData(errorcode()['6']['code'],errorcode()['6']['msg']);
        }
        $res = Ver::VerificationIns($data);
        if($res)
            rData(successcode()['1']['code'],successcode()['1']['msg']);
        else
            rData(errorcode()['8']['code'],errorcode()['8']['msg']);
    }

}