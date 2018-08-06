<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/19
 * Time: 16:57
 */

namespace app\api\model;


class Theme extends BaseModel{
    protected $hidden = ['delete_time','update_time','head_img_id','topic_img_id'];

    public function topicImg(){
        //关联表名称，当前表外键，被关联表主键    一对一关系
        return $this->belongsTo('Image','topic_img_id','id');
    }

    public function headImg(){
        return $this->belongsTo('Image','head_img_id','id');
    }

    /**
     *  多对多关系
     *  theme   和   products
     *  belongsToMany('关联模型','中间表表名','外键','关联键');
     */
    public function products(){
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    public static  function getTheme($ids){
        $result = self::with(['headImg','topicImg'])
            ->select($ids);
        return $result;
    }

    public static function getThemeWithProducts($id){

        $theme = self::with(['products','headImg','topicImg'])
            ->find($id);
        return $theme;
    }
}