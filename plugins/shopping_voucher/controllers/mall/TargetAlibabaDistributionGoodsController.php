<?php

namespace app\plugins\shopping_voucher\controllers\mall;

use app\plugins\Controller;
use app\plugins\shopping_voucher\forms\mall\ShoppingVoucherAlibabaDistributionGoodsDeleteForm;
use app\plugins\shopping_voucher\forms\mall\ShoppingVoucherAlibabaDistributionGoodsEditForm;
use app\plugins\shopping_voucher\forms\mall\ShoppingVoucherAlibabaDistributionGoodsListForm;

class TargetAlibabaDistributionGoodsController extends Controller{

    /**
     * 红包商品管理
     * @return bool|string|\yii\web\Response
     */
    public function actionList(){
        if (\Yii::$app->request->isAjax) {
            $form = new ShoppingVoucherAlibabaDistributionGoodsListForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getList());
        } else {
            return $this->render('list');
        }
    }

    /**
     * 编辑保存
     * @return bool|string|\yii\web\Response
     */
    public function actionEdit(){
        $form = new ShoppingVoucherAlibabaDistributionGoodsEditForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->save());
    }

    /**
     * 删除红包商品
     * @return bool|string|\yii\web\Response
     */
    public function actionDelete(){
        $form = new ShoppingVoucherAlibabaDistributionGoodsDeleteForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->delete());
    }
}