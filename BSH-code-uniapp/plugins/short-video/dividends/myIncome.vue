<template>
	<view class="app">
		<com-nav-bar :fixed="true" @clickLeft="back" left-icon="back" title="收益明细" :status-bar="true" background-color="#ffffff"
		 :border="false" color="#000000"></com-nav-bar>
		<block v-if="parent_agent">
			<view class="order">
				<scroll-view :scroll-x="true" class="order-tabs flex">
					<view class="flex order-tabs-item" v-for="(item, i) in 2" :key="i">
						<view class="tab flex-grow-1 flex-x-center flex-y-center" :style="{color:currentTabIndex == i ? textColor:''}" @click="tabChange(i)">
							<view class="name" :style="{'border-bottom':currentTabIndex == i ?'2px solid'+textColor:''}">{{ orderListTabs[i] }}</view>
						</view>
					</view>
				</scroll-view>
				<view style="height: 100rpx;margin-bottom: 20rpx;"></view>
				<!-- <view class="order-tabs flex">
					<block v-for="(item, i) in 2" :key="i">
						<view class="tab flex-grow-1 flex-x-center flex-y-center" :style="{color:currentTabIndex == i ?textColor:''}"
						 @click="tabChange(i)">
							<span class="name" :style="{'border-bottom':currentTabIndex == i ?'2px solid'+textColor:''}">{{ orderListTabs[i] }}</span>
						</view>
					</block>
				</view> -->
				
				<view class="order-list" v-if="list && list.length > 0">
					<view class="item" v-for="(item, i) in list" :key="i">
						<view class="user-status flex flex-x-between flex-y-center">
							<view class="flex flex-y-center">
								<image :src="item.avatar_url" mode="aspectFill" class="acatar"></image>
								<view class="name-datetime">
									<view class="flex">
										<view class="name over1">{{ item.nickname }}</view>
										<view class="id" :style="{color:textColor,border:'1px solid'+textColor}">ID:{{ item.user_id }}</view>
									</view>
									<view class="datetime">下单时间:{{ date(item.created_at) }}</view>
								</view>
							</view>
							<view :style="{color:textColor}" class="price" v-if="currentTabIndex != 0">
								{{item.money}}元
							</view>
							<view v-if="currentTabIndex == 0" class="status-text" :style="{background:textColor,border:'1px solid'+textColor}">{{ item.is_settlement ?'未结算':'已结算' }}</view>
						</view>
						<view class="info" v-if="currentTabIndex == 0">
							<view class="mark">
								<view class="goods-name">商品名称: {{ item.name }}</view>
								<view class="order-id">订单编号: {{ item.order_no }}</view>
							</view>
							<view class="money">
								<view class="order-money">订单金额: {{ item.total_price }}元</view>
								<view class="commission">带货奖励：{{ item.money }} 元</view>
							</view>
						</view>
					</view>
				</view>
				<view class="order-list" v-else>
					<main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore>
				</view>
			</view>
		</block>

		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				loading: false,
				data: null,
				orderListTabs: ['带货奖励', '观看奖励'],
				currentTabIndex: 0,
				list: [],
				page: 1,
				is_no_more: false,
				parent_agent: null,
				frozen_price: '',
				is_price: '',
				total_price: '',
				yesterday_price: '',
				http_url:'',
				look_type:'',

				textColor: '#bc0100',
				bg_url: '',
				navBg: '',
				navCol: '',
			};
		},
		onReachBottom: function(e) {
			this.getList();
		},
		computed:{
			date(){
				return function (time){
					return this.dateFormat(time);
				}
			}
		},
		onLoad() {
			this.navBg = this.globalSet('navBg');
			this.navCol = this.globalSet('navCol');
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');

			this.getInfo();
			this.getList();
		},
		methods: {
			openUrl(url) {
				uni.navigateTo({
					url
				})
			},
			tabChange(index) {
				this.currentTabIndex = index;
				this.list = [];
				this.page = 1;
				this.is_no_more = false;
				if(index == 0){
					this.http_url = this.$api.plugin.video.shopping_award;
				}else{
					this.http_url = this.$api.plugin.video.look_award;
					if(index == 1){
						this.look_type = 3;
					}else{
						this.look_type = index - 1; 
					}
				}
				this.getList();
			},
			getInfo() {
				this.loading = true;
				this.$http.request({
						url: this.$api.plugin.video.user_award,
						method:'post'
					}).then(res => {
						this.loading = false;
						if (res.code == 0) {
							this.parent_agent = res.data;
							this.frozen_price = res.data.unsettled;
							this.is_price = res.data.settled;
							this.total_price = res.data.sum_award;
							this.yesterday_price = res.data.yt_award;
						}
					});
			},
			getList() {
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
						url: this.http_url || this.$api.plugin.video.shopping_award,
						method:'post',
						data: {
							page: this.page,
							look_type:this.look_type
						}
					})
					.then(res => {
						this.loading = false;
						if (res.code == 0) {
							if (this.page == 1) {
								this.list = res.data.list;
							} else {
								this.list.concat(res.data.list);
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
			back() {
				this.navBack();
			}
		},
		onPullDownRefresh() {
			setTimeout(() => {
				uni.stopPullDownRefresh();
				// this.getDateList(_status);
			}, 1000);
		}
	};
</script>

<style lang="scss" scoped>
	.app {
		min-height: 100%;
		background-color: #f7f7f7;

		.order {
			color: #333333;
			border-radius: 15rpx;
		
			.order-tabs {
				border-top: 1rpx solid #f3f3f3;
				border-bottom: 1rpx solid #f3f3f3;
				background-color: #ffffff;
				margin-bottom: 20rpx;
				height: 100rpx;
				white-space: nowrap;
				position: fixed;
				width: 100%;
				z-index: 99;
				
				.order-tabs-item{
					display: inline-block;
					width: 50%;
				}
				.tab {
					height: 100rpx;
					font-size: 11pt;
					border-right: 1rpx solid #f2f2f2;
		
					.name {
						position: relative;
					}
		
					// .name {
					// 	&::before {
					// 		content: '';
					// 		position: absolute;
					// 		bottom: -8rpx;
					// 		height: 4rpx;
					// 		width: 100%;
					// 		background-color: #bc0100;
					// 	}
					// }
		
					&:last-child {
						border-right: 0;
					}
				}
			}
		
			.order-list {
				height: 100%;
				background-color: #ffffff;
		
				.item {
					padding: 30rpx;
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
								display: flex;
								line-height: 37rpx;
								max-width: 142rpx;
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
							padding: 0 10rpx;
							background-color: #bc0100;
							border: 1rpx solid #bc0100;
							border-radius: 18rpx;
							color: #ffffff;
							font-size: 9pt;
							transform: scale(0.8);
						}
						.price{
							font-size: 30rpx;
							
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
</style>
