<template>
	<view class="login-root">
		<view v-if="!is_weixn ">
			<view class="avatar">
				<image class="avatar-img" :src="logo_img" mode="" v-if="logo_img"></image>
				<image class="avatar-img" :src="img_url+'images/login/user.png'" mode="" v-else></image>
			</view>
			<!-- #ifdef H5 || APP-PLUS || MP-WEIXIN -->
			<view class="login-content">
				<view class="common">
					<view class="iconCss iconfont icon-shouji"></view>
					<input class="com-inp" type="number" v-model="dataForm.username" placeholder="请输入您的手机号" />
				</view>
				<view class="common">
					<view class="iconCss iconfont icon-mima"></view>
					<input class="com-inp" v-model="dataForm.captcha" placeholder="请输入您的验证码" />
					<view class="get-code" :style="{color:'#FF7104'}" @tap="getCode" v-if="is_show_code">获取验证码</view>
					<view class="get-code get-code2" :style="{color:'#FF7104'}" v-else>重新发送({{countdown}})</view>
				</view>
				<view class="common login-btn" @tap="pwdLogin" :style="{background:'#FF7104'}">登录</view>
				
				<view class="otherSelect">
					<view @tap="goRes">用户注册</view>
					<!-- <view @tap="forget">忘记密码</view> -->
				</view>
			</view>
			<!-- #endif -->
			<!-- #ifdef MP-WEIXIN -->
			<view class="shop-name">兰亭名品</view>
			<view class="line"></view>
			<view class="login-tips">
				<view>登陆后开发者将会以下权限</view>
				<view class="login-tips2">· 获得你的公开信息（昵称、头像等）</view>
			</view>
			<view class="wx-login-btn">
				<view class="iconfont icon-weixin2 wx-login-btn-icon"></view>
				<button class="jx-info" @getuserinfo="login('wx')" type="primary" open-type="getUserInfo" style="margin-left: 0;">授权微信登录</button>
			</view>
			<!-- #endif -->
		</view>
		<view class="center" v-else>
			<view class="load-container load">
				<div class="loader">登录中~~~</div>
			</view>
			<view class="desc">正在登录中，请稍等~</view>
		</view>
	</view>
</template>

