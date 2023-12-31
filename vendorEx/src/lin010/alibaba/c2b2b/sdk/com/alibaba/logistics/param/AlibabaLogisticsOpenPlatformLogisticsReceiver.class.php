<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');

class AlibabaLogisticsOpenPlatformLogisticsReceiver extends SDKDomain {

       	
    private $receiverName;
    
        /**
    * @return 收件人名字
    */
        public function getReceiverName() {
        return $this->receiverName;
    }
    
    /**
     * 设置收件人名字     
     * @param String $receiverName     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setReceiverName( $receiverName) {
        $this->receiverName = $receiverName;
    }
    
        	
    private $receiverPhone;
    
        /**
    * @return 收件人电话
    */
        public function getReceiverPhone() {
        return $this->receiverPhone;
    }
    
    /**
     * 设置收件人电话     
     * @param String $receiverPhone     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setReceiverPhone( $receiverPhone) {
        $this->receiverPhone = $receiverPhone;
    }
    
        	
    private $receiverMobile;
    
        /**
    * @return 收件人电话
    */
        public function getReceiverMobile() {
        return $this->receiverMobile;
    }
    
    /**
     * 设置收件人电话     
     * @param String $receiverMobile     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setReceiverMobile( $receiverMobile) {
        $this->receiverMobile = $receiverMobile;
    }
    
        	
    private $encrypt;
    
        /**
    * @return 
    */
        public function getEncrypt() {
        return $this->encrypt;
    }
    
    /**
     * 设置     
     * @param String $encrypt     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setEncrypt( $encrypt) {
        $this->encrypt = $encrypt;
    }
    
        	
    private $receiverProvinceCode;
    
        /**
    * @return 省编码
    */
        public function getReceiverProvinceCode() {
        return $this->receiverProvinceCode;
    }
    
    /**
     * 设置省编码     
     * @param String $receiverProvinceCode     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setReceiverProvinceCode( $receiverProvinceCode) {
        $this->receiverProvinceCode = $receiverProvinceCode;
    }
    
        	
    private $receiverCityCode;
    
        /**
    * @return 市编码
    */
        public function getReceiverCityCode() {
        return $this->receiverCityCode;
    }
    
    /**
     * 设置市编码     
     * @param String $receiverCityCode     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setReceiverCityCode( $receiverCityCode) {
        $this->receiverCityCode = $receiverCityCode;
    }
    
        	
    private $receiverCountyCode;
    
        /**
    * @return 国家编码
    */
        public function getReceiverCountyCode() {
        return $this->receiverCountyCode;
    }
    
    /**
     * 设置国家编码     
     * @param String $receiverCountyCode     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setReceiverCountyCode( $receiverCountyCode) {
        $this->receiverCountyCode = $receiverCountyCode;
    }
    
        	
    private $receiverAddress;
    
        /**
    * @return 地址
    */
        public function getReceiverAddress() {
        return $this->receiverAddress;
    }
    
    /**
     * 设置地址     
     * @param String $receiverAddress     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setReceiverAddress( $receiverAddress) {
        $this->receiverAddress = $receiverAddress;
    }
    
        	
    private $receiverProvince;
    
        /**
    * @return 省份
    */
        public function getReceiverProvince() {
        return $this->receiverProvince;
    }
    
    /**
     * 设置省份     
     * @param String $receiverProvince     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setReceiverProvince( $receiverProvince) {
        $this->receiverProvince = $receiverProvince;
    }
    
        	
    private $receiverCity;
    
        /**
    * @return 城市
    */
        public function getReceiverCity() {
        return $this->receiverCity;
    }
    
    /**
     * 设置城市     
     * @param String $receiverCity     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setReceiverCity( $receiverCity) {
        $this->receiverCity = $receiverCity;
    }
    
        	
    private $receiverCounty;
    
        /**
    * @return 国家
    */
        public function getReceiverCounty() {
        return $this->receiverCounty;
    }
    
