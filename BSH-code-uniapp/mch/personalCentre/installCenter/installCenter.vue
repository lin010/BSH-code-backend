<template>
	<view class="installCenter-app">
		<view class="installCenter-set">
			<view @click="link(1)">
				<image :src="img_url+'/accountSettings.png'" mode=""></image>
				<text>帐号设置</text>
				<image :src="img_url+'/arrow-right.png'" mode=""></image>
			</view>
			<view v-if="!is_sub" @click="link(6)">
				<image :src="img_url+'/accountSettings.png'" mode=""></image>
				<text>子帐号管理</text>
				<image :src="img_url+'/arrow-right.png'" mode=""></image>
			</view>
			<view @click="link(2)">
				<image :src="img_url+'/shopSetting.png'" mode=""></image>
				<text>店铺设置</text>
				<image :src="img_url+'/arrow-right.png'" mode=""></image>
			</view>
			<view  @click="link(4)">
				<image :src="img_url+'/pass_LOGO.png'" mode=""></image>
				<text>交易密码设置</text>
				<image :src="img_url+'/arrow-right.png'" mode=""></image>
			</view>
			<view  @click="link(3)">
				<image :src="img_url+'/money.png'" mode=""></image>
				<text>结算设置</text>
				<image :src="img_url+'/arrow-right.png'" mode=""></image>
			</view>
			<view  @click="link(5)">
				<image :src="img_url+'/editphone.png'" mode=""></image>
				<text>绑定手机号码</text>
				<image :src="img_url+'/arrow-right.png'" mode=""></image>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				phone:'',
				is_sub: false
			};
		},
		onLoad() {
			if(uni.getStorageSync('x-sub-mch-id') > 0){
				this.is_sub = true;
			}
		},
		methods:{
			link(index){
				let baseInfo = uni.getStorageSync("mchMessage");
				if(index==1){
					uni.navigateTo({
						url:'./accountSetting/accountSetting'
					})
				}
				if(index==6){
					uni.navigateTo({
						url:'../subAccount/subAccount'
					})
				}
				if(index==2){
					uni.navigateTo({
						url:'../shopSetting/shopSetting'
					})
				}
				if(index==3){
					uni.navigateTo({
						url:'../countSet/countSet'
					})
				}
				if(index==4){
					
					if(baseInfo){
						uni.navigateTo({
							url:'../personalCentreSETPassWorde/personalCentreSETPassWorde?phone='+baseInfo.bind_mobile
						})
					}else{
						uni.navigateTo({
							url:'../personalCentreSETPassWorde/personalCentreSETPassWorde'
						})
					}
				}
				if(index==5){
					if(baseInfo){
						uni.navigateTo({
							url:'../editePhone/editePhone?phone='+baseInfo.bind_mobile+"&title="+"绑定手机"
						})
					}else{
						uni.navigateTo({
							url:'../editePhone/editePhone'
						})
					}
				}
			}
		}
	}
</script>

<style lang="less" scoped>
	.installCenter-app{width: 100%;height: 100%;}
	.installCenter-set{width: 100%;overflow: hidden;padding-top: 100rpx;}
	.installCenter-set view{width: 90%;overflow: hidden;margin: 0 auto 50rpx;border-radius: 10rpx;
	box-shadow: 0 3rpx 20rpx rgba(183, 183, 183, 0.5);box-sizing: border-box;padding: 0rpx 20rpx;}
	.installCenter-set view image:nth-of-type(1){float: left;display: block;width: 40rpx;height: 40rpx;margin-top: 25rpx;}
	.installCenter-set view text{float: left;display: block;line-height: 90rpx;font-size: 30rpx;color: #000;margin-left: 30rpx;}
	.installCenter-set view image:nth-of-type(2){float: right;display: block;width: 40rpx;height: 40rpx;margin-top: 25rpx;}
</style>
