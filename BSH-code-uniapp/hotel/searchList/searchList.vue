<template>
	<view class="searchList-app">
		<view class="to-upload" v-if="heightShow">
			<view class="to-upload-show">
				<image :src="img_url+'/hotel/hotel_loading.png'" mode=""></image>
				<text>已为你找到{{num}}家酒店</text>
				<text>请稍后.....</text>
			</view>
		</view>
		<view class="hotel-list-num" v-if="!heightShow">
		<view class="searchList-app-top">
			<view class="searchList-app-header">
				<view class="searchList-app-header-city" @click="setCITY">
					<text style="display: inline-block;width: 78rpx;overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">{{region}}</text>
					<image :src="img_url+'unmy-hotel.png'" mode="" style="width: 20rpx;height: 20rpx;display: block;float: right;margin: 40rpx 0 0 0;"></image>
				</view>
				<view class="searchList-app-header-time"  @click="showHoteltime_1" v-if="checkTimeSHow">
					<view class="searchList-app-header-time-check" >
						<view>
							<text style="margin-right: 5rpx;">住</text>
							<text>{{timeStaus.startStr.dateStr}}</text>
						</view>
						<view>
							<text style="margin-right: 5rpx;">离</text>
							<text>{{timeStaus.endStr.dateStr}}</text>
						</view>
					</view>
					<view class="searchList-app-header-time-count" style="line-height: 80rpx;">
						<text style="color: #FF7104;">{{timeStaus.dayCount}}晚</text>
						<image :src="img_url+'unmy-hotel.png'" mode="" style="width: 25rpx;height: 25rpx;display: block;float: right;margin: 28rpx 0 0 5rpx;"></image>
					</view>
				</view>
				<view class="searchList-app-header-time"  @click="showHoteltime_2" v-if="!checkTimeSHow">
					<view class="searchList-app-header-time-check" >
						<view>
							<text style="margin-right: 5rpx;">住</text>
							<text>{{timeStaus.startStr.dateStr}}</text>
						</view>
						<view>
							<text style="margin-right: 5rpx;">离</text>
							<text>{{timeStaus.endStr.dateStr}}</text>
						</view>
					</view>
					<view class="searchList-app-header-time-count" style="line-height: 80rpx;">
						<text style="color: #FF7104;">{{timeStaus.dayCount}}晚</text>
						<image :src="img_url+'unmy-hotel.png'" mode="" style="width: 25rpx;height: 25rpx;display: block;float: right;margin: 28rpx 0 0 5rpx;"></image>
					</view>
				</view>
				<view class="searchList-app-header-search" @click="search">
					<view style="height: 100%;width: 60rpx;float: left;">
						<image :src="img_url+'search-my-hotel.png'" mode="" style="width: 35rpx;height: 35rpx;display: block;float: left;margin: 35rpx 20rpx 0 10rpx;"></image>
					</view>
					<input type="text" value="" placeholder="酒店名称" style="font-size: 28rpx;" v-model="searchText"/>
				</view>
			</view>
			<view class="searchList-app-sorting">
				<view @click="sorting" style="margin: 0 auto;">
					<text>{{sortTitle}}</text>
					<image :src="img_url+'unmy-hotel.png'" mode=""></image>
				</view>
				<!-- <view @click="typeing">
					<text>{{typeingTitle}}</text>
					<image :src="img_url+'unmy-hotel.png'" mode=""></image>
				</view> -->
			</view>
		</view>
		<view class="hotel-List">
			<view class="hotel-item" v-for="(item,index) in hotelList" :key='index'  @click="hoteldetail(item.id)">
				<view class="hotel-item-left">
					<image :src="item.thumb_url" mode="aspectFill"></image>
				</view>
				<view class="hotel-item-right">
					<view class="hotel-item-right-name">
						{{item.name}}
					</view>
					<view class="hotel-item-right-score">
						<text>{{item.cmt_grade}}超棒</text>
					</view>
					<view class="hotel-item-right-distance" v-if="item.distance!='N'">
						<text>距离你：{{item.distance}}</text>
						<text v-if="item.distance_unit=='km'">公里</text>
						<text v-if="item.distance_unit=='m'">米</text>
					</view>
					<view class="hotel-item-right-notice">
						<text>{{currencyAlias.red_envelope_alias}}当钱花</text>
						<text>消毒保障</text>
						<text>现金消费送{{currencyAlias.silver_beans_alias}}</text>
					</view>
					<view class="hotel-item-right-money">
						<text style="font-size: 26rpx;color:#FF7104;">￥</text>
						<text style="font-weight: bold;color:#FF7104;margin: 0 5rpx;font-size: 34rpx;">{{item.price}}</text>
						<text style="font-size: 24rpx;margin-right: 20rpx;">起</text>
					</view>
				</view>
			</view>
		</view>
		<citySelect @back_city="back_city" v-if="show"></citySelect>
		<calendar :is-show="timeShow"  :start-date="startDate" :end-date="endDate" mode="2"  @callback="getDate" />
		<unipopup ref="popupSort" type="top">
			<view  class="searchList-app-sort">
				<view @click="sortSec(index,item)" :class="sortIndex==index?'sortClass':''" v-for="(item,index) in sortType" :key='index'>{{item}}</view>
			</view>
		</unipopup>
		<unipopup ref="popupType" type="top">
			<view  class="searchList-app-sort">
				<!-- 左边活动选项 -->
				<scroll-view scroll-y scroll-with-animation class="left-box" >
					<view v-for="(item,index) in type" :key="index" class="tab-bar-item" @tap.stop="swichNav(index)">
						<text :class="currentTab==index ? 'active' : ''" >{{item}}</text>
					</view>
				</scroll-view>
				<scroll-view scroll-y scroll-with-animation class="right-box"  v-if="currentTab==0">
					<view v-for="(item,index) in typeLei" :key="index" class="tab-bar-item" @click="locationSec(index,item)" style="border-bottom: 1rpx solid rgb(242,245,249);">
						<text :class="currentTabRight==index ? 'actove' : ''" style="width: 100%;height: 100%;text-align: left;margin-left: 30rpx;">{{item.name}}</text>
					</view>
				</scroll-view>
			<!-- 	<scroll-view scroll-y scroll-with-animation class="right-box"  v-if="currentTab==1">
					<view v-for="(item,index) in typeLei" :key="index" class="tab-bar-item" @click="accommodationCheck(index,item)" style="border-bottom: 1rpx solid rgb(242,245,249);">
						<text :class="accommodationTabRight==index ? 'actove' : ''" style="width: 100%;height: 100%;text-align: left;margin-left: 30rpx;">{{item}}</text>
					</view>
				</scroll-view> -->
				<view class="sure-sort" style="width: 100%;height: 99rpx;background: #fff;margin-top: 600rpx;display: flex;justify-content: space-between;">
					<view style="width: 30%;text-align: center;line-height: 100rpx;" @click="cancle">重置</view>
					<view style="width: 70%;text-align: center;line-height: 100rpx;background: #FF7104;color: #fff;" @click="btnSure">确认</view>
				</view>
			</view>
		</unipopup>
		</view>
		<backTop :src="backTop.src"  :scrollTop="backTop.scrollTop"></backTop>
	</view>
