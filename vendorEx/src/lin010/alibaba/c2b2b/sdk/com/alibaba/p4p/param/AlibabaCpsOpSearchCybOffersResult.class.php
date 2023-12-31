<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');
include_once ('AlibabaCpsOpSearchCybOffersParam/AlibabaAscXuanwuCoreDtoPageResultDTO.class.php');

class AlibabaCpsOpSearchCybOffersResult {

        	
    private $result;
    
        /**
    * @return 结果
    */
        public function getResult() {
        return $this->result;
    }
    
    /**
     * 设置结果     
     * @param AlibabaAscXuanwuCoreDtoPageResultDTO $result     
          
     * 此参数必填     */
    public function setResult(AlibabaAscXuanwuCoreDtoPageResultDTO $result) {
        $this->result = $result;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "result", $this->stdResult )) {
    				$resultResult=$this->stdResult->{"result"};
    				$this->result = new AlibabaAscXuanwuCoreDtoPageResultDTO();
    				$this->result->setStdResult ( $resultResult);
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    		if (array_key_exists ( "result", $this->arrayResult )) {
    		$resultResult=$arrayResult['result'];
    			    			$this->result = new AlibabaAscXuanwuCoreDtoPageResultDTO();
    			    			$this->result->setStdResult ( $resultResult);
    		}
    		    	    		}

}
?>