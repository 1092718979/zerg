<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/5/5
 * Time: 16:30
 */

namespace app\api\service;

use app\api\service\Token as TokenService;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Loader;
use think\Log;

//加载Wx类库  import(,,);   extend/WxPay/WxPay.Api.php
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay {
    private $orderID;
    private $orderNO;

    function __construct($orderID) {
        if (!$orderID){
            throw new Exception('订单号不能为空');
        }
        $this->orderID = $orderID;
    }

    /**
     * 支付主方法
     * 校验订单号：订单号不存在，用户不匹配，支付状态
     * 进行库存检测
     *      失败，返回给客户端$status
     *      成功,调用微信接口获取支付参数
     */
    public function pay(){
        $this->checkOrderValid();
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);
        if (!$status['pass']){
            return $status;
        }
        return $this->makeWxPreOrder($status['orderPrice']);
    }

    /**
     * 生成微信预订单
     */
    private function makeWxPreOrder($totalPrice){
        $openid = TokenService::getCurrentTokenVar('openid');
        if (!$openid){
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice*100);
        $wxOrderData->SetBody('ANTA专卖');
        $wxOrderData->SetOpenid($openid);
        //微信支付回掉接口
        $wxOrderData->SetNotify_url('http://hh.com/zerg/public/api/v1/pay/notify');
        return $this->getPaySignature($wxOrderData);
    }

    /**
     * 调用统一下单接口
     * @param $wxOrderData
     */
    private function getPaySignature($wxOrderData){
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS'){
            Log::init([
                'type'  => 'file',
                'path'  => LOG_PATH,
                'level' => ['error'],
            ]);
            Log::record($wxOrder,'error');
            Log::record('获取与支付订单失败','error');
        }

        $this->recordPreOrder($wxOrder['prepay_id']);
        $sign = $this->sign($wxOrder);
        return $sign;
    }

    /**
     * 生成返回集，以及签名
     * @param $wxOrder
     * @return array
     */
    private function sign($wxOrder){
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0,1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');

        $sign = $jsApiPayData->MakeSign();
        $rawValue = $jsApiPayData->GetValues();
        $rawValue['paySign'] = $sign;
        //不向客户端返回appId
        unset($rawValue['appId']);

        return $rawValue;
    }

    /**
     * 处理prepay_id  用于向用户推送模板消息
     * @param $wxOrder
     */
    private function recordPreOrder($prepay_id){
    OrderModel::where('id','=',$this->orderID)
        ->update(['prepay_id' => $prepay_id]);
    }

    /**
     * 订单号检验
     */
    private function checkOrderValid(){
        $order = OrderModel::get($this->orderID);
        if (!$order){
            throw new OrderException();
        }
        if (!TokenService::isValidOperate($order->user_id)){
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003,
            ]);
        }
        if ($order->status != OrderStatusEnum::UNPAID){
            throw new OrderException([
                'msg' => '订单已支付',
                'errorCode' => 80003,
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }
}