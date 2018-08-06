<?php
/**
 * Created by PhpStorm.
 * User: 10927
 * Date: 2018/4/15
 * Time: 10:06
 */

namespace app\lib\exception;


class BannerMissException extends BaceException
{
    public $code = 404;
    public $msg = '请求Banner不存在';
    public $error_code = 40000;
}