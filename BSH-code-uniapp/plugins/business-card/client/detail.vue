<template>
	<view class="detail-root">
		<view class="set flex flex-y-center" @tap="navTo(`/plugins/business-card/client/edit?id=${detail_data.id}`)">
			<text class="set-icon iconfont icon-shezhi"></text>
			<view>设置</view>
		</view>
		
		<view class="client-label flex flex-y-center flex-x-center" :style="{background: 'url('+img_url+'images/business/client/detailBg.jpg)no-repeat'}">
			<view class="avatar-box">
				<block v-if="detail_data">
					<image class="avatar-img" :src="detail_data.user.avatar_url "></image>
				</block>
				<block v-else>
					<image class="avatar-img" :src="img_url+'images/business/business-default.jpg'"></image>
				</block>
				
				<view class="label-common label1 label-bg1" :class="[label_show?'label-hide':'label-transition']">{{label_list[0]}}</view>
				<view class="label-common label2 label-bg2" :class="[label_show?'label-hide':'label-transition']">{{label_list[1]}}</view>
				<view class="label-common label3 label-bg1" :class="[label_show?'label-hide':'label-transition']">{{label_list[2]}}</view>
				<view class="label-common label4 label-bg3" :class="[label_show?'label-hide':'label-transition']">{{label_list[3]}}</view>
				<view class="label-common label5 label-bg1" :class="[label_show?'label-hide':'label-transition']">{{label_list[4]}}</view>
				<view class="label-common label6 label-bg2" :class="[label_show?'label-hide':'label-transition']">{{label_list[5]}}</view>
				<view class="label-common label7 label-bg3" :class="[label_show?'label-hide':'label-transition']">{{label_list[6]}}</view>
				<view class="label-common label8 label-bg2" :class="[label_show?'label-hide':'label-transition']">{{label_list[7]}}</view>
			</view>
		</view>
		
		<view class="userinfo-box flex">
			<block v-if="detail_data">
				<image class="avatar" :src="detail_data.user.avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
			
				<view class="right">
					<view class="shop-name flex flex-x-between flex-y-center">
						<text class="over1">{{detail_data.user.nickname}}</text>
						<view class="btn flex">
							<view class="btn-item" @tap="goChat(detail_data.user,detail_data.user_id)" style="margin-right: 10rpx;" :style="{background:textColor}">私聊</view>
							<view class="btn-item" v-if="detail_data.basic_info" @tap="call(detail_data.basic_info.mobile)">电话</view>
						</view>
					</view>
					<view class="common-style">姓名:{{detail_data.basic_info ? detail_data.basic_info.full_name : '暂无'}}</view>
					<view class="common-style">电话:{{detail_data.basic_info ? detail_data.basic_info.mobile : '暂无'}}</view>
					<view class="label-list flex">
						<view class="label-item" v-for="(item,index) in detail_data.diy_tag" :key='index'>{{item.name}}</view>
					</view>
				</view>
			</block>
		</view>
		
		<view class="analysis">
			<scroll-view scroll-x="true" >
				<view class="tabs-box" :class="{bor:tabs_index == 0}">
					<view class="tabs-item" v-for="(item,index) in tabs_list" @tap="tabSwitch(index)" :key='index'>
						<text class="tabs-active" :style="{color:tabs_index == index?textColor:'','border-bottom-color':tabs_index == index?textColor:''}">{{item}}</text>
					</view>
				</view>
			</scroll-view>
			
			<!-- AI分析 -->
			<view v-show="tabs_index == 0">
				<view class="day-btn flex flex-x-center flex-y-center" @tap="showDayPop">
					{{day_list[day_index]}}
					<view class="triangle" :style="{'border-top-color':textColor}"></view>
				</view>
				
				<view class="doughnut-box">
					<view class="header flex flex-x-between flex-y-center">
						<view class="left flex flex-y-center">
							<view class="line" :style="{background:textColor}"></view>
							<view>客户兴趣</view>
						</view>
					</view>
					<view class="flex ring">
						<canvas canvas-id="canvasRing" id="canvasRing" class="charts" @touchstart="touchRing"></canvas>
					</view>
				</view>
				
				<view class="goods">
					<view class="goods-header flex flex-x-between" :style="{color:textColor}">
						<view>关注商品</view>
					</view>
					
					<view class="goods-list-box">
						<view class="goods-item flex flex-y-center" v-for="(item,index) in ai_data.hot_list" :key='index'>
							<view>{{index+1}}</view>
							<image class="goods-img" :src="item.image" mode=""></image>
							<view class="goods-info flex flex-col flex-x-between">
								<view>
									<view class="goods-name over2">{{item.name}}</view>
									<!-- <view class="spec">色号：白色</view> -->
								</view>
								<view class="flex flex-x-between">
									<view>浏览量{{item.total}}</view>
									<view>销量{{item.sales}}</view>
								</view>
							</view>
						</view>
					</view>
				</view>
				
				<view class="access-box goods">
					<view class="goods-header flex flex-x-between" style="border-bottom: 1px solid #F2F2F2;margin-bottom: 50rpx;" :style="{color:textColor}">
						<view>访问深度</view>
					</view>
					<view class="curve">
						<canvas canvas-id="canvasLineA" id="canvasLineA" class="chartsL" @touchstart="touchLineA"></canvas>
					</view>
				</view>
				
				<view class="source goods">
					<view class="goods-header flex flex-x-between" style="margin-bottom: 50rpx;" :style="{color:textColor}">
						<view>用户来源</view>
					</view>
					
					<view class="radar">
						<canvas canvas-id="canvasRadar" id="canvasRadar" class="charts"></canvas>
					</view>
				</view>
			</view>
			
			<!-- 浏览记录 -->
			<view v-if="tabs_index == 1">
				<scroll-view scroll-y="true" class="follow_scroll" @scrolltolower="loadMore(1)">
					<block v-if="browse_list.length != 0">
						<block v-for="(item,index) in browse_list" :key='index'>
							<view class="history-title flex flex-x-between">
								<view class="time">{{item.date}}</view>
								<picker mode="date" :value="date" :start="startDate" :end="endDate" @change="bindDateChange">
									<view class="btn" v-if="index == 0" :style="{background:textColor}">查看时间</view>
								</picker>
							</view>
							
							<view class="history-list">
								<view class="history-item flex flex-x-between flex-y-center" v-for="(itemL,indexL) in item.list" :key='index'>
									<image class="avatar" :src="itemL.user_data.avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
									<view class="text">TA <text class="col" :style="{color:textColor}">{{itemL.text}}</text>查看你的<text class="col" :style="{color:textColor}">{{itemL.type_name}}</text>，高意向 客户立刻主动沟通</view>
									<view class="time flex flex-y-center">
										<text class="time-icon iconfont icon-shouji1"></text>
										<view>{{itemL.time}}</view>
									</view>
								</view>
							</view>
						</block>
					</block>
					
					<view v-else style="text-align: center;margin-top: 40rpx;">暂时没有更多记录</view>
				</scroll-view>
			</view>
			
			<!-- 跟进记录 -->
			<view v-if="tabs_index == 2">
				<scroll-view scroll-y="true" class="follow_scroll" @scrolltolower='loadMore(2)'>
					<view v-for="(item,index) in showData" :key='index'>
						<view class="history-title flex flex-x-between">
							<view class="time">{{item.date}}</view>
						</view>
						
						<view class="history-list">
							<view class="history-item flex flex-x-between" style="align-items: center;" v-for="(itemL,indexL) in item.list" :key='indexL'>
								<image class="avatar":src="itemL.user_data.avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
								<view class="text">{{itemL.text}}</view>
								<view class="time flex flex-y-center">
									<text class="time-icon iconfont icon-shouji1"></text>
									<view>{{itemL.time}}</view>
								</view>
							</view>
						</view>
					</view>
				</scroll-view>
			</view>
			
			<!-- 团队列表 -->
			<view v-if="tabs_index == 3">
				<scroll-view scroll-x="true" class="type-box">
					<view style="white-space: nowrap;">
						<view class="type-item" :style="{color:index == type_index?textColor:''}" v-for="(item,index) in type_list" :key='index' @tap="type(index)">{{item}}</view>
					</view>
				</scroll-view>
				
				<view class="type2-box flex" v-if="type_index == 0">
					<view class="type2-item"  @tap="switchType2(index)" v-for="(item,index) in type2_list" :key='item'>
						<text class="type2-text bor" :style="{'border-bottom-color':index == type2_index?textColor:''}">
							{{item}}
							<block v-if="team_detail">
								<text v-if="index == 0">({{team_detail.stat.direct_push_total}}人)</text>
								<text v-else>({{team_detail.stat.space_push_total}}人)</text>
							</block>
						</text>
					</view>
				</view>
				
				<view class="goods-list-box">
					<block v-if="team_list.length != 0">
						<scroll-view scroll-y="true" class="follow_scroll" @scrolltolower="loadMore(3)">
							<view class="goods-item flex flex-x-between flex-y-center" v-for="(item,index) in team_list" :key='index'>
								<view class="left flex flex-y-center" @tap="navTo(`/plugins/business-card/client/detail?user_id=${item.user_id}`)">
									<image class="goods-img2" :src="item.avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
									<view class="detail">
										<view class="shop-name over1">{{item.nickname}}</view>
										<!-- <view class="text">15次互动来自名片</view> -->
									</view>
								</view>
								<view class="right" v-if="type2_index == 0">
									<view class="btn" @tap="showBusiness" :style="{background:textColor}">商机</view>
									<view>{{item.status_name}}</view>
								</view>
								<!-- 添加商机弹窗 -->
								<com-modal :show="is_modal_bus" padding='30rpx 30rpx' width="80%" :custom='true' @cancel='hidePop'>
									<view class="business-box">
										<view class="title">添加为</view>
										
										<view class="list-box">
											<view :class="{col:indexB == select_index}" 
											:style="{color:indexB == select_index?'':textColor,background:indexB == select_index?textColor:'','border-color':textColor}" 
											class="list-item" v-for="(itemB,indexB) in business_list" @tap="select(indexB)" :key='itemB'>{{itemB}}</view>
										</view>
										
										<view class="confim-btn" @tap="hideBusiness(item.user_id)" :style="{background:textColor}">确定添加</view>
									</view>
								</com-modal>
							</view>
						</scroll-view>
					</block>
					<view v-else style="text-align: center;margin-top: 30rpx;">暂时没有更多客户</view>
				</view>
			</view>
		</view>
		
		<view class="footer flex flex-x-between">
			<view class="left flex" @tap="showPop">
				<text class="left-icon iconfont icon-pinglun"></text>
				<view>跟进记录</view>
			</view>
			<view class="right flex">
				<!-- <view class="right-common right-item1">提醒跟进</view> -->
				<view class="right-common right-item2" @tap="goChat(detail_data.user,detail_data.user_id)">私信客户</view>
			</view>
		</view>
		
		<!-- 添加跟进记录弹窗 -->
		<com-modal :show="is_modal" padding='40rpx 40rpx' width="80%" :custom='true' @cancel='hidePop'>
			<view class="secretOrder-box">
				<view class="title">添加跟进记录</view>
				<view class="inp-box">
					<input class="secretOrder-inp" v-model="remark" placeholder="请输入跟进记录" type="text" />
				</view>
				<view class="btn-list flex">
					<view style="margin-right: 40rpx;" @tap="hidePop" :style="{color:textColor}">取消</view>
					<view @tap="sure" :style="{color:textColor}">确定</view>
				</view>
			</view>
		</com-modal>
		<!-- 天数选择弹窗 -->
		<!-- #ifdef MP-WEIXIN || H5  -->
		<cover-view class="popUp_cover flex flex-x-center flex-y-center" v-if="is_modal_day" @click='hidePop'>
			<cover-view class="department">
				<cover-view class="title">天数选择</cover-view>
				<scroll-view scroll-y="true" style="max-height: 600rpx;">
					<cover-view class="dep-item" v-for="(item,index) in day_list" :key='item' @click="daySelect(index)">
						{{item}}
					</cover-view>
				</scroll-view>
			</cover-view>
		</cover-view>
		<!-- #endif -->
		
		<!-- #ifdef APP-PLUS  -->
		<view class="popUp_cover flex flex-x-center flex-y-center" v-if="is_modal_day" @click='hidePop'><!-- 因为APP的cove-view 不支持嵌套cove-view -->
			<view class="department">
				<view class="title">天数选择</view>
				<scroll-view scroll-y="true" style="max-height: 600rpx;">
					<view class="dep-item" v-for="(item,index) in day_list" :key='item' @click="daySelect(index)">
						{{item}}
					</view>
				</scroll-view>
			</view>
		</view>
		<!-- #endif -->
	
		
		<!-- <com-modal :show="is_modal_day" padding='0rpx 0rpx' width="70%" :custom='true' @cancel='hidePop'>
			<view class="department">
				<view class="title">天数选择</view>
				<scroll-view scroll-y="true">
					<view class="dep-item" v-for="(item,index) in day_list" :key='item' @tap="daySelect(index)">
						{{item}}
					</view>
				</scroll-view>
			</view>
		</com-modal> -->
	</view>
