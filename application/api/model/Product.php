<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/19
 * Time: 16:56
 */

namespace app\api\model;


class Product extends BaseModel{

    protected $hidden = [
        'delete_time','update_time','create_time','from','pivot','category_id'
    ];

    public function getMainImgUrlAttr($value,$date){
        return $this->prefixImgUrl($value,$date);
    }

    public static function getMostRecent($count){
        $products = self::limit($count)     //指定数量
            ->order('create_time desc')     //排序方式
            ->select();
        if (!$products){
            return $products;
        }
        /**
         * 不为空使用collection就把$products变成一个数据集
         *      并使用collection所提供的方法临时隐藏summary属性

        $collection = collection($products);
        $products = $collection->hidden(['summary']);*/
        return $products;
    }

    public static function getProductsByCategoryID($id){
        $products = self::where('category_id','=',$id)
                    ->select();
        return $products;
    }

    public static function getProductDetail($id){
        $product = self::with([
                    'imgs' => function($query){
                        $query->with(['imgUrl'])
                        ->order('order','asc');
                    }
                ])
                ->with(['properties'])
                ->find($id);
        return $product;
    }

    public function imgs(){
        return $this->hasMany('ProductImage','product_id','id');
    }

    public function properties(){
        return $this->hasMany('ProductProperty','product_id','id');
    }
}