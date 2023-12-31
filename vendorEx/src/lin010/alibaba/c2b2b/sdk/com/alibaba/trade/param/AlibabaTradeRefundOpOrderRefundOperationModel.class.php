<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');
include_once ('AlibabaTradeRefundBuyerQueryOrderRefundListParam/AlibabaTradeRefundOpLogisticsCompanyModel.class.php');

class AlibabaTradeRefundOpOrderRefundOperationModel extends SDKDomain {

       	
    private $afterOperateStatus;
    
        /**
    * @return 操作后的退款状态
    */
        public function getAfterOperateStatus() {
        return $this->afterOperateStatus;
    }
    
    /**
     * 设置操作后的退款状态     
     * @param String $afterOperateStatus     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setAfterOperateStatus( $afterOperateStatus) {
        $this->afterOperateStatus = $afterOperateStatus;
    }
    
        	
    private $beforeOperateStatus;
    
        /**
    * @return 操作前的退款状态
    */
        public function getBeforeOperateStatus() {
        return $this->beforeOperateStatus;
    }
    
    /**
     * 设置操作前的退款状态     
     * @param String $beforeOperateStatus     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setBeforeOperateStatus( $beforeOperateStatus) {
        $this->beforeOperateStatus = $beforeOperateStatus;
    }
    
        	
    private $closeRefundStepId;
    
        /**
    * @return 分阶段订单正向操作关闭退款时的阶段ID
    */
        public function getCloseRefundStepId() {
        return $this->closeRefundStepId;
    }
    
    /**
     * 设置分阶段订单正向操作关闭退款时的阶段ID     
     * @param Long $closeRefundStepId     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setCloseRefundStepId( $closeRefundStepId) {
        $this->closeRefundStepId = $closeRefundStepId;
    }
    
        	
    private $crmModifyRefund;
    
        /**
    * @return 是否小二修改过退款单
    */
        public function getCrmModifyRefund() {
        return $this->crmModifyRefund;
    }
    
    /**
     * 设置是否小二修改过退款单     
     * @param Boolean $crmModifyRefund     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setCrmModifyRefund( $crmModifyRefund) {
        $this->crmModifyRefund = $crmModifyRefund;
    }
    
        	
    private $discription;
    
        /**
    * @return 描述、说明
    */
        public function getDiscription() {
        return $this->discription;
    }
    
    /**
     * 设置描述、说明     
     * @param String $discription     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setDiscription( $discription) {
        $this->discription = $discription;
    }
    
        	
    private $email;
    
        /**
    * @return 联系人EMAIL
    */
        public function getEmail() {
        return $this->email;
    }
    
    /**
     * 设置联系人EMAIL     
     * @param String $email     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setEmail( $email) {
        $this->email = $email;
    }
    
        	
    private $freightBill;
    
        /**
    * @return 运单号
    */
        public function getFreightBill() {
        return $this->freightBill;
    }
    
    /**
     * 设置运单号     
     * @param String $freightBill     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setFreightBill( $freightBill) {
        $this->freightBill = $freightBill;
    }
    
        	
    private $gmtCreate;
    
        /**
    * @return 创建时间
    */
        public function getGmtCreate() {
        return $this->gmtCreate;
    }
    
    /**
     * 设置创建时间     
     * @param Date $gmtCreate     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setGmtCreate( $gmtCreate) {
        $this->gmtCreate = $gmtCreate;
    }
    
        	
    private $gmtModified;
    
        /**
    * @return 修改时间
    */
        public function getGmtModified() {
        return $this->gmtModified;
    }
    
    /**
     * 设置修改时间     
     * @param Date $gmtModified     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setGmtModified( $gmtModified) {
        $this->gmtModified = $gmtModified;
    }
    
        	
    private $id;
    
        /**
    * @return 主键，退款操作记录流水号
    */
        public function getId() {
        return $this->id;
    }
    
    /**
     * 设置主键，退款操作记录流水号     
     * @param Long $id     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setId( $id) {
        $this->id = $id;
    }
    
        	
    private $messageStatus;
    
        /**
    * @return 凭证状态，1:正常 2:后台小二屏蔽
    */
        public function getMessageStatus() {
        return $this->messageStatus;
    }
    
    /**
     * 设置凭证状态，1:正常 2:后台小二屏蔽     
     * @param Integer $messageStatus     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setMessageStatus( $messageStatus) {
        $this->messageStatus = $messageStatus;
    }
    
        	
    private $mobile;
    
        /**
    * @return 联系人手机
    */
        public function getMobile() {
        return $this->mobile;
    }
    
