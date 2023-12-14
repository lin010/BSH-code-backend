<template>
	<view class="becused-app">
		<view class="becused-table">
			<view v-for="(item,index) in table" :key='index' @click="tabchange(index)">
				<text :class="activeIndex==index?'active':''">{{item}}</text>
			</view>
		</view>
		<view :class="{'tui-order-list':scrollTop>=0}" v-if="dataList && dataList.length">
			<view class="tui-order-item" v-for="(model,orderIndex) in dataList" :key="orderIndex">
				<view>
					<tui-list-cell :hover="false" :lineLeft="false" padding="26rpx 20rpx">
						<view class="tui-goods-title">
							<view class="logo" @tap="toShop(model.mch_info.id)">
								<!-- <span :style="`background-image:url(${})`"></span> -->
								<image class="img" lazy-load="true" 
								:src="model.mch_info.cover_url?model.mch_info.cover_url:url+'/shoplogo.png'" 
								mode="aspectFill"></image>
								<span class="name">{{model.mch_info.name?model.mch_info.name:'兰亭官方商城'}}</span>
								<view class="toright"></view>
							</view>
							<!-- <view class="tui-order-status" :style="{color:textColor}">{{model.status_text}}</view> -->
						</view>
					</tui-list-cell>
					<block v-for="(item,index) in model.detail" :key="index">
						<tui-list-cell padding="0"  @click="detail(model.id,model.order_type)">
							<view class="tui-goods-item">
								<image :src="item.goods_info.pic_url" lazy-load="true" class="tui-goods-img"></image>
								<view class="tui-goods-center">
									<view class="tui-goods-name">{{item.goods_info.name}}</view>
									<view class="tui-goods-attr">{{item.goods_info.attr_list[0].attr_name}}</view>
								</view>
								<view class="tui-price-right">
									<view>¥{{item.goods_info.total_original_price}}</view>
									<view>x{{item.goods_info.num}}</view>
								<!-- 	<view style="margin-top:30rpx;" v-if="item.refund_status == 10">售后待处理</view>
									<view style="margin-top:30rpx;" v-else-if="item.refund_status == 11">退款已同意</view>
									<view style="margin-top:30rpx;" v-else-if="item.refund_status == 12">退款退货中</view>
									<view style="margin-top:30rpx;" v-else-if="item.refund_status == 20">已退款</view>
									<view style="margin-top:30rpx;" v-else-if="item.refund_status == 21">退款已拒绝</view> -->
								</view>
							</view>
						</tui-list-cell>
					</block>
					<tui-list-cell :hover="false" :last="true">
						<view class="tui-goods-price">
							<view>共{{model.detail | totalNum}}件商品 合计：</view>
							<view class="tui-size-24">¥</view>
							<view class="tui-price-large">{{model.total_pay_price.slice(0,-3)}}</view>
							<view class="tui-size-24">{{model.total_pay_price.slice(-3)}}</view>
						</view>
					</tui-list-cell>
				</view>
				<view class="tui-order-btn">
					<view class="tui-btn-ml" v-if="activeIndex == 0">
						<view class="btn-style" :style="{color:'#FF7104',border:'1px solid '+'#FF7104'}" @click.stop="detail(model.id,model.order_type)">去使用</view>
					</view>
					<view class="tui-btn-ml" v-if="activeIndex == 1">
						<view class="btn-style btn-gary" :style="{color:'#FF7104',border:'1px solid '+'#FF7104'}"  @click.stop="cancel(model.id,model.order_type)">查看详情</view>
					</view>
				</view>
			</view>
		</view>
		<view class="order-nothing" v-else>
			<image class="order-nothing-img" :src="img_url+'images/order/order-nothing.png'" mode=""></image>
			<view class="order-nothing-text">目前没有订单哦~</view>
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
				table:[
					"未使用","已使用"
				],
				activeIndex:0,
				img_url: this.$api.img_url,
				url:this.$api.img_url,
				textColor:'#bc0100',
				tabBar: null,
				tabs: [{
					name: "全部"
				}, {
					name: "待付款"
				}, {
					name: "待发货"
				}, {
					name: "待收货"
				}, {
					name: "待评价"
				}],
				showTab: {
					'-1': 0,
					'0': 1,
					'1': 2,
					'2': 3,
					'3': 4
				},
				status: 0,
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
			};
		},
		onLoad(options) {
			console.log(this.url)
			
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
			this.getDateList('refresh')
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
			data_list(){
				if(this.status == -1){
					return this.dataList;
				}
				return this.dataList.filter(item => item.status == this.status);
			}
		},
		methods:{
			tabchange(index){//table切换
				this.activeIndex=index
				let sum = 0;
				this.getDateList('refresh')
			},
			toShop(id){
				uni.navigateTo({
					url:'../../../merchants/detail/detail?store_id='+id
				})
			},
			toPage(orderId,oddNum){
				uni.navigateTo({
					url: `./logistics/index?orderId=${orderId}&odd=${oddNum}`
				})
			},
			// 通过 key 和 status 判断通过何种方式修改数据
			getDateList(key) {
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
				let {
					current_page,
					pageSize
				} = this.pages;
				this.dataList = this.dataList ? this.dataList : [];
				this.$http.request({
					url: this.$api.order.list,
					method: 'POST',
					data: {
						page: current_page,
						limit: pageSize,
						offline:1,//传1获取核销订单，否则只返回寄送订单
						offline_used:this.activeIndex,//是否已核销，0否1是
					}
				}).then(res => {
					this.loading = false;
					if (res.code === 0) {
						let {
							list,
							pagination
						} = res.data;
						
						this.dataList = key != 'refresh' ? this.dataList.concat(list) : list; // key != refresh 则是加载下一页数据
						this.pages = pagination;
						this.pullUpOn = true;
					}
				})
			},
			detail(id,type) {
				console.log(id, type);
				if(type=='offline_baopin'){
					uni.navigateTo({
						url: `/pages/verification/canceldetail/canceldetail?id=${id}`
					})
				}else{
					uni.navigateTo({
						url: `/pages/order/detail?orderId=${id}`
					})
				}

			},
			cancel(id,type){
				if(type=='offline_baopin'){
					uni.navigateTo({
						url: `/pages/verification/canceldetail/canceldetail?id=${id}`
					})
				}else{
					uni.navigateTo({
						url: `/pages/order/detail?orderId=${id}`
					})
				}
			}
		},
		onPullDownRefresh() {
			setTimeout(() => {
				uni.stopPullDownRefresh()
				this.getDateList('refresh')
			}, 1000);
		},
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

