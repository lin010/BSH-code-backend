<?php


namespace app\plugins\addcredit\plateform\sdk\dayuanren;


class Req{

    public $host;
    public $apikey;

    public function __construct($host, $apikey) {
        $this->host   = $host;
        $this->apikey = $apikey;
    }

    public function doPost($uri, $params){
        $result = [];

        try {
            $sign = static::getSign($params, $this->apikey);
            $params['sign'] = $sign;

            $headers = [
                'Content-Type：application/x-www-form-urlencoded'
            ];

            $ch = curl_init($this->host . $uri);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            $result['request_data']     = json_encode($params);
            $result['response_content'] = @curl_exec($ch);
            $result['message']          = "";

            $errno  = curl_errno($ch);
            $error  = curl_error($ch);

            if(!empty($error)){
                throw new \Exception($error);
            }

            $data = @json_decode($result['response_content'], true);

            $result['code'] = Code::SUCCESS;
            $result['data'] = isset($data['data']) ? $data['data'] : null;
        }catch (\Exception $e){
            $result['code']    = Code::FAIL;
            $result['message'] = $e->getMessage();
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