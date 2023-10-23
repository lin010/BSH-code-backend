<?php


namespace app\plugins\addcredit\handlers;


use app\handlers\BaseHandler;
use app\models\Order;
use app\plugins\addcredit\forms\api\order\PhoneOrderSubmitForm;
use app\plugins\addcredit\models\AddcreditOrder;
use app\plugins\addcredit\models\AddcreditPlateforms;

class AddcreditHandler extends BaseHandler
{
    public function register()
    {
        try {
            \Yii::$app->on(Order::EVENT_PAYED, function ($event) {
                $plateforms = AddcreditPlateforms::find()->where(["is_enabled" => 1])->orderBy("id DESC")->one();
                if($plateforms){
                    $products = $products = @json_decode($plateforms->product_json_data, true);
                    $details = $event->order->detail;
                    foreach($details as $detail){
                        foreach($products as $product){
                            if($product['goods_id'] == $detail->goods_id){
                                //生成充值订单
                                $orderNo = "HF" . $event->order->order_no;
                                if(!AddcreditOrder::findOne(['order_no' => $orderNo])){
                                    $addcreditOrder = new AddcreditOrder([
                                        "mall_id"       => $plateforms->mall_id,
                                        "plateform_id"  => $plateforms->id,
                                        "order_id"      => $event->order->id,
                                        "user_id"       => $event->order->user_id,
                                        "mobile"        => $event->order->remark,
                                        "order_no"      => $orderNo,
                                        "order_price"   => $product['price'],
                                        "created_at"    => time(),
                                        "updated_at"    => time(),
                                        "order_status"  => 'processing',
                                        "pay_status"    => 'paid',
                                        "pay_at"        => time(),
                                        "product_id"    => $product['product_id'],
                                        "recharge_type" => $product['type']
                                    ]);
                                    $addcreditOrder->save();
                                }
                            }
                        }
                    }
                }

            });
        }catch (\Exception $e){

        }
    }
}