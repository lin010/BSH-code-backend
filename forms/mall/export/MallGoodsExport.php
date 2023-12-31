<?php
/**
  * @link:http://www.gdqijianshi.com/
 * copyright: Copyright (c) 2020 广东七件事集团
 * author: zal
 */

namespace app\forms\mall\export;

use app\core\ApiCode;
use app\core\BasePagination;
use app\forms\mall\goods\BaseGoodsList;
use app\forms\mall\goods\GoodsListForm;
use app\forms\mall\goods\ImportGoodsLogForm;
use app\models\BaseActiveQuery;
use app\models\Goods;
use yii\helpers\ArrayHelper;

/**
 * Class MallGoodsExport
 * @package app\forms\mall\export
 * @Notes 商城商品导出
 */
class MallGoodsExport extends BaseExport
{
    public $send_type;
    public $newDataList;
    public function fieldsList()
    {
        $fieldsList = [
            [
                'key' => 'name',
                'value' => '商品名称',
            ],
            [
                'key' => 'goods_brand',
                'value' => '品牌'
            ],
            [
                'key' => 'goods_supplier',
                'value' => '供应商'
            ],
            [
                'key' => 'original_price',
                'value' => '原价',
            ],
            [
                'key' => 'cost_price',
                'value' => '成本价',
            ],
            [
                'key' => 'detail',
                'value' => '商品详情',
            ],
            [
                'key' => 'cover_pic',
                'value' => '商品缩略图',
            ],
            [
                'key' => 'pic_url',
                'value' => '商品轮播图',
            ],
            [
                'key' => 'video_url',
                'value' => '商品视频',
            ],
            [
                'key' => 'unit',
                'value' => '单位',
            ],
            [
                'key' => 'price',
                'value' => '售价',
            ],
            [
                'key' => 'first_attr_price',
                'value' => '第一规格价',
            ],
            [
                'key' => 'use_attr',
                'value' => '是否使用规格',
            ],
            [
                'key' => 'attr_groups',
                'value' => '规格组',
            ],
            [
                'key' => 'goods_stock',
                'value' => '商品库存',
            ],
            [
                'key' => 'virtual_sales',
                'value' => '虚拟销量',
            ],
            [
                'key' => 'real_sales',
                'value' => '真实销量',
            ],
            [
                'key' => 'confine_count',
                'value' => '购物数量限制',
            ],
            [
                'key' => 'pieces',
                'value' => '单品满件包邮',
            ],
            [
                'key' => 'forehead',
                'value' => '单品满额包邮',
            ],
            [
                'key' => 'give_score',
                'value' => '赠送积分',
            ],
            [
                'key' => 'give_score_type',
                'value' => '赠送积分类型',
            ],
            [
                'key' => 'forehead_score',
                'value' => '可抵扣积分',
            ],
            [
                'key' => 'forehead_score_type',
                'value' => '可抵扣积分类型',
            ],
            [
                'key' => 'accumulative',
                'value' => '允许多件累计折扣',
            ],
            [
                'key' => 'sign',
                'value' => '商品标识',
            ],
            [
                'key' => 'app_share_pic',
                'value' => '自定义分享图片',
            ],
            [
                'key' => 'app_share_title',
                'value' => '自定义分享标题',
            ],
            [
                'key' => 'sort',
                'value' => '排序',
            ],
            [
                'key' => 'confine_order_count',
                'value' => '限购订单',
            ],
            [
                'key' => 'is_area_limit',
                'value' => '是否单独区域购买',
            ],
            [
                'key' => 'area_limit',
                'value' => '区域限购详情',
            ],
            [
                'key' => 'attr',
                'value' => '规格详情',
            ],
            [
                'key' => 'is_sell_well',
                'value' => '是否热销',
            ],
            [
                'key' => 'is_negotiable',
                'value' => '是否面议',
            ]
        ];

        return $fieldsList;
    }

