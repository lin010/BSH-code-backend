<?php
namespace app\component\jobs;


use app\component\bspay\Bs;
use app\core\payment\PaymentNotify;
use app\models\Mall;
use yii\base\Component;
use yii\queue\JobInterface;
use app\logic\ExceptionLogLogic;
use app\models\PaymentRefund;
class BsRefundQueryJob extends Component implements JobInterface{

    public $outTradeNo;

    public function execute($queue){
    	$log = new ExceptionLogLogic();
        $PaymentRefund = PaymentRefund::find()->where([
            "order_no" => $this->outTradeNo
        ])->one();
        $log->info('汇付天下退款回调查询订单', [$this->outTradeNo,var_export($PaymentRefund,true)]);
        if(!$PaymentRefund) {
        	$log->info('汇付天下退款回调查询不到待支付订单', []);
            return;
        }
        if($PaymentRefund->is_pay == PaymentRefund::YES) {
        	$log->info('该订单已经退款成功', []);
            return;
        }
        $t = \Yii::$app->getDb()->beginTransaction();
        try {
            $batch_no = $PaymentRefund->bspay_org_hf_seq_id;//请求时候返回的全局流水后
            $reqdate = $PaymentRefund->bspay_req_time;
            $log->info('汇付天下退款回调进来了逻辑了', [$batch_no,$reqdate,Bs::CODE_SUCCESS,$PaymentRefund->fail_retry_count]);
            $res = \Yii::$app->bs->refund_query($reqdate, $batch_no,5);
            $log->info('提现回调查询结果', [$res,$res['code']]);
            if($res['code'] == Bs::CODE_SUCCESS){
	            $paymentRefund->is_pay   = PaymentRefund::YES;
	            $paymentRefund->pay_type = PaymentRefund::PAY_TYPE_WECHAT;
	            if(!$PaymentRefund->save()){
	            	$log->info('汇付天下退款回调保存失败', []);
	                throw new \Exception(json_encode($PaymentRefund->getFirstErrors()));
	            }
            }
            $t->commit();
        }catch (\Exception $e) {
            $t->rollBack();
            \Yii::error("查询出现异常 File=".$e->getFile().";Line:".$e->getLine().";message:".$e->getMessage());
        }
    }

}