<template>
	<view class="app">
		<main-tabbar></main-tabbar>
		<view class="jx-mybg-box">
			<block v-if="configData.user_center">
				<image v-if="configData.user_center.top_style == 3" :src="configData.user_center.style_bg_pic_url" class="jx-my-bg"></image>
				<image v-else :src="img_url+'/userTOP_logo.jpg'" class="jx-my-bg"></image>
			</block>
			<view v-if="userInfo && userInfo.identity && configData.user_center" class="jx-header-center"
				:class="configData.user_center.top_style == 3 ? 'right' : configData.user_center.top_style == 2 ? 'top' : ''">
				<image :src="userInfo.avatar || configData.user_center.member_pic_url" class="jx-avatar" @tap="href(3)"></image>
				<view class="jx-info">
					<view class="jx-nickname">
						<view class="nickname">{{ userInfo.nickname || '点击头像去设置昵称' }}</view>
					</view>
					<view class="flex flex-y-center">
						<view class="jx-img-vip">
							<image class="vip_url" v-if="userInfo.identity.member_pic_url" :src="userInfo.identity.member_pic_url"></image>
							<view class="iconfont icon-zuanshi" v-else></view>
							<view class="text">{{ userInfo.identity.level_name }}</view>
						</view>
						<view class="user-id">账号:BSH{{ userInfo.user_id }}</view>
					</view>
				</view>
				<!-- 社区团购 -->
				<view class="withdraw flex" v-if="0 == 1">
					<navigator url="./interests">
						<view class="icon-text flex" @click="showCode()">
							<image :src="img_url+'images/index/qrcode@2x.png'" class="icon-img img qrcode"></image>
							<view class="text">提货二维码</view>
						</view>
					</navigator>
				</view>
				<!-- 社区团购 -->
			</view>
			<block v-else>
				<view v-if="configData.user_center" class="jx-header-center flex flex-y-center" :class="{'flex-col':configData.user_center.top_style == 2,'marginB':configData.user_center.top_style == 2}">
					<image :src="configData.user_center.member_pic_url" class="jx-avatar"></image>
					<button type="primary" class="jx-info" @click="userLogin" style="margin-left: 0;margin-right: 0;">点击登录/注册</button>
				</view>
			</block>
		</view>
		<view class="jx-content-box" v-if="configData.user_center">
			<view class="jx-header-btm">
				<view class="jx-btm-item" @click="openUrl('balance')">
					<view class="jx-btm-num">{{ userInfo.balance }}</view>
					<view class="jx-btm-text">{{currencyAlias.balance_alias}}</view>
				</view>
				<view class="jx-btm-item" @click="openUrl('total_score')">
					<view class="jx-btm-num">{{ userInfo.total_score }}</view>
					<view class="jx-btm-text">{{currencyAlias.integral_alias}}</view>
				</view>
				<view class="jx-btm-item last" @click="openUrl('redBag')">
					<view class="jx-btm-num">{{ Number(userInfo.static_integral) }}</view>
					<!-- <view class="jx-btm-num">{{ Number(userInfo.dynamic_integral)+Number(userInfo.static_integral) }}</view> -->
					<view class="jx-btm-text">{{currencyAlias.red_envelope_alias}}</view>
				</view>
				<view class="jx-btm-item last" @click="openUrl('shopping_voucher')">
					<view class="jx-btm-num">{{ Number(userInfo.shopping_voucher)}}</view>
					<view class="jx-btm-text">{{currencyAlias.silver_beans_alias}}</view>
				</view>
			</view>
			
			<view class="jx-box jx-info-box" v-if="mytoken!=0 && userInfo && !userInfo.mobile" @click="openUrl(`mobile`)">
				<icon type="info" color="#7d5912" size="14"/><text class="text">请绑定你的手机号</text>
			</view>
			
			<view class="jx-box jx-order-box" v-if="configData.user_center.is_order_bar_status">
				<!-- <jx-list-cell :arrow="true" padding="0" :lineLeft="false" @click="href(4)"> -->
				<jx-list-cell :arrow="false" padding="0" :lineLeft="false" >
					<view class="jx-cell-header">
						<view class="jx-cell-title" style="font-weight: 700;">我的订单</view>
						<!-- <view class="jx-cell-sub">查看全部订单</view> -->
					</view>
				</jx-list-cell>
				<view class="jx-order-list">
					<view class="jx-order-item" @tap="openlink(item.link_url)" v-for="(item, i) in configData.user_center.order_bar" :key="i">
						<view class="jx-icon-box">
							<image :src="item.icon_url" class="jx-order-icon"></image>
							<!-- <view class="jx-badge jx-badge-red" v-if="item.num">{{ item.num }}</view> -->
						</view>
						<view class="jx-order-text">{{ item.name }}</view>
					</view>
				</view>
			</view>
			<!-- 社区团购 -->
			<block v-if="0 == 1">
				<view class="address">
					<view class="address-title flex-x-between">
						<view class="title color-333333">我的当前自提点</view>
						<view class="cut-address flex">
							<view>切换自提点</view>
							<view class="iconfont icon-xiala"></view>
						</view>
					</view>
					<view class="address-info">
						<view class="store-name">员村友客便利店</view>
						<view class="store-detail color-333333">门店地址：广东省广州市天河区广州是天河区友客来便利店</view>
						<view class="store-tel color-333333">
							<span>门店号码：</span>
							<span style="color: #BC0100;">13710482718</span>
						</view>
						<view class="remark">
							<image :src="img_url+'images/index/shield.png'" mode="aspectFill" class="shield"></image>
							<span>如果您购买的商品有任何问题，请直接与购买的门店联系！ 门店讲为您处理，</span>
							<span class="color-333333">让您售后无忧!</span>
						</view>
					</view>
				</view>
			</block>
			<!-- #ifdef H5 -->
			<view v-if="configData.user_center.menus && configData.user_center.menus.length">
				<toolbar
					v-if="configData.user_center.is_menu_status != 0"
					:toolbarTitle="configData.user_center.menu_title"
					:flex="configData.user_center.menu_style"
					:toolbarData="userMessage.user_center.menus"
				></toolbar>
			</view>
			<!--#endif -->
			<!-- #ifdef MP-WEIXIN -->
			<view v-if="configData.user_center.menus && configData.user_center.menus.length">
				<toolbar
					v-if="configData.user_center.is_menu_status != 0"
					:toolbarTitle="configData.user_center.menu_title"
					:flex="configData.user_center.menu_style"
					:toolbarData="configData.user_center.menus"
				></toolbar>
			</view>
			<!--#endif -->
			<!-- #ifdef APP-PLUS -->
			<view v-if="configData.user_center.menus && configData.user_center.menus.length">
				<toolbar
					v-if="configData.user_center.is_menu_status != 0"
					:toolbarTitle="configData.user_center.menu_title"
					:flex="configData.user_center.menu_style"
					:toolbarData="configData.user_center.menus"
				></toolbar>
			</view>
			<!--#endif -->
		</view>
		<placeholder :placeholderHeight="placeholderHeight3"></placeholder>
		<jx-tips ref="toast"></jx-tips>
		<com-modal :show="modal" @cancel="hide" :custom="true">
			<view class="jx-modal-custom">
				<view class="jx-modal-custom-text">签到成功</view>
				<jx-button type="danger" shape="circle" @click="hide">确定</jx-button>
			</view>
		</com-modal>
		<com-modal :show="!!take_a_code" @cancel="codeHide()" :custom="true" width="350rpx" padding="20rpx">
			<view class="flex-y-center flex-x-center">
				<image class="tack-code" :src="take_a_code"></image>
			</view>
		</com-modal>
		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
