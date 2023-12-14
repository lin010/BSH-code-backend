<template>
	<view class="giftsubmit-app">
		<view class="giftsubmit-detail">
			<image :src="detail.cover_pic" mode=""></image>
			<view class="giftsubmit-detail-product">
				<text>{{detail.title}}</text>
				<text>{{detail.descript}}</text>
			</view>
		</view>
		<view class="giftbagDetail-service" v-if="detail.allow_currency=='integral'">
			<jx-list-cell :arrow="false" padding="0" :lineLeft="false">
				<view class="jx-cell-header" style="height: 80rpx;">
					<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">需使用{{currencyAlias.red_envelope_alias}}支付</view>
				</view>
				<view class="jx-cell-header" style="margin-left: 350rpx;color: #FF5A0E;height: 80rpx;">
					<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">{{integral_deduction_price}}{{currencyAlias.red_envelope_alias}}</view>
				</view>
			</jx-list-cell>		
		</view>
		<view class="bottom"  v-if="detail.allow_currency=='integral'">
			<text>剩余{{currencyAlias.red_envelope_alias}}：{{integrals}}</text>
			<text @click="buy(detail.id)">去支付</text>
		</view>
		<view class="giftbagDetail-service" v-if="detail.allow_currency=='money'">
			<radio-group @change="radioChange">
                <view  v-for="(item, index) in payitems" :key="index" style="width: 100%;height: 100rpx;line-height: 100rpx;padding: 0 20rpx;box-sizing: border-box;">
					<view style="float: left;">{{item.name}}</view>
					<view style="float: right;">
                        <radio :value="item.value" :checked="index === current" />
                    </view>
                </view>
            </radio-group>
		</view>
		<view class="giftbagDetail-service" v-if="detail.allow_currency=='money'&&current==0">
			<jx-list-cell :arrow="false" padding="0" :lineLeft="false">
				<view class="jx-cell-header" style="height: 80rpx;">
					<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">需使用{{currencyAlias.balance_alias}}支付</view>
				</view>
				<view class="jx-cell-header" style="margin-left: 350rpx;color: #FF5A0E;height: 80rpx;">
					<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">{{price}}元</view>
				</view>
			</jx-list-cell>		
		</view>
		<view class="giftbagDetail-service" v-if="detail.allow_currency=='money'&&current==1">
			<jx-list-cell :arrow="false" padding="0" :lineLeft="false">
				<!-- #ifdef H5||MP -->
					<view class="jx-cell-header" style="height: 80rpx;">
						<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">需使用微信支付</view>
					</view>
				<!-- #endif -->	
				<!-- #ifdef APP-PLUS -->
					<view class="jx-cell-header" style="height: 80rpx;">
						<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">需使用支付宝支付</view>
					</view>
				<!-- #endif -->	
				<view class="jx-cell-header" style="margin-left: 350rpx;color: #FF5A0E;height: 80rpx;">
					<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">{{price}}元</view>
				</view>
			</jx-list-cell>		
		</view>
		<view class="bottom"  v-if="detail.allow_currency=='money'&&current==0">
			<text>剩余{{currencyAlias.balance_alias}}：{{balance}}</text>
			<text @click="buy(detail.id)">去支付</text>
		</view>
		<view class="bottom"  v-if="detail.allow_currency=='money'&&current==1">
			<text   style="width: 350rpx;height: 80rpx;background: red;text-align: center;line-height: 80rpx;border-radius: 30rpx;
					margin-left: 230rpx;color: #fff;margin-top: 10rpx;"  @click="buy(detail.id)">去支付</text>
		</view>
		<com-modal :button="button" :show="modal" @click="handleClick" @cancel="hide" title="提示" content="您没有设置支付密码,是否需要跳转设置？"></com-modal>
		<com-payment-password ref="paymentPassword" :show="cashFlag" :value="paymentPwd" :digits="6"
		@submit="checkPwd" @cancel="togglePayment" @checkSafePwd="safePasswork"></com-payment-password>
	</view>
</template>

<script>
	import jxListCell from '@/components/list-cell/list-cell';
	// #ifdef H5
	var jweixin = require('jweixin-module');
	// #endif
	// #ifdef MP-WEIXIN || APP-PLUS
	import {setPay} from '@/config/utils.js'
	// #endif
	export default {
		components: {
			jxListCell
		},
		data() {
			return {
				integral_deduction_price:'',//需支付的金豆
				integrals:'',//剩余金豆
				price:'',//需支付的余额
				balance:'',//剩余余额
				detail:'',
				modal:false,//模态弹窗
				button:[],
				cashFlag: false,
				paymentPwd: '',
				is_transaction_password:true,//是否设置过支付密码
				order_id:'',//订单ID
				payitems: [
					{
                        value: '余额支付',
                        name: '余额支付'
                    },
					// #ifdef H5 ||MP
					{
					    value: '微信支付',
					    name: '微信支付'
					}
					// #endif
					// #ifdef APP-PLUS
					{
					    value: '支付宝支付',
					    name: '支付宝支付'
					}
					// #endif
                ],
				current: 0,
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		onLoad(options) {
			if(options&&options.pack_id){
				this.prevwDetail(options.pack_id)
			}
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
				this.payitems=[
					{
				        value: this.currencyAlias.balance_alias+'支付',
				        name: this.currencyAlias.balance_alias+'支付'
				    },
					// #ifdef H5 ||MP
					{
					    value: '微信支付',
					    name: '微信支付'
					}
					// #endif
					// #ifdef APP-PLUS
					{
					    value: '支付宝支付',
					    name: '支付宝支付'
					}
					// #endif
				];
			}
			this.button = this.globalSet('btnCol','确定');
		},
		onShow() {
			this.initSetting()
		},
		methods:{
			prevwDetail(id){ //预览订单
				this.$http.request({
					url: this.$api.package.previewOrder,
					method: 'POST',
					data: {
						pack_id:id
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.detail=res.data.detail
						this.integral_deduction_price=res.data.integral_deduction_price
						this.integrals=res.data.integrals
						this.balance=res.data.balance
						this.price=res.data.price
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			radioChange: function(evt) {
				for (let i = 0; i < this.payitems.length; i++) {
					if (this.payitems[i].value === evt.detail.value) {
						this.current = i;
						console.log(this.current )
						break;
					}
				}
			},
			buy(id){//生成订单并且支付
				this.$http.request({
					url: this.$api.package.productOrder,
					method: 'POST',
					data: {
						pack_id:id
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.order_id=res.data.order_id
						if(this.current==0){
							if(!this.is_transaction_password){
								this.modal = true;
								return;
							}
							this.cashFlag=true
							this.$refs.paymentPassword.modalFun('show');
						}else{
							this.payMoney(this.order_id,this.paymentPwd)
						}
						
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			payMoney(order_id,trade_pwd){ //支付
				if(this.detail.allow_currency=='integral'){ //金豆支付
					this.$http.request({
						url: this.$api.package.payMoney,
						method: 'POST',
						data: {
							order_id:order_id,
							trade_pwd:trade_pwd
						},
						showLoading: true
					}).then(res => {
						if (res.code == 0) {
							this.$refs.paymentPassword.modalFun('hide');
							this.$http.toast('支付成功');
							setTimeout(()=>{
								uni.navigateTo({
									url:'../giftOrderDetail/giftOrderDetail?order_id=' + order_id
								})
							},2000)
						} else {
							this.$http.toast(res.msg);
							this.paymentPwd=''
							this.$refs.paymentPassword.modalFun('hide');
						}
					});
				}
				if(this.detail.allow_currency=='money'&&this.current==0){ //余额支付
					this.$http.request({
						url: this.$api.package.paybalance,
						method: 'POST',
						data: {
							order_id:order_id,
							trade_pwd:trade_pwd
						},
						showLoading: true
					}).then(res => {
						if (res.code == 0) {
							this.$refs.paymentPassword.modalFun('hide');
							this.$http.toast('支付成功');
							setTimeout(()=>{
								uni.navigateTo({
									url:'../giftOrderDetail/giftOrderDetail?order_id=' + order_id
								})
							},2000)
						} else {
							this.$http.toast(res.msg);
							this.paymentPwd=''
							this.$refs.paymentPassword.clearAll();
						}
					});
				}
				if(this.detail.allow_currency=='money'&&this.current==1){ //微信支付
					this.$http.request({
						url: this.$api.package.paywechatcreated,
						method: 'POST',
						data: {
							order_id:order_id,
						},
					}).then(res => {
						if (res.code == 0) {
							var union_id=res.data.union_id
							// #ifdef H5 ||MP
							this.getWchat(union_id)
							// #endif
							// #ifdef APP-PLUS
							this.getalipay(union_id)
							// #endif
						} else {
							this.$http.toast(res.msg);
						}
					});
				}
			},
			hide() {
				this.modal = false;
			},
			togglePayment() {
				this.cashFlag = false;
			},
			handleClick(e) {
				let index = e.index;
				if (index === 0) {
					this.modal = false;
				} else {
					uni.navigateTo({
						url:'../../../pages/user/payment/password'
					})
				}
				this.hide();
			},
			checkPwd(e) { //输入密码
				console.log(e)
			},
			safePasswork(e){ //密码校验
				this.paymentPwd = e.passwork;
				this.payMoney(this.order_id,this.paymentPwd)
			},
			initSetting() { //获取是否设置支付密码
				this.$http
					.request({
						url: this.$api.plugin.extensions.cash.setting
					})
					.then(res => {
						if (res.code == 0) {
							this.is_transaction_password = res.data.user_info.is_transaction_password;
						} else {
							this.$http.toast(res.msg);
						}
					});
			},
			getWchat(union_id){ //第三方支付
				// #ifdef H5
				var url="https://dev.mingyuanriji.cn/h5/#/mch/orderList/orderList"
				this.$http.request({
					url: this.$api.package.paywechat,
					method: 'POST',
					data: {
						union_id:union_id,
						stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
						wx_type:'wechat',//公众号：wechat  小程序：mp-wx
						redirect_url:url
					},
					showLoading: true
				}).then(res => {
					this.$refs.paymentPassword.modalFun('hide');
					if (res.code == 0) {
						this.$wechatSdk.pay(res.data,url);
					} else {
						this.$http.toast(res.msg);
					}
				});
				// #endif
				// #ifdef MP-WEIXIN || APP-PLUS
					var url="/mch/orderList/orderList"
					this.$http.request({
						url: this.$api.package.paywechat,
						method: 'POST',
						data: {
							union_id:union_id,
							stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
							wx_type:'mp-wx',//公众号：wechat  小程序：mp-wx
							redirect_url:url
						},
						showLoading: true
					}).then(res => {
						this.$refs.paymentPassword.modalFun('hide');
						if (res.code == 0) {
							setPay(res.data, (result) => {
								if (result.success) {
									this.$http.toast("支付成功")
									setTimeout(() => {
										uni.redirectTo({
											url: url
										})
									},500)
								} else {
									this.$http.toast("支付失败")
								}													
							});
						} else {
							this.$http.toast(res.msg);
						}
					});
				// #endif	
			},		
			getalipay(union_id){
				let that=this
				that.$http.request({
					url: that.$api.moreShop.alipay,
					showLoading: true,
					method: 'post',
					data: {
						union_id:union_id,
						stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
					}
				}).then(res=>{
					if(res.code==0){
						// #ifdef APP-PLUS
							uni.navigateTo({
								url: '/pages/order/alipayWeb?url=' + res.data.codeUrl
							})
						// #endif
						 // #ifdef H5
						let url=res.data.codeUrl
						location.href=url
						// #endif
					}else{
						that.$http.toast(res.msg)
					}
				})
			},
		}
	}
</script>

<style lang="less" scoped>
	.giftsubmit-app{width: 100%;overflow: hidden;}
	.giftsubmit-detail{width: 100%;margin: 10rpx auto 0;overflow: hidden;background: #fff;padding: 20rpx;}
	.giftsubmit-detail image{width: 240rpx;height: 200rpx;display: block;float: left;}
	.giftsubmit-detail-product{float: left;width: 450rpx;margin-left: 10rpx;overflow: hidden;}
	.giftsubmit-detail-product text{display: block;width: 100%;}
	.giftsubmit-detail-product text:nth-of-type(1){overflow: hidden;text-overflow:ellipsis;white-space: nowrap;color: #000;font-size: 34rpx;}
	.giftsubmit-detail-product text:nth-of-type(2){overflow: hidden;font-size: 25rpx;}
	.giftbagDetail-service{margin: 5rpx 0;}
	.bottom{position: fixed;left: 0;bottom: 0;width: 100%;height: 100rpx;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.4);background: #fff;}
	.bottom text{display: inline-block;}
	.bottom text:nth-of-type(1){line-height: 100rpx;margin-left: 50rpx;font-size: 30rpx;color: #FF5A0E;font-weight: bold;}
	.bottom text:nth-of-type(2){width: 260rpx;height: 80rpx;background: red;text-align: center;line-height: 80rpx;border-radius: 30rpx;
	margin-left: 100rpx;color: #fff;}
</style>
