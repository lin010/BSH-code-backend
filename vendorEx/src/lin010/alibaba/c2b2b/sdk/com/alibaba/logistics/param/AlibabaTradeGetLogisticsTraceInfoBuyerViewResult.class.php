<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');
include_once ('AlibabaTradeGetLogisticsTraceInfoBuyerViewParam/AlibabaLogisticsOpenPlatformLogisticsTrace.class.php');

class AlibabaTradeGetLogisticsTraceInfoBuyerViewResult {

        	
    private $logisticsTrace;
    
        /**
    * @return 跟踪单详情
    */
        public function getLogisticsTrace() {
        return $this->logisticsTrace;
    }
    
    /**
     * 设置跟踪单详情     
     * @param array include @see AlibabaLogisticsOpenPlatformLogisticsTrace[] $logisticsTrace     
          
     * 此参数必填     */
    public function setLogisticsTrace(AlibabaLogisticsOpenPlatformLogisticsTrace $logisticsTrace) {
        $this->logisticsTrace = $logisticsTrace;
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
    
        	
    private $errorMessage;
    
        /**
    * @return 错误描述
    */
        public function getErrorMessage() {
        return $this->errorMessage;
    }
    
    /**
     * 设置错误描述     
     * @param String $errorMessage     
          
     * 此参数必填     */
    public function setErrorMessage( $errorMessage) {
        $this->errorMessage = $errorMessage;
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
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "logisticsTrace", $this->stdResult )) {
    			$logisticsTraceResult=$this->stdResult->{"logisticsTrace"};
    				$object = json_decode ( json_encode ( $logisticsTraceResult ), true );
					$this->logisticsTrace = array ();
					for($i = 0; $i < count ( $object ); $i ++) {
						$arrayobject = new ArrayObject ( $object [$i] );
						$AlibabaLogisticsOpenPlatformLogisticsTraceResult=new AlibabaLogisticsOpenPlatformLogisticsTrace();
						$AlibabaLogisticsOpenPlatformLogisticsTraceResult->setArrayResult($arrayobject );
						$this->logisticsTrace [$i] = $AlibabaLogisticsOpenPlatformLogisticsTraceResult;
					}
    			}
    			    		    				    			    			if (array_key_exists ( "errorCode", $this->stdResult )) {
    				$this->errorCode = $this->stdResult->{"errorCode"};
    			}
    			    		    				    			    			if (array_key_exists ( "errorMessage", $this->stdResult )) {
    				$this->errorMessage = $this->stdResult->{"errorMessage"};
    			}
    			    		    				    			    			if (array_key_exists ( "success", $this->stdResult )) {
    				$this->success = $this->stdResult->{"success"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    		if (array_key_exists ( "logisticsTrace", $this->arrayResult )) {
    		$logisticsTraceResult=$arrayResult['logisticsTrace'];
    			$this->logisticsTrace = new AlibabaLogisticsOpenPlatformLogisticsTrace();
    			$this->logisticsTrace->setStdResult ( $logisticsTraceResult);
    		}
    		    	    			    		    			if (array_key_exists ( "errorCode", $this->arrayResult )) {
    			$this->errorCode = $arrayResult['errorCode'];
    			}
    		    	    			    		    			if (array_key_exists ( "errorMessage", $this->arrayResult )) {
    			$this->errorMessage = $arrayResult['errorMessage'];
    			}
    		    	    			    		    			if (array_key_exists ( "success", $this->arrayResult )) {
    			$this->success = $arrayResult['success'];
    			}
    		    	    		}

}
?>