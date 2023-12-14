<!--  这个是拼团订单列表  -->
<template>
	<view class="container">
		<com-tabs :tabs="tabs" :isFixed="scrollTop>=0" :currentTab="showTab[status]" :selectedColor="textColor" :sliderBgColor="textColor"
		 :sliderHeight="4" :sliderWidth="50" bgColor="#FFF" @change="change" bottom="10rpx">
		 </com-tabs>
		 
		<!--选项卡逻辑自己实现即可，此处未做处理-->
		<view :class="{'tui-order-list':scrollTop>=0}" v-if="dataList && dataList.length">
			<view class="tui-order-item" v-for="(model,orderIndex) in data_list" :key="orderIndex">
				<view @click="detail(model.id)">
					<tui-list-cell :hover="false" :lineLeft="false" padding="26rpx 20rpx">
						<view class="tui-goods-title">
							<view class="logo">
								<!-- <span :style="`background-image:url(${})`"></span> -->
								<image class="img" lazy-load="true" src="http://jxmall.sinbel.cn/web/statics/img/admin/user.png" mode="aspectFill"></image>
								<span class="name">http://www.kemean.cn/</span>
							</view>
							<view class="tui-order-status" :style="{color:textColor}">{{model.status_text}}</view>
						</view>
					</tui-list-cell>
					<block v-for="(item,index) in model.detail" :key="index">
						<tui-list-cell padding="0">
							<view class="tui-goods-item">
								<image :src="item.goods_info.pic_url" lazy-load="true" class="tui-goods-img"></image>
								<view class="tui-goods-center">
									<view class="tui-goods-name">{{item.goods_info.name}}</view>
									<view class="tui-goods-attr">{{item.goods_info.attr_list[0].attr_name}}</view>
								</view>
								<view class="tui-price-right">
									<view class="group_price" :style="{color:textColor}">¥{{item.goods_info.total_original_price}}</view>
									<view class="group_num">x{{item.goods_info.num}}</view>
									<!-- <view v-if="item.refund_status > 0 && '578’.indexOf(model.status) == -1">{{item.refund_status==1 ? '售后中' : '售后完成'}}</view> -->
									
									<view v-if="item.refund_status > 0 && !undone">{{item.refund_status==1 ? '售后中' : '售后完成'}}</view>
									<!-- <view v-if="item.refund_status > 0 && '8'.indexOf(model.status) == -1 ">{{'已退款'}}</view>
									<view v-if="item.refund_status > 0 && '8'.indexOf(model.status) == -1 ">{{'已退货退款'}}</view> -->
									<view style="margin-top:30rpx;" v-if="item.diy_refund_status == 1">退款中</view>
									<view style="margin-top:30rpx;" v-else-if="item.diy_refund_status == 2">已退款</view>
									<view style="margin-top:30rpx;" v-else-if="item.diy_refund_status == 3">退款退货中</view>
									<view style="margin-top:30rpx;" v-else-if="item.diy_refund_status == 4">已退款退货</view>
									<view style="margin-top:30rpx;" v-else-if="item.diy_refund_status == 5">换货中</view>
									<view style="margin-top:30rpx;" v-else-if="item.diy_refund_status == 6">换货完成</view>
									<!-- && item.refund.type == 0
									&& item.refund.type == 1 -->
								</view>
							</view>
						</tui-list-cell>
					</block>
					<tui-list-cell :hover="false" :last="true">
						<view class="tui-goods-price">
							<!-- <view>总价¥{{model.total_goods_price}},优惠¥{{model.coupon_discount_price}} 需付款</view> -->
							<view>实付款</view>
							<!-- <view>共{{model.detail | totalNum}}件商品 合计：</view> -->
							<!-- <view class="tui-size-24">¥{{model.total_goods_price}}</view> -->
							<view class="tui-price-large">{{model.total_pay_price.slice(0,-3)}}</view>
							<view class="tui-size-24">{{model.total_pay_price.slice(-3)}}</view>
						</view>
					</tui-list-cell>
				</view>
				<view class="order-bottom">
					<!-- <view class="order-left">24小时后订单关闭</view> -->
					<view class="order-left"></view>
					<view class="tui-order-btn">
						<view class="tui-btn-ml" v-if="model.status == 5">
							<view class="btn-style" :style="{border:'1px solid'+textColor,color:textColor}" @click.stop="deleteOrderById(model.id)">删除订单</view>
						</view>
						<view class="tui-btn-ml" v-if="status == -1">
							<view class="btn-style btn-gary" :style="{border:'1px solid'+textColor,color:textColor}" @click.stop="cancel(model.id)">取消订单</view>
						</view>
						<view class="tui-btn-ml" v-if="status == -1">
							<view class="btn-style" :style="{border:'1px solid'+textColor,color:textColor}" @click.stop="pay(model.id,model.active.id)">去付款</view>
						</view>
						<view class="tui-btn-ml" v-if="status == 0">
							<view class="btn-style" :style="{border:'1px solid'+textColor,color:textColor}" @click.stop="toShare(model.id,model.active.id,model.active.goods_id)">分享拼团</view>
						</view>
						<view class="tui-btn-ml" v-if="status == 1">
							<view class="btn-style" :style="{border:'1px solid'+textColor,color:textColor}" @click.stop="remindSendById(model.id)">提醒发货</view>
						</view>
						
						<!-- <view class="tui-btn-ml" v-if="model.status == (2 || 3)">
							<tui-button type="black" :plain="true" width="148rpx" height="56rpx" :size="26" shape="circle">加入购物车</tui-button>
						</view> -->
						<view class="tui-btn-ml" v-if="model.status > 1 && model.status != 5 && model.status != 8">
							<view class="btn-style btn-gary" :style="{border:'1px solid'+textColor,color:textColor}" @click.stop="toPage(model.id)">查看物流</view>
						</view>
						<view class="tui-btn-ml" v-if="model.status == 2">
							<view class="btn-style" :style="{border:'1px solid'+textColor,color:textColor}" @click.stop="confirm(model.id)">确认收货</view>
						</view>
						<view class="tui-btn-ml" v-if="model.status == 3">
							<view class="btn-style" :style="{border:'1px solid'+textColor,color:textColor}" @click.stop="goComment(model.id)">去评价</view>
						</view>
					</view>
				
				</view>
			</view>
		</view>
		<view class="order-nothing" v-else>
			<image class="order-nothing-img" :src="img_url+'images/order/order-nothing.png'" mode=""></image>
			<view class="order-nothing-text">目前没有{{ status ? tabs[status+1].name : ""}}订单哦~</view>
		</view>
		<!--加载loadding-->
		<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="!pullUpOn" bgcolor="#fafafa"></main-nomore>
		<main-loading :visible="loading"></main-loading>
		<!--加载loadding-->

		<main-tabbar></main-tabbar>
	</view>
