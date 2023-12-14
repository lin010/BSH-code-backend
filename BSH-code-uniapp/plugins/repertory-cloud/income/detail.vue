<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="收益详细" :status-bar="true" background-color="#FFFFFF" :border="true" color="#000000"></com-nav-bar>
		<view class="detail">
			<view class="title">
				<view class="text">订单信息</view>
			</view>
			<view class="info">
				<view class="line">
					<view class="flex margin-bottom-40rpx">
						<view class="margin-right-74rpx">订货奖金:</view>
						<view class="primary-color">+{{'39.9'}}</view>
					</view>
					<view class="flex margin-bottom-40rpx">
						<view class="margin-right-74rpx">结算天数:</view>
						<view>{{7}}天</view>
					</view>
					<view class="flex margin-bottom-40rpx">
						<view class="margin-right-74rpx">分佣时间:</view>
						<view>{{'2020-05-10 10:55:24'}}</view>
					</view>
					<view class="flex">
						<view class="margin-right-74rpx">结算时间:</view>
						<view>{{'2020-05-10 10:55:24'}}</view>
					</view>
				</view>
				<view class="line">
					<view class="flex margin-bottom-40rpx">
						<view class="margin-right-74rpx">订单编号:</view>
						<view>{{'SN965117570900487836'}}</view>
						<view class="copy primary-color flex-grow-1" @click="copyText($event,'SN965117570900487836')">复制</view>
					</view>
					<view class="flex">
						<view class="margin-right-74rpx">订单状态:</view>
						<view>{{'交易完成'}}</view>
					</view>
				</view>
				<view class="line">
					<view class="flex margin-bottom-40rpx">
						<view class="margin-right-74rpx">商品名称:</view>
						<view class="goods-name over1">{{'Chanel/香奈儿流浪包单肩包'}}</view>
					</view>
					<view class="flex">
						<view class="margin-right-74rpx">付款金额:</view>
						<view>{{'399'}}元</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	const thorui = require("@/components/common/tui-clipboard/tui-clipboard.js");
	export default {
		data() {
			return {
				loading: false,
				data: null
			};
		},
		onLoad(options) {
			let {id} = options;
			if(id){
				this.getData(id);
			}
		},
		methods:{
			copyText(event,data){
				thorui.getClipboardData(data, (res) => {
					// #ifdef H5 || MP-ALIPAY
					if (res) {
						this.$http.toast("复制成功");
					} else {
						this.$http.toast("复制失败")
					}
					// #endif
				},event)
			},
			getData(){
				
			},
			back(){
				this.navBack();
			}
		}
	}
</script>

<style lang="scss" scoped>
.app{
	height: 100%;
	background-color: #F7F7F7;
}
.detail{
	margin: 30rpx 30rpx 0;
	// height:716rpx;
	background-color: #FFFFFF;
	border-radius:15rpx;
	
	.title{
		padding: 30rpx 30rpx 0;
		color: #000000;
		font-size: 30rpx;
		
		.text{
			position: relative;
			
			&::after{
				top: 50%;
				transform: translateY(-50%);
				left: -10rpx;
				position: absolute;
				content: "";
				width:4rpx;
				height:28rpx;
				background-color: $jx-color-primary;
			}
		}
	}
	.info{
		color: #636363;
		font-size: 25rpx;
		
		.line{
			padding: 42rpx 25rpx;
			border-bottom: 2rpx solid #F2F2F2;
			
			&:last-child{
				border-bottom: 0;
			}
		}
		
		.goods-name{
			max-width: 350rpx;
		}
		
		.copy{
			text-align: right;
		}
	}
}
.margin-bottom-40rpx{
	margin-bottom: 40rpx;
}
.margin-right-74rpx{
	margin-right: 74rpx;
}
.primary-color{
	color: $jx-color-primary;
}
</style>
