<template>
	<view class="container">
		<view class="top-panel">
			<view class="flex stat-income" :style="{background:textColor}">
				<view class="flex flex-col">
					<text>{{stat_price1}}</text>
					<text>待结算收益(元)</text>
				</view>
				<view class="flex flex-col">
					<text>{{stat_price2}}</text>
					<text>已到账收益(元)</text>
				</view>
			</view>
			<view style="background:white;padding-top:20rpx;">
				<uni-datetime-picker
					style="margin-left:5%;width:90%;"
					v-model="range"
					type="daterange"
					rangeSeparator="至"
					@change="dateChange"
				/>
			</view>
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
					<input type="search" placeholder="请输入订单号/名称/ID搜索"  placeholder-class="tui-input-plholder"
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
			
			<view class="flex tabs" style="background:white;">
				<view @click="change(0)" class="flex flex-col" :style="{color:tabIndex == 0 ? textColor : '#666'}">
					<text>待结算</text>
					<text v-show="tabIndex == 0" :style="{background:textColor}"></text>
				</view>
				<view @click="change(1)" lass="flex flex-col" :style="{color:tabIndex == 1 ? textColor : '#666'}">
					<text>已到账</text>
					
					<text v-show="tabIndex == 1" :style="{background:textColor}"></text>
				</view>
			</view>
		</view>
		<view style="margin-top:470rpx;width:100%;"></view>
		
		
		<view class="tui-order-list" v-if="dataList && dataList.length" style="">
			<view class="item" v-for="(item,orderIndex) in data_list" :key="orderIndex">
				<view class="user-status">
					<view class="item-uesr-info">
						<image :src="item.avatar_url" mode="aspectFill" class="acatar"></image>
						<view class="name-datetime">
							<view class="name">
								<view class="name-text">{{ item.nickname }}</view>
								<view class="id" :style="{color: textColor,border:'1px solid'+textColor}">ID:{{ item.user_id }}</view>
							</view>
							<view class="tel" >
								{{item.mobile}}
								<span class="iconfont icon-dianhua3"></span>
							</view>
						</view>
					</view>
					<view class="price-mark">
						<view class="price-money" v-if="tabIndex == 0">预计收益：￥{{item.income_price||0}}</view>
						<view class="price-money" v-else style="color:green">收益：￥{{item.income_price||0}}</view>
					</view>
				</view>
				<view class="info">
					<view class="mark">
						<view class="flex">
							<text style="width:130rpx;text-align: right;">推荐人：</text>
							<text style="color:#07beb4">{{ item.parent_nickname }}</text>
						</view>
						<view class="flex">
							<text style="width:130rpx;text-align: right;">订单号：</text>
							<text>{{ item.order_no }}</text>
						</view>
						<view class="flex">
							<text style="width:130rpx;text-align: right;">订单时间：</text>
							<text> {{ item.created_at }}</text>
						</view>
						<view class="flex">
							<text style="width:130rpx;text-align: right;">订单金额：</text>
							<text :style="{color:textColor}">￥{{item.total_price}}</text>
						</view>
					</view>
				</view>
				<view class="flex goods" style="">
					<image @click="toGoodsDetail(item.goods_id)" :src="item.cover_pic" mode="aspectFill"></image>
					<view>
						<view @click="toGoodsDetail(item.goods_id)">{{item.attr_name}}</view>
						<view class="flex flex-y-center" style="justify-content: space-between;">
							<text :style="{color:textColor}">￥{{item.price}}</text>
							<text>x{{item.num}}</text>
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="order-nothing" v-else>
			<image class="order-nothing-img" :src="img_url+'images/order/order-nothing.png'" mode=""></image>
			<view class="order-nothing-text">暂无记录</view>
		</view>
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
				img_url: this.$api.img_url,
				textColor:'#bc0100',
				tabBar: null,
				scrollTop: 0,
				dataList: [],
				pages: {
					current_page: 1,
					pageSize: 20,
					page_count: 1,
					total_count: 0
				},
				key:'',
				flag:false,
				range: [],
				tabIndex: 0,
				stat_price1: 0,
				stat_price2: 0
			}
		},
		onLoad(options) {
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
				return this.dataList;
			}
		},
		methods: {
			toGoodsDetail(goods_id){
				uni.navigateTo({
					url: `/pages/goods/detail?proId=${goods_id}`
				})
			},
			cleanKey: function() { //清空搜索
				this.key = ''
				this.getDateList('refresh')
			},
			search(){
				this.getDateList('refresh')
			},
			// 通过 key 和 status 判断通过何种方式修改数据
			getDateList(key) {
				if(this.flag){
					return
				}
				this.flag=true
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
					url: this.$api.income_log.incomeData,
					method: 'POST',
					data: {
						page: current_page,
						status: this.tabIndex,
						keywords:this.key,
						start_date: this.range.length > 0 ? this.range[0] : '',
						end_date: this.range.length > 0 ? this.range[1] : ''
					},
					showLoading: true
				}).then(res => {
					this.flag=false
					if (res.code === 0) {
						let {
							list,
							pagination
						} = res.data;
						
						this.dataList = key != 'refresh' ? this.dataList.concat(list) : list; // key != refresh 则是加载下一页数据
						this.pages = pagination;
						this.stat_price1 = res.data.stat_price1;
						this.stat_price2 = res.data.stat_price2;
					}
				})
			},
			change(e) {
				this.tabIndex = e;
				this.getDateList('refresh');
			},
			dateChange(e){
				this.range = e;
				this.getDateList('refresh');
			}
		},
		onPullDownRefresh() {
			setTimeout(() => {
				uni.stopPullDownRefresh()
				this.getDateList('refresh')
			}, 1000);
		},
		onReachBottom() {
			let {
				current_page,
				page_count
			} = this.pages;
			setTimeout(() => {
				if (current_page >= page_count){
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
		height: 100%;
	}
	
	.top-panel{
		position: fixed;
		/* #ifdef H5 */
		top: 80rpx;
		/* #endif */
		/* #ifdef  MP  */
		top: 0rpx;
		/* #endif */
		left: 0;
		width:100%;
		z-index: 999;
		box-shadow: 0rpx 3rpx 5rpx #d9d9d9;
	}
	
	.stat-income{
		width:100%;
		height:170rpx;
		justify-content: space-around;
		align-items: center;
		font-size:24rpx;
		color:#fff;
		> view{
			align-items: center;
			text:first-child{font-size:16pt}
		}
	}

	.tabs{
		justify-content: space-around;
		font-size:28rpx;
		padding-bottom:20rpx;
		> view{
			align-items: center;
			text:last-child{width:50rpx;height:3px;}
		}
	}

	.tui-order-list {
		display: flex;
		flex-direction: column;
		background:white;
		.item {
			padding: 20rpx;
	
			border-bottom: 1rpx solid #f3f3f3;
	
			.user-status {
				display: flex;
				align-items: center;
				position: relative;
				margin-bottom: 16rpx;
				justify-content: space-between;
				.item-uesr-info{
					display: flex;
					align-items: center;
					.acatar {
						width: 80rpx;
						height: 80rpx;
						border-radius: 50%;
						margin-right: 16rpx;
					}
						
					.name-datetime {
						.name {
							display: flex;
							line-height: 37rpx;
						
							.name-text {
								overflow: hidden;
								text-overflow: ellipsis;
								white-space: nowrap;
								max-width: 151rpx;
							}
						
							.id {
								margin-left: 12rpx;
								color: #bc0100;
								padding: 0 10rpx;
								font-size: 9pt;
								transform: scale(0.8);
								line-height: 32rpx;
								border: 1rpx solid rgba(188, 1, 0, 1);
								border-radius: 14rpx;
							}
						}
						
						.tel,
						.datetime {
							font-size: 9pt;
							color: #808080;
						
							.iconfont {
								line-height: 16px;
								font-size: 10pt;
							}
						}
					}
				}
				
				.price-mark{
					text-align: right;
					font-size: 9pt;;
					.price-status{color: #07beb4;}
					.price-money{color: #bc0100;}
				}
				.status-text {
					position: absolute;
					top: 0;
					right: 0;
					padding: 0 10rpx;
					background-color: #bc0100;
					border: 1rpx solid #bc0100;
					border-radius: 18rpx;
					color: #ffffff;
					font-size: 9pt;
					transform: scale(0.8);
					text-align: center;
					line-height: 18px;
				}
			}
	
			.info {
				display: flex;
				font-size: 9pt;
	
				.mark {
					flex: 1;
	
					.goods-name,
					.order-id {
						overflow: hidden;
						text-overflow: ellipsis;
						white-space: nowrap;
					}
				}
	
				.money {
					.commission {
						color: #bc0100;
					}
				}
			}
		
			.goods{
				font-size:26rpx;
				margin-top:10rpx;
				> image{
					flex-shrink: 0;
					background:#f1f1f1;
					width:130rpx;
					height:130rpx
				}
				> view{
					margin-left:20rpx;
					width:100%;
				}
			}
		}
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
		width: 46rpx;
		height: 46rpx;
		display: block;
		border-radius: 15rpx;
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
