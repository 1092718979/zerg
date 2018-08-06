<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/5/3
 * Time: 10:01
 */

namespace app\api\model;


class Order extends BaseModel{

    protected $hidden = ['user_id','delete_time','update_time',];
    protected $autoWriteTimestamp = true;

    public function getSnapItemsAttr($value) {
        if (empty($value)){
            return null;
        }
        return json_decode($value);
    }

    public function getSnapAddressAttr($value) {
        if (empty($value)){
            return null;
        }
        return json_decode($value);
    }

    public static function getSummaryByUser($uid,$page=1,$size=15){
        $pagingData = self::where('user_id','=',$uid)
            ->order('create_time desc')
            //（每页数量,简洁模式,相关配置），返回一个Paginator对象,等同于查询方法
            ->paginate($size,true,['page' => $page]);
        return $pagingData;
    }

    public static function getSummaryByPage($page=1,$size=15){
        $pagingData = self::order('create_time desc')
            //（每页数量,简洁模式,相关配置），返回一个Paginator对象,等同于查询方法
            ->paginate($size,true,['page' => $page]);
        return $pagingData;
    }
}