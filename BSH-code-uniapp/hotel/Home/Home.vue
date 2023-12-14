<template>
	<view class="hotel-home">
		<view class="swiper">
			<FatFatMeng-Swiper
			:swiperStyleClass="{'height':'350rpx','background-color':'rgba(0, 0, 0, .2)'}"
			:SwiperImglist="banner"
			>
			</FatFatMeng-Swiper>
		</view>
		<view class="hote-message">
			<view class="hotel-Fill">
				<view class="hotel-type">
					<view @click="changeType(1)" :class="changeTypeIndex==1?'active':'activeNo'">全日房</view>
					<view @click="changeType(2)" :class="changeTypeIndex==2?'active':'actoveNo'">钟点房</view>
				</view>
				<view class="hotel-select-city" >
					<view style="width: 70%;padding-left: 30rpx;color: rgb(255, 113, 4);font-weight: bold;border-right: 1rpx solid rgb(232,236,240);overflow: hidden;
					text-overflow:ellipsis;white-space: nowrap;" @click="setCITY">
						{{region}}
					</view>
					<view style="width: 30%;text-align: left;font-size: 28rpx;color: #000;" @tap="chooseAddress">
						 <image :src="img_url+'location-my-new.png'" mode="" ></image>     我的位置
					</view>
				</view>  
				<view class="hotel-select-date" @click="timeShow=true">
					<view style="width: 35%;height: 120rpx;">
						<view style="text-align: center;line-height: 60rpx;font-size: 30rpx;">入住时间</view>
						<view style="text-align: center;line-height: 60rpx;color: rgb(255, 113, 4);">
							{{timeStaus.startStr.dateStr}}
						</view>
					</view>
					<view style="width: 10%;height: 120rpx;line-height: 120rpx;text-align: center;font-size: 30rpx;" v-if="changeTypeIndex==1">
						至
					</view>
					<view style="width: 35%;height: 120rpx;" v-if="changeTypeIndex==1">
						<view style="text-align: center;line-height: 60rpx;font-size: 30rpx;">离店时间</view>
						<view style="text-align: center;line-height: 60rpx;color: rgb(255, 113, 4);">
						{{timeStaus.endStr.dateStr}}
						</view>
					</view>
					<view style="width: 20%;height: 120rpx;box-sizing: border-box;" v-if="changeTypeIndex==1">
						<view style="width: 100%;margin: 65rpx 0 0 0;font-size: 28rpx;text-align: center;">共{{timeStaus.dayCount}}晚</view>
					</view>
				</view>
				<view class="hotel-select-name" @click="hotelsearch">
					{{hotelName}}
					<image :src="img_url+'arrow-right-seracher.png'" mode=""></image>
					<image :src="img_url+'delete_error.png'" mode="" style="width: 30rpx;height: 30rpx;float: right;display: block;margin-top: 32rpx;margin-right: 60rpx;" @click.stop="deletesearch"  
					v-if="form.keyword.length>0"></image>
				</view>
				<view class="hotel-query">
					<button type="default" @click="checkHotel">查询酒店</button>
				</view>
			</view>
			<view class="hotel-advertising">
				<view class="hotel-advertising-image">
					<image :src="advert" mode=""></image>
				</view>
			</view>
		</view>
		<citySelect @back_city="back_city" v-if="show"></citySelect>
		<calendar :is-show="timeShow"  :start-date="startDate" :end-date="endDate" mode="2"  @callback="getDate" />
	</view>
</template>

