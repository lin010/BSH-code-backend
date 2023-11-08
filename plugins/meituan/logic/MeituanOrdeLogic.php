<?php


namespace app\plugins\meituan\logic;


use app\models\Goods;
use app\models\Order;
use app\plugins\meituan\helpers\Aes;
use app\plugins\meituan\helpers\Req;
use app\plugins\meituan\models\MeituanOrdeDetail;
use app\plugins\meituan\models\MeituanSetting;

class MeituanOrdeLogic
{
    /**
     * 支付成功通知
     * @param MeituanOrdeDetail $meituanOrderDetail
     * @param Order $order
     * @param Goods $goods
     */
    public static function doPay(MeituanOrdeDetail $meituanOrderDetail, Order $order, Goods $goods){
        try {
            $setting = MeituanSetting::getSettings();

            $result = Req::post($meituanOrderDetail->notifyUrl, [
                "accessKey" => $setting['accessKey'],
                "content" => Aes::encrypt([
                    "ts" => time() * 1000,
                    "entId" => $setting['entId'],
                    "tradeNo" => $meituanOrderDetail->tradeNo,
                    "thirdTradeNo" => (string)$meituanOrderDetail->id,
                    "tradeAmount" => $meituanOrderDetail->tradeAmount
                ], $setting['secretKey'])
            ]);

            $meituanOrderDetail->notifyStatus = isset($result['status']) && $result['status'] == 0 ? 1 : 0;
            $meituanOrderDetail->notifyFeeback = json_encode(is_array($result) ? $result : []);
            $meituanOrderDetail->payStatus = 1;
            $meituanOrderDetail->updated_at = time();

            if(!$meituanOrderDetail->save()){
                throw new \Exception(json_encode($meituanOrderDetail->getAttributes()));
            }

        }catch (\Exception $e){

        }
    }
}