<template>
	<view class="cart-root">
		<com-nav-bar @clickLeft="back" left-icon="back" title="账单支付" :status-bar="true" :background-color="navBg" :border="false"
		 :color="navCol"></com-nav-bar>
		<!-- 背景图，可自定义 -->
		<view class="cart-bg" :style="{background:'url('+bg_url+')'}"></view>
		<view class="mainContent">
			<view class="up">
				<view class="balance">
					<text>账户{{currencyAlias.balance_alias}}:</text>

					<view><text class="price">{{payData.balance}}</text>元</view>
				</view>
				<view class="pay-content">
					<view class="pay-title">支付金额</view>
					<view class="pay-price" :style="{color:textColor}" v-if="payData"><text class="pay-icon">&yen;</text>{{payData.amount}}</view>
					<view class="numbering">订单编号:{{payData.orderNo}}</view>
				</view>
			</view>
			<view class="down" v-if="payData.amount">
				<view class="down-item" v-for="(item,index) in payData.supportPayTypes" :key="index">
					<view class="item-left">
						<image class="item-img" :src="img_url+'images/pay/'+item+'.png'" mode=""></image>
						<text v-if="item == 'wechat'">微信支付</text>
						<text v-if="item == 'bswechat'">微信在线支付</text>
						<text v-if="item == 'alipay'">支付宝支付</text>
						<text v-if="item == 'balance'">{{currencyAlias.balance_alias}}支付</text>
					</view>
					<view class="item-right-box" @tap="switchIcon(index)">
						<view v-if="index == switchIndex" class="item-icon iconfont icon-dagou1" :style="{color:textColor}"></view>
						<view v-else class="item-right"></view>
					</view>
				</view>
			</view>
			<view class="down" v-else>
				<view class="down-item">
					<view class="item-left">
						<image class="item-img" :src="img_url+'images/pay/'+'balance'+'.png'" mode=""></image>
						<text>{{currencyAlias.balance_alias}}支付</text>
					</view>
					<view class="item-right-box">
						<view class="item-icon iconfont icon-dagou1" :style="{color:textColor}"></view>
					</view>
				</view>
			</view>

			<view class="confirmPay" :style="{background:textColor}" @click="confirmPay">确认支付</view>
		</view>
	</view>
</template>

<script>
	// #ifdef H5
	var jweixin = require('jweixin-module');
	// #endif
	// #ifdef MP-WEIXIN || APP-PLUS
	import {
		setPay
	} from '@/config/utils.js'
	// #endif
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				showBool: false, // 固定定位状态栏占位
				opacity: 0,
				payData: '',
				switchIndex: 1,
				textColor: "#BC0100",
				bg_url: '',
				navBg: '',
				navCol: '',
				id: '', //结账ID
				use_integral: '', //折扣卷使用数量
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			}
		},
		onLoad(options) {
			console.log(options)
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.navBg = this.globalSet('navBg');
			this.navCol = this.globalSet('navCol');
			if (options.id) {
				this.id = options.id
			}
			if (options.use_integral) {
				this.use_integral = options.use_integral
			}
			this.getPayData()

		},
		methods: {
			back() {
				this.navBack();
			},
			getPayData() { //获取支付信息
				this.$http.request({
					url: this.$api.moreShop.getpayINfo,
					data: {
						id: this.id,
						use_integral: this.use_integral,
					},
					showLoading: true,
				}).then((res) => {
					if (res.code == 0) {
						this.payData = res.data;
						if(res.data.detail.is_pay==1){
							uni.showModal({
							    title: '恭喜你！',
							    content: '已用'+this.currencyAlias.red_envelope_alias+'支付成功',
								showCancel:false,
							    success: function (resSure) {
							        if (resSure.confirm) {
							           	uni.redirectTo({
											url:'../../../pages/index/index'							           		
							           	})
							        } 
							    }
							});							
						}
					}
				})
			},
			switchIcon(index) { //支付模式切换
				this.switchIndex = index;
			},
			confirmPay() { // 请求支付接口，如果是余额支付
				let that = this
				if(that.payData.supportPayTypes[that.switchIndex]=='alipay'){
					that.$http.request({
						url: that.$api.moreShop.alipay,
						showLoading: true,
						method: 'post',
						data: {
							union_id: that.payData.union_id,
						}
					}).then(res=>{
						if(res.code==0){
							// #ifdef APP-PLUS
								uni.navigateTo({
									url: '/pages/order/alipayWeb?url=' + res.data.codeUrl
								})
							// #endif
							 // #ifdef H5
							let url=res.data.codeUrl
							location.href=url
							// #endif
						}else{
							that.$http.toast(res.msg)
						}
					})	
				}			
				if(that.payData.supportPayTypes[that.switchIndex]=='wechat'){
					// #ifdef H5
							that.$http.request({
								url: that.$api.moreShop.wechatpay,
								showLoading: true,
								method: 'post',
								data: {
									union_id: that.payData.union_id,
									stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
									wx_type:'wechat'//公众号：wechat  小程序：mp-wx
								}
							}).then(res=>{
								if(res.code==0){
									that.$wechatSdk.pay(res.data,'/pages/index/index');
								}else{
									that.$http.toast(res.msg);	
								}
							})
					// #endif
					// #ifdef MP-WEIXIN || APP-PLUS
							that.$http.request({
								url: that.$api.moreShop.wechatpay,
								showLoading: true,
								method: 'post',
								data: {
									union_id: that.payData.union_id,
									stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
									wx_type:'mp-wx'//公众号：wechat  小程序：mp-wx
								}
							}).then(res=>{
								if(res.code==0){
									setPay(res.data, (result) => {
										let _url = '/pages/user/index'
										if (result.success) {
											that.$http.toast("支付成功")
										} else {
											that.$http.toast("未支付")
											_url = '/pages/user/index'
										}															
										setTimeout(() => {
											uni.redirectTo({
												url: _url
											})
										},1000)
															
									});
								}else{
									that.$http.toast(res.msg);	
								}
							})
					// #endif			
				}
				if(that.payData.supportPayTypes[that.switchIndex]=='bswechat'){
					// #ifdef H5
							that.$http.request({
								url: that.$api.moreShop.bswechatpay,
								showLoading: true,
								method: 'post',
								data: {
									union_id: that.payData.union_id,
									stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
									wx_type:'wechat'//公众号：wechat  小程序：mp-wx
								}
							}).then(res=>{
								if(res.code==0){
									that.$wechatSdk.pay(res.data,'/pages/index/index');
								}else{
									that.$http.toast(res.msg);	
								}
							})
					// #endif
					// #ifdef MP-WEIXIN || APP-PLUS
							that.$http.request({
								url: that.$api.moreShop.bswechatpay,
								showLoading: true,
								method: 'post',
								data: {
									union_id: that.payData.union_id,
									stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
									wx_type:'mp-wx'//公众号：wechat  小程序：mp-wx
								}
							}).then(res=>{
								if(res.code==0){
									setPay(res.data, (result) => {
										let _url = '/pages/user/index'
										if (result.success) {
											that.$http.toast("支付成功")
										} else {
											that.$http.toast("未支付")
											_url = '/pages/user/index'
										}															
										setTimeout(() => {
											uni.redirectTo({
												url: _url
											})
										},1000)
															
									});
								}else{
									that.$http.toast(res.msg);	
								}
							})
					// #endif			
				}
				if(that.payData.supportPayTypes[that.switchIndex]=='balance'){
					that.$http.request({
						url: that.$api.moreShop.balancepay,
						showLoading: true,
						method: 'post',
						data: {
							union_id: that.payData.union_id,
						}
					}).then(res=>{
						if(res.code==0){
							uni.showModal({
								title: '恭喜你！',
								content: '支付成功',
								showCancel:false,
								success: function (resSure) {
									if (resSure.confirm) {
										uni.redirectTo({
											url:'../../../pages/index/index'
										})
									} 
								}
							});
							return;
						}else{
							that.$http.toast(res.msg)
						}
					})
				}
			}
		},
	}
