<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/5/1
 * Time: 9:34
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product as ProductModel;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use app\api\model\Order as OrderModel;
use think\Db;
use think\Exception;

class Order {
    //订单商品列表，客户端传递过来的参数
    protected $oProducts;
    //数据库查询出的每个商品对应的库存和全部信息
    protected $products;

    protected $uid;

    /**
     * 下单请求
     * 根据订单信息查询真是库存
     * 库存检测
     *      失败则返回status
     *      成功则创建订单
     */
    public function place($uid,$oProducts){
        $this->oProducts = $oProducts;
        $this->products = $this->getProductByOrder($oProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        if (!$status['pass']){
            //order_id  将作为主键返回客户端，为保 持代码一致性用-1标识没通过
            $status['order_id'] = -1;
            return $status;
        }
        //开始创建订单
        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;
    }

    /**
     * 生成订单
     */
    private function createOrder($Snap){
        try{
            //事务
            Db::startTrans();

            $orderNo = $this->makeOrderNo();
            $order = new OrderModel();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $Snap['orderPrice'];
            $order->total_count = $Snap['totalCount'];
            $order->snap_img = $Snap['snapImg'];
            $order->snap_name = $Snap['snapName'];
            $order->snap_address = $Snap['snapAddress'];
            $order->snap_items = json_encode($Snap['pStatus']);
            $order->save();

            $orderID = $order->id;
            foreach ($this->oProducts as &$value){
                $value['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);

            Db::commit();

            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $order->create_time,
            ];
        }catch (Exception $e){
            //回滚
            Db::rollback();
            throw $e;
        }
    }

    /**
     * @return string 订单编号
     */
    public function makeOrderNo(){
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }

    /**
     * 生成订单快照
     */
    private function snapOrder($status){
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => '',
            'snapImg' => '',
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        //序列化为JSON
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        if (count($this->products) > 1){
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    private function getUserAddress(){
        $userAddress = UserAddress::where('user_id','=',$this->uid)
                    ->find();
        if (!$userAddress){
            throw new UserException([
                'msg' => '用户收货地址不存在，下单失败',
                'error_code' => 60001,
            ]);
        }
        return $userAddress->toArray();
    }

    /**
     * 库存检测与数据处理
     */
    private function getOrderStatus(){
        $status = [
            //检测是否通过
            'pass' => true,
            //商品价格总和
            'orderPrice' => 0,
            //所有商品总数
            'totalCount' => 0,
            //保存所有商品详细信息
            'pStatusArray' => []
        ];
        foreach ($this->oProducts as $value){
            $pStatus = $this->getProducts(
                       $value['product_id'],$value['count'],$this->products
            );
            if (!$pStatus['haveStock']){
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['counts'];
            array_push($status['pStatusArray'],$pStatus);
        }
        return $status;
    }

    /**
     * 获取单个product的状态,详细信息
     *      历史订单要展示所有商品信息
     */
    private function getProducts($opID,$ocount,$products){
        $pIndex = -1;

        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'counts' => 0,
            'price' => 0,
            'name' => '',
            'totalPrice' => 0,
            'main_img_url' => null,
        ];
        for ($i = 0; $i < count($products) ; $i++){
            if ($opID == $products[$i]['id']){
                $pIndex = $i;
            }
        }
        if ($pIndex == -1){
            throw new OrderException([
                'msg' => 'id为'.$opID.'商品不存在创建订单失败',
            ]);
        }else{
            $product = $products[$pIndex];
            $stock = $product['stock'] - $ocount;
            if ($stock > 0){
                $pStatus['haveStock'] = true;
            }else{
                $pStatus['haveStock'] = false;
            }
            $pStatus['id'] = $product['id'];
            $pStatus['counts'] = $ocount;
            $pStatus['name'] = $product['name'];
            $pStatus['price'] = $product['price'];
            $pStatus['totalPrice'] = $product['price']*$ocount;
            $pStatus['main_img_url'] = $product['main_img_url'];
        }
        return $pStatus;
    }

    /**
     * 根据订单列表查真实的信息
     * @param $oProducts
     * 避免循环查数据库
     */
    private function getProductByOrder($oProducts){
        $p_ID = [];
        for ($i = 0 ; $i < count($oProducts) ; $i++){
            array_push($p_ID,$oProducts[$i]['product_id']);
        }
        $products = ProductModel::all($p_ID)
                    ->visible(['id','price','stock','name','main_img_url'])
                    ->toArray();
        return $products;
    }

    /**
     * 对外库存量检测的方法
     */
    public function checkOrderStock($orderID){
        $this->oProducts = OrderProduct::where('order_id','=',$orderID)
                    ->select();
        $this->products = $this->getProductByOrder($this->oProducts);
        $status = $this->getOrderStatus();
        return $status;
    }
}