<script>
	import base from '@/config/baseUrl';
	import {
		isWeChat,
		isNullOrEmpty,
		isMobile
	} from '@/utils/util.js';
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				dataForm: {
					username: '',
					captcha: ''
				},
				is_weixn: false,
				textColor: '',
				is_show_code: true,
				countdown: 60,
				logo_img:'',
				user_id:''
			};
		},
		onLoad(options) {
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
				this.logo_img=JSON.parse(uni.getStorageSync('mall_config')).mall_setting.setting.logo;
			}
			//#ifdef H5
			this.user_id = this.$route.query.user_id !== undefined ? this.$route.query.user_id : 0;
			if(this.$route.query.user_id !== undefined){
				uni.setStorageSync('user_id',this.user_id);
			}
			//#endif

			if (this.$http.isLogin()) {
				this.$http.toast('您已登录，请勿重新登录');
				// #ifdef H5
				setTimeout(() => {
					window.history.go(1);
				}, 1000);
				// #endif
			}
			if (isWeChat()) {
				this.is_weixn = true;

				// 通过链接 获取 code
				let code = this.$http.getUrlParam('code');

				if (!code) {
					// code 不存在进入此判断
					let _baseUrl = window.location.href;
					_baseUrl = encodeURIComponent(_baseUrl);
					// console.log(_baseUrl);
	let url =
		`https://open.weixin.qq.com/connect/oauth2/authorize?appid=${base.publicAppId}&redirect_uri=${_baseUrl}&response_type=code&scope=snsapi_userinfo#wechat_redirect`;
					// window.location.href = url;
					window.location.replace(url);
				} else {
					// 获取当前 URL
					let temp = document.URL.match(/\?.*#/)[0];
					if (temp.match(/=.*&/)) {
						// 解析code
						let _code = temp.match(/=.*&/)[0];
						_code = _code.substr(1, _code.length); // 去掉 ?
						_code = _code.substr(0, _code.length - 1); // 去掉 #
						// 重置URL(去掉code参数，避免重复调用)
						window.history.replaceState({}, 0, document.URL.replace(temp.substr(0, temp.length - 1), ''));
						// 通过code请求获取openId或者用户信息
						this.wxLogin(code);
					}
				}
			}
		},
		methods: {
			goRes() {
				uni.navigateTo({
					url: '/pages/public/registered'
				});
			},
			getCode() {
				this.is_show_code = false;
				var timer = setInterval(() => {
					this.countdown--;
					if (this.countdown <= 0) {
						this.countdown = 60;
						this.is_show_code = true;
						clearInterval(timer);
					}
				}, 1000)

				this.$http.request({
					url: this.$api.default.phoneCode,
					method: 'post',
					data: {
						mobile: this.dataForm.username,
						is_register: 0
					}
				}).then(res => {
					if (res.code != 0) {
						this.$http.toast(res.msg);
					}
				})
			},
			wxLogin(code) {
				this.$http
					.request({
						url: this.$api.default.wxLogin,
						data: {
							code: code,
							mall_id: uni.getStorageSync('mall_id'),
							stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
						}
					})
					.then(res => {
						this.$http.toast(res.msg);
						if (res.code === 0) {
							let {
								access_token,
								config,
								key
							} = res.data;
							access_token = access_token.trim();
							// 判断 access_token 是否存在 并且 判断 系统是否开启了全网通
							if (!access_token.length && config.all_network_enable == 1) {
								// 开启了全网通 并且 access_token 跳转到 绑定手机号的页面
								uni.redirectTo({
									url: `/pages/public/bind?key=${key}&user_id=` + this.user_id								
								});
								return;
							}
							// 否则保存 access_token 到缓存 跳转到上次的页面 或者个人页 跳转后删除 _login_pre_url 的缓存
							this.$http.setToken(access_token);
							let url = uni.getStorageSync('_login_pre_url') ? uni.getStorageSync('_login_pre_url') : '/pages/user/index';
							this.$http.setUserInfo();
							// uni.reLaunch({
							// 	url: url,
							// 	success: function(e) {
							// 		uni.removeStorageSync('_login_pre_url');
							// 	}
							// })
							uni.redirectTo({
								url: url,
								success: function(e) {
									uni.removeStorageSync('_login_pre_url');
								}
							});
						}
					});
			},
			forget() {
				uni.navigateTo({
					url: '/pages/public/forget'
				});
			},
			login(key) {
				let _login_key;
				uni.getProvider({
					service: 'oauth',
					success: res => {
						_login_key = res.provider[0];
					}
				});

				// #ifdef MP-WEIXIN
				uni.login({
					provider: _login_key,
					scopes: 'auth_user',
					success: (loginRes) => {
						wx.getUserInfo({
							success: (userinfoRes) => {
								let {
									encryptedData,
									iv,
									rawData,
									signature
								} = userinfoRes;
								this.$http.request({
									url: this.$api.default.mini_login,
									method: 'POST',
									showLoading: true,
									data: {
										code: loginRes.code,
										encryptedData: encryptedData,
										iv: iv,
										stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
									}
								}).then(res => {
									this.$http.toast(res.msg);
									if (res.code == 0) {
										// if(uni.getStorageSync('parent_user_id')){
										// 	uni.setStorageSync('new_user',1);
										// }
										this.$http.setToken(res.data.access_token);
										if (res.data.close_auth_bind == '0' && !res.data.mobile) {
											uni.redirectTo({
												url: "/pages/public/authorization?form=login&key=" + res.data.key
											})
										} else {
											uni.navigateBack();
										}
									}
								})
							}
						})

					},
					complete: () => {}
				});
				// #endif
			},
			pwdLogin() {
				if (!isNullOrEmpty(this.dataForm.username) || !isMobile(this.dataForm.username)) {
					this.$http.toast('请输入正确的手机号');
					return;
				}
				if (!isNullOrEmpty(this.dataForm.captcha)) {
					this.$http.toast('密码不能为空');
					return;
				}
				this.$http
					.request({
						url: this.$api.default.login,
						data: this.dataForm,
						method: 'POST',
						showLoading: true
					})
					.then(res => {
						this.$http.toast(res.msg);
						if (res.code == 0) {
							
							this.$http.setToken(res.data.access_token);
							console.log(res.data.access_token)
							this.$http.setUserInfo();
							let url = uni.getStorageSync('_login_pre_url') ? uni.getStorageSync('_login_pre_url') : '/pages/user/index';
							uni.removeStorageSync('_login_pre_url');
							setTimeout(() => {
								uni.redirectTo({
									url
								});
							}, 1000);
						}
					});
			}
		}
	};
</script>

