<?php


namespace app\plugins\meituan\models;


use app\models\BaseActiveRecord;

class MeituanSetting extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin_meituan_setting}}';
    }

    public function rules()
    {
        return [
            [['mall_id', 'created_at', 'updated_at', 'name', 'value'], 'required'],
            [[], 'safe']
        ];
    }

    /**
     * 获取设置信息
     * @return array
     */
    public static function getSettings(){
        $rows = static::find()->asArray()->all();
        $settings = [];
        if($rows){
            foreach($rows as $row){
                $settings[$row['name']] = $row['value'];
            }
        }
        return $settings;
    }
}