<?php
namespace app\plugins\meituan\controllers\api;

use app\controllers\api\ApiController;
use app\controllers\api\filters\LoginFilter;
use app\core\ApiCode;
use app\models\Cart;
use app\models\Goods;
use app\models\Order;
use app\plugins\meituan\helpers\Aes;
use app\plugins\meituan\helpers\MeituanOrderGoods;
use app\plugins\meituan\models\MeituanOrdeDetail;
use app\plugins\meituan\models\MeituanSetting;

class IndexController extends ApiController
{

    public function behaviors(){
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class
            ]
        ]);
    }

    /**
     * 检查美团订单状态
     * @return \yii\web\Response
     */
    public function actionCheckStatus(){
        try {

            $order = Order::findOne(["order_no" => $this->requestData['orderNo']]);
            if(!$order){
                throw new \Exception("系统异常！平台订单记录不存在");
            }

            $detail = $order->detail;
            $goods = $detail[0]->goods;

            $meituanOrderDetail = MeituanOrdeDetail::findOne(["goods_id" => $goods->id]);
            if(!$meituanOrderDetail){
                throw new \Exception("系统异常！美团订单记录不存在");
            }

            return $this->asJson(["code" => ApiCode::CODE_SUCCESS, "detail" => $meituanOrderDetail->getAttributes()]);
        }catch (\Exception $e) {
            return $this->asJson(["code" => ApiCode::CODE_FAIL, "msg" => $e->getMessage()]);
        }
    }

    /**
     * 下单接口
     */
    public function actionCreate(){
        try {

            $setting = MeituanSetting::getSettings();

            $accessKey = $setting['accessKey'];
            $secretKey = $setting['secretKey'];
            $entId     = $setting['entId'];

            $contents = Aes::decrypt($this->requestData['content'], $secretKey);
            if(empty($contents)){
               throw new \Exception("参数错误", 401);
            }

            //判断关单时间
            $tradeExpiringTime = $contents['tradeExpiringTime'];
            if((time() - 60) >= strtotime($tradeExpiringTime)){
                throw new \Exception("订单已关闭", 410);
            }

            $meituanOrdeDetail = MeituanOrdeDetail::findOne(["mall_id" => \Yii::$app->mall->id, "sqtBizOrderId" => $contents['sqtBizOrderId']]);
            if($meituanOrdeDetail){
                if($meituanOrdeDetail->is_delete){
                    throw new \Exception("参数错误", 401);
                }
                if($meituanOrdeDetail->payStatus){
                    throw new \Exception("订单已支付", 412);
                }

                $goods = Goods::findOne($meituanOrdeDetail->goods_id);
            }else{
                $meituanOrdeDetail = new MeituanOrdeDetail([
                    "mall_id"       => \Yii::$app->mall->id,
                    "created_at"    => time(),
                    "is_delete"     => 0,
                    "sqtBizOrderId" => $contents['sqtBizOrderId']
                ]);

                //生成美团订单商品
                $goods = MeituanOrderGoods::create(\Yii::$app->mall->id, floatval($contents['tradeAmount']), $contents['goodsName']);
           }

           if(!$goods){
                throw new \Exception("订单商品创建失败", 500);
           }


           $staffInfo = $contents['staffInfo'];
           $userId = $staffInfo['staffNum'];

           //创建购物车记录
           $attr = $goods->attr;
           Cart::updateAll(["is_delete" => 1, "deleted_at" => time()], ["mall_id" => \Yii::$app->mall->id, "user_id" => $userId]);
           $cart = new Cart([
               "mall_id"       => $goods->mall_id,
               "user_id"       => $userId,
               "goods_id"      => $goods->id,
               "attr_id"       => $attr[0]->id,
               "num"           => 1,
               "sign"          => "meituan",
               "created_at"    => time(),
               "updated_at"    => time(),
               "mch_baopin_id" => 0,
               "deleted_at"    => 0,
               "is_delete"     => 0,
               "mch_id"        => 0,
               "attr_info"     => json_encode($attr[0]->getAttributes()),
               "buy_now"       => 1
           ]);
           if(!$cart->save()){
               throw new \Exception("购物车记录创建失败", 500);
           }

           //美团跳转收银台地址
           $thirdPayUrl = "https://www.mingyuanriji.cn/h5/#/pages/order/submit?sign=meituan&nav_id=&mch_id=0&user_address_id=0&use_score=0&use_integral=0&list=" . $cart->id;

           $meituanOrdeDetail->updated_at         = time();
           $meituanOrdeDetail->payStatus          = 0;
           $meituanOrdeDetail->goods_id          = $goods->id;
           $meituanOrdeDetail->ts                 = $contents['ts'];
           $meituanOrdeDetail->entId              = $contents['entId'];
           $meituanOrdeDetail->traceId            = $contents['traceId'];
           $meituanOrdeDetail->method             = $contents['method'];
           $meituanOrdeDetail->tradeNo            = $contents['tradeNo'];
           $meituanOrdeDetail->tradeAmount        = $contents['tradeAmount'];
           $meituanOrdeDetail->serviceFeeAmount   = $contents['serviceFeeAmount'];
           $meituanOrdeDetail->goodsName          = $contents['goodsName'];
           $meituanOrdeDetail->tradeExpiringTime  = $contents['tradeExpiringTime'];
           $meituanOrdeDetail->notifyUrl          = $contents['notifyUrl'];
           $meituanOrdeDetail->returnUrl          = $contents['returnUrl'];
           $meituanOrdeDetail->firstBusinessType  = $contents['firstBusinessType'];
           $meituanOrdeDetail->secondBusinessType = $contents['secondBusinessType'];
           $meituanOrdeDetail->staffInfo          = json_encode($staffInfo, JSON_UNESCAPED_UNICODE);
           $meituanOrdeDetail->extInfoMap         = isset($contents['extInfoMap']) && !empty($contents['extInfoMap']) ? json_encode($contents['extInfoMap'], JSON_UNESCAPED_UNICODE) : "";

           if(!$meituanOrdeDetail->save()){
               throw new \Exception("订单记录保存失败", 500);
           }

           $data = Aes::encrypt(["thirdTradeNo" => $contents['tradeNo'], "thirdPayUrl" => $thirdPayUrl], $secretKey);

           return $this->asJson([
               "traceId" => $contents['traceId'],
               "status"  => 0,
               "msg"     => "成功",
               "thirdPayUrl" => $thirdPayUrl,
               "data"    => $data
           ], false);

            /*$encryptContent = Aes::encrypt([
                "ts" => time() * 1000,
                "traceId" => "9042536864303509621",
                "entId"  => $entId,
                "method" => "trade.third.pay",
                "tradeNo" => "393033370136691",
                "sqtBizOrderId" => "345905477526403",
                "tradeAmount" => "1.00",
                "serviceFeeAmount" => "0.06",
                "goodsName" => "MTDP-香丰阁(望京店)",
                "tradeExpiringTime" => "2023-11-13 00:00:00",
                "notifyUrl" => "https://waimai-openapi.apigw.test.meituan.com/api/sqt/open/standardThird/v2/pay/callback?tradeModel=FLOW",
                "returnUrl" => "https://sqt.waimai.test.sankuai.com/c/finance/cashier/#/cashier-loading?serialNum=CCH1UH2E4SPV&payId=1625066085491413067",
                "firstBusinessType" => "010",
                "secondBusinessType" => "010120",
                "staffInfo" => [
                    "staffId" => 865,
                    "staffName" => "测试",
                    "staffNum" => 865,
                    "staffPhone" => "13422078495",
                    "staffEmail" => "test@test.com"
                ],
                "extInfoMap" => [
                    "ssoUser" => []
                ]
            ], $secretKey);

            echo $encryptContent;
            exit;*/

        }catch (\Exception $e){
            return $this->asJson([
                "status"  => $e->getCode() > 0 ? $e->getCode() : 401,
                "msg"     => $e->getMessage()
            ], false);
        }
    }

    /**
     * 免登
     */
    public function actionLoginFree(){
        try {

            $longitude = $this->requestData['longitude'];
            $latitude = $this->requestData['latitude'];

            $setting = MeituanSetting::getSettings();

            $accessKey = $setting['accessKey'];
            $secretKey = $setting['secretKey'];

            $contents['ts'] = time() * 1000;
            $contents['entId'] = $setting['entId'];
            $contents['nonce'] = md5(uniqid());
            $contents['productType'] = 'mt_waimai';
            $contents['sceneType'] = 4;
            $contents['bizParam'] = [
                'longitude' =>  [
                    'longitude' => $longitude,
                    'latitude' => $latitude,
                    'geotype' => 'gcj02',
                    'address' => '北京市朝阳区阜通东大街6号',
                ],
                'tmcApplyExtraJson' => [
                    'applyNo' => 33006,
                    'externalApplyNo' => "xxx-test-4",
                    'tripId' => 16758,
                    'externalTripId' => 'externalTripId1'
                ],
                'RepastApplyExtraJson' => [
                    'applyNo' => '5NQZ0NBUDZPD',
                    'externalApplyNo' => '5N9D0AD96RY92',
                ]
            ];
            $contents['staffInfo'] = [
                'staffPhone' => \Yii::$app->user->identity->mobile,
                'staffNum' => \Yii::$app->user->id
            ];

            $encryptContent = Aes::encrypt($contents, $secretKey);

            return $this->asJson(["data" => [
                "loginFreeUrl" => $setting['loginFreeUrl'],
                "accessKey"    => $accessKey,
                "content"      => $encryptContent]
            ]);
        }catch (\Exception $e){
            die($e->getMessage());
        }
    }
}