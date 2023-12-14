<template>
	<view class="hotel-detail-app">
		<view class="hotel-detai-header">
			<text>{{orderMessage.status_text}}</text>
			<text v-if="orderMessage.real_status == 'confirmed'">房间将为您整晚保留，请安心入住</text>
		</view>
		<view class="hotel-detai-setAgain">
			<text v-if="action.is_payable==1" @click="payAgain(orderMessage.order_no)">去支付</text>
			<text v-if="action.is_cancelable==1" @click="cancleOrder(orderMessage.id)">取消订单</text>
			<text v-if="is_refundable==1" @click="applyrefund(orderMessage.id)">退款/售后</text>
			<text @click="againReservation" v-if="action.is_payable==0">再次预订</text>
		</view>
		<view class="hotel-detail-prompt">
			<view class="hotel-detail-prompt-title" v-if="action.is_payable==1">
				<text>倒计时</text>
				<text style="width: 50rpx;height: 50rpx;background: red;color: #fff;display: inline-block;text-align: center;margin: 0 10rpx;">{{minute}}</text>
				<text style="margin: 0 10rpx;">:</text>
				<text style="width: 50rpx;height: 50rpx;background: red;color: #fff;display: inline-block;text-align: center;margin: 0 10rpx;">{{seconds}}</text>
				<text>内可再次支付，超过时间，订单失效</text>
			</view>
			<view class="hotel-detail-prompt-detail">
				<view class="hotel-detail-prompt-detail-title">
					<image :src="img_url+'/hotel/prompt-logo.png'" mode=""></image>
					<text>温馨提示</text>
				</view>
				<view class="hotel-detail-prompt-detail-list">
					<view class="hotel-detail-prompt-detail-item">
						<text></text>
						<text>不允许携带宠物</text>
					</view>
				</view>
			</view>
		</view>
		<view class="hotel-detail">
			<view class="hotel-detail-name">
				<image :src="img_url+'/shoplogo.png'" mode="" style="width: 100rpx;height: 24rpx;margin-top: 10rpx;"></image>
				<!-- <text>{{orderDetail.hotel.name}}</text> -->
				<!-- <image :src="img_url+'/hotel/right.png'" mode="" style="width: 10rpx;height: 16rpx;margin-top: 15rpx;"></image> -->
			</view>
			<view class="hotel-detail-product">
				<view class="hotel-detail-product-image">
					<image :src="hotelMessage.thumb_url" mode=""></image>
				</view>
				<view class="hotel-detail-product-messgae">
					<view class="hotel-detail-product-messgae-name">
						{{hotelMessage.name}} 
					</view>
					<view class="hotel-phone-address">
						<text style="margin-bottom: 5rpx;">酒店联系方式 : {{hotelMessage.contact_phone}}</text>
						<text>地址 :{{hotelMessage.province_name}}{{hotelMessage.city_name}}{{hotelMessage.address}}</text>
					</view>
				</view>
			</view>
			<view class="hotel-phone-address-logo">
				<image :src="img_url+'/hotel/tellphone.png'" mode="" @click="tellphone(hotelMessage.contact_phone)"></image>
				<image :src="img_url+'/hotel/locations.png'" mode="" @click="location(hotelMessage.tx_lat,hotelMessage.tx_lng,hotelMessage.address)"></image>
			</view>
			<view class="hotel-room-detail">
				<view class="hotel-room-detail-messahe">
					{{roomMessage.name}}-
					<text v-if="hotelMessage.policy_breakfast==0">无早餐</text>
					<text v-if="hotelMessage.policy_breakfast==1">有早餐</text>
					·{{orderMessage.booking_num}}间·{{orderMessage.booking_days}}天
				</view>
				<view class="hotel-room-detail-specifications">
					{{roomMessage.room_size}}m²|
					<text v-if="roomMessage.bed_type=='single'">单床</text>
					<text v-if="roomMessage.bed_type=='double'">双床</text>
					<text v-if="roomMessage.bed_type=='double'">大床</text>
					{{roomMessage.bed_width}}m |可住{{roomMessage.people_num}}人
				</view>
				<view class="hotel-room-detail-time">
					<view>
						<text style="color: #000;margin-right: 10rpx;">{{orderMessage.booking_start_date}}</text>
						<text>入住</text>
					</view>
					<view>
						<text style="color: #000;margin-right: 10rpx;">{{orderMessage.end_date}}</text>
						<text>14:00前离店</text>
					</view>
				</view>
			</view>
			<view class="passengers-message" v-for="(item,index) in roomePeople" :key='index'>
				<view>
					<text>入住人</text>
					<input type="text" value=""  v-model.trim="item.name"  style="width: 300rpx;font-size: 28rpx;color: #000;height: 70rpx;display: inline-block;background: #fafafa;"/>
				</view>
				<view>
					<text>联系手机</text>
					<input type="text" value=""  v-model.trim="item.mobile"  style="width: 300rpx;font-size: 28rpx;color: #000;height: 70rpx;display: inline-block;background: #fafafa;"/>
				</view>
				<view class="tui-address-imgbox" style="position: absolute;top: 60rpx;right: 0rpx;width: 80rpx;" @click="savePeople(item,index)" >
					<image src="../../img/save.png" mode="" style="display: block;width: 50rpx;height: 50rpx;"></image>
				</view>
			</view>
		</view>
		<uni-popup ref="popup" type="center">
			<view class="pay-poup">
				<image :src="img_url+'/hotel/paySuccess.png'" mode=""></image>
				<view>恭喜你</view>
				<view>{{currencyAlias.red_envelope_alias}}支付成功</view>
			</view>
		</uni-popup>
	</view>
