<template>
	<view class="login-root">
		<view>
			<view class="login-content">
				<view class="common">
					<view class="iconCss iconfont icon-shouji"></view>
					<input class="com-inp" type="number" v-model="dataForm.new_mobile" placeholder="请输入您的手机号" />
				</view>
				<view class="common code">
					<view class="code-left">
						<view class="iconCss iconfont icon-yanzhengma"></view>
						<input class="com-inp" type="number" v-model="dataForm.captcha" placeholder="验证码" />
					</view>
					<view v-if="!countDown" class="code-right" @tap="getCode">
						获取验证码
					</view>
					<view v-else class="code-right">
						{{countDown}}
					</view>
				</view>
				<view class="common login-btn" @tap="submit" :style="{background:textColor}">
					绑定手机
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
				dataForm: {
					new_mobile: '', //手机号
					captcha: '', //手机验证码
					auth_key:'',//key
				},
				countDown: '',
				textColor:'',
			}
		},
		onLoad(options) {
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
			if(options.key){
				this.dataForm.auth_key= options.key;
			}
		},
		methods: {
			submit() {
				let _self = this;
				let {new_mobile,captcha,auth_key} = _self.dataForm;
				if (!isNullOrEmpty(new_mobile) || !isMobile(new_mobile)) {
					_self.$http.toast("请输入正确的手机号")
					return;
				}
				if(!isNullOrEmpty(captcha)){
					_self.$http.toast("请输入验证码")
					return;
				}
				// 验证通过 发送请求
				_self.$http.request({
					url: this.$api.moreShop.updatePhone,
					data: {
						new_mobile,
						captcha,
						auth_key,
					},
					method: 'POST',
					showLoading: true
				}).then((res) => {
					_self.showMsg(res.msg);
					if (res.code == 0) {
						if(uni.getStorageSync('userInfo')){
							let users=JSON.parse(uni.getStorageSync('userInfo'))
							users.mch_info.store.mobile=this.dataForm.new_mobile
							uni.setStorageSync('userInfo',JSON.stringify(users))							
						}
						uni.navigateTo({
							url:'../personalCentre'
						})
					}
				})
			},
			getCode() {
				if (!isMobile(this.dataForm.new_mobile)) {
					this.showMsg('请输入手机号后在获取验证码');
					return;
				}
				let _self = this;
				_self.countDown = 60;
				let temp = setInterval(() => {
					_self.countDown--
					if (_self.countDown <= 0) {
						clearInterval(temp);
					}
				}, 1000)

				_self.$http.request({
					url: _self.$api.default.phoneCode,
					data: {
						mobile: _self.dataForm.new_mobile
					},
					method: 'POST'
				}).then((res) => {
					this.$http.toast(res.msg);
				}).catch(err => {
					console.log(err);
				})
			},
			showMsg(msg) {
				uni.showToast({
					icon: 'none',
					title: msg
				})
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
