<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="我的收益" :status-bar="true" background-color="#BC0100" :border="false" color="#ffffff"></com-nav-bar>
		<view class="container">
			<image src="https://pic.downk.cc/item/5eccd9e9c2a9a83be50140b6.png" mode="aspectFill" class="jx-bg"></image>
			<view class="content">
				<view class="withdraw flex" @click="">
					<view class="icon-text flex">
						<view class="icon iconfont icon-tixian"></view>
						<view class="text">我要提现</view>
						<view class="icon iconfont icon-right"></view>
					</view>
				</view>
				<view class="income">
					<view class="total flex">
						<view class="text">总金额 (元)</view>
						<view class="sum">{{ '159968.25' }}</view>
						<view class="yesterday">昨日收益{{ 135.03 }}元</view>
					</view>
					<view class="status flex">
						<view class="text-money flex-col flex-y-center flex-grow-1">
							<view class="text">昨天总收益</view>
							<view class="money">{{ '583.99' }}元</view>
						</view>
						<view class="text-money flex-col flex-y-center flex-grow-1">
							<view class="text">本周收益</view>
							<view class="money">{{ '193.91' }}元</view>
						</view>
						<view class="text-money flex-col flex-y-center flex-grow-1">
							<view class="text">本月收益</view>
							<view class="money">{{ '133.51' }}元</view>
						</view>
					</view>
				</view>
				<view class="list">
					<view class="list-title">
						<span class="text">收益明细</span>
					</view>
					<view class="items" v-if="0 == 0">
						<view class="item flex flex-x-between flex-y-center" v-for="(item,index) in 9" :key="index">
							<view class="item-info">
								<view class="item-title">{{'Mary-mlh买了女装包包'}}</view>
								<view class="item-datetime">{{'2020-05-12 10:16'}}</view>
							</view>
							<view class="status flex">
								<span>+</span>
								<!-- <span>-</span> -->
								<span>39.9</span>
							</view>
						</view>
					</view>
					<view class="items" v-else>
						<main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore>
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
			data: null,
			loading: false
		};
	},
	onLoad() {},
	methods: {
		getData() {},
		back(){
			this.navBack();
		}
	},
	onPullDownRefresh() {}
};
</script>

<style lang="scss" scoped>
.app {
	min-height: 100%;
	background-color: #f7f7f7;
}
.container {
	color: #ffffff;
	position: relative;

	.jx-bg {
		position: absolute;
		top: 0;
		width: 100%;
		height: 540rpx;
	}

	.content {
		position: relative;
		.withdraw {
			justify-content: flex-end;
		
			.icon-text {
				align-items: center;
				background-color: #ec7676;
				font-size: 9pt;
				line-height: 54rpx;
				padding: 0 10rpx;
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
		
		.income {
			padding: 0 30rpx;
			margin-bottom: 60rpx;
		
			.total {
				align-items: center;
				justify-content: center;
				flex-direction: column;
				margin-bottom: 80rpx;
				font-size: 11pt;
		
				.sum {
					font-size: 23pt;
					font-weight: bold;
				}
		
				.yesterday {
					padding: 0 20rpx;
					background-color: #ffffff;
					color: #bc0100;
					font-size: 9pt;
					font-weight: 700;
					line-height: 45rpx;
					border-radius: 22rpx;
				}
			}
		
			.status {
				font-size: 12pt;
		
				.text {
					font-size: 10pt;
				}
			}
		}
		
		.list{
			margin: 0 30rpx 36rpx;
			background-color: #FFFFFF;
			border-radius: 15rpx;
			height: 100%;
			.list-title{
				height: 110rpx;
				line-height: 110rpx;
				padding: 0 32rpx;
				color: #000000;
				font-size: 11.5pt;
				border-bottom: 1rpx solid #F2F2F2;
				
				.text{
					position: relative;
					
					&::after{
						content: '';
						position: absolute;
						top: 50%;
						transform: translateY(-50%);
						left: -12rpx;
						width:4rpx;
						height:28rpx;
						background-color: #BC0100;
					}
				}
			}
				
			.item{
				padding: 20rpx 32rpx;
				border-bottom: 1rpx solid #F2F2F2;
				line-height: 37rpx;
				height: 108rpx;
				
				&:last-child{
					border-bottom: 0;
				}
			}
			
			.item-title{
				color: #333333;
				font-size: 11.5pt;
			}
			
			.item-datetime{
				color: #666666;
				font-size: 9pt;
			}
			
			.status{
				color: #BC0100;
				font-size: 11.5pt;
			}
		}
	}
}
</style>