import jxTips from '@/components/tips/tips';
import jxIcon from '@/components/icon/icon';
import jxButton from '@/components/extend/button/button';
import jxListCell from '@/components/list-cell/list-cell';
import toolbar from '@/components/toolbar/toolbar.vue';
export default {
	components: {
		jxIcon,
		jxButton,
		jxListCell,
		jxTips,
		toolbar
	},
	data() {
		return {
			modal: false,
			placeholderHeight3: 70,
			height: 64, //header高度
			top: 0, //标题图标距离顶部距离
			scrollH: 0, //滚动总高度
			opcity: 0,
			iconOpcity: 0.5,
			pageIndex: 1,
			loadding: false,
			pullUpOn: true,
			//导航栏数据
			tabCurrent: 3,
			selectedColor: '#bc0100',
			configData: {
				copyright: null,
				title_bar: null,
				user_center: null
			},
			mytoken:0,
			userInfo: {
				mobile:0,
				balance: 0,
				score: 0,
				total_score:0,
				coupon: 0,
				dynamic_integral:0,
				static_integral:0,
				shopping_voucher:0,
			},
			img_url: this.$api.img_url,
			// 社区团购
			take_a_code: '',
			loading: false,
			textColor:'#bc0100',
			userMessage:{},
			showFoucs:false,
			currencyAlias:{
				balance_alias: '',
				red_envelope_alias: '',
				integral_alias: '',
				silver_beans_alias: '',
			},
		};
	},
	onLoad: function(options) {
		//#ifdef MP-WEIXIN
		if(options.user_id){
			uni.setStorageSync('parent_user_id',options.user_id);
		}
		//#endif
		this.textColor = this.globalSet('textCol');
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
		

		uni.getSystemInfo({
			success: res => {
				this.width = obj.left || res.windowWidth;
				this.height = obj.top ? obj.top + obj.height + 8 : res.statusBarHeight + 44;
				this.top = obj.top ? obj.top + (obj.height - 32) / 2 : res.statusBarHeight + 6;
				this.scrollH = res.windowWidth * 0.6;
			}
		});
		let currencyAliasConfig = uni.getStorageSync('currencyAlias');
		if(currencyAliasConfig){
			JSON.parse(currencyAliasConfig);
			this.currencyAlias = JSON.parse(currencyAliasConfig);
		}
		if (uni.getStorageSync('token')) {
			this.mytoken = uni.getStorageSync('token');
		}else{
			this.mytoken=0
			uni.removeStorageSync('addressID')
			uni.showModal({
				title: "提示",
				content: "您还未登录，去登录吧~",
				confirmText: "去登录",
				showCancel:false,
				success: (res) => {
					if (res.confirm) {
						uni.navigateTo({
							url: '/pages/public/login'
						});
					}else{
						
					}
				}
			});
		}
		//this.initData(false);
	},
	onShow() {
		this.$http.isLogin() && this.getUser(false);
		if(uni.getStorageSync('token')){
			this.initData(false);
		}
	},
	methods: {
		foucusInfo(){
			uni.navigateTo({
				url: '/pages/diy/diy?page_id=114'
			});
		},
		link(){
			
				uni.navigateTo({
					url:'../personalCentre/accountingOrder/accountingOrder'
				})
		},
		userLogin() {
			// #ifdef H5
			let pages = getCurrentPages();
			let curPage = pages[pages.length - 1];
			let preUrl = curPage.$route.fullPath;
			uni.setStorageSync('_login_pre_url', preUrl);
			uni.removeStorageSync('token');
			uni.removeStorageSync('initMenus');
			// #endif
			uni.navigateTo({
				url: '/pages/public/login'
			});
		},
		initData() {
			this.$http
				.request({
					url: this.$api.index.userConfig
				})
				.then(res => {
					if (res.code === 0) {
						this.configData = res.data.config;
						this.userMessage=res.data.config;
						console.log(res.data)
						uni.setStorageSync('initMenus', JSON.stringify(res.data.config));
						if(res.wechat_subscribe==1){
							this.showFoucs=false
						}else{
							this.showFoucs=true
						}
					}
				});
		},
		getUser(bool) {
			this.loading = bool;
			this.$http
				.request({
					url: this.$api.user.userInfo,
					method: 'POST',
					showLoading: true
				})
				.then(res => {
					this.loading = false;
					if (res.code == 0) {
						this.userInfo = res.data;
						let token = uni.getStorageSync('token') || 0;
						uni.setStorageSync('userInfo', JSON.stringify(res.data));
						if(res.wechat_subscribe==1){
							this.showFoucs=false
						}else{
							this.showFoucs=true
						}
						
					} else {
						uni.removeStorageSync('userInfo');
						this.userInfo = {
							balance: 0,
							score: 0,
							coupon: 0
						};
						this.showFoucs=true
					}
				});
		},
		openUrl(name) {
			let url=''
			if(name=='balance'){
				url='./balance/details'
			}else if(name=='total_score'){
				url='./integral/integral'
			}else if(name=='redBag'){
				url='../../mch/redBag/redBag'
			}else if(name=='shopping_voucher'){
				url='../../mch/vouchers/vouchers'
			}			
			uni.navigateTo({
				url: url+"?name="+name,
			});
		},
		openlink(url){
			uni.navigateTo({
				url:url
			})
		},
		href(page) {
			let url = '';
			switch (page) {
				case 1:
					break;
				case 2:
					url = './setting';
					break;
				case 3:
					url = './info';
					break;
				case 4:
					url = '../order/list';
					break;
				default:
					break;
			}
			if (url) {
				uni.navigateTo({
					url: url
				});
			} else {
				this.$http.toast('功能尚未完善~');
			}
		},
		checkSign() {
			this.modal = true;
		},
		hide() {
			this.modal = false;
		},
		// 显示取件码
		showCode() {
			this.take_a_code = 'data:image/png;base64,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';
		},
		// 隐藏取件码
		codeHide() {
			this.take_a_code = '';
		},
		shopInto(){ //点击进入商户页面
			uni.navigateTo({
				url:'../personalCentre/personalCentre'
			})
		}
	},
	onPageScroll(e) {
		let scroll = e.scrollTop <= 0 ? 0 : e.scrollTop;
		let opcity = scroll / this.scrollH;
		if (this.opcity >= 1 && opcity >= 1) {
			return;
		}
		this.opcity = opcity;
		this.iconOpcity = 0.5 * (1 - opcity < 0 ? 0 : 1 - opcity);
	},
	onPullDownRefresh() {
		setTimeout(() => {
			uni.stopPullDownRefresh();
			this.$http.isLogin() && this.getUser(true);
		}, 1000);
	}
};
</script>

