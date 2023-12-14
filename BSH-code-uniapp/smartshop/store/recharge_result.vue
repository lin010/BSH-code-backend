<template>
	<view class="app">
		<view v-if="status=='waiting'" class="pay-result pay-wait flex flex-col">
			<icon type="waiting" size="32"/>
			<text style="margin-top:20rpx;">查询支付结果</text>
			<text class="counter">请稍后...{{counter}}</text>
		</view>
		<view v-if="status=='success'" class="pay-result pay-success flex flex-col">
			<icon type="success" size="32" color="green"/>
			<text>支付成功</text>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				status: 'waiting',
				order_id: '',
				counter: 10
			}
		},
		onLoad(options) {
			this.order_id = options.orderId;
			let timer, that = this;
			timer = setInterval(function(){
				that.counter = that.counter > 0 ? (that.counter - 1) : 10;
				console.log(that.status);
				if(that.status == 'success'){
					clearInterval(timer);
					return;
				}
				if(that.counter % 3 == 0){
					that.getData();
				}
			}, 1000);
		},
		methods: {
			getData(){
				this.$http.request({
					url: this.$api.smartshop.store.pay_order_detail,
					showLoading: true,
					data: {
						order_id: this.order_id
					}
				}).then((res) => {
					if(res.code == 0){
						if(res.data.detail.pay_status == 'paid'){
							this.status = 'success';
						}
					}else{
						this.$http.toast(res.msg)
					}
				})
			}
		}
	}
</script>

<style>
	.app{background:#f1f1f1;height:100%;}
	.pay-result{font-size:32rpx;padding-top:7%;padding-bottom:7%;align-items: center;width:100%;background:white;}
	.pay-result text{margin-top:10rpx;}
	.pay-wait{}
	.counter{color:gray;font-size:26rpx;}
	.pay-success icon{color:green;}
	.pay-success{color:green;}
</style>
