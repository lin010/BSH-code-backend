<template>
	<view class="hotel_app">
		<view class="hotel_search">
			<view class="hotel_search_header">
				<view @click="select(1)" :class="selectIndex==1?'active':''">国内</view>
				<view @click="select(2)" :class="selectIndex==2?'active':''">钟点房</view>
				<view @click="select(3)" :class="selectIndex==3?'active':''">民宿</view>
			</view>
			<view class="hotel_search_address">
				<picker  mode="multiSelector" @change="picker"  :value="value" @columnchange="columnPicker" :range="multiArray"
				style="float: left;">	
					<view hotel_search_address_int>
						<view class="index1_content_top_l_name" style="max-width: 400rpx;height: 80rpx;line-height: 80rpx;font-size: 28rpx;">{{text.length<=0?'请选择':text}}</view>
					</view>
				</picker>
				<view class="hotel_search_address_right">
					<image :src="img_url+'/hotel/right.png'" mode=""></image>
				</view>
				<view class="hotel_search_address_location" @tap="chooseAddress">
					<image :src="img_url+'/hotel/my-location.png'" mode=""></image>
					<text>我的位置</text>
				</view>
			</view>
			<view class="hotel_search_time" @click="timeShow=true">
				<view v-if="timeStaus.endStr.dateStr.length<=0">
					请选择入住时间
					<image :src="img_url+'/hotel/right.png'" mode=""></image>
				</view>
				<view v-if="timeStaus.endStr.dateStr.length>0">
					<text style="font-size: 26rpx;">
						{{timeStaus.startStr.dateStr}}{{timeStaus.startStr.recent}}--{{timeStaus.endStr.dateStr}}{{timeStaus.endStr.recent}}
					</text>
					<text>
						共{{timeStaus.dayCount}}天
					</text>
				</view>
			</view>
			<view class="hotel_search_hotel_address">
				<input type="text" value="" placeholder="酒店/地标/关键词" v-model="form.keyword"/>
			</view>
			<view class="hotel_search_screening" @click="popupShow=true">
				<input type="text"  placeholder="价格/星级" disabled v-model="price_star"/>
				<image :src="img_url+'/hotel/right.png'" mode=""></image>
			</view>
			<view class="hotel_search_sure" @click="search">
				搜索酒店
			</view>
			<view class="hotel_search_advertising">
				<image :src="img_url+'/hotel/hotel_advising.png'" mode=""></image>
			</view>
		</view>
		<view class="hotel_go_shop" @click="linkTo">
			<image :src="img_url+'/hotel/go_myShop.png'" mode=""></image>
		</view>
		<view class="hotel_list">
			<view class="hotel_list_title">
				推荐酒店
			</view>
			<view class="hotel_list_item" v-for="(item,index) in recommendedList" :key='index' @click="checkInto(item.id)">
				<image :src="item.thumb_url" mode="" class="hotel_logo"></image>
				<view class="hotel_list_item_center">
					<view class="hotel_list_item_name">
						<text>{{item.name}}</text>
						<text>{{item.type_text}}</text>
					</view>
					<view class="hotel_list_item_product">
						<text style="display: inline-block;margin-right: 10rpx;color: red;">{{item.cmt_grade}}分</text>很好 
						<text style="display: inline-block;margin:0 10rpx;color: red;">{{item.cmt_num}}+</text>点评
					</view>
					<view class="hotel_list_item_distance" v-if="item.distance!='N'">
						<text style="margin-left: 15rpx;">距离你：{{item.distance}}</text>
						<text v-if="item.distance_unit=='km'">公里</text>
						<text v-if="item.distance_unit=='m'">米</text>
					</view>
					<view class="hotel_list_item_price">
						<!-- <image :src="img_url+'/hotel/rightColor.png'" mode=""></image> -->
						<text>￥{{item.price}}起</text>
					</view>
				</view>
			</view>
		</view>
		<calendar :is-show="timeShow"  :start-date="startDate" :end-date="endDate" mode="2"  @callback="getDate" />
		<com-bottom-popup :show="popupShow" @close="hidePopup">
			<view class="hotel_screening">
				筛选
			</view>
			<view class="hotel_screening_title">
				价格
			</view>
			<view class="hotel_screening_price">
				<input type="number"  placeholder="请输入起始价格" v-model="form.s_price"/>
				<text>————</text>
				<input type="number"  placeholder="请输入截止价格" v-model="form.e_price"/>
			</view>
			<view class="hotel_screening_title">
				星级
			</view>
			<view class="hotel_screening_star">
				<text v-for="(item,index) in star" :key='index' @click="selectStar(index)"
				:class="selectStarindex==index?'StarText':'unStarText'">{{item}}</text>
			</view>
			<view class="sure">
				<view style="color: #000;" @click="clearInfo">重置</view>
				<view style="background: #FF8F0B;color: #fff;" @click="sureRequest">完成</view>
			</view>
		</com-bottom-popup>
	</view>
