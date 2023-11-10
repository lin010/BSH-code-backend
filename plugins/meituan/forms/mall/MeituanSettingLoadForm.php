<?php


namespace app\plugins\meituan\forms\mall;


use app\core\ApiCode;
use app\models\BaseModel;
use app\plugins\meituan\models\MeituanSetting;

class MeituanSettingLoadForm extends BaseModel{

    public function get(){
        if(!$this->validate()){
            return $this->responseErrorInfo();
        }

        try {

            $settings = MeituanSetting::getSettings();

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => $settings
            ];
        }catch (\Exception $e){
            return [
                'code' => ApiCode::CODE_FAIL,
                'msg'  => $e->getMessage()
            ];
        }
    }

}