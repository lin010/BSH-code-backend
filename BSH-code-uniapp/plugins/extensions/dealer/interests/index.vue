<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="权益中心" :status-bar="true" :background-color="navBg" :border="false"
		:color="navCol"></com-nav-bar>
		<view class="container">
			<image :src="bg_url" mode="aspectFill" class="jx-bg"></image>
			<!-- <view class="content" v-if="data"> -->
			<view class="content">
				<view class="content-head">
					<view class="user-info" v-if="info">
						<image :src="info.avatar_url" mode="aspectFill" class="acatar"></image>
						<view class="userinfo">
							<view class="username">{{ info.nickname}}</view>
							<view class="desc">账号ID: {{info.user_id}}</view>
							<view class="level">等级:{{ info.level_name }}</view>
						</view>
					</view>
				</view>
		 
				<view class="content-bottom">
					<view class="items">
						<view class="item" v-for="(item, i) in list" :key="i">
							<view class="item-head flex flex-x-between flex-y-center">
								<view class="title">{{item.name}}</view>
							</view>
							<view class="item-body">
								<view class="inner flex flex-x-between flex-y-center">
									<view class="line flex-grow-1"></view>
									<view class="text">等级权益</view>
									<view class="line flex-grow-1"></view>
								</view>
								<view class="legend">
									<view class="legend-item">
										<view class="text">{{item.detail}}</view>
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
				loading: false,
				data: null,
				list: [],
				info: null,
				
				textColor:'#bc0100',
				bg_url:'',
				navBg:'',
				navCol:'',
			};
		},
	 
		onLoad: function(e) {
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.navBg = this.globalSet('navBg');
			this.navCol = this.globalSet('navCol');

			this.getInfo();
			this.getList();
		},
		methods: {
			getInfo() {
				this.loading = true;
				this.$http
					.request({
						url: this.$api.plugin.agent.info
					})
					.then(res => {
						this.loading = false;
						if (res.code == 0) {
							this.info = res.data
						}
					});
			},
			getList() {
			  
				this.$http
					.request({
						url: this.$api.plugin.agent.level_list,
						data: {
							page: this.page,
					 
						}
					})
					.then(res => {
						this.loading = false;
 
						if (res.code == 0) {
							this.list = res.data.list;

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
