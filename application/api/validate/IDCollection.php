<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/20
 * Time: 19:17
 */

namespace app\api\validate;


class IDCollection extends BaceValidate{
    protected $rule = [
        'ids' => 'require|checkIDs',
    ];
    protected $message = [
        'ids' => 'ids参数必须为以逗号分隔的多个正整数',
    ];
}