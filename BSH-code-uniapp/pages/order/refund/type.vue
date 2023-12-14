<template>
	<view class="app">
		<view class="container">
			<view class="image-text" v-if="data">
				<image class="image" :src="data.goods_info.pic_url" mode="aspectFit"></image>
				<view class="goods-info">
					<view class="goods-name">{{data.goods_info.name}}</view>
					<view class="goods-attr-name">{{data.goods_info.attr_list[0].attr_group_name}}:{{data.goods_info.attr_list[0].attr_name}}</view>
				</view>
			</view>
			<view class="select">
				<block v-if="data.type_list!=undefined && data.type_list.length==0">
					<view class="item refund">
						<view class="iconfont icon-gantanhao1" :style="{color:textColor}"></view>
						<view class="text">
							<view class="title">注意</view>
							<view class="msg">当前商品不支持退款、退货退款、换货</view>
						</view>
					</view>
				</block>
				<block v-else>
					<view class="item refund" @click="openUrl(index)" v-for="(item, index) in data.type_list">
						<view class="iconfont icon-tuihuotuikuan" :style="{color:textColor}"></view>
						<view class="text">
							<view class="title">{{cannotrefund[index]['title']}}</view>
							<view class="msg">{{cannotrefund[index]['desc']}}</view>
						</view>
						<view class="iconfont icon-xiala right"></view>
					</view>
				</block>
				
				<!-- <view class="item refunds" @click="openUrl(1)">
					<view class="iconfont icon-tuihuotuikuan1"></view>
					<view class="text">
						<view class="title">我要退货退款</view>
						<view class="msg">已收到货，需要退还收到的货物</view>
					</view>
					<view class="iconfont icon-xiala right"></view>
				</view> -->
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
				loading: false,
				cannotrefund:[
					{title:'我要退款(无需退货)',desc:'没收到货，或与卖家协商同意不用退货只退款'},
					{title:'我要退货退款',desc:'已收到货，需要退还收到的货物'},
					//{title:'换货',desc:'已收到货，需要更换已收到的货物'},
				],
				textColor: '#bc0100',
			}
		},
		onLoad(options) {
			this.textColor = this.globalSet('textCol');
			if(options.id){
				this.getDetail(options.id,true);
			}
		},
		methods: {
			getDetail(id,bool){
				this.loading = bool ? true : false;
				this.$http.request({
					url: this.$api.order.refundData,
					method: 'POST',
					data: {
						order_detail_id: id
					}
				}).then(res => {
					this.loading = false;
					if(res.code === 0){
						let {detail} = res.data;
						this.data = detail;
					}
				})
			},
			openUrl(status){
				uni.redirectTo({
					url: `./refund?status=${status}&id=${this.data.order_detail_id}`
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
.app{
	height: 100%;
	background-color: #F7F7F7;
	
	.container{
		
		.image-text{
			padding: 20rpx 30rpx;
			background-color: #FFFFFF;
			margin-bottom: 20rpx;
			display: flex;
			
			.image{
				max-width: 160rpx;
				height: 160rpx;
				margin-right: 20rpx;
			}
			.goods-info{
				color: #333333;
				
				.goods-name{
					font-size: 10pt;
					margin-bottom: 10rpx;
				}
				.goods-attr-name{
					font-size: 9pt;
					color: #B4B4B4;
				}
			}
		}
		
		.select{
			
			.item{
				background-color: #FFFFFF;
				padding: 20rpx 30rpx;
				display: flex;
				align-items: center;
				border-bottom: 2rpx solid #F7F7F7;
				
				.iconfont{
					
					color: rgb(7, 190, 180);
					font-size: 20pt;
					margin-right: 30rpx;
					
					&.right{
						margin: 0;
						color: #A2A2A2;
					}
				}
				
				.text{
					font-size: 9pt;
					flex: 1;
					.title{
						color: #333333;
						font-size: 12pt;
						font-weight:400;
						margin-bottom: 12rpx;
					}
					.msg{
						color: #B4B4B4;
					}
				}
			}
		}
	}
}
</style>
