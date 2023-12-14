<template>
	<view class="container">
		<view class="tui-searchbox">
			<view class="tui-search-input">
				<!-- #ifdef APP-PLUS || MP -->
				<icon type="search" :size='13' color='#333'></icon>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<view>
					<com-icons type="search" :size='16' color='#333333'></com-icons>
				</view>
				<!-- #endif -->
				<input type="search" placeholder="请输入商品名"  placeholder-class="tui-input-plholder"
				 class="tui-input" v-model.trim="key" @confirm='search'/>
				<!-- #ifdef APP-PLUS || MP -->
				<icon type="clear" :size='13' color='#bcbcbc' @tap="cleanKey" v-show="key"></icon>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<view @tap="cleanKey" v-show="key"><tui-icon name="close-fill" :size='16' color='#bcbcbc'></tui-icon></view>
				<!-- #endif -->
			</view>
			<view class="tui-cancle" @tap="search">搜索</view>
		</view>
		
		
		
		
		
		
		
		
		

		<com-tabs :tabs="tabs"  :currentTab="showTab[status]" selectedColor="#FF7104" sliderBgColor="#FF7104"
		 :sliderHeight="4" :sliderWidth="50" bgColor="#F7F7F7" @change="change" bottom="10rpx" style="z-index:9999;"></com-tabs>
		<!--选项卡逻辑自己实现即可，此处未做处理-->
		<view class="tui-order-list" v-if="dataList && dataList.length">
			<view class="tui-order-item" v-for="(model,orderIndex) in data_list" :key="orderIndex">
				<view>
					<tui-list-cell :hover="false" :lineLeft="false" padding="26rpx 20rpx">
						<view class="tui-goods-title">
							<view class="logo" @tap="toShop(model.mch_id)">
								<!-- <span :style="`background-image:url(${})`"></span> -->
								<image class="img" lazy-load="true" 
								:src="model.mch_info.cover_url?model.mch_info.cover_url:model.mall_logo" 
								mode="aspectFill"></image>
								<span class="name">{{model.mch_info.name?model.mch_info.name:model.mall_name}}</span>
								<view class="toright"></view>
							</view>
							<view v-if="model.cancel_status == 0" class="tui-order-status" style="color:#FF7104">
								<text v-if="!model.reimburse">{{model.status_text}}</text>
								<text else></text>					
							</view>
							<view v-else class="tui-order-status" style="color:gray">已取消</view>
						</view>
					</tui-list-cell>
					<block v-for="(item,index) in model.detail" :key="index">
						<tui-list-cell padding="0"  @click="detail(model.id)">
							<view class="tui-goods-item">
								<image :src="item.goods_info.pic_url" lazy-load="true" class="tui-goods-img"></image>
								<view class="tui-goods-center">
									<view class="tui-goods-name">{{item.goods_info.name}}</view>
									<view class="tui-goods-attr">{{item.goods_info.attr_list[0].attr_name}}</view>
								</view>
								<view class="tui-price-right">
									<view>¥{{item.goods_info.total_original_price}}</view>
									<view>x{{item.goods_info.num}}</view>
									<view style="margin-top:30rpx;" v-if="item.refund_status == 10">售后待处理</view>
									<view style="margin-top:30rpx;" v-else-if="item.refund_status == 11">退款已同意</view>
									<view style="margin-top:30rpx;" v-else-if="item.refund_status == 12">退款退货中</view>
									<view style="margin-top:30rpx;" v-else-if="item.refund_status == 20">已退款</view>
									<view style="margin-top:30rpx;" v-else-if="item.refund_status == 21">退款已拒绝</view>
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
				<view class="tui-order-btn" v-if="model.cancel_status == 0">
					<view class="tui-btn-ml" v-if="model.status == 5">
						<view class="btn-style" :style="{color:'#FF7104',border:'1px solid '+'#FF7104'}" @click.stop="deleteOrderById(model.id)">删除订单</view>
					</view>
					<view class="tui-btn-ml" v-if="model.status == 0">
						<view class="btn-style btn-gary" :style="{color:'#FF7104',border:'1px solid '+'#FF7104'}"  @click.stop="cancel(model.id)">取消订单</view>
					</view>
					<view class="tui-btn-ml" v-if="model.status == 0">
						<view class="btn-style" :style="{color:'#FF7104',border:'1px solid '+'#FF7104'}"  @click.stop="pay(model.id)">去付款</view>
					</view>
					<view class="tui-btn-ml" v-if="model.status == 1">
						<view class="btn-style" :style="{color:'#FF7104',border:'1px solid '+'#FF7104'}"  @click.stop="remindSendById(model.id)">提醒发货</view>
					</view>
					<!-- <view class="tui-btn-ml" v-if="model.status == (2 || 3)">
						<tui-button type="black" :plain="true" width="148rpx" height="56rpx" :size="26" shape="circle">加入购物车</tui-button>
					</view> -->
					<view class="tui-btn-ml" v-if="model.status > 1 && model.status != 5 && model.status != 8">
						<view class="btn-style btn-gary" :style="{color:textColor,border:'1px solid '+textColor}" @click.stop="toPage(model.id)">查看物流</view>
					</view>
					<view class="tui-btn-ml" v-if="model.status == 2&&!model.reimburse">
						<view class="btn-style" :style="{color:'#FF7104',border:'1px solid '+'#FF7104'}"  @click.stop="confirm(model.id)">确认收货</view>
					</view>
					<view class="tui-btn-ml" v-if="model.status == 3">
						<view class="btn-style" :style="{color:'#FF7104',border:'1px solid '+'#FF7104'}"  @click.stop="goComment(model.id)">去评价</view>
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
	import tuiIcon from "@/components/icon/icon";
	import tuiTag from "@/components/tag/tag";
	import tuiButton from "@/components/extend/button/button"
	import tuiListCell from "@/components/list-cell/list-cell"
	export default {
		components: {
			tuiButton,
			tuiListCell,
			tuiIcon,
			tuiTag,
		},
		data() {
			return {
				key: "",
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
				},
				{
					name: "售后"
				},
				],
				statusObj: {
					0: -1,
					1: 0,
					2: 1,
					3: 2,
					4: 3,
					5: 4,
				},
				showTab: {
					'-1': 0,
					'0': 1,
					'1': 2,
					'2': 3,
					'3': 4,
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
			}
		},
		onLoad(options) {
			console.log(this.url)
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
			if (options.status) {
				this.status = Number(options.status);
			}
			this.getDateList('refresh', options.status)
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
				return this.dataList;
				/* if(this.status == -1){
					return this.dataList;
				}
				return this.dataList.filter(item => item.status == this.status); */
			}
		},
		methods: {
			cleanKey: function() { //清空搜索
				this.key = ''
			},
			search(){
				this.getDateList('refresh', this.status)
			},
			toShop(id){
				if(id){
					uni.navigateTo({
						url:"/pages/shop/home/home?mch_id="+id
					})
				}else{
					uni.navigateTo({
						url:"pages/shop/shop"
					})
				}
			},
			toPage(orderId,oddNum){
				uni.navigateTo({
					url: `./logistics/index?orderId=${orderId}&odd=${oddNum}`
				})
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
				uni.navigateTo({
					url: `./comment/edit?id=${id}`
				})
			},
			// 支付
			pay(id) {
				// console.log("支付", id);
				uni.navigateTo({
					url: `/pages/order/pay?orderId=${id}`
				})
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
					// this.$http.toast(res.msg);
					if (res.code === 0) {
						this.getDateList('refresh', this.status);
						uni.showToast({
						    title: '确认收货成功',
						    duration: 2000,
							icon:'none'
						});
						setTimeout(() => {
							uni.navigateTo({
								url:"/pages/order/list?status=3"
							})
						},1500);
						// this.$http.request({
						// 	url: this.$api.order.ordersales,
						// 	method: 'POST',
						// 	data: {
						// 		order_id: id
						// 	}
						// }).then(res => {
							
						// })
						
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
				status = status == -1 ? '' : status; // 判断查询条件是否存在 不存在则为 -1
				let {
					current_page,
					pageSize
				} = this.pages;
				this.dataList = this.dataList ? this.dataList : [];
				this.$http.request({
					url: this.$api.order.list,
					method: 'POST',
					data: {
						status,
						page: current_page,
						limit: pageSize,
						keywords:this.key
					}
				}).then(res => {
					this.loading = false;
					if (res.code === 0) {
						let {
							list,
							pagination
						} = res.data;
						
						this.dataList = key != 'refresh' ? this.dataList.concat(list) : list; // key != refresh 则是加载下一页数据
						for(let i=0;i<this.dataList.length;i++){
							for(let j=0;j<this.dataList[i].detail.length;j++){
								if(this.dataList[i].detail[j].is_refund==1){
									this.dataList[i].reimburse=true
								}
							}
						}
						this.pages = pagination;
						this.pullUpOn = true;
					}
				})
			},
			change(e) {
				if(this.statusObj[e.index]==4){
					uni.navigateTo({
						url:'./refund/list'
					})
				}else{
					this.status = this.statusObj[e.index]
					let sum = 0;
					this.getDateList('refresh', this.status)
				}			
			},
			detail(id) {
				uni.navigateTo({
					url: `/pages/order/detail?orderId=${id}`
				})
			}
		},
		onPullDownRefresh() {
			setTimeout(() => {
				uni.stopPullDownRefresh()
				this.getDateList('refresh', this.status)
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

<style lang="scss" scoped>
	page {
		background-color: #F7F7F7;
	}
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
		margin-top: 185rpx;
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
		margin-top: 310rpx;
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
	.tui-searchbox {
		width: 100%;
		padding: 30rpx;
		background: #fff;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		position: fixed;
		/* #ifdef H5 */
		top: 80rpx;
		/* #endif */
		/* #ifdef  MP  */
		top: 0rpx;
		/* #endif */
		left: 0;
		z-index: 999;
	}
	
	.tui-search-input {
		width: 90%;
		height: 66rpx;
		border-radius: 35rpx;
		padding: 0 30rpx;
		box-sizing: border-box;
		background: #f2f2f2;
		display: flex;
		align-items: center;
		flex-wrap: nowrap;
	}
	
	.tui-input {
		flex: 1;
		color: #333;
		padding: 0 16rpx;
		font-size: 11pt;
	}
	
	.tui-input-plholder {
		font-size: 11pt;
		color: #b2b2b2;
	}
	
	.tui-cancle {
		color: #888;
		font-size: 11pt;
		padding-left: 30rpx;
		flex-shrink: 0;
	}
	
	.tui-history-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 30rpx 0;
	}
	
	.tui-icon-delete {
		padding: 10rpx;
	}
	
	.tui-search-title {
		font-size: 11pt;
		font-weight: bold;
	}
	
	.tui-hot-header {
		padding: 30rpx 0;
	}
	
	.tui-tag-class {
		display: inline-block;
		margin-bottom: 20rpx;
		margin-right: 20rpx;
		font-size: 9pt !important;
	}
	.tui-history-content{
		display: flex;
		flex-wrap: wrap;
	}
</style>
