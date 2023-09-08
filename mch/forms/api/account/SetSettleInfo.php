<?php


namespace app\mch\forms\api\account;


use app\core\ApiCode;
use app\models\BaseModel;
use app\models\EfpsMchReviewInfo;
use app\plugins\mch\models\Mch;
use app\models\User;
class SetSettleInfo extends BaseModel{

    public $mch_id;
    public $withdraw_pwd;
    public $paper_settleAccountType;
    public $paper_settleAccountNo;
    public $paper_settleAccount;
    public $paper_settleTarget;
    public $paper_openBank;
    public $bankprovinceid;
    public $bankprovince;
    public $bankcityid;
    public $bankcity;    

    public function rules(){
        return array_merge(parent::rules(), [
            [['mch_id', 'withdraw_pwd', 'paper_settleAccountType', 'paper_settleAccountNo',
              'paper_settleAccount', 'paper_settleTarget', 'paper_openBank','bankprovinceid','bankcityid','bankprovince','bankcity'], 'required'],
            [['withdraw_pwd'], 'string', 'min' => 6, 'max' => 6]
        ]);
    }

    public function save(){
        if(!$this->validate()){
            return $this->responseErrorInfo();
        }

        try {
            if (!$this->checkBank($this->paper_settleAccountNo))
                throw new \Exception("银行卡填写不正确");

            $mch = Mch::findOne([
                "id"            => $this->mch_id,
                "review_status" => Mch::REVIEW_STATUS_CHECKED
            ]);
            if(!$mch){
                throw new \Exception("商户不存在");
            }

            if(empty($mch->withdraw_pwd)){
                throw new \Exception("交易密码不正确");
            }

            $security = \Yii::$app->getSecurity();
            if(!$security->validatePassword($this->withdraw_pwd, $mch->withdraw_pwd)){
                throw new \Exception("交易密码不正确");
            }

            $reviewInfo = EfpsMchReviewInfo::findOne([
                "mch_id" => $this->mch_id
            ]);
            if(!$reviewInfo){
                throw new \Exception("无法获取审核信息");
            }
            if(!$this->bankcityid || !$this->bankprovinceid){
                return $this->returnApiResultData(ApiCode::CODE_FAIL, '请选择开户行所在省市！');
            }
            $user = User::findOne(\Yii::$app->user->identity->id);
            if(empty($user->huifu_id)){
                return $this->returnApiResultData(ApiCode::CODE_FAIL, '请先完成实名认证！');
            }
            if($user->realname != $this->paper_settleAccount){
                return $this->returnApiResultData(ApiCode::CODE_FAIL, '银行卡户名和实名认证名字不一致,请使用实名认证名字开户的银行卡');
            }
            $huifu_bank_token_no = $reviewInfo->huifu_bank_token_no;            
            $huifu = [];
            $huifu['card_name'] = $this->paper_settleAccount;
            $huifu['card_no'] =  $this->paper_settleAccountNo;
            $huifu['prov_id'] = $this->bankprovinceid;
            $huifu['area_id'] = $this->bankcityid;
            $huifu['huifu_id'] = $user->huifu_id;
            $huifu['cert_validity_type'] = $user->cert_validity_type;
            $huifu['cert_begin_date'] = $user->cert_begin_date;
            $huifu['cert_end_date'] = $user->cert_end_date;
            $huifu['cert_type'] = '00';
            $huifu['cert_no'] = $user->cert_no;
            $huifu['huifu_cash_type'] = 'DM';
            $hasEdit = false;
            if(empty($reviewInfo->huifu_bank_token_no)){
                $res = \Yii::$app->bs->user_busi_open($huifu);
                if($res['code']==-1){
                    return $this->returnApiResultData(ApiCode::CODE_FAIL,$res['message']);
                }
                $hasEdit = true;
            }else{
                if($reviewInfo->paper_settleAccountNo != $huifu['card_no']){
                    $res = \Yii::$app->bs->user_busi_modify($huifu);
                    $hasEdit = true;
                    if($res['code']==-1){
                        return $this->returnApiResultData(ApiCode::CODE_FAIL,$res['message']);
                    }
                }
            }
            if($hasEdit){
                
                $user->bankprovinceid = $reviewInfo->bankprovinceid = $huifu['prov_id'];
                $user->bankcityid = $reviewInfo->bankcityid = $huifu['area_id'];
                $user->bankprovince = $reviewInfo->bankprovince = $this->bankprovince;
                $user->bankcity = $reviewInfo->bankcity = $this->bankcity;
                $user->huifu_bank_token_no = $reviewInfo->huifu_bank_token_no = $res['data']['token_no'];
                $reviewInfo->huifu_cash_type = $huifu['huifu_cash_type'];
                $user->bank_account = $huifu['card_no'];
                $user->save();
                
            }
            if(!in_array($this->paper_settleTarget, [1, 2])){
                $this->paper_settleTarget = 2;
            }

            $reviewInfo->paper_settleAccountType = $this->paper_settleAccountType;
            $reviewInfo->paper_settleAccountNo   = $this->paper_settleAccountNo;
            $reviewInfo->paper_settleAccount     = $this->paper_settleAccount;
            $reviewInfo->paper_settleTarget      = $this->paper_settleTarget;
            $reviewInfo->paper_openBank          = $this->paper_openBank;

            if(!$reviewInfo->save()){
                throw new \Exception($this->responseErrorMsg($reviewInfo));
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg'  => "操作成功"
            ];
        }catch (\Exception $e){
            return [
                'code' => ApiCode::CODE_FAIL,
                'msg'  => $e->getMessage(),
            ];
        }
    }

    public function checkBank($no)
    {
        $arr_no = str_split($no);
        $last_n = $arr_no[count($arr_no) - 1];
        krsort($arr_no);
        $i = 1;
        $total = 0;
        foreach ($arr_no as $n) {
            if ($i % 2 == 0) {
                $ix = $n * 2;
                if ($ix >= 10) {
                    $nx = 1 + ($ix % 10);
                    $total += $nx;
                } else {
                    $total += $ix;
                }
            } else {
                $total += $n;
            }
            $i++;
        }
        $total -= $last_n;
        $total *= 9;
        if ($last_n == ($total % 10)) {
            return true;
        } else {
            return false;
        }
    }
}