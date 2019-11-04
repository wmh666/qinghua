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
            '18'=>['code'=>218,'msg'=>'手机号格式不正确'],
            '19'=>['code'=>219,'msg'=>'账号/用户名已存在请从新添加'],
            '20'=>['code'=>220,'msg'=>'验证码过期']
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

function ma(){
    $data = [
        1=>'运动物体的重量',
        2=>'静止物体的重量',
        3=>'运动物体的长度',
        4=>'静止物体的长度',
        5=>'运动物体的面积',
        6=>'静止物体的面积',
        7=>'运动物体的体积',
        8=>'静止物体的体积',
        9=>'速率',
        10=>'力',
        11=>'应力或压力',
        12=>'形状',
        13=>'物体组成的稳定性',
        14=>'强度',
        15=>'移动物体的作用时间',
        16=>'静止物体作用时间',
        17=>'温度',
        18=>'亮度',
        19=>'移动物体使用的能量',
        20=>'静止物体使用的能量',
        21=>'功率',
        22=>'能源耗费',
        23=>'物质耗费',
        24=>'信息耗费',
        25=>'时间耗费',
        26=>'物质数量',
        27=>'可靠度',
        28=>'量测准确度',
        29=>'运行准确度',
        30=>'外界作用在物体上的有害因素',
        31=>'物体产生的有害因素',
        32=>'可制造性/可实现性',
        33=>'可用性',
        34=>'可修复性',
        35=>'适应性',
        36=>'系统复杂性',
        37=>'测量和控制复杂性',
        38=>'自动化程度',
        39=>'生产力'
    ];
    return $data;
}

function t(){
    $data = [
        1=>'分割',
        2=>'抽取',
        3=>'局部质量',
        4=>'非对称',
        5=>'合并/组合',
        6=>'多功能/通用',
        7=>'嵌套',
        8=>'重量补偿',
        9=>'预先反作用',
        10=>'预先作用',
        11=>'预先补偿/缓冲',
        12=>'等势原则',
        13=>'逆向思维',
        14=>'曲面化',
        15=>'动态化',
        16=>'不足或超额作用',
        17=>'维度转换',
        18=>'机械震动',
        19=>'周期性动作',
        20=>'有效作用的连续性',
        21=>'减少有害作用的时间',
        22=>'变害为利',
        23=>'反馈',
        24=>'中介体',
        25=>'自我服务',
        26=>'复制',
        27=>'廉价不耐用',
        28=>'机械系统的代替',
        29=>'气动或液压原理',
        30=>'柔性外壳和薄膜',
        31=>'多孔材料',
        32=>'改变颜色/光学参数',
        33=>'同质性',
        34=>'抛弃与再生',
        35=>'改变参数',
        36=>'相变',
        37=>'热膨胀（战略扩张）',
        38=>'加速氧化（病毒营销）',
        39=>'惰性气氛',
        40=>'复合材料'

    ];

    return $data;
}