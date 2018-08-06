<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/23
 * Time: 17:09
 */

namespace app\api\controller\v1;

use app\api\model\User;
use app\api\model\UserAddress;
use app\api\service\Token as TokenService;
use app\api\validate\AddressValidate;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address extends BaseController{
    //前置操作方法
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress,getUserAddress'],
    ];

    /**
     * 1 用户传递Token令牌
     *      去缓存中对比数据，获得用户的UID
     *      根据UID查看用户是否存在
     *          不存在则抛出异常
     *          存在则获取用户传入的信息
     * 2 根据用户地址信息是否存在，从而判断是添加还是更新
     */
    public function createOrUpdateAddress(){
        $validate = new AddressValidate();
        $validate->goCheck();

        $uid = TokenService::getCurrentUid(); 
        $user = User::get($uid);
        if (!$user){
            throw new UserException();
        }

        $dataArray = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address;

        if (!$userAddress){
            $user->address()->save($dataArray);
        }
        else{
            $user->address->save($dataArray);
        }
        return json(new SuccessMessage(),201);
    }

    public function getUserAddress(){
        $uid = TokenService::getCurrentUid();
        $userAddress = UserAddress::where('user_id',$uid)
                    ->find();
        if (!$userAddress){
            throw new UserException([
                'msg' => '用户地址不存在',
                'error_code' => 60001,
            ]);
        }
        return $userAddress;
    }
}