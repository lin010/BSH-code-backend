<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * 导出余额变动日志
 * Author: zal
 * Date: 2020-04-16
 * Time: 09:45
 */

namespace app\forms\mall\export;

use app\core\CsvExport;
use app\models\MallSetting;

class BalanceLogExport extends BaseExport
{
    public function fieldsList()
    {
        //币种别名
        $getCurrencyAlias = MallSetting::getValueByKeys([
            'balance_alias',
            'red_envelope_alias',
            'integral_alias',
            'silver_beans_alias',
        ],\Yii::$app->mall->id);
        return [
            [
                'key' => 'platform',
                'value' => '所属平台',
            ],
            [
                'key' => 'user_id',
                'value' => '用户ID',
            ],
            [
                'key' => 'nickname',
                'value' => '用户昵称',
            ],
            [
                'key' => 'mobile',
                'value' => '用户手机号',
            ],
            [
                'key' => 'type',
                'value' => $getCurrencyAlias['balance_alias'].'类型',
            ],
            [
                'key' => 'money',
                'value' => $getCurrencyAlias['balance_alias'],
            ],
            [
                'key' => 'created_at',
                'value' => '支付日期',
            ],
            [
                'key' => 'desc',
                'value' => '说明',
            ],
        ];
    }

    public function export($query, $alias = '')
    {
        $orderBy = $alias . 'created_at';
        $list = $query->orderBy($orderBy)->with(['user.userInfo'])->asArray()->all();
        $this->transform($list);
        $this->getFields();
        $dataList = $this->getDataList();
        //币种别名
        $getCurrencyAlias = MallSetting::getValueByKeys([
            'balance_alias',
            'red_envelope_alias',
            'integral_alias',
            'silver_beans_alias',
        ],\Yii::$app->mall->id);
        $fileName = $getCurrencyAlias['balance_alias'].'记录' . date('YmdHis');
        (new CsvExport())->export($dataList, $this->fieldsNameList, $fileName);
    }

    protected function transform($list)
    {
        $newList = [];
        $number = 1;
        foreach ($list as $item) {
            $arr = [];
            $arr['number'] = $number++;
            $arr['platform'] = $this->getPlatform($item['user']['userInfo']['platform']);
            $arr['desc'] = $item['desc'];
            $arr['nickname'] = $item['user']['nickname'];
            $arr['user_id'] = $item['user']['id'];
            $arr['mobile'] = $item['user']['mobile'];
            $arr['money'] = (float)$item['money'];
            $arr['created_at'] = $this->getDateTime($item['created_at']);
            $arr['type'] = $item['type'] == 1 ? '收入' : '支出';
            $newList[] = $arr;
        }

        $this->dataList = $newList;
    }
}
