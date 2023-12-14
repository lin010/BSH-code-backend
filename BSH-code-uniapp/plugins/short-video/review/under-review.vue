<template>
	<view>
		<view class="iconfont icon-gantanhao1 header-icon"></view>
		<view class="content-box">
			<view class="content-title">审核中</view>
			<view>提交成功，请等待管理员审核！</view>
			<view class="detail-text">
				预计3小时内审核完毕，审核通过将会自动发布短视频!
			</view>
			<view class="flex flex-y-center btn-box">
				<view class="common" @tap="navTo('/pages/index/index')">返回首页</view>
				<view class="review-list common" @tap="navTo('/plugins/short-video/review/review-list')">审核列表</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				navTo(url){
					uni.navigateTo({
						url
					})
				}
			};
		}
	}
</script>

<style lang="scss" scoped>
	.header-icon{
		font-size: 150rpx;
		color: #BC0100;
		text-align: center;
		margin-top: 50rpx;
	}
	.content-box{
		color: #000000;
		width: 460rpx;
		margin: auto;
		text-align: center;
		font-size: 28rpx;
		letter-spacing: 1px;
		font-weight: 600;
		
		.content-title{
			font-size: 38rpx;
			margin-bottom: 30rpx;
		}
		.detail-text{
			font-size: 26rpx;
			color: #808080;
			font-weight: 500;
			margin-top: 26rpx;
		}
		.btn-box{
			margin-top: 50rpx;
			font-weight: 500;
			
			.common{
				width: 222rpx;
				height: 68rpx;
				line-height: 68rpx;
				border-radius: 50rpx;
				border: 1px solid #BC0100;
				color: #BC0100;
				letter-spacing: 1px;
			}
			.review-list{
				background: #BC0100;
				color: #FFFFFF;
				margin-left: 66rpx;
			}
		}
	}
</style>