</template>

<script>
	import tuiButton from "@/components/extend/button/button"
	import tuiListCell from "@/components/list-cell/list-cell"
	export default {
		components: {
			tuiButton,
			tuiListCell,
		},
		data() {
			return {
				img_url: this.$api.img_url,
				tabBar: null,
				tabs: [{
					name: "待付款"
				}, {
					name: "待分享"
				}, {
					name: "待发货"
				}, {
					name: "待收货"
				}, 
				{
					name: "待评价"
				},
				{
					name: "已完成"
				},
				],
				statusObj: {
					0: -1,
					1: 0,
					2: 1,
					3: 2,
					4: 3,
					5: 4,	//已完成状态是8
				},
				showTab: {
					'-1': 0,
					'0': 1,
					'1': 2,
					'2': 3,
					'3': 4,
					'4': 5,
				},
				status: -1,
				loadding: false,
				pullUpOn: true,
				loading: false,
				scrollTop: 0,
				dataList: [],
				pages: {
					current_page: 1,
					pageSize: 20,
					page_count: 1,
					total_count: 0
				},
				active_status : '',
				textColor : '#bc0100',	//全局颜色
			}
		},
		onLoad(options) {
			this.textColor = this.globalSet('textCol');
			// if (options.status) {
			// 	this.status = Number(options.status);
			// }
			// 默认查看全部订单
			this.status=-1;
			this.getDateList('refresh', this.status);
		},
		computed:{
			undone(){
				return (order_state) => {
					if(order_state == 5 || order_state == 7 || order_state == 8){
						return false;
					}else{
						return true;
					}
				}
			},
			// 返回列表数据
			data_list(){
				if(this.status == -1){
					return this.dataList;
				}
				let status = this.status;
				//这里也对应的状态码改变一下
				if(status<1){
					status=status+1
				}else if(status==4){
					status=8;
				}	
				return this.dataList.filter(item => item.status == status);
			}
		},
		methods: {
			// 跳转订单详情
			toPage(orderId,oddNum){
				uni.navigateTo({
					url: `./logistics/index?orderId=${orderId}&odd=${oddNum}`
				})
			},
			// 跳转拼团详情页-带拼团id&商品id
			toShare(id,detailId,goods_id){
				uni.navigateTo({
				    url: '/mch/group-buy/detail?detailId='+detailId+'&goods_id='+goods_id
				});
			},
			
			// 提现发货
			remindSendById(id) {
				this.$http.request({
					url: this.$api.order.remindSend,
					method: 'POST',
					data: {
						order_id: id
					},
					showLoading: true
				}).then(res => {
					this.$http.toast(res.msg);
				})
			},
			// 删除
			deleteOrderById(id) {
				this.$http.request({
					url: this.$api.order.deleteOrder,
					method: 'POST',
					data: {
						order_id: id
					},
					showLoading: true
				}).then(res => {
					this.$http.toast(res.msg);
					if (res.code === 0) {
						this.getDateList('refresh', this.status)
					}
				})
			},
			// 评价
			goComment(id) {
				console.log(789);
				uni.navigateTo({
					url:'/pages/order/comment/edit?id='+id
				})
			},
			// 拼团支付
			pay(id,active_id) {
				// console.log("支付", id);
				uni.navigateTo({
				    url: '/pages/order/pay?orderId='+id+'&is_index=1&active_id='+active_id
				});
			},
			// 确认收货
			confirm(id) {
				this.$http.request({
					url: this.$api.order.confirm,
					method: 'POST',
					data: {
						order_id: id
					}
				}).then(res => {
					this.$http.toast(res.msg);
					if (res.code === 0) {
						this.getDateList('refresh', this.status)
					}
				})
			},
			// 取消订单
			cancel(id) {
				this.$http.request({
					url: this.$api.order.cancel,
					method: 'POST',
					data: {
						order_id: id
					}
				}).then(res => {
					this.$http.toast(res.msg);
					if (res.code === 0) {
						this.getDateList('refresh', this.status)
					}
				})
			},
			// 通过 key 和 status 判断通过何种方式修改数据
			getDateList(key, status) {
				console.log(status);
				this.loading = true;
				// 如果 key == 'refresh' 重置数据
				if(key == 'refresh'){
					this.pages = {
						current_page: 1,
						pageSize: 20,
						page_count: 1,
						total_count: 0
					}
				}
				// status = status == -1 ? '' : status; // 判断查询条件是否存在 不存在则为 -1
				let {
					current_page,	//第几页
					pageSize		//页容量
				} = this.pages;
				let postData = {
					status,
					page: current_page,
					limit: pageSize
				}
				if(status==-1){
					postData.status=postData.status+1;
				}else if(status==0){
					postData.status=postData.status+1;
					postData['active_status']=1;
					this.active_status = 1;
				}else if(status==1){
					postData['active_status']=2;
				}else if(status==4){
					postData.status=8;
				}
				// status==0?postData['active_status']=1:'';
				this.dataList = this.dataList ? this.dataList : [];
				this.$http.request({
					url: this.$api.plugin.group.group_order_list,
					// url: this.$api.order.list,		
					method: 'POST',
					data: postData
				}).then(res => {
					this.loading = false;
					if (res.code === 0) {
						let {
							list,
							pagination
						} = res.data;
						// console.log(list);
						this.dataList = key != 'refresh' ? this.dataList.concat(list) : list; // key != refresh 则是加载下一页数据
						this.pages = pagination;
						this.pullUpOn = true;
					}
				})
			},
			
			// tab点击的change事件
			change(e) {
				this.status = this.statusObj[e.index]
				let sum = 0;
				this.getDateList('refresh', this.status)
			},
			
			detail(id) {
				uni.navigateTo({
					url: '/pages/order/detail?orderId='+id+'&active_status='+this.active_status
				})
			}
		},
		
		/*下拉刷新 */ 
		onPullDownRefresh() {
			setTimeout(() => {
				uni.stopPullDownRefresh()
				this.getDateList('refresh', this.status)
			}, 1000);
		},
		/* 页面触底加载 */ 
		onReachBottom() {
			//只是测试效果，逻辑以实际数据为准
			this.loadding = true
			this.pullUpOn = true

			let {
				current_page,
				page_count
			} = this.pages;
			setTimeout(() => {
				this.loadding = false
				if (current_page >= page_count){
					this.pullUpOn = false;
					return;
				}
				this.pages.current_page++;
				this.getDateList();
			}, 1000)
		},
		onPageScroll(e) {
			this.scrollTop = e.scrollTop;
		},
		filters: {
			totalNum(value) {
				let sum = 0;
				value.forEach((v, i) => {
					sum += v.num;
				})
				return sum;
			}
		}
	}
