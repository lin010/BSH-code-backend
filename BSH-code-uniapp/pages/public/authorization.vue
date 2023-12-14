<template>
	<view class="authorization-root">
		<com-nav-bar title="授权手机" :status-bar="true" background-color="#ffffff" :border="false" color="#000000"></com-nav-bar>
		<view v-if="!is_weixn">
			<view class="avatar">
				<image class="avatar-img" :src="logo_img" mode="" v-if="logo_img"></image>
				<image class="avatar-img" :src="img_url+'images/login/user.png'" mode="" v-else></image>
			</view>
			<view class="login-content">
				<view class="common">
					<view class="iconCss iconfont icon-shouji"></view>
					<input class="com-inp" type="number" v-model="dataForm.username" placeholder="请输入您的手机号" />
				</view>
				<view class="common">
					<view class="iconCss iconfont icon-mima"></view>
					<input class="com-inp" v-model="dataForm.captcha" placeholder="请输入您的验证码" />
					<view class="get-code" :style="{color:textColor}" @tap="getCode" v-if="is_show_code">获取验证码</view>
					<view class="get-code get-code2" :style="{color:textColor}" v-else>重新发送({{countdown}})</view>
				</view>
				<view class="common login-btn" @tap="pwdLogin" :style="{background:textColor}">绑定手机号</view>
			</view>
		</view>
	</view>
</template>

<script>
import { isNullOrEmpty, isMobile } from '@/utils/util.js';
export default {
	data() {
		return {
			img_url: this.$api.img_url,
			dataForm: {
				username: '',
				captcha: '',
				recommend_id: ''
			},
			is_weixn: false,
			textColor:'',
			is_show_code:true,
			countdown:60,
			key:'',
			form:'',
			logo_img:'',
		};
	},
	onLoad(options) {
		this.key = options.key;
		this.form = options.form;
		if(uni.getStorageSync('mall_config')){
			this.textColor = this.globalSet('textCol');
			this.logo_img=JSON.parse(uni.getStorageSync('mall_config')).mall_setting.setting.logo;
		}
		if(uni.getStorageSync('pid')){
			this.dataForm.recommend_id = uni.getStorageSync('pid')
		}
		uni.removeStorageSync('parent_source');
	},
	methods: {
		getCode(){
			this.is_show_code = false;
			var timer = setInterval(()=>{
				this.countdown--;
				if(this.countdown <= 0){
					this.countdown = 60;
					this.is_show_code = true;
					clearInterval(timer);
				}
			},1000)
			
			this.$http.request({
				url:this.$api.default.phoneCode,
				method:'post',
				data:{
					mobile:this.dataForm.username,
					is_register:0
				}
			}).then(res => {
				if(res.code != 0){
					this.$http.toast(res.msg);
				}
			})
		},
		pwdLogin(){
			var url;
			if(this.form == 'login'){
				url = this.$api.default.bind;
			}else{
				url = this.$api.default.bindPhone;
			}
			if (!isNullOrEmpty(this.dataForm.username) || !isMobile(this.dataForm.username)) {
				this.$http.toast('请输入正确的手机号');
				return;
			}
			if (!isNullOrEmpty(this.dataForm.captcha)) {
				this.$http.toast('密码不能为空');
				return;
			}
			this.$http.request({
				url,
				method:'post',
				showLoading:true,
				data:{
					recommend_id: this.dataForm.recommend_id,
					mobile:this.dataForm.username,
					captcha:this.dataForm.captcha,
					key:this.key,
					stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
				}
			}).then(res =>{
				if(res.code == 0){
					// uni.setStorageSync('new_user',1);
					this.$http.toast('绑定成功!');
					
					this.$http.setToken(res.data.access_token);
					uni.removeStorageSync("pid");
					uni.removeStorageSync("user_id");
					
					
					if(this.form == 'login'){
						// uni.navigateBack();
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
										uni.navigateBack();
									}else{
										uni.navigateTo({
											url:'./bindParent'
										})	
									}		
								}
							});	
						
						
					}else{
						uni.redirectTo({
							url:'/pages/order/submit'
						})
					}
					
					uni.removeStorageSync("_login_pre_url");
				}else{
					this.$http.toast(res.msg);
				}
			})
		}
	}
};
</script>

<style scoped>
	.wx-box{
		position: relative;
	}
	.jx-info{
		position: absolute;
		top: 0;
		left: 0;
		width: 64rpx;
		height: 64rpx;
		opacity: 0;
	}
	
	.authorization-root {
		min-height: 100%;
		display: flex;
		flex-direction: column;
	}

	page {
		background: #ffffff !important;
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

	.fixed {
		position: fixed;
		min-width: 100%;
		display: flex;
		justify-content: center;
		bottom: 70rpx;
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
	.get-code{
		background: #f5f5f5;
		font-size: 26rpx;
		letter-spacing: 1px;
		color: #FFFFFF;
		padding: 4rpx 20rpx;
		border-radius: 30rpx;
	}
	.get-code2{
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
</style>