<?php

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    $tokenUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx128948e34645cbd0&secret=ec31b991d2d11d5823b67c0191895937";
    $getArr = [];
    $postdata = http_build_query($getArr);
    $options = array(
        'http' => array(
            'method' => "GET", //or GET
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($tokenUrl, false, $context);
//    return $result;
//    $this->getResponse()->redirect(Url::to(array('index')));
    echo $result;
//    die('echo');
//    die('You are not allowed to access this file!!!'.$tokenUrl);
}

/*defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/test.php';

(new yii\web\Application($config))->run();*/
//use yii\helpers\CurlRequest;


//public function SetToken()
//{
////        $tokenUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $config['app_id'] . "&secret=" . $config['secret'];
//    $tokenUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx128948e34645cbd0&secret=ec31b991d2d11d5823b67c0191895937";
//    $getArr = [];
//    $tokenArr = json_decode($this->send_post($tokenUrl, $getArr, "GET"));
//    $access_token = $tokenArr->access_token;
//    \Yii::$app->redis->set('app/forms/common::wechat:getToken', $access_token);
//    \Yii::$app->redis->expire('app/forms/common::wechat:getToken', 5400);
//    return $access_token;
//}
//
//function send_post($url, $post_data, $method = 'POST')
//{
//    $postdata = http_build_query($post_data);
//    $options = array(
//        'http' => array(
//            'method' => $method, //or GET
//            'header' => 'Content-type:application/x-www-form-urlencoded',
//            'content' => $postdata,
//            'timeout' => 15 * 60 // 超时时间（单位:s）
//        )
//    );
//    $context = stream_context_create($options);
//    $result = file_get_contents($url, false, $context);
//    return $result;
//}