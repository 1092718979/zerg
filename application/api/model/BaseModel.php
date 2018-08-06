<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/19
 * Time: 15:28
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model{


    protected function prefixImgUrl($value,$data){
        if ($data['from'] == 1){
            $prefix = config('setting.img_prefix');
            return $prefix.$value;
        }
        return $value;
    }
}