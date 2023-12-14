
<!-- 这个是拼团支付 -->
<template>
	<view class="cart-root">
		<view class="status_bar" style="background: #bc0100;"></view>
		<view :class="['status_bar2',showBool?'show':'hide']" :style="{'opacity':opacity,'background':'#ffffff'}"></view>
		
		<!-- 背景图，可自定义 -->
		<view class="cart-bg">
			<image class="cart-bg-img" :src="img_url+'cartBg.png'" mode="widthFix"></image>
		</view>

		<view class="cart-title" style="background: #bc0100;">
			确认支付
			<view class="iconfont icon-fanhui back" @tap="back"></view>
		</view>
		
		<view class="mainContent">
			<view class="up">
				<view class="balance">
					<text>账户{{currencyAlias.balance_alias}}:</text>
					
					<view><text class="price">{{payData.balance}}</text>元</view>
				</view>
				<view class="pay-content">
					<view class="pay-title">支付金额</view>
					<view class="pay-price" v-if="payData.amount"><text class="pay-icon">&yen;</text>{{(payData.amount).toFixed(2)}}</view>
					<view class="numbering">订单编号：{{payData.orderNo}}</view>
				</view>
			</view>
			<view class="down">
				<view class="down-item" v-for="(item,index) in payData.supportPayTypes" :key="index">
					<view class="item-left">
						<image class="item-img" :src="img_url+'images/pay/'+item+'.png'" mode=""></image>
						<text v-if="item == 'wechat'">微信支付</text>
						<text v-if="item == 'alipay'">支付宝支付</text>
						<text v-if="item == 'balance'">{{currencyAlias.balance_alias}}支付</text>
					</view>
					<view class="item-right-box" @tap="switchIcon(index)">
						<view v-if="index == switchIndex" class="item-icon iconfont icon-dagou1"></view>
						<view v-else class="item-right"></view>
					</view>
				</view>
			</view>
			
			<view class="confirmPay" @tap="confirmPay">确认支付</view>
		</view>
	</view>
</template>

<script>
	// #ifdef H5
	var jweixin = require('jweixin-module');
	// #endif
	// #ifdef MP-WEIXIN
	import {setPay} from '@/config/utils.js'
	// #endif
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				showBool: false, // 固定定位状态栏占位
				opacity:0,
				token:'',
				queue_id:'',
				payData:'',
				switchIndex:0,
				is_index : '',	//是否为拼团支付的标记
				active_id : '',	//团id
				goods_id : '',	//商品id
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
			this.goods_id = options.goods_id;
			console.log(this.goods_id);
			this.token = options.token;
			this.queue_id = options.queue_id;
			let orderId = options.orderId ? options.orderId : '';
			this.getPayData(orderId);
			// 加多一个是否为拼团支付的标记
			if(options.is_index){
				this.is_index = 1;
				this.active_id = options.active_id;
				console.log('this.is_index:'+this.is_index);
				console.log('this.active_id:'+this.active_id);
			}
			
		},
		methods: {
			back(){
				uni.navigateBack();
			},
			getPayData(id){
				this.$http.request({
					url:this.$api.order.toPay,
					showLoading:true,
					data:{
						token: this.token || '',
						queue_id: this.queue_id || '',
						id
					}
				}).then((res)=>{
					if(res.code == 0){
						this.payData = res.data;
					}
				})
			},
			
			// 请求支付接口，如果是余额支付
			confirmPay(){
				this.$http.request({
					url:this.$api.payment.doPay,
					showLoading:true,
					method:'post',
					data:{
						union_id: this.payData.union_id,
						pay_type: this.payData.supportPayTypes[this.switchIndex]
					}
				}).then(res =>{
					/* 这里成功跳转的页面改一下就是了 */
					
					if(res.code == 0){
						if(this.payData.supportPayTypes[this.switchIndex] == 'balance'){
							this.$http.toast('支付成功!');
							setTimeout(() => {
								uni.redirectTo({
									url: '/pages/order/list?status=1&goods_id='+this.goods_id
								})
							},1000)
							return;
						}
						// #ifdef H5
						this.$wechatSdk.pay(res.data,'/pages/order/list?status=1&goods_id='+this.goods_id);
						// #endif
						
						// #ifdef MP-WEIXIN || APP-PLUS
						setPay(res.data,(result) => {
							let _url = '/pages/order/list?status=1&goods_id='+this.goods_id
							
							if(result.success){
								this.$http.toast("支付成功")
							}else{
								this.$http.toast("未支付")
								_url = '/pages/order/list?status=0&goods_id='+this.goods_id
							}
							
							setTimeout(() => {
								uni.redirectTo({
									url: _url
								})
							},1000)
						});
						// #endif
					}else{
						this.$http.toast(res.msg);
						setTimeout(() => {
							uni.redirectTo({
								url: '/pages/order/list?status=0&goods_id='+this.goods_id
							})
						},1000)
					}
				})
			},
			switchIcon(index){
				this.switchIndex = index;
			}
		}
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
	.back{
		position: absolute;
		top: 40rpx;
		left: 30rpx;
		font-size: 40rpx;
	}
	
	.mainContent{
		z-index: 999;
		position: relative;
		width: 690rpx;
		margin: 118rpx auto 0;
	}
	.up{
		background: #FFFFFF;
		border-radius: 15rpx;
		margin-bottom: 30rpx;
	}
	.balance{
		display: flex;
		justify-content: space-between;
		padding: 30rpx 23rpx;
		font-size: 10pt;
		color: #000000;
		border-bottom: 2rpx solid #F2F2F2;
	}
	.price{
		font-size: 13pt;
		margin-right: 10rpx;
	}
	.pay-content{
		padding: 43rpx 0;
		display: flex;
		flex-direction: column;
		align-items: center;
	}
	.pay-title{
		font-size: 9pt;
		font-weight: 600;
		margin-bottom: 20rpx;
		color: #000000;
	}
	.pay-icon{
		font-size: 12pt;
		margin-right: 20rpx;
		font-weight: 500;
	}
	.pay-price{
		color: #BC0100;
		font-size: 16pt;
		font-weight: 600;
		margin-bottom: 20rpx;
	}
	.numbering{
		font-size: 10pt;
		color: #000000;
	}
	.down{
		background: #FFFFFF;
		border-radius: 15rpx;
		padding: 40rpx 20rpx;
	}
	.down-item{
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 40rpx;
	}
	.down .down-item:last-child{
		margin-bottom: 0rpx;
	}
	.item-left{
		display: flex;
		align-items: center;
		color: #000000;
		font-size: 10pt;
	}
	.item-img{
		width: 40rpx;
		height: 40rpx;
		margin-right: 30rpx;
	}
	.item-right-box{
		width: 38rpx;
		height: 38rpx;
		position: relative;
	}
	.item-right{
		width: 38rpx;
		height: 38rpx;
		border-radius: 50%;
		border: 2rpx solid #8B8B8B;
		position: absolute;
	}
	.item-icon{
		font-size: 38rpx;
		position: absolute;
		top: 0rpx;
		left: 0rpx;
		line-height: 40rpx;
		color: #BC0100;
	}
	.confirmPay{
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
