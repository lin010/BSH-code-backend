<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');
include_once ('AlibabaTradeGetBuyerViewParam/AlibabaTradeTradeContact.class.php');
include_once ('AlibabaTradeGetBuyerViewParam/AlibabaTradeOrderReceiverInfo.class.php');
include_once ('AlibabaTradeGetBuyerViewParam/AlibabaTradeStepOrderModel.class.php');
include_once ('AlibabaTradeGetBuyerViewParam/AlibabaTradeTradeSellerContact.class.php');
include_once ('AlibabaTradeGetBuyerViewParam/AlibabaTradeBizNewStepOrderModel.class.php');

class AlibabaOpenplatformTradeModelOrderBaseInfo extends SDKDomain {

       	
    private $allDeliveredTime;
    
        /**
    * @return 完全发货时间
    */
        public function getAllDeliveredTime() {
        return $this->allDeliveredTime;
    }
    
    /**
     * 设置完全发货时间     
     * @param Date $allDeliveredTime     
     * 参数示例：<pre>20180614101942000+0800</pre>     
     * 此参数必填     */
    public function setAllDeliveredTime( $allDeliveredTime) {
        $this->allDeliveredTime = $allDeliveredTime;
    }
    
        	
    private $businessType;
    
        /**
    * @return 业务类型。国际站：ta(信保),wholesale(在线批发)。
中文站：普通订单类型 = "cn";
大额批发订单类型 = "ws";
普通拿样订单类型 = "yp";
一分钱拿样订单类型 = "yf";
倒批(限时折扣)订单类型 = "fs";
加工定制订单类型 = "cz";
协议采购订单类型 = "ag";
伙拼订单类型 = "hp";
供销订单类型 = "supply";
淘工厂订单 = "factory";
快订下单  = "quick";
享拼订单  = "xiangpin";
当面付 = "f2f";
存样服务 = "cyfw";
代销订单 = "sp";
微供订单 = "wg";零售通 = "lst";跨境='cb';分销='distribution';采源宝='cab';加工定制="manufact"
    */
        public function getBusinessType() {
        return $this->businessType;
    }
    
    /**
     * 设置业务类型。国际站：ta(信保),wholesale(在线批发)。
中文站：普通订单类型 = "cn";
大额批发订单类型 = "ws";
普通拿样订单类型 = "yp";
一分钱拿样订单类型 = "yf";
倒批(限时折扣)订单类型 = "fs";
加工定制订单类型 = "cz";
协议采购订单类型 = "ag";
伙拼订单类型 = "hp";
供销订单类型 = "supply";
淘工厂订单 = "factory";
快订下单  = "quick";
享拼订单  = "xiangpin";
当面付 = "f2f";
存样服务 = "cyfw";
代销订单 = "sp";
微供订单 = "wg";零售通 = "lst";跨境='cb';分销='distribution';采源宝='cab';加工定制="manufact"     
     * @param String $businessType     
     * 参数示例：<pre>cn</pre>     
     * 此参数必填     */
    public function setBusinessType( $businessType) {
        $this->businessType = $businessType;
    }
    
        	
    private $buyerID;
    
        /**
    * @return 买家主账号id
    */
        public function getBuyerID() {
        return $this->buyerID;
    }
    
    /**
     * 设置买家主账号id     
     * @param String $buyerID     
     * 参数示例：<pre>1234531</pre>     
     * 此参数必填     */
    public function setBuyerID( $buyerID) {
        $this->buyerID = $buyerID;
    }
    
        	
    private $buyerMemo;
    
        /**
    * @return 买家备忘信息
    */
        public function getBuyerMemo() {
        return $this->buyerMemo;
    }
    
    /**
     * 设置买家备忘信息     
     * @param String $buyerMemo     
     * 参数示例：<pre>备忘</pre>     
     * 此参数必填     */
    public function setBuyerMemo( $buyerMemo) {
        $this->buyerMemo = $buyerMemo;
    }
    
        	
    private $completeTime;
    
        /**
    * @return 完成时间
    */
        public function getCompleteTime() {
        return $this->completeTime;
    }
    
    /**
     * 设置完成时间     
     * @param Date $completeTime     
     * 参数示例：<pre>20180614101942000+0800</pre>     
     * 此参数必填     */
    public function setCompleteTime( $completeTime) {
        $this->completeTime = $completeTime;
    }
    
        	
    private $createTime;
    
        /**
    * @return 创建时间
    */
        public function getCreateTime() {
        return $this->createTime;
    }
    
    /**
     * 设置创建时间     
     * @param Date $createTime     
     * 参数示例：<pre>20180614101942000+0800</pre>     
     * 此参数必填     */
    public function setCreateTime( $createTime) {
        $this->createTime = $createTime;
    }
    
        	
    private $currency;
    
        /**
    * @return 币种，币种，整个交易单使用同一个币种。值范围：USD,RMB,HKD,GBP,CAD,AUD,JPY,KRW,EUR
    */
        public function getCurrency() {
        return $this->currency;
    }
    
    /**
     * 设置币种，币种，整个交易单使用同一个币种。值范围：USD,RMB,HKD,GBP,CAD,AUD,JPY,KRW,EUR     
     * @param String $currency     
     * 参数示例：<pre>EUR</pre>     
     * 此参数必填     */
    public function setCurrency( $currency) {
        $this->currency = $currency;
    }
    
        	
    private $id;
    
        /**
    * @return 交易id
    */
        public function getId() {
        return $this->id;
    }
    
    /**
     * 设置交易id     
     * @param Long $id     
     * 参数示例：<pre>1231231231111</pre>     
     * 此参数必填     */
    public function setId( $id) {
        $this->id = $id;
    }
    
        	
    private $modifyTime;
    
        /**
    * @return 修改时间
    */
        public function getModifyTime() {
        return $this->modifyTime;
    }
    
    /**
     * 设置修改时间     
     * @param Date $modifyTime     
     * 参数示例：<pre>20180614101942000+0800</pre>     
     * 此参数必填     */
    public function setModifyTime( $modifyTime) {
        $this->modifyTime = $modifyTime;
    }
    
        	
    private $payTime;
    
        /**
    * @return 付款时间，如果有多次付款，这里返回的是首次付款时间
    */
        public function getPayTime() {
        return $this->payTime;
    }
    
    /**
     * 设置付款时间，如果有多次付款，这里返回的是首次付款时间     
     * @param Date $payTime     
     * 参数示例：<pre>20180614101942000+0800</pre>     
     * 此参数必填     */
    public function setPayTime( $payTime) {
        $this->payTime = $payTime;
    }
    
        	
    private $receivingTime;
    
        /**
    * @return 收货时间，这里返回的是完全收货时间
    */
        public function getReceivingTime() {
        return $this->receivingTime;
    }
    
    /**
     * 设置收货时间，这里返回的是完全收货时间     
     * @param Date $receivingTime     
     * 参数示例：<pre>20180614101942000+0800</pre>     
     * 此参数必填     */
    public function setReceivingTime( $receivingTime) {
        $this->receivingTime = $receivingTime;
    }
    
        	
    private $refund;
    
        /**
    * @return 退款金额，单位为元
    */
        public function getRefund() {
        return $this->refund;
    }
    
    /**
     * 设置退款金额，单位为元     
     * @param BigDecimal $refund     
     * 参数示例：<pre>1</pre>     
     * 此参数必填     */
    public function setRefund( $refund) {
        $this->refund = $refund;
    }
    
        	
    private $remark;
    
