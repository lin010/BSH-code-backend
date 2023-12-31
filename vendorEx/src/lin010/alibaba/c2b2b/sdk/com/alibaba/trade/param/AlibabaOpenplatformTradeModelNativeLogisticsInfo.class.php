<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');
include_once ('AlibabaTradeGetBuyerViewParam/AlibabaOpenplatformTradeModelNativeLogisticsItemsInfo.class.php');

class AlibabaOpenplatformTradeModelNativeLogisticsInfo extends SDKDomain {

       	
    private $address;
    
        /**
    * @return 详细地址
    */
        public function getAddress() {
        return $this->address;
    }
    
    /**
     * 设置详细地址     
     * @param String $address     
     * 参数示例：<pre>杭州市网商路699号</pre>     
     * 此参数必填     */
    public function setAddress( $address) {
        $this->address = $address;
    }
    
        	
    private $area;
    
        /**
    * @return 县，区
    */
        public function getArea() {
        return $this->area;
    }
    
    /**
     * 设置县，区     
     * @param String $area     
     * 参数示例：<pre>滨江区</pre>     
     * 此参数必填     */
    public function setArea( $area) {
        $this->area = $area;
    }
    
        	
    private $areaCode;
    
        /**
    * @return 省市区编码
    */
        public function getAreaCode() {
        return $this->areaCode;
    }
    
    /**
     * 设置省市区编码     
     * @param String $areaCode     
     * 参数示例：<pre>330108</pre>     
     * 此参数必填     */
    public function setAreaCode( $areaCode) {
        $this->areaCode = $areaCode;
    }
    
        	
    private $city;
    
        /**
    * @return 城市
    */
        public function getCity() {
        return $this->city;
    }
    
    /**
     * 设置城市     
     * @param String $city     
     * 参数示例：<pre>杭州市</pre>     
     * 此参数必填     */
    public function setCity( $city) {
        $this->city = $city;
    }
    
        	
    private $contactPerson;
    
        /**
    * @return 联系人姓名
    */
        public function getContactPerson() {
        return $this->contactPerson;
    }
    
    /**
     * 设置联系人姓名     
     * @param String $contactPerson     
     * 参数示例：<pre>张三</pre>     
     * 此参数必填     */
    public function setContactPerson( $contactPerson) {
        $this->contactPerson = $contactPerson;
    }
    
        	
    private $fax;
    
        /**
    * @return 传真
    */
        public function getFax() {
        return $this->fax;
    }
    
    /**
     * 设置传真     
     * @param String $fax     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setFax( $fax) {
        $this->fax = $fax;
    }
    
        	
    private $mobile;
    
        /**
    * @return 手机
    */
        public function getMobile() {
        return $this->mobile;
    }
    
    /**
     * 设置手机     
     * @param String $mobile     
     * 参数示例：<pre>13988888888</pre>     
     * 此参数必填     */
    public function setMobile( $mobile) {
        $this->mobile = $mobile;
    }
    
        	
    private $province;
    
        /**
    * @return 省份
    */
        public function getProvince() {
        return $this->province;
    }
    
    /**
     * 设置省份     
     * @param String $province     
     * 参数示例：<pre>浙江省</pre>     
     * 此参数必填     */
    public function setProvince( $province) {
        $this->province = $province;
    }
    
        	
    private $telephone;
    
        /**
    * @return 电话
    */
        public function getTelephone() {
        return $this->telephone;
    }
    
    /**
     * 设置电话     
     * @param String $telephone     
     * 参数示例：<pre>0517-88990077</pre>     
     * 此参数必填     */
    public function setTelephone( $telephone) {
        $this->telephone = $telephone;
    }
    
        	
    private $zip;
    
        /**
    * @return 邮编
    */
        public function getZip() {
        return $this->zip;
    }
    
    /**
     * 设置邮编     
     * @param String $zip     
     * 参数示例：<pre>000000</pre>     
     * 此参数必填     */
    public function setZip( $zip) {
        $this->zip = $zip;
    }
    
        	
    private $logisticsItems;
    
        /**
    * @return 运单明细
    */
        public function getLogisticsItems() {
        return $this->logisticsItems;
    }
    
    /**
     * 设置运单明细     
     * @param array include @see AlibabaOpenplatformTradeModelNativeLogisticsItemsInfo[] $logisticsItems     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setLogisticsItems(AlibabaOpenplatformTradeModelNativeLogisticsItemsInfo $logisticsItems) {
        $this->logisticsItems = $logisticsItems;
    }
    
        	
    private $townCode;
    
        /**
    * @return 镇，街道地址码
    */
        public function getTownCode() {
        return $this->townCode;
    }
    
    /**
     * 设置镇，街道地址码     
     * @param String $townCode     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setTownCode( $townCode) {
        $this->townCode = $townCode;
    }
    
        	
    private $town;
    
        /**
    * @return 镇，街道
    */
        public function getTown() {
        return $this->town;
    }
    
    /**
     * 设置镇，街道     
     * @param String $town     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setTown( $town) {
        $this->town = $town;
    }
    
        	
    private $caid;
    
        /**
    * @return 解密地址ID，用于电商平台收货人信息加密场景使用，非订单加密场景请勿使用。
    */
        public function getCaid() {
        return $this->caid;
    }
    