    /**
     * @param BaseActiveQuery $query
     * @return array|bool|resource
     */
    public function export($query)
    {
        $fieldsKeyList = [];
        foreach ($this->fieldsList() as $item) {
            $fieldsKeyList[] = $item['key'];
        }
        $this->fieldsKeyList = $fieldsKeyList;
        $this->getFields();

        if (is_array($this->newDataList)) {
            try {
                $this->dataList = $this->newDataList;
                $dataList = $this->getDataList();
                $fp = (new \app\core\CsvExport())->exportMultiple($dataList, $this->fieldsNameList);
                return $fp;
            } catch (\Exception $exception) {
                dd($exception);
            }
        } else {
            $page = \Yii::$app->request->post('page');
            $dirPath = \Yii::$app->basePath . \Yii::$app->urlManager->baseUrl . '/temp/goods/';
            if ($page == 1) {
                $this->deleteDir($dirPath);
            }
            $fileNum = ceil($page / 30); // 每300条数据保存成一个csv文件
            $fileName = '商品数据';
            $isDownload = 0;
            // 下载zip包
            if (\Yii::$app->request->post('is_download')) {
                $form = new ImportGoodsLogForm();
                $fileList = [];
                $dirs = scandir($dirPath);
                $newDirs = [];
                foreach ($dirs as $dir) {
                    if ($dir != '.' && $dir != '..') {
                        $newDirs[] = $dir;
                    }
                }

                for ($i = 1; $i <= count($newDirs); $i++) {
                    $filePath = '/temp/goods/' . $fileName . $i . '.csv';
                    $newFilePath = \Yii::$app->basePath . \Yii::$app->urlManager->baseUrl . $filePath;

                    $newItem = [];
                    $newItem['name'] = $fileName . $i . '.csv';
                    $newItem['data'] = fopen($newFilePath, 'a+');
                    $fileList[] = $newItem;
                }
                $form->zipFile('商品数据.zip', $fileList);
                return true;
            }

            $goodsCount = $query->count();
            $limit = 10;
            /**
             * @var BasePagination $pagination
             */
            $list = $query->with('goodsWarehouse', 'attr', 'mallGoods')->orderBy('g.created_at DESC')->page($pagination, $limit, $page)->all();
            try {
                $this->transform($list);
                $dataList = $this->getDataList();
                (new \app\core\CsvExport())->ajaxExport($dataList, $this->fieldsNameList, $this->getFileName($fileName, $fileNum));

                // 当查询全部数据后 返回可下载标识
                /** @var BasePagination  $pagination */
                if ($pagination->current_page == $pagination->page_count) {
                    $isDownload = 1;
                }
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '请求成功',
                    'data' => [
                        'pagination' => $pagination,
                        'export_goods' => [
                            'goods_count' => (int)$goodsCount,
                            'is_download' => $isDownload,
                        ],
                    ]
                ];
            } catch (\Exception $exception) {
                dd($exception);
            }
        }
    }

    /**
     * 获取csv名称
     * @return string
     */
    public function getFileName($fileName, $fileNum)
    {

        $fileName = $fileName . $fileNum;

        return $fileName;
    }

    protected function transform($list)
    {
        $goodsIds = [];
        foreach ($list as $item)
        {
            $goodsIds[] = $item->id;
        }
        $realSales = [];
        $realSaleDatas = (new GoodsListForm())->real_sales($goodsIds);
        if($realSaleDatas)
        {
            foreach($realSaleDatas as $realSale)
            {
                $realSales[$realSale->goods_id] = $realSale->num;
            }
        }

        $newList = [];
        $number = 1;
        /** @var Goods $item */
        foreach ($list as $item) {
            $arr = [];
            $arr['number'] = $number++;
            $arr['name'] = $item->name;
            $arr['original_price'] = $item->originalPrice;
            $arr['cost_price'] = $item->costPrice;
            $arr['detail'] = $item->detail;
            $arr['cover_pic'] = $item->coverPic;
            $arr['pic_url'] = $item->picUrl;
            $arr['video_url'] = $item->videoUrl;
            $arr['unit'] = $item->unit;
            $arr['price'] = $item->price;
            $arr['use_attr'] = $item->use_attr;
            $arr['attr_groups'] = $item->attr_groups;
            $arr['goods_stock'] = $item->goods_stock;
            $arr['virtual_sales'] = $item->virtual_sales;
            $arr['confine_count'] = $item->confine_count;
            $arr['pieces'] = $item->pieces;
            $arr['forehead'] = $item->forehead;
            $arr['give_score'] = $item->give_score;
            $arr['give_score_type'] = $item->give_score_type;
            $arr['forehead_score'] = $item->forehead_score;
            $arr['forehead_score_type'] = $item->forehead_score_type;
            $arr['accumulative'] = $item->accumulative;
            $arr['sign'] = $item->sign;
            $arr['app_share_pic'] = $item->app_share_pic;
            $arr['app_share_title'] = $item->app_share_title;
            $arr['sort'] = $item->sort;
            $arr['confine_order_count'] = $item->confine_order_count;
            $arr['is_area_limit'] = $item->is_area_limit;
            $arr['area_limit'] = $item->area_limit;
            $newAttr = ArrayHelper::toArray($item->attr);
            $attrGroups = \Yii::$app->serializer->decode($item->attr_groups);
            $attrList = $item->resetAttr($attrGroups);
            foreach ($newAttr as $key => $attrItem) {
                $newAttr[$key]['attr_list'] = $attrList[$attrItem['sign_id']];
            }
            $arr['attr'] = json_encode($newAttr, true);
          //  $arr['is_quick_shop'] = $item->mallGoods->is_quick_shop;
            $arr['is_sell_well'] = $item->mallGoods->is_sell_well;
            $arr['is_negotiable'] = $item->mallGoods->is_negotiable;

            //品牌
            $arr['goods_brand'] = $item->goods_brand;

            //第一规格价
            $arr['first_attr_price'] = isset($newAttr[0]) ? $newAttr[0]['price'] : 0;

            //真实销量
            $arr['real_sales'] = isset($realSales[$item->id]) ? $realSales[$item->id] : 0;

            $newList[] = $arr;
        }
        $this->dataList = $newList;
    }

    public function deleteDir($path) {

        if (is_dir($path)) {
            //扫描一个目录内的所有目录和文件并返回数组
            $dirs = scandir($path);

            foreach ($dirs as $dir) {
                //排除目录中的当前目录(.)和上一级目录(..)
                if ($dir != '.' && $dir != '..') {
                    //如果是目录则递归子目录，继续操作
                    $sonDir = $path.'/'.$dir;
                    if (is_dir($sonDir)) {
                        //目录内的子目录和文件删除后删除空目录
                        @rmdir($sonDir);
                    } else {
                        //如果是文件直接删除
                        @unlink($sonDir);
                    }
                }
            }
            @rmdir($path);
        }
    }
}