        /**
    * @return 备注，1688指下单时的备注
    */
        public function getRemark() {
        return $this->remark;
    }
    
    /**
     * 设置备注，1688指下单时的备注     
     * @param String $remark     
     * 参数示例：<pre>备注</pre>     
     * 此参数必填     */
    public function setRemark( $remark) {
        $this->remark = $remark;
    }
    
        	
    private $sellerID;
    
        /**
    * @return 卖家主账号id
    */
        public function getSellerID() {
        return $this->sellerID;
    }
    
    /**
     * 设置卖家主账号id     
     * @param String $sellerID     
     * 参数示例：<pre>123123123123</pre>     
     * 此参数必填     */
    public function setSellerID( $sellerID) {
        $this->sellerID = $sellerID;
    }
    
        	
    private $sellerMemo;
    
        /**
    * @return 卖家备忘信息
    */
        public function getSellerMemo() {
        return $this->sellerMemo;
    }
    
    /**
     * 设置卖家备忘信息     
     * @param String $sellerMemo     
     * 参数示例：<pre>备忘</pre>     
     * 此参数必填     */
    public function setSellerMemo( $sellerMemo) {
        $this->sellerMemo = $sellerMemo;
    }
    
        	
    private $shippingFee;
    
        /**
    * @return 运费，单位为元
    */
        public function getShippingFee() {
        return $this->shippingFee;
    }
    
    /**
     * 设置运费，单位为元     
     * @param BigDecimal $shippingFee     
     * 参数示例：<pre>1</pre>     
     * 此参数必填     */
    public function setShippingFee( $shippingFee) {
        $this->shippingFee = $shippingFee;
    }
    
        	
    private $status;
    
        /**
    * @return 交易状态，waitbuyerpay:等待买家付款;waitsellersend:等待卖家发货;waitlogisticstakein:等待物流公司揽件;waitbuyerreceive:等待买家收货;waitbuyersign:等待买家签收;signinsuccess:买家已签收;confirm_goods:已收货;success:交易成功;cancel:交易取消;terminated:交易终止;未枚举:其他状态
    */
        public function getStatus() {
        return $this->status;
    }
    
    /**
     * 设置交易状态，waitbuyerpay:等待买家付款;waitsellersend:等待卖家发货;waitlogisticstakein:等待物流公司揽件;waitbuyerreceive:等待买家收货;waitbuyersign:等待买家签收;signinsuccess:买家已签收;confirm_goods:已收货;success:交易成功;cancel:交易取消;terminated:交易终止;未枚举:其他状态     
     * @param String $status     
     * 参数示例：<pre>waitbuyerpay</pre>     
     * 此参数必填     */
    public function setStatus( $status) {
        $this->status = $status;
    }
    
        	
    private $totalAmount;
    
        /**
    * @return 应付款总金额，totalAmount = ∑itemAmount + shippingFee，单位为元
    */
        public function getTotalAmount() {
        return $this->totalAmount;
    }
    
    /**
     * 设置应付款总金额，totalAmount = ∑itemAmount + shippingFee，单位为元     
     * @param BigDecimal $totalAmount     
     * 参数示例：<pre>1000</pre>     
     * 此参数必填     */
    public function setTotalAmount( $totalAmount) {
        $this->totalAmount = $totalAmount;
    }
    
        	
    private $buyerRemarkIcon;
    
        /**
    * @return 买家备忘标志
    */
        public function getBuyerRemarkIcon() {
        return $this->buyerRemarkIcon;
    }
    
    /**
     * 设置买家备忘标志     
     * @param String $buyerRemarkIcon     
     * 参数示例：<pre>1</pre>     
     * 此参数必填     */
    public function setBuyerRemarkIcon( $buyerRemarkIcon) {
        $this->buyerRemarkIcon = $buyerRemarkIcon;
    }
    
        	
    private $sellerRemarkIcon;
    
        /**
    * @return 卖家备忘标志
    */
        public function getSellerRemarkIcon() {
        return $this->sellerRemarkIcon;
    }
    
    /**
     * 设置卖家备忘标志     
     * @param String $sellerRemarkIcon     
     * 参数示例：<pre>1</pre>     
     * 此参数必填     */
    public function setSellerRemarkIcon( $sellerRemarkIcon) {
        $this->sellerRemarkIcon = $sellerRemarkIcon;
    }
    
        	
    private $discount;
    
        /**
    * @return 折扣信息，单位分
    */
        public function getDiscount() {
        return $this->discount;
    }
    
    /**
     * 设置折扣信息，单位分     
     * @param Long $discount     
     * 参数示例：<pre>11</pre>     
     * 此参数必填     */
    public function setDiscount( $discount) {
        $this->discount = $discount;
    }
    
        	
    private $buyerContact;
    
        /**
    * @return 买家联系人
    */
        public function getBuyerContact() {
        return $this->buyerContact;
    }
    
    /**
     * 设置买家联系人     
     * @param AlibabaTradeTradeContact $buyerContact     
     * 参数示例：<pre>{}</pre>     
     * 此参数必填     */
    public function setBuyerContact(AlibabaTradeTradeContact $buyerContact) {
        $this->buyerContact = $buyerContact;
    }
    
        	
    private $tradeType;
    
        /**
    * @return 1:担保交易
2:预存款交易
3:ETC境外收单交易
4:即时到帐交易
5:保障金安全交易
6:统一交易流程
7:分阶段付款
8.货到付款交易
9.信用凭证支付交易
10.账期支付交易，50060 交易4.0
    */
        public function getTradeType() {
        return $this->tradeType;
    }
    
    /**
     * 设置1:担保交易
2:预存款交易
3:ETC境外收单交易
4:即时到帐交易
5:保障金安全交易
6:统一交易流程
7:分阶段付款
8.货到付款交易
9.信用凭证支付交易
10.账期支付交易，50060 交易4.0     
     * @param String $tradeType     
     * 参数示例：<pre>50060</pre>     
     * 此参数必填     */
    public function setTradeType( $tradeType) {
        $this->tradeType = $tradeType;
    }
    
        	
    private $refundStatus;
    
        /**
    * @return 订单的售中退款状态，等待卖家同意：waitselleragree ，待买家修改：waitbuyermodify，等待买家退货：waitbuyersend，等待卖家确认收货：waitsellerreceive，退款成功：refundsuccess，退款失败：refundclose
    */
        public function getRefundStatus() {
        return $this->refundStatus;
    }
    
    /**
     * 设置订单的售中退款状态，等待卖家同意：waitselleragree ，待买家修改：waitbuyermodify，等待买家退货：waitbuyersend，等待卖家确认收货：waitsellerreceive，退款成功：refundsuccess，退款失败：refundclose     
     * @param String $refundStatus     
     * 参数示例：<pre>refundclose</pre>     
     * 此参数必填     */
    public function setRefundStatus( $refundStatus) {
        $this->refundStatus = $refundStatus;
    }
    
        	
    private $refundStatusForAs;
    
        /**
    * @return 订单的售后退款状态
    */
        public function getRefundStatusForAs() {
        return $this->refundStatusForAs;
    }
    
    /**
     * 设置订单的售后退款状态     
     * @param String $refundStatusForAs     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setRefundStatusForAs( $refundStatusForAs) {
        $this->refundStatusForAs = $refundStatusForAs;
    }
    
        	
    private $refundPayment;
    
        /**
    * @return 退款金额
    */
        public function getRefundPayment() {
        return $this->refundPayment;
    }
    
