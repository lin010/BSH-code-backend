(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["plugins-repertory-cloud-cargo-list"],{"0002":function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={name:"jxLoading",props:{text:{type:String,default:"正在加载..."},visible:{type:Boolean,default:!1}}};e.default=n},"02ea":function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={name:"jxNomore",props:{visible:{type:Boolean,default:!1},bgcolor:{type:String,default:"#fafafa"},isDot:{type:Boolean,default:!1},text:{type:String,default:"没有更多了"}},data:function(){return{dotText:"●"}}};e.default=n},"0563":function(t,e,i){"use strict";i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var n={comStatusBar:i("6e1b").default,comIcons:i("f934").default},a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"uni-navbar"},[i("v-uni-view",{staticClass:"uni-navbar__content",class:{"uni-navbar--fixed":t.fixed,"uni-navbar--shadow":t.shadow,"uni-navbar--border":t.border},style:{"background-color":t.backgroundColor}},[t.statusBar?i("com-status-bar"):t._e(),i("v-uni-view",{staticClass:"uni-navbar__header uni-navbar__content_view",style:{color:t.color,backgroundColor:t.backgroundColor}},[i("v-uni-view",{staticClass:"uni-navbar__header-btns uni-navbar__header-btns-left uni-navbar__content_view",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.onClickLeft.apply(void 0,arguments)}}},[t.leftIcon.length?i("v-uni-view",{staticClass:"uni-navbar__content_view"},[i("com-icons",{attrs:{color:t.color,type:t.leftIcon,size:"24"}})],1):t._e(),t.leftText.length?i("v-uni-view",{staticClass:"uni-navbar-btn-text uni-navbar__content_view",class:{"uni-navbar-btn-icon-left":!t.leftIcon.length}},[i("v-uni-text",{style:{color:t.color,fontSize:"14px"}},[t._v(t._s(t.leftText))])],1):t._e(),t._t("left")],2),i("v-uni-view",{staticClass:"uni-navbar__header-container uni-navbar__content_view"},[t.title.length?i("v-uni-view",{staticClass:"uni-navbar__header-container-inner uni-navbar__content_view"},[i("v-uni-text",{staticClass:"uni-nav-bar-text",style:{color:t.color}},[t._v(t._s(t.title))])],1):t._e(),t._t("default")],2),i("v-uni-view",{staticClass:"uni-navbar__header-btns uni-navbar__content_view",class:t.title.length?"uni-navbar__header-btns-right":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.onClickRight.apply(void 0,arguments)}}},[t.rightIcon.length?i("v-uni-view",{staticClass:"uni-navbar__content_view"},[i("com-icons",{attrs:{color:t.color,type:t.rightIcon,size:"24"}})],1):t._e(),t.rightText.length&&!t.rightIcon.length?i("v-uni-view",{staticClass:"uni-navbar-btn-text uni-navbar__content_view"},[i("v-uni-text",{staticClass:"uni-nav-bar-right-text"},[t._v(t._s(t.rightText))])],1):t._e(),t._t("right")],2)],1)],1),t.fixed?i("v-uni-view",{staticClass:"uni-navbar__placeholder"},[t.statusBar?i("com-status-bar"):t._e(),i("v-uni-view",{staticClass:"uni-navbar__placeholder-view"})],1):t._e()],1)},o=[]},"05b8":function(t,e,i){"use strict";var n=i("be74"),a=i.n(n);a.a},"097b":function(t,e,i){"use strict";i.r(e);var n=i("02ea"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},1924:function(t,e,i){"use strict";i("a9e3"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={name:"jxTabs",props:{tabs:{type:Array,default:function(){return[]}},height:{type:Number,default:80},padding:{type:Number,default:30},bgColor:{type:String,default:"#FFFFFF"},isFixed:{type:Boolean,default:!1},top:{type:Number,default:44},unlined:{type:Boolean,default:!1},currentTab:{type:Number,default:0},sliderWidth:{type:Number,default:68},sliderHeight:{type:Number,default:6},sliderBgColor:{type:String,default:"#bc0100"},sliderRadius:{type:String,default:"50rpx"},bottom:{type:String,default:"0"},itemWidth:{type:String,default:"25%"},color:{type:String,default:"#666"},selectedColor:{type:String,default:"#bc0100"},size:{type:Number,default:28},bold:{type:Boolean,default:!1}},watch:{currentTab:function(){this.checkCor()}},created:function(){var t=this;setTimeout((function(){uni.getSystemInfo({success:function(e){t.winWidth=e.windowWidth,t.checkCor()}})}),50)},data:function(){return{jxWidth:750,winWidth:0,scrollLeft:0}},methods:{checkCor:function(){var t=this.tabs.length,e=this.winWidth/this.jxWidth*this.padding,i=this.winWidth-2*e,n=(i/t-this.winWidth/this.jxWidth*this.sliderWidth)/2+e,a=n;this.currentTab>0&&(a+=i/t*this.currentTab),this.scrollLeft=a},swichTabs:function(t){var e=this.tabs[t];if(!e||!e.disabled)return this.currentTab!=t&&void this.$emit("change",{index:Number(t)})}}};e.default=n},3673:function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return t.visible?i("v-uni-view",{staticClass:"jx-loading-init"},[i("v-uni-view",{staticClass:"jx-loading-center"}),i("v-uni-view",{staticClass:"jx-loadmore-tips"},[t._v(t._s(t.text))])],1):t._e()},o=[]},"36f7":function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=uni.getSystemInfoSync().statusBarHeight+"px",a={name:"ComStatusBar",data:function(){return{statusBarHeight:n}}};e.default=a},"41a0":function(t,e,i){"use strict";var n=i("5344"),a=i.n(n);a.a},"460d":function(t,e,i){"use strict";var n=i("8652"),a=i.n(n);a.a},"4acb":function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'.jx-loadmore-none[data-v-332b08f2]{width:50%;margin:1.5em auto;line-height:1.5em;font-size:%?24?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center}.jx-nomore[data-v-332b08f2]{width:100%;height:100%;position:relative;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;margin-top:%?10?%;padding-bottom:%?6?%}.jx-nomore[data-v-332b08f2]::before{content:" ";position:absolute;border-bottom:%?1?% solid #e5e5e5;-webkit-transform:scaleY(.5);transform:scaleY(.5);width:100%;top:%?18?%;left:0}.jx-nomore-text[data-v-332b08f2]{color:#999;font-size:%?24?%;text-align:center;padding:0 %?18?%;height:%?36?%;line-height:%?36?%;position:relative;z-index:1}.jx-nomore-dot[data-v-332b08f2]{position:relative;text-align:center;-webkit-display:flex;display:-webkit-box;display:flex;-webkit-justify-content:center;-webkit-box-pack:center;justify-content:center;margin-top:%?10?%;padding-bottom:%?6?%}.jx-nomore-dot[data-v-332b08f2]::before{content:"";position:absolute;border-bottom:%?1?% solid #e5e5e5;-webkit-transform:scaleY(.5);transform:scaleY(.5);width:%?360?%;top:%?18?%}.jx-dot-text[data-v-332b08f2]{position:relative;color:#e5e5e5;font-size:10px;text-align:center;width:%?50?%;height:%?36?%;line-height:%?36?%;-webkit-transform:scale(.8);transform:scale(.8);-webkit-transform-origin:center center;transform-origin:center center;z-index:1}',""]),t.exports=e},5344:function(t,e,i){var n=i("b4c7");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("8c4c822a",n,!0,{sourceMap:!1,shadowMode:!1})},5682:function(t,e,i){"use strict";var n=i("8ba6"),a=i.n(n);a.a},"642a":function(t,e,i){"use strict";i.r(e);var n=i("ed76"),a=i("6606");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("5682");var r,s=i("f0c5"),l=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"74b49a57",null,!1,n["a"],r);e["default"]=l.exports},"644b":function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return t.visible?i("v-uni-view",{staticClass:"jx-nomore-class jx-loadmore-none"},[i("v-uni-view",{class:[t.isDot?"jx-nomore-dot":"jx-nomore"]},[i("v-uni-view",{class:[t.isDot?"jx-dot-text":"jx-nomore-text"],style:{background:t.bgcolor}},[t._v(t._s(t.isDot?t.dotText:t.text))])],1)],1):t._e()},o=[]},6606:function(t,e,i){"use strict";i.r(e);var n=i("7b1d"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},"6dac":function(t,e,i){"use strict";var n=i("9db0"),a=i.n(n);a.a},"6e1b":function(t,e,i){"use strict";i.r(e);var n=i("d1d3"),a=i("f82f");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("6dac");var r,s=i("f0c5"),l=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"31d08a7f",null,!1,n["a"],r);e["default"]=l.exports},7869:function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"jx-tabs-view",class:[t.isFixed?"jx-tabs-fixed":"jx-tabs-relative",t.unlined?"jx-unlined":""],style:{height:t.height+"rpx",padding:"0 "+t.padding+"rpx",background:t.bgColor,top:t.isFixed?t.top+"px":"auto"}},[t._l(t.tabs,(function(e,n){return i("v-uni-view",{key:n,staticClass:"jx-tabs-item",style:{width:t.itemWidth},on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.swichTabs(n)}}},[i("v-uni-view",{staticClass:"jx-tabs-title",class:{"jx-tabs-active":t.currentTab==n,"jx-tabs-disabled":e.disabled},style:{color:t.currentTab==n?t.selectedColor:t.color,fontSize:t.size+"rpx",lineHeight:t.size+"rpx",fontWeight:t.bold&&t.currentTab==n?"bold":"normal"}},[t._v(t._s(e.name))])],1)})),i("v-uni-view",{staticClass:"jx-tabs-slider",style:{transform:"translateX("+t.scrollLeft+"px)",width:t.sliderWidth+"rpx",height:t.sliderHeight+"rpx",borderRadius:t.sliderRadius,bottom:t.bottom,background:t.sliderBgColor,marginBottom:"50%"==t.bottom?"-"+t.sliderHeight/2+"rpx":0}})],2)},o=[]},"7b1d":function(t,e,i){"use strict";i("99af"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={data:function(){return{loading:!1,status:0,list:[],page:1,is_no_more:!1,textColor:"#bc0100",bg_url:"",button:[]}},computed:{tbas:function(){var t=[{name:"待发货",type:"0"},{name:"待收货",type:"1"},{name:"已完成",type:"2"}];return t}},onLoad:function(t){this.textColor=this.globalSet("textCol"),this.bg_url=this.globalSet("imgUrl"),this.button=this.globalSet("btnCol"),this.getList()},methods:{back:function(){this.navBack()},statusChange:function(t){this.status=t.index,this.list=[],this.page=1,this.is_no_more=!1,this.getList()},confirm:function(t){var e=this;uni.showModal({title:"提示",content:"确认收货",success:function(i){var n=this;i.confirm&&e.$http.request({url:e.$api.plugin.stock.order_confirm,data:{order_id:t}}).then((function(t){n.loading=!1,0==t.code?(e.list=[],e.page=1,e.is_no_more=!1,e.getList()):uni.showToast({title:t.msg})}))}})},getList:function(){var t=this;if(this.loading=!0,this.is_no_more)return uni.showToast({title:"没有更多数据"}),void(this.loading=!1);this.$http.request({url:this.$api.plugin.stock.agent_order_list,data:{page:this.page,type:this.status}}).then((function(e){t.loading=!1,0==e.code?(1==t.page?t.list=e.data.list:t.list.concat(e.data.list),t.page<e.data.pagination.page_count?t.page++:t.is_no_more=!0):uni.showToast({title:e.msg})}))}}};e.default=n},"7e82":function(t,e,i){"use strict";i.r(e);var n=i("e991"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},8652:function(t,e,i){var n=i("fbdf");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("15a9f5a8",n,!0,{sourceMap:!1,shadowMode:!1})},8764:function(t,e,i){"use strict";i.r(e);var n=i("1924"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},"8ba6":function(t,e,i){var n=i("d7ae");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("88d831ce",n,!0,{sourceMap:!1,shadowMode:!1})},"95db":function(t,e,i){var n=i("4acb");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("0f41b927",n,!0,{sourceMap:!1,shadowMode:!1})},"98b4":function(t,e,i){"use strict";i.r(e);var n=i("3673"),a=i("ddf4");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("05b8");var r,s=i("f0c5"),l=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"85db7258",null,!1,n["a"],r);e["default"]=l.exports},"9db0":function(t,e,i){var n=i("af90");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("0b5b5a3a",n,!0,{sourceMap:!1,shadowMode:!1})},af90:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 商城主题色 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.uni-status-bar[data-v-31d08a7f]{width:%?750?%;height:20px}',""]),t.exports=e},b4c7:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'.jx-tabs-view[data-v-e6cd55f4]{width:100%;-webkit-box-sizing:border-box;box-sizing:border-box;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;z-index:9999}.jx-tabs-relative[data-v-e6cd55f4]{position:relative}.jx-tabs-fixed[data-v-e6cd55f4]{position:fixed;left:0}.jx-tabs-fixed[data-v-e6cd55f4]::before,\n.jx-tabs-relative[data-v-e6cd55f4]::before{content:"";position:absolute;border-bottom:%?1?% solid #eaeef1;-webkit-transform:scaleY(.5);transform:scaleY(.5);bottom:0;right:0;left:0}.jx-unlined[data-v-e6cd55f4]::before{border-bottom:0!important}.jx-tabs-item[data-v-e6cd55f4]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center}.jx-tabs-disabled[data-v-e6cd55f4]{opacity:.6}.jx-tabs-title[data-v-e6cd55f4]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;position:relative;z-index:2}.jx-tabs-active[data-v-e6cd55f4]{-webkit-transition:all .15s ease-in-out;transition:all .15s ease-in-out}.jx-tabs-slider[data-v-e6cd55f4]{position:absolute;left:0;-webkit-transition:all .15s ease-in-out;transition:all .15s ease-in-out;z-index:0;-webkit-transform:translateZ(0);transform:translateZ(0)}',""]),t.exports=e},ba9f:function(t,e,i){"use strict";i.r(e);var n=i("7869"),a=i("8764");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("41a0");var r,s=i("f0c5"),l=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"e6cd55f4",null,!1,n["a"],r);e["default"]=l.exports},be74:function(t,e,i){var n=i("dfeb");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("5227196c",n,!0,{sourceMap:!1,shadowMode:!1})},c332:function(t,e,i){"use strict";var n=i("95db"),a=i.n(n);a.a},c410:function(t,e,i){"use strict";i.r(e);var n=i("0563"),a=i("7e82");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("460d");var r,s=i("f0c5"),l=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"431c4463",null,!1,n["a"],r);e["default"]=l.exports},ca5f:function(t,e,i){"use strict";i.r(e);var n=i("644b"),a=i("097b");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("c332");var r,s=i("f0c5"),l=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"332b08f2",null,!1,n["a"],r);e["default"]=l.exports},d1d3:function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"uni-status-bar",style:{height:t.statusBarHeight}},[t._t("default")],2)},o=[]},d7ae:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 商城主题色 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.app[data-v-74b49a57]{height:100%;background-color:#f2f2f2}.list[data-v-74b49a57]{margin:%?20?% 0;background-color:#fff}.list .item[data-v-74b49a57]{padding:%?24?% %?30?%;border-bottom:%?1?% solid #f2f2f2;line-height:%?38?%}.list .item[data-v-74b49a57]:last-child{border-bottom:0}.list .item .goods-cover[data-v-74b49a57]{width:%?168?%;height:%?168?%;-webkit-border-radius:%?5?%;border-radius:%?5?%;margin-right:%?18?%}.list .item .item-goods-info[data-v-74b49a57]{-webkit-box-flex:1;-webkit-flex-grow:1;flex-grow:1}.list .item .item-goods-info .goods-name[data-v-74b49a57]{color:#333;max-width:%?450?%;font-size:%?36?%}.list .item .item-goods-info .goods-nums[data-v-74b49a57]{color:#bc0100;font-size:%?18?%;line-height:%?18?%;margin:%?20?% 0}.list .item .item-goods-info .goods-nums .goods-num[data-v-74b49a57]{padding:%?7?% %?9?%;-webkit-border-radius:%?5?%;border-radius:%?5?%;border:%?1?% solid #bc0100;margin-right:%?18?%}.list .item .item-goods-info .goods-cost .text[data-v-74b49a57]{color:#666;font-size:%?18?%}.list .item .item-goods-info .goods-cost .price[data-v-74b49a57]{color:#bc0100;font-size:%?24?%;-webkit-box-align:baseline;-webkit-align-items:baseline;align-items:baseline;-webkit-box-flex:1;-webkit-flex:1;flex:1}.list .item .item-goods-info .goods-cost .dot[data-v-74b49a57]{font-size:%?18?%;line-height:%?18?%}.list .item .item-goods-info .goods-cost .btn[data-v-74b49a57]{width:%?133?%;height:%?40?%;-webkit-border-radius:%?20?%;border-radius:%?20?%;font-size:%?22?%}',""]),t.exports=e},ddf4:function(t,e,i){"use strict";i.r(e);var n=i("0002"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},dfeb:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,".jx-loading-init[data-v-85db7258]{min-width:%?200?%;min-height:%?200?%;max-width:%?500?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;position:fixed;top:50%;left:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);z-index:9999;font-size:%?26?%;color:#fff;background:rgba(0,0,0,.7);-webkit-border-radius:%?10?%;border-radius:%?10?%}.jx-loading-center[data-v-85db7258]{width:%?50?%;height:%?50?%;border:3px solid #fff;-webkit-border-radius:50%;border-radius:50%;margin:0 6px;display:inline-block;vertical-align:middle;-webkit-clip-path:polygon(0 0,100% 0,100% 40%,0 40%);clip-path:polygon(0 0,100% 0,100% 40%,0 40%);-webkit-animation:rotate-data-v-85db7258 1s linear infinite;animation:rotate-data-v-85db7258 1s linear infinite;margin-bottom:%?36?%}.jx-loadmore-tips[data-v-85db7258]{text-align:center;padding:0 %?20?%;-webkit-box-sizing:border-box;box-sizing:border-box}@-webkit-keyframes rotate-data-v-85db7258{from{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}@keyframes rotate-data-v-85db7258{from{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}",""]),t.exports=e},e991:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={name:"ComNavBar",props:{title:{type:String,default:""},leftText:{type:String,default:""},rightText:{type:String,default:""},leftIcon:{type:String,default:""},rightIcon:{type:String,default:""},fixed:{type:[Boolean,String],default:!1},color:{type:String,default:"#000000"},backgroundColor:{type:String,default:"#FFFFFF"},statusBar:{type:[Boolean,String],default:!1},shadow:{type:[String,Boolean],default:!1},border:{type:[String,Boolean],default:!0}},mounted:function(){uni.report&&""!==this.title&&uni.report("title",this.title)},methods:{onClickLeft:function(){var t=getCurrentPages();1==t.length?uni.redirectTo({url:"/pages/index/index"}):this.$emit("clickLeft")},onClickRight:function(){this.$emit("clickRight")}}};e.default=n},ed76:function(t,e,i){"use strict";i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var n={comNavBar:i("c410").default,comTabs:i("ba9f").default,mainNomore:i("ca5f").default,mainLoading:i("98b4").default},a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"app"},[i("com-nav-bar",{attrs:{"left-icon":"back",title:"发货明细","status-bar":!0,"background-color":"#FFFFFF",border:!0,color:"#000000"},on:{clickLeft:function(e){arguments[0]=e=t.$handleEvent(e),t.back.apply(void 0,arguments)}}}),i("com-tabs",{attrs:{tabs:t.tbas,"current-tab":t.status,height:100,color:"#333333","selected-color":t.textColor,padding:0,bottom:"20rpx","slider-height":4,"slider-width":110,"slider-bg-color":t.textColor,itemWidth:"33.33%"},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.statusChange.apply(void 0,arguments)}}}),i("v-uni-view",{staticClass:"list"},[t.list&&t.list.length>0?i("v-uni-view",{staticClass:"items"},t._l(t.list,(function(e,n){return i("v-uni-view",{key:n,staticClass:"item flex flex-y-center"},[i("v-uni-image",{staticClass:"goods-cover",attrs:{src:e.cover_pic}}),i("v-uni-view",{staticClass:"item-goods-info"},[i("v-uni-view",{staticClass:"goods-name over1"},[t._v(t._s(e.goods_name))]),i("v-uni-view",{staticClass:"goods-nums flex"},[i("v-uni-view",{staticClass:"goods-num",style:{color:t.textColor,border:"1px solid"+t.textColor}},[t._v("数量:"+t._s(e.num)+" 件")])],1),i("v-uni-view",{staticClass:"goods-cost flex-x-between"},[i("v-uni-view",[i("v-uni-view",{staticClass:"flex",staticStyle:{"margin-right":"20rpx"}},[i("v-uni-view",{staticClass:"text",staticStyle:{width:"88rpx"}},[t._v("收货人:")]),i("v-uni-view",{staticClass:"price flex",style:{color:t.textColor}},[i("span",[t._v(t._s(e.name)+" "+t._s(e.mobile))])])],1),i("v-uni-view",{staticClass:"flex",staticStyle:{"margin-right":"20rpx"}},[i("v-uni-view",{staticClass:"text",staticStyle:{width:"70rpx"}},[t._v("地址:")]),i("v-uni-view",{staticClass:"price flex",style:{color:t.textColor}},[i("span",[t._v(t._s(e.address))])])],1)],1),1==e.status?i("v-uni-view",{staticClass:"btn flex-x-center flex-y-center jx-primary-btn",style:{background:t.textColor}},[i("v-uni-view",{on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.confirm(e.id)}}},[t._v("确认收货")])],1):t._e()],1)],1)],1)})),1):i("v-uni-view",{staticClass:"items"},[i("main-nomore",{attrs:{text:"暂无记录",visible:!0,bgcolor:"transparent"}})],1)],1),i("main-loading",{attrs:{visible:t.loading}})],1)},o=[]},f82f:function(t,e,i){"use strict";i.r(e);var n=i("36f7"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},fbdf:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 商城主题色 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.uni-nav-bar-text[data-v-431c4463]{font-size:%?32?%}.uni-nav-bar-right-text[data-v-431c4463]{font-size:%?28?%}.uni-navbar[data-v-431c4463]{width:%?750?%}.uni-navbar__content[data-v-431c4463]{position:relative;width:%?750?%;background-color:#fff;overflow:hidden}.uni-navbar__content_view[data-v-431c4463]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row}.uni-navbar__header[data-v-431c4463]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;width:%?750?%;height:44px;line-height:44px;font-size:16px}.uni-navbar__header-btns[data-v-431c4463]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-flex-wrap:nowrap;flex-wrap:nowrap;width:%?120?%;padding:0 6px;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.uni-navbar__header-btns-left[data-v-431c4463]{display:-webkit-box;display:-webkit-flex;display:flex;width:%?150?%;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start}.uni-navbar__header-btns-right[data-v-431c4463]{display:-webkit-box;display:-webkit-flex;display:flex;width:%?150?%;padding-right:%?30?%;-webkit-box-pack:end;-webkit-justify-content:flex-end;justify-content:flex-end}.uni-navbar__header-container[data-v-431c4463]{-webkit-box-flex:1;-webkit-flex:1;flex:1}.uni-navbar__header-container-inner[data-v-431c4463]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-flex:1;-webkit-flex:1;flex:1;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;font-size:%?28?%}.uni-navbar__placeholder-view[data-v-431c4463]{height:44px}.uni-navbar--fixed[data-v-431c4463]{position:fixed;z-index:998}.uni-navbar--shadow[data-v-431c4463]{-webkit-box-shadow:0 1px 6px #ccc;box-shadow:0 1px 6px #ccc}.uni-navbar--border[data-v-431c4463]{border-bottom-width:%?1?%;border-bottom-style:solid;border-bottom-color:#f3f3f3}',""]),t.exports=e}}]);