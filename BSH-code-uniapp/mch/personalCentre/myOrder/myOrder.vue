<template>
	<view class="installCenter-app">
		<view class="installCenter-set" @click="link(1)">
			<view >
				<image :src="img_url+'/accountSettings.png'" mode=""></image>
				<text>本地生活</text>
				<image :src="img_url+'/arrow-right.png'" mode=""></image>
			</view>
		</view>
		<view class="installCenter-set" @click="link(2)">
			<view >
				<image :src="img_url+'/accountSettings.png'" mode=""></image>
				<text>收款记录</text>
				<image :src="img_url+'/arrow-right.png'" mode=""></image>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url
			};
		},
		onLoad() {

		},
		methods:{
			link(index){ //跳转到订单
				if(index==1){
					uni.navigateTo({
						url:'./orderList'
					})
				}
				if(index==2){
					uni.navigateTo({
						url:'./collectionlist'
					})
				}
			}
		}
	}
</script>

<style lang="less" scoped>
	.installCenter-app{width: 100%;height: 100%;}
	.installCenter-set{width: 100%;overflow: hidden;padding-top: 70rpx;}
	.installCenter-set view{width: 90%;overflow: hidden;margin: 0 auto 20rpx;border-radius: 10rpx;
	box-shadow: 0 3rpx 20rpx rgba(183, 183, 183, 0.5);box-sizing: border-box;padding: 0rpx 20rpx;box-sizing: border-box;}
	.installCenter-set view image:nth-of-type(1){float: left;display: block;width: 40rpx;height: 40rpx;margin-top: 25rpx;}
	.installCenter-set view text{float: left;display: block;line-height: 90rpx;font-size: 30rpx;color: #000;margin-left: 30rpx;}
	.installCenter-set view image:nth-of-type(2){float: right;display: block;width: 40rpx;height: 40rpx;margin-top: 25rpx;}
</style>

