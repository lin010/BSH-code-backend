<?php


namespace app\plugins\meituan\helpers;


class Req{

    private function getContent(){
        $params = [
            'ts' => '',
            'entId' => '',

        ];
    }

    public function doPost($params){
        $result = [];

        try {
            //$sign = static::getSign($params, $this->apikey);
            //$params['sign'] = $sign;

            $newParams['accessKey'] = "123";
            $newParams['content'] = $this->getContent();

            $headers = [
                'Content-Type：application/x-www-form-urlencoded'
            ];

            $ch = curl_init($this->host . $uri);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $newParams);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            $errno  = curl_errno($ch);
            $error  = curl_error($ch);

            if(!empty($error)){
                throw new \Exception($error);
            }


        }catch (\Exception $e){

        }
        return $result;
    }

    /**
     * 生成签名
     * @param $params
     * @param $apiKey
     * @return string
     */
    public static function getSign(&$params, $apiKey){
        foreach($params as $key => $val){
            $val = trim($val);
            if(empty($val)){
                unset($params[$key]);
            }
        }
        ksort($params);
        $signString = "";
        foreach($params as $key => $val){
            $signString .= "{$key}={$val}&";
        }
        $signString = substr($signString, 0, -1);
        $signString .= "&apikey={$apiKey}";
        $sign = strtoupper(md5($signString));
        return $sign;
    }
}