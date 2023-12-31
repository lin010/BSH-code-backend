<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');
include_once ('AlibabaTradeRefundBuyerQueryOrderRefundListParam/AlibabaTradeRefundOpOrderRefundModel.class.php');

class AlibabaTradeRefundOpQueryOrderRefundListResult extends SDKDomain {

       	
    private $opOrderRefundModels;
    
        /**
    * @return 退款单列表
    */
        public function getOpOrderRefundModels() {
        return $this->opOrderRefundModels;
    }
    
    /**
     * 设置退款单列表     
     * @param array include @see AlibabaTradeRefundOpOrderRefundModel[] $opOrderRefundModels     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setOpOrderRefundModels(AlibabaTradeRefundOpOrderRefundModel $opOrderRefundModels) {
        $this->opOrderRefundModels = $opOrderRefundModels;
    }
    
        	
    private $totalCount;
    
        /**
    * @return 符合条件总的记录条数
    */
        public function getTotalCount() {
        return $this->totalCount;
    }
    
    /**
     * 设置符合条件总的记录条数     
     * @param Integer $totalCount     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setTotalCount( $totalCount) {
        $this->totalCount = $totalCount;
    }
    
        	
    private $currentPageNum;
    
        /**
    * @return 查询的当前页码
    */
        public function getCurrentPageNum() {
        return $this->currentPageNum;
    }
    
    /**
     * 设置查询的当前页码     
     * @param Integer $currentPageNum     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setCurrentPageNum( $currentPageNum) {
        $this->currentPageNum = $currentPageNum;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "opOrderRefundModels", $this->stdResult )) {
    			$opOrderRefundModelsResult=$this->stdResult->{"opOrderRefundModels"};
    				$object = json_decode ( json_encode ( $opOrderRefundModelsResult ), true );
					$this->opOrderRefundModels = array ();
					for($i = 0; $i < count ( $object ); $i ++) {
						$arrayobject = new ArrayObject ( $object [$i] );
						$AlibabaTradeRefundOpOrderRefundModelResult=new AlibabaTradeRefundOpOrderRefundModel();
						$AlibabaTradeRefundOpOrderRefundModelResult->setArrayResult($arrayobject );
						$this->opOrderRefundModels [$i] = $AlibabaTradeRefundOpOrderRefundModelResult;
					}
    			}
    			    		    				    			    			if (array_key_exists ( "totalCount", $this->stdResult )) {
    				$this->totalCount = $this->stdResult->{"totalCount"};
    			}
    			    		    				    			    			if (array_key_exists ( "currentPageNum", $this->stdResult )) {
    				$this->currentPageNum = $this->stdResult->{"currentPageNum"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    		if (array_key_exists ( "opOrderRefundModels", $this->arrayResult )) {
    		$opOrderRefundModelsResult=$arrayResult['opOrderRefundModels'];
    			$this->opOrderRefundModels = new AlibabaTradeRefundOpOrderRefundModel();
    			$this->opOrderRefundModels->setStdResult ( $opOrderRefundModelsResult);
    		}
    		    	    			    		    			if (array_key_exists ( "totalCount", $this->arrayResult )) {
    			$this->totalCount = $arrayResult['totalCount'];
    			}
    		    	    			    		    			if (array_key_exists ( "currentPageNum", $this->arrayResult )) {
    			$this->currentPageNum = $arrayResult['currentPageNum'];
    			}
    		    	    		}
 
   
}
?>