<style scoped>
	.wx-box {
		position: relative;
	}

	.wx-login-btn {
		position: relative;
	}

	.wx-login-btn-icon {
		color: #fff;
		font-size: 52rpx;
		position: absolute;
		left: 31%;
		top: 20rpx;
		z-index: 9;
		line-height: 50rpx;
	}

	.jx-info {
		position: absolute;
		left: 50%;
		transform: translateX(-50%);
		width: 540rpx;
		height: 84rpx;
		line-height: 84rpx;
		font-size: 30rpx;
		letter-spacing: 1px;
		padding-left: 80rpx;
		/* opacity: 0; */
	}

	.login-root {
		min-height: 100%;
		display: flex;
		flex-direction: column;
	}

	page {
		background: #ffffff !important;
	}

	.avatar {
		text-align: center;
		padding: 100rpx 0 20rpx;
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

	.fixed {
		position: fixed;
		min-width: 100%;
		display: flex;
		justify-content: center;
	}

	.fixed .iconfont {
		color: #2ba246;
		font-size: 24pt;
		line-height: 100%;
	}

	.common {
		height: 90rpx;
		border: 2rpx solid #f3f3f3;
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
		width: 90%;
		flex: 1;
	}

	.get-code {
		background: #f5f5f5;
		font-size: 26rpx;
		letter-spacing: 1px;
		color: #FFFFFF;
		padding: 4rpx 20rpx;
		border-radius: 30rpx;
	}

	.get-code2 {
		background: transparent;
		padding: 0;
	}

	.login-btn {
		background: #bc0100;
		border: 0;
		color: #ffffff;
		justify-content: center;
		font-size: 10pt;
		letter-spacing: 4rpx;
		margin-bottom: 28rpx;
	}

	.otherSelect {
		display: flex;
		justify-content: space-between;
		font-size: 9pt;
		color: #8a8a8a;
		padding: 0 30rpx;
		box-sizing: border-box;
	}

	/* 微信公众号授权登录 */
	.center {
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: #f7f7f7;
		flex-direction: column;
		flex: 1;
	}

	.center .desc {
		color: #7f7f7f;
		font-size: 13pt;
		font-weight: 500;
	}

	.load .loader {
		margin: 4em auto;
		font-size: 20px;
		width: 1em;
		height: 1em;
		border-radius: 50%;
		position: relative;
		text-indent: -9999em;
		-webkit-animation: load 1.1s infinite ease;
		animation: load 1.1s infinite ease;
	}

	@-webkit-keyframes load {

		0%,
		100% {
			box-shadow: 0em -2.6em 0em 0em #f04a4a, 1.8em -1.8em 0 0em rgba(244, 216, 214), 2.5em 0em 0 0em rgba(244, 216, 214), 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(244, 216, 214), -1.8em 1.8em 0 0em rgba(244, 216, 214), -2.6em 0em 0 0em rgba(244, 216, 214), -1.8em -1.8em 0 0em rgba(247, 182, 182);
		}

		12.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(247, 182, 182), 1.8em -1.8em 0 0em #f04a4a, 2.5em 0em 0 0em rgba(244, 216, 214), 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(244, 216, 214), -1.8em 1.8em 0 0em rgba(244, 216, 214), -2.6em 0em 0 0em rgba(244, 216, 214), -1.8em -1.8em 0 0em rgba(244, 216, 214);
		}

		25% {
			box-shadow: 0em -2.6em 0em 0em rgba(244, 216, 214), 1.8em -1.8em 0 0em rgba(247, 182, 182), 2.5em 0em 0 0em #f04a4a, 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(244, 216, 214), -1.8em 1.8em 0 0em rgba(244, 216, 214), -2.6em 0em 0 0em rgba(244, 216, 214), -1.8em -1.8em 0 0em rgba(244, 216, 214);
		}

		37.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(244, 216, 214), 1.8em -1.8em 0 0em rgba(244, 216, 214), 2.5em 0em 0 0em rgba(247, 182, 182), 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(244, 216, 214), -1.8em 1.8em 0 0em rgba(244, 216, 214), -2.6em 0em 0 0em rgba(244, 216, 214), -1.8em -1.8em 0 0em rgba(244, 216, 214);
		}

		50% {
			box-shadow: 0em -2.6em 0em 0em rgba(244, 216, 214), 1.8em -1.8em 0 0em rgba(244, 216, 214), 2.5em 0em 0 0em rgba(244, 216, 214), 1.75em 1.75em 0 0em rgba(247, 182, 182),
				0em 2.5em 0 0em #f04a4a, -1.8em 1.8em 0 0em rgba(244, 216, 214), -2.6em 0em 0 0em rgba(244, 216, 214), -1.8em -1.8em 0 0em rgba(244, 216, 214);
		}

		62.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(244, 216, 214), 1.8em -1.8em 0 0em rgba(244, 216, 214), 2.5em 0em 0 0em rgba(244, 216, 214), 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(247, 182, 182), -1.8em 1.8em 0 0em #f04a4a, -2.6em 0em 0 0em rgba(244, 216, 214), -1.8em -1.8em 0 0em rgba(244, 216, 214);
		}

		75% {
			box-shadow: 0em -2.6em 0em 0em rgba(244, 216, 214), 1.8em -1.8em 0 0em rgba(244, 216, 214), 2.5em 0em 0 0em rgba(244, 216, 214), 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(244, 216, 214), -1.8em 1.8em 0 0em rgba(247, 182, 182), -2.6em 0em 0 0em #f04a4a, -1.8em -1.8em 0 0em rgba(244, 216, 214);
		}

		87.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(244, 216, 214), 1.8em -1.8em 0 0em rgba(244, 216, 214), 2.5em 0em 0 0em rgba(244, 216, 214), 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(244, 216, 214), -1.8em 1.8em 0 0em rgba(244, 216, 214), -2.6em 0em 0 0em rgba(247, 182, 182), -1.8em -1.8em 0 0em #f04a4a;
		}
	}

	@keyframes load {

		0%,
		100% {
			box-shadow: 0em -2.6em 0em 0em #f04a4a, 1.8em -1.8em 0 0em rgba(244, 216, 214), 2.5em 0em 0 0em rgba(244, 216, 214), 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(244, 216, 214), -1.8em 1.8em 0 0em rgba(244, 216, 214), -2.6em 0em 0 0em rgba(244, 216, 214), -1.8em -1.8em 0 0em rgba(247, 182, 182);
		}

		12.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(247, 182, 182), 1.8em -1.8em 0 0em #f04a4a, 2.5em 0em 0 0em rgba(244, 216, 214), 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(244, 216, 214), -1.8em 1.8em 0 0em rgba(244, 216, 214), -2.6em 0em 0 0em rgba(244, 216, 214), -1.8em -1.8em 0 0em rgba(244, 216, 214);
		}

		25% {
			box-shadow: 0em -2.6em 0em 0em rgba(244, 216, 214), 1.8em -1.8em 0 0em rgba(247, 182, 182), 2.5em 0em 0 0em #f04a4a, 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(244, 216, 214), -1.8em 1.8em 0 0em rgba(244, 216, 214), -2.6em 0em 0 0em rgba(244, 216, 214), -1.8em -1.8em 0 0em rgba(244, 216, 214);
		}

		37.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(244, 216, 214), 1.8em -1.8em 0 0em rgba(244, 216, 214), 2.5em 0em 0 0em rgba(247, 182, 182), 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(244, 216, 214), -1.8em 1.8em 0 0em rgba(244, 216, 214), -2.6em 0em 0 0em rgba(244, 216, 214), -1.8em -1.8em 0 0em rgba(244, 216, 214);
		}

		50% {
			box-shadow: 0em -2.6em 0em 0em rgba(244, 216, 214), 1.8em -1.8em 0 0em rgba(244, 216, 214), 2.5em 0em 0 0em rgba(244, 216, 214), 1.75em 1.75em 0 0em rgba(247, 182, 182),
				0em 2.5em 0 0em #f04a4a, -1.8em 1.8em 0 0em rgba(244, 216, 214), -2.6em 0em 0 0em rgba(244, 216, 214), -1.8em -1.8em 0 0em rgba(244, 216, 214);
		}

		62.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(244, 216, 214), 1.8em -1.8em 0 0em rgba(244, 216, 214), 2.5em 0em 0 0em rgba(244, 216, 214), 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(247, 182, 182), -1.8em 1.8em 0 0em #f04a4a, -2.6em 0em 0 0em rgba(244, 216, 214), -1.8em -1.8em 0 0em rgba(244, 216, 214);
		}

		75% {
			box-shadow: 0em -2.6em 0em 0em rgba(244, 216, 214), 1.8em -1.8em 0 0em rgba(244, 216, 214), 2.5em 0em 0 0em rgba(244, 216, 214), 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(244, 216, 214), -1.8em 1.8em 0 0em rgba(247, 182, 182), -2.6em 0em 0 0em #f04a4a, -1.8em -1.8em 0 0em rgba(244, 216, 214);
		}

		87.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(244, 216, 214), 1.8em -1.8em 0 0em rgba(244, 216, 214), 2.5em 0em 0 0em rgba(244, 216, 214), 1.75em 1.75em 0 0em rgba(244, 216, 214),
				0em 2.5em 0 0em rgba(244, 216, 214), -1.8em 1.8em 0 0em rgba(244, 216, 214), -2.6em 0em 0 0em rgba(247, 182, 182), -1.8em -1.8em 0 0em #f04a4a;
		}
	}

	.shop-name {
		text-align: center;
		font-size: 36rpx;
		color: #000000;
		letter-spacing: 1px;
	}

	.line {
		background: #DCDCDC;
		height: 1px;
		width: 620rpx;
		margin: 30rpx auto 40rpx;
	}

	.login-tips {
		width: 620rpx;
		margin: auto;
		padding: 0 30rpx;
		color: #000000;
		box-sizing: border-box;
	}

	.login-tips2 {
		padding-left: 20rpx;
		color: #A0A0A0;
		font-size: 28rpx;
		margin: 20rpx 0 50rpx;
	}
</style>
