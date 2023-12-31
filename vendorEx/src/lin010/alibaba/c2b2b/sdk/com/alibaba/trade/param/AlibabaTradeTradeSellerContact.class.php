<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');

class AlibabaTradeTradeSellerContact extends SDKDomain {

       	
    private $phone;
    
        /**
    * @return 联系电话
    */
        public function getPhone() {
        return $this->phone;
    }
    
    /**
     * 设置联系电话     
     * @param String $phone     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setPhone( $phone) {
        $this->phone = $phone;
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
    
        	
    private $email;
    
        /**
    * @return 邮箱
    */
        public function getEmail() {
        return $this->email;
    }
    
    /**
     * 设置邮箱     
     * @param String $email     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setEmail( $email) {
        $this->email = $email;
    }
    
        	
    private $imInPlatform;
    
        /**
    * @return 联系人在平台的IM账号
    */
        public function getImInPlatform() {
        return $this->imInPlatform;
    }
    
    /**
     * 设置联系人在平台的IM账号     
     * @param String $imInPlatform     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setImInPlatform( $imInPlatform) {
        $this->imInPlatform = $imInPlatform;
    }
    
        	
    private $name;
    
        /**
    * @return 联系人名称
    */
        public function getName() {
        return $this->name;
    }
    
    /**
     * 设置联系人名称     
     * @param String $name     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setName( $name) {
        $this->name = $name;
    }
    
        	
    private $mobile;
    
        /**
    * @return 联系人手机号
    */
        public function getMobile() {
        return $this->mobile;
    }
    
    /**
     * 设置联系人手机号     
     * @param String $mobile     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setMobile( $mobile) {
        $this->mobile = $mobile;
    }
    
        	
    private $companyName;
    
        /**
    * @return 公司名称
    */
        public function getCompanyName() {
        return $this->companyName;
    }
    
    /**
     * 设置公司名称     
     * @param String $companyName     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setCompanyName( $companyName) {
        $this->companyName = $companyName;
    }
    
        	
    private $wgSenderName;
    
        /**
    * @return 发件人名称，在微供等分销场景下由分销商设置
    */
        public function getWgSenderName() {
        return $this->wgSenderName;
    }
    
    /**
     * 设置发件人名称，在微供等分销场景下由分销商设置     
     * @param String $wgSenderName     
     * 参数示例：<pre>张**</pre>     
     * 此参数必填     */
    public function setWgSenderName( $wgSenderName) {
        $this->wgSenderName = $wgSenderName;
    }
    
        	
    private $wgSenderPhone;
    
        /**
    * @return 发件人电话，在微供等分销场景下由分销商设置
    */
        public function getWgSenderPhone() {
        return $this->wgSenderPhone;
    }
    
    /**
     * 设置发件人电话，在微供等分销场景下由分销商设置     
     * @param String $wgSenderPhone     
     * 参数示例：<pre>13800000000</pre>     
     * 此参数必填     */
    public function setWgSenderPhone( $wgSenderPhone) {
        $this->wgSenderPhone = $wgSenderPhone;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "phone", $this->stdResult )) {
    				$this->phone = $this->stdResult->{"phone"};
    			}
    			    		    				    			    			if (array_key_exists ( "fax", $this->stdResult )) {
    				$this->fax = $this->stdResult->{"fax"};
    			}
    			    		    				    			    			if (array_key_exists ( "email", $this->stdResult )) {
    				$this->email = $this->stdResult->{"email"};
    			}
    			    		    				    			    			if (array_key_exists ( "imInPlatform", $this->stdResult )) {
    				$this->imInPlatform = $this->stdResult->{"imInPlatform"};
    			}
    			    		    				    			    			if (array_key_exists ( "name", $this->stdResult )) {
    				$this->name = $this->stdResult->{"name"};
    			}
    			    		    				    			    			if (array_key_exists ( "mobile", $this->stdResult )) {
    				$this->mobile = $this->stdResult->{"mobile"};
    			}
    			    		    				    			    			if (array_key_exists ( "companyName", $this->stdResult )) {
    				$this->companyName = $this->stdResult->{"companyName"};
    			}
    			    		    				    			    			if (array_key_exists ( "wgSenderName", $this->stdResult )) {
    				$this->wgSenderName = $this->stdResult->{"wgSenderName"};
    			}
    			    		    				    			    			if (array_key_exists ( "wgSenderPhone", $this->stdResult )) {
    				$this->wgSenderPhone = $this->stdResult->{"wgSenderPhone"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    			if (array_key_exists ( "phone", $this->arrayResult )) {
    			$this->phone = $arrayResult['phone'];
    			}
    		    	    			    		    			if (array_key_exists ( "fax", $this->arrayResult )) {
    			$this->fax = $arrayResult['fax'];
    			}
    		    	    			    		    			if (array_key_exists ( "email", $this->arrayResult )) {
    			$this->email = $arrayResult['email'];
    			}
    		    	    			    		    			if (array_key_exists ( "imInPlatform", $this->arrayResult )) {
    			$this->imInPlatform = $arrayResult['imInPlatform'];
    			}
    		    	    			    		    			if (array_key_exists ( "name", $this->arrayResult )) {
    			$this->name = $arrayResult['name'];
    			}
    		    	    			    		    			if (array_key_exists ( "mobile", $this->arrayResult )) {
    			$this->mobile = $arrayResult['mobile'];
    			}
    		    	    			    		    			if (array_key_exists ( "companyName", $this->arrayResult )) {
    			$this->companyName = $arrayResult['companyName'];
    			}
    		    	    			    		    			if (array_key_exists ( "wgSenderName", $this->arrayResult )) {
    			$this->wgSenderName = $arrayResult['wgSenderName'];
    			}
    		    	    			    		    			if (array_key_exists ( "wgSenderPhone", $this->arrayResult )) {
    			$this->wgSenderPhone = $arrayResult['wgSenderPhone'];
    			}
    		    	    		}
 
   
}
?>