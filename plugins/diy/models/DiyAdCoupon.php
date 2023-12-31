<?php

namespace app\plugins\diy\models;

use app\models\BaseActiveRecord;

/**
 * This is the model class for table "{{%diy_ad_coupon}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property int $user_coupon_id
 * @property int $is_delete 删除
 * @property string $created_at
 * @property string $deleted_at
 */
class DiyAdCoupon extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin_diy_ad_coupon}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'user_coupon_id', 'created_at', 'deleted_at'], 'required'],
            [['mall_id', 'user_id', 'user_coupon_id', 'is_delete'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
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
            'user_id' => 'User ID',
            'user_coupon_id' => 'User Coupon ID',
            'is_delete' => '删除',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
