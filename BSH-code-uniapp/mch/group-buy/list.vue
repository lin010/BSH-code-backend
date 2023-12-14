<template>
	<view class="list-root">
		<view class="app-header">
			<view class="tui-mtop">
				<com-tabs :tabs="tabs" :currentTab="currentTab" :selectedColor="textColor" :sliderBgColor="textColor" color="#000000"
				 @change="change" :sliderWidth="60" itemWidth="50%"></com-tabs>
			</view>
		</view>
		
		<view class="list-box">
			<view class="list-items flex flex-x-between" v-for="(item,index) in detailData" :key='index' @tap="navTo(item.group_buy_goods.goods_id)">
				<image class="list-item-left" :src="item.goods_warehouse.cover_pic"></image>
				<view class="list-item-right flex flex-col flex-x-between">
					<view class="over2 pro-name">{{item.goods_warehouse.name}}</view>
					
					<view class="pro-detail">
						<view class="group-item-center flex">
							<view class="people-num flex flex-x-center flex-y-center" :style="{background:textColor}">{{item.group_buy_goods.people}}人团</view>
							<view v-if="currentTab==1" class="group-start flex flex-y-center" :style="{color:textColor}">{{item.group_buy_goods.start_at_format}}后开抢</view>
						</view>
						<view class="pro-detail-bottom flex flex-y-center flex-x-between">
							<view class="flex flex-y-center">
								<view class="flex flex-y-bottom price-box" :style="{color:textColor}">
									&yen; <text class="price">{{item.group_buy_goods.price}}</text>
								</view>
								<block v-if="currentTab==0">
									<view v-if="item.group_buy_goods.sales>10000" class="group-num">已团:{{parseInt(item.group_buy_goods.sales/10000)}}+万件</view>
									<view v-else class="group-num">已团:{{item.group_buy_goods.sales}}件</view>
								</block>
							</view>
							<view v-if="currentTab==0" @tap.stop="navTo(item.group_buy_goods.goods_id)" :style="{background:textColor}" class="start-btn-bottom start-btn">开团抢</view>
							<view v-else-if="currentTab==1" @tap.stop="showStart(item.group_buy_goods.start_at_format)" :style="{background:textColor}" class="start-btn-bottom show-start-btn">未开始</view>
							<!-- <view @tap.stop="navToDetail(item.group_buy_goods.id)" class="start-btn">立即开团</view> -->
						</view>
					</view>
				</view>
			</view>
		</view>
		
		<view class="coupon-items" v-if="nodata">
			<main-nomore text="暂无数据" :visible="true" bgcolor="transparent"></main-nomore>
		</view>
	</view>
	
</template>

<script>
	export default {
		data() {
			return {
				dataStatus: 0,
				currentTab: 0,
				tabs: [{
					name: "进行中"
				}, {
					name: "未开始"
				}],
				textColor:'#bc0100',
				
				img_url: this.$api.img_url,
				
				detailData:[],
				page:1,
				page_count:5,
				nodata : false,
				requestFlag : true,	//默认是可以请求的
				textColor : '#bc0100',	//全局颜色
			};
		},
		onLoad() {
			this.textColor = this.globalSet('textCol');
			// 获取全局设置的样式
			let navBg = this.globalSet('navBg');
			let navCol = this.globalSet('navCol');
			console.log(navCol);
			if(navBg&&navCol){
				uni.setNavigationBarColor({
					frontColor: navCol,
					backgroundColor: navBg,
					animation: {
						duration: 500,
						timingFunc: 'easeIn'
					}
				});
			}
			let status = this.currentTab==0?1:0;
			console.log(status);
			this.getList(status);
		},
		/* 触底加载更多 */
		onReachBottom(){
			let status = this.currentTab==0?1:0;
			this.page++;
			if(this.page <= this.page_count){
				this.getList(status);
			}
		},
		methods:{
			// tab的点击事件
			change(e) {
				if(!this.requestFlag){
					return
				}
				this.currentTab = e.index;
				// 初始化数据
				this.detailData = [];
				this.page = 1;
				let status = this.currentTab==0?1:0;
				this.getList(status);
			},
			// 拼团活动暂未开始
			showStart(e){
				this.$http.toast(e+'钟后开抢');
			},
			
			// 0.1 跳转商品详情
			navTo(id){
				if(this.currentTab==0){
					uni.navigateTo({
						url:`/mch/group-buy/good?proId=${id}`
					})
				}
			},
			// 0.2 跳转该团详情
			navToDetail(id){
				uni.navigateTo({
					url:`/mch/group-buy/detail?detailId=${id}`
				})
			},
			
			
			// 1.0 获取拼团列表
			getList(status){
				if(!this.requestFlag){
					return
				}
				this.requestFlag = false;	//请求中切换为不可请求
				this.$http.request({
					url: this.$api.plugin.group.getList,
					method:'post',
					showLoading:true,
					data:{
						status:status,
						page:this.page,
					}
				}).then(res => {
					this.requestFlag = true;	//请求完成切换为可以请求
					if(res.code == 0){
						if(this.page==1){	//请求的第一列数据
							this.nodata = res.data.list.length==0?true:false;
						}
						this.detailData.push(...res.data.list);
						this.page_count = res.data.pagination.page_count;
					}else{
						this.$http.toast(res.msg);
					}
				})
			}
		}
	}
</script>

<style lang="scss">
	.group-item-center{
		margin-bottom: 32rpx;
	}
	
	.list-root{
		padding-top: 20rpx;
	}
	
	.list-box{
		background: #FFFFFF;
		padding: 0 30rpx;
	}
	
	.list-items{
		padding: 24rpx 0;
		border-bottom: 1px solid #F2F2F2;
		
		.list-item-left{
			width: 218rpx;
			height: 218rpx;
			border-radius: 10rpx;
			margin-right: 24rpx;
		}
		.list-item-right{
			flex: 1;
			padding: 6rpx 0;
			
			.pro-name{
				font-size: 32rpx;
				font-family: Microsoft YaHei;
				font-weight: 400;
				color: #333333;
				line-height: 48rpx;
			}
			
			.pro-detail{
				.group-start{
					height: 30rpx;
					margin-left: 4rpx;
					margin-bottom: 8rpx;
					// text-align:left;
					font-size: 20rpx;
					font-family: Microsoft YaHei;
					font-weight: 400;
					line-height: 48rpx;
					padding: 4rpx 8rpx;
					background: rgba(248,221,221,.3);
					border-radius: 0rpx 4rpx 4rpx 0rpx;
				}
				.people-num{
					width: 80rpx;
					height: 30rpx;
					letter-spacing: 2rpx;
					margin-bottom: 2rpx;
					font-size: 20rpx;
					font-family: Microsoft YaHei;
					font-weight: 400;
					color: #FFFFFF;
					border-radius: 4rpx 0rpx 0rpx 4rpx;
				}
				.pro-detail-bottom{
					font-size: 24rpx;
					.price-box{
						margin-right: 14rpx;
						.price{
							font-size: 40rpx;
							line-height: 48rpx;
						}
					}
					.group-num{
						color: #999999;
						letter-spacing: 1px;
						transform: scale(0.9);
						margin-top: 4rpx;
					}
					.start-btn-bottom{
						color: #FFFFFF;
						letter-spacing: 1px;
						text-align: center;
						width: 100rpx;
						height: 56rpx;
						line-height: 56rpx;
						background: linear-gradient(90deg, #D92F2F, #FB3939);
						border-radius: 6rpx;
					}
				}
			}
		}
	}
</style>
