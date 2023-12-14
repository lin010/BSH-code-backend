<template>
	<view class="shopping-coupon">
		<view class="shopping-main">
			<view class="shopping-tools shopping-coupon-list">
			
				<view v-if="dataList && dataList.length" class="coupon-list">
					<view v-for="(item, i) in dataList" :key="i" class="coupon-list-item">
						<view class="item-time">{{item.created_at}}</view>
						<view class="coupon-list-item-main">
							<view class="item-left">
								<view class="item-income">账号：{{item.nickname}}</view>
								<view class="item-income">手机：{{item.mobile}}</view>
							</view>
							<view class="item-right">
								<view @click="deleteOn(item)" class="item-button">删除</view>
							</view>
						</view>
					</view>
				</view>
				<view v-else class="list-empty"><main-nomore text="暂无数据" :visible="true" bgcolor="#fff"></main-nomore></view>
				
				<!--加载loadding-->
				<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
				<main-loading :visible="loading"></main-loading>
				<!--加载loadding-->
				
			</view>
		
			<view class="add-sub-account"> 
				<view @click="addLink(1)" class="add-btn">添加子账号</view>
			</view> 
		</view>
	</view>
</template>

<script>
	const _status = 'refresh';
	export default {
		data() { 
			return {
				pages: {
					total_count: 1,
					page_count: 1,
					pageSize: 10,
					current_page: 1
				},
				loadding: false,
				loading: false,
				dataList: []
			}
		},
		onShow() {
			this.getList(_status, true);
		},
		onReachBottom() {
			this.loadding = true;
			let { current_page, page_count } = this.pages;
			setTimeout(() => {
				this.loadding = false;
				if (current_page >= page_count) {
					return;
				}
				this.pages.current_page++;
				this.getList();
			}, 1000);
		},
		methods: {
			deleteOn(item){
				let that = this;
				uni.showModal({
					content: '确定要删除该账户吗?',
					confirmColor: "#C90A0C",
					success: function(res) {
						if (res.confirm) {
							that.$http.request({
								showLoading: true,
								url: that.$api.moreShop.deleteSubAccount,
								data: {
									user_id: item.user_id
								}
							}).then(res => {
								that.$http.toast(res.msg);
								if (res.code == 0) {
									let tmps = [];
									for(var i=0; i < that.dataList.length; i++){
										if(that.dataList[i].user_id != item.user_id){
											tmps.push(that.dataList[i]);
										}
									}
									that.dataList = tmps;
									that.loading = false;
								}
							});
						}
					}
				});
			},
			getList(status, bool) {
				this.loading = bool ? true : false;
				if (status == 'refresh') {
					this.pages = {
						current_page: 1,
						pageSize: 20,
						page_count: 1,
						total_count: 0
					};
				}
				let { current_page, pageSize } = this.pages;
				this.$http.request({
					url: this.$api.moreShop.subAccountList,
					method: 'POST',
					data: {
						page: current_page,
						limit: pageSize
					}
				}).then(res => {
						this.loading = false;
						if (res.code === 0) {
							let { list, pagination } = res.data;
							this.dataList = status != 'refresh' ? this.dataList.concat(list) : list;
							this.pages = pagination;
						}
				});
			},
			addLink() { 
				uni.navigateTo({
					url:'./add/add'
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.shopping-coupon{
		.shopping-main{
			box-sizing: border-box;
			margin: 0 30rpx;
			.shopping-tools{
				box-sizing: border-box;
				margin-top: 20rpx;
				// height: 160rpx;
				background: #FFFFFF;
				border-radius: 10rpx;
				.list-empty{
					overflow: hidden;
				}
			}
			.shopping-coupon-list{
				margin-bottom: 90rpx;
				.coupon-list{
					.coupon-list-item{
						box-sizing: border-box;
						padding: 20rpx 26rpx;
						border-bottom:1rpx solid #f7f7f7;
						.item-time{
							font-size: 22rpx;
							font-family: Source Han Sans CN;
							font-weight: 400;
							color: #999999;
							line-height: 37rpx;
						}
						.coupon-list-item-main{
							display: flex;
							align-items: flex-start;
							justify-content: space-between;
							font-size: 28rpx;
							font-family: Source Han Sans CN;
							font-weight: 400;
							color: #333333;
							.item-income{
								height:50rpx;
								line-height:50rpx;
							}
							.item-right{
								display: flex;
								align-items: center;
								height:100rpx;
								.item-button{
									width: 100rpx;
									height: 50rpx;
									text-align: center;
									background:#ee4411;
									border-radius: 20rpx;
									font-size: 22rpx;
									font-family: Source Han Sans CN;
									font-weight: 400;
									color: #FFFFFF;
									line-height: 50rpx;
								}
							}
						}
					}
				}
			}
			
			
		}
	}
	
	.add-sub-account{
		position: fixed;
		z-index: 10;
		bottom: 0rpx;
		left: 0rpx;
		width: 100%;
		.add-btn{
			background:#FF7104;
			font-size: 28rpx;
			font-family: Source Han Sans CN;
			font-weight: 400;
			color: #FFFFFF;
			height: 90rpx;
			line-height: 90rpx;
			text-align: center;
			letter-spacing: 2rpx;
		}
	}
	
	.nothing{
		padding-top: 200rpx;
		text-align: center;
		letter-spacing: 1px;
		
	}
</style>

