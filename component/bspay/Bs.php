<?php
namespace app\component\bspay;
use yii\base\Component;
use app\logic\AppConfigLogic;
use app\logic\ExceptionLogLogic;
class Bs extends Component{

    const CODE_SUCCESS  = 0;
    const CODE_FALI     = -1;

    public $on_dev = true;
    private function getPaymentConfig($mall_id = 0)
    {
    	$payconfig = AppConfigLogic::getPaymentConfig($mall_id);
    	return [
			'rsa_merch_private_key' => $payconfig['bspay_wechat_private_key'],
			'rsa_huifu_public_key' => $payconfig['bspay_wechat_public_key'],
			'product_id' => $payconfig['bspay_wechat_product_id'],
			'sys_id' => $payconfig['bspay_wechat_sys_id'],
			'huifu_id' => $payconfig['bspay_wechat_merchid']    		
    	];
    }
    private function info($title,$data)
    {
		$log = new ExceptionLogLogic();
        $log->error($title, $data);    	
    }
    private function error($msg='',$resText='',$jsonStr='')
    {
		return [
                "code"     => self::CODE_FALI,
                "msg"      => $msg,
                "data"     => !empty($resText) ? $resText : "{}",
                "json_str" => $jsonStr
            ];
    }
    private function success($resText='',$jsonStr='')
    {
		return [
            "code"     => self::CODE_SUCCESS,
            "data"     => $resText,
            "json_str" => $jsonStr,
            "res_text" => $resText
        ];
    }
	public function wechat_build($paramss, $type = 0)
	{

		$log = new ExceptionLogLogic();
		require_once __DIR__ . "/BsPaySdk/loader.php";
		$realpayconfig = $this->getPaymentConfig();
		require_once __DIR__ . "/BsPaySdk/request/V2TradePaymentJspayRequest.php";
		$request = new \BsPaySdk\request\V2TradePaymentJspayRequest();
		// 请求日期
		$request->setReqDate(date("Ymd"));
		// 请求流水号
		$request->setReqSeqId($paramss['outTradeNo']);
		// 商户号
		$request->setHuifuId($realpayconfig['huifu_id']);
		// 交易类型
		$request->setTradeType("T_JSAPI");
		// 交易金额
		$request->setTransAmt(number_format($paramss['payAmount']/100,2,'.',''));
		// 商品描述
		$request->setGoodsDesc('订单:'.$paramss['outTradeNo']);

		// 设置非必填字段
	    $extendInfoMap = array();
	    // 交易有效期
	    $extendInfoMap["time_expire"]= date('YmdHis', time() + 3600*24);//"20230418235959";
	    // 禁用信用卡标记
	    // $extendInfoMap["limit_pay_type"]= "NO_CREDIT";
	    // 是否延迟交易
	    // $extendInfoMap["delay_acct_flag"]= "N";
	    // 手续费扣款标志
	    // $extendInfoMap["fee_flag"]= "";
	    // 营销补贴信息
	    // $extendInfoMap["combinedpay_data"]= getCombinedpayData();
	    // 场景类型 01-标准费率线上（支持统一进件页面版）02-标准费率线下（支持统一进件页面版）
	    $extendInfoMap["pay_scene"]= "01";
	    // 安全信息

	    $extendInfoMap["wx_data"]= json_encode(array('sub_appid'=>$paramss['appId'],'sub_openid'=>$paramss['openId']),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	    $extendInfoMap["risk_check_data"]= json_encode(array('ip_addr'=>\Yii::$app->getRequest()->getUserIP()),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	    // 商户贴息标记
	    // $extendInfoMap["fq_mer_discount_flag"]= "N";
	    // 异步通知地址
	    $extendInfoMap["notify_url"]= $paramss['notifyUrl'];
	    // 备注
	    $extendInfoMap["remark"]= $paramss['mall_id']  . ':' . $type;
		$request->setExtendInfo($extendInfoMap);
		// 3. 发起API调用
		$client = new \BsPaySdk\core\BsPayClient();
		$result = $client->postRequest($request);
//        throw new \Exception("0xxxx".($result==null));
		if (!$result || $result->isError()) {  //失败处理
		    $err = $result->getErrorInfo();
		    $log->error('汇付天下微信支付请求失败', [$err]); 
		    return $this->error($err);
		} else {    //成功处理
			$RspDatas = $result->getRspDatas();
		    $log->info('汇付天下微信支付下单成功', $RspDatas); 
			if($RspDatas['data']['resp_code'] == '00000100'){
				$wOpt = json_decode($RspDatas['data']['pay_info'], true);
				$wOpt['wxPackage'] = $wOpt['package'];//兼容前端
				return $this->success($wOpt);
			}else{
				$log->error('汇付天下微信支付下单失败', $RspDatas); 
				return $this->error($RspDatas['data']['resp_desc']);
			}
		}
	}
	// 汇付用户注册
	public function basic_data_indv($paramss)
	{
		$log = new ExceptionLogLogic();
		require_once __DIR__ . "/BsPaySdk/loader.php";
		
		require_once __DIR__ . "/BsPaySdk/request/V2UserBasicdataIndvRequest.php";
		$request = new \BsPaySdk\request\V2UserBasicdataIndvRequest();
		// 请求流水号
		$request->setReqSeqId(date("YmdHis").mt_rand());
		// 请求日期
		$request->setReqDate(date("Ymd"));
		// 个人姓名
		$request->setName($paramss['realname']);
		// 个人证件类型
		$request->setCertType("00");
		// 个人证件号码
		$request->setCertNo($paramss['cert_no']);
		// 个人证件有效期类型
		$request->setCertValidityType($paramss['cert_validity_type']);
		// 个人证件有效期开始日期
		$request->setCertBeginDate(date('Ymd',strtotime($paramss['cert_begin_date'])));

		// 手机号
		$request->setMobileNo($paramss['mobile']);

		// 设置非必填字段
		$extendInfoMap = array('login_name'=>'Lt'.date("YmdHis").str_pad($paramss['id'],7,0,STR_PAD_LEFT));
		if($paramss['cert_validity_type'] == 0){
			$extendInfoMap['cert_end_date'] = date('Ymd',strtotime($paramss['cert_end_date']));
		}
		$request->setExtendInfo($extendInfoMap);
		// 3. 发起API调用
		$client = new \BsPaySdk\core\BsPayClient();
		$result = $client->postRequest($request);
$log->info('汇付用户注册', [var_export($request,true)]);
		if (!$result || $result->isError()) {  //失败处理
		    $err = $result->getErrorInfo();
		    $log->error('汇付用户注册失败', [$err]); 
		    return $this->error( $RspDatas['data']['resp_desc']);
		} else {    //成功处理
			$RspDatas = $result->getRspDatas();
			$log->info('汇付天下开户返回', $RspDatas); 
			if($RspDatas['data']['resp_code'] == '00000000'){
				
				return $this->success($RspDatas['data']);
			}else{
				$log->error('汇付天下开户失败', $RspDatas); 
				return $this->error($RspDatas['data']['resp_desc']);
			}
		}
	}
// 汇付用户基本信息修改
	public function basic_data_indv_modify($paramss)
	{
		$log = new ExceptionLogLogic();
		require_once __DIR__ . "/BsPaySdk/loader.php";
		$realpayconfig = $this->getPaymentConfig();
		require_once __DIR__ . "/BsPaySdk/request/V2UserBasicdataIndvModifyRequest.php";
		$request = new \BsPaySdk\request\V2UserBasicdataIndvModifyRequest();
		// 请求流水号
		$request->setReqSeqId(date("YmdHis").mt_rand());
		// 请求日期
		$request->setReqDate(date("Ymd"));
		// 汇付客户Id
		$request->setHuifuId($paramss['huifu_id']);


		// 设置非必填字段
		$extendInfoMap = array(
		);
		// 个人证件有效期类型
	    $extendInfoMap["cert_validity_type"]= $paramss['cert_validity_type'];
	    // 个人证件有效期开始日期
	    $extendInfoMap["cert_begin_date"]= date('Ymd',strtotime($paramss['cert_begin_date']));
	    // 手机号
	    $extendInfoMap["mobile_no"]= $paramss['mobile'];		
		if($paramss['cert_validity_type'] == 0){
		    // 个人证件有效期截止日期
			$extendInfoMap['cert_end_date'] = date('Ymd',strtotime($paramss['cert_end_date']));
		}
		$request->setExtendInfo($extendInfoMap);
		// 3. 发起API调用
		$client = new \BsPaySdk\core\BsPayClient();
		$result = $client->postRequest($request);
		@$log->info('汇付用户信息修改', [var_export($request,true)]);
		if (!$result || $result->isError()) {  //失败处理
		    $err = $result->getErrorInfo();
		    $log->error('汇付天下用户基本信息修改请求失败', [$err]); 
		    return $this->error( $RspDatas['data']['resp_desc']);
		} else {    //成功处理
			$RspDatas = $result->getRspDatas();
			$log->info('汇付天下用户基本信息修改返回', $RspDatas); 
			if($RspDatas['data']['resp_code'] == '00000000'){
				return $this->success($RspDatas['data']);	
			}else{
				$log->error('汇付天下用户基本信息修改失败', $RspDatas);
				return $this->error($RspDatas['data']['resp_desc']);
			}
		}
	}	
	// 用户业务入驻
	public function user_busi_open($paramss)
	{
		$log = new ExceptionLogLogic();
		require_once __DIR__ . "/BsPaySdk/loader.php";
		@$log->info('汇付业务入驻参数', [$paramss]);
		$realpayconfig = $this->getPaymentConfig();
		require_once __DIR__ . "/BsPaySdk/request/V2UserBusiOpenRequest.php";
		$request = new \BsPaySdk\request\V2UserBusiOpenRequest();
		$request->setHuifuId($paramss['huifu_id']);
		// 请求流水号
		$request->setReqSeqId(date("YmdHis").mt_rand());
		// 请求日期
		$request->setReqDate(date("Ymd"));
		// 渠道商/商户汇付Id
		$request->setUpperHuifuId($realpayconfig['huifu_id']);

		// 设置非必填字段
		$extendInfoMap = array(
			// 'settle_config'=>array(),
			'card_info'=>array(
				'card_type'=>1,
				'card_name'=>$paramss['card_name'],
				'card_no'=>$paramss['card_no'],
				'prov_id'=>$paramss['prov_id'],
				'area_id'=>$paramss['area_id'],
				'cert_type'=>$paramss['cert_type'],
				'cert_no'=>$paramss['cert_no'],
				'cert_validity_type'=>$paramss['cert_validity_type'],
				'cert_begin_date'=>date('Ymd',strtotime($paramss['cert_begin_date'])),
				// 'cert_end_date'=>date('Ymd',strtotime($paramss['cert_end_date'])),
			),
			'cash_config'=>json_encode(array(array('fee_rate'=>'0.00','cash_type'=>$paramss['huifu_cash_type'])),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
		);
		if($paramss['cert_validity_type'] == 0){
			$extendInfoMap['card_info']['cert_end_date'] = date('Ymd',strtotime($paramss['cert_end_date']));
		}
		$extendInfoMap['card_info'] = json_encode($extendInfoMap['card_info'],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		$request->setExtendInfo($extendInfoMap);
		// 3. 发起API调用
		$client = new \BsPaySdk\core\BsPayClient();
		@$log->info('汇付业务入驻', [var_export($request,true)]);
		$result = $client->postRequest($request);
		if (!$result || $result->isError()) {  //失败处理
		    $err = $result->getErrorInfo();
		    $log->error('汇付天下用户业务入驻请求失败', [$err]); 
		    return $this->error($err);
		} else {    //成功处理
			$RspDatas = $result->getRspDatas();
		    $log->info('汇付天下用户业务入驻请求返回', $RspDatas); 
			if($RspDatas['data']['resp_code'] == '00000000'){
				return $this->success($RspDatas['data']);
			}else{
			    $log->error('汇付天下用户业务入驻失败', $RspDatas); 
				return $this->error($RspDatas['data']['resp_desc']);
			}
		}
	}
	// 用户业务修改
	public function user_busi_modify($paramss)
	{
		$log = new ExceptionLogLogic();
		require_once __DIR__ . "/BsPaySdk/loader.php";
		
		$realpayconfig = $this->getPaymentConfig();
		require_once __DIR__ . "/BsPaySdk/request/V2UserBusiModifyRequest.php";
		$request = new \BsPaySdk\request\V2UserBusiModifyRequest();
		$request->setHuifuId($paramss['huifu_id']);
		// 请求流水号
		$request->setReqSeqId(date("YmdHis").mt_rand());
		// 请求日期
		$request->setReqDate(date("Ymd"));
		// 渠道商/商户汇付Id
		$request->setUpperHuifuId($realpayconfig['huifu_id']);

		// 设置非必填字段
		$extendInfoMap = array(
			// 'settle_config'=>array(),
			'card_info'=>array(
				'card_type'=>1,
				'card_name'=>$paramss['card_name'],
				'card_no'=>$paramss['card_no'],
				'prov_id'=>$paramss['prov_id'],
				'area_id'=>$paramss['area_id'],
				'cert_type'=>$paramss['cert_type'],
				'cert_no'=>$paramss['cert_no'],
				'cert_validity_type'=>$paramss['cert_validity_type'],
				'cert_begin_date'=>date('Ymd',strtotime($paramss['cert_begin_date'])),
				// 'cert_end_date'=>date('Ymd',strtotime($paramss['cert_end_date'])),
			),
			'cash_config'=>json_encode(array(array('fee_rate'=>'0.00','cash_type'=>$paramss['huifu_cash_type'],'switch_state'=>1)),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
		);
		if($paramss['cert_validity_type'] == 0){
			$extendInfoMap['card_info']['cert_end_date'] = date('Ymd',strtotime($paramss['cert_end_date']));
		}
		$extendInfoMap['card_info'] = json_encode($extendInfoMap['card_info'],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		$request->setExtendInfo($extendInfoMap);
		// 3. 发起API调用
		$client = new \BsPaySdk\core\BsPayClient();
		@$log->info('汇付业务修改', [var_export($request,true)]);
		$result = $client->postRequest($request);
		if (!$result || $result->isError()) {  //失败处理
		    $err = $result->getErrorInfo();
		    $log->error('汇付天下用户业务修改请求失败', [$err]); 
		    return $this->error($err);
		} else {    //成功处理
			$RspDatas = $result->getRspDatas();
		    $log->info('汇付天下用户业务修改请求返回', $RspDatas); 
			if($RspDatas['data']['resp_code'] == '00000000'){
				return $this->success($RspDatas['data']);
			}else{
			    $log->error('汇付天下用户业务修改失败', $RspDatas); 
				return $this->error( $RspDatas['data']['resp_desc']);
			}
		}		
	}
	/**
	 * 汇付天下余额支付
	 * @param  [type] $params     [description]
	 * @param  [type] $out_biz_no [description]
	 * @param  string $remark     [description]
	 * @return [type]             [description]
	 */
	public function acctpaymen_pay($params, $out_biz_no, $remark = '现金提现')
	{
		$log = new ExceptionLogLogic();
		require_once __DIR__ . "/BsPaySdk/loader.php";
		
		$realpayconfig = $this->getPaymentConfig();
		require_once __DIR__ . "/BsPaySdk/request/V2TradeAcctpaymentPayRequest.php";
		$request = new \BsPaySdk\request\V2TradeAcctpaymentPayRequest();
		// A23980120
		// 请求流水号
		$request->setReqSeqId($params['batch_no']);
		// 请求日期
		$request->setReqDate($params['date']);
		// 出款方商户号
		$request->setOutHuifuId($realpayconfig['huifu_id']);
		// 支付金额
		$request->setOrdAmt(number_format($params['money'],2,'.',''));
		// 分账对象
		$request->setAcctSplitBunch(json_encode(array('acct_infos'=>[['div_amt'=>number_format($params['money'],2,'.',''),'huifu_id'=>$params['huifu_id']]]),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
		// 安全信息
		$request->setRiskCheckData(json_encode(['transfer_type'=>'05','sub_product'=>'1','ip_addr'=>\Yii::$app->getRequest()->getUserIP()],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));

		// 设置非必填字段
		$extendInfoMap = array(
			'remark'=>$remark
		);
		
		$request->setExtendInfo($extendInfoMap);
		// 3. 发起API调用
		$client = new \BsPaySdk\core\BsPayClient();
		@$log->info('汇付余额支付', [var_export($request,true)]);
		$result = $client->postRequest($request);
		if (!$result || $result->isError()) {  //失败处理
		    $err = $result->getErrorInfo();
		    $log->error('汇付天下余额支付请求失败', [$err]); 

		    return $this->error( $err);
		} else {    //成功处理
			$RspDatas = $result->getRspDatas();
		    $log->info('汇付天下余额支付请求返回', $RspDatas); 

			if($RspDatas['data']['resp_code'] == '00000000'){
				return $this->success($RspDatas['data']);
			}else{
			    $log->error('汇付天下余额支付失败', $RspDatas); 
				return $this->error($RspDatas['data']['resp_desc']);
			}
		}		
	}
	/**
	 * 汇付天下余额支付查询
	 * @param  [type] $params     [description]
	 * @param  [type] $out_biz_no [description]
	 * @param  [type] $config     [description]
	 * @param  string $remark     [description]
	 * @return [type]             [description]
	 */
	public function acctpaymen_pay_query($reqdate, $batch_no)
	{
		$log = new ExceptionLogLogic();
		require_once __DIR__ . "/BsPaySdk/loader.php";
		
		$realpayconfig = $this->getPaymentConfig();
		require_once __DIR__ . "/BsPaySdk/request/V2TradeAcctpaymentPayQueryRequest.php";
		$request = new \BsPaySdk\request\V2TradeAcctpaymentPayQueryRequest();
		// A23980120
		// 请求流水号
		// 商户号
		$request->setHuifuId($realpayconfig['huifu_id']);
		// 原交易请求日期
		$request->setOrgReqDate($reqdate);
		// 设置非必填字段
		$extendInfoMap = array(
			'org_req_seq_id'=>$batch_no
		);
		
		$request->setExtendInfo($extendInfoMap);
		// 3. 发起API调用
		$client = new \BsPaySdk\core\BsPayClient();
		$result = $client->postRequest($request);
		@$log->info('汇付余额支付查询', [var_export($request,true)]);
		if (!$result || $result->isError()) {  //失败处理
		    $err = $result->getErrorInfo();
		    $log->error('汇付天下余额支付查询请求失败', [$err]); 

		    return $this->error($err);
		} else {    //成功处理
			$RspDatas = $result->getRspDatas();
		    $log->info('汇付天下余额支付查询请求返回', $RspDatas); 
			if($RspDatas['data']['resp_code'] == '00000000'){
				return $this->success($RspDatas['data']);
			}else{
			    $log->error('汇付天下余额支付查询失败', $RspDatas); 
				return $this->error( $RspDatas['data']['resp_desc']);
			}
		}		
	}	
	// 汇付提现
	public function settlement_enchashment($params, $remark = '现金提现')
	{
		$log = new ExceptionLogLogic();
		require_once __DIR__ . "/BsPaySdk/loader.php";
		
		$realpayconfig = $this->getPaymentConfig();
		require_once __DIR__ . "/BsPaySdk/request/V2TradeSettlementEnchashmentRequest.php";
		$request = new \BsPaySdk\request\V2TradeSettlementEnchashmentRequest();
		// A23980120
		// 请求日期
		$request->setReqDate($params['date']);
		// 请求流水号
		$request->setReqSeqId($params['batch_no']);
		// 取现金额
		$request->setCashAmt(number_format($params['money'],2,'.',''));
		// 取现方商户号
		$request->setHuifuId($params['huifu_id']);
		// 到账日期类型
		$request->setIntoAcctDateType("DM");//T1：次工作日到账； DM：当日到账；到账资金不包括当天的交易资金；
		// 取现卡序列号
		$request->setTokenNo($params['huifu_bank_token_no']);

		$notify_url = $params['notifyUrl'];
		// 设置非必填字段
		$extendInfoMap = array(
			'remark'=>$remark,
			'notify_url'=>$notify_url
		);
		
		$request->setExtendInfo($extendInfoMap);
		// 3. 发起API调用
		$client = new \BsPaySdk\core\BsPayClient();
		$result = $client->postRequest($request);
		@$log->info('汇付提现申请', [var_export($request,true)]);
		if (!$result || $result->isError()) {  //失败处理
		    $err = $result->getErrorInfo();
		    $log->error('汇付天下提现请求失败', [$err]); 
		    return $this->error($err);
		} else {    //成功处理
			$RspDatas = $result->getRspDatas();
		    $log->info('汇付天下提现请求返回', $RspDatas); 
			if($RspDatas['data']['resp_code'] == '00000000'){
				return $this->success($RspDatas['data']);
			}else{
			    $log->error('汇付天下提现失败', $RspDatas); 
				return $this->error( $RspDatas['data']['resp_desc']);
			}
		}		
	}	
	/**
	 * 汇付出金交易查询
	 * @param  [type] $reqdate  [description]
	 * @param  [type] $batch_no [description]
	 * @return [type]           [description]
	 */
	public function settlement_query($reqdate, $batch_no,$huifu_id,$mall_id = 0)
	{
		$log = new ExceptionLogLogic();
		require_once __DIR__ . "/BsPaySdk/loader.php";
		
		$realpayconfig = $this->getPaymentConfig($mall_id);
		require_once __DIR__ . "/BsPaySdk/request/V2TradeSettlementQueryRequest.php";
		$request = new \BsPaySdk\request\V2TradeSettlementQueryRequest();
		$request->setHuifuId($huifu_id);
		// 原交易请求日期
		$request->setOrgReqDate($reqdate);
		$request->setOrgReqSeqId($batch_no);
		// $request->setOrgHfSeqId($batch_no);
		// 设置非必填字段
		$extendInfoMap = array(
			'org_req_seq_id'=>$batch_no
		);
		
		$request->setExtendInfo($extendInfoMap);
		// 3. 发起API调用
		$client = new \BsPaySdk\core\BsPayClient();
		$result = $client->postRequest($request);
		$log->info('汇付提现查询原始参数', [$reqdate, $batch_no,$huifu_id]);
		@$log->info('汇付提现查询', [var_export($request,true)]);
		if (!$result || $result->isError()) {  //失败处理
		    $err = $result->getErrorInfo();
		    $log->error('汇付天下提现查询请求失败', [$err]); 
		    return $this->error($err);
		} else {    //成功处理
			$RspDatas = $result->getRspDatas();
		    $log->info('汇付天下提现查询请求返回', $RspDatas); 
			if($RspDatas['data']['resp_code'] == '00000000'){
				if($RspDatas['data']['trans_status'] == 'S'){
					return $this->success(true);
				}else{
					return $this->error($RspDatas['data']['trans_desc']);
				}
				return $this->success($RspDatas['data']);
			}else{
			    $log->error('汇付天下提现查询失败', $RspDatas); 
				return $this->error( $RspDatas['data']['resp_desc']);
			}
		}		
	}		
	
	public function isBsPayWeixinPay($out_trade_no, $OrgReqDate)
	{
		$log = new ExceptionLogLogic();
		
		require_once __DIR__ . "/BsPaySdk/loader.php";
		$realpayconfig = $this->getPaymentConfig(5);
		require_once __DIR__ . "/BsPaySdk/request/V2TradePaymentScanpayQueryRequest.php";		
		$request = new \BsPaySdk\request\V2TradePaymentScanpayQueryRequest();
		// 原机构请求流水号
		$request->setOrgReqSeqId($out_trade_no);
		$request->setOrgReqDate($OrgReqDate);
		// 商户号
		$request->setHuifuId($realpayconfig['huifu_id']);
		// 3. 发起API调用
		$client = new \BsPaySdk\core\BsPayClient();
		$result = $client->postRequest($request);
		if (!$result || $result->isError()) {  //失败处理
		    $err = $result->getErrorInfo();
		    $log->error('汇付天下支付查询请求失败', [$err]); 
		    return $this->error($err);
		} else {    //成功处理
	    	$RspDatas = $result->getRspDatas();
		    $log->info('汇付天下支付查询请求返回', $RspDatas); 
	    	if($RspDatas['data']['resp_code'] == '00000000'){
			    if("S"==$RspDatas['data']["trans_stat"]){
		    		// 支付成功
			    	return $this->success(true);
			    }
			    else{
			    	// 未支付
				    $log->info('汇付天下支付查询:未支付', $RspDatas); 
					return $this->error('未支付');
			    }

	    	}else{
	    		// 异常
			    $log->error('汇付天下支付查询失败', $RspDatas); 
	    		return $this->error($RspDatas['data']['resp_desc']);
	    	}
		}
	}
	/**
     * 退款
     * @param type $openid openid
     * @param type $money
     */
	public function refund($paramss)
	{
		$log = new ExceptionLogLogic();
		require_once __DIR__ . "/BsPaySdk/loader.php";
		
		$realpayconfig = $this->getPaymentConfig($mall_id);
		require_once __DIR__ . "/BsPaySdk/request/V2TradePaymentScanpayRefundRequest.php";

		
		$request = new \BsPaySdk\request\V2TradePaymentScanpayRefundRequest();
		// 请求日期
		$request->setReqDate(date("Ymd"));
		// 请求流水号
		$request->setReqSeqId($paramss['outRefundNo']);
		// 商户号
		$request->setHuifuId($realpayconfig['huifu_id']);
		// 退款金额
		$request->setOrdAmt(number_format($paramss['refundAmount'],2,'.',''));
		// 原交易请求日期
		$request->setOrgReqDate($paramss['paydate']);

		// 设置非必填字段
		$extendInfoMap = array();
		// 原交易全局流水号
		// $extendInfoMap["org_hf_seq_id"]= !empty($transaction_id) ? $transaction_id : "";
		// 原交易请求流水号
		$extendInfoMap["org_req_seq_id"]= $paramss['outTradeNo'];
		// $notify_url = "{$_W['siteroot']}addons/vcshop/payment/bspay/notify_refund.php";
		$extendInfoMap["notify_url"] = $paramss['notifyUrl'];
		
		$request->setExtendInfo($extendInfoMap);
		// 3. 发起API调用
		$client = new \BsPaySdk\core\BsPayClient();
		$log->info('汇付退款报文', $paramss);
		$result = $client->postRequest($request);
		if (!$result || $result->isError()) {  //失败处理
		    $err = $result->getErrorInfo();
		    $log->error('汇付天下退款请求失败', [$err]); 
		    return $this->error($err);
		} else {    //成功处理
			$RspDatas = $result->getRspDatas();
			$log->info('汇付天下退款返回', $RspDatas);
			if($RspDatas['data']['resp_code'] == '00000000'){
				if($RspDatas['data']['trans_stat'] == 'S'){
					return $this->success($RspDatas['data']);
				}elseif($RspDatas['data']['trans_stat'] == 'P'){
					$log->error('汇付天下退款处理中', []);
					return $this->error('交易处理中',$RspDatas['data'],10);
				}
				else{
					$log->error('汇付天下退款失败2', []);
					return $this->error($RspDatas['data']['resp_desc'],$RspDatas['data'],0);
				}
			}elseif($RspDatas['data']['resp_code'] == '00000100'){
				$log->error('汇付天下退款交易处理中', []);
				return $this->error('交易处理中',$RspDatas['data'],10);
			}else{
				$log->error('汇付天下退款失败处理', []);
				return $this->error($RspDatas['data']['resp_desc'],$RspDatas['data'],0);
			}
		}		
	}	
/**
	 * 汇付出金交易查询
	 * @param  [type] $reqdate  [description]
	 * @param  [type] $batch_no [description]
	 * @return [type]           [description]
	 */
	public function refund_query($reqdate, $batch_no,$mall_id = 0)
	{
		$log = new ExceptionLogLogic();
		require_once __DIR__ . "/BsPaySdk/loader.php";
		
		$realpayconfig = $this->getPaymentConfig($mall_id);
		require_once __DIR__ . "/BsPaySdk/request/V2TradePaymentScanpayRefundqueryRequest.php";
		$request = new \BsPaySdk\request\V2TradePaymentScanpayRefundqueryRequest();
		$request->setHuifuId($realpayconfig['huifu_id']);
		// 原交易请求日期
		$request->setOrgReqDate($reqdate);
		$request->setOrgReqSeqId($batch_no);
		// $request->setOrgHfSeqId($batch_no);
		// 设置非必填字段
		$extendInfoMap = array(
			'org_req_seq_id'=>$batch_no
		);
		
		$request->setExtendInfo($extendInfoMap);
		// 3. 发起API调用
		$client = new \BsPaySdk\core\BsPayClient();
		$result = $client->postRequest($request);
		$log->info('汇付退款查询原始参数', [$reqdate, $batch_no]);
		@$log->info('汇付退款查询', [var_export($request,true)]);
		if (!$result || $result->isError()) {  //失败处理
		    $err = $result->getErrorInfo();
		    $log->error('汇付天下退款查询请求失败', [$err]); 
		    return $this->error($err);
		} else {    //成功处理
			$RspDatas = $result->getRspDatas();
		    $log->info('汇付天下退款查询请求返回', $RspDatas); 
			if($RspDatas['data']['resp_code'] == '00000000'){
				if($RspDatas['data']['trans_stat'] == 'S'){
					return $this->success(true);
				}else{
					return $this->error($RspDatas['data']['trans_desc']);
				}
				return $this->success($RspDatas['data']);
			}else{
			    $log->error('汇付天下退款查询失败', $RspDatas); 
				return $this->error( $RspDatas['data']['resp_desc']);
			}
		}		
	}	
}