<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');
include_once ('AlibabaTradeGetRefundReasonListParam/AlibabaOceanOpenplatformBizTradeResultOrderRefundReasonListResult.class.php');

class AlibabaOceanOpenplatformCommonOrderRefundReasonListResult extends SDKDomain {

       	
    private $code;
    
        /**
    * @return 错误码
    */
        public function getCode() {
        return $this->code;
    }
    
    /**
     * 设置错误码     
     * @param String $code     
     * 参数示例：<pre> </pre>     
     * 此参数必填     */
    public function setCode( $code) {
        $this->code = $code;
    }
    
        	
    private $message;
    
        /**
    * @return 错误信息
    */
        public function getMessage() {
        return $this->message;
    }
    
    /**
     * 设置错误信息     
     * @param String $message     
     * 参数示例：<pre> </pre>     
     * 此参数必填     */
    public function setMessage( $message) {
        $this->message = $message;
    }
    
        	
    private $result;
    
        /**
    * @return 结果
    */
        public function getResult() {
        return $this->result;
    }
    
    /**
     * 设置结果     
     * @param AlibabaOceanOpenplatformBizTradeResultOrderRefundReasonListResult $result     
     * 参数示例：<pre> </pre>     
     * 此参数必填     */
    public function setResult(AlibabaOceanOpenplatformBizTradeResultOrderRefundReasonListResult $result) {
        $this->result = $result;
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
     * 参数示例：<pre> </pre>     
     * 此参数必填     */
    public function setSuccess( $success) {
        $this->success = $success;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "code", $this->stdResult )) {
    				$this->code = $this->stdResult->{"code"};
    			}
    			    		    				    			    			if (array_key_exists ( "message", $this->stdResult )) {
    				$this->message = $this->stdResult->{"message"};
    			}
    			    		    				    			    			if (array_key_exists ( "result", $this->stdResult )) {
    				$resultResult=$this->stdResult->{"result"};
    				$this->result = new AlibabaOceanOpenplatformBizTradeResultOrderRefundReasonListResult();
    				$this->result->setStdResult ( $resultResult);
    			}
    			    		    				    			    			if (array_key_exists ( "success", $this->stdResult )) {
    				$this->success = $this->stdResult->{"success"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    			if (array_key_exists ( "code", $this->arrayResult )) {
    			$this->code = $arrayResult['code'];
    			}
    		    	    			    		    			if (array_key_exists ( "message", $this->arrayResult )) {
    			$this->message = $arrayResult['message'];
    			}
    		    	    			    		    		if (array_key_exists ( "result", $this->arrayResult )) {
    		$resultResult=$arrayResult['result'];
    			    			$this->result = new AlibabaOceanOpenplatformBizTradeResultOrderRefundReasonListResult();
    			    			$this->result->setStdResult ( $resultResult);
    		}
    		    	    			    		    			if (array_key_exists ( "success", $this->arrayResult )) {
    			$this->success = $arrayResult['success'];
    			}
    		    	    		}
 
   
}
?>