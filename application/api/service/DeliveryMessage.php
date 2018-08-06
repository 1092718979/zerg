<?php
/**
 * Created by JiFeng.
 * User: 10927
 * Date: 2018/6/14
 * Time: 19:05
 */

namespace app\api\service;


use app\lib\exception\OrderException;

/**
 * Class DeliveryMessage
 * @package app\api\service
 * 模板消息
 */
class DeliveryMessage extends WxMessage{

    const DELIVERY_MSG_ID = '';

    public function sendDeliveryMessage($order, $tplJumpPage = '')
    {
        if (!$order) {
            throw new OrderException();
        }
        $this->tplID = self::DELIVERY_MSG_ID;
        $this->formID = $order->prepay_id;
        $this->page = $tplJumpPage;
        $this->prepareMessageData($order);
        $this->emphasisKeyWord='keyword2.DATA';
        return parent::sendMessage($this->getUserOpenID($order->user_id));
    }

    private function prepareMessageData($order)
    {
        $dt = new \DateTime();
        $data = [
            'keyword1' => [
                'value' => '顺风速运',
            ],
            'keyword2' => [
                'value' => $order->snap_name,
                'color' => '#27408B'
            ],
            'keyword3' => [
                'value' => $order->order_no
            ],
            'keyword4' => [
                'value' => $dt->format("Y-m-d H:i")
            ]
        ];
        $this->data = $data;
    }

    private function getUserOpenID($uid)
    {
        $user = User::get($uid);
        if (!$user) {
            throw new UserException();
        }
        return $user->openid;
    }


}