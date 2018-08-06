<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/21
 * Time: 14:56
 */

namespace app\lib\exception;


class ProductException extends BaceException{
    public $code = 404;
    public $msg = '指定的商品不存在，请检查参数';
    public $error_code = 20000;
}