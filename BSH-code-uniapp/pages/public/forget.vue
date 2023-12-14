<template>
	<view class="login-root">
		<view>
			<view class="sky"></view>
			<view class="login-content">
				<view class="common">
					<view class="iconCss iconfont icon-shouji"></view>
					<input class="com-inp" type="number" value="" placeholder="请输入您的手机号"/>
				</view>
				<view class="common code">
					<view class="code-left">
						<view class="iconCss iconfont icon-yanzhengma"></view>
						<input class="com-inp" type="number" value="" placeholder="验证码"/>
					</view>
					<view class="code-right" @tap="getCode">
						获取验证码
					</view>
				</view>
				<view class="common">
					<view class="iconCss iconfont icon-mima"></view>
					<input class="com-inp" type="text" value="" placeholder="请设置您的密码"/>
				</view>
				<view class="common login-btn" @tap="submit">
					确认找回
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				isShow:false,
			}
		},
		methods: {
			goRes(){
				uni.navigateTo({
					url:'/pages/public/registered'
				})
			},
			goLogin(){
				uni.navigateTo({
					url:'/pages/public/login'
				})
			},
			submit(){
				this.$http.toast('提交');
			},
			getCode(){
				this.$http.toast('获取验证码');
			}
		}
	}
</script>

<style scoped>
	page{
		background: #FFFFFF!important;
	}
	
	.avatar{
		text-align: center;
		padding: 100rpx 0 57rpx;
	}
	.avatar-img{
		width: 168rpx;
		height: 168rpx;
	}
	.login-content{
		padding: 0 30rpx;
		box-sizing: border-box;
	}
	
	.common{
		height: 90rpx;
		border: 2rpx solid #F3F3F3;
		border-radius: 45rpx;
		padding: 0rpx 42rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		margin-bottom: 50rpx;
	}
	.iconCss{
		font-size: 16pt;
		color: #797979;
		margin-right: 18rpx;
	}
	.com-inp{
		font-size: 10pt;
		letter-spacing: 2rpx;
	}
	.login-btn{
		background: #BC0100;
		border: 0;
		color: #FFFFFF;
		justify-content: center;
		font-size: 10pt;
		letter-spacing: 4rpx;
		margin-bottom: 28rpx;
	}
	
	.code{
		justify-content: space-between;
	}
	.code-left{
		display: flex;
		align-items: center;
	}
	.code-right{
		font-size: 10pt;
		color: #8A8A8A;
		letter-spacing: 2rpx;
	}
	.sky{
		height: 180rpx;
	}
</style>
