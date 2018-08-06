<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/21
 * Time: 16:12
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category {

    public function getAllCategories(){
        $categories = CategoryModel::getALL();
        if ($categories->isEmpty())
        {
            throw new CategoryException();
        }
        return $categories;
    }
}