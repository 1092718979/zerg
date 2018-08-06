<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/18
 * Time: 20:19
 */

namespace app\api\model;


class BannerItem extends BaseModel{

    protected $hidden = ['id','img_id','banner_id','delete_time','update_time'];

    public function img(){
        #belongsTo('关联模型','当前模型外键','关联主键');
        return $this->belongsTo('Image','img_id','id');
    }


}