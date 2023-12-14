<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="发货明细" :status-bar="true" 
		background-color="#FFFFFF" :border="true" color="#000000"></com-nav-bar>
		<com-tabs :tabs="tbas" :current-tab="status" :height="100" color="#333333" :selected-color="textColor" :padding="0"
		 bottom="20rpx" :slider-height="4" :slider-width="110" :slider-bg-color="textColor" itemWidth="33.33%" @change="statusChange"></com-tabs>

		<view class="list">
			<view class="items" v-if="list&&list.length > 0">
				<view class="item flex flex-y-center" v-for="(item, index) in list" :key="index">
					<image :src="item.cover_pic" class="goods-cover"></image>
					<view class="item-goods-info">
						<view class="goods-name over1">{{ item.goods_name }}</view>
						<view class="goods-nums flex">
							<view class="goods-num" :style="{color:textColor,border:'1px solid'+textColor}">数量:{{item.num}} 件</view>
						</view>
						<view class="goods-cost flex-x-between">
							<view>
								<view class="flex" style="margin-right: 20rpx;">
									<view class="text" style="width: 88rpx;">收货人:</view>
									<view class="price flex" :style="{color:textColor}">
										<span>{{item.name}} {{item.mobile}}</span>
									</view>
								</view>
								<view class="flex" style="margin-right: 20rpx;">
									<view class="text" style="width: 70rpx;">地址:</view>
									<view class="price flex" :style="{color:textColor}">
										<span>{{ item.address }}</span>
									</view>
								</view>
							</view>
							<view class="btn flex-x-center flex-y-center jx-primary-btn" :style="{background:textColor}" v-if="item.status == 1">
								<!-- status!=2-->
								<view @click="confirm(item.id)">确认收货</view>
							</view>
						</view>
					</view>
				</view>
			</view>
			<view class="items" v-else>
				<main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore>
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
				status: 0,
				list: [],
				page: 1,
				is_no_more: false,
				
				textColor:'#bc0100',
				bg_url:'',
				button:[],
			};
		},
		computed: {
			tbas() {
				let temp = [{
					name: '待发货',
					type: '0'
				}, {
					name: '待收货',
					type: '1'
				}, {
					name: '已完成',
					type: '2'
				}];
				return temp;
			}
		},
		onLoad: function(options) {
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.button = this.globalSet('btnCol');
			
			this.getList();
		},
		methods: {
			back(){
				this.navBack();
			},
			statusChange(e) {
				this.status = e.index;
				this.list=[];
				this.page=1;
				this.is_no_more=false;
				this.getList();
			},
			confirm(order_id){
				let that=this;
				uni.showModal({
					title:'提示',
					content:'确认收货',
					success:function(e){
						if(e.confirm){
							that.$http.request({
								url: that.$api.plugin.stock.order_confirm,
								data: {
									order_id: order_id,
								}
							}).then(res => {
								this.loading = false;
								if (res.code == 0) {
									that.list=[];
									that.page=1;
									that.is_no_more=false;
									that.getList();
								} else {
									uni.showToast({
										title: res.msg
									});
								}
							});
						}
					}
				})
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
				this.$http.request({
						url: this.$api.plugin.stock.agent_order_list,
						data: {
							page: this.page,
							type: this.status //类型
						}
					}).then(res => {
						this.loading = false;
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

		}
	}
</script>

<style lang="scss" scoped>
	.app {
		height: 100%;
		background-color: #F2F2F2;
	}

	.list {
		margin: 20rpx 0;
		background-color: #ffffff;

		.item {
			padding: 24rpx 30rpx;
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
				flex-grow: 1;

				.goods-name {
					color: #333333;
					max-width: 450rpx;
					font-size: 36rpx;
				}

				.goods-nums {
					color: #bc0100;
					font-size: 18rpx;
					line-height: 18rpx;
					margin: 20rpx 0;

					.goods-num {
						padding: 7rpx 9rpx;
						border-radius: 5rpx;
						border: 1rpx solid #bc0100;
						margin-right: 18rpx;
					}
				}

				.goods-cost {
					.text {
						color: #666666;
						font-size: 18rpx;
					}

					.price {
						color: #bc0100;
						font-size: 24rpx;
						align-items: baseline;
						flex: 1;
					}

					.dot {
						font-size: 18rpx;
						line-height: 18rpx;
					}

					.btn {
						width: 133rpx;
						height: 40rpx;
						border-radius: 20rpx;
						font-size: 22rpx;
					}
				}
			}
		}
	}
</style>
