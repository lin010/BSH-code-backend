<template>
	<view class="app">
		<view class="container">
			<view class="img-msg flex-col flex-y-center">
				<!-- <view class="img"></view> -->
				<image class="img" :src="img_url+'images/order/order-nothing.png'" mode=""></image>
				<view class="msg">已收到您的售后退款信息，请耐心等待商家审核~</view>
			</view>
			<view class="btn" @click="goIndex">
				返回首页
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
			}
		},
		onLoad() {
			
		},
		methods: {
			
			goIndex(){
				uni.redirectTo({
					url: '../list'
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.app{
		background-color: #F7F7F7;
		height: 100%;
		padding: 0 30rpx;
		
		.container{
			
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			font-size: 9pt;
			height: 50%;
			
			.img-msg{
				
				margin-bottom: 50rpx;
				.img{
					width: 187rpx;
					height: 187rpx;
					margin-bottom: 35rpx;
				}
				
				.msg{
					color: #808080;
				}
			}
			
			.btn{
				width: 100%;
				height:90rpx;
				background-color: #BC0100;
				color: #FFFFFF;
				text-align: center;
				line-height: 90rpx;
				border-radius:45rpx;
			}
		}
	}
</style>