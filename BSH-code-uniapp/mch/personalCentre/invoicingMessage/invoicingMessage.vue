<template>
	<view class="invoicingMessage-app">
		<view class="order-code">
			订单号:{{orderMessage.order_info.order_no}}
		</view>
		<view class="shop-detail">
			<image :src="orderMessage.mch_info.cover_url" mode="" class="shop-logo"></image>
			<view class="shop-name">
				<text>店铺名:{{orderMessage.mch_info.name}}</text>
				<text>ID:{{orderMessage.mch_info.mch_id}}</text>
			</view>
		</view>
		<view class="product-price">
			价格:{{orderMessage.order_info.order_price}}元+{{num+count}}{{currencyAlias.red_envelope_alias}}
		</view>
		<view class="use-points flex flex-y-center flex-x-between">
			<view>
				<!-- 使用抵扣券 :{{orderMessage.integral_max_deduction}} -->
				<view class="xieti">{{currencyAlias.red_envelope_alias}}总额:{{orderMessage.user_integral_num}}{{currencyAlias.red_envelope_alias}}</view>
				<view>使用{{currencyAlias.red_envelope_alias}}:{{num}}{{currencyAlias.red_envelope_alias}}</view>
				<view>额外服务费:{{is_integral?orderMessage.integral_service_fee:0}}{{currencyAlias.red_envelope_alias}}</view>
			</view>
			<switch :checked="is_integral" @change="useIntegral" :color='textColor' class="points-switch" />
		</view>
		<view class="go-pay">
			<button type="default" @click='pay'>去支付</button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				orderMessage:{
					// order_info:{
					// 	"order_no": "MS202103110906011010910457",
					// 	"order_price":100,
					// },
					// mch_info:{
					// 	"cover_url":'https://thirdwx.qlogo.cn/mmopen/vi_32/OUCO4yHsnBoibopxichHiaHFZicKmShUY5BrsvVdhmyxo8chuKo2pAlumI6T1bztDiafDydguiblxVChfQtiaaL7zTJBA/132',
					// 	"name":"小周周的店",
					// 	"mch_id":54,
					// },
					// "user_integral_num": 99999,
					// "integral_max_deduction": 100,
					// "integral_service_fee":20
				},//结账单信息
				textColor: '#00dd00',
				is_integral:false,
				num:0,
				count:0,
				totle:0,
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		onLoad() {   //通过扫二维码进入此页面会带有一个id
		let currencyAliasConfig = uni.getStorageSync('currencyAlias');
		if(currencyAliasConfig){
			JSON.parse(currencyAliasConfig);
			this.currencyAlias = JSON.parse(currencyAliasConfig);
		}
			this.getmessage()
		},
		methods:{
			useIntegral(e) {
				this.is_integral = e.detail.value;
				console.log(this.is_integral)
				if(this.is_integral==false){
					this.orderMessage.order_info.order_price=this.orderMessage.order_info.order_price
					this.num=0
					this.count=0
					this.totle=0
					this.getmessage()
				}else{
					this.orderMessage.order_info.order_price=Number(this.orderMessage.order_info.order_price)-Number(this.orderMessage.integral_max_deduction)
					this.num=this.orderMessage.integral_max_deduction
					this.count=this.orderMessage.integral_service_fee
					this.totle=this.num+this.count
				}
			},
			getmessage(){
				let routes = getCurrentPages(); // 获取当前打开过的页面路由数组
				let curParam = routes[routes.length - 1].options; //获取路由参数
				this.$http.request({
					url: this.$api.moreShop.getpayMessage,
					method: 'POST',
					data: {
						id:curParam.id
					},
					showLoading: true
				}).then(res => {
					if(res.code==0){
						this.orderMessage=res.data
					}else{
						this.$http.toast(res.msg)
					}
				})
			},
			pay(){ //点击去支付
				uni.navigateTo({
					url:'../invoicingMessage/pay?id='+this.orderMessage.order_info.id+"&use_integral="+this.totle
				})
			}
		}
	}
</script>

<style lang="less" scoped>
	.invoicingMessage-app{width: 100%;height: 100%;background: url(../../../plugins/images/confirm_order.jpg)no-repeat;background-size: 100%;padding-top: 30rpx;}
	.order-code{width: 100%;height: 100rpx;background: #FF7104;line-height: 100rpx;
	font-weight: 600;padding: 0rpx 20rpx;color: #000;}
	.shop-detail{width: 100%;overflow: hidden;background: #FF7104;margin-top: 20rpx;display: flex;padding: 0rpx 20rpx;}
	.shop-logo{width: 158rpx;height: 158rpx;}
	.shop-name{margin-left: 20rpx;width: 500rpx;color: #000;}
	.shop-name text{display: block;}
	.shop-name text:nth-of-type(1){margin-top:25rpx ;}
	.product-price{width: 100%;height: 100rpx;background: #FF7104;margin: 30rpx 0 0 0;line-height: 100rpx;
	font-weight: 600;padding: 0rpx 20rpx;color: #000;}
	.use-points{background: #FF7104;margin-top: 40rpx;color: #000;}
	.go-pay{width: 100%;height: 100rpx;margin-top: 100rpx;}
	.go-pay button{width: 100%;outline: none;border: none;background: #FF7104;color: #fff;}
</style>
