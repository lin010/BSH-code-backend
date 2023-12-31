<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-04-16
 * Time: 14:11
 */

namespace app\controllers\mall;

use app\forms\mall\sensitive\SensitiveEditForm;
use app\forms\mall\sensitive\SensitiveForm;

class SensitiveController extends MallController
{
    /**
     * @Author: 广东七件事 ganxiaohao
     * @Date: 2020-04-16
     * @Time: 14:25
     * @Note:服务列表
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
            } else {
                $form = new SensitiveForm();
                $form->attributes = \Yii::$app->request->get();
                $list = $form->search();

                return $this->asJson($list);
            }
        } else {
            return $this->render('index');
        }
    }

    /**
     * @Author: 广东七件事 ganxiaohao
     * @Date: 2020-04-16
     * @Time: 14:23
     * @Note:编辑服务
     * @return string|\yii\web\Response
     */
    public function actionEdit()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new SensitiveEditForm();
                $form->attributes = \Yii::$app->request->post('form');
                $res = $form->save();

                return $this->asJson($res);
            } else {
                $form = new SensitiveForm();
                $form->attributes = \Yii::$app->request->get();
                $detail = $form->getDetail();

                return $this->asJson($detail);
            }
        } else {
            return $this->render('edit');
        }
    }

    /**
     * @Author: 广东七件事 ganxiaohao
     * @Date: 2020-04-16
     * @Time: 14:23
     * @Note:删除服务
     * @return \yii\web\Response
     */
    public function actionDelete()
    {
        $form = new SensitiveForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->delete();

        return $this->asJson($res);
    }
}