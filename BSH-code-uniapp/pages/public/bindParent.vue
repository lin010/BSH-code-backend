<template>
	<view class="login-root">
		<view>

			<view class="login-content">
				<!-- <view class="common">
					<view class="iconCss iconfont icon-shouji"></view>
					<input class="com-inp" type="number" v-model="dataForm.parent_mobile" placeholder="请输入邀请人的手机号" />
				</view> -->
				
				<view class="common login-btn" @tap="submit" :style="{background:textColor}">
					点击进入商城
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		isNullOrEmpty,
		isMobile,
		isWeChat
	} from '@/utils/util.js';
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				parentInfo:{},
				dataForm: {
					parent_mobile:'',//邀请人手机号
					mobile: '--', //手机号
					captcha: '--' //手机验证码
				},
				countDown: '',
				key: '',
				textColor:'',
			}
		},
		onLoad(options) {
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
		},
		onBackPress(e) {
			console.log(e);
		    return true;//阻止默认返回行为
		},
		methods: {
			getUser(bool) {
				this.loading = bool;
				this.$http
					.request({
						url: this.$api.user.userInfo,
						method: 'POST'
					})
					.then(res => {
						this.loading = false;
						if (res.code == 0) {
							let recommend_id = res.data.parent_id||0;
							let token = uni.getStorageSync('token') || 0;
							uni.setStorageSync('userInfo', JSON.stringify(res.data));
							uni.setStorageSync('recommend_id', recommend_id);
							let _login_pre_url = uni.getStorageSync("_login_pre_url");
							let url = _login_pre_url ? _login_pre_url : '/pages/user/index';
							uni.redirectTo({
								url
							})
						}
					});
			},
			submit() {
				let _self = this;
				// let {parent_mobile,mobile,captcha} = _self.dataForm;
				// if (!isNullOrEmpty(parent_mobile) || !isMobile(parent_mobile)) {
				// 	_self.$http.toast("请输入正确的我的供货商手机号")
				// 	return;
				// }
				// _self.$http.request({
				// 	url: this.$api.default.bindParent,
				// 	data: {
				// 		parent_mobile
				// 	},
				// 	method: 'POST',
				// 	showLoading: true
				// }).then((res) => {
				// 	_self.$http.toast(res.msg);
				// 	if (res.code == 0) {
				// 		// key 不存在则为 下单时绑定手机号
						let url = uni.getStorageSync('_login_pre_url') ? uni.getStorageSync('_login_pre_url') : '/pages/user/index';
						// setTimeout(() => {
							uni.redirectTo({
								url
							})
				// 		}, 1000)
				// 	}
				// })
			}
		}
	}
</script>

<style scoped>
	page {
		background: #FFFFFF !important;
	}

	.avatar {
		text-align: center;
		padding: 100rpx 0 57rpx;
	}

	.avatar-img {
		width: 168rpx;
		height: 168rpx;
	}

	.login-content {
		padding: 0 30rpx;
		box-sizing: border-box;
	}

	.common {
		height: 90rpx;
		border: 2rpx solid #F3F3F3;
		border-radius: 45rpx;
		padding: 0rpx 42rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		margin-bottom: 50rpx;
	}

	.iconCss {
		font-size: 16pt;
		color: #797979;
		margin-right: 18rpx;
	}

	.com-inp {
		font-size: 10pt;
		letter-spacing: 2rpx;
		flex: 1;
	}

	.login-btn {
		background: #BC0100;
		border: 0;
		color: #FFFFFF;
		justify-content: center;
		font-size: 10pt;
		letter-spacing: 4rpx;
		margin-bottom: 28rpx;
	}

	.otherSelect {
		display: flex;
		justify-content: space-between;
		font-size: 9pt;
		color: #8A8A8A;
		padding: 0 30rpx;
		box-sizing: border-box;
	}

	.agreement {
		color: #BC0100;
	}

	.code {
		justify-content: space-between;
	}

	.code-left {
		display: flex;
		align-items: center;
	}

	.code-right {
		font-size: 10pt;
		color: #8A8A8A;
		letter-spacing: 2rpx;
	}

	.agreement-box {
		display: flex;
		align-items: center;
	}

	.agreement-btn {
		border: 2rpx solid #F3F3F3;
		width: 26rpx;
		height: 26rpx;
		border-radius: 50%;
		margin-right: 16rpx;
		position: relative;
	}

	.agreement-icon {
		position: absolute;
		top: 14rpx;
		left: -2rpx;
		line-height: 0;
		color: #BC0100;
	}

	.show {
		display: block;
	}

	.hide {
		display: none;
	}
</style>
