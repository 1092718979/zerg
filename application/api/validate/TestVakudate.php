<?php
/**
 * Created by PhpStorm.
 * User: 10927
 * Date: 2018/4/14
 * Time: 9:52
 */

namespace app\api\validate;


use think\Validate;

class TestVakudate extends Validate
{
    //固定变量名
    protected $rule = [
        'name' => 'require|max:10',
        'email' => 'email'
    ];
}