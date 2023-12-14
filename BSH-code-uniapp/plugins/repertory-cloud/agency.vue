<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="我的代理" :status-bar="true" :background-color="navBg" :border="false"
		:color="navCol"></com-nav-bar>
		<view class="container">
			<view class="content">
				<!-- <view class="content-head" v-if="parent"> -->
				<view class="content-head">
					<image :src="bg_url" mode="scaleToFill" class="jx-bg"></image>
					<view class="super" v-if="info">
						<!-- <image :src="parent.avatar_url" mode="aspectFill" class="acatar"></image> -->
						<block v-if="info.is_parent==1">
							<image :src="info.parent_avatar_url" mode="aspectFill" class="acatar"></image>
						</block>
						<block v-if="info.is_parent==0">
							<image :src="info.avatar_url" mode="aspectFill" class="acatar"></image>
						</block>
						<view class="userinfo" v-if="info.is_parent==1">
							<!-- <view class="username">推荐人: {{ parent.parent_name || '社交新零售系统-七件事' }}</view> -->
							<view class="username over1">推荐人: {{ info.nickname }}</view>
							<view class="desc" :style="{color:textColor}">
								<span class="iconfont icon-huiyuan1"></span>
								<!-- {{ parent.parent_level_name }} -->
								{{ '省级代理' }}
							</view>
							<!-- <view class="tel" v-if="parent.mobile != ''"> -->
							<view class="tel" :style="{color:textColor}">
								<span class="iconfont icon-dianhua3"></span>
								<!-- {{ parent.mobile || '15015756796' }} -->
								{{ '15015756796' }}
							</view>
						</view>
						<view class="userinfo" v-if="info.is_parent==0">
							<!-- <view class="username">推荐人: {{ parent.parent_name || '社交新零售系统-七件事' }}</view> -->
							<view class="username over1"> {{ info.nickname }}</view>
							<view class="desc" :style="{color:textColor}">
								<span class="iconfont icon-huiyuan1"></span>
								<!-- {{ parent.parent_level_name }} -->
								{{ info.level_name }}
							</view>
							<!-- <view class="tel" v-if="parent.mobile != ''"> -->
							<view class="tel" :style="{color:textColor}">
								<span class="iconfont icon-dianhua3"></span>
								<!-- {{ parent.mobile || '15015756796' }} -->
								{{ info.mobile }}
							</view>
						</view>
					</view>
				</view>
				<view class="content-bottom">
					<view class="card">
						<view class="title">代理会员</view>
						<view class="bill">
							<block v-if="info&&info.level_list.length>0">
								<view class="icon-text flex-column-x-center" v-for="(item,index) in info.level_list">
									<view class="sum" :style="{color:textColor}">{{ item.count }}人</view>
									<view class="name">{{item.name}}</view>
								</view>
							</block>

						</view>
					</view>
					<view class="order">
						<view class="tabs">
							<view class="tab" v-for="(tab,i) in team_level_list" :key="i" :style="{color:tabIndex == i ?textColor:''}" @click="switchTab(i,tab.level)">
								<span class="name" :style="{'border-bottom':tabIndex == i ?'2px solid'+textColor:''}">{{tab.name}}</span>
							</view>
						</view>
						<view class="order-items" v-if="list && list.length > 0">
							<view class="item" v-for="(item, i) in list" :key="i">
								<view class="user-status">
									<image :src="item.avatar_url || 'http://yingmlife-1302693724.cos.ap-guangzhou.myqcloud.com/uploads/images/original/20201216/15262b999e48acc5891864e3f2463cb0.jpg'" mode="aspectFill"
									 class="acatar"></image>
									<view class="name-datetime">
										<view class="name">
											<view class="name-text">{{ item.nickname }}</view>
											<view class="id" :style="{color:textColor,border:'1px solid'+textColor}">ID:{{ item.user_id }}</view>
										</view>
										<view class="tel">
											{{ item.mobile }}
											<span class="iconfont icon-dianhua3" style="margin-left: 10rpx;"  :style="{color:textColor}"></span>
										</view>
										<view class="datetime">{{ item.created_at }}</view>
									</view>
								  
								</view>
								<view class="info">
									<view class="mark flex-x-between">
										<view class="order-id">团队总数: {{ item.team_count }}个</view>
										<view class="goods-name">直推总数: {{ item.first_team_count }}人</view>
										<view class="goods-name">间推总数: {{ item.other_team_count }}人</view>
									</view>
									<view class="money flex-x-between">
										<view class="commission" :style="{color:textColor}">累计收益: {{ item.total_price }}元</view>
									</view>
								</view>
							</view>
						</view>
						<view class="order-items" v-else>
							<main-nomore text="暂无客户" :visible="true" bgcolor="transparent"></main-nomore>
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
				parent: null,
				team_level_list: [{
					name: '全部',
					level: 0
				}],
				tabIndex: 0,
				team_commission: null,
				list: [],
				 page:1,
				loadding: false,
				pullUpOn: true,
				loading: false,
				sign: '',
				tabType: 0,
				info: null,
				
				textColor:'#bc0100',
				bg_url:'',
				button:[],
				navBg:'',
				navCol:'',
			};
		},
		onLoad(options) {
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.button = this.globalSet('btnCol');
			this.navBg = this.globalSet('navBg');
			this.navCol = this.globalSet('navCol');
			
			if (options.sign) {
				this.sign = options.sign;
			}
			this.getData();
			this.getList(0);
		},
		computed: {

		},
		methods: {
			switchTab(i,level) {
				 this.tabIndex=i;
				 this.list=[]
				 this.is_no_more=false;
				 this.page=1;
				 this.getList(level)
			},
			getData() {
				var self=this;
				this.loading = true;
				this.$http
					.request({
						url: this.$api.plugin.stock.info,
						method: 'GET'
					})
					.then(res => {
						this.loading = false;
						if (res.code == 0) {
							this.info = res.data;
							if (this.info.level_list.length > 0) {
								for (var i = 0; i < this.info.level_list.length; i++) {
									let item = this.info.level_list[i]
									let newItem = {
										name: item.name,
										level: item.level
									}
									self.team_level_list.push(newItem)
								}
							}
						}
					});
			},

			getList(level) {
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
						url: this.$api.plugin.stock.team_list,
						data: {
							page: this.page,
							level:level
						}
					})
					.then(res => {
						this.loading = false;
						//console.log(res);
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
							flex: 1;
							text-align: center;
							line-height: 90rpx;
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
										bottom: -12rpx;
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
											max-width: 154rpx;
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
</style>
