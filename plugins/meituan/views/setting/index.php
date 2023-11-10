<?php

?>
<div id="app" v-cloak>
    <el-card class="box-card" v-loading="loading" shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span>基础设置</span>
            </div>
        </div>
        <div class="form_box">
            <el-card class="box-card" shadow="never">
                <el-form :model="ruleForm" size="small" ref="ruleForm" label-width="150px">
                    <el-tabs v-model="activeName" >
                        <el-tab-pane label="基本配置" name="first">
                            <el-form-item label="红包（金豆）服务费">
                                <el-input placeholder="请输入" type="number" min="0" v-model="ruleForm.integral_fee_rate" style="width:350px;"></el-input>
                            </el-form-item>
                        </el-tab-pane>
                        <el-tab-pane label="美团配置" name="second">
                            <el-form-item label="accessKey">
                                <el-input placeholder="请输入" v-model="ruleForm.accessKey" style="width:350px;"></el-input>
                            </el-form-item>
                            <el-form-item label="secretKey">
                                <el-input placeholder="请输入" v-model="ruleForm.secretKey" style="width:350px;"></el-input>
                            </el-form-item>
                            <el-form-item label="entId">
                                <el-input placeholder="请输入" v-model="ruleForm.entId" style="width:350px;"></el-input>
                            </el-form-item>
                            <el-form-item label="loginFreeUrl">
                                <el-input placeholder="请输入" v-model="ruleForm.loginFreeUrl" style="width:350px;"></el-input>
                            </el-form-item>
                        </el-tab-pane>
                    </el-tabs>
                    <el-form-item>
                        <el-button @click="update('ruleForm')" :loading="btnLoading" class="button-item" type="primary" style="margin-top: 24px;"size="small">保存</el-button>
                    </el-form-item>
                </el-form>
            </el-card>
        </div>

    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                activeName: 'first',
                loading: false,
                btnLoading: false,
                ruleForm: {
                    accessKey: "",
                    secretKey: "",
                    entId: "",
                    loginFreeUrl: "",
                    integral_fee_rate: ""
                }
            }
        },
        mounted: function () {
            this.loadData();
        },
        methods: {
            loadData() {
                this.loading = true;
                request({
                    params: {
                        r: 'plugin/meituan/mall/setting/index',
                    },
                    method: 'get'
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        this.ruleForm = Object.assign(this.ruleForm, e.data.data);
                    }
                }).catch(e => {
                    this.loading = false;
                })
            },
            update(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.btnLoading = true;
                        request({
                            params: {
                                r: 'plugin/meituan/mall/setting/index',
                            },
                            method: 'post',
                            data: {settings:this.ruleForm}
                        }).then(e => {
                            this.btnLoading = false;
                            if (e.data.code == 0) {
                                this.$message.success(e.data.msg);
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            this.$message.error(e);
                            this.btnLoading = false;
                        })
                    } else {
                        this.btnLoading = false;
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
        }
    });
</script>
<style>
    .form_box {
        background-color: #f3f3f3;
        padding: 0 0 20px;
    }

    .button-item {
        margin-top: 12px;
        padding: 9px 25px;
    }

    .el-input-group__append {
        background-color: #fff;
        color: #353535;
    }
</style>