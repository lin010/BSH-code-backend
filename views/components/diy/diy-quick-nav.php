<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2019/5/6
 * Time: 16:03
 */
?>

<template id="diy-quick-nav">
    <div class="diy-quick-nav">
        <div class="diy-component-preview">
            <div style="padding: 20px 0;text-align: center;">
                <div>快捷导航设置</div>
                <div style="font-size: 22px;color: #909399">本条内容不占高度</div>
            </div>
        </div>
        <div class="diy-component-edit">
            <el-form @submit.native.prevent label-width="100px">
                <el-form-item label="快捷导航开关">
                    <el-switch v-model="data.navSwitch" :inactive-value="0" :active-value="1"></el-switch>
                </el-form-item>
                <el-form-item v-if="data.navSwitch == 1" label="使用商城配置">
                    <el-switch v-model="data.useMallConfig"></el-switch>
                </el-form-item>
                <template v-if="!data.useMallConfig && data.navSwitch == 1">
                    <el-form-item label="导航样式">
                        <el-radio v-model="data.navStyle" :label="1">浮动</el-radio>
                        <el-radio v-model="data.navStyle" :label="2">固定底部</el-radio>
                    </el-form-item>
                    <el-form-item label="收起图标">
                        <com-image-upload width="100" height="100" v-model="data.closedPicUrl"></com-image-upload>
                    </el-form-item>
                    <el-form-item label="展开图标">
                        <com-image-upload width="100" height="100" v-model="data.openedPicUrl"></com-image-upload>
                    </el-form-item>
                    <div class="edit-item">
                        <div style="margin-bottom: 10px;">分享</div>
                        <el-form-item label="是否开启">
                            <el-switch v-model="data.share.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.share.opened" label="分享图片">
                            <com-image-upload width="100" height="100" v-model="data.share.pic_url"></com-image-upload>
                        </el-form-item>
                        <!--
                        <el-form-item v-if="data.share.opened" label="分享链接">
                            <el-input :disabled="true" size="small"
                                      v-model="data.share.link_url" autocomplete="off">
                                <com-pick-link slot="append" @selected="selectShareLink">
                                    <el-button size="mini">选择链接</el-button>
                                </com-pick-link>
                            </el-input>
                        </el-form-item>
                        -->
                    </div>
                    <div class="edit-item">
                        <div style="margin-bottom: 10px;">返回首页</div>
                        <el-form-item label="是否开启">
                            <el-switch v-model="data.home.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.home.opened" label="图标">
                            <com-image-upload width="100" height="100" v-model="data.home.picUrl"></com-image-upload>
                        </el-form-item>
                    </div>
                    <div class="edit-item">
                        <div style="margin-bottom: 10px;">小程序客服</div>
                        <el-form-item label="是否开启">
                            <el-switch v-model="data.customerService.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.customerService.opened" label="图标">
                            <com-image-upload width="100" height="100"
                                              v-model="data.customerService.picUrl"></com-image-upload>
                        </el-form-item>
                    </div>
                    <div class="edit-item">
                        <div style="margin-bottom: 10px;">一键拨号</div>
                        <el-form-item label="是否开启">
                            <el-switch v-model="data.tel.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.tel.opened" label="图标">
                            <com-image-upload width="100" height="100" v-model="data.tel.picUrl"></com-image-upload>
                        </el-form-item>
                        <el-form-item v-if="data.tel.opened" label="电话号码">
                            <el-input v-model="data.tel.number"></el-input>
                        </el-form-item>
                    </div>
                    <div class="edit-item">
                        <div style="margin-bottom: 10px;">网页链接</div>
                        <el-form-item label="是否开启">
                            <el-switch v-model="data.web.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.web.opened" label="图标">
                            <com-image-upload width="100" height="100" v-model="data.web.picUrl"></com-image-upload>
                        </el-form-item>
                        <el-form-item v-if="data.web.opened" label="网址">
                            <el-input v-model="data.web.url"></el-input>
                        </el-form-item>
                    </div>
                    <div class="edit-item">
                        <div style="margin-bottom: 10px;">跳转小程序</div>
                        <el-form-item label="是否开启">
                            <el-switch v-model="data.mApp.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.mApp.opened" label="图标">
                            <com-image-upload width="100" height="100" v-model="data.mApp.picUrl"></com-image-upload>
                        </el-form-item>
                        <el-form-item v-if="data.mApp.opened" label="appId">
                            <el-input v-model="data.mApp.appId"></el-input>
                        </el-form-item>
                        <el-form-item v-if="data.mApp.opened" label="页面路径">
                            <el-input v-model="data.mApp.page"></el-input>
                        </el-form-item>
                    </div>
                    <div class="edit-item">
                        <div style="margin-bottom: 10px;">地图导航</div>
                        <el-form-item label="是否开启">
                            <el-switch v-model="data.mapNav.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.mapNav.opened" label="图标">
                            <com-image-upload width="100" height="100" v-model="data.mapNav.picUrl"></com-image-upload>
                        </el-form-item>
                        <el-form-item v-if="data.mapNav.opened" label="详细地址">
                            <el-input v-model="data.mapNav.address"></el-input>
                        </el-form-item>
                        <el-form-item v-if="data.mapNav.opened" label="经纬度">
                            <com-map @map-submit="mapEvent">
                                <el-input v-model="data.mapNav.location" placeholder="点击进入地图选择" readonly></el-input>
                            </com-map>
                        </el-form-item>
                    </div>
                </template>
            </el-form>
        </div>
    </div>
