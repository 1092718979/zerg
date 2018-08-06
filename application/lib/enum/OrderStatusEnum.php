<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/5/5
 * Time: 17:41
 */

namespace app\lib\enum;

//订单状态
class OrderStatusEnum
{
    //未支付
    const UNPAID = 1;
    //已支付
    const PAIN = 2;
    //已发货
    const DELIVERED = 3;
    //已支付，但是库存不足
    const PAID_BUT_OUT_OF = 4;
}