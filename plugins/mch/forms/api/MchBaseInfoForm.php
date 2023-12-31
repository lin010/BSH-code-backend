<?php

namespace app\plugins\mch\forms\api;


use app\core\ApiCode;
use app\helpers\CityHelper;
use app\models\BaseModel;
use app\models\EfpsMchReviewInfo;
use app\models\Goods;
use app\models\Order;
use app\plugins\mch\models\Mch;
use app\plugins\mch\models\MchApply;
use app\plugins\mch\models\MchGroup;
use app\plugins\mch\models\MchPriceLog;
use app\models\User;
/**
 * @author mr.lin
 * @deprecated 即将弃用
 */
class MchBaseInfoForm extends BaseModel{

    public $mch_id;

    public function rules(){
        return [
            [['mch_id'], 'required'],
        ];
    }

    public function get()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }

        try {

            $baseData = [
                'store'      => null,
                'category'   => null,
                'stat'       => null,
                'mch_mobile' => '',
                'settle'     => []
            ];

            $mchInfo = Mch::find()->where([
                'id' => $this->mch_id,
                'is_delete' => 0
            ])->with(["store", "category"])->asArray()->one();

            if(!$mchInfo || $mchInfo['is_delete']){
                throw new \Exception("商户不存在");
            }

            if($mchInfo['review_status'] == Mch::REVIEW_STATUS_CHECKED){
                $mchInfo['mch_status'] = "passed";
            }else{
                $mchApply = MchApply::findOne(["user_id" => $mchInfo['user_id']]);
                if($mchApply && $mchApply->status == "passed"){
                    $mchApply->status = "applying";
                    if(!$mchApply->save()){
                        throw new \Exception($this->responseErrorMsg($mchApply));
                    }
                }
                if(!$mchApply){
                    $mchInfo['mch_status'] = "applying";
                }else{
                    $mchInfo['mch_status'] = $mchApply->status;
                }
            }

            $baseData['store'] = $mchInfo['store'];
            $city = CityHelper::reverseData($mchInfo['store']['district_id'],
                $mchInfo['store']['city_id'], $mchInfo['store']['province_id']);
            $baseData['store']['province'] = $city['province'] ? $city['province']['name'] : '';
            $baseData['store']['city'] = $city['city'] ? $city['city']['name'] : '';
            $baseData['store']['district'] = $city['district'] ? $city['district']['name'] : '';
            if(!preg_match("/^https?:\/\//i", trim($baseData['store']['cover_url']))){
                $baseData['store']['cover_url'] =  $this->host_info . "/web/static/header-logo.png";
            }

            $baseData['mch_status']  = $mchInfo['mch_status'];
            $baseData['category']    = $mchInfo['category'];
            $baseData['bind_mobile'] = $mchInfo['mobile'];
            $baseData['stat']        = [
                'account_money' => (float)$mchInfo['account_money'],
                'order_num'     => 0,
                'goods_num'     => 0
            ];

            //商户订单数量
            $baseData['stat']['order_num'] = (int)Order::find()->where([
                'is_delete'  => 0,
                'is_recycle' => 0,
                'mch_id'     => $mchInfo['id']
            ])->count();

            //商户商品数量
            $baseData['stat']['goods_num'] = (int)Goods::find()->where([
                'is_delete'  => 0,
                'is_recycle' => 0,
                'mch_id'     => $mchInfo['id']
            ])->count();

            $baseData['mch_mobile'] = $mchInfo['mobile'];

            $user = User::findOne(\Yii::$app->user->identity->id);
            //获取结算信息
            $efpsReviewInfo = EfpsMchReviewInfo::find()->where([
                "mch_id" => $mchInfo['id']
            ])->select([
                "paper_settleAccountType", "paper_settleAccountNo",
                "paper_settleAccount", "paper_settleTarget", "paper_openBank","bankcity","bankcityid","bankprovince","bankprovinceid"
            ])->one();
            if($efpsReviewInfo){
                $baseData['settle'] = $efpsReviewInfo;
            }elseif(!empty($user->huifu_bank_token_no)){
                $baseData['settle'] = array(
                    "paper_settleAccountType"=>2,
                    "paper_settleTarget"=>2,
                    "paper_settleAccountNo"=>$user->bank_account,
                    "paper_settleAccount"=>$user->realname,
                    "paper_openBank"=>$user->bank_name,
                    "bankcity"=>$user->bankcity,
                    "bankcityid"=>$user->bankcityid,
                    "bankprovince"=>$user->bankprovince,
                    "bankprovinceid"=>$user->bankprovinceid                    
                );
            }

            //获取商户待结算金额
            $fzAccountMoney = (float)MchPriceLog::find()->where([
                "mch_id" => $mchInfo['id']
            ])->andWhere(["IN", "status", ["unconfirmed", "confirmed"]])->sum("price");
            $baseData['stat']['fz_account_money'] = $fzAccountMoney;

            //判断是否总店账号
            $mchGroup = MchGroup::findOne([
                "mch_id"    => $this->mch_id,
                "is_delete" => 0
            ]);
            $baseData['mch_group_id'] = $mchGroup ? $mchGroup->id : 0;

            if ($baseData['store']['business_hours']) {
                $business_hours = explode('-', $baseData['store']['business_hours']);
                if (count($business_hours) <= 1) {
                    $business_hours = explode('~', $baseData['store']['business_hours']);
                }
                if ($business_hours > 1) {
                    foreach ($business_hours as &$item) {
                        $item = trim($item);
                    }
                    $baseData['store']['business_hours'] = $business_hours;
                } else {
                    $baseData['store']['business_hours'] = ['08:00', '22:00'];
                }
            } else {
                $baseData['store']['business_hours'] = ['08:00', '22:00'];
            }
            
            $baseData['user'] = ['huifu_id'=>$user->huifu_id];
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => [
                    'base_info' => $baseData
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_FAIL,
                'msg' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ];
        }
    }
}