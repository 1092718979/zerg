<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/22
 * Time: 19:37
 */

namespace app\api\model;


class ProductImage extends BaseModel{
    protected $hidden = ['img_id','delete_time','product_id'];

    public function imgUrl(){
        return $this->belongsTo('Image','img_id','id');
    }
}