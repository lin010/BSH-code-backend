<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');

class AlibabaCreditOrderForDetail extends SDKDomain {

       	
    private $payAmount;
    
        /**
    * @return 订单金额
    */
        public function getPayAmount() {
        return $this->payAmount;
    }
    
    /**
     * 设置订单金额     
     * @param Long $payAmount     
     * 参数示例：<pre>10</pre>     
     * 此参数必填     */
    public function setPayAmount( $payAmount) {
        $this->payAmount = $payAmount;
    }
    
        	
    private $createTime;
    
        /**
    * @return 支付时间
    */
        public function getCreateTime() {
        return $this->createTime;
    }
    
    /**
     * 设置支付时间     
     * @param String $createTime     
     * 参数示例：<pre>2018-01-01 00:00:00</pre>     
     * 此参数必填     */
    public function setCreateTime( $createTime) {
        $this->createTime = $createTime;
    }
    
        	
    private $status;
    
        /**
    * @return 状态
    */
        public function getStatus() {
        return $this->status;
    }
    
    /**
     * 设置状态     
     * @param String $status     
     * 参数示例：<pre>END</pre>     
     * 此参数必填     */
    public function setStatus( $status) {
        $this->status = $status;
    }
    
        	
    private $gracePeriodEndTime;
    
        /**
    * @return 最晚还款时间
    */
        public function getGracePeriodEndTime() {
        return $this->gracePeriodEndTime;
    }
    
    /**
     * 设置最晚还款时间     
     * @param String $gracePeriodEndTime     
     * 参数示例：<pre>2018-01-01 00:00:00</pre>     
     * 此参数必填     */
    public function setGracePeriodEndTime( $gracePeriodEndTime) {
        $this->gracePeriodEndTime = $gracePeriodEndTime;
    }
    
        	
    private $statusStr;
    
        /**
    * @return 状态描述
    */
        public function getStatusStr() {
        return $this->statusStr;
    }
    
    /**
     * 设置状态描述     
     * @param String $statusStr     
     * 参数示例：<pre>已完结</pre>     
     * 此参数必填     */
    public function setStatusStr( $statusStr) {
        $this->statusStr = $statusStr;
    }
    
        	
    private $restRepayAmount;
    
        /**
    * @return 应还金额
    */
        public function getRestRepayAmount() {
        return $this->restRepayAmount;
    }
    
    /**
     * 设置应还金额     
     * @param Long $restRepayAmount     
     * 参数示例：<pre>11</pre>     
     * 此参数必填     */
    public function setRestRepayAmount( $restRepayAmount) {
        $this->restRepayAmount = $restRepayAmount;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "payAmount", $this->stdResult )) {
    				$this->payAmount = $this->stdResult->{"payAmount"};
    			}
    			    		    				    			    			if (array_key_exists ( "createTime", $this->stdResult )) {
    				$this->createTime = $this->stdResult->{"createTime"};
    			}
    			    		    				    			    			if (array_key_exists ( "status", $this->stdResult )) {
    				$this->status = $this->stdResult->{"status"};
    			}
    			    		    				    			    			if (array_key_exists ( "gracePeriodEndTime", $this->stdResult )) {
    				$this->gracePeriodEndTime = $this->stdResult->{"gracePeriodEndTime"};
    			}
    			    		    				    			    			if (array_key_exists ( "statusStr", $this->stdResult )) {
    				$this->statusStr = $this->stdResult->{"statusStr"};
    			}
    			    		    				    			    			if (array_key_exists ( "restRepayAmount", $this->stdResult )) {
    				$this->restRepayAmount = $this->stdResult->{"restRepayAmount"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    			if (array_key_exists ( "payAmount", $this->arrayResult )) {
    			$this->payAmount = $arrayResult['payAmount'];
    			}
    		    	    			    		    			if (array_key_exists ( "createTime", $this->arrayResult )) {
    			$this->createTime = $arrayResult['createTime'];
    			}
    		    	    			    		    			if (array_key_exists ( "status", $this->arrayResult )) {
    			$this->status = $arrayResult['status'];
    			}
    		    	    			    		    			if (array_key_exists ( "gracePeriodEndTime", $this->arrayResult )) {
    			$this->gracePeriodEndTime = $arrayResult['gracePeriodEndTime'];
    			}
    		    	    			    		    			if (array_key_exists ( "statusStr", $this->arrayResult )) {
    			$this->statusStr = $arrayResult['statusStr'];
    			}
    		    	    			    		    			if (array_key_exists ( "restRepayAmount", $this->arrayResult )) {
    			$this->restRepayAmount = $arrayResult['restRepayAmount'];
    			}
    		    	    		}
 
   
}
?>