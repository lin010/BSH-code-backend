<template>
	<view class="app">
		<!-- <image src="https://pic.downk.cc/item/5eccd9e9c2a9a83be50140b6.png" mode="aspectFill" class="jx-bg"></image> -->
		<view class="container">
			<image src="https://pic.downk.cc/item/5eccd9e9c2a9a83be50140b6.png" mode="aspectFill" class="jx-bg"></image>
			<!-- <view class="content" v-if="data"> -->
			<view class="content">
				<view class="content-head">
					<view class="user-info">
						<image :src="'' || 'http://yingmlife-1302693724.cos.ap-guangzhou.myqcloud.com/uploads/images/original/20201216/15262b999e48acc5891864e3f2463cb0.jpg'" mode="aspectFill" class="acatar"></image>
						<view class="userinfo">
							<view class="username">{{ '' || '武江区茗鼎茶行' }}</view>
							<view class="desc">ID: {{ '' || '223648' }}</view>
							<view class="level">绑定社区：太阳城芙蓉湾</view>
						</view>
						<view class="withdraw flex">
							<navigator url="./edit">
								<view class="icon-text flex">
									<view class="icon iconfont icon-shezhi"></view>
									<view class="text">
										<navigator url="./edit"></navigator>
									</view>
								</view>
							</navigator>
						</view>
					</view>
				</view>
				<view class="content-bottom">
					<view class="income ffffff-redius margin-bottom-10px">
						<view class="income-title">团长概况</view>
						<view class="status flex">
							<view class="text-money flex-grow-1 flex-col flex-y-center">
								<view class="money">{{ '156.00' }}</view>
								<view class="text">总收入(元)</view>
							</view>
							<view class="text-money flex-grow-1 flex-col flex-y-center">
								<view class="num">{{ 8 }}</view>
								<view class="text">总订单</view>
							</view>
							<view class="text-money flex-grow-1 flex-col flex-y-center">
								<view class="num">{{ 4 }}</view>
								<view class="text">会员数</view>
							</view>
						</view>
					</view>
					<view class="to-day-detail ffffff-redius margin-bottom-10px">
						<view class="to-day-title font-size-14px">今日详情</view>
						<view class="items flex">
							<view class="item flex-grow-1 flex-col flex-y-center">
								<view class="num">{{ 8 }}</view>
								<view class="text">订单总数(笔)</view>
							</view>
							<view class="item flex-grow-1 flex-col flex-y-center">
								<view class="num">{{ 8 }}</view>
								<view class="text">有效订单(笔)</view>
							</view>
							<view class="item flex-grow-1 flex-col flex-y-center">
								<view class="num">{{ 8 }}</view>
								<view class="text">付款人数</view>
							</view>
							<view class="item flex-grow-1 flex-col flex-y-center">
								<view class="num">{{ '156.00' }}</view>
								<view class="text">销售额(元)</view>
							</view>
							<view class="item flex-grow-1 flex-col flex-y-center">
								<view class="num">{{ 8 }}</view>
								<view class="text">新增会员数</view>
							</view>
							<view class="item flex-grow-1 flex-col flex-y-center">
								<view class="num">{{ 8 }}</view>
								<view class="text">售后订单(笔)</view>
							</view>
							<view class="item flex-grow-1 flex-col flex-y-center">
								<view class="num">{{ 8 }}</view>
								<view class="text">预估收入</view>
							</view>
							<view class="item flex-grow-1 flex-col flex-y-center">
								<view class="num">{{ 15 }}</view>
								<view class="text">今日访客</view>
							</view>
						</view>
					</view>
					<view class="jx-box jx-order-box margin-bottom-10px" v-if="0 == 1">
						<jx-list-cell :arrow="true" padding="0" :lineLeft="false" @click="">
							<view class="jx-cell-header">
								<view class="jx-cell-title">我的订单</view>
								<view class="jx-cell-sub">查看全部订单</view>
							</view>
						</jx-list-cell>
						<view class="jx-order-list">
							<view class="jx-order-item" @tap="openUrl(item.link_url)" v-for="(item, i) in configData.user_center.order_bar" :key="i">
								<view class="jx-icon-box">
									<image :src="item.icon_url" class="jx-order-icon"></image>
									<view class="jx-badge jx-badge-red" v-if="item.num">{{ item.num }}</view>
								</view>
								<view class="jx-order-text">{{ item.name }}</view>
							</view>
						</view>
					</view>
					<view class="toolbox ffffff-redius">
						<jx-list-cell :arrow="true" padding="0" :lineLeft="false" @click="">
							<view class="jx-cell-header">
								<view class="jx-cell-title">社区用户</view>
								<view class="jx-cell-sub">待核销</view>
							</view>
						</jx-list-cell>
						<jx-list-cell :arrow="true" padding="0" :lineLeft="false" @click="">
							<view class="jx-cell-header">
								<view class="jx-cell-title">结算记录</view>
								<view class="jx-cell-sub"></view>
							</view>
						</jx-list-cell>
						<jx-list-cell :arrow="true" padding="0" :lineLeft="false" @click="">
							<view class="jx-cell-header">
								<view class="jx-cell-title">我的收入</view>
								<view class="jx-cell-sub"></view>
							</view>
						</jx-list-cell>
						<jx-list-cell :arrow="true" padding="0" :lineLeft="false" @click="">
							<view class="jx-cell-header" style="border-bottom: 0;">
								<view class="jx-cell-title">清单列表</view>
								<view class="jx-cell-sub"></view>
							</view>
						</jx-list-cell>
					</view>
					
				</view>
			</view>
		</view>

		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