</template>
<script>
    Vue.component('diy-quick-nav', {
        template: '#diy-quick-nav',
        props: {
            value: Object,
        },
        data() {
            return {
                data: {
                    navSwitch: 0,
                    useMallConfig: true,
                    navStyle: 1,
                    closedPicUrl: '',
                    openedPicUrl: '',
                    share: {
                        opened: false,
                        pic_url: '',
                        link_url: '',
                        open_type: '',
                        params: '',
                        key: ''
                    },
                    home: {
                        opened: false,
                        picUrl: '',
                    },
                    customerService: {
                        opened: false,
                        picUrl: '',
                    },
                    tel: {
                        opened: false,
                        picUrl: '',
                        number: '',
                    },
                    web: {
                        opened: false,
                        picUrl: '',
                        url: '',
                    },
                    mApp: {
                        opened: false,
                        picUrl: '',
                        appId: '',
                        page: '',
                    },
                    mapNav: {
                        opened: false,
                        picUrl: '',
                        address: '',
                        location: '',
                    },
                }
            };
        },
        created() {
            if (!this.value) {
                this.$emit('input', JSON.parse(JSON.stringify(this.data)))
            } else {
                this.data = JSON.parse(JSON.stringify(this.value));
            }
            if(!this.data.share){
                this.data['share'] = {
                    opened: false,
                    pic_url: '',
                };
            }
        },
        computed: {},
        watch: {
            data: {
                deep: true,
                handler(newVal, oldVal) {
                    this.$emit('input', newVal, oldVal)
                },
            }
        },
        methods: {
            mapEvent(e) {
                this.data.mapNav.location = e.lat + ',' + e.long;
                this.data.mapNav.address = e.address;
            },
            selectShareLink(e) {
                e.map(item => {
                    this.data.share.link_url = item.new_link_url;
                    this.data.share.open_type = item.open_type;
                    this.data.share.params = item.params;
                    this.data.share.key = item.key ? item.key : '';
                });
            },
        }
    });
</script>
<style>
    .diy-quick-nav .pic-select {
        width: 72px;
        height: 72px;
        color: #00a0e9;
        border: 1px solid #ccc;
        line-height: normal;
        text-align: center;
        cursor: pointer;
        font-size: 12px;
    }

    .diy-quick-nav .pic-preview {
        width: 72px;
        height: 72px;
        border: 1px solid #ccc;
        cursor: pointer;
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
    }

    .diy-quick-nav .edit-item {
        border: 1px solid #e2e2e2;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>