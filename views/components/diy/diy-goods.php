<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * Author: zal
 * Date: 2020-04-23
 * Time: 10:30
 */

$baseUrl = Yii::$app->request->baseUrl;
$diyIconPath = "{$baseUrl}/statics/img/mall/diy";
$hostInfo = Yii::$app->request->hostInfo;
?>
<template id="diy-goods">
    <div class="diy-goods">
        <div class="diy-component-preview">

            <div :flex="cMainFlex">
                <div :flex="cCatFlex" class="cat-list"
                     v-if="data.showCat && (data.catPosition=='left'||(data.catPosition=='top'&&cCatList.length>1))"
                     :class="'cat-list-'+data.catPosition+' cat-style-'+data.catStyle">
                    <div class="cat-item" v-for="(item,index) in cCatList" :class="index===0?'active':''"
                         flex="main:center cross:center">
                        <div class="cat-name">{{item.menuName}}</div>
                    </div>
                </div>
                <div class="goods-list" :class="'goods-list-style-'+data.listStyle">
                    <div v-for="(cat,catIndex) in cCatList">
                        <div v-if="data.catPosition==='left'" style="color: #666666;margin: 24px 11px;font-size: 24px">
                            {{cat.menuName}}
                        </div>
                        <div :style="cGoodsListStyle" flex>
                            <div v-for="(goods,goodsIndex) in cCatGoodsList(cat, catIndex)"
                                 class="goods-item"
                                 :class="data.catPosition==='left'?'goods-cat-list':''">
                                <div style="display: inline-block;" :style="cGoodsItemWidth">
                                    <div :style="cGoodsItemStyle" :flex="cGoodsItemFlex" style="position: relative;">
                                        <div class="goods-pic"
                                             :class="'goods-pic-'+data.goodsCoverProportion+' goods-pic-fill-'+data.fill"
                                             :style="'background-image: url('+goods.cover_pic+')'">
                                            <div v-if="data.showGoodsTag" class="goods-tag"
                                                 :style="'background-image: url('+data.goodsTagPicUrl+')'"></div>
                                        </div>
                                        <div :style="cGoodsItemInfoStyle">
                                            <div class="goods-name" :class="data.listStyle===-1?'goods-name-static':''">
                                                <template v-if="data.showGoodsName==1">{{goods.name}}</template>
                                            </div>
                                            <div flex="box:last">
                                                <div>
                                                    <div class="goods-price">
                                                        <template v-if="data.showGoodsOriginalPrice==1">{{data.originalPriceLabel}} ￥{{goods.original_price}}</template>
                                                    </div>
                                                    <div class="goods-price">
                                                        <template v-if="data.showGoodsPrice==1">{{data.priceLabel}} ￥{{goods.price}}</template>
                                                    </div>
                                                    <div class="goods-level-price">
                                                        <template v-if="data.showGoodsLevelPrice==1">{{data.levelPriceLabel}} ￥{{goods.price}}</template>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <template v-if="cShowBuyBtn==1">
                                                        <template v-if="data.buyBtn==='pic'">
                                                            <img :src="data.buyBtnPic"
                                                                 style="width: 48px;height: 48px">
                                                        </template>
                                                        <template v-if="data.buyBtn==='text'">
                                                            <div :style="cButtonStyle"
                                                                 style="font-size: 24px;border: 1px solid;color: #ffffff;">
                                                                {{data.buyBtnText}}
                                                            </div>
                                                        </template>
                                                    </template>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="diy-component-edit">
            <el-form label-width='160px' @submit.native.prevent>
                <el-form-item label="显示分类">
                    <el-switch v-model="data.showCat"
                               @change="showCatChange"
                               active-value="1"
                               inactive-value="0"

                    ></el-switch>
                </el-form-item>
                <template v-if="data.showCat==1">
                    <el-form-item label="分类栏位置">
                        <el-radio v-model="data.catPosition" label="top" @change="catPositionChange">顶部</el-radio>
                        <el-radio v-model="data.catPosition" label="left" @change="catPositionChange">左侧</el-radio>
                        <div style="color: #909399;line-height: normal;">只有一个分类时顶部不会显示分类栏</div>
                    </el-form-item>
                    <el-form-item label="分类样式" v-if="data.catPosition==='top'">
                        <el-radio v-model="data.catStyle" :label="1">样式1</el-radio>
                        <el-radio v-model="data.catStyle" :label="2">样式2</el-radio>
                    </el-form-item>
                    <el-form-item label="分类列表">
                        <div v-for="(cat,catIndex) in data.catList" class="cat-item">
                            <div class="cat-item-options">
                                <el-button type="primary" icon="el-icon-delete"
                                           @click="deleteCat(catIndex)"></el-button>
                            </div>
                            <div flex="box:first">
                                <div class="diy-goods-label">商品分类</div>
                                <div>{{cat.name}}</div>
                            </div>
                            <div flex="box:first">
                                <div class="diy-goods-label">菜单名称</div>
                                <div>
                                    <el-input v-model="cat.menuName" size="small"></el-input>
                                </div>
                            </div>
                            <div flex="box:first">
                                <div class="diy-goods-label">商品数量</div>
                                <div>
                                    <el-input v-model.number="cat.goodsNum" type="number" min="1" max="30"
                                              size="small" :disabled="cat.staticGoods"
                                              @change="catGoodsNumChange(catIndex)"></el-input>
                                </div>
                            </div>
                            <div flex="box:first">
                                <div class="diy-goods-label">自定义商品</div>
                                <div>
                                    <el-switch v-model="cat.staticGoods"></el-switch>
                                </div>
                            </div>
                            <div flex="box:first" v-if="cat.staticGoods">
                                <div class="diy-goods-label">商品列表</div>
                                <div>
                                    <draggable v-model="cat.goodsList" flex class="goods-list">
                                        <div class="goods-item" v-for="(goods,goodsIndex) in cat.goodsList">
                                            <el-tooltip effect="dark" content="移除商品" placement="top">
                                                <el-button @click="deleteGoods(goodsIndex,catIndex)" circle
                                                           class="goods-delete" type="danger"
                                                           icon="el-icon-close"></el-button>
                                            </el-tooltip>
                                            <div class="goods-pic"
                                                 :style="'background-image:url('+goods.cover_pic+')'"></div>
                                        </div>
                                    </draggable>
                                    <div class="goods-add">
                                        <el-tooltip effect="dark" content="添加商品" placement="top">
                                            <el-button @click="showGoodsDialog(catIndex)"
                                                       icon="el-icon-plus"></el-button>
                                        </el-tooltip>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <el-button size="small" @click="catDialog.visible=true">添加分类</el-button>
                    </el-form-item>
                </template>
                <template v-else>
                    <el-form-item label="商品列表">
                        <draggable v-model="data.list" flex class="goods-list">
                            <div class="goods-item"
                                 v-for="(goods,goodsIndex) in data.list">
                                <el-tooltip effect="dark" content="移除商品" placement="top">
                                    <el-button @click="deleteGoods(goodsIndex,null)" circle class="goods-delete"
                                               type="danger"
                                               icon="el-icon-close"></el-button>
                                </el-tooltip>
                                <div class="goods-pic"
                                     :style="'background-image:url('+goods.cover_pic+')'"></div>
                            </div>
                        </draggable>
                        <div class="goods-add">
                            <el-tooltip effect="dark" content="添加商品" placement="top">
                                <el-button size="small" @click="showGoodsDialog(null)"
                                           icon="el-icon-plus"></el-button>
                            </el-tooltip>
                        </div>
                    </el-form-item>
                    <el-form-item label="更新商品列表">
                        <el-button type="primary" size="small" @click="updateGoods()" icon="el-icon-refresh"></el-button>
                    </el-form-item>
                </template>
                <hr>
                <el-form-item label="列表样式" v-if="data.catPosition==='top'">
                    <el-radio v-model="data.listStyle" :label="-1" @change="listStyleChange">列表模式</el-radio>
                    <el-radio v-model="data.listStyle" :label="0" @change="listStyleChange">左右滑动</el-radio>
                    <el-radio v-model="data.listStyle" :label="1" @change="listStyleChange">一行一个</el-radio>
                    <el-radio v-model="data.listStyle" :label="2" @change="listStyleChange">一行两个</el-radio>
                    <el-radio v-model="data.listStyle" :label="3" @change="listStyleChange">一行三个</el-radio>
                </el-form-item>
                <el-form-item label="商品封面图宽高比例" v-if="data.listStyle==1">
                    <el-radio v-model="data.goodsCoverProportion" label="1-1">1:1</el-radio>
                    <el-radio v-model="data.goodsCoverProportion" label="3-2">3:2</el-radio>
                </el-form-item>
                <el-form-item label="商品封面图填充">
                    <el-radio v-model="data.fill" :label="1">填充</el-radio>
                    <el-radio v-model="data.fill" :label="0">留白</el-radio>
                </el-form-item>
                <el-form-item label="单品样式">
                    <el-radio v-model="data.goodsStyle" :label="1">无边框</el-radio>
                    <el-radio v-model="data.goodsStyle" :label="2">有边框</el-radio>
                    <template v-if="data.listStyle!==-1">
                        <el-radio v-model="data.goodsStyle" :label="3">无边框居中</el-radio>
                        <el-radio v-model="data.goodsStyle" :label="4">有边框居中</el-radio>
                    </template>
                </el-form-item>
                <el-form-item label="显示商品名称">
                    <el-switch v-model="data.showGoodsName"
                               active-value="1"
                               inactive-value="0"
                    ></el-switch>
                </el-form-item>
                <el-form-item label="显示商品市场价格">
                    <el-switch v-model="data.showGoodsOriginalPrice"></el-switch> <el-input class="price_input" size="small" placeholder="请输入市场价格标签" v-model="data.originalPriceLabel" max="11"></el-input>
                </el-form-item>
                <el-form-item label="显示商品官方价格">
                    <el-switch v-model="data.showGoodsPrice" active-value="1"
                               inactive-value="0"></el-switch> <el-input size="small" class="price_input" placeholder="请输入官方价格标签" v-model="data.priceLabel" max="11"></el-input>
                </el-form-item>
                <el-form-item label="显示商品会员折扣价格">
                    <el-switch v-model="data.showGoodsLevelPrice" active-value="1"
                               inactive-value="0"></el-switch> <el-input size="small" class="price_input" placeholder="请输入会员折扣价格标签" v-model="data.levelPriceLabel" max="11"></el-input>
                </el-form-item>
                
                <el-form-item label="显示购买按钮"
                              v-if="data.listStyle!=0&&(data.goodsStyle==1||data.goodsStyle==2)">
                    <el-switch v-model="data.showBuyBtn"></el-switch>
                </el-form-item>


                <el-form-item label="购买按钮样式"
                              v-if="data.listStyle!=0&&(data.goodsStyle==1||data.goodsStyle==2)&&data.showBuyBtn==1">
                    <el-radio v-model="data.buyBtn" label="pic">图标</el-radio>
                    <el-radio v-if="data.listStyle!=3"
                              v-model="data.buyBtn" label="text">文字
                    </el-radio>
                </el-form-item>

                <el-form-item label="购买按钮图标" v-if="data.buyBtn=='pic'&&data.showBuyBtn==1">
                    <com-image-upload v-model="data.buyBtnPic" width="64" height="64"></com-image-upload>
                </el-form-item>


                <el-form-item label="购买按钮文字样式"
                              v-if="data.listStyle!=0&&data.listStyle!=3&&(data.goodsStyle==1||data.goodsStyle==2)&&data.showBuyBtn==1&&data.buyBtn=='text'">
                    <el-radio v-model="data.buyBtnStyle" :label="1">填充</el-radio>
                    <el-radio v-model="data.buyBtnStyle" :label="2">线条</el-radio>
                    <el-radio v-model="data.buyBtnStyle" :label="3">圆角填充</el-radio>
                    <el-radio v-model="data.buyBtnStyle" :label="4">圆角线条</el-radio>
                </el-form-item>
                <el-form-item label="购买按钮颜色"
                              v-if="data.listStyle!=0&&data.listStyle!=3&&(data.goodsStyle==1||data.goodsStyle==2)&&data.showBuyBtn==1&&data.buyBtn=='text'">
                    <el-color-picker v-model="data.buttonColor"></el-color-picker>
                </el-form-item>
                <el-form-item label="购买按钮文字"
                              v-if="data.listStyle!=0&&data.listStyle!=3&&(data.goodsStyle==1||data.goodsStyle==2)&&data.showBuyBtn==1&&data.buyBtn=='text'">
                    <el-input maxlength="4" size="small" v-model="data.buyBtnText"></el-input>
                </el-form-item>
                <el-form-item label="显示商品角标">
                    <el-switch v-model="data.showGoodsTag"
                               active-value="1"
                               inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="商品角标" v-if="data.showGoodsTag==1">
                    <el-radio v-model="data.goodsTagPicUrl" v-for="tag in goodsTags" :label="tag.cover_pic"
                              :key="tag.name"
                              @change="goodsTagChange">
                        {{tag.name}}
                    </el-radio>
                    <el-radio v-model="data.customizeGoodsTag" :label="true" @change="customizeGoodsTagChange">自定义
                    </el-radio>
                </el-form-item>
                <el-form-item label="自定义商品角标" v-if="data.showGoodsTag==1&&data.customizeGoodsTag">
                    <com-image-upload v-model="data.goodsTagPicUrl" width="64" height="64"></com-image-upload>
                </el-form-item>
            </el-form>
        </div>
        <el-dialog title="选择分类" :visible.sync="catDialog.visible" :close-on-click-modal="false"
                   @open="loadCatData">
            <el-table :data="catDialog.list" v-loading="catDialog.loading" @selection-change="catSelectionChange">
                <el-table-column label="选择" type="selection"></el-table-column>
                <el-table-column label="ID" prop="id" width="100px"></el-table-column>
                <el-table-column label="名称" prop="name"></el-table-column>
            </el-table>
            <div style="text-align: center">
                <el-pagination
                        v-if="catDialog.pagination"
                        style="display: inline-block;"
                        background
                        @current-change="catDialogPageChange"
                        layout="prev, pager, next"
                        :page-size.sync="catDialog.pagination.pageSize"
                        :total="catDialog.pagination.totalCount">
                </el-pagination>
            </div>
            <div slot="footer">
                <el-button @click="catDialog.visible = false">取 消</el-button>
                <el-button type="primary" @click="addCat">确 定</el-button>
            </div>
        </el-dialog>
        <el-dialog title="选择商品" :visible.sync="goodsDialog.visible" :close-on-click-modal="false"
                   @open="loadGoodsData">
            <el-input size="mini" v-model="goodsDialog.keyword" placeholder="根据名称搜索" :clearable="true"
                      @clear="loadGoodsData" @keyup.enter.native="loadGoodsData">
                <el-button slot="append" @click="loadGoodsData">搜索</el-button>
            </el-input>
            <el-table :data="goodsDialog.list" v-loading="goodsDialog.loading" @selection-change="goodsSelectionChange">
                <el-table-column label="选择" type="selection"></el-table-column>
                <el-table-column label="ID" prop="id" width="100px"></el-table-column>
                <el-table-column label="名称" prop="name"></el-table-column>
            </el-table>
            <div style="text-align: center">
                <el-pagination
                        v-if="goodsDialog.pagination"
                        style="display: inline-block"
                        background
                        @current-change="goodsDialogPageChange"
                        layout="prev, pager, next"
                        :page-size.sync="goodsDialog.pagination.pageSize"
                        :total="goodsDialog.pagination.total_count">
                </el-pagination>
            </div>
            <div slot="footer">
                <el-button @click="goodsDialog.visible = false">取 消</el-button>
                <el-button type="primary" @click="addGoods">确 定</el-button>
            </div>

        </el-dialog>
    </div>
