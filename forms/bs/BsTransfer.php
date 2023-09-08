<?php
namespace app\forms\bs;


use app\component\bspay\Bs;
use app\core\ApiCode;
use app\models\BaseModel;
use app\models\BsTransferOrder;
use app\logic\AppConfigLogic;
use app\models\User;
class BsTransfer extends BaseModel{

    

    /**
     * 查询打款状态
     * @param $outTradeNo
     * @return array
     */
    public static function query($outTradeNo){
        $res = \Yii::$app->efps->withdrawalToCardQuery([
            "customerCode" => \Yii::$app->efps->getCustomerCode(),
            "outTradeNo"   => $outTradeNo
        ]);

        if($res['code'] == Efps::CODE_SUCCESS && $res['data']['returnCode'] == "0000" && $res['data']['payState'] == "00"){
            $res['code'] = ApiCode::CODE_SUCCESS;
            $res['msg']  = '打款成功';
        }else{
            $res['code'] = ApiCode::CODE_FAIL;
            $res['msg']  = "打款失败：" . $res['data']['returnMsg'];
        }

        return $res;
    }

    public static function execute(BsTransferData $transferData){

        if(!$transferData->validate()){
            throw new \Exception(json_encode($transferData->getErrors()));
        }

        $transferOrder = BsTransferOrder::findOne(["outTradeNo" => $transferData->outTradeNo]);
        $payconfig = AppConfigLogic::getPaymentConfig(\Yii::$app->mall->id ?? 5);
        if(!$transferOrder){
            $transferOrder = new BsTransferOrder([
                "status"          => 0,
                "outTradeNo"      => $transferData->outTradeNo,
                "source_type"     => $transferData->source_type,
                "customerCode"    => $payconfig['bspay_wechat_merchid'] ?? "",
                "notifyUrl"       => $transferData->notifyUrl,
                "amount"          => $transferData->amount,
                "bankUserName"    => $transferData->bankUserName,
                "bankCardNo"      => $transferData->bankCardNo,
                "bankName"        => $transferData->bankName,
                "bankAccountType" => $transferData->bankAccountType,
                "huifu_bank_token_no" => $transferData->huifu_bank_token_no,
                "bankcity" => $transferData->bankcity,
                "bankcityid" => $transferData->bankcityid,
                "bankprovince" => $transferData->bankprovince,
                "bankprovinceid" => $transferData->bankprovinceid,
                "created_at"      => time(),
                "updated_at"      => time()
            ]);
            if(!$transferOrder->save()){
                return [
                    'code' => ApiCode::CODE_FAIL,
                    'msg'  => json_encode($transferOrder->getErrors())
                ];
            }
        }

        try {
            $isTransmitting = 1;
            if($transferOrder->status == 2){ //成功
                $res['code'] = ApiCode::CODE_SUCCESS;
                $res['msg']  = '打款成功';
            }else{
            	$user = User::findOne($transferData->user_id);
				$batch_no_money = $transferOrder->amount;

				$batch_no = 'D' . date('YmdHis') . 'CW' . $transferOrder->id . 'MONEY' . $batch_no_money;


				$reqdate = date("Ymd");
				if($transferOrder->status == 0){
					$acctpaymen_pay_data = [
								'name' => $transferData->bankUserName,
								'money' => $batch_no_money,
								'batch_no'=>$batch_no,
								'date'=>$reqdate,
								'huifu_id'=>$user->huifu_id
							];
					$transferOrder->huifu_id = $user->huifu_id;
					$transferOrder->acctpayment_org_req_seq_id = $batch_no;
					$transferOrder->acctpayment_org_req_date = $reqdate;
					$transferOrder->acctpayment_request_text = json_encode($acctpaymen_pay_data);
					$res = \Yii::$app->bs->acctpaymen_pay($acctpaymen_pay_data, $batch_no, $transferData->title);
                    $transferOrder->acctpayment_resonse_text = json_encode($res['data']);
					if($res['code'] != ApiCode::CODE_SUCCESS){
                        $res['code'] = ApiCode::CODE_FAIL;
                        $res['msg']  = $res['msg'];
                        $transferOrder->remark = $res['msg'];
                    }else{
						$transferOrder->status = 1;
                    }
				}
				if($transferOrder->status == 1){
					$batch_no = $transferOrder->acctpayment_org_req_seq_id;
					$reqdate = $transferOrder->acctpayment_org_req_date;
					$res = \Yii::$app->bs->acctpaymen_pay_query($reqdate, $batch_no);

					if($res['code'] != ApiCode::CODE_SUCCESS){
						$res['code'] = ApiCode::CODE_SUCCESS;
                        $res['msg']  = $res['msg'];
                    }else{
						$transferOrder->status = 2;
                    }
				}
				if($transferOrder->status == 2){
					$batch_no = 'D' . date('Ymdhis') . 'BW' . $transferOrder->id . 'MONEY' . $batch_no_money;
					$reqdate = date("Ymd");
					$settlement_enchashment_data = array('money' => $batch_no_money,'batch_no'=>$batch_no,'date'=>$reqdate,'huifu_id'=>$user->huifu_id,'huifu_bank_token_no'=>$transferData->huifu_bank_token_no,'notifyUrl'=>$transferOrder->notifyUrl);
					$transferOrder->acctpayment_request_text = json_encode($settlement_enchashment_data);
					$transferOrder->settlement_org_req_seq_id = $batch_no;
					$transferOrder->settlement_org_req_date = $reqdate;
					$res = \Yii::$app->bs->settlement_enchashment($settlement_enchashment_data, $transferData->title);
                    $transferOrder->acctpayment_resonse_text = json_encode($res['data']);
					if($res['code'] != ApiCode::CODE_SUCCESS){
                        $res['code'] = ApiCode::CODE_SUCCESS;
                        $res['msg']  = $res['msg'] == '交易不存在' ? '申请取现已提交,请等待汇付打款' : '申请取现失败,请重试; 失败原因:'.$res['msg'];
                    }else{
						$transferOrder->status = 3;
                    }
				}
				if($transferOrder->status == 3){
					$batch_no = $transferOrder->settlement_org_req_seq_id;
					$reqdate = $transferOrder->settlement_org_req_date;
					$res = \Yii::$app->bs->settlement_query($reqdate, $batch_no,$transferOrder->huifu_id);
					if($res['code'] != ApiCode::CODE_SUCCESS){
						$res['code'] = ApiCode::CODE_SUCCESS;
                        $res['msg']  = $res['msg'] == '交易不存在' ? '申请取现已提交,请等待汇付打款' : '申请取现失败,请重试; 失败原因:'.$res['msg'];
                    }else{
						$transferOrder->status = 4;
                    	$isTransmitting = 0;
                    }
				}

                $transferOrder->updated_at = time();
                $transferOrder->save();
            }

            $res['is_transmitting'] = $isTransmitting;

            return $res;
        }catch (\Exception $e){
            return [
                'code' => ApiCode::CODE_FAIL,
                'msg'  => $e->getMessage()
            ];
        }
    }
}