</template>

<script>
	import citySelect from '@/components/linzq-citySelect/linzq-citySelect.vue';
	import Calendar from '@/components/mobile-calendar-simple/Calendar.vue';
	import unipopup from '@/components/uni-popup/uni-popup';
	import backTop from '@/components/back-top/back-top.vue';
	export default {
		components:{
			citySelect,
			Calendar,
			unipopup,
			backTop
		},
		data() {
			return {
				img_url: this.$api.img_url,
				heightShow:false,//控制搜索过渡页显示
				flag:1,
				num:0,//搜索显示数量
				id:'',
				page: 1, //分页用
				page_count:0,//总页数
				hotelList:[],//搜索到的酒店列表
				region:'',//默认广州市
				show:false,//城市选择显示
				timeShow:false,//入住时间选择
				timeStaus:{ //入住时间
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
				searchText:"",//搜索的内容
				popupSort:false,//排序的弹窗显示
				popupType:false,//房间的弹窗显示
				currentTab: 0, //预设左边当前项的值（控制样式）
				sortIndex:0,//排序的样式
				sortTitle:'综合排序',
				typeingTitle:'不限',
				sortType:[ //排序的类容
					"综合排序",
					"价格由低到高",
					"距离由近到远"
				],
				typeLei:[],//房间选择右边区域默认
				type:[ //房间选择左边的类容
					"位置区域",
					// "住宿类型",
				],
				currentTabRight:0, //预设右边位置的值（控制样式）
				accommodationTabRight:0,//预设右边住宿类型的值（控制样式）
				typeLocation:[ ],//房间选择右边区域
				locationSecTitle:'不限',
				accommodationSecTitle:'全日房',
				accommodationType:[ //房间选择右边住宿类型
					"全日房",
					"钟点房",
				],
				form:{ //直接查询需要的参数
					city_id:'',
					type:'in',//酒店类型：in全日房、hour钟点房
					start_date:'',
					days:"",
					lng:'',
					lat:'',
				},
				city_id:'',
				sorttype:'',
				checkTimeSHow:true,
				backTop: {
					src: '../../static/back-top/top.png',
					scrollTop: 0
				},
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
			}
			if(options&&options.searchKey){
				this.searchText=options.searchKey
				// this.form.keyword=this.searchText
			}
			if(options&&options.prepare_id){
				this.heightShow=true
				this.searchList(options.prepare_id)
			}
			if(options&&options.search_id){
				this.heightShow=false
				this.id=options.search_id
				this.getList(options.search_id)
			}
			if(options&&options.region){
				this.region=options.region
			}
			if(options&&options.city_id){
				this.city_id=options.city_id
				this.form.city_id=options.city_id
			}
			if(uni.getStorageSync('timeStaus')){
				this.timeStaus=uni.getStorageSync('timeStaus')
				console.log(this.timeStaus)
				this.form.start_date=this.timeStaus.startStr.dateStr
				this.form.days=this.timeStaus.dayCount
				this.startDate=uni.getStorageSync('timeStaus').startStr.dateStr
				this.endDate=uni.getStorageSync('timeStaus').endStr.dateStr
			}else{
				this.getTime()
			}
			this.getcitymessage()
		},
		methods:{
			showHoteltime_1(){
				this.timeShow=true
				this.checkTimeSHow=false
			},
			showHoteltime_2(){
				this.timeShow=false
				this.checkTimeSHow=true
			},
			getcitymessage(){
				if(uni.getStorageSync('citymessage')){
					let arr=[
						{
							id:3,
							name:"不限",
							parent_id:3
						}
					]
					let citymessage=uni.getStorageSync('citymessage')
					for(let i=0;i<citymessage.length;i++){
						if(arr.indexOf(citymessage[i]==-1)){
							arr.push(citymessage[i])
						}
					}	
					this.typeLei=arr
					this.typeLocation=arr
				}
			},
			searchList(id){
				if(this.flag==1){
					this.$http
						.request({
							url: this.$api.hotel.searchfilter,
							method: 'POST',
							data:{
								prepare_id:id
							},
						})
						.then(res => {
							if(res.code==0){
								if(res.data.finished==0){
									this.num=res.data.founds
									this.searchList(id)
								}else{
									this.id=res.data.search_id
									this.heightShow=false
									this.getList(this.id)
								}
							}else{
								this.$http.toast(res.msg);
							}
					});
				}
			},
			getList(id){
				this.$http
					.request({
						url: this.$api.hotel.getrecommended,
						method: 'POST',
						data:{
							page:this.page,
							search_id:id,
							lng:'',
							lat:'',
							order_by_name:this.sorttype,
							sort_type:'asc'
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							if(res.data.list.length==0)return false
							let list= res.data.list;
							var arr=this.hotelList.concat(list)
							this.hotelList =arr
							this.page_count = res.data.pagination.page_count;
						}else{
							this.$http.toast(res.msg);
						}
				});
			},
			
			
			
			setCITY(){//选择城市
				this.show=true
			},
			back_city(e) {  //获取城市
				if (e !== 'no') { 
					this.region = e.cityName ;
					this.searchText=''
					this.show=false;
					this.getcityinfo('','',this.region)	
				} 
				else { 
					this.show=false;
				 }
			},
			search(){//点击搜索	
			
				uni.navigateTo({
					url:'../hotelSearch/hotelSearch?form='+JSON.stringify(this.form)+"&region="+this.region
				})
			},
			getTime(){ //获取时间
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
				this.form.start_date=this.timeStaus.startStr.dateStr
				this.form.days=this.timeStaus.dayCount
				this.checkHotel()
				this.page=1;//分页用
				this.page_count=0;//总页数
				this.hotelList=[];//搜索到的酒店列表
				uni.setStorageSync('timeStaus',this.timeStaus)
			},
			changeTime(d){ //获取今日时间
			     return d.getFullYear() + '-' +((d.getMonth()+1)<10?'0'+(d.getMonth()+1):(d.getMonth()+1)) + '-' + (d.getDate()<10?'0'+d.getDate():d.getDate());
			},
			sorting(){ //排序弹窗显示
				this.$refs.popupSort.open();
				this.$refs.popupType.close();
			},
			sortSec(index,item){//排序内容选择  order_by_name:'',
				this.sortIndex=index
				this.sortTitle=item
				console.log(item)
				if(item=='综合排序'){
					this.page=1;//分页用
					this.page_count=0;//总页数
					this.hotelList=[];//搜索到的酒店列表
					this.getList(this.id)
				}
				if(item=='价格由低到高'){
					this.page=1;//分页用
					this.page_count=0;//总页数
					this.hotelList=[];//搜索到的酒店列表
					this.sorttype='price'
					this.getList(this.id)
				}
				if(item=='距离由近到远'){
					this.page=1;//分页用
					this.page_count=0;//总页数
					this.hotelList=[];//搜索到的酒店列表
					this.sorttype='distance_mi'
					this.getList(this.id)
				}
				
				
				
				this.$refs.popupSort.close();
				this.$refs.popupType.close();
			},
			typeing(){ //房间类型弹窗显示
				this.$refs.popupSort.close();
				this.$refs.popupType.open();
			},
			swichNav(index) { //房间左边选择样式变化
				this.currentTab = index;
				if(index==0){
					this.typeLei=this.typeLocation
				}
				if(index==1){
					this.typeLei=this.accommodationType
				}
			},
			locationSec(index,item){//右边位置区域选择
				this.currentTabRight=index
				this.locationSecTitle=item.name
			},
			accommodationCheck(index,item){
				this.accommodationTabRight=index
				this.accommodationSec=item
			},
			btnSure(){ //确认
				this.typeingTitle=this.locationSecTitle
				this.$refs.popupSort.close();
				this.$refs.popupType.close();
				this.page=1;//分页用
				this.page_count=0;//总页数
				this.hotelList=[];//搜索到的酒店列表
				this.sorttype=''
				this.getList(this.id)
			},
			cancle(){//重置
				this.currentTabRight=0
				this.accommodationTabRight=0
				this.typeingTitle="不限"
				this.$refs.popupSort.close();
				this.$refs.popupType.close();
			},
			hoteldetail(id){//跳转到酒店详情页面
				uni.navigateTo({
					url:'../detail/detail?id='+id
				})
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
							this.region=res.data.city_name
							this.form.city_id=res.data.city_id
							uni.setStorageSync("citymessage",res.data.district)
							uni.setStorageSync("region",this.region)
							
							this.page=1;//分页用
							this.page_count=0;//总页数
							this.hotelList=[];//搜索到的酒店列表
							this.checkHotel()
						}else{
							this.$http.toast(res.msg);
						}
				});	
			},
			checkHotel(){ //酒店列表搜索
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
								this.heightShow=false
								this.id=res.data.search_id
								this.getList(res.data.search_id)
							}else if(res.data.history==0){
								this.heightShow=true
								this.searchList(res.data.prepare_id)
							}
						}else{
							this.$http.toast(res.msg);
						}
				});	
			}
		},
		onPageScroll(e) {
			this.backTop.scrollTop = e.scrollTop;
		},
		onReachBottom() {
			if(this.page==this.page_count){
				return false;
			} 		
			this.page=this.page+1
			this.getList(this.id);
		},
		onUnload() {
			this.flag=0
		}
	}
