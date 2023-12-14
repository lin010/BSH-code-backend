<template>
	<view>
		<view class="top-box">
			<view class="top-title">发起直播</view>
			<view class="top-cont">
				<view class="top-cont-one">
					<image src="https://www.diyimei.net/upload/2018/1540988741494445.jpg"></image>
					<view class="top-cont-one-but">更换封面</view>
				</view>
				<view class="title">
				<input class="uni-input" maxlength="10" placeholder="输入你的直播标题" placeholder-style="color:#FFFFFF" focus="true" />
				</view>
			</view>
		</view>
		<view @click="open" class="but">发起直播</view>
	</view>
</template>

<script>
	export default{
		data(){
			return{
				
			}
		},
		methods:{
			open(){
				uni.navigateTo({
					url:'./pusher'
				})
			}
		}
	}
</script>

<style>
	.top-box{
		width: 650rpx;
		border-radius: 20rpx;
		background: rgba(48,48,48,.4);
		padding: 20rpx;
		margin-left: 30rpx;
		margin-top: 50rpx;
	}
	.but{
		position: fixed;
		bottom: 50rpx;
		width: 690rpx;
		border-radius: 40rpx;
		padding-top: 20rpx;
		padding-bottom: 20rpx;
		text-align: center;
		font-size: 30rpx;
		color: white;
		margin-left: 30rpx;
		background: #d4237a;
	}
	.top-title{
		color: white;
		font-size: 30rpx;
		
	}
	.top-cont{
		padding-top: 20rpx;
		display: flex;
		align-items: flex-start;
	}
	.top-cont-one{
		position: relative;
		width: 200rpx;
		height: 200rpx;
		display: flex;
		align-items: center;
		justify-content: flex-end;
		flex-direction: column;
	}
	.top-cont-one>image{
		width: 200rpx;
		height: 200rpx;
		position: absolute;
		top: 0rpx;
	}
	.top-cont-one-but{
		width: 200rpx;
		position: relative;
		z-index: 1;
		background: rgba(0,0,0,.2);
		color: white;
		padding-top: 10rpx;
		padding-bottom: 10rpx;
		text-align: center;
		font-size: 24rpx;
	}
	.title{
		padding-left: 20rpx;
		color: white;
		font-size: 30rpx;
	}
</style>