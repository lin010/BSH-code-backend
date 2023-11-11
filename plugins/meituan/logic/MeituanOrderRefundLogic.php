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
    public static function doRefund(MeituanOrdeDetail $meituanOrderDetail, $tradeRefundNo, $refundPrice, $serviceFeeRefundAmount){

        //金豆券比例
        //多少金豆券抵扣一元;
        $integralPrice = 1;
        $order = Order::findOne($meituanOrderDetail->order_id);

        //计算出红包服务费
        $totalIntegralPrice = OrderDetail::find()->where(['order_id'  => $order->id, 'is_delete' => 0])->sum("integral_price");
        $integralFeePrice = max(0, $order->integral_deduction_price - $totalIntegralPrice);

        $t = \Yii::$app->db->beginTransaction();
        try {

            $thirdRefundNo = !empty($meituanOrderDetail->thirdRefundNo) ? @json_decode($meituanOrderDetail->thirdRefundNo, true) : [];
            if(!isset($thirdRefundNo[$tradeRefundNo])){
                $thirdRefundNo[$tradeRefundNo] = date("ymdhis") . rand(1000, 9999);
            }

            $meituanOrderDetail->thirdRefundNo = json_encode($thirdRefundNo);

            if($meituanOrderDetail->refund_status != 1){

                //剩余可退款金额
                $remainRefundAmount = bcsub(floatval($meituanOrderDetail->tradeAmount), floatval($meituanOrderDetail->refund_money));
                if(floatval($refundPrice) > $remainRefundAmount){
                    throw new \Exception('最多可退款金额￥' .$remainRefundAmount);
                }

                /** @var OrderDetail $orderDetail */
                $orderDetail = OrderDetail::find()->where([
                    'order_id'  => $order->id,
                    'is_delete' => 0,
                    'is_refund' => 0
                ])->with(['order', 'userCards' => function ($query) {
                    /** @var Query $query */
                    $query->andWhere(['is_use' => 1]);
                }])->one();
                if(!$orderDetail){
                    throw new \Exception('订单详情记录不存在');
                }

                //部分退款
                if($refundPrice < $remainRefundAmount){
                    $newCopyData = $orderDetail->getAttributes();
                    unset($newCopyData['id']);
                    $newCopyData['integral_price'] = 0;
                    if($orderDetail->total_price >= $refundPrice){ //优先退现金余额
                        $orderDetail->total_price = bcsub(floatval($orderDetail->total_price), floatval($refundPrice));
                        $newCopyData['total_price'] = $refundPrice;
                    }else{ //现金余额不足，退红包
                        $newCopyData['total_price'] = $orderDetail->total_price;
                        $stillNeedAmount = bcsub(floatval($refundPrice), floatval($orderDetail->total_price));
                        $orderDetail->total_price = 0;

                        //计算需要退款的金豆
                        $needRefundIntegral = $integralPrice * $stillNeedAmount;

                        if(floatval($orderDetail->integral_price) >= $needRefundIntegral){
                            $orderDetail->integral_price = bcsub(floatval($orderDetail->integral_price), $needRefundIntegral);
                        }else{
                            $needRefundIntegral = $orderDetail->integral_price;
                            $orderDetail->integral_price = 0;
                        }

                        $newCopyData['integral_price'] = $needRefundIntegral;
                    }

                    if(!$orderDetail->save()){
                        throw new \Exception(json_encode($orderDetail->getErrors()));
                    }

                    $orderDetailCopy = new OrderDetail($newCopyData);
                    $orderDetailCopy->created_at = time();
                    $orderDetailCopy->updated_at = time();
                    if(!$orderDetailCopy->save()){
                        throw new \Exception(json_encode($orderDetailCopy->getErrors()));
                    }

                    $orderDetail = $orderDetailCopy;

                    $meituanOrderDetail->refund_status = 2;
                }else{ //全额退款
                    $meituanOrderDetail->refund_status = 1;

                    //退还红包服务费
                    if($integralFeePrice > 0){
                        $orderDetail->integral_price += $integralFeePrice;
                        if(!$orderDetail->save()){
                            throw new \Exception(json_encode($orderDetail->getErrors()));
                        }
                    }
                }

                $meituanOrderDetail->refund_money = bcadd(floatval($meituanOrderDetail->refund_money), floatval($refundPrice));

                if ($order->is_sale == Order::IS_SALE_YES) {
                    throw new \Exception('订单已过售后时间,无法申请售后');
                }

                if (!in_array($order->status, [Order::STATUS_WAIT_DELIVER,Order::STATUS_WAIT_RECEIVE,Order::STATUS_WAIT_COMMENT]))
                    throw new \Exception('该订单状态下,无法申请售后');

                $refundPrice = $orderDetail->total_price;
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

                if(empty($meituanOrderDetail->thirdRefundNo)){
                    $meituanOrderDetail->thirdRefundNo = $orderRefund->id;
                }

                $meituanOrderDetail->tradeRefundNo          = $tradeRefundNo;
                $meituanOrderDetail->serviceFeeRefundAmount = $serviceFeeRefundAmount;
                $meituanOrderDetail->refund_id              = $orderRefund->id;
                $meituanOrderDetail->refund_at              = time();
                if(!$meituanOrderDetail->save()){
                    throw new \Exception(json_encode($meituanOrderDetail->getErrors()));
                }
            }else{
                throw new \Exception("订单已退款");
            }

            $t->commit();

            return $orderRefund;
        }catch (\Exception $e){
            $t->rollBack();
            return json_encode(["message" => $e->getMessage(), "file" => $e->getFile(), "line" => $e->getLine()], JSON_UNESCAPED_UNICODE);
        }
    }
}