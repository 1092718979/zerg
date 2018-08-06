<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/23
 * Time: 21:06
 */

namespace app\lib\exception;


class UserException extends BaceException{
    public $code = 400;
    public $msg = '用户不存在';
    public $error_code = 60000;
}