    /**
     * 设置退款金额     
     * @param Long $refundPayment     
     * 参数示例：<pre>1</pre>     
     * 此参数必填     */
    public function setRefundPayment( $refundPayment) {
        $this->refundPayment = $refundPayment;
    }
    
        	
    private $idOfStr;
    
        /**
    * @return 交易id(字符串格式)
    */
        public function getIdOfStr() {
        return $this->idOfStr;
    }
    
    /**
     * 设置交易id(字符串格式)     
     * @param String $idOfStr     
     * 参数示例：<pre>123121212123</pre>     
     * 此参数必填     */
    public function setIdOfStr( $idOfStr) {
        $this->idOfStr = $idOfStr;
    }
    
        	
    private $alipayTradeId;
    
        /**
    * @return 外部支付交易Id
    */
        public function getAlipayTradeId() {
        return $this->alipayTradeId;
    }
    
    /**
     * 设置外部支付交易Id     
     * @param String $alipayTradeId     
     * 参数示例：<pre>123123121111</pre>     
     * 此参数必填     */
    public function setAlipayTradeId( $alipayTradeId) {
        $this->alipayTradeId = $alipayTradeId;
    }
    
        	
    private $receiverInfo;
    
        /**
    * @return 收件人信息
    */
        public function getReceiverInfo() {
        return $this->receiverInfo;
    }
    
    /**
     * 设置收件人信息     
     * @param AlibabaTradeOrderReceiverInfo $receiverInfo     
     * 参数示例：<pre>{}</pre>     
     * 此参数必填     */
    public function setReceiverInfo(AlibabaTradeOrderReceiverInfo $receiverInfo) {
        $this->receiverInfo = $receiverInfo;
    }
    
        	
    private $buyerLoginId;
    
        /**
    * @return 买家loginId，旺旺Id
    */
        public function getBuyerLoginId() {
        return $this->buyerLoginId;
    }
    
    /**
     * 设置买家loginId，旺旺Id     
     * @param String $buyerLoginId     
     * 参数示例：<pre>alitestforusv01</pre>     
     * 此参数必填     */
    public function setBuyerLoginId( $buyerLoginId) {
        $this->buyerLoginId = $buyerLoginId;
    }
    
        	
    private $sellerLoginId;
    
        /**
    * @return 卖家oginId，旺旺Id
    */
        public function getSellerLoginId() {
        return $this->sellerLoginId;
    }
    
    /**
     * 设置卖家oginId，旺旺Id     
     * @param String $sellerLoginId     
     * 参数示例：<pre>alitestforusv02</pre>     
     * 此参数必填     */
    public function setSellerLoginId( $sellerLoginId) {
        $this->sellerLoginId = $sellerLoginId;
    }
    
        	
    private $buyerUserId;
    
        /**
    * @return 买家数字id
    */
        public function getBuyerUserId() {
        return $this->buyerUserId;
    }
    
    /**
     * 设置买家数字id     
     * @param Long $buyerUserId     
     * 参数示例：<pre>12314144</pre>     
     * 此参数必填     */
    public function setBuyerUserId( $buyerUserId) {
        $this->buyerUserId = $buyerUserId;
    }
    
        	
    private $sellerUserId;
    
        /**
    * @return 卖家数字id
    */
        public function getSellerUserId() {
        return $this->sellerUserId;
    }
    
    /**
     * 设置卖家数字id     
     * @param Long $sellerUserId     
     * 参数示例：<pre>12312422</pre>     
     * 此参数必填     */
    public function setSellerUserId( $sellerUserId) {
        $this->sellerUserId = $sellerUserId;
    }
    
        	
    private $buyerAlipayId;
    
        /**
    * @return 买家支付宝id
    */
        public function getBuyerAlipayId() {
        return $this->buyerAlipayId;
    }
    
    /**
     * 设置买家支付宝id     
     * @param String $buyerAlipayId     
     * 参数示例：<pre>12312311233</pre>     
     * 此参数必填     */
    public function setBuyerAlipayId( $buyerAlipayId) {
        $this->buyerAlipayId = $buyerAlipayId;
    }
    
        	
    private $sellerAlipayId;
    
        /**
    * @return 卖家支付宝id
    */
        public function getSellerAlipayId() {
        return $this->sellerAlipayId;
    }
    
    /**
     * 设置卖家支付宝id     
     * @param String $sellerAlipayId     
     * 参数示例：<pre>12312311111</pre>     
     * 此参数必填     */
    public function setSellerAlipayId( $sellerAlipayId) {
        $this->sellerAlipayId = $sellerAlipayId;
    }
    
        	
    private $confirmedTime;
    
        /**
    * @return 确认时间
    */
        public function getConfirmedTime() {
        return $this->confirmedTime;
    }
    
    /**
     * 设置确认时间     
     * @param Date $confirmedTime     
     * 参数示例：<pre>20180614101942000+0800</pre>     
     * 此参数必填     */
    public function setConfirmedTime( $confirmedTime) {
        $this->confirmedTime = $confirmedTime;
    }
    
        	
    private $closeReason;
    
        /**
    * @return 关闭原因。buyerCancel:买家取消订单，sellerGoodsLack:卖家库存不足，other:其它
    */
        public function getCloseReason() {
        return $this->closeReason;
    }
    
    /**
     * 设置关闭原因。buyerCancel:买家取消订单，sellerGoodsLack:卖家库存不足，other:其它     
     * @param String $closeReason     
     * 参数示例：<pre>buyerCancel</pre>     
     * 此参数必填     */
    public function setCloseReason( $closeReason) {
        $this->closeReason = $closeReason;
    }
    
        	
    private $sumProductPayment;
    
        /**
    * @return 产品总金额(该订单产品明细表中的产品金额的和)，单位元
    */
        public function getSumProductPayment() {
        return $this->sumProductPayment;
    }
    
    /**
     * 设置产品总金额(该订单产品明细表中的产品金额的和)，单位元     
     * @param BigDecimal $sumProductPayment     
     * 参数示例：<pre>1212</pre>     
     * 此参数必填     */
    public function setSumProductPayment( $sumProductPayment) {
        $this->sumProductPayment = $sumProductPayment;
    }
    
        	
    private $stepOrderList;
    
        /**
    * @return [交易3.0]分阶段交易，分阶段订单list
    */
        public function getStepOrderList() {
        return $this->stepOrderList;
    }
    
    /**
     * 设置[交易3.0]分阶段交易，分阶段订单list     
     * @param array include @see AlibabaTradeStepOrderModel[] $stepOrderList     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setStepOrderList(AlibabaTradeStepOrderModel $stepOrderList) {
        $this->stepOrderList = $stepOrderList;
    }
    
        	
    private $stepAgreementPath;
    
        /**
    * @return 分阶段法务协议地址
    */
        public function getStepAgreementPath() {
        return $this->stepAgreementPath;
    }
    
    /**
     * 设置分阶段法务协议地址     
     * @param String $stepAgreementPath     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setStepAgreementPath( $stepAgreementPath) {
        $this->stepAgreementPath = $stepAgreementPath;
    }
    
        	
    private $stepPayAll;
    
        /**
    * @return 是否一次性付款
    */
        public function getStepPayAll() {
        return $this->stepPayAll;
    }
    
