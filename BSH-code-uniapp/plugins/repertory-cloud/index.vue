<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" :fixed="true" left-icon="back" title="云库存" :status-bar="true" :background-color="navBg" :border="false"
		:color="navCol"></com-nav-bar>
		<view class="container">
			<image :src="bg_url" mode="aspectFill" class="jx-bg"></image>
			<!-- <view class="content" v-if="data"> -->
			<view class="content" v-if="info">
				<view class="content-head">
					<view class="user-info">
						<image :src="info.avatar_url" mode="aspectFill" class="acatar"></image>
						<view class="userinfo">
							<view class="username">{{info.nickname}}</view>
							<view class="desc">账号ID: {{ info.user_id }}</view>
							<view class="level">等级:{{ info.level_name }}</view>
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
				<view class="content-bottom">
					<view class="income margin-bottom-20rpx">
						<view class="status flex">
							<view class="text-money flex flex-column-x-center">
								<view class="text">未结算</view>
								<view class="money">{{ info.frozen_price }}元</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">累计</view>
								<view class="money">{{ info.total_price }}元</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">昨日收益</view>
								<view class="money">{{ info.yesterday_price }}元</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">本周</view>
								<view class="money">{{ info.week_price }}元</view>
							</view>
						</view>
					</view>
					<view class="team-benefits margin-bottom-20rpx">
						<view class="text-icon flex flex-column-x-center" @click="navigateTo('/plugins/repertory-cloud/agency')">
							<view class="icon iconfont icon-tuandui" :style="{color:textColor}"></view>
							<view class="text">我的代理</view>
						</view>
						<view class="text-icon flex flex-column-x-center" @click="navigateTo('/plugins/repertory-cloud/income/index')">
							<view class="icon iconfont icon-icon" :style="{color:textColor}"></view>
							<view class="text">我的收益</view>
						</view>

					</view>
					<view class="activation-code-list  margin-bottom-20rpx flex flex-x-between">
						<view>我要补货</view>
						<navigator url="/plugins/repertory-cloud/cargo/add">
							<view class="click">立即查看></view>
						</navigator>
					</view>
					<view class="activation-code-list  margin-bottom-20rpx flex flex-x-between">
						<view>自取订单</view>
						<navigator url="/plugins/repertory-cloud/cargo/list">
							<view class="click">立即查看></view>
						</navigator>
					</view>


					<view class="list">
						<view class="list-title"><span class="text">我的商品</span></view>
						<view class="items" v-if="list.length>0">
							<view class="item flex flex-y-center" v-for="(item, index) in list" :key="index">

								<image :src="item.cover_pic" class="goods-cover"></image>
								<view class="item-goods-info">
									<view class="goods-name over1 margin-bottom-20rpx">{{item.goods_name}}</view>
									<view class="goods-nums flex margin-bottom-20rpx">

										<view class="goods-num" :style="{color:textColor,border:'1px solid'+textColor}">库存：{{item.num}}</view>
									</view>
									<view class="goods-cost flex-x-between">
										<view class="flex">
											<view class="flex" style="margin-right: 16rpx;">
												<view class="text">补货价:</view>
												<view class="price flex" :style="{color:textColor}">
													<span class="dot">￥</span>
													<span>{{item.stock_price}}</span>
												</view>
											</view>
											<view class="flex">
												<view class="text">利润:</view>
												<view class="price flex" :style="{color:textColor}">
													<span class="dot">￥</span>
													<span>{{item.sale_price-item.stock_price}}</span>
												</view>
											</view>
										</view>
										<view class="btn flex-x-center flex-y-center jx-primary-btn" :style="{background:textColor}" @click="changeSendState(item.goods_id)">
											<view>立即发货</view>
										</view>
									</view>
								</view>

							</view>
						</view>
						<view class="items" v-else>
							<main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore>
						</view>
					</view>
				</view>
			</view>
		</view>

		<!-- 补货提醒 -->
		<com-modal width="90%" :show="true" :custom="true" v-if="addState" padding="0">
			<view class="modal">
				<view class="flex-col flex-y-center" style="padding:40rpx 0 0">
					<view class="modal-title">补货提醒</view>
					<view class="modal-content-text flex-col flex-y-center">
						<view>您的商品库存不足，请在3小时内进行补货</view>
						<view>否则会自动跳到我的供货代理商！</view>
					</view>
				</view>
				<view class="btns flex">
					<view class="btn cancel flex-grow-1" @click="">取消补货</view>
					<view class="btn confirm flex-grow-1" @click="">立即补货</view>
				</view>
			</view>
		</com-modal>
		<!-- 补货提醒 -->

		<!-- 发货 -->
		<com-modal width="90%" :show="true" :custom="true" v-if="showSendState" padding="0">
			<view class="modal">
				<view class="flex-col flex-y-center" style="padding:40rpx 54rpx 0">
					<view class="modal-title">发货数量</view>
					<view class="modal-content-input">
						<input class="modal-input" type="text" v-model="num" placeholder="请输入发货数量！" placeholder-class="modal-input-placeholder" />
					</view>
					<jx-list-cell @click="toAddr" :hover="false" :arrow="true" padding="24rpx 0" :line-right="true">
						{{addressData?addressData.user_address:'请选择地址'}}</jx-list-cell>
				</view>
				<view class="btns flex">
					<view class="btn cancel flex-grow-1" @click="changeSendState()">取消发货</view>
					<view class="btn confirm flex-grow-1" @click="confirm()" :style="{color:textColor}">确认发货</view>
				</view>
			</view>
		</com-modal>
		<!-- 发货 -->
		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	export default {
		components: {
			jxListCell: () => import('@/components/list-cell/list-cell')
		},
		data() {
			return {
				addressData: null,
				store_avatar: 'http://jxmall.sinbel.top/web//uploads/images/original/20200730/b565d9a2635e48ddf47200f67602c3cb.jpg',
				loading: false,
				data: null,
				currentTabIndex: 0,
				list: [],
				page: 1,
				is_no_more: false,
				parent_stock: null,
				frozen_price: '',
				is_price: '',
				total_price: '',
				yesterday_price: '',
				showSendState: false,
				goods_id: -1,
				num: 0,
				addState: false,
				info: null,
				
				textColor:'#bc0100',
				bg_url:'',
				button:[],
				navBg:'',
				navCol:'',
			};
		},
		onReachBottom: function(e) {
			this.getList();
		},
		onShow() {
			// 这里用于获取选择地址后的数据 可做一些逻辑操作
			let picker_address = uni.getStorageSync("picker_address");
			if (picker_address) {
				this.addressData = picker_address;
			}
		},
		onLoad() {
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.button = this.globalSet('btnCol');
			this.navBg = this.globalSet('navBg');
			this.navCol = this.globalSet('navCol');
			
			this.getInfo();
			this.getList();
		},
		methods: {
			toAddr() {
				uni.navigateTo({
					url: '/pages/user/address/list?name=picker_address'
				})
			},
			navigateTo(url) {
				uni.navigateTo({
					url: url
				})
			},
			confirm() {
				if (this.goods_id < 0) {
					uni.showToast({
						title: '请选择需要发货的商品'
					})
					return;
				}
				if (this.num == 0) {
					uni.showToast({
						title: '请输入数量'
					})
					return;
				}
				if (this.addressData == null) {
					uni.showToast({
						title: '请选择收货地址'
					})
					return;
				}

				this.loading = true;
				this.$http
					.request({
						url: this.$api.plugin.stock.agent_order_submit,
						methods: 'POST',
						data: {
							goods_id: this.goods_id,
							num: this.num,
							name: this.addressData.name,
							mobile: this.addressData.mobile,
							address: this.addressData.user_address
						}
					})
					.then(res => {

						this.showSendState = !this.showSendState;
						this.list = []
						this.page = 1;
						this.is_no_more = false;
						
						this.loading = false;
						if (res.code == 0) {
							uni.removeStorageSync('picker_address');
							uni.navigateTo({
								url: '/plugins/repertory-cloud/cargo/list'
							})
						} else {
							uni.showModal({
								title: '提示',
								content: res.msg,

							})
						}
					});
			},
			changeSendState(id) {
				this.goods_id = id;
				this.showSendState = !this.showSendState;
			},
			tabChange(index) {
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
						url: this.$api.plugin.stock.info
					})
					.then(res => {
						this.loading = false;
						if (res.code == 0) {
							this.info = res.data
						}
						
						
						if(res.code==1){
							
							uni.showModal({
								title:'提示',
								content:res.msg,
								success:function(e){
									uni.navigateBack({
										delta:1
									})
									
								}
							})
							
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
						url: this.$api.plugin.stock.agent_goods,
						data: {
							page: this.page,
							type: this.currentTabIndex //类型
						}
					})
					.then(res => {
						this.loading = false;
						//console.log(res);
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
		filters: {
			brokerage(type, money) {
				let text = type > 1 ? '间推佣金' : '直推佣金';
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
	.jx-list-cell.jx-cell-arrow:before {
		right: 0 !important;
	}

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
				}
			}

			.list {
				margin: 0 0 36rpx;
				background-color: #ffffff;
				border-radius: 15rpx;
				height: 100%;

				.list-title {
					height: 110rpx;
					line-height: 110rpx;
					padding: 0 32rpx;
					color: #000000;
					font-size: 11.5pt;
					border-bottom: 1rpx solid #f2f2f2;

					.text {
						position: relative;

						&::after {
							content: '';
							position: absolute;
							top: 50%;
							transform: translateY(-50%);
							left: -12rpx;
							width: 4rpx;
							height: 28rpx;
							background-color: #bc0100;
						}
					}
				}

				.item {
					padding: 24rpx 15rpx 24rpx 36rpx;
					border-bottom: 1rpx solid #f2f2f2;
					line-height: 38rpx;

					&:last-child {
						border-bottom: 0;
					}

					.goods-cover {
						width: 168rpx;
						height: 168rpx;
						border-radius: 5rpx;
						margin-right: 18rpx;
					}

					.item-goods-info {
						flex: 1;

						.goods-name {
							color: #333333;
							font-size: 36rpx;
							line-height: normal;
						}

						.goods-nums {
							color: #BC0100;
							font-size: 18rpx;
							line-height: 18rpx;

							.goods-num {
								padding: 7rpx 9rpx;
								border-radius: 5rpx;
								border: 1rpx solid #BC0100;
								margin-right: 18rpx;
							}
						}

						.goods-cost {
							.text {
								color: #666666;
								font-size: 18rpx;
							}

							.price {
								color: #BC0100;
								font-size: 24rpx;
								align-items: baseline;
							}

							.dot {
								font-size: 18rpx;
								line-height: 18rpx;
							}

							.btn {
								width: 133rpx;
								height: 40rpx;
								border-radius: 20rpx;
							}
						}
					}
				}


			}
		}
	}

	.modal {
		font-size: 31rpx;

		.modal-title {
			font-size: 48rpx;
			color: #000000;
			line-height: 60rpx;
			margin-bottom: 72rpx;
		}

		.modal-content-text {
			color: #666666;
			font-size: 32rpx;
			line-height: 40rpx;
		}

		.modal-content-input {
			color: #767676;
			text-align: center;
			line-height: 44rpx;
			width: 100%;
			padding: 6rpx 0;
			border-bottom: 1rpx solid #E5E5E5;

			.modal-input {
				color: #000000;
				font-size: 32rpx;
				text-align: left;
			}

			.modal-input-placeholder {
				color: #999999;
			}
		}

		.btns {
			margin-top: 40rpx;
			width: 100%;
			border-top: 1rpx solid #f2f2f2;
		}

		.btn {
			text-align: center;
			line-height: 100rpx;
			border-right: 1rpx solid #f2f2f2;

			&.cancel {
				color: #999999;
			}

			&.confirm {
				color: $jx-color-primary;
			}

			&:last-child {
				border-right: 0;
			}
		}
	}

	.margin-bottom-20rpx {
		margin-bottom: 20rpx;
	}

	.flex-column-x-center {
		flex: 1;
		flex-direction: column;
		align-items: center;
	}
</style>