</script>

<style scoped>
	.status_bar {
		height: var(--status-bar-height);
		width: 100%;
	}

	.status_bar2 {
		height: var(--status-bar-height);
		width: 100%;
		position: fixed;
		top: 0;
		z-index: 99;
	}

	body,
	page {
		background-color: $uni-bg-color-grey;
	}

	.cart-root {
		position: relative;
		z-index: 1;
	}

	.cart-bg {
		position: absolute;
		width: 100%;
		background-size: cover;
		height: 320rpx;
	}

	.cart-bg-img {
		width: 100%;
	}

	.cart-title {
		padding: 40rpx 0 0rpx;
		color: #FFFFFF;
		font-size: 14pt;
		letter-spacing: 4rpx;
		text-align: center;
		position: relative;
		margin-bottom: 40rpx;
	}

	.back {
		position: absolute;
		top: 40rpx;
		left: 30rpx;
		font-size: 40rpx;
	}

	.mainContent {
		z-index: 999;
		position: relative;
		width: 690rpx;
		margin: 118rpx auto 0;
	}

	.up {
		background: #FFFFFF;
		border-radius: 15rpx;
		margin-bottom: 30rpx;
	}

	.balance {
		display: flex;
		justify-content: space-between;
		padding: 30rpx 23rpx;
		font-size: 10pt;
		color: #000000;
		border-bottom: 2rpx solid #F2F2F2;
	}

	.price {
		font-size: 13pt;
		margin-right: 10rpx;
	}

	.pay-content {
		padding: 43rpx 0;
		display: flex;
		flex-direction: column;
		align-items: center;
	}

	.pay-title {
		font-size: 9pt;
		font-weight: 600;
		margin-bottom: 20rpx;
		color: #000000;
	}

	.pay-icon {
		font-size: 12pt;
		margin-right: 20rpx;
		font-weight: 500;
	}

	.pay-price {
		color: #BC0100;
		font-size: 16pt;
		font-weight: 600;
		margin-bottom: 20rpx;
	}

	.numbering {
		font-size: 10pt;
		color: #000000;
	}

	.down {
		background: #FFFFFF;
		border-radius: 15rpx;
		padding: 40rpx 20rpx;
	}

	.down-item {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 40rpx;
	}

	.down .down-item:last-child {
		margin-bottom: 0rpx;
	}

	.item-left {
		display: flex;
		align-items: center;
		color: #000000;
		font-size: 10pt;
	}

	.item-img {
		width: 40rpx;
		height: 40rpx;
		margin-right: 30rpx;
	}

	.item-right-box {
		width: 38rpx;
		height: 38rpx;
		position: relative;
	}

	.item-right {
		width: 38rpx;
		height: 38rpx;
		border-radius: 50%;
		border: 2rpx solid #8B8B8B;
		position: absolute;
	}

	.item-icon {
		font-size: 38rpx;
		position: absolute;
		top: 0rpx;
		left: 0rpx;
		line-height: 40rpx;
		color: #BC0100;
	}

	.confirmPay {
		color: #FFFFFF;
		background: #bc0100;
		padding: 26rpx 0;
		text-align: center;
		border-radius: 80rpx;
		font-size: 10pt;
		letter-spacing: 4rpx;
		margin-top: 80rpx;
	}
</style>
