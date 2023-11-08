<?php


namespace app\plugins\meituan\helpers;


class Aes
{
    /**
     * 加密
     * @param $contents
     * @param $secretKey
     * @return string
     */
    public static function encrypt($contents, $secretKey){
        $encryptContent = openssl_encrypt(json_encode($contents,JSON_UNESCAPED_UNICODE), 'AES-128-ECB', base64_decode($secretKey));
        $encryptContent = str_replace('/', '_', $encryptContent);
        $encryptContent = str_replace('+', '-', $encryptContent);
        $encryptContent = str_replace('=', '', $encryptContent);
        return $encryptContent;
    }

    /**
     * 解密
     * @param $str
     * @param $secretKey
     * @return mixed
     */
    public static function decrypt($str, $secretKey){
        $str = str_replace('_', '/', $str);
        $str = str_replace('-', '+', $str);
        $data = openssl_decrypt($str, 'AES-128-ECB', base64_decode($secretKey));
        return json_decode($data, TRUE);
    }
}