</script>

<style lang="less" scoped>
	.searchList-app{width: 100%;overflow: hidden;position: relative;}
	/* #ifdef H5 */
	.searchList-app-top{width: 100%;overflow: hidden;position: fixed;left: 0;top: 78rpx;z-index: 9999;background: #fff;}
	/* #endif */
	/* #ifdef  MP ||APP-PLUS */
		.searchList-app-top{width: 100%;overflow: hidden;position: fixed;left: 0;top: 0rpx;z-index: 9999;background: #fff;}
	/* #endif */
	.searchList-app-header{width: 95%;height: 100rpx;background: rgb(242,245,249);margin: 15rpx auto 0;border-radius: 55rpx;display: flex;justify-content: space-evenly;}
	.searchList-app-header-city{width: 18%;line-height: 100rpx;text-align: left;padding-left: 10rpx;font-size: 26rpx;}
	.searchList-app-header-time{width: 40%;height: 80%;margin-top: 1.5%;display: flex;justify-content: space-between;font-size: 24rpx;
	border-left: 1rpx solid rgb(218,224,230);padding: 0 10rpx;border-right: 1rpx solid rgb(218,224,230);box-sizing: border-box;}
	.searchList-app-header-search{width: 37%;}
	.searchList-app-header-search input{height: 100rpx;line-height: 100rpx;padding-right: 10rpx;}
	.searchList-app-sorting{width: 100%;height: 100rpx;border-bottom: 1rpx solid rgb(242,245,249);display: flex;justify-content: space-between;}
	.searchList-app-sorting view{width: 50%;text-align: center;line-height: 100rpx;font-size: 28rpx;position: relative;}
	.searchList-app-sorting view image{width: 35rpx;height: 35rpx;display: block;position: absolute;top: 35rpx;right: 60rpx;}
	.searchList-app-sort{width: 100%;overflow: hidden;background: #fff;
	margin-top: 205rpx;}
	.searchList-app-sort view{width: 100%;height: 100rpx;text-align: center;font-size: 30rpx;line-height: 100rpx;border-bottom: 1rpx solid rgb(242,245,249);}
	.searchList-app-sort view:nth-of-type(3){border: none!important;}
	.left-box{width: 30%;position: fixed;left: 0;z-index: 10;background: rgb(242,245,249);height: 600rpx;}
	.active {color: #FFFFFF;background: #FF7104;width: 100%;height:100%;line-height: 110rpx;}
	.tab-bar-item {width: 100%;height: 110rpx;box-sizing: border-box;display: flex;align-items: center;justify-content: center;font-size: 9pt;color: #444;font-weight: 400;}
	.right-box {width: 70%;position: fixed;box-sizing: border-box;left: 30%;background: #fff;height: 600rpx;}
	.actove{width: 100%;height: 100%;background: url('https://dev.mingyuanriji.cn/web/static/yellow-right.png')no-repeat;background-size: 5%;
	background-position: 90% 50% ;}
	.sortClass{color: #FF7104;font-weight: bold;background: url('https://dev.mingyuanriji.cn/web/static/yellow-right.png')no-repeat;background-size: 5%;
	background-position: 90% 50% ;}
	.hotel-List{width: 100%;overflow: hidden;margin-top:230rpx ;padding: 0 20rpx;box-sizing: border-box;margin-bottom: 100rpx;}
	.hotel-item{width: 100%;min-height: 360rpx;display: flex;justify-content: space-between;border-bottom: 4rpx solid rgb(242,245,249);padding: 20rpx 0;box-sizing: border-box;}
	.hotel-item-left{width: 30%;}
	.hotel-item-left image{width: 100%;height: 100%;border-radius: 10rpx;}
	.hotel-item-right{width: 70%;padding-left: 20rpx;}
	.hotel-item-right-name{width: 100%;overflow: hidden;font-weight: bold;color: #000;}
	.hotel-item-right-score{width: 100%;overflow: hidden;font-size: 30rpx;color:  #FF7104;margin: 5rpx 0;}
	.hotel-item-right-distance{width: 100%;overflow: hidden;font-size: 26rpx;}
	.hotel-item-right-money{width: 100%;overflow: hidden;text-align: right;margin-top: 10rpx;}
	.hotel-item-right-notice{width: 100%;overflow: hidden;font-size: 24rpx;margin-top: 10rpx;}
	.hotel-item-right-notice text{display:inline-block;min-width: 130rpx;text-align: center;margin-right: 15rpx;margin-top: 10rpx;background: rgb(151,189,227);color: #fff;border-radius: 10rpx;}
	.to-upload{width: 100%;height: 100%;position: fixed;top: 0;left: 0;z-index: 9999;background: rgb(255,255,255);}
	.to-upload-show{width: 300rpx;overflow: hidden;position: absolute;top: 0;left: 0;right: 0;bottom: 0;margin: auto;height: 230rpx;}
	.to-upload-show image{display: block;width: 120rpx;height: 120rpx;margin: 0 auto;}
	.to-upload-show text{display: block;font-size: 26rpx;color:#ED6D00 ;width: 100%;overflow: hidden;text-align: center;}


</style>