<style lang="less">
	.becused-app{width: 100%;overflow: hidden;}
	.becused-table{width: 100%;height: 80rpx;padding: 0px 15px;
	background: rgb(247, 247, 247);display: flex;justify-content: space-between;}
	.becused-table view{height: 88rpx;width: 40%;text-align: center;line-height: 88rpx;}
	.becused-table view text{display: block;width: 40%;height: 100%;margin: 0 auto ;}
	.active{color: #FF7104; border-bottom: 5rpx solid  #FF7104;font-weight: bold;}
	.toright{
		width: 8px;
		height: 8px;
		border-top: 1px #999 solid;
		border-right: 1px #999 solid;
		transform: rotate(45deg);
		margin-left: 5px;
	}
	.container {
		padding-bottom: env(safe-area-inset-bottom);
		background-color: #F7F7F7;
		padding: 0 24rpx;
		height: 100%;
	}
	
	.tui-order-list {
		margin-top: 40rpx;
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
	        align-items: center;
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
		color: #BC0100;
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
	}
	.tui-color-red {
		color: #E41F19;
		padding-right: 30rpx;
	}
	
	.tui-goods-price {
		width: 100%;
		display: flex;
		align-items: flex-end;
		justify-content: flex-end;
		font-size: 9pt;
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
	
	.tui-order-btn {
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: flex-end;
		background: #fff;
		padding: 10rpx 30rpx 20rpx;
		box-sizing: border-box;
		z-index: 5;
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
		color: #EB0909 ;
		border-radius: 100rpx;
		border: 1px solid #EB0909;
		font-size: 28rpx;
		padding: 4rpx 24rpx;
	}
	.btn-gary{
		color: #8F8D8E !important;
		border: 1px solid #8F8D8E !important;
	}
</style>
