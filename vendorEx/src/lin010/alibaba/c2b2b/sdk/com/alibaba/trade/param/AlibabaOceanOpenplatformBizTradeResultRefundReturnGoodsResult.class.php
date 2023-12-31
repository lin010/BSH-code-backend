<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');

class AlibabaOceanOpenplatformBizTradeResultRefundReturnGoodsResult extends SDKDomain {

       	
    private $errorCode;
    
        /**
    * @return 1
    */
        public function getErrorCode() {
        return $this->errorCode;
    }
    
    /**
     * 设置1     
     * @param String $errorCode     
     * 参数示例：<pre>1</pre>     
     * 此参数必填     */
    public function setErrorCode( $errorCode) {
        $this->errorCode = $errorCode;
    }
    
        	
    private $errorInfo;
    
        /**
    * @return 1
    */
        public function getErrorInfo() {
        return $this->errorInfo;
    }
    
    /**
     * 设置1     
     * @param String $errorInfo     
     * 参数示例：<pre>1</pre>     
     * 此参数必填     */
    public function setErrorInfo( $errorInfo) {
        $this->errorInfo = $errorInfo;
    }
    
        	
    private $success;
    
        /**
    * @return 1
    */
        public function getSuccess() {
        return $this->success;
    }
    
    /**
     * 设置1     
     * @param Boolean $success     
     * 参数示例：<pre>1</pre>     
     * 此参数必填     */
    public function setSuccess( $success) {
        $this->success = $success;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "errorCode", $this->stdResult )) {
    				$this->errorCode = $this->stdResult->{"errorCode"};
    			}
    			    		    				    			    			if (array_key_exists ( "errorInfo", $this->stdResult )) {
    				$this->errorInfo = $this->stdResult->{"errorInfo"};
    			}
    			    		    				    			    			if (array_key_exists ( "success", $this->stdResult )) {
    				$this->success = $this->stdResult->{"success"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    			if (array_key_exists ( "errorCode", $this->arrayResult )) {
    			$this->errorCode = $arrayResult['errorCode'];
    			}
    		    	    			    		    			if (array_key_exists ( "errorInfo", $this->arrayResult )) {
    			$this->errorInfo = $arrayResult['errorInfo'];
    			}
    		    	    			    		    			if (array_key_exists ( "success", $this->arrayResult )) {
    			$this->success = $arrayResult['success'];
    			}
    		    	    		}
 
   
}
?>