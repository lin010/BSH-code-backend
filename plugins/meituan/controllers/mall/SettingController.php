<?php


namespace app\plugins\meituan\controllers\mall;


use app\plugins\Controller;
use app\plugins\meituan\forms\mall\MeituanSettingLoadForm;
use app\plugins\meituan\forms\mall\MeituanSettingUpdateForm;

class SettingController extends Controller{

    public function actionIndex(){
        if (\Yii::$app->request->isAjax) {
            if(\Yii::$app->request->isPost){
                $form = new MeituanSettingUpdateForm();
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->save());
            }else{
                $form = new MeituanSettingLoadForm();
                $form->attributes = \Yii::$app->request->get();
                return $this->asJson($form->get());
            }
        } else {
            return $this->render('index');
        }
    }

}