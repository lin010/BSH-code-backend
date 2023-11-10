<?php
namespace app\plugins\meituan\models;

use app\models\BaseActiveRecord;

class MeituanOrdeDetail extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin_meituan_order_detail}}';
    }

    public function rules()
    {
        return [
            [['mall_id',  'goods_id', 'payStatus', 'created_at', 'updated_at', 'ts', 'entId', 'traceId', 'method', 'tradeNo',
              'sqtBizOrderId', 'tradeAmount', 'goodsName', 'tradeExpiringTime', 'notifyUrl',
              'returnUrl',  'staffInfo', 'extInfoMap'], 'required'],
            [['is_delete', 'firstBusinessType', 'secondBusinessType', 'notifyStatus', 'notifyFeeback', 'order_id', 'tradeRefundNo',
              'serviceFeeRefundAmount', 'refund_status', 'refund_money', 'thirdRefundNo', 'refund_id', 'refund_at', 'serviceFeeAmount'], 'safe']
        ];
    }
}