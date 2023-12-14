<template>
	<view class="root">
		
		<!-- #ifdef MP-WEIXIN -->
		<com-nav-bar title="兰亭名品" :fixed="true" :statusBar="true"></com-nav-bar>
		<!-- #endif -->
		
		<!-- #ifdef H5 -->
		<view class="foucs_H5" style="width: 100%;height: 96rpx;background:#3e4144;position: fixed;top: 0rpx;left: 0;z-index: 999;" v-if="showFoucs">
			<image :src="img_url+'/fillShop.png'" mode="" style="width: 100rpx;height: 96rpx;display: block;float: left;"></image>
			<view class="foucs_H5_messga" style="float: left;">
				<text style="display: block;font-size: 25rpx;color: #fff;margin-top: 8rpx;">欢迎访问兰亭名品</text>
				<text style="display: block;font-size: 25rpx;color: #fff">点击关注我们哦~</text>
			</view>
			<view class="foucs_check" style="height:100%;float: right;display:flex;align-items: center;">
				<button type="default" style="width: 130rpx;height: 50rpx;text-align: center;font-size: 25rpx;
				background: rgb(255, 113, 4);color: #fff;line-height: 50rpx;"
				@click='foucusInfo'>关注</button> 
				<text @click="showFoucs = false" style="color:#fff;margin:0 20rpx;font-size:28rpx;border-left:1px solid #aaa;padding-left:20rpx;">关闭</text>
			</view>
		</view><strong></strong>
		<!--#endif -->	
		
		<!-- #ifdef H5 || APP-PLUS--> 
		<view class="status_bar"></view> 
		 <!--#endif -->
		 
		<!-- #ifdef H5 || APP-PLUS -->
		<view class="customer_service" @click="linkService">
			<image :src="img_url+'/service_logo.png'" mode=""></image>
		</view>
		<!--#endif -->
		<!-- #ifdef MP-WEIXIN -->
		<view class="customer_service">
			<button open-type="contact" :session-from='webapp' class="buttonWeapp" @tap="goService">
				
			</button>
		</view>
		<!--#endif -->
		<block v-for="(item, index) in indexData" :key="index" style="">
			<view class="header" v-if="item.id == 'search'"> 
				<view class="search_box" :style="{position: is_fixed == 1 ? 'relative' : 'fixed' }">
					<!-- <view class="header">
					<diy-header :backgroundCol='receiveColor'></diy-header>
				</view> -->
					<!-- 搜索 -->
					<view class="search" @tap="navTo('/pages/search/search')" style="width:100%">
						<search
							:message="item.data.placeholder"
							:textAlign="item.data.textPosition"
							:frameColor="item.data.background"
							:innerFrameColor="item.data.color"
							:textColor="item.data.textColor"
							:borderRadius="item.data.radius"
						></search>
						<!-- :frameColor="scrollTop>0?item.data.background:receiveColor" 用来做渐变 -->
					</view>
					<!-- 搜索 -->
				</view>
			</view>
			<placeholder v-if="item.id == 'search' && is_fixed == 0" :placeholderHeight="placeholderHeight4" ></placeholder>
			<!-- 轮播图 -->
			<view class="bannersBox" v-if="item.id == 'banner'">
				<banners :bannerData="item.data"></banners>
			</view>
			<!-- 导航按钮 -->
			<nav-icon v-if="item.id == 'nav'" class="navIcon_style" :list="item.data.navs" :textColor="item.data.color" :rowNums="item.data.rows"
			 :background="item.data.background" :listNums="item.data.columns"></nav-icon>
			<!-- 导航按钮 -->
			<!-- 轮播图 -->

			<!-- 公告 -->
			<notice v-if="item.id == 'notice'" :value="item.data"></notice>
			<!-- 公告 -->

			<!-- 倒计时 -->
			<diy-timer v-if="item.id == 'timer'" :value="item.data"></diy-timer>
			
			<!-- 好店推荐 -->
			<diy-mch v-if="item.id == 'mch'" :value="item.data"></diy-mch>

			<view class="content">
				<placeholder v-if="item.id == 'empty'" :placeholderHeight="placeholderHeight"></placeholder>
				<!-- 视频组件 -->
				<diy-video v-if="item.id == 'video'" :value="item.data"></diy-video>
				<!-- 视频组件 -->

				<!-- 图文详情 -->
				<diy-image-text v-if="item.id == 'image-text'" :value="item.data"></diy-image-text>
				<!-- 图文详情 -->

				<!-- 广告弹窗 -->
				<diy-modal v-if="item.id == 'modal'" :modalData="item.data"></diy-modal>
				<!-- 广告弹窗 -->
				<!-- 专题 -->
				<view class="thematicBox" v-if="item.id == 'topic'">
					<thematic :topicData="item.data" @click="topicNavTo"></thematic>
				</view>
				<!-- 专题 -->
				<!-- 图片魔方 -->
				<view class="adBoxs" v-if="item.id == 'rubik'">
					<!-- <view class="ad1">图片广告一</view> -->
					<rubik :types="item.data.style" :image-data="item.data.list" :hotspotData="item.data.hotspot"></rubik>
				</view>
				<!-- 图片魔方 -->

				<!-- 商品 -->
				<view v-if="item.id == 'goods'" style="background: #FFFFFF;border-radius: 10rpx;">
					<commodity :listStyle="item.data.listStyle" :showGoodsName="item.data.showGoodsName" :showGoodsOriginalPrice="item.data.showGoodsOriginalPrice"
					 :showGoodsPrice="item.data.showGoodsPrice" :showGoodsLevelPrice="item.data.showGoodsLevelPrice"
					 :originalPriceLabel="item.data.originalPriceLabel" :priceLabel="item.data.priceLabel" :levelPriceLabel="item.data.levelPriceLabel"
					 :showBuyBtn="item.data.showBuyBtn" :subscriptIcon="item.data.goodsTagPicUrl" :showGoodsTag="item.data.showGoodsTag"
					 :buyBtnStyle="item.data.buyBtnStyle" :buyBtns="item.data.buyBtn" :buyBtnText="item.data.buyBtnText" :displayStyle="item.data.goodsStyle"
					 :productData="item.data.list" :buyBtnPic="item.data.buyBtnPic" :buttonColor="item.data.buttonColor"></commodity>
				</view>
				<!-- 商品 -->
			</view>
		</block>
		
		<!-- 购物券列表 -->
		<vouchers :page="page"></vouchers>
		
		<!-- 
		<citySelect @back_city="back_city" v-if="cityselec"></citySelect>
		-->
		
		
		<backTop :src="backTop.src"  :scrollTop="backTop.scrollTop"></backTop>
		
		
		
		<!--加载loadding-->
		<!-- 版权 -->
		<diy-copyright v-if="copyright.status == 1" :value="copyright"></diy-copyright>
		<view class="ap-link" @click="navToLink()"
		style="width: 100%;height: 30rpx;margin-bottom: 30rpx;text-align: center;font-size: 25rpx;">		
					粤ICP备19145623号
		</view>
			
		<!-- 版权 -->
		<view style="height: 100rpx;"></view>
		<!-- 导航栏 -->
		<!-- #ifdef H5||MP-WEIXIN   -->
		<main-tabbar v-if='modelSHOw'></main-tabbar>
		<!--#endif -->	
		<!-- #ifdef APP-PLUS  -->
		<main-tabbar></main-tabbar>
		<!--#endif -->	
		<!-- 导航栏 -->
		
		<!-- <navigator url="../business-card/client/index">跳转名片</navigator> -->
	</view>