<style lang="scss" scoped>
.jx-header-box {
	width: 100%;
	position: fixed;
	left: 0;
	top: 0;
	z-index: 9998;
}

.jx-header {
	width: 100%;
	line-height: 18px;
	font-weight: 500;
	height: 32px;
	display: flex;
	align-items: center;
	justify-content: center;
}

.jx-modal-custom {
	display: flex;
	flex-direction: column;
}

.jx-modal-custom-text {
	text-align: center;
}

.btns { 
	display: flex;
}
.user_shop{
	position: absolute;
	right: 100upx;
	color:#000;
	min-width: 100upx;
	text-align: center;
	background:#fff;
	border-radius: 20upx;
	font-size:30upx ;
}
.member {
	height: 80rpx;
	margin: 20rpx 0;

	.vip-card {
		width: 100%;
		height: 100%;
	}
}

/* #ifndef MP */
.jx-header-icon {
	position: fixed;
	top: 0;
	right: 15px;
	display: flex;
	align-items: center;
	justify-content: space-between;
	height: 32px;
	transform: translateZ(0);
	z-index: 99999;
}

/* #endif */
/* #ifdef MP */
.jx-set-box {
	display: flex;
	align-items: center;
	justify-content: space-between;
}

/* #endif */
.jx-icon-box {
	position: relative;

	.icon {
		font-size: 44rpx;
		line-height: 48rpx;
		margin-right: 20rpx;
	}

	&:last-child {
		.icon {
			margin-right: 10rpx;
		}
	}
}

