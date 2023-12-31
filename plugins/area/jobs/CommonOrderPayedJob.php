<?php

namespace app\plugins\area\jobs;

use app\models\CommonOrder;
use app\models\CommonOrderDetail;
use app\plugins\area\models\AreaSetting;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class CommonOrderPayedJob extends BaseObject implements JobInterface
{
    public $order_id;
    public $order_type;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        $common_order = CommonOrder::findOne(['order_type' => $this->order_type, 'order_id' => $this->order_id, 'is_pay' => 0]);
        if (!$common_order) {
            return;
        }
        $common_order->is_pay = 1;
        if (!$common_order->save()) {
            return;
        }
        $compute_type = AreaSetting::getValueByKey(AreaSetting::COMPUTE_TYPE, $common_order->mall_id);

        if ($compute_type == 1) {
            $common_order_detail_list = CommonOrderDetail::find()->where(['common_order_id' => $common_order->id, 'is_delete' => 0])->all();
            foreach ($common_order_detail_list as $order) {
                /**
                 * @var  CommonOrderDetail $order
                 */
                \Yii::$app->queue->delay(5)->push(new AreaLogJob([
                    'order' => $order,
                    'common_order_detail_id' => $order->id,
                    'type' => 3
                ]));
            }
            unset($order);
        }
    }
}