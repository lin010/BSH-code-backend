<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-05-08
 * Time: 17:53
 */

namespace app\plugins\boss\forms\mall;


use app\core\ApiCode;
use app\models\BaseModel;
use app\plugins\boss\models\BossLevel;
use app\plugins\boss\models\BossSetting;

class BossLevelListForm extends BaseModel
{

    public $keyword;
    public $page;

    public function rules()
    {
        return [
            [['keyword'], 'string'],
            [['keyword'], 'trim'],
            [['page'], 'integer'],
            [['page'], 'default', 'value' => 1]
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $list = BossLevel::find()->where([
            'mall_id' => \Yii::$app->mall->id, 'is_delete' => 0
        ])->keyword($this->keyword, ['or',['like', 'name', $this->keyword],['like', 'level', $this->keyword]])
            ->page($pagination, 20, $this->page)->orderBy(['id' => SORT_ASC])->all();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => [
                'list' => $list,
                'pagination' => $pagination
            ]
        ];
    }


}