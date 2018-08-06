<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/4/30
 * Time: 9:52
 */

namespace app\api\controller\v1;

use app\api\service\Token as TokenService;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\OrderPlace;
use app\api\service\Order as OrderService;
use app\api\validate\PaginParameter;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;

/**
 * 用户选择商品后，提交所选择商品信息
 * 收到信息后，检查库存量
 * 有库存，把订单数据存入数据库中，下单成功，告诉客户端可以支付。
 * 调用我们的支付接口进行预支付（用户不是马上支付）
 *      付款也需要检测库存量
 * 发起支付，服务器向微信获取支付参数，返还给小程序，之后小程序内部调用支付，
 *      成功或失败微信都会返回给服务器和小程序结果
 *          成功  检测库存量，减去库存量
 */

/**
 * 先做库存检测
 * 创建订单     根据数量减去库存（预扣除）
 * 如果用户支付
 *      真正减去库存
 * 一定时间没有支付
 *      还原库存
 *          1.PHP定时器(linux crontab)    遍历数据库找到数据库未支付订单
 *          2.任务队列  将任务加入到任务队列(redis)
 *              触发缓存过期事件
 */
class Order extends BaseController{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getDetail,getSummaryByUser']
    ];

    /**
     * 获取用户历史订单简要信息并分页
     * @param $page 页数
     * @param $size 一页的数量
     */
    public function getSummaryByUser($page=1,$size=15){
        (new PaginParameter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid,$page,$size);
        if ($pagingOrders->isEmpty()){
            return [
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage(),
            ];
        }
        $data = $pagingOrders->hidden([
                'snap_items','snap_address','prepay_id'
            ])
            ->toArray();
        return [
            'data' => $data,
            'current_page' => $pagingOrders->getCurrentPage(),
        ];

    }

    /**
     * 获取全部订单简要信息（分页）
     */
    public function getSummary($page=1,$size=20){


        (new PaginParameter())->goCheck();
        $pageingOrders = OrderModel::getSummaryByPage($page,$size);
        if (!$pageingOrders){
            return [
                'current_page' => $pageingOrders->currentPage(),
                'data' => [],
            ];
        }
        $data = $pageingOrders->hidden(['snap_item','snap_address'])
                ->toArray();
        return [
            'current_page' => $pageingOrders->currentPage(),
            'data' => $data,
        ];
    }

    /**
     * 历史订单详细信息页
     */
    public function getDetail($id){
        (new IDMustBePostiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if (!$orderDetail){
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }

    /**
     * 下单接口
     * 参数形式
     * products = [
     *      [product_id=>1,count=3],
     *      [product_id=>2,count=5],
     * ]
     */
    public function placeOrder(){
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid,$products);
        return $status;
    }
}