<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-04-06
 * Time: 17:16
 */

namespace app\forms\common;


use app\core\ApiCode;
use app\models\AttachmentInfo;
use app\models\FilePath;
use yii\base\Model;
use yii\data\Pagination;


/**
 * Class AttachmentListForm
 * @package app\forms\common
 * @Notes 附件列表类
 */
class AttachmentListForm extends Model
{

    public $limit;
    public $page;
    public $type; // 类型
    public $mch_id; //多商户ID
    public $group_id;//分组ID
    public $mall_id; //商城ID

    public function rules()
    {
        return [
            [['mall_id'], 'required'],
            [['group_id', 'mall_id','page'], 'integer'],
            [['type'], 'string'],
            [['page'],'default', 'value' => 1],
            [['mall_id'], 'default', 'value' => 0],
            [['limit'], 'default', 'value' => 10],
            [['type'], 'default', 'value' => 'image']
        ]; // TODO: Change the autogenerated stub
    }


    public function search()
    {
        if (!$this->validate()) {
            return ['code' => ApiCode::CODE_FAIL, 'msg' => '查询参数不存在!', 'error' => $this->getErrors()];
        }
        $query = AttachmentInfo::find()->where(['mall_id' => $this->mall_id, 'is_delete' => 0]);
        if ($this->group_id) {
            $query->andWhere(['group_id' => $this->group_id]);
        }
        if ($this->type) {
            $query->andWhere(['type' => $this->type]);
        }
        if ($this->mch_id) {
            $query->andWhere(['mch_id' => $this->mch_id]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $list = $query->orderBy('created_at DESC')->select('url,thumb_url,type,id')->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => 'success',
            'data' => [
                'row_count' => $count,
                'page_count' => $pagination->pageCount,
                'list' => $list,
            ],
        ];
    }

}