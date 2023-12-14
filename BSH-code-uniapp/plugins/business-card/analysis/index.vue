<template>
	<view class="analysis-root">
		<view class="select flex flex-y-center flex-x-center" @tap="showPop">
			{{department_text}}
			<view class="triangle"></view>
		</view>
		
		<com-modal :show="is_modal" padding='0rpx 0rpx' width="70%" :custom='true' @cancel='hidePop'>
			<view class="department">
				<view class="title">部门选择</view>
				<scroll-view scroll-y="true" class="dep-scroll">
					<view class="dep-item" v-for="(item,index) in general_data.department_list" :key='item.name' @tap="depSelect(index,item.id)">
						{{item.name}}
					</view>
				</scroll-view>
			</view>
		</com-modal>
		
		<view class="tabs-box flex" :class="{bor:tabs_index == 0}">
			<view class="tabs-item" v-for="(item,index) in tabs_list" @tap="tabSwitch(index,'time1')">
				<text :class="{'tabs-active':tabs_index == index}">{{item}}</text>
			</view>
		</view>
		
		<!-- 概况 -->
		<view v-show="tabs_index == 0">
			<view class="timeTab-box flex flex-x-between flex-y-center">
				<view class="timeTab-item" :class="{active:index == time_index}" @tap="switchTime(index,'time1')" v-for="(item,index) in time_list" :key='index'>{{item}}</view>
			</view>
			<view style="height: 16rpx;background: #F7F7F7;"></view>
			<view class="content flex flex-x-between">
				<block v-if="general_data">
					<view class="content-item flex flex-col flex-x-center flex-y-center">
						<view class="title">新增客户</view>
						<view>{{general_data.new_client_total}}</view>
					</view>
					<view class="content-item flex flex-col flex-x-center flex-y-center">
						<view class="title">浏览量</view>
						<view>{{general_data.browse_total}}</view>
					</view>
					<view class="content-item flex flex-col flex-x-center flex-y-center">
						<view class="title">新增线索</view>
						<view>{{general_data.new_clue_total}}</view>
					</view>
					<view class="content-item flex flex-col flex-x-center flex-y-center">
						<view class="title">订单数</view>
						<view>{{general_data.team_order_count}}</view>
					</view>
					<view class="content-item flex flex-col flex-x-center flex-y-center">
						<view class="title">订单金额</view>
						<view>{{general_data.team_order_total}}</view>
					</view>
					<view class="content-item flex flex-col flex-x-center flex-y-center">
						<view class="title">下单人数</view>
						<view>{{general_data.order_user_total}}</view>
					</view>
					<view class="content-item flex flex-col flex-x-center flex-y-center">
						<view class="title">意向客户</view>
						<view>{{general_data.intent_total}}</view>
					</view>
					<view class="content-item flex flex-col flex-x-center flex-y-center">
						<view class="title">比较客户</view>
						<view>{{general_data.compare_total}}</view>
					</view>
					<view class="content-item flex flex-col flex-x-center flex-y-center">
						<view class="title">待成交客户</view>
						<view>{{general_data.clinch_total}}</view>
					</view>
				</block>
			</view>
			<view style="height: 16rpx;background: #F7F7F7;"></view>
			
			<!-- 漏斗图 -->
			<view class="funnel-title">成交转化率</view>
			<view class="funnel flex flex-col flex-y-center">
				<block v-if="general_data">
					<view class="floor flex flex-y-center">
						<view class="left-triangle"></view>
						<view class="center-rectangle">总客户数:{{general_data.stat.client_total}}</view>
						<view class="right-triangle"></view>
					</view>
					<view class="floor flex flex-y-center">
						<view class="left-triangle left-triangle2"></view>
						<view class="center-rectangle center-rectangle2">跟进中:{{general_data.stat.follow_total}}</view>
						<view class="right-triangle right-triangle2"></view>
					</view>
					<view class="floor flex flex-y-center">
						<view class="left-triangle left-triangle3"></view>
						<view class="center-rectangle center-rectangle3">成交数:{{general_data.stat.deal_total}}</view>
						<view class="right-triangle right-triangle3"></view>
					</view>
				</block>
			</view>
			<view class="explanation flex flex-x-center">
				<block v-if="general_data">
					<view class="explanation-item flex flex-y-center">
						<view class="rectangle"></view>
						<view>总客户数{{general_data.stat.client_total}}</view>
					</view>
					<view class="explanation-item flex flex-y-center">
						<view class="rectangle" style="background: #F12726;"></view>
						<view>跟进中{{general_data.stat.follow_total}}</view>
					</view>
					<view class="explanation-item flex flex-y-center">
						<view class="rectangle" style="background: #BC0100;"></view>
						<view>成交数{{general_data.stat.deal_total}}</view>
					</view>
				</block>
			</view>
			<view style="height: 16rpx;background: #F7F7F7;"></view>
			
			<view class="line" style="padding-bottom: 30rpx;">
				<view class="line-title">商城订单量&交易金额</view>
				<view class="timeTab-box flex flex-x-center flex-y-center" style="border-top: 0;margin-bottom: 6rpx;">
					<view class="timeTab-item" style="margin-right: 40rpx;" :class="{active:index == time_index2}" @tap="switchTime(index,'time2')" v-for="(item,index) in time_list2" :key='index'>{{item}}</view>
				</view>
				
				<view class="curve">
					<canvas canvas-id="canvasLineA" id="canvasLineA" class="charts" @touchstart="touchLineA"></canvas>
				</view>
			</view>
			<view style="height: 16rpx;background: #F7F7F7;"></view>
			
			<view class="add-client">
				<view class="line-title">新增客户</view>
				<view class="timeTab-box flex flex-x-center flex-y-center" style="border-top: 0;margin-bottom: 6rpx;">
					<view class="timeTab-item" style="margin-right: 40rpx;" :class="{active:index == time_index3}" @tap="switchTime(index,'time3')" v-for="(item,index) in time_list2" :key='index'>{{item}}</view>
				</view>
				
				<view class="curve">
					<canvas canvas-id="canvasLineOA" id="canvasLineOA" class="charts"></canvas>
				</view>
			</view>
			<view style="height: 16rpx;background: #F7F7F7;"></view>
			
			<view class="follow-client add-client">
				<view class="line-title">跟进客户</view>
				<view class="timeTab-box flex flex-x-center flex-y-center" style="border-top: 0;margin-bottom: 6rpx;">
					<view class="timeTab-item" style="margin-right: 40rpx;" :class="{active:index == time_index4}" @tap="switchTime(index,'time4')" v-for="(item,index) in time_list2" :key='index'>{{item}}</view>
				</view>
				
				<view class="curve">
					<canvas canvas-id="canvasLineOA2" id="canvasLineOA2" class="charts"></canvas>
				</view>
			</view>
			<view style="height: 16rpx;background: #F7F7F7;"></view>
			
			<view class="interest">
				<view class="interest-title">客户兴趣占比</view>
				
				<view class="flex area-box">
					<view class="qiun-charts" >
						<canvas canvas-id="canvasArea" id="canvasArea" class="chartsa" ></canvas>
					</view>
					
					<view class="legend">
						<view class="legend-item flex" v-for="(item,index) in CustomerInterest_data">
							<view class="left flex flex-y-center flex-x-center">
								<view class="solidCricle" :style="{background: index == 1?'#ea2424':index==2?'#FF6665':''}"></view>
							</view>
							<view class="right">
								<view :style="{color: index == 1?'#ea2424':index==2?'#FF6665':''}">{{calculation(item.data)}} </view>
								<view class="text">{{item.name}}</view>
							</view>
						</view>
					</view>
				</view>
			</view>
			<view style="height: 16rpx;background: #F7F7F7;"></view>
			
			<view class="add-client">
				<view class="line-title">客户活跃度</view>
				<view class="timeTab-box flex flex-x-center flex-y-center" style="border-top: 0;margin-bottom: 6rpx;">
					<view class="timeTab-item" style="margin-right: 40rpx;" :class="{active:index == time_index5}" @tap="switchTime(index,'time5')" v-for="(item,index) in time_list2" :key='index'>{{item}}</view>
				</view>
				
				<view class="curve">
					<canvas canvas-id="canvasLineOA3" id="canvasLineOA3" class="charts"></canvas>
				</view>
				<input type="text" value=""/>
			</view>
		</view>
		
		<!-- 销售排行 -->
		<view v-show="tabs_index == 1">
			<view class="timeTab-box flex flex-x-between flex-y-center">
				<view class="timeTab-item" style="width: 158rpx;" :class="{active:index == sale_index}" @tap="switchSale(index,'time1')" v-for="(item,index) in sale_list" :key='index'>{{item}}</view>
			</view>
			<view style="height: 16rpx;background: #F7F7F7;"></view>
			
			<view class="rank-list">
				<view class="rank-item flex flex-x-between flex-y-center" v-for="(item,index) in sale_data" :key='index'>
					<view class="flex flex-y-center">
						<view class="sequence">{{index+1}}</view>
						<view class="flex flex-y-center">
							<image class="rank-avatar" :src="item.avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
							<view>{{item.nickname}}</view>
						</view>
					</view>
					<view style="color: #BC0100;font-size: 36rpx;">{{item.total}}</view>
				</view>
			</view>
		</view>
		
		<!-- AI分析 -->
		<view v-show="tabs_index == 2">
			<view class="AI">
				<view class="up">
					<block v-if="Ai_data.length != 0">
						<view class="one flex flex-y-center">
							<image class="medals" :src="img_url+'gold.png'" mode=""></image>
							<view class="userInfo flex flex-y-center">
								<image class="userInfo-avatar" :src="Ai_data[0].avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
								<view>
									<view class="userName">{{Ai_data[0].nickname}}</view>
									<view class="user-position">{{Ai_data[0].position_name}}</view>
								</view>
							</view>
						</view>
					</block>
					<view class="gold-radar">
						<canvas canvas-id="canvasRadar" id="canvasRadar" class="charts"></canvas>
					</view>
				</view>
				<view style="height: 16rpx;background: #F7F7F7;"></view>
				
				<view class="center">
					<block v-if="Ai_data.length != 0">
						<view class="one flex flex-y-center">
							<view class="flex flex-y-center">
								<image class="medals" :src="img_url+'silver.png'" mode=""></image>
								<view class="userInfo flex flex-y-center">
									<image class="userInfo-avatar" :src="Ai_data[1].avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
									<view>
										<view class="userName userName2 over1">{{Ai_data[1].nickname}}</view>
										<view class="user-position">{{Ai_data[1].position_name}}</view>
									</view>
								</view>
							</view>
							<view class="flex flex-y-center">
								<image class="medals" :src="img_url+'silver.png'" mode=""></image>
								<view class="userInfo flex flex-y-center">
									<image class="userInfo-avatar" :src="Ai_data[2].avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
									<view>
										<view class="userName userName2 over1">{{Ai_data[2].nickname}}</view>
										<view class="user-position">{{Ai_data[2].position_name}}</view>
									</view>
								</view>
							</view>
						</view>
					</block>
					
					<view class="flex">
						<view class="gold-radar gold-radar2">
							<canvas canvas-id="canvasRadarTwo" id="canvasRadarTwo" class="charts charts2"></canvas>
						</view>
						<view class="gold-radar gold-radar2">
							<canvas canvas-id="canvasRadarThree" id="canvasRadarThree" class="charts charts2"></canvas>
						</view>
					</view>
				</view>
				<view style="height: 16rpx;background: #F7F7F7;"></view>
				
				<view class="bottom flex">
					<view class="item" v-for="(item,index) in Ai_data_item" :key='index'>
						<view class="name over1">{{item.nickname}}</view>
						<view class="position">{{item.position_name}}</view>
						<view class="gold-radar gold-radar2">
							<canvas :canvas-id="'canvasRadar'+index" :id="'canvasRadar'+index" class="charts charts2"></canvas>
						</view>
					</view>
				</view>
			</view>
		</view>
		
		<!-- 热度排行 -->
		<view v-show="tabs_index == 3">
			<view class="timeTab-box flex flex-x-between flex-y-center">
				<view @tap="switchHot(index,'time1')" v-for="(item,index) in hot_list" style="width: 158rpx;" :class="{active:index == hot_index}" class="timeTab-item" :key='index'>{{item}}</view>
			</view>
			<view style="height: 16rpx;background: #F7F7F7;"></view>
			
			<view class="hot-list">
				<view class="title-btn flex flex-y-center" @tap="showDayPop">
					<view class="triangle"></view>
					{{day_list[day_index]}}
				</view>
				
				<view class="goods-list">
					<view class="goods-item flex flex-y-center" v-for="(item,index) in hot_data">
						<view>{{index+1}}</view>
						<image class="goods-img" :src="item.image" mode=""></image>
						<view class="goods-info flex flex-col flex-x-between">
							<view>
								<view class="goods-name over2">{{item.name}}</view>
								<view class="spec" v-if="hot_index == 1">{{item.position_name}}</view>
							</view>
							<view class="flex flex-x-between">
								<view>浏览量{{item.total}}</view>
								<view>销量{{item.sales}}</view>
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>
		
		<!-- 天数选择弹窗 -->
		<com-modal :show="is_modal_day" padding='0rpx 0rpx' width="70%" :custom='true' @cancel='hidePop'>
			<view class="department">
				<view class="title">天数选择</view>
				<scroll-view scroll-y="true">
					<view class="dep-item" v-for="(item,index) in day_list" :key='item' @tap="daySelect(index)">
						{{item}}
					</view>
				</scroll-view>
			</view>
		</com-modal>
	</view>
