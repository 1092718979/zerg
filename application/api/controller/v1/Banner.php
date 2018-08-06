<?php
namespace app\api\controller\v1;

use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\BannerMissException;


class Banner
{
    /**
     * 获取指定的ID的Banner信息
     * url  /banner/:id
     * http GET
     * @id Banner的id号
     */
    public function getBanner($id)
    {
        /*#validate 验证器
        $data = [
            'name' => 'vendorasdfasdf',
            'email' => '1092718979qq.com'
        ];
        $validate = new TestVakudate();
        #正确返回true   batch()     批量验证返回数组
        $result = $validate->batch()->check($data);
        dump($validate->getError());

        */
        #使用静态方式     类对应数据库中的一张表

        (new IDMustBePostiveInt())->goCheck();

        $banner = BannerModel::getBannerByID($id);

        if (!$banner){
            throw new BannerMissException();
        }
        return $banner;

    }
}