<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');

class AlibabaTradeGetBuyerOrderListParam {

        
        /**
    * @return 业务类型，支持： "cn"(普通订单类型), "ws"(大额批发订单类型), "yp"(普通拿样订单类型), "yf"(一分钱拿样订单类型), "fs"(倒批(限时折扣)订单类型), "cz"(加工定制订单类型), "ag"(协议采购订单类型), "hp"(伙拼订单类型), "gc"(国采订单类型), "supply"(供销订单类型), "nyg"(nyg订单类型), "factory"(淘工厂订单类型), "quick"(快订下单), "xiangpin"(享拼订单), "nest"(采购商城-鸟巢), "f2f"(当面付), "cyfw"(存样服务), "sp"(代销订单标记), "wg"(微供订单), "factorysamp"(淘工厂打样订单), "factorybig"(淘工厂大货订单)
    */
        public function getBizTypes() {
        $tempResult = $this->sdkStdResult["bizTypes"];
        return $tempResult;
    }
    
    /**
     * 设置业务类型，支持： "cn"(普通订单类型), "ws"(大额批发订单类型), "yp"(普通拿样订单类型), "yf"(一分钱拿样订单类型), "fs"(倒批(限时折扣)订单类型), "cz"(加工定制订单类型), "ag"(协议采购订单类型), "hp"(伙拼订单类型), "gc"(国采订单类型), "supply"(供销订单类型), "nyg"(nyg订单类型), "factory"(淘工厂订单类型), "quick"(快订下单), "xiangpin"(享拼订单), "nest"(采购商城-鸟巢), "f2f"(当面付), "cyfw"(存样服务), "sp"(代销订单标记), "wg"(微供订单), "factorysamp"(淘工厂打样订单), "factorybig"(淘工厂大货订单)     
     * @param array include @see String[] $bizTypes     
     * 参数示例：<pre>["cn","ws"]</pre>     
     * 此参数必填     */
    public function setBizTypes( $bizTypes) {
        $this->sdkStdResult["bizTypes"] = $bizTypes;
    }
    
        
        /**
    * @return 下单结束时间
    */
        public function getCreateEndTime() {
        $tempResult = $this->sdkStdResult["createEndTime"];
        return $tempResult;
    }
    
    /**
     * 设置下单结束时间     
     * @param Date $createEndTime     
     * 参数示例：<pre>20180802211113000+0800</pre>     
     * 此参数必填     */
    public function setCreateEndTime( $createEndTime) {
        $this->sdkStdResult["createEndTime"] = $createEndTime;
    }
    
        
        /**
    * @return 下单开始时间
    */
        public function getCreateStartTime() {
        $tempResult = $this->sdkStdResult["createStartTime"];
        return $tempResult;
    }
    
    /**
     * 设置下单开始时间     
     * @param Date $createStartTime     
     * 参数示例：<pre>20180102211113000+0800</pre>     
     * 此参数必填     */
    public function setCreateStartTime( $createStartTime) {
        $this->sdkStdResult["createStartTime"] = $createStartTime;
    }
    
        
        /**
    * @return 是否查询历史订单表,默认查询当前表，即默认值为false
    */
        public function getIsHis() {
        $tempResult = $this->sdkStdResult["isHis"];
        return $tempResult;
    }
    
    /**
     * 设置是否查询历史订单表,默认查询当前表，即默认值为false     
     * @param Boolean $isHis     
     * 参数示例：<pre>false</pre>     
     * 此参数必填     */
    public function setIsHis( $isHis) {
        $this->sdkStdResult["isHis"] = $isHis;
    }
    
        
        /**
    * @return 查询修改时间结束
    */
        public function getModifyEndTime() {
        $tempResult = $this->sdkStdResult["modifyEndTime"];
        return $tempResult;
    }
    