</template>

<script>
	// import uCharts from '@/components/u-charts/u-charts.js';
	import uCharts from '@/plugins/u-charts/u-charts.js';
	var _self;
	var canvaLineA=null,
		canvaArea=null,
		canvaRadar=null;
	
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				page:1,
				is_no_more:false,
				department_text:'',
				department_id:'',
				tabs_list:['概况','销售排行','AI分析','热度排行'],
				tabs_index:0,
				time_list:['昨天','7天','15天','30天','汇总'],
				time_index:0,
				time_list2:['7天','15天','30天'],
				time_index2:0,
				time_index3:0,
				time_index4:0,
				time_index5:0,
				MallOrderStat_data:'',
				AddCustomer_data:'',
				Follow_data:'',
				UserActivity_data:'',
				CustomerInterest_data:'',
				Ai_data:[],
				// 图表
				cWidth:'',
				cHeight:'',
				pixelRatio:1,
				// 多进度
				mWidth:'',
				mHeight:'',
				arcbarWidth:0,
				
				// 概况
				general_data:'',
				
				// 销售排行数据
				sale_list:['客户人数','线索数量','订单数量','交易金额'],
				sale_index:0,
				sale_data:[],
				
				// AI分析
				rWidth:'',
				rHeight:'',
				arr:[0,1,2,3,4,5],
				Ai_data_item:[],
				
				// 热度排行
				hot_list:['商品热度','名片热度','资讯热度','直播热度'],
				hot_index:0,
				hot_data:[],
				is_modal_day:false,
				day_index:0,
				day_list:['近7天','近15天','近30天'],
				
				// 选择部门弹窗
				is_modal:false,
			};
		},
		onLoad() {
			this.moreRequest();
			this.getOverviewData();
			
			_self = this;
			this.cWidth=uni.upx2px(690);
			this.cHeight=uni.upx2px(470);
			
			// 多进度
			this.mWidth=uni.upx2px(340);
			this.mHeight=uni.upx2px(140);
			this.arcbarWidth=uni.upx2px(26);
		},
		onReachBottom() {
			switch (this.tabs_index){
				case 1:
					this.getSalesRank();
					break;
				case 2:
					this.getAI();
					break;
				case 3:
					this.getHotRank();
					break;
			}
		},
		computed:{
			calculation(){
				return function(num){
					return parseInt(num*100)+'%';
				}
			}
		},
		methods:{
			getOverviewData(){ //概况
				this.$http.request({
					url:this.$api.plugin.business.general,
					method:'post',
					showLoading:true,
					data:{
						time_type: this.time_index,
						department_id:this.department_id || 0
					}
				}).then(res => {
					if(res.code == 0){
						this.general_data = res.data;
						this.initDepartment(res.data.department_list);
					}else if(res.code == 4){
						this.$http.toast(res.msg);
						setTimeout(()=>{
							this.navBack();
						},1000)
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			getMallOrderStat(){ //概况里的商城订单量&交易金额
				this.$http.request({
					url:this.$api.plugin.business.mall_order_stat,
					method:'post',
					showLoading:true,
					data:{
						time_type: this.time_index2+1,
						department_id:this.department_id || 0
					}
				}).then(res => {
					if(res.code == 0){
						this.MallOrderStat_data = res.data.list;
						this.getServerData();
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			getAddCustomer(){ //概况里的新增客户
				this.$http.request({
					url:this.$api.plugin.business.add_customer_stat,
					method:'post',
					showLoading:true,
					data:{
						time_type: this.time_index3+1,
						department_id:this.department_id || 0
					}
				}).then(res => {
					if(res.code == 0){
						this.AddCustomer_data = res.data.list;
						this.getServerData();
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			getFollow(){ //概况里的跟进客户
				this.$http.request({
					url:this.$api.plugin.business.follow_customer_stat,
					method:'post',
					showLoading:true,
					data:{
						time_type: this.time_index4+1,
						department_id:this.department_id || 0
					}
				}).then(res => {
					if(res.code == 0){
						this.Follow_data = res.data.list;
						this.getServerData();
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			getCustomerInterest(){ //概况里的客户兴趣占比
				this.$http.request({
					url:this.$api.plugin.business.interest_stat,
					method:'post',
					showLoading:true,
					data:{
						time_type: 0,
						department_id:this.department_id || 0
					}
				}).then(res => {
					if(res.code == 0){
						this.CustomerInterest_data = res.data.list;
						this.getServerData();
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			getCustomerAvtive(){ //概况里的客户活跃
				this.$http.request({
					url:this.$api.plugin.business.user_activity_stat,
					method:'post',
					showLoading:true,
					data:{
						time_type: this.time_index5+1,
						department_id:this.department_id || 0
					}
				}).then(res => {
					if(res.code == 0){
						this.UserActivity_data = res.data.list;
						this.getServerData();
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			moreRequest(){ //多个请求都返回完才进行下一步
				var mallOrderStat = this.$http.request({
					url:this.$api.plugin.business.mall_order_stat,
					method:'post',
					showLoading:true,
					data:{
						time_type: this.time_index2+1,
						department_id:this.department_id || 0
					}
				});
				
				var addCustomerStat = this.$http.request({
					url:this.$api.plugin.business.add_customer_stat,
					method:'post',
					showLoading:true,
					data:{
						time_type: this.time_index3+1,
						department_id:this.department_id || 0
					}
				})
				
				var followCustomerStat = this.$http.request({
					url:this.$api.plugin.business.follow_customer_stat,
					method:'post',
					showLoading:true,
					data:{
						time_type: this.time_index4+1,
						department_id:this.department_id || 0
					}
				})
				
				var userActivityStat = this.$http.request({
					url:this.$api.plugin.business.user_activity_stat,
					method:'post',
					showLoading:true,
					data:{
						time_type: this.time_index5+1,
						department_id:this.department_id || 0
					}
				})
				
				var CustomerInterest = this.$http.request({
					url:this.$api.plugin.business.interest_stat,
					method:'post',
					showLoading:true,
					data:{
						time_type: 0,
						department_id:this.department_id || 0
					}
				})
				
				const more = Promise.all([mallOrderStat,addCustomerStat,followCustomerStat,userActivityStat,CustomerInterest]);
				more.then(res => {
					this.MallOrderStat_data = res[0].data.list;
					this.AddCustomer_data = res[1].data.list;
					this.Follow_data = res[2].data.list;
					this.UserActivity_data = res[3].data.list;
					this.CustomerInterest_data = res[4].data.list;
					
					this.getServerData();
				})
			},
			
			// 销售排行
			getSalesRank(){
				if(this.is_no_more){
					this.$http.toast('没有更多信息了');
					return;
				}
				this.$http.request({
					url:this.$api.plugin.business.sales_rank,
					method:'post',
					showLoading:true,
					data:{
						department_id: this.department_id || 0,
						page_type: this.sale_index +1,
						page:this.page,
					}
				}).then(res => {
					if(res.code == 0){
						this.sale_data.push(...res.data.list);
						
						if(res.data.pagination.page_count >= this.page){
							this.page++;
						}else{
							this.$http.toast('没有更多信息了');
							this.is_no_more = true;
						}
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			
			// AI分析
			getAI(){
				if(this.is_no_more){
					this.$http.toast('没有更多信息了');
					return;
				}
				this.$http.request({
					url:this.$api.plugin.business.ai_analysis,
					method:'post',
					showLoading:true,
					data:{
						department_id: this.department_id || 0,
						page:this.page
					}
				}).then(res => {
					if(res.code == 0){
						this.Ai_data.push(...res.data.list);
						this.Ai_data_item = [];
						this.Ai_data.forEach((item,index)=>{ //除去前三的数据
							if(index>2){
								this.Ai_data_item.push(item);
							}
						})
						this.getServerData();
						if(res.data.pagination.page_count >= this.page){
							this.page++;
						}else{
							this.$http.toast('没有更多信息了');
							this.is_no_more = true;
						}
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			
			// 热度排行
			getHotRank(){
				if(this.is_no_more){
					this.$http.toast('没有更多信息了');
					return;
				}
				this.$http.request({
					url:this.$api.plugin.business.hot_rank,
					method:'post',
					showLoading:true,
					data:{
						page_type:this.hot_index + 1,
						time_type:this.day_index + 1,
						department_id: this.department_id || 0,
						page:this.page
					}
				}).then(res => {
					if(res.code == 0){
						this.hot_data.push(...res.data.list);
						
						if(res.data.pagination.page_count >= this.page){
							this.page++;
						}else{
							this.$http.toast('没有更多信息了');
							this.is_no_more = true;
						}
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			// 天数弹窗
			showDayPop(){
				this.is_modal_day = true;
			},
			daySelect(index){//天数选择
				this.day_index = index;
				this.is_modal_day = false;
				this.hot_data = [];
				this.page = 1;
				this.is_no_more = false;
				this.getHotRank();
			},
			
			initDepartment(list){ // 初始化部门选择
				if(this.department_id == ''){
					this.department_text = list[0].name;
				}else{
					var obj = list.find(val => val.id == this.department_id);
					this.department_text = obj.name;
				}
			},
			depSelect(index,id){//部门选择
				this.department_text = this.general_data.department_list[index].name;
				this.is_modal = false;
				this.department_id = id;
				this.page = 1;
				this.is_no_more = false;
				
				switch (this.tabs_index){
					case 0:
						this.getOverviewData();
						this.moreRequest();
						break;
					case 1:
						this.sale_data = [];
						this.getSalesRank();
						break;
					case 2:
						this.Ai_data = [];
						this.getAI();
						break;
					case 3:
						this.hot_data = [];
						this.getHotRank();
						break;
				}
				
			},
			showPop(){ //展示部门选择弹窗
				this.is_modal = true;
			},
			hidePop(){//关闭弹窗
				this.is_modal = false;
				this.is_modal_day = false;
			},
			tabSwitch(index){
				this.tabs_index = index;
				this.page = 1;
				this.is_no_more = false;
				switch (index){
					case 0:
						this.moreRequest();
						break;
					case 1:
						this.sale_data=[];
						this.getSalesRank();
						break;
					case 2:
						this.Ai_data=[];
						this.getAI();
						break;
					case 3:
						this.hot_data=[];
						this.getHotRank();
						break;
				}
			},
			switchTime(index,name){
				if(name == 'time1'){
					this.time_index = index;
					this.getOverviewData();
				}else if(name == 'time2'){
					this.time_index2 = index;
					this.getMallOrderStat();
				}else if(name == 'time3'){
					this.time_index3 = index;
					this.getAddCustomer();
				}else if(name == 'time4'){
					this.time_index4 = index;
					this.getFollow();
				}else if(name == 'time5'){
					this.time_index5 = index;
					this.getCustomerAvtive();
				}
			},
			switchSale(index){
				this.sale_index = index;
				this.sale_data=[];
				this.page = 1;
				this.is_no_more = false;
				this.getSalesRank();
			},
			switchHot(index){
				this.hot_index = index;
				this.hot_data = [];
				this.page = 1 ;
				this.is_no_more = false;
				this.getHotRank();
			},
			getServerData(){
				// 雷达
				if(this.tabs_index == 2){
					_self.showRadar("canvasRadar",this.Ai_data[0].radar_data);
					_self.showRadar("canvasRadarTwo",this.Ai_data[1].radar_data);
					_self.showRadar("canvasRadarThree",this.Ai_data[2].radar_data);
					
					this.Ai_data_item.forEach((item,index)=>{
						// console.log(item.radar_data,'item.radar_data');
						_self.showRadar(`canvasRadar${index}`,item.radar_data);
					})
				}else{
					// 曲线图
					_self.showLineA("canvasLineA",this.MallOrderStat_data);
					
					// 带渐变的曲线图
					_self.showLineOA("canvasLineOA",this.AddCustomer_data);
					
					_self.showLineOA2("canvasLineOA2",this.Follow_data);
					
					_self.showLineOA3("canvasLineOA3",this.UserActivity_data);
					
					// 多进度
					console.log(this.CustomerInterest_data,'CustomerInterest_data');
					let Area={series:this.CustomerInterest_data};
					this.showArea("canvasArea",Area);
				}
			},
			showRadar(canvasId,chartData){
				var labelColor,padding;
				if(canvasId == 'canvasRadar'){
					_self.rWidth=uni.upx2px(650);
					_self.rHeight=uni.upx2px(500);
					padding = [15,15,0,15];
				}else if(canvasId == 'canvasRadarTwo'){
					_self.rWidth=uni.upx2px(390);
					_self.rHeight=uni.upx2px(390);
					chartData.categories = ['','','','','',''];
					padding = [15,15,0,15];
				}else if(canvasId == 'canvasRadarThree'){
					_self.rWidth=uni.upx2px(390);
					_self.rHeight=uni.upx2px(390);
					chartData.categories = ['','','','','',''];
					padding = [15,15,0,15];
				}else{
					_self.rWidth=uni.upx2px(242);
					_self.rHeight=uni.upx2px(290);
					chartData.categories = ['','','','','',''];
					padding = [10,0,0,0];
				}
				canvaRadar=new uCharts({
					$this:_self,
					canvasId: canvasId,
					type: 'radar',
					fontSize:10,
					padding:padding,
					legend:{
						show:false,
					},
					background:'#FFFFFF',
					pixelRatio:_self.pixelRatio,
					animation: false,
					dataLabel: false,
					categories: chartData.categories,
					series: chartData.series,
					width: _self.rWidth*_self.pixelRatio,
					height: _self.rHeight*_self.pixelRatio,
					dataPointShape:false,
					extra: {
						radar: {
							max: 200,//雷达数值的最大值
							gridCount:4,
							opacity:1,
							labelColor:labelColor
						},
					}
				});
			},
			showArea(canvasId,chartData){
				let border=this.arcbarWidth;
				let aWidth=this.mWidth/2-border;
				let aHeight=this.mHeight-border;
				let radius=(aWidth*aWidth+aHeight*aHeight)/aHeight/2;
				let centerPoint={
					x:this.mWidth/2*this.pixelRatio,
					y:(radius+border)*this.pixelRatio
				};
				canvaArea=new uCharts({
					$this:_self,
					canvasId: canvasId,
					type: 'arcbar',
					fontSize:11,
					colors:['#BC0100','#F12726','#FF6665'],
					legend:{show:false},
					background:'#FFFFFF',
					pixelRatio:_self.pixelRatio,
					series: chartData.series,
					animation: false,
					width: _self.mWidth*_self.pixelRatio,
					height: _self.mHeight*_self.pixelRatio,
					extra: {
						arcbar:{
							type:'circle',
							width: border*_self.pixelRatio,
							backgroundColor:'rgba(233,233,233,0.4)',
							startAngle:1.5,
							endAngle:0.0001,
							radius:radius*_self.pixelRatio,
							gap:10*_self.pixelRatio,
							center:centerPoint
						}
					}
				});
				
			},
			showLineOA(canvasId,chartData){
				canvaLineA=new uCharts({
					$this:_self,
					canvasId: canvasId,
					type: 'area',
					fontSize:11,
					padding:[15,20,0,15],
					legend:{
						show:false
					},
					background:'#FFFFFF',
					pixelRatio:_self.pixelRatio,
					categories: chartData.categories,
					series: chartData.series,
					animation: false,
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
						gridType:'dash',
						gridColor:'#EEEEEE',
						dashLength:8,
						splitNumber:5,
						format:(val)=>{return val.toFixed(0)}
					},
					width: _self.cWidth*_self.pixelRatio,
					height: _self.cHeight*_self.pixelRatio,
					extra: {
						area:{
							type: 'curve',
							addLine:true,
							gradient:true,
							opacity:0.7
						}
					}
				});
				
			},
			showLineOA2(canvasId,chartData){
				canvaLineA=new uCharts({
					$this:_self,
					canvasId: canvasId,
					type: 'area',
					fontSize:11,
					padding:[15,20,0,15],
					legend:{
						show:false
					},
					background:'#FFFFFF',
					pixelRatio:_self.pixelRatio,
					categories: chartData.categories,
					series: chartData.series,
					animation: false,
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
						gridType:'dash',
						gridColor:'#EEEEEE',
						dashLength:8,
						splitNumber:5,
						format:(val)=>{return val.toFixed(0)}
					},
					width: _self.cWidth*_self.pixelRatio,
					height: _self.cHeight*_self.pixelRatio,
					extra: {
						area:{
							type: 'curve',
							addLine:true,
							gradient:true,
							opacity:0.7
						}
					}
				});
				
			},
			showLineOA3(canvasId,chartData){
				canvaLineA=new uCharts({
					$this:_self,
					canvasId: canvasId,
					type: 'area',
					fontSize:11,
					padding:[15,20,0,15],
					legend:{
						show:false
					},
					background:'#FFFFFF',
					pixelRatio:_self.pixelRatio,
					categories: chartData.categories,
					series: chartData.series,
					animation: false,
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
						gridType:'dash',
						gridColor:'#EEEEEE',
						dashLength:8,
						splitNumber:5,
						format:(val)=>{return val.toFixed(0)}
					},
					width: _self.cWidth*_self.pixelRatio,
					height: _self.cHeight*_self.pixelRatio,
					extra: {
						area:{
							type: 'curve',
							addLine:true,
							gradient:true,
							opacity:0.7
						}
					}
				});
				
			},
			showLineA(canvasId,chartData){
				canvaLineA=new uCharts({
					$this:_self,
					canvasId: canvasId,
					type: 'line',
					fontSize:10,
					padding:[15,20,0,15],
					colors:['#BC0100','#2873F2'],
					legend:{
						show:true,
						position:'top',
					},
					dataLabel:true,
					dataPointShape:true,
					background:'#FFFFFF',
					pixelRatio:_self.pixelRatio,
					categories: chartData.categories,
					series: chartData.series,
					animation: false,
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
					width: _self.cWidth*_self.pixelRatio,
					height: _self.cHeight*_self.pixelRatio,
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
		}
	}
</script>

<style lang="scss" scoped>
	page{
		background: #FFFFFF;
	}
	
	.select{
		color: #000;
		font-size: 38rpx;
		font-weight: 700;
		
		.triangle{
			width: 0;
			height: 0;
			border-width: 16rpx 10rpx 0rpx 10rpx;
			border-color: #000000 transparent transparent transparent;
			border-style: solid;
			margin-left: 10rpx;
		}
	}
	
	.tabs-box{
		padding: 60rpx 0 40rpx;
		border-bottom: 1px solid #F2F2F2;
		
		.tabs-item{
			width: 25%;
			text-align: center;
			color: #666666;
			font-size: 30rpx;
			letter-spacing: 1px;
		}
		.tabs-active{
			color: #BC0100;
			padding-bottom: 20rpx;
			border-bottom: 2px solid #BC0100;
		}
	}
	.bor{
		border-bottom: 1px solid #F2F2F2;
	}
	
	.timeTab-box{
		padding: 20rpx 30rpx;
		background: #FFFFFF;
		border-top: 1px solid #F2F2F2;
		
		.timeTab-item{
			width: 110rpx;
			height: 50rpx;
			text-align: center;
			line-height: 50rpx;
			border: 1px solid #C0C0C0;
			border-radius: 16rpx;
			font-size: 24rpx;
		}
		.active{
			border: 1px solid #BC0100;
			color: #BC0100;
		}
	}
	.content{
		background: #FFFFFF;
		padding: 30rpx;
		flex-wrap: wrap;
		
		.content-item{
			width: 216rpx;
			height: 216rpx;
			border: 1px solid #C0C0C0;
			font-size: 32rpx;
			color: #BC0100;
			margin-bottom: 18rpx;
			
			.title{
				color: #8F8F8F;
				font-size: 24rpx;
			}
		}
	}
	
	// 漏斗图
	.funnel-title{
		font-size: 30rpx;
		color: #000;
		text-align: center;
		padding-top: 50rpx;
	}
	.funnel{
		color: #FFFFFF;
		margin: 40rpx 0;
		background: #FFFFFF;
		
		.floor{
			height: 56rpx;
			margin-bottom: 20rpx;
			
			.left-triangle{
				width: 0; height: 0;
				border-color: #FF6565 #FF6565 transparent transparent;
				border-width: 30rpx 18rpx 30rpx 18rpx;
				border-style: solid;
			}
			.center-rectangle{
				background: #FF6565;
				width: 380rpx;
				height: 60rpx;
				font-size: 26rpx;
				line-height: 60rpx;
				text-align: center;
			}
			.right-triangle{
				width: 0; height: 0;
				border-color: #FF6565 transparent transparent  #FF6565;
				border-width: 30rpx 18rpx 30rpx 18rpx;
				border-style: solid;
			}
			
			.left-triangle2{
				border-color: #F12625 #F12625 transparent transparent;
			}
			.center-rectangle2{
				width: 294rpx;
				background: #F12625;
			}
			.right-triangle2{
				border-color: #F12625 transparent transparent  #F12625;
			}
			
			.left-triangle3{
				border-color: #BB0100 #BB0100 transparent transparent;
			}
			.center-rectangle3{
				width: 210rpx;
				background: #BB0100;
			}
			.right-triangle3{
				border-color: #BB0100 transparent transparent  #BB0100;
			}
			
		}
	}
	.explanation{
		margin-bottom: 50rpx;
		
		.explanation-item{
			font-size: 24rpx;
			color: #000;
			margin-right: 20rpx;
			
			.rectangle{
				width: 50rpx;
				height: 30rpx;
				background: #FF6665;
				margin-right: 10rpx;
			}
			.cricle{
				width: 26rpx;
				height: 26rpx;
				border-radius: 50%;
				background: #BC0100;
				margin-right: 26rpx;
			}
		}
	}
	
	.line-title{
		padding: 46rpx 0 0;
		font-size: 28rpx;
		color: #000;
		text-align: center;
		letter-spacing: 1px;
	}
	
	// 图表样式
	.qiun-charts {
		width: 690rpx;
		height: 500rpx;
		background-color: #FFFFFF;
	}
	
	.charts {
		width: 690rpx;
		height: 500rpx;
		background-color: #FFFFFF;
	}
	
	// 折线图单独样式
	.curve{
		padding: 30rpx 0;
		margin: 0 30rpx;
		box-shadow: 0rpx 0rpx 6rpx 0rpx #dfdfdf;
	}
	
	.add-client{
		padding-bottom: 50rpx;
	}
	
	.interest{
		font-size: 28rpx;
		color: #000;
		text-align: center;
		padding: 48rpx 30rpx;
		
		.interest-title{
			margin-bottom: 50rpx;
		}
	}
	
	// 多进度图
	.area-box{
		box-shadow: 0rpx 0rpx 6rpx 0rpx #dfdfdf;
		padding: 40rpx 50rpx 80rpx 40rpx;
		
		.qiun-charts {
			width: 340rpx;
			height: 340rpx;
			background: '#ffffff';
			background-size: 100% 100%;
		}
		
		.chartsa {
			width: 340rpx;
			height: 340rpx;
		}
		
		.legend{
			margin-left: 80rpx;
			padding: 40rpx 0 0;
			
			.legend-item{
				margin-bottom: 30rpx;
			}
			.left{
				width: 40rpx;
				height: 40rpx;
				border-radius: 50%;
				border: 1px solid #F2F2F2;
				margin-right: 16rpx;
				
				.solidCricle{
					width: 20rpx;
					height: 20rpx;
					background: #BC0100;
					border-radius: 50%;
				}
			}
			.right{
				color: #BC0100;
				font-size: 30rpx;
				flex: 1;
				text-align: left;
				font-weight: 700;
				transform: scale(0.7);
				position: relative;
				top: -14rpx;
				left: -20rpx;
				.text{
					font-size: 24rpx;
					white-space: nowrap;
					color: #666666;
					font-weight: 500;
				}
			}
		}
	}
	
	// 销售排行
	.rank-item{
		border-bottom: 1px solid #F2F2F2;
		padding: 30rpx;
		color: #000000;
		font-size: 28rpx;
		
		.rank-avatar{
			width: 80rpx;
			height: 80rpx;
			border-radius: 50%;
			margin-left: 40rpx;
			margin-right: 20rpx;
		}
	}
	
	// AI分析
	.AI{
		.one-box{
			
		}
		.one{
			padding: 40rpx 30rpx;
			border-bottom: 1px solid #F2F2F2;
			
			.medals{
				width: 66rpx;
				height: 76rpx;
				display: block;
				margin-right: 10rpx;
			}
			.userInfo{
				
				.userInfo-avatar{
					width: 84rpx;
					height: 84rpx;
					margin-right: 16rpx;
					border-radius: 50%;
				}
				.userName{
					font-size: 26rpx;
					color: #000000;
					margin-bottom: 4rpx;
				}
				.userName2{
					width: 164rpx;
				}
				.user-position{
					font-size: 24rpx;
					color: #999999;
				}
			}
		}
		
		.gold-radar{
			width: 750rpx;
			height: 480rpx;
			background-color: #FFFFFF;
			display: flex;
			justify-content: center;
		}
		.gold-radar2{
			width: 375rpx;
			height: 380rpx;
		}
		.charts {
			width: 600rpx;
			height: 470rpx;
			background-color: #FFFFFF;
		}
		.charts2{
			width: 400rpx;
			height: 380rpx;
		}
		
		.bottom{
			padding-top: 60rpx;
			flex-wrap: wrap;
			
			.item{
				width: 33.33%;
				text-align: center;
				
				.name{
					font-size: 26rpx;
					color: #000000;
					margin-bottom: 4rpx;
				}
				.position{
					font-size: 24rpx;
					color: #999999;
				}
				.gold-radar2{
					width: 100%;
					height: 300rpx;
				}
				.charts2{
					width: 242rpx;
					height: 290rpx;
				}
			}
		}
	}
	
	// 热度排行
	.hot-list{
		
		.title-btn{
			padding: 20rpx 30rpx;
			color: #000;
			font-size: 24rpx;
			letter-spacing: 2px;
			border-bottom: 1px solid #F2F2F2;
			
			.triangle{
				width: 0;
				height: 0;
				border-width: 20rpx 10rpx 0 10rpx;
				border-color: #BC0100 transparent transparent transparent;
				border-style: solid;
				margin-right: 10rpx;
			}
		}
		
		.goods-list{
			
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
	}
	
	// 部门选择弹窗
	.department{
		border-radius: 10rpx;
		overflow: hidden;
		background: #FFFFFF;
		
		.title{
			background: #BC0100;
			padding: 28rpx 0;
			text-align: center;
			color: #FFFFFF;
			font-size: 28rpx;
		}
		.dep-scroll{
			height: 700rpx;
		}
		.dep-item{
			padding: 30rpx 26rpx;
			border-bottom: 1px solid #E6E6E6;
			font-size: 26rpx;
			color: #000000;
		}
	}
	
	// 天数选择弹窗
	.department{
		border-radius: 10rpx;
		overflow: hidden;
		background: #FFFFFF;
		
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
</style>
