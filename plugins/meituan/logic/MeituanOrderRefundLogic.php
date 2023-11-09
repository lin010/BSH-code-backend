<?php


namespace app\plugins\meituan\logic;


use app\core\ApiCode;
use app\forms\common\order\OrderRefundForm;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\plugins\meituan\models\MeituanOrdeDetail;
use yii\db\Query;

class MeituanOrderRefundLogic
{
    public static function doRefund(MeituanOrdeDetail $meituanOrderDetail, $tradeRefundNo, $serviceFeeRefundAmount){

        $t = \Yii::$app->db->beginTransaction();
        try {

            if($meituanOrderDetail->refund_status != 1){

                //处理退款
                $order = Order::findOne($meituanOrderDetail->order_id);

                /** @var OrderDetail $orderDetail */
                $orderDetail = OrderDetail::find()->where([
                    'order_id' => $order->id,
                    'is_delete' => 0
                ])->with(['order', 'userCards' => function ($query) {
                    /** @var Query $query */
                    $query->andWhere(['is_use' => 1]);
                }])->one();

                $refundPrice = $orderDetail->total_price;

                if ($order->is_sale == Order::IS_SALE_YES) {
                    throw new \Exception('订单已过售后时间,无法申请售后');
                }

                if (!in_array($order->status, [Order::STATUS_WAIT_DELIVER,Order::STATUS_WAIT_RECEIVE,Order::STATUS_WAIT_COMMENT]))
                    throw new \Exception('该订单状态下,无法申请售后');

                $realityPrice = price_format($orderDetail->total_price);

                if ($refundPrice > $realityPrice) {
                    throw new \Exception('最多可退款金额￥' .$realityPrice);
                }

                // 生成售后订单
                $orderRefund = new OrderRefund();
                $orderRefund->mall_id = $order->mall_id;
                $orderRefund->mch_id = $orderDetail->order->mch_id;
                $orderRefund->user_id = $order->user_id;
                $orderRefund->order_id = $orderDetail->order_id;
                $orderRefund->order_detail_id = $orderDetail->id;
                $orderRefund->order_no = Order::getOrderNo('RE');
                $orderRefund->type = OrderRefund::TYPE_ONLY_REFUND;
                $orderRefund->refund_type = 1;
                $orderRefund->is_receipt = 0;
                $orderRefund->reason = "系统自动退款";
                $orderRefund->refund_price = $refundPrice;
                $orderRefund->remark = "";
                $orderRefund->pic_list = '';//$this->pic_list;
                $orderRefund->is_refund = OrderRefund::NO;
                $orderRefund->is_backstage = 0;
                $orderRefund->admin_id = 0;
                if (!$orderRefund->save()) {
                    throw new \Exception(json_encode($orderRefund->getErrors()));
                }

                // 更新订单详情售后状态
                $orderDetail->refund_status = OrderDetail::REFUND_STATUS_SALES;
                if (!$orderDetail->save()) {
                    throw new \Exception(json_encode($orderDetail->getErrors()));
                }

                //执行退款逻辑
                $form = new OrderRefundForm(["order_refund_id" => $orderRefund->id,
                    "type"            => OrderRefund::TYPE_ONLY_REFUND,
                    "refund"          => 2,
                    "is_agree"        => 1,
                ]);
                $res = $form->save(false);
                if($res['code'] != ApiCode::CODE_SUCCESS){
                    throw new \Exception($res['msg']);
                }

                $form = new OrderRefundForm(["order_refund_id" => $orderRefund->id,
                    "type"            => OrderRefund::TYPE_ONLY_REFUND,
                    "refund"          => 2,
                    "is_agree"        => 1,
                ]);
                $res = $form->save(false);
                if($res['code'] != ApiCode::CODE_SUCCESS){
                    throw new \Exception($res['msg']);
                }

                $meituanOrderDetail->tradeRefundNo          = $tradeRefundNo;
                $meituanOrderDetail->refund_money           = $meituanOrderDetail->tradeAmount;
                $meituanOrderDetail->serviceFeeRefundAmount = $serviceFeeRefundAmount;
                $meituanOrderDetail->refund_id              = $orderRefund->id;
                $meituanOrderDetail->refund_status          = 1;
                $meituanOrderDetail->refund_at              = time();
                if(!$meituanOrderDetail->save()){
                    throw new \Exception(json_encode($meituanOrderDetail->getErrors()));
                }
            }else{
                $orderRefund = OrderRefund::findOne($meituanOrderDetail->refund_id);
            }

            $t->commit();

            return $orderRefund;
        }catch (\Exception $e){
            $t->rollBack();
            return null;
        }
    }
}