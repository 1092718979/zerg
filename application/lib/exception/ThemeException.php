<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/20
 * Time: 20:20
 */

namespace app\lib\exception;


class ThemeException extends BaceException{
    public $code = 404;
    public $msg = '指定主题不存在，请检查对应ID';
    public $error_code = 30000;
}