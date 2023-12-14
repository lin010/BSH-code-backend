<template>
	<view class="payPages-app">
		<view class="payPages_shop">
			<view class="payPages_shop_left">
				<view>付款给商家:</view>
				<view>{{message.name}}</view>
			</view>
			<view class="payPages_shop_right">
				<image :src="message.logo" mode=""></image>
			</view>
		</view>
		<view class="payPages_num">
			<view class="payPages_num_title">
				金额
			</view>
			<view class="int">
				<text>￥</text>
				<input type="digit" value="" v-model.trim="num"/>
			</view>
		</view>
		<view class="sure-code">
			<button type="default" @click='sure'>确认</button>
		</view>		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				num:'',
				id:'',
				message:{},
			};
		},
	
		onLoad(options) {		
			this.beforeOnLoad(options);
			if(options.pid){
				uni.setStorageSync("pid",options.pid)
			}
			this.id=options.id
			this.$http.request({
						url: this.$api.moreShop.getshopnewdetail,
						method: 'POST',
						data:{mch_id:this.id,store_id:0},
						showLoading: true
					}).then(res => {
						if(res.code==0){
							this.message=res.data.detail
						}
			})
		},
		onShow() {
			this.getCartList()
		},		
		methods:{
			getCartList() { //获取购物车列表
				this.$http.request({
					url: this.$api.cart.list,
					method: 'GET'
				}).then((res) => { 
					if(res.code==0){
					
					}else{
						
					}
				})
			},	
			sure(){
				if(this.num.length<=0){
					this.$http.toast("金额不能为空")
					return
				}
				this.$http.request({
						url: this.$api.moreShop.creatCheckOrder,
						method: 'POST',
						data: {
							id:this.id,
							order_price:this.num
						},
						showLoading: true
					}).then(res => {
						if(res.code==0){
							uni.navigateTo({
								url:'../../invoicingMessage/invoicingMessage?id='+res.data.id
							})
						}
					})
				
			}
		}
	}
</script>

<style lang="less" scoped>
	.payPages-app{width: 100%;height: 100%;
	// background: url(../../../../plugins/images/userInputmoney.jpg)no-repeat;background-size: cover;
	}
	.input-btn{height: 100rpx;background: rgb(245, 245, 245);width: 65%;padding-left: 10rpx;}
	.sure-code{width: 100%;overflow: hidden;}
	.sure-code button{width: 100%;margin: 100rpx auto;background: #FF7104;color: #fff;}		
	.payPages_shop{width: 100%;overflow: hidden;display: flex;justify-content: space-between;padding: 40rpx 20rpx 0 20rpx;box-sizing: border-box;}
	.payPages_shop_left {max-width: 500rpx;}
	.payPages_shop_left view{margin: 10rpx 0;color: #000;}
	.payPages_shop_left view:nth-of-type(2){margin-left: 30rpx;}
	.payPages_shop_right image{width: 150rpx;height: 150rpx;border-radius: 50%;margin-top: 10rpx;}
	.payPages_num{width: 100%;height: 300rpx;background: #fff;border-radius: 30rpx;padding: 50rpx 20rpx;}
	.payPages_num_title{color: #000;font-weight: 600;}
	.int{width: 100%;overflow: hidden;}
	.int text{display: block;float: left;margin-top: 40rpx ;margin-left: 30rpx;color: #000;font-weight: 600;font-size: 35rpx;}
	.int input{width: 80%;border-bottom: 2rpx solid #000000;margin: 38rpx auto 0;float: left;padding-left: 10rpx;font-size: 45rpx;color: #000;font-weight: bold;}
</style>
