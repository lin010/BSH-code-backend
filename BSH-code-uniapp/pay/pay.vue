<template>
	<view class="cart-root">
		
		<!-- #ifdef MP-WEIXIN -->
		<com-nav-bar  title="提交订单" :status-bar="true" :background-color="navBg" :border="false" :color="navCol"></com-nav-bar>
		<!-- #endif -->
		
		
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
					<view class="pay-price" :style="{color:'#FF7104'}" v-if="payData"><text class="pay-icon">&yen;</text>{{(payData.amount).toFixed(2)}}</view>
					<view class="numbering">订单编号：{{payData.orderNo}}</view>
				</view>
			</view>
			<view class="down" v-if="payData.amount">
				<view class="down-item" v-for="(item,index) in payData.supportPayTypes" :key="index">
					<view class="item-left">
						<image class="item-img" :src="img_url+'images/pay/'+item+'.png'" mode=""></image>
						<text v-if="item == 'wechat'">微信支付</text>
						<text v-if="item == 'alipay'">支付宝支付</text>
						<text v-if="item == 'balance'">{{currencyAlias.balance_alias}}支付</text>
					</view>
					<view class="item-right-box" @tap="switchIcon(index)">
						<view v-if="switchIndex == index" class="item-icon iconfont icon-dagou1" :style="{color:'#FF7104'}"></view>
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
						<view class="item-icon iconfont icon-dagou1" :style="{color:'#FF7104'}"></view>
					</view>
				</view>
			</view>

			<view class="confirmPay" @tap="confirmPay" :style="{background:'#FF7104'}">确认支付</view>
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
				is_index : '',	//是否为拼团支付的标记
				active_id : '',	//团id
				opacity: 0,
				token: '',
				queue_id: '',
				payData: '',
				switchIndex: 1,
				textColor:"#BC0100",
				bg_url:'',
				navBg:'',
				navCol:'',
				goods_id : '',
				auth_pay: false,
				auth_token: '',
				order_id: '',
				source_table: '',
				to_path: '',
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			}
		},
		onLoad(options) {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			if(!options.source_table){
				uni.showModal({
					content: "业务参数未定义",
					showCancel:false,
					success() {
						uni.navigateBack({
							delta: 1
						})
					}
				});
				return;
			}
			
			this.source_table = options.source_table;
			
			if(options.goods_id){
				this.goods_id = options.goods_id;
			}
			let that = this, timer = setInterval(function(){
				if(uni.getStorageSync('mall_config')){
					that.textColor = that.globalSet('textCol');
					that.bg_url    = that.globalSet('imgUrl');
					that.navBg     = that.globalSet('navBg');
					that.navCol    = that.globalSet('navCol');
					clearInterval(timer);
				}
			}, 500);
			
			this.token = options.token;
			this.queue_id = options.queue_id;
			this.order_id = options.orderId ? options.orderId : '';
			
			// 加多一个是否为拼团支付的标记
			if(options.is_index){
				this.is_index = 1;
				this.active_id = options.active_id;
			}
		},
		onShow() {
			// 拿到支付信息
			if(!uni.getStorageSync("auth_pay")){
				this.getPayData(this.order_id);
			}else{
				this.auth_pay = true;
				this.auth_token = uni.getStorageSync("auth_token");
			}
		},
		methods: {
			back() {
				this.navBack();
			},
			// 获取支付信息
			getPayData(id) {
				this.$http.request({
					url: this.$api.pay.get_pay_data,
					showLoading: true,
					data: {
						source_table: this.source_table,
						token: this.token || '',
						queue_id: this.queue_id || '',
						id
					}
				}).then((res) => {
					if (res.code == 0) {
						this.payData = res.data;
						this.to_path = res.data.to_path ? res.data.to_path : '';
						let i;
						for(i=0; i < this.payData.supportPayTypes.length; i++){
							if(this.payData.supportPayTypes[i] == 'wechat'){
								this.switchIndex = i;
								break;
							}
						}
					}else{
						this.$http.toast(res.msg)
					}
				})
			},
			// 请求支付接口
			confirmPay(){
				let that = this;
				if(that.payData.amount==0){
					that.$http.request({
						url: that.$api.moreShop.balancepay,
						showLoading: true,
						method: 'post',
						data: {
							union_id: that.payData.union_id,
						}
					}).then(res=>{
						if(res.code==0){
							that.$http.toast('支付成功!');
							if(that.payData.is_send==1){
								setTimeout(() => {
									// #ifdef APP-PLUS
										uni.navigateTo({
											url: that.to_path ? that.to_path : '/pages/order/beused/beused'
										})
									// #endif
									// #ifdef H5||MP-WEIXIN
										uni.redirectTo({
											url: that.to_path ? that.to_path : '/pages/order/beused/beused'
										})
									// #endif
								},500)
							}else{
								setTimeout(() => {
									// #ifdef APP-PLUS
										uni.navigateTo({
											url: that.to_path ? that.to_path : '/pages/order/list?status=1'
										})
									// #endif
									// #ifdef H5||MP-WEIXIN
										uni.redirectTo({
											url: that.to_path ? that.to_path : '/pages/order/list?status=1'
										})
									// #endif
								},500)
							}
							return;
						}else{
							that.$http.toast(res.msg)
						}
					})
				}else{
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
												url: that.to_path ? that.to_path : '/pages/order/alipayWeb?url=' + res.data.codeUrl
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
										if(that.payData.is_send==1){
											that.$wechatSdk.pay(res.data, that.to_path ? that.to_path : '/pages/order/beused/beused');
										}else{
											that.$wechatSdk.pay(res.data, that.to_path ? that.to_path : '/pages/order/list?status=1');
										}
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
											let _url = that.to_path ? that.to_path : '/pages/order/list?status=1'
											if (result.success) {
												that.$http.toast("支付成功")
											} else {
												that.$http.toast("未支付")
												_url = '/pages/order/list?status=0'
											}
											if(that.payData.is_send==1){
												setTimeout(() => {
													// #ifdef APP-PLUS
														uni.navigateTo({
															url: that.to_path ? that.to_path : '/pages/order/beused/beused'
														})
													// #endif
													// #ifdef H5||MP-WEIXIN
														uni.redirectTo({
															url: that.to_path ? that.to_path : '/pages/order/beused/beused'
														})
													// #endif
												},500)
											}else{
												setTimeout(() => {
													// #ifdef APP-PLUS
														uni.navigateTo({
															url: _url
														})
													// #endif
													// #ifdef H5||MP-WEIXIN
														uni.redirectTo({
															url: _url
														})
													// #endif
												},1000)
											}																
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
								that.$http.toast('支付成功!');
								if(that.payData.is_send==1){
									setTimeout(() => {
										// #ifdef APP-PLUS
											uni.navigateTo({
												url: that.to_path ? that.to_path : '/pages/order/beused/beused'
											})
										// #endif
										// #ifdef H5||MP-WEIXIN
											uni.redirectTo({
												url: that.to_path ? that.to_path : '/pages/order/beused/beused'
											})
										// #endif
									},500)
								}else{
									setTimeout(() => {
										
										// #ifdef APP-PLUS
											uni.navigateTo({
												url: that.to_path ? that.to_path : '/pages/order/list?status=1'
											})
										// #endif
										
										// #ifdef H5||MP-WEIXIN
											uni.redirectTo({
												url: that.to_path ? that.to_path : '/pages/order/list?status=1'
											})
										// #endif
									},500)
								}
								return;
							}else{
								that.$http.toast(res.msg)
							}
						})
					}
				}
			},						
			switchIcon(index) {
				this.switchIndex = index;
			},
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
		padding-top:30rpx;
		margin: 0rpx auto 0;
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
