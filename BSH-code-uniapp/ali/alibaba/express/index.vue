<template>
	<view class="app">
		<block v-if="orderData">
			<view v-for="(item,index) in orderData.detail" :key="index">
				<view class="order">
					<list-cell padding="0">
						<view class="jx-goods-item">
							<image :src="item.cover_url" lazy-load="true" class="jx-goods-img"></image>
							<view class="jx-goods-center">
								<view class="jx-goods-name">{{item.name}}</view>
								<view class="jx-goods-attr">{{item.sku_labels[0]}}</view>
							</view>
						</view>
					</list-cell>
				</view>
			</view>
		
		<view class="order-num" v-if="expressDate.order">物流单号：{{expressDate.order.express_no}}</view>
		</block>
		<!-- <block v-if="orderData.express_no"> -->
		<block>
			<view class="logistics-overview">
				<view class="center">
					<view class="circle-box flex flex-y-center flex-x-center"   v-for="(itemS,indexS) in status_list" :style="{left:distance(indexS)}">
						<view class="circle" :class="indexS==status_index?'active':'actove'"></view>
						<view class="status" :class="indexS==status_index?'activestatus':'actovestatus'">{{itemS}}</view>
					</view>
				</view>
			</view>
			<block v-if="logistics_data">
				<view class="logistics-msg" v-if="logistics_data.Success">
					<view v-if="!logistics_data.Traces" class="logisticsOrder">
						{{logistics_data.State==-1?'单号或快递公司代码错误':logistics_data.State=='4'?'问题件':logistics_data.State=='5'?'疑难件':logistics_data.State=='6'?'退件签收':''}}
					</view>
					<view v-else class="box" :class="{boxs:indexT == logistics_data.Traces.length-1}" v-for="(itemT,indexT) in logistics_data.Traces">
						<view class="flex flex-y-center" style="height: 170rpx;">
							<view class="left">
								<view :class="indexT==0?'styleColorTime':'time'">{{time(itemT.AcceptTime)}}</view>
								<view :class="indexT==0?'styleColorDate':'date'">{{date(itemT.AcceptTime)}}</view>
							</view>
							<view :class="indexT==0?'styleColorRight':'right'">{{itemT.AcceptStation}}</view>
						</view>
					</view>
				</view>
				<view class="logisticsOrder" v-else>
					暂不支持查询{{expressDate.order.express}}的物流信息
				</view>
			</block>
		</block>
		<!-- <view class="nomore" v-else>暂无物流信息</view> -->
		
	</view>
</template>

