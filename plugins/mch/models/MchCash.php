<?php

namespace app\plugins\mch\models;

use app\models\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%mch_cash}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $mch_id 多商户ID
 * @property string $money 提现金额
 * @property string $order_no 订单号
 * @property int $status 提现状态：0=待处理，1=同意，2=拒绝
 * @property int transfer_status 打款状态: 0.未打款 | 1.已打款
 * @property int $type 提现类型 wx 微信| alipay 支付宝 | bank 银行卡 | balance 余额
 * @property string $type_data 不同提现类型，提交的数据
 * @property int $virtual_type 实际上打款方式
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property string $fact_price  实际到账
 * @property int $is_delete
 * @property string $content
 * @property Mch $mch
 */
class MchCash extends BaseActiveRecord
{
    //状态  拒绝
    const STATUS_TWO = 2;

    //提现状态  待转账
    const TRANSFER_STATUS_ZERO = 0;

    //提现状态 已转账
    const TRANSFER_STATUS_ONE = 1;

    //提现状态 拒绝转账
    const TRANSFER_STATUS_TWO = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin_mch_cash}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'mch_id', 'money', 'created_at', 'updated_at', 'deleted_at', 'type'], 'required'],
            [['mall_id', 'mch_id', 'status', 'virtual_type', 'is_delete', 'transfer_status', 'retry_count'], 'integer'],
            [['money', 'fact_price'], 'number'],
            [['type', 'content'], 'string'],
            [['created_at', 'updated_at', 'deleted_at', 'service_fee_rate', 'fact_price', 'type_data'], 'safe'],
            [['service_fee_rate'], 'number', 'min' => 0, 'max' => 100],
            [['order_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => 'Mall ID',
            'mch_id' => '多商户ID',
            'money' => '提现金额',
            'order_no' => '订单号',
            'service_fee_rate' => '服务费',
            'status' => '提现状态：0=待处理，1=已转账，2=已拒绝',
            'transfer_status' => '打款状态: 0.未打款 | 1.已打款',
            'type' => '提现类型 wx 微信| alipay 支付宝 | bank 银行卡 | balance 余额',
            'type_data' => '不同提现类型，提交的数据',
            'virtual_type' => '实际上打款方式',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
        ];
    }

    public function getMch()
    {
        return $this->hasOne(Mch::className(), ['id' => 'mch_id']);
    }

    /**
     * @param MchCash $mchCash
     */
    public function getType($mchCash)
    {

        switch ($mchCash->type) {
            case 'wx':
                $type = '微信';
                break;
            case 'alipay':
                $type = '支付宝';
                break;
            case 'bank':
                $type = '银行卡';
                break;
            case 'balance':
                $type = '余额';
                break;
            default:
                $type = '未知';
                break;
        }

        return $type;
    }
}
