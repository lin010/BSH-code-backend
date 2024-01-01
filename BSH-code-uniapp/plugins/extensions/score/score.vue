<template>
	<view class="app">
		<com-nav-bar @clickLeft='back' :fixed="true" left-icon="back" :title="`${currencyAlias.integral_alias}宝`" :status-bar="true" :background-color="navBg" :border="false" :color="navCol"></com-nav-bar>
		<view class="container">
			<image :src="bg_url" mode="aspectFill" class="jx-bg"></image>
			<!-- <view class="content" v-if="data"> -->
			<view class="content">
				<view class="content-head">
					<view class="user-info">
						<image :src="avatar_url" mode="aspectFill" class="acatar"></image>
						<view class="userinfo">
							<view class="username">{{ nickname }}</view>
							<view class="desc">账号ID: {{ '' || user_id }}</view>
						</view>
					</view>
				</view>
				<view class="content-bottom">
					<view class="income margin-bottom-30rpx">
						<view class="status flex">
							<view class="text-money flex flex-column-x-center">
								<view class="text">累计{{currencyAlias.integral_alias}}</view>
								<view class="money">{{ total_score }}</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">可用{{currencyAlias.integral_alias}}</view>
								<view class="money">{{ score }}</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">已用{{currencyAlias.integral_alias}}</view>
								<view class="money">{{ use_score }}</view>
							</view>
						</view>
					</view>

					<view class="activation-code-list  margin-bottom-30rpx flex flex-x-between" style="display: none;">
						<view>我的激活码</view>
						<navigator url="./interests/activation-code-list">
							<view class="click">立即查看></view>
						</navigator>
					</view>


					<view class="order">
						<view class="order-tabs flex">
							<block v-for="(item, i) in orderListTabs.length" :key="i">
								<view class="tab flex-grow-1 flex-x-center flex-y-center" :style="{color:currentTabIndex == i ?textColor:''}" @click="tabChange(i)">
									<span class="name" :style="{'border-bottom':currentTabIndex == i ?'2px solid'+textColor:''}">{{ orderListTabs[i] }}</span>
								</view>
							</block>
						</view>

						<!-- 积分 item -->
						<view class="order-list" v-if="list && list.length > 0">
							<view class="item flex" v-for="(item, i) in list" :key="i">

								<view :class="currentTabIndex == 0 ||currentTabIndex == 1? 'item_left' : 'item_lefts'">
									<text>￥</text>
									{{item.score}}
								</view>

								<view :class="currentTabIndex == 0 ||currentTabIndex == 1? 'item_right' : 'item_rights'">
									<view class="item_right_top">
										{{item.score}}{{currencyAlias.integral_alias}}
									</view>
									<view class="item_right_bottom">
										<text class="txx">发放时间：{{item.created_at}}</text>
									</view>
									<view :class="currentTabIndex == 0? 'top_bgc' : 'top_past'">
										<block v-if="currentTabIndex == 0 ">已发放</block>
										<block v-if="currentTabIndex == 1 ">待发放</block>
										<block v-if="currentTabIndex == 2 ">已过期</block>
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
				orderListTabs: ['已发放', '待发放', '已过期'],
				currentTabIndex: 0,
				page: 1,
				is_no_more: false,
				use_score: '',
				score: '',
				total_score: '',
				nickname: '',
				avatar_url: '',
				list: [],
				user_id:'',
				
				textColor:'#bc0100',
				bg_url:'',
				button:[],
				navBg:'',
				navCol:'',
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		onReachBottom: function(e) {
			this.getList();
		},
		onLoad() {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.button = this.globalSet('btnCol');
			this.navBg = this.globalSet('navBg');
			this.navCol = this.globalSet('navCol');
			
			this.getInfo();
			this.getList();
		},
		methods: {
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
						url: this.$api.plugin.score.info
					})
					.then(res => {
						this.loading = false;
						if (res.code == 0) {
							this.use_score = res.data.use_score;
							this.total_score = res.data.total_score;
							this.avatar_url = res.data.avatar_url;
							this.nickname = res.data.nickname;
							this.score = res.data.score;
							this.user_id=res.data.user_id
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
				console.log('160='+this.$api.plugin.score.log_list+"-----------"+this.page+'---------'+this.currentTabIndex)
				this.$http
					.request({
						url: this.$api.plugin.score.log_list,
						data: {
							page: this.page,
							status: this.currentTabIndex //类型
						}
					})
					.then(res => {
						console.log('169res='+JSON.stringify(res));
						this.loading = false;
						if (res.code == 0) {
							if (this.page == 1) {
								this.list = res.data.list;
							} else {
								this.list = this.list.concat(res.data.list);
							}
							if (this.page < res.data.pagination.page_count) {
								this.page++;
							} else {
								this.is_no_more = true;
							}
						} else {
							uni.showToast({
								title: res.msg,
								icon:'none'
							});
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
								width: 100%;
								height: 250rpx;
								border-radius: 10rpx;
								padding: 20rpx;
								margin: 20rpx 0;
								border-bottom: 1rpx solid #f3f3f3;

								.item_left {
									width: 40%;
									height: 210rpx;
									background-color: #BA0203;
									text-align: center;
									line-height: 210rpx;
									font-size: 18pt;
									font-weight: 600;
									color: #fff;
									position: relative;
									border-radius: 10rpx;
								}

								.item_lefts {
									width: 40%;
									height: 210rpx;
									background-color: #CDCBCE;
									text-align: center;
									line-height: 210rpx;
									font-size: 18pt;
									font-weight: 600;
									color: #fff;
									position: relative;
									border-radius: 10rpx;
								}

								.item_left>text {
									font-size: 12pt;
								}

								.item_right::after {
									content: "";
									background-color: #FFFFFF;
									width: 20rpx;
									height: 20rpx;
									border-radius: 50%;
									position: absolute;
									top: -10rpx;
									left: -10rpx;
								}

								.item_right::before {
									content: "";
									background-color: #FFFFFF;
									width: 20rpx;
									height: 20rpx;
									border-radius: 50%;
									position: absolute;
									bottom: -10rpx;
									left: -10rpx;
								}

								.item_rights::after {
									content: "";
									background-color: #FFFFFF;
									width: 20rpx;
									height: 20rpx;
									border-radius: 50%;
									position: absolute;
									top: -10rpx;
									left: -10rpx;
								}

								.item_rights::before {
									content: "";
									background-color: #FFFFFF;
									width: 20rpx;
									height: 20rpx;
									border-radius: 50%;
									position: absolute;
									bottom: -10rpx;
									left: -10rpx;
								}

								.item_right {
									width: 60%;
									height: 210rpx;
									padding: 40rpx 0 20rpx 20rpx;
									background-color: #FBF8F3;
									border-top-right-radius: 20rpx;
									border-bottom-right-radius: 20rpx;
									position: relative;
								}

								.item_rights {
									width: 60%;
									height: 210rpx;
									padding: 40rpx 0 20rpx 20rpx;
									background-color: #FAF7FA;
									// border-radius: 20rpx;
									position: relative;
									border-top-right-radius: 20rpx;
									border-bottom-right-radius: 20rpx;
								}

								.top_bgc {
									background-color: #BA0203;
									padding: 5rpx 10rpx 5rpx 10rpx;
									position: absolute;
									top: 0;
									right: 0;
									width: 110rpx;
									height: 50rpx;
									color: #fff;
									border-top-right-radius: 20rpx;
									border-bottom-left-radius: 20rpx;
								}

								.top_past {
									background-color: #ccc;
									padding: 5rpx 10rpx 5rpx 10rpx;
									position: absolute;
									top: 0;
									right: 0;
									color: #fff;
									width: 110rpx;
									height: 50rpx;
									border-top-right-radius: 20rpx;
									border-bottom-left-radius: 20rpx;
								}

								.item_right_top {
									font-size: 10pt;
								}

								.item_right_bottom {
									width: 100%;
									margin-top: 70rpx;
								}

								.item_right_bottom .txx {
									font-size: 10pt;
								}

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

			.from {
				margin: 20upx 30upx;
				padding: 51upx 30upx;
				border-radius: 39upx;
				background-color: #ffffff;
				color: #000000;

				.from-item {
					font-size: 13.5pt;
					margin-bottom: 68upx;
					position: relative;
				}

				.from-input {
					font-size: 10.5pt;
					height: 50upx;
					line-height: 50upx;
					border-bottom: 1upx solid #cccccc;
					margin-left: 26upx;
				}

				.from-input-placeholder {
					font-size: 10.5pt;
					color: #989696;
				}

				.icon-left {
					position: absolute;
					right: 0;
					width: 32upx;
					font-size: 15pt;
					color: #666666;
				}

				.submit {
					color: #ffffff;
					height: 78upx;
				}
			}
		}
	}
	.btn {
		font-size: 34upx;
		border-radius: 39upx;
	}
	.margin-bottom-30rpx {
		margin-bottom: 30upx;
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
