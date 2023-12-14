<template>
	<view class="hotel-detail-app">
		<view class="hotel-detail-img">
			<image :src="hotelProduct.thumb_url" mode="aspectFill" class="hotel-detail-img_big"></image>
			<view class="hotel-detail-img_num" @click="lookImg(hotelProduct.id)">
				<image src="../../mch/img/pic_logo.png" mode="aspectFill" style="width: 30rpx;height: 30rpx;display: block;float: left;margin:8rpx 10rpx 0 0"></image>
				<text style="display: block;float: left;font-size: 28rpx;">{{diagram_num}}张</text>
			</view>
		</view>
		<view class="hotel-detail-product">
			<view class="hotel-name">
				{{hotelProduct.name}}
			</view>
			<view class="hotel-detail-address-phone">
				<view style="width: 70%;font-size: 26rpx;">
					{{hotelProduct.address}}
				</view>
				<view class="address-phone">
					<view style="margin-right: 20rpx;" @click="location(hotelProduct.tx_lat,hotelProduct.tx_lng,hotelProduct.address)">
						<image src="https://dev.mingyuanriji.cn/web/static//hotel/locations.png" mode="" style="margin: 5rpx 0 5rpx 0;"></image>
						<text>地图</text>
					</view>
					<view @click="callPhone(hotelProduct.contact_phone,hotelProduct.contact_mobile)">
						<image :src="img_url+'iphone-my-new.png'" mode="" style="width: 58rpx;height: 58rpx;"></image>
						<text>电话</text>
					</view>
				</view>
			</view>
		</view>
		<view class="hotel-facilities">
			<view class="hotel-facilities-list">
				<view>
					<image :src="img_url+'parking-lot.png'" mode=""></image>
					<text>停车场</text>
				</view>
				<view>
					<image :src="img_url+'wifi.png'" mode=""></image>
					<text>WIFI</text>
				</view>
				<view style="margin-right: 80rpx;">
					<image :src="img_url+'luggage.png'" mode=""></image>
					<text>行李寄存</text>
				</view>
				<view @click="morefacilities(hotelProduct)">
					<text>设施/服务</text>
					<image :src="img_url+'arrow-right-seracher.png'" mode=""></image>
				</view>
			</view>
		</view>
		<view class="hotel-time" @click="timeShow=true">
			<view class="hotel-select-date">
				<view style="width: 35%;height: 120rpx;">
					<view style="text-align: center;line-height: 60rpx;font-size: 30rpx;">入住时间</view>
					<view style="text-align: center;line-height: 60rpx;color: rgb(255, 113, 4);">
						{{timeStaus.startStr.dateStr}}
					</view>
				</view>
				<view style="width: 10%;height: 120rpx;line-height: 120rpx;text-align: center;font-size: 30rpx;">
					至
				</view>
				<view style="width: 35%;height: 120rpx;">
					<view style="text-align: center;line-height: 60rpx;font-size: 30rpx;">离店时间</view>
					<view style="text-align: center;line-height: 60rpx;color: rgb(255, 113, 4);">
					{{timeStaus.endStr.dateStr}}
					</view>
				</view>
				<view style="width: 20%;height: 120rpx;box-sizing: border-box;" >
					<view style="width: 100%;margin: 65rpx 0 0 0;font-size: 28rpx;text-align: center;">共{{timeStaus.dayCount}}晚</view>
				</view>
			</view>
		</view>
		<view class="hoteil-room-list">
			<view class="hoteil-room-item" v-for="(item,index) in hotelroomlist" :key='index' @click="reservation(item)">
				<view class="hoteil-room-item-img">
					<image :src="item.product_thumb" mode=""></image>
				</view>
				<view class="hoteil-room-item-product">
					<view style="font-size: 30rpx;color: #000;font-weight: bold;margin-top: 10rpx;">
						{{item.product_name}}
					</view>
					<view style="font-size: 27rpx;margin-top: 10rpx;">
						<text  v-if="item.bed_type=='single'" style="margin: 0 10rpx 0 0;">单床</text>
						<text  v-if="item.bed_type=='double'" style="margin: 0 10rpx 0 0;">双床</text>
						<text  v-if="item.bed_type=='big'" style="margin: 0 10rpx 0 0;">大床</text>
						<text  v-if="item.window=='no'" style="margin: 0 10rpx 0 0;">无窗</text>
						<text  v-if="item.window=='out'" style="margin: 0 10rpx 0 0;">外窗</text>
						<text  v-if="item.window=='part_no'" style="margin: 0 10rpx 0 0;">部分无窗</text>
						<text  v-if="item.window=='inner'" style="margin: 0 10rpx 0 0;">内窗</text>
						<text  v-if="item.window=='part_inner'" style="margin: 0 10rpx 0 0;">部分内窗</text>
						<text  v-if="item.ban_smoking==1" style="margin: 0 10rpx 0 0;">禁烟</text>
					</view>
				</view>
				<view class="hoteil-room-item-money">
					<view class="room-num" style="width: 100%;font-size: 24rpx;">
						剩余房间数：{{item.product_num}}
					</view>
					<view style="color:rgb(255, 113, 4) ;">
						<text style="font-size: 30rpx;">￥</text>
						<text style="font-weight: bold;font-size: 36rpx;">{{item.product_price}}</text>
					</view>
					<view>
						<image src="https://dev.mingyuanriji.cn/web/static//hotel/drawup.png" mode=""></image>
					</view>
				</view>
			</view>
		</view>
		<view class="hotel-notice">
			<view class="hotel-notice-title">
				入住提示
			</view>
			<view class="hotel-notice-item">
				<text>
					•到店时间：请在入住日下午14：00后办理入住,提前到店可能需要等待
				</text>
			</view>
		</view>
		<calendar :is-show="timeShow"  :start-date="startDate" :end-date="endDate" mode="2"  @callback="getDate" />
		<unipopup ref="popup" type="bottom">
			<view style="width: 100%;min-height: 600rpx;background: #fff;border-radius: 20rpx 20rpx 0 0;box-sizing: border-box;">
				<view class="popup-title">
					{{roomdetail.product_name}}
				</view>
				<view class="popup-image">
					<view class="tui-banner-swiper" v-if="swiperShow">
						<swiper :duration="150"  @change="bannerChange" :style="{height:180+'px'}">
							<block v-for="(item,b_index) in roomdetail.diagram_url" :key="b_index">
								<swiper-item :data-index="b_index+1">
									<image :src="item.pic_url" mode="aspectFill" class="tui-slide-image" :style="{height:180+'px'}" />
								</swiper-item>
							</block>
						</swiper>
						<jx-tag class="tui-tag-class" type="translucent" shape="circle" size="small">{{bannerIndex+1}}/{{roomdetail.diagram_url.length}}</jx-tag>
					</view>
					<image :src="roomdetail.product_thumb" mode="aspectFill"  v-if="!swiperShow"  style="display: block;width: 100%;height: 100%;"></image>
				</view>
				<view class="popup-pruc-list">
					<view>
						<text>楼层</text>
						<text>{{roomdetail.floor}}层</text>
					</view>
					<view>
						<text>窗户</text>
						<text  v-if="roomdetail.window=='no'" >无窗</text>
						<text  v-if="roomdetail.window=='out'" >外窗</text>
						<text  v-if="roomdetail.window=='part_no'" >部分无窗</text>
						<text  v-if="roomdetail.window=='inner'" >内窗</text>
						<text  v-if="roomdetail.window=='part_inner'" >部分内窗</text>
					</view>
					<view v-if="Number(roomdetail.bed_width)>0">
						<text>床型</text>
						<text>{{roomdetail.bed_width}}米</text>
					</view>
					<view>
						<text>面积</text>
						<text>30㎡</text>
					</view>
					<view>
						<text>人数</text>
						<text>{{roomdetail.people_num}}人</text>
					</view>
					<view>
						<text>网络</text>
						<text>WIFI</text>
					</view>
				</view>
				<view class="popup-facilities">
					<view class="popup-facilities-title" style="width: 100%;margin-bottom: 15rpx;color: #000;font-size: 36rpx;">
						设施信息
					</view>
					<text>免费WIFI</text>
					<text>免费停车</text>
					<text>行李寄存</text>
					<text>24小时前台服务</text>
					<text>公共区域闭路电视监控系统</text>
					<text>公共休息区</text>
				</view>
				<view class="reservationOrder">
					<view style="width: 30%;text-align: center;">
						<text style="font-size: 30rpx;">￥</text>
						<text style="font-size: 40rpx;color: rgb(255, 113, 4);font-weight: bold;">{{roomdetail.product_price}}</text>
					</view>
					<view style="width: 70%;background:rgb(255, 113, 4);text-align: center;color: #fff;" @click="orderSure">立即预订</view>
				</view>
			</view>
		</unipopup>
	
	</view>
