<template>
	<view class="accountSetting-app">
		<view class="accountSetting-main">
			<view>
				<text>商户账号：</text>
				<input type="text" value="" placeholder="请填写" v-model="form.username" />
			</view>
			<view>
			<text style="padding-right: 40rpx;box-sizing: border-box;">密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码：</text>
				<input type="password" value="" placeholder="密码" v-model="form.password" />
			</view>
			<view>
				<text>重新输入：</text>
				<input type="password" value="" placeholder="再次输入密码" v-model="againpassword" />
			</view>
			<view>
				<button type="default" class="btn" @click="sureBtn">确定</button>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		isEmpty
	} from '../../../../common/validate.js'
	export default {
		data() {
			return {
				form: {
					username: '',
					password: ''
				},
				againpassword: ''
			};
		},
		methods: {
			sureBtn() {
				if (isEmpty(this.form.username)) {
					uni.showToast({
						title: '请填写商户账号',
						icon: 'none'
					});
					setTimeout(function() {
						uni.hideToast();
					}, 2000);
					return
				}
				if (isEmpty(this.form.password)) {
					uni.showToast({
						title: '请填写密码',
						icon: 'none'
					});
					setTimeout(function() {
						uni.hideToast();
					}, 2000);
					return
				}
				if (this.againpassword != this.form.password) {
					uni.showToast({
						title: '请再次输入密码或两次输入密码不一致',
						icon: 'none'
					});
					setTimeout(function() {
						uni.hideToast();
					}, 2000);
					return
				}
				this.$http.request({
					url: this.$api.moreShop.setShoppsaa,
					method: 'POST',
					showLoading: true,
					data: this.form
				}).then(res => {
					if (res.code == 0) {
						uni.navigateTo({
							url: '../../personalCentre'
						})
					} else {
						uni.showToast({
							title: res.msg,
							icon: 'none'
						});
					}
				})
			}
		}
	}
</script>

<style lang="less" scoped>
	.accountSetting-app {
		width: 100%;
		height: 100%;
		background: url(https://dev.mingyuanriji.cn/web/static/account_set_back.jpg)no-repeat;
		background-size: cover;
		padding-top: 180rpx;
	}

	.accountSetting-main {
		width: 95%;
		background: #fff;
		height: 700rpx;
		margin: 0 auto;
		box-shadow: 0 3rpx 20rpx rgba(183, 183, 183, 0.5);
		border-radius: 20rpx;
		box-sizing: border-box;
		padding: 60rpx 20rpx 0 20rpx;
	}

	.accountSetting-main view {
		display: flex;
		height: 100rpx;
		width: 100%;
		box-sizing: border-box;
		margin-bottom: 40rpx;
	}

	.accountSetting-main view text {
		width: 200rpx;
		height: 100rpx;
		display: block;
		line-height: 100rpx;
		color: #000000;
	}

	.accountSetting-main view input {
		border-bottom: 1rpx solid #B0B0B0;
		height: 100rpx;
		width: 70%;
	}

	.btn {
		width: 90%;
		color: #fff;
		background: #FF7104;
		border-radius: 45rpx;
		line-height: 100rpx;
	}

	.accountSetting-main view:nth-of-type(3) {
		margin-bottom: 80rpx;
		height: 80rpx;
	}
</style>
