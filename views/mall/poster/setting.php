<style>
    .mobile-box {
        width: 400px;
        height: 740px;
        padding: 35px 11px;
        background-color: #fff;
        border-radius: 30px;
        margin-right: 20px;
    }

    .bg-box {
        position: relative;
        border: 1px solid #e2e3e3;
        width: 750px;
        height: 1334px;
        zoom: 0.5;
    }

    .bg-pic {
        width: 100%;
        height: 100%;
        background-size: 100% 100%;
        background-position: center;
    }

    .title {
        padding: 15px 0;
        background-color: #f7f7f7;
        margin-bottom: 10px;
    }

    .component-item {
        width: 100px;
        height: 100px;
        cursor: pointer;
        position: relative;
        padding: 10px 0;
        border: 1px solid #e2e2e2;
        margin-right: 15px;
        margin-top: 15px;
        border-radius: 5px;
    }

    .component-item.active {
        border: 1px solid #7BBDFC;
    }

    .component-item-remove {
        position: absolute;
        top: 0;
        right: 0;
        cursor: pointer;
        width: 28px;
        height: 28px;
    }

    .component-attributes-box {
        color: #ff4544;
    }

    .box-card {
        margin-top: 35px;
    }

    .el-tabs__header {
        padding: 0 20px;
        height: 56px;
        line-height: 56px;
        background-color: #fff;
    }

    .form-body {
        padding: 20px 35% 20px 20px;
        background-color: #fff;
        margin-bottom: 20px;
        width: 100%;
        height: 100%;
        position: relative;
        min-width: 640px;
    }

    .button-item {
        padding: 9px 25px;
        position: absolute !important;
        bottom: -52px;
        left: 0;
    }

    .el-card, .el-tabs__content {
        overflow: visible;
    }
</style>

