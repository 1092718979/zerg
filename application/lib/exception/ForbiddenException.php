<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/30
 * Time: 9:28
 */

namespace app\lib\exception;


class ForbiddenException extends BaceException{
    public $code = 403;
    public $msg = '权限不够';
    public $error_code = 10000;
}