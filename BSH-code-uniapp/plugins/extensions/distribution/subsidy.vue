<template>
	<view class="app">
		<com-nav-bar left-icon="back" title="复购奖励" :status-bar="true" background-color="#BC0100" :border="false" color="#ffffff"></com-nav-bar>
		<view class="app-body">
			<view class="content">
				<view class="content-head" v-if="detail">
					<image src="https://pic.downk.cc/item/5ecbac9ac2a9a83be58f7458.png" mode="scaleToFill" class="jx-bg"></image>
					<view class="super">
						<image :src="detail.avatar_url || 'http://yingmlife-1302693724.cos.ap-guangzhou.myqcloud.com/uploads/images/original/20201216/15262b999e48acc5891864e3f2463cb0.jpg'" mode="aspectFill"
						 class="acatar"></image>
						<view class="userinfo">
							<!-- <view class="username">{{parent.username}}(推荐人)</view> -->
							<view class="username">推荐人: {{ detail.parent_name }}</view>
							<view class="desc">
								<span class="iconfont icon-huiyuan1"></span>
								{{ detail.parent_level_name }}
							</view>
							<view class="tel" v-if="detail.mobile != ''">
								<span class="iconfont icon-dianhua3"></span>
								{{ detail.mobile }}
							</view>
						</view>
					</view>
					<view class="reward-info">
						<view class="reward-info-cont">
							<view class="reward-info-box">
								<view>累计奖励</view>
								<view><text>¥</text>{{detail.subsidy_total_price}}</view>
							</view>
							<view class="reward-info-box">
								<view>上月发放奖励</view>
								<view><text>¥</text>{{detail.subsidy_last_price}}</view>
							</view>

						</view>
					</view>
					<view class="reward-details">
						<view class="reward-details-title">
							<text>奖励明细</text>
						</view>
						<view class="reward-details-box">
							<view class="reward-details-cont" v-if="list.length" v-for="(item,index) in list">
								<view class="reward-details-cont-info">
									<view class="reward-details-cont-info-n">结算月份：{{item.month}}</view>
									<view class="reward-details-cont-info-n">新增团队熟练：{{item.team_new_count}}元</view>
									<view class="reward-details-cont-info-time">{{item.created_at}}</view>
								</view>
								<view class="reward-details-cont-num">+{{item.price}}<text>元</text></view>
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>

		<!--加载loadding-->
		<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="!pullUpOn" bgcolor="#FFFFFF"></main-nomore>
		<main-loading :visible="loading"></main-loading>
		<!--加载loadding-->
	</view>
</template>

