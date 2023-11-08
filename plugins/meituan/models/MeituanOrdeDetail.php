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
            [['mall_id', 'goods_id', 'payStatus', 'created_at', 'updated_at', 'ts', 'entId', 'traceId', 'method', 'tradeNo',
              'sqtBizOrderId', 'tradeAmount', 'serviceFeeAmount', 'goodsName', 'tradeExpiringTime', 'notifyUrl',
              'returnUrl', 'firstBusinessType', 'secondBusinessType', 'staffInfo', 'extInfoMap'], 'required'],
            [['is_delete', 'notifyStatus', 'notifyFeeback'], 'safe']
        ];
    }
}