<?php

namespace app\commands;

use app\commands\telephone_order_task\DoProcessingAction;
use app\component\jobs\BsPayQueryJob;
use app\core\ApiCode;
use app\core\payment\PaymentOrder;
use app\logic\AppConfigLogic;
use app\models\BsTransferOrder;
use app\models\Mall;
use app\models\User;
use app\models\UserRelationshipLink;
use app\notify_class\goods\OrderPayNotify;
use app\plugins\addcredit\models\AddcreditOrder;
use app\plugins\addcredit\models\AddcreditPlateforms;
use app\plugins\mch\models\Mch;
use app\plugins\mch\models\MchCheckoutOrder;
use app\plugins\meituan\logic\MeituanOrderRefundLogic;
use app\plugins\meituan\models\MeituanOrdeDetail;
use app\plugins\shopping_voucher\helpers\ShoppingVoucherHelper;
use app\plugins\shopping_voucher\models\ShoppingVoucherFromStore;
use app\plugins\shopping_voucher\models\ShoppingVoucherSendLog;
use app\plugins\taobao\models\TaobaoAccount;
use lin010\taolijin\Ali;

class DebugController extends BaseCommandController{

    public function actionIndex(){

        (new BsPayQueryJob([
            "outTradeNo" => "2023112514385941774"
        ]))->execute(null);

        /*$addcreditOrder = AddcreditOrder::findOne(1);
        $plateform = AddcreditPlateforms::findOne($addcreditOrder->plateform_id);
        $platClass = new \app\plugins\addcredit\plateform\sdk\liandong\PlateForm();
        $platClass->submit($addcreditOrder, $plateform);
        exit;*/

        //$meituanOrderDetail = MeituanOrdeDetail::findOne(100042);
        //MeituanOrderRefundLogic::doRefund($meituanOrderDetail, "1723268063257903159", 0, 0);


        //$addcreditOrder = AddcreditOrder::findOne(812);
        /*$plateform = AddcreditPlateforms::findOne($addcreditOrder->plateform_id);

        $platClass = new \app\plugins\addcredit\plateform\sdk\dayuanren\PlateForm();
        $res = $platClass->query2($addcreditOrder, $plateform);
        print_r($res);
        exit;*/


        (new DoProcessingAction(null, null, null))->debug();
        exit;

        /*\Yii::$app->mall = Mall::findOne(5);

        $account = TaobaoAccount::findOne(1);
        $ali = new Ali($account->app_key, $account->app_secret);
        $ali->publisher->save();
        exit;


        $transferOrder = BsTransferOrder::findOne(["outTradeNo" => "TX2023092010340233749"]);
        $payconfig = AppConfigLogic::getPaymentConfig(5);
        $user = User::findOne(235814);

        $batch_no = $transferOrder->settlement_org_req_seq_id;
        $reqdate = $transferOrder->settlement_org_req_date;
        $res = \Yii::$app->bs->settlement_query($reqdate, $batch_no, $transferOrder->huifu_id);
        print_r($res);
        exit;

        $isTransmitting = 1;
        $batch_no_money = $transferOrder->amount;
        $batch_no = 'D' . date('YmdHis') . 'CW' . $transferOrder->id . 'MONEY' . $batch_no_money;
        $reqdate = date("Ymd");

        $settlement_enchashment_data = array('money' => $batch_no_money,'batch_no'=>$batch_no,'date'=>$reqdate,'huifu_id'=>$user->huifu_id,'huifu_bank_token_no'=> "10034357702",'notifyUrl'=>$transferOrder->notifyUrl);
        $transferOrder->acctpayment_request_text = json_encode($settlement_enchashment_data);
        $transferOrder->settlement_org_req_seq_id = $batch_no;
        $transferOrder->settlement_org_req_date = $reqdate;
        $res = \Yii::$app->bs->settlement_enchashment($settlement_enchashment_data, "用户金豆提现");
        $transferOrder->acctpayment_resonse_text = json_encode($res['data']);
        if($res['code'] != ApiCode::CODE_SUCCESS){
            $res['code'] = ApiCode::CODE_SUCCESS;
            $res['msg']  = $res['msg'] == '交易不存在' ? '申请取现已提交,请等待汇付打款' : '申请取现失败,请重试; 失败原因:'.$res['msg'];
        }else{
            $transferOrder->status = 3;
        }

        $transferOrder->updated_at = time();
        $transferOrder->save();

        $res['is_transmitting'] = $isTransmitting;

        print_r($res);
        ext;*/
        /*$query = MchCheckoutOrder::find()->alias("mco");
        $query->innerJoin(["m" => Mch::tableName()], "m.id=mco.mch_id AND m.is_delete=0 AND m.review_status=1");
        $query->innerJoin(["svfs" => ShoppingVoucherFromStore::tableName()], "svfs.store_id=mco.store_id AND svfs.is_delete=0");
        $query->leftJoin(["svsl" => ShoppingVoucherSendLog::tableName()], "svsl.source_id=mco.id AND svsl.source_type='from_mch_checkout_order'");

        $query->andWhere([
            "AND",
            //"svsl.id IS NULL",
            "mco.created_at>svfs.start_at",
            "mco.pay_price > 0",
            ["mco.id" => 9028],
            ["mco.is_pay" => 1],
            ["mco.is_delete" => 0]
        ]);
        $query->orderBy("mco.updated_at ASC");

        $selects = ["mco.id", "mco.mall_id", "mco.pay_user_id", "mco.pay_price", "mco.mch_id", "mco.store_id",
            "svfs.give_type", "svfs.give_value", "m.transfer_rate"];

        $checkOrders = $query->select($selects)->asArray()->limit(10)->all();
        foreach($checkOrders as $checkOrder){
            //print_r($checkOrder);exit;
            $giveValue = ShoppingVoucherHelper::calculateMchRateByTransferRate($checkOrder['transfer_rate']);
            echo $giveValue;
            exit;
        }*/
    }
}