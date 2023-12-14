<template>
	<view class="orderSure_app">
		<view class="order_timer">
			<view class="order_timer_interval">
				<view>
					<text>入住时间</text>
					<text>{{beginTime}}</text>
				</view>
				<view>
					<text>离店时间</text>
					<text>{{privewMessage.end_day}}</text>
				</view>
			</view>
			<view class="order_timer_count">
				  共{{privewMessage.days}}晚
			</view>
		</view>
		<view class="order-message">
			<view class="order_hotel_info">
				<view class="order_hotel_logo">
					<image :src="privewMessage.hotel_info.thumb_url" mode=""></image>
				</view>
				<view class="order_hotel-detail">
					<view>{{privewMessage.hotel_info.name}}</view>
					<view>
						<text>{{privewMessage.booking_item.product_name}}</text>
						<text v-if="privewMessage.booking_item.bed_type=='single'">单床</text>
						<text v-if="privewMessage.booking_item.bed_type=='double'">双床</text>
						<text v-if="privewMessage.booking_item.bed_type=='big'">大床</text>
						<text v-if="privewMessage.booking_item.window=='no'">无窗</text>
						<text v-if="privewMessage.booking_item.window=='out'">外窗</text>
						<text v-if="privewMessage.booking_item.window=='part_no'">部分无窗</text>
						<text v-if="privewMessage.booking_item.window=='inner'">内窗</text>
						<text v-if="privewMessage.booking_item.window=='part_inner'">部分内窗</text>
						<text v-if="privewMessage.booking_item.ban_smoking==1">禁烟</text>
					</view>
					<view>{{privewMessage.hotel_info.address}}</view>
				</view>
			</view>
		</view>
		<view class="check_in_message">
			<view class="check_in_message_title">
				<text style="color: #FF5D0D;font-weight: bold;margin-right: 10rpx;">|</text>
				<text style="font-size: 28rpx;color: #000;font-weight: 600;">入住信息</text>
			</view>
			<view class="check_in_message_form">
				<view>
					<text>房间数</text>
					<view style="width: 450rpx;height: 80rpx;float: left;margin: 0;">
						<text style="width: 80rpx;text-align: center;background:#FF5D0D ;color: #fff;" @click="count">-</text>
						<input type="number" v-model="form.num" placeholder="1" style="width: 200rpx;border: none;text-align: center;background: #F5F5F5;" disabled/>
						<text style="width: 80rpx;text-align: center;background:#FF5D0D ;color: #fff;" @click="add">+</text>
					</view>
				</view>
				<view style="position: relative;">
					<text>到店时间</text>
					<view style="width: 450rpx;height: 80rpx;float: left;margin: 0;font-size: 26rpx;">
						<picker mode="time" :value="time" start="00:00" end="24:00" @change="bindTimeChange">
						        <view style="height: 80rpx;margin: 0;line-height: 80rpx;">{{beginTime}} {{time}}</view>
						</picker>
					</view>
					<!-- <input type="text" placeholder="14:00点后办理入住" disabled/> -->
					<image :src="img_url+'/hotel/down.png'" mode="" class="down-logo"></image>
				</view>
			</view>		
			<view class="check_in_message_title" style="margin: 15rpx 0 0 0;position: relative;" >
				<text style="color: #FF5D0D;font-weight: bold;margin-right: 10rpx;">|</text>
				<text style="font-size: 28rpx;color: #000;font-weight: 600;">添加入住人</text>
				<image :src="img_url+'/hotel/add.png'" mode="" class="add-logo" @click="addpepple"></image>
			</view>
			<view class="check_in_message_form" v-for="(item,index) in form.passengers" :key='index'
			style="border: 1rpx solid rgb(255, 93, 13);position: relative;margin:25rpx auto ;padding: 20rpx;border-radius: 10rpx;box-sizing: border-box;">
				<image :src="img_url+'/hotel/count.png'" mode="" class="count-logo" @click="removepepple(index)" v-if="form.passengers.length>1"></image>
				<view>
					<text>姓名</text>
					<input type="text" v-model="item.name" placeholder="请填写姓名"/>
				</view>
				<view>
					<text>联系手机</text>
					<input type="text" v-model="item.mobile"  placeholder="请填写联系手机" />
				</view>	
			</view>
		</view>	
		<view class="check_in_message" style="margin-bottom: 20rpx;">
			<view class="check_in_message_form">
				<view>
					<text>发票说明：</text>
					<text style="width:450rpx ;color: #EF4A1A;">如需发票请向酒店前台索取（酒店可提供普票，无法提供专票）</text>
				</view>
			</view>
		
		</view>	
		<view class="check_in_message" style="margin-bottom: 200rpx;">
			<view class="check_in_message_title">
				<text style="color: #FF5D0D;font-weight: bold;margin-right: 10rpx;">|</text>
				<text style="font-size: 28rpx;color: #000;font-weight: 600;">优惠</text>
			</view>
			<view class="check_in_message_form">
				<view>
					<text>促销优惠</text>
					<text style="width:450rpx ;color: #EF4A1A;">{{currencyAlias.red_envelope_alias}}全额抵扣</text>
				</view>
			</view>
			<view class="use-points flex flex-y-center flex-x-between" v-if="privewMessage.user_total_integral>0">
				<view>使用{{currencyAlias.red_envelope_alias}} <view class="xieti">拥有{{currencyAlias.red_envelope_alias}}：{{privewMessage.user_total_integral}} 
				<text class="text" v-if="is_checked">-{{privewMessage.integral_deduction_price}}</text>
				</view>
				</view>
				<switch :checked="is_checked" @change="use" color='#FF7104' class="points-switch" />
			</view>
		</view>
		<view class="goPAy">
			<view class="goPAy_left">
				<text style="display: inline-block;font-size: 32rpx;line-height: 120rpx;margin-left: 10rpx;">需支付金额：</text>
				<text style="display: inline-block;font-size: 32rpx;">{{privewMessage.order_price}}</text>
			</view>
			<view class="goPAy_right">
				<button type="default" @click="gopay" class="btn-check">去支付</button>
				<!-- <button type="default" class="active" v-if="privewMessage.user_integral<privewMessage.integral_price">去支付</button> -->
			</view>
		</view>
		
		<com-bottom-popup :show="roomPoup" @close="hidePopup">
			<view class="hotel_screening">
				筛选
			</view>
		</com-bottom-popup>
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
	import {isEmpty} from '../../../common/validate.js'
	import unipopup from '../../../components/uni-popup/uni-popup.vue'
	export default {
		components:{
			unipopup,
		},
		data() {
			return {
				img_url: this.$api.img_url,
				form:{
					unique_id:'',//第三方唯一产品编号
					product_code:'',//产品编号
					start_date:'',//起始日期 (0000-00-00)
					days:'',//预订天数
					num:1,//房间数量
					arrive_date:'',//到达日期（0000-00-00 00:00）
					passengers:[
						{
							"name":"",
							"mobile":""
						}
					],//入住人信息
					use_integral:1
				},
				beginTime:'',
				privewMessage:'',
				roomPoup:false,
				time:'14:00',
				disabled:true,
				is_checked:true,
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		onLoad(options){
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			if(options){
				this.beginTime=options.start_date
				this.form.unique_id=options.unique_id
				this.form.product_code=options.product_code
				this.form.start_date=options.start_date
				this.form.days=options.days
				this.form.arrive_date=options.start_date+' '+'14:00'
				this.getorderprivew(options.unique_id,options.product_code,options.start_date,options.days,this.form.num,this.form.use_integral)
			}			
		},
		methods:{
			// 使用金豆
			use(e) {
				console.log(e.detail.value)
				this.is_checked=e.detail.value
				if(this.is_checked){
					this.form.use_integral=1
					this.getorderprivew(this.form.unique_id,this.form.product_code,this.form.start_date,this.form.days,this.form.num,this.form.use_integral)
				}else{
					this.form.use_integral=0
					this.getorderprivew(this.form.unique_id,this.form.product_code,this.form.start_date,this.form.days,this.form.num,this.form.use_integral)
				}
			},
			bindTimeChange(e){
				 this.time = e.target.value
				 this.form.arrive_date=this.form.start_date+' '+e.target.value
			},
			count(){//房间数减
				if(this.form.num==1){
					uni.showToast({
						title: '房间最少为1间',
						icon: 'none'
					});
					setTimeout(function() {
						uni.hideToast();
					}, 2000);
					return
				}
				this.form.num--;
				this.getorderprivew(this.form.unique_id,this.form.product_code,this.form.start_date,this.form.days,this.form.num,this.form.use_integral)
			},
			add(){//房间增加
				if(this.form.num==this.privewMessage.booking_item.product_num){
					uni.showToast({
						title: '房间最多为'+this.privewMessage.booking_item.product_num+'间',
						icon: 'none'
					});
					setTimeout(function() {
						uni.hideToast();
					}, 2000);
					return
				}
				this.form.num++;
				this.getorderprivew(this.form.unique_id,this.form.product_code,this.form.start_date,this.form.days,this.form.num,this.form.use_integral)
			},
			getorderprivew(unique_id,product_code,start_date,days,num,use_integral){//获取预览订单信息
				this.$http
					.request({
						url: this.$api.hotel.getpreviewOrder,
						method: 'POST',
						data:{
							unique_id:unique_id,
							product_code:product_code,
							start_date:start_date,
							days:days,
							num:num,
							use_integral:use_integral,
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							this.privewMessage=res.data
							if(this.privewMessage.hotel_order_info.length>0){
								for(let i=0;i<this.form.passengers.length;i++){
									if(isEmpty(this.form.passengers[i].name)){
										this.form.passengers=this.privewMessage.hotel_order_info
									}
								}
							}
							// if(this.privewMessage.user_integral<this.privewMessage.integral_price){
							// 	this.disabled=false
							// }else{
							// 	this.disabled=true
							// }
						}else{
							this.$http.toast(res.msg);
						}
				});
			},
			addpepple(){ //添加入住人
				let arr=this.form.passengers
				arr.push({name:'',mobile:''})
				this.form.passengers=arr
				console.log(this.form)
			},
			removepepple(index){ //移除入住人
				let arr=[];
				for(let i=0;i<this.form.passengers.length;i++){
					if(i!=index){
						arr.push(this.form.passengers[i])		
					}
				}
				this.form.passengers=arr
			},
			gopay(){ //生成订单
				var myreg=/^[1][3,4,5,7,8][0-9]{9}$/;
				for(let i=0;i<this.form.passengers.length;i++){
					if (isEmpty(this.form.passengers[i].name)) {
						uni.showToast({
							title: '请填写姓名',
							icon: 'none'
						});
						setTimeout(function() {
							uni.hideToast();
						}, 2000);
						return
					}
					if (isEmpty(this.form.passengers[i].mobile)) {
						uni.showToast({
							title: '请填写联系电话,',
							icon: 'none'
						});
						setTimeout(function() {
							uni.hideToast();
						}, 2000);
						return
					}
					if (!myreg.test(this.form.passengers[i].mobile)) {
						uni.showToast({
							title: '请填写正确的手机号码,',
							icon: 'none'
						});
						setTimeout(function() {
							uni.hideToast();
						}, 2000);
						return
					}
				}
				let data={
					unique_id:this.form.unique_id,//第三方唯一产品编号
					product_code:this.form.product_code,//产品编号
					start_date:this.form.start_date,//起始日期 (0000-00-00)
					days:this.form.days,//预订天数
					num:this.form.num,//房间数量
					use_integral:this.form.use_integral,//是否使用金豆
					arrive_date:this.form.arrive_date,//到达日期（0000-00-00 00:00）
					passengers:JSON.stringify(this.form.passengers),//入住人信息
				}
				this.$http
					.request({
						url: this.$api.hotel.generateOrder,
						method: 'POST',
						data:data,
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							uni.navigateTo({
								url:'../pay?order_no='+res.data.order_no
							})
						}else{
							this.$http.toast(res.msg);
						}
				});
			},
		}
	}
</script>

<style lang="less">
	.orderSure_app{width: 100%;overflow: hidden;background: url(https://dev.mingyuanriji.cn/web/static/hotel/orderBack.png)no-repeat;background-size: 100% 100%;}
	.order_timer{width: 720rpx;height: 135rpx;background: #fff;margin: 30rpx auto;border-radius: 20rpx;
	display: flex;justify-content: space-between;}
	.order_timer_interval{display: flex;justify-content: space-evenly;width: 80%;}
	.order_timer_interval view{width: 50%;height: 135rpx;}
	.order_timer_interval view text{display:block ;width: 100%;text-align: center;height: 50%;line-height: 65rpx;}
	.order_timer_interval view text:nth-of-type(1){font-size: 25rpx;color: #000;}
	.order_timer_interval view text:nth-of-type(2){font-size: 28rpx;color: #FF5D0D;}
	.order_timer_count{width: 20%;height: 100%;line-height: 135rpx;text-align: center;font-size: 28rpx;color: #000;}
	.order-message{width: 720rpx;overflow: hidden;background: #fff;border-radius: 20rpx;margin: 0 auto;}
	.order_hotel_info{width: 100%;overflow: hidden;padding: 20rpx;display: flex;justify-content: space-evenly;}
	.order_hotel_logo{width: 200rpx;height: 200rpx;}
	.order_hotel_logo image{width: 200rpx;height: 200rpx;}
	.order_hotel-detail view:nth-of-type(1){font-size: 30rpx;color: #000000;width: 420rpx;overflow: hidden;
	text-overflow:ellipsis;
	white-space: nowrap;}
	.order_hotel-detail view:nth-of-type(2){font-size: 25rpx;margin: 10rpx 0;}
	.order_hotel-detail view:nth-of-type(2) text{margin: 0 10rpx;}
	.order_hotel-detail view:nth-of-type(3){font-size: 25rpx;}
	.order_hotel_notice{width: 100%;overflow: hidden;padding: 0 20rpx;background: #F3F3F3;border-radius: 0 0 20rpx 20rpx;}
	.order_hotel_notice view{margin: 0rpx 0 10rpx 0;}
	.order_hotel_notice text{display: inline-block;}
	.order_hotel_notice text:nth-of-type(1){width: 10rpx;height: 10rpx;background: #000;border-radius: 50%;margin: 0 10rpx;}
	.order_hotel_notice text:nth-of-type(2){font-size: 25rpx;}
	.check_in_message{width: 720rpx;overflow: hidden;background: #fff;margin: 20rpx auto;border-radius: 20rpx;padding: 20rpx;}
	.check_in_message_form{width: 95%;overflow: hidden;}
	.check_in_message_form view{width: 100%;height: 80rpx;margin: 10rpx 0;}
	.check_in_message_form view text{display: block;float: left;width: 150rpx;font-size: 28rpx;color: #000;line-height: 80rpx;text-align: left;}
	.check_in_message_form view input{width: 400rpx;height: 80rpx;font-size: 28rpx;color: #000;line-height: 80rpx;border-bottom: 1rpx dashed #808080;display: block;float: left;}
	.goPAy{width: 100%;height: 160rpx;background: #fff;padding:20rpx;display: flex;justify-content: space-between;
	position: fixed;bottom: 0;left: 0;box-sizing: border-box;}
	.btn-check{width: 260rpx;height: 80rpx;background: #FF5D0D;line-height: 80rpx;border-radius: 40rpx;color: #fff;outline: none;margin-top: 20rpx;}
	.active{width: 260rpx;height: 80rpx;background: #F3F4F3;line-height: 80rpx;border-radius: 40rpx;color: #fff;outline: none;margin-top: 20rpx;}
	.down-logo{width: 24rpx;height: 24rpx;position: absolute;top: 30rpx;right: 90rpx;}
	.add-logo{width: 50rpx;height: 50rpx;position: absolute;top: 0;right: 20rpx;}
	.count-logo{width: 50rpx;height: 50rpx;position: absolute;top: 10rpx;right: 20rpx;}
	.pay-poup{width: 400rpx;height: 400rpx;border-radius: 30rpx;background: #fff;padding: 40rpx 20rpx;}
	.pay-poup image{display: block;width: 120rpx;height: 120rpx;margin: 0rpx auto 35rpx;}
	.pay-poup view{width: 100%;overflow: hidden;font-size: 30rpx;color: #000;text-align: center;margin: 25rpx 0;}
	.use-points {
		background: #FFFFFF;
		margin-top: 40rpx;
		border-radius: 20rpx;
		padding: 10rpx 30rpx;
		font-size: 26rpx;
		color: #000000;
	}
	
	.use-points .xieti {
		font-size: 18rpx;
		color: #666;
		overflow: hidden;
	}
	
	.use-points .xieti .text {
		font-style: oblique;
		color: #F53939;
		margin-left: 20rpx;
		padding-right: 10rpx;
	}
	
	.points-switch {
		transform: scale(0.7);
	}
	
</style>
