<template>
	<view class="app">
		<!-- <view class="app-header">
			<view class="back iconfont icon-fanhui" @click="back()"></view>
		</view> -->
		<com-nav-bar :fixed="true" @clickLeft="back" left-icon="back" title="推广中心" :status-bar="true" :background-color="navBg" :border="false"
		:color="navCol"></com-nav-bar>
		<view class="app-body">
			<image :src="bg_url" mode="aspectFill" class="jx-bg"></image>
			<view class="content" v-if="data">
				<view class="content-head">
					<view class="withdraw flex" @click="openUrl('/plugins/extensions/cash/edit')">
						<view class="icon-text flex" style="margin-top: 20rpx;">
							<view class="icon iconfont icon-tixian"></view>
							<view class="text">我要提现</view>
							<view class="icon iconfont icon-right"></view>
						</view>
					</view>
					<view class="income">
						<view class="total flex">
							<view class="text">总金额 (元)</view>
							<view class="sum">{{data.total_income}}</view>
							<view class="yesterday" :style="{color:'#FF7104'}">昨日收益{{data.yesterday_income}}元</view>
						</view>
						<view class="status flex">
							<view class="text-money flex flex-column-x-center">
								<view class="text">待结算</view>
								<view class="money">{{data.income_frozen}}元</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">可提现</view>
								<view class="money">{{data.income}}元</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">已提现</view>
								<view class="money">{{data.total_cash}}元</view>
							</view>
						</view>
					</view>
					<view class="team-benefits">
						<view class="text-icon flex flex-column-x-center" @click="openUrl('/plugins/extensions/team/index')">
							<view class="icon iconfont icon-tuandui" :style="{color:'#FF7104'}"></view>
							<view class="text">我的客户</view>
						</view>
						<view class="text-icon flex flex-column-x-center" @click="openUrl('/plugins/extensions/income/income')">
							<view class="icon iconfont icon-shouyi" :style="{color:'#FF7104'}"></view>
							<view class="text">收益明细</view>
						</view>
						<view class="text-icon flex flex-column-x-center" @click="openUrl('/plugins/extensions/cash/details')">
							<view class="icon iconfont icon-icon" :style="{color:'#FF7104'}"></view>
							<view class="text">提现明细</view>
						</view>
					</view>
				</view>
				<view class="content-bottom">
					<view class="dividend">
						<view class="card top left flex" v-if="data.distribution && data.distribution.is_display" @click.stop="openUrl('/plugins/extensions/distribution/index')">
							<view class="icon iconfont icon-fenxiao1" :style="{color:'#FF7104'}"></view>
							<view class="info">
								<view class="title">{{'分销佣金'}}</view>
								<view class="desc">{{data.distribution.level_name || `青铜`}}</view>
								<view class="money" :style="{color:'#FF7104'}">
									<span class="dot">¥</span>
									{{data.distribution.frozen_price || '0.00'}}
								</view>
							</view>
						</view>
						<view class="card top right flex" v-if="data.agent && data.agent.is_display" @click.stop="openUrl('/plugins/extensions/dealer/index')">
							<view class="icon iconfont icon-tuandui2" :style="{color:'#FF7104'}"></view>
							<view class="info">
								<view class="title">{{'团队佣金'}}</view>
								<view class="desc">{{data.agent.level_name || '区域服务商'}}</view>
								<view class="money" :style="{color:'#FF7104'}">
									<span class="dot">¥</span>
									{{'0.00'}}
								</view>
							</view>
						</view>
						<view class="card bottom top left flex" v-if="data.boss && data.boss.is_display" @click.stop="openUrl('/plugins/extensions/partner/index')">
							<view class="icon iconfont icon-gudong1" :style="{color:'#FF7104'}"></view>
							<view class="info">
								<view class="title">{{'股东分红'}}</view>
								<view class="desc">{{data.boss.level_name || '创始人'}}</view>
								<view class="money" :style="{color:textColor}">
									<span class="dot">¥</span>
									{{'0.00'}}
								</view>
							</view>
						</view>
						<view class="card bottom top right flex" v-if="data.area && data.area.is_display" @click.stop="openUrl('/plugins/extensions/area/index')">
							<view class="icon iconfont icon-address" :style="{color:'#FF7104'}"></view>
							<view class="info">
								<view class="title">{{'区域分红'}}</view>
								<view class="desc">{{data.area.level_name || '市代理'}}</view>
								<view class="money" :style="{color:'#FF7104'}">
									<span class="dot">¥</span>
									{{'0.00'}}
								</view>
							</view>
						</view>
						<!-- <view class="card bottom left flex" @click.stop="openUrl('/plugins/extensions/score/score')">
							<view class="icon iconfont icon-gudong1" :style="{color:textColor}"></view>
							<view class="info">
								<view class="title">{{'积分宝'}}</view>
								<view class="desc">{{'积分宝'}}</view>
							</view>
						</view> -->
						<view class="card bottom right flex" v-if="data.stock && data.stock.is_display" @click.stop="openUrl('/plugins/repertory-cloud/index')">
							<view class="icon iconfont icon-jifen" :style="{color:'#FF7104'}"></view>
							<view class="info">
								<view class="title">{{'云仓'}}</view>
								<view class="desc">{{'云仓'}}</view>
							</view>
						</view>
						<view class="card bottom left flex" v-if="data.short_video && data.short_video.is_display" @click.stop="openUrl('/plugins/short-video/dividends/index')">
							<view class="icon iconfont icon-jifen" :style="{color:'#FF7104'}"></view>
							<view class="info">
								<view class="title">{{'短视频分红'}}</view>
								<view class="money" :style="{color:'#FF7104'}">
									<span class="dot">¥</span>
									{{data.short_video.total_price}}
								</view>
							</view>
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
				data: null,
				loading: false,
				
				textColor:'#bc0100',
				bg_url:'',
				navBg:'',
				navCol:'',
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			}
		},
		onLoad() {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.navBg = this.globalSet('navBg');
			this.navCol = this.globalSet('navCol');
			
			this.getData();
		},
		methods: {
			getData() {
				this.loading = true;
				this.$http.request({
					url: this.$api.plugin.extensions.info,
					method: 'POST'
				}).then(res => {
					this.loading = false;
					if (res.code == 0) {
						this.data = res.data;
					}
				})
			},
			back() {
				this.navBack();
			},
			openUrl(url) {
				if(url=='/plugins/extensions/income/income'){
					uni.navigateTo({
						url:url+"?name="+'income'
					})
				}else{
					uni.navigateTo({
						url
					})
				}
			}
		},
		onPullDownRefresh() {
			setTimeout(() => {
				uni.stopPullDownRefresh()
				// this.getDateList(_status);
			}, 1000);
		}
	}