    /**
     * 设置联系人手机     
     * @param String $mobile     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setMobile( $mobile) {
        $this->mobile = $mobile;
    }
    
        	
    private $msgType;
    
        /**
    * @return 留言类型 3:小二留言给买家和卖家 4:给买家的留言 5:给卖家的留言 7:cbu的普通留言等同于淘宝的1
    */
        public function getMsgType() {
        return $this->msgType;
    }
    
    /**
     * 设置留言类型 3:小二留言给买家和卖家 4:给买家的留言 5:给卖家的留言 7:cbu的普通留言等同于淘宝的1     
     * @param Integer $msgType     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setMsgType( $msgType) {
        $this->msgType = $msgType;
    }
    
        	
    private $operateRemark;
    
        /**
    * @return 操作备注
    */
        public function getOperateRemark() {
        return $this->operateRemark;
    }
    
    /**
     * 设置操作备注     
     * @param String $operateRemark     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setOperateRemark( $operateRemark) {
        $this->operateRemark = $operateRemark;
    }
    
        	
    private $operateTypeInt;
    
        /**
    * @return 操作类型 取代operateType
    */
        public function getOperateTypeInt() {
        return $this->operateTypeInt;
    }
    
    /**
     * 设置操作类型 取代operateType     
     * @param Integer $operateTypeInt     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setOperateTypeInt( $operateTypeInt) {
        $this->operateTypeInt = $operateTypeInt;
    }
    
        	
    private $operatorId;
    
        /**
    * @return 操作者-memberID
    */
        public function getOperatorId() {
        return $this->operatorId;
    }
    
    /**
     * 设置操作者-memberID     
     * @param String $operatorId     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setOperatorId( $operatorId) {
        $this->operatorId = $operatorId;
    }
    
        	
    private $operatorLoginId;
    
        /**
    * @return 操作者-loginID
    */
        public function getOperatorLoginId() {
        return $this->operatorLoginId;
    }
    
    /**
     * 设置操作者-loginID     
     * @param String $operatorLoginId     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setOperatorLoginId( $operatorLoginId) {
        $this->operatorLoginId = $operatorLoginId;
    }
    
        	
    private $operatorRoleId;
    
        /**
    * @return 操作者角色名称 买家 卖家 系统
    */
        public function getOperatorRoleId() {
        return $this->operatorRoleId;
    }
    
    /**
     * 设置操作者角色名称 买家 卖家 系统     
     * @param Integer $operatorRoleId     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setOperatorRoleId( $operatorRoleId) {
        $this->operatorRoleId = $operatorRoleId;
    }
    
        	
    private $operatorUserId;
    
        /**
    * @return 操作者-userID
    */
        public function getOperatorUserId() {
        return $this->operatorUserId;
    }
    
    /**
     * 设置操作者-userID     
     * @param Long $operatorUserId     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setOperatorUserId( $operatorUserId) {
        $this->operatorUserId = $operatorUserId;
    }
    
        	
    private $phone;
    
        /**
    * @return 联系人电话
    */
        public function getPhone() {
        return $this->phone;
    }
    
    /**
     * 设置联系人电话     
     * @param String $phone     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setPhone( $phone) {
        $this->phone = $phone;
    }
    
        	
    private $refundAddress;
    
        /**
    * @return 退货地址
    */
        public function getRefundAddress() {
        return $this->refundAddress;
    }
    
    /**
     * 设置退货地址     
     * @param String $refundAddress     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setRefundAddress( $refundAddress) {
        $this->refundAddress = $refundAddress;
    }
    
        	
    private $refundId;
    
        /**
    * @return 退款记录ID
    */
        public function getRefundId() {
        return $this->refundId;
    }
    
    /**
     * 设置退款记录ID     
     * @param String $refundId     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setRefundId( $refundId) {
        $this->refundId = $refundId;
    }
    
        	
    private $rejectReason;
    
        /**
    * @return 卖家拒绝退款原因
    */
        public function getRejectReason() {
        return $this->rejectReason;
    }
    
    /**
     * 设置卖家拒绝退款原因     
     * @param String $rejectReason     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setRejectReason( $rejectReason) {
        $this->rejectReason = $rejectReason;
    }
    
        	
    private $vouchers;
    
        /**
    * @return 凭证图片地址
    */
        public function getVouchers() {
        return $this->vouchers;
    }
    
    /**
     * 设置凭证图片地址     
     * @param List $vouchers     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setVouchers(array $vouchers) {
        $this->vouchers = $vouchers;
    }
    
        	
    private $logisticsCompany;
    
        /**
    * @return 物流公司详情
    */
        public function getLogisticsCompany() {
        return $this->logisticsCompany;
    }
    
