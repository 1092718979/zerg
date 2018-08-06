<?php
/**
 * Created by PhpStorm.
 * User: 10927
 * Date: 2018/4/14
 * Time: 17:19
 */

namespace app\api\model;


class Banner extends BaseModel
{
    //当前模型所连接的表明
    protected $table = '';
    //隐藏表中属性的显示
    protected $hidden = ['delete_time','update_time',];


    public static function getBannerByID($id){
        /**
         *  数据库查询两种方式
         * $result = Db::query('select * from banner_item where banner_id=?',[$id]);
        $result = Db::table('banner_item')
                ->where(function ($query) use ($id){
                    $query->where('banner_id','=',$id);
                })
                ->select();

        return $result;*/
        $banner = self::with(['items','items.img'])
            ->find($id);
        //$banner->hidden(['delete_time','update_time']);
        return $banner;
    }

    /**
     * @return \think\model\relation\HasMany
     * 表间关联
     */
    public function items(){
        #（关联模型名，关联模型外键，当前模型主键）
        return $this->hasMany('BannerItem','banner_id','id');
    }

}