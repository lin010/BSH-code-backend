(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["plugins-extensions-dealer-team"],{"0002":function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={name:"jxLoading",props:{text:{type:String,default:"正在加载..."},visible:{type:Boolean,default:!1}}};e.default=i},"02ea":function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={name:"jxNomore",props:{visible:{type:Boolean,default:!1},bgcolor:{type:String,default:"#fafafa"},isDot:{type:Boolean,default:!1},text:{type:String,default:"没有更多了"}},data:function(){return{dotText:"●"}}};e.default=i},"0563":function(t,e,n){"use strict";n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var i={comStatusBar:n("6e1b").default,comIcons:n("f934").default},a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"uni-navbar"},[n("v-uni-view",{staticClass:"uni-navbar__content",class:{"uni-navbar--fixed":t.fixed,"uni-navbar--shadow":t.shadow,"uni-navbar--border":t.border},style:{"background-color":t.backgroundColor}},[t.statusBar?n("com-status-bar"):t._e(),n("v-uni-view",{staticClass:"uni-navbar__header uni-navbar__content_view",style:{color:t.color,backgroundColor:t.backgroundColor}},[n("v-uni-view",{staticClass:"uni-navbar__header-btns uni-navbar__header-btns-left uni-navbar__content_view",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.onClickLeft.apply(void 0,arguments)}}},[t.leftIcon.length?n("v-uni-view",{staticClass:"uni-navbar__content_view"},[n("com-icons",{attrs:{color:t.color,type:t.leftIcon,size:"24"}})],1):t._e(),t.leftText.length?n("v-uni-view",{staticClass:"uni-navbar-btn-text uni-navbar__content_view",class:{"uni-navbar-btn-icon-left":!t.leftIcon.length}},[n("v-uni-text",{style:{color:t.color,fontSize:"14px"}},[t._v(t._s(t.leftText))])],1):t._e(),t._t("left")],2),n("v-uni-view",{staticClass:"uni-navbar__header-container uni-navbar__content_view"},[t.title.length?n("v-uni-view",{staticClass:"uni-navbar__header-container-inner uni-navbar__content_view"},[n("v-uni-text",{staticClass:"uni-nav-bar-text",style:{color:t.color}},[t._v(t._s(t.title))])],1):t._e(),t._t("default")],2),n("v-uni-view",{staticClass:"uni-navbar__header-btns uni-navbar__content_view",class:t.title.length?"uni-navbar__header-btns-right":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.onClickRight.apply(void 0,arguments)}}},[t.rightIcon.length?n("v-uni-view",{staticClass:"uni-navbar__content_view"},[n("com-icons",{attrs:{color:t.color,type:t.rightIcon,size:"24"}})],1):t._e(),t.rightText.length&&!t.rightIcon.length?n("v-uni-view",{staticClass:"uni-navbar-btn-text uni-navbar__content_view"},[n("v-uni-text",{staticClass:"uni-nav-bar-right-text"},[t._v(t._s(t.rightText))])],1):t._e(),t._t("right")],2)],1)],1),t.fixed?n("v-uni-view",{staticClass:"uni-navbar__placeholder"},[t.statusBar?n("com-status-bar"):t._e(),n("v-uni-view",{staticClass:"uni-navbar__placeholder-view"})],1):t._e()],1)},o=[]},"05b8":function(t,e,n){"use strict";var i=n("be74"),a=n.n(i);a.a},"097b":function(t,e,n){"use strict";n.r(e);var i=n("02ea"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},"15dc":function(t,e,n){"use strict";n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var i={comNavBar:n("c410").default,mainNomore:n("ca5f").default,mainLoading:n("98b4").default},a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"app"},[n("com-nav-bar",{attrs:{"left-icon":"back",title:"我的团队","status-bar":!0,"background-color":"#BC0100",border:!1,color:"#ffffff"},on:{clickLeft:function(e){arguments[0]=e=t.$handleEvent(e),t.back.apply(void 0,arguments)}}}),n("v-uni-view",{staticClass:"container"},[n("v-uni-view",{staticClass:"content"},[n("v-uni-view",{staticClass:"content-head"},[n("v-uni-image",{staticClass:"jx-bg",attrs:{src:"https://pic.downk.cc/item/5ecbac9ac2a9a83be58f7458.png",mode:"scaleToFill"}}),n("v-uni-view",{staticClass:"super"},[n("v-uni-image",{staticClass:"acatar",attrs:{src:"https://pic.downk.cc/item/5ed21429c2a9a83be556f8fe.png",mode:"aspectFill"}}),n("v-uni-view",{staticClass:"userinfo"},[n("v-uni-view",{staticClass:"username"},[t._v("推荐人: "+t._s("社交新零售系统-七件事"))]),n("v-uni-view",{staticClass:"desc"},[n("span",{staticClass:"iconfont icon-huiyuan1"}),t._v(t._s("省级代理"))]),n("v-uni-view",{staticClass:"tel"},[n("span",{staticClass:"iconfont icon-dianhua3"}),t._v(t._s("15015756796"))])],1)],1)],1),n("v-uni-view",{staticClass:"content-bottom"},[n("v-uni-view",{staticClass:"card"},[n("v-uni-view",{staticClass:"title"},[t._v("团队会员")]),n("v-uni-view",{staticClass:"bill"},[n("v-uni-view",{staticClass:"icon-text flex-column-x-center"},[n("v-uni-view",{staticClass:"sum"},[t._v(t._s("1776")+"人")]),n("v-uni-view",{staticClass:"name"},[t._v("省级代理")])],1),n("v-uni-view",{staticClass:"icon-text flex-column-x-center"},[n("v-uni-view",{staticClass:"sum"},[t._v(t._s("1843")+"人")]),n("v-uni-view",{staticClass:"name"},[t._v("市级代理")])],1),n("v-uni-view",{staticClass:"icon-text flex-column-x-center"},[n("v-uni-view",{staticClass:"sum"},[t._v(t._s("1384")+"人")]),n("v-uni-view",{staticClass:"name"},[t._v("县级代理")])],1),n("v-uni-view",{staticClass:"icon-text flex-column-x-center"},[n("v-uni-view",{staticClass:"sum"},[t._v(t._s("1843")+"人")]),n("v-uni-view",{staticClass:"name"},[t._v("门店代理")])],1)],1)],1),t.team_commission?n("v-uni-view",{staticClass:"order"},[n("v-uni-view",{staticClass:"tabs"},t._l(t.tabs,(function(e,i){return n("v-uni-view",{key:i,staticClass:"tab",class:t.tabType==e.type?"active":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.switchTab(i)}}},[n("span",{staticClass:"name"},[t._v(t._s(e.name))])])})),1),n("div",{staticClass:"status"},[n("v-uni-view",{staticClass:"name",class:1==t.type?"active":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.switchState(1)}}},[t._v("直推客户("+t._s("59")+"人)")]),n("v-uni-view",{staticClass:"name",class:2==t.type?"active":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.switchState(2)}}},[t._v("间推客户("+t._s("1549")+"人)")])],1),t.dataList&&t.dataList.length>0?n("v-uni-view",{staticClass:"order-items"},t._l(t.dataList,(function(e,i){return n("v-uni-view",{key:i,staticClass:"item"},[n("v-uni-view",{staticClass:"user-status"},[n("v-uni-image",{staticClass:"acatar",attrs:{src:e.children.avatar_url||"https://pic.downk.cc/item/5ed21429c2a9a83be556f8fe.png",mode:"aspectFill"}}),n("v-uni-view",{staticClass:"name-datetime"},[n("v-uni-view",{staticClass:"name"},[n("v-uni-view",{staticClass:"name-text"},[t._v(t._s(e.children.nickname))]),n("v-uni-view",{staticClass:"id"},[t._v("ID:"+t._s(e.children.id))])],1),n("v-uni-view",{staticClass:"tel"},[t._v(t._s(e.children.mobile||"15015756796")),n("span",{staticClass:"iconfont icon-dianhua3",staticStyle:{"margin-left":"10rpx"}})]),n("v-uni-view",{staticClass:"datetime"},[t._v(t._s(e.created_at))])],1),n("v-uni-view",{staticClass:"status-text"},[t._v(t._s("详情"))])],1),n("v-uni-view",{staticClass:"info"},[n("v-uni-view",{staticClass:"mark"},[n("v-uni-view",{staticClass:"goods-name"},[t._v("订单数量: "+t._s(e.order_count)+"个")]),n("v-uni-view",{staticClass:"order-id"},[t._v("团队人数: "+t._s(e.team_user_count)+"个")])],1),n("v-uni-view",{staticClass:"money"},[n("v-uni-view",{staticClass:"order-money"},[t._v("订单金额: "+t._s(e.total_price)+"元")]),n("v-uni-view",{staticClass:"commission"},[t._v("团队金额: "+t._s(e.team_total_price)+"元")])],1)],1)],1)})),1):n("v-uni-view",{staticClass:"order-items"},[n("main-nomore",{attrs:{text:"暂无客户",visible:!0,bgcolor:"transparent"}})],1)],1):t._e()],1)],1)],1),n("main-loading",{attrs:{visible:t.loading}})],1)},o=[]},"1dcc":function(t,e,n){"use strict";var i=n("280c"),a=n.n(i);a.a},"280c":function(t,e,n){var i=n("9eb2");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("0030d77d",i,!0,{sourceMap:!1,shadowMode:!1})},3673:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return t.visible?n("v-uni-view",{staticClass:"jx-loading-init"},[n("v-uni-view",{staticClass:"jx-loading-center"}),n("v-uni-view",{staticClass:"jx-loadmore-tips"},[t._v(t._s(t.text))])],1):t._e()},o=[]},"36f7":function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=uni.getSystemInfoSync().statusBarHeight+"px",a={name:"ComStatusBar",data:function(){return{statusBarHeight:i}}};e.default=a},"460d":function(t,e,n){"use strict";var i=n("8652"),a=n.n(i);a.a},"4acb":function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'.jx-loadmore-none[data-v-332b08f2]{width:50%;margin:1.5em auto;line-height:1.5em;font-size:%?24?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center}.jx-nomore[data-v-332b08f2]{width:100%;height:100%;position:relative;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;margin-top:%?10?%;padding-bottom:%?6?%}.jx-nomore[data-v-332b08f2]::before{content:" ";position:absolute;border-bottom:%?1?% solid #e5e5e5;-webkit-transform:scaleY(.5);transform:scaleY(.5);width:100%;top:%?18?%;left:0}.jx-nomore-text[data-v-332b08f2]{color:#999;font-size:%?24?%;text-align:center;padding:0 %?18?%;height:%?36?%;line-height:%?36?%;position:relative;z-index:1}.jx-nomore-dot[data-v-332b08f2]{position:relative;text-align:center;-webkit-display:flex;display:-webkit-box;display:flex;-webkit-justify-content:center;-webkit-box-pack:center;justify-content:center;margin-top:%?10?%;padding-bottom:%?6?%}.jx-nomore-dot[data-v-332b08f2]::before{content:"";position:absolute;border-bottom:%?1?% solid #e5e5e5;-webkit-transform:scaleY(.5);transform:scaleY(.5);width:%?360?%;top:%?18?%}.jx-dot-text[data-v-332b08f2]{position:relative;color:#e5e5e5;font-size:10px;text-align:center;width:%?50?%;height:%?36?%;line-height:%?36?%;-webkit-transform:scale(.8);transform:scale(.8);-webkit-transform-origin:center center;transform-origin:center center;z-index:1}',""]),t.exports=e},"644b":function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return t.visible?n("v-uni-view",{staticClass:"jx-nomore-class jx-loadmore-none"},[n("v-uni-view",{class:[t.isDot?"jx-nomore-dot":"jx-nomore"]},[n("v-uni-view",{class:[t.isDot?"jx-dot-text":"jx-nomore-text"],style:{background:t.bgcolor}},[t._v(t._s(t.isDot?t.dotText:t.text))])],1)],1):t._e()},o=[]},"6dac":function(t,e,n){"use strict";var i=n("9db0"),a=n.n(i);a.a},"6e1b":function(t,e,n){"use strict";n.r(e);var i=n("d1d3"),a=n("f82f");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("6dac");var r,s=n("f0c5"),c=Object(s["a"])(a["default"],i["b"],i["c"],!1,null,"31d08a7f",null,!1,i["a"],r);e["default"]=c.exports},"7e82":function(t,e,n){"use strict";n.r(e);var i=n("e991"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},8652:function(t,e,n){var i=n("fbdf");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("15a9f5a8",i,!0,{sourceMap:!1,shadowMode:!1})},8828:function(t,e,n){"use strict";n("99af"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i="refresh",a={data:function(){return{parent:null,team_level_list:[],team_commission:null,dataList:[],pages:{total_count:1,page_count:1,pageSize:20,current_page:1},loadding:!1,pullUpOn:!0,loading:!1,sign:"",tabType:0,type:1}},onLoad:function(t){t.sign&&(this.sign=t.sign),this.getData(),this.getDetail(),this.getDateList(i,!0)},computed:{tabs:function(){var t=[{name:"全部",type:0},{name:"经销",type:1},{name:"团队",type:2},{name:"代理",type:3}];return t}},methods:{switchState:function(t){this.type=t},switchTab:function(t){this.tabType=t},getData:function(){var t=this;this.loading=!0,this.$http.request({url:this.$api.plugin.extensions.team.info,method:"POST"}).then((function(e){t.loading=!1,0==e.code&&(t.team_level_list=e.data.team_level,t.team_commission=e.data.team_commission)}))},getDetail:function(){var t=this;this.loading=!0,this.$http.request({url:this.$api.plugin.distribution.info,method:"POST"}).then((function(e){t.loading=!1,0==e.code&&(t.parent=e.data.info)}))},getDateList:function(t,e){var n=this;this.loading=!!e,t==i&&(this.pages={current_page:1,pageSize:20,page_count:1,total_count:0});var a=this.pages,o=a.current_page,r=a.pageSize;this.$http.request({url:this.$api.plugin.extensions.team.list,method:"POST",data:{flag:this.type,page:o,limit:r}}).then((function(e){if(n.loading=!1,0===e.code){var i=e.data,a=i.list,o=i.pagination;n.dataList="refresh"!=t?n.dataList.concat(a):a,n.pages=o,n.pullUpOn=!0}}))},back:function(){this.navBack()}},onPullDownRefresh:function(){var t=this;setTimeout((function(){uni.stopPullDownRefresh(),t.getDateList(i)}),1e3)},onReachBottom:function(){var t=this;this.loadding=!0,this.pullUpOn=!0;var e=this.pages,n=e.current_page,i=e.page_count;setTimeout((function(){t.loadding=!1,n>=i?t.pullUpOn=!1:(t.pages.current_page++,t.getDateList())}),1e3)}};e.default=a},"95db":function(t,e,n){var i=n("4acb");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("0f41b927",i,!0,{sourceMap:!1,shadowMode:!1})},"98b4":function(t,e,n){"use strict";n.r(e);var i=n("3673"),a=n("ddf4");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("05b8");var r,s=n("f0c5"),c=Object(s["a"])(a["default"],i["b"],i["c"],!1,null,"85db7258",null,!1,i["a"],r);e["default"]=c.exports},"9db0":function(t,e,n){var i=n("af90");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("0b5b5a3a",i,!0,{sourceMap:!1,shadowMode:!1})},"9eb2":function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 商城主题色 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.app[data-v-533dd3ce]{min-height:100%;background-color:#f7f7f7;padding-bottom:10px}.app .container .content-head[data-v-533dd3ce]{width:100%;position:relative;padding-top:%?30?%}.app .container .content-head .jx-bg[data-v-533dd3ce]{position:absolute;top:0;width:100%;height:%?414?%}.app .container .content-head .super[data-v-533dd3ce]{position:relative;background-color:#fff;-webkit-border-radius:%?8?%;border-radius:%?8?%;margin:0 %?30?%;padding:%?30?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.app .container .content-head .super .acatar[data-v-533dd3ce]{width:%?122?%;height:%?122?%;-webkit-border-radius:50%;border-radius:50%;margin-right:%?30?%}.app .container .content-head .super .userinfo .username[data-v-533dd3ce],\n.app .container .content-head .super .userinfo .desc[data-v-533dd3ce],\n.app .container .content-head .super .userinfo .tel[data-v-533dd3ce]{line-height:%?48?%}.app .container .content-head .super .userinfo .username[data-v-533dd3ce]{color:#000;font-weight:700;font-size:%?36?%}.app .container .content-head .super .userinfo .desc[data-v-533dd3ce],\n.app .container .content-head .super .userinfo .tel[data-v-533dd3ce]{color:#bc0100;font-size:9pt}.app .container .content-head .super .userinfo .iconfont[data-v-533dd3ce]{font-size:10pt;margin-right:%?8?%}.app .container .content-bottom[data-v-533dd3ce]{position:relative;padding:0 %?30?%}.app .container .content-bottom .card[data-v-533dd3ce],\n.app .container .content-bottom .order[data-v-533dd3ce]{margin-top:%?20?%;-webkit-border-radius:%?15?%;border-radius:%?15?%;background-color:#fff;color:#333}.app .container .content-bottom .card .title[data-v-533dd3ce]{padding:0 %?30?%;line-height:%?90?%;font-size:12pt;border-bottom:%?1?% solid #f3f3f3}.app .container .content-bottom .card .bill[data-v-533dd3ce]{display:-webkit-box;display:-webkit-flex;display:flex}.app .container .content-bottom .card .bill .icon-text[data-v-533dd3ce]{-webkit-box-flex:1;-webkit-flex:1;flex:1;padding:%?30?% 0}.app .container .content-bottom .card .bill .icon-text .logo-img[data-v-533dd3ce]{width:%?50?%;height:%?50?%}.app .container .content-bottom .card .bill .icon-text .name[data-v-533dd3ce]{font-size:10pt}.app .container .content-bottom .card .bill .icon-text .sum[data-v-533dd3ce]{font-weight:700;color:#bc0100;font-size:11pt;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:%?180?%}.app .container .content-bottom .order[data-v-533dd3ce]{font-size:11pt}.app .container .content-bottom .order .tabs[data-v-533dd3ce]{display:-webkit-box;display:-webkit-flex;display:flex;border-bottom:%?1?% solid #f3f3f3}.app .container .content-bottom .order .tabs .tab[data-v-533dd3ce]{-webkit-box-flex:1;-webkit-flex:1;flex:1;text-align:center;line-height:%?90?%;border-right:%?1?% solid #f2f2f2}.app .container .content-bottom .order .tabs .tab .name[data-v-533dd3ce]{position:relative}.app .container .content-bottom .order .tabs .tab.active[data-v-533dd3ce]{color:#bc0100}.app .container .content-bottom .order .tabs .tab.active .name[data-v-533dd3ce]::before{content:"";position:absolute;bottom:%?-12?%;height:%?4?%;width:100%;background-color:#bc0100}.app .container .content-bottom .order .tabs .tab[data-v-533dd3ce]:last-child{border-right:0}.app .container .content-bottom .order .status[data-v-533dd3ce]{display:-webkit-box;display:-webkit-flex;display:flex;padding:%?36?% %?30?%;line-height:%?60?%}.app .container .content-bottom .order .status .name[data-v-533dd3ce]{-webkit-box-flex:1;-webkit-flex:1;flex:1;text-align:center;margin:0 %?36?%}.app .container .content-bottom .order .status .name.active[data-v-533dd3ce]{border-bottom:%?4?% solid #bc0100}.app .container .content-bottom .order .order-items[data-v-533dd3ce]{border-top:%?1?% solid #f3f3f3;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column}.app .container .content-bottom .order .order-items .item[data-v-533dd3ce]{padding:%?20?%;border-bottom:%?1?% solid #f3f3f3}.app .container .content-bottom .order .order-items .item .user-status[data-v-533dd3ce]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;position:relative;margin-bottom:%?16?%}.app .container .content-bottom .order .order-items .item .user-status .acatar[data-v-533dd3ce]{width:%?100?%;height:%?100?%;-webkit-border-radius:50%;border-radius:50%;margin-right:%?16?%}.app .container .content-bottom .order .order-items .item .user-status .name-datetime .name[data-v-533dd3ce]{display:-webkit-box;display:-webkit-flex;display:flex;line-height:%?37?%}.app .container .content-bottom .order .order-items .item .user-status .name-datetime .name .name-text[data-v-533dd3ce]{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:%?154?%}.app .container .content-bottom .order .order-items .item .user-status .name-datetime .name .id[data-v-533dd3ce]{margin-left:%?12?%;color:#bc0100;padding:0 %?10?%;font-size:9pt;-webkit-transform:scale(.8);transform:scale(.8);line-height:%?32?%;border:%?1?% solid #bc0100;-webkit-border-radius:%?14?%;border-radius:%?14?%}.app .container .content-bottom .order .order-items .item .user-status .name-datetime .tel[data-v-533dd3ce],\n.app .container .content-bottom .order .order-items .item .user-status .name-datetime .datetime[data-v-533dd3ce]{font-size:9pt;color:grey}.app .container .content-bottom .order .order-items .item .user-status .name-datetime .tel .iconfont[data-v-533dd3ce],\n.app .container .content-bottom .order .order-items .item .user-status .name-datetime .datetime .iconfont[data-v-533dd3ce]{color:#bc0100;line-height:16px;font-size:10pt}.app .container .content-bottom .order .order-items .item .user-status .status-text[data-v-533dd3ce]{position:absolute;width:%?88?%;height:%?36?%;top:0;right:0;padding:0 %?10?%;background-color:#bc0100;border:%?1?% solid #bc0100;-webkit-border-radius:%?18?%;border-radius:%?18?%;color:#fff;font-size:9pt;-webkit-transform:scale(.8);transform:scale(.8);text-align:center;line-height:%?32?%}.app .container .content-bottom .order .order-items .item .info[data-v-533dd3ce]{display:-webkit-box;display:-webkit-flex;display:flex;font-size:9pt}.app .container .content-bottom .order .order-items .item .info .mark[data-v-533dd3ce]{-webkit-box-flex:1;-webkit-flex:1;flex:1}.app .container .content-bottom .order .order-items .item .info .mark .goods-name[data-v-533dd3ce],\n.app .container .content-bottom .order .order-items .item .info .mark .order-id[data-v-533dd3ce]{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:%?420?%}.app .container .content-bottom .order .order-items .item .info .money .commission[data-v-533dd3ce]{color:#bc0100}.flex-column-x-center[data-v-533dd3ce]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center}',""]),t.exports=e},af90:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 商城主题色 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.uni-status-bar[data-v-31d08a7f]{width:%?750?%;height:20px}',""]),t.exports=e},be74:function(t,e,n){var i=n("dfeb");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("5227196c",i,!0,{sourceMap:!1,shadowMode:!1})},c332:function(t,e,n){"use strict";var i=n("95db"),a=n.n(i);a.a},c410:function(t,e,n){"use strict";n.r(e);var i=n("0563"),a=n("7e82");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("460d");var r,s=n("f0c5"),c=Object(s["a"])(a["default"],i["b"],i["c"],!1,null,"431c4463",null,!1,i["a"],r);e["default"]=c.exports},ca5f:function(t,e,n){"use strict";n.r(e);var i=n("644b"),a=n("097b");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("c332");var r,s=n("f0c5"),c=Object(s["a"])(a["default"],i["b"],i["c"],!1,null,"332b08f2",null,!1,i["a"],r);e["default"]=c.exports},d1d3:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"uni-status-bar",style:{height:t.statusBarHeight}},[t._t("default")],2)},o=[]},ddf4:function(t,e,n){"use strict";n.r(e);var i=n("0002"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},dfeb:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,".jx-loading-init[data-v-85db7258]{min-width:%?200?%;min-height:%?200?%;max-width:%?500?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;position:fixed;top:50%;left:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);z-index:9999;font-size:%?26?%;color:#fff;background:rgba(0,0,0,.7);-webkit-border-radius:%?10?%;border-radius:%?10?%}.jx-loading-center[data-v-85db7258]{width:%?50?%;height:%?50?%;border:3px solid #fff;-webkit-border-radius:50%;border-radius:50%;margin:0 6px;display:inline-block;vertical-align:middle;-webkit-clip-path:polygon(0 0,100% 0,100% 40%,0 40%);clip-path:polygon(0 0,100% 0,100% 40%,0 40%);-webkit-animation:rotate-data-v-85db7258 1s linear infinite;animation:rotate-data-v-85db7258 1s linear infinite;margin-bottom:%?36?%}.jx-loadmore-tips[data-v-85db7258]{text-align:center;padding:0 %?20?%;-webkit-box-sizing:border-box;box-sizing:border-box}@-webkit-keyframes rotate-data-v-85db7258{from{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}@keyframes rotate-data-v-85db7258{from{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}",""]),t.exports=e},e991:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={name:"ComNavBar",props:{title:{type:String,default:""},leftText:{type:String,default:""},rightText:{type:String,default:""},leftIcon:{type:String,default:""},rightIcon:{type:String,default:""},fixed:{type:[Boolean,String],default:!1},color:{type:String,default:"#000000"},backgroundColor:{type:String,default:"#FFFFFF"},statusBar:{type:[Boolean,String],default:!1},shadow:{type:[String,Boolean],default:!1},border:{type:[String,Boolean],default:!0}},mounted:function(){uni.report&&""!==this.title&&uni.report("title",this.title)},methods:{onClickLeft:function(){var t=getCurrentPages();1==t.length?uni.redirectTo({url:"/pages/index/index"}):this.$emit("clickLeft")},onClickRight:function(){this.$emit("clickRight")}}};e.default=i},f444:function(t,e,n){"use strict";n.r(e);var i=n("15dc"),a=n("fdeb");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("1dcc");var r,s=n("f0c5"),c=Object(s["a"])(a["default"],i["b"],i["c"],!1,null,"533dd3ce",null,!1,i["a"],r);e["default"]=c.exports},f82f:function(t,e,n){"use strict";n.r(e);var i=n("36f7"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},fbdf:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 商城主题色 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.uni-nav-bar-text[data-v-431c4463]{font-size:%?32?%}.uni-nav-bar-right-text[data-v-431c4463]{font-size:%?28?%}.uni-navbar[data-v-431c4463]{width:%?750?%}.uni-navbar__content[data-v-431c4463]{position:relative;width:%?750?%;background-color:#fff;overflow:hidden}.uni-navbar__content_view[data-v-431c4463]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row}.uni-navbar__header[data-v-431c4463]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;width:%?750?%;height:44px;line-height:44px;font-size:16px}.uni-navbar__header-btns[data-v-431c4463]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-flex-wrap:nowrap;flex-wrap:nowrap;width:%?120?%;padding:0 6px;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.uni-navbar__header-btns-left[data-v-431c4463]{display:-webkit-box;display:-webkit-flex;display:flex;width:%?150?%;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start}.uni-navbar__header-btns-right[data-v-431c4463]{display:-webkit-box;display:-webkit-flex;display:flex;width:%?150?%;padding-right:%?30?%;-webkit-box-pack:end;-webkit-justify-content:flex-end;justify-content:flex-end}.uni-navbar__header-container[data-v-431c4463]{-webkit-box-flex:1;-webkit-flex:1;flex:1}.uni-navbar__header-container-inner[data-v-431c4463]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-flex:1;-webkit-flex:1;flex:1;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;font-size:%?28?%}.uni-navbar__placeholder-view[data-v-431c4463]{height:44px}.uni-navbar--fixed[data-v-431c4463]{position:fixed;z-index:998}.uni-navbar--shadow[data-v-431c4463]{-webkit-box-shadow:0 1px 6px #ccc;box-shadow:0 1px 6px #ccc}.uni-navbar--border[data-v-431c4463]{border-bottom-width:%?1?%;border-bottom-style:solid;border-bottom-color:#f3f3f3}',""]),t.exports=e},fdeb:function(t,e,n){"use strict";n.r(e);var i=n("8828"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a}}]);