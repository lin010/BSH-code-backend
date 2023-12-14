<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="我的收益" :status-bar="true" :background-color="navBg" :border="false"
		:color="navCol"></com-nav-bar>
		<view class="container">
			<image :src="bg_url" mode="aspectFill" class="jx-bg"></image>
			<view class="content">
				<view class="income" v-if="info">
					<view class="total flex">
						<view class="text">总金额 (元)</view>
						<view class="sum">{{info.total_price}}</view>
						<view class="yesterday">昨日收益{{ info.yesterday_price }}元</view>
					</view>
					<view class="status flex">
						<view class="text-money flex-col flex-y-center flex-grow-1">
							<view class="text">昨天总收益</view>
							<view class="money">{{ info.yesterday_price }}元</view>
						</view>
						<view class="text-money flex-col flex-y-center flex-grow-1">
							<view class="text">本周收益</view>
							<view class="money">{{ info.week_price }}元</view>
						</view>
						<view class="text-money flex-col flex-y-center flex-grow-1">
							<view class="text">本月收益</view>
							<view class="money">{{ info.month_price }}元</view>
						</view>
					</view>
				</view>
				<view class="order">
					<view class="order-tabs flex">
						<block v-for="(item, i) in 4" :key="i">
							<view class="tab flex-grow-1 flex-x-center flex-y-center" :style="{color:currentTabIndex == i ?textColor:''}" @click="tabChange(i)">
								<span class="name" :style="{'border-bottom':currentTabIndex == i ?'2px solid'+textColor:''}">{{ orderListTabs[i] }}</span>
							</view>
						</block>
					</view>
					<view class="order-list" v-if="list && list.length > 0">
						<view class="item" v-for="(item, i) in list" :key="i">
							<view class="user-status">
								<image :src="item.avatar_url" mode="aspectFill" class="acatar"></image>
								<view class="name-datetime">
									<view class="flex">
										<view class="name over1">{{ item.nickname }}</view>
										<view class="id">ID:{{ item.user_id }}</view>
									</view>
									<view class="datetime">下单时间:{{ item.created_at }}</view>
								</view>
								<!-- <view class="status-text">{{ item.status }}</view> -->
							</view>
							<view class="info">
								<view class="mark flex-x-between">
									<view class="goods-name">商品名称: {{ item.goods_name }}</view>
									<view class="order-money">订单金额: {{ item.order_price }}元</view>
								</view>
								<view class="money flex-x-between">
									<view class="order-id">订单编号: {{ item.order_no }}</view>
									<view class="commission">奖励：{{ item.price }} 元</view>
								</view>
							</view>
						</view>
					</view>
					<view class="order-list" v-else>
						<main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore>
					</view>
				</view>
			</view>
		</view>

		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				data: null,
				loading: false,
				info: null,
				dataList: [],
				page:1,
				type:0,
				status:0,
				currentTabIndex: 0,
				orderListTabs: ['货款收益', '订货奖励', '平级奖励', '越级奖励'],
				
				textColor:'#bc0100',
				bg_url:'',
				button:[],
				navBg:'',
				navCol:'',
				list:[],
			};
		},
		onLoad() {
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.button = this.globalSet('btnCol');
			this.navBg = this.globalSet('navBg');
			this.navCol = this.globalSet('navCol');
			
			this.getInfo();
			 this.getList()
		},
		methods: {
			getList() {
				let url='';
				if(this.type==0){
					url=this.$api.plugin.stock.goods_price_log;
				}
				
				if(this.type==1){
					url=this.$api.plugin.stock.buy_goods_price;
					
				}
				if(this.type==2||this.type==3){
					url=this.$api.plugin.stock.fill_price_log;
				}
				
				this.loading = true;
				if (this.is_no_more) {
					uni.showToast({
						title: '没有更多数据'
					});
					this.loading = false;
					return;
				}
				this.$http
					.request({
						url: url,
						data: {
							page: this.page,
							type: this.status //类型
						}
					})
					.then(res => {
						this.loading = false;
						console.log(res,'----------------');
						if (res.code == 0) {
							if (this.page == 1) {
								this.list = res.data.list;
							} else {
								this.list=this.list.concat(res.data.list);
							}
							if (this.page < res.data.pagination.page_count) {
								this.page++;
							} else {
								this.is_no_more = true;
							}
						} else {
							uni.showToast({
								title: res.msg
							});
						}
					});
			},

			getInfo() {
				this.loading = true;
				this.$http
					.request({
						url: this.$api.plugin.stock.info
					})
					.then(res => {
						this.loading = false;
						if (res.code == 0) {
							this.info = res.data
						}
					});
			},
			tabChange(index) {
				this.currentTabIndex = index;
				this.list = [];
				this.page = 1;
				this.is_no_more = false;
				
				if(index==2){
					this.status=1;
				}else if(index==3){
					this.status=2;
				}else{
					this.status=0;
				}
				this.type=index
				this.getList();
			},
			getData() {

			},
			back() {
				this.navBack();
			}
		},
		onPullDownRefresh() {}
	};
