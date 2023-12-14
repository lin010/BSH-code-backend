<template>
	<view class="audit-app">
		<view class="audit-main">
			<view class="audit-main-title-logo">
				<image :src="img_url+'/auditicon.png'" mode=""></image>
			</view>
			<view class="audit-main-title-message">
				<view>提现已成功</view>
				<!-- <view>2021-11-12 15:11:12</view> -->
			</view>
			<view class="sure">
				<button type="default" @click='backRouter'>完成</button>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
			};
		},
		methods:{
			backRouter(){
				uni.navigateBack({
					delta:1
				})
			},
		}
	}
</script>

<style lang="less" scoped>
	.audit-app{width: 100%;height: 100%;position: relative;}
	.audit-main{width: 90%;height: 800rpx;background: #fff;position: absolute;top: 0;left: 0;right: 0;bottom: 0;
	margin: auto;box-shadow: 0 3rpx 20rpx rgba(183, 183, 183, 0.5);border-radius: 20rpx;padding: 30rpx;}
	.audit-main-title-logo{width: 100%;overflow: hidden;box-sizing: border-box;}
	.audit-main-title-logo image{width: 300rpx;height: 220rpx;margin: 0 auto;display: block;}
	.audit-main-title-message{width: 100%;overflow: hidden;}
	.audit-main-title-message view{width: 100%;overflow: hidden;text-align: center;}
	.audit-main-title-message view:nth-of-type(1){font-size: 40rpx;font-weight: bold;color: #000;
	margin: 20rpx 0 10rpx 0;}
	.audit-main-title-message view:nth-of-type(2),.audit-main-title-message view:nth-of-type(3){
		margin: 20rpx 0;
	}
	.sure{width: 100%;}
	.sure button{background: #FF7104;color: #fff;margin-top: 80rpx;border: none;outline: none;}
</style>