</script>

<style lang="scss" scoped>
	.app {
		min-height: 100%;
		background-color: #F7F7F7;

		.app-header {
			padding: 14rpx 20rpx;
			background-color: #BC0100;
			color: #FFFFFF;

			.back {
				font-size: 13pt;
				line-height: 60rpx;
				width: 60rpx;
			}

			.title {
				line-height: 60rpx;
				text-align: center;
				font-weight: 700;
			}
		}

		.app-body {
			color: #FFFFFF;
			position: relative;

			.jx-bg {
				position: absolute;
				top: 0;
				width: 100%;
				height: 520rpx;
			}

			.content {
				font-size: 0;
				position: relative;

				.content-head {
					// padding-bottom: 100rpx;

					.withdraw {
						justify-content: flex-end;

						.icon-text {
							align-items: center;
							background-color: #EC7676;
							font-size: 9pt;
							line-height: 54rpx;
							padding: 0 10rpx;
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

					.income {
						padding: 0 30rpx;
						margin-bottom: 40rpx;

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
								background-color: #FFFFFF;
								color: #BC0100;
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
				}

				.team-benefits {
					padding: 30rpx 0;
					color: #212121;
					background-color: #FFFFFF;
					display: flex;
					margin: 0 30rpx;
					border-radius: 10rpx;
					// box-shadow: 0 8rpx 11rpx 3rpx rgba(8, 1, 3, 0.07);

					.icon {
						font-size: 18pt;
						color: #BC0100;
						line-height: 56rpx;
					}

					.text {
						font-size: 11pt;
					}
				}

				.content-bottom {
					color: #1F1F1F;
					padding: 20rpx 30rpx;

					.doing {
						margin-top: 16rpx;
						height: 125rpx;

						.img {
							width: 100%;
							height: 100%;
						}
					}

					.dividend {
						margin-top: 30rpx;
						display: flex;
						flex-wrap: wrap;

						.card {
							width: 49.5%;
							background-color: #FFFFFF;
							height: 200rpx;
							align-items: center;
							padding-left: 32rpx;

							.icon {
								color: #BC0100;
								width: 86rpx;
								line-height: 86rpx;
								font-size: 25pt;
								border-radius: 50%;
								box-shadow: 0px 9rpx 19rpx 2rpx rgba(8, 1, 3, 0.1);
								text-align: center;
								margin-right: 32rpx;
							}

							.info {
								font-size: 11pt;

								.title {
									color: #1F1F1F;
									font-weight: bold;
								}

								.desc {
									color: #717171;
									font-size: 9pt;
								}

								.money {
									color: #BC0100;

									.dot {
										font-size: 9pt;
										margin-right: 6rpx;
									}
								}
							}

							&.top {
								border-bottom: 0.5rpx solid #E0E0E0;

								&.left {
									border-top-left-radius: 15rpx;
								}

								&.right {
									border-top-right-radius: 15rpx;
								}
							}

							&.bottom {
								// border-top: 0.5rpx solid #E0E0E0;

								&.left {
									border-bottom-left-radius: 15rpx;
								}

								&.right {
									border-bottom-right-radius: 15rpx;
								}
							}

							&.left {
								border-right: 0.5rpx solid #E0E0E0;
							}

							&.right {
								// border-left: 0.5rpx solid #E0E0E0;
							}
						}
					}
				}
			}
		}
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