.jx-icon-setup {
	margin-left: 8rpx;
}

.jx-badge {
	position: absolute;
	font-size: 9pt;
	height: 30rpx;
	/* #ifdef H5 */
	min-width: 30rpx;
	/* #endif */
	/* #ifndef H5 */
	min-width: 20rpx;
	/* #endif */
	padding: 0 6rpx;
	border-radius: 40rpx;
	right: 10rpx;
	top: -5rpx;
	transform: scale(0.8) translateX(60%);
	transform-origin: center center;
	display: flex;
	align-items: center;
	justify-content: center;
	z-index: 10;
}

.jx-badge-red {
	background: #f74d54;
	color: #fff;
	right: 0;
}

.jx-badge-white {
	background: #fff;
	color: #f74d54;
}

.jx-badge-dot {
	position: absolute;
	height: 12rpx;
	width: 12rpx;
	border-radius: 50%;
	right: -12rpx;
	top: 0;
	background: #f74d54;
}

.jx-mybg-box {
	width: 100%;
	// height: 360rpx;
	position: relative;
	display: flex;
	align-items: center;
	// padding: 120rpx 0 100rpx;
}

.jx-my-bg {
	width: 100%;
	// height: 464rpx;
	height: 420rpx;
	display: block;
	position: absolute;
	top: 0;
}