    /**
     * 设置物流公司详情     
     * @param AlibabaTradeRefundOpLogisticsCompanyModel $logisticsCompany     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setLogisticsCompany(AlibabaTradeRefundOpLogisticsCompanyModel $logisticsCompany) {
        $this->logisticsCompany = $logisticsCompany;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "afterOperateStatus", $this->stdResult )) {
    				$this->afterOperateStatus = $this->stdResult->{"afterOperateStatus"};
    			}
    			    		    				    			    			if (array_key_exists ( "beforeOperateStatus", $this->stdResult )) {
    				$this->beforeOperateStatus = $this->stdResult->{"beforeOperateStatus"};
    			}
    			    		    				    			    			if (array_key_exists ( "closeRefundStepId", $this->stdResult )) {
    				$this->closeRefundStepId = $this->stdResult->{"closeRefundStepId"};
    			}
    			    		    				    			    			if (array_key_exists ( "crmModifyRefund", $this->stdResult )) {
    				$this->crmModifyRefund = $this->stdResult->{"crmModifyRefund"};
    			}
    			    		    				    			    			if (array_key_exists ( "discription", $this->stdResult )) {
    				$this->discription = $this->stdResult->{"discription"};
    			}
    			    		    				    			    			if (array_key_exists ( "email", $this->stdResult )) {
    				$this->email = $this->stdResult->{"email"};
    			}
    			    		    				    			    			if (array_key_exists ( "freightBill", $this->stdResult )) {
    				$this->freightBill = $this->stdResult->{"freightBill"};
    			}
    			    		    				    			    			if (array_key_exists ( "gmtCreate", $this->stdResult )) {
    				$this->gmtCreate = $this->stdResult->{"gmtCreate"};
    			}
    			    		    				    			    			if (array_key_exists ( "gmtModified", $this->stdResult )) {
    				$this->gmtModified = $this->stdResult->{"gmtModified"};
    			}
    			    		    				    			    			if (array_key_exists ( "id", $this->stdResult )) {
    				$this->id = $this->stdResult->{"id"};
    			}
    			    		    				    			    			if (array_key_exists ( "messageStatus", $this->stdResult )) {
    				$this->messageStatus = $this->stdResult->{"messageStatus"};
    			}
    			    		    				    			    			if (array_key_exists ( "mobile", $this->stdResult )) {
    				$this->mobile = $this->stdResult->{"mobile"};
    			}
    			    		    				    			    			if (array_key_exists ( "msgType", $this->stdResult )) {
    				$this->msgType = $this->stdResult->{"msgType"};
    			}
    			    		    				    			    			if (array_key_exists ( "operateRemark", $this->stdResult )) {
    				$this->operateRemark = $this->stdResult->{"operateRemark"};
    			}
    			    		    				    			    			if (array_key_exists ( "operateTypeInt", $this->stdResult )) {
    				$this->operateTypeInt = $this->stdResult->{"operateTypeInt"};
    			}
    			    		    				    			    			if (array_key_exists ( "operatorId", $this->stdResult )) {
    				$this->operatorId = $this->stdResult->{"operatorId"};
    			}
    			    		    				    			    			if (array_key_exists ( "operatorLoginId", $this->stdResult )) {
    				$this->operatorLoginId = $this->stdResult->{"operatorLoginId"};
    			}
    			    		    				    			    			if (array_key_exists ( "operatorRoleId", $this->stdResult )) {
    				$this->operatorRoleId = $this->stdResult->{"operatorRoleId"};
    			}
    			    		    				    			    			if (array_key_exists ( "operatorUserId", $this->stdResult )) {
    				$this->operatorUserId = $this->stdResult->{"operatorUserId"};
    			}
    			    		    				    			    			if (array_key_exists ( "phone", $this->stdResult )) {
    				$this->phone = $this->stdResult->{"phone"};
    			}
    			    		    				    			    			if (array_key_exists ( "refundAddress", $this->stdResult )) {
    				$this->refundAddress = $this->stdResult->{"refundAddress"};
    			}
    			    		    				    			    			if (array_key_exists ( "refundId", $this->stdResult )) {
    				$this->refundId = $this->stdResult->{"refundId"};
    			}
    			    		    				    			    			if (array_key_exists ( "rejectReason", $this->stdResult )) {
    				$this->rejectReason = $this->stdResult->{"rejectReason"};
    			}
    			    		    				    			    			if (array_key_exists ( "vouchers", $this->stdResult )) {
    				$this->vouchers = $this->stdResult->{"vouchers"};
    			}
    			    		    				    			    			if (array_key_exists ( "logisticsCompany", $this->stdResult )) {
    				$logisticsCompanyResult=$this->stdResult->{"logisticsCompany"};
    				$this->logisticsCompany = new AlibabaTradeRefundOpLogisticsCompanyModel();
    				$this->logisticsCompany->setStdResult ( $logisticsCompanyResult);
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    			if (array_key_exists ( "afterOperateStatus", $this->arrayResult )) {
    			$this->afterOperateStatus = $arrayResult['afterOperateStatus'];
    			}
    		    	    			    		    			if (array_key_exists ( "beforeOperateStatus", $this->arrayResult )) {
    			$this->beforeOperateStatus = $arrayResult['beforeOperateStatus'];
    			}
    		    	    			    		    			if (array_key_exists ( "closeRefundStepId", $this->arrayResult )) {
    			$this->closeRefundStepId = $arrayResult['closeRefundStepId'];
    			}
    		    	    			    		    			if (array_key_exists ( "crmModifyRefund", $this->arrayResult )) {
    			$this->crmModifyRefund = $arrayResult['crmModifyRefund'];
    			}
    		    	    			    		    			if (array_key_exists ( "discription", $this->arrayResult )) {
    			$this->discription = $arrayResult['discription'];
    			}
    		    	    			    		    			if (array_key_exists ( "email", $this->arrayResult )) {
    			$this->email = $arrayResult['email'];
    			}
    		    	    			    		    			if (array_key_exists ( "freightBill", $this->arrayResult )) {
    			$this->freightBill = $arrayResult['freightBill'];
    			}
    		    	    			    		    			if (array_key_exists ( "gmtCreate", $this->arrayResult )) {
    			$this->gmtCreate = $arrayResult['gmtCreate'];
    			}
    		    	    			    		    			if (array_key_exists ( "gmtModified", $this->arrayResult )) {
    			$this->gmtModified = $arrayResult['gmtModified'];
    			}
    		    	    			    		    			if (array_key_exists ( "id", $this->arrayResult )) {
    			$this->id = $arrayResult['id'];
    			}
    		    	    			    		    			if (array_key_exists ( "messageStatus", $this->arrayResult )) {
    			$this->messageStatus = $arrayResult['messageStatus'];
    			}
    		    	    			    		    			if (array_key_exists ( "mobile", $this->arrayResult )) {
    			$this->mobile = $arrayResult['mobile'];
    			}
    		    	    			    		    			if (array_key_exists ( "msgType", $this->arrayResult )) {
    			$this->msgType = $arrayResult['msgType'];
    			}
    		    	    			    		    			if (array_key_exists ( "operateRemark", $this->arrayResult )) {
    			$this->operateRemark = $arrayResult['operateRemark'];
    			}
    		    	    			    		    			if (array_key_exists ( "operateTypeInt", $this->arrayResult )) {
    			$this->operateTypeInt = $arrayResult['operateTypeInt'];
    			}
    		    	    			    		    			if (array_key_exists ( "operatorId", $this->arrayResult )) {
    			$this->operatorId = $arrayResult['operatorId'];
    			}
    		    	    			    		    			if (array_key_exists ( "operatorLoginId", $this->arrayResult )) {
    			$this->operatorLoginId = $arrayResult['operatorLoginId'];
    			}
    		    	    			    		    			if (array_key_exists ( "operatorRoleId", $this->arrayResult )) {
    			$this->operatorRoleId = $arrayResult['operatorRoleId'];
    			}
    		    	    			    		    			if (array_key_exists ( "operatorUserId", $this->arrayResult )) {
    			$this->operatorUserId = $arrayResult['operatorUserId'];
    			}
    		    	    			    		    			if (array_key_exists ( "phone", $this->arrayResult )) {
    			$this->phone = $arrayResult['phone'];
    			}
    		    	    			    		    			if (array_key_exists ( "refundAddress", $this->arrayResult )) {
    			$this->refundAddress = $arrayResult['refundAddress'];
    			}
    		    	    			    		    			if (array_key_exists ( "refundId", $this->arrayResult )) {
    			$this->refundId = $arrayResult['refundId'];
    			}
    		    	    			    		    			if (array_key_exists ( "rejectReason", $this->arrayResult )) {
    			$this->rejectReason = $arrayResult['rejectReason'];
    			}
    		    	    			    		    			if (array_key_exists ( "vouchers", $this->arrayResult )) {
    			$this->vouchers = $arrayResult['vouchers'];
    			}
    		    	    			    		    		if (array_key_exists ( "logisticsCompany", $this->arrayResult )) {
    		$logisticsCompanyResult=$arrayResult['logisticsCompany'];
    			    			$this->logisticsCompany = new AlibabaTradeRefundOpLogisticsCompanyModel();
    			    			$this->logisticsCompany->setStdResult ( $logisticsCompanyResult);
    		}
    		    	    		}
 
   
}
?>