    /**
     * 设置查询修改时间结束     
     * @param Date $modifyEndTime     
     * 参数示例：<pre>20180802211113000+0800</pre>     
     * 此参数必填     */
    public function setModifyEndTime( $modifyEndTime) {
        $this->sdkStdResult["modifyEndTime"] = $modifyEndTime;
    }
    
        
        /**
    * @return 查询修改时间开始
    */
        public function getModifyStartTime() {
        $tempResult = $this->sdkStdResult["modifyStartTime"];
        return $tempResult;
    }
    
    /**
     * 设置查询修改时间开始     
     * @param Date $modifyStartTime     
     * 参数示例：<pre>20180102211113000+0800</pre>     
     * 此参数必填     */
    public function setModifyStartTime( $modifyStartTime) {
        $this->sdkStdResult["modifyStartTime"] = $modifyStartTime;
    }
    
        
        /**
    * @return 订单状态，值有 success, cancel(交易取消，违约金等交割完毕), waitbuyerpay(等待卖家付款)， waitsellersend(等待卖家发货), waitbuyerreceive(等待买家收货 )
    */
        public function getOrderStatus() {
        $tempResult = $this->sdkStdResult["orderStatus"];
        return $tempResult;
    }
    
    /**
     * 设置订单状态，值有 success, cancel(交易取消，违约金等交割完毕), waitbuyerpay(等待卖家付款)， waitsellersend(等待卖家发货), waitbuyerreceive(等待买家收货 )     
     * @param String $orderStatus     
     * 参数示例：<pre>success</pre>     
     * 此参数必填     */
    public function setOrderStatus( $orderStatus) {
        $this->sdkStdResult["orderStatus"] = $orderStatus;
    }
    
        
        /**
    * @return 查询分页页码，从1开始
    */
        public function getPage() {
        $tempResult = $this->sdkStdResult["page"];
        return $tempResult;
    }
    
    /**
     * 设置查询分页页码，从1开始     
     * @param Integer $page     
     * 参数示例：<pre>1</pre>     
     * 此参数必填     */
    public function setPage( $page) {
        $this->sdkStdResult["page"] = $page;
    }
    
        
        /**
    * @return 查询的每页的数量
    */
        public function getPageSize() {
        $tempResult = $this->sdkStdResult["pageSize"];
        return $tempResult;
    }
    
    /**
     * 设置查询的每页的数量     
     * @param Integer $pageSize     
     * 参数示例：<pre>20</pre>     
     * 此参数必填     */
    public function setPageSize( $pageSize) {
        $this->sdkStdResult["pageSize"] = $pageSize;
    }
    
        
        /**
    * @return 退款状态，支持： "waitselleragree"(等待卖家同意), "refundsuccess"(退款成功), "refundclose"(退款关闭), "waitbuyermodify"(待买家修改), "waitbuyersend"(等待买家退货), "waitsellerreceive"(等待卖家确认收货)
    */
        public function getRefundStatus() {
        $tempResult = $this->sdkStdResult["refundStatus"];
        return $tempResult;
    }
    
