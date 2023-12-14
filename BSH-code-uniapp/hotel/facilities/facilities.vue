<template>
	<view class="facilities-app">
		<view class="should-know">
			<view class="title">
				入住需知
			</view>
			<view class="should-know-prduc">
				<text>不能接待外宾,不能携带宠物</text>
			</view>
		</view>
		<view class="facilities">
			<view class="title">
				设施服务
			</view>
			<view class="facilities-list">
				<text>免费WIFI</text>
				<text>免费停车</text>
				<text>行李寄存</text>
				<text>24小时前台服务</text>
				<text>公共区域闭路电视监控系统</text>
				<text>公共休息区</text>
			</view>
		</view>
		<view class="hotel-introduce">
			<view class="title">
				酒店介绍
			</view>
			<view class="introduce">
				{{hotelProduct.descript}}
			</view>
		</view>
		<view class="phone">
			<text style="font-weight: bold;color: #000;">联系电话</text>
			<text style="font-size: 28rpx;" v-if="hotelProduct.contact_phone.length>0">{{hotelProduct.contact_phone}}</text>
			<text style="font-size: 28rpx;" v-if="hotelProduct.contact_mobile.length>0">{{hotelProduct.contact_mobile}}</text>
			<image :src="img_url+'hotel-my-phone-new.png'" mode="" v-if="hotelProduct.contact_phone.length>0"  @click="callphone(hotelProduct.contact_phone)"></image>
				<image :src="img_url+'hotel-my-phone-new.png'" mode="" v-if="hotelProduct.contact_mobile.length>0"  @click="callphone(hotelProduct.contact_mobile)"></image>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				hotelProduct:uni.getStorageSync('hotelProduct')
			};
		},
		methods:{
			callphone(phone){ //联系电话
				uni.makePhoneCall({
				 	// 手机号
				    phoneNumber: phone, 				
					// 成功回调
					success: (res) => {
						console.log('调用成功!')	
					},				
					// 失败回调
					fail: (res) => {
						console.log('调用失败!')
					}					
				 })
			},	
		}
	}
</script>

<style lang="less">
	.facilities-app{width: 100%;overflow: hidden;}
	.should-know{width: 100%;overflow: hidden;background: #fff;margin: 10rpx 0 0 0;padding: 0 30rpx;box-sizing: border-box;}
	.title{width: 100%;height: 80rpx;line-height: 80rpx;color: #000;font-weight: bold;}
	.should-know-prduc{width: 100%;overflow: hidden;margin: 0 0 30rpx 0;}
	.should-know-prduc text{display: block;width: 100%;overflow: hidden;font-size: 28rpx;}
	.facilities{width: 100%;overflow: hidden;background: #fff;margin: 10rpx 0 0 0;padding: 0 30rpx;box-sizing: border-box;}
	.facilities-list{width: 100%;overflow: hidden;margin: 0 0 30rpx 0;display: flex;justify-content: space-evenly;flex-wrap: wrap;font-size: 28rpx;}
	.facilities-list text{display: block;margin: 5rpx 0;}
	.hotel-introduce{width: 100%;overflow: hidden;background: #fff;margin: 10rpx 0 0 0;padding: 0 30rpx;box-sizing: border-box;}
	.introduce{font-size: 30rpx;margin-bottom: 20rpx;}
	.phone{width: 100%;height: 100rpx;background: #fff;margin: 20rpx 0 100rpx 0;padding: 0 30rpx;box-sizing: border-box;line-height: 100rpx;display: flex;justify-content: space-between;}
	.phone image{display: block;width: 50rpx;height: 50rpx;margin-top: 25rpx;}
</style>