.jx-header-center {
	width: 100%;
	// height: 128rpx;
	padding: 80rpx 30rpx 60rpx;
	box-sizing: border-box;
	display: flex;
	align-items: center;
	position: relative;

	&.top {
		justify-content: center;
		flex-direction: column;
		margin-bottom: 36rpx;

		.jx-info {
			/* #ifndef APP-NVUE */
			display: flex;
			/* #endif */
			flex-direction: column;
			align-items: center;

			.jx-nickname {
				margin: 0;
			}
		}
	}

	.withdraw {
		position: absolute;
		right: 0;
		color: #ffffff;
		.icon-text {
			align-items: center;
			background: rgba(0, 0, 0, 0.2);
			font-size: 9pt;
			line-height: 54rpx;
			padding: 0 20rpx;
			border-bottom-left-radius: 27rpx;
			border-top-left-radius: 27rpx;

			.img {
				height: 37rpx;
				width: 37rpx;
				margin-right: 10rpx;
			}

			.icon {
				font-size: 11pt;
				line-height: 26px;

				&.icon-tixian {
					margin-left: 10rpx;
				}
			}

			.text {
				margin-left: 6rpx;
			}
		}
	}
}

.marginB{
	padding: 60rpx 30rpx 20rpx;
}

.icon-img {
	background-size: 100%;
	background-repeat: no-repeat;
}

.jx-avatar {
	flex-shrink: 0;
	width: 128rpx;
	height: 128rpx;
	display: block;
	border-radius: 50px;
}

.jx-info {
	// width: 60%;
	margin-left: 20rpx;
	color: #ffffff;
	background: transparent;
	/* #ifdef MP-WEIXIN */
	flex-grow: 1;
	text-align: left;
	/* #endif */

	.jx-nickname {
		font-size: 14pt;
		font-weight: 400;
		display: flex;
		align-items: center;
		margin-bottom: 4rpx;

		.nickname {
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			max-width: 240rpx;
		}
	}

	.user-id {
		font-size: 10pt;
		margin-left: 10rpx;
	}
}

.jx-img-vip {
	height: 36rpx;
	// margin-left: 18rpx;
	display: flex;
	align-items: center;
	background-color: #ffffff;
	color: #FF7104;
	padding: 0 10rpx;
	border-radius: 19rpx;
	line-height: 18px;
	.vip_url {
		width: 28rpx;
		height: 26rpx;
	}
	.iconfont {
		font-size: 13pt;
		width: 28rpx;
		height: 20rpx;
		line-height: 26rpx;
	}
	.text {
		margin-left: 12rpx;
		font-size: 9pt;
		font-weight: 400;
	}
}

.address {
	font-size: 10.5pt;
	background-color: #ffffff;
	padding: 0 20rpx;
	border-radius: 15rpx;

	.address-title {
		line-height: 88rpx;
		padding: 0 16rpx;
		border-bottom: 2rpx solid #f2f2f2;
		.cut-address {
			color: $jx-color-primary;
			.iconfont {
				font-size: 16pt;
				width: 16px;
			}
		}
	}

	.address-info {
		padding: 32rpx 16rpx;
		line-height: 48rpx;
		.store-name {
			color: $jx-color-primary;
			line-height: 44rpx;
		}
		.remark {
			font-size: 9pt;
			color: #939393;
			line-height: 44rpx;
		}

		.shield {
			width: 22rpx;
			height: 24rpx;
			background-size: 100%;
			background-repeat: no-repeat;
			margin-right: 13rpx;
		}
	}
}

.color-333333 {
	color: #333333;
}

