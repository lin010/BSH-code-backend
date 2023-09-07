<?php

namespace app\plugins\smart_shop\components;

trait SmartShopGiftpackOrderTrait{

    /**
     * 获取订单列表
     * @param string[] $selects
     * @param array $wheres
     * @param int $limit
     * @param null $orderBy
     * @return array|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public function getGiftpackOrders($selects = ["o.*"], $wheres = [], $limit = 10, $orderBy = null){
        $sql = "SELECT " .implode(",", $selects) . " FROM {{%giftpack_order}} o " .
            "INNER JOIN {{%store}} s ON s.id=o.store_id " .
            "INNER JOIN {{%merchant}} m ON s.admin_id=m.admin_id " .
            "WHERE " . (!empty($wheres) ? implode(" AND ", $wheres) : "1") . " " .
            "ORDER BY " . (!empty($orderBy) ? $orderBy : " o.id DESC") . " " .
            "LIMIT 0,{$limit}";
        $rows = $this->getDB()->createCommand($sql)->queryAll();
        return $rows ? $rows : [];
    }

    /**
     * 批量设置KPI新订单状态
     * @param $orderIds
     * @param $status
     */
    public function batchSetGiftpackOrderKpiNewStatus($orderIds, $status){
        $sql = "UPDATE {{%giftpack_order}} SET `kpi_new_status`='{$status}' WHERE `id` IN(".implode(",", $orderIds).")";
        $this->getDB()->createCommand($sql)->execute();
    }

    /**
     * 获取套餐活动订单详情
     * @param $record_id
     * @return array
     */
    public function getGiftpackOrderDetail($record_id, $selects = ["go.*"]){
        $sql = "SELECT " .implode(",", $selects) . " FROM {{%giftpack_order}} go WHERE go.id='{$record_id}'";
        $row = $this->getDB()->createCommand($sql)->queryOne();
        return $row ? $row : [];
    }
}