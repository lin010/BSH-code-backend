<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');
include_once ('AlibabaCpsOpMediaUserRecommendOfferServiceParam/AlibabaPpFaasGinkgoLibCommonDtoPageResultDTO.class.php');

class AlibabaCpsOpMediaUserRecommendOfferServiceResult {

        	
    private $result;
    
        /**
    * @return 商品列表，个性化推荐流，没有total返回，翻页到空结果为结束
    */
        public function getResult() {
        return $this->result;
    }
    
    /**
     * 设置商品列表，个性化推荐流，没有total返回，翻页到空结果为结束     
     * @param AlibabaPpFaasGinkgoLibCommonDtoPageResultDTO $result     
          
     * 此参数必填     */
    public function setResult(AlibabaPpFaasGinkgoLibCommonDtoPageResultDTO $result) {
        $this->result = $result;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "result", $this->stdResult )) {
    				$resultResult=$this->stdResult->{"result"};
    				$this->result = new AlibabaPpFaasGinkgoLibCommonDtoPageResultDTO();
    				$this->result->setStdResult ( $resultResult);
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    		if (array_key_exists ( "result", $this->arrayResult )) {
    		$resultResult=$arrayResult['result'];
    			    			$this->result = new AlibabaPpFaasGinkgoLibCommonDtoPageResultDTO();
    			    			$this->result->setStdResult ( $resultResult);
    		}
    		    	    		}

}
?>