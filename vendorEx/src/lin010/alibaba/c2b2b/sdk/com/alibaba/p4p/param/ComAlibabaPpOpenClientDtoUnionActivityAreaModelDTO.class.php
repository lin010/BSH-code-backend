<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');

class ComAlibabaPpOpenClientDtoUnionActivityAreaModelDTO extends SDKDomain {

       	
    private $code;
    
        /**
    * @return code
    */
        public function getCode() {
        return $this->code;
    }
    
    /**
     * 设置code     
     * @param String $code     
     * 参数示例：<pre>0000</pre>     
     * 此参数必填     */
    public function setCode( $code) {
        $this->code = $code;
    }
    
        	
    private $name;
    
        /**
    * @return name
    */
        public function getName() {
        return $this->name;
    }
    
    /**
     * 设置name     
     * @param String $name     
     * 参数示例：<pre>地区</pre>     
     * 此参数必填     */
    public function setName( $name) {
        $this->name = $name;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "code", $this->stdResult )) {
    				$this->code = $this->stdResult->{"code"};
    			}
    			    		    				    			    			if (array_key_exists ( "name", $this->stdResult )) {
    				$this->name = $this->stdResult->{"name"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    			if (array_key_exists ( "code", $this->arrayResult )) {
    			$this->code = $arrayResult['code'];
    			}
    		    	    			    		    			if (array_key_exists ( "name", $this->arrayResult )) {
    			$this->name = $arrayResult['name'];
    			}
    		    	    		}
 
   
}
?>