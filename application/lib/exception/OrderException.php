<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/5/1
 * Time: 10:54
 */

namespace app\lib\exception;


class OrderException extends BaceException{
    public $code = 400;
    public $msg = '订单ID不存在';
    public $error_code = 80000;
}