<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" shadow="never" body-style="background-color: #f3f3f3;padding: 0 0;"
             v-loading="cardLoading">
        <el-form v-if="ruleForm" :model="ruleForm" :rules="rules" size="small" ref="ruleForm" label-width="20%">
            <el-tabs v-model="activeName" @tab-click="handleClick">
                <el-tab-pane label="分销海报" name="first">
                    <div style="display: flex;">
                        <div class="mobile-box" flex="dir:top">
                            <div class="bg-box">
                                <div class="bg-pic"
                                     :style="{'background-image':'url('+ruleForm.share.bg_pic.url+')'}">
                                </div>
                                <com-image v-if="ruleForm.share.head.is_show == 1"
                                           mode="aspectFill"
                                           radius="50%"
                                           :style="{
                                                    position: 'absolute',
                                                    top: ruleForm.share.head.top + 'px',
                                                    left: ruleForm.share.head.left + 'px'}"
                                           :width='ruleForm.share.head.size + ""'
                                           :height='ruleForm.share.head.size + ""'
                                           src="statics/img/mall/poster/default_head.png">
                                </com-image>
                                <com-image v-if="ruleForm.share.qr_code.is_show == 1"
                                           mode="aspectFill"
                                           :radius="ruleForm.share.qr_code.type == 1 ? '50%' : '0%'"
                                           :style="{
                                                    position: 'absolute',
                                                    top: ruleForm.share.qr_code.top + 'px',
                                                    left: ruleForm.share.qr_code.left + 'px'}"
                                           :width='ruleForm.share.qr_code.size + ""'
                                           :height='ruleForm.share.qr_code.size + ""'
                                           src="statics/img/mall/poster/default_qr_code.png">
                                </com-image>

                                <span v-if="ruleForm.share.name.is_show == 1 && ruleForm.share.name.center == 1"
                                      :style="{
                                                    position: 'absolute',
                                                    top: ruleForm.share.name.top + 'px',
                                                    left: ruleForm.share.name.left + 'px',
                                                    fontSize: ruleForm.share.name.font * 2 + 'px',
                                                    color: ruleForm.share.name.color}">
                                          用户昵称
                                </span>

                                <span v-else="ruleForm.share.name.is_show == 1 && ruleForm.share.name.center == 2"
                                      :style="{
                                                    position: 'absolute',
                                                    top: ruleForm.share.name.top + 'px',
                                                    fontSize: ruleForm.share.name.font * 2 + 'px',
                                                    color: ruleForm.share.name.color,
                                                    textAlign: 'center',
                                                    display: 'block',
                                                    width: '100%',
                                               }"
                                >
                                    用户昵称
                                </span>

                            </div>
                        </div>
                        <div class="form-body" v-if="ruleForm.share.bg_pic.url" flex="dir:top">
                            <com-attachment style="margin-bottom: 15px" :multiple="false" :max="1"
                                            v-model="ruleForm.share.bg_pic.url">
                                <el-tooltip class="item"
                                            effect="dark"
                                            content="建议尺寸:750 * 1334"
                                            placement="top">
                                    <el-button size="mini">
                                        {{ruleForm.share.bg_pic.url ? '更换背景图' : '添加背景图'}}
                                    </el-button>
                                </el-tooltip>
                            </com-attachment>
                            <div flex="wrap:wrap">
                                <div v-for="(item,index) in shareComponent"
                                     @click="componentItemClick(index)"
                                     class="component-item"
                                     :class="shareComponentKey == item.key ? 'active' : ''"
                                     flex="dir:top cross:center main:center">
                                    <img :src="item.icon_url">
                                    <div>{{item.title}}</div>
                                    <img v-if="test(index)"
                                         @click.stop="componentItemRemove(index)"
                                         class="component-item-remove"
                                         src="statics/img/mall/poster/icon_delete.png">
                                </div>
                            </div>
                            <el-card shadow="never" class="box-card" style="width: 100%">
                                <div slot="header">
                                    <span v-if="shareComponentKey == 'head'">头像设置</span>
                                    <span v-if="shareComponentKey == 'name'">昵称设置</span>
                                    <span v-if="shareComponentKey == 'qr_code'">二维码设置</span>
                                </div>
                                <div>
                                    <template v-if="shareComponentKey == 'head'">
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=40
                                                    :max=300
                                                    v-model="ruleForm.share.head.size"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.share.head.size)
                                                    v-model="ruleForm.share.head.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.share.head.size)
                                                    v-model="ruleForm.share.head.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                    </template>
                                    <template v-else-if="shareComponentKey == 'name'">
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=12
                                                    :max=40
                                                    v-model="ruleForm.share.name.font"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.share.name.font)
                                                    v-model="ruleForm.share.name.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距" v-if="showLeftSpacing">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.share.name.font)
                                                    v-model="ruleForm.share.name.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左右间距">
                                            <el-radio-group v-model="ruleForm.share.name.center" @change="spacing">
                                                <el-radio :label="1">调整</el-radio>
                                                <el-radio :label="2">居中</el-radio>
                                            </el-radio-group>
                                        </el-form-item>
                                        <el-form-item label="颜色">
                                            <el-color-picker
                                                    style="margin-left: 20px;"
                                                    color-format="rgb"
                                                    v-model="ruleForm.share.name.color"
                                                    :predefine="predefineColors">
                                            </el-color-picker>
                                        </el-form-item>
                                    </template>
                                    <template v-else-if="shareComponentKey == 'qr_code'">
                                        <el-form-item label="样式">
                                            <el-radio v-model="ruleForm.share.qr_code.type" :label="1">圆形</el-radio>
                                            <el-radio v-model="ruleForm.share.qr_code.type" :label="2">方形</el-radio>
                                        </el-form-item>
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=80
                                                    :max=300
                                                    v-model="ruleForm.share.qr_code.size"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.share.qr_code.size)
                                                    v-model="ruleForm.share.qr_code.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.share.qr_code.size)
                                                    v-model="ruleForm.share.qr_code.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                    </template>
                                </div>
                            </el-card>
                            <el-button class="button-item" :loading="btnLoading" type="primary"
                                       @click="store('ruleForm')" size="small">保存
                            </el-button>
                        </div>
                    </div>
                </el-tab-pane>
                <el-tab-pane label="商品海报" name="second">
                    <div style="display: flex;">
                        <div class="mobile-box" flex="dir:top">
                            <div class="bg-box">
                                <div class="bg-pic" :style="{'background-image':'url('+ruleForm.goods.bg_pic.url+')'}">
                                </div>
                                <com-image
                                        v-if="ruleForm.goods.pic.is_show == 1"
                                        mode="aspectFill" width='375px' height="375px"
                                        :style="{
                                                    position: 'absolute',
                                                    width: ruleForm.goods.pic.width + 'px',
                                                    height: ruleForm.goods.pic.height + 'px',
                                                    top: ruleForm.goods.pic.top + 'px',
                                                    left: ruleForm.goods.pic.left + 'px'}"
                                        src="statics/img/mall/poster/default_goods.jpg">
                                </com-image>
                                <com-image v-if="ruleForm.goods.qr_code.is_show == 1"
                                           mode="aspectFill"
                                           :radius="ruleForm.goods.qr_code.type == 1 ? '50%' : '0%'"
                                           :style="{
                                                     position: 'absolute',
                                                     top: ruleForm.goods.qr_code.top + 'px',
                                                     left: ruleForm.goods.qr_code.left + 'px'}"
                                           :width='ruleForm.goods.qr_code.size + ""'
                                           :height='ruleForm.goods.qr_code.size + ""'
                                           src="statics/img/mall/poster/default_qr_code.png">
                                </com-image>
                                <com-image v-if="ruleForm.goods.head.is_show == 1"
                                           mode="aspectFill"
                                           radius="50%"
                                           :style="{
                                                    position: 'absolute',
                                                    top: ruleForm.goods.head.top + 'px',
                                                    left: ruleForm.goods.head.left + 'px'}"
                                           :width='ruleForm.goods.head.size + ""'
                                           :height='ruleForm.goods.head.size + ""'
                                           src="statics/img/mall/poster/default_head.png">
                                </com-image>
                                <span v-if="ruleForm.goods.nickname.is_show == 1"
                                      :style="{
                                                    position: 'absolute',
                                                    top: ruleForm.goods.nickname.top + 'px',
                                                    left: ruleForm.goods.nickname.left + 'px',
                                                    fontSize: ruleForm.goods.nickname.font * 2 + 'px',
                                                    color: ruleForm.goods.nickname.color}">
                                          用户昵称
                                    </span>


                                <span v-if="ruleForm.goods.name.is_show == 1"
                                      :style="{
                                                position: 'absolute',
                                                top: ruleForm.goods.name.top + 'px',
                                                left: ruleForm.goods.name.left + 'px',
                                                fontSize: ruleForm.goods.name.font * 2 + 'px',
                                                color: ruleForm.goods.name.color}">
                                    商品名称|商品名称
                                </span>
                                <span v-if="ruleForm.goods.price.is_show == 1"
                                      :style="{
                                                position: 'absolute',
                                                top: ruleForm.goods.price.top + 'px',
                                                left: ruleForm.goods.price.left + 'px',
                                                fontSize: ruleForm.goods.price.font * 2 + 'px',
                                                color: ruleForm.goods.price.color}">
                                        ￥99.00
                                </span>
                                <span v-if="ruleForm.goods.desc.is_show == 1"
                                      :style="{
                                                width: ruleForm.goods.desc.width + 'px',
                                                wordWrap: 'break-word',
                                                wordBreak: 'normal',
                                                position: 'absolute',
                                                top: ruleForm.goods.desc.top + 'px',
                                                left: ruleForm.goods.desc.left + 'px',
                                                fontSize: ruleForm.goods.desc.font * 2 + 'px',
                                                color: ruleForm.goods.desc.color}">
                                        {{ruleForm.goods.desc.text}}
                                </span>
                            </div>
                        </div>
                        <div class="form-body" flex="dir:top">
                            <div flex="dir:left" style="margin-bottom: 15px">
                                <com-attachment :multiple="false" :max="1"
                                                v-model="ruleForm.goods.bg_pic.url">
                                    <el-tooltip class="item"
                                                effect="dark"
                                                content="建议尺寸:750 * 1334"
                                                placement="top">
                                        <el-button size="mini">
                                            {{ruleForm.goods.bg_pic.url ? '更换背景图' : '添加背景图'}}
                                        </el-button>
                                    </el-tooltip>
                                </com-attachment>
                                <el-button v-if="ruleForm.goods.bg_pic.url" @click="removeBgPic()"
                                           style="margin-left: 10px;"
                                           type="danger"
                                           size="mini">
                                    删除背景
                                </el-button>
                            </div>
                            <div flex="wrap:wrap">
                                <div v-for="(item,index) in goodsComponent"
                                     @click="componentItemClick(index)"
                                     class="component-item"
                                     :class="goodsComponentKey == item.key ? 'active' : ''"
                                     flex="dir:top cross:center main:center">
                                    <img :src="item.icon_url">
                                    <div>{{item.title}}</div>
                                    <img v-if="test2(index)"
                                         @click.stop="componentItemRemove(index)"
                                         class="component-item-remove"
                                         src="statics/img/mall/poster/icon_delete.png">
                                </div>
                            </div>
                            <el-card shadow="never" class="box-card" style="width: 100%;">
                                <div slot="header">
                                    <span v-if="goodsComponentKey == 'head'">头像</span>
                                    <span v-if="goodsComponentKey == 'nickname'">昵称</span>
                                    <span v-if="goodsComponentKey == 'pic'">商品图片设置</span>
                                    <span v-if="goodsComponentKey == 'name'">商品名称设置</span>
                                    <span v-if="goodsComponentKey == 'price'">商品价格设置</span>
                                    <span v-if="goodsComponentKey == 'desc'">海报描述设置</span>
                                    <span v-if="goodsComponentKey == 'qr_code'">二维码设置</span>
                                </div>
                                <div>
                                    <template v-if="goodsComponentKey == 'head'">
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=40
                                                    :max=300
                                                    v-model="ruleForm.goods.head.size"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.goods.head.size)
                                                    v-model="ruleForm.goods.head.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.goods.head.size)
                                                    v-model="ruleForm.goods.head.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                    </template>
                                    <template v-else-if="goodsComponentKey == 'nickname'">
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=12
                                                    :max=40
                                                    v-model="ruleForm.goods.nickname.font"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.goods.nickname.font)
                                                    v-model="ruleForm.goods.nickname.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.goods.nickname.font)
                                                    v-model="ruleForm.goods.nickname.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="颜色">
                                            <el-color-picker
                                                    style="margin-left: 20px;"
                                                    color-format="rgb"
                                                    v-model="ruleForm.goods.nickname.color"
                                                    :predefine="predefineColors">
                                            </el-color-picker>
                                        </el-form-item>
                                    </template>

                                    <template v-else-if="goodsComponentKey == 'name'">
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=12
                                                    :max=30
                                                    v-model="ruleForm.goods.name.font"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.goods.name.font)
                                                    v-model="ruleForm.goods.name.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.goods.name.font)
                                                    v-model="ruleForm.goods.name.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="颜色">
                                            <el-color-picker
                                                    style="margin-left: 20px;"
                                                    color-format="rgb"
                                                    v-model="ruleForm.goods.name.color"
                                                    :predefine="predefineColors">
                                            </el-color-picker>
                                        </el-form-item>
                                    </template>

                                    <template v-else-if="goodsComponentKey == 'price'">
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=12
                                                    :max=30
                                                    v-model="ruleForm.goods.price.font"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.goods.price.font)
                                                    v-model="ruleForm.goods.price.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.goods.price.font)
                                                    v-model="ruleForm.goods.price.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="颜色">
                                            <el-color-picker
                                                    style="margin-left: 20px;"
                                                    color-format="rgb"
                                                    v-model="ruleForm.goods.price.color"
                                                    :predefine="predefineColors">
                                            </el-color-picker>
                                        </el-form-item>
                                    </template>

                                    <template v-else-if="goodsComponentKey == 'qr_code'">
                                        <el-form-item label="样式">
                                            <el-radio v-model="ruleForm.goods.qr_code.type" :label="1">圆形</el-radio>
                                            <el-radio v-model="ruleForm.goods.qr_code.type" :label="2">方形</el-radio>
                                        </el-form-item>
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=35
                                                    :max=300
                                                    v-model="ruleForm.goods.qr_code.size"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.goods.qr_code.size)
                                                    v-model="ruleForm.goods.qr_code.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.goods.qr_code.size)
                                                    v-model="ruleForm.goods.qr_code.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                    </template>

                                    <template v-else-if="goodsComponentKey == 'desc'">
                                        <el-form-item label="文本内容">
                                            <el-input v-model="ruleForm.goods.desc.text"></el-input>
                                        </el-form-item>
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=12
                                                    :max=30
                                                    v-model="ruleForm.goods.desc.font"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.goods.desc.font)
                                                    v-model="ruleForm.goods.desc.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.goods.desc.font)
                                                    v-model="ruleForm.goods.desc.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="文本宽度">
                                            <el-slider
                                                    :min=1
                                                    :max=750
                                                    v-model="ruleForm.goods.desc.width"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="颜色">
                                            <el-color-picker
                                                    style="margin-left: 20px;"
                                                    color-format="rgb"
                                                    v-model="ruleForm.goods.desc.color"
                                                    :predefine="predefineColors">
                                            </el-color-picker>
                                        </el-form-item>
                                    </template>
                                    <template v-else-if="goodsComponentKey == 'pic'">
                                        <el-form-item label="宽度">
                                            <el-slider
                                                    :min=0
                                                    :max=750
                                                    v-model="ruleForm.goods.pic.width"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="高度">
                                            <el-slider
                                                    :min=0
                                                    :max=1334
                                                    v-model="ruleForm.goods.pic.height"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.goods.pic.height)
                                                    v-model="ruleForm.goods.pic.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.goods.pic.width)
                                                    v-model="ruleForm.goods.pic.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                    </template>
                                </div>
                            </el-card>
                            <el-button class="button-item" :loading="btnLoading" type="primary"
                                       @click="store('ruleForm')" size="small">保存
                            </el-button>
                        </div>
                    </div>
                </el-tab-pane>
                <el-tab-pane label="专题海报" name="third">
                    <div style="display: flex;">
                        <div class="mobile-box">
                            <div class="bg-box">
                                <div class="bg-pic"
                                     :style="{'background-image':'url('+ruleForm.topic.bg_pic.url+')'}"></div>
                                <span v-if="ruleForm.topic.title.is_show == 1"
                                      :style="{
                                                position: 'absolute',
                                                top: ruleForm.topic.title.top + 'px',
                                                left: ruleForm.topic.title.left + 'px',
                                                fontSize: ruleForm.topic.title.font * 2 + 'px',
                                                color: ruleForm.topic.title.color}">
                                        专题标题
                                </span>
                                <com-image
                                        v-if="ruleForm.topic.pic.is_show == 1"
                                        mode="aspectFill" width='375px' height="200px"
                                        :style="{
                                                    position: 'absolute',
                                                    width: ruleForm.topic.pic.width + 'px',
                                                    height: ruleForm.topic.pic.height + 'px',
                                                    top: ruleForm.topic.pic.top + 'px',
                                                    left: ruleForm.topic.pic.left + 'px'}"
                                        src="statics/img/mall/poster/default_topic.jpg">
                                </com-image>
                                <com-image v-if="ruleForm.topic.qr_code.is_show == 1"
                                           mode="aspectFill"
                                           :radius="ruleForm.topic.qr_code.type == 1 ? '50%' : '0%'"
                                           :style="{
                                                    position: 'absolute',
                                                    top: ruleForm.topic.qr_code.top + 'px',
                                                    left: ruleForm.topic.qr_code.left + 'px'}"
                                           :width='ruleForm.topic.qr_code.size + ""'
                                           :height='ruleForm.topic.qr_code.size + ""'
                                           src="statics/img/mall/poster/default_qr_code.png">
                                </com-image>
                                <span v-if="ruleForm.topic.look.is_show == 1"
                                      :style="{
                                                position: 'absolute',
                                                top: ruleForm.topic.look.top + 'px',
                                                left: ruleForm.topic.look.left + 'px',
                                                fontSize: ruleForm.topic.look.font * 2 + 'px',
                                                color: ruleForm.topic.look.color}">
                                        100人浏览
                                </span>
                                <span v-if="ruleForm.topic.content.is_show == 1"
                                      :style="{
                                                position: 'absolute',
                                                width: '335px',
                                                wordWrap: 'break-word',
                                                wordBreak: 'normal',
                                                position: 'absolute',
                                                top: ruleForm.topic.content.top + 'px',
                                                left: ruleForm.topic.content.left + 'px',
                                                fontSize: ruleForm.topic.content.font * 2 + 'px',
                                                color: ruleForm.topic.content.color}">
                                        专题内容|专题内容|专题内容专题内容
                                </span>
                                <span v-if="ruleForm.topic.open_desc.is_show == 1"
                                      flex="dir:top"
                                      :style="{
                                                position: 'absolute',
                                                top: ruleForm.topic.open_desc.top + 'px',
                                                left: ruleForm.topic.open_desc.left + 'px',
                                                fontSize: ruleForm.topic.open_desc.font * 2 + 'px',
                                                color: ruleForm.topic.open_desc.color}">
                                        {{ruleForm.topic.open_desc.text}}
                                        <i style="transform:rotate(90deg)"
                                           class="el-icon-d-arrow-right"
                                           flex="main:center"></i>
                                </span>
                                <div v-if="ruleForm.topic.line.is_show == 1"
                                     :style="{
                                                position: 'absolute',
                                                backgroundColor: ruleForm.topic.line.color,
                                                width: ruleForm.topic.line.width + 'px',
                                                height: ruleForm.topic.line.height + 'px',
                                                top: ruleForm.topic.line.top + 'px',
                                                left: ruleForm.topic.line.left + 'px'}">
                                </div>
                                <span v-if="ruleForm.topic.desc.is_show == 1"
                                      :style="{
                                                width: ruleForm.topic.desc.width + 'px',
                                                wordWrap: 'break-word',
                                                wordBreak: 'normal',
                                                position: 'absolute',
                                                top: ruleForm.topic.desc.top + 'px',
                                                left: ruleForm.topic.desc.left + 'px',
                                                fontSize: ruleForm.topic.desc.font * 2 + 'px',
                                                color: ruleForm.topic.desc.color}">
                                        {{ruleForm.topic.desc.text}}
                                </span>
                            </div>
                        </div>
                        <div class="form-body" flex="dir:top">
                            <div flex="dir:left" style="margin-bottom: 15px">
                                <com-attachment :multiple="false" :max="1"
                                                v-model="ruleForm.topic.bg_pic.url">
                                    <el-tooltip class="item"
                                                effect="dark"
                                                content="建议尺寸:750 * 1334"
                                                placement="top">
                                        <el-button size="mini">
                                            {{ruleForm.topic.bg_pic.url ? '更换背景图' : '添加背景图'}}
                                        </el-button>
                                    </el-tooltip>
                                </com-attachment>
                                <el-button v-if="ruleForm.topic.bg_pic.url" @click="removeBgPic()"
                                           style="margin-left: 10px;"
                                           type="danger"
                                           size="mini">
                                    删除背景
                                </el-button>
                            </div>
                            <div flex="wrap:wrap" style="width: 100%;">
                                <div v-for="(item,index) in topicComponent"
                                     @click="componentItemClick(index)"
                                     class="component-item"
                                     :class="topicComponentKey == item.key ? 'active' : ''"
                                     flex="dir:top cross:center main:center">
                                    <img :src="item.icon_url">
                                    <div>{{item.title}}</div>
                                    <img v-if="test3(index)"
                                         @click.stop="componentItemRemove(index)"
                                         class="component-item-remove"
                                         src="statics/img/mall/poster/icon_delete.png">
                                </div>
                            </div>
                            <el-card shadow="never" class="box-card" style="width: 100%;">
                                <div slot="header">
                                    <span v-if="topicComponentKey == 'title'">专题标题设置</span>
                                    <span v-if="topicComponentKey == 'pic'">专题图片设置</span>
                                    <span v-if="topicComponentKey == 'look'">阅读量设置</span>
                                    <span v-if="topicComponentKey == 'content'">专题内容设置</span>
                                    <span v-if="topicComponentKey == 'open_desc'">文章提示设置</span>
                                    <span v-if="topicComponentKey == 'line'">分割线设置</span>
                                    <span v-if="topicComponentKey == 'desc'">海报描述设置</span>
                                    <span v-if="topicComponentKey == 'qr_code'">二维码设置</span>
                                </div>
                                <div>
                                    <template v-if="topicComponentKey == 'title'">
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=12
                                                    :max=30
                                                    v-model="ruleForm.topic.title.font"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.topic.title.font)
                                                    v-model="ruleForm.topic.title.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.topic.title.font)
                                                    v-model="ruleForm.topic.title.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="颜色">
                                            <el-color-picker
                                                    style="margin-left: 20px;"
                                                    color-format="rgb"
                                                    v-model="ruleForm.topic.title.color"
                                                    :predefine="predefineColors">
                                            </el-color-picker>
                                        </el-form-item>
                                    </template>

                                    <template v-if="topicComponentKey == 'look'">
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=12
                                                    :max=30
                                                    v-model="ruleForm.topic.look.font"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.topic.look.font)
                                                    v-model="ruleForm.topic.look.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.topic.look.font)
                                                    v-model="ruleForm.topic.look.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="颜色">
                                            <el-color-picker
                                                    style="margin-left: 20px;"
                                                    color-format="rgb"
                                                    v-model="ruleForm.topic.look.color"
                                                    :predefine="predefineColors">
                                            </el-color-picker>
                                        </el-form-item>
                                    </template>

                                    <template v-if="topicComponentKey == 'content'">
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=12
                                                    :max=30
                                                    v-model="ruleForm.topic.content.font"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.topic.content.font)
                                                    v-model="ruleForm.topic.content.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.topic.content.font)
                                                    v-model="ruleForm.topic.content.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="颜色">
                                            <el-color-picker
                                                    style="margin-left: 20px;"
                                                    color-format="rgb"
                                                    v-model="ruleForm.topic.content.color"
                                                    :predefine="predefineColors">
                                            </el-color-picker>
                                        </el-form-item>
                                    </template>

                                    <template v-else-if="topicComponentKey == 'open_desc'">
                                        <el-form-item label="文本内容">
                                            <el-input v-model="ruleForm.topic.open_desc.text"></el-input>
                                        </el-form-item>
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=12
                                                    :max=30
                                                    v-model="ruleForm.topic.open_desc.font"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.topic.open_desc.font)
                                                    v-model="ruleForm.topic.open_desc.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.topic.open_desc.font)
                                                    v-model="ruleForm.topic.open_desc.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="颜色">
                                            <el-color-picker
                                                    style="margin-left: 20px;"
                                                    color-format="rgb"
                                                    v-model="ruleForm.topic.open_desc.color"
                                                    :predefine="predefineColors">
                                            </el-color-picker>
                                        </el-form-item>
                                    </template>

                                    <template v-else-if="topicComponentKey == 'qr_code'">
                                        <el-form-item label="样式">
                                            <el-radio v-model="ruleForm.topic.qr_code.type" :label="1">圆形</el-radio>
                                            <el-radio v-model="ruleForm.topic.qr_code.type" :label="2">方形</el-radio>
                                        </el-form-item>
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=30
                                                    :max=300
                                                    v-model="ruleForm.topic.qr_code.size"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.topic.qr_code.size)
                                                    v-model="ruleForm.topic.qr_code.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.topic.qr_code.size)
                                                    v-model="ruleForm.topic.qr_code.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                    </template>

                                    <template v-else-if="topicComponentKey == 'desc'">
                                        <el-form-item label="文本内容">
                                            <el-input v-model="ruleForm.topic.desc.text"></el-input>
                                        </el-form-item>
                                        <el-form-item label="大小">
                                            <el-slider
                                                    :min=12
                                                    :max=30
                                                    v-model="ruleForm.topic.desc.font"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="文本宽度">
                                            <el-slider
                                                    :min=1
                                                    :max=750
                                                    v-model="ruleForm.topic.desc.width"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.topic.desc.font)
                                                    v-model="ruleForm.topic.desc.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.topic.desc.font)
                                                    v-model="ruleForm.topic.desc.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="颜色">
                                            <el-color-picker
                                                    style="margin-left: 20px;"
                                                    color-format="rgb"
                                                    v-model="ruleForm.topic.desc.color"
                                                    :predefine="predefineColors">
                                            </el-color-picker>
                                        </el-form-item>
                                    </template>
                                    <template v-else-if="topicComponentKey == 'pic'">
                                        <el-form-item label="宽度">
                                            <el-slider
                                                    :min=0
                                                    :max=750
                                                    v-model="ruleForm.topic.pic.width"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="高度">
                                            <el-slider
                                                    :min=0
                                                    :max=1334
                                                    v-model="ruleForm.topic.pic.height"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.topic.pic.height)
                                                    v-model="ruleForm.topic.pic.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.topic.pic.width)
                                                    v-model="ruleForm.topic.pic.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                    </template>
                                    <template v-else-if="topicComponentKey == 'line'">
                                        <el-form-item label="宽度">
                                            <el-slider
                                                    :min=1
                                                    :max=750
                                                    v-model="ruleForm.topic.line.width"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="高度">
                                            <el-slider
                                                    :min=1
                                                    :max=30
                                                    v-model="ruleForm.topic.line.height"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="上间距">
                                            <el-slider
                                                    :min=0
                                                    :max=1334-(ruleForm.topic.line.height)
                                                    v-model="ruleForm.topic.line.top"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="左间距">
                                            <el-slider
                                                    :min=0
                                                    :max=750-(ruleForm.topic.line.width)
                                                    v-model="ruleForm.topic.line.left"
                                                    show-input>
                                            </el-slider>
                                        </el-form-item>
                                        <el-form-item label="颜色">
                                            <el-color-picker
                                                    style="margin-left: 20px;"
                                                    color-format="rgb"
                                                    v-model="ruleForm.topic.line.color"
                                                    :predefine="predefineColors">
                                            </el-color-picker>
                                        </el-form-item>
                                    </template>
                                </div>
                            </el-card>
                            <el-button class="button-item" :loading="btnLoading" type="primary"
                                       @click="store('ruleForm')" size="small">保存
                            </el-button>
                        </div>
                    </div>
                </el-tab-pane>
            </el-tabs>
        </el-form>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                ruleForm: null,
                shareComponent: [
                    {
                        key: 'head',
                        icon_url: 'statics/img/mall/poster/icon_head.png',
                        title: '头像',
                        is_active: true
                    },
                    {
                        key: 'name',
                        icon_url: 'statics/img/mall/poster/icon_name.png',
                        title: '昵称',
                        is_active: true
                    },
                    {
                        key: 'qr_code',
                        icon_url: 'statics/img/mall/poster/icon_qr_code.png',
                        title: '二维码',
                        is_active: true
                    },
                ],
                shareComponentKey: 'head',
                goodsComponent: [
                    {
                        key: 'head',
                        icon_url: 'statics/img/mall/poster/icon_head.png',
                        title: '头像',
                        is_active: true
                    },
                    {
                        key: 'nickname',
                        icon_url: 'statics/img/mall/poster/icon_name.png',
                        title: '昵称',
                        is_active: true
                    },
                    {
                        key: 'pic',
                        icon_url: 'statics/img/mall/poster/icon_pic.png',
                        title: '商品图片',
                        is_active: true
                    },
                    {
                        key: 'name',
                        icon_url: 'statics/img/mall/poster/icon_name.png',
                        title: '商品名称',
                        is_active: true
                    },
                    {
                        key: 'price',
                        icon_url: 'statics/img/mall/poster/icon_price.png',
                        title: '商品价格',
                        is_active: true
                    },
                    {
                        key: 'desc',
                        icon_url: 'statics/img/mall/poster/icon_desc.png',
                        title: '海报描述',
                        is_active: true
                    },
                    {
                        key: 'qr_code',
                        icon_url: 'statics/img/mall/poster/icon_qr_code.png',
                        title: '二维码',
                        is_active: true
                    },
                ],
                goodsComponentKey: 'pic',
                topicComponent: [
                    {
                        key: 'title',
                        icon_url: 'statics/img/mall/poster/icon_name.png',
                        title: '专题标题',
                        is_active: true
                    },
                    {
                        key: 'pic',
                        icon_url: 'statics/img/mall/poster/icon_pic.png',
                        title: '专题图片',
                        is_active: true
                    },
                    {
                        key: 'look',
                        icon_url: 'statics/img/mall/poster/icon_look.png',
                        title: '阅读量',
                        is_active: true
                    },
                    {
                        key: 'content',
                        icon_url: 'statics/img/mall/poster/icon_content.png',
                        title: '专题内容',
                        is_active: true
                    },
                    {
                        key: 'open_desc',
                        icon_url: 'statics/img/mall/poster/icon_point.png',
                        title: '文章提示',
                        is_active: true
                    },
                    {
                        key: 'line',
                        icon_url: 'statics/img/mall/poster/icon_line.png',
                        title: '分割线',
                        is_active: true
                    },
                    {
                        key: 'desc',
                        icon_url: 'statics/img/mall/poster/icon_desc.png',
                        title: '海报描述',
                        is_active: true
                    },
                    {
                        key: 'qr_code',
                        icon_url: 'statics/img/mall/poster/icon_qr_code.png',
                        title: '二维码',
                        is_active: true
                    },
                ],
                topicComponentKey: 'title',
                rules: {},
                predefineColors: [
                    '#000',
                    '#fff',
                    '#888',
                    '#ff4544'
                ],
                btnLoading: false,
                cardLoading: false,
                activeName: 'first',
                showLeftSpacing:true,
            };
        },
        computed: {
            // 控制显示的内容

            test() {
                return function (index) {
                    var isShow = this.ruleForm.share[this.shareComponent[index].key].is_show;
                    return isShow == 1 ? true : false;
                }
            },
            test2() {
                return function (index) {
                    var isShow = this.ruleForm.goods[this.goodsComponent[index].key].is_show;
                    return isShow == 1 ? true : false;
                }
            },
            test3() {
                return function (index) {
                    var isShow = this.ruleForm.topic[this.topicComponent[index].key].is_show;
                    return isShow == 1 ? true : false;
                }
            },
        },
        methods: {
            spacing(val) {
                console.log(val);
                if (val == 1) {
                    this.showLeftSpacing = true;
                } else {
                    this.showLeftSpacing = false;
                }
            },
            store(formName) {
                this.$refs[formName].validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'mall/poster/setting'
                            },
                            method: 'post',
                            data: {
                                form: self.ruleForm,
                            }
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code == 0) {
                                self.$message.success(e.data.msg);
                            } else {
                                self.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            self.$message.error(e.data.msg);
                            self.btnLoading = false;
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            getDetail() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: 'mall/poster/setting',
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        self.ruleForm = e.data.data.detail;
                        if (self.ruleForm.share.name.center == 1) {
                            this.showLeftSpacing = true;
                        } else {
                            this.showLeftSpacing = false;
                        }
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            transformData() {
            },
            handleClick(tab, event) {
                console.log(tab, event);
            },
            // 移除背景图片
            removeBgPic() {
                if (this.activeName == 'second') {
                    this.ruleForm.goods.bg_pic.url = '';
                }

                if (this.activeName == 'third') {
                    this.ruleForm.topic.bg_pic.url = '';
                }
            },
            // 添加组件
            componentItemClick(index) {
                if (this.activeName == 'first') {
                    this.shareComponent[index].is_active = true;
                    this.ruleForm.share[this.shareComponent[index].key].is_show = '1';
                    this.shareComponentKey = this.shareComponent[index].key;
                }
                if (this.activeName == 'second') {
                    this.goodsComponent[index].is_active = true;
                    this.ruleForm.goods[this.goodsComponent[index].key].is_show = '1';
                    this.goodsComponentKey = this.goodsComponent[index].key;
                }

                if (this.activeName == 'third') {
                    this.topicComponent[index].is_active = true;
                    this.ruleForm.topic[this.topicComponent[index].key].is_show = '1';
                    this.topicComponentKey = this.topicComponent[index].key;
                }
            },
            // 移除组件
            componentItemRemove(index) {
                if (this.activeName == 'first') {
                    this.shareComponent[index].is_active = false;
                    this.ruleForm.share[this.shareComponent[index].key].is_show = '0';
                    this.shareComponentKey = '';
                }
                if (this.activeName == 'second') {
                    this.goodsComponent[index].is_active = false;
                    this.ruleForm.goods[this.goodsComponent[index].key].is_show = '0';
                    this.goodsComponentKey = '';
                }

                if (this.activeName == 'third') {
                    this.topicComponent[index].is_active = false;
                    this.ruleForm.topic[this.topicComponent[index].key].is_show = '0';
                    this.topicComponentKey = '';
                }
            }
        },
        mounted: function () {
            this.getDetail();
        }
    });
</script>
