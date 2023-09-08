<?php
namespace app\forms\bs;

use app\component\bspay\Bs;
use app\core\payment\PaymentException;
use app\forms\common\refund\BaseRefund;
use app\models\EfpsPaymentOrder;
use app\models\PaymentRefund;
use yii\db\Exception;
use app\models\Order;
use app\models\PaymentOrder;
class BsRefund extends BaseRefund{

    public function refund($paymentRefund, $paymentOrderUnion){

        $t = \Yii::$app->db->beginTransaction();
        try {

            $efpsPaymentOrder = EfpsPaymentOrder::findOne([
                "payment_order_union_id" => $paymentOrderUnion->id
            ]);
            if(!$efpsPaymentOrder){
                throw new \Exception("交易订单不存在");
            }
            $paymentOrder = PaymentOrder::findOne(['payment_order_union_id' => $paymentOrderUnion->id,'is_pay' => 1]);
            if(!$paymentOrder){
                throw new \Exception("交易订单不存在");
            }

            $order = Order::findOne([
                'order_no' => $paymentOrder->order_no,
            ]);
            if (!$order) {
                throw new \Exception("交易订单不存在");
            }            

            $result = \Yii::$app->bs->refund([
                "outRefundNo"  => $paymentRefund->order_no,
                "outTradeNo"   => $efpsPaymentOrder->outTradeNo,
                "refundAmount" => $paymentRefund->amount,
                "amount"       => $efpsPaymentOrder->payAmount,
                "orderInfo"    => json_decode($efpsPaymentOrder->orderInfo, true),
                "paydate"    => date('Ymd',$order->pay_at),
                'notifyUrl' => \Yii::$app->getRequest()->getHostInfo() . "/web/pay-notify/bs-refund.php",
            ]);

            if($result["code"] == Bs::CODE_FALI && $result["json_str"] == 0){
                throw new \Exception($result['data']["returnMsg"]);
            }
            $paymentRefund->bspay_req_time   = date("Ymd");
            $paymentRefund->bspay_org_hf_seq_id   = $result['data']['org_hf_seq_id'];
            if($result["code"] == Bs::CODE_SUCCESS){
                $paymentRefund->is_pay   = PaymentRefund::YES;
                $paymentRefund->pay_type = PaymentRefund::PAY_TYPE_WECHAT;
            }
            if (!$paymentRefund->save()) {
                throw new Exception($this->responseErrorMsg($paymentRefund));
            }

            $t->commit();
            return true;
        }catch (\Exception $e){
            $t->rollBack();
            throw new PaymentException($e->getMessage());
        }
    }
}