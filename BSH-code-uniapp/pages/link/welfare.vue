<template>
	<view>
		<view class="integral_text">300{{currencyAlias.integral_alias}}，赠送一个月</view>
		<view>
			<button type="warn" @tap="get_integral">立即获取</button>
		</view>
	</view>  
</template>

<script>
	export default {
		data() {
			return {
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			}
		},
		mounted() {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			//#ifdef H5
			var a = document.getElementsByClassName('uni-page-head-hd')[0]
			a.style.display = 'none';	
			//#endif
		},
		methods: {
			/*
			先简单实现功能，项目进度安排到这，再详细创建数据表，和编写页面
			*/
			get_integral() {
				var user = uni.getStorageSync('user-id');								
				this.$http.request({
					url: this.$api.plugin.extensions.GetIntegral,
					method: 'POST',
					data:{
						"user_id":user
					}
				}).then(res => {
					if (res.code == 0) {
						uni.showToast({
						    title: res.msg,
						    duration: 2000,
							icon:'none'
						});
						setTimeout(function () {
							uni.redirectTo({
								url:'/pages/index/index'
							});
						},2000);
					} else {
						this.$http.toast(res.msg);
						uni.redirectTo();
					}
				})
			}
		}
	}
</script>

<style lang="scss">
	.integral_text {
		// border: 1px solid red;
		font-size: 38rpx;
		text-align: center;
	}
</style>
