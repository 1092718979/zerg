<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/21
 * Time: 21:02
 */

namespace app\api\controller\v1;


use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\validate\AppTokenGet;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;

class Token {

    public function getToken($code = ''){
        (new TokenGet())->goCheck();
        $usertoken = new UserToken($code);
        $token = $usertoken->get();
        return [
            'token' => $token,
        ];
    }

    /**
     * 校验用户Token
     * @param string $token
     * @return array
     * @throws ParameterException
     */
    public function verifyToken($token = ''){
        if (!$token){
            throw new ParameterException([
                'msg' => 'token不能为空',
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return [
            'isValid' => $valid,
        ];
    }

    /**
     * 第三方应用
     */
    public function getAppToken($ac='',$se=''){
        (new AppTokenGet())->goCheck();
        $app = new AppToken();
        $token = $app->get($ac,$se);
        return [
            'token' => $token,
        ];
    }
}