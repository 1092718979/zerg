<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/21
 * Time: 21:13
 */

namespace app\api\model;


class User extends BaseModel{

    public function address(){
        /**
         * 一对一关系中
         * 有外键的一方使用  belongsTo
         * 没有外键的一方使用 hasOne
         */
        return $this->hasOne('UserAddress','user_id','id');
    }

    public static function getByOpenID($openid){
        $user = self::where('openid','=',$openid)
                ->find();
        return $user;
    }

    public static function newUser($openid){
        $user = self::create([
            'openid' => $openid
        ]);
        return $user->id;
    }
}