    /**
     * 设置是否一次性付款     
     * @param Boolean $stepPayAll     
     * 参数示例：<pre>false</pre>     
     * 此参数必填     */
    public function setStepPayAll( $stepPayAll) {
        $this->stepPayAll = $stepPayAll;
    }
    
        	
    private $buyerFeedback;
    
        /**
    * @return 买家留言，不超过500字
    */
        public function getBuyerFeedback() {
        return $this->buyerFeedback;
    }
    
    /**
     * 设置买家留言，不超过500字     
     * @param String $buyerFeedback     
     * 参数示例：<pre>留言</pre>     
     * 此参数必填     */
    public function setBuyerFeedback( $buyerFeedback) {
        $this->buyerFeedback = $buyerFeedback;
    }
    
        	
    private $overSeaOrder;
    
        /**
    * @return 是否海外代发订单，是：true
    */
        public function getOverSeaOrder() {
        return $this->overSeaOrder;
    }
    
    /**
     * 设置是否海外代发订单，是：true     
     * @param Boolean $overSeaOrder     
     * 参数示例：<pre>true</pre>     
     * 此参数必填     */
    public function setOverSeaOrder( $overSeaOrder) {
        $this->overSeaOrder = $overSeaOrder;
    }
    
        	
    private $subBuyerLoginId;
    
        /**
    * @return 买家子账号
    */
        public function getSubBuyerLoginId() {
        return $this->subBuyerLoginId;
    }
    
    /**
     * 设置买家子账号     
     * @param String $subBuyerLoginId     
     * 参数示例：<pre>alitestforusv02:temp</pre>     
     * 此参数必填     */
    public function setSubBuyerLoginId( $subBuyerLoginId) {
        $this->subBuyerLoginId = $subBuyerLoginId;
    }
    
        	
    private $sellerOrder;
    
        /**
    * @return 是否自主订单（邀约订单）
    */
        public function getSellerOrder() {
        return $this->sellerOrder;
    }
    
    /**
     * 设置是否自主订单（邀约订单）     
     * @param Boolean $sellerOrder     
     * 参数示例：<pre>false</pre>     
     * 此参数必填     */
    public function setSellerOrder( $sellerOrder) {
        $this->sellerOrder = $sellerOrder;
    }
    
        	
    private $preOrderId;
    
        /**
    * @return 预订单ID
    */
        public function getPreOrderId() {
        return $this->preOrderId;
    }
    
    /**
     * 设置预订单ID     
     * @param Long $preOrderId     
     * 参数示例：<pre>123123</pre>     
     * 此参数必填     */
    public function setPreOrderId( $preOrderId) {
        $this->preOrderId = $preOrderId;
    }
    
        	
    private $refundId;
    
        /**
    * @return 退款单ID
    */
        public function getRefundId() {
        return $this->refundId;
    }
    
    /**
     * 设置退款单ID     
     * @param String $refundId     
     * 参数示例：<pre>TQ4562212313</pre>     
     * 此参数必填     */
    public function setRefundId( $refundId) {
        $this->refundId = $refundId;
    }
    
        	
    private $flowTemplateCode;
    
        /**
    * @return 4.0交易流程模板code
    */
        public function getFlowTemplateCode() {
        return $this->flowTemplateCode;
    }
    
    /**
     * 设置4.0交易流程模板code     
     * @param String $flowTemplateCode     
     * 参数示例：<pre>flow</pre>     
     * 此参数必填     */
    public function setFlowTemplateCode( $flowTemplateCode) {
        $this->flowTemplateCode = $flowTemplateCode;
    }
    
        	
    private $buyerLevel;
    
        /**
    * @return 买家等级
    */
        public function getBuyerLevel() {
        return $this->buyerLevel;
    }
    
    /**
     * 设置买家等级     
     * @param String $buyerLevel     
     * 参数示例：<pre>L1</pre>     
     * 此参数必填     */
    public function setBuyerLevel( $buyerLevel) {
        $this->buyerLevel = $buyerLevel;
    }
    
        	
    private $sellerCreditLevel;
    
        /**
    * @return 卖家诚信等级
    */
        public function getSellerCreditLevel() {
        return $this->sellerCreditLevel;
    }
    
    /**
     * 设置卖家诚信等级     
     * @param String $sellerCreditLevel     
     * 参数示例：<pre>L1</pre>     
     * 此参数必填     */
    public function setSellerCreditLevel( $sellerCreditLevel) {
        $this->sellerCreditLevel = $sellerCreditLevel;
    }
    
        	
    private $sellerContact;
    
        /**
    * @return 卖家联系人信息
    */
        public function getSellerContact() {
        return $this->sellerContact;
    }
    
    /**
     * 设置卖家联系人信息     
     * @param AlibabaTradeTradeSellerContact $sellerContact     
     * 参数示例：<pre>{}</pre>     
     * 此参数必填     */
    public function setSellerContact(AlibabaTradeTradeSellerContact $sellerContact) {
        $this->sellerContact = $sellerContact;
    }
    
        	
    private $newStepOrderList;
    
        /**
    * @return [交易4.0]分阶段交易，分阶段订单list
    */
        public function getNewStepOrderList() {
        return $this->newStepOrderList;
    }
    
    /**
     * 设置[交易4.0]分阶段交易，分阶段订单list     
     * @param array include @see AlibabaTradeBizNewStepOrderModel[] $newStepOrderList     
     * 参数示例：<pre>{}</pre>     
     * 此参数必填     */
    public function setNewStepOrderList(AlibabaTradeBizNewStepOrderModel $newStepOrderList) {
        $this->newStepOrderList = $newStepOrderList;
    }
    
        	
    private $closeRemark;
    
        /**
    * @return 关闭订单备注
    */
        public function getCloseRemark() {
        return $this->closeRemark;
    }
    
    /**
     * 设置关闭订单备注     
     * @param String $closeRemark     
     * 参数示例：<pre>备注</pre>     
     * 此参数必填     */
    public function setCloseRemark( $closeRemark) {
        $this->closeRemark = $closeRemark;
    }
    
        	
    private $closeOperateType;
    
        /**
    * @return 关闭订单操作类型。CLOSE_TRADE_BY_SELLER:卖家关闭交易,CLOSE_TRADE_BY_BOPS:BOPS后台关闭交易,CLOSE_TRADE_BY_SYSTEM:系统（超时）关闭交易,CLOSE_TRADE_BY_BUYER:买家关闭交易,CLOSE_TRADE_BY_CREADIT:诚信保障投诉关闭
    */
        public function getCloseOperateType() {
        return $this->closeOperateType;
    }
    
    /**
     * 设置关闭订单操作类型。CLOSE_TRADE_BY_SELLER:卖家关闭交易,CLOSE_TRADE_BY_BOPS:BOPS后台关闭交易,CLOSE_TRADE_BY_SYSTEM:系统（超时）关闭交易,CLOSE_TRADE_BY_BUYER:买家关闭交易,CLOSE_TRADE_BY_CREADIT:诚信保障投诉关闭     
     * @param String $closeOperateType     
     * 参数示例：<pre>CLOSE_TRADE_BY_SELLER</pre>     
     * 此参数必填     */
    public function setCloseOperateType( $closeOperateType) {
        $this->closeOperateType = $closeOperateType;
    }
    
        	
    private $couponFee;
    
        /**
    * @return 金豆金额，实付金额（totalAmount）已经计算过金豆金额
    */
        public function getCouponFee() {
        return $this->couponFee;
    }
    