</template>

<script>
	import Calendar from '@/components/mobile-calendar-simple/Calendar.vue';
	import unipopup from '@/components/uni-popup/uni-popup';
	import {isEmpty} from '@/common/validate.js';
	import jxTag from "@/components/tag/tag"
	export default {
		components:{
			Calendar, 
			unipopup,
			jxTag
		},
		data() {
			return {
				height:'',
				scrollH:'',
				// bannerLength:0,
				bannerIndex: 0,
				img_url: this.$api.img_url,
				timeShow:false,//入住时间选择
				timeStaus:{
					dayCount:'',
					endStr:{
						dateStr:'',
						recent:'',
						week:''
					},
					startStr:{
						dateStr:'',
						recent:'',
						week:''
					}
				},
				startDate:'',//开始时间
				endDate:'',//结束时间
				time:'',//当前日期
				tomoryTime:'',//明天日期
				id:'',
				hotelroomlist:'',
				hotelProduct:'',
				roomdetail:'',
				swiperShow:false,
				diagram_num:''
			};
		},
		onLoad(options) {
			if(options&&options.id){
				this.id=options.id
				if(uni.getStorageSync('timeStaus')){
					this.timeStaus=uni.getStorageSync('timeStaus')
					this.startDate=uni.getStorageSync('timeStaus').startStr.dateStr
					this.endDate=uni.getStorageSync('timeStaus').endStr.dateStr
					this.getDetail(options.id,this.timeStaus.startStr.dateStr,this.timeStaus.dayCount)
				}else{
					this.getTime()
					this.getDetail(options.id,this.time,this.timeStaus.days)
				}
			}
		},
		onShow() {
			let obj = {};
			// #ifdef MP-WEIXIN
			obj = wx.getMenuButtonBoundingClientRect();
			// #endif
			setTimeout(() => {
				uni.getSystemInfo({
					success: (res) => {
						this.height = obj.top ? (obj.top + obj.height + 8) : (res.statusBarHeight + 44);
						this.scrollH = res.windowWidth
					}
				})
			}, 50)
		},
		methods:{
			bannerChange: function(e) {
				this.bannerIndex = e.detail.current
			},
			lookImg(id){ //查看酒店所有图片
				uni.navigateTo({
					url:'../hotelImg/hotelImg?hotel_id='+id
				})
			},
			getTime(){
				this.time=this.changeTime(new Date())
				var day= new Date();
				day.setTime(day.getTime()+24*60*60*1000);
				this.tomoryTime = day.getFullYear()+"-" +((day.getMonth()+1)<10?'0'+(day.getMonth()+1):(day.getMonth()+1))+ "-" +(day.getDate()<10?'0'+day.getDate():day.getDate());
				this.startDate=this.time
				this.endDate=this.tomoryTime
				this.timeStaus.startStr.dateStr=this.time
				this.timeStaus.startStr.recent='今天'
				this.timeStaus.endStr.dateStr=this.tomoryTime
				this.timeStaus.endStr.recent='明天'
				this.timeStaus.dayCount=1
			},
			getDate(date){ //获取入住时间
				this.timeStaus=date				
				this.startDate=this.timeStaus.startStr.dateStr
				this.endDate=this.timeStaus.endStr.dateStr
				this.timeShow=false
				uni.setStorageSync('timeStaus',this.timeStaus)
			},
			changeTime(d){ //获取今日时间
			     return d.getFullYear() + '-' +((d.getMonth()+1)<10?'0'+(d.getMonth()+1):(d.getMonth()+1)) + '-' + (d.getDate()<10?'0'+d.getDate():d.getDate());
			},
			morefacilities(hotelProduct){//跳转到跟多的设施/服务
				uni.setStorageSync('hotelProduct',hotelProduct)
				uni.navigateTo({
					url:'../facilities/facilities'
				})
			},
			reservation(item){ //预定弹窗
				this.$refs.popup.open()
				this.roomdetail=item
				if(this.roomdetail.diagram_url.length>0){
					this.swiperShow=true
				}else{
					this.swiperShow=false
				}
			},
			orderSure(){
				let roominfo=this.roomdetail
				if(roominfo.product_num==0){
					this.$http.toast('剩余房间为0，没有更多房间了.请选择其他房间预订，谢谢');
					return
				}
				if(isEmpty(this.timeStaus.dayCount)){
					var myDate = new Date();
					let year=myDate.getFullYear();
					let monthy=myDate.getMonth()+1;
					let day=myDate.getDate(); 
					if(monthy<10){
						monthy='0'+monthy
					}
					if(day<10){
						day='0'+day
					}
					let nowTime=year+'-'+monthy+'-'+day
					uni.navigateTo({
						url:'../../mch/hotel/orderSure/orderSure?unique_id='+roominfo.unique_id+'&product_code='+roominfo.product_code+"&start_date="+nowTime+"&days="+'1'
					})
				}else{
					uni.navigateTo({
						url:'../../mch/hotel/orderSure/orderSure?unique_id='+roominfo.unique_id+'&product_code='+roominfo.product_code+"&start_date="+this.timeStaus.startStr.dateStr+"&days="+this.timeStaus.dayCount
					})
					this.$refs.popup.close()
				}	
			},
			callPhone(phone,mobile){ //拨打电话
				if(phone.length>0){
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
				}
				if(mobile.length>0){
					uni.makePhoneCall({
					 	// 手机号
					    phoneNumber: mobile, 				
						// 成功回调
						success: (res) => {
							console.log('调用成功!')	
						},				
						// 失败回调
						fail: (res) => {
							console.log('调用失败!')
						}					
					 })
				}
			},
			location(lat,lnt,addrress){
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
				})	
				// #endif
			},
			getDetail(id,start_date,days){
				this.$http
					.request({
						url: this.$api.hotel.gethoteldetail,
						method: 'POST',
						data:{
							hotel_id:id,
							start_date:start_date,
							days:days,
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							this.hotelroomlist=res.data.booking_list
							this.hotelProduct=res.data.hotel_info
							this.diagram_num=res.data.diagram_num
						}else{
							this.$http.toast(res.msg);
						}
				});
			},
		
		
		}
	}
