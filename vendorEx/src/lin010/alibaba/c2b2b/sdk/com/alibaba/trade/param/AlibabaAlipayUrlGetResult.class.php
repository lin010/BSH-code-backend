<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');

class AlibabaAlipayUrlGetResult {

        	
    private $erroMsg;
    
        /**
    * @return 错误信息
    */
        public function getErroMsg() {
        return $this->erroMsg;
    }
    
    /**
     * 设置错误信息     
     * @param String $erroMsg     
          
     * 此参数必填     */
    public function setErroMsg( $erroMsg) {
        $this->erroMsg = $erroMsg;
    }
    
        	
    private $payUrl;
    
        /**
    * @return 支付链接
    */
        public function getPayUrl() {
        return $this->payUrl;
    }
    
    /**
     * 设置支付链接     
     * @param String $payUrl     
          
     * 此参数必填     */
    public function setPayUrl( $payUrl) {
        $this->payUrl = $payUrl;
    }
    
        	
    private $success;
    
        /**
    * @return 是否成功
    */
        public function getSuccess() {
        return $this->success;
    }
    
    /**
     * 设置是否成功     
     * @param Boolean $success     
          
     * 此参数必填     */
    public function setSuccess( $success) {
        $this->success = $success;
    }
    
        	
    private $errorCode;
    
        /**
    * @return 错误码
    */
        public function getErrorCode() {
        return $this->errorCode;
    }
    
    /**
     * 设置错误码     
     * @param String $errorCode     
          
     * 此参数必填     */
    public function setErrorCode( $errorCode) {
        $this->errorCode = $errorCode;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "erroMsg", $this->stdResult )) {
    				$this->erroMsg = $this->stdResult->{"erroMsg"};
    			}
    			    		    				    			    			if (array_key_exists ( "payUrl", $this->stdResult )) {
    				$this->payUrl = $this->stdResult->{"payUrl"};
    			}
    			    		    				    			    			if (array_key_exists ( "success", $this->stdResult )) {
    				$this->success = $this->stdResult->{"success"};
    			}
    			    		    				    			    			if (array_key_exists ( "errorCode", $this->stdResult )) {
    				$this->errorCode = $this->stdResult->{"errorCode"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    			if (array_key_exists ( "erroMsg", $this->arrayResult )) {
    			$this->erroMsg = $arrayResult['erroMsg'];
    			}
    		    	    			    		    			if (array_key_exists ( "payUrl", $this->arrayResult )) {
    			$this->payUrl = $arrayResult['payUrl'];
    			}
    		    	    			    		    			if (array_key_exists ( "success", $this->arrayResult )) {
    			$this->success = $arrayResult['success'];
    			}
    		    	    			    		    			if (array_key_exists ( "errorCode", $this->arrayResult )) {
    			$this->errorCode = $arrayResult['errorCode'];
    			}
    		    	    		}

}
?>