<script>
	const _status = 'refresh';
	export default {
		data() {
			return {
				page: 1,
				rebuy_total_price: '',
				rebuy_last_price: '',
				list: [],
				detail: null,
				loadding: false,
				pullUpOn: true,
				loading: false
			};
		},
		onLoad() {
			this.getDetail();
			this.getList();
		},
		computed: {

		},
		methods: {
			getList() {
				if (this.is_no_more) {
					uni.showToast({
						title: '暂无更多数据'
					})
					return;
				}

				uni.showLoading({
					title: '加载中'
				})

		 

				this.$http.request({
					url: this.$api.plugin.distribution.subsidy_price_list,
					data: {
						page: this.page,
						status: this.status
					}
				}).then((res) => {
					console.log(res);
					uni.hideLoading();
					if (res.code == 0) {
						if (this.page == 1) {
							this.list = res.data.list
						}
						if (this.page != 1) {
							this.list = this.list.concat(res.data.list);
						}
						// 判断 页面数据是否大于当前页面
						if (res.data.pagination.page_count > this.page) {
							// 如果大于当前页面 页面++
							this.page++;
						} else {
							this.is_no_more = true;
						}
					} else {
						uni.showToast({
							title: res.msg
						})
					}
				})

			},

			getDetail() {
				this.loading = true;
				this.$http
					.request({
						url: this.$api.plugin.distribution.info,
						method: 'GET'
					})
					.then(res => {
						this.loading = false;
						if (res.code == 0) {
							this.detail = res.data.info;
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
			}, 1000);
		},
		onReachBottom() {
			 this.getList()
		}
	};
</script>

<style lang="scss" scoped>
	.app {
		min-height: 100%;
		background-color: #f7f7f7;
		padding-bottom: 10px;

		.app-header {
			padding: 14rpx 20rpx;
			background-color: #bc0100;
			color: #ffffff;
			position: relative;

			.back {
				position: absolute;
				font-size: 13pt;
				top: 20rpx;
				left: 20rpx;
				width: 60rpx;
			}

			.title {
				line-height: 60rpx;
				text-align: center;
				font-weight: 700;
			}
		}

		.app-body {
			.content-head {
				width: 100%;
				position: relative;
				padding-top: 130rpx;

				.jx-bg {
					position: absolute;
					top: 0;
					width: 100%;
					height: 610rpx;
				}

				.super {
					position: relative;
					margin-bottom: 60rpx;
					background-color: #ffffff;
					border-top-left-radius: 8rpx;
					border-top-right-radius: 8rpx;
					margin: 0 30rpx;
					padding: 32rpx;
					display: flex;
					align-items: center;

					.acatar {
						width: 122rpx;
						height: 122rpx;
						border-radius: 50%;
						margin-right: 30rpx;
					}

					.userinfo {

						.username,
						.desc,
						.tel {
							line-height: 48rpx;
						}

						.username {
							color: #000000;
							font-weight: bold;
							font-size: 11pt;
						}

						.desc,
						.tel {
							color: #bc0100;
							font-size: 9pt;
						}

						.iconfont {
							font-size: 10pt;
							margin-right: 8rpx;
						}
					}
				}
			}

			.reward-info {
				position: relative;
				padding: 0 30rpx;

				.reward-info-cont {
					background-color: #FFFFFF;
					display: flex;
					height: 130rpx;
					align-items: center;
					font-size: 10pt;
					border-bottom-left-radius: 8rpx;
					border-bottom-right-radius: 8rpx;

					.reward-info-box {
						flex: 1;
						text-align: center;
						color: #000000;
					}

					.reward-info-box>view>text {
						font-size: 8pt;
					}
				}
			}

			.reward-details {
				position: relative;
				margin-top: 30rpx;
				padding: 0 30rpx;

				.reward-details-title {
					border-top-left-radius: 8rpx;
					border-top-right-radius: 8rpx;
					font-size: 12pt;
					font-weight: bold;
					padding-left: 50rpx;
					height: 80rpx;
					line-height: 80rpx;
				}

				.reward-details-title>text {
					position: relative;
					color: #444;
				}

				.reward-details-title>text::before {
					content: "";
					width: 8rpx;
					height: 100%;
					position: absolute;
					background-color: #000;
					top: 0;
					left: -25rpx;
					border-radius: 8rpx;
					background-color: #BC0101;
				}
			}

			.reward-details>view {
				background-color: #fff;
			}

			.reward-details-box {
				padding: 0 30rpx;

				.reward-details-cont {
					padding: 30rpx 0;
					display: flex;
					align-items: center;

					.reward-details-cont-info {
						flex: 1;
						min-width: 280rpx;

						.reward-details-cont-info-n {
							font-size: 10pt;
							color: #333;
						}

						.reward-details-cont-info-time {
							font-size: 9pt;
						}
					}

					.reward-details-cont-num {
						min-width: 150rpx;
						text-align: right;
						font-size: 14pt;
						color: #BC0100;
						// 订单总额，商品数量
					}

					.reward-details-cont-num>text {
						font-size: 10pt;
					}
				}
			}
		}
	}

	.flex-column-x-center {
		display: flex;
		flex-direction: column;
		align-items: center;
	}
</style>
