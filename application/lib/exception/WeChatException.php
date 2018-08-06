<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/22
 * Time: 10:59
 */

namespace app\lib\exception;


class WeChatException extends BaceException{
    public $code = 400;
    public $msg = '参数错误';
    public $error_code = 999;
}