    /**
     * 设置退款状态，支持： "waitselleragree"(等待卖家同意), "refundsuccess"(退款成功), "refundclose"(退款关闭), "waitbuyermodify"(待买家修改), "waitbuyersend"(等待买家退货), "waitsellerreceive"(等待卖家确认收货)     
     * @param String $refundStatus     
     * 参数示例：<pre>refundsuccess</pre>     
     * 此参数必填     */
    public function setRefundStatus( $refundStatus) {
        $this->sdkStdResult["refundStatus"] = $refundStatus;
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
     * 参数示例：<pre>b2b-1624961198</pre>     
     * 此参数必填     */
    public function setSellerMemberId( $sellerMemberId) {
        $this->sdkStdResult["sellerMemberId"] = $sellerMemberId;
    }
    
        
        /**
    * @return 卖家loginId
    */
        public function getSellerLoginId() {
        $tempResult = $this->sdkStdResult["sellerLoginId"];
        return $tempResult;
    }
    
    /**
     * 设置卖家loginId     
     * @param String $sellerLoginId     
     * 参数示例：<pre>alitestforisv02</pre>     
     * 此参数必填     */
    public function setSellerLoginId( $sellerLoginId) {
        $this->sdkStdResult["sellerLoginId"] = $sellerLoginId;
    }
    
        
        /**
    * @return 卖家评价状态 (4:已评价,5:未评价,6;不需要评价)
    */
        public function getSellerRateStatus() {
        $tempResult = $this->sdkStdResult["sellerRateStatus"];
        return $tempResult;
    }
    
    /**
     * 设置卖家评价状态 (4:已评价,5:未评价,6;不需要评价)     
     * @param Integer $sellerRateStatus     
     * 参数示例：<pre>6</pre>     
     * 此参数必填     */
    public function setSellerRateStatus( $sellerRateStatus) {
        $this->sdkStdResult["sellerRateStatus"] = $sellerRateStatus;
    }
    
        
        /**
    * @return 交易类型:
担保交易(1),
预存款交易(2),
ETC境外收单交易(3),
即时到帐交易(4),
保障金安全交易(5),
统一交易流程(6),
分阶段交易(7),
货到付款交易(8),
信用凭证支付交易(9),
账期支付交易(10),
1688交易4.0，新分阶段交易(50060),
当面付的交易流程(50070),
服务类的交易流程(50080)
    */
        public function getTradeType() {
        $tempResult = $this->sdkStdResult["tradeType"];
        return $tempResult;
    }
    
    /**
     * 设置交易类型:
担保交易(1),
预存款交易(2),
ETC境外收单交易(3),
即时到帐交易(4),
保障金安全交易(5),
统一交易流程(6),
分阶段交易(7),
货到付款交易(8),
信用凭证支付交易(9),
账期支付交易(10),
1688交易4.0，新分阶段交易(50060),
当面付的交易流程(50070),
服务类的交易流程(50080)     
     * @param String $tradeType     
     * 参数示例：<pre>50060</pre>     
     * 此参数必填     */
    public function setTradeType( $tradeType) {
        $this->sdkStdResult["tradeType"] = $tradeType;
    }
    
        
        /**
    * @return 商品名称
    */
        public function getProductName() {
        $tempResult = $this->sdkStdResult["productName"];
        return $tempResult;
    }
    
    /**
     * 设置商品名称     
     * @param String $productName     
     * 参数示例：<pre>测试商品</pre>     
     * 此参数必填     */
    public function setProductName( $productName) {
        $this->sdkStdResult["productName"] = $productName;
    }
    
        
        /**
    * @return 是否需要查询买家的详细地址信息和电话
    */
        public function getNeedBuyerAddressAndPhone() {
        $tempResult = $this->sdkStdResult["needBuyerAddressAndPhone"];
        return $tempResult;
    }
    
    /**
     * 设置是否需要查询买家的详细地址信息和电话     
     * @param Boolean $needBuyerAddressAndPhone     
     * 参数示例：<pre>false</pre>     
     * 此参数必填     */
    public function setNeedBuyerAddressAndPhone( $needBuyerAddressAndPhone) {
        $this->sdkStdResult["needBuyerAddressAndPhone"] = $needBuyerAddressAndPhone;
    }
    
        
        /**
    * @return 是否需要查询备注信息
    */
        public function getNeedMemoInfo() {
        $tempResult = $this->sdkStdResult["needMemoInfo"];
        return $tempResult;
    }
    
    /**
     * 设置是否需要查询备注信息     
     * @param Boolean $needMemoInfo     
     * 参数示例：<pre>false</pre>     
     * 此参数必填     */
    public function setNeedMemoInfo( $needMemoInfo) {
        $this->sdkStdResult["needMemoInfo"] = $needMemoInfo;
    }
    
        
    private $sdkStdResult=array();
    
    public function getSdkStdResult(){
    	return $this->sdkStdResult;
    }

}
?>