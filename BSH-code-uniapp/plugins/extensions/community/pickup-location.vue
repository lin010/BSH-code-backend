<template>
	<view class="app">
		<view class="current-location">
			<view class="title">当前自提点</view>
			<view class="location-item">
				<view class="state">
					<view>正在使用</view>
				</view>
				<view class="store-info flex-y-center">
					<view class="store-name">自提点：{{'员村友客来便利店(13710482718)'}}</view>
					<image src="../../images/extensions/community/phone.png" class="phone-img"></image>
				</view>
				<view class="store-detail">提货地点：{{'万乐宫主题酒店(广州市白云区)'}}</view>
			</view>
		</view>
		<view class="history-locations">
			<view class="title">历史自提点</view>
			<view class="location-item" v-for="(item,i) in 2" :key="i">
				<view class="state">
					<view>历史使用</view>
				</view>
				<view class="store-info flex-y-center">
					<view class="store-name">自提点：{{'禅城永新南路南区兴盛店(13710482718)'}}</view>
					<image src="../../images/extensions/community/phone.png" class="phone-img"></image>
				</view>
				<view class="store-detail">提货地点：{{'广东省佛山市禅城区祖庙街道永新南村南区35号1楼'}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				currentPickup: {},
				dataList: []
			};
		}
	}
</script>

<style lang="scss" scoped>
.app{
	height: 100%;
	background-color: #F7F7F7;
}
.current-location,.history-locations{
	background-color: #FFFFFF;
	line-height: 60rpx;
	margin-bottom: 15rpx;
}
.location-item{
	border-top: 1rpx solid #F2F2F2;
	padding: 20rpx 20rpx 15rpx;
	font-size: 24rpx;
	
	.state{
		width:112rpx;
		height:38rpx;
		background: #BC0100;
		border-radius:19rpx;
		color: #FFFFFF;
		line-height: 38rpx;
		text-align: center;
	}
	
	.store-info,.store-detail{
		color: #333333;
	}
	
	.phone-img{
		width: 42rpx;
		height: 42rpx;
		margin-left: 10rpx;
	}
}
.title{
	color: #000000;
	font-size:30rpx;
	padding: 16rpx 20rpx;
}
</style>
