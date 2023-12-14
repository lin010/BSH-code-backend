<template>
	<view class="hotel-orderList-app">
		<view class="hotel-orderList-status">
			<view v-for="(item,index) in list" :key='index' :class="switchIndex==index?'active':''" @click="checkIndex(index,item.status)">
				{{item.title}}
			</view>
		</view>
		<view class="hotel-orderLis" >
			<view class="hotel-orderLis-item" v-for="(item,index) in orderList" :key='index' @click="linkTo(item.order_id)">
				<view class="hotel-orderLis-item-title">
					<image :src="img_url+'/shoplogo.png'" mode="widthFix"></image>
					<text>订单号：{{item.order_no}}</text>
					<text>{{item.status_text}}</text>
				</view>
				<view class="hotel-orderLis-item-detail">
					<view class="hotel-orderLis-item-detail-image">
						<image :src="item.thumb_url" mode=""></image>
					</view>
					<view class="hotel-orderLis-item-detail-message">
						<view class="hotel-orderLis-item-detail-name">
							{{item.hotel_name}}
						</view>
						<view class="hotel-orderLis-item-detail-selectType">
							<text>{{item.room_name}}</text>,
							<text>床宽：{{item.bed_width}}m</text>,
							<text v-if="item.bed_type=='single'">单床,</text>
							<text v-if="item.bed_type=='double'">双床,</text>
							<text v-if="item.bed_type=='big'">大床,</text>
							<text v-if="item.window=='no'">无窗,</text>
							<text v-if="item.window=='part_no'">部分无窗,</text>
							<text v-if="item.window=='inner'">内窗,</text>
							<text v-if="item.window=='part_inner'">部分内窗,</text>
							<text v-if="item.policy_ban_smoking=='0'">不禁烟,</text>
							<text v-if="item.policy_ban_smoking=='1'">禁烟</text>
							<text v-if="item.policy_add_bed=='0'">不可加床</text>
							<text v-if="item.policy_add_bed=='1'">可加床</text>
						</view>
						<view class="hotel-orderLis-item-detail-time">
							下单时间：{{item.created_at}}
						</view>
						<view class="hotel-orderLis-item-detail-time">
							入离日期：{{item.booking_start_date}}--{{item.end_date}}
						</view>
					</view>
					<view class="hotel-orderLis-item-detail-price">
						<!-- <text>￥{{item.order_price}}</text> -->
						<text>x{{item.booking_num}}</text>
					</view>
				</view>
				<view class="hotel-orderLis-item-count">
					<text>共{{item.booking_num}}间 合计：</text>
					<text>{{item.pay_price}}现金+{{item.integral_deduction_price}}{{currencyAlias.red_envelope_alias}}</text>
				</view>
				<view class="hotel-orderLis-statusDetail">
					<text v-if="item.is_payable==1">去支付</text>				
					<text v-if="item.is_cancelable==1" @click.stop="cancleOrder(item.order_id)">取消订单</text>
					<!-- <text v-if="item.is_refundable==1">退款/售后</text> -->
				</view>
			</view>
		</view>
		<view class="no-more" v-if="orderList.length==0">
			<image :src="img_url+'/hotel/hotel_loading.png'" mode=""></image>
			<text>暂无订单信息哦</text>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				list:[
					{
						title:'全部',
						status:''
					},
					{
						title:'待确认',
						status:'unconfirmed'
					},
					{
						title:'待付款',
						status:'unpaid'
					},
					{
						title:'已确认',
						status:'confirmed'
					},
					{
						title:'已结束',
						status:'finished'
					},
					{
						title:'退款/售后',
						status:'refund'
					}
				],
				switchIndex:0,
				form:{
					page:1,
					status:'',//状态：finished已结束，refund售后，cancel已取消，confirmed已确认，unpaid待付款，unconfirmed待确认
				},
				orderList:[],
				page_count:0,//总页数
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		onLoad() {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			this.getOrderList()
		},
		methods:{
			checkIndex(index,status){//选择状态
				this.switchIndex=index
				this.form.status=status
				this.orderList=[]
				this.form.page=1
				this.getOrderList()
			},
			linkTo(id){//跳转到订单详情
				uni.navigateTo({
					url:'../hotelDetail/hotelDetail?hotel_order_id='+id
				})
			},
			getOrderList(){//获取订单列表
				this.$http
					.request({
						url: this.$api.hotel.getOrderList,
						method: 'POST',
						data:this.form,
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							if(res.data.list.length==0)return false
							let list= res.data.list;
							var arr=this.orderList.concat(list)
							this.orderList =arr
							this.page_count = res.data.pagination.page_count;
						}else{
							this.$http.toast(res.msg);
						}
				});
			},
			cancleOrder(order_id){//取消订单
				this.$http
					.request({
						url: this.$api.hotel.cancleOrder,
						method: 'POST',
						data:{
							hotel_order_id:order_id
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							this.$http.toast('取消订单成功');
							this.form.page=1
							this.orderList=[]
							this.getOrderList()
						}else{
							this.$http.toast(res.msg);
						}
				});
			}
		},
		onReachBottom() {
			if(this.form.page==this.page_count){
				return false;
			} 		
			this.form.page=this.form.page+1
			this.getOrderList();
		},
	}
