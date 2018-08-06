<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/21
 * Time: 21:04
 */

namespace app\api\validate;


class TokenGet extends BaceValidate{
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];
    protected $message = [
        'code' => 'code不能为空',
    ];

}