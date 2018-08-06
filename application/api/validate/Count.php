<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/21
 * Time: 14:08
 */

namespace app\api\validate;



class Count extends BaceValidate{
    protected $rule = [
        'count' => 'isPostiveInteger|between:1,15',
    ];
    protected $message = [
        'count' => 'count必须是1-15的正整数',
    ];
}