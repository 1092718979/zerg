<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/5/5
 * Time: 15:52
 */

namespace app\api\controller\v1;

use app\api\service\Pay as PayService;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePostiveInt;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder'],
    ];

    /**
     * 预订单接口
     * 客户端传递token,order_id
     */
    public function getPreOrder($id = ''){
        (new IDMustBePostiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }

    /**
     * 微信回掉接口（post,xml格式,不能用？回掉参数）
     *      回掉频率15/15/30/180/1800/1800/1800/1800/3600秒（不是一定能回掉成功）
     * 使用微信提供的SDK解析处理数据
     * 第三次检测库存量
     * 更新订单的状态，进行库存量的扣除
     *      成功处理，向微信返回成功处理结果，微信会终止接口回掉
     *      没成功，返回没有处理成功，微信会继续回掉
     */
    public function receiveNotify(){
        $notify = new WxNotify();
        $notify->Handle();
    }
}