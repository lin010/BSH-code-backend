<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-05-29
 * Time: 16:58
 */

namespace app\forms\api\cash;

use app\core\ApiCode;
use app\core\BasePagination;
use app\forms\common\UserIncomeForm;
use app\helpers\SerializeHelper;
use app\logic\OptionLogic;
use app\models\BaseModel;
use app\models\Cash;
use app\models\CashLog;
use app\models\Option;
use app\models\IncomeLog;
use app\models\RelationSetting;
use app\models\User;

class CashForm extends BaseModel
{
    public $page;
    public $limit;
    public $price;
    public $type;
    public $content;
    public $name;
    public $bank_name;
    public $bank_account;
    public $mobile;
    public $status;
    public $wechat_qrcode;
    public $transaction_password;
    public $bankprovinceid;
    public $bankprovince;
    public $bankcityid;
    public $bankcity;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page', 'limit', 'status','bankprovinceid','bankcityid'], 'integer'],
            [['price'], 'number'],
            [['content', 'mobile','transaction_password', 'bank_name', 'name', 'bank_account', 'wechat_qrcode','bankprovince','bankcity'], 'string'],
            [['type'], 'string', 'max' => 45],
        ];
    }

    public function attributeLabels()
    {
        return [
            'price' => '提现金额',
            'type' => '提现方式 auto--自动打款 wechat--微信打款 alipay--支付宝打款 bank--银行转账 balance--打款到余额',
            'name' => '支付宝或者银行卡开户名',
            'mobile' => '支付宝账号',
            'bank_name' => '开户行名称',
            'bank_account' => '银行卡账号',
            'content' => '备注信息',
            'wechat_qrcode' => '微信收款码',
            'transaction_password' => '支付密码'
        ]; // TODO: Change the autogenerated stub
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }

        if(empty($this->transaction_password)){
            return $this->returnApiResultData(ApiCode::CODE_FAIL, '交易密码不能为空！');
        }

        $payment = OptionLogic::get(Option::NAME_PAYMENT, \Yii::$app->mall->id, Option::GROUP_APP);
        if (!$payment) {
            return $this->returnApiResultData(ApiCode::CODE_FAIL, '系统添加财务设置！');
        }
        if (!$payment['is_income_cash']) {
            return $this->returnApiResultData(ApiCode::CODE_FAIL, '系统未开启提现功能！');
        }

        if ($this->price <= 0) {
            return $this->returnApiResultData(ApiCode::CODE_FAIL, '提现佣金必须大于0！');
        }
        $user = User::findOne(\Yii::$app->user->identity->id);
        if ($user->income < $this->price) {
            return $this->returnApiResultData(ApiCode::CODE_FAIL, '提现金额超出可提现金额！');
        }

        if (!\Yii::$app->getSecurity()->validatePassword(trim($this->transaction_password), $user->transaction_password)) {
            return $this->returnApiResultData(ApiCode::CODE_FAIL, '支付密码错误！');
        }

        $exists = Cash::find()->where([
            'is_delete' => 0, 'mall_id' => \Yii::$app->mall->id, 'status' => 0, 'user_id' => \Yii::$app->user->identity->id
        ])->exists();

        if ($exists) {
            return $this->returnApiResultData(ApiCode::CODE_FAIL, '尚有未审核的提现申请！');
        }
        $dayMaxMoney = $payment['day_max_money'];
        if ($dayMaxMoney > -1) {
            $today_cash_price = Cash::find()->where(['user_id' => $user->id, 'mall_id' => \Yii::$app->mall->id])->andWhere(['>', 'created_at', strtotime(date('Y-m-d', time()))])->andWhere(['<', 'status', 3])->sum('price');
            if ($today_cash_price >= $dayMaxMoney) {
                return $this->returnApiResultData(ApiCode::CODE_FAIL, '今日可提现额度已用完！');
            }
            if (floatval($this->price) + floatval($today_cash_price) > floatval($dayMaxMoney)) {
                return $this->returnApiResultData(ApiCode::CODE_FAIL, '提现金额大于今日剩余可提现额度！');
            }
        }
        


        if (!$this->type) {
            return $this->returnApiResultData(ApiCode::CODE_FAIL, '请选择提现方式！');
        }

        if ($this->type == 'wechat') {
            if (!$this->wechat_qrcode) {
                return $this->returnApiResultData(ApiCode::CODE_FAIL, '请上传微信收款码！');
            }
        }
        if ($this->type == 'alipay') {
            if (!$this->mobile) {
                return $this->returnApiResultData(ApiCode::CODE_FAIL, '请填写支付宝账号！');
            }
            if (!$this->name) {
                return $this->returnApiResultData(ApiCode::CODE_FAIL, '请填写支付宝户名！');
            }
        }
        $huifu_bank_token_no = $user->huifu_bank_token_no;
        if($this->type == 'bank'){
            if(!$this->bankcityid || !$this->bankprovinceid){
                return $this->returnApiResultData(ApiCode::CODE_FAIL, '请选择开户行所在省市！');
            }
            if(empty($user->huifu_id)){
                return $this->returnApiResultData(ApiCode::CODE_FAIL, '请先完成实名认证！');
            }
            if($user->realname != $this->name){
                return $this->returnApiResultData(ApiCode::CODE_FAIL, '银行卡户名和实名认证名字不一致,请使用实名认证名字开户的银行卡');
            }
            $huifu = [];
            $huifu['card_name'] = $this->name;
            $huifu['card_no'] =  $this->bank_account;
            $huifu['prov_id'] = $this->bankprovinceid;
            $huifu['area_id'] = $this->bankcityid;
            $huifu['huifu_id'] = $user->huifu_id;
            $huifu['cert_validity_type'] = $user->cert_validity_type;
            $huifu['cert_begin_date'] = $user->cert_begin_date;
            $huifu['cert_end_date'] = $user->cert_end_date;
            $huifu['cert_type'] = '00';
            $huifu['cert_no'] = $user->cert_no;
            $huifu['huifu_cash_type'] = 'DM';
            $hasEdit = false;
            if(empty($user->huifu_bank_token_no)){
                $res = \Yii::$app->bs->user_busi_open($huifu);
                if($res['code']==-1){
                    return $this->returnApiResultData(ApiCode::CODE_FAIL,$res['message']);
                }
                $hasEdit = true;
            }else{
                if($user->bank_account != $huifu['card_no']){
                    $res = \Yii::$app->bs->user_busi_modify($huifu);
                    $hasEdit = true;
                    if($res['code']==-1){
                        return $this->returnApiResultData(ApiCode::CODE_FAIL,$res['message']);
                    }
                }
            }
            if($hasEdit){
                $user->bank_name = $this->bank_name;
                $user->bank_account = $huifu['card_no'];
                $user->bankprovinceid = $huifu['prov_id'];
                $user->bankcityid = $huifu['area_id'];
                $user->bankprovince = $this->bankprovince;
                $user->bankcity = $this->bankcity;
                $user->huifu_bank_token_no = $res['data']['token_no'];
                $user->huifu_cash_type = $huifu['huifu_cash_type'];
                if($user->save() === false){
                    return $this->returnApiResultData(ApiCode::CODE_FAIL,'保存银行卡信息失败');
                }
                $huifu_bank_token_no = $res['data']['token_no'];
                
            }
        }
        $extra = SerializeHelper::encode([
            'name' => $this->name,
            'mobile' => $this->mobile,
            'bank_name' => $this->bank_name,
            'bank_account' => $this->bank_account,
            'bankcity' => $this->bankcity,
            'bankcityid' => $this->bankcityid,
            'bankprovince' => $this->bankprovince,
            'bankprovinceid' => $this->bankprovinceid,
            'wechat_qrcode' => $this->wechat_qrcode,
            'huifu_bank_token_no' =>$huifu_bank_token_no
        ]);        
        $content = SerializeHelper::encode(['user_content' => $this->content]);

          
        $t = \Yii::$app->db->beginTransaction();
        try {
            
            $cash = new Cash();
            $cash->mall_id    = \Yii::$app->mall->id;
            $cash->user_id    = $user->id;
            $cash->price      = $this->price;
            $cash->fact_price = $this->price;
            if ($payment['cash_service_fee'] > 0 && $payment['cash_service_fee'] < 100) {
                $cash->fact_price = (100 - $payment['cash_service_fee']) * $this->price / 100;
            }
            $cash->order_no         = $this->getCashOrder();
            $cash->service_fee_rate = $payment['cash_service_fee'];
            $cash->content          = $content;
            $cash->type             = $this->type;
            $cash->extra            = $extra;
            if (!$cash->save()) {
                throw new \Exception($this->responseErrorMsg($cash));
            }

            $res = UserIncomeForm::cashSub($user, floatval($this->price), $cash->id);
            if($res['code'] != ApiCode::CODE_SUCCESS){
                throw new \Exception($res['msg']);
            }

            $log = new CashLog();
            $log->price   = $this->price;
            $log->type    = 2;
            $log->desc    = '提现申请';
            $log->user_id = $user->id;
            $log->mall_id = $user->mall_id;
            if(!$log->save()){
                throw new \Exception($this->responseErrorMsg($log));
            }

            $t->commit();

            return $this->returnApiResultData(ApiCode::CODE_SUCCESS, '提交成功!');
        }catch (\Exception $e){
            $t->rollBack();
            return $this->returnApiResultData(ApiCode::CODE_FAIL, $e->getMessage());
        }


    }

    private function getCashOrder()
    {
        $order_no = null;
        while (true) {
            $order_no = 'TX' . date('YmdHis') . rand(10000, 99999);
            $exist = Cash::find()->where(['mall_id' => \Yii::$app->mall->id, 'order_no' => $order_no])->exists();
            if (!$exist) {
                break;
            }
        }
        return $order_no;
    }

    public function getSetting()
    {
        if (!$this->validate()) {
            return $this->returnApiResultData();
        }
        $setting = OptionLogic::get(Option::NAME_PAYMENT, \Yii::$app->mall->id, Option::GROUP_APP);
        if (!$setting) {
            return $this->returnApiResultData(ApiCode::CODE_FAIL, '系统未开启提现功能！');
        }

        if (!$setting['is_income_cash']) {
            return $this->returnApiResultData(ApiCode::CODE_FAIL, '系统未开启提现功能！');
        }

        if (!$setting['cash_type']) {
            return $this->returnApiResultData(ApiCode::CODE_FAIL, '系统未设置提现方式！');
        }
        $cash_type = SerializeHelper::decode($setting['cash_type']);
        $cash__type_name = [];
        foreach ($cash_type as $i => $item) {
            $cash__type_name[$i]['type'] = $item;
            if ($item == 'wechat') {
                $cash__type_name[$i]['name'] = '提现微信';
            }
            if ($item == 'auto') {
                $cash__type_name[$i]['name'] = '提现到微信零钱';
            }
            if ($item == 'alipay') {
                $cash__type_name[$i]['name'] = '提现到支付宝';
            }
            if ($item == 'balance') {
                $cash__type_name[$i]['name'] = '提现到余额（余额仅用于消费）';
            }
            if ($item == 'bank') {
                $cash__type_name[$i]['name'] = '提现到银行卡';
            }
        }

        $isTransactionPassword = false;
        $transaction_password = \Yii::$app->user->identity->transaction_password;
        $transaction_password = trim($transaction_password);
        if(!empty($transaction_password)){
            $isTransactionPassword = true;
        }

        //获取最近一次提现的银行卡
        $lastBankCash = Cash::find()->where([
            "user_id" => \Yii::$app->user->id,
            "type"    => "bank"
        ])->asArray()->select(["extra"])->orderBy("id DESC")->one();
        $defaultBank = [
            "name"         => "",
            "bank_name"    => "",
            "bank_account" => ""
        ];
        if(!empty($lastBankCash['extra'])){
            $extra = !empty($lastBankCash['extra']) ? @json_decode($lastBankCash['extra'], true) : [];
            $defaultBank = array_merge($defaultBank, $extra);
        }
        $user = User::getOneData([
            'id' => \Yii::$app->user->id,
            'mall_id' => \Yii::$app->mall->id
        ]);
        if(!empty($user) && !empty($user['huifu_bank_token_no'])){
            $defaultBank = [
                "name"         => $user['realname'],
                "bank_name"    => $user['bank_name'],
                "bank_account" => $user['bank_account'],
                "bankprovince"=> $user['bankprovince'],
                "bankcity"=> $user['bankcity'],
                "bankprovinceid"=> $user['bankprovinceid'],
                "bankcityid"=> $user['bankcityid'],
            ];
        }
        return $this->returnApiResultData(
            ApiCode::CODE_SUCCESS,
            '',
            [
                'setting' => [
                    'cash_type' => $cash_type,
                    'cash_type_name' => $cash__type_name,
                    'cash_service_fee' => $setting['cash_service_fee'],
                    'min_money' => $setting['min_money'],
                    'day_max_money' => $setting['day_max_money']
                ],
                'user_info' => [
                    'income' => \Yii::$app->user->identity->income,
                    'huifu_id' => $user['huifu_id'],
                    'huifu_bank_token_no' => $user['huifu_bank_token_no'],
                    'is_transaction_password' => $isTransactionPassword
                ],
                'default_bank' => $defaultBank
            ]);
    }

    public function getCashLogList()
    {
        if (!$this->validate()) {
            return $this->returnApiResultData();
        }
        $query = CashLog::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
            'user_id' => \Yii::$app->user->identity->id
        ]);

        if ($this->type) {
            $query = $query->andWhere(['type' => $this->type]);
        }
        if ($this->status != -1) {
            $query = $query->andWhere(['status' => $this->status]);
        }
        $list = $query->orderBy("created_at  DESC")
            ->page($pagination, $this->limit, $this->page)->all();
        /**
         * @var CashLog $list []
         *
         *
         */
        $newList = [];
        foreach ($list as $item) {
            /**
             * @var CashLog $item
             */
            $newItem['created_at'] = date('Y-m-d H:i:s', $item->created_at);
            $newItem['desc'] = $item->desc;
            $newItem['custom_desc'] = $item->custom_desc;
            $newItem['price'] = $item->price;
            $newItem['type'] = $item->type;
            $newList[] = $newItem;
        }

        return $this->returnApiResultData(ApiCode::CODE_SUCCESS, '', ['list' => $newList]);

    }


    public function getCashDetail()
    {
        if (!$this->validate()) {
            return $this->returnApiResultData();
        }
        $query = Cash::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
            'user_id' => \Yii::$app->user->identity->id,
            'status' => 2
        ]);
        $total_cash_price = $query->sum('price');
        $today = date("Y-m-d");
        $first = 1;
