<?php


namespace app\plugins\meituan\forms\mall;


use app\core\ApiCode;
use app\models\BaseModel;
use app\plugins\meituan\models\MeituanSetting;

class MeituanSettingUpdateForm extends BaseModel{

    public $settings;

    public function rules(){
        return [
            [['settings'], 'required']
        ];
    }

    public function save() {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }

        try {
            $settings = is_array($this->settings) ? $this->settings : [];
            foreach($settings as $name => $value){
                $name = trim($name);
                if(empty($name)) continue;
                $setting = MeituanSetting::findOne(["name" => $name, "mall_id" => \Yii::$app->mall->id]);
                if(!$setting){
                    $setting = new MeituanSetting([
                        "mall_id"    => \Yii::$app->mall->id,
                        "name"       => $name,
                        "created_at" => time()
                    ]);
                }
                $setting->value      = trim($value);
                $setting->updated_at = time();
                if(!$setting->save()){
                    throw new \Exception($this->responseErrorMsg($setting));
                }
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg'  => '保存成功'
            ];
        }catch (\Exception $e){
            return [
                'code' => ApiCode::CODE_FAIL,
                'msg'  => $e->getMessage()
            ];
        }

    }
}