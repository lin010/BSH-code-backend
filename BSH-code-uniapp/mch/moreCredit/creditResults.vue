<template>
	<view class="creditResult_app">
		<view class="creditResult_app-detail">
			<view>
				<image :src="img_url+'/huafe-logo.png'"></image>
				<text>充值中...</text>
				<!-- <text v-if="order_status=='processing'">充值中...</text>
				<text v-if="order_status=='success'">充值成功</text>
				<text v-if="order_status=='fail'">充值失败</text> -->
			</view>
			<view>
				<text style="font-size: 28rpx;">预计10分钟内到账 请注意到账信息</text>
				<!-- <text style="font-size: 28rpx;" v-if="order_status=='processing'">预计10分钟内到账 请注意到账信息</text>
				<text style="font-size: 28rpx;" v-if="order_status=='fail'">金豆已经原路返回</text> -->
			</view>
			<view>
				<button type="default" @click="linkTo">确定</button>
			</view>
		</view>
	</view>
</template>
<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				// timer:'',
				// order_status:'processing',//unpaid未付款，processing充值中，success成功，fail失败'
			}
		},
		onLoad(options) {
			// if(options&&options.order_id){			
			// 		this.timer=setInterval(()=>{
			// 			this.getStatus(options.order_id)
			// 		},2000)
			// }
		},
		methods: {
			linkTo() { //点击确定跳转页面
				uni.redirectTo({
					url:'./moreCredit'
				})
			},
			// getStatus(order_id) {//查询充值状态 
			// 	if(this.order_status!='processing') return false
			// 	this.$http.request({ 
			// 		url: this.$api.morecredit.creditOrderStatus,
			// 		method: 'get',
			// 		data:{
			// 			order_id:order_id
			// 		},
			// 	}).then(res => {
			// 		if (res.code == 0) {
			// 			this.order_status=res.data
			// 		} else {
			// 			this.$http.toast(res.msg);
			// 		}
			// 	});
			// },
		},
		// onUnload() {
		// 	if(this.timer!=null) {
		// 	    clearInterval(this.timer);  
		// 	    this.timer = null;  
		// 	}  
		// },
	}
</script>
<style lang="less">
	.creditResult_app {
		width: 100%;
		height: 100%;
		background: #ff7104;
		position: relative;
	}

	.creditResult_app-detail {
		width: 100%;
		height: 300rpx;
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		margin: auto;
	}

	.creditResult_app-detail view {
		width: 100%;
		overflow: hidden;
		text-align: center;
		line-height: 88rpx;
		color: #fff;
	}

	.creditResult_app-detail view image {
		display: inline-block;
		width: 92rpx;
		height: 88rpx;
		margin-right: 10rpx;
	}

	.creditResult_app-detail view text {
		display: block;
		height: 88rpx;
		line-height: 88rpx;
		color: #fff;
		font-size: 40rpx;
		font-weight: bold;
	}

	.creditResult_app-detail button {
		width: 60%;
		height: 80rpx;
		line-height: 80rpx;
		margin-top: 50rpx;
		outline: none;
		border: none;
	}
</style>
