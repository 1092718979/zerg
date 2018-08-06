<?php
/**
 * Created by PhpStorm.
 * User: 10927
 * Date: 2018/4/14
 * Time: 14:38
 */

namespace app\api\validate;

class IDMustBePostiveInt extends BaceValidate
{
    protected $rule = [
        'id' => 'require|isPostiveInteger',
    ];
    protected $message = [
        'id' => 'id必须为正整数',
    ];

}