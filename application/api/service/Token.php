<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/22
 * Time: 15:11
 */

namespace app\api\service;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

/**
 * Class Token
 * @package app\api\service
 * 有两种Token  UserToken  AppToken
 *      相同的东西放在一起，不同的东西分别处理
 */
class Token {
    /**
     * 生成Token      用三组字符串做MD5加密，加密后的数据再返回
     *  32个字符组成的一组随机字符串
     *  当前时间的时间戳
     *  salt    盐
     */
    protected function generateToken(){
        $randChars = getRandChar(32);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $salt = config('secure.token_salt');
        return md5($randChars.$timestamp.$salt);
    }

    /**
     * 获取缓存中对应的字段值
     * @param $key  想要获取的缓存信息
     * @throws TokenException
     */
    public static function getCurrentTokenVar($key){
        $token =Request::instance()
                ->header('token');
        $vars = Cache::get($token);
        if (!$vars){
            throw new TokenException();
        }else{
            if (!is_array($vars)){
                $vars = json_decode($vars,true);
            }
            if (array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                throw new Exception('尝试获取的Token变量并不存在');
            }
        }
    }

    public static function getCurrentUid(){
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    /**
     * 检测用户操作的对象是否与令牌所得到的UID为同一个
     */
    public static function isValidOperate($checkedUID){
        if (!$checkedUID){
            throw new Exception('UID不能为空');
        }
        $UID = self::getCurrentUid();
        if ($UID == $checkedUID){
            return true;
        }
        return false;
    }

    /**
     * 判断token是否有效
     */
    public static function verifyToken($token){
        $exist = Cache::get($token);
        if ($exist){
            return true;
        }else{
            return false;
        }
    }
}