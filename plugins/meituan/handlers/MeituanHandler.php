<?php


namespace app\plugins\meituan\handlers;


use app\handlers\BaseHandler;
use app\models\Mall;
use app\models\Order;
use app\plugins\meituan\logic\MeituanOrdeLogic;
use app\plugins\meituan\models\MeituanOrdeDetail;

class MeituanHandler extends BaseHandler
{
    public function register()
    {
        try {
            \Yii::$app->on(Order::EVENT_CREATED, function ($event) {
                $order = $event->order;
                if($order->sign == "meituan") {
                    $detail = $order->detail;
                    $goods = $detail[0]->goods;

                    //关联订单ID
                    $meituanOrderDetail = MeituanOrdeDetail::findOne(["goods_id" => $goods->id]);
                    $meituanOrderDetail->order_id = $order->id;
                    $meituanOrderDetail->save();
                }
            });

            \Yii::$app->on(Order::EVENT_PAYED, function ($event) {
                $order = $event->order;
                if($order->sign == "meituan"){
                    $detail = $order->detail;
                    $goods = $detail[0]->goods;

                    //下架商品
                    $goods->status = 0;
                    if(!$goods->save()){
                        throw new \Exception(json_encode($goods->getErrors()));
                    }

                    //订单自动发货
                    $form = new \app\forms\common\order\OrderSendForm([
                        "order_id"          => $order->id,
                        "is_express"        => 0,
                        "express"           => "",
                        "express_no"        => "",
                        "merchant_remark"   => "",
                        "mch_id"            => 0,
                        "customer_name"     => "",
                        "order_detail_id"   => [$detail[0]->id],
                        "express_content"   => "系统自动发货",
                        "express_single_id" => "",
                        "express_code"      => "",
                        "is_console"        => true
                    ]);
                    $form->save(false, Mall::findOne($order->mall_id));

                    //美团订单详情支付设置逻辑
                    $meituanOrderDetail = MeituanOrdeDetail::findOne(["goods_id" => $goods->id]);
                    MeituanOrdeLogic::doPay($meituanOrderDetail, $order, $goods);
                }
            });
        }catch(\Exception $e){

        }
    }
}