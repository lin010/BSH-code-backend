<template>
	<view class="login-root">
		<view>
			<view class="avatar">
				<image class="avatar-img" :src="logo_img" mode="" v-if="logo_img"></image>
				<image class="avatar-img" :src="img_url+'images/login/user.png'" mode="" v-else></image>
			</view>
			<view class="login-content">
				<view class="common">
					<view class="iconCss iconfont icon-shouji"></view>
					<input class="com-inp" type="number" v-model="dataForm.parent_mobile" placeholder="请输入邀请人的手机号" />
				</view>
				<view class="common">
					<view class="iconCss iconfont icon-shouji"></view>
					<input class="com-inp" type="number" v-model="dataForm.mobile" placeholder="请输入您的手机号" />
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
				<!-- <view class="common">
					<view class="iconCss iconfont icon-mima"></view>
					<input class="com-inp" type="password" v-model.trim="dataForm.password" placeholder="请输入您的密码" />
				</view>
				<view class="common">
					<view class="iconCss iconfont icon-mima"></view>
					<input class="com-inp" type="password" v-model.trim="dataForm.confirm_password" placeholder="请再次确认密码" />
				</view> -->
				<view class="common login-btn" @tap="submit" :style="{background:textColor}">
					立即注册
				</view>
				<view class="otherSelect">
					<view class="agreement-box">
						<view class="agreement-btn" @tap="cutover">
							<view class="iconfont icon-dagou1 agreement-icon" v-if="isShow" :style="{color:textColor}"></view>
						</view>
						阅读并同意 <text class="agreement" @tap.stop="goAgreement" :style="{color:textColor}">《用户协议》</text>
					</view>
					<view @tap="goLogin">已有账号立即登陆</view>
				</view>
				<bf-validate :model="dataForm" :rules="dataRule" ref="validator" />
			</view>
		</view>
		<uni-popup ref="popup" type="center">
			<scroll-view scroll-y="true"  class="scroll-Ys">
				<view class="htm" v-html="agreement">
					<rich-text :nodes="agreement"></rich-text>
				</view>
			</scroll-view>
		</uni-popup>
	</view>
</template>

<script>
	import bfValidate from '@/components/bf-validate/bf-validate.vue';
	import {
		isNullOrEmpty,
		isMobile,
		isWeChat
	} from '@/utils/util.js';
	export default {
		components: {
			bfValidate
		},
		data() {
			return {
				img_url: this.$api.img_url,
				parentInfo:{},
				textColor:'',
				logo_img:'',
				dataForm: {
					recommend_id:0,//邀请人ID
					mobile: '', //手机号
					// password: '', //密码
					// confirm_password: '', //确认密码
					captcha: '' //手机验证码
				},
				dataRule: {
					mobile: {
						required: true,
						name: '手机号',
						pattern: /^(?:(?:\+|00)86)?1(?:(?:3[\d])|(?:4[5-7|9])|(?:5[0-3|5-9])|(?:6[5-7])|(?:7[0-8])|(?:8[\d])|(?:9[1|8|9]))\d{8}$/,
						message: '请输入正确的手机号'
					},
					// password: {
					// 	required: true,
					// 	name: '密码',
					// 	pattern: /^[\w]{6,20}$/,
					// 	transform(value) {
					// 		return value.trim();
					// 	},
					// 	message: '密码为6~20位数字和字母组合'
					// },
					captcha: {
						required: true,
						name: '手机验证码',
						message: '请输入获取到的验证码'
					},
				},
				isShow: false,
				countDown: '',
				agreement:''
			}
		},
		onLoad() {
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
				this.logo_img=JSON.parse(uni.getStorageSync('mall_config')).mall_setting.setting.logo;
			}
			if(uni.getStorageSync('pid')){
				this.dataForm.recommend_id = uni.getStorageSync('pid')
			}
			this.getAgreement()
			
		},
		methods: {
			getData() {

			},
			goRes() {
				uni.navigateTo({
					url: '/pages/public/registered'
				})
			},
			goLogin() {
				uni.navigateTo({
					url: '/pages/public/login'
				})
			},
			submit() {
				let _self = this;
				this.$refs.validator.validate()
					.then((res) => {
						// 验证通过
						// if (_self.dataForm.confirm_password != _self.dataForm.password) {
						// 	_self.showMsg('两次输入的密码不一致')
						// 	return;
						// }
						if (!_self.isShow) {
							_self.showMsg('请同意协议后注册')
							return;
						}

						_self.$http.request({
							url: this.$api.default.register,
							data: _self.dataForm,
							method: 'POST',
							showLoading: true
						}).then((res) => {

							this.showMsg(res.msg);
							if (res.code == 0) {
								setTimeout(() => {
									uni.reLaunch({
										url: '/pages/public/login'
									})
								}, 1000)
							}

						})
					})
					.catch(err => {
						console.log(err)
					})
			},
			getCode() {

				if (!isMobile(this.dataForm.mobile)) {
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
						mobile: _self.dataForm.mobile,
						type: 'reg'
					},
					method: 'POST'
				}).then((res) => {
					this.$http.toast(res.msg);
				}).catch(err => {
					console.log(err);
				})
			},
			goAgreement() {
				// this.$http.toast('跳转用户协议');
				this.$refs.popup.open()
			},
			cutover() {
				this.isShow = !this.isShow;
			},
			showMsg(msg) {
				uni.showToast({
					icon: 'none',
					title: msg
				})
			},
			getAgreement(){
				this.$http.request({
					url: this.$api.moreShop.getagreement,
					method: 'get',
					data: {
						mall_id:uni.getStorageSync('mall_id')?uni.getStorageSync('mall_id'):5
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.agreement=res.data.content
					}
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
		width: 200rpx;
		height: 200rpx;
		border-radius: 50%;
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
		width: 220rpx;
		text-align: right;
	}

	.agreement-box {
		display: flex;
		align-items: center;
	}

	.agreement-btn {
		border: 2rpx solid #F3F3F3;
		width: 32rpx;
		height: 32rpx;
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
	.scroll-Ys{width: 60%;overflow: hidden;margin: 0 auto;background: #fff;border-radius: 20rpx;}
	.htm{width: 85%;height: 500rpx;margin: 0 auto;border-radius: 20rpx;font-size: 30rpx;text-align: center;
	line-height: 60rpx;padding: 20rpx;}
</style>
