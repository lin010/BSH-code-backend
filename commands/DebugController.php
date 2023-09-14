<?php

namespace app\commands;

use app\models\User;
use app\models\UserRelationshipLink;
use app\plugins\mch\models\Mch;
use app\plugins\mch\models\MchCheckoutOrder;
use app\plugins\shopping_voucher\helpers\ShoppingVoucherHelper;
use app\plugins\shopping_voucher\models\ShoppingVoucherFromStore;
use app\plugins\shopping_voucher\models\ShoppingVoucherSendLog;

class DebugController extends BaseCommandController{

    public function actionIndex(){
        $query = MchCheckoutOrder::find()->alias("mco");
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
        }
    }
}