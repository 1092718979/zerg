<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/29
 * Time: 10:05
 */

namespace app\api\model;


class UserAddress extends BaseModel{
    protected $hidden = ['id','delete_time','user'];
}