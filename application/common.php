<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 *
 * @param $url
 * @param int $httpCode
 * @return mixed
 */
function curl_get($url,&$httpCode = 0){
    //创建一个curl会话
    $ch = curl_init();
    //填写URL地址
    curl_setopt($ch,CURLOPT_URL,$url);
    //返回一个结果，而不是输出
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    //不做证书验证，当放到服务器时要开启验证
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    //服务器10秒无响应，则脚本就会断开连接
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
    //执行并获取HTML文档内容
    $file_contents = curl_exec($ch);
    //获取$ch信息
    $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    //释放curl句柄
    curl_close($ch);
    return $file_contents;
}

/**
 *
 * @param $length
 * @return null|string
 */
function getRandChar($length){
    $str = '';
    $strPol = 'QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm';
    $max = strlen($strPol)-1;
    for ($i = 0; $i < $length ; $i++){
        $str .= $strPol[rand(0,$max)];
    }
    return $str;
}











