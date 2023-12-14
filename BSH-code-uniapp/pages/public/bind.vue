<template>
	<view class="login-root">
		<view>
			<view class="avatar">
				<image class="avatar-img" :src="img_url+'images/login/user.png'" mode=""></image>
			</view>
			<view class="login-content">
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
	import {
		isEmpty
	} from '../../common/validate.js';
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				parentInfo:{},
				dataForm: {
					recommend_id:0,//邀请人ID
					mobile: '', //手机号
					captcha: '' //手机验证码
				},
				countDown: '',
				key: '',
				textColor:'',
				user_id:''
			}
		},
		onLoad(options) {
			//#ifdef H5
			if(uni.getStorageSync('user_id')){
				this.user_id = uni.getStorageSync('user_id');
			}else{
				this.user_id = uni.getStorageSync('pid') ? uni.getStorageSync('pid') : 0;
			}
			//#endif
			if(options.key){
				this.key = options.key
			}
			if(uni.getStorageSync('pid')){
				this.dataForm.recommend_id = uni.getStorageSync('pid')
			}else{
				this.dataForm.recommend_id = uni.getStorageSync('user_id')
			}
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}					
		},
		mounted() {
			//#ifdef H5
				var a = document.getElementsByClassName('uni-page-head-hd')[0];
				a.style.display = 'none';	
			//#endif
		},
		methods: {
			submit() {
				
				let _self = this;
				let key = this.key.trim();
				let {recommend_id,mobile,captcha} = _self.dataForm;
				if (!isNullOrEmpty(mobile) || !isMobile(mobile)) {
					_self.$http.toast("请输入正确的手机号")
					return;
				}
				if(!isNullOrEmpty(captcha)){
					_self.$http.toast("请输入验证码")
					return;
				}
				// 验证通过 发送请求
				_self.$http.request({
					url: key.length ? this.$api.default.bind : this.$api.default.bindPhone,
					data: {
						key,
						recommend_id,
						mobile,
						captcha,
						'user_id':this.user_id,
						stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
					},
					method: 'POST',
					showLoading: true
				}).then((res) => {
					_self.showMsg(res.msg);
					if (res.code == 0) {
						// key 不存在则为 下单时绑定手机号
						if(!key){
							uni.navigateBack()
							return;
						}
						_self.$http.setToken(res.data.access_token);
						let url = uni.getStorageSync("_login_pre_url") ? uni.getStorageSync("_login_pre_url") : '/pages/user/index';
						// uni.setStorageSync('new_user',1);
						uni.removeStorageSync("pid");
						uni.removeStorageSync("user_id");
						uni.removeStorageSync("_login_pre_url");
						
		
						this.$http
							.request({
								url: this.$api.user.userInfo,
								method: 'POST',
								showLoading: true
							})
							.then(res => {
								if (res.code == 0) {
									let userMessage=res.data
									if(userMessage.parent_id>1){									
										uni.redirectTo({
											url
										})
									}else{
										uni.navigateTo({
											url:'./bindParent'
										})	
									}		
								}
							});		
					}
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
						mobile: _self.dataForm.mobile
					},
					method: 'POST'
				}).then((res) => {
					this.$http.toast(res.msg);
				}).catch(err => {
					console.log(err);
				})
			},
			goAgreement() {
				this.$http.toast('跳转用户协议');
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
