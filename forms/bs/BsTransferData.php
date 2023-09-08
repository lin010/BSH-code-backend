<?php
namespace app\forms\bs;


use app\models\BaseModel;

class BsTransferData extends BaseModel{

    public $outTradeNo;
    public $source_type;
    public $amount;
    public $bankUserName;
    public $bankCardNo;
    public $bankName;
    public $bankAccountType;
    public $bankNo;
    public $huifu_bank_token_no;
    public $bankcity;
    public $bankcityid;
    public $bankprovince;
    public $bankprovinceid;
    public $title;
    public $user_id;
    public $notifyUrl;

    public function rules(){
        return [
            [['outTradeNo', 'source_type', 'amount', 'bankUserName', 'bankCardNo', 'bankName', 'bankAccountType','huifu_bank_token_no','bankcity','bankcityid','bankprovince','bankprovinceid','title','user_id','notifyUrl'], 'required'],
            [['bankNo'], 'safe']
        ];
    }

}