<template>
	<view>
		<view class="flex flex-x-center flex-y-center">
			<image class="head-img" src="./sub_success.png" mode=""></image>
		</view>

		<view class="success-title">提交成功</view>

		<view class="detail-text">您好，您的入驻申请正在审核中，审核时间为资料提交后1-3天，请您耐心等待！</view>

		<view class="btn-list flex flex-x-between" style="align-items: center;flex-direction: column;">
			<view class="btn-item" @tap="navTo('/pages/index/index')" :style="{background:textColor}">跳转首页</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				textColor: ''
			}
		},
		onLoad() {
			if (this.globalSet('textCol')) {
				this.textColor = this.globalSet('textCol');
			}
		},
		methods:{
			navTo(url){
				uni.navigateTo({
					url
				})
			},
		}
	}
</script>

<style lang="scss">
	page {
		background: #FFFFFF;
	}

	.head-img {
		width: 240rpx;
		height: 240rpx;
		display: block;
		margin: 40rpx 0;
	}

	.success-title {
		font-size: 40rpx;
		text-align: center;
		color: #000000;
		letter-spacing: 1px;
		margin-bottom: 60rpx;
	}

	.detail-text {
		width: 490rpx;
		margin: auto;
		color: #888888;
		font-size: 26rpx;
	}

	.btn-list {
		padding: 60rpx 30rpx 0;
		color: #FFFFFF;

		.btn-item {
			padding: 16rpx 96rpx;
			border-radius: 50rpx;
			letter-spacing: 1px;
		}
	}
</style>