    /**
     * 设置国家     
     * @param String $receiverCounty     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setReceiverCounty( $receiverCounty) {
        $this->receiverCounty = $receiverCounty;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "receiverName", $this->stdResult )) {
    				$this->receiverName = $this->stdResult->{"receiverName"};
    			}
    			    		    				    			    			if (array_key_exists ( "receiverPhone", $this->stdResult )) {
    				$this->receiverPhone = $this->stdResult->{"receiverPhone"};
    			}
    			    		    				    			    			if (array_key_exists ( "receiverMobile", $this->stdResult )) {
    				$this->receiverMobile = $this->stdResult->{"receiverMobile"};
    			}
    			    		    				    			    			if (array_key_exists ( "encrypt", $this->stdResult )) {
    				$this->encrypt = $this->stdResult->{"encrypt"};
    			}
    			    		    				    			    			if (array_key_exists ( "receiverProvinceCode", $this->stdResult )) {
    				$this->receiverProvinceCode = $this->stdResult->{"receiverProvinceCode"};
    			}
    			    		    				    			    			if (array_key_exists ( "receiverCityCode", $this->stdResult )) {
    				$this->receiverCityCode = $this->stdResult->{"receiverCityCode"};
    			}
    			    		    				    			    			if (array_key_exists ( "receiverCountyCode", $this->stdResult )) {
    				$this->receiverCountyCode = $this->stdResult->{"receiverCountyCode"};
    			}
    			    		    				    			    			if (array_key_exists ( "receiverAddress", $this->stdResult )) {
    				$this->receiverAddress = $this->stdResult->{"receiverAddress"};
    			}
    			    		    				    			    			if (array_key_exists ( "receiverProvince", $this->stdResult )) {
    				$this->receiverProvince = $this->stdResult->{"receiverProvince"};
    			}
    			    		    				    			    			if (array_key_exists ( "receiverCity", $this->stdResult )) {
    				$this->receiverCity = $this->stdResult->{"receiverCity"};
    			}
    			    		    				    			    			if (array_key_exists ( "receiverCounty", $this->stdResult )) {
    				$this->receiverCounty = $this->stdResult->{"receiverCounty"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    			if (array_key_exists ( "receiverName", $this->arrayResult )) {
    			$this->receiverName = $arrayResult['receiverName'];
    			}
    		    	    			    		    			if (array_key_exists ( "receiverPhone", $this->arrayResult )) {
    			$this->receiverPhone = $arrayResult['receiverPhone'];
    			}
    		    	    			    		    			if (array_key_exists ( "receiverMobile", $this->arrayResult )) {
    			$this->receiverMobile = $arrayResult['receiverMobile'];
    			}
    		    	    			    		    			if (array_key_exists ( "encrypt", $this->arrayResult )) {
    			$this->encrypt = $arrayResult['encrypt'];
    			}
    		    	    			    		    			if (array_key_exists ( "receiverProvinceCode", $this->arrayResult )) {
    			$this->receiverProvinceCode = $arrayResult['receiverProvinceCode'];
    			}
    		    	    			    		    			if (array_key_exists ( "receiverCityCode", $this->arrayResult )) {
    			$this->receiverCityCode = $arrayResult['receiverCityCode'];
    			}
    		    	    			    		    			if (array_key_exists ( "receiverCountyCode", $this->arrayResult )) {
    			$this->receiverCountyCode = $arrayResult['receiverCountyCode'];
    			}
    		    	    			    		    			if (array_key_exists ( "receiverAddress", $this->arrayResult )) {
    			$this->receiverAddress = $arrayResult['receiverAddress'];
    			}
    		    	    			    		    			if (array_key_exists ( "receiverProvince", $this->arrayResult )) {
    			$this->receiverProvince = $arrayResult['receiverProvince'];
    			}
    		    	    			    		    			if (array_key_exists ( "receiverCity", $this->arrayResult )) {
    			$this->receiverCity = $arrayResult['receiverCity'];
    			}
    		    	    			    		    			if (array_key_exists ( "receiverCounty", $this->arrayResult )) {
    			$this->receiverCounty = $arrayResult['receiverCounty'];
    			}
    		    	    		}
 
   
}
?>