<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="发货成功" :status-bar="true" background-color="#BC0100" :border="false" color="#ffffff"></com-nav-bar>
		<view class="content flex-col flex-x-center flex-y-center">
			<view class="bg flex flex-x-center flex-y-center">
				<!-- <image src="" class="icon-img"></image> -->
				<image src="../images/repertory-cloud/succeed.png" class="icon-img"></image>
			</view>
			<view class="msg">商品已发货完成，祝您生活愉快~</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			};
		},
		methods:{
			back(){
				this.navBack();
			}
		}
	}
</script>

<style lang="scss" scoped>
.app{
	height: 100%;
	background-color: #F7F7F7;
}
.content{
	width: 100%;
	margin-top: 88rpx;
	
	.bg{
		width: 187rpx;
		height: 187rpx;
		background-color: #FFD5D5;
		border-radius: 50%;
		margin-bottom: 34rpx;
		
		.icon-img{
			width: 94rpx;
			height: 88rpx;
			background-size: 100%;
		}
	}
	.msg{
		font-size:25rpx;
		color: #808080;
		line-height: 60rpx;
	}
}
</style>