.jx-explain {
	width: 80%;
	font-size: 9pt;
	font-weight: 400;
	color: #fff;
	opacity: 0.75;
	padding-top: 8rpx;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.jx-btn-edit {
	flex-shrink: 0;
	padding-right: 22rpx;
}

.jx-header-btm {
	border-radius: 12rpx;
	box-sizing: border-box;
	display: flex;
	align-items: center;
	justify-content: space-between;
	color: #000;
	background: #ffffff;
	margin-bottom: 20rpx;
}

.jx-btm-item {
	flex: 1;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	padding: 40rpx 10rpx;
	position: relative;
	&::after {
		content: '';
		border-right: 1rpx solid #b3b3b3;
		width: 1px;
		height: 100rpx;
		position: absolute;
		right: 0;
		top: 50%;
		transform: translateY(-50%);
	}

	&:last-child {
		&::after {
			display: none;
		}
	}
}

.jx-btm-num {
	font-size: 11pt;
	font-weight: 600;
	position: relative;
}

.jx-btm-text {
	font-size: 9pt;
	opacity: 0.85;
	padding-top: 4rpx;
}

.jx-content-box {
	margin-bottom: 30rpx;
	width: 100%;
	padding: 0 30rpx;
	box-sizing: border-box;
	position: relative;
	// top: -72rpx;
	z-index: 10;
}

.jx-box {
	width: 100%;
	background: #fff;
	box-shadow: 0 3rpx 20rpx rgba(183, 183, 183, 0.1);
	border-radius: 10rpx;
	overflow: hidden;
}
.jx-info-box {
	height: 100rpx;
	padding: 0 40rpx;
	box-sizing: border-box;
	display: flex;
	align-items: center;
	background-color: #ffe691;
	color:#7d5912;
	margin-bottom: 20rpx;
}
.jx-info-box .text{
	margin-left:10rpx;
}
.jx-order-box {
	// height: 300rpx;
	overflow: hidden;
}

.jx-cell-header {
	width: 100%;
	height: 100rpx;
	padding: 0 40rpx;
	box-sizing: border-box;
	display: flex;
	align-items: center;
	justify-content: space-between;
	border-bottom: 1rpx solid #f3f3f3;
}

.jx-cell-title {
	font-size: 11pt;
	line-height: 30rpx;
	font-weight: 400;
	color: #333;
	font-weight: 600;
	letter-spacing: 1px;
}

.jx-cell-sub {
	font-size: 9pt;
	font-weight: 400;
	color: #999;
	padding-right: 10rpx;
}

.jx-order-list {
	width: 100%;
	height: 280rpx;
	// padding: 0 30rpx;
	box-sizing: border-box;
	display: flex;
	align-items: center;
	justify-content: space-between;
	flex-wrap: wrap;
}

.jx-order-item {
	// flex: 1;
	width: 25%;
	display: flex;
	flex-direction: column;
	align-items: center;
	margin: 20rpx 0;
}

.jx-order-text,
.jx-tool-text {
	font-size: 10pt;
	font-weight: 400;
	color: #333;
	padding-top: 4rpx;
}

.jx-tool-text {
	font-size: 9pt;
}

.jx-order-icon {
	width: 56rpx;
	height: 56rpx;
	display: block;
}

.jx-assets-box {
	height: 178rpx;
	margin-top: 20rpx;
}

.jx-assets-list {
	height: 84rpx;
}

.jx-assets-num {
	font-size: 12pt;
	font-weight: 500;
	color: #333;
	position: relative;
}

.jx-assets-text {
	font-size: 9pt;
	font-weight: 400;
	color: #666;
	padding-top: 6rpx;
}

.jx-tool-box {
	margin-top: 20rpx;
}

.jx-flex-wrap {
	flex-wrap: wrap;
	height: auto;
	padding-bottom: 30rpx;
}

.jx-tool-item {
	width: 25%;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	padding-top: 30rpx;
}

.jx-tool-icon {
	width: 64rpx;
	height: 64rpx;
	display: block;
}

.jx-badge-icon {
	width: 66rpx;
	height: 30rpx;
	position: absolute;
	right: 0;
	transform: translateX(88%);
	top: -15rpx;
}

/*为你推荐*/
.jx-product-list {
	display: flex;
	justify-content: space-between;
	flex-direction: row;
	flex-wrap: wrap;
	box-sizing: border-box;
}

.jx-product-container {
	flex: 1;
	margin-right: 2%;
}

.jx-product-container:last-child {
	margin-right: 0;
}

.jx-pro-item {
	width: 100%;
	margin-bottom: 4%;
	background: #fff;
	box-sizing: border-box;
	border-radius: 12rpx;
	overflow: hidden;
}

.jx-pro-img {
	width: 100%;
	display: block;
}

.jx-pro-content {
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	box-sizing: border-box;
	padding: 20rpx;
}

.jx-pro-tit {
	color: #2e2e2e;
	font-size: 9pt;
	word-break: break-all;
	overflow: hidden;
	text-overflow: ellipsis;
	display: -webkit-box;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2;
}

.jx-pro-price {
	padding-top: 18rpx;
}

.jx-sale-price {
	font-size: 13pt;
	font-weight: 500;
	color: #e41f19;
}

.jx-factory-price {
	font-size: 9pt;
	color: #a0a0a0;
	text-decoration: line-through;
	padding-left: 12rpx;
}

.jx-pro-pay {
	padding-top: 10rpx;
	font-size: 9pt;
	color: #656565;
}
.tack-code {
	width: 307rpx;
	height: 307rpx;
}
</style>
