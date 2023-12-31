<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * Created by PhpStorm
 * Author: zal
 * Date: 2020-04-17
 * Time: 20:01
 */

namespace app\controllers\api;

use app\controllers\api\filters\BlackListFilter;
use app\controllers\api\filters\MallDisabledFilter;
use app\controllers\BaseController;
use app\controllers\behavior\AddTagBehavior;
use app\controllers\behavior\BusinessCardBehavior;
use app\controllers\behavior\TrackBehavior;
use app\events\UserInfoEvent;
use app\forms\api\LoginForm;
use app\handlers\HandlerRegister;
use app\handlers\RelationHandler;
use app\helpers\TencentMapHelper;
use app\logic\CommonLogic;
use app\logic\RelationLogic;
use app\models\DistrictData;
use app\models\Formid;
use app\models\Mall;
use app\models\MallSetting;
use app\models\User;
use app\models\UserInfo;
use Exception;
use yii\web\NotFoundHttpException;

class ApiController extends BaseController
{
    /** @var array 处理后的请求数据 */
    public $requestData = [];

    /**
     * @var User $user
     */
    private $user;

    public static $wechatIsSubscribe = 0; //是否已关注微信

    public static $commonData = [];

    public function beforeAction($action)
    {
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function asJson($data)
    {
        if(is_array($data)){
            $data = array_merge($data, static::$commonData);
        }
        return parent::asJson($data);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'disabled' => [
                'class' => MallDisabledFilter::class,
            ],
            'blackList' => [
                'class' => BlackListFilter::class
            ],
            'track' =>[
                'class' => TrackBehavior::class,
                'params' => array_merge($this->requestData,\yii\helpers\ArrayHelper::toArray(\Yii::$app->request->headers)),
            ],
            'addTag' =>[
                'class' => AddTagBehavior::class,
                'params' => array_merge($this->requestData,\yii\helpers\ArrayHelper::toArray(\Yii::$app->request->headers)),
            ]
        ]);
    }

    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
        \Yii::$app->user->enableAutoLogin = false;
        $this->enableCsrfValidation = false;
        $headers = \Yii::$app->request->headers;

        $this->getParamsData();
        //TODO 临时应付小程序上线审核处理 2021/08/13
        $devUrl = "mpwx.mingyuanriji.cn";
        if($headers['x-app-platform'] == "mp-wx" && \Yii::$app->getRequest()->getHostName() != $devUrl){
            $appId          = isset($headers['x-mp-appid']) ? $headers['x-mp-appid'] : "";
            $version        = isset($headers['x-mp-version']) ? $headers['x-mp-version'] : "";
            $path = dirname(ROOT_PATH) . "/runtime/mp-wx";
            !is_dir($path) && mkdir($path, 0755, true);
            $currentVersion = @file_get_contents("{$path}/{$appId}.version");
            if(false && (empty($version) || $version != trim($currentVersion))){
                @file_put_contents("{$path}/{$appId}.debug", json_encode([
                    "version" => $version
                ]));
                //重定向到测试地址
                $curl = new \Curl\Curl();
                foreach($headers as $name => $values){
                    if(substr($name, 0, 2) == "x-"){
                        $value = is_array($values) ? array_shift($values) : "";
                        $curl->setHeader($name, $value);
                    }
                }
                $url = str_replace(\Yii::$app->getRequest()->getHostName(), $devUrl, \Yii::$app->getRequest()->getAbsoluteUrl());
                $url = preg_replace("/https?/i", "https", $url);
                $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
                $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
                $curl->setOpt(CURLOPT_CONNECTTIMEOUT, 10);
                $curl->setOpt(CURLOPT_TIMEOUT, 15);
                $curl->post($url, $this->requestData);
                header('Content-Type:application/json; charset=utf-8');
                die($curl->getResponse());
            }
        }

        $this->setMall($headers)->setCity($headers)->login($headers)->wechatSubscribe()->saveFormIdList($headers)->bindParent($headers)->checkInviter();

        /*$lng = "113.1172052002";
        $lat = "23.017962033827";
        $info = TencentMapHelper::toPoi("https://dev.mingyuanriji.cn", $lng, $lat);
        print_r($info);
        exit;*/
    }

    /**
     * 检测是否是具备发展下线资格（是否是邀请者）
     * @return $this
     */
    private function checkInviter()
    {
        $user = $this->user;
        if (!$user) {
            return $this;
        }
        if ($user->is_inviter) {
            \Yii::$app->trigger(HandlerRegister::TO_USER_UPGRADE, new UserInfoEvent(['mall_id' =>$this->mall_id, 'user_id' => $user->id]));
            return $this;
        }
        \Yii::$app->trigger(RelationHandler::USER_INVITER_UPDATE, new UserInfoEvent([
            'user_id' => $user->id,
            'mall_id' => $this->mall_id,
        ]));
//        $relation = RelationSetting::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'use_relation' => 1, 'get_power_way' => RelationSetting::GET_POWER_WAY_NO_CONDITION]);
//        if (!$relation) {
//            return $this;
//        } else {
//            $user->is_inviter = 1;
//            $user->inviter_at = time();
//            $user->save();
//        }
        return $this;
    }

    /**
     * 设置城市信息
     * @return $this
     */
    private function setCity($headers)
    {
        $cityData = [
            'sel_city'      => '',
            'city_id'       => 0,
            'province_id'   => 0,
            'district_id'   => 0,
            'province'      => '',
            'city'          => '',
            'district'      => '',
            'longitude'     => '113.265953',
            'latitude'      => '23.140281',
            'address'       => '',
            'pois'          => []
        ];

        $key = \Yii::$app->params['qqMapApiKey'];

        $longitude = !empty($headers['x-longitude']) ? $headers['x-longitude'] : null;
        $latitude = !empty($headers['x-latitude']) ? $headers['x-latitude'] : null;
        $selCityId = !empty($headers['x-city-id']) ? $headers['x-city-id'] : null;

        $pattern = "/^\d+\.\d+$/";
        if(preg_match($pattern, $latitude) && preg_match($pattern, $longitude)){
            $cityData['longitude'] = $longitude;
            $cityData['latitude']  = $latitude;
        }

        $cacheKey = "city_location:" . md5($cityData['longitude'] . $cityData['latitude']);
        $cache = \Yii::$app->getCache();
        $cacheData = $cache->get($cacheKey);

        $districtList = DistrictData::getArr();

        if(empty($cacheData) || empty($cacheData['city'])){
            $url = "https://apis.map.qq.com/ws/geocoder/v1/?location=".$cityData['latitude'].",".$cityData['longitude']."&key={$key}&get_poi=1";

            $hostInfo = \Yii::$app->getRequest()->getHostInfo();
            $hostInfo = "https://www.mingyuanriji.cn";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_REFERER, $hostInfo);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $result = @curl_exec($ch);
            @curl_close($ch);

            $data = !empty($result) ? json_decode($result, true) : null;
            if(isset($data['status']) && $data['status'] == 0){
                $cityData['province'] = $data['result']['address_component']['province'];
                $cityData['city'] = $data['result']['address_component']['city'];
                $cityData['district'] = $data['result']['address_component']['district'];
                $cityData['street'] = $data['result']['address_component']['street_number'];


                //获取省份ID
                foreach($districtList as $district){
                    if($district['level'] == "province" && $district['name'] == $cityData['province']) {
                        $cityData['province_id'] = $district['id'];
                        break;
                    }
                }

                //获取城市ID
                foreach($districtList as $district){
                    if($district['parent_id'] == $cityData['province_id'] && $district['name'] == $cityData['city']) {
                        $cityData['city_id'] = $district['id'];
                        break;
                    }
                }

                //获取区域ID
                foreach($districtList as $district){
                    if($district['parent_id'] == $cityData['city_id'] && $district['name'] == $cityData['district']) {
                        $cityData['district_id'] = $district['id'];
                        break;
                    }
                }

                foreach($data['result']['pois'] as $item){
                    $cityData['result']['pois'][] = [
                        'address'  => $item['address'],
                        'location' => $item['location']
                    ];
                }
            }

            $cache->set($cacheKey, $cityData, 600);
        }else{
            $cityData = $cacheData;
        }

        if(!empty($selCityId) && $selCityId > 0){
            $district = DistrictData::getDistrict($selCityId);
            if($district){
                $cityData['sel_city'] = $district->name;
            }
        }else{
            $cityData['sel_city'] = $cityData['city'];
        }

        static::$commonData['city_data'] = $cityData;

        return $this;
    }

    /**
     * 微信关注
     * @return $this
     */
    private function wechatSubscribe()
    {

        $isSubscribe = 0;

        if($this->user){
            $cacheObject = \Yii::$app->getCache();
            $cacheKey = "user_wechat_subscript:" . $this->user->id;
            $isSubscribe = (int)$cacheObject->get($cacheKey);
            $wechatModel = \Yii::$app->wechat;
            if(($wechatModel->isWechat)){

                if(!$isSubscribe){
                    $userInfo = UserInfo::findOne([
                        "user_id"  => $this->user->id,
                        "platform" => "wechat"
                    ]);
                    if($userInfo){
                        $info = $wechatModel->app->user->get($userInfo->openid);
                        if(isset($info['subscribe']) && $info['subscribe'] == 1){ //
                            $isSubscribe = 1;
                        }else{ //未关注
                            $isSubscribe = 0;
                        }
                        if($isSubscribe){
                            $cacheObject->set($cacheKey, 1, 3600 * 2);
                        }
                    }
                }

            }
        }

        static::$commonData['wechat_subscribe'] = $isSubscribe;

        return $this;
    }

    /**
     * 设置商城
     * @param $headers
     * @return $this
     * @throws NotFoundHttpException
     */
    private function setMall($headers)
    {
        /** @var $acid 第三方传过来的商城id参数 */
        $acid = empty($headers['acid']) ? null : $headers['acid'];
        if ($acid && $acid > 0) {
            $we7app = We7App::findOne([
                'acid' => $acid,
                'is_delete' => 0,
            ]);
            $mallId = $we7app ? $we7app->mall_id : null;
        } else {
            $mallId = $this->mall_id;
        }

        $mall = Mall::findOne([
            'id' => $mallId,
            'is_delete' => 0,
            'is_recycle' => 0,
        ]);
        if (!$mall) {
            \Yii::$app->removeSessionJxMallId();
            throw new NotFoundHttpException('商城不存在，id = ' . $mallId);
        }
        \Yii::$app->setMallId($mallId);
        \Yii::$app->setMall($mall);
        return $this;
    }

    /**
     * 登录
     * @param $headers
     * @return $this
     * @throws NotFoundHttpException
     */
    private function login($headers)
    {
        $this->setMall($headers);
        if (isset($headers["x-access-token"])) {
            $accessToken = $headers["x-access-token"];
        } else {
            $accessToken = isset($headers['accessToken']) ? $headers['accessToken'] : "";
        }
        if (empty($accessToken)) {
            return $this;
        }
        /**
         * @var User $user
         */
        $user = User::findIdentityByAccessToken($accessToken);
        if ($user) {
            $this->user = $user;
            //已经登录了，不再登录
            if (\Yii::$app->user->isGuest || \Yii::$app->user->id != $user->id) {
                \Yii::$app->user->login($user);
            }


        } else {
            \Yii::$app->user->logout();
        }
        return $this;
    }

    /**
     * 保存表单
     * @param $headers
     * @return $this
     */
    private function saveFormIdList($headers)
    {
        if (\Yii::$app->user->isGuest) {
            return $this;
        }
        if (empty($headers['x-form-id-list'])) {
            return $this;
        }
        $rawData = $headers['x-form-id-list'];
        $list = json_decode($rawData, true);
        if (!$list || !is_array($list) || !count($list)) {
            return $this;
        }
        foreach ($list as $item) {
            $formid = new Formid();
            $formid->user_id = \Yii::$app->user->id;
            $formid->form_id = $item['value'];
            $formid->remains = $item['remains'];
            $formid->expired_at = $item['expires_at'];
            $formid->save();
        }
        return $this;
    }

    /**
     * @Author: 广东七件事 ganxiaohao
     * @Date: 2020-05-15
     * @Time: 10:10
     * @Note:绑定上级
     * @param $headers
     * @return $this
     */
    private function bindParent($headers)
    {
        if (\Yii::$app->user->isGuest) {
            return $this;
        }
        $parentId = empty($headers['x-parent-id']) ? null : $headers['x-parent-id'];

        if (!$parentId) {
            return $this;
        }
        $user = $this->user;
        if (!$user) {
            return $this;
        }

        try {
            RelationLogic::bindParent($user, $parentId);
            static::$commonData['clean_bind_parent'] = 1;
        } catch (\Exception $exception) {
            \Yii::error("apiController bindParent parent_id={$parentId} error ".$exception->getMessage());
            if(\Yii::$app->user->identity->id != $parentId){
                $user->temp_parent_id = $parentId;
                $user->save();
            }
        }
        return $this;
    }

    /**
     * 不同平台授权登录
     * @return LoginForm
     * @throws \Exception
     */
    protected function getAuthLoginForm()
    {
        $appPlatform = \Yii::$app->appPlatform;
        $Class = null;
        if ($appPlatform === APP_PLATFORM_MP_WX) {
            $Class = 'app\\plugins\\wxapp\\models\\LoginForm';
        }
        if ($appPlatform === APP_PLATFORM_MP_ALI) {
            $Class = 'app\\plugins\\aliapp\\models\\LoginForm';
        }
        if ($appPlatform === APP_PLATFORM_MP_BD) {
            $Class = 'app\\plugins\\bdapp\\models\\LoginForm';
        }
        if ($appPlatform === APP_PLATFORM_MP_TT) {
            $Class = 'app\\plugins\\ttapp\\models\\LoginForm';
        }
        if (!$Class || !class_exists($Class)) {
            throw new \Exception('未安装相关平台的插件或未知的客户端平台，平台标识`' . ($appPlatform ? $appPlatform : 'null') . '`');
        }
        return new $Class();
    }

    /**
     * 获取传过来的参数
     * @throws Exception
     */
    protected function getParamsData()
    {
        //接收传过来的json数据
        $paramsData = @file_get_contents('php://input');
        $json = CommonLogic::analyJson($paramsData);
        if ($json === false) {
            //throw new Exception('参数必须是json数据格式');
        }
        $requests = \Yii::$app->request;
        $jsonData = [];
        if (!empty($paramsData)) {
            $jsonData = json_decode($paramsData, true);
        }
        $this->requestData = array_merge($requests->get(), $requests->post(), (!empty($jsonData) && is_array($jsonData) ? $jsonData : []));
        if (isset($this->requestData["r"])) {
            unset($this->requestData["r"]);
        }
        return $this;
    }

}
