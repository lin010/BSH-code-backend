<template>
	<view class="container">
		<!--header-->
		<view class="tui-header-box" :style="{height:height+'px',background:'rgba(255,255,255,'+opcity+')'}">
			<view class="tui-header" :style="{paddingTop:top+'px', opacity:opcity}">
				商品详情
			</view>
			<view class="tui-header-icon" :style="{marginTop:top+'px'}">
				<view class="tui-icon iconfont icon-fanhui" :style="{color:opcity>=1?'#000':'#fff',background:'rgba(0, 0, 0,'+iconOpcity+')'}"
				 @tap.stop="back"></view>
				<view class="tui-icon iconfont icon-gengduo" :style="{color:opcity>=1?'#000':'#fff',background:'rgba(0, 0, 0,'+iconOpcity+')',fontSize:'20px'}"
				 @tap.stop="openMenu"></view>
			</view>
		</view>
		<!--header-->

		<!--banner-->
		<view class="tui-banner-swiper">
			<swiper :autoplay="true" :interval="5000" :duration="150" :circular="true" :style="{height:scrollH + 'px'}" @change="bannerChange">
				<block v-for="(item,b_index) in goodsData.pic_list" :key="b_index">
					<swiper-item :data-index="b_index">
						<image :src="item.pic_url" mode="aspectFill" class="tui-slide-image" :style="{height:scrollH+'px'}" />
					</swiper-item>
				</block>
			</swiper>
			<jx-tag class="tui-tag-class" type="translucent" shape="circle" size="small">{{bannerIndex+1}}/{{bannerLength}}</jx-tag>
		</view>
		<!--banner-->


		<block v-if="goodsData">
			<view class="group-detail flex flex-x-between" :style="{background:textColor}">
				<view class="left">
					<view class="price">
						<view>
							&yen;<text class="price-sale">{{goodsData.group_buy.group_buy_price}}</text>
							<text class="price-original">&yen;&nbsp;{{goodsData.original_price}}</text>
						</view>
					</view>
					<view class="sale-num">累计已经拼{{goodsData.group_buy.cumulative_sales}}件 | 还剩{{goodsData.goods_stock}}件</view>
				</view>
				<!-- <view class="right flex flex-col flex-y-center">
					<view class="right-tips">距结束仅剩:</view>
					<view class="countdown">
						12：06 ：52
					</view>
				</view> -->
			</view>
		</block>

		<view class="tui-pro-detail" v-if="goodsData">
			<view class="tui-product-title">
				<view class="tui-pro-titbox flex flex-y-center">
					<view class="tui-pro-title">{{goodsData.name}}</view>
					<view class="group-people-num" :style="{color:textColor}">{{goodsData.group_buy.people}}人团</view>
				</view>
				<view class="tui-padding">
					<!-- <view class="tui-sub-title">此商品将于2019-06-28,10点结束闪购特卖，时尚美饰联合专场</view> -->
					<view class="tui-sale-info tui-size tui-gray" style="padding: 16rpx 0;">
						<!-- <view class="text">快递：0.00</view> -->
						<view class="text">销量：{{sales}}件</view>
					</view>

					<view class="coupon" @tap="coupon" v-if="goodsData">
						<block v-if="goodsData.coupon_list.length != 0">
							<view class="coupon-title">领券</view>
							<view class="coupon-list" :style="{color:textColor}">
								<view class="iconfont icon-youhuiquan"></view>
								<view class="text">{{goodsData.coupon_list[0].name}}</view>
							</view>
							<view class="to">
								<i class="iconfont icon-xiala"></i>
							</view>
						</block>
					</view>
				</view>
			</view>

			<!-- 拼团列表 -->
			<view v-if="indexGoodsGroupList.length>0" class="group-box">
				<view class="title flex flex-y-center flex-x-between">
					<view class="title-left">
						<text style="color: #BC0100;">{{total_actual_people}}</text>
						人在拼团，可直接参团
					</view>
					<view @click="moreGroupLis" class="title-right flex flex-y-center">
						查看更多 <text class="iconfont icon-right title-icon"></text>
					</view>
				</view>
				<!-- 详情页的拼团列表 -->
				<view class="group-item flex flex-y-center flex-x-between" v-for="(item,index) in indexGoodsGroupList" :key="index">
					<view class="group-item-left flex">
						<view v-for="(its,index) in item.active_item_list" :key="index" class="avatar flex flex-x-center flex-y-center" :class="{'avatar2':index == 1,'avatar3':index == 2}">
							<image class="avatar-img" :src="its.user.avatar_url" mode=""></image>
							<view v-if="index == 2" class="iconfont icon-gengduo avatar-icon"></view>
						</view>
					</view>
					<view class="group-item-right flex flex-y-center">
						<view class="time flex flex-col flex-y-bottom">
							<view>
								还差<text style="color: #BC0100;">{{item.people-item.actual_people}}人</text>成团
							</view>
							<view class="count-down-time">剩余
								<view class="tui-countdown">
									<tui-countdown :time="item.countDownTime" borderColor="#fff"></tui-countdown>
								</view>
							</view>
						</view>
						<view class="btn" @click="joinGroup(item.id)">去拼团</view>
						<!-- <view class="btn" @click="goDetail(item.group_id)">去拼团</view> -->
					</view>
				</view>
			</view>
			<!-- 选规格 -->
			<view class="tui-basic-info tui-mtop">
				<view class="tui-list-cell" @tap="showPopup(0)">
					<view class="tui-bold tui-cell-title">已选</view>
					<view class="selected-box" v-if="goodsData.use_attr == 1">{{strName}}</view>
					<view class="selected-box" v-else>
						<block v-if="goodsData.attr_list">{{goodsData.attr_list[0].attr_list[0].attr_name}}</block>
					</view>
					<view class="iconfont icon-xiala"></view>
				</view>
				<view class="tui-list-cell last" style="padding: 12rpx 30rpx;" v-if="goodsData.service_list != 0">
					<view class="tui-bold tui-cell-title">服务</view>
					<view class="selected-box">
						<view class="tag line" v-for="(item,index) in goodsData.service_list" :key="index">
							<view class="iconfont icon-dagou" style=""></view>
							<view class="text">{{item}}</view>
						</view>
					</view>
				</view>
			</view>
			<!-- 用户评论 -->
			<view class="assess-content tui-mtop" v-if="commentsData && commentsData.length">
				<view class="tui-list-cell last tui-between">
					<view class="tui-bold user-assess-title">用户评价({{commentsData.length}})</view>
					<view class="user-assess" @click="common(4)">
						<text class="tui-cmt-all">{{goodRate}}%满意</text>
						<view class="iconfont icon-xiala"></view>
					</view>
				</view>
				<view class="tui-cmt-content tui-padding">
					<view class="tags">
						<jx-tag v-for="(item,i) in commentCount" :key="i" class="tag" type="gold" :shape="'circle'">{{item.name}}({{item.count}})</jx-tag>
					</view>
					<!-- <scroll-view class="user-assess-list" scroll-x="true" enable-flex="ture"> -->
						<view class="items">
							
							<view class="user-assess-item" v-for="(item,i) in _commentsCopy" :key="i">
								<view class="user">
									<image :src="item.avatar" class="acatar"></image>
									<view>{{item.nickname}}</view>
									<jx-rate class="rate" :current="item.score" :size="14" normal="#ccc" active="#DDB052" ></jx-rate>
								</view>
								<view class="assess">{{item.content}}</view>
							</view>
							<view class="user-assess-item last" @click="common(4)">
								查看更多评价
								<span class="icon iconfont icon-gengduo1"></span>
							</view>
						</view>
					<!-- </scroll-view> -->
				</view>
			</view>
			<!-- <view v-else class="assess-content tui-mtop">
				<main-nomore text="暂无评价" :visible="true" bgcolor="#f7f7f7"></main-nomore>
			</view> -->

			<view class="nomore-box">
				<main-nomore text="宝贝详情" :visible="true" bgcolor="#f7f7f7"></main-nomore>
			</view>
			<view class="tui-product-img tui-radius-all">
				<jyf-parser :html="goodsData.detail"></jyf-parser>
			</view>
			<main-nomore text="已经到最底了" :visible="true" bgcolor="#f7f7f7"></main-nomore>
			<view class="tui-safearea-bottom"></view>
		</view>

		<!--底部操作栏-->
		<view class="tui-operation">
			<view class="tui-operation-left tui-col-5">
				<view class="tui-operation-item" hover-class="opcity" @tap="navTo('index')" :hover-stay-time="150">
					<view class="iconfont icon-shouye"></view>
					<view class="tui-operation-text tui-scale-small">首页</view>
				</view>
				<view class="tui-operation-item" hover-class="opcity" @tap='customerService' :hover-stay-time="150">
					<view class="iconfont icon-kefu2"></view>
					<view class="tui-operation-text tui-scale-small">客服</view>
				</view>
			</view>
			<view class="tui-operation-right tui-right-flex tui-col-7 tui-btnbox-4">
				<view class="jx-btn radius-left show-popup" :style="{background:textColor}" style="height: 80%;opacity:.6" @click="showPopup(2)">
					<view>&yen;<text style="font-size: 30rpx;">{{selectData.price}}</text></view>
					<view style="font-size: 26rpx;letter-spacing: 1px;">单独购买</view>
				</view>
				<view class="jx-btn radius-right submit" :style="{background:textColor}" style="height: 80%;" @click="showPopup(1)">
					<view>&yen;<text style="font-size: 30rpx;">{{selectData.group_buy_price}}</text></view>
					<view style="font-size: 26rpx;letter-spacing: 1px;">拼团购买</view>
				</view>
			</view>
		</view>
		<!--底部操作栏--->

		<!--顶部下拉菜单-->
		<tui-top-dropdown tui-top-dropdown="tui-top-dropdown" bgcolor="rgba(76, 76, 76, 0.95)" :show="menuShow" :height="0"
		 @close="closeMenu">
			<view class="tui-menu-box tui-padding tui-ptop">
				<view class="tui-menu-header" :style="{paddingTop:top+'px'}">
					功能直达
				</view>
				<view class="tui-menu-itembox">
					<block v-for="(item,index) in topMenu" :key="index">
						<view class="tui-menu-item" hover-class="tui-opcity" :hover-stay-time="150" @tap="common(index)">
							<view class="tui-badge-box">
								<tui-icon :name="item.icon" color="#fff" :size="item.size"></tui-icon>
								<tui-badge type="red" tui-badge-class="tui-menu-badge" size="small" v-if="item.badge">{{item.badge}}</tui-badge>
							</view>
							<view class="tui-menu-text">{{item.text}}</view>
						</view>
					</block>
				</view>
				<view class="tui-icon tui-icon-up" style="color: #fff; font-size: 26px;" @tap.stop="closeMenu"></view>
			</view>

		</tui-top-dropdown>
		<!---顶部下拉菜单-->

		<!--优惠券底部选择层-->
		<com-bottom-popup :show="popupShow2" @close="hidePopup">
			<scroll-view scroll-y="true" style="max-height: 1000rpx;">
				<view class="coupon-box">
					<view class="coupon-title2">
						优惠券
						<view class="coupon-icon iconfont icon-guanbi" @tap="hidePopup"></view>
					</view>
					<view style="height: 88rpx;"></view>
					<view class="coupon-tips"></view>
					<view class="coupon-content">
						<view class="coupon-item" :style="{background: 'url('+couponImg+')no-repeat'}" v-for="(Citem,Cindex) in goodsData.coupon_list"
						 :key="Cindex">
							<view class="coupon-item-left">
								<view class="coupon-item-price">
									<block v-if="Citem.type == 2">
										<text class="price-symbol">&yen;</text>
										<text class="price-int">{{Citem.sub_price}}</text>
									</block>
									<block v-else>
										<text class="price-int">{{Citem.discount*10}}</text>
										<text>折</text>
									</block>
									<!-- <text class="price-decimal">.00</text> -->
								</view>
								<view class="coupon-item-condition">
									满{{Citem.min_price}}可用
								</view>
							</view>
							<view class="coupon-item-right">
								<view class="coupon-item-name">{{Citem.name}}</view>
								<view class="coupon-item-time">
									<view v-if="Citem.expire_type == 1">领取{{Citem.expire_day}}天后过期</view>
									<view v-else>{{Citem.begin_at}}~{{Citem.end_at}}</view>
									<!-- <view class="receive" v-if="Citem.is_receive == 0" @tap="receiveCoupon(Citem.id)">领取</view> -->
									<view class="receive" :style="{background:textColor}" v-if="isReceive(Citem.is_receive)" @tap="receiveCoupon(Cindex,Citem.id)">领取</view>
									<view class="receive coupon-item-received" :style="{color:textColor,border:'1px solid'+textColor}" v-else>已领取</view>
								</view>
							</view>
						</view>
					</view>
				</view>
			</scroll-view>
		</com-bottom-popup>
		<!--优惠券底部选择层-->

		<!--底部选择层，渲染普通购买/拼团对应数据 use_attr:1多规格 -->
		<com-bottom-popup :show="popupShow" @close="hidePopup">
			<view class="tui-popup-box" style="border-radius: 20rpx;">
				<view v-if="goodsData.use_attr == 1" class="tui-product-box tui-padding">
					<image :src="selectData.pic_url?selectData.pic_url:goodsData.cover_pic" class="tui-popup-img"></image>
					<view class="tui-popup-price">
						<view class="tui-amount tui-bold" :style="{color:textColor}">¥{{is_index==1?selectData.group_buy_price:selectData.price}}</view>
						<view class="tui-number">已选:{{strName}}</view>
						<view class="tui-number">库存:{{selectData.stock}}</view>
					</view>
				</view>
				<view v-else class="tui-product-box tui-padding">
					<image :src="goodsData.cover_pic" class="tui-popup-img"></image>
					<view class="tui-popup-price" v-if="goodsData.attr_list">
						<!-- 如果是默认规格 -->
						<block v-if="!goodsData.attr_groups">
							<view class="tui-amount tui-bold">¥{{is_index==2?selectData.price:selectData.group_buy_price}}</view>
							<view class="tui-number">已选:默认</view>
						</block>
						<block v-else>
							<view class="tui-amount tui-bold" :style="{color:textColor}">¥{{goodsData.attr_list[0].price}}</view>
							<view class="tui-number">已选:{{goodsData.attr_list[0].attr_list[0].attr_name}}</view>
						</block>
					</view>
				</view>
				<scroll-view scroll-y class="tui-popup-scroll">
					<view class="tui-scrollview-box">
						<!-- 规格 -->
						<view>
							<view v-if="!goodsData.use_attr">-
								<block v-if="goodsData.attr_list">
									<view class="tui-bold tui-attr-title">{{goodsData.attr_list[0].attr_list[0].attr_group_name}}</view>
									<view class="tui-attr-box">
										<view class="tui-attr-item tui-attr-active" :style="{color:textColor}">
											{{goodsData.attr_list[0].attr_list[0].attr_name}}
										</view>
									</view>
								</block>
							</view>
							<view v-for="(item,index) in goodsData.attr_groups" :key='index' v-else>
								<view class="tui-bold tui-attr-title">{{item.attr_group_name}}</view>
								<view class="tui-attr-box">
									<view class="tui-attr-item" @tap="specCol(item.attr_group_id,items.attr_id,items)" :class="queryAttr(items) ?'tui-attr-active':''"
										:style="{color:queryAttr(items)?textColor:'',border:queryAttr(items)?'1px solid'+textColor:''}"
										v-for="(items,indexs) in item.attr_list" :key='indexs'>
										{{items.attr_name}}
									</view>
								</view>
							</view>
						</view>
						<!-- 规格-->

						<view class="tui-number-box tui-bold tui-attr-title">
							<view class="tui-attr-title">数量</view>
							<!-- 这里最大与显示数量不能超当前规格库存  拼团时商品数量只能为1 -->
							<tui-numberbox v-if="is_index==1" :max="1" :min="1" :value="1" :disabled="true"></tui-numberbox>
							<tui-numberbox v-if="is_index==2" :max="selectData.stock" :min="1" :value="value<selectData.stock?value:selectData.stock" @change="change"></tui-numberbox>
						</view>

					</view>
				</scroll-view>
				<view class="tui-operation tui-operation-right tui-right-flex tui-popup-btn">
					<view class="sure-btn" :style="{background:textColor}" @tap="determine">确定</view>
					
				</view>
				<view class="tui-icon tui-icon-close-fill tui-icon-close" style="color: #999;font-size:16pt" @tap="hidePopup"></view>
			</view>
		</com-bottom-popup>
		
		
		<!-- 商品拼团列表 -->
		<com-bottom-popup :show="popupShow3" @close="hidePopup3">
			<scroll-view scroll-y class="group-list-scroll" @scrolltolower="getGoodsGroupList(listPage)">
				<view class="group-item flex flex-y-center flex-x-between" v-for="(item,index) in goodsGroupList" :key="index">
					<view class="group-item-left flex">
						<view v-for="(its,index) in item.active_item_list" :key="index" class="avatar flex flex-x-center flex-y-center" :class="{'avatar2':index == 1,'avatar3':index == 2}">
							<image class="avatar-img" :src="its.user.avatar_url" mode=""></image>
							<view v-if="index == 2" class="iconfont icon-gengduo avatar-icon"></view>
						</view>
					</view>
					<view class="group-item-right flex flex-y-center">
						<view class="time flex flex-col flex-y-bottom">
							<view>
								还差<text style="color: #BC0100;">{{item.people-item.actual_people}}人</text>成团
							</view>
							<view class="count-down-time">剩余
								<view class="tui-countdown">
									<tui-countdown :time="item.countDownTime" borderColor="#fff"></tui-countdown>
								</view>
							</view>
						</view>
						<view class="btn" @click="joinGroup(item.id)">去拼团</view>
						<!-- <view class="btn" @click="goDetail(item.group_id)">去拼团</view> -->
					</view>
				</view>
			</scroll-view>
		</com-bottom-popup>
		
		

		<view class="goods-qrcode-modal" v-if="showPoster">
			<view class="goods-qrcode-body flex-col">
				<view class="flex-grow-1" style="position: relative;">
					<view style="position: absolute;left: 0;top:0;width: 100%;height: 100%;">
						<view class="goods-qrcode-box">
							<image :src="poster_url" class="goods-qrcode"></image>
						</view>
					</view>
				</view>
				<view class="flex-grow-0 flex-col flex-y-center" style="padding: 0 60rpx 80rpx">
					<view>长按图片保存至本地</view>
				</view>
				<view class="goods-qrcode-close" @click="poster(-1)">
					<view style="width: 50rpx;height: 50rpx;text-align: center;background-color: #ADADAD;color: #FFFFFF;border-radius: 50%;line-height: 50rpx;"
					 class="iconfont icon-guanbi"></view>
				</view>
			</view>
		</view>
		<!--底部选择层-->
		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	import jxRate from "@/components/rate/rate"
	import tuiIcon from "@/components/icon/icon"
	import jxTag from "@/components/tag/tag"
	import tuiBadge from "@/components/badge/badge"
	import tuiButton from "@/components/button/button"
	import tuiTopDropdown from "@/components/top-dropdown/top-dropdown"
	import tuiCountdown from "@/components/tui-countdown/tui-countdown"
	import tuiNumberbox from "@/components/numberbox/numberbox"
	import jyfParser from "@/components/jyf-parser/jyf-parser";
	export default {
		components: {
			tuiIcon,
			jxTag,
			tuiBadge,
			tuiButton,
			tuiTopDropdown,
			tuiCountdown,
			tuiNumberbox,
			jyfParser,
			jxRate
		},
		data() {
			return {
				textColor:'#bc0100',	//全局字体颜色
				
				couponImg:'',	//优惠券背景图
				curRoute : '',	//当前页面路由
				img_url: this.$api.img_url,
				is_index: 1, //1是拼团购买，2是单独购买
				proId: 0, //商品id
				goodRate: '', // 商品好评率
				commentsData: '', //评论数据
				commentCount: '', //评论类型数据
				goodsData: '', //商品详情数据
				bannerLength: 0, //轮播图有多少张
				attrGroupsLength: 0, //有多少条多规格属性
				strs: [], //已选规格id拼接成字符串，进行对比
				strName: '', //已选规格名拼接成字符串
				selectArr: [], //多规格选中数组
				selectData: '', //选中的多规格不同价格图片数据
				height: 64, //header高度
				top: 0, //标题图标距离顶部距离
				scrollH: 0, //滚动总高度
				opcity: 0,
				iconOpcity: 0.5,
				insuranceCut_current: 0,
				bannerIndex: 0,
				sales: 0,
				topMenu: [{
					icon: "home",
					text: "首页",
					size: 23,
					badge: 0
				}, {
					icon: "people",
					text: "我的",
					size: 26,
					badge: 0
				}, {
					icon: "cart",
					text: "购物车",
					size: 23,
					badge: 0
				}, {
					icon: "share",
					text: "分享",
					size: 26,
					badge: 0
				}],
				menuShow: false,
				popupShow: false,
				popupShow2: false, //优惠券弹窗是否显示
				value: 1,
				collected: false,
				cartList: [], //购物车列表
				cartList_num: 0, //购物车列表
				c_attr_id: -1, //购物车带过来的数据，如果是-1则不是购物车跳转过来
				loading: false,
				showPoster: false,
				poster_url: '',
				
				popupShow3 : false,
				listPage : 1,		//拼团列表页页面数
				goodsGroupList : [],
				indexGoodsGroupList : [],	//拼团列表前3条
				active_id : '',	//当前拼团id
			}
		},
		onLoad: function(options) {
			this.textColor = this.globalSet('textCol');
			this.couponImg = this.globalSet('couponImg');
			
			let {
				proId,
				pid,
				source
			} = options;
			if (pid) {
				uni.setStorageSync("pid", pid);
			}
			if (source) {
				uni.setStorageSync("source", source);
			}

			this.proId = proId;
			if (options.attr_id) {
				this.c_attr_id = options.attr_id;
			}
			
			let routes = getCurrentPages(); // 获取当前打开过的页面路由数组
			let curRoute = routes[routes.length - 1].route 
			this.curRoute = curRoute
			console.log(curRoute);
			
			// 
			this.active_id = '';
			this.getGoodsDetail(); //获取拼团商品详情
			this.getComment(); 	//获取评论详情
			
			this.listPage = 1;
			this.page_count = '';
			this.goodsGroupList = [];
			this.total_actual_people = 0;
			this.indexGoodsGroupList = [];
			this.getGoodsGroupList(this.listPage); //获取该商品下的拼团列表
			
			this.getCartList(); //获取购物车列表

			let obj = {};
			// #ifdef MP-WEIXIN
			obj = wx.getMenuButtonBoundingClientRect();
			// #endif
			// #ifdef MP-BAIDU
			obj = swan.getMenuButtonBoundingClientRect();
			// #endif
			// #ifdef MP-ALIPAY
			my.hideAddToDesktopMenu();
			// #endif

			setTimeout(() => {
				uni.getSystemInfo({
					success: (res) => {
						this.width = obj.left || res.windowWidth;
						this.height = obj.top ? (obj.top + obj.height + 8) : (res.statusBarHeight + 44);
						this.top = obj.top ? (obj.top + (obj.height - 32) / 2) : (res.statusBarHeight + 6);
						this.scrollH = res.windowWidth
					}
				})
			}, 50)
		},
		computed: {
			isReceive() {
				return function(val) {
					if (val != 0) {
						return false;
					} else {
						return true;
					}
				}
			},
			_commentsCopy() {
			    let temp = []
			    if (!this.commentsData) {
			     return;
			    }
			    let temps = JSON.parse(JSON.stringify(this.commentsData));
			    let temps_item = temps[0];
			    temp.push(temps_item);
			    console.log(temp);
			    return temp;
		    }
		},
		methods: {
			
			// 0.1 查看更多拼团列表
			moreGroupLis(){
				console.log(456465);
				this.popupShow3 = true;
			},
			hidePopup3(){
				this.popupShow3 = false;
			},
			
			//1.0 获取商品海报 
			poster(key) {
				if (key == -1) {
					this.showPoster = false;
					return;
				}
				this.loading = true;
				this.showPoster = true;
				if (this.poster_url) {
					setTimeout(() => {
						this.loading = false;
					}, 1000)
					return;
				}
				this.$http.request({
					url: this.$api.goods.poster,
					method: 'POST',
					data: {
						goods_id: this.proId,
						source: 2
					}
				}).then(res => {
					// console.log(res);
					if (res.code == 0) {
						this.poster_url = res.data.pic_url;
						setTimeout(() => {
							this.loading = false;
						}, 1000)
					}
				})
			},

			//点击确定按钮，判断是拼团购买还是立即下单
			determine() {
				this.popupShow = false;
				if (this.goodsData.use_attr == 0) {
					if (this.goodsData.attr_list[0].stock == 0) {
						this.$http.toast('该商品已没有库存!');
						return;
					}
				} else {
					if (this.selectData.stock == 0) {
						this.$http.toast('该商品已没有库存!');
						return;
					}
				}
				/* 这里的逻辑可能有点问题 */
				if (this.is_index == 1) {  // is_index == 1开团
					/* 这里要记录下拼团价格 */
					this.popupShow = false;
					var goods_attr_id = this.goodsData.attr_groups ? this.selectData.id : this.goodsData.attr_list[0].id;
					// 设置订单数据缓存
					uni.setStorage({
						key: 'orderData',
						data: [{
							num: this.value,
							goods_attr_id: goods_attr_id,
							id: this.proId,
							cart_id: 0
						}],
						fail() {
							console.log('存入本地失败');
						}
					});
					uni.navigateTo({
						url: '/mch/group-buy/submit?goods_id='+this.proId+'&is_index=1&active_id='+this.active_id	//这个是拼团购买流
					})
					
				} else if (this.is_index == 2) { // is_index == 2立即下单
					this.popupShow = false;
					var goods_attr_id = this.goodsData.attr_groups ? this.selectData.id : this.goodsData.attr_list[0].id;
					// 设置订单数据缓存
					uni.setStorage({
						key: 'orderData',
						data: [{
							num: this.value,
							goods_attr_id: goods_attr_id,
							id: this.proId,
							cart_id: 0
						}],
						fail() {
							console.log('存入本地失败');
						}
					});
					uni.navigateTo({
						url: '/pages/order/submit' 	//普通购买走普通购买流程，不用管
					})
				}
			},
			
			// 2.0 点击跳转到拼团详情页
			goDetail(group_id){
				uni.navigateTo({
					url:"/mch/group-buy/detail?detailId="+'6'
				})
			},
			
			//获取购物车列表
			getCartList() {
				this.$http.request({
					url: this.$api.cart.list,
					method: 'GET'
				}).then((res) => {
					if (res.code == 0) {
						this.cartList = res.data.list;
						this.cartList_num = this.cartList.length;
					}
				})
			},
			
			//领取优惠券
			receiveCoupon(index, id) { 
				this.$http.request({
					url: this.$api.coupon.receive,
					method: 'post',
					showLoading: true,
					data: {
						coupon_id: id * 1,
						receive_type: 2
					}
				}).then((res) => {
					if (res.code == 0) {
						this.goodsData.coupon_list[index].is_receive = 1; //改变已经领取的优惠券状态
						this.$http.toast('领取成功');
					}
				})
			},
			//3.0 请求评论数据
			getComment() { 
				this.$http.request({
					url: this.$api.goods.comment,
					data: {
						goods_id: this.proId
						// goods_id:12
					}
				}).then((res) => {
					if (res.code == 0) {
						this.commentsData = res.data.comments;
						this.commentCount = res.data.comment_count;
						this.goodRate = res.data.good_rate;
					}
				})
			},

			// 3.1 获取该商品下的可拼团列表--递归循环应该是没有问题的
			getGoodsGroupList(page) {
				if(this.page_count && this.page_count<this.listPage){
					return false;
				}
				this.listPage = this.listPage + 1;
				this.$http.request({
					url: this.$api.plugin.group.goods_group_list,
					method: 'post',
					data: {
						goods_id: this.proId,
						page : page
					}
				}).then((res) => {
					console.log(this.listPage);
					console.log(this.page_count);
					if (res.code == 0) {
						// 循环 多追加一个 倒计时
						let nowTime = parseInt(new Date().getTime()/1000);
						res.data.list.forEach((item) => {
								let endAtTime = this.getUnixTime(item.end_at);
								let countDownTime = endAtTime - nowTime;
								item['countDownTime']=countDownTime;
						})
						if(page==1){
							this.page_count = res.data.pagination.page_count;
							this.total_actual_people = res.data.total_actual_people;	//拼团总人数
							this.indexGoodsGroupList = res.data.list.length<3?res.data.list:res.data.list.slice(0,3);
						}
						this.goodsGroupList.push(...res.data.list);	//es6数组添加
						console.log(this.goodsGroupList);
					}
				})
			},

			//请求商品详情数据
			getGoodsDetail() {
				this.loading = true;
				this.$http.request({
					url: this.$api.plugin.group.detail,
					data: {
						id: this.proId
					},
				}).then((res) => {
					this.loading = false;
					if (res.code == 0) {
						this.goodsData = res.data.goods;
						this.sales = (this.goodsData.sales * 1) + (this.goodsData.group_buy.cumulative_sales * 1);
						// #ifdef H5
						this.$wechatSdk.initShareUrl(res.data.share_data, 'source=3');
						// #endif

						this.bannerLength = this.goodsData.pic_list.length;
						if (this.goodsData.collect.is_collect == 1) { //判断商品是否收藏
							this.collected = true;
						} else {
							this.collected = false;
						}

						if (this.goodsData.attr_groups) {
							this.attrGroupsLength = this.goodsData.attr_groups.length;
						}

						if (this.c_attr_id == -1) {
							// 初始化多规格数组,并且判断商品是否是多规格，是才执行
							if (this.goodsData.attr_groups) {
								this.goodsData.attr_groups.forEach((item) => {
									this.selectArr.push({
										'attr_group_id': item.attr_group_id,
										...item.attr_list[0]
									})
								})
							}else{	//默认规格
								this.selectData = this.goodsData.attr_list[0];
							}
							
						} else {
							//如果是购物车跳转过来，则默认显示已经选中的多规格
							var url;
							if (this.goodsData.attr_groups) {
								this.goodsData.attr_list.forEach((item) => {
									if (this.c_attr_id == item.id) {
										url = item.pic_url;
										this.selectArr.push(...item.attr_list)
									}
								})
								this.selectArr.forEach((item, index) => {
									delete item.attr_group_name;
									this.$set(item, 'pic_url', url);
								})
							}
						}

						var arr = [];
						this.selectArr.forEach((item) => {
							arr.push(item.attr_name);
						})
						this.strName = arr.join('， ');
						this.skuCommon();
					} else {
						uni.showModal({
							content: res.msg,
							showCancel: false,
							confirmColor: '#BC0100',
							success: function(res) {
								if (res.confirm) {
									uni.navigateBack();
								}
							}
						});
					}
				})
			},
			queryAttr(items) {
				let temp = this.selectArr.find(v => v.attr_id == items.attr_id);
				return temp ? true : false;
			},
			specCol(attr_group_id, attr_id, items) {
				this.strs = [];
				this.selectArr.forEach((item, index) => {
					if (item.attr_group_id == attr_group_id) {
						this.selectArr.splice(index, 1, {
							'attr_group_id': attr_group_id,
							...items
						})
					}
				})
				//拼接所选属性名
				var arr = [];
				this.selectArr.forEach((item) => {
					arr.push(item.attr_name);
				})
				this.strName = arr.join('， ');

				// console.log(this.selectArr, 'this.selectArr');
				this.skuCommon();
			},
			skuCommon() {
				this.selectArr.forEach(item => this.strs.push(item.attr_id));

				this.goodsData.attr_list.forEach((item) => {
					if (item.sign_id == this.strs.join(':')) {
						this.selectData = item;
					}
				})
			},
			insuranceCut(index) {
				this.insuranceCut_current = index;
			},
			navTo(name) {
				if (name == 'index') {
					uni.redirectTo({
						url: '/pages/index/index'
					})
				} else if (name == 'cart') {
					uni.navigateTo({
						url: '/pages/cart/index'
					})
				}
			},
			// 2.0 联系客服
			customerService() { //跳转客服
				// #ifdef H5
				window.location.href = JSON.parse(uni.getStorageSync('mall_config')).mall_setting.setting.web_service_url;
				// #endif
				// #ifdef MP-WEIXIN || APP-PLUS
				uni.navigateTo({
					url: '/pages/customerService/customerService'
				})
				// #endif
			},
			hidePopup(num) { //关闭弹窗
				this.popupShow = false;
				this.popupShow2 = false;
			},
			collecting(goods_id) { //点击收藏
				this.loading = true;
				if (this.collected) {
					this.$http.request({
						url: this.$api.collect.deletes,
						method: 'post',
						data: {
							type: 'goods',
							id: this.goodsData.collect.collect_id
						}
					}).then((res) => {
						if (res.code == 0) {
							this.collected = !this.collected;
							this.loading = false;
							this.$http.toast(res.msg);
						} else {
							this.loading = false;
							this.$http.toast(res.msg);
						}
					})
				} else {
					this.$http.request({
						url: this.$api.collect.add,
						method: 'post',
						data: {
							goods_id: this.proId
						}
					}).then(res => {
						if (res.code == 0) {
							this.collected = !this.collected;
							this.getGoodsDetail();
							this.loading = false;
							this.$http.toast(res.msg);
						} else {
							this.loading = false;
							this.$http.toast(res.msg);
						}
					})
				}
			},
			bannerChange: function(e) {
				this.bannerIndex = e.detail.current
			},
			// 返回上一页
			back: function() {
				if(this.curRoute=='mch/group-buy/good'){
					uni.redirectTo({
					    url: '/mch/group-buy/list'
					});
				}else{
					uni.navigateBack()
				}
			},
			openMenu: function() {
				this.menuShow = true
			},
			closeMenu: function() {
				this.menuShow = false
			},
			
			// 9.0 点击去拼团
			joinGroup(active_id){
				console.log(active_id);
				this.popupShow = true;
				this.popupShow3 = false;	//拼单列表隐藏
				this.is_index = 1;
				this.active_id = active_id;
			},
			
			showPopup(num) {
				if(!this.goodsData.attr_groups&&(!num)){	//默认规格的时候不让他选规格
					return
				}
				this.popupShow = true;
				// this.popupShow3 = false;	//拼单列表隐藏
				if (num) {	//有分类的时候，判定 1是拼团购买， 2是单独购买
					this.is_index = num;
				}
			},
			change: function(e) {
				this.value = e.value;
			},
			// 查看更多评论
			common: function(index) {
				console.log(index, 'index');
				switch (index) {
					case 0:
						uni.navigateTo({
							url: '/pages/index/index'
						})
						break;
					case 1:
						uni.navigateTo({
							url: '/pages/user/index'
						})
						break;
					case 2:
						uni.navigateTo({
							url: '/pages/cart/index'
						})
						break;
					case 3:
						this.$http.toast("功能开发中~");
						break;
					case 4:
						uni.navigateTo({
							url: '/pages/goods/commentList?proId='+this.proId
						})
				}
			},
			submit(num) {
				if (num == 1) {
					this.popupShow = true;
					return;
				}
			},
			coupon() {
				this.popupShow2 = true;
			},
			
			// 2019-06-17 23:11:54转时间戳
			getUnixTime(dateStr){
			    var newstr = dateStr.replace(/-/g,'/'); 
			    var date =  new Date(newstr); 
			    var time_str = date.getTime().toString();
			    return time_str.substr(0, 10);
			},
			
		},
		onPageScroll(e) {
			let scroll = e.scrollTop <= 0 ? 0 : e.scrollTop;
			let opcity = scroll / this.scrollH;
			if (this.opcity >= 1 && opcity >= 1) {
				return;
			}
			this.opcity = opcity;
			this.iconOpcity = 0.5 * (1 - opcity < 0 ? 0 : 1 - opcity)
		}
	}
