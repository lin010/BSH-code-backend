<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * 名片客户初始化类
 * Author: zal
 * Date: 2020-07-14
 * Time: 14:12
 */

namespace app\plugins\business_card\behavior;

use app\plugins\business_card\forms\common\BusinessCardCustomerCommon;
use Yii;
use yii\base\ActionFilter;

class BusinessCardBehavior extends ActionFilter
{
    public $user_id;

    public function beforeAction($action)
    {
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }
}