</template>

<script>
	// import uCharts from '@/components/u-charts/u-charts.js';
	import uCharts from '@/plugins/u-charts/u-charts.js';
	var _self;
	var canvaRing=null,
		canvaLineA=null,
		canvaRadar=null,
		timeArr = [],
		timeArr2 = [];
	
	export default {
		data() {
			const currentDate = this.getDate({
				format: true
			})
			return {
				// 图片url
				img_url: this.$api.img_url,
				// 时间弹窗数据
				date: currentDate,
				// 标签动画显示
				label_show:true,
				
				detail_data:'',
				is_request:false,
				
				// 弹窗数据
				is_modal:false,
				remark:'',
				
				label_list:[],
				tabs_list:['AI分析','浏览记录','跟进记录','团队列表'],
				tabs_index:0,
				// 圆环图
				cWidth:'',
				cHeight:'',
				lWidth:'',
				lHeight:'',
				pixelRatio:1,
				type_list:['新客户','意向客户','比较客户','待成交','已成交'],
				type_index:0,
				type2_list:['直推客户','间推客户'],
				type2_index:0,
				team_list:[],
				team_detail:'',
				browse_list:[],
				browse_data:'',
				ai_data:'',
				
				page:1,
				is_no_more:false,
				
				showData:[],
				
				// 天数弹窗
				is_modal_day:false,
				day_index:0,
				day_list:['近7天','近15天','近30天'],
				day_text:'',
				
				// 商机弹窗
				is_modal_bus:false,
				select_index:0,
				business_list:['意向客户','比较客户','待成交客户','已成交客户'],
				is_request_bus:false,
				
				id:0,
			};
		},
		computed: {
			startDate() {
				return this.getDate('start');
			},
			endDate() {
				return this.getDate('end');
			}
		},
		onLoad(options) {
			this.id = options.user_id;
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
			
			_self = this;
			this.getdetail();
			this.cWidth=uni.upx2px(660);
			this.cHeight=uni.upx2px(500);
			this.lWidth=uni.upx2px(690);
			this.lHeight=uni.upx2px(450);
			this.getAIData();
		},
		methods:{
			call(mobile){
				uni.makePhoneCall({
				    phoneNumber: mobile,  //需要拨打的电话
					success: (res)=>{
						console.log('成功');
					}
				});
			},
			goChat(obj,id){
				this.$http.request({
					url:this.$api.plugin.business.follow,
					method:'post',
					data:{
						log_type:4,
						remark:'',
						user_id:id,
					}
				}).then(res=>{
					console.log(res,'resssssssss');
				})
				
				obj.id = 'user_'+id;
				uni.setStorageSync('other_info', JSON.stringify(obj));
				uni.navigateTo({
					url:'/plugins/business-card/message/detail',
				})
			},
			getdetail(){ //获取客户详情数据
				this.$http.request({
					url:this.$api.plugin.business.detail,
					method:'post',
					showLoading:true,
					data:{
						user_id:this.id
					}
				}).then(res => {
					if(res.code == 0){
						this.label_list = res.data.auto_tag;
						this.detail_data = res.data;
						this.label_show = false;
						console.log(res.data.basic_info,'res.data.basic_info');
						uni.setStorageSync('basic_info',JSON.stringify(res.data.basic_info));
					}
				})
				
			},
			loadMore(num){ //加载更多
				switch(num){
					case 1:
						this.getBrowseList();
						break;
					case 2:
						this.getFollowList();
						break;
					case 3 :
						this.getTeamList();
						break;
				}
			},
			sure(){ //跳转去创建名片
				if(this.is_request) return;
				
				this.is_request = true;
				this.$http.request({
					url:this.$api.plugin.business.follow,
					method:'post',
					data:{
						user_id:this.detail_data.user_id,
						remark:this.remark
					}
				}).then(res => {
					this.is_modal = false;
					this.is_request = false;
					if(res.code == 0){
						this.$http.toast('添加成功');
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			showPop(){ //显示弹窗
				this.is_modal = true;
			},
			hidePop(){ //关闭弹窗
				this.is_modal = false;
				this.is_modal_bus = false;
				this.is_modal_day = false;
			},
			navTo(url){
				console.log(url,'urlllll');
				uni.navigateTo({
					url
				})
			},
			tabSwitch(index){
				this.tabs_index = index;
				this.page = 1;
				this.is_no_more = false;
				switch (index){
					case 0:
						this.getAIData();
						break;
					case 1:
						this.browse_list = [];
						timeArr2 = [];
						console.log(timeArr2,'timeArr2');
						this.getBrowseList();
						break;
					case 2:
						this.showData = [];
						this.getFollowList();
						break;
					case 3:
						this.team_list = [];
						this.getTeamList();
						break;
				}
			},
			select(index){
				this.select_index = index;
			},
			switchType2(index){
				this.type2_index = index;
				this.page = 1;
				this.is_no_more = false;
				this.team_list = [];
				this.getTeamList();
			},
			showBusiness(){
				this.is_modal_bus = true;
			},
			hideBusiness(user_id){ //商机弹窗点击确定
				if(this.is_request) return;
				
				this.is_modal_bus = false;
				this.is_request = true;
				this.$http.request({
					url:this.$api.plugin.business.business,
					method:'post',
					showLoading:true,
					data:{
						user_type: this.select_index + 1,
						user_id
					}
				}).then(res => {
					this.is_modal = false;
					this.is_request = false;
					if(res.code == 0){
						this.$http.toast('添加成功');
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			type(index){
				this.type_index = index;
				this.page = 1;
				this.team_list = [];
				this.is_no_more = false;
				this.getTeamList();
			},
			getAIData(){ //获取AI分析数据
				this.$http.request({
					url:this.$api.plugin.business.c_ai_analysis,
					method:'post',
					showLoading:true,
					data:{
						time_type:this.day_index + 1,
						user_id:6
					}
				}).then(res => {
					if(res.code == 0){
						this.ai_data = res.data;
						
						this.getServerData();
					}
					console.log(res,'resssss');
				})
			},
			getTeamList(){ //获取团队记录列表
				if(this.is_no_more){
					uni.showToast({
						title:'暂无更多数据'
					})
					return;
				}
				
				this.$http.request({
					url:this.$api.plugin.business.team_list,
					method:'post',
					showLoading:true,
					data:{
						user_id:this.id,
						flag:this.type2_index+1,
						user_type:this.type_index,
						limit:7,
						page:this.page,
					}
				}).then(res => {
					if(res.code == 0){
						this.team_list.push(...res.data.list);
						this.team_detail = res.data;
						
						res.data.pagination.page_count>this.page?this.page++ :this.is_no_more=true;
					}
				})
			},
			getBrowseList(date){ //获取浏览记录列表
				if(this.is_no_more){
					uni.showToast({
						title:'暂无更多数据'
					})
					return;
				}
				
				this.$http.request({
					url:this.$api.plugin.business.browsing_history,
					method:'post',
					showLoading:true,
					data:{
						user_id:this.detail_data.user_id,
						date:date,
						page:this.page,
						limit:7
					}
				}).then(res => {
					if(res.code == 0){
						this.changeData(res.data.list,timeArr2,this.browse_list);
						res.data.pagination.page_count>this.page ? this.page++ : this.is_no_more=true;
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			getFollowList(){ //获取跟进记录列表
				if(this.is_no_more){
					uni.showToast({
						title:'暂无更多数据'
					})
					return;
				}
				this.$http.request({
					url:this.$api.plugin.business.followList,
					method:'post',
					showLoading:true,
					data:{
						page: this.page,
						limit:7
					}
				}).then(res => {
					if(res.code == 0){
						this.changeData(res.data.list,timeArr,this.showData);
						res.data.pagination.page_count>this.page ? this.page++ : this.is_no_more=true;
					}
				})
			},
			changeData(res,arr,showData){ //整合数据
				res.forEach(item => {
					arr.push(item.date);
				})
				arr = Array.from(new Set(arr));
				
				if(showData.length == 0){
					arr.forEach((item,index) => {
						let obj = {
							date:item,
							list:[]
						}
						showData.push(obj);
					})
				}else{
					arr.forEach((item,index) => {
						if(showData.map(o=>o.date).indexOf(item) == '-1'){
							let obj = {
								date:item,
								list:[]
							}
							showData.push(obj);
						}
					})
				}
				
				showData.forEach((item,index) => {
					res.forEach((ritem,rindex) => {
						if(ritem.date == item.date){
							item.list.push(ritem);
						}
					})
				})
			},
			// 时间弹窗方法
			bindDateChange(e) {
				this.date = e.target.value;
				this.page = 1;
				this.is_no_more = false;
				this.browse_list = [];
				timeArr2 = [];
				this.getBrowseList(this.date);
			},
			getDate(type) {
				const date = new Date();
				let year = date.getFullYear();
				let month = date.getMonth() + 1;
				let day = date.getDate();
		
				if (type === 'start') {
					year = year - 60;
				} else if (type === 'end') {
					year = year + 5;
				}
				month = month > 9 ? month : '0' + month;;
				day = day > 9 ? day : '0' + day;
				return `${year}-${month}-${day}`;
			},
			
			// 天数弹窗
			showDayPop(){
				this.is_modal_day = true;
			},
			daySelect(index){
				this.day_text = this.day_list[index];
				this.is_modal_day = false;
				this.day_index = index;
				
				this.getAIData();
			},
			// 图表方法
			getServerData(){
				// 圆环图
				let Ring={series:[]};
				Ring.series= this.ai_data.interest_list;
				_self.showRing("canvasRing",Ring);
				
				// 曲线图
				let LineA={categories:[],series:[]};
				//这里我后台返回的是数组，所以用等于，如果您后台返回的是单条数据，需要push进去
				LineA.categories=this.ai_data.access_list.categories;
				LineA.series=this.ai_data.access_list.series;
				_self.showLineA("canvasLineA",LineA);
				
				// 雷达图
				let Radar={categories:[],series:[]};
				Radar.categories=this.ai_data.user_source.categories;
				Radar.series=this.ai_data.user_source.series;
				_self.showRadar("canvasRadar",Radar);
			},
			showRing(canvasId,chartData){
				canvaRing=new uCharts({
					$this:_self,
					canvasId: canvasId,
					type: 'ring',
					fontSize:11,
					padding:[0,0,0,0],
					legend:{ //说明
						show:true,
						position:'right',
						float:'center',
						itemGap:10,
						padding:5,
						lineHeight:26,
						margin:5,
						//backgroundColor:'rgba(41,198,90,0.2)',
						//borderColor :'rgba(41,198,90,0.5)',
						borderWidth :1
					},
					background:'#FFFFFF', //圆环中间背景色
					pixelRatio:_self.pixelRatio,
					series: chartData.series,
					animation: true,
					width: _self.cWidth*_self.pixelRatio,
					height: _self.cHeight*_self.pixelRatio,
					disablePieStroke: true,
					dataLabel: true,
					extra: {
						pie: {
						  offsetAngle: 0,
						  ringWidth: 25*_self.pixelRatio, //条抓圆环的宽度
						  labelWidth:12 //调整触须的长度
						}
					},
				});
			},
			touchRing(e){
				canvaRing.touchLegend(e, {
					animation : false
				});
				canvaRing.showToolTip(e, {
					format: function (item) {
						return item.name
					}
				});
			},
			showLineA(canvasId,chartData){
				canvaLineA=new uCharts({
					$this:_self,
					canvasId: canvasId,
					enableScroll:false,
					type: 'line',
					fontSize:10,
					padding:[15,20,0,15],
					legend:{
						show:false,
					},
					dataLabel:true,
					dataPointShape:true,
					background:'#FFFFFF',
					pixelRatio:_self.pixelRatio,
					categories: chartData.categories,
					series: chartData.series,
					animation: true,
					xAxis: {
						type:'grid',
						gridColor:'#ffffff',
						gridType:'dash',
						dashLength:8,
						labelCount:10,
						boundaryGap:'justify',
						rotateLabel:true,
					},
					yAxis: {
						gridType:'grid',
						gridColor:'#e5e4e6',
						dashLength:8,
						splitNumber:5, //控制Y轴显示多少个个数
						format:(val)=>{return val.toFixed(0)}
					},
					width: _self.lWidth*_self.pixelRatio,
					height: _self.lHeight*_self.pixelRatio,
					extra: {
						line:{
							type: 'curve'
						}
					}
				});
			},
			touchLineA(e) {
				canvaLineA.touchLegend(e);
				canvaLineA.showToolTip(e, {
					format: function (item, category) {
						return category + ' ' + item.name + ':' + item.data 
					}
				});
			},
			showRadar(canvasId,chartData){
				canvaRadar=new uCharts({
					$this:_self,
					canvasId: canvasId,
					type: 'radar',
					fontSize:10,
					padding:[15,15,0,15],
					legend:{
						show:false,
					},
					background:'#FFFFFF',
					pixelRatio:_self.pixelRatio,
					animation: true,
					dataLabel: false,
					categories: chartData.categories,
					series: chartData.series,
					width: _self.cWidth*_self.pixelRatio,
					height: _self.cHeight*_self.pixelRatio,
					dataPointShape:false,
					extra: {
						radar: {
							max: 200,//雷达数值的最大值
							gridCount:4,
							opacity:1
						},
					}
				});
			},
		}
	}
</script>

<style lang="scss" scoped>
	page{
		background: #0D2061;
	}
	
	.set{
		color: #FFFFFF;
		justify-content: flex-end;
		padding-right: 30rpx;
		font-size: 24rpx;
		background: rgb(13, 32, 97);
		
		.set-icon{
			margin-right: 10rpx;
			position: relative;
			top: 1px;
		}
	}
	
	.client-label{
		width: 100%;
		height: 600rpx;
		background-size: cover !important;
		background-position: center !important;
		
		.avatar-box{
			position: relative;
			
			.avatar-img{
				width: 134rpx;
				height: 134rpx;
				border-radius: 50%;
				display: block;
			}
		}
		// 标签围绕
		.label-common{
			border-radius: 32rpx 0 32rpx 0;
			color: #FFFFFF;
			text-align: center;
			padding: 14rpx 26rpx;
			font-size: 26rpx;
			position: absolute;
			letter-spacing: 1px;
			white-space: nowrap;
			opacity: 1;
		}
		.label-transition{
			transition: all 0.7s;
		}
		
		.label-bg1{
			background:linear-gradient(90deg,rgba(35,179,203,1),rgba(65,225,203,1));
		}
		.label-bg2{
			background:linear-gradient(90deg,rgba(134,109,255,1),rgba(191,139,244,1));
		}
		.label-bg3{
			background:linear-gradient(90deg,rgba(38,112,242,1),rgba(82,177,252,1));
		}
		
		.label1{
			top: -180rpx;
			left: -20rpx;
		}
		.label2{
			right: -220rpx;
			top: -156rpx;
		}
		.label3{
			right: -260rpx;
			top: -10rpx;
		}
		.label4{
			right: -260rpx;
			top: 120rpx;
		}
		.label5{
			right: -60rpx;
			top: 230rpx;
		}
		.label6{
			left: -180rpx;
			top: 200rpx;
		}
		.label7{
			left: -260rpx;
			top: 70rpx;
		}
		.label8{
			left: -230rpx;
			top: -70rpx;
		}
		
		.label-hide{
			top: 0;
			left: 0;
			right: 0;
			opacity: 0;
		}
	}
	
	.userinfo-box{
		background: #FFFFFF;
		border-radius: 10rpx;
		padding: 50rpx 10rpx 40rpx 20rpx;
		margin: 0 30rpx 40rpx;
		
		.avatar{
			width: 120rpx;
			height: 120rpx;
			border-radius: 50%;
			margin-right: 20rpx;
		}
		
		.right{
			flex: 1;
			
			.shop-name{
				font-size: 30rpx;
				font-weight: 700;
				color: #000;
			}
			.btn-item{
				width: 110rpx;
				height: 40rpx;
				border-radius: 20rpx;
				color: #FFFFFF;
				background: #BC0100;
				text-align: center;
				line-height: 40rpx;
				font-size: 24rpx;
				font-weight: 500;
			}
			.common-style{
				font-size: 24rpx;
				color: #000;
				margin: 4rpx 0;
			}
			
			.label-list{
				flex-wrap: wrap;
				
				.label-item{
					width: 128rpx;
					height: 40rpx;
					background: #DD807F;
					border-radius: 6rpx;
					font-size: 24rpx;
					text-align: center;
					line-height: 40rpx;
					color: #FFFFFF;
					margin-bottom: 10rpx;
					margin-right: 18rpx;
				}
			}
		}
	}
	
	.analysis{
		background: #FFFFFF;
		margin: 0 30rpx;
		border-radius: 10rpx;
		padding-bottom: 40rpx;
		
		.tabs-box{
			height: 150rpx;
			line-height: 150rpx;
			white-space: nowrap;
			
			.tabs-item{
				width: 30%;
				height: 148rpx;
				text-align: center;
				color: #666666;
				font-size: 30rpx;
				letter-spacing: 1px;
				display: inline-block;
				border-bottom: 1px solid #F2F2F2;
			}
			.tabs-active{
				padding-bottom: 20rpx;
				border-bottom: 2px solid transparent;
			}
		}
		
		// 分析的环形图
		.doughnut-box{
			
			.header{
				padding: 40rpx 30rpx;
			}
			.left{
				font-size: 24rpx;
				color: #000000;
				font-weight: 700;
				
				.line{
					width: 3px;
					height: 28rpx;
					background: #BC0100;
					margin-right: 10rpx;
				}
			}
			.right{
				font-weight: 700;
				font-size: 24rpx;
				color: #000000;
				letter-spacing: 2px;
			}
			
			.ring{
				padding: 0 30rpx;
				
				.frequency{
					font-size: 24rpx;
					padding: 70rpx 0;
				}
			}
		}
		
		// 关注商品列表
		.goods-header{
			padding: 0 30rpx;
			color: #BC0100;
			font-size: 28rpx;
			padding-bottom: 24rpx;
		}
		.day-btn{
			color: #000000;
			font-size: 30rpx;
			font-weight: 700;
			align-items: flex-start;
			letter-spacing: 2px;
			margin-top: 30rpx;
			
			.triangle{
				width: 0;
				height: 0;
				border-width: 24rpx 14rpx 0rpx 14rpx;
				border-color: #BC0100 transparent transparent transparent;
				border-style: solid;
				position: relative;
				top: 4px;
				margin-left: 10rpx;
				margin-top: 4rpx;
			}
		}
		
		.goods-list-box{
			background: #FFFFFF;
			
			.goods-item{
				padding: 50rpx 24rpx;
				border-top: 1px solid #F2F2F2;
				
				.goods-img{
					width: 154rpx;
					height: 154rpx;
					border-radius: 10rpx;
					margin: 0 20rpx;
				}
				
				.goods-info{
					color: #999999;
					font-size: 24rpx;
					flex: 1;
					height: 154rpx;
				}
				.goods-name{
					color: #000;
					font-size: 30rpx;
					margin-bottom: 6rpx;
				}
			}
		}
		
		.history-title{
			font-size: 30rpx;
			color: #000000;
			padding: 40rpx 20rpx;
			border-bottom: 1px solid #F2F2F2;
			
			.btn{
				width: 138rpx;
				height: 40rpx;
				background: #BC0100;
				border-radius: 20rpx;
				font-size: 24rpx;
				color: #FFFFFF;
				text-align: center;
				line-height: 40rpx;
			}
		}
		.history-list{
			
			.history-item{
				padding: 40rpx 30rpx 20rpx;
				border-bottom: 1px solid #F2F2F2;
				font-size: 28rpx;
				align-items: flex-start;
				
				.avatar{
					width: 80rpx;
					height: 80rpx;
					border-radius: 50%;
				}
				.text{
					width: 380rpx;
				}
				.col{
					color: #BC0100;
				}
				.time{
					font-size: 24rpx;
					color: #8B8B8B;
					
					.time-icon{
						position: relative;
						top: 1px;
					}
				}
			}
		}
		
		.type-box{
			height: 104rpx;
			
			.type-item{
				width: 25%;
				border: 1px solid #F2F2F2;
				text-align: center;
				line-height: 100rpx;
				display: inline-block;
			}
			.col{
				color: #BC0100;
			}
		}
		
		.type2-box{
			
			.type2-item{
				width: 50%;
				height: 120rpx;
				text-align: center;
				line-height: 120rpx;
				font-size: 28rpx;
				color: #000000;
				
				.type2-text{
					padding: 0 10rpx 8rpx;
				}
				.bor{
					border-bottom: 2px solid transparent;
				}
			}
		}
		
		.goods-list-box{
			
			.goods-item{
				padding: 30rpx;
				border-top: 1px solid #F3F3F3;
			}
			
			.left{
				
				.goods-img2{
					width: 120rpx;
					height: 120rpx;
					border-radius: 50%;
					margin-right: 20rpx;
				}
				
				.detail{
					color: #BC0100;
					font-size: 24rpx;
					flex: 1;
					margin-right: 20rpx;
					
					.shop-name{
						font-size: 30rpx;
						color: #000000;
						font-weight: 600;
						letter-spacing: 1px;
						width: 360rpx;
					}
				}
			}
			
			.right{
				color: #C2C2C2;
				font-size: 24rpx;
				text-align: center;
				
				.btn{
					background: #BC0100;
					color: #FFFFFF;
					width: 110rpx;
					height: 40rpx;
					border-radius: 40rpx;
					text-align: center;
					line-height: 40rpx;
					font-size: 24rpx;
					margin-bottom: 4rpx;
				}
			}
		}
	}
	// 圆环图
	/*样式的width和height一定要与定义的cWidth和cHeight相对应*/
	.qiun-charts {
		width: 660rpx;
		height: 600rpx;
		background-color: #FFFFFF;
	}
	
	.charts {
		width: 660rpx;
		height: 500rpx;
		background-color: #FFFFFF;
	}
	.chartsL {
		width: 690rpx;
		height: 500rpx;
		background-color: #FFFFFF;
	}
	
	// 曲线图
	.access-box{
		margin-bottom: 50rpx;
	}
	.curve{
		box-shadow:0px 6rpx 10rpx 0px rgba(0, 0, 0, 0.1);
		padding-bottom: 20rpx;
	}
	
	.footer{
		padding: 36rpx 30rpx;
		
		.left{
			color: #FFFFFF;
			font-size: 30rpx;
			
			.left-icon{
				margin-right: 16rpx;
			}
		}
		.right{
			
			.right-common{
				width: 220rpx;
				height: 60rpx;
				border-radius: 30rpx;
				font-size: 30rpx;
				color: #FFFFFF;
				text-align: center;
				line-height: 60rpx;
			}
			.right-item1{
				background:linear-gradient(90deg,rgba(35,179,203,1),rgba(65,225,203,1));
				margin-right: 16rpx;
			}
			.right-item2{
				background:linear-gradient(90deg,rgba(38,112,242,1),rgba(82,177,252,1));
			}
		}
	}
	
	// 弹窗
	.secretOrder-box{
		
		.title{
			font-size: 40rpx;
			color: #000000;
			margin-bottom: 30rpx;
		}
		.inp-box{
			border-bottom: 1px solid #000000;
			padding-bottom: 10rpx;
			margin-bottom: 30rpx;
		}
		.secretOrder-inp{
			color: #000000;
		}
		.wrong{
			color: #bc0100;
			font-size: 24rpx;
			margin-bottom: 30rpx;
		}
		.btn-list{
			color: #BC0100;
			justify-content: flex-end;
		}
	}
	
	.follow_scroll{
		max-height: 1000rpx;
	}
	
	// 商机弹窗
	.business-box{
		padding: 42rpx 56rpx 24rpx;
		
		.title{
			font-size: 36rpx;
			color: #000;
			text-align: center;
			letter-spacing: 2px;
			margin-bottom: 38rpx;
		}
		
		.list-box{
			
			.list-item{
				height: 70rpx;
				border-radius: 40rpx;
				border: 1px solid #BC0100;
				color: #BC0100;
				font-size: 30rpx;
				text-align: center;
				line-height: 70rpx;
				margin-bottom: 30rpx;
			}
		}
		
		.confim-btn{
			color: #FFFFFF;
			background: #BC0100;
			width: 304rpx;
			height: 82rpx;
			text-align: center;
			line-height: 82rpx;
			border-radius: 50rpx;
			margin: 60rpx auto 0;
		}
		.col{
			background: #BC0100;
			color: #FFFFFF !important;
			border: 0;
		}
	}
	
	// 天数选择弹窗
	.department{
		border-radius: 10rpx;
		overflow: hidden;
		background: #FFFFFF;
		position: relative;
		z-index: 99999;
		width: 70%;
		
		.title{
			background: #BC0100;
			padding: 28rpx 0;
			text-align: center;
			color: #FFFFFF;
			font-size: 28rpx;
		}
		.dep-item{
			padding: 30rpx 26rpx;
			border-bottom: 1px solid #E6E6E6;
			font-size: 26rpx;
			color: #000000;
		}
	}
	
	.popUp_cover{
		width: 100%;
		height: 100%;
		background: rgba(0,0,0,0.6);
		position: fixed;
		top: 0;
	}
</style>
