<template>
	<view class="app">
		<view class="refund-list" v-if="dataList && dataList.length">
			<view class="refund-item" v-for="(item, i) in dataList" :key="i">
				<jx-list-cell :hover="false" :lineLeft="false" padding="26rpx 20rpx">
					<view class="jx-goods-title">
						<view class="logo">
							<span class="name">兰亭</span>
						</view>
						<view class="jx-order-status" :style="{'color':textColor}">{{ item.status_text }}</view>
					</view>
				</jx-list-cell>
				<block v-for="(detail, index) in item.detail" :key="index">
					<jx-list-cell padding="0" @click="toDetail(item.order_detail_id)">
						<view class="jx-goods-item">
							<image :src="detail.goods_info.pic_url" class="jx-goods-img"></image>
							<view class="jx-goods-center">
								<view class="jx-goods-name">{{ detail.goods_info.name }}</view>
								<view class="jx-goods-attr">{{ detail.goods_info.attr_list[0].attr_name }}</view>
							</view>
							<view class="jx-price-right">
								<view class="price" :style="{'color':textColor}">¥{{ detail.goods_info.total_original_price }}</view>
							</view>
						</view>
					</jx-list-cell>
				</block>
				<jx-list-cell :hover="false" :last="true">
					<view class="jx-goods-price">
						<view class="jx-size-24">退款金额: ¥</view>
						<view class="jx-price-large" :style="{'color':textColor}">{{ item.refund_price.slice(0, -3) }}</view>
						<view class="jx-size-24" :style="{'color':textColor}">{{ item.refund_price.slice(-3) }}</view>
					</view>
				</jx-list-cell>
				<view class="jx-order-btn">
					<view class="jx-btn-ml">
						<jx-button type="black" :plain="true" width="148rpx" height="56rpx" :size="26" shape="circle" @click="deleted(item.id)">删除记录</jx-button>
					</view>
					<view class="jx-btn-ml">
						<view class="btn-style" :style="{color:textColor,border:'1px solid '+textColor}" @click="toDetail(item.order_detail_id)">查看详情</view>
					</view>
				</view>
			</view>
		</view>
		<view v-else class="refund-list">
			<view class="empty"><main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore></view>
		</view>

		<!--加载loadding-->
		<main-loadmore :visible="loadding" :index="3" :type="textColor"></main-loadmore>
		<main-nomore :visible="!pullUpOn" bgcolor="#fafafa"></main-nomore>
		<main-loading :visible="loading"></main-loading>
		<!--加载loadding-->
	</view>
</template>

<script>
import jxListCell from '@/components/list-cell/list-cell';
import jxButton from '@/components/extend/button/button';
export default {
	components: {
		jxListCell,
		jxButton
	},
	data() {
		return {
			dataList: [],
			pages: {
				current_page: 1,
				pageSize: 20,
				page_count: 1,
				total_count: 0
			},
			loadding: false,
			pullUpOn: true,
			loading: false,
			textColor:''
		};
	},
	onLoad() {
		this.textColor = this.globalSet('textCol');
		this.getDataList(true);
	},
	methods: {
		toDetail(id) {
			uni.navigateTo({
				url: `./detail?id=${id}`
			});
		},
		deleted(id){
			let that=this
			uni.showModal({
				title:'确定删除记录吗？',
				 success: function (res) {
				        if (res.confirm) {
				           that.$http
				           	.request({
				           		url: that.$api.order.refundOrider,
				           		method: 'POST',
				           		data: {
				           			order_refund_id:id
				           		}
				           	})
				           	.then(result => {
				           		if (result.code == 0) {
				           			that.$http.toast(result.msg);
									that.dataList=[]
									that.pages={
										current_page: 1,
										pageSize: 20,
										page_count: 1,
										total_count: 0
									},
									that.loadding=false,
									that.pullUpOn=true,
									that.loading=false,
									that.getDataList(true)
				           		}
				           	}); 
				        } else if (res.cancel) {
				           
				        }
				    }
			})
		},
		getDataList(bool, status) {
			this.loading = bool;
			let { current_page, pageSize } = this.pages;
			this.$http
				.request({
					url: this.$api.order.refundList,
					method: 'POST',
					data: {
						status,
						page: current_page,
						limit: pageSize
					}
				})
				.then(res => {
					this.loading = false;
					if (res.code == 0) {
						let { list, pagination } = res.data;
						this.dataList = list;
						this.pages = pagination;
					}
				});
		}
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
			this.pagination.current_page++;
			this.getDateList();
		}, 1000);
	}
};
</script>

<style lang="scss" scoped>
.app {
	height: 100%;
	background-color: #f7f7f7;
	padding: 0 24rpx;

	.refund-list {
		color: #1e1e1e;

		.refund-item {
			margin-top: 20rpx;
			border-radius: 30rpx;
			overflow: hidden;

			.jx-goods-title {
				width: 100%;
				font-size: 11pt;
				display: flex;
				align-items: center;
				justify-content: space-between;

				.logo {
					display: flex;

					.img {
						width: 40rpx;
						height: 40rpx;
						margin-right: 20rpx;
						border-radius: 10rpx;
					}

					.name {
						color: #1e1e1e;
						font-size: 11pt;
					}
				}

				.jx-order-status {
					color: #bc0100;
					font-size: 9pt;
				}
			}

			.jx-goods-item {
				width: 100%;
				padding: 20rpx 30rpx;
				box-sizing: border-box;
				display: flex;
				justify-content: space-between;

				.jx-goods-img {
					width: 180rpx;
					height: 180rpx;
					display: block;
					flex-shrink: 0;
				}

				.jx-goods-center {
					flex: 1;
					padding: 0 20rpx;
					box-sizing: border-box;

					.jx-goods-name {
						max-width: 310rpx;
						word-break: break-all;
						overflow: hidden;
						text-overflow: ellipsis;
						display: -webkit-box;
						-webkit-box-orient: vertical;
						-webkit-line-clamp: 2;
						font-size: 9pt;
						line-height: 32rpx;
					}

					.jx-goods-attr {
						font-size: 9pt;
						color: #888888;
						line-height: 32rpx;
						padding-top: 20rpx;
						word-break: break-all;
						overflow: hidden;
						text-overflow: ellipsis;
						display: -webkit-box;
						-webkit-box-orient: vertical;
						-webkit-line-clamp: 2;
					}
				}

				.jx-price-right {
					text-align: right;
					line-height: 30rpx;

					.price {
						color: #bc0100;
						font-size: 10pt;
					}
				}
			}

			.jx-goods-price {
				width: 100%;
				display: flex;
				align-items: flex-end;
				justify-content: flex-end;
				font-size: 9pt;
			}

			.jx-order-btn {
				width: 100%;
				display: flex;
				align-items: center;
				justify-content: flex-end;
				background: #fff;
				padding: 10rpx 30rpx 20rpx;
				box-sizing: border-box;
				.jx-btn-ml {
					margin-left: 20rpx;
					.btn-style{
						color: #EB0909 ;
						border-radius: 100rpx;
						border: 1px solid #EB0909;
						font-size: 28rpx;
						padding: 4rpx 24rpx;
					}
				}
			}
		}

		.empty {
			overflow: hidden;
		}
	}
}
</style>
