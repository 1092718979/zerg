<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/30
 * Time: 18:15
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Validate;

class OrderPlace extends BaceValidate{
    protected $rule = [
        'products' => 'checkProducts'
    ];

    protected $singleRule = [
        'product_id' => 'require|isPostiveInteger',
        'count' => 'require|isPostiveInteger',
    ];

    protected function checkProducts($values)
    {
        if(empty($values)){
            throw new ParameterException([
                'msg' => '商品列表不能为空'
            ]);
        }
        foreach ($values as $value)
        {
            $this->checkProduct($value);
        }
        return true;
    }

    private function checkProduct($value)
    {
        $validate = new BaceValidate($this->singleRule);
        $result = $validate->check($value);
        if(!$result){
            throw new ParameterException([
                'msg' => '商品列表参数错误',
            ]);
        }
    }
}