    /**
     * 设置解密地址ID，用于电商平台收货人信息加密场景使用，非订单加密场景请勿使用。     
     * @param String $caid     
     * 参数示例：<pre> </pre>     
     * 此参数必填     */
    public function setCaid( $caid) {
        $this->caid = $caid;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "address", $this->stdResult )) {
    				$this->address = $this->stdResult->{"address"};
    			}
    			    		    				    			    			if (array_key_exists ( "area", $this->stdResult )) {
    				$this->area = $this->stdResult->{"area"};
    			}
    			    		    				    			    			if (array_key_exists ( "areaCode", $this->stdResult )) {
    				$this->areaCode = $this->stdResult->{"areaCode"};
    			}
    			    		    				    			    			if (array_key_exists ( "city", $this->stdResult )) {
    				$this->city = $this->stdResult->{"city"};
    			}
    			    		    				    			    			if (array_key_exists ( "contactPerson", $this->stdResult )) {
    				$this->contactPerson = $this->stdResult->{"contactPerson"};
    			}
    			    		    				    			    			if (array_key_exists ( "fax", $this->stdResult )) {
    				$this->fax = $this->stdResult->{"fax"};
    			}
    			    		    				    			    			if (array_key_exists ( "mobile", $this->stdResult )) {
    				$this->mobile = $this->stdResult->{"mobile"};
    			}
    			    		    				    			    			if (array_key_exists ( "province", $this->stdResult )) {
    				$this->province = $this->stdResult->{"province"};
    			}
    			    		    				    			    			if (array_key_exists ( "telephone", $this->stdResult )) {
    				$this->telephone = $this->stdResult->{"telephone"};
    			}
    			    		    				    			    			if (array_key_exists ( "zip", $this->stdResult )) {
    				$this->zip = $this->stdResult->{"zip"};
    			}
    			    		    				    			    			if (array_key_exists ( "logisticsItems", $this->stdResult )) {
    			$logisticsItemsResult=$this->stdResult->{"logisticsItems"};
    				$object = json_decode ( json_encode ( $logisticsItemsResult ), true );
					$this->logisticsItems = array ();
					for($i = 0; $i < count ( $object ); $i ++) {
						$arrayobject = new ArrayObject ( $object [$i] );
						$AlibabaOpenplatformTradeModelNativeLogisticsItemsInfoResult=new AlibabaOpenplatformTradeModelNativeLogisticsItemsInfo();
						$AlibabaOpenplatformTradeModelNativeLogisticsItemsInfoResult->setArrayResult($arrayobject );
						$this->logisticsItems [$i] = $AlibabaOpenplatformTradeModelNativeLogisticsItemsInfoResult;
					}
    			}
    			    		    				    			    			if (array_key_exists ( "townCode", $this->stdResult )) {
    				$this->townCode = $this->stdResult->{"townCode"};
    			}
    			    		    				    			    			if (array_key_exists ( "town", $this->stdResult )) {
    				$this->town = $this->stdResult->{"town"};
    			}
    			    		    				    			    			if (array_key_exists ( "caid", $this->stdResult )) {
    				$this->caid = $this->stdResult->{"caid"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    			if (array_key_exists ( "address", $this->arrayResult )) {
    			$this->address = $arrayResult['address'];
    			}
    		    	    			    		    			if (array_key_exists ( "area", $this->arrayResult )) {
    			$this->area = $arrayResult['area'];
    			}
    		    	    			    		    			if (array_key_exists ( "areaCode", $this->arrayResult )) {
    			$this->areaCode = $arrayResult['areaCode'];
    			}
    		    	    			    		    			if (array_key_exists ( "city", $this->arrayResult )) {
    			$this->city = $arrayResult['city'];
    			}
    		    	    			    		    			if (array_key_exists ( "contactPerson", $this->arrayResult )) {
    			$this->contactPerson = $arrayResult['contactPerson'];
    			}
    		    	    			    		    			if (array_key_exists ( "fax", $this->arrayResult )) {
    			$this->fax = $arrayResult['fax'];
    			}
    		    	    			    		    			if (array_key_exists ( "mobile", $this->arrayResult )) {
    			$this->mobile = $arrayResult['mobile'];
    			}
    		    	    			    		    			if (array_key_exists ( "province", $this->arrayResult )) {
    			$this->province = $arrayResult['province'];
    			}
    		    	    			    		    			if (array_key_exists ( "telephone", $this->arrayResult )) {
    			$this->telephone = $arrayResult['telephone'];
    			}
    		    	    			    		    			if (array_key_exists ( "zip", $this->arrayResult )) {
    			$this->zip = $arrayResult['zip'];
    			}
    		    	    			    		    		if (array_key_exists ( "logisticsItems", $this->arrayResult )) {
    		$logisticsItemsResult=$arrayResult['logisticsItems'];
    			$this->logisticsItems = new AlibabaOpenplatformTradeModelNativeLogisticsItemsInfo();
    			$this->logisticsItems->setStdResult ( $logisticsItemsResult);
    		}
    		    	    			    		    			if (array_key_exists ( "townCode", $this->arrayResult )) {
    			$this->townCode = $arrayResult['townCode'];
    			}
    		    	    			    		    			if (array_key_exists ( "town", $this->arrayResult )) {
    			$this->town = $arrayResult['town'];
    			}
    		    	    			    		    			if (array_key_exists ( "caid", $this->arrayResult )) {
    			$this->caid = $arrayResult['caid'];
    			}
    		    	    		}
 
   
}
?>