<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/6/11
 * Time: 19:59
 */

namespace app\api\validate;


class AppTokenGet extends BaceValidate{
    protected $rule = [
        'ac' => 'require|isNotEmpty',
        'se' => 'require|isNotEmpty',
    ];
}