</script>

<style lang="scss" scoped>
	.app {
		min-height: 100%;
		background-color: #f7f7f7;
	}

	.container {
		color: #ffffff;
		position: relative;

		.jx-bg {
			position: absolute;
			top: 0;
			width: 100%;
			height: 540rpx;
		}

		.content {
			position: relative;

			.income {
				padding: 0 30rpx;
				margin-bottom: 60rpx;

				.total {
					align-items: center;
					justify-content: center;
					flex-direction: column;
					margin-bottom: 80rpx;
					font-size: 11pt;

					.sum {
						font-size: 23pt;
						font-weight: bold;
					}

					.yesterday {
						padding: 0 20rpx;
						background-color: #ffffff;
						color: #bc0100;
						font-size: 9pt;
						font-weight: 700;
						line-height: 45rpx;
						border-radius: 22rpx;
					}
				}

				.status {
					font-size: 12pt;

					.text {
						font-size: 10pt;
					}
				}
			}

			.order {
				color: #333333;
				background-color: #ffffff;
				border-radius: 15rpx;
				margin: 0 30rpx 30rpx;

				.order-tabs {
					border-bottom: 1rpx solid #f3f3f3;

					.tab {
						height: 100rpx;
						font-size: 11pt;
						border-right: 1rpx solid #f2f2f2;

						.name {
							position: relative;
						}

						&.active {
							color: #bc0100;

							.name {
								&::before {
									content: '';
									position: absolute;
									bottom: -8rpx;
									height: 4rpx;
									width: 100%;
									background-color: #bc0100;
								}
							}
						}

						&:last-child {
							border-right: 0;
						}
					}
				}

				.order-list {
					height: 100%;

					.item {
						padding: 20rpx;
						border-bottom: 1rpx solid #f3f3f3;

						.user-status {
							display: flex;
							align-items: center;
							position: relative;
							margin-bottom: 16rpx;

							.acatar {
								width: 100rpx;
								height: 100rpx;
								border-radius: 50%;
								margin-right: 16rpx;
							}

							.name-datetime {
								
								.name {
									line-height: 37rpx;
									max-width: 316rpx;
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

								.datetime {
									font-size: 9pt;
								}
							}

							.status-text {
								position: absolute;
								top: 0;
								right: 0;
								padding: 0 18rpx;
								height: 36rpx;
								background-color: #bc0100;
								border: 1rpx solid #bc0100;
								border-radius: 18rpx;
								color: #ffffff;
								font-size: 22rpx;
								line-height: 36rpx;
								text-align: center;
							}
						}

						.info {
							font-size: 22rpx;

							.mark {

								.goods-name,
								.order-id {
									overflow: hidden;
									text-overflow: ellipsis;
									white-space: nowrap;
									max-width: 420rpx;
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
			}
		}
	}
</style>
