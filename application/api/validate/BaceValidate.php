<?php
/**
 * Created by PhpStorm.
 * User: 10927
 * Date: 2018/4/14
 * Time: 15:11
 */

namespace app\api\validate;

use app\lib\exception\ParameterException;
use think\image\Exception;
use think\Request;
use think\Validate;

class BaceValidate extends Validate
{
    protected $rule = [];

    public function goCheck(){
        /**
         *  获取传入的参数
         *  对参数做校验
         *  batch()对数组进行校验
         *  $this->error    继承了Validate类里有这个参数
         *  在check（）中只要是返回结果不是true就会被记录error
         */
        $request = Request::instance();
        $params = $request->param();
        $result = $this->batch()->check($params);
        if (!$result){
            $e = new ParameterException([
                'msg' => $this->error
            ]);
            throw $e;
        }else{
            return true;
        }
    }
                                                        //$data=array[],$field='id'
    protected function isPostiveInteger($value,$rule='',$data='',$field='') {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function isNotEmpty($value,$rule='',$data='',$field=''){
        if(empty($value)){
            return false;
        }else{
            return true;
        }
    }

    protected function checkIDs($value){
        $value = explode(',',$value);
        if (empty($value)){
            return false;
        }
        foreach ($value as $id){
            if(!$this->isPostiveInteger($id)){
                return false;
            }
        }
        return true;
    }

    public function getDataByRule($arrays){
        if (array_key_exists('user_id',$arrays) | array_key_exists('uid',$arrays)){
            throw new ParameterException([
                'msg' => '参数中包含有非法参数名',
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value){
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }

    public function isMobile($value){
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule,$value);
        if ($result){
            return true;
        }
        else{
            return false;
        }
    }
}