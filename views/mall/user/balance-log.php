<?php
Yii::$app->loadComponentView('com-user-finance-stat');
?>
<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .input-item {
        display: inline-block;
        width: 250px;
        margin: 0 0 20px 20px;
    }

    .input-item .el-input__inner {
        border-right: 0;
    }

    .input-item .el-input__inner:hover{
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input__inner:focus{
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
        padding: 0!important;
        border: 0;
        margin: 0 5px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span>{{currencyAlias.balance_alias}}收支</span>
                <div style="float: right;margin: -5px 0">
                    <com-export-dialog :field_list='export_list' :params="searchData" @selected="exportConfirm"></com-export-dialog>
                </div>
            </div>
        </div>
        <div class="table-body">
            <el-date-picker size="small" v-model="date" type="datetimerange"
                            style="float: left"
                            value-format="yyyy-MM-dd HH:mm:ss"
                            range-separator="至" start-placeholder="开始日期"
                            @change="selectDateTime"
                            end-placeholder="结束日期">
            </el-date-picker>
            <div class="input-item">
                <el-input @keyup.enter.native="search" size="small" placeholder="请输入昵称搜索" v-model="keyword" clearable @clear="search">
                    <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
                </el-input>
            </div>
            <el-table :data="form" border style="width: 100%" v-loading="listLoading">

                <el-table-column prop="id" label="ID" width="80"></el-table-column>

                <el-table-column prop="user.nickname" label="昵称">
                    <template slot-scope="scope">
                        <com-user-finance-stat :user-id="parseInt(scope.row.user.id)">
                            {{scope.row.user.nickname}}
                        </com-user-finance-stat>
                    </template>
                </el-table-column>

                <el-table-column label="收支情况(元)" width="180">
                    <template slot-scope="scope">
                        <div style="font-size: 18px;" 
                        :style="{color: scope.row.type == 1 ? '#68CF3D' : scope.row.type == 2 ? '#F6AA5A' : ''}"
                        >
                        {{scope.row.type == 1 ? '+' : scope.row.type == 2 ? '-' : ''}}
                        {{scope.row.money}}
                        </div>
                    </template>
                </el-table-column>

                <el-table-column prop="desc" label="说明" width="500px"></el-table-column>

                <el-table-column label="备注">
                    <template slot-scope="scope">
                        <div flex="box:first" v-if="scope.row.info_desc">
                            <div style="padding-right: 10px" v-if="scope.row.info_desc.hasOwnProperty('pic_url') && scope.row.info_desc.pic_url.length > 0">
                                <com-image mode="aspectFill" :src="scope.row.info_desc.pic_url"></com-image>
                            </div>
                            <div v-if="scope.row.info_desc.hasOwnProperty('remark')">{{scope.row.info_desc.remark}}</div>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column prop="created_at" width="180" label="充值时间"></el-table-column>

            </el-table>
            <div style="text-align: right;margin: 20px 0;">
                <el-pagination @current-change="pagination" background layout="prev, pager, next"
                               :page-count="pageCount"></el-pagination>
            </div>
        </div>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                searchData: {
                    keyword: '',
                    date: '',
                    start_date: '',
                    end_date: '',
                },
                date: '',
                keyword: '',
                form: [],
                pageCount: 0,
                listLoading: false,
                export_list: [],
                currencyAlias:{
                    balance_alias: '',
                    red_envelope_alias: '',
                    integral_alias: '',
                    silver_beans_alias: '',
                },
            };
        },
        methods: {
            exportConfirm() {
                this.searchData.keyword = this.keyword;
                this.searchData.date = this.date;
            },
            pagination(currentPage) {
                this.page = currentPage;
                this.getList();
            },
            search() {
                this.page = 1;
                if(this.date == null) {
                    this.searchData.start_date = '';
                    this.searchData.end_date = ''
                }
                this.getList();
            },
            getList() {
                this.listLoading = true;
                request({
                    params: {
                        r: 'mall/user/balance-log',
                        page: this.page,
                        date: this.date,
                        user_id: getQuery('user_id'),
                        keyword: this.keyword,
                        start_date: this.searchData.start_date,
                        end_date: this.searchData.end_date,
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        let { list, export_list, pagination } = e.data.data;
                        this.form = list;
                        this.export_list = export_list;
                        this.pageCount = pagination.page_count;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                    this.listLoading = false;
                }).catch(e => {
                    this.listLoading = false;
                });
            },
            selectDateTime(e) {
                if(e != null) {
                    this.searchData.start_date = e[0];
                    this.searchData.end_date = e[1];
                }else {
                    this.searchData.start_date = '';
                    this.searchData.end_date = '';
                }
                this.search();
            },
            //获取币种别名函数
            getCurrencyAliasData(){
                request({
                    params: {
                        r: 'mall/setting/mall-more',
                        key:'t',
                        keys:'balance_alias,red_envelope_alias,integral_alias,silver_beans_alias',
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        this.currencyAlias = e.data.data;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                })
            },
        },
        mounted: function () {
            this.getCurrencyAliasData();
            this.getList();
        }
    });
</script>
