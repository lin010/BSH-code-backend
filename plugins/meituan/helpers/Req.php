<?php


namespace app\plugins\meituan\helpers;


class Req{


    public static function post($url, $params){

        try {
            /*$headers = [
                'Content-Type：application/x-www-form-urlencoded'
            ];*/

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            $result = curl_exec($ch);

            $errno  = curl_errno($ch);
            $error  = curl_error($ch);

            if(!empty($error)){
                throw new \Exception($error);
            }

            return !empty($result) ? json_decode($result, true) : [];
        }catch (\Exception $e){
            return null;
        }
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