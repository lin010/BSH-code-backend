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
				<input type="search" placeholder="请输入名称和ID搜索"  placeholder-class="tui-input-plholder"
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
		
		
		
		
		<com-tabs :tabs="tabs"  :currentTab="showTab[status]" :selectedColor="textColor" :sliderBgColor="textColor"
		 :sliderHeight="4" :sliderWidth="50" bgColor="#F7F7F7" @change="change" bottom="10rpx" style="z-index:9999;"></com-tabs>
		<view class="tui-order-list" v-if="dataList && dataList.length">
			<view class="item" v-for="(item,orderIndex) in data_list" :key="orderIndex">
				<view class="user-status">
					<view class="item-uesr-info">
						<image :src="item.avatar_url" mode="aspectFill" class="acatar"></image>
						<view class="name-datetime">
							<view class="name">
								<view class="name-text">{{ item.nickname }}</view>
								<view class="id" :style="{color: textColor,border:'1px solid'+textColor}">ID:{{ item.uid }}</view>
							</view>
							<view class="tel" v-if="item.mobile">
								{{ item.mobile }}
								<span class="iconfont icon-dianhua3"></span>
							</view>
						</view>
					</view>
					<view class="price-mark">
						<view class="price-status">{{item.status_text}}</view>
						<view class="price-money">预计收益：￥{{item.price||0}}</view>
					</view>
				</view>
				<view class="info">
					<view class="mark">
						<view class="goods-name">订单号: {{ item.order_no }}</view>
						<view class="order-id">订单时间: {{ item.created_at }}</view>
					</view>
				</view>
			</view>
		</view>
		<view class="order-nothing" v-else>
			<image class="order-nothing-img" :src="img_url+'images/order/order-nothing.png'" mode=""></image>
			<view class="order-nothing-text">目前没有{{ status ? tabs[status+1].name : ""}}订单哦~</view>
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
				tabs: [{
					name: "全部"
				}, {
					name: "待付款"
				}, {
					name: "待发货"
				}, {
					name: "待收货"
				}],
				statusObj: {
					0: -1,
					1: 0,
					2: 1,
					3: 2
				},
				showTab: {
					'-1': 0,
					'0': 1,
					'1': 2,
					'2': 3
				},
				status: -1,
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
			}
		},
		onLoad(options) {
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
				if(this.status == -1){
					return this.dataList;
				}
				return this.dataList.filter(item => item.status == this.status);
			}
		},
		methods: {
			cleanKey: function() { //清空搜索
				this.key = ''
				this.getDateList('refresh', this.status)
			},
			search(){
				this.getDateList('refresh', this.status)
			},
			// 通过 key 和 status 判断通过何种方式修改数据
			getDateList(key, status) {
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
				status = status == -1 ? '' : status; // 判断查询条件是否存在 不存在则为 -1
				let {
					current_page,
					pageSize
				} = this.pages;
				this.dataList = this.dataList ? this.dataList : [];
				this.$http.request({
					url: this.$api.plugin.extensions.team.order,
					method: 'POST',
					data: {
						status,
						page: current_page,
						limit: pageSize,
						keywords:this.key
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
					}
				})
			},
			change(e) {
				this.status = this.statusObj[e.index]
				let sum = 0;
				this.getDateList('refresh', this.status)
			}
		},
		onPullDownRefresh() {
			setTimeout(() => {
				uni.stopPullDownRefresh()
				this.getDateList('refresh', this.status)
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
		padding: 0 24rpx;
		height: 100%;
	}

	.tui-order-list {
		margin-top: 230rpx;
		display: flex;
		flex-direction: column;
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
		margin-top: 300rpx;
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
