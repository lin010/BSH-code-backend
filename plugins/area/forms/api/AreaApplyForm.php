<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * 分销佣金接口处理类
 * Author: zal
 * Date: 2020-05-26
 * Time: 10:30
 */

namespace app\plugins\area\forms\api;

use app\core\ApiCode;
use app\models\BaseModel;
use app\models\User;

use app\plugins\area\forms\common\Common;
use app\plugins\area\models\AreaAgent;
use app\plugins\area\models\AreaApply;
use app\plugins\area\models\AreaSetting;
use app\plugins\distribution\models\DistributionSetting;


class AreaApplyForm extends BaseModel
{


    public $address;
    public $realname;
    public $mobile;
    public $marks;


    public function rules()
    {
        return [
            [['realname', 'mobile', 'address'], 'required'],
            [['address'], 'string', 'max' => 128],
            [['realname', 'mobile'], 'string', 'max' => 45],
        ]; // TODO: Change the autogenerated stub
    }

    public function apply()
    {

        if (!$this->validate()) {
            return $this->returnApiResultData();
        }
        /** @var AreaApply $apply */
        $apply = AreaApply::find()->where(['user_id' => \Yii::$app->user->identity->id, 'is_delete' => 0])->orderBy("id desc")->one();
        if (!empty($apply)) {
            if($apply->status == AreaApply::STATUS_WAIT_REVIEW){
                return $this->returnApiResultData(ApiCode::CODE_FAIL, '您已经提交了申请,请耐心等待处理');
            }else if($apply->status == AreaApply::STATUS_PASS){
                return $this->returnApiResultData(ApiCode::CODE_FAIL, '您已经成为通过审核成为区域代理，无需再申请');
            }
        }
        $apply = new AreaApply();
        $apply->attributes = $this->attributes;
        $apply->mall_id = \Yii::$app->mall->id;
        $apply->user_id = \Yii::$app->user->identity->id;
        if (!$apply->save()) {
            return $this->returnApiResultData(ApiCode::CODE_FAIL, '保存失败', ['error' => $apply->getErrors()]);
        }
        $is_apply = AreaSetting::getValueByKey(AreaSetting::IS_APPLY, \Yii::$app->mall->id);
        $is_check = AreaSetting::getValueByKey(AreaSetting::IS_CHECK, \Yii::$app->mall->id);
        $user = User::findOne($apply->user_id);
        if ($is_apply == 0 && $is_check == 0) {
            $area = new AreaAgent();
            $area->mall_id = $apply->mall_id;
            $area->user_id = $apply->user_id;
            $area->created_at = time();
            $area->level = 0;
            $area->district_id = 0;
            $area->province_id = 0;
            $area->city_id = 0;
            $area->town_id = 0;
            $area->is_delete = 0;
            if ($area->save()) {
                if (!$user->is_inviter) {
                    $user->inviter_at = time();
                    $user->save();
                }
                if ($is_apply == 0 && $is_check == 0) {
                    $apply->status = 1;
                    $apply->marks = '审核通过';
                    $apply->save();
                }
                return $this->returnApiResultData(ApiCode::CODE_SUCCESS, '恭喜您成为区域代理商，请退出后再次进入');
            }
        }
        return $this->returnApiResultData(ApiCode::CODE_SUCCESS, '提交成功,请耐心等待处理');
    }
}