    /**
     * 设置金豆金额，实付金额（totalAmount）已经计算过金豆金额     
     * @param BigDecimal $couponFee     
     * 参数示例：<pre>7.5</pre>     
     * 此参数必填     */
    public function setCouponFee( $couponFee) {
        $this->couponFee = $couponFee;
    }
    
        	
    private $tradeTypeDesc;
    
        /**
    * @return 下单时指定的交易方式
    */
        public function getTradeTypeDesc() {
        return $this->tradeTypeDesc;
    }
    
    /**
     * 设置下单时指定的交易方式     
     * @param String $tradeTypeDesc     
     * 参数示例：<pre>担保交易</pre>     
     * 此参数必填     */
    public function setTradeTypeDesc( $tradeTypeDesc) {
        $this->tradeTypeDesc = $tradeTypeDesc;
    }
    
        	
    private $payChannelList;
    
        /**
    * @return 支付渠道名称列表。一笔订单可能存在多种支付渠道。枚举值：支付宝,网商银行信任付,诚e赊,对公转账,赊销宝,账期支付,合并支付渠道,支付平台,声明付款,网商电子银行承兑汇票,银行转账,跨境宝,金豆,其它
    */
        public function getPayChannelList() {
        return $this->payChannelList;
    }
    
    /**
     * 设置支付渠道名称列表。一笔订单可能存在多种支付渠道。枚举值：支付宝,网商银行信任付,诚e赊,对公转账,赊销宝,账期支付,合并支付渠道,支付平台,声明付款,网商电子银行承兑汇票,银行转账,跨境宝,金豆,其它     
     * @param array include @see String[] $payChannelList     
     * 参数示例：<pre>["支付宝","跨境宝","银行转账"]</pre>     
     * 此参数必填     */
    public function setPayChannelList( $payChannelList) {
        $this->payChannelList = $payChannelList;
    }
    
        	
    private $tradeTypeCode;
    
        /**
    * @return 下单时指定的交易方式tradeType
    */
        public function getTradeTypeCode() {
        return $this->tradeTypeCode;
    }
    
    /**
     * 设置下单时指定的交易方式tradeType     
     * @param String $tradeTypeCode     
     * 参数示例：<pre>assureTrade</pre>     
     * 此参数必填     */
    public function setTradeTypeCode( $tradeTypeCode) {
        $this->tradeTypeCode = $tradeTypeCode;
    }
    
        	
    private $payTimeout;
    
        /**
    * @return 支付超时时间，定长情况时单位：秒，目前都是定长
    */
        public function getPayTimeout() {
        return $this->payTimeout;
    }
    
    /**
     * 设置支付超时时间，定长情况时单位：秒，目前都是定长     
     * @param Long $payTimeout     
     * 参数示例：<pre>43200</pre>     
     * 此参数必填     */
    public function setPayTimeout( $payTimeout) {
        $this->payTimeout = $payTimeout;
    }
    
        	
    private $payTimeoutType;
    
        /**
    * @return 支付超时TYPE，0：定长，1：固定时间
    */
        public function getPayTimeoutType() {
        return $this->payTimeoutType;
    }
    
    /**
     * 设置支付超时TYPE，0：定长，1：固定时间     
     * @param Integer $payTimeoutType     
     * 参数示例：<pre>0</pre>     
     * 此参数必填     */
    public function setPayTimeoutType( $payTimeoutType) {
        $this->payTimeoutType = $payTimeoutType;
    }
    
        	
    private $payChannelCodeList;
    
        /**
    * @return 支付渠道
    */
        public function getPayChannelCodeList() {
        return $this->payChannelCodeList;
    }
    
    /**
     * 设置支付渠道     
     * @param array include @see String[] $payChannelCodeList     
     * 参数示例：<pre>["1"]</pre>     
     * 此参数必填     */
    public function setPayChannelCodeList( $payChannelCodeList) {
        $this->payChannelCodeList = $payChannelCodeList;
    }
    
        	
    private $ccid;
    
        /**
    * @return 联系人信息解密ID，用于电商平台联系人信息加密场景使用，非订单加密场景请勿使用。
    */
        public function getCcid() {
        return $this->ccid;
    }
    
    /**
     * 设置联系人信息解密ID，用于电商平台联系人信息加密场景使用，非订单加密场景请勿使用。     
     * @param String $ccid     
     * 参数示例：<pre>xxx</pre>     
     * 此参数必填     */
    public function setCcid( $ccid) {
        $this->ccid = $ccid;
    }
    
        	
    private $relatedCode;
    
        /**
    * @return 关联code
    */
        public function getRelatedCode() {
        return $this->relatedCode;
    }
    
