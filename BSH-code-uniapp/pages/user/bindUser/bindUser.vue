<template>
	<view class="bindUser">
		<view class="center">
			<image :src="img_url+'/header-logo.png'" mode="" class="shop-logo"></image>
			<view class="title">
				正在跳转，请稍后.....
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
		onLoad(options) {
			this.getBind(options.pid)
		},
		methods:{
			getBind(pid){
				this.$http.request({
					url: this.$api.moreShop.getBindUser,
					showLoading: true,
					data:{
						x_parent_id:pid
					},
				}).then(res => {
					if (res.code == 0) {
						uni.setStorageSync('is_pid_bind', 1);
						uni.navigateBack();
					}else{
						this.$http.toast(res.msg);
					}
				})
			}
		}
	}
</script>

<style lang="less">
	.bindUser{width: 100%;height: 100%;position: relative;}
	.center{width: 100%;height: 500rpx;position: absolute;top: 0;left: 0;right: 0;bottom: 0;margin: auto;}
	.shop-logo{width: 200rpx;height: 200rpx;margin: 0 auto;display: block;}
	.title{width: 100%;overflow: hidden;font-size: 25rpx;text-align: center;margin-top: 20rpx;color: rgb(255, 113, 4);}
	.logo{width: 100%;overflow: hidden;}
	.logo image{width: 160rpx;height: 160rpx;margin: 30rpx auto;display: block;border-radius: 50%;}
	.name{width: 100%;overflow: hidden;font-size: 34rpx;color: #000000;text-align: center;margin: 20rpx 0;}
	.id{width: 100%;overflow: hidden;text-align: center;font-size: 26rpx;}
	.role{width: 100%;overflow: hidden;text-align: center;font-size: 28rpx;margin: 20rpx 0;}
</style>