<script>
	import citySelect from '@/components/linzq-citySelect/linzq-citySelect.vue';
	import Calendar from '@/components/mobile-calendar-simple/Calendar.vue';
	import {isEmpty} from '@/common/validate.js'
	export default {
		components:{
			citySelect,
			Calendar,
		},
		data() {
			return {
				img_url: this.$api.img_url,
				changeTypeIndex:1,//房间类型选择
				show:false,//市区选择影藏和显示
				region:'定位中...',//默认给广州
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
				hotelName:'酒店名称',//酒店名称
				flag:false,
				timeflag:true,
				form:{ //直接查询需要的参数
					city_id:'',
					type:'in',//酒店类型：in全日房、hour钟点房
					start_date:'',
					days:"",
					lng:'',
					lat:'',
					keyword:'',
				},
				citymessage:'',//城市信息
				banner:[],
				advert:''
			};
		},
		onLoad() {
			this.getTime()
			this.getmyLOcation() 
		},
		onShow() {
			if(uni.getStorageSync('searchtext')){
				this.hotelName=uni.getStorageSync('searchtext')
				this.form.keyword=uni.getStorageSync('searchtext')
			}
		},
		methods:{
			changeType(index){ //房间类型
				this.changeTypeIndex=index
				if(index==1){this.form.type='in'}
				if(index==2){this.form.type='hour'}
			},
			setCITY(){ //选择城市
				this.show=true
			},
			deletesearch(){
				uni.removeStorageSync('searchtext');
				this.form.keyword='';
				this.hotelName='酒店名称';
			},
			back_city(e) {  //获取城市
				if (e !== 'no') { 
					this.region = e.cityName ;
					this.show=false;
					this.getcityinfo('','',this.region)
				} 
				else { 
					this.show=false;
				 }
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
				this.form.start_date=this.time
				this.form.days=1
			},
			getDate(date){ //获取入住时间
				this.timeStaus=date				
				this.startDate=this.timeStaus.startStr.dateStr
				this.endDate=this.timeStaus.endStr.dateStr
				this.timeShow=false
				this.form.start_date=this.timeStaus.startStr.dateStr
				this.form.days=this.timeStaus.dayCount
				uni.setStorageSync('timeStaus',this.timeStaus)
			},
			changeTime(d){ //获取今日时间
			     return d.getFullYear() + '-' +((d.getMonth()+1)<10?'0'+(d.getMonth()+1):(d.getMonth()+1)) + '-' + (d.getDate()<10?'0'+d.getDate():d.getDate());
			},
			getmyLOcation(){
				let that=this
				//#ifdef H5
					that.$unifylocation.locationH5()
				setTimeout(() => {
					if (uni.getStorageSync('x-longitude-new') || uni.getStorageSync('x-latitude-new')) {
						if(uni.getStorageSync('locationTime')){
							if(parseInt(new Date().getTime()/1000)-uni.getStorageSync('locationTime')>=86400){
								that.timeflag=uni.getStorageSync("flag")
							}
						}else{
							that.timeflag=true
						}
						var countLO = that.$unifylocation.getMapDistanceApi(uni.getStorageSync('x-longitude'), uni
							.getStorageSync('x-latitude'), uni.getStorageSync('x-longitude-new'), uni
							.getStorageSync('x-latitude-new'))
						if ((Math.floor(countLO / 1000 * 100) / 100) > 3&&that.timeflag) {
							uni.showModal({
								title: '提示',
								content: "已经超出初次定位3公里，是否重新定位",
								success: function(result) {
									if (result.confirm) {
										uni.setStorageSync('x-longitude', uni.getStorageSync(
											'x-longitude-new'))
										uni.setStorageSync('x-latitude', uni.getStorageSync(
											'x-latitude-new'))
										that.getcityinfo(uni.getStorageSync('x-longitude-new'),uni.getStorageSync('x-latitude-new'))
									} else if (result.cancel) {
										uni.setStorageSync("flag",false)
										uni.setStorageSync("locationTime",parseInt(new Date().getTime()/1000))
										that.getcityinfo(uni.getStorageSync('x-longitude'),uni.getStorageSync('x-latitude'))
									}
								}
							})
						} else {
							that.getcityinfo(uni.getStorageSync('x-longitude'),uni.getStorageSync('x-latitude'))
						}
				
					}
				}, 1000)
				// #endif
				// #ifdef MP||APP-PLUS
				// #ifdef MP
				uni.getSetting({
				   success(res) {
				      if(!res.authSetting['scope.userLocation']){
						  uni.showModal({
						  	title:"是否授权当前位置",
						  	content: '需要获取您的地理位置，请确认授权，否则地图功能将无法使用',
						  	confirmText: "确认",
							showCancel:false,
						  	success: (res) => {
						  		if (res.confirm) {
						  			uni.openSetting({
						  				success: (res) => {
						  					uni.redirectTo({
						  						url:'/mch/hotel/hotel'
						  					})
						  					that.$unifylocation.locationMp()
						  				}
						  			})
						  		} else {
						  		
						  		}
						  	}
						  })
					  }
				   }
				})
				// #endif
				that.$unifylocation.locationMp()
				setTimeout(() => {
					if (uni.getStorageSync('x-longitude-new') || uni.getStorageSync('x-latitude-new')) {
						if(uni.getStorageSync('locationTime')){
							if(parseInt(new Date().getTime()/1000)-uni.getStorageSync('locationTime')>=86400){
								that.timeflag=uni.getStorageSync("flag")
							}
						}else{
							that.timeflag=true
						}
						var countLO = that.$unifylocation.getMapDistanceApi(uni.getStorageSync('x-longitude'), uni
							.getStorageSync('x-latitude'), uni.getStorageSync('x-longitude-new'), uni
							.getStorageSync('x-latitude-new'))
						if ((Math.floor(countLO / 1000 * 100) / 100) > 3&&that.timeflag) {
							uni.showModal({
								title: '提示',
								content: "已经超出初次定位3公里，是否重新定位",
								success: function(result) {
									if (result.confirm) {
										uni.setStorageSync('x-longitude', uni.getStorageSync(
											'x-longitude-new'))
										uni.setStorageSync('x-latitude', uni.getStorageSync(
											'x-latitude-new'))
										that.getcityinfo(uni.getStorageSync('x-longitude-new'),uni.getStorageSync('x-latitude-new'))
									} else if (result.cancel) {
										uni.setStorageSync("flag",false)
										uni.setStorageSync("locationTime",parseInt(new Date().getTime()/1000))
										that.getcityinfo(uni.getStorageSync('x-longitude'),uni.getStorageSync('x-latitude'))
									}
								}
							})
						} else {
							that.getcityinfo(uni.getStorageSync('x-longitude'),uni.getStorageSync('x-latitude'))
						}
				
					}
				}, 1000)
				// #endif
			},
			getcityinfo(longitude,latitude,city_name){ //获取当前城市信息
				this.$http
					.request({
						url: this.$api.hotel.getcity,
						method: 'POST',
						data:{
							longitude:longitude,
							latitude:latitude,
							city_name:city_name,
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							this.citymessage=res.data
							this.banner=this.citymessage.banner
							this.advert=this.citymessage.advert.image
							this.region=res.data.city_name
							this.form.city_id=res.data.city_id
							uni.setStorageSync("citymessage",this.citymessage.district)
						}else{
							this.$http.toast(res.msg);
						}
				});	
			},
			chooseAddress() { //选择地址我的定位
				var that = this
				this.chooseLocation(function(res){
					// 		console.log(res)
					// 		that.region=res.name
				});
				uni.showLoading({
				    title: '加载中'
				});
				
				setTimeout(function () {
				    uni.hideLoading();
				}, 2000);
				that.getmyLOcation()
			},
			hotelsearch(){//点击输入酒店搜索
				console.log(this.form)
				uni.navigateTo({
					url:'../hotelSearch/hotelSearch?form='+JSON.stringify(this.form)+"&region="+this.region
				})
			},
			checkHotel(){ //直接点击搜索酒店
				if(isEmpty(this.form.start_date)){
					uni.showToast({
						title: '请选择入住时间',
						icon: 'none'
					});
					setTimeout(function() {
						uni.hideToast();
					}, 2000);
					return
				}
				this.$http
					.request({
						url: this.$api.hotel.searchhotel,
						method: 'POST',
						data:this.form,
						showLoading: true
					})
					.then(res => {
						uni.setStorageSync('timeStaus',this.timeStaus)
						if(res.code==0){
							if(res.data.history==1){
								if(uni.getStorageSync('searchtext')){
									uni.navigateTo({
										url:'../searchList/searchList?search_id='+res.data.search_id+"&region="+this.region+"&city_id="+this.form.city_id+"&searchKey="+uni.getStorageSync('searchtext')
									})
								}else{
									uni.navigateTo({
										url:'../searchList/searchList?search_id='+res.data.search_id+"&region="+this.region+"&city_id="+this.form.city_id
									})
								}
							}else if(res.data.history==0){
								if(uni.getStorageSync('searchtext')){
									uni.navigateTo({
										url:'../searchList/searchList?prepare_id='+res.data.prepare_id+"&region="+this.region+"&city_id="+this.form.city_id+"&searchKey="+uni.getStorageSync('searchtext')
									})
								}else{
									uni.navigateTo({
										url:'../searchList/searchList?prepare_id='+res.data.prepare_id+"&region="+this.region+"&city_id="+this.form.city_id
									})
								}
							}
						}else{
							this.$http.toast(res.msg);
						}
				});	
			}
		}
	}