</template>
<script>
    Vue.component('diy-goods', {
        template: '#diy-goods',
        props: {
            value: Object,
        },
        data() {
            return {
                catDialog: {
                    visible: false,
                    page: 1,
                    loading: null,
                    pagination: null,
                    list: null,
                    selectedList: null,
                },
                goodsDialog: {
                    visible: false,
                    page: 1,
                    catIndex: null,
                    catId: null,
                    loading: null,
                    pagination: null,
                    list: null,
                    selectedList: null,
                    keyword: null
                },
                goodsTags: [
                    {
                        name: '热销',
                        cover_pic: '<?php echo $hostInfo . $diyIconPath?>/components/goods-tag-rx.png',
                    },
                    {
                        name: '新品',
                        cover_pic: '<?php echo $hostInfo . $diyIconPath?>/components/goods-tag-xp.png',
                    },
                    {
                        name: '折扣',
                        cover_pic: '<?php echo $hostInfo . $diyIconPath?>/components/goods-tag-zk.png',
                    },
                    {
                        name: '推荐',
                        cover_pic: '<?php echo $hostInfo . $diyIconPath?>/components/goods-tag-tj.png',
                    },
                ],
                data: {
                    showCat: 0,
                    catPosition: 'top',
                    catStyle: 1,
                    catList: [],
                    list: [],
                    listStyle: 1,
                    goodsCoverProportion: '1-1',
                    fill: 1,
                    goodsStyle: 1,
                    showGoodsName: 1,
                    showGoodsOriginalPrice:1,
                    showGoodsPrice: 1,
                    showGoodsLevelPrice: 1,
                    showBuyBtn: 1,
                    buyBtn: 'cart',
                    buyBtnStyle: 1,
                    buyBtnText: '购买',
                    buyBtnPic: '<?php echo $hostInfo . $baseUrl?>' + '/statics/img/mall/goods/cart_active.png',
                    buttonColor: '#ff4544',
                    showGoodsTag: 0,
                    customizeGoodsTag: false,
                    goodsTagPicUrl: '',
                    priceLabel:'官方价',
                    levelPriceLabel:'会员价',
                    originalPriceLabel:'市场价'
                }
            };
        },
        created() {


            if (!this.value) {
                this.$emit('input', JSON.parse(JSON.stringify(this.data)))
            } else {
                this.data = JSON.parse(JSON.stringify(this.value));
                if (!this.data.buttonColor) {
                    this.$set(this.data, 'buttonColor', '#ff4544');
                }
                if (!this.data.buyBtnPic) {
                    this.$set(this.data, 'buyBtnPic', '<?php echo $hostInfo. $baseUrl?>' + '/statics/img/mall/goods/cart_active.png',);
                }
            }
        },
        computed: {
            cMainFlex() {
                if (this.data.catPosition === 'left') {
                    return 'dir:left box:first';
                }
                if (this.data.catPosition === 'top') {
                    return 'dir:top';
                }
            },
            cCatFlex() {
                if (this.data.catPosition === 'left') {
                    return 'dir:top';
                }
                if (this.data.catPosition === 'top') {
                    return 'dir:left';
                }
            },
            cCatList() {
                if (this.data.showCat == 1) {
                    if (this.data.catList && this.data.catList.length) {
                        return this.data.catList;
                    } else {
                        const defaultCatItem = {
                            id: 0,
                            name: '分类名称',
                            menuName: '分类名称',
                            goodsList: [],
                            goodsNum: 3,
                        };
                        return [defaultCatItem, defaultCatItem];
                    }
                } else {
                    return [{
                        id: null,
                        name: null,
                        menuName: null,
                        goodsList: this.data.list,
                    }];
                }
            },
            cCatStyle() {
            },
            cGoodsListStyle() {
                if (this.data.listStyle === 0) {
                    return 'flex-wrap: nowrap;overflow-x:auto;';
                } else {
                    return 'flex-wrap: wrap;';
                }
            },
            cGoodsItemFlex() {
                if (this.data.listStyle === -1) {
                    return 'dir:left box:first cross:center';
                }
                return 'dir:top';
            },
            cGoodsItemStyle() {
                let style;
                if (this.data.listStyle == -1) {
                    style = 'border: 1px solid #e2e2e2;border-top-right-radius:10px;border-bottom-right-radius:10px;background:#fff;'
                } else {
                    style = 'border: 1px solid #e2e2e2;border-bottom-left-radius:10px;border-bottom-right-radius:10px;background:#fff;'
                }
                if (this.data.goodsStyle === 2 || this.data.goodsStyle === 4) {
                    return style
                } else {
                    return 'background:#fff;';
                }
            },
            cShowBuyBtn() {
                if (this.data.showBuyBtn != 1) {
                    return false;
                }
                if (this.data.goodsStyle === 3 || this.data.goodsStyle === 4 || this.data.listStyle === 0) {
                    return false;
                }
                return true;
            },
            cGoodsItemInfoStyle() {
                let style = '';
                if (this.data.goodsStyle === 3 || this.data.goodsStyle === 4) {
                    style += `text-align: center;`;
                }
                if (this.data.listStyle === -1) {
                    style += `height: 200px;padding: 20px 24px 20px 32px;`;
                } else {
                    style += `padding:24px 24px;`;
                }
                return style;
            },
            cGoodsItemWidth() {
                if (this.data.listStyle === 0) {
                    return 'width: 200px;';
                }
                return 'width: 100%;';
            },
            cButtonStyle() {
                console.log(this.data.buyBtnStyle);
                let style = `background: ${this.data.buttonColor};border-color: ${this.data.buttonColor};height:48px;line-height:48px;padding:0 20px;`;
                if (this.data.buyBtnStyle === 3 || this.data.buyBtnStyle === 4) {
                    style += `border-radius:999px;`;
                }
                if (this.data.buyBtnStyle === 2 || this.data.buyBtnStyle === 4) {
                    style += `background:#fff;color:${this.data.buttonColor}`;
                }
                return style;
            },
        },
        watch: {
            data: {
                deep: true,
                handler(newVal, oldVal) {
                    this.$emit('input', newVal, oldVal)
                },
            }
        },


        methods: {
            cCatGoodsList(cat, catIndex) {
                const goodsList = cat.goodsList;
                let newList = [];
                if (this.data.catPosition == 'top' && catIndex > 0) {
                    newList = [];
                } else {
                    if (goodsList && goodsList.length) {
                        newList = goodsList;
                    } else {
                        const defaultGoodsItem = {
                            id: 0,
                            name: '商品名称',
                            cover_pic: '',
                            price: '100.00',
                            original_price: '100.00',
                        };
                        newList = new Array(cat.goodsNum).fill(defaultGoodsItem);
                    }
                }
                return newList;
            },
            goodsTagChange(e) {
                this.data.goodsTagPicUrl = e;
                this.data.customizeGoodsTag = false;
            },
            catPositionChange(e) {
                if (e === 'left') {
                    this.data.listStyle = -1;
                }
            },
            customizeGoodsTagChange(e) {
                this.data.goodsTagPicUrl = '';
                this.data.customizeGoodsTag = true;
            },
            loadCatData() {
                this.catDialog.loading = true;
                this.catDialog.selectedList = null;
                this.$request({
                    params: {
                        r: 'mall/cat/index',
                        page: this.catDialog.page,
                    }
                }).then(response => {
                    this.catDialog.loading = false;
                    if (response.data.code === 0) {
                        let list = this.transCatList(response.data.data.list);
                        let newList = [];
                        for (let i in list) {
                            if (list[i].status == 1) {
                                newList.push(list[i]);
                            }
                        }
                        this.catDialog.list = newList;
                        // this.catDialog.pagination = response.data.data.pagination;
                    } else {
                    }
                }).catch(e => {
                });
            },
            transCatList(list) {
                let newList = [];
                for (let i in list) {
                    if (list[i].child && list[i].child.length) {
                        let listJ = list[i].child;
                        delete list[i].child;
                        newList.push(list[i]);
                        for (let j in listJ) {
                            if (listJ[j].child && listJ[j].child.length) {
                                let listK = listJ[j].child;
                                delete listJ[j].child;
                                newList.push(listJ[j]);
                                for (let k in listK) {
                                    newList.push(listK[k]);
                                }
                            } else {
                                newList.push(listJ[j]);
                            }
                        }
                    } else {
                        newList.push(list[i]);
                    }
                }
                return newList;
            },
            catDialogPageChange(page) {
                this.catDialog.page = page;
                this.loadCatData();
            },
            catSelectionChange(e) {
                this.catDialog.selectedList = e;
            },
            addCat() {
                this.catDialog.visible = false;
                for (let i in this.catDialog.selectedList) {
                    this.data.catList.push({
                        id: this.catDialog.selectedList[i].id,
                        name: this.catDialog.selectedList[i].name,
                        menuName: this.catDialog.selectedList[i].name,
                        goodsNum: 30,
                        staticGoods: false,
                        goodsList: [],
                    });
                }
                this.catDialog.selectedList = null;
            },
            deleteCat(index) {
                this.data.catList.splice(index, 1);
            },
			// 2.0 获取可添加的商品列表
            loadGoodsData() {
                this.goodsDialog.loading = true;
                this.$request({
                    params: {
                        r: 'mall/home-page/get-goods',
                        page: this.goodsDialog.page,
                        mch_id: 0,
                        cat_id: this.goodsDialog.catId,
                        keyword: this.goodsDialog.keyword
                    },
                }).then(response => {
                    this.goodsDialog.loading = false;
                    if (response.data.code === 0) {
                        this.goodsDialog.list = response.data.data.list;	//商品列表
                        this.goodsDialog.pagination = response.data.data.pagination;
                    } else {
                    }
                }).catch(e => {
                });
            },
            goodsDialogPageChange(page) {
                this.goodsDialog.page = page;
                this.loadGoodsData();
            },
            showGoodsDialog(catIndex) {
                console.log(this.goodsDialog.selectedList);
                if (catIndex !== null) {
                    this.goodsDialog.catIndex = catIndex;
                    this.goodsDialog.catId = this.data.catList[catIndex].id;
                } else {
                    this.goodsDialog.catIndex = null;
                    this.goodsDialog.catId = null;
                }
                this.goodsDialog.visible = true;
            },
            goodsSelectionChange(e) {
                this.goodsDialog.selectedList = e;
            },
            addGoods() {
                this.goodsDialog.visible = false;
                for (let i in this.goodsDialog.selectedList) {
                    console.log(this.goodsDialog.selectedList[i]);
                    const item = {
                        id: this.goodsDialog.selectedList[i].id,
                        name: this.goodsDialog.selectedList[i].name,
                        cover_pic: this.goodsDialog.selectedList[i].cover_pic,
                        price: this.goodsDialog.selectedList[i].price,
                        original_price: this.goodsDialog.selectedList[i].original_price,
                        max_deduct_integral: this.goodsDialog.selectedList[i].max_deduct_integral,
                        price_display: this.goodsDialog.selectedList[i].price_display
                    };
                    if (this.goodsDialog.catIndex !== null) {
                        this.data.catList[this.goodsDialog.catIndex].goodsList.push(item);
                    } else {
                        this.data.list.push(item);
                    }
                }
            },
            updateGoods(){
                this.$message({
                    message: '更新排序成功',
                    type: 'success'
                });
                // return true;
                let ids = [];
                for (let i in this.data.list) {
                    ids.push(this.data.list[i].id);
                }
                if(ids.length>0){
                    this.$request({
                        params: {
                            r: 'mall/home-page/get-goods',
                            page: this.goodsDialog.page,
                            mch_id: 0,
                            ids:ids,
                            limit:ids.length
                        },
                    }).then(response => {
                        if (response.data.code === 0) {
                            let list = response.data.data.list;
                            let newlist = [];
                            for(let i in list){
                                newlist.push({
                                    id: list[i].id,
                                    name: list[i].name,
                                    cover_pic: list[i].cover_pic,
                                    price: list[i].price,
                                    original_price: list[i].original_price,
                                    max_deduct_integral: list[i].max_deduct_integral,
                                    price_display: list[i].price_display
                                });
                            }
                            this.data.list = newlist;
                        }
                    }).catch(e => {
                    });
                }
                
                
            },
            deleteGoods(goodsIndex, catIndex) {
                if (catIndex !== null) {
                    this.data.catList[catIndex].goodsList.splice(goodsIndex, 1);
                } else {
                    this.data.list.splice(goodsIndex, 1);
                }
            },
            listStyleChange(listStyle) {
                if (listStyle === -1 && this.data.goodsStyle === 3) {
                    this.data.goodsStyle = 1;
                }
                if (listStyle === -1 && this.data.goodsStyle === 4) {
                    this.data.goodsStyle = 2;
                }
                if (listStyle === 3 && this.data.buyBtn === 'text') {
                    this.data.buyBtn = 'cart';
                }
            },
            catGoodsNumChange(catIndex) {
                if (this.data.catList[catIndex].goodsNum > 30) {
                    this.data.catList[catIndex].goodsNum = 30;
                }
                if (this.data.catList[catIndex].goodsNum < 1) {
                    this.data.catList[catIndex].goodsNum = 1;
                }
            },
            showCatChange(value) {
                if (!value) {
                    this.data.catPosition = 'top';
                }
            },
        }
    });
