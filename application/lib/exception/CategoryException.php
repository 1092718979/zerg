<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/21
 * Time: 16:29
 */

namespace app\lib\exception;


class CategoryException extends BaceException{
    public $code = 404;
    public $msg = '找不到对应Category类别';
    public $error_code = 50000;
}