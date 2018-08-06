<?php
/**
 * Created by PhpStorm.
 * User: 10927
 * Date: 2018/4/15
 * Time: 10:02
 */

namespace app\lib\exception;


use think\Exception;

class BaceException extends Exception
{
    #HTTP状态码    错误具体信息  自定义错误码
    public $code = 400;
    public $msg = '参数错误';
    public $error_code = 10000;

    public function __construct($params = []) {
        if (!is_array($params)){
            return ;
            //throw new Exception('参数必须是数组');
        }
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('error_code',$params)){
            $this->error_code = $params['error_code'];
        }
    }
}