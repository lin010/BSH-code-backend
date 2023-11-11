<?php


namespace app\plugins\meituan\helpers;


use app\models\Goods;
use app\models\GoodsAttr;
use app\models\GoodsWarehouse;
use app\models\MallGoods;
use app\plugins\meituan\models\MeituanSetting;

class MeituanOrderGoods
{
    /**
     * 创建美团商品
     * @param $mallId
     * @param $price
     * @param $name
     * @return Goods|null
     */
    public static function create($mallId, $price, $name){

        $settings = MeituanSetting::getSettings();

        $t = \Yii::$app->db->beginTransaction();
        try {

            $goodsWare = new GoodsWarehouse();
            $goodsWare->mall_id = $mallId;
            $goodsWare->name = $name;
            $goodsWare->product = "";
            $goodsWare->original_price = $price;
            $goodsWare->cost_price = 0;
            $goodsWare->detail = $name;
            $goodsWare->cover_pic = "";
            $goodsWare->pic_url = json_encode([]);
            $goodsWare->video_url = "";
            $goodsWare->unit = "件";
            $goodsWare->created_at = time();
            $goodsWare->deleted_time = 0;
            $goodsWare->updated_at = time();
            $goodsWare->is_delete = 0;
            if(!$goodsWare->save()){
                throw new \Exception(json_encode($goodsWare->getErrors()));
            }


            $goods = new Goods();
            $goods->mall_id = $mallId;
            $goods->mch_id = 0;
            $goods->goods_warehouse_id = $goodsWare->id;
            $goods->status = 1;
            $goods->price = $price;
            $goods->use_attr = 0;
            $goods->attr_groups = json_encode([['attr_group_id' => 1, 'attr_group_name' => '规格', 'attr_list' => [['attr_id' => 2, 'attr_name' => '默认']]]]);
            $goods->goods_stock = 999;
            $goods->virtual_sales = 0;
            $goods->confine_count = -1;
            $goods->pieces = 0;
            $goods->forehead = 0.00;
            $goods->freight_id = 41; //免邮
            $goods->give_score = 0;
            $goods->give_score_type = 1;
            $goods->forehead_score = 0;
            $goods->forehead_score_type = 1;
            $goods->accumulative = 0;
            $goods->individual_share = 0;
            $goods->attr_setting_type = 0;
            $goods->is_level = 1;
            $goods->is_level_alone = 0;
            $goods->share_type = 0;
            $goods->sign = "meituan";
            $goods->app_share_pic = "";
            $goods->app_share_title = "";
            $goods->is_default_services = 1;
            $goods->sort = 0;
            $goods->created_at = time();
            $goods->deleted_at = 0;
            $goods->updated_at = time();
            $goods->is_delete = 0;
            $goods->payment_people = 0;
            $goods->payment_num = 0;
            $goods->payment_amount = 0;
            $goods->payment_order = 0;
            $goods->confine_order_count = -1;
            $goods->is_area_limit = 0;
            $goods->area_limit = json_encode(["list" => []]);
            $goods->form_id = 0;
            $goods->is_show_sales = 0;
            $goods->use_score = 0;
            $goods->use_virtual_sales = 1;
            $goods->labels = "";
            $goods->full_relief_price = 0;
            $goods->fulfil_price = 0;
            $goods->price_display = json_encode([["key" => "price", "display_id" => 0]]);
            $goods->max_deduct_integral = $price;
            $goods->enable_integral = 0;
            $goods->integral_setting = "";
            $goods->enable_score = 0;
            $goods->score_setting = "";
            $goods->is_order_paid = 0;
            $goods->order_paid = "";
            $goods->is_order_sales = 0;
            $goods->order_sales = "";
            $goods->cannotrefund = "";
            $goods->is_on_site_consumption = 0;
            $goods->integral_fee_rate = isset($settings['integral_fee_rate']) ? max(0, intval($settings['integral_fee_rate'])) : 0;
            $goods->goods_brand = "";
            $goods->goods_supplier = "";
            $goods->is_recycle = 0;
            $goods->profit_price = 0;
            $goods->enable_upgrade_user_role = 0;
            //$goods->upgrade_user_role_type = "";
            $goods->purchase_permission = "";
            $goods->first_buy_setting = json_encode(["buy_num" => 0, "return_red_envelopes" => 0, "return_commission" => 0, "period" => 0, "period_unit" => "month", "expire" => -1]);
            $goods->lianc_user_id = 0;
            $goods->lianc_commission_type = 1;
            $goods->lianc_commisson_value = 0;
            //$goods->goods_source = "";
            $goods->order_prompt = 0;
            $goods->order_prompt_content = "";
            $goods->enable_commisson_price = 0;
            $goods->branch_office_price = 0;
            $goods->partner_price = 0;
            $goods->store_price = 0;
            $goods->branch_office_freight_id = 0;
            $goods->partner_freight_id = 0;
            $goods->store_freight_id = 0;

            if(!$goods->save()){
                throw new \Exception(json_encode($goods->getErrors()));
            }

            $mallGoods = new MallGoods();
            $mallGoods->mall_id = $goods->mall_id;
            $mallGoods->goods_id = $goods->id;
            $mallGoods->is_sell_well = 0;
            $mallGoods->is_negotiable = 0;
            $mallGoods->created_at = time();
            $mallGoods->deleted_at = 0;
            $mallGoods->updated_at = time();
            $mallGoods->is_delete = 0;
            if(!$mallGoods->save()){
                throw new \Exception(json_encode($mallGoods->getErrors()));
            }

            $goodsAttr = new GoodsAttr();
            $goodsAttr->goods_id = $goods->id;
            $goodsAttr->sign_id = "2";
            $goodsAttr->stock = 999;
            $goodsAttr->price = $price;
            $goodsAttr->no = "";
            $goodsAttr->weight = 0;
            $goodsAttr->pic_url = "";
            $goodsAttr->branch_office_price = 0;
            $goodsAttr->partner_price = 0;
            $goodsAttr->store_price = 0;
            $goodsAttr->is_delete = 0;
            if(!$goodsAttr->save()){
                throw new \Exception(json_encode($goodsAttr->getErrors()));
            }

            $t->commit();

            return $goods;
        }catch (\Exception $e){
            $t->rollBack();
            return null;
        }

    }
}