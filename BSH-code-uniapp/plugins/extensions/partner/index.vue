<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" :fixed="true" left-icon="back" title="股东分红" :status-bar="true" :background-color="navBg" :border="false" :color="navCol"></com-nav-bar>
		<view class="container">
			<image :src="bg_url" mode="aspectFill" class="jx-bg"></image>
			<!-- <view class="content" v-if="data"> -->
			<view class="content">
				<view class="content-head">
					<view class="user-info">
						<image :src="'' || 'http://yingmlife-1302693724.cos.ap-guangzhou.myqcloud.com/uploads/images/original/20201216/15262b999e48acc5891864e3f2463cb0.jpg'" mode="aspectFill" class="acatar"></image>
						<view class="userinfo" v-if="info">
							<view class="username">{{info.nickname}}</view>
							<view class="desc">账号ID: {{info.user_id}}</view>
							<view class="level">股东等级: {{info.level_name}}</view>
						</view>
						<view class="withdraw flex">
							<navigator url="./interests">
								<view class="icon-text flex">
									<view class="text">权益中心</view>
								</view>
							</navigator>
						</view>
					</view>
				</view>
				<view class="content-bottom" v-if="info">
					<view class="income margin-bottom-30rpx">
						<view class="status flex">
							<view class="text-money flex flex-column-x-center">
								<view class="text">昨日分红池</view>
								<view class="money">{{info.yestoday_total_price?info.yestoday_total_price:0}} 元</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">昨日股东收益</view>
								<view class="money">{{info.yesterday_price?info.yesterday_price:0}} 元</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">收益比例</view>
								<view class="money">{{info.income_rate}} %</view>
							</view>
						</view>
					</view>
					<view class="team-benefits margin-bottom-30rpx">
						<view class="text-icon flex flex-column-x-center" @tap="navigateTo('/plugins/extensions/partner/agency')">
							<view class="icon iconfont icon-tuandui" :style="{color:textColor}"></view>
							<view class="text">我的供货商</view>
						</view>
							<view class="text-icon flex flex-column-x-center" @click="refresh('收益')">
							<view class="icon iconfont icon-icon" :style="{color:textColor}"></view>
							<view class="text">我的收益</view>
						</view>
						<view class="text-icon flex flex-column-x-center" @click="refresh('明细')">
							<view class="icon iconfont icon-shouyi" :style="{color:textColor}"></view>
							<view class="text">收益明细</view>
						</view>
					</view>
					<view class="order">
						<view class="order-tabs flex">
							<view class="tab flex-grow-1 flex-x-center flex-y-center" v-for="(item, i) in 3" :key="i" :style="{color:tabIndex ==i ?textColor:''}"
							 @tap="tabClick(i)">
								<span class="name" :style="{'border-bottom':tabIndex ==i ?'2px solid'+textColor:''}">{{ orderListTabs[i] }}</span>
							</view>
						</view>
						<view class="order-list" v-if="list&&tabIndex<2  && list.length > 0">
							<view class="item" v-for="(item, i) in list" :key="i">
								<view class="user-status">
									<image :src="item.avatar_url" mode="aspectFill" class="acatar"></image>
									<view class="name-datetime">
										<view class="name">
											{{ item.nickname }}
											<view class="id" :style="{color:textColor,border:'1px solid'+textColor}">ID:{{ item.user_id }}</view>
										</view>
										<view class="datetime">下单时间:{{ item.created_at }}</view>
									</view>

								</view>
								<view class="info">
									<view class="mark">
										<view class="goods-name">商品名称: {{ item.goods_name }}</view>
										<view class="order-id">订单编号: {{ item.order_no }}</view>
									</view>
									<view class="money">
										<view class="order-money">商品金额: {{ item.goods_price }}元</view>
										<view class="commission" :style="{color:textColor}">股东分红比例：{{item.price}}</view>
									</view>
								</view>
							</view>
						</view>

						<view class="order-list" v-if="list&&tabIndex==2&& list.length > 0">
							<view class="item" v-for="(item, i) in list" :key="i">
								<view class="user-status">
									<view class="name-datetime">
										<view class="datetime">结算时间:{{ item.created_at }}</view>
									</view>
								</view>
								<view class="info">
									<view class="mark">
										<view class="goods-name">开始日期: {{ item.start_time }}</view>
										<view class="order-id">开始结束: {{ item.end_time }}</view>
									</view>
									<view class="money">
										<view class="order-money">分红类型: {{ item.price_type }}</view>
										<view class="commission">到账金额：{{item.price}}</view>
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
		</view>
		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				loading: false,
				data: null,
				orderListTabs: ['分红收益', '额外奖励', '结算记录'],
				list: [],
				info: null,
				is_no_more: false,
				page: 1,
				tabIndex: 0,
				navBg:'',
				navCol:'',
				textColor:'#bc0100',
				bg_url:'',
			};
		},
		onLoad() {
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.navBg = this.globalSet('navBg');
			this.navCol = this.globalSet('navCol');
			
			this.getData();
			this.getList()
		},
		methods: {
			refresh(str){
				this.page = 1;
				this.type = 0;
				this.page_count = 0;
				this.is_no_more = false;
				if(str == '明细'){
					this.tabIndex = 2;
					this.list = [];
					this.getPriceList();
				}else{
					this.tabIndex = 0;
					this.getList('refresh');
				}
				
			},
			navigateTo(url) {
				uni.navigateTo({
					url: url,
					fail: function(e) {
						console.log(e);
					}
				})

			},
			tabClick(index) {
				this.tabIndex = index
				this.list = [];
				this.is_no_more = false;
				this.page = 1;
				if (index < 2) {
					this.getList();
				} else {
					this.getPriceList();
				}
			},
			openUrl(key) {
				let url = '';
				switch (key) {
					case 1:
						url = '/pages/extensions/dealer/team'
						break;
					default:
						console.log("无法跳转");
						return;
				}
				uni.navigateTo({
					url
				})
			},

			getList(str) {
				if (this.is_no_more) {
					uni.showToast({
						title: '没有更多数据'
					})
					return;
				}
				this.loading = true;
				this.$http
					.request({
						url: this.$api.plugin.boss.log_list,
						data: {
							page: this.page,
							type: this.tabIndex
						}
					}).then(res => {
						this.loading = false;
						if (res.code == 0) {
							if(str == 'refresh'){
								this.list = res.data.list;
							}else{
								if (this.page == 1) {
									this.list = res.data.list
								} else {
									this.list = this.list.concat(res.data.list)
								}
								if (res.data.pagination.page_count > this.page) {
									this.page++;
								} else {
									this.is_no_more = true;
								}
							}
						} else {
							uni.showToast({
								title: res.msg
							})
						}
					});
			},
			getPriceList() {
				if (this.is_no_more) {
					uni.showToast({
						title: '没有更多数据'
					})
					return;
				}
				this.loading = true;
				this.$http
					.request({
						url: this.$api.plugin.boss.price_list,
						data: {
							page: this.page,
						}
					}).then(res => {
						this.loading = false;
						if (res.code == 0) {
							if (this.page == 1) {
								this.list = res.data.list
							} else {
								this.list = this.list.concat(res.data.list)
							}
							if (res.data.pagination.page_count > this.page) {
								this.page++;
							} else {
								this.is_no_more = true;
							}
						} else {
							uni.showToast({
								title: res.msg
							})
						}
					});
			},
			getData() {
				this.loading = true;
				this.$http
					.request({
						url: this.$api.plugin.boss.info,
					}).then(res => {
						this.loading = false;
						if (res.code == 0) {
							this.info = res.data.info;

							if (this.info.is_boss == 0) {

								uni.showModal({
									title: '提示',
									content: '你不是股东',
									success: function() {

										uni.navigateBack({
											delta: 1
										})
									}
								})
							}
							
							
						}else{
							uni.showModal({
								title: '提示',
								content: res.msg,
								success: function() {
							
									uni.navigateBack({
										delta: 1
									})
								}
							})
							
							
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
				// this.getList(_status);
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
								font-size: 11pt;
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
									padding-bottom: 4rpx;
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
											display: flex;
											line-height: 37rpx;

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
