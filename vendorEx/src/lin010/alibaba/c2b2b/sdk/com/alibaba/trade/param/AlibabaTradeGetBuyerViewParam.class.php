<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');

class AlibabaTradeGetBuyerViewParam {

        
        /**
    * @return 站点信息，指定调用的API是属于国际站（alibaba）还是1688网站（1688）
    */
        public function getWebSite() {
        $tempResult = $this->sdkStdResult["webSite"];
        return $tempResult;
    }
    
    /**
     * 设置站点信息，指定调用的API是属于国际站（alibaba）还是1688网站（1688）     
     * @param String $webSite     
     * 参数示例：<pre>1688</pre>     
     * 此参数必填     */
    public function setWebSite( $webSite) {
        $this->sdkStdResult["webSite"] = $webSite;
    }
    
        
        /**
    * @return 交易的订单id
    */
        public function getOrderId() {
        $tempResult = $this->sdkStdResult["orderId"];
        return $tempResult;
    }
    
    /**
     * 设置交易的订单id     
     * @param Long $orderId     
     * 参数示例：<pre>123456</pre>     
     * 此参数必填     */
    public function setOrderId( $orderId) {
        $this->sdkStdResult["orderId"] = $orderId;
    }
    
        
        /**
    * @return 查询结果中包含的域，GuaranteesTerms：保障条款，NativeLogistics：物流信息，RateDetail：评价详情，OrderInvoice：发票信息。默认返回GuaranteesTerms、NativeLogistics、OrderInvoice。
    */
        public function getIncludeFields() {
        $tempResult = $this->sdkStdResult["includeFields"];
        return $tempResult;
    }
    
    /**
     * 设置查询结果中包含的域，GuaranteesTerms：保障条款，NativeLogistics：物流信息，RateDetail：评价详情，OrderInvoice：发票信息。默认返回GuaranteesTerms、NativeLogistics、OrderInvoice。     
     * @param String $includeFields     
     * 参数示例：<pre>GuaranteesTerms,NativeLogistics,RateDetail,OrderInvoice</pre>     
     * 此参数必填     */
    public function setIncludeFields( $includeFields) {
        $this->sdkStdResult["includeFields"] = $includeFields;
    }
    
        
        /**
    * @return 垂直表中的attributeKeys
    */
        public function getAttributeKeys() {
        $tempResult = $this->sdkStdResult["attributeKeys"];
        return $tempResult;
    }
    
    /**
     * 设置垂直表中的attributeKeys     
     * @param array include @see String[] $attributeKeys     
     * 参数示例：<pre>[]</pre>     
     * 此参数必填     */
    public function setAttributeKeys( $attributeKeys) {
        $this->sdkStdResult["attributeKeys"] = $attributeKeys;
    }
    
        
    private $sdkStdResult=array();
    
    public function getSdkStdResult(){
    	return $this->sdkStdResult;
    }

}
?>