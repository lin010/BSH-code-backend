<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-04-23
 * Time: 13:13
 */

$mchId = Yii::$app->admin->identity->mch_id;
Yii::$app->loadComponentView('goods/com-recycle-search');
?>

<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .com-goods-list .table-body .edit-sort-img {
        width: 14px;
        height: 14px;
        margin-left: 5px;
        cursor: pointer;
    }

    .com-goods-list .goods-cat .el-tag--mini {
        max-width: 60px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .com-goods-list .export-dialog .el-dialog {
        min-width: 350px;
    }

    .com-goods-list .export-dialog .el-dialog__body {
        padding: 20px 20px;
    }

    .com-goods-list .export-dialog .el-button--submit {
        color: #FFF;
        background-color: #409EFF;
        border-color: #409EFF;
    }

</style>
<template id="com-goods-list">
    <div class="com-goods-list">
        <el-card v-loading="listLoading" class="box-card" shadow="never" style="border:0"
                 body-style="background-color: #f3f3f3;padding: 10px 0 0;">
            <div slot="header">
                <span>商品回收站列表</span>
            </div>
            <div class="table-body">
                <com-search :tabs="tabs" :new-search="search" @to-search="toSearch"
                            :new-active-name="newActiveName"></com-search>
                <com-batch :choose-list="choose_list"
                           @to-search="getList"
                           @get-all-checked="getAllChecked"
                           :batch-update-status-url="batch_update_status_url"
                           :status-change-text="status_change_text"
                           :is-show-svip="isShowSvip"
                           :is-show-express="isShowExpress"
                           :is-show-integral="isShowIntegral"
                           :is-show-batch-button="isShowBatchButton"
                           :batch-list="batchList">
                    <template slot="batch" slot-scope="item">
                        <slot name="batch" :item="item.item"></slot>
                    </template>
                </com-batch>
                <el-table
                        ref="multipleTable"
                        :data="list"
                        border
                        style="width: 100%;margin-bottom: 15px"
                        @selection-change="handleSelectionChange"
                        @sort-change="sortChange">
                    <el-table-column prop="id" label="ID" sortable="false" width="100"></el-table-column>
                    <el-table-column v-if="!is_mch" prop="sort" :width="sort_goods_id != id ? 150 : 100" label="排序"
                                     sortable="false">
                        <template slot-scope="scope">
                            <div v-if="sort_goods_id != scope.row.id" flex="dir:left cross:center">
                                <span>{{scope.row.sort}}</span>
                                <el-button class="edit-sort" type="text" @click="editSort(scope.row)">
                                    <img src="statics/img/mall/order/edit.png" alt="">
                                </el-button>
                            </div>
                            <div style="display: flex;align-items: center" v-else>
                                <el-input style="min-width: 70px" type="number" size="mini" class="change"
                                          v-model="sort"
                                          autocomplete="off"></el-input>
                                <el-button class="change-quit" type="text" style="color: #F56C6C;padding: 0 5px"
                                           icon="el-icon-error"
                                           circle @click="quit()"></el-button>
                                <el-button class="change-success" type="text"
                                           style="margin-left: 0;color: #67C23A;padding: 0 5px"
                                           icon="el-icon-success" circle @click="changeSortSubmit(scope.row)">
                                </el-button>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column v-else prop="mchGoods.sort" :width="sort_goods_id != id ? 150 : 100" label="排序"
                                     sortable="false">
                        <template slot-scope="scope">
                            <div v-if="sort_goods_id != scope.row.id" flex="dir:left cross:center">
                                <span>{{scope.row.mchGoods.sort}}</span>
                                <img class="edit-sort-img" @click="editSort(scope.row)"
                                     src="statics/img/mall/order/edit.png" alt="">
                            </div>
                            <div style="display: flex;align-items: center" v-else>
                                <el-input style="min-width: 70px" type="number" size="mini" class="change"
                                          v-model="sort"
                                          autocomplete="off"></el-input>
                                <el-button class="change-quit" type="text" style="color: #F56C6C;padding: 0 5px"
                                           icon="el-icon-error"
                                           circle @click="quit()"></el-button>
                                <el-button class="change-success" type="text"
                                           style="margin-left: 0;color: #67C23A;padding: 0 5px"
                                           icon="el-icon-success" circle @click="changeSortSubmit(scope.row)">
                                </el-button>
                            </div>
                        </template>
                    </el-table-column>
                    <slot name="column-col-first"></slot>
                    <el-table-column label="分类"  width="200">
                        <template slot-scope="scope">
                            <div class="goods-cat" v-if="!is_mch">
                                <el-tag v-if="scope.row.cats && scope.row.cats.length > 0"
                                        size="mini">
                                    {{scope.row.cats[0].name}}
                                </el-tag>
                                <el-tooltip v-if="scope.row.cats && scope.row.cats.length > 1" placement="top">
                                    <div slot="content">
                                        <span v-for="item in scope.row.cats" :key="item.id">{{item.name}}&nbsp;</span>
                                    </div>
                                    <span>...</span>
                                </el-tooltip>
                            </div>
                            <div class="goods-cat" v-if="is_mch">
                                <el-tag v-if="scope.row.mchCats.length > 0" size="mini">
                                    {{scope.row.mchCats[0].name}}
                                </el-tag>
                                <el-tooltip v-if="scope.row.mchCats.length > 1" placement="top" effect="light">
                                    <div slot="content">
                                        <el-tag style="margin-right: 5px" size="mini"
                                                v-for="item in scope.row.mchCats" :key="item.id">
                                            {{item.name}}
                                        </el-tag>
                                    </div>
                                    <span>...</span>
                                </el-tooltip>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="商品名称" width="220">
                        <template slot-scope="scope">
                            <div flex="box:first">
                                <div style="padding-right: 10px;">
                                    <com-image mode="aspectFill" :src="scope.row.goodsWarehouse.cover_pic"></com-image>
                                </div>
                                <div flex="cross:top cross:center">
                                    <div v-if="goodsId != scope.row.id" flex="dir:left">
                                        <el-tooltip class="item"
                                                    effect="dark"
                                                    placement="top">
                                            <template slot="content">
                                                <div style="width: 320px;">{{scope.row.goodsWarehouse.name}}</div>
                                            </template>
                                            <com-ellipsis :line="2">{{scope.row.goodsWarehouse.name}}</com-ellipsis>
                                        </el-tooltip>
                                        <el-button v-if="is_edit_goods_name" style="padding: 0;" type="text"
                                                   @click="editGoodsName(scope.row)">
                                            <img src="statics/img/mall/order/edit.png" alt="">
                                        </el-button>
                                    </div>
                                    <div style="display: flex;align-items: center" v-else>
                                        <el-input style="min-width: 70px"
                                                  type="text"
                                                  size="mini"
                                                  class="change"
                                                  v-model="goodsName"
                                                  maxlength="100"
                                                  show-word-limit
                                                  autocomplete="off"
                                        ></el-input>
                                        <el-button class="change-quit" type="text"
                                                   style="color: #F56C6C;padding: 0 5px"
                                                   icon="el-icon-error"
                                                   circle @click="quit()"></el-button>
                                        <el-button class="change-success" type="text"
                                                   style="margin-left: 0;color: #67C23A;padding: 0 5px"
                                                   icon="el-icon-success" circle
                                                   @click="changeGoodsName(scope.row)">
                                        </el-button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="goods_brand" label="品牌" sortable="false" width="130"></el-table-column>
                    <el-table-column prop="price" label="售价" sortable="false" width="160"></el-table-column>
                    <el-table-column prop="goods_stock" label="库存" sortable="false" width="100">
                        <template slot-scope="scope">
                            <div v-if="scope.row.goods_stock > 0">{{scope.row.goods_stock}}</div>
                            <div v-else style="color: red;">售罄</div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="virtual_sales" width="110" label="虚拟销量" sortable="false"></el-table-column>
                    <el-table-column prop="real_sales" width="110" label="真实销量" sortable="false"></el-table-column>
                    <el-table-column prop="scope" width="180" label="添加时间">
                        <template slot-scope="scope">
                            {{scope.row.created_at|dateTimeFormat('Y-m-d H:i:s')}}
                        </template>
                    </el-table-column>
                    <el-table-column width="100" v-if="is_mch && mchMallSetting.is_goods_audit == 1" label="申请状态">
                        <template slot-scope="scope">
                            <template>
                                <el-button v-if="scope.row.mchGoods.status == 0 || scope.row.mchGoods.status == 3"
                                           @click="applyStatus(scope.row.id)"
                                           type="primary" size="mini">申请上架
                                </el-button>
                                <div v-if="scope.row.mchGoods.status == 1">
                                    申请中
                                </div>
                                <div v-if="scope.row.mchGoods.status == 2">
                                    已通过
                                </div>
                                <el-tooltip v-if="scope.row.mchGoods.status == 3 && scope.row.mchGoods.remark"
                                            effect="dark"
                                            :content="scope.row.mchGoods.remark"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </template>
                        </template>
                    </el-table-column>
                    <slot name="column-col"></slot>
                    <el-table-column
                            label="操作"
                            :width="actionWitch">
                        <template slot-scope="scope">
                            <slot name="action" :item="scope.row"></slot>
                            <template v-if="is_action">
                                <el-button v-if="!scope.row.not_editable" @click="recovery(scope.row, scope.$index)" type="text" circle size="mini">
                                    <el-tooltip class="item" effect="dark" content="恢复商品" placement="top">
                                        <img src="statics/img/mall/order/renew.png" alt="">
                                    </el-tooltip>
                                </el-button>
                                <el-button v-if="!scope.row.not_editable" @click="destroy(scope.row, scope.$index)" type="text" circle size="mini">
                                    <el-tooltip class="item" effect="dark" content="彻底删除" placement="top">
                                        <img src="statics/img/mall/del.png" alt="">
                                    </el-tooltip>
                                </el-button>
                                <!--<el-button v-if="!scope.row.not_editable" @click="edit(scope.row)" type="text" circle size="mini">
                                    <el-tooltip class="item" effect="dark" content="编辑" placement="top">
                                        <img src="statics/img/mall/edit.png" alt="">
                                    </el-tooltip>
                                </el-button>-->
								<el-tooltip class="item" v-if="scope.row.not_editable&&scope.row.groupBuyGoods"
								            effect="dark"
								            placement="top">
								    <template slot="content">
								        <div class="goods-cat">该商品正在参与拼团活动,不可编辑或删除</div>
								    </template>
								    <com-ellipsis :line="2">
										<el-tag v-if="scope.row.cats && scope.row.cats.length > 0"
										        size="mini">
										    拼团商品
										</el-tag>
									</com-ellipsis>
								</el-tooltip>
                            </template>
                        </template>
                    </el-table-column>
                </el-table>
				<!-- 这里就是el-table的分页数 -->
                <div flex="main:right cross:center" style="margin-top: 20px;">
                    <div v-if="pageCount > 0">
                        <el-pagination
                                @current-change="pagination"
                                background
                                :current-page="current_page"
                                layout="prev, pager, next"
                                :page-count="pageCount">
                        </el-pagination>
                    </div>
                </div>
            </div>
        </el-card>
    </div>
</template>
<script>
    Vue.component('com-goods-list', {
        template: '#com-goods-list',
        props: {
            goods_url: {
                type: String,
                default: 'mall/goods/recycle-bin'
            },
            edit_goods_url: {
                type: String,
                default: 'mall/goods/edit'
            },
            destroy_goods_url: {
                type: String,
                default: 'mall/goods/delete'
            },
            recovery_goods_url: {
                type: String,
                default: 'mall/goods/recovery-goods'
            },
            edit_goods_sort_url: {
                type: String,
                default: 'mall/goods/edit-sort'
            },
            edit_goods_status_url: {
                type: String,
                default: 'mall/goods/switch-status'
            },
            batch_update_status_url: {
                type: String,
                default: 'mall/goods/batch-update-status'
            },
            is_edit_goods_name: {
                type: Boolean,
                default: false
            },
            is_action: {
                type: Boolean,
                default: true
            },
            actionWitch: {
                type: Number,
                default: 200
            },
            is_add_goods: {
                type: Boolean,
                default: true
            },
            status_change_text: {
                type: String,
                default: '',
            },
            tabs: {
                type: Array,
                default: function () {
                    return [
                    ];
                }
            },
            /**
             * 批量设置参数
             * 具体参数看 com-batch 组件
             */
            batchList: Array,
            isShowSvip: true,// 批量设置超级会员卡是否显示
            isShowExpress: true, // 批量设置运费是否显示
            isShowIntegral: true,// 批量设置积分是否显示
            isShowBatchButton: true,//批量设置按钮是否显示
            isShowExportGoods: false,//商品导出按钮
        },
        data() {
            return {
                search: {
                    keyword: '',
                    status: '-1',
                    sort_prop: '',
                    sort_type: '',
                    cats: [],
                    date_start: null,
                    date_end: null,
                },
                list: [],
                listLoading: false,
                page: 1,
                pageCount: 0,
                current_page: 1,
                is_mch: <?= $mchId > 0 ? 1 : 0 ?>,
                mchMallSetting: {},
                choose_list: [],
                btnLoading: false,
                sort: 0,
                id: 0,// 应该无用了
                sort_goods_id: 0,
                goodsId: 0,
                goodsName: '',
                // 分类筛选
                dialogVisible: false,
                dialogLoading: false,
                options: [],
                cats: [],
                children: [],
                third: [],
                mch_id: '<?= $mchId ?>',
                newActiveName: '-1',
                exportDialogVisible: false,
                // 商品导出参数
                exportParams: {
                    page: 1,
                    is_show_download: false,
                    is_download: 0,
                    percentage: 0,
                    action_url: '<?= Yii::$app->urlManager->createUrl('mall/goods/export-goods-list') ?>',
                    goods_count: 0,//商品总数
                }
            };
        },
        created() {
            let self = this;
            if (this.is_mch) {
                this.getMchMallSetting();
            }
            if (getQuery('page') > 1) {
                this.page = getQuery('page');
            }
            if(localStorage.getItem('goods_page')){
                this.page = localStorage.getItem('goods_page');
                this.getList();
            }

            // 搜索条件从缓存中获取
            let search = this.getCookie('search');
            if (search) {
                let newSearch = JSON.parse(search);
                this.search.keyword = newSearch.keyword;
                this.search.cats = newSearch.cats;
                this.search.date_start = newSearch.date_start;
                this.search.date_end = newSearch.date_end;
                self.tabs.forEach(function (item) {
                    if (item.value == newSearch.status) {
                        self.search.status = newSearch.status;
                        self.newActiveName = newSearch.status;
                    }
                })
            }
            this.getList();
        },
        methods: {
            editGoodsName(row) {
                this.goodsId = row.id;
                this.goodsName = row.goodsWarehouse.name;
            },
            changeGoodsName(row) {
                let self = this;
                request({
                    params: {
                        r: 'mall/goods/update-goods-name'
                    },
                    data: {
                        goods_id: self.goodsId,
                        goods_name: self.goodsName
                    },
                    method: 'post'
                }).then((e) => {
                    if (e.data.code == 0) {
                        self.goodsId = null;
                        self.$message.success(e.data.msg);
                        self.getList();
                    }
                    else {
                        self.$message.error(e.data.msg);
                    }
                }).catch((e) => {
                    self.$message.error(e.data.msg);
                })
                ;
            },
            // 全选单前页
            handleSelectionChange(val) {
                let self = this;
                self.choose_list = [];
                val.forEach(function (item) {
                    self.choose_list.push(item.id);
                })
            },
            pagination(currentPage) {
                let self = this;
                self.page = currentPage;
                localStorage.setItem('goods_page',self.page);
                self.getList();
            },
            getList() {
                let self = this;
                self.listLoading = true;
                self.saveSearch();
                request({
                    params: {
                        r: self.goods_url,
                        page: self.page,
                        search: self.search,
                    },
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    self.list = e.data.data.list;
                    self.pageCount = e.data.data.pagination.page_count;
                    self.current_page = e.data.data.pagination.current_page;
                    self.exportParams.goods_count = e.data.data.goods_count;

                }).catch((e) => {
                    console.log(e);
                })
                ;
            },
            edit(row) {
                if (row.id) {
                    var path = window.location.origin + window.location.pathname + '?r=mall%2Fgoods%2Fedit&id=' + row.id + '&mch_id=' + row.mch_id + '&page=' + this.page;
                    window.open(path,'_blank');
                    // navigateTo({
                    //     r: this.edit_goods_url,
                    //     id: row.id,
                    //     mch_id: row.mch_id,
                    //     page: this.page,
                    // });
                    this.saveSearch();
                } else {
                    navigateTo({
                        r: this.edit_goods_url,
                        page: this.page
                    });
                }
            },
            getCookie(cname) {
                let name = cname + "=";
                let decodedCookie = decodeURIComponent(document.cookie);
                let ca = decodedCookie.split(';');
                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            },
            saveSearch() {
                document.cookie = "search=" + JSON.stringify(this.search);
            },
            destroy(row, index) {
                let self = this;
                self.$confirm('删除该条数据, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: this.destroy_goods_url,
                        },
                        method: 'post',
                        data: {
                            id: row.id,
                        }
                    }).then(e => {
                        self.listLoading = false;
                    if (e.data.code === 0) {
                        self.list.splice(index, 1);
                        self.$message.success(e.data.msg);
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).
                    catch(e => {
                        console.log(e);
                })
                    ;
                }).catch(() => {
                    self.$message.info('已取消删除')
            })
                ;
            },
            recovery(row, index) {
                let self = this;
                self.$confirm('是否移出回收站?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: this.recovery_goods_url,
                        },
                        method: 'post',
                        data: {
                            id: row.id,
                        }
                    }).then(e => {
                        self.listLoading = false;
                        if (e.data.code === 0) {
                            self.list.splice(index, 1);
                            self.$message.success(e.data.msg);
                        } else {
                            self.$message.error(e.data.msg);
                        }
                    }).
                    catch(e => {
                        console.log(e);
                    })
                    ;
                }).catch(() => {
                })
                ;
            },
            applyStatus(id) {
                let self = this;
                self.$confirm('申请上架, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                request({
                    params: {
                        r: 'mall/goods/apply-status',
                    },
                    method: 'post',
                    data: {
                        id: id,
                    }
                }).then(e => {
                    self.listLoading = false;
                if (e.data.code === 0) {
                    self.getList();
                    self.$message.success(e.data.msg);
                } else {
                    self.$message.error(e.data.msg);
                }
            }).
                catch(e => {
                    console.log(e);
            })
                ;
            }).
                catch(() => {
                    self.$message.info('已取消申请')
            })
                ;
            },
            // 商品上下架
            switchStatus(row) {
                let self = this;
                self.btnLoading = true;
                request({
                    params: {
                        r: this.edit_goods_status_url,
                    },
                    method: 'post',
                    data: {
                        status: row.status,
                        id: row.id,
                        mch_id: row.mch_id
                    }
                }).then(e => {
                    self.btnLoading = false;
                if (e.data.code === 0) {
                    self.$message.success(e.data.msg);
                } else {
                    self.$message.error(e.data.msg);
                }
                self.getList();
            }).
                catch(e => {
                    console.log(e);
            })
                ;
            },
            getMchMallSetting() {
                let self = this;
                request({
                    params: {
                        r: 'mall/mch/mch-setting',
                    },
                    method: 'get',
                }).then(e => {
                    self.mchMallSetting = e.data.data.setting;
            }).
                catch(e => {
                    console.log(e);
            })
                ;
            },
            toSearch(searchData) {
                this.page = 1;
                this.search = searchData;
                this.getList();
            },
            // 排序排列
            sortChange(row) {
                if (row.prop) {
                    this.search.sort_prop = row.prop;
                    this.search.sort_type = row.order == "descending" ? 0 : 1;
                } else {
                    this.search.sort_prop = '';
                    this.search.sort_type = '';
                }
                this.getList();
            },
            quit() {
                this.sort_goods_id = null;
                this.goodsId = null;
            },
            editSort(row) {
                this.sort_goods_id = row.id;
                this.sort = row.sort;
                if (this.is_mch) {
                    this.sort = row.mchGoods.sort;
                }
            },
            changeSortSubmit(row) {
                let self = this;
                row.sort = self.sort;
                let route = self.edit_goods_sort_url;
                if (!row.sort || row.sort < 0) {
                    self.$message.warning('排序值不能小于0')
                    return;
                }
                if (this.is_mch) {
                    route = 'plugin/mch/mall/goods/edit-sort';
                    row.mchGoods.sort = self.sort;
                }
                request({
                    params: {
                        r: route
                    },
                    method: 'post',
                    data: {
                        id: row.id,
                        sort: row.sort,
                    }
                }).then(e => {
                    self.btnLoading = false;
                if (e.data.code === 0) {
                    self.$message.success(e.data.msg);
                    this.sort_goods_id = null;
                    self.getList();
                } else {
                    self.$message.error(e.data.msg);
                }
            }).
                catch(e => {
                    self.$message.error(e.data.msg);
                self.btnLoading = false;
            })
                ;
            },
            getAllChecked(e) {
                this.$emit('get-all-checked', e)
            },
            // 商品导出
            exportGoods() {
                this.exportDialogVisible = true;
                this.exportParams = {
                    page: 1,
                    is_show_download: false,
                    is_download: 0,
                    percentage: 0,
                    action_url: '<?= Yii::$app->urlManager->createUrl('mall/goods/export-goods-list') ?>',
                    goods_count: 0,//商品总数
                }
            },
            newExportGoodsData(){
                /*let self = this;
                window.location.href = 'https://' + document.domain + '/web/index.php?r=mall%2Fgoods%2Fget-goods-data' + '&choose_list=' + self.choose_list;
                this.exportDialogVisible = false;*/
                this.exportGoodsData();
            },
            exportGoodsData() {
                let self = this;
                self.exportParams.is_show_download = true;
                request({
                    params: {
                        r: 'mall/goods/export-goods-list',
                    },
                    data: {
                        page: self.exportParams.page,
                        search: JSON.stringify(self.search),
                        flag: 'EXPORT',
                        choose_list: self.choose_list,
                        _csrf: '<?= Yii::$app->request->csrfToken ?>',
                        is_download: self.exportParams.is_download,
                    },
                    method: 'post',
                }).then(e => {
                    let data = e.data.data;
                if (e.data.code === 0) {
                    self.exportParams.increase = 100 / data.pagination.page_count;
                    self.exportParams.percentage += self.exportParams.increase;
                    self.exportParams.percentage = parseFloat(self.exportParams.percentage.toFixed(2));
                    if (data.pagination.current_page == data.pagination.page_count) {
                        self.exportParams.percentage = 100;
                        self.exportParams.is_download += data.export_goods.is_download;
                    }
                    if (data.pagination.current_page < data.pagination.page_count) {
                        self.exportParams.page += 1;
                        self.exportGoodsData();
                    }
                }
            }).
                catch(e => {
                    console.log(e);
            })
                ;
            },

            //商品复制
            copy(row) {
                console.log(row.id);
                let self = this;
                self.$confirm('复制该条数据, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: this.goods_copy_goods_url,
                        },
                        method: 'post',
                        data: {
                            id: row.id,
                        }
                    }).then(e => {
                        self.listLoading = false;
                        if (e.data.code === 0) {
                            self.$message.success(e.data.msg);
                            location.reload();
                        } else {
                            self.$message.error(e.data.msg);
                        }
                    }).
                    catch(e => {
                        console.log(e);
                    })
                    ;
                }).catch(() => {
                    self.$message.info('已取消复制')
                })
                ;
            },
        },
    });
</script>

