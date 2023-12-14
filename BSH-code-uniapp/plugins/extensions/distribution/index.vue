<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" :fixed="true" left-icon="back" title="分销分红" :status-bar="true" :background-color="navBg" :border="false" :color="navCol"></com-nav-bar>
		<view class="app-body">
			<view class="content">
				<view class="content-head" v-if="detail">
					<image :src="bg_url" mode="scaleToFill" class="jx-bg"></image>
					<view class="super">
						<image :src="detail.avatar_url || 'http://yingmlife-1302693724.cos.ap-guangzhou.myqcloud.com/uploads/images/original/20201216/15262b999e48acc5891864e3f2463cb0.jpg'" mode="aspectFill" class="acatar"></image>
						<view class="userinfo">
							<!-- <view class="username">{{parent.username}}(推荐人)</view> -->
							<view class="username">推荐人: {{ detail.parent_name }}</view>
							<view class="desc" :style="{color:textColor}">
								<span class="iconfont icon-huiyuan1"></span>
								{{ detail.parent_level_name }}
							</view>
							<view class="tel" v-if="detail.mobile != ''" :style="{color:textColor}">
								<span class="iconfont icon-dianhua3"></span>
								{{ detail.mobile }}
							</view>
						</view>
					</view>
				</view>
			<view class="reward-box">
				<view class="rebuy"  v-if="detail&&detail.is_rebuy==1">
					<view class="rebuy-box">
						<view class="rebuy-title">复购奖励</view>
						<view class="rebuy-check"><navigator url="./rebuy">立即查看></navigator></view>
					</view>
				</view>
				<view class="rebuy" v-if="detail&&detail.is_subsidy==1">
					<view class="rebuy-box">
						<view class="rebuy-title">补贴奖励</view>
						<view class="rebuy-check"><navigator url="./subsidy">立即查看></navigator></view>
					</view>
				</view>
				<view class="rebuy"  v-if="detail&&detail.is_team==1">
					<view class="rebuy-box">
						<view class="rebuy-title">团队奖励</view>
						<view class="rebuy-check"><navigator url="./team">立即查看></navigator></view>
					</view>
				</view>
			</view>
				<view class="content-bottom" v-if="detail">
					<view class="card">
						<view class="title">分销佣金</view>
						<view class="bill">
							<view class="icon-text flex-column-x-center">
								<view class="icon iconfont icon-jiesuancopy"></view>
								<view class="name">未结算</view>
								<view class="money" :style="{color:textColor}">{{ detail.frozen_price }}元</view>
							</view>
							<view class="icon-text flex-column-x-center">
								<view class="icon iconfont icon-yijiesuan"></view>
								<view class="name">已结算</view>
								<view class="money" :style="{color:textColor}">{{ detail.is_price }}元</view>
							</view>
							<view class="icon-text flex-column-x-center">
								<view class="icon iconfont icon-jiesuan1"></view>
								<view class="name">昨日收益</view>
								<view class="money" :style="{color:textColor}">{{ detail.yestoday_pirce || '0' }}元</view>
							</view>
							<view class="icon-text flex-column-x-center">
								<view class="icon iconfont icon-tuikuan"></view>
								<view class="name">总收益</view>
								<view class="money" :style="{color:textColor}">{{ detail.total_price }}元</view>
							</view>
						</view>
					</view>

					<view class="order">
						<view class="tabs">
							<view class="tab" v-for="(tab, i) in tabs" :key="i" :style="{color:type == tab.type ?textColor:''}" @click="switchTab(i)">{{ tab.name }}</view>
						</view>
						<view class="order-items" v-if="dataList && dataList.length">
							<view class="item" v-for="(item, i) in dataList" :key="i">
								<view class="user-status">
									<image :src="item.avatar_url" mode="aspectFill" class="acatar"></image>
									<view class="name-datetime">
										<view class="flex">
											<view class="name over1">{{ item.nickname }}</view>
											<view class="id" :style="{color:textColor,border:'1px solid'+textColor}">ID:{{ item.user_id }}</view>
										</view>
										<view class="datetime">下单时间:{{ item.created_at }}</view>
									</view>
									<view class="status-text" :style="{background:textColor,border:'1px solid'+textColor}">{{ item.status }}</view>
								</view>
								<view class="info">
									<view class="mark">
										<view class="goods-name">商品名称: {{ item.goods_name }}</view>
										<view class="order-id">订单编号: {{ item.order_no }}</view>
									</view>
									<view class="money">
										<view class="order-money">商品金额: {{ item.goods_price }}元</view>
										<view class="commission" :style="{color:textColor}">{{ item.commission_type | brokerage(item.price) }}</view>
									</view>
								</view>
							</view>
						</view>
						<view class="order-items" v-else><main-nomore text="暂无订单" :visible="true" bgcolor="transparent"></main-nomore></view>
					</view>
				</view>
			</view>
		</view>
		
		<view class="from" v-if='is_apply==1'>
			<view class="from-item flex">
				姓名:
				<input class="from-input flex-grow-1" type="text" v-model="realname" placeholder="请输入您的真实姓名" placeholder-class="from-input-placeholder" />
			</view>
			<view class="from-item flex">
				手机号:
				<input class="from-input flex-grow-1" type="text" v-model="mobile" placeholder="请输入您的手机号码" placeholder-class="from-input-placeholder" />
			</view>
			<view class="from-item flex">
				备注:
				<input class="from-input flex-grow-1" type="text" v-model="remarks" placeholder="请输入备注" placeholder-class="from-input-placeholder" />
			</view>
			<view class="submit btn flex-x-center flex-y-center" @click="applySubmit" :style="{background:textColor}"><span>确认提交</span></view>
		</view>
		
		<com-modal :show="is_modal" :diyBtn="true" @click='define' :button='button' padding='30rpx 30rpx' width="80%" :custom='false' title='申请失败' :content='reason' ></com-modal>

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
			dataList: [],
			pages: {
				total_count: 1,
				page_count: 1,
				pageSize: 20,
				current_page: 1
			},
			detail: null,
			loadding: false,
			pullUpOn: true,
			loading: false,
			sign: '',
			type: 0,
			is_apply: 0,
			
			// 表单数据
			realname:'',
			mobile:'',
			remarks:'',
			is_modal:false,
			reason:"",
			
			textColor:'#bc0100',
			bg_url:'',
			button:[],
			navBg:'',
			navCol:'',
		};
	},
	onLoad(options) {
		if (options.sign) {
			this.sign = options.sign;
		}
		this.textColor = this.globalSet('textCol');
		this.bg_url = this.globalSet('imgUrl');
		this.button = this.globalSet('btnCol');
		this.navBg = this.globalSet('navBg');
		this.navCol = this.globalSet('navCol');
		
		this.getDetail();
		this.getDateList(_status, true);
	},
	filters: {
		brokerage(type, money) {
			let text = type > 1 ? '间推佣金' : '直推佣金';
			return `${text}: ${money}元`;
		}
	},
	computed: {
		tabs() {
			let temp = [
				{
					name: '全部',
					type: 0
				},
				{
					name: '直推订单',
					type: 1
				},
				{
					name: '间推订单',
					type: 2
				}
			];
			return temp;
		}
	},
	methods: {
		define(e){
			this.is_modal = false;
			if(e.index == 0){
				this.navBack();
			}
		},
		hide(){
			this.is_modal = false;
		},
		applySubmit(){ //提交申请
			this.$http.request({
				url:this.$api.plugin.distribution.apply,
				data:{
					realname:this.realname,
					mobile:this.mobile,
					marks:this.marks
				}
			}).then(res => {
				this.is_apply = 0;
				this.$http.toast(res.msg);
				setTimeout(()=>{
					this.back();
				},1500)
			})
		},
		switchTab(sub) {
			let self = this;
			this.tabs.forEach((item, i) => {
				if (sub == i) {
					self.type = item.type;
					return;
				}
			});
			this.getDateList(_status, true);
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
						this.detail = res.data.info;
					}else{
						this.is_apply = res.data.is_apply;
						if(res.data.status == 2){
							this.is_modal=true;
							this.reason = res.data.reason;
						}
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
					url: this.$api.plugin.distribution.log_list,
					method: 'POST',
					data: {
						sign: this.sign,
						level: this.type,
						page: current_page,
						limit: pageSize
					}
				})
				.then(res => {
					this.loading = false;
					// console.log(res);
					if (res.code === 0) {
						let { list, pagination, parent } = res.data;
						this.dataList = status != 'refresh' ? this.dataList.concat(list) : list;
						this.pages = pagination;
						this.parent = parent;
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
			// this.getDateList(_status);
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
						font-size: 36rpx;
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
		.reward-box{
			margin-top: 20rpx;
			.rebuy{
				padding: 0 30rpx;
				.rebuy-box{
					display: flex;
					width: 100%;
					height: 80rpx;
					line-height: 80rpx;
					background-color: #fff;
					.rebuy-title{
						font-size: 12pt;
					}
					.rebuy-check{
						font-size: 10pt;
						text-align: right;
					}
				}
				.rebuy-box>view{
					flex: 1;
					padding: 0 30rpx;
				}
			}
			.rebuy:nth-of-type(1)>.rebuy-box{
				border-top-left-radius: 8rpx;
				border-top-right-radius: 8rpx;
			}
			.rebuy:nth-last-child(1)>.rebuy-box{
				border-bottom-left-radius: 8rpx;
				border-bottom-right-radius: 8rpx;
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

						.icon {
							font-size: 20pt;
						}

						.name,
						.money {
							font-size: 10pt;
						}

						.money {
							font-weight: bold;
						}
					}
				}
			}

			.order {
				font-size: 11pt;

				.tabs {
					display: flex;

					.tab {
						flex: 1;
						text-align: center;
						line-height: 90rpx;
						border-right: 1rpx solid #f2f2f2;

						&:last-child {
							border-right: 0;
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
									// line-height: 37rpx;
									max-width:280rpx;
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

								.datetime {
									font-size: 9pt;
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

.from {
				margin: 20upx 30upx;
				padding: 51upx 30upx;
				border-radius: 39upx;
				background-color: #ffffff;
				color: #000000;

				.from-item {
					font-size: 13.5pt;
					margin-bottom: 68upx;
					position: relative;
				}

				.from-input {
					font-size: 10.5pt;
					height: 50upx;
					line-height: 50upx;
					border-bottom: 1upx solid #cccccc;
					margin-left: 26upx;
				}

				.from-input-placeholder {
					font-size: 10.5pt;
					color: #989696;
				}

				.icon-left {
					position: absolute;
					right: 0;
					width: 32upx;
					font-size: 15pt;
					color: #666666;
				}

				.submit {
					color: #ffffff;
					height: 78upx;
				}
			}
</style>