</template>

<script>
	import {isEmpty} from '../../common/validate.js'
	import Calendar from '@/components/mobile-calendar-simple/Calendar.vue'
	export default {
		components:{
			Calendar,
		},
		data() {
			return {
				img_url: this.$api.img_url,
				selectIndex:1,
				startDate:'',//开始时间
				endDate:'',//结束时间
				betweenStart:'',//区间时间开始
				betweenEnd:'',//取件时间结束
				timeShow:false,
				price_star:'',
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
				popupShow: false,//筛选弹窗
				star:['1星级','2星级','3星级','4星级','5星级','6星级','7星级','8星级','9星级','10星级'],
				selectStarindex:null,//选择星级样式
				screeningSprice:{ //填写的价格区间
					begin:'',
					end:""
				},
				value:[0,0,0],
				multiArray: [], //picker数据
				selectList:[],
				provice: "",
				city: "",
				district: "",
				proviceId: "",
				cityId: "",
				districtId: "",
				text:'',
				recommendedList:[],//推荐酒店
				form:{
					city_id:'',
					type:'in',//酒店类型：in国内、hour钟点房、bb民宿
					start_date:'',
					days:"",
					keyword:'',
					s_price:'',
					e_price:'',
					level:'',
					lng:'',
					lat:'',
				},
				time:'',
				tomoryTime:'',
				recommendedForm:{
					page:1,
					lng:String(uni.getStorageSync('x-longitude-new')),
					lat:String(uni.getStorageSync('x-latitude-new')),
					city_id:'',
				},
				flag:false,
				timeflag:true,
			};
		},
		onLoad() {
			this.getCity();	
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
			
			
			// //#ifdef H5
			//    if(this.$http.getPlatform()=='wechat'){
			// 	   let that=this
			// 	   uni.showLoading({
			// 			title:'正在定位中...'
			// 	   })
			// 	   this.$wechatSdk.location(function(res){
			// 	   		that.recommendedForm.lat=String(res.latitude)
			// 	   		that.recommendedForm.lng=String(res.longitude)
			// 	   		uni.setStorageSync('x-longitude',res.longitude)
			// 	   		uni.setStorageSync('x-latitude',res.latitude)
			// 			that.getrecommended()
			// 			setTimeout(function () {
			// 			    uni.hideLoading();
			// 			}, 4000)
						
			// 	   })
			//    }else{
			// 	    this.getLocationData()
			//    }
			   
			// // #endif
			// // #ifndef H5
			// 		this.getLocationData()	  
			// // #endif
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
									that.recommendedForm.lat =String(uni.getStorageSync('x-latitude-new')) //纬度
									that.recommendedForm.lng =String(uni.getStorageSync('x-longitude-new'))//经度
									that.getrecommended()
								} else if (result.cancel) {
									uni.setStorageSync("flag",false)
									uni.setStorageSync("locationTime",parseInt(new Date().getTime()/1000))
									that.getrecommended()
								}
							}
						})
					} else {
						that.getrecommended()
					}
			
				}
			}, 1000)
			// #endif
			// #ifndef H5
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
									that.recommendedForm.lat =String(uni.getStorageSync('x-latitude-new')) //纬度
									that.recommendedForm.lng =String(uni.getStorageSync('x-longitude-new'))//经度
									that.getrecommended()
								} else if (result.cancel) {
									uni.setStorageSync("flag",false)
									uni.setStorageSync("locationTime",parseInt(new Date().getTime()/1000))
									that.getrecommended()
								}
							}
						})
					} else {
						that.getrecommended()
					}
			
				}
			}, 1000)
			// #endif
			
			
		},
		methods:{
			// getLocationData(){
			// 	var that=this
			// 	uni.getLocation({
			// 		type:'gcj02',
			// 		success(res) {
			// 			that.recommendedForm.lng=String(res.longitude)
			// 			that.recommendedForm.lat=String(res.latitude)
			// 			uni.setStorageSync('x-longitude',res.longitude)
			// 			uni.setStorageSync('x-latitude',res.latitude)
			// 			that.getrecommended()
			// 		}
			// 	})
			// },
			changeTime(d){
			     return d.getFullYear() + '-' +((d.getMonth()+1)<10?'0'+(d.getMonth()+1):(d.getMonth()+1)) + '-' + (d.getDate()<10?'0'+d.getDate():d.getDate());
			},
			select(index){ //点击切换类型
				this.selectIndex=index
				if(index==1){
					this.form.type='in'
				}else if(index==2){
					this.form.type='hour'
				}else if(index==3){
					this.form.type='bb'
				}
			},
			getDate(date){ //获取入住时间
				this.timeStaus=date				
				this.startDate=this.timeStaus.startStr.dateStr
				this.endDate=this.timeStaus.endStr.dateStr
				this.form.start_date=this.timeStaus.startStr.dateStr
				this.form.days=this.timeStaus.dayCount
				this.timeShow=false
			},
			linkTo(){ //跳转商城首页
				uni.navigateTo({
					url:'../index/index'
				})
			},
			hidePopup() { //关闭弹窗
				this.popupShow = false;
			},
			selectStar(index){ //选择星级
				this.selectStarindex=index
				for(let i=0;i<this.star.length;i++){
					if(this.selectStarindex==i){
						this.form.level=i+1
					}
				}
			},
			sureRequest(){
				this.popupShow=false
				this.price_star="￥"+this.form.s_price+'-'+"￥"+this.form.e_price+"/"+this.form.level
			},
			clearInfo(){//重置
				this.screeningSprice={
					begin:'',
					end:""
				}
				this.selectStarindex=null
			},
			chooseAddress() { //选择地址我的定位
				var that = this;
				this.chooseLocation(function(res){
					that.text=res.name
					that.form.lng=res.longitude
					that.form.lat=res.latitude
					that.form.city_id=0
					uni.setStorageSync('x-longitude',res.longitude)
					uni.setStorageSync('x-latitude',res.latitude)
					that.recommendedForm.city_id=""
					that.recommendedForm.lng=String(res.longitude)
					that.recommendedForm.lat=String(res.latitude)
					that.getrecommended()
				});
			},
			getCity() { //请求省市区数据
				this.$http.request({
					url: this.$api.moreShop.getCity,
					method: 'post',
					showLoading:true,
				}).then((res) => {
					// 处理数据
					var provinceArr = [];
					var cityArr = [];
					var districtArr = [];
					for (var key in res.data) { //分为三个数组
						if (res.data[key].level == 'province' || res.data[key].level == 'city') {
							this.$set(res.data[key], 'children', [])
						}
						if (res.data[key].level == 'province') {
							provinceArr.push(res.data[key])
						}
						if (res.data[key].level == 'city') {
							cityArr.push(res.data[key])
						}
						if (res.data[key].level == 'district') {
							districtArr.push(res.data[key])
						}
					}
					this.multiArray = [provinceArr, cityArr];
					cityArr.forEach((item, index) => {
						districtArr.forEach((items, index) => {
							if (item.id == items.parent_id) {
								item.children.push(items);
							}
						})
					})
			
					provinceArr.forEach((item, index) => {
						cityArr.forEach((items, indexs) => {
							if (item.id == items.parent_id) {
								item.children.push(items);
							}
						})
					})
					this.selectList = provinceArr;
					this.multiArray = [
						this.toArr(this.selectList),
						this.toArr(this.selectList[0].children),						
					];	
				})
			},
			toArr(object) {
				let arr = [];
				for (let i in object) {
					arr.push(object[i].name);
				}
				return arr;
			},
			picker(e){
				let value = e.detail.value;
				if (this.selectList.length > 0) {
					this.provice = this.selectList[value[0]].name; //获取省
					// this.district = this.selectList[value[0]].children[value[1]].children[value[2]].name; //获取区
					this.city = this.selectList[value[0]].children[value[1]].name; //获取区
					this.proviceId = this.selectList[value[0]].id; //获取省id
					this.cityId = this.selectList[value[0]].children[value[1]].id; //获取市id
					// this.districtId = this.selectList[value[0]].children[value[1]].children[value[2]].id; //获取区id
					this.text =this.city;
					this.form.city_id=this.cityId
					this.recommendedForm.city_id=this.cityId
					this.getrecommended()
				}
			},
			columnPicker(e){
				//第几列 下标从0开始
				let column = e.detail.column;
				//第几行 下标从0开始
				let value = e.detail.value;
				if (column === 0) {
					this.multiArray = [
						this.multiArray[0],
						this.toArr(this.selectList[value].children),
						
					];
					this.value = [value, 0, 0]
				} else if (column === 1) {
					this.multiArray = [
						this.multiArray[0],
						this.multiArray[1],
						
					];
					this.value = [this.value[0], value, 0]
				}
			},
			checkInto(id){ //进入推荐列表详情
				uni.navigateTo({
					url:'selectRoom/selectRoom?id='+id
				})
			},
			search(){ //搜索
				if(!this.flag)return
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
						if(res.code==0){
							uni.setStorageSync('timeStaus',this.timeStaus)
							if(res.data.history==1){
								uni.navigateTo({
									url:'hotelList/hotelList?search_id='+res.data.search_id
								})
							}else if(res.data.history==0){
								uni.navigateTo({
									url:'hotelList/hotelList?prepare_id='+res.data.prepare_id
								})
							}
						}else{
							this.$http.toast(res.msg);
						}
				});	
			},
			getrecommended(){ //获取酒店推荐
				let that=this
				that.$http
					.request({
						url: that.$api.hotel.getrecommended,
						method: 'POST',
						data:this.recommendedForm,
					})
					.then(res => {
						if(res.code==0){
							that.flag=true
							that.recommendedList=res.data.list
							if(isEmpty(this.form.city_id)){
								that.form.city_id=res.city_data.city_id
								that.text=res.city_data.sel_city
							}
						}else{
							that.$http.toast(res.msg);
						}
				});
			},
		}		
	}
