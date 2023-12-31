<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');

class AlibabaTradePromotionModel extends SDKDomain {

       	
    private $promotionId;
    
        /**
    * @return 优惠券ID
    */
        public function getPromotionId() {
        return $this->promotionId;
    }
    
    /**
     * 设置优惠券ID     
     * @param String $promotionId     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setPromotionId( $promotionId) {
        $this->promotionId = $promotionId;
    }
    
        	
    private $selected;
    
        /**
    * @return 是否默认选中
    */
        public function getSelected() {
        return $this->selected;
    }
    
    /**
     * 设置是否默认选中     
     * @param Boolean $selected     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setSelected( $selected) {
        $this->selected = $selected;
    }
    
        	
    private $text;
    
        /**
    * @return 优惠券名称
    */
        public function getText() {
        return $this->text;
    }
    
    /**
     * 设置优惠券名称     
     * @param String $text     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setText( $text) {
        $this->text = $text;
    }
    
        	
    private $desc;
    
        /**
    * @return 优惠券描述
    */
        public function getDesc() {
        return $this->desc;
    }
    
    /**
     * 设置优惠券描述     
     * @param String $desc     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setDesc( $desc) {
        $this->desc = $desc;
    }
    
        	
    private $freePostage;
    
        /**
    * @return 是否免邮
    */
        public function getFreePostage() {
        return $this->freePostage;
    }
    
    /**
     * 设置是否免邮     
     * @param Boolean $freePostage     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setFreePostage( $freePostage) {
        $this->freePostage = $freePostage;
    }
    
        	
    private $discountFee;
    
        /**
    * @return 减去金额，单位为分
    */
        public function getDiscountFee() {
        return $this->discountFee;
    }
    
    /**
     * 设置减去金额，单位为分     
     * @param Long $discountFee     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setDiscountFee( $discountFee) {
        $this->discountFee = $discountFee;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "promotionId", $this->stdResult )) {
    				$this->promotionId = $this->stdResult->{"promotionId"};
    			}
    			    		    				    			    			if (array_key_exists ( "selected", $this->stdResult )) {
    				$this->selected = $this->stdResult->{"selected"};
    			}
    			    		    				    			    			if (array_key_exists ( "text", $this->stdResult )) {
    				$this->text = $this->stdResult->{"text"};
    			}
    			    		    				    			    			if (array_key_exists ( "desc", $this->stdResult )) {
    				$this->desc = $this->stdResult->{"desc"};
    			}
    			    		    				    			    			if (array_key_exists ( "freePostage", $this->stdResult )) {
    				$this->freePostage = $this->stdResult->{"freePostage"};
    			}
    			    		    				    			    			if (array_key_exists ( "discountFee", $this->stdResult )) {
    				$this->discountFee = $this->stdResult->{"discountFee"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    			if (array_key_exists ( "promotionId", $this->arrayResult )) {
    			$this->promotionId = $arrayResult['promotionId'];
    			}
    		    	    			    		    			if (array_key_exists ( "selected", $this->arrayResult )) {
    			$this->selected = $arrayResult['selected'];
    			}
    		    	    			    		    			if (array_key_exists ( "text", $this->arrayResult )) {
    			$this->text = $arrayResult['text'];
    			}
    		    	    			    		    			if (array_key_exists ( "desc", $this->arrayResult )) {
    			$this->desc = $arrayResult['desc'];
    			}
    		    	    			    		    			if (array_key_exists ( "freePostage", $this->arrayResult )) {
    			$this->freePostage = $arrayResult['freePostage'];
    			}
    		    	    			    		    			if (array_key_exists ( "discountFee", $this->arrayResult )) {
    			$this->discountFee = $arrayResult['discountFee'];
    			}
    		    	    		}
 
   
}
?>