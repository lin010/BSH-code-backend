<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-05-21
 * Time: 20:25
 */

namespace app\plugins\boss\handlers;

use yii\base\BaseObject;

class HandlerRegister extends BaseObject
{
    public function getHandlers()
    {
        return [
            BossInsertHandler::class,
            BossUpgradeHandler::class,
            BossCommonOrderDetailHandler::class,
           // CommonOrderPayedHandler::class,
            MallInitHandler::class
        ];
    }
}