</script>

<style lang="scss" scoped>
	/* icon 也可以使用组件*/
	page {
		background: #f7f7f7;
	}

	.red {
		color: #BC0100;
	}

	.tui-header-box {
		width: 100%;
		position: fixed;
		left: 0;
		top: 0;
		z-index: 9998;
	}

	.tui-header {
		width: 100%;
		font-size: 15pt;
		line-height: 18px;
		font-weight: 500;
		height: 32px;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.tui-header-icon {
		position: fixed;
		top: 0;
		left: 10px;
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		height: 32px;
		transform: translateZ(0);
		z-index: 99999;
	}



	.tui-header-icon .tui-badge {
		background: #e41f19 !important;
		position: absolute;
		right: -5px;
	}

	.tui-icon {
		border-radius: 16px;

		&.iconfont {
			line-height: 32px;
			width: 32px;
			font-size: 20px;
			display: flex;
			justify-content: center;
		}

		margin-right: 10rpx;
	}

	.tui-banner-swiper {
		position: relative;
	}

	.tui-banner-swiper .tui-tag-class {
		position: absolute;
		color: #fff;
		bottom: 30rpx;
		right: 30rpx;
	}

	.tui-slide-image {
		width: 100%;
		display: block;
	}

	/*顶部菜单*/

	.tui-menu-box {
		box-sizing: border-box;
	}

	.tui-menu-header {
		font-size: 12pt;
		color: #fff;
		height: 32px;
		display: flex;
		align-items: center;
	}

	.tui-top-dropdown {
		z-index: 9999 !important;
	}

	.tui-menu-itembox {
		color: #fff;
		padding: 40rpx 10rpx 0 10rpx;
		box-sizing: border-box;
		display: flex;
		flex-wrap: wrap;
		font-size: 10pt;
	}

	.tui-menu-item {
		width: 22%;
		height: 160rpx;
		border-radius: 24rpx;
		display: flex;
		align-items: center;
		flex-direction: column;
		justify-content: center;
		background: rgba(0, 0, 0, 0.4);
		margin-right: 4%;
		margin-bottom: 4%;
	}

	.tui-menu-item:nth-of-type(4n) {
		margin-right: 0;
	}

	.tui-badge-box {
		position: relative;
	}

	.tui-badge-box .tui-badge-class {
		position: absolute;
		top: -8px;
		right: -8px;
	}

	.tui-icon-up {
		position: relative;
		display: inline-block;
		left: 50%;
		transform: translateX(-50%);
	}

	.tui-menu-text {
		padding-top: 12rpx;
	}

	.tui-opcity .tui-menu-text,
	.tui-opcity .tui-badge-box {
		opacity: 0.5;
		transition: opacity 0.2s ease-in-out;
	}

	/*顶部菜单*/

	/*内容 部分*/

	.tui-padding {
		padding: 0 30rpx;
		box-sizing: border-box;
	}

	.padding {
		padding: 0 30rpx 0 20rpx;
		box-sizing: border-box;
	}

	.padding {
		padding: 0 20rpx 0 30rpx;
		box-sizing: border-box;
	}

	/* #ifdef H5 */
	.tui-ptop {
		padding-top: 44px;
	}

	/* #endif */

	.tui-size {
		font-size: 9pt;
		line-height: 24rpx;
	}

	.tui-gray {
		color: #808080;
		background-color: #FFFFFF !important;
	}

	.tui-radius-all {
		overflow: hidden;
	}

	.tui-mtop {
		margin-top: 20rpx;
	}

	.tui-pro-detail {
		box-sizing: border-box;
		color: #333;
	}

	.tui-product-title {
		background: #fff;
		padding: 44rpx 0 0;
	}

	.tui-pro-pricebox {
		display: flex;
		align-items: center;
		justify-content: space-between;
		color: #ff201f;
		font-size: 13pt;
		font-weight: bold;
		line-height: 44rpx;
	}

	.tui-pro-pricebox .round-btn {
		display: flex;
	}

	.tui-pro-price {
		display: flex;
		align-items: center;
		font-size: 0;
	}

	.tui-pro-price .price {
		color: #BC0100;
	}

	.tui-pro-price .price .cur-price {
		font-size: 18pt;
	}

	.tui-pro-price .price .text {
		font-size: 12pt;
	}

	.tui-pro-price .price .original-price {
		font-weight: 400;
		color: #9C9C9C;
		font-size: 9pt;
		margin-left: 10rpx;
		text-decoration: line-through;
	}

	.tui-pro-price .tui-tag-class {
		transform: scale(0.7);
		transform-origin: center center;
		line-height: 24rpx;
		font-weight: normal;
	}

	.tui-collection {
		color: #333;
		display: flex;
		align-items: center;
		flex-direction: column;
		justify-content: center;
		height: 96rpx;
		width: 96rpx;
		border-radius: 100rpx;
		background: #F7F7F7;
		margin-right: 10rpx;
	}

	.tui-scale {
		transform: scale(0.7);
		transform-origin: center center;
		line-height: 24rpx;
		font-weight: normal;
	}

	.tui-icon-collection {
		line-height: 20px !important;
		margin-bottom: 0 !important;
		color: #333333;
		font-size: 20px;
	}

	.tui-pro-titbox {
		font-size: 12pt;
		font-weight: 700;
		position: relative;
		padding: 0 40rpx 20rpx 30rpx;
		box-sizing: border-box;
	}

	.tui-pro-title {
		color: #272727;
	}

	.group-people-num {
		font-size: 24rpx;
		background: #F2CCCC;
		border-radius: 40rpx;
		padding: 4rpx 20rpx;
		letter-spacing: 1px;
		margin-left: 12rpx;
	}

	.tui-sub-title {
		padding: 20rpx 0;
		line-height: 36rpx;
		color: #2C2C2C;
		font-size: 10pt;
	}

	.tui-sale-info {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.tui-sale-info .text {
		flex: 1;
	}

	.coupon {
		font-size: 10pt;
		line-height: 45px;
		display: flex;
		align-items: center;
	}

	.coupon .coupon-title {
		color: #9C9C9C;
		margin-right: 20rpx;
	}

	.coupon .coupon-list {
		display: flex;
		align-items: center;
	}

	.coupon .coupon-list .iconfont {
		font-size: 18pt;
		margin-right: 20rpx;
	}

	.coupon .coupon-list .text {
		font-weight: 400;
	}

	.coupon .to {
		text-align: end;
		flex: 1;
		color: #9C9C9C;
	}

	.coupon .to .iconfont {
		font-size: 18pt;
	}

	.tui-list-cell {
		display: flex;
		align-items: center;
		font-size: 10pt;
		line-height: 100rpx;
		padding: 0 30rpx;
		box-sizing: border-box;
		border-bottom: 1rpx solid #E6E6E6;
	}

	.tui-list-cell.last {
		border-bottom: 0;
	}

	.tui-list-cell .iconfont {
		color: #9C9C9C;
		font-size: 18pt;
		flex: 1;
		text-align: end;
	}

	.tui-right {
		position: absolute;
		right: 30rpx;
		top: 30rpx;
	}

	.tui-bold {
		font-weight: bold;
	}

	.tui-cell-title {
		padding-right: 30rpx;
		flex-shrink: 0;
		color: #9C9C9C;
		font-weight: 400;
	}

	.tags {
		display: flex;
		margin-bottom: 20rpx;
		color: #2D2D2D;

		.tag {
			margin-right: 10rpx;
			/* #ifdef H5 */
			padding: 10rpx 16rpx;
			/* #endif */
			font-size: 9pt;
			font-weight: 400;
		}
	}

	.user-assess-list {

		display: flex;
		white-space: nowrap;

		& ::-webkit-scrollbar {
			width: 0;
			height: 0;
			background-color: transparent;
		}

	}
	.items {
		display: flex;
	
		.user-assess-item {
			box-sizing: border-box;
			background-color: #F8F8F8;
			padding: 40rpx 30rpx;
			color: #2D2D2D;
			display: inline-block;
			margin-right: 30rpx;
			min-width: 600rpx;
			
			&.last {
				min-width: 60rpx;
				line-height: 0;
				writing-mode: vertical-rl;
				font-size: 10pt;
				padding: 20rpx 50rpx;
			}
			
			.user {
				display: flex;
				align-items: center;
				margin-bottom: 10rpx;
			
				.acatar {
					width: 80rpx;
					height: 80rpx;
					border-radius: 100rpx;
					margin-right: 16rpx;
				}
			
				.rate {
					margin-left: 40rpx;
				}
			}
			
			.assess {
				padding: 14rpx 0;
				white-space:nowrap;
				overflow:hidden;
				text-overflow:ellipsis;
			}
			
			.icon {
				font-size: 10pt;
			}
		}
			
	}

	.user-assess-title {
		padding-right: 15px;
		flex-shrink: 0;
		font-weight: 500;
		color: #2D2D2D;
		font-size: 11pt;
	}

	.selected-box {
		color: #000000;
		font-weight: 400;
		display: flex;
		flex-wrap: wrap;
		line-height: 40rpx;
	}

	.selected-box .tag {
		display: flex;
		margin-right: 20rpx;
	}

	.selected-box .tag .iconfont {
		font-size: 12pt;
		color: #CF4F4E;
		margin-right: 10rpx;
	}

	.tui-basic-info {
		background: #fff;
	}

	.tui-between {
		justify-content: space-between !important;
	}

	.assess-content {
		background-color: #FFFFFF;
		padding-bottom: 40rpx;

		.msg {
			text-align: center;
		}
	}

	.user-assess {
		display: flex;
		align-items: center;
	}

	.tui-cmt-all {
		color: #9E9E9E;
		font-size: 9pt;
	}

	.tui-cmt-content {
		font-size: 10pt;
	}

	.nomore-box {
		padding-top: 10rpx;
	}

	.tui-product-img {
		transform: translateZ(0);
	}

	.tui-product-img image {
		width: 100%;
		display: block;
	}

	/*底部操作栏*/

	.tui-col-7 {
		width: 58.33333333%;
	}

	.tui-col-5 {
		width: 41.66666667%;
	}

	.tui-operation {
		width: 100%;
		height: 100rpx;
		/* box-sizing: border-box; */
		background: rgba(255, 255, 255, 0.98);
		position: fixed;
		display: flex;
		align-items: center;
		justify-content: space-between;
		z-index: 10;
		bottom: 0;
		left: 0;
		padding-bottom: env(safe-area-inset-bottom);
	}

	.tui-safearea-bottom {
		width: 100%;
		height: env(safe-area-inset-bottom);
	}

	.tui-operation::before {
		content: '';
		position: absolute;
		top: 0;
		right: 0;
		left: 0;
		border-top: 1rpx solid #eaeef1;
		-webkit-transform: scaleY(0.5);
		transform: scaleY(0.5);
	}

	.tui-operation-left {
		display: flex;
		align-items: center;
	}

	.tui-operation-item {
		flex: 1;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
		position: relative;

		.iconfont {
			font-size: 22px;
			line-height: 24px;
			color: #6A6A6A;
		}
	}

	.tui-operation-text {
		font-size: 9pt;
		color: #333;
	}

	.tui-opacity {
		opacity: 0.5;
	}

	.tui-scale-small {
		transform: scale(0.9);
		transform-origin: center center;
	}

	.tui-operation-right {
		height: 94rpx;
		/* box-sizing: border-box; */
		padding: 0 10rpx;
	}

	.jx-btn {
		height: 100%;
		width: 100%;
		border-radius: 0px;
		display: flex;
		flex: 1;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		color: #FFFFFF;
		font-size: 24rpx;
		line-height: 28rpx;

		&.radius-left {
			border-radius: 100rpx 0 0 100rpx;
		}

		&.radius-right {
			border-radius: 0 100rpx 100rpx 0;
		}
	}

	.tui-right-flex {
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.tui-btn {
		width: 100%;
		position: relative;
		border: 0 !important;
		border-radius: 0px;
		display: flex;
	}

	.tui-operation .tui-badge-class {
		position: absolute;
		top: -6rpx;
		/* #ifdef H5 || APP-PLUS*/
		transform: translateX(50%)
			/* #endif  */
	}

	.tui-flex-1 {
		flex: 1;
	}

	/*底部操作栏*/

	/*底部选择弹层*/

	.tui-popup-box {
		position: relative;
		padding: 30rpx 0 100rpx 0;
	}

	.tui-popup-btn {
		width: 100%;
		position: absolute;
		left: 0;
		bottom: 0;
	}

	.tui-icon-close {
		position: absolute;
		top: 30rpx;
		right: 30rpx;
	}

	.tui-product-box {
		display: flex;
		align-items: center;
		font-size: 9pt;
		padding-bottom: 30rpx;
	}

	.tui-popup-img {
		height: 200rpx;
		width: 200rpx;
		border-radius: 24rpx;
		display: block;
	}

	.tui-popup-price {
		padding-left: 20rpx;
		padding-bottom: 8rpx;
		flex: 1;
	}

	.tui-amount {
		color: #ff201f;
		font-size: 14pt;
	}

	.tui-number {
		font-size: 9pt;
		line-height: 28rpx;
		padding-top: 12rpx;
		color: #999;
	}

	.tui-popup-scroll {
		height: 600rpx;
		font-size: 10pt;
	}

	.tui-scrollview-box {
		padding: 0 30rpx 60rpx 30rpx;
		box-sizing: border-box;
	}

	.tui-attr-title {
		padding: 10rpx 0;
		color: #333;
	}

	.tui-attr-box {
		font-size: 0;
		padding: 20rpx 0;
	}

	.tui-attr-item {
		max-width: 100%;
		min-width: 200rpx;
		height: 64rpx;
		display: -webkit-inline-flex;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: #f7f7f7;
		padding: 0 26rpx;
		box-sizing: border-box;
		border-radius: 10rpx;
		margin-right: 20rpx;
		margin-bottom: 20rpx;
		font-size: 9.5pt;
	}

	.tui-attr-active {
		font-weight: bold;
		position: relative;
		border-radius: 10rpx;
	}
	.tui-number-box {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 20rpx 0 30rpx 0;
		box-sizing: border-box;
	}

	/*底部选择弹层*/

	/*优惠券底部选择弹层*/
	.coupon-box {
		padding: 0 20rpx 80rpx;
		box-sizing: border-box;
		overflow: hidden;
	}

	.coupon-icon {
		position: absolute;
		right: 20rpx;
		top: 50%;
		transform: translateY(-50%);
		color: #ACACAC;
		font-size: 28rpx;
	}

	.coupon-tips {
		margin: 40rpx 0 30rpx;
		color: #000000;
		font-size: 11pt;
	}

	.coupon-item {
		width: 100%;
		height: 195rpx;
		display: flex;
		color: #000000;
		margin-bottom: 20rpx;
		background-size: cover !important;
	}

	.coupon-item-left {
		width: 248rpx;
		height: 100%;
		color: #FFFFFF;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}

	.price-symbol {
		font-size: 14pt;
	}

	.price-int {
		font-size: 18pt;
		font-weight: 700;
		margin: 0 2rpx 0 4rpx;
	}

	.coupon-item-right {
		flex: 1;
		height: 100%;
		display: flex;
		flex-direction: column;
		justify-content: space-around;
		position: relative;
		padding: 20rpx 0 10rpx 20rpx;
		box-sizing: border-box;
	}

	.coupon-item-name {
		font-size: 11pt;
	}

	.coupon-item-condition {
		font-size: 10pt;
	}

	.coupon-item-time {
		font-size: 9pt;
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding-right: 12rpx;
	}

	.receive {
		background: #bc0100;
		color: #FFFFFF;
		padding: 6rpx 30rpx;
		border-radius: 40rpx;
		font-size: 9pt;
	}

	.received {
		position: absolute;
		top: 0;
		right: 0;
		font-size: 60rpx;
		color: #BC0100;
		line-height: 60rpx;
		height: 60rpx;
	}

	.coupon-item-received {
		background: transparent;
		// color: #BC0100;
		// border: 2rpx solid #BC0100;
	}

	.coupon-title2 {
		font-weight: 700;
		text-align: center;
		position: fixed;
		width: 100%;
		left: 0;
		top: 0;
		background: #FFFFFF;
		padding: 20rpx 0;
		color: #000000;
		border-radius: 20rpx 20rpx 0 0;
		border-bottom: 2rpx solid #ebebeb;
		z-index: 99;
	}

	/*优惠券底部选择弹层*/
	.line {
		line-height: 60rpx;
	}

	.sure-btn {
		// background: #BC0100;
		border-radius: 40rpx;
		width: 90%;
		font-size: 11pt;
		padding: 14rpx 0;
		margin: auto;
		color: #FFFFFF;
		text-align: center;
	}

	.goods-qrcode-modal {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 1000;
		background: rgba(0, 0, 0, 0.5);
		opacity: 1;
		transition: opacity 250ms;
		z-index: 9999;
	}

	.goods-qrcode-body {
		background: #ffffff;
		height: 100%;
		border-radius: 10rpx;
		padding: 30rpx;
	}

	.goods-qrcode-body .goods-qrcode-box {
		height: 100%;
		position: relative;
		box-shadow: 0 0 15rpx rgba(0, 0, 0, 0.15);
	}

	.goods-qrcode {
		width: 100%;
		height: 100%;
		background: #fffffff;
		background-size: 100%;
	}

	.goods-qrcode-close {
		position: absolute;
		top: 40rpx;
		/* #ifdef MP-WEIXIN */
		top: 150rpx;
		/* #endif */
		right: 40rpx;
		padding: 15rpx;
	}


	// 拼团
	.group-detail {
		padding: 24rpx 28rpx;
		color: #FFFFFF;
		.left {

			.price-sale {
				font-size: 50rpx;
				font-weight: 600;
				margin-right: 14rpx;
			}

			.price-original {
				text-decoration: line-through;
				font-size: 28rpx;
			}

			.sale-num {
				font-size: 28rpx;
				position: relative;
				top: -1px;
			}
		}

		.right {

			.right-tips {
				letter-spacing: 2px;
				font-size: 28rpx;
			}

			.countdown {
				background: #FFFFFF;
				color: #BC0100;
				width: 258rpx;
				border-radius: 50rpx;
				text-align: center;
				margin-top: 18rpx;
				font-weight: 600;
			}
		}
	}

	.group-box {
		background: #FFFFFF;
		margin-top: 14rpx;

		.title {
			padding: 18rpx 30rpx;
			border-bottom: 1px solid #EEEEEE;

			.title-left {
				font-weight: 600;
				font-size: 30rpx;
			}

			.title-right {
				color: #9E9E9E;
				font-size: 24rpx;

				.title-icon {
					position: relative;
					top: 4rpx;
					font-size: 38rpx;
				}
			}
		}

	}
	
	.group-item {
		padding: 26rpx 28rpx;
		border-bottom: 1px solid #EEEEEE;

		.group-item-left {

			.avatar {
				border-radius: 50%;
				width: 70rpx;
				height: 70rpx;
				background: #FFFFFF;
				position: relative;
				z-index: 2;

				.avatar-img {
					width: 66rpx;
					height: 66rpx;
					border-radius: 50%;
				}
			}

			.avatar2 {
				position: relative;
				left: -20rpx;
				z-index: 1;
			}

			.avatar3 {
				position: relative;
				left: -40rpx;
				z-index: 0;
			}

			.avatar-icon {
				text-align: center;
				line-height: 70rpx;
				width: 70rpx;
				height: 70rpx;
				position: absolute;
				color: #FFFFFF;
				background: rgba(0, 0, 0, 0.5);
				border-radius: 50%;
			}
		}

		.group-item-right {
			font-size: 24rpx;

			.btn {
				color: #FFFFFF;
				text-align: center;
				line-height: 58rpx;
				font-size: 28rpx;
				letter-spacing: 1px;
				width: 154rpx;
				height: 58rpx;
				background: linear-gradient(90deg, #BC0100, #F14A2E);
				border-radius: 30rpx;
				margin-left: 14rpx;
			}
			.count-down-time{
				display: flex;
				align-items: center;
				.tui-countdown{
					margin-left: 6rpx;
				}
			}
		}
	}
		
	/* 商品拼团列表 */
	.group-list-scroll{
		max-height:60vh;
		min-height:50vh;
	}
</style>
