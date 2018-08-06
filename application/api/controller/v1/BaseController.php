<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/30
 * Time: 12:44
 */

namespace app\api\controller\v1;

use app\api\service\Token as TokenService;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Controller;

class BaseController extends Controller{

    //验证最小权限    用户和管理员都可以访问
    public function checkPrimaryScope(){
        $scope = TokenService::getCurrentTokenVar('scope');
        if ($scope){
            if ($scope >= ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    //验证专有的权限   只有用户可以访问
    public function checkExclusiveScope(){
        $scope = TokenService::getCurrentTokenVar('scope');
        if ($scope){
            if ($scope == ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }
}