</script>
<style>
    /*-----------------设置部分--------------*/
    .diy-goods .diy-component-edit .diy-goods-label {
        width: 85px;
    }

    .diy-goods .diy-component-edit .cat-item {
        border: 1px solid #e2e2e2;
        margin-bottom: 5px;
        padding: 15px;
        max-width: 400px;
    }

    .diy-goods .diy-component-edit .goods-list {
        flex-wrap: wrap;
    }

    .diy-goods .diy-component-edit .goods-item,
    .diy-goods .diy-component-edit .goods-add {
        width: 50px;
        height: 50px;
        position: relative;
        margin-right: 15px;
        margin-bottom: 15px;
    }

    .diy-goods .diy-component-edit .goods-add .el-button {
        width: 100%;
        height: 100%;
        border-radius: 0;
        padding: 0;
    }

    .diy-goods .diy-component-edit .goods-pic {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
    }

    .diy-goods .diy-component-edit .goods-delete {
        position: absolute;
        left: calc(100% - 13px);
        top: -13px;
        width: 25px;
        height: 25px;
        line-height: 25px;
        padding: 0 0;
        visibility: hidden;
        z-index: 1;
    }

    .diy-goods .diy-component-edit .goods-item:hover .goods-delete {
        visibility: visible;
    }

    .diy-goods .diy-component-edit .cat-item-options {
        position: relative;
    }

    .diy-goods .diy-component-edit .cat-item-options .el-button {
        height: 25px;
        line-height: 25px;
        width: 25px;
        padding: 0;
        text-align: center;
        border: none;
        border-radius: 0;
        position: absolute;
        margin-left: 0;
        top: -16px;
        right: -40px;
    }

    /*-----------------预览部分--------------*/

    .diy-goods .diy-component-preview .cat-list {
    }

    .diy-goods .diy-component-preview .cat-list-top {
        background: #fff;
    }

    .diy-goods .diy-component-preview .cat-list-left {
        width: 160px;
        background-color: #f7f7f7;
    }

    .diy-goods .diy-component-preview .cat-item {
        height: 104px;
        padding: 0 10px;
        text-align: center;
        max-width: 100%;
        white-space: nowrap;
    }

    .diy-goods .diy-component-preview .cat-list-left .cat-name {
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .diy-goods .diy-component-preview .cat-item.active {
        background: #fff;
        color: #ff4544;
    }

    .diy-goods .diy-component-preview .cat-list-top .cat-item {
        margin: 0 20px;
    }

    .diy-goods .diy-component-preview .cat-list-top {
        border-bottom: 1px solid #e2e2e2;
        overflow-x: auto;
    }

    .diy-goods .diy-component-preview .cat-list-top.cat-style-1 .cat-item {
        border-bottom: 4px solid transparent;
    }

    .diy-goods .diy-component-preview .cat-list-top.cat-style-2 .cat-name {
        background: #ff4544;
        color: #fff;
        border-radius: 100px;
        padding: 0 18px;
    }

    .diy-goods .diy-component-preview .cat-list-top .cat-item.active {
        border-bottom-color: #ff4544;
    }

    .diy-goods .diy-component-preview .cat-list-left .cat-item {
        border-left: 2px solid transparent;
    }

    .diy-goods .diy-component-preview .cat-list-left .cat-item.active {
        border-left-color: #ff4544;
    }

    .diy-goods .diy-component-preview .goods-list {
        padding: 11px;
        background: #fff;
    }

    .diy-goods .diy-component-preview .goods-item {
        padding: 11px;
    }

    .diy-goods .diy-component-preview .goods-pic {
        background-size: cover;
        background-position: center;
        width: 99.8%;
        height: 700px;
        background-color: #f6f6f6;
        background-repeat: no-repeat;
        position: relative;
    }

    .diy-goods .diy-component-preview .goods-pic-3-2 {
        height: 471px;
    }

    .diy-goods .diy-component-preview .goods-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 10px;
    }

    .diy-goods .diy-component-preview .goods-name-static {
        height: 94px;
    }

    .diy-goods .diy-component-preview .goods-price {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #ff4544;
        line-height: 48px;
    }

    .diy-goods .diy-component-preview .goods-level-price {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #ff4544;
        line-height: 48px;
    }

    .diy-goods .diy-component-preview .goods-list-style--1 .goods-item,
    .diy-goods .diy-component-preview .goods-list-style-1 .goods-item {
        width: 100%;
    }

    .diy-goods .diy-component-preview .goods-list-style-2 .goods-item {
        width: 50%;
    }

    .diy-goods .diy-component-preview .goods-list-style-3 .goods-item {
        width: 33.333333%;
    }

    .diy-goods .diy-component-preview .goods-list-style-0 .goods-item {
        width: 249px;
    }

    .diy-goods .diy-component-preview .goods-list-style--1 .goods-pic {
        width: 152px;
        height: 152px;
        margin-left: 1px;
    }

    .diy-goods .diy-component-preview .goods-list-style-2 .goods-pic {
        height: 342px;
    }

    .diy-goods .diy-component-preview .goods-list-style-0 .goods-pic,
    .diy-goods .diy-component-preview .goods-list-style-3 .goods-pic {
        height: 200px;
    }

    .diy-goods .diy-component-preview .goods-pic-fill-0 {
        background-size: contain;
    }

    .diy-goods .diy-component-preview .buy-btn {
        border-color: #ff4544;
        color: #ff4544;
        padding: 0 20px;
        height: 48px;
        line-height: 50px;
        font-size: 24px;
    }

    .diy-goods .diy-component-preview .buy-btn.el-button--primary {
        background-color: #ff4544;
        color: #fff;
    }

    .diy-goods .diy-component-preview .goods-tag {
        position: absolute;
        top: 0;
        left: 0;
        width: 64px;
        height: 64px;
        z-index: 1;
        background-size: 100% 100%;
        background-position: center;
        background-repeat: no-repeat;
    }

    .diy-goods hr {
        border: none;
        height: 1px;
        background-color: #e2e2e2;
    }

    .diy-goods .diy-component-preview .goods-item .buy-btn.is-round {
        border-radius: 24px;
    }

    .diy-goods .goods-item.goods-cat-list {
        border-top: 1px solid #e2e2e2;
    }

    .diy-goods .goods-item.goods-cat-list:first-of-type {
        border-top: 0;
    }
    .price_input {
        width: 180px;
        margin-left:10px;
    }
</style>