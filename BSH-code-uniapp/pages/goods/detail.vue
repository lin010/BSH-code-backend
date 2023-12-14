<template>
	<view class="container">
		<!-- #ifdef H5 -->
		<view class="foucs_H5" style="width: 100%;height: 96rpx;background:#3e4144;position: fixed;top: 0rpx;left: 0;z-index: 9999;" v-if="is_seckill==0&&showFoucs">
			<image :src="img_url+'/fillShop.png'" mode="" style="width: 100rpx;height: 96rpx;display: block;float: left;"></image>
			<view class="foucs_H5_messga" style="float: left;">
				<text style="display: block;font-size: 25rpx;color: #fff;margin-top: 8rpx;">欢迎访问兰亭</text>
				<text style="display: block;font-size: 25rpx;color: #fff">点击关注我们哦~</text>
			</view>
			<view class="foucs_check" style="float: right;">
				<button type="default" style="width: 130rpx;height: 50rpx;text-align: center;font-size: 25rpx;
				background: rgb(255, 113, 4);margin: 20rpx 10rpx 0 0;color: #fff;line-height: 50rpx;"
				@click='foucusInfo'
				>关注</button>
			</view>
		</view>
		 <!--#endif -->	
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
		<view class="tui-banner-swiper">
			<swiper :duration="150" :style="{height:scrollH + 'px'}" @change="bannerChange">
				<swiper-item :data-index="0" v-if="goodsData.video_url">
					<video id='swiperVideo' :enable-progress-gesture='false' :src="goodsData.video_url" loop autoplay muted controls style="width: 750rpx;" :style="{height:scrollH+'px'}"></video>
				</swiper-item>
				<block v-for="(item,b_index) in goodsData.pic_list" :key="b_index">
					<swiper-item :data-index="b_index+1">
						<image :src="item.pic_url" mode="aspectFill" class="tui-slide-image" :style="{height:scrollH+'px'}"  @click="enlarge(b_index)"/>
					</swiper-item>
				</block>
			</swiper>
			<jx-tag class="tui-tag-class" type="translucent" shape="circle" size="small">{{bannerIndex+1}}/{{bannerLength}}</jx-tag>
		</view>
		<view class="seckill-time" v-if="is_seckill!=0">
			<view style="width: 42%;display: flex;justify-content: space-between;">
				<view style="width: 30%;text-align: center;">
					<image :src="plugins_img_url+'/sss.jpg'" mode="widthFix" style="width: 120rpx;display: block;height: 116rpx;margin-top: 5rpx;"></image>
				</view>
				<view style="width: 45%">
					<view  style="width: 100%;display: block;text-align: center;margin-top: 15rpx;font-size: 30rpx;color: #fff;">热卖中</view>
					<view  style="width: 100%;display: block;text-align: center;font-size: 30rpx;color: #fff;">不能错过</view>
				</view>
			</view>
			<view style="width: 58%;display: flex;justify-content: space-evenly;flex-wrap: wrap;">
				<view style="margin-top: 5rpx;color: #fff;width: 100%;text-align: right;">距离秒杀结束</view>
				<!-- <view style="margin-top: 10rpx;color: #fff;width: 100%;">活动已结束</view> -->
				<view  style="margin-top: 10rpx;color: #fff;width: 100%;text-align: right;">{{expired_at}}</view>
			</view>
		</view>
		
		
		
		
		
		
		
		
		
		
		
		
		<view class="tui-pro-detail" v-if="goodsData">
			<view class="tui-product-title">
				<view class="tui-pro-pricebox padding">
					<view class="tui-pro-price">
						<view class="price">
							<text class="cur-price" :style="{color:'#FF7104'}">&yen;{{goodsData.min_price}}</text>
							<text class="text" :style="{color:'#FF7104'}" v-if="attrGroupsLength != 0">起</text>
							<text class="original-price" v-if="attrGroupsLength != 0">&yen;{{goodsData.original_price}}</text>
						</view>
						<jx-tag size="small" :plain="true"  type="high-green" shape="circle" v-if="is_seckill==0">新品</jx-tag>
					</view>
					<view class="round-btn">
						<view class="tui-collection tui-size" @click.stop="poster()">
							<view class="tui-icon-collection iconfont icon-qrcode"></view>
							<view class="tui-scale">分享</view>
						</view>
						<view class="tui-collection tui-size" @tap="collecting" v-if="is_seckill==0">
							<view class="tui-icon-collection iconfont" :class="collected ? 'icon-shoucang' : 'icon-shoucang1' " :style="{color:collected?'#FF7104':'#333',fontSize:'20px'}"></view>
							<view class="tui-scale" :style="{color:collected?'#FF7104':''}">收藏</view>
						</view>
					</view>
				</view>
				<view class="tui-pro-titbox">
					<view class="tui-pro-title">
						<text :selectable="true">{{goodsData.name}}</text>
					</view>
					<view class="tui-pro-title-product" v-if="goodsData.product">
						{{goodsData.product}}
					</view>
				</view>
				<view class="tui-padding">
					
					<view class="tui-sale-info tui-size tui-gray" style="padding: 16rpx 0;">
						
						<view class="text">销量：{{goodsData.sales}}件</view>
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

			<view class="tui-basic-info tui-mtop">
				<view class="tui-list-cell" @tap="showPopup">
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
							<view class="iconfont icon-dagou" :style="{color:textColor}"></view>
							<view class="text">{{item}}</view>
						</view>
					</view>
				</view>
			</view>
			<info :params="mch" v-if="is_mch==1"></info>
			<view class="assess-content tui-mtop" v-if="commentsData && commentsData.length&&is_seckill==0">
				<view class="tui-list-cell last tui-between">
					<view class="tui-bold user-assess-title">用户评价({{commentCount[0].count}})</view>
					<view class="user-assess" @click="common(4)">
						<text class="tui-cmt-all">{{goodRate}}%满意</text>
						<view class="iconfont icon-xiala"></view>
					</view>
				</view>
				<view class="tui-cmt-content tui-padding">
					<view class="tags">
						<jx-tag v-for="(item,i) in commentCount" :key="i" class="tag" type="gold" :shape="'circle'">{{item.name}}({{item.count}})</jx-tag>
					</view>
				
					<view class="items">
						<view class="user-assess-item" v-for="(item,i) in _commentsCopy" :key="i">
							<view class="user">
								<image :src="item.avatar" class="acatar"></image>
								<view>{{item.nickname}}</view>
								<jx-rate class="rate" :current="item.score" :size="14" normal="#ccc" active="#DDB052" :disabled="false"></jx-rate>
							</view>
							<view class="assess">{{item.content}}</view>
						</view>
						<view class="user-assess-item last" @click="common(4)">
							查看更多评价
							<span class="icon iconfont icon-gengduo1"></span>
						</view>
					</view>
				</view>
			</view>
			

			<view class="nomore-box">
				<main-nomore text="宝贝详情" :visible="true" bgcolor="#f7f7f7"></main-nomore>
			</view>
			<view class="tui-product-img tui-radius-all">
				<jyf-parser :html="goodsData.detail"></jyf-parser>
			</view>
			<view class="guessLike" v-if="productData.length != 0">
				猜你喜欢
			</view>
			<view style="padding: 0 20rpx;">
				<commodity :listStyle='listStyle' key-value='cart' :displayStyle='displayStyle' :productData='productData'></commodity>
			</view>
			<main-nomore text="已经到最底了" :visible="true" bgcolor="#f7f7f7"></main-nomore>

			<view class="tui-safearea-bottom"></view>
		</view>

		<!--底部操作栏-->
		<view class="tui-operation">
			<view class="tui-operation-left tui-col-5">
				<view class="tui-operation-item" hover-class="opcity" :hover-stay-time="150" @tap="navTo('index')">
					<view class="iconfont icon-shouye"></view>
					<view class="tui-operation-text tui-scale-small">首页</view>
				</view>
				<view class="tui-operation-item" hover-class="opcity" :hover-stay-time="150" @tap='customerService'>
					<view class="iconfont icon-kefu2"></view>
					
					<view class="tui-operation-text tui-scale-small">客服</view>
				</view>
				<view class="tui-operation-item" hover-class="opcity" @tap="navTo('cart')" :hover-stay-time="150" v-if="is_seckill==0">
					<view class="iconfont icon-gouwuche"></view>
					<view class="tui-operation-text tui-scale-small">购物车</view>
					<view class="red-dots">{{cartList_num}}</view>
				</view>
			</view>
			<view class="tui-operation-right tui-right-flex tui-col-7 tui-btnbox-4" v-if="is_seckill==0">
				<view class="jx-btn radius-left" style="height: 80%;" :style="{border:'1px solid'+'#FF7104',color:'#FF7104'}"
				 @click="showPopup(1)" v-if="is_show_cart">
					加入购物车
				</view>
				
				<view class="jx-btn radius-right" style="height: 80%;" :style="{background:'#FF7104','border-radius':is_show_cart?'':'100rpx'}"
				 @click="showPopup(2)">
					立即下单
				</view>
			</view>
			<view class="tui-operation-right tui-right-flex tui-col-7 tui-btnbox-4" v-if="is_seckill!=0">
				<view class="jx-btn radius-right" style="height: 80%;" :style="{background:'#FF7104','border-radius':'100rpx'}"
				 @click="showPopup(2)">
					立即抢购
				</view>
			</view>
		</view>
		<view style="height: 100rpx;width: 100%;"></view>
		
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
									
									<view class="receive" :style="{background:'#FF7104'}" v-if="isReceive(Citem.is_receive)" @tap="receiveCoupon(Cindex,Citem.id)">领取</view>
									<view class="receive coupon-item-received" :style="{color:'#FF7104',border:'1px solid'+textColor}" v-else>已领取</view>
								</view>
								
							</view>
						</view>
					</view>
				</view>
			</scroll-view>
		</com-bottom-popup>
		
		<com-bottom-popup :show="popupShow" @close="hidePopup">
			<view class="tui-popup-box" style="border-radius: 20rpx;">
				<view class="tui-product-box tui-padding" v-if="goodsData.use_attr == 1">
					<image :src="selectData.pic_url?selectData.pic_url:goodsData.cover_pic" class="tui-popup-img"></image>
					<view class="tui-popup-price">
						<view class="tui-amount tui-bold" :style="{color:'#FF7104'}" v-if="is_seckill==0">¥{{selectData.price}}</view>
						<view class="tui-amount tui-bold" :style="{color:'#FF7104'}" v-if="is_seckill==1">¥{{selectData.operating_expenses_price}}</view>
						<view class="tui-number">已选:{{strName}}</view>
						<view class="tui-number" v-if="is_seckill==0">库存:{{selectData.stock}}</view>
					</view>
				</view>
				<view v-else class="tui-product-box tui-padding">
					<image :src="goodsData.cover_pic" class="tui-popup-img"></image>
					<view class="tui-popup-price" v-if="goodsData.attr_list">
						<view class="tui-amount tui-bold" :style="{color:'#FF7104'}">¥{{goodsData.attr_list[0].price}}</view>
						<view class="tui-number">已选:{{goodsData.attr_list[0].attr_list[0].attr_name}}</view>
					</view>
				</view>
				<scroll-view scroll-y class="tui-popup-scroll">
					<view class="tui-scrollview-box">
					
						<view>
							<view v-if="!goodsData.use_attr">
								<block v-if="goodsData.attr_list">
									<view class="tui-bold tui-attr-title">{{goodsData.attr_list[0].attr_list[0].attr_group_name}}</view>
									<view class="tui-attr-box">
										<view class="tui-attr-item tui-attr-active" :style="{color:'#FF7104'}">
											{{goodsData.attr_list[0].attr_list[0].attr_name}}
										</view>
									</view>
								</block>
							</view>
							<view v-for="(item,index) in goodsData.attr_groups" :key='index' v-else>
								<view class="tui-bold tui-attr-title">{{item.attr_group_name}}</view>
								<view class="tui-attr-box">
									<view class="tui-attr-item" @tap="specCol(item.attr_group_id,items.attr_id,items)" :class="queryAttr(items) ?'tui-attr-active':''"
									 :style="{color:queryAttr(items)?'#FF7104':'',border:queryAttr(items)?'1px solid'+'#FF7104':''}" v-for="(items,indexs) in item.attr_list"
									 :key='indexs'>
										{{items.attr_name}}
									</view>
								</view>
							</view>
						</view>
					
						<view class="tui-number-box tui-bold tui-attr-title">
							<view class="tui-attr-title">数量</view>
							<tui-numberbox :max="9999999999999999" :min="1" :value="value" @change="change"></tui-numberbox>
						</view>

					</view>
				</scroll-view>
				<view class="tui-operation tui-operation-right tui-right-flex tui-popup-btn">
					<view class="sure-btn" :style="{background:'#FF7104'}" @tap="determine">确定</view>
					
				</view>
				<view class="tui-icon tui-icon-close-fill tui-icon-close" style="color: #999;font-size:16pt" @tap="hidePopup"></view>
			</view>
		</com-bottom-popup>

		<view class="goods-qrcode-modal" v-if="showPoster">
			<view class="goods-qrcode-body flex-col">
				
				<view class="goods-qrcode2 flex flex-y-center flex-x-center">
					<view class="codeImg_box">
						<!-- #ifdef H5 -->
						<view class="goods-qrcode-box">
							<image :src="poster_url" class="goods-qrcode" mode='aspectFit'></image>
						</view>
						<!-- #endif -->
						
						<!-- #ifdef MP-WEIXIN -->
						<view class="goods-qrcode-box" @longpress="saveImage(poster_url)">
							<image :src="poster_url" class="goods-qrcode" mode='aspectFit'></image>
						</view>
						<!-- #endif -->
						
						<!-- #ifdef APP-PLUS -->
						<view class="goods-qrcode-box" @longpress="appSaveImg(poster_url)">
							<image :src="poster_url" class="goods-qrcode" mode='aspectFit'></image>
						</view>
						<!-- #endif -->
					</view>
					<view class="saveCode-btn">长按图片保存至本地</view>
				</view>
				
				<view class="goods-qrcode-close" @click="poster(-1)">
					<view style="width: 50rpx;height: 50rpx;text-align: center;background-color: #ADADAD;color: #FFFFFF;border-radius: 50%;line-height: 50rpx;"
					 class="iconfont icon-guanbi"></view>
				</view>
			</view>
		</view>

	
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
	import tuiNumberbox from "@/components/numberbox/numberbox"
	import jyfParser from "@/components/jyf-parser/jyf-parser";
	import info from '@/components/shop/info';
	export default {
		components: {
			tuiIcon,
			jxTag,
			tuiBadge,
			tuiButton,
			tuiTopDropdown,
			tuiNumberbox,
			jyfParser,
			jxRate,
			info
		},
		data() {
			return {
				img_url: this.$api.img_url,
				plugins_img_url: this.$api.plugins_img_url,
				is_index: 1, //1是加入购物车，2是立即下单
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
				is_show_cart: true,
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
					},
					
				],
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
				textColor: '#bc0100',
				couponImg: '',
				server_img:'',//客服图片

				sign: '', // 用来判断是过来的
				related_user_id: '', //是短视频过来的需要带的参数

				// 猜你喜欢数据
				productData: [],
				displayStyle: 1, //1为常规 2为边框 3为居中显示 4为边框居中
				listStyle: 2, //1为大图 2为一行一个 3为一行两个 4一行三个 5为左右滑动
				wx_nav_id:'',
				mch:{},//店铺信息
				is_mch:0,//是否有店铺 1有
				mch_baopin_id:'',//爆品id
				serviceLink:'',
				showFoucs:false,
				is_buy_power:'',
				foucsID:'',//关注ID
				is_seckill:0,
				expired_at:'',
				// iTimer:null
			}
		},
		onLoad(options) {

			this.beforeOnLoad(options);
			
			this.getService()
			this.textColor = this.globalSet('textCol');
			this.couponImg = this.globalSet('couponImg');
			// #ifdef MP-WEIXIN || APP-PLUS
			this.wx_nav_id = options;
			// #endif
			this.server_img = JSON.parse(uni.getStorageSync('mall_config')).mall_setting.setting.web_service_pic;

			let {
				proId,
				pid,
				source
			} = options;

			if (pid) {
				uni.setStorageSync("pid", pid);
				uni.setStorageSync("user_id", pid);
			}
			if (source) {
				uni.setStorageSync("source", source);
			}

			this.proId =options.proId;
			if (options.attr_id) {
				this.c_attr_id = options.attr_id;
			}
			if (options.video) {
				this.is_show_cart = false;
			}
			this.sign = options.sign || '';
			this.related_user_id = options.related_user_id || '';

			this.getGoodsDetail();
			this.getComment();
			this.getRecommend();
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
			//#ifdef H5
			var user_id = this.$route.query.pid2 !== undefined ? this.$route.query.pid2 : 0;
			if(Array.isArray(user_id)){
				user_id = user_id[0];
			}else{
				user_id = this.$route.query.pid2 !== undefined ? this.$route.query.pid2 : 0;
			}
			if(this.$route.query.pid2 !== undefined){
				uni.setStorageSync('user_id',user_id);
			}
			//#endif
			if(options.mch_baopin_id){
				this.mch_baopin_id=options.mch_baopin_id
			}else{
				this.mch_baopin_id=0
			}
		},
		onShow() {
			this.getCartList();
			
			let routes = getCurrentPages(); // 获取当前打开过的页面路由数组
			let curRoute = routes[routes.length - 1].route //获取当前页面路由
			let curParam = routes[routes.length - 1].options; //获取路由参数
			if(curParam.pid) {
				uni.setStorageSync("pid", curParam.pid);
			}
			
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
				return temp;
			}
		},
		methods: {
			enlarge(index){ //点击主图放大
				  let photoList = this.goodsData.pic_list.map(item => {
				                    return item.pic_url;
				      });
				                uni.previewImage({
				                    current: index,     // 当前显示图片的链接/索引值
				                    urls: photoList,    // 需要预览的图片链接列表，photoList要求必须是数组
				                    loop:true   // 是否可循环预览
				                });
			},
			foucusInfo(){
				uni.navigateTo({
					url: '/pages/diy/diy?page_id=114'
				});
			},
			getService() {
				this.$http
					.request({
						url: this.$api.moreShop.getservice,
						method: 'POST',
					})
					.then(res => {
						if (res.code == 0) {
							this.serviceLink = res.data
						}
					});
			},
			saveImage(url) { //保存图片
				var that = this;
				uni.authorize({
					/* 这个就是保存相册的 */
					scope: 'scope.writePhotosAlbum',
					success() {
						/* 保存图片方法 */
						that.saveImg(url);
					},
					complete(res) {
						/* 这里判断一下如果没有授权重新打开设置选项 */
						uni.getSetting({
							success(res) {
								if (!res.authSetting['scope.writePhotosAlbum']) {
									/* 打开设置的方法 */
									that.opensit();
								}
							}
						});
					}
				});
			},
			opensit() {
				uni.showModal({
					content: '由于您还没有允许保存图片到您相册里,请点击确定去允许授权',
					success: function(res) {
						if (res.confirm) {
							/* 这个就是打开设置的API*/
							uni.openSetting({
								success(res) {
									console.log(res.authSetting);
								}
							});
						} else if (res.cancel) {
							uni.showModal({
								cancelText: '依然取消',
								confirmText: '重新授权',
								content: '很遗憾你点击了取消，请慎重考虑',
								success: function(res) {
									if (res.confirm) {
										uni.openSetting({
											success(res) {
												console.log(res.authSetting);
											}
										});
									} else if (res.cancel) {
										console.log('用户不授权');
									}
								}
							});
						}
					}
				});
			},
			saveImg(url) {
				var that = this;
				/* 获取图片信息 */
				uni.getImageInfo({
					src: url,
					success: function(image) {
						/* 保存图片到相册 */
						uni.saveImageToPhotosAlbum({
							filePath: image.path,
							success: function() {
								that.$http.toast('保存成功');
							},
							fail(res){
								that.$http.toast('保存失败,请稍后重试');
							}
						});
					}
				});
			},
			appSaveImg(url){
				let that = this;
				/* 保存图片到相册 */
				uni.saveImageToPhotosAlbum({
					filePath: url,
					success: function() {
						that.$http.toast('保存成功');
					},
					fail(res){
						that.$http.toast('保存失败,请稍后重试');
					}
				});
			},
			customerService() { //跳转客服
				//#ifdef H5
				location.href = this.serviceLink
				//#endif
				// #ifdef APP-PLUS
				plus.runtime.openURL(this.serviceLink);//成功跳转了
				//#endif
			},
			getRecommend() { //获取推荐商品
				this.loading = true;
				this.$http.request({
					url: this.$api.goods.recommend,
					data: {
						page: 1,
						tyep: 'goods'
					}
				}).then((res) => {
					this.loading = false;
					if (res.code == 0) {
						this.productData = res.data.list;
					}
				})
			},
			// 这就是分享海报的方法
			poster(key) {
				if (key == -1) {
					this.showPoster = false;
					return;
				}
				this.loading = true;
				if (this.poster_url) {
					setTimeout(() => {
						this.loading = false;
						this.showPoster = true;
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
					if (res.code == 0) {
						this.showPoster = true;
						this.poster_url = res.data.pic_url;
						setTimeout(() => {
							this.loading = false;
						}, 1000)
					}else{
						this.loading = false;
					}
				})
			},

			determine() { //点击确定按钮，判断是加入购物车还是立即下单
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
				let that = this;
				if(this.goodsData.order_prompt && this.goodsData.order_prompt == 1){
					uni.showModal({
						title: '提示',
						content: this.goodsData.order_prompt_content,
						showCancel: false,
						confirmText: '我已知晓',
						success: function (res) {
							that.determine_ex();
						}
					});
				}else{
					that.determine_ex();
				}
				
			},
			determine_ex(){
				this.popupShow = false;
				if (this.is_index == 1) { //加入购物车
					//获取选中的多规格名字进行拼接显示
					var arr = [];
					this.selectArr.forEach((item) => {
						arr.push(item.attr_name);
					})
					this.strName = arr.join('， ');
					this.$http.request({
						url: this.$api.cart.addCart,
						method: 'post',
						showLoading: true,
						data: {
							goods_id: this.proId,
							attr: this.goodsData.attr_groups ? this.selectData.id : this.goodsData.attr_list[0].id,
							num: this.value,
							mch_id: 0,
							mch_baopin_id:this.mch_baopin_id,
							buy_now:0,//加入购物车传0
						}
					}).then((res) => {
						if (res.code == 0) {
							this.cartList_num = res.data.cart_num
							this.$http.toast('购物车加入成功');
						} else {
							this.$http.toast(res.msg);
						}
					})
				} else if (this.is_index == 2) { //立即下单
					this.popupShow = false;
					var goods_attr_id = this.goodsData.attr_groups ? this.selectData.id : this.goodsData.attr_list[0].id;
					var mch_id=0
					var is_mch=this.is_mch
					if(is_mch==1){
						var mch=this.mch
						var mch_id=mch['mch_id']
					}
					uni.setStorage({
						key: 'orderData',
						data: [{
							num: this.value,
							goods_attr_id: goods_attr_id,
							id: this.proId,
							cart_id: 0,
							mch_id:mch_id
						}],
						fail() {
							console.log('存入本地失败');
						}
					});
					this.$http.request({
						url: this.$api.cart.addCart,
						method: 'post',
						showLoading: true,
						data: {
							goods_id: this.proId,
							attr: this.goodsData.attr_groups ? this.selectData.id : this.goodsData.attr_list[0].id,
							num: this.value,
							mch_id: 0,
							mch_baopin_id:this.mch_baopin_id,
							buy_now:1,//立即下单传1
						}
					}).then((res) => {
						if (res.code == 0) {
							
							var url = '';
							if (this.sign == 'short_video') {
								url = `/pages/order/submit?sign=${this.sign}&related_user_id=${this.related_user_id}&mch_id=${mch_id}`;
							} else {
								url = '/pages/order/submit';
							}
							// #ifdef H5
							if(this.is_seckill==0){
									url = url + '?nav_id=' + JSON.stringify([{id:this.$route.query.proId,num:this.value}])+'&mch_id='+mch_id+"&user_address_id=0"+"&use_score=0"+"&use_integral=0"+"&list="+String(res.data.cart_id)
							}
							if(this.is_seckill!=0){
									url = url + '?nav_id=' + JSON.stringify([{id:this.$route.query.proId,num:this.value}])+'&mch_id='+mch_id+"&user_address_id=0"+"&use_score=1"+"&use_integral=0"+"&list="+String(res.data.cart_id)
							}
							
							// url = url + '?nav_id=' + JSON.stringify([{id:this.$route.query.proId,num:this.value}])+'&mch_id='+mch_id+"&user_address_id=0"+"&use_score=0"+"&use_integral=0"+"&list="+String(res.data.cart_id)
							     
							// #endif
							
							// #ifdef MP-WEIXIN || APP-PLUS
							
							if(this.is_seckill==0){
								url = url + '?nav_id=' + this.wx_nav_id.proId+'&mch_id='+mch_id+"&user_address_id=0"+"&use_score=0"+"&use_integral=0"+"&list="+String(res.data.cart_id)	
							}
							if(this.is_seckill!=0){
								url = url + '?nav_id=' + this.wx_nav_id.proId+'&mch_id='+mch_id+"&user_address_id=0"+"&use_score=1"+"&use_integral=0"+"&list="+String(res.data.cart_id)
							}
							
							
							// url = url + '?nav_id=' + this.wx_nav_id.proId+'&mch_id='+mch_id+"&user_address_id=0"+"&use_score=0"+"&use_integral=0"+"&list="+String(res.data.cart_id)
							// #endif
							
							uni.navigateTo({
								url
							})
							
						} else {
							this.$http.toast(res.msg);
						}
					})
				}
			},
			getCartList() { //获取购物车列表
				this.$http.request({
					url: this.$api.cart.list,
					method: 'GET'
				}).then((res) => {
					if (res.code == 0) {
						this.cartList = res.data.list;
						this.cartList_num = this.cartList.length;
						if(res.wechat_subscribe==1){
							this.showFoucs=false
						}else{
							this.showFoucs=true
						}
					}else{
						this.showFoucs=true
					}
				})
			},
			receiveCoupon(index, id) { //领取优惠券
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
			getComment() { //请求评论数据
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
			getGoodsDetail() { //请求商品详情数据
				this.loading = true;
				this.$http.request({
					url: this.$api.goods.detail,
					data: {
						id: this.proId
					},
				}).then((res) => {
					this.loading = false;
					if (res.code == 0) {
						this.goodsData = res.data.goods;
						
						// if(iTimer != null){
						// 	clearInterval(iTimer);
						// }
						let that=this
						this.iTimer = setInterval(function(){
							that.expired_at= that.countdownFun(res.data.goods.surplus_time);
						}, 1000);
						
			
						this.is_seckill=this.goodsData.is_seckill
						console.log(this.is_seckill)
						if(this.is_seckill==0){
							this.topMenu= [{
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
							},
							
							]
						}else{
							this.topMenu= [{
								icon: "home",
								text: "首页",
								size: 23,
								badge: 0
							}, {
								icon: "people",
								text: "我的",
								size: 26,
								badge: 0
							}							
							]
						}
					
						
						
						
						
						
						
						
						
						
						
						
						
						
						this.foucsID=this.goodsData.collect.collect_id
						this.is_buy_power=res.data.is_buy_power
						if(res.data.goods.shopping_voucher.is_shopping_voucher_goods==1){
							uni.redirectTo({
								url:'../../coupon/detail?proId='+this.proId
							})
						}
						//#ifdef H5
						this.$wechatSdk.share("pages/goods/detail?proId="+this.proId, {
							app_share_title: res.data.goods.name,
							app_share_desc: res.data.product,
							app_share_pic: res.data.goods.cover_pic,
						});	
						//#endif
						
			
						
						
						
						
						
						
						
						
						if(this.goodsData.video_url){
							this.bannerLength = this.goodsData.pic_list.length + 1;
						}else{
							this.bannerLength = this.goodsData.pic_list.length;
						}
						
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
						this.mch=res.data.mch
						this.is_mch=res.data.is_mch
					} else {
						uni.showModal({
							content: res.msg,
							showCancel: false,
							confirmColor: '#BC0100',
							success: function(res) {
								if (res.confirm) {
									this.navBack();
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

				this.skuCommon();
			},
			skuCommon() {
				this.selectArr.forEach(item => this.strs.push(item.attr_id));
				console.log(this.selectArr);
				console.log(this.goodsData);
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
				console.log('/////');
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
							// type: 'goods',
							// id: this.goodsData.collect.collect_id
							id:this.foucsID
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
							// this.getGoodsDetail();
							this.foucsID=res.data.id
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
				if(this.goodsData.video_url){
					var ctx = uni.createVideoContext('swiperVideo', this);
					if(e.detail.current == 0){
						ctx.play();
					}else if(e.detail.current == 1){
						ctx.pause();
					}
				}
				this.bannerIndex = e.detail.current
			},
			back() {
				this.navBack();
			},
			openMenu: function() {
				this.menuShow = true
			},
			closeMenu: function() {
				this.menuShow = false
			},
			showPopup(num) {
				if(this.is_buy_power==0){
					uni.showModal({
					    title: '提示',
					    content: '会员等级不够，无法购买此商品',
						showCancel:false,
					    success: function (res) {
					        if (res.confirm) {
					            
					        }
					    }
					});
				}else{
					this.popupShow = true;
					if (num) {
						this.is_index = num;
					}
				}
			},
			change: function(e) {
				this.value = e.value;
			},
			common: function(index) {
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
						//带商品ids
						uni.navigateTo({
							url: '/pages/goods/commentList?proId=' + this.proId
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
			countdownFun(expired_at){
				var date = new Date();
				var timestamp =parseInt(date.getTime()/1000);
				var text = "";
				let time =(expired_at-timestamp);;
				if(time <= 0){
					text = '0'+'天'+'0'+"时"+'0'+'分'+'0'+'秒';
				}else{
					let days, hours, minutes, seconds;
					days 	= parseInt(time / 60 / 60 / 24 , 10); //计算剩余的天数
					hours 	= parseInt(time / 60 / 60 % 24 , 10) < 10 ? "0" + parseInt(time / 60 / 60 % 24 , 10) : parseInt(time  / 60 / 60 % 24 , 10); //计算剩余的小时 
					minutes = parseInt(time / 60 % 60, 10)<10 ? "0" + parseInt(time / 60 % 60, 10) : parseInt(time / 60 % 60, 10);//计算剩余的分钟 
					seconds = parseInt(time % 60, 10)<10 ? "0" + parseInt(time  % 60, 10) : parseInt(time  % 60, 10);//计算剩余的秒数 								
					text = days + "天" + hours + "时" + minutes + '分' + seconds + "秒";
				}
				return text;
			}
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
		bottom: 40rpx;
		right: 40rpx;
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
		height: 50px;
		padding-top: 10rpx;
		display: flex;
		align-items: center;
			/* #ifdef MP*/
		margin-top: 60rpx;
		/* #endif */
		/* #ifdef APP-PLUS */
		margin-top: 80rpx;
		/* #endif */
		margin-bottom: 20rpx;
	}

	.tui-top-dropdown {
		z-index: 9999 !important;
	}

	.tui-menu-itembox {
		color: #fff;
		padding: 0 10rpx;
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
		padding-top: 0rpx;
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
		padding: 30rpx 0 0;
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
		padding: 0 90rpx 20rpx 30rpx;
		box-sizing: border-box;
	}

	.tui-pro-title {
		padding-top: 20rpx;
		color: #272727;
		width: 100%;
		overflow: hidden;
	}
	.tui-pro-title-product{
		padding-top: 20rpx;
		color: #808080;
		font-size: 25rpx;
		width: 100%;
		overflow: hidden;
	}
	.tui-pro-title text{
		display: block;
		user-select: text;
		-webkit-user-select: text;
		width: 100%;
		overflow: hidden;
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
		color: #BE0807;
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
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
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

		.red-dots {
			position: absolute;
			top: -8rpx;
			transform: translateX(75%);
			font-size: 7pt;
			line-height: 1;
			padding: 2rpx 6rpx;
			border-radius: 50px;
			background: #ff4850;
			color: #fff;
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
		align-items: center;
		justify-content: center;
		color: #FFFFFF;
		font-size: 11pt;

		&.radius-left {
			border-radius: 100rpx 0 0 100rpx;
			box-sizing: border-box;
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

	// .tui-attr-active::after {
	// 	content: "";
	// 	position: absolute;
	// 	border: 1rpx solid #e41f19;
	// 	width: 100%;
	// 	height: 100%;
	// 	border-radius: 10rpx;
	// 	left: 0;
	// 	top: 0;
	// }

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
		color: #BC0100;
		border: 2rpx solid #BC0100;
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
	.goods-qrcode2{
		position: relative;
		height: 1300rpx;
		flex-direction: column;
		margin-top: 40rpx;
	}
	.codeImg_box {
		width: 92%;
		height: 82%;
		margin-bottom: 20rpx;
	}
	.saveCode-btn{
		color: #939292;
		padding: 10px 20px;
		border-radius: 10px;
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

	/* 猜你喜欢 */
	.guessLike {
		text-align: center;
		font-weight: 700;
		font-size: 12pt;
		color: #000000;
		padding: 50rpx 0;
	}
	.seckill-time{width: 100%;height: 140rpx;padding: 10rpx 20rpx;
	box-sizing: border-box;background: rgb(255,71,83);
	display: flex;justify-content: space-between;}
</style>
