<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/22
 * Time: 16:01
 */

namespace app\lib\exception;


class TokenException extends BaceException{
    public $code = 401;
    public $msg = 'Token已经过期或者为无效Token';
    public $error_code = 10000;
}