<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="收益明细" :status-bar="true" background-color="#FFFFFF" :border="true" color="#000000"></com-nav-bar>
		<com-tabs :tabs="tbas" :height="100" color="#333333" selected-color="#333333" :padding="0" bottom="20rpx" :slider-height="4" :slider-width="110" slider-bg-color="#BC0100"></com-tabs>
		<view class="items" v-if="dataList && dataList.length > 0">
			<view class="item" v-for="(item,i) in dataList" :key="i">
				<view class="user-status">
					<image :src="item.avatar_url" mode="aspectFill" class="acatar"></image>
					<view class="name-datetime">
						<view class="flex">
							<view class="name over1">{{ item.nickname }}</view>
							<view class="id">ID:{{ item.user_id }}</view>
						</view>
						<view class="datetime">下单时间:{{ item.created_at }}</view>
					</view>
					<view class="status-text">{{ item.status }}</view>
				</view>
				<view class="info">
					<view class="mark flex-x-between">
						<view class="goods-name">商品名称: {{ item.goods_name }}</view>
						<view class="order-money">订单金额: {{ item.goods_price }}元</view>
					</view>
					<view class="money flex-x-between">
						<view class="order-id">订单编号: {{ item.order_no }}</view>
						<view class="commission">奖励：{{ item.price }} 元</view>
					</view>
				</view>
			</view>
		</view>
		<view class="order-list" v-else><main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore></view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				loading: false,
				tabsub: 0,
				dataList: []
			};
		},
		onLoad() {
			for (var i = 0; i < 9; i++) {
				this.dataList.push({
					user_id: i+1,
					avatar_url: 'http://yingmlife-1302693724.cos.ap-guangzhou.myqcloud.com/uploads/images/original/20201216/15262b999e48acc5891864e3f2463cb0.jpg',
					nickname: '小可爱',
					mobile: '13200000000',
					status: '货款收入',
					created_at: '2020-05-05 10:16:34',
					goods_name: '时尚女包',
					order_no: 'SN965117570900487836',
					goods_price: '399',
					price: '39.9'
				})
			}
		},
		computed:{
			tbas(){
				let temp = [
					{
						name: '全部',
						type: '0'
					},{
						name: '货款收益',
						type: '1'
					},{
						name: '订货奖励',
						type: '2'
					},{
						name: '平级奖励',
						type: '3'
					},{
						name: '越级奖励',
						type: '4'
					},
				];
				return temp;
			}
		},
		methods:{
			back(){
				this.navBack();
			}
		}
	}
</script>

<style lang="scss" scoped>
.app{
	height: 100%;
	background-color: #F2F2F2;
}
.items{
	background-color: #FFFFFF;
	margin-top: 20rpx;
	padding: 0 30rpx;
	
	.item {
		padding: 20rpx 0;
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
					max-width:142rpx;
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
				padding: 0 18rpx;
				height: 36rpx;
				background-color: #bc0100;
				border: 1rpx solid #bc0100;
				border-radius: 18rpx;
				color: #ffffff;
				font-size:22rpx;
				line-height: 36rpx;
				text-align: center;
			}
		}
			
		.info {
			font-size: 22rpx;
			
			.mark {
				
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
</style>
