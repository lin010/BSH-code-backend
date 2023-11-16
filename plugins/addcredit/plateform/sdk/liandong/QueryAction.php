<?php


namespace app\plugins\addcredit\plateform\sdk\liandong;


use app\plugins\addcredit\plateform\result\QueryResult;

class QueryAction extends BaseAction {

    public function run(){
        $configs = $this->getPlateConfig();
        $req = new Req($configs['host'], $configs['secretKey']);
        $params = [
            'merchant_no' => $configs['merchantNo'],
            'merchant_order_no' => $this->orderModel->order_no,
        ];

        $res = $req->doPost("/api/order/getorder", $params);

        $queryResult = new QueryResult();
        $queryResult->request_data     = $res['request_data'];
        $queryResult->response_content = $res['response_content'];
        $queryResult->code             = $res['code'] == Code::SUCCESS ? QueryResult::CODE_SUCC : QueryResult::CODE_FAIL;
        $queryResult->message          = $res['message'];
        //充值状态：-1取消，0充值中 ，1充值成功，2充值失败，3部分成功
        if($queryResult->code == QueryResult::CODE_SUCC){
            if(!empty($res['data'])){
                $orderStatus = $res['data']['orderStatus'];
                if($orderStatus == "Success"){
                    $queryResult->status = "success";
                }elseif($orderStatus == "Processing"){
                    $queryResult->status = "waiting";
                }else{
                    $queryResult->status = "fail";
                }
            }else{
                $queryResult->status = "fail";
            }
        }
        return $queryResult;
    }
}