</script>

<style lang="scss" scoped>
	page {
		background-color: #F7F7F7;
	}

	.container {
		padding-bottom: env(safe-area-inset-bottom);
		background-color: #F7F7F7;
		padding: 0 24rpx;
		height: 100%;
	}

	.tui-order-list {
		margin-top: 80rpx;
	}

	.tui-order-item {
		margin-top: 20rpx;
		border-radius: 30rpx;
		overflow: hidden;
	}

	.tui-goods-title {
		width: 100%;
		font-size: 11pt;
		display: flex;
		align-items: center;
		justify-content: space-between;

		.logo {
			display: flex;

			.img {
				width: 40rpx;
				height: 40rpx;
				margin-right: 20rpx;
				border-radius: 10rpx;
			}

			.name {
				color: #1E1E1E;
				font-size: 11pt;
			}
		}
	}

	.tui-order-status {
		font-size: 9pt;
	}

	.tui-goods-item {
		width: 100%;
		padding: 20rpx 30rpx;
		box-sizing: border-box;
		display: flex;
		justify-content: space-between;
	}

	.tui-goods-img {
		width: 180rpx;
		height: 180rpx;
		display: block;
		flex-shrink: 0;
	}

	.tui-goods-center {
		flex: 1;
		padding: 20rpx;
		box-sizing: border-box;
	}

	.tui-goods-name {
		max-width: 310rpx;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
		font-size: 9pt;
		line-height: 32rpx;
	}

	.tui-goods-attr {
		font-size: 9pt;
		color: #888888;
		line-height: 32rpx;
		padding-top: 20rpx;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
	}

	.tui-price-right {
		text-align: right;
		font-size: 9pt;
		color: #888888;
		line-height: 30rpx;
		padding-top: 20rpx;
		.group_price{
			// color: #bc0100;
		}
		.group_num{
			color: #1e1e1e;
		}
	}

	.tui-goods-price {
		width: 100%;
		display: flex;
		align-items: flex-end;
		justify-content: flex-end;
		font-size: 9pt;
		line-height: 24rpx;
	}

	.tui-size-24 {
		font-size: 9pt;
		line-height: 24rpx;
	}

	.tui-price-large {
		font-size: 12pt;
		line-height: 30rpx;
		font-weight: 500;
	}
	.order-bottom{
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: space-between;
		background: #fff;
		padding: 10rpx 30rpx 20rpx;
		box-sizing: border-box;
		z-index: 5;
		.order-left{
			font-size: 26rpx;
			font-family: Source Han Sans CN Regular, Source Han Sans CN Regular-Regular;
			font-weight: 400;
			text-align: left;
			color: #bc0100;
			line-height: 60rpx;
			letter-spacing: 2rpx;
		}
		
		.tui-order-btn {
			// width: 100%;
			display: flex;
			align-items: center;
			justify-content: flex-end;
		}
	}

	.tui-btn-ml {
		margin-left: 20rpx;
	}

	// 缺省页
	.order-nothing {
		margin-top: 200rpx;
		height: 100%;
		display: flex;
		flex-direction: column;
		align-items: center;
	}

	.order-nothing-img {
		width: 187rpx;
		height: 187rpx;
		margin-bottom: 35rpx;
	}

	.order-nothing-text {
		font-size: 10pt;
		color: #808080;
	}
	
	.btn-style{
		border-radius: 100rpx;
		font-size: 28rpx;
		padding: 4rpx 24rpx;
	}
	.btn-gary{
		color: #8F8D8E;
		border: 1px solid #8F8D8E;
	}
</style>
