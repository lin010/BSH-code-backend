<template>
	<view class="check-status">
		<uni-icons type="info" size="65" color="royalblue"></uni-icons>
		<text class="txt">状态查询中</text>
		<text class="txt">请勿关闭此页面，查询成功将会自动跳转</text>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				orderNo: ''
			}
		},
		onLoad(options) {
			this.orderNo = options.order_no || '';
		},
		onShow(){
			this.checkStatus();
		},
		methods: {
			checkStatus(){
				uni.showLoading({
					title: '请稍后'
				});
				let that = this;
				this.$http.request({
					url: this.$api.meituan.checkStatus,
					showLoading: false,
					method: 'post',
					data: {
						orderNo: this.orderNo
					}
				}).then(res => {
					if(res.code == 0){
						if(res.detail.notifyStatus == 1){
							location.href = res.detail.returnUrl;
							return;
						}
					}else{
						this.$http.toast(res.msg);
					}
					setTimeout(function(){
						that.checkStatus();
					}, 3000);
					
				}).catch(e => {
					console.log(e);
				});
			}
		}
	}
</script>

<style scoped>
	.check-status{;height:100vh;background:white;display: flex;flex-direction:column;align-items: center;justify-content: center;}
	.check-status .txt{font-size: 45rpx;color:royalblue;}
	.check-status .txt:last-child{font-size:32rpx;margin-top:20rpx;margin-bottom:300rpx;color:red}
</style>