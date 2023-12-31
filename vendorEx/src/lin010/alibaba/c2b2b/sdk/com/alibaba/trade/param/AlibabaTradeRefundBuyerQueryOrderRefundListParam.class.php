<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');

class AlibabaTradeRefundBuyerQueryOrderRefundListParam {

        
        /**
    * @return 订单Id
    */
        public function getOrderId() {
        $tempResult = $this->sdkStdResult["orderId"];
        return $tempResult;
    }
    
    /**
     * 设置订单Id     
     * @param Long $orderId     
     * 参数示例：<pre>179087886005498520</pre>     
     * 此参数必填     */
    public function setOrderId( $orderId) {
        $this->sdkStdResult["orderId"] = $orderId;
    }
    
        
        /**
    * @return 退款申请时间（起始）
    */
        public function getApplyStartTime() {
        $tempResult = $this->sdkStdResult["applyStartTime"];
        return $tempResult;
    }
    
    /**
     * 设置退款申请时间（起始）     
     * @param Date $applyStartTime     
     * 参数示例：<pre>20170926114526000+0800</pre>     
     * 此参数必填     */
    public function setApplyStartTime( $applyStartTime) {
        $this->sdkStdResult["applyStartTime"] = $applyStartTime;
    }
    
        
        /**
    * @return 退款申请时间（截止）
    */
        public function getApplyEndTime() {
        $tempResult = $this->sdkStdResult["applyEndTime"];
        return $tempResult;
    }
    
    /**
     * 设置退款申请时间（截止）     
     * @param Date $applyEndTime     
     * 参数示例：<pre>20220926114526000+0800</pre>     
     * 此参数必填     */
    public function setApplyEndTime( $applyEndTime) {
        $this->sdkStdResult["applyEndTime"] = $applyEndTime;
    }
    
        
        /**
    * @return 退款状态列表
    */
        public function getRefundStatusSet() {
        $tempResult = $this->sdkStdResult["refundStatusSet"];
        return $tempResult;
    }
    
    /**
     * 设置退款状态列表     
     * @param array include @see String[] $refundStatusSet     
     * 参数示例：<pre>等待卖家同意 waitselleragree;退款成功 refundsuccess;退款关闭 refundclose;待买家修改 waitbuyermodify;等待买家退货 waitbuyersend;等待卖家确认收货 waitsellerreceive</pre>     
     * 此参数必填     */
    public function setRefundStatusSet( $refundStatusSet) {
        $this->sdkStdResult["refundStatusSet"] = $refundStatusSet;
    }
    
        
        /**
    * @return 卖家memberId
    */
        public function getSellerMemberId() {
        $tempResult = $this->sdkStdResult["sellerMemberId"];
        return $tempResult;
    }
    
    /**
     * 设置卖家memberId     
     * @param String $sellerMemberId     
     * 参数示例：<pre>b2b-1623492085</pre>     
     * 此参数必填     */
    public function setSellerMemberId( $sellerMemberId) {
        $this->sdkStdResult["sellerMemberId"] = $sellerMemberId;
    }
    
        
        /**
    * @return 当前页码
    */
        public function getCurrentPageNum() {
        $tempResult = $this->sdkStdResult["currentPageNum"];
        return $tempResult;
    }
    
    /**
     * 设置当前页码     
     * @param Integer $currentPageNum     
     * 参数示例：<pre>0</pre>     
     * 此参数必填     */
    public function setCurrentPageNum( $currentPageNum) {
        $this->sdkStdResult["currentPageNum"] = $currentPageNum;
    }
    
        
        /**
    * @return 每页条数
    */
        public function getPageSize() {
        $tempResult = $this->sdkStdResult["pageSize"];
        return $tempResult;
    }
    
    /**
     * 设置每页条数     
     * @param Integer $pageSize     
     * 参数示例：<pre>20</pre>     
     * 此参数必填     */
    public function setPageSize( $pageSize) {
        $this->sdkStdResult["pageSize"] = $pageSize;
    }
    
        
        /**
    * @return 退货物流单号（传此字段查询时，需同时传入sellerMemberId）
    */
        public function getLogisticsNo() {
        $tempResult = $this->sdkStdResult["logisticsNo"];
        return $tempResult;
    }
    
    /**
     * 设置退货物流单号（传此字段查询时，需同时传入sellerMemberId）     
     * @param String $logisticsNo     
     * 参数示例：<pre>3101***159271</pre>     
     * 此参数必填     */
    public function setLogisticsNo( $logisticsNo) {
        $this->sdkStdResult["logisticsNo"] = $logisticsNo;
    }
    
        
        /**
    * @return 退款修改时间(起始)
    */
        public function getModifyStartTime() {
        $tempResult = $this->sdkStdResult["modifyStartTime"];
        return $tempResult;
    }
    
    /**
     * 设置退款修改时间(起始)     
     * @param Date $modifyStartTime     
     * 参数示例：<pre>20170926114526000+0800</pre>     
     * 此参数必填     */
    public function setModifyStartTime( $modifyStartTime) {
        $this->sdkStdResult["modifyStartTime"] = $modifyStartTime;
    }
    
        
        /**
    * @return 退款修改时间(截止)
    */
        public function getModifyEndTime() {
        $tempResult = $this->sdkStdResult["modifyEndTime"];
        return $tempResult;
    }
    
    /**
     * 设置退款修改时间(截止)     
     * @param Date $modifyEndTime     
     * 参数示例：<pre>20220926114526000+0800</pre>     
     * 此参数必填     */
    public function setModifyEndTime( $modifyEndTime) {
        $this->sdkStdResult["modifyEndTime"] = $modifyEndTime;
    }
    
        
        /**
    * @return 1:售中退款，2:售后退款；0:所有退款单
    */
        public function getDipsuteType() {
        $tempResult = $this->sdkStdResult["dipsuteType"];
        return $tempResult;
    }
    
    /**
     * 设置1:售中退款，2:售后退款；0:所有退款单     
     * @param Integer $dipsuteType     
     * 参数示例：<pre>1</pre>     
     * 此参数必填     */
    public function setDipsuteType( $dipsuteType) {
        $this->sdkStdResult["dipsuteType"] = $dipsuteType;
    }
    
        
    private $sdkStdResult=array();
    
    public function getSdkStdResult(){
    	return $this->sdkStdResult;
    }

}
?>