</script>

<style lang="less">
	.hotel-home {width: 100%;overflow: hidden;}
	.swiper{width: 100%;overflow: hidden;}
	.hote-message{width: 100%;overflow: hidden;position: absolute;top: 300rpx;left: 0;z-index: 99;}
	.hotel-Fill{width: 90%;overflow: hidden;background: #fff;margin: 0 auto 20rpx;border-radius: 30rpx;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.4);}
	.hotel-type{width: 100%;overflow: hidden;display: flex;justify-content: space-between;}
	.hotel-type view{width: 50%;height: 80rpx;text-align: center;line-height: 80rpx;}
	.active{background: #fff;border-radius: 30rpx 30rpx 0 0;color: rgb(255, 113, 4);font-weight: bold;}
	.activeNo{background: rgb(245,245,249);border-radius: 30rpx 0rpx 30rpx 0;}
	.actoveNo{background: rgb(245,245,249);border-radius: 0rpx 30rpx 0 30rpx;}
	.hotel-select-city{width: 100%;height: 80rpx;border-bottom: 1rpx solid rgb(239,239,239);padding: 10rpx 0;display: flex;justify-content: space-between;line-height: 60rpx;box-sizing: border-box;}
	.hotel-select-city image{width: 35rpx;height: 35rpx;margin: 13rpx 5rpx 0 10rpx;display: block;float: left;}
	.hotel-select-date{width: 100%;height: 120rpx;border-bottom: 1rpx solid rgb(239,239,239);display: flex;justify-content: space-between;}
	.hotel-select-name{width: 100%;height: 100rpx;box-sizing: border-box;border-bottom: 1rpx solid rgb(239,239,239);padding: 0 30rpx 0 30rpx;line-height: 100rpx;}
	.hotel-select-name image{width: 30rpx;height: 30rpx;float: right;display: block;margin-top: 32rpx;}
	.hotel-query{width: 100%;overflow: hidden;}
	.hotel-query button{width: 80%;height: 80rpx;line-height: 80rpx;font-size: 32rpx;text-align: center;margin: 25rpx auto;background: rgb(255, 113, 4);color: #fff;border-radius: 40rpx;}
	.hotel-advertising{width: 100%;overflow: hidden;}
	.hotel-advertising-image{width: 90%;height: 200rpx;margin: 0 auto;}
	.hotel-advertising-image image{width: 100%;height: 100%;}
</style>
