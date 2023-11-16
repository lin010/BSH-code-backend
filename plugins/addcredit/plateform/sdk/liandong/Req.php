<?php


namespace app\plugins\addcredit\plateform\sdk\liandong;


class Req{

    public $host;
    public $secretKey;

    public function __construct($host, $secretKey) {
        $this->host       = $host;
        $this->secretKey  = $secretKey;
    }

    public function doPost($uri, $params){
        $result = [];

        try {
            $sign = static::getSign($params, $this->secretKey);
            $params['sign'] = $sign;

            $headers = [
                'Content-Type: application/json; charset=utf-8'
            ];

            $ch = curl_init($this->host . $uri);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
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
     * @param $secretKey
     * @return string
     */
    public static function getSign(&$params, $secretKey){
        ksort($params);
        $signString = "";
        foreach($params as $key => $val){
            $signString .= "{$key}{$val}";
        }
        $signString .= $secretKey;
        $sign = strtolower(md5($signString));

        return $sign;
    }
}