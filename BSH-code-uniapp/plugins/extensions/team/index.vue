<template>
	<view class="app">
		<!-- <view class="app-header">
			<view class="back iconfont icon-fanhui" @click="back()"></view>
			<view class="title">我的团队</view>
		</view> -->
		<com-nav-bar @clickLeft="back" left-icon="back" title="我的客户" :status-bar="true" :background-color="navBg" :border="false" :color="navCol"></com-nav-bar>
		<view class="app-body">
			<view class="content">
				<view class="content-head" v-if="parent">
					<image :src="bg_url" mode="scaleToFill" class="jx-bg"></image>
					<view class="super">
						<image :src="parent.avatar_url || 'https://dev.mingyuanriji.cn/web/static//header-logo.png'" mode="aspectFill" class="acatar"></image>
						<view class="userinfo">
							<view class="username">推荐人: {{ parent.parent_name || '平台' }}</view>
							<view class="desc">
								<span class="iconfont icon-huiyuan1"></span>
								{{ parent.parent_level_name }}
							</view>
							<view class="tel" :style="{color:textColor}" v-if="parent.mobile != ''">
								<!-- 暂时取消 -->
								<!-- <span class="iconfont icon-dianhua3"></span> -->
								<!-- {{ parent.mobile }} -->
							</view>
						</view>
					</view>
				</view>
				<view class="content-bottom">
					<!-- <view class="card" v-if="team_level_list && team_level_list.length">
						<view class="title">团队会员</view>
						<view class="bill">
							<view class="icon-text flex-column-x-center" v-for="(item,i) in team_level_list" :key="i">
								<image class="logo-img" :src='item.pic_url || `https://pic.downk.cc/item/5ef0a0a514195aa594e93bc9.png`' mode="aspectFill"></image>
								<view class="sum">{{item.total}}人</view>
								<view class="name">{{item.name}}</view>
							</view>
							<view class="icon-text flex-column-x-center">
								<image class="logo-img" src="@/static/team-1.png" mode="aspectFill"></image>
								<view class="sum">{{detail.commission_frozen}}人</view>
								<view class="name">普通会员</view>
							</view>
							<view class="icon-text flex-column-x-center">
								<image class="logo-img" src="@/static/team-2.png" mode="aspectFill"></image>
								<view class="sum">{{detail.commission_usable}}人</view>
								<view class="name">黄金会员</view>
							</view>
							<view class="icon-text flex-column-x-center">
								<image class="logo-img" src="@/static/team-3.png" mode="aspectFill"></image>
								<view class="sum">{{detail.yesterday_commission || '0'}}人</view>
								<view class="name">铂金会员</view>
							</view>
							<view class="icon-text flex-column-x-center">
								<image class="logo-img" src="@/static/team-4.png" mode="aspectFill"></image>
								<view class="sum">{{detail.total_commission}}人</view>
								<view class="name">钻石会员</view>
							</view>
						</view>
					</view> -->
					<view class="card" v-if="team_commission">
						<!-- <view class="title">团队佣金</view> -->
						<view class="title">客户佣金</view>
						<view class="bill">
							<view class="icon-text flex-column-x-center">
								<view class="sum">{{ team_commission.direct_push_total }}人</view>
								<view class="name">直推客户</view>
							</view>
							<view class="icon-text flex-column-x-center">
								<view class="sum">{{ team_commission.direct_push_total + team_commission.space_push_total }}人</view>
								<view class="name">团队客户</view>
							</view>
							<view class="icon-text flex-column-x-center"  @click="toTeamOrder">
								<view class="sum">{{ team_commission.team_order_count || '0' }}单</view>
								<view class="name">团队订单</view>
							</view>
							<view class="icon-text flex-column-x-center">
								<view class="sum">{{ team_commission.team_order_total }}元</view>
								<view class="name">订单金额</view>
							</view>
						</view>
					</view>
					<view class="order" v-if="team_commission">
						<!-- <view class="tabs">
							<view class="tab" v-for="(tab,i) in tabs" :key="i" :class="type == tab.type ? 'active' : ''" @click="switchTab(i)">
								{{tab.name}}
							</view>
						</view> -->
						<div class="status">
							<view class="name" :style="{'border-bottom':type == 1?'1px solid'+'#FF7104':''}" @click="switchTab(1)">直推客户({{ team_commission.direct_push_total }}人)</view>
							<view class="name" :style="{'border-bottom':type == 2?'1px solid'+'#FF7104':''}" @click="switchTab(2)">间推客户({{ team_commission.space_push_total }}人)</view>
						</div>
						<view class="order-items" v-if="dataList && dataList.length">
							<view class="item" v-for="(item, i) in dataList" :key="i">
								<view class="user-status">
									<image :src="item.children.avatar_url" mode="aspectFill" class="acatar"></image>
									<view class="name-datetime">
										<view class="name">
											<view class="name-text">{{ item.children.nickname }}</view>
											<view class="id" :style="{color: '#FF7104',border:'1px solid'+'#FF7104'}">ID:{{ item.children.id }}</view>
										</view>
										<view class="tel" v-if="item.children.mobile">
											{{ item.children.mobile }}
											<span class="iconfont icon-dianhua3"></span>
										</view>
										<view class="datetime">{{ item.created_at }}</view>
									</view>
									<!-- <view class="status-text">{{'详情'}}</view> -->
								</view>
								<view class="info">
									<view class="mark">
										<view class="goods-name">订单数量: {{ item.order_count }}个</view>
										<view class="order-id">客户人数: {{ item.team_user_count }}个</view>
									</view>
									<view class="money">
										<view class="order-money">订单金额: {{ item.total_price }}元</view>
										<view class="commission" :style="{color:'#FF7104'}">团队金额: {{ item.team_total_price }}元</view>
									</view>
								</view>
								<view class="shop-name" style="font-size: 25rpx;">
									店铺名：{{item.store_name}}
								</view>
							</view>
						</view>
						<view class="order-items" v-else><main-nomore text="暂无客户" :visible="true" bgcolor="transparent"></main-nomore></view>
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
			parent: null,
			team_level_list: [],
			team_commission: null,
			dataList: [],
			pages: {
				total_count: 1,
				page_count: 1,
				pageSize: 20,
				current_page: 1
			},
			loadding: false,
			pullUpOn: true,
			loading: false,
			sign: '',
			type: 1,
			textColor:'',
			navBg:'',
			navCol:'',
			bg_url:'',
		};
	},
	onLoad(options) {
		this.textColor = this.globalSet('textCol');
		this.navBg = this.globalSet('navBg');
		this.navCol = this.globalSet('navCol');
		this.bg_url = this.globalSet('imgUrl');
		
		if (options.sign) {
			this.sign = options.sign;
		}
		this.getData();
		this.getDetail();
		this.getDateList(_status, true);
	},
	computed: {
		tabs() {
			let temp = [
				{
					name: '全部',
					type: 0
				},
				{
					name: '分销',
					type: 1
				},
				{
					name: '团队',
					type: 2
				},
				{
					name: '代理',
					type: 3
				}
			];
			return temp;
		}
	},
	methods: {
		toTeamOrder(){
			uni.navigateTo({
				url:'/plugins/extensions/team/order'
			})
		},
		switchTab(sub) {
			let self = this;
			this.type = sub;
			// this.tabs.forEach((item,i) => {
			// 	if(sub == i){
			// 		self.type = item.type;
			// 		return;
			// 	}
			// })
			this.getDateList(_status, true);
		},
		getData() {
			this.loading = true;
			this.$http
				.request({
					url: this.$api.plugin.extensions.team.info,
					method: 'POST'
				})
				.then(res => {
					this.loading = false;
					if (res.code == 0) {
						this.team_level_list = res.data.team_level;
						this.team_commission = res.data.team_commission;
					}
				});
		},
		getDetail() {
			this.loading = true;
			this.$http
				.request({
					url: this.$api.plugin.distribution.info,
					method: 'POST'
				})
				.then(res => {
					this.loading = false;
					if (res.code == 0) {
						// console.log(res);
						this.parent = res.data.info;
					}
				});
		},
		getDateList(status, bool) {
			this.loading = bool ? true : false;
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
					url: this.$api.plugin.extensions.team.list,
					method: 'POST',
					data: {
						flag: this.type,
						page: current_page,
						limit: pageSize
					}
				})
				.then(res => {
					this.loading = false;
					// console.log(res);
					if (res.code === 0) {
						let { list, pagination } = res.data;
						this.dataList = status != 'refresh' ? this.dataList.concat(list) : list;
						this.pages = pagination;
						this.pullUpOn = true;
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
			this.getDateList();
		}, 1000);
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
				height: 310rpx;
			}

			.super {
				position: relative;
				margin-bottom: 60rpx;
				background-color: #ffffff;
				border-radius: 8rpx;
				margin: 0 30rpx;
				padding: 30rpx;
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
						font-size: 36rpx;
					}

					.desc,
					.tel {
						color: #FF7104;
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
			padding: 0 30rpx;

			.card,
			.order {
				margin-top: 20rpx;
				border-radius: 15rpx;
				background-color: #ffffff;
				color: #333333;
			}

			.card {
				.title {
					padding: 0 30rpx;
					line-height: 90rpx;
					font-size: 12pt;
					border-bottom: 1rpx solid #f3f3f3;
				}

				.bill {
					display: flex;

					.icon-text {
						flex: 1;
						padding: 30rpx 0;

						.logo-img {
							width: 50rpx;
							height: 50rpx;
						}

						.name {
							font-size: 10pt;
						}

						.sum {
							font-weight: bold;
							color:#FF7104;
							font-size: 11pt;
							overflow: hidden;
							text-overflow: ellipsis;
							white-space: nowrap;
							max-width: 180rpx;
						}
					}
				}
			}

			.order {
				font-size: 11pt;

				.tabs {
					display: flex;
					border-bottom: 1rpx solid #f3f3f3;

					.tab {
						flex: 1;
						text-align: center;
						line-height: 90rpx;
						border-right: 1rpx solid #f2f2f2;

						&:last-child {
							border-right: 0;
						}

						&.active {
							color: #bc0100;
						}
					}
				}

				.status {
					display: flex;
					padding: 36rpx 30rpx;
					line-height: 60rpx;

					.name {
						flex: 1;
						text-align: center;
						margin: 0 10px;

						&.active {
							border-bottom: 4rpx solid #bc0100;
						}
					}
				}

				.order-items {
					border-top: 1rpx solid #f3f3f3;
					display: flex;
					flex-direction: column;

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

									.name-text {
										overflow: hidden;
										text-overflow: ellipsis;
										white-space: nowrap;
										max-width: 151rpx;
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
								}

								.tel,
								.datetime {
									font-size: 9pt;
									color: #808080;

									.iconfont {
										line-height: 16px;
										font-size: 10pt;
									}
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
								text-align: center;
								line-height: 18px;
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

.flex-column-x-center {
	display: flex;
	flex-direction: column;
	align-items: center;
}
</style>
