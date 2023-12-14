<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" :title="`${currencyAlias.red_envelope_alias}提现明细`" :status-bar="true" :background-color="navBg" :border="false" :color="navCol"></com-nav-bar>
		<view class="app-body">
			<image :src="bg_url" mode="aspectFill" class="jx-bg"></image>
			<view class="content">
				<view class="content-head">
					<view class="income" v-if="detail">
						<view class="total flex">
							<view class="sum">{{ detail.static_integral }}</view>
							<view class="text">可提现{{currencyAlias.red_envelope_alias}}</view>
						</view>
						<view class="status flex">
							<view class="text-money flex flex-column-x-center">
								<view class="text">本周提现</view>
								<view class="money">{{ detail.week_cash_price }}元</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">本月提现</view>
								<view class="money">{{ detail.month_cash_price }}元</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">累计提现</view>
								<view class="money">{{ detail.total_cash_price }}元</view>
							</view>
						</view>
					</view>
				</view>
				<view class="content-bottom">
					<view class="list" v-if="!loading || detail">
						<view class="tabs">
							<view class="tab flex" :style="{color: dataStatus == item.status?'#FF7104':'','border-bottom':dataStatus == item.status?'4rpx solid'+'#FF7104':''}" v-for="(item, i) in tabs" :key="i" @click="switchTab(item.status)">
								<view class="name">{{ item.name }}</view>
							</view>
						</view>
						<view class="items" v-if="dataList && dataList.length">
							<view class="item" v-for="(item, i) in dataList" :key="i">
								<view class="status flex">
									<view class="name font-size-11">{{ '提现状态' }}</view>
									<view class="desc font-size-9" :style="{background:'#FF7104'}">{{ statusText[item.status] }}</view>
								</view>
								<view class="handling-fee flex" :style="{color:'#FF7104'}">
									<view class="name">{{ '扣除手续费' }}</view>
									<view class="fee" :style="{color:'#FF7104'}">-{{ ((item.price*100) - (item.fact_price*100))/100 }}元</view>
								</view>
								<view class="arrive flex">
									<view class="name">{{ '实际到账' }}</view>
									<view class="fee" :style="{color:'#FF7104'}">+{{ item.fact_price }}元</view>
								</view>
								<view class="arrive " v-if="item.content.reject_content">
									<view class="name">{{ '备注:' }}</view>
									<view class="notice">
										{{item.content.reject_content}}
									</view>
								</view>
							</view>
						</view>
						<view class="items" style="padding: 10rpx 0;" v-else><main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore></view>
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
			detail: null,
			dataList: [],
			pages: {
				total_count: 1,
				page_count: 1,
				pageSize: 20,
				current_page: 1
			},
			dataStatus: -1,
			loadding: false,
			pullUpOn: true,
			loading: false,
			statusText: ['待审核', '待打款', '已打款', '已驳回'],
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
		};
	},
	onLoad() {
		this.textColor = this.globalSet('textCol');
		this.bg_url = this.globalSet('imgUrl');
		this.navBg = this.globalSet('navBg');
		this.navCol = this.globalSet('navCol');
		let currencyAliasConfig = uni.getStorageSync('currencyAlias');
		if(currencyAliasConfig){
			JSON.parse(currencyAliasConfig);
			this.currencyAlias = JSON.parse(currencyAliasConfig);
		}
		this.getDetail();
		this.getDataList(_status, true);
	},
	computed: {
		tabs() {
			let temp = [];
			let tabs = ['全部', '待审核', '待打款', '已打款', '已驳回'];
			tabs.forEach((item, i) => {
				let obj = {};
				(obj.name = item), (obj.status = i - 1);
				temp.push(obj);
			});
			return temp;
		}
	},
	methods: {
		getDataList(status, bool) {
			this.loading = bool ? true : false;
			this.$forceUpdate()
			if (status == _status) {
				this.pages = {
					current_page: 1,
					pageSize: 20,
					page_count: 1,
					total_count: 0
				};
			}
			let { current_page, pageSize } = this.pages;

			this.$http
				.request({
					url: this.$api.plugin.extensions.integral_cash.list,
					method: 'POST',
					data: {
						page: current_page,
						limit: pageSize,
						status: this.dataStatus
					}
				})
				.then(res => {
					this.loading = false;
					if (res.code === 0) {
						let { list, pagination } = res.data;
						this.dataList = status != 'refresh' ? this.dataList.concat(list) : list;
						this.pages = pagination;
						this.pullUpOn = true;
					}
				});
		},
		getDetail() {
			this.loading = true;
			this.$http
				.request({
					url: this.$api.plugin.extensions.integral_cash.detail
				})
				.then(res => {
					this.loading = false;
					if (res.code == 0) {
						this.detail = res.data;
					}
				});
		},
		switchTab(status) {
			this.dataStatus = status;
			this.getDataList(_status, true);
		},
		back() {
			this.navBack();
		}
	},
	onPullDownRefresh() {
		setTimeout(() => {
			uni.stopPullDownRefresh();
			this.getDateList(_status);
		}, 1000);
	},
	onReachBottom() {
		this.loadding = true;
		this.pullUpOn = true;
		let { current_page, page_count } = this.pages;
		setTimeout(() => {
			this.loadding = false;
			if (current_page >= page_count) {
				this.pullUpOn = false;
				return;
			}
			this.pages.current_page++;
			this.getDateList(null, true);
		}, 1000);
	}
};
</script>

<style lang="scss" scoped>
.app {
	min-height: 100%;
	background-color: #f7f7f7;

	.app-body {
		color: #ffffff;
		position: relative;

		.jx-bg {
			position: absolute;
			top: 0;
			width: 100%;
			height: 500rpx;
		}
		.content {
			font-size: 0;
			position: relative;

			.content-head {
				.income {
					padding: 60rpx 30rpx 0;
					margin-bottom: 40rpx;

					.total {
						align-items: center;
						justify-content: center;
						flex-direction: column;
						margin-bottom: 100rpx;
						font-size: 11pt;

						.sum {
							font-size: 23pt;
							font-weight: bold;
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

			.content-bottom {
				color: #434343;
				padding: 0 30rpx 30rpx;

				.list {
					background-color: #ffffff;
					border-radius: 10rpx;
					font-size: 11pt;

					.tabs {
						padding: 24rpx 0;
						display: flex;
						border-bottom: 1rpx solid #f2f2f2;

						.tab {
							flex: 1;
							justify-content: center;
							line-height: 50rpx;
							margin: 0 20rpx;
						}
					}

					.items {
						.item {
							padding: 20rpx;
							border-bottom: 1rpx solid #f2f2f2;
							&:last-child {
								border-bottom: 0;
							}

							.status {
								.name {
									color: #434343;
								}
							}

							.name {
								flex: 1;
								font-size: 9pt;
								color: #666666;
							}

							.desc {
								transform: scale(0.9);
								text-align: center;
								color: #ffffff;
								width: 88rpx;
								height: 36rpx;
								border-radius: 18rpx;
							}

							.fee {
								font-size: 9pt;
								font-weight: 600;
							}
						}
					}
				}
			}
		}
	}
}

.font-size-11 {
	font-size: 11pt !important;
}

.font-size-9 {
	font-size: 9pt !important;
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
