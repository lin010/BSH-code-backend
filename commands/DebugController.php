<?php

namespace app\commands;

use app\models\User;
use app\models\UserRelationshipLink;

class DebugController extends BaseCommandController{

    public function actionIndex(){
        $endTime = 1690819200;
        while(true){
            $row = UserRelationshipLink::find()->asArray()->orderBy("user_id DESC, created_at DESC")->limit(1)->one();
            $lastUserId = $row ? $row['user_id'] : 0;
            $lastRightVal = $row ? $row['right'] : 2;
            $lastTime = $row ? $row['created_at'] : time();
            $user = User::find()->where([
                "AND",
                [">", "id", $lastUserId],
                ["<", "created_at", $endTime],
                ["parent_id" => 1]
            ])->asArray()->select(["id"])->orderBy("id ASC")->limit(1)->one();
            !$user && exit("over");
            $model = UserRelationshipLink::findOne(["user_id" => $user['id']]);
            if(!$model){
                $model = new UserRelationshipLink([
                    "user_id"       => $user['id'],
                    "parent_id"     => 1,
                    "left"          => $lastRightVal + 1,
                    "right"         => $lastRightVal + 2,
                    "is_delete"     => 0,
                    "created_at"    =>  $lastTime + 1
                ]);
                $model->save();
            }
            echo "build " . $user['id'] . "\n";
        }
    }
}