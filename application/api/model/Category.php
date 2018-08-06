<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/21
 * Time: 16:13
 */

namespace app\api\model;


class Category extends BaseModel{
    protected $hidden = ['delete_time','update_time','create_time'];

    public function Img(){
        return $this->belongsTo('Image','topic_img_id','id');
    }

    public static function getALL(){
        $result = self::with(['Img'])
            ->select();
        return $result;
    }
}