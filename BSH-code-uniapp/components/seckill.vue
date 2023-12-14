<template>
	<view class="seckill_container">
		<view class="seckill_container_top">
			<image :src="plugins_img_url+'/sss.jpg'" mode="widthFix" style="width: 120rpx;display: block;height: 116rpx;"></image>
			<view style="font-size: 28rpx;color: #000;line-height: 112rpx;width: 50%;padding-left: 10rpx;">
				开始时间：2021-11-12
			</view>
			<view style="font-size: 24rpx;color: #000;line-height: 112rpx;color: red;width: 36%;text-align: right;">更多好货限时疯抢></view>
		</view>
		<scroll-view class="s-c-list-x" scroll-x="true">
			<view class="s-c-l-item" v-for="item in 6" :key='item'>
				<image src="https://cbu01.alicdn.com/img/ibank/O1CN01deq9NU1fcPedZD6jc_!!2208038164027-0-cib.jpg"
				mode="aspectFit" style="display: block;width: 100%;height: 140rpx;"></image>
				<view style="width: 100%;height: 60rpx;color: red;font-size: 28rpx;text-align: center;margin-top: 10rpx;">￥199</view>
			</view>
		</scroll-view>
	</view>
</template>

<script>
	export default {
		name:"seckill",
		data() {
			return {
				plugins_img_url: this.$api.plugins_img_url,
			};
		}
	}
</script>

<style lang="less">
	.seckill_container{width: 100%;overflow: hidden;margin-top: 100rpx;background:rgb(240,240,240);padding: 20rpx 0;}
	.s-c-list-x{white-space: nowrap;width: 100%;height: 200rpx;color:#fff;overflow: hidden;margin: 20rpx 0;}
	.s-c-list-x .s-c-l-item{display: inline-block; width: 180rpx;height: 220rpx;margin-right: 10rpx;text-align: center;}
	.seckill_container_top{padding: 0 20rpx;box-sizing: border-box;display: flex;justify-content: space-evenly;}


</style>
