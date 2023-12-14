<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="权益中心" :status-bar="true" background-color="#BC0100" :border="false" color="#ffffff"></com-nav-bar>
		<view class="container">
			<image src="https://pic.downk.cc/item/5eccd9e9c2a9a83be50140b6.png" mode="aspectFill" class="jx-bg"></image>
			<!-- <view class="content" v-if="data"> -->
			<view class="content">
				<view class="content-head">
					<view class="user-info">
						<image :src="'' || 'http://yingmlife-1302693724.cos.ap-guangzhou.myqcloud.com/uploads/images/original/20201216/15262b999e48acc5891864e3f2463cb0.jpg'" mode="aspectFill" class="acatar"></image>
						<view class="userinfo">
							<view class="username">{{ '' || '七件事集团' }}</view>
							<view class="desc">账号ID: {{ '' || '223648' }}</view>
							<view class="level">区域等级:{{ '' || '区域总代' }}</view>
						</view>
						<!-- <view class="withdraw flex" @click="">
							<view class="icon-text flex">
								<navigator url="/pages/extensions/dealer/interests/activation">
									<view class="text">我要激活</view>
								</navigator>
							</view>
						</view> -->
					</view>
				</view>
				<view class="content-bottom">
					<view class="items">
						<view class="item" v-for="(item, i) in dataList" :key="i">
							<view class="item-head flex flex-x-between flex-y-center">
								<view class="title">{{item}}</view>
								<view class="ratio">{{ i + 1 }}/100</view>
							</view>
							<view class="item-body">
								<view class="inner flex flex-x-between flex-y-center">
									<view class="line flex-grow-1"></view>
									<view class="text">等级权益</view>
									<view class="line flex-grow-1"></view>
								</view>
								<view class="legend">
									<view class="legend-item">
										<view class="round"></view>
										<view class="text">全场运费减免{{ i + 1 }}5%</view>
									</view>
									<view class="legend-item">
										<view class="round"></view>
										<view class="text">日常生活用品区打8.5折</view>
									</view>
									<view class="legend-item">
										<view class="round"></view>
										<view class="text">可领取零食产品区35元优惠卷3张</view>
									</view>
									<view class="legend-item">
										<view class="round"></view>
										<view class="text">连续签到30天可获得128元优惠卷1张</view>
									</view>
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
			dataList: ['区域总代','市级总代','省级总代']
		};
	},
	onLoad() {
		// this.getData();
		console.log('123');
	},
	methods: {
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
				color: #1f1f1f;
				padding: 20rpx 30rpx;
				font-size: 9pt;

				.items {
					.item {
						border-radius: 15rpx;
						margin-bottom: 20rpx;
						overflow: hidden;
					}

					.item-head {
						padding: 0 30rpx;
						background-color: #f5e7cd;
						height: 97rpx;

						.title {
							color: #333333;
							font-size: 11pt;
						}
						.ratio {
							color: #6a6a6a;
						}
					}
					.item-body {
						padding: 30rpx;
						background-color: #ffffff;
						.inner {
							.text {
								margin: 0 40rpx;
								font-size: 10pt;
								color: #6a6a6a;
							}

							.line {
								height: 2rpx;
								background-color: #cccccc;
							}
						}

						.legend {
							color: #999999;
							font-size: 9pt;

							.legend-item {
								display: flex;
								align-items: center;
								line-height: 48rpx;
							}
						}

						.round {
							width: 15rpx;
							height: 15rpx;
							background-color: #cccccc;
							border-radius: 50%;
							margin-right: 10rpx;
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
