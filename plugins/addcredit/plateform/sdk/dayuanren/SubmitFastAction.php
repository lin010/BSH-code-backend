<?php


namespace app\plugins\addcredit\plateform\sdk\dayuanren;



use app\plugins\addcredit\plateform\result\SubmitResult;

class SubmitFastAction extends BaseAction {

    public function run(){
        $configs = $this->getPlateConfig();
        $req = new Req($configs['host'], $configs['apikey']);
        $params = [
            'out_trade_num' => $this->orderModel->order_no,
            'product_id'    => $this->orderModel->product_id, //产品ID（代理后台查看）
            'mobile'        => $this->orderModel->mobile,
            'notify_url'    => 'https://www.mingyuanriji.cn/web/addcredit-notify/index.php',
            'userid'        => $configs['userid']
        ];

        $res = $req->doPost("/index/recharge", $params);

        $submitResult = new SubmitResult();
        $submitResult->request_data     = $res['request_data'];
        $submitResult->response_content = $res['response_content'];
        $submitResult->code             = $res['code'] == Code::SUCCESS ? SubmitResult::CODE_SUCC : SubmitResult::CODE_FAIL;
        $submitResult->message          = $res['message'];

        return $submitResult;

        /*$res = array (
            'request_data' => '{"mobile":"13422078495","notify_url":"https:\/\/www.mingyuanriji.cn\/web\/addcredit-notify\/index.php","out_trade_num":"HF3231020220003602","product_id":212,"useri
d":"12507","sign":"45B9F48FD6A72E464F94DCD601F4CBE3"}',
            'response_content' => '{"errno":0,"errmsg":"提交成功","data":{"id":414942,"order_number":"CZY231020414942","mobile":"13422078495","product_id":212,"total_price":"94.00","create_time"
:1697814574,"guishu":"广东广州","title":"移动100元话费","out_trade_num":"HF3231020220003602"}}',
            'message' => '',
            'code' => '',
            'data' => array(
                'id' => '414942',
                'order_number' => 'CZY231020414942',
                'mobile' => '13422078495',
                'product_id' => 212,
                'total_price' => 94.00,
                'create_time' => 1697814574,
                'guishu' => '广东广州',
                'title' => '移动100元话费',
                'out_trade_num' => 'HF3231020220003602'
            )
        );*/
    }

}