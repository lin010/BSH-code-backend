<?php
namespace app\controllers\api;
use app\component\jobs\BsPayQueryJob;
use app\component\jobs\BsWithdrawQueryJob;
use app\component\jobs\BsMchWithdrawQueryJob;
use app\component\jobs\BsRefundQueryJob;
use app\component\jobs\BsIntegralWithdrawQueryJob;
use yii\web\Controller;
use app\logic\ExceptionLogLogic;
class BsPayNotifyController extends Controller{

    public function init(){
        parent::init();
        $this->enableCsrfValidation = false;
    }


    /**
     * 支付通知
     * @return array
     */
    public function actionAliJsApiPayment(){
    	$log = new ExceptionLogLogic();
        $get = array();
		foreach($_POST as $key=>$val) {//动态遍历获取所有收到的参数,此步非常关键,因为收银宝以后可能会加字段,动态获取可以兼容由于收银宝加字段而引起的签名异常
			$get[$key] = $val;
		}
		$log->info('汇付天下微信支付回调', $get); 
		$notifyData = $get['resp_data'] = json_decode($get['resp_data'],true);
        $outTradeNo = !empty($notifyData['req_seq_id']) ? $notifyData['req_seq_id'] : null;
        $log->info('汇付天下微信支付回调:outTradeNo', [$outTradeNo]);
        if(!empty($outTradeNo)){
            $job = new BsPayQueryJob([
                "outTradeNo" => $outTradeNo
            ]);
            $job->execute(null);
        }
    }
    /**
     * 佣金提现通知
     * @return array
     */
    public function actionWithdraw(){
        $log = new ExceptionLogLogic();
        $get = array();
        foreach($_POST as $key=>$val) {//动态遍历获取所有收到的参数,此步非常关键,因为收银宝以后可能会加字段,动态获取可以兼容由于收银宝加字段而引起的签名异常
            $get[$key] = $val;
        }
        $log->info('汇付天下佣金提现回调', $get); 
        $notifyData = $get['resp_data'] = json_decode($get['resp_data'],true);
        $outTradeNo = !empty($notifyData['req_seq_id']) ? $notifyData['req_seq_id'] : null;
        $log->info('汇付天下佣金提现回调:outTradeNo', [$outTradeNo]);
        if(!empty($outTradeNo)){
            $job = new BsWithdrawQueryJob([
                "outTradeNo" => $outTradeNo
            ]);
            $job->execute(null);
        }
    }
    /**
     * 金豆提现通知
     * @return array
     */
    public function actionWithdrawIntegral(){
        $log = new ExceptionLogLogic();
        $get = array();
        foreach($_POST as $key=>$val) {//动态遍历获取所有收到的参数,此步非常关键,因为收银宝以后可能会加字段,动态获取可以兼容由于收银宝加字段而引起的签名异常
            $get[$key] = $val;
        }
        $log->info('汇付天下金豆提现回调', $get); 
        $notifyData = $get['resp_data'] = json_decode($get['resp_data'],true);
        $outTradeNo = !empty($notifyData['req_seq_id']) ? $notifyData['req_seq_id'] : null;
        $log->info('汇付天下金豆提现回调:outTradeNo', [$outTradeNo]);
        if(!empty($outTradeNo)){
            $job = new BsIntegralWithdrawQueryJob([
                "outTradeNo" => $outTradeNo
            ]);
            $job->execute(null);
        }
    }
    /**
     * 商户提现通知
     * @return array
     */
    public function actionWithdrawMch(){
        $log = new ExceptionLogLogic();
        $get = array();
        foreach($_POST as $key=>$val) {//动态遍历获取所有收到的参数,此步非常关键,因为收银宝以后可能会加字段,动态获取可以兼容由于收银宝加字段而引起的签名异常
            $get[$key] = $val;
        }
        $log->info('汇付天下商户提现回调', $get); 
        $notifyData = $get['resp_data'] = json_decode($get['resp_data'],true);
        $outTradeNo = !empty($notifyData['req_seq_id']) ? $notifyData['req_seq_id'] : null;
        $log->info('汇付天下商户提现回调:outTradeNo', [$outTradeNo]);
        if(!empty($outTradeNo)){
            $job = new BsMchWithdrawQueryJob([
                "outTradeNo" => $outTradeNo
            ]);
            $job->execute(null);
        }
    }
    /**
     * 退款通知
     * @return array
     */
    public function actionRefund(){
        $log = new ExceptionLogLogic();
        $get = array();
        foreach($_POST as $key=>$val) {//动态遍历获取所有收到的参数,此步非常关键,因为收银宝以后可能会加字段,动态获取可以兼容由于收银宝加字段而引起的签名异常
            $get[$key] = $val;
        }
        $log->info('汇付天下退款回调', $get); 
        $notifyData = $get['resp_data'] = json_decode($get['resp_data'],true);
        $outTradeNo = !empty($notifyData['req_seq_id']) ? $notifyData['req_seq_id'] : null;
        $log->info('汇付天下退款回调:outTradeNo', [$outTradeNo]);
        if(!empty($outTradeNo)){
            $job = new BsRefundQueryJob([
                "outTradeNo" => $outTradeNo
            ]);
            $job->execute(null);
        }
    }    

}