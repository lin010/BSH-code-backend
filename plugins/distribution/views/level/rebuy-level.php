<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广东七件事集团
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-05-08
 * Time: 16:11
 */

Yii::$app->loadPluginComponentView('rebuy-edit');
?>

<div id="app" v-cloak>
    <el-card shadow="never" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span>复购等级</span>
                <div style="float: right">
                    <el-button type="primary" size="small" style="padding: 9px 15px !important;" @click="editClick">
                        添加复购等级
                    </el-button>
                </div>
            </div>
        </div>
        <div class="table-body">
            <div class="input-item">
                <el-input @keyup.enter.native="search" size="small"
                          placeholder="请输入等级名称或等级进行搜索"
                          v-model="keyword"
                          clearable
                          @clear="search">
                    <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
                </el-input>
            </div>
            <el-table
                    v-loading="listLoading"
                    :data="list"
                    border
                    style="width: 100%">
                <el-table-column
                        prop="id"
                        label="ID"
                        width="80">
                </el-table-column>
                <el-table-column prop="level" label="复购等级" width="80">
                    <template slot-scope="scope">
                        <com-ellipsis :line="1">等级{{scope.row.level}}</com-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column prop="name" label="等级名称" width="80">

                </el-table-column>





                <el-table-column label="分销商等级名称" width="120">
                    <template slot-scope="scope">
                        <com-ellipsis :line="1">{{scope.row.distribution_level_name}}</com-ellipsis>
                    </template>
                </el-table-column>

                <el-table-column prop="upgrade_type" label="升级类型" width="200">
                    <template slot-scope="scope">

                         {{scope.row.upgrade_type==1?'高于或等于':'低于'}}

                    </template>
                </el-table-column>
                <el-table-column prop="child_num" label="邀请新人" width="200">

                </el-table-column>
                <el-table-column prop="price_type" label="分佣类型" width="200">
                    <template slot-scope="scope">

                        {{scope.row.price_type==1?'固定金额':'百分比'}}

                    </template>
                </el-table-column>

                <el-table-column prop="price" label="分佣"  width="200">

                </el-table-column>

                <el-table-column
                        label="启用状态"
                        width="120">
                    <template slot-scope="scope">
                        {{scope.row.is_enable==1?'已启用':'未启用'}}
                    </template>
                </el-table-column>
                <el-table-column
                        label="操作"
                        width="120">
                    <template slot-scope="scope">
                        <el-button circle size="mini" type="text" @click="editLevel(scope.row)">
                            <el-tooltip class="item" effect="dark" content="编辑" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button circle size="mini" type="text" @click="levelDelete(scope.row, scope.$index)">
                            <el-tooltip class="item" effect="dark" content="删除" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-tooltip>
                        </el-button>

                    </template>
                </el-table-column>
            </el-table>

            <div style="text-align: right;margin: 20px 0;">
                <el-pagination
                        @current-change="pagination"
                        background
                        layout="prev, pager, next"
                        :page-count="pageCount">
                </el-pagination>
            </div>
        </div>
    </el-card>
    <rebuy-edit v-model="edit.show" :row="edit.row" @success="success"></rebuy-edit>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                list: [],
                keyword: '',
                listLoading: false,
                page: 1,

                pageCount: 0,
                edit: {
                    show: false,
                    row:{}
                },
                condition_type_list: {
                    1: '下线用户数',
                    2: '累计佣金',
                    3: '已提现佣金'
                },
                price_type_list: {
                    1: '%',
                    2: '元'
                }
            };
        },
        mounted: function () {
            this.getList();
        },
        methods: {
            search() {
                this.page = 1;
                this.getList();
            },
            pagination(currentPage) {
                let self = this;
                self.page = currentPage;
                self.getList();
            },
            getList() {
                let self = this;
                self.listLoading = true;
                request({
                    params: {
                        r: 'plugin/distribution/mall/level/rebuy-level',
                        page: self.page,
                        keyword: this.keyword
                    },
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    self.list = e.data.data.list;

                    self.pageCount = e.data.data.pagination.page_count;
                }).catch(e => {
                    console.log(e);
                });
            },

            editLevel(row) {
                this.edit.row = row;
                this.edit.show = true;
            },
            editClick() {
                this.edit.show = true;
            },

            switchStatus(row) {
                let self = this;
                self.listLoading = true;
                request({
                    params: {
                        r: 'plugin/distribution/mall/level/switch-status',
                    },
                    method: 'post',
                    data: {
                        id: row.id,
                    }
                }).then(e => {
                    self.listLoading = false;
                    if (e.data.code === 0) {
                        self.$message.success(e.data.msg);
                    } else {
                        self.$message.error(e.data.msg);
                    }
                    self.getList();
                }).catch(e => {
                    console.log(e);
                });
            },

            success(e) {
                this.list = [];
                this.page = 1;
                this.getList();
            },
            demo(row) {

                console.log("测试")
            },
            levelDelete(row, index) {

                let self = this;
                self.$confirm('删除该分销商等级, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: 'plugin/distribution/mall/level/rebuy-delete',
                        },
                        method: 'get',
                        data: {
                            id: row.id,
                        }
                    }).then(e => {
                        self.listLoading = false;
                        if (e.data.code === 0) {
                            self.$message.success(e.data.msg);
                            self.list.splice(index, 1);
                        } else {
                            self.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        console.log(e);
                    });
                }).catch(() => {
                    self.$message.info('已取消删除')
                });
            },
        }
    });
</script>

<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .input-item {
        display: inline-block;
        width: 250px;
        margin: 0 0 20px;
    }

    .input-item .el-input__inner {
        border-right: 0;
    }

    .input-item .el-input__inner:hover {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input__inner:focus {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input-group__append {
        background-color: #fff;
        border-left: 0;
        width: 10%;
        padding: 0;
    }

    .input-item .el-input-group__append .el-button {
        padding: 0;
    }

    .input-item .el-input-group__append .el-button {
        margin: 0;
    }

    .table-body .el-button {
        padding: 0 !important;
        border: 0;
        margin: 0 5px;
    }
</style>