</script>

<style lang="less">
	.hotel-detail-app{width: 100%;overflow: hidden;}
	.hotel-detail-img{width: 100%;height: 350rpx;position: relative;}
	.hotel-detail-img_big{display: block;width: 100%;height: 100%;}
	.hotel-detail-img_num{position: absolute;right: 50rpx;bottom: 20rpx;z-index: 999;color: #fff;}
	.hotel-detail-product{width: 100%;overflow: hidden;background: #fff;padding: 20rpx 30rpx;box-sizing: border-box;}
	.hotel-name{width: 100%;overflow: hidden;color: #000;font-weight: bold;}
	.hotel-detail-address-phone{width: 100%;overflow: hidden;display: flex;justify-content: space-between;margin: 20rpx 0 0 0;}
	.address-phone{width: 30%;overflow: hidden;display: flex;justify-content: space-evenly;font-size: 24rpx;}
	.address-phone image{display: block;width: 50rpx;height: 50rpx;}
	.hotel-facilities{width:100%;overflow: hidden;margin-top: 4rpx;background: #fff;padding: 0 30rpx;box-sizing: border-box;}
	.hotel-facilities-list{width: 100%;overflow: hidden;display: flex;justify-content: space-between;margin: 20rpx 0 30rpx 0;}
	.hotel-facilities-list view{display: flex;justify-content: space-evenly;font-size: 26rpx;}
	.hotel-facilities-list view image{width: 40rpx;height: 40rpx;}
	.hotel-time{width: 100%;overflow: hidden;background: #fff;margin: 6rpx 0 4rpx 0;}
	.hotel-select-date{width: 100%;height: 120rpx;border-bottom: 1rpx solid rgb(239,239,239);display: flex;justify-content: space-between;}
	.hotel-select-name{width: 100%;height: 100rpx;box-sizing: border-box;border-bottom: 1rpx solid rgb(239,239,239);padding: 0 30rpx 0 30rpx;line-height: 100rpx;}
	.hotel-select-name image{width: 30rpx;height: 30rpx;float: right;display: block;margin-top: 32rpx;}
	.hoteil-room-list{width: 100%;overflow: hidden;padding: 0 20rpx;box-sizing: border-box;margin:10rpx 0 4rpx 0;}
	.hoteil-room-item{width: 100%;height: 240rpx;display: flex;justify-content: space-evenly;background: #fff;padding: 20rpx 0;margin: 10rpx 0;}
	.hoteil-room-item-img{width: 30%;overflow: hidden;}
	.hoteil-room-item-img image{width: 100%;height:180rpx;display: block;float: left;margin-top: 10rpx;}
	.hoteil-room-item-product{width: 40%;height: 200rpx;padding-left: 15rpx;box-sizing: border-box;}
	.hoteil-room-item-money{width: 30%;height: 100rpx;display: flex;justify-content: space-evenly;flex-wrap: wrap;margin-top: 20rpx;line-height: 100rpx;}
	.hoteil-room-item-money image{width: 55rpx;height: 60rpx;}
	.hotel-notice{width: 100%;overflow: hidden;padding:30rpx;box-sizing: border-box;background: #fff;margin-bottom: 100rpx;}
	.hotel-notice-title{color: #000;margin-bottom: 20rpx;}
	.hotel-notice-item{font-size: 26rpx;}
	.popup-title{width: 100%;height: 100rpx;text-align: center;line-height: 100rpx;color: #000;}
	.popup-image{width: 100%;height: 400rpx;}
	.popup-pruc-list{width: 100%;overflow: hidden;display: flex;justify-content: space-between;flex-wrap: wrap;margin-top:20rpx}
	.popup-pruc-list view{width: 50%;text-align: center;font-size: 28rpx;margin: 5rpx 0;}
	.popup-pruc-list view text{display: inline-block;width: 50%;}
	.popup-pruc-list view text:nth-of-type(1){text-align: right;padding-right: 30rpx;box-sizing: border-box;}
	.popup-pruc-list view text:nth-of-type(2){text-align: left;color: #000;}
	.popup-facilities{width: 100%;overflow: hidden;display: flex;justify-content: space-evenly;flex-wrap: wrap;padding: 20rpx;box-sizing: border-box;border-top: 1rpx solid rgb(242,245,249);border-bottom: 1rpx solid rgb(242,245,249);margin-top: 20rpx;font-size: 28rpx;margin-bottom: 40rpx;}
	.reservationOrder{width: 100%;height: 100rpx;display: flex;justify-content: space-between;line-height: 100rpx;}



	.tui-banner-swiper{width: 100%;overflow: hidden;position: relative;}
	.tui-banner-swiper .tui-tag-class {position: absolute;color: #fff;bottom: 25rpx;right: 40rpx;}
	.tui-slide-image {width: 100%;display: block;}

</style>
