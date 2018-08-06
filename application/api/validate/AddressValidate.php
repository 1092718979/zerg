<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/23
 * Time: 17:15
 */

namespace app\api\validate;


class AddressValidate extends BaceValidate{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty',
    ];
}