import jxListCell from '@/components/list-cell/list-cell';
export default {
	components:{
		jxListCell
	},
	data() {
		return {
			loading: false,
			data: null
		};
	}
};
</script>

<style lang="scss" scoped>
.app {
	height: 100%;
	background-color: #f7f7f7;
}
.container {
	color: #ffffff;
	position: relative;

	.jx-bg {
		position: absolute;
		top: 0;
		width: 100%;
		height: 240rpx;
	}
	.content {
		font-size: 0;
		position: relative;
		color: #ffffff;

		.content-head {
			.withdraw {
				position: absolute;
				right: 20rpx;
				top: 0;

				.icon-text {
					align-items: center;
					// background-color: #ec7676;
					// padding: 0 20rpx;
					line-height: 60rpx;
					font-size: 9pt;
					border-bottom-left-radius: 27rpx;
					border-top-left-radius: 27rpx;

					.icon {
						font-size: 9pt;
						height: 58rpx;

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
				margin-bottom: 12rpx;
				border-radius: 8rpx;
				padding: 30rpx;
				display: flex;
				align-items: center;

				.acatar {
					width: 141rpx;
					height: 141rpx;
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
			margin-bottom: 60rpx;

			.income {
				.income-title {
					text-align: center;
					color: #000000;
					padding: 30rpx 0;
					font-size: 11.5pt;
				}
				.status {
					font-size: 12pt;
					align-items: center;

					.text-money {
						max-width: 33.33%;
						line-height: 60rpx;
						padding-bottom: 30rpx;
					}
				}
			}

			.to-day-detail {
				.to-day-title {
					color: #333333;
					padding: 0 35rpx;
					line-height: 86rpx;
					border-bottom: 1rpx solid #f2f2f2;
				}

				.items {
					flex-wrap: wrap;
					padding: 20rpx 0 36rpx;

					.item {
						width: 25%;
						margin-bottom: 20rpx;
					}
				}
			}

			.money,
			.num {
				color: #bc0100;
				font-size: 13pt;
				font-weight: bold;
			}
			.text {
				color: #898989;
				font-size: 9pt;
			}
		}

		.jx-box {
			width: 100%;
			background: #fff;
			box-shadow: 0 3rpx 20rpx rgba(183, 183, 183, 0.1);
			border-radius: 10rpx;
			overflow: hidden;
		}
		.jx-order-box {
			height: 300rpx;

			.jx-order-list {
				width: 100%;
				height: 200rpx;
				// padding: 0 30rpx;
				box-sizing: border-box;
				display: flex;
				align-items: center;
				justify-content: space-between;
			}

			.jx-order-item {
				flex: 1;
				display: flex;
				flex-direction: column;
				align-items: center;
			}
			
			.jx-order-icon {
				width: 56rpx;
				height: 56rpx;
				display: block;
			}
			
			.jx-order-text{
				font-size: 10pt;
				font-weight: 400;
				color: #333;
				padding-top: 4rpx;
			}
		}
	}
}
.jx-cell-header {
	width: 100%;
	height: 100rpx;
	padding: 0 44rpx;
	box-sizing: border-box;
	display: flex;
	align-items: center;
	justify-content: space-between;
	border-bottom: 1rpx solid #F2F2F2;
}

.jx-cell-title {
	font-size: 11pt;
	line-height: 30rpx;
	font-weight: 400;
	color: #333333;
}

.jx-cell-sub {
	font-size: 9pt;
	font-weight: 400;
	color: #6a6a6a;
	padding-right: 28rpx;
}

.jx-icon-box {
	position: relative;

	.icon {
		font-size: 22px;
		line-height: 24px;
		margin-right: 20rpx;
	}

	&:last-child {
		.icon {
			margin-right: 10rpx;
		}
	}
}

.jx-badge {
	position: absolute;
	font-size: 9pt;
	height: 30rpx;
	/* #ifdef H5 */
	min-width: 30rpx;
	/* #endif */
	/* #ifndef H5 */
	min-width: 20rpx;
	/* #endif */
	padding: 0 6rpx;
	border-radius: 40rpx;
	right: 10rpx;
	top: -5rpx;
	transform: scale(0.8) translateX(60%);
	transform-origin: center center;
	display: flex;
	align-items: center;
	justify-content: center;
	z-index: 10;
}

.jx-badge-red {
	background: #f74d54;
	color: #fff;
	right: 0;
}

.margin-bottom-10px {
	margin-bottom: 20rpx;
}

.ffffff-redius {
	background-color: #ffffff;
	border-radius: 15rpx;
	overflow: hidden;
}
</style>
