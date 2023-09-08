<?php
namespace app\component\jobs;


use app\component\efps\Efps;
use app\core\payment\PaymentNotify;
use app\models\Mall;
use app\models\EfpsPaymentOrder;
use app\models\PaymentOrder;
use app\models\PaymentOrderUnion;
use yii\base\Component;
use yii\queue\JobInterface;
use app\logic\ExceptionLogLogic;
class BsPayQueryJob extends Component implements JobInterface{

    public $outTradeNo;

    public function execute($queue){
    	$log = new ExceptionLogLogic();
        if(empty($this->outTradeNo)){
            $efpsOrder = EfpsPaymentOrder::find()->where([
                "is_pay" => 0
            ])->andWhere([
                "AND",
                ["<", "do_query_count", 10],
                ["=", "payAPI", 'IF-BsWeChat-01'],
                [">", "transactionStartTime", date("YmdHis", time() - 600)]
            ])->orderBy("update_at ASC")->one();
            $log->info('汇付天下微信支付回调查询订单0', []);
        }else{
            $efpsOrder = EfpsPaymentOrder::find()->where([
                "is_pay"     => 0,
                "outTradeNo" => $this->outTradeNo
            ])->one();
            $log->info('汇付天下微信支付回调查询订单1', []);
        }
        $log->info('汇付天下微信支付回调查询订单', [$efpsOrder]);
        if(!$efpsOrder) {
        	$log->info('汇付天下微信支付回调查询不到订单', []);
            return;
        }
        $t = \Yii::$app->getDb()->beginTransaction();
        try {
        	$log->info('汇付天下微信支付回调进来了逻辑了', []);
        	$OrgReqDate = date('Ymd',strtotime($efpsOrder->transactionStartTime));
            $res = \Yii::$app->bs->isBsPayWeixinPay($efpsOrder->outTradeNo,$OrgReqDate);
            if($res['code'] == Efps::CODE_SUCCESS){
                $efpsOrder->is_pay = 1;
            }
            $efpsOrder->do_query_count += 1;
            $efpsOrder->update_at = time();
            if(!$efpsOrder->save()){
            	$log->info('汇付天下微信支付回调保存失败', []);
                throw new \Exception(json_encode($efpsOrder->getFirstErrors()));
            }

            if($efpsOrder->is_pay){ //支付成功
                $paymentOrderUnion = PaymentOrderUnion::findOne($efpsOrder->payment_order_union_id);
                if(!$paymentOrderUnion){
            	$log->info('汇付天下微信支付回调保存后订单不存在', []);
                    throw new \Exception('订单不存在: ' . $efpsOrder->payment_order_union_id);
                }

                if (!$paymentOrderUnion->is_pay) {
                    $mall = Mall::findOne($paymentOrderUnion->mall_id);
                    if (!$mall) {
                        throw new \Exception('未查询到id=' . $paymentOrderUnion->id . '的商城。 ');
                    }

                    \Yii::$app->setMall($mall);

                    $paymentOrders = PaymentOrder::findAll(['payment_order_union_id' => $paymentOrderUnion->id]);

                    if($efpsOrder->payAPI == "IF-BsQRcode-01"){ //支付宝
                        $paymentOrderUnion->pay_type = 11;
                    }else{
                        $paymentOrderUnion->pay_type = 10;
                    }
                    $paymentOrderUnion->is_pay = 1;

                    if (!$paymentOrderUnion->save()) {
                        throw new \Exception($paymentOrderUnion->getFirstErrors());
                    }

                    foreach ($paymentOrders as $paymentOrder) {
                        $Class = $paymentOrder->notify_class;
                        if (!class_exists($Class)) {
                            continue;
                        }
                        if($efpsOrder->payAPI == "IF-BsQRcode-01"){ //支付宝
                            $paymentOrder->pay_type = 11;
                        }else{
                            $paymentOrder->pay_type = 10;
                        }
                        $paymentOrder->is_pay = 1;
                        if (!$paymentOrder->save()) {
                            throw new \Exception($paymentOrder->getFirstErrors());
                        }
                        /** @var PaymentNotify $notify */
                        $notify = new $Class();
                        try {
                            $po = new \app\core\payment\PaymentOrder([
                                'orderNo'     => $paymentOrder->order_no,
                                'amount'      => (float)$paymentOrder->amount,
                                'title'       => $paymentOrder->title,
                                'notifyClass' => $paymentOrder->notify_class,
                                'payType'     => $paymentOrder->pay_type == 10 ?
                                                    \app\core\payment\PaymentOrder::PAY_TYPE_WECHAT :
                                                    \app\core\payment\PaymentOrder::PAY_TYPE_ALIPAY,
                                'user_id'     => $paymentOrderUnion->user_id
                            ]);
                            $notify->notify($po);
                            echo "RECV_ORD_ID_" . $this->outTradeNo;
                        } catch (\Exception $e) {
                            \Yii::error($e);
                        }
                    }
                }
            }
            $t->commit();
        }catch (\Exception $e) {
            $t->rollBack();
            \Yii::error("查询出现异常 File=".$e->getFile().";Line:".$e->getLine().";message:".$e->getMessage());
        }
    }

}