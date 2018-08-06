<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/21
 * Time: 21:14
 */

namespace app\api\service;


use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;

class UserToken extends Token{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code) {
        //每个code值，只能使用一次
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),
                $this->wxAppID,
                $this->wxAppSecret,
                $this->code
            );
    }

    /**
     * 调用微信接口，并进行返回验证。
     *      通过则去获取Token令牌
     * @throws Exception
     */
    Public function get(){
        $result = curl_get($this->wxLoginUrl);
        //把字符串变成数组
        $wxResult = json_decode($result,true);
        if(empty($wxResult)){
            //服务器内部异常，不返回客户端
            throw new Exception('微信内部错误');
        }else{
            //接口调用异常微信会返回errcode 包含具体错误异常
            $loginFail = array_key_exists('errcode',$wxResult);
            if ($loginFail){
                $this->processLoginError($wxResult);
            }else{
                $token = $this->grantToken($wxResult);
            }
        }
        return $token;
    }

    private function processLoginError($wxResult){
        //方便拓展,可添加日志等信息
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'error_code' => $wxResult['errcode'],
        ]);
    }

    /**
     *  授权Token
     * 1 拿到openid
     * 2 数据库查一下，openid是不是存在
     *      存在      不处理
     *      不存在    新增一条user
     * 3 生成令牌，把数据写入缓存
     *      用户访问后台携带令牌，存在缓存中更方便
     *      (键值对缓存) key:令牌  ->  value:wxResult,userid,scope(权限级别)
     * 4 把令牌返回客户端
     */
    private function grantToken($wxResult){
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if ($user){
            $userid = $user->id;
        }else{
            $userid = UserModel::newUser($openid);
        }
        $cacheValue = $this->prepareCacheValue($wxResult,$userid);
        $token = $this->saceToCache($cacheValue);
        return $token;
    }

    /**
     * 把数据读到缓存
     * @param $cacheValue
     */
    private function saceToCache($cacheValue){
        $key = self::generateToken();
        $value = json_encode($cacheValue);
        $expire_in = config('token_expire_in');

        //(key,value，过期时间)
        $request = cache($key,$value,$expire_in);

        if (!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005,
            ]);
        }
        return $key;
    }

    /**
     * 准备缓存值
     * @param $wxResult openid,session_key
     * @param $uid  自己数据库的用户ID
     * scope  用户身份权限
     */
    private function prepareCacheValue($wxResult,$uid){
        $cacheValue = $wxResult;
        $cacheValue['uid'] = $uid;
        $cacheValue['scope'] = ScopeEnum::User;
        return $cacheValue;
    }
}