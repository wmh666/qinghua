<?php
    //对象转数据
    function json($list){
        $list = json_encode($list);
        $list = json_decode($list,true);
        return $list;
    }

    function successcode(){
        $data = [
            '1'=>['code'=>200,'msg'=>'请求成功'],
            '2'=>['code'=>200,'msg'=>'登录成功'],
            '3'=>['code'=>200,'msg'=>'操作成功'],
            '4'=>['code'=>200,'msg'=>'验证码发送成功'],
            '5'=>['code'=>200,'msg'=>'注册成功'],
            '6'=>['code'=>200,'msg'=>'上传成功'],
            '7'=>['code'=>200,'msg'=>'请查收验证码']
        ];
        return $data;
    }
    function errorcode(){
        $data = [
            '1'=>['code'=>201,'msg'=>'请求失败'],
            '2'=>['code'=>202,'msg'=>'登录失败'],
            '3'=>['code'=>203,'msg'=>'账号不存在'],
            '4'=>['code'=>204,'msg'=>'密码错误'],
            '5'=>['code'=>205,'msg'=>'非法请求'],
            '6'=>['code'=>206,'msg'=>'缺少参数'],
            '7'=>['code'=>207,'msg'=>'数据异常'],
            '8'=>['code'=>208,'msg'=>'请稍后重试'],
            '9'=>['code'=>209,'msg'=>'操作失败'],
            '10'=>['code'=>210,'msg'=>'账号/密码不存在'],
            '11'=>['code'=>211,'msg'=>'上传失败'],
            '12'=>['code'=>212,'msg'=>'从新登录'],
            '13'=>['code'=>213,'msg'=>'稍后重试'],
            '14'=>['code'=>214,'msg'=>'稍后重试'],
            '15'=>['code'=>215,'msg'=>'验证码错误'],
            '16'=>['code'=>216,'msg'=>'两次密码不一致'],
            '17'=>['code'=>217,'msg'=>'账号已存在'],
            '18'=>['code'=>218,'msg'=>'手机号格式不正确']
        ];
        return $data;
    }

    function rData($state,$message,$data = []){
        $value = array(
            "state" => $state,
            "msg" => $message,
            "data" => $data,
        );
        echo json_encode($value);die;
    }

    function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^1[3,4,5,7,8,9]{1}[\d]{9}$#', $mobile) ? true : false;
    }

    function csv_export($data = array(), $head_list = array(), $fileName, $fre){
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$fileName.'.csv"');
    header('Cache-Control: max-age=0');
    
    //打开PHP文件句柄,php://output 表示直接输出到浏览器
    $fp = fopen('php://output', 'a');
    //首次插入数据写入Excel列名信息
   
    if( $fre == 1 || $fre == 0 )
    {
        foreach ( $head_list as $key => $value )
        {
            //CSV的Excel支持GBK编码，一定要转换，否则乱码
            $head_list[$key] = iconv('utf-8', 'gbk', $value);
        }
        //将数据通过fputcsv写到文件句柄
        fputcsv($fp, $head_list);
    }
    //计数器
    $num = 0;
    //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
    $limit = 100000;
    //逐行取出数据，不浪费内存
    $count = count($data);
    for ($i = 0; $i < $count; $i++)
    {
        $num++;
        //刷新一下输出buffer，防止由于数据过多造成问题
        if ($limit == $num)
        {
            ob_flush();
            flush();
            $num = 0;
        }
        $row = $data[$i];
        foreach ( $row as $key => $value )
        {
            $row[$key] = iconv('utf-8', 'gbk', $value);
        }
        fputcsv($fp, $row);
    }
}