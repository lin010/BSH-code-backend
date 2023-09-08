<?php
namespace app\forms\bs;

use app\core\ApiCode;
use app\models\BaseModel;
use app\models\IntegralCash;

class BsIntegralCashTransfer extends BaseModel{
    /**
     * 用户提现转账
     * @param Cash $cash
     * @return array
     */
    public static function transfer(IntegralCash $cash,$title='用户提现'){
        if($cash->status == 1){
            $extra = (array)@json_decode($cash->extra, true);

            $transferData = new BsTransferData([
                'outTradeNo'      => $cash->order_no,
                'source_type'     => 'user_integral_cash',
                'amount'          => (float)$cash->fact_price,
                'bankUserName'    => !empty($extra['name']) ? $extra['name'] : "",
                'bankCardNo'      => !empty($extra['bank_account']) ? $extra['bank_account'] : "",
                'bankName'        => !empty($extra['bank_name']) ? $extra['bank_name'] : "",
                'bankAccountType' => !empty($extra['bankAccountType']) ? $extra['bankAccountType'] : "2",
                'bankprovinceid' => !empty($extra['bankprovinceid']) ? $extra['bankprovinceid'] : "",
                'bankprovince' => !empty($extra['bankprovince']) ? $extra['bankprovince'] : "",
                'bankcityid' => !empty($extra['bankcityid']) ? $extra['bankcityid'] : "",
                'bankcity' => !empty($extra['bankcity']) ? $extra['bankcity'] : "",
                'huifu_bank_token_no' => !empty($extra['huifu_bank_token_no']) ? $extra['huifu_bank_token_no'] : "",
                'title' => $title,
                'user_id' => $cash->user_id,
                'notifyUrl' => \Yii::$app->getRequest()->getHostInfo() . "/web/withdraw-notify/integralbs.php",
            ]);

            return BsTransfer::execute($transferData);
        }

        return ['code' => ApiCode::CODE_FAIL, 'msg' => '未同意该提现'];
    }

}