</script>

<style lang="less">
	.hotel_app{width: 100%;overflow: hidden;background: url(https://dev.mingyuanriji.cn/web/static/hotel/hotel_back.png)no-repeat;background-size: 100%;}
	.hotel_search{width:96%;height: 626rpx;margin: 80px auto 0;border-radius: 20rpx;opacity: 1;background: #FFFFFF;
	 box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.1);}
	.hotel_search_header{width: 100%;height: 88rpx;display: flex;justify-content: space-evenly;}
	.hotel_search_header view{line-height: 84rpx;}
	.active{border-bottom: 4rpx solid #ED6D00;color: #0E0E0E;font-size: 30rpx;font-weight: 600;}
	.hotel_search_address{width: 100%;margin-top: 20rpx;height: 80rpx;padding: 0 30rpx;
	box-sizing: border-box;display: flex;justify-content: space-between;position: relative;}
	.hotel_search_address_int{width: 70%;height: 80rpx;line-height: 80rpx;color: #000;}
	.hotel_search_address_right{width: 5%;text-align: center;line-height: 80rpx;}
	.hotel_search_address_right image{width: 16rpx;height: 26rpx;}
	.hotel_search_address_location {width: 25%;height: 80rpx;}
	.hotel_search_address_location image{width: 30rpx;height: 36rpx;display: block;margin: 0 auto;}
	.hotel_search_address_location  text{display: block;font-size: 25rpx;color: #2EAFD0;text-align: center;}
	.hotel_search_time{width: 90%;height: 80rpx;border-bottom: 1rpx dashed ;margin: 0 auto;font-size: 28rpx;line-height: 80rpx;position: relative;}
	.hotel_search_time view{display: flex;justify-content: space-between;} 
	.hotel_search_time image{width: 16rpx;height: 26rpx;display: block;position: absolute;top: 30rpx;right: 0;}
	.hotel_search_time view text{display: block;}
	.hotel_search_time view text:nth-of-type(1){width: 80%;color: #000;}
	.hotel_search_time view text:nth-of-type(2){width: 20%;text-align: right;font-size: 25rpx;}
	.hotel_search_hotel_address{width: 90%;height: 80rpx;border-bottom: 1rpx dashed ;margin: 10rpx auto 0;font-size: 28rpx;line-height: 80rpx;}
	.hotel_search_hotel_address input{width: 80%;height: 80rpx;line-height: 80rpx;font-size: 28rpx;float: left;}
	.hotel_search_hotel_address image{width: 16rpx;height: 26rpx;float: right;margin-top: 30rpx;}
	.hotel_search_screening{width: 90%;height: 80rpx;margin: 10rpx auto 0;font-size: 28rpx;line-height: 80rpx;}
	.hotel_search_screening input{width: 80%;height: 80rpx;line-height: 80rpx;font-size: 28rpx;float: left;}
	.hotel_search_screening image{width: 16rpx;height: 26rpx;float: right;margin-top: 30rpx;}
	.hotel_search_sure{width: 90%;height: 90rpx;margin: 20rpx auto 0;text-align: center;line-height: 90rpx;color: #fff;font-size: 30rpx;
	background: url(https://dev.mingyuanriji.cn/web/static/hotel/searchHotel.png)no-repeat;background-size: 100%;}
	.hotel_search_advertising{width: 252rpx;height: 24rpx;margin: 20rpx auto;}
	.hotel_search_advertising image{width: 252rpx;height: 24rpx;display: block;}
	.hotel_go_shop{width: 696rpx;height: 256rpx;margin: 20rpx auto 0;border-radius: 30rpx;}
	.hotel_go_shop image{width: 100%;height: 100%;display: block;border-radius: 30rpx;}
	.hotel_list{width: 92%;overflow: hidden;margin: 30rpx auto 80rpx;padding-top: 20rpx;background: #FFFFFF;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.15);border-radius: 20rpx;}
	.hotel_list_title{width: 100%;height: 50rpx;margin-bottom: 30rpx;line-height: 50rpx;text-align: left;font-size: 35rpx;color: #000;font-weight: bold;box-sizing: border-box;padding-left: 20rpx;}
	.hotel_list_item{width: 100%;overflow: hidden;margin-bottom: 40rpx;}
	.hotel_logo{width: 208rpx;height: 208rpx;display: block;float: left;margin-left: 15rpx;}
	.hotel_list_item_center{width: 450rpx;float: left;}
	.hotel_list_item_name{width: 100%;overflow: hidden;display: flex;justify-content: space-between;}
	.hotel_list_item_name text:nth-of-type(1){width: 68%;height: 76rpx;font-size: 26rpx;color: #000;margin-left: 15rpx;
	display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;overflow: hidden;}
	.hotel_list_item_name text:nth-of-type(2){width: 30%;height: 76rpx;font-size: 25rpx;color: #FB4512;text-align: right;}
	.hotel_list_item_product{width: 100%;margin-left: 15rpx;font-size: 25rpx;}
	.hotel_list_item_price{width: 40%;font-size: 28rpx;font-weight: bold;float: right;text-align: right;margin: 40rpx 0 0 0;color: #FB4512;}
	.hotel_list_item_price text{display: inline-block;float: right;}
	.hotel_list_item_price image{width: 28rpx;height: 28rpx;display: inline-block;margin:8rpx 10rpx 0 15rpx;float: right;}
	.hotel_screening{width: 100%;height: 80rpx;text-align: center;margin:20rpx 0;color: #000000;font-weight: bold;}    .hotel_screening_title{width:98%;margin: 10rpx auto 40rpx;font-size: 30rpx;color: #000;font-weight: bold;}
	.hotel_screening_price{width: 100%;display: flex;justify-content: space-evenly;margin: 20rpx 0 30rpx 0;}
	.hotel_screening_price input{width: 30%;font-size: 25rpx;height: 60rpx;border: 1rpx solid #80848F;border-radius: 20rpx;text-align: center;}
	.hotel_screening_star{width: 100%;margin: 20rpx 0 30rpx 0;overflow: hidden;}
	.hotel_screening_star text{display: block;float: left;color: #fff;width: 20%;height: 50rpx;
	line-height: 50rpx;text-align: center;margin: 20rpx 18rpx;font-size: 30rpx;border-radius: 20rpx;}
	.sure{width: 100%;height: 80rpx;}
	.sure view{width: 50%;float: left;text-align: center;line-height: 80rpx;}
	.StarText{background: red;}
	.unStarText{background:  rgba(110, 125, 130, 0.7)}
	.index1_content_top_l_name{overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}
	.hotel_list_item_distance{font-size: 25rpx;margin-top: 40rpx;float: left;width: 60%;}
</style>
