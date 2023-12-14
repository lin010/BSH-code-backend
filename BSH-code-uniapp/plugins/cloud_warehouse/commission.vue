<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="进货记录" :status-bar="true" :background-color="navBg" :border="false"
		:color="navCol"></com-nav-bar>
		<view class="container">
			<view class="content">
				<view class="content-head">
					<image :src="bg_url" mode="scaleToFill" class="jx-bg"></image>
					<view class="super">
						<view class="userinfo">
							<view class="username over1"> <span>总利润：</span>{{totals.totals}}</view>
							<view class="username over1"> <span>总数量：</span>{{ totals.counts }}</view>
						</view>
					</view>
				</view>
				
				<view class="content-bottom">
					<view class="order">
						<view class="tabs">
							<view class="tab"  :style="{color:textColor}">
								<span class="name" :style="{'border-bottom':'2px solid'+textColor}">进货记录</span>
							</view>
							<view class="tab" @click="openUrl('/plugins/cloud_warehouse/index')">
								<span class="name">返回云仓</span>
							</view>
						</view>
						
						<view class="order-items" v-if="list && list.length > 0">
							<view class="item" v-for="(item, i) in list" :key="i">
								<view class="user-status">
									<view class="name-datetime">
										<view class="name">
											<view class="name-text">编号：{{ item.order_sn }}</view>
											<view class="id" :style="{color:successColor,border:'1px solid'+successColor}">{{item.source_type_text}}：+{{ item.money}}</view>
										</view>
										<view class="tel">
											<view>{{item.desc}}</view>
											<view v-if="item.purchase_price>0">进货【{{ item.leve_name_child }}】，进货价格【￥{{item.purchase_price}}】。</view>
										</view>
										<view class="datetime">{{ item.show_at }}</view>
									</view>
								</view>
							</view>
						</view>
						<view class="order-items" v-else>
							<main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore>
						</view>
					</view>
				</view>
			</view>
		</view>

		<!--加载loadding-->
		<!-- <main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore> -->
		<!-- <main-nomore :visible="!pullUpOn" bgcolor="#FFFFFF"></main-nomore> -->
		<main-loading :visible="loading"></main-loading>
		<!--加载loadding-->
	</view>
</template>

<script>
	const _status = 'refresh';
	export default {
		data() {
			return {
				totals: {},
				list: [],
				page:1,
				loadding: false,
				pullUpOn: true,
				loading: false,
				textColor:'#bc0100',
				successColor:'#009c00',
				bg_url:'',
				navBg:'',
				navCol:'',
			};
		},
		onLoad(options) {
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.navBg = this.globalSet('navBg');
			this.navCol = this.globalSet('navCol');

			this.getData();
			this.getList();
		},
		computed: {

		},
		methods: {
			getData() {
				var self=this;
				this.loading = true;
				this.$http
					.request({
						url: this.$api.plugin.cloud_warehouse.commission_total,
						method: 'GET'
					})
					.then(res => {
						this.loading = false;
						console.log(res)
						if (res.code == 0) {
							this.totals = res.data.totals;
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
						url: this.$api.plugin.cloud_warehouse.commission_log,
						data: {
							page: this.page,
						}
					})
					.then(res => {
						this.loading = false;
						// console.log(res);
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
								title: res.msg
							});
						}
					});
			},

			openUrl(url){
				uni.navigateTo({
					url
				})
			},
			back() {
				this.navBack();
			}
		},
	 
		onReachBottom() {
			 this.getList();
		}
	};
</script>

<style lang="scss" scoped>
	.app {
		min-height: 100%;
		background-color: #f7f7f7;
		padding-bottom: 10px;

		.container {

			.content-head {
				width: 100%;
				position: relative;
				padding-top: 30rpx;

				.jx-bg {
					position: absolute;
					top: 0;
					width: 100%;
					height: 414rpx;
				}

				.super {
					position: relative;
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
							font-size: 30rpx;
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
			
			.content-center{
				background: #FFFFFF;
				border-radius: 16rpx;
				position: relative;
				z-index: 4;
				margin: 20rpx 30rpx;
				
				.title{
					font-size: 28rpx;
					color: #333333;
					padding: 40rpx 40rpx 26rpx;
					border-bottom: 1px solid #F3F3F3;
				}
				.main-content{
					padding: 50rpx 0rpx 60rpx;
					
					.main-item{
						font-size: 24rpx;
						color: #333333;
						text-align: center;
						letter-spacing: 1px;
						width: 25%;
						display: inline-block;
						
						.people{
							font-size: 30rpx;
							font-weight: 600;
							margin-bottom: 6rpx;
						}
					}
				}
			}
			
			.main-scroll{
				white-space: nowrap;
			}
			.content-bottom {
				position: relative;
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
								color: #bc0100;
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
							text-align: center;
							line-height: 90rpx;
							border-right: 1rpx solid #f2f2f2;
							display: inline-block;
							width: 50%;

							.name {
								position: relative;
								padding-bottom: 4rpx;
							}

							&:last-child {
								border-right: 0;
							}

						}
					}
					
					.tabs2{
						
					}

					.status {
						display: flex;
						padding: 36rpx 30rpx;
						line-height: 60rpx;

						.name {
							flex: 1;
							text-align: center;
							margin: 0 36rpx;

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

									// position: relative;
									.name {
										display: flex;
										line-height: 37rpx;

										.name-text {
											overflow: hidden;
											text-overflow: ellipsis;
											white-space: nowrap;
											max-width: 254rpx;
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
											color: #bc0100;
											line-height: 16px;
											font-size: 10pt;
										}
									}
								}


								.status-text {
									position: absolute;
									width: 88rpx;
									height: 36rpx;
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
									line-height: 32rpx;
								}
							}

							.info {
								font-size: 22rpx;

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
	.type2-box{
		
		.type2-item{
			width: 50%;
			height: 120rpx;
			text-align: center;
			line-height: 120rpx;
			font-size: 28rpx;
			color: #000000;
			
			.type2-text{
				padding: 0 10rpx 8rpx;
			}
		}
	}
</style>