<script>
	import ListCell from "@/components/list-cell/list-cell"
	export default {
		components: {
			ListCell
		},
		data() {
			return {
				orderData: '',
				status_list:['待发货','已发货','已揽件','运输中','已签收'],
				status_index:null,
				logistics_data:'',
				logistics_order:'',
				textColor:'',
				expressDate:''
			}
		},
		computed:{
			distance(){
				return function(index){
					var left = 154 * index;
					return left + 'rpx';
				}
			},
			date(){
				return function(str){
					return str.split(' ')[0];
				}
			},
			time(){
				return function(str){
					return str.split(' ')[1];
				}
			}
		},
		onLoad(options) {
			this.textColor = this.globalSet('textCol');
			// 初始化数据
			if (options&&options.id) {
				this.getaliDetail(options.id)
			}
			if (options&&options.order_id) {
				this.getexpress(options.order_id)
			}
		},
		methods: {
			getaliDetail(id){
				this.$http.request({
					url: this.$api.taolijin.getOrderdetail,
					method: 'POST',
					data: {
						order_id:id
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.orderData=res.data
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			getexpress(order_id){
				this.$http.request({
					url: this.$api.taolijin.getexpress,
					method: 'POST',
					data: {
						id_1688:order_id
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.expressDate=res.data
						this.logistics_data = res.data.express.list;
						if(this.expressDate.order.express_no.length>0){
							this.status_index=1
						}
						if(this.logistics_data.State=='1'){
							this.status_index=2
						}
						if(this.logistics_data.State=='2'){
							this.status_index=3
						}
						if(this.logistics_data.State=='3'){
							this.status_index=4
						}
						this.logistics_data.Traces.reverse();
					} else {
						this.$http.toast(res.msg);
					}
				});
			},		
			getlogistics(obj){
				this.$http.request({
					url: this.$api.express.query,
					showLoading:true,
					data:{
						express_no:obj.express_no,
						express_code:obj.express_code,
						express:obj.express,
						mobile:obj.mobile,
					}
				}).then(res => {
					if(res.code == 0){
						this.logistics_data = res.data.express.list;
						this.logistics_order = res.data.order;
						if(this.logistics_order.express_no.length>0){
							this.status_index=1
						}
						if(this.logistics_data.State=='1'){
							this.status_index=2
						}
						if(this.logistics_data.State=='2'){
							this.status_index=3
						}
						if(this.logistics_data.State=='3'){
							this.status_index=4
						}
						this.logistics_data.Traces.reverse();
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.app{
		min-height: 100%;
		background-color: #FFFFFF;
	}
	.jx-goods-item {
		width: 100%;
		padding: 20rpx 30rpx;
		box-sizing: border-box;
		display: flex;
		justify-content: space-between;

		.jx-goods-img {
			width: 180rpx;
			height: 180rpx;
			display: block;
			flex-shrink: 0;
		}

		.jx-goods-center {
			flex: 1;
			padding: 0 20rpx;
			box-sizing: border-box;

			.jx-goods-name {
				// max-width: 310rpx;
				word-break: break-all;
				overflow: hidden;
				text-overflow: ellipsis;
				display: -webkit-box;
				-webkit-box-orient: vertical;
				-webkit-line-clamp: 2;
				font-size: 11pt;
				line-height: 32rpx;
			}

			.jx-goods-attr {
				font-size: 10pt;
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
		}
	}
	
	
	.logistics-overview{
		padding: 82rpx 55rpx;
		
		.center{
			margin: 26rpx 0rpx;
			height: 6rpx;
			background: #999;
			position: relative;
			
			.circle-box{
				width: 30rpx;
				height: 30rpx;
				background: #fff;
				position: absolute;
				top: 50%;
				left: 0;
				transform: translateY(-50%);
				
				.circle{
					width: 20rpx;
					height: 20rpx;
					border-radius: 50%;
					background: #999;
				}
				.active{
					width: 20rpx;
					height: 20rpx;
					border-radius: 50%;
					background: #16AB60;
				}
				.actove{
					width: 20rpx;
					height: 20rpx;
					border-radius: 50%;
					background: #999;
				}
				
				
				
				.status{
					width: 120rpx;
					letter-spacing: 2rpx;
					font-size: 26rpx;
					color: #999;
					position: absolute;
					top: -80rpx;
					text-align: center;
				}
				.activestatus{
					width: 120rpx;
					letter-spacing: 2rpx;
					font-size: 26rpx;
					color: #16AB60;
					position: absolute;
					top: -80rpx;
					text-align: center;
				}
				.actovestatus{
					width: 120rpx;
					letter-spacing: 2rpx;
					font-size: 26rpx;
					color: #999;
					position: absolute;
					top: -80rpx;
					text-align: center;
				}
				.col{
					background: var(--textColor);
					color: #FFFFFF !important;
				}
				.text-col{
					background: var(--textColor);
					color: #FFFFFF;
					font-size: 28rpx;
					border-radius: 30rpx;
					width: 100rpx;
					text-align: center;
					height: 50rpx;
					line-height: 50rpx;
					top: -70rpx;
					letter-spacing: 2rpx;
					
					&::after{
						content: '';
						display: block;
						position: absolute;
						top: 48rpx;
						left: 50%;
						transform: translateX(-50%);
						border-top: 14rpx solid var(--textColor);
						border-bottom: 14rpx solid transparent;
						border-left: 14rpx solid transparent;
						border-right: 14rpx solid transparent;
					}
				}
				.city{
					font-size: 26rpx;
					color: #000;
					width: 96rpx;
					position: absolute;
					top: 50rpx;
					letter-spacing: 2rpx;
				}
			}
		}
	}	
	
	.logistics-msg{
		padding: 20rpx 38rpx;
		
		.box{
			&::after{
				content: '';
				display: block;
				background: #ccc;
				height: 80rpx;
				width: 4rpx;
				left: 50%;
				transform: translateX(-50%);
				// margin: 30rpx 0 30rpx 60rpx;
				margin-left: 60rpx;
			}
		}
		.boxs{
			overflow: hidden;
			&::after{
				content: '';
				height: 0rpx;
				width: 0rpx;
			}
		}
		
		.left{
			text-align: center;
			margin-right: 30rpx;
			width: 140rpx;
			
			.time{
				font-size: 30rpx;
				color: #000;
			}
			.date{
				font-size: 20rpx;
				color: #999;
			}
			.styleColorTime{
				font-size: 30rpx;
				color:#16AB60;
			}
			.styleColorDate{
				font-size: 20rpx;
				color: #16AB60;
			}
		}
		.right{
			color: #000;
			font-size: 25rpx;
			letter-spacing: 2rpx;
			flex: 1;
		}
		.styleColorRight{
			color: #16AB60;
			font-size: 25rpx;
			letter-spacing: 2rpx;
			flex: 1;
		}
	}
	
	.order-num{
		padding: 20rpx 30rpx 30rpx;
		font-size: 30rpx;
	}
	
	.nomore{
		text-align: center;
		margin-top: 100rpx;
	}
	.logisticsOrder{
		text-align: center;
		letter-spacing: 1px;
	}
	.acive{
		background: #16AB60;
	}
</style>
