<template>
	<view class="shopping-coupon">
		<view class="uni-form-item uni-column recharge-card">
			<view class="title">请输入卡片兑换码</view>
			<input v-model="serialize_no" class="uni-input" type="text" placeholder="请输入卡号" />
			<input v-model="use_code" class="uni-input" type="text" placeholder="请输入兑换码" />
			<view class="exchange-btn" :style="{background:'#FF7104',borderColor:'#FF7104'}" @click="integralRecharge">立即充值</view>
		</view>
	</view>
</template>

<script>
	import base from '@/config/baseUrl';
	import { isWeChat } from '@/utils/util.js';
	export default {
		data() {
			return {
				is_weixn: false,
				serialize_no : '',	//卡号
				use_code : '',	//兑换码
				textColor:''
			};
		},
		onLoad(options){
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
				uni.setNavigationBarTitle({
				  title: this.currencyAlias.red_envelope_alias+'充值'
				});
			}
			if(options.source){
				uni.setStorageSync('source',options.source);
			}
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
			}
			let token = uni.getStorageSync('token');
			if (isWeChat() && !token) {
				this.is_weixn = true;
				// 通过链接 获取 code
				let code = this.$http.getUrlParam('code');
				if (!code) {
					// code 不存在进入此判断
					let _baseUrl = window.location.href;
					_baseUrl = encodeURIComponent(_baseUrl);
					let url =
						`https://open.weixin.qq.com/connect/oauth2/authorize?appid=${base.publicAppId}&redirect_uri=${_baseUrl}&response_type=code&scope=snsapi_userinfo#wechat_redirect`;
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
		onReachBottom:function(e){
			
		},
		methods:{
			wxLogin(code) {
				this.$http
					.request({
						url: this.$api.default.wxLoginRegister,
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
							// 否则保存 access_token 到缓存 跳转到上次的页面 或者个人页 跳转后删除 _login_pre_url 的缓存
							this.$http.setToken(access_token);
							if(!access_token){
								window.location.reload();
							}
						}
					});
			},
			// 立即充值
			integralRecharge(){
				if(!this.serialize_no){
					this.$http.toast('请输入卡号');
					return
				}
				if(!this.use_code){
					this.$http.toast('请输入兑换码');
					return
				}
				let postData = {
					serialize_no : this.serialize_no,
					use_code : this.use_code,
					controller_type:1
				};
				this.$http.request({
					url: this.$api.user.shopping_integral_recharge,
					method: 'POST',
					data: postData,
					showLoading: true
				}).then(res => {
					this.$http.toast(res.msg);
					if(res.code==0){
						setTimeout(() => {							uni.redirectTo({
							    url:'/pages/user/shopping/shopping'
							});						}, 800);
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.shopping-coupon{
		width: 750rpx;
		background: #F7F7F7;
		.recharge-card{
			box-sizing: border-box;
			width: 690rpx;
			margin: 0 30rpx;
			.title{
				margin-top: 30rpx;
				font-size: 30rpx;
				font-family: SourceHanSansCN-Regular;
				line-height: 61rpx;
				color: #808080;
				letter-spacing: 1rpx;
			}
			.uni-input{
				margin-top: 30rpx;
				box-sizing: border-box;
				width: 100%;
				height: 72rpx;
				padding: 15rpx 20rpx;
				background: #FFFFFF;
				opacity: 1;
				border-radius: 10rpx;
				font-size: 28rpx;
				font-family: SourceHanSansCN-Regular;
				line-height: 42rpx;
				color: #999999;
				letter-spacing: 1rpx;
			}
			.exchange-btn{
				margin-top: 30rpx;
				width: 690rpx;
				height: 72rpx;
				background: #BC0100;
				border: 1px solid #BC0100;
				border-radius: 10px;
				font-size: 30rpx;
				font-family: SourceHanSansCN-Regular;
				line-height: 72rpx;
				color: #FFFFFF;
				letter-spacing: 2rpx;
				text-align: center;
			}
		}
	}
	
	.nothing{
		padding-top: 200rpx;
		text-align: center;
		letter-spacing: 1px;
	}
</style>
