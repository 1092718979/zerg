<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/21
 * Time: 14:01
 */

namespace app\api\controller\v1;

use app\api\model\Product as ProductModel;
use app\api\validate\Count;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ProductException;

class Product {
    /**
     * 显示最近商品
     * @param $count 显示数量的参数
     * /recent?count=*  开启了路由全匹配
     */
    public function getRecent($count = 15 ){
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if ($products->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    /**
     * 查询每个分类下对应的商品
     * @param $id   分类ID
     * @throws ProductException
     */
    public function getALLInCategory($id){
        (new IDMustBePostiveInt())->goCheck();
        $products = ProductModel::getProductsByCategoryID($id);
        if ($products->isEmpty()){
            throw new ProductException();
        }
        return $products;
    }

    public function getOne($id){
        (new IDMustBePostiveInt())->goCheck();
        $products = ProductModel::getProductDetail($id);
        if (!$products){
            throw new ProductException();
        }
        return $products;
    }
}





