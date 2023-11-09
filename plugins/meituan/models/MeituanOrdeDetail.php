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
              'sqtBizOrderId', 'tradeAmount', 'serviceFeeAmount', 'goodsName', 'tradeExpiringTime', 'notifyUrl',
              'returnUrl', 'firstBusinessType', 'secondBusinessType', 'staffInfo', 'extInfoMap'], 'required'],
            [['is_delete', 'notifyStatus', 'notifyFeeback', 'order_id', 'tradeRefundNo', 'serviceFeeRefundAmount', 'refund_status', 'refund_money', 'refund_id', 'refund_at'], 'safe']
        ];
    }
}