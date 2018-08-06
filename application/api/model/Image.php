<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/19
 * Time: 10:16
 */

namespace app\api\model;


use think\Model;

class Image extends BaseModel{

    protected $hidden = ['id','from','delete_time','update_time'];

    public function getUrlAttr($value,$date){
        return $this->prefixImgUrl($value,$date);
    }
}