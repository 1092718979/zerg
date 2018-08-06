<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/6/11
 * Time: 20:25
 */

namespace app\api\model;


class ThirdApp extends BaseModel{


    public static function check($ac,$se){
        $app = self::where([
            'app_id' => $ac,
            'app_secret' => $se,
        ])->find();
        return $app;
    }
}