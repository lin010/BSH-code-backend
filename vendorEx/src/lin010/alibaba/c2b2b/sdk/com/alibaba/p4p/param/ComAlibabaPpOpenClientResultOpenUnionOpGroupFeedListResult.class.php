<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');
include_once ('AlibabaCpsOpListCybUserGroupFeedParam/ComAlibabaPpOpenClientDtoOpenUnionOpGroupFeedDTO.class.php');

class ComAlibabaPpOpenClientResultOpenUnionOpGroupFeedListResult extends SDKDomain {

       	
    private $resultList;
    
        /**
    * @return 列表
    */
        public function getResultList() {
        return $this->resultList;
    }
    
    /**
     * 设置列表     
     * @param array include @see ComAlibabaPpOpenClientDtoOpenUnionOpGroupFeedDTO[] $resultList     
     * 参数示例：<pre>[]</pre>     
     * 此参数必填     */
    public function setResultList(ComAlibabaPpOpenClientDtoOpenUnionOpGroupFeedDTO $resultList) {
        $this->resultList = $resultList;
    }
    
        	
    private $totalRow;
    
        /**
    * @return 总数
    */
        public function getTotalRow() {
        return $this->totalRow;
    }
    
    /**
     * 设置总数     
     * @param Integer $totalRow     
     * 参数示例：<pre>12</pre>     
     * 此参数必填     */
    public function setTotalRow( $totalRow) {
        $this->totalRow = $totalRow;
    }
    
        	
    private $success;
    
        /**
    * @return 状态
    */
        public function getSuccess() {
        return $this->success;
    }
    
    /**
     * 设置状态     
     * @param Boolean $success     
     * 参数示例：<pre>true</pre>     
     * 此参数必填     */
    public function setSuccess( $success) {
        $this->success = $success;
    }
    
        	
    private $errorMsg;
    
        /**
    * @return success=false时，错误Msg
    */
        public function getErrorMsg() {
        return $this->errorMsg;
    }
    
    /**
     * 设置success=false时，错误Msg     
     * @param String $errorMsg     
     * 参数示例：<pre>msg</pre>     
     * 此参数必填     */
    public function setErrorMsg( $errorMsg) {
        $this->errorMsg = $errorMsg;
    }
    
        	
    private $errorCode;
    
        /**
    * @return success=false时，错误code
    */
        public function getErrorCode() {
        return $this->errorCode;
    }
    
    /**
     * 设置success=false时，错误code     
     * @param String $errorCode     
     * 参数示例：<pre>code</pre>     
     * 此参数必填     */
    public function setErrorCode( $errorCode) {
        $this->errorCode = $errorCode;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "resultList", $this->stdResult )) {
    			$resultListResult=$this->stdResult->{"resultList"};
    				$object = json_decode ( json_encode ( $resultListResult ), true );
					$this->resultList = array ();
					for($i = 0; $i < count ( $object ); $i ++) {
						$arrayobject = new ArrayObject ( $object [$i] );
						$ComAlibabaPpOpenClientDtoOpenUnionOpGroupFeedDTOResult=new ComAlibabaPpOpenClientDtoOpenUnionOpGroupFeedDTO();
						$ComAlibabaPpOpenClientDtoOpenUnionOpGroupFeedDTOResult->setArrayResult($arrayobject );
						$this->resultList [$i] = $ComAlibabaPpOpenClientDtoOpenUnionOpGroupFeedDTOResult;
					}
    			}
    			    		    				    			    			if (array_key_exists ( "totalRow", $this->stdResult )) {
    				$this->totalRow = $this->stdResult->{"totalRow"};
    			}
    			    		    				    			    			if (array_key_exists ( "success", $this->stdResult )) {
    				$this->success = $this->stdResult->{"success"};
    			}
    			    		    				    			    			if (array_key_exists ( "errorMsg", $this->stdResult )) {
    				$this->errorMsg = $this->stdResult->{"errorMsg"};
    			}
    			    		    				    			    			if (array_key_exists ( "errorCode", $this->stdResult )) {
    				$this->errorCode = $this->stdResult->{"errorCode"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    		if (array_key_exists ( "resultList", $this->arrayResult )) {
    		$resultListResult=$arrayResult['resultList'];
    			$this->resultList = new ComAlibabaPpOpenClientDtoOpenUnionOpGroupFeedDTO();
    			$this->resultList->setStdResult ( $resultListResult);
    		}
    		    	    			    		    			if (array_key_exists ( "totalRow", $this->arrayResult )) {
    			$this->totalRow = $arrayResult['totalRow'];
    			}
    		    	    			    		    			if (array_key_exists ( "success", $this->arrayResult )) {
    			$this->success = $arrayResult['success'];
    			}
    		    	    			    		    			if (array_key_exists ( "errorMsg", $this->arrayResult )) {
    			$this->errorMsg = $arrayResult['errorMsg'];
    			}
    		    	    			    		    			if (array_key_exists ( "errorCode", $this->arrayResult )) {
    			$this->errorCode = $arrayResult['errorCode'];
    			}
    		    	    		}
 
   
}
?>