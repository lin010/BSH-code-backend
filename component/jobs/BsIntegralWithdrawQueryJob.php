<?php
namespace app\component\jobs;


use app\component\bspay\Bs;
use app\core\payment\PaymentNotify;
use app\models\Mall;
use app\models\BsTransferOrder;;
use yii\base\Component;
use yii\queue\JobInterface;
use app\logic\ExceptionLogLogic;
use app\models\IntegralCash;
class BsIntegralWithdrawQueryJob extends Component implements JobInterface{

    public $outTradeNo;

    public function execute($queue){
    	$log = new ExceptionLogLogic();
        $bsTransferOrder = BsTransferOrder::find()->where([
            "settlement_org_req_seq_id" => $this->outTradeNo
        ])->one();
        $log->info('汇付天下金豆提现回调查询订单', [$this->outTradeNo,var_export($bsTransferOrder,true)]);
        if(!$bsTransferOrder) {
        	$log->info('汇付天下金豆提现回调查询不到待支付订单', []);
            return;
        }
        if($bsTransferOrder->status != 3) {
        	$log->info('该订单非待支付状态', []);
            return;
        }
        $t = \Yii::$app->getDb()->beginTransaction();
        try {
            $batch_no = $bsTransferOrder->settlement_org_req_seq_id;
            $reqdate = $bsTransferOrder->settlement_org_req_date;
            $log->info('汇付天下金豆提现回调进来了逻辑了', [$batch_no,$reqdate,Bs::CODE_SUCCESS,$bsTransferOrder->fail_retry_count]);
            $res = \Yii::$app->bs->settlement_query($reqdate, $batch_no,$bsTransferOrder->huifu_id,5);
            $log->info('提现回调查询结果', [$res,$res['code']]);
            if($res['code'] == Bs::CODE_SUCCESS){
                $bsTransferOrder->status = 4;
            }
            $bsTransferOrder->fail_retry_count += 1;
            $bsTransferOrder->updated_at = time();
            if(!$bsTransferOrder->save()){
            	$log->info('汇付天下金豆提现回调保存失败', []);
                throw new \Exception(json_encode($bsTransferOrder->getFirstErrors()));
            }
            if($bsTransferOrder->status == 4){ //支付成功
            	$cash = IntegralCash::findOne(['order_no' => $bsTransferOrder->outTradeNo, 'is_delete' => 0,'is_transmitting'=>1]);
                $log->info('查询cash', [var_export($bsTransferOrder,true)]);
				if (!$cash) {
                    $log->info('提现记录不存在', []);
	            }else{
    	            if($cash->type == "bank"){
                        $log->info('bank', []);
    	                $cash->is_transmitting = 0;
    		            $cash->save();
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