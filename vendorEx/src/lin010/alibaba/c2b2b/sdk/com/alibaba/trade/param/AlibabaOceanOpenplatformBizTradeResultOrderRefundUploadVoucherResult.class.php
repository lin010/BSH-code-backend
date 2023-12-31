<?php

include_once ('com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('com/alibaba/openapi/client/entity/ByteArray.class.php');

class AlibabaOceanOpenplatformBizTradeResultOrderRefundUploadVoucherResult extends SDKDomain {

       	
    private $imageDomain;
    
        /**
    * @return 图片域名
    */
        public function getImageDomain() {
        return $this->imageDomain;
    }
    
    /**
     * 设置图片域名     
     * @param String $imageDomain     
     * 参数示例：<pre> </pre>     
     * 此参数必填     */
    public function setImageDomain( $imageDomain) {
        $this->imageDomain = $imageDomain;
    }
    
        	
    private $imageRelativeUrl;
    
        /**
    * @return 图片相对路径
    */
        public function getImageRelativeUrl() {
        return $this->imageRelativeUrl;
    }
    
    /**
     * 设置图片相对路径     
     * @param String $imageRelativeUrl     
     * 参数示例：<pre> </pre>     
     * 此参数必填     */
    public function setImageRelativeUrl( $imageRelativeUrl) {
        $this->imageRelativeUrl = $imageRelativeUrl;
    }
    
    	
	private $stdResult;
	
	public function setStdResult($stdResult) {
		$this->stdResult = $stdResult;
					    			    			if (array_key_exists ( "imageDomain", $this->stdResult )) {
    				$this->imageDomain = $this->stdResult->{"imageDomain"};
    			}
    			    		    				    			    			if (array_key_exists ( "imageRelativeUrl", $this->stdResult )) {
    				$this->imageRelativeUrl = $this->stdResult->{"imageRelativeUrl"};
    			}
    			    		    		}
	
	private $arrayResult;
	public function setArrayResult($arrayResult) {
		$this->arrayResult = $arrayResult;
				    		    			if (array_key_exists ( "imageDomain", $this->arrayResult )) {
    			$this->imageDomain = $arrayResult['imageDomain'];
    			}
    		    	    			    		    			if (array_key_exists ( "imageRelativeUrl", $this->arrayResult )) {
    			$this->imageRelativeUrl = $arrayResult['imageRelativeUrl'];
    			}
    		    	    		}
 
   
}
?>