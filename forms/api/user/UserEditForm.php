<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * 购物车api-购物车操作
 * Author: zal
 * Date: 2020-04-21
 * Time: 14:50
 */

namespace app\forms\api\user;

use app\forms\api\identity\SmsForm;
use app\helpers\sms\Sms;
use app\logic\CommonLogic;
use app\models\BaseModel;
use app\core\ApiCode;
use app\models\Cart;
use app\models\GoodsAttr;
use app\models\User;
use app\validators\PhoneNumberValidator;

class UserEditForm extends BaseModel
{
    public $avatar;
    public $nickname;
    public $birthday;
    public $mobile;
    public $captcha;
    public $realname;
    public $cert_validity_type;
    public $cert_no;
    public $cert_begin_date;
    public $cert_end_date;

    public function rules()
    {
        return [
            [['nickname','avatar','captcha','realname','cert_no','cert_begin_date','cert_end_date'],'string'],
            [['birthday'], 'safe'],
            [['cert_validity_type'], 'number'],

            [['mobile'], PhoneNumberValidator::className()],
        ];
    }

    /**
     * 修改
     * @Author: zal
     * @Date: 2020-05-07
     * @Time: 14:33
     * @return array
     */
    public function edit()
    {
        if (!$this->validate()) {
            return $this->returnApiResultData();
        }
        try {
            $user_id = \Yii::$app->user->id;
            /** @var User $user */
            $user = User::getOneData([
                'id' => $user_id,
                'mall_id' => \Yii::$app->mall->id
            ]);
            //是否更新
            $isUpdate = false;
            if(empty($user)){
                throw new \Exception("用户不存在");
            }
            if(!empty($this->nickname)){
                $isUpdate = true;
                $user->nickname = $this->nickname;
            }
            if(!empty($this->birthday)){
                $isUpdate = true;
                $user->birthday = strtotime($this->birthday);
            }
            if(!empty($this->avatar)){
                $isUpdate = true;
                $user->avatar_url = $this->avatar;
            }
            if(!empty($this->realname)){
                $isUpdate = true;
                if(!empty($user['huifu_id'])){// && $this->realname == $user['realname']){
//                    return $this->returnApiResultData(ApiCode::CODE_FAIL,'本账号已实名认证,真实姓名不能修改');
                }else{
                    if($isUpdate==true) $user->realname = $this->realname;
                    if(is_numeric($this->cert_validity_type)){
                        $isUpdate = true;
                        $user->cert_validity_type = $this->cert_validity_type;
                        if($this->cert_validity_type == 0 && empty($this->cert_end_date)){
                            return $this->returnApiResultData(ApiCode::CODE_FAIL,'非长期身份证需要填写身份证有效期截止日期');
                        }
                    }
                    if(!empty($this->cert_no)){
                        $isUpdate = true;
                        $user->cert_no = $this->cert_no;
                    }
                }
            }
            if(!empty($this->cert_begin_date)){
                $isUpdate = true;
                $user->cert_begin_date = $this->cert_begin_date;
            }
            if(!empty($this->cert_end_date)){
                $isUpdate = true;
                $user->cert_end_date = $this->cert_end_date;
            }
            if(!empty($this->mobile)){
                $smsForm = new SmsForm();
                $smsForm->captcha = $this->captcha;
                $smsForm->mobile = $this->mobile;
                if(!$smsForm->checkCode()){
                    return $this->returnApiResultData(ApiCode::CODE_FAIL,'验证码不正确');
                }
                if($user['mobile'] == $this->mobile){
                    return $this->returnApiResultData(ApiCode::CODE_FAIL,'不必重复绑定同一号码');
                }
                $check = User::find()->select('id')->where(['and',['=','mobile',$this->mobile],['<>','id',$user_id],['=','mall_id',\Yii::$app->mall->id]])->asArray()->one();
                if($check){
                    return $this->returnApiResultData(ApiCode::CODE_FAIL,'已经有其他用户绑定该号码');
                }
                $isUpdate = true;
                $user->mobile = $this->mobile;
            }

            $code = ApiCode::CODE_SUCCESS;
            if($isUpdate){
                if($user->save() === false){
                    $code = ApiCode::CODE_FAIL;
                }
            }
            $user = User::getOneData([
                'id' => $user_id,
                'mall_id' => \Yii::$app->mall->id
            ]);
            $hasMustHuiFuInfo = ( !empty($user['realname']) && !empty($user['mobile']) && !empty($user['cert_no']) && !empty($user['cert_begin_date']) && ($user['cert_validity_type']==1 || !empty($user['cert_end_date']) )  );
            if($hasMustHuiFuInfo){
                if(empty($user['huifu_id'])){
                    $res = \Yii::$app->bs->basic_data_indv($user);
                    if($res['code']==-1){
                        return $this->returnApiResultData(ApiCode::CODE_FAIL,$res['msg']);
                    }
                    $user->huifu_id = $res['data']['huifu_id'];
                    $user->huifu_login_name = $res['data']['login_name'];
                    $user->huifu_login_password = $res['data']['login_password'];
                    $user->huifu_create_time=date('Y-m-d H:i:s');
                }else{
                    // 修改
                    $res = \Yii::$app->bs->basic_data_indv_modify($user);
                    if($res['code']==-1){
                        return $this->returnApiResultData(ApiCode::CODE_FAIL,$res['msg']);
                    }
                    $user->huifu_update_time = date('Y-m-d H:i:s');
                }
                $user->save();
            }
            return $this->returnApiResultData($code,"");
        } catch (\Exception $e) {
            return $this->returnApiResultData(ApiCode::CODE_FAIL,CommonLogic::getExceptionMessage($e));
        }
    }
}
