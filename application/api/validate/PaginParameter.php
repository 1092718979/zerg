<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/5/15
 * Time: 19:05
 */

namespace app\api\validate;


class PaginParameter extends BaceValidate{
    protected $rule = [
        'page' => 'isPostiveInteger',
        'size' => 'isPostiveInteger'

    ];
    protected $message = [
        'size' => 'size是正整数',
        'page' => 'page是正整数',
    ];
}