// 获取当前周的第几天 周日是0 周一到周六是 1 - 6
        $w = date('w', strtotime($today));
        $week_start = strtotime("$today -" . ($w ? $w - $first : 6) . ' days');
        $week_cash_price = $query->andWhere(['>=', 'created_at', $week_start])->sum('price');
        $month_cash_price = $query->andWhere(['>=', 'created_at', strtotime($today)])->sum('price');
        $week_cash_price = $week_cash_price ?? 0;
        $month_cash_price = $month_cash_price ?? 0;
        $total_cash_price = $total_cash_price ?? 0;

        return $this->returnApiResultData(ApiCode::CODE_SUCCESS, '', ['week_cash_price' => $week_cash_price, 'total_cash_price' => $total_cash_price, 'month_cash_price' => $month_cash_price, 'income' => \Yii::$app->user->identity->income]);
    }

    public function getCashList()
    {
        if (!$this->validate()) {
            return $this->returnApiResultData();
        }
        $query = Cash::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
            'user_id' => \Yii::$app->user->identity->id
        ]);

        /*   if ($this->status||$this->status==0) {
               $query = $query->andWhere(['status' => $this->status]);
           }*/

        if($this->status!=-1){
            if ($this->status) {
                $query = $query->andWhere(['status' => $this->status]);
            }
            if($this->status==0){
                $query = $query->andWhere(['status' => 0]);
            }
        }
        /**
         * @var BasePagination $pagination
         */

        $list = $query->orderBy("created_at  DESC")
            ->page($pagination, $this->limit, $this->page)->all();
        /**
         * @var Cash $list []
         *
         *
         */
        $newList = [];
        foreach ($list as $item) {
            /**
             * @var Cash $item
             */
            $newItem['created_at'] = date('Y-m-d H:i:s', $item->created_at);
            $newItem['content'] = @json_decode($item->content, true);
            $newItem['fact_price'] = $item->fact_price;
            $newItem['price'] = $item->price;
            $newItem['status'] = $item->status;
            $newItem['type'] = $item->type;
            if ($item->extra) {
                $newItem['extra'] = SerializeHelper::decode($item->extra);
            } else {
                $newItem['extra'] = [];
            }
            $newItem['service_fee_rate'] = $item->service_fee_rate;
            $newList[] = $newItem;
        }
        return $this->returnApiResultData(ApiCode::CODE_SUCCESS, '', [
            'list' => $newList,
            'pagination' => [
                'total_count' => $pagination->total_count,
                'page_count' => $pagination->page_count,
                'pageSize' => $pagination->pageSize,
                'current_page' => $pagination->current_page
            ]
        ]);
    }

}