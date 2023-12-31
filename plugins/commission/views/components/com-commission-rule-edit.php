<style type="text/css">
.commission-rule{margin:5px 0px;border-left:1px solid #ddd;border-top:1px solid #ddd;}
.commission-rule td{padding:10px 10px;text-align:center;border-right:1px solid #ddd;border-bottom:1px solid #ddd;}
.commission-rule td:last-child{width:230px;}
</style>
<template id="com-commission-rule-edit">
    <el-card class="box-card">
        <div slot="header" class="clearfix">
            <el-radio-group @change="changeCommissionType" v-model="commission_type">
                <el-radio :label="1">按百分比</el-radio>
                <el-radio :label="2">按固定值</el-radio>
            </el-radio-group>
            <el-button style="float: right; padding: 3px 0" type="text">操作按钮</el-button>
        </div>
        <el-tabs type="border-card">

            <!-- 城市服务商 -->
            <el-tab-pane :label="userRoleTypes.branch_office">

                <table cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <th>关系</th>
                        <th style="width:200px;">消费返佣</th>
                    </tr>

                    <tr v-for="row in groupData.branch_office">
                        <td colspan="2">
                            <table class="commission-rule"  cellspacing="0" cellpadding="0" width="100%">
                                <tr v-for="chain in row.chain">
                                    <td v-for="role_type in chain.relationship">
                                        <span v-if="role_type == 'branch_office'">{{userRoleTypes.branch_office}}</span>
                                        <span v-if="role_type == 'partner'">{{userRoleTypes.partner}}</span>
                                        <span v-if="role_type == 'store'">{{userRoleTypes.store}}</span>
                                        <span v-if="role_type == 'all'">消费者</span>
                                    </td>
                                    <td>
                                        <el-input @input="changeCommissionChain(row, chain)" type="number" placeholder="请输入内容" v-model="chain.commisson_value">
                                            <template slot="append">{{commission_type == 1 ? '%' : '元'}}</template>
                                        </el-input>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>

            </el-tab-pane>

            <!-- 区域服务商 -->
            <el-tab-pane :label="userRoleTypes.partner">

                <table cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <th>关系</th>
                        <th style="width:200px;">消费返佣</th>
                    </tr>

                    <tr v-for="row in groupData.partner">
                        <td colspan="2">
                            <table  class="commission-rule"  cellspacing="0" cellpadding="0" width="100%">
                                <tr v-for="chain in row.chain">
                                    <td v-for="role_type in chain.relationship">
                                        <span v-if="role_type == 'branch_office'">{{userRoleTypes.branch_office}}</span>
                                        <span v-if="role_type == 'partner'">{{userRoleTypes.partner}}</span>
                                        <span v-if="role_type == 'store'">{{userRoleTypes.store}}</span>
                                        <span v-if="role_type == 'all'">消费者</span>
                                    </td>
                                    <td>
                                        <el-input @input="changeCommissionChain(row, chain)" type="number" placeholder="请输入内容" v-model="chain.commisson_value">
                                            <template slot="append">{{commission_type == 1 ? '%' : '元'}}</template>
                                        </el-input>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>

            </el-tab-pane>

            <!-- VIP代理商 -->
            <el-tab-pane :label="userRoleTypes.store">
                <table cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <th>关系</th>
                        <th style="width:200px;">消费返佣</th>
                    </tr>

                    <tr v-for="row in groupData.store">
                        <td colspan="2">
                            <table  class="commission-rule"  cellspacing="0" cellpadding="0" width="100%">
                                <tr v-for="chain in row.chain">
                                    <td v-for="role_type in chain.relationship">
                                        <span v-if="role_type == 'branch_office'">{{userRoleTypes.branch_office}}</span>
                                        <span v-if="role_type == 'partner'">{{userRoleTypes.partner}}</span>
                                        <span v-if="role_type == 'store'">{{userRoleTypes.store}}</span>
                                        <span v-if="role_type == 'all'">消费者</span>
                                    </td>
                                    <td>
                                        <el-input @input="changeCommissionChain(row, chain)" type="number" placeholder="请输入内容" v-model="chain.commisson_value">
                                            <template slot="append">{{commission_type == 1 ? '%' : '元'}}</template>
                                        </el-input>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </el-tab-pane>

        </el-tabs>
    </el-card>
</template>
<script>
    Vue.component('com-commission-rule-edit', {
        template: '#com-commission-rule-edit',
        props: ['itemType', 'ctype', 'chains', 'commissonPrice'],
        computed: {},
        watch: {
            chains: {
                handler(rows, oldval) {
                    rows = typeof rows == "object" ? rows : [];
                    var chains = [], item, key;
                    for(var i=0; i < rows.length; i++){
                        item = rows[i];
                        key = item.role_type+'_'+item.level+'_'+item.unique_key;
                        chains[key] = item.commisson_value;
                    }
                    var m, n, i, l;
                    for(m in this.groupData){
                        for(n in this.groupData[m]){
                            l = this.groupData[m][n].level;
                            for(i in this.groupData[m][n].chain){
                                key = m + '_' + l + '_' + this.groupData[m][n].chain[i].unique_key;
                                if(typeof chains[key] != "undefined"){
                                    this.groupData[m][n].chain[i].commisson_value = chains[key] ;
                                }
                            }
                        }
                    }
                },
                immediate: true
            },
            ctype: {
                handler(val, oldval) {
                    this.commission_type = val;
                },
                immediate: true
            }
        },
        data() {
            let branch_office_level_1_chain = [
                {commisson_value:0, relationship:['branch_office', 'branch_office'], unique_key:'branch_office#branch_office'},
                {commisson_value:0, relationship:['branch_office', 'partner'], unique_key:'branch_office#partner'},
                {commisson_value:0, relationship:['branch_office', 'store'], unique_key:'branch_office#store'},
                {commisson_value:0, relationship:['branch_office', 'all'], unique_key:'branch_office#all'}
            ];
            let partner_level_1_chain = [
                {commisson_value:0, relationship:['partner', 'partner'], unique_key:'partner#partner'},
                {commisson_value:0, relationship:['partner', 'store'], unique_key:'partner#store'},
                {commisson_value:0, relationship:['partner', 'all'], unique_key:'partner#all'},
            ];
            return {
                userRoleTypes: USER_ROLE_TYPES,
                commission_type: 1,
                groupData: {
                    branch_office:[
                        {role:'branch_office', level:1, chain: branch_office_level_1_chain},
                        {role:'branch_office', level:2, chain:[
                            {commisson_value:0, relationship:['branch_office', 'partner', 'all'], unique_key:'branch_office#partner#all'},
                            {commisson_value:0, relationship:['branch_office', 'store', 'all'], unique_key:'branch_office#store#all'}
                        ]},
                        {role:'branch_office', level:3, chain:[
                            {commisson_value:0, relationship:['branch_office', 'store', 'store', 'all'], unique_key:'branch_office#store#store#all'},
                            {commisson_value:0, relationship:['branch_office', 'partner', 'store', 'all'], unique_key:'branch_office#partner#store#all'},
                            {commisson_value:0, relationship:['branch_office', 'partner', 'partner', 'all'], unique_key:'branch_office#partner#partner#all'},
                        ]},
                        {role:'branch_office', level:4, chain:[
                            {commisson_value:0, relationship:['branch_office', 'partner', 'partner', 'store', 'all'], unique_key:'branch_office#partner#partner#store#all'},
                        ]},
                        {role:'branch_office', level:5, chain:[
                            {commisson_value:0, relationship:['branch_office', 'partner', 'partner', 'store', 'store', 'all'], unique_key:'branch_office#partner#partner#store#store#all'},
                        ]}
                    ],
                    partner:[
                        {role:'partner', level:1, chain:partner_level_1_chain},
                        {role:'partner', level:2, chain:[
                            {commisson_value:0, relationship:['partner', 'partner', 'all'], unique_key:'partner#partner#all'},
                            {commisson_value:0, relationship:['partner', 'store', 'all'], unique_key:'partner#store#all'}
                        ]},
                        {role:'partner', level:3, chain:[
                            {commisson_value:0, relationship:['partner', 'store', 'store', 'all'], unique_key:'partner#store#store#all'},
                            {commisson_value:0, relationship:['partner', 'partner', 'store', 'all'], unique_key:'partner#partner#store#all'},
                        ]}
                    ],
                    store:[
                        {role:'store', level:1, chain:[
                            {commisson_value:0, relationship:['store', 'store'], unique_key:'store#store'},
                            {commisson_value:0, relationship:['store', 'all'], unique_key:'store#all'}
                        ]},
                        {role:'store', level:2, chain:[
                            {commisson_value:0, relationship:['store', 'store', 'all'], unique_key:'store#store#all'}
                        ]}
                    ]
                }
            };
        },
        created() {},
        methods: {
            baseRowClassName({row, rowIndex}){
                return 'base-row-class';
            },
            changeCommissionType(){
                this.$emit('update', {
                    type: this.commission_type
                });
            },
            changeCommissionChain(row, chain){
                var chainList = this.transferChainGroupToList();
                for(var i=0; i < chainList.length; i++){
                    if(chainList[i].role_type == row.role &&
                        chainList[i].level == row.level &&
                        chainList[i].unique_key == chain.unique_key){
                        chainList[i].commisson_value = chain.commisson_value;
                    }
                }
                this.$emit('update', {
                    chains: chainList
                });
            },
            transferChainGroupToList(){
                var group =  this.groupData;
                var list = [], m, n, i, item, chain;
                for(m in group){
                    for(n in group[m]){
                        item = group[m][n];
                        for(i in group[m][n].chain){
                            chain = group[m][n].chain[i];
                            var new_item = {
                                role_type      : item.role,
                                level          : item.level,
                                commisson_value: chain.commisson_value,
                                unique_key     : chain.unique_key
                            };
                            list.push(new_item);
                        }
                    }
                }
                return list;
            },
        }
    });
</script>