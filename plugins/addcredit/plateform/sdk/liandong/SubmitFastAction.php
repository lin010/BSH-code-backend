<?php

namespace app\plugins\addcredit\plateform\sdk\liandong;

use app\plugins\addcredit\plateform\result\SubmitResult;

class SubmitFastAction extends BaseAction {

    public function run(){
        $configs = $this->getPlateConfig();

        $products = !empty($this->plateModel->product_json_data) ? json_decode($this->plateModel->product_json_data, true) : [];
        $product = null;
        foreach($products as $item){
            if($item['product_id'] == $this->orderModel->product_id){
                $product = $item;
                break;
            }
        }

        !$product && exit("话费套餐不存在");

        $req = new Req($configs['host'], $configs['secretKey']);
        $params = [
            'merchant_no' => $configs['merchantNo'],
            'merchant_order_no' => $this->orderModel->order_no,
            'isp_name' => '',
            'face_value' => $product['price'],
            'num' => 1,
            'equity_number' => $this->orderModel->mobile,
            'notify_url' => 'https://www.h5-mingyuanriji.cn/web/addcredit-notify/index.php'
        ];

        $res = $req->doPost("/api/order/phone", $params);

        $submitResult = new SubmitResult();
        $submitResult->request_data     = $res['request_data'];
        $submitResult->response_content = $res['response_content'];
        $submitResult->code             = $res['code'] == Code::SUCCESS ? SubmitResult::CODE_SUCC : SubmitResult::CODE_FAIL;
        $submitResult->message          = $res['message'];

        return $submitResult;
    }

}