<?php
namespace app\forms\bs;

use app\core\ApiCode;
use app\models\BaseModel;
use app\plugins\mch\models\MchCash;
class BsMchCashTransfer extends BaseModel{
    /**
     * 用户提现转账
     * @param Cash $cash
     * @return array
     */
    public static function transfer(MchCash $cash,$title='商户提现'){
        $extra = (array)@json_decode($cash->type_data, true);
        $transferData = new BsTransferData([
            'outTradeNo'      => $cash->order_no,
            'source_type'     => 'mch_cash',
            'amount'          => (float)$cash->fact_price,
            'bankUserName'    => !empty($extra['bankUserName']) ? $extra['bankUserName'] : "",
            'bankCardNo'      => !empty($extra['bankCardNo']) ? $extra['bankCardNo'] : "",
            'bankName'        => !empty($extra['bankName']) ? $extra['bankName'] : "",
            'bankAccountType' => !empty($extra['bankAccountType']) ? $extra['bankAccountType'] : "2",
            
            'bankprovinceid' => !empty($extra['bankprovinceid']) ? $extra['bankprovinceid'] : "",
            'bankprovince' => !empty($extra['bankprovince']) ? $extra['bankprovince'] : "",
            'bankcityid' => !empty($extra['bankcityid']) ? $extra['bankcityid'] : "",
            'bankcity' => !empty($extra['bankcity']) ? $extra['bankcity'] : "",
            'huifu_bank_token_no' => !empty($extra['huifu_bank_token_no']) ? $extra['huifu_bank_token_no'] : "",
            'title' => $title,
            'user_id' => !empty($cash->mch->user_id) ? $cash->mch->user_id : "",
            'notifyUrl' => \Yii::$app->getRequest()->getHostInfo() . "/web/withdraw-notify/mchbs.php",
        ]);
        return BsTransfer::execute($transferData);
    }

}