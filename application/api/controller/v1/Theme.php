<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/19
 * Time: 16:53
 */

namespace app\api\controller\v1;

use app\api\model\Theme as ThemeModel;
use app\api\validate\IDCollection;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ThemeException;
use think\Exception;

class Theme {
    /**
     * @url /thems?ids = id1,id2,id3....
     * @return 一组theme模型
     * 获取一组特殊商品的ID 图片和 点进去的图片
     */
    public function getSimpleList($ids = ''){
        (new IDCollection())->goCheck();
        $ids = explode(',',$ids);
        $result = ThemeModel::getTheme($ids);
        if ($result->isEmpty()){
            throw new ThemeException();
        }
        return $result;
    }

    /**
     * @url /theme/:id
     * @id thmem id号
     * 点进专题后的商品信息
     */
    public function getComplexOne($id){
        (new IDMustBePostiveInt())->goCheck();
        $theme = ThemeModel::getThemeWithProducts($id);
        if (!$theme){
            throw new ThemeException();
        }
        return $theme;
    }


}















