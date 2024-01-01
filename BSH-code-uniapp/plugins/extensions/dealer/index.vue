<template>
	<view class="app">
		<com-nav-bar :fixed="true" @clickLeft="back" left-icon="back" title="经销商中心" :status-bar="true" :background-color="navBg" :border="false" :color="navCol"></com-nav-bar>
		<block v-if="parent_agent">
			<view class="container">
				<image :src="bg_url" mode="aspectFill" class="jx-bg"></image>
				<!-- <view class="content" v-if="data"> -->
				<view class="content">
					<view class="content-head">
						<!-- <view class="user-info" v-if="parent_agent">
							<image :src="parent_agent.avatar_url" mode="aspectFill" class="acatar"></image>
							<view class="userinfo">
								<view class="username">{{ parent_agent.nickname }}</view>
								<view class="level">{{ parent_agent.level_name }}</view>
							</view>
						</view> -->
						
						<view class="user-info">
							<image :src="parent_agent.avatar_url" mode="aspectFill" class="acatar"></image>
							<view class="userinfo">
								<view class="username">{{ parent_agent.nickname }}</view>
								<view class="desc">账号ID: {{ parent_agent.user_id }}</view>
								<view class="level">等级:{{ parent_agent.level_name }}</view>
							</view>
							<view class="withdraw flex">
								<navigator url="./interests/index">
									<view class="icon-text flex"><view class="text">权益中心</view></view>
								</navigator>
							</view>
						</view>
					</view>
					<view class="content-bottom">
						<view class="income margin-bottom-30rpx">
							<view class="status flex">
								<view class="text-money flex flex-column-x-center">
									<view class="text">未结算</view>
									<view class="money">{{ frozen_price || 0 }}元</view>
								</view>
								<view class="text-money flex flex-column-x-center">
									<view class="text">已结算</view>
									<view class="money">{{ is_price || 0 }}元</view>
								</view>
								<view class="text-money flex flex-column-x-center">
									<view class="text">昨日收益</view>
									<view class="money">{{ yesterday_price || 0 }}元</view>
								</view>
								<view class="text-money flex flex-column-x-center">
									<view class="text">总收益</view>
									<view class="money">{{ total_price || 0 }}元</view>
								</view>
							</view>
						</view>
						<view class="team-benefits margin-bottom-30rpx">
							<view class="text-icon flex flex-column-x-center" @click="openUrl('/plugins/extensions/dealer/agency')">
								<view class="icon iconfont icon-tuandui" :style="{color:textColor}"></view>
								<view class="text">我的供货商</view>
							</view>
							<view class="text-icon flex flex-column-x-center" @click="refresh()">
								<view class="icon iconfont icon-icon" :style="{color:textColor}"></view>
								<view class="text">我的收益</view>
							</view>
							<view class="text-icon flex flex-column-x-center" @click="refresh()">
								<view class="icon iconfont icon-shouyi" :style="{color:textColor}"></view>
								<view class="text">收益明细</view>
							</view>
						</view>
						<!-- <view class="activation-code-list  margin-bottom-30rpx flex flex-x-between">
							<view>我的激活码</view>
							<navigator url="./interests/activation-code-list"><view class="click">立即查看></view></navigator>
						</view> -->
						<view class="order">
							<view class="order-tabs flex">
								<block v-for="(item, i) in 3" :key="i">
									<view class="tab flex-grow-1 flex-x-center flex-y-center"
									 :style="{color:currentTabIndex == i ?textColor:''}" @click="tabChange(i)">
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
												<view class="id" :style="{color:textColor,border:'1px solid'+textColor}">ID:{{ item.user_id }}</view>
											</view>
											<view class="datetime">下单时间:{{ item.created_at }}</view>
										</view>
										<view class="status-text":style="{background:textColor,border:'1px solid'+textColor}">{{ item.status }}</view>
									</view>
									<view class="info">
										<view class="mark">
											<view class="goods-name">商品名称: {{ item.goods_name }}</view>
											<view class="order-id">订单编号: {{ item.order_no }}</view>
										</view>
										<view class="money">
											<view class="order-money">商品金额: {{ item.goods_price }}元</view>
											<view class="commission">佣：{{ item.price }} 元</view>
										</view>
									</view>
								</view>
							</view>
							<view class="order-list" v-else><main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore></view>
						</view>
					</view>
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
			orderListTabs: ['供货商奖', '平级奖', '感恩奖'],
			currentTabIndex: 0,
			list: [],
			page: 1,
			is_no_more: false,
			parent_agent: null,
			frozen_price: '',
			is_price: '',
			total_price: '',
			yesterday_price: '',
			
			textColor:'#bc0100',
			bg_url:'',
			navBg:'',
			navCol:'',
		};
	},
	onReachBottom: function(e) {
		this.getList();
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
		refresh(str){
			this.page = 1;
			this.currentTabIndex = 0;
			this.page_count = 0;
			this.tabIndex = 0;
			this.is_no_more = false;
			this.list = [];
			this.getList('refresh');
		},
		openUrl(url){
			uni.navigateTo({
				url
			})
		},
		tabChange: function(index) {
			this.currentTabIndex = index;
			this.list = [];
			this.page = 1;
			this.is_no_more = false;
			this.getList();
		},
		getInfo() {
			this.loading = true;
			this.$http
				.request({
					url: this.$api.plugin.agent.info
				})
				.then(res => {
					this.loading = false;
					if (res.code == 0) {
						this.parent_agent = res.data;
						this.frozen_price = res.data.frozen_price;
						this.is_price = res.data.is_price;
						this.total_price = res.data.total_price;
						this.yesterday_price = res.data.yesterday_price;
					}
				});
		},
		getList(str) {
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
					url: this.$api.plugin.agent.log_list,
					data: {
						page: this.page,
						type: this.currentTabIndex //类型
					}
				})
				.then(res => {
					this.loading = false;
					//console.log(res);
					if (res.code == 0) {
						if(str == 'refresh'){
							this.list = res.data.list;
						}else{
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
						}
					} else {
						uni.showToast({
							title: res.msg
						});
					}
				});
		},
		getData() {
			this.loading = true;
			this.$http
				.request({
					url: this.$api.plugin.extensions.info,
					method: 'POST'
				})
				.then(res => {
					this.loading = false;
					if (res.code == 0) {
						this.data = res.data;
					}
				});
		},
		back() {
			this.navBack();
		}
	},
	filters: {
		brokerage(type, money) {
			let text = type > 1 ? '间佣' : '直佣';
			return `${text}: ${money}元`;
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

	.container {
		color: #ffffff;
		position: relative;

		.jx-bg {
			position: absolute;
			top: 0;
			width: 100%;
			height: 402rpx;
		}

		.content {
			font-size: 0;
			position: relative;
			color: #ffffff;

			.content-head {
				// padding-bottom: 100rpx;
				.withdraw {
					position: absolute;
					right: 0;

					.icon-text {
						align-items: center;
						background-color: #ec7676;
						font-size: 9pt;
						line-height: 54rpx;
						padding: 0 20rpx;
						border-bottom-left-radius: 27rpx;
						border-top-left-radius: 27rpx;

						.icon {
							font-size: 11pt;
							line-height: 26px;

							&.icon-tixian {
								margin-left: 10rpx;
							}
						}

						.text {
							margin-left: 6rpx;
						}
					}
				}

				.user-info {
					position: relative;
					margin-bottom: 20rpx;
					border-radius: 8rpx;
					padding: 30rpx;
					display: flex;
					align-items: center;

					.acatar {
						width: 122rpx;
						height: 122rpx;
						border-radius: 50%;
						margin-right: 30rpx;
						background-color: #ffffff;
					}

					.userinfo {
						.username,
						.desc,
						.level {
							// line-height: 48rpx;
							line-height: 44rpx;
						}

						.username {
							font-weight: 400;
							font-size: 36rpx;
						}

						.desc,
						.level {
							font-size: 9pt;
						}

						.iconfont {
							font-size: 10pt;
							margin-right: 8rpx;
						}
					}
				}
			}

			.content-bottom {
				color: #ffffff;
				font-size: 9pt;
				padding: 0 30rpx;

				.income {
					.status {
						font-size: 12pt;

						.text {
							font-size: 10pt;
						}
					}
				}

				.team-benefits {
					padding: 30rpx 0;
					color: #212121;
					background-color: #ffffff;
					display: flex;
					border-radius: 10rpx;
					// box-shadow: 0 8rpx 11rpx 3rpx rgba(8, 1, 3, 0.07);

					.icon {
						font-size: 18pt;
						color: #bc0100;
						line-height: 56rpx;
					}

					.text {
						font-size: 11pt;
					}
				}

				.activation-code-list {
					color: #333333;
					font-size: 11pt;
					padding: 20rpx 26rpx;
					background-color: #ffffff;
					border-radius: 10rpx;

					.click {
						font-size: 9pt;
						color: #6a6a6a;
					}
				}

				.order {
					color: #333333;
					background-color: #ffffff;
					border-radius: 15rpx;

					.order-tabs {
						border-bottom: 1rpx solid #f3f3f3;

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
										display: flex;
										line-height: 37rpx;
										max-width:142rpx;
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
		}
	}
}

.margin-bottom-30rpx {
	margin-bottom: 30rpx;
}

.flex {
	display: flex;
}

.flex-column-x-center {
	flex: 1;
	flex-direction: column;
	align-items: center;
}
</style>
