<?php

namespace app\plugins\addcredit\plateform\sdk\dayuanren;

use app\plugins\addcredit\models\AddcreditOrder;
use app\plugins\addcredit\models\AddcreditPlateforms;
use app\plugins\addcredit\plateform\IOrder;
use app\plugins\addcredit\plateform\result\QueryResult;
use app\plugins\addcredit\plateform\result\SubmitResult;

class PlateForm implements IOrder
{

    /**
     * 提交订单
     * @param AddcreditOrder $addcreditOrder
     * @param AddcreditPlateforms $plateform
     * @param $requestNum
     * @return SubmitResult
     */
    public function submit(AddcreditOrder $addcreditOrder, AddcreditPlateforms $plateform)
    {
        if($addcreditOrder->recharge_type == "fast") { //快充
            $action = new SubmitFastAction($addcreditOrder, $plateform);
        }else{ //TODO 慢充
            $action = new SubmitFastAction($addcreditOrder, $plateform);
        }
        return $action->run();
    }

    /**
     * 查询订单
     * @param AddcreditOrder $orderModel
     * @param AddcreditPlateforms $plateform
     * @return QueryResult
     */
    public function query2(AddcreditOrder $orderModel, AddcreditPlateforms $plateform)
    {
        return (new QueryAction($orderModel, $plateform))->run();
    }

    /**
     * 查询订单
     * @param AddcreditOrder $orderModel
     * @return QueryResult
     */
    public function query(AddcreditOrder $orderModel)
    {
        return (new QueryAction())->run();
    }
}