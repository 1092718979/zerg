<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/6/14
 * Time: 20:49
 */

namespace app\api\behavior;


class CORS {
    public function appInit(&$params){
        //允许所有域访问API
        header('Access-Control-Allow-Origin: *');
        //允许携带的值
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET,POST');
        if (request()->isOptions()){
            exit();
        }
    }
}