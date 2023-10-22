<?php

namespace app\plugins\addcredit\handlers;

use yii\base\BaseObject;

class HandlerRegister extends BaseObject
{
    public function getHandlers()
    {
        return [
            AddcreditHandler::class
        ];
    }
}