</template>

<script>
	import diyHeader from '@/components/commodity/header.vue';
	import seckillSlider from '@/components/commodity/seckill-slider.vue';
	import search from '@/components/search/search.vue';
	import banners from '@/components/banners/banners';
	import navIcon from '@/components/navIcon/navIcon';
	import rubik from '@/components/rubik/rubik';
	import commodity from '@/components/commodity/commodity';
	import groupBuy from '@/components/groupBuy/groupBuy';
	import thematic from '@/components/thematic/thematic.vue';
	import placeholder from '@/components/placeholder/placeholder.vue';
	import orderPopup from '@/components/order-popup/order-popup.vue';
	import coupon from '@/components/coupon/coupon';
	import tuiScrollTop from '@/components/scroll-top/scroll-top';
	// import pendant from "@/components/pendant/pendant.vue";
	import ceiling from '@/components/ceiling/ceiling.vue';
	import moreGoods from '@/components/moreGoods/moreGoods.vue';
	import tuiFab from '@/components/tui-fab/tui-fab';
	import notice from '@/components/notice/notice.vue';
	import diyImageText from '@/components/diy/diy-image-text.vue';
	import diyCopyright from '@/components/diy/diy-copyright.vue';
	import diyMap from '@/components/diy/diy-map.vue';
	import diyModal from '@/components/diy/diy-modal.vue';
	import vouchers from '@/components/vouchers.vue';
	import citySelect from '@/components/linzq-home/linzq-city.vue';
	import backTop from '@/components/back-top/back-top.vue';
	import diyTimer from '@/components/diy/diy-timer.vue';
	import diyMch from '@/components/diy/diy-mch.vue';
	//#ifdef H5 
		var jweixin = require('jweixin-module');
	//#endif
	import {
		isEmpty
	} from '../../common/validate.js';
	export default {
		components: {
			diyTimer,
			diyMch,
			search,
			banners,
			navIcon,
			rubik,
			commodity,
			groupBuy,
			thematic,
			placeholder,
			coupon,
			seckillSlider,
			diyHeader,
			orderPopup,
			tuiScrollTop,
			// pendant,
			ceiling,
			moreGoods,
			tuiFab,
			notice,
			diyImageText,
			diyCopyright,
			diyMap,
			diyModal,
			vouchers,
			backTop,
			citySelect
		},
		data() {
			return {
				img_url: this.$api.img_url,
				plugins_img_url: this.$api.plugins_img_url,
				loading: false,

				top: 0,
				left: 0,
				right: 30,
				bottom: 300,
				bgColor: '#5677fc',
				btnList: [],
				statusHeight: 0, //状态栏高度
				suspendedList: [{
						bgColor: '#16C2C2',
						//图标/图片地址
						imgUrl: '/static/images/fab/fab_about.png',
						//图片高度 rpx
						imgHeight: 64,
						//图片宽度 rpx
						imgWidth: 64,
						//名称
						text: '关于',
						//字体大小
						fontSize: 34,
						//字体颜色
						color: '#fff'
					},
					{
						bgColor: '#64B532',
						imgUrl: '/static/images/fab/fab_share.png',
						imgHeight: 64,
						imgWidth: 64,
						text: '分享',
						fontSize: 34,
						color: '#fff'
					},
					{
						bgColor: '#FFA000',
						imgUrl: '/static/images/fab/fab_reward.png',
						imgHeight: 64,
						imgWidth: 64,
						text: '联系客服',
						fontSize: 34,
						color: '#fff'
					}
				],

				fromTop: 0, //获取元素距离顶部高度
				receiveColor: '', //接收轮播图组件穿过来的背景色
				timer: '', //延时器变量
				oldScrollTop: 0, //记录上一次滚动结束后的滚动距离
				scrollTop: 0, // 记录当前的滚动距离
				searchHeight: 0, //搜索框高度
				// 回到顶部参数
				scrollTop: 0,
				isAllCat: 0,

				//占位符参数
				placeholderHeight: 10,
				placeholderHeight4: 80, //80,

				//吸顶组件参数
				styleBool: true,
				t_page: 1,

				// 搜索参数
				is_fixed: 0, //暂时用来控制搜索框是否固定定位

				// 气泡弹窗
				orderPopupData: [{
						pic_url: '',
						name: '哈哈哈哈',
						time: 3
					},
					{
						pic_url: '',
						name: 'dense1',
						time: 3
					},
					{
						pic_url: '',
						name: 'dense2',
						time: 3
					}
				],

				//团购商品数据
				g_listStyle: 2, //1为大图 2为一行一个 3为一行两个
				g_displayStyle: 1, //1为常规 2为边框 3为居中显示 4为边框居中
				g_productNameBool: true, //商品名，true为显示，false为不显示
				g_priceBool: true, //商品价格，true为显示，false为不显示
				g_buyBtnBool: true, //购物车按钮，true为显示，false为不显示
				g_buyBtnStyle: 3, //购物车按钮样式。3为文字样式1 4为文字样式2 5为文字样式3 6为文字样式4
				g_subscriptBool: true, //角标，true为显示，false为不显示
				g_subscriptStyle: 1, //角标样式。1为热销 2为新品 3为折扣 4为推荐 5为自定义
				g_subscriptIcon: 'http://store.hzstorm.com/web/statics/mch/images/diy/time.png', //角标为自定义的时候才需要传
				//导航栏数据
				tabCurrent: 0,
				selectedColor: '#bc0100',
				// 秒杀商品列表
				seckillGoods: [{
						img: 'http://img12.360buyimg.com/n1/jfs/t1/111874/33/3368/122650/5ea7bfb4Ea097bc8e/04b759fb100461ae.jpg',
						des: '这是第一个描述',
						price: 498,
						curPrice: 298
					},
					{
						img: '//img20.360buyimg.com/mobilecms/s255x255_jfs/t1/116052/20/3382/139411/5ea7bfb2Ea3bf2dda/722cb20b6dff28a2.jpg!cc_255x255.webp',
						des: '这是第二个描述',
						price: 598,
						curPrice: 398
					},
					{
						img: '//img12.360buyimg.com/n1/jfs/t1/115076/4/3420/174848/5ea7bfb1E5f015ad3/73f770f69cea5571.jpg',
						des: '这是第三个描述',
						price: 711,
						curPrice: 311
					},
					{
						img: '//m.360buyimg.com/babel/s333x333_jfs/t1/33728/10/12924/112345/5cf61386E2db7f1f0/81f13416b1522681.jpg!q70.dpg',
						des: '这是第一个描述',
						price: 698,
						curPrice: 398
					},
					{
						img: 'http://m.360buyimg.com/babel/s333x333_jfs/t1/33728/10/12924/112345/5cf61386E2db7f1f0/81f13416b1522681.jpg!q70.dpg',
						des: '这是第二个描述',
						price: 512,
						curPrice: 312
					},
					{
						img: '//img12.360buyimg.com/n1/jfs/t1/115076/4/3420/174848/5ea7bfb1E5f015ad3/73f770f69cea5571.jpg',
						des: '这是第三个描述',
						price: 423,
						curPrice: 223
					}
				],
				copyright: '', //版权数据
				indexData: '',
				serviceLink: '',
				webapp: {
					nickName: "",
					avatarUrl: '',
					province: '',
					city: '',
					pageUrl: '',
					pageTitle: '',
					phone: '',
				},
				modelSHOw:true,
				showFoucs:false,
				page_count:'',
				page:1,
				
				goods_ist:[],
				backTop: {
					src: '../../static/back-top/top.png',
					scrollTop: 0
				},
				city: '',
				cityselec:false,
				loadding: false,
				pullUpOn: true,
				loading: false,
			};
		},
		onShow() {
			this.getData('show');
			let routes = getCurrentPages(); // 获取当前打开过的页面路由数组
			let curRoute = routes[routes.length - 1].route //获取当前页面路由   //   webview/webview
			let curParam = routes[routes.length - 1].options; //获取路由参数     //   {"userId":"1000007"}
			if (typeof curParam['pid'] != "undefined" && curParam.pid) {
				uni.setStorageSync('pid', curParam.pid);
				uni.setStorageSync("user_id", curParam.pid);
			}
			if(uni.getStorageSync("shopCity")){
				uni.removeStorageSync("shopCity")
			}
			if(uni.getStorageSync("current")){
				uni.removeStorageSync("current")
			}
//---------------------------------------------------------------首页进来绑定上下级
			//#ifdef MP-WEIXIN||H5
			this.getCartList()
			//#endif
//--------------------------------------------------------------------------						
		},
		onReachBottom() {
			//上拉加载
			this.t_page++;
			this.page++;
		},
		onLoad(options) {
			this.getmyLOcation()
			this.beforeOnLoad(options);
			
			if (options.pid) {
				uni.setStorageSync('pid', options.pid);
				uni.setStorageSync("user_id", options.pid);
			}
			if (uni.getStorageSync('mall_config')) {
				this.copyright = JSON.parse(uni.getStorageSync('mall_config')).copyright;
			}
			this.getData();
			this.btnList = [...this.suspendedList];
			//#ifdef MP-WEIXIN
			this.checkUpdateVersion();
			//#endif
			this.getService()
			if(uni.getStorageSync('userInfo')){
				let userMessage=JSON.parse(uni.getStorageSync('userInfo'))
				this.webapp=JSON.stringify(
					{
						nickName:userMessage.nickname,
						avatarUrl:userMessage.avatar,
						province:'',
						city:'',
						pageUrl:'pages/index/index',
						pageTitle:'',
						phone:userMessage.mobile,
					}
				)
			}
			//#ifdef H5 || APP-PLUS
				this.$wechatSdk.initJssdk(function(signData){
					
				}); 
			//#endif
			/* 
			uni.navigateTo({
				url: '/pages/personalCentre/ercode/payPages/payPages?id=262'
			});
			return; */
			
			let that = this;
			uni.getSystemInfo({
				success(res) {
					that.top = (res.statusBarHeight/res.screenWidth) * 750;
				}
			});
		},
		methods: {
			getmyLOcation() {
				let that = this				
				//#ifdef H5
				that.$unifylocation.locationH5()
				setTimeout(() => {
					if(uni.getStorageSync('x-longitude-new'),uni.getStorageSync('x-latitude-new')){
						uni.setStorageSync('x-longitude',uni.getStorageSync('x-longitude-new'))
						uni.setStorageSync('x-latitude',uni.getStorageSync('x-latitude-new'))
					}
				}, 500)
				// #endif
				
				// #ifndef H5
				that.$unifylocation.locationMp()
				setTimeout(() => {
					if(uni.getStorageSync('x-longitude-new'),uni.getStorageSync('x-latitude-new')){
						uni.setStorageSync('x-longitude',uni.getStorageSync('x-longitude-new'))
						uni.setStorageSync('x-latitude',uni.getStorageSync('x-latitude-new'))
					}
				}, 500)
				// #endif
			},
			
			
			
			
			
			foucusInfo(){
				//window.location.href="https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzI3MTIzMjAyOQ==&scene=#wechat_redirect"
				uni.navigateTo({
					url: '/pages/diy/diy?page_id=114'
				});
			},
			getCartList() { //获取购物车列表
				this.$http.request({
					url: this.$api.cart.list,
					method: 'GET'
				}).then((res) => { 
					if(res.code==0){
						this.modelSHOw=true
						if(res.wechat_subscribe==1){
							this.showFoucs=false
						}else{
							this.showFoucs=true
						}
					}else{
						this.modelSHOw=false
						this.showFoucs=true
					}
				})
			},
			navToLink(){
				window.location.href='http://beian.miit.gov.cn'
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
			goService(){
				if(uni.getStorageSync('userInfo')){
					let userMessage=JSON.parse(uni.getStorageSync('userInfo'))
					this.webapp=JSON.stringify(
						{
							nickName:userMessage.nickname,
							avatarUrl:userMessage.avatar,
							province:'',
							city:'',
							pageUrl:'pages/index/index',
							pageTitle:'',
							phone:userMessage.mobile,
						}
					)
					console.log(this.webapp)
				}
			},
			linkService() {
				//#ifdef H5
				location.href = this.serviceLink
				//#endif
				// #ifdef APP-PLUS
				plus.runtime.openURL(this.serviceLink);//成功跳转了
				//#endif
			},
			// 判断微信版本是否 兼容小程序更新机制API的使用
			checkUpdateVersion() {
				if (uni.canIUse('getUpdateManager')) {
					const updateManager = wx.getUpdateManager();
					updateManager.onCheckForUpdate(function(res) {
						console.log(res.hasUpdate)
						if (res.hasUpdate) {
							updateManager.onUpdateReady(function() {
								updateManager.applyUpdate();
							})
							updateManager.onUpdateFailed(function() {
								uni.showModal({
									title: '已经有新版本喽~',
									content: '请您删除当前小程序，到微信 “发现-小程序” 页，重新搜索打开哦~',
								})
							})
						}
					})
				} else {
					uni.showModal({
						title: '溫馨提示',
						content: '当前微信版本过低，无法使用该功能，请升级到最新微信版本后重试。'
					})
				}
			},
			ceilingSwitch() {
				//吸顶组件点击事件
				this.t_page = 1;
			},
			getData(key) {
				var that = this;
				this.$http
					.request({
						url: this.$api.index2
					})
					.then(res => {
						if (res.code == 0) {
							if (key) {
								// #ifdef  H5
								this.$wechatSdk.initShareUrl(res.data.share_data, 'source=1');
								// #endif
								return;
							}
							this.indexData = res.data.page_data;
							uni.stopPullDownRefresh();
						}
					});
			},
			setStatusBarHeight() {
				//获取状态栏高度
				var that = this;
				uni.getSystemInfo({
					success(res) {
						that.statusHeight = res.statusBarHeight;
					}
				});
			},
			setSearchHeight() {
				this.searchHeight = 96 / 750 * wx.getSystemInfoSync().windowWidth;
			},
			onClick(e) {
				let index = e.index;
				switch (index) {
					case -1:
						this.tui.toast('您点击了悬浮按钮');
						break;
					case 0:
						uni.navigateTo({
							url: '/pages/about/about'
						});
						break;
					case 1:
						// #ifdef MP || H5
						this.clipboard('https://thorui.cn/');
						// #endif
						//#ifdef APP-PLUS
						plus.share.sendWithSystem({
								content: 'ThorUI组件库',
								href: 'https://thorui.cn/'
							},
							function() {
								console.log('分享成功');
							},
							function(e) {
								console.log('分享失败：' + JSON.stringify(e));
							}
						);
						//#endif
						break;
					case 2:
						uni.makePhoneCall({
							phoneNumber: '13535014139', //需要拨打的电话
							success: res => {
								this.tui.toast('打电话回调成功！');
							}
						});
						break;
					default:
						break;
				}
			},
			getConfigData() {
				this.$http
					.request({
						url: this.$api.index.config
					})
					.then(res => {
						if (res.code === 0) {
							let {
								cat_style,
								copyright,
								mall_setting,
								navbar,
								page_title
							} = res.data;
							this.copyright = copyright;
							uni.setStorageSync('mall_config', JSON.stringify(res.data));
						}
					});
			},
			change(e) {
				if (this.bannerData.style == 1 && this.bannerData.filling == 1) {
					this.receiveColor = e;
				}
			},
			//路由跳转
			navTo(url) {
				// uni.navigateTo({
				// 	url:'../search/search'
				// })
			},
			//优惠券路由跳转
			navTo2() {
				uni.navigateTo({
					url: '/pages/coupon/list'
				});
			},
			//优惠券领取
			receiveClick(val) {
				this.couponData;
				if (this.couponData[val.index].receive == 1) {
					this.$http.toast('你已经领取过该优惠券啦！');
				} else {
					this.couponData[val.index].receive = 1;
				}
			},
			setCITY() { //选择城市
				this.cityselec = true
			},
			back_city(e) { //城市选择回显
				if (e !== 'no') {
					this.city = e.name;
					this.cityselec = false;
				} else {
					this.cityselec = false;
					uni.pageScrollTo({
						scrollTop: 0,
						duration: 100
					});
					this.page=1
					this.goods_ist=[]
					this.getmyLOcation()
				}
			},
		},
		onPageScroll(e) {
			this.backTop.scrollTop = e.scrollTop;
			var that = this;
			let info = uni.createSelectorQuery().select('.ceiling-box');
			info.boundingClientRect(function(data) {
				if (data) {
					that.fromTop = data.top; //获取元素距离顶部的距离
				}
			}).exec();

			this.setSearchHeight();
			this.setStatusBarHeight();
			this.scrollTop = e.scrollTop;
			this.searchHeight = this.is_fixed == 1 ? 0 : this.searchHeight;
			if (this.fromTop < this.searchHeight + this.statusHeight) {
				this.styleBool = false;
			} else {
				this.styleBool = true;
			}
		},

	}; 
</script>

<style lang="less">
	.root {
		background: #f7f7f7;
	}

	.content {
		/* padding: 0 20rpx; */
	}
	.customer_service {
		width: 100rpx;
		height: 100rpx;
		position: fixed;
		z-index: 999;
		right: 40rpx;
		/* #ifdef H5*/
			bottom: 180rpx;
		/* #endif */
		/* #ifdef APP-PLUS*/
			bottom: 260rpx;
		/* #endif */
		
		/* #ifdef  MP  */
			bottom: 260rpx;
		/* #endif */
		border-radius: 50%
	}

	.customer_service image {
		width: 100rpx;
		height: 100rpx;
		display: block;
		border-radius: 50%;
	}

	.buttonWeapp {
		width: 100rpx;
		height: 100rpx;
		display: block;
		border-radius: 50%;
		background: url(https://dev.mingyuanriji.cn/web/static//service_logo.png)no-repeat;
		background-size: cover;
	}

	.limited {
		background: #ffffff;
		box-sizing: border-box;
	}

	.status_bar {
		height: 44px;
		width: 100%;
		position: fixed;
		
		top: 0;
		z-index: 99;
		background-color: #ffffff;

	}	
	.search_box {
		width: 100%;
		z-index: 99;
		display: flex;
		background: #fff;
	}

	.navIcon_style {
		/* padding: 30rpx 0px; */
	}

	.adBoxs {
		overflow: hidden;
	}

	.ad1 {
		font-weight: 700;
		text-align: center;
		padding: 20rpx 0px;
	}

	.rubikBox {
		width: 92%;
		margin: auto;
		border-radius: 20rpx;
		overflow: hidden;
	}

	.thematicBox {
		padding: 20rpx 0rpx 20rpx;
		border-radius: 10rpx;
		/* background: #FFFFFF; */
	}

	.bannersBox {
		position: relative;
		margin-top: 10rpx;
	}

	.order {
		position: absolute;
		top: 20px;
		left: 20rpx;
		z-index: 9;
	}
</style>