</script>

<style lang="less">
	.hotel-orderList-app{width: 100%;overflow: hidden;}
	.hotel-orderList-status{width: 100%;height: 80rpx;display: flex;justify-content: space-evenly;}
	.hotel-orderList-status view{font-size: 26rpx;line-height: 80rpx;text-align: center;}
	.active{color: rgb(255, 113, 4);border-bottom: 6rpx solid  rgb(255, 113, 4);font-weight: bold;}
	.hotel-orderLis{width: 100%;overflow: hidden;margin: 20rpx 0 80rpx 0;}
	.hotel-orderLis-item{width: 96%;overflow: hidden;padding: 20rpx;background: #FFFFFF;margin: 20rpx auto;border-radius: 20rpx;
	 box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.4);}
	.hotel-orderLis-item-title{width: 100%;overflow: hidden;display: flex;justify-content: space-between;}
	.hotel-orderLis-item-title image{width: 100rpx;margin-top: 8rpx;}
	.hotel-orderLis-item-title text{font-size: 26rpx;color: #000;display: inline-block;}
	.hotel-orderLis-item-title text:nth-of-type(1){width: 70%;}
	.hotel-orderLis-item-title text:nth-of-type(2){color: #FF7B10;}
	.hotel-orderLis-item-detail{margin: 25rpx 0 0 0;width: 100%;overflow: hidden;}
	.hotel-orderLis-item-detail-image{width: 150rpx;height: 150rpx;margin-top: 20rpx;float: left;}
	.hotel-orderLis-item-detail-image image{display: block;width: 150rpx;height: 150rpx;}
	.hotel-orderLis-item-detail-message{width: 60%;margin:0 10rpx;overflow: hidden;display: flex;justify-content: space-evenly;flex-wrap: wrap;float: left;}
	.hotel-orderLis-item-detail-message view{width: 100%;margin-bottom: 10rpx;}
	.hotel-orderLis-item-detail-name{font-size: 28rpx;color: #000;
	display: -webkit-box;            
	-webkit-box-orient: vertical;     
	-webkit-line-clamp: 2;          
	overflow: hidden; }
	.hotel-orderLis-item-detail-selectType{font-size: 25rpx;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;overflow: hidden; }
	.hotel-orderLis-item-detail-time{font-size: 25rpx;}
	.hotel-orderLis-item-detail-price{overflow: hidden;width: 15%;float: left;}
	.hotel-orderLis-item-detail-price text{display: block;text-align: right;font-size: 25rpx;color:#FF7B10 ;}
	.hotel-orderLis-item-count{width: 100%;overflow: hidden;text-align: right;margin: 0rpx 0 10rpx 0;}
	.hotel-orderLis-item-count text:nth-of-type(1){font-size: 25rpx;}
	.hotel-orderLis-item-count text:nth-of-type(2){font-size: 35rpx;color: #FF7B10;font-weight: bold;}
	.hotel-orderLis-statusDetail{width: 100%;overflow: hidden;}
	.hotel-orderLis-statusDetail text{display: block;float: right;width: 150rpx;height: 60rpx;background:#FF7B10;border-radius: 30rpx;text-align: center;line-height: 58rpx;font-size: 25rpx;color: #fff;margin-right: 10rpx;}
	.no-more{width: 200rpx;height: 280rpx;position: absolute;top: 0;left: 0;right: 0;margin: auto;bottom: 0;
	font-size: 26rpx;text-align: center;color: #808080;}
	.no-more image{width: 200rpx;height: 200rpx;}
</style>
