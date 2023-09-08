<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-05-29
 * Time: 16:52
 */

namespace app\controllers\api;


use app\controllers\api\filters\LoginFilter;
use app\forms\api\integral\IntegralCashForm;

class IntegralCashController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
                'ignore' => ['config']
            ],
        ]);
    }


    /**
     * @Author: han
     * @Date: 2023-08-20
     * @Time: 19:40
     * @Note:提现相关设置
     */

    public function actionCashSetting()
    {

        $form = new IntegralCashForm();
        $form->attributes = $this->requestData;
        return $form->getSetting();
    }


    /**
     * @Author: han
     * @Date: 2023-08-20
     * @Time: 16:54
     * @Note:提现申请提交
     */
    public function actionCashSubmit()
    {
        $form = new IntegralCashForm();
        $form->attributes = $this->requestData;
        return $form->save();
    }


    /**
     * @Author: han
     * @Date: 2023-08-20
     * @Time: 16:55
     * @Note:提现记录
     */
    public function actionCashLog()
    {
        $form = new IntegralCashForm();
        $form->attributes = $this->requestData;
        return $form->getCashLogList();
    }


    public function actionList(){

        $form = new IntegralCashForm();
        $form->attributes = $this->requestData;
        return $form->getCashList();
    }


    public function actionDetail(){

        $form = new IntegralCashForm();
        $form->attributes = $this->requestData;
        return $form->getCashDetail();
    }


}