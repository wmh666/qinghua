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

    //信息表添加
    public function  information(Request $Request){
        $data = $Request->all();
        $list = Ver::information($data);
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }
    //上传文件
   public function addfile (Request $request) {
        $file = $request->file('file');
        $path = date('Ymd');
        if(!is_dir('file/'.$path)){
            mkdir('file/'.$path);
        }
        if(!empty($file)){
            $fil = $file->getClientOriginalName();
            $pathname = 'file/'.$path.'/'.$fil;
            $file->move('file/'.$path,$fil);
        }
       
        if($file){
            rData(successcode()[6]['code'],successcode()[6]['msg'],$pathname);
        }else{
            rData(errorcode()[11]['code'],errorcode()[11]['msg']);
        }
    }

    //下载
    public function downloadfile(Request $request){
        $id = $request->input();
        if(empty($id)){
            rData(errorcode()['6']['code'],errorcode()['6']['msg']);
        }
        $path = Ver::downloadfile($id);
        $name = basename($path);
        return response()->download($path, $name ,$headers = ['Content-Type'=>'application/zip;charset=utf-8']);
    }

    //信息列表
    public function inflist(Request $request){
        $data =  $request->all();
        $list = Ver::inflist($data);
        rData(successcode()['1']['code'],successcode()['1']['msg'],$list);
    }
}