<template>
	<view class="shopProductItem" @tap.stop="navTo(item.id)">
		<view class="shop-img">
			<image :src="item.cover_pic"
			 mode=""></image>
		</view>
		<view class="shop-title">
			{{item.name}}
		</view>
		<view class="shop-money">
			<view>
				<text>￥</text>
				<text>{{item.price}}</text>
			</view>
			<view>
				{{item.sales}}
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props:{
			item:{
				type: Object,
				default: false 
			}
		},
		data() {
			return {

			};
		},
		methods:{
			navTo(id){
				uni.navigateTo({
					url: `/pages/goods/detail?proId=${id}`
				})
			}
		}
	}
</script>

<style lang="less">
	.shopProductItem {
		width: 325rpx;
		overflow: hidden;
		box-shadow: 0 3rpx 20rpx rgba(183, 183, 183, 0.5);
		margin: 10rpx 0;
		border-radius: 10rpx;
		padding: 5rpx;
	}

	.shopProductItem  .shop-img {
		width: 325rpx;
		overflow: hidden;
	}

	.shop-img image {
		width: 100%;
		display: block;
		height: 325rpx;
	}

	.shop-title {
		width: 100%;
		font-size: 30rpx;
		color: #000;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: normal;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
		/*规定最多显示两行*/
		margin: 10rpx 0;
	}

	.shop-money {
		width: 100%;
		overflow: hidden;
		display: flex;
		justify-content: space-between;
		margin: 10rpx 0;
	}
	.shop-money view:nth-of-type(1){
		font-size: 30rpx;
		color: #07beb4;
	}
	.shop-money view:nth-of-type(2){
		font-size: 25rpx;
		margin-top: 5rpx;
	}
</style>
