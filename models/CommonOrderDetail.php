<?php

namespace app\models;

use app\events\CommonOrderDetailEvent;
use app\handlers\CommonOrderDetailHandler;
use Yii;

/**
 * This is the model class for table "{{%common_order_detail}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $order_id
 * @property int $goods_id
 * @property float $price 该笔支付金额
 * @property int $num
 * @property int $user_id
 * @property int $is_delete
 * @property int $created_at
 * @property int $updated_at
 * @property int $deleted_at
 * @property int $common_order_id 公共订单ID
 * @property int $goods_type 商品类型
 * @property int $attr_id 商品规格id
 * @property int $status 状态
 * @property int $order_no 订单编号
 * @property int $order_detail_id 订单详情id
 * @property float $profit 利润
 *
 * @property CommonOrder $commonOrder
 * @property Order $order
 * @property OrderDetail $orderDetail
 */
class CommonOrderDetail extends BaseActiveRecord
{
    const TYPE_MALL_GOODS = 'mall';//商城
    const TYPE_MCH_GOODS = 'mch_goods';

    /** @var int 状态0未生效 1待结算 2已完成 3已失效 4退款 */
    const STATUS_NORMAL = 0;
    const STATUS_INVALID = -1;
    const STATUS_COMPLETE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_order_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'order_id', 'goods_id', 'user_id', 'common_order_id'], 'required'],
            [['mall_id', 'order_id', 'goods_id', 'num', 'user_id', 'is_delete', 'created_at', 'updated_at', 'deleted_at', 'common_order_id', 'attr_id', 'status', 'order_detail_id'], 'integer'],
            [['order_no', 'goods_type'], 'string'],
            [['price', 'profit'], 'number'],
            [['process_priority_level'], 'safe']
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
            'order_id' => 'Order ID',
            'goods_id' => 'Goods ID',
            'price' => '该笔支付金额',
            'num' => 'Num',
            'user_id' => 'User ID',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'common_order_id' => '公共订单ID',
            'goods_type' => '商品类型',
            'order_detail_id' => '订单详情ID',
            'attr_id' => '商品规格ID',
            'order_no' => '订单号',
            'status' => '状态',
            'profit'=>'利润'
        ];
    }


    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            //这里会触发两次，暂时注释掉
            $event = new CommonOrderDetailEvent();
            $event->common_order_detail_id = $this->id;
            Yii::$app->trigger(CommonOrderDetailHandler::COMMON_ORDER_DETAIL_CREATED, $event);
        }
        return parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }


    /**
     * @Author: 广东七件事 ganxiaohao
     * @Date: 2020-07-02
     * @Time: 17:10
     * @Note: 更新公共订单详情
     * @param $updateData
     * @param $columns
     * @return int
     */
    public static function edit($updateData, $columns)
    {
        $result = self::updateAll($updateData, $columns);
        if ($result) {
            if (isset($updateData['status']) && $updateData['status'] != 0) {
                $common_order_detail_list = CommonOrderDetail::find()->where($columns)->andWhere(['is_delete' => 0])->all();
                foreach ($common_order_detail_list as $item) {
                    /**
                     * @var CommonOrderDetail $item
                     */
                    $event = new CommonOrderDetailEvent();
                    $event->common_order_detail_id = $item->id;
                    Yii::$app->trigger(CommonOrderDetailHandler::COMMON_ORDER_DETAIL_STATUS_CHANGED, $event);
                }
                unset($item);
            }
        }
        return $result;
    }


    public function getCommonOrder()
    {
        return $this->hasOne(CommonOrder::className(), ['id' => 'mch_id']);
    }

    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    public function getGoodsAttr()
    {
        return $this->hasOne(GoodsAttr::className(), ['id' => 'attr_id']);
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ["id" => "order_id"]);
    }

    public function getOrderDetail()
    {
        return $this->hasOne(OrderDetail::className(), ['id' => 'order_detail_id']);
    }


    /**
     * 列表
     * @param $wheres
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getList($wheres)
    {
        $list = self::find()->where($wheres)->with(["common_order" => function ($query) {
            $query->where(['status' => CommonOrder::STATUS_IS_PAY]);
        }])->all();
        return $list;
    }
}