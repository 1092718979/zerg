<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/5/7
 * Time: 20:20
 */

namespace app\api\service;

use app\api\model\Product;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class WxNotify extends \WxPayNotify{

    /**
     * 继承了微信回掉接口重写这个方法
     * @param array $data 微信返回的数据集
     * @param string $msg
     * TODO 用户基础该类之后需要重写该方法，成功的时候返回true，失败返回false
     */
    public function NotifyProcess($data, &$msg){
        if ($data['result_code'] == 'SUCCESS'){
            $orderNo = $data['out_trade_no'];
            Db::startTrans();
            try{
                $order = OrderModel::where('order_no','=',$orderNo)
                        ->lock(true)
                        ->find();
                //订单状态为未支付
                if ($order->status == OrderStatusEnum::UNPAID){
                    $oService = new OrderService();
                    $stockStatus = $oService->checkOrderStock($order->id);
                    //库存检验通过
                    if ($stockStatus['pass']){
                        $this->updateOrderStatus($order->id,true);
                        $this->reduceStock($stockStatus);
                    }else{
                        $this->updateOrderStatus($order->id,false);
                    }
                }
                Db::commit();

                return true;
            }catch (Exception $e){
                Db::rollback();
                Log::error($e);
                return false;
            }
        }else{
            //当微信返回支付失败时也要告诉微信知道了返回结果
            return true;
        }
    }

    /**
     * 订单状态
     *      付款 && 库存通过，改为已支付
     *      付款 && 库存没通过 改为付款但是库存不足
     */
    private function updateOrderStatus($orderID,$success){
        $status = $success ?
            OrderStatusEnum::PAIN :
            OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id','=',$orderID)
                ->lock(true)
                ->update(['status' => $status]);
    }

    /**
     * 减去库存量
     */
    private function reduceStock($stockStatus){
        foreach ($stockStatus['pStatusArray'] as $value){
            Product::where('id','=',$value['id'])
                    ->lock(true)
                    ->setDec('stock',$value['count']);
        }
    }
}