    /**
     * 设置关联code     
     * @param String $relatedCode     
     * 参数示例：<pre>229195140003841187</pre>     
     * 此参数必填     */
    public function setRelatedCode( $relatedCode) {
        $this->relatedCode = $relatedCode;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "allDeliveredTime", $this->stdResult )) {
    				$this->allDeliveredTime = $this->stdResult->{"allDeliveredTime"};
    			}
    			    		    				    			    			if (array_key_exists ( "businessType", $this->stdResult )) {
    				$this->businessType = $this->stdResult->{"businessType"};
    			}
    			    		    				    			    			if (array_key_exists ( "buyerID", $this->stdResult )) {
    				$this->buyerID = $this->stdResult->{"buyerID"};
    			}
    			    		    				    			    			if (array_key_exists ( "buyerMemo", $this->stdResult )) {
    				$this->buyerMemo = $this->stdResult->{"buyerMemo"};
    			}
    			    		    				    			    			if (array_key_exists ( "completeTime", $this->stdResult )) {
    				$this->completeTime = $this->stdResult->{"completeTime"};
    			}
    			    		    				    			    			if (array_key_exists ( "createTime", $this->stdResult )) {
    				$this->createTime = $this->stdResult->{"createTime"};
    			}
    			    		    				    			    			if (array_key_exists ( "currency", $this->stdResult )) {
    				$this->currency = $this->stdResult->{"currency"};
    			}
    			    		    				    			    			if (array_key_exists ( "id", $this->stdResult )) {
    				$this->id = $this->stdResult->{"id"};
    			}
    			    		    				    			    			if (array_key_exists ( "modifyTime", $this->stdResult )) {
    				$this->modifyTime = $this->stdResult->{"modifyTime"};
    			}
    			    		    				    			    			if (array_key_exists ( "payTime", $this->stdResult )) {
    				$this->payTime = $this->stdResult->{"payTime"};
    			}
    			    		    				    			    			if (array_key_exists ( "receivingTime", $this->stdResult )) {
    				$this->receivingTime = $this->stdResult->{"receivingTime"};
    			}
    			    		    				    			    			if (array_key_exists ( "refund", $this->stdResult )) {
    				$this->refund = $this->stdResult->{"refund"};
    			}
    			    		    				    			    			if (array_key_exists ( "remark", $this->stdResult )) {
    				$this->remark = $this->stdResult->{"remark"};
    			}
    			    		    				    			    			if (array_key_exists ( "sellerID", $this->stdResult )) {
    				$this->sellerID = $this->stdResult->{"sellerID"};
    			}
    			    		    				    			    			if (array_key_exists ( "sellerMemo", $this->stdResult )) {
    				$this->sellerMemo = $this->stdResult->{"sellerMemo"};
    			}
    			    		    				    			    			if (array_key_exists ( "shippingFee", $this->stdResult )) {
    				$this->shippingFee = $this->stdResult->{"shippingFee"};
    			}
    			    		    				    			    			if (array_key_exists ( "status", $this->stdResult )) {
    				$this->status = $this->stdResult->{"status"};
    			}
    			    		    				    			    			if (array_key_exists ( "totalAmount", $this->stdResult )) {
    				$this->totalAmount = $this->stdResult->{"totalAmount"};
    			}
    			    		    				    			    			if (array_key_exists ( "buyerRemarkIcon", $this->stdResult )) {
    				$this->buyerRemarkIcon = $this->stdResult->{"buyerRemarkIcon"};
    			}
    			    		    				    			    			if (array_key_exists ( "sellerRemarkIcon", $this->stdResult )) {
    				$this->sellerRemarkIcon = $this->stdResult->{"sellerRemarkIcon"};
    			}
    			    		    				    			    			if (array_key_exists ( "discount", $this->stdResult )) {
    				$this->discount = $this->stdResult->{"discount"};
    			}
    			    		    				    			    			if (array_key_exists ( "buyerContact", $this->stdResult )) {
    				$buyerContactResult=$this->stdResult->{"buyerContact"};
    				$this->buyerContact = new AlibabaTradeTradeContact();
    				$this->buyerContact->setStdResult ( $buyerContactResult);
    			}
    			    		    				    			    			if (array_key_exists ( "tradeType", $this->stdResult )) {
    				$this->tradeType = $this->stdResult->{"tradeType"};
    			}
    			    		    				    			    			if (array_key_exists ( "refundStatus", $this->stdResult )) {
    				$this->refundStatus = $this->stdResult->{"refundStatus"};
    			}
    			    		    				    			    			if (array_key_exists ( "refundStatusForAs", $this->stdResult )) {
    				$this->refundStatusForAs = $this->stdResult->{"refundStatusForAs"};
    			}
    			    		    				    			    			if (array_key_exists ( "refundPayment", $this->stdResult )) {
    				$this->refundPayment = $this->stdResult->{"refundPayment"};
    			}
    			    		    				    			    			if (array_key_exists ( "idOfStr", $this->stdResult )) {
    				$this->idOfStr = $this->stdResult->{"idOfStr"};
    			}
    			    		    				    			    			if (array_key_exists ( "alipayTradeId", $this->stdResult )) {
    				$this->alipayTradeId = $this->stdResult->{"alipayTradeId"};
    			}
    			    		    				    			    			if (array_key_exists ( "receiverInfo", $this->stdResult )) {
    				$receiverInfoResult=$this->stdResult->{"receiverInfo"};
    				$this->receiverInfo = new AlibabaTradeOrderReceiverInfo();
    				$this->receiverInfo->setStdResult ( $receiverInfoResult);
    			}
    			    		    				    			    			if (array_key_exists ( "buyerLoginId", $this->stdResult )) {
    				$this->buyerLoginId = $this->stdResult->{"buyerLoginId"};
    			}
    			    		    				    			    			if (array_key_exists ( "sellerLoginId", $this->stdResult )) {
    				$this->sellerLoginId = $this->stdResult->{"sellerLoginId"};
    			}
    			    		    				    			    			if (array_key_exists ( "buyerUserId", $this->stdResult )) {
    				$this->buyerUserId = $this->stdResult->{"buyerUserId"};
    			}
    			    		    				    			    			if (array_key_exists ( "sellerUserId", $this->stdResult )) {
    				$this->sellerUserId = $this->stdResult->{"sellerUserId"};
    			}
    			    		    				    			    			if (array_key_exists ( "buyerAlipayId", $this->stdResult )) {
    				$this->buyerAlipayId = $this->stdResult->{"buyerAlipayId"};
    			}
    			    		    				    			    			if (array_key_exists ( "sellerAlipayId", $this->stdResult )) {
    				$this->sellerAlipayId = $this->stdResult->{"sellerAlipayId"};
    			}
    			    		    				    			    			if (array_key_exists ( "confirmedTime", $this->stdResult )) {
    				$this->confirmedTime = $this->stdResult->{"confirmedTime"};
    			}
    			    		    				    			    			if (array_key_exists ( "closeReason", $this->stdResult )) {
    				$this->closeReason = $this->stdResult->{"closeReason"};
    			}
    			    		    				    			    			if (array_key_exists ( "sumProductPayment", $this->stdResult )) {
    				$this->sumProductPayment = $this->stdResult->{"sumProductPayment"};
    			}
    			    		    				    			    			if (array_key_exists ( "stepOrderList", $this->stdResult )) {
    			$stepOrderListResult=$this->stdResult->{"stepOrderList"};
    				$object = json_decode ( json_encode ( $stepOrderListResult ), true );
					$this->stepOrderList = array ();
					for($i = 0; $i < count ( $object ); $i ++) {
						$arrayobject = new ArrayObject ( $object [$i] );
						$AlibabaTradeStepOrderModelResult=new AlibabaTradeStepOrderModel();
						$AlibabaTradeStepOrderModelResult->setArrayResult($arrayobject );
						$this->stepOrderList [$i] = $AlibabaTradeStepOrderModelResult;
					}
    			}
    			    		    				    			    			if (array_key_exists ( "stepAgreementPath", $this->stdResult )) {
    				$this->stepAgreementPath = $this->stdResult->{"stepAgreementPath"};
    			}
    			    		    				    			    			if (array_key_exists ( "stepPayAll", $this->stdResult )) {
    				$this->stepPayAll = $this->stdResult->{"stepPayAll"};
    			}
    			    		    				    			    			if (array_key_exists ( "buyerFeedback", $this->stdResult )) {
    				$this->buyerFeedback = $this->stdResult->{"buyerFeedback"};
    			}
    			    		    				    			    			if (array_key_exists ( "overSeaOrder", $this->stdResult )) {
    				$this->overSeaOrder = $this->stdResult->{"overSeaOrder"};
    			}
    			    		    				    			    			if (array_key_exists ( "subBuyerLoginId", $this->stdResult )) {
    				$this->subBuyerLoginId = $this->stdResult->{"subBuyerLoginId"};
    			}
    			    		    				    			    			if (array_key_exists ( "sellerOrder", $this->stdResult )) {
    				$this->sellerOrder = $this->stdResult->{"sellerOrder"};
    			}
    			    		    				    			    			if (array_key_exists ( "preOrderId", $this->stdResult )) {
    				$this->preOrderId = $this->stdResult->{"preOrderId"};
    			}
    			    		    				    			    			if (array_key_exists ( "refundId", $this->stdResult )) {
    				$this->refundId = $this->stdResult->{"refundId"};
    			}
    			    		    				    			    			if (array_key_exists ( "flowTemplateCode", $this->stdResult )) {
    				$this->flowTemplateCode = $this->stdResult->{"flowTemplateCode"};
    			}
    			    		    				    			    			if (array_key_exists ( "buyerLevel", $this->stdResult )) {
    				$this->buyerLevel = $this->stdResult->{"buyerLevel"};
    			}
    			    		    				    			    			if (array_key_exists ( "sellerCreditLevel", $this->stdResult )) {
    				$this->sellerCreditLevel = $this->stdResult->{"sellerCreditLevel"};
    			}
    			    		    				    			    			if (array_key_exists ( "sellerContact", $this->stdResult )) {
    				$sellerContactResult=$this->stdResult->{"sellerContact"};
    				$this->sellerContact = new AlibabaTradeTradeSellerContact();
    				$this->sellerContact->setStdResult ( $sellerContactResult);
    			}
    			    		    				    			    			if (array_key_exists ( "newStepOrderList", $this->stdResult )) {
    			$newStepOrderListResult=$this->stdResult->{"newStepOrderList"};
    				$object = json_decode ( json_encode ( $newStepOrderListResult ), true );
					$this->newStepOrderList = array ();
					for($i = 0; $i < count ( $object ); $i ++) {
						$arrayobject = new ArrayObject ( $object [$i] );
						$AlibabaTradeBizNewStepOrderModelResult=new AlibabaTradeBizNewStepOrderModel();
						$AlibabaTradeBizNewStepOrderModelResult->setArrayResult($arrayobject );
						$this->newStepOrderList [$i] = $AlibabaTradeBizNewStepOrderModelResult;
					}
    			}
    			    		    				    			    			if (array_key_exists ( "closeRemark", $this->stdResult )) {
    				$this->closeRemark = $this->stdResult->{"closeRemark"};
    			}
    			    		    				    			    			if (array_key_exists ( "closeOperateType", $this->stdResult )) {
    				$this->closeOperateType = $this->stdResult->{"closeOperateType"};
    			}
    			    		    				    			    			if (array_key_exists ( "couponFee", $this->stdResult )) {
    				$this->couponFee = $this->stdResult->{"couponFee"};
    			}
    			    		    				    			    			if (array_key_exists ( "tradeTypeDesc", $this->stdResult )) {
    				$this->tradeTypeDesc = $this->stdResult->{"tradeTypeDesc"};
    			}
    			    		    				    			    			if (array_key_exists ( "payChannelList", $this->stdResult )) {
    				$this->payChannelList = $this->stdResult->{"payChannelList"};
    			}
    			    		    				    			    			if (array_key_exists ( "tradeTypeCode", $this->stdResult )) {
    				$this->tradeTypeCode = $this->stdResult->{"tradeTypeCode"};
    			}
    			    		    				    			    			if (array_key_exists ( "payTimeout", $this->stdResult )) {
    				$this->payTimeout = $this->stdResult->{"payTimeout"};
    			}
    			    		    				    			    			if (array_key_exists ( "payTimeoutType", $this->stdResult )) {
    				$this->payTimeoutType = $this->stdResult->{"payTimeoutType"};
    			}
    			    		    				    			    			if (array_key_exists ( "payChannelCodeList", $this->stdResult )) {
    				$this->payChannelCodeList = $this->stdResult->{"payChannelCodeList"};
    			}
    			    		    				    			    			if (array_key_exists ( "ccid", $this->stdResult )) {
    				$this->ccid = $this->stdResult->{"ccid"};
    			}
    			    		    				    			    			if (array_key_exists ( "relatedCode", $this->stdResult )) {
    				$this->relatedCode = $this->stdResult->{"relatedCode"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    			if (array_key_exists ( "allDeliveredTime", $this->arrayResult )) {
    			$this->allDeliveredTime = $arrayResult['allDeliveredTime'];
    			}
    		    	    			    		    			if (array_key_exists ( "businessType", $this->arrayResult )) {
    			$this->businessType = $arrayResult['businessType'];
    			}
    		    	    			    		    			if (array_key_exists ( "buyerID", $this->arrayResult )) {
    			$this->buyerID = $arrayResult['buyerID'];
    			}
    		    	    			    		    			if (array_key_exists ( "buyerMemo", $this->arrayResult )) {
    			$this->buyerMemo = $arrayResult['buyerMemo'];
    			}
    		    	    			    		    			if (array_key_exists ( "completeTime", $this->arrayResult )) {
    			$this->completeTime = $arrayResult['completeTime'];
    			}
    		    	    			    		    			if (array_key_exists ( "createTime", $this->arrayResult )) {
    			$this->createTime = $arrayResult['createTime'];
    			}
    		    	    			    		    			if (array_key_exists ( "currency", $this->arrayResult )) {
    			$this->currency = $arrayResult['currency'];
    			}
    		    	    			    		    			if (array_key_exists ( "id", $this->arrayResult )) {
    			$this->id = $arrayResult['id'];
    			}
    		    	    			    		    			if (array_key_exists ( "modifyTime", $this->arrayResult )) {
    			$this->modifyTime = $arrayResult['modifyTime'];
    			}
    		    	    			    		    			if (array_key_exists ( "payTime", $this->arrayResult )) {
    			$this->payTime = $arrayResult['payTime'];
    			}
    		    	    			    		    			if (array_key_exists ( "receivingTime", $this->arrayResult )) {
    			$this->receivingTime = $arrayResult['receivingTime'];
    			}
    		    	    			    		    			if (array_key_exists ( "refund", $this->arrayResult )) {
    			$this->refund = $arrayResult['refund'];
    			}
    		    	    			    		    			if (array_key_exists ( "remark", $this->arrayResult )) {
    			$this->remark = $arrayResult['remark'];
    			}
    		    	    			    		    			if (array_key_exists ( "sellerID", $this->arrayResult )) {
    			$this->sellerID = $arrayResult['sellerID'];
    			}
    		    	    			    		    			if (array_key_exists ( "sellerMemo", $this->arrayResult )) {
    			$this->sellerMemo = $arrayResult['sellerMemo'];
    			}
    		    	    			    		    			if (array_key_exists ( "shippingFee", $this->arrayResult )) {
    			$this->shippingFee = $arrayResult['shippingFee'];
    			}
    		    	    			    		    			if (array_key_exists ( "status", $this->arrayResult )) {
    			$this->status = $arrayResult['status'];
    			}
    		    	    			    		    			if (array_key_exists ( "totalAmount", $this->arrayResult )) {
    			$this->totalAmount = $arrayResult['totalAmount'];
    			}
    		    	    			    		    			if (array_key_exists ( "buyerRemarkIcon", $this->arrayResult )) {
    			$this->buyerRemarkIcon = $arrayResult['buyerRemarkIcon'];
    			}
    		    	    			    		    			if (array_key_exists ( "sellerRemarkIcon", $this->arrayResult )) {
    			$this->sellerRemarkIcon = $arrayResult['sellerRemarkIcon'];
    			}
    		    	    			    		    			if (array_key_exists ( "discount", $this->arrayResult )) {
    			$this->discount = $arrayResult['discount'];
    			}
    		    	    			    		    		if (array_key_exists ( "buyerContact", $this->arrayResult )) {
    		$buyerContactResult=$arrayResult['buyerContact'];
    			    			$this->buyerContact = new AlibabaTradeTradeContact();
    			    			$this->buyerContact->setStdResult ( $buyerContactResult);
    		}
    		    	    			    		    			if (array_key_exists ( "tradeType", $this->arrayResult )) {
    			$this->tradeType = $arrayResult['tradeType'];
    			}
    		    	    			    		    			if (array_key_exists ( "refundStatus", $this->arrayResult )) {
    			$this->refundStatus = $arrayResult['refundStatus'];
    			}
    		    	    			    		    			if (array_key_exists ( "refundStatusForAs", $this->arrayResult )) {
    			$this->refundStatusForAs = $arrayResult['refundStatusForAs'];
    			}
    		    	    			    		    			if (array_key_exists ( "refundPayment", $this->arrayResult )) {
    			$this->refundPayment = $arrayResult['refundPayment'];
    			}
    		    	    			    		    			if (array_key_exists ( "idOfStr", $this->arrayResult )) {
    			$this->idOfStr = $arrayResult['idOfStr'];
    			}
    		    	    			    		    			if (array_key_exists ( "alipayTradeId", $this->arrayResult )) {
    			$this->alipayTradeId = $arrayResult['alipayTradeId'];
    			}
    		    	    			    		    		if (array_key_exists ( "receiverInfo", $this->arrayResult )) {
    		$receiverInfoResult=$arrayResult['receiverInfo'];
    			    			$this->receiverInfo = new AlibabaTradeOrderReceiverInfo();
    			    			$this->receiverInfo->setStdResult ( $receiverInfoResult);
    		}
    		    	    			    		    			if (array_key_exists ( "buyerLoginId", $this->arrayResult )) {
    			$this->buyerLoginId = $arrayResult['buyerLoginId'];
    			}
    		    	    			    		    			if (array_key_exists ( "sellerLoginId", $this->arrayResult )) {
    			$this->sellerLoginId = $arrayResult['sellerLoginId'];
    			}
    		    	    			    		    			if (array_key_exists ( "buyerUserId", $this->arrayResult )) {
    			$this->buyerUserId = $arrayResult['buyerUserId'];
    			}
    		    	    			    		    			if (array_key_exists ( "sellerUserId", $this->arrayResult )) {
    			$this->sellerUserId = $arrayResult['sellerUserId'];
    			}
    		    	    			    		    			if (array_key_exists ( "buyerAlipayId", $this->arrayResult )) {
    			$this->buyerAlipayId = $arrayResult['buyerAlipayId'];
    			}
    		    	    			    		    			if (array_key_exists ( "sellerAlipayId", $this->arrayResult )) {
    			$this->sellerAlipayId = $arrayResult['sellerAlipayId'];
    			}
    		    	    			    		    			if (array_key_exists ( "confirmedTime", $this->arrayResult )) {
    			$this->confirmedTime = $arrayResult['confirmedTime'];
    			}
    		    	    			    		    			if (array_key_exists ( "closeReason", $this->arrayResult )) {
    			$this->closeReason = $arrayResult['closeReason'];
    			}
    		    	    			    		    			if (array_key_exists ( "sumProductPayment", $this->arrayResult )) {
    			$this->sumProductPayment = $arrayResult['sumProductPayment'];
    			}
    		    	    			    		    		if (array_key_exists ( "stepOrderList", $this->arrayResult )) {
    		$stepOrderListResult=$arrayResult['stepOrderList'];
    			$this->stepOrderList = new AlibabaTradeStepOrderModel();
    			$this->stepOrderList->setStdResult ( $stepOrderListResult);
    		}
    		    	    			    		    			if (array_key_exists ( "stepAgreementPath", $this->arrayResult )) {
    			$this->stepAgreementPath = $arrayResult['stepAgreementPath'];
    			}
    		    	    			    		    			if (array_key_exists ( "stepPayAll", $this->arrayResult )) {
    			$this->stepPayAll = $arrayResult['stepPayAll'];
    			}
    		    	    			    		    			if (array_key_exists ( "buyerFeedback", $this->arrayResult )) {
    			$this->buyerFeedback = $arrayResult['buyerFeedback'];
    			}
    		    	    			    		    			if (array_key_exists ( "overSeaOrder", $this->arrayResult )) {
    			$this->overSeaOrder = $arrayResult['overSeaOrder'];
    			}
    		    	    			    		    			if (array_key_exists ( "subBuyerLoginId", $this->arrayResult )) {
    			$this->subBuyerLoginId = $arrayResult['subBuyerLoginId'];
    			}
    		    	    			    		    			if (array_key_exists ( "sellerOrder", $this->arrayResult )) {
    			$this->sellerOrder = $arrayResult['sellerOrder'];
    			}
    		    	    			    		    			if (array_key_exists ( "preOrderId", $this->arrayResult )) {
    			$this->preOrderId = $arrayResult['preOrderId'];
    			}
    		    	    			    		    			if (array_key_exists ( "refundId", $this->arrayResult )) {
    			$this->refundId = $arrayResult['refundId'];
    			}
    		    	    			    		    			if (array_key_exists ( "flowTemplateCode", $this->arrayResult )) {
    			$this->flowTemplateCode = $arrayResult['flowTemplateCode'];
    			}
    		    	    			    		    			if (array_key_exists ( "buyerLevel", $this->arrayResult )) {
    			$this->buyerLevel = $arrayResult['buyerLevel'];
    			}
    		    	    			    		    			if (array_key_exists ( "sellerCreditLevel", $this->arrayResult )) {
    			$this->sellerCreditLevel = $arrayResult['sellerCreditLevel'];
    			}
    		    	    			    		    		if (array_key_exists ( "sellerContact", $this->arrayResult )) {
    		$sellerContactResult=$arrayResult['sellerContact'];
    			    			$this->sellerContact = new AlibabaTradeTradeSellerContact();
    			    			$this->sellerContact->setStdResult ( $sellerContactResult);
    		}
    		    	    			    		    		if (array_key_exists ( "newStepOrderList", $this->arrayResult )) {
    		$newStepOrderListResult=$arrayResult['newStepOrderList'];
    			$this->newStepOrderList = new AlibabaTradeBizNewStepOrderModel();
    			$this->newStepOrderList->setStdResult ( $newStepOrderListResult);
    		}
    		    	    			    		    			if (array_key_exists ( "closeRemark", $this->arrayResult )) {
    			$this->closeRemark = $arrayResult['closeRemark'];
    			}
    		    	    			    		    			if (array_key_exists ( "closeOperateType", $this->arrayResult )) {
    			$this->closeOperateType = $arrayResult['closeOperateType'];
    			}
    		    	    			    		    			if (array_key_exists ( "couponFee", $this->arrayResult )) {
    			$this->couponFee = $arrayResult['couponFee'];
    			}
    		    	    			    		    			if (array_key_exists ( "tradeTypeDesc", $this->arrayResult )) {
    			$this->tradeTypeDesc = $arrayResult['tradeTypeDesc'];
    			}
    		    	    			    		    			if (array_key_exists ( "payChannelList", $this->arrayResult )) {
    			$this->payChannelList = $arrayResult['payChannelList'];
    			}
    		    	    			    		    			if (array_key_exists ( "tradeTypeCode", $this->arrayResult )) {
    			$this->tradeTypeCode = $arrayResult['tradeTypeCode'];
    			}
    		    	    			    		    			if (array_key_exists ( "payTimeout", $this->arrayResult )) {
    			$this->payTimeout = $arrayResult['payTimeout'];
    			}
    		    	    			    		    			if (array_key_exists ( "payTimeoutType", $this->arrayResult )) {
    			$this->payTimeoutType = $arrayResult['payTimeoutType'];
    			}
    		    	    			    		    			if (array_key_exists ( "payChannelCodeList", $this->arrayResult )) {
    			$this->payChannelCodeList = $arrayResult['payChannelCodeList'];
    			}
    		    	    			    		    			if (array_key_exists ( "ccid", $this->arrayResult )) {
    			$this->ccid = $arrayResult['ccid'];
    			}
    		    	    			    		    			if (array_key_exists ( "relatedCode", $this->arrayResult )) {
    			$this->relatedCode = $arrayResult['relatedCode'];
    			}
    		    	    		}
 
   
}
?>