</template>

<script>
	import unipopup from '../../../components/uni-popup/uni-popup.vue'
	export default {
		components:{
			unipopup,
		},
		data() {
			return {
				img_url: this.$api.img_url,
				orderDetail:'',
				hotel_order_id:'',
				hotelMessage:'',
				orderMessage:'',
				roomMessage:'',
				action:'',
				is_refundable:'',//0不可以退款，1可以退款
				minute:"00",
				seconds:'00',
				timer:'',
				roomePeople:[],
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		onLoad(options) {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
				uni.setNavigationBarTitle({
				  title: this.currencyAlias.integral_alias+'卡券充值'
				});
			}
			if(options&&options.hotel_order_id){
				this.hotel_order_id=options.hotel_order_id
				this.getOrderDetail(options.hotel_order_id)
				this.isrefund(options.hotel_order_id)
			}
		},
		methods:{
			getOrderDetail(hotel_order_id){//获取订单详情
				this.$http
					.request({
						url: this.$api.hotel.getOrderdetail,
						method: 'POST',
						data:{
							hotel_order_id:hotel_order_id
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							this.action=res.data.action
							this.hotelMessage=res.data.hotel
							this.orderMessage=res.data.order
							this.roomePeople=res.data.order.booking_passengers
							console.log(this.roomePeople)
							this.roomMessage=res.data.room
							if(this.action.is_payable==1){
								let time=this.action.pay_last_second
								this.timer=setInterval(()=>{
									time--
									if(time<0){
										clearInterval(this.timer)
										this.orderMessage.status_text='已失效'
										this.action.is_payable=0
										return			
									}
									this.minute=Math.floor(time / 60)
									this.seconds = Math.floor(time % 60);
									if(this.minute<10){
										this.minute="0"+this.minute
									}
									if(this.seconds<10){
										this.seconds="0"+this.seconds
									}
								},1000)			
							}
						}else{
							this.$http.toast(res.msg);
						}
				});
			},
			isrefund(hotel_order_id){//判断是否可以退款
				this.$http
					.request({
						url: this.$api.hotel.isrefund,
						method: 'POST',
						data:{
							hotel_order_id:hotel_order_id
						},
					})
					.then(res => {
						if(res.code==0){
							this.is_refundable=res.data.is_refundable
						}else{
							this.$http.toast(res.msg);
						}
				});
			},
			applyrefund(id){ //退款
				let that=this
				uni.showModal({
				    title: '提示',
				    content: '您确定要退款吗？',
				    success: function (res) {
				        if (res.confirm) {
				           that.$http
				           	.request({
				           		url: that.$api.hotel.applyrefund,
				           		method: 'POST',
				           		data:{
				           			hotel_order_id:id
				           		},
				           		showLoading: true
				           	}).then(result => {
				           		if(result.code==0){
									that.$http.toast("申请退款成功！");
									that.getOrderDetail(that.hotel_order_id);
									that.isrefund(that.hotel_order_id);
				           		}else{
				           			that.$http.toast(result.msg);
				           		}
				           });
				        } else if (res.cancel) {
				           
				        }
				    }
				});
			},
			cancleOrder(id){//取消订单
				this.$http
					.request({
						url: this.$api.hotel.cancleOrder,
						method: 'POST',
						data:{
							hotel_order_id:id
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							this.$http.toast('取消订单成功');
							setTimeout(()=>{
								uni.redirectTo({
									url:'../orderList/orderList'
								})
							},2000)
						}else{
							this.$http.toast(res.msg);
						}
				});
			},
			tellphone(phone){ //拨打电话
				// alert(phone)
				uni.makePhoneCall({
				 	// 手机号
				    phoneNumber: phone, 				
					// 成功回调
					success: (res) => {
						console.log('调用成功!')	
					},				
					// 失败回调
					fail: (res) => {
						console.log('调用失败!')
					}					
				 })
			},
			location(lat,lnt,addrress){ //导航
			
				// #ifdef H5
				window.location.href='https://apis.map.qq.com/tools/poimarker?type=0&marker=coord:'+lat+','+lnt+';addr:'+addrress+'&referer=myapp&key=O3DBZ-IFH3W-KKIRN-RZPNQ-AOSH3-EGB5N'
				// #endif
				
				// #ifdef MP-WEIXIN || APP-PLUS
				uni.openLocation({
				 	latitude:Number(lat),
				 	longitude:Number(lnt),
				 	name:addrress,
				 	address:addrress,
				 	success: function () {
						
				 	}
				 });
				 // #endif
			},
			againReservation(){ //再次预定
				uni.navigateTo({
					url:'../../../hotel/detail/detail?id='+this.hotelMessage.id
				})
			},
			payAgain(order_no){//再次支付
				uni.navigateTo({
					url:'../pay?order_no='+res.data.order_no
				})
				// this.$http
				// 	.request({
				// 		url: this.$api.hotel.hotelPay,
				// 		method: 'POST',
				// 		data:{
				// 			order_no:order_no,
				// 		},
				// 		showLoading: true
				// 	})
				// 	.then(res => {
				// 		if(res.code==0){							
				// 			this.$refs.popup.open()
				// 			setTimeout(()=>{
				// 				this.$refs.popup.close()
				// 				uni.navigateTo({
				// 					url:'../hotelList/hotelList'
				// 				})
				// 			},2000)	
				// 		}else{
				// 			this.$http.toast(res.msg);
				// 		}
				// });
			},
			savePeople(item,index){
				this.$http
					.request({
						url: this.$api.hotel.editeroom,
						method: 'POST',
						data:{
							hotel_order_id:this.hotel_order_id,
							booking_passengers:this.roomePeople,
						},
					})
					.then(res => {
						if(res.code==0){
							this.getOrderDetail(this.hotel_order_id)
						}else{
							this.$http.toast(res.msg);
						}
				});
			}
		},
		onUnload() {
			clearInterval(this.timer)
		},
	}
</script>

<style lang="less">
	.hotel-detail-app{width: 100%;overflow: hidden;background: #FF7B10;}
	.hotel-detai-header{width: 100%;overflow: hidden;padding: 0 20rpx;margin-top: 50rpx;}
	.hotel-detai-header text{display: block;width: 100%;overflow: hidden;}
	.hotel-detai-header text:nth-of-type(1){font-size: 60rpx;color: #fff;}
	.hotel-detai-header text:nth-of-type(2){color: #F3F3F3;font-size: 24rpx;}
	.hotel-detai-setAgain{width: 100%;overflow: hidden;display: flex;justify-content: space-evenly;margin: 40rpx 0 20rpx 0;}
	.hotel-detai-setAgain text{display: block;width: 194rpx;height: 60rpx;text-align: center;line-height: 60rpx;background: rgba(255, 255, 255, 0.85);
border-radius: 31px;color: #0F0F0F;font-size: 26rpx;}
.hotel-detail-prompt{width: 100%;overflow: hidden;background: #fff;border-radius: 20rpx 20rpx 0 0;border-bottom: 6rpx solid #F1F3F4;}
.hotel-detail-prompt-title{width: 90%;height: 60rpx;line-height: 50rpx;font-size: 25rpx;margin: 10rpx auto;border-bottom: 1rpx solid #E6E6E6;}
.hotel-detail-prompt-title text:nth-of-type(1){color: #14C4AD;}
.hotel-detail-prompt-title text:nth-of-type(2){color: #000;}
.hotel-detail-prompt-detail-title{width: 90%;overflow: hidden;font-size: 28rpx;color: #000;margin: 10rpx auto 0;}
.hotel-detail-prompt-detail-title image{width: 30rpx;height: 30rpx;margin-top: 8rpx;display: block;float: left;margin-right: 15rpx;}
.hotel-detail-prompt-detail-list{width: 90%;margin: 0 auto ;overflow: hidden;}
.hotel-detail-prompt-detail-item{width: 100%;overflow: hidden;line-height: 50rpx;font-size: 25rpx;margin: 10rpx 0;}
.hotel-detail-prompt-detail-item text{display: inline-block;}
.hotel-detail-prompt-detail-item text:nth-of-type(1){width: 10rpx;height: 10rpx;background: #000;border-radius: 50%;margin-right: 20rpx;}
.hotel-detail{width: 100%;overflow: hidden;background: #fff;padding: 20rpx 0;}
.hotel-detail-name{width: 90%;overflow: hidden;margin: 0 auto;}
.hotel-detail-name image{display: block;float: left;}
.hotel-detail-name text{display: block;float: left;font-size: 28rpx;color: #000;margin: 0 15rpx;}
.hotel-detail-product{width: 90%;overflow: hidden;margin: 30rpx auto 10rpx;display: flex;justify-content: space-evenly;}
.hotel-detail-product-image image{display: block;width: 120rpx;height: 120rpx;margin-top: 25rpx;}
.hotel-detail-product-messgae{width: 450rpx;overflow: hidden;}
.hotel-detail-product-messgae-name{width: 100%;overflow: hidden;font-size: 30rpx;color: #000;overflow: hidden;
text-overflow: ellipsis;
display: -webkit-box;
-webkit-line-clamp: 2;
-webkit-box-orient: vertical;}
.hotel-detail-product-messgae-facilities{font-size: 25rpx;margin: 10rpx 0;width: 100%;}
.hotel-detail-product-messgae-facilities text{margin: 0 5rpx;}
.hotel-phone-address{font-size: 25rpx;width: 100%;}
.hotel-phone-address text{display: block;}
.hotel-phone-address text:nth-of-type(2){text-overflow: ellipsis;
display: -webkit-box;
-webkit-line-clamp: 2;
-webkit-box-orient: vertical;}
.hotel-phone-address-logo{width: 40%;overflow: hidden;margin: 0 auto;}
.hotel-phone-address-logo image{width: 60rpx;height: 60rpx;margin: 0 35rpx;}
.hotel-room-detail{width: 90%;margin: 25rpx auto ;overflow: hidden;border-top: 1rpx solid #E6E6E6;}
.hotel-room-detail-messahe{font-size: 28rpx;color: #000;width: 80%;margin-bottom: 10rpx;margin-top: 20rpx;}
.hotel-room-detail-specifications{font-size: 25rpx;margin-bottom: 15rpx;}
.hotel-room-detail-time{width: 100%;overflow: hidden;display: flex;justify-content: space-between;font-size: 25rpx;}
.passengers-message{width: 90%;overflow: hidden;position: relative;margin: 0 auto 20rpx;border: 1rpx solid #E6E6E6;;border-radius: 30rpx;padding: 20rpx;}
.passengers-message view{width: 100%;height: 70rpx;line-height: 70rpx;margin-bottom: 20rpx;}
.passengers-message view text{display: inline-block;float: left;}
.passengers-message view text:nth-of-type(1){font-size: 28rpx;width: 150rpx;}
.passengers-message view text:nth-of-type(2){font-size: 28rpx;color: #000;}
.pay-poup{width: 400rpx;height: 400rpx;border-radius: 30rpx;background: #fff;padding: 40rpx 20rpx;}
.pay-poup image{display: block;width: 120rpx;height: 120rpx;margin: 0rpx auto 35rpx;}
.pay-poup view{width: 100%;overflow: hidden;font-size: 30rpx;color: #000;text-align: center;margin: 25rpx 0;}



</style>

