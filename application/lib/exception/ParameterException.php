<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/16
 * Time: 16:55
 */

namespace app\lib\exception;


class ParameterException extends BaceException
{
    public $code = 400;
    public $msg = '参数错误';
    public $error_code = '10000';
}