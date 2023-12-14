<template>
	<view class="shopping-coupon">
		<view class="uni-form-item uni-column recharge-card">
			<view class="title">请输入卡片兑换码</view>
			<input :disabled="loading" v-model="serialize_no" class="uni-input" type="text" placeholder="请输入卡号" />
			<input :disabled="loading" v-model="use_code" class="uni-input" type="text" placeholder="请输入兑换码" />
			<view class="exchange-btn" :style="{background:!loading ? '#FF7104' : '#e5e5e5',borderColor:!loading ? '#FF7104' : '#e5e5e5'}" @click="integralRecharge">立即充值</view>
		</view>
	</view>
</template>

<script>
	import base from '@/config/baseUrl';
	import { isWeChat } from '@/utils/util.js';
	export default {
		data() {
			return {
				loading: false,
				is_weixn: false,
				serialize_no : '',	//卡号
				use_code : '',	//兑换码
				textColor:'',
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		onLoad(options){
			if(options.source){
				uni.setStorageSync('source',options.source);
			}
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
			}
			
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
				uni.setNavigationBarTitle({
				  title: this.currencyAlias.integral_alias+'卡券充值'
				});
			}
		},
		onShow(){
			//先引导登录操作
			this.getUserInfo();
		},
		onReachBottom:function(e){
			
		},
		methods:{
			getUserInfo() {
				this.$http.request({
					url: this.$api.user.userInfo,
					method: 'POST',
				}).then(res => {
					if (res.code == 0) {
						
					}
				}).catch(err => {
					console.log(err);
				})
			},
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
				};
				this.loading = true;
				this.$http.request({
					url: this.$api.user.score_integral_recharge,
					method: 'POST',
					data: postData,
					showLoading: true
				}).then(res => {
					if(res.code==0){
						this.$http.toast("充值成功！");
						let url;
						if(res.data.link_url){
							url = res.data.link_url.new_link_url;
						}else{
							url = '/pages/user/integral/integral';
						}
						setTimeout(() => {
							uni.redirectTo({
							    url: url
							});						
						}, 800);
					}else{
						this.$http.toast(res.msg);
						this.loading = false;
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
