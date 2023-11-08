<?php

namespace app\plugins\meituan\handlers;

use yii\base\BaseObject;

class HandlerRegister extends BaseObject
{
    public function getHandlers()
    {
        return [
            MeituanHandler::class
        ];
    }
}
