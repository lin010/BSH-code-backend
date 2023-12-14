<template>
	<view>
		<view class="header">
			<view class="header-search">
				<view class="iconfont icon-search"></view>
				<view class="search-text">大家都在搜“MAC口红”</view>
			</view>
			<!-- 分类 -->
			<sliding :cateList='cateList'></sliding>
		</view>
		
		<!-- 商品 -->
		<view class="goods-item-box">
			<view class="goods-item" v-for="item in 5">
				<image class="goods-img" :src="img_url+'ceshi/ceshi.png'" mode=""></image>
				<view class="goods-content">
					<view class="goods-name">花西子极细三角眉笔/持久防水防汗</view>
					<view class="goods-price">
						<view>&yen; <text class="price">170</text></view>
						<view class="shareEarn">
							<view>分享赚</view>
							<view class="shareEarn-price-box">&yen; <text class="shareEarn-price">13.6</text></view>
						</view>
					</view>
					<view class="goods-sale">
						已售:189件
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import sliding from '@/components/slidingCate/slidingCate.vue'
	export default {
		components:{
			sliding
		},
		data() {
			return {
				img_url: this.$api.img_url,
				cateList:[
					{
						name:'洗护'
					},
					{
						name:'美妆'
					},
					{
						name:'护理'
					},
					{
						name:'家电'
					},
					{
						name:'服装'
					},
					{
						name:'家电'
					},
					{
						name:'生活用品'
					},
					{
						name:'内衣配饰'
					},
					{
						name:'钟表珠宝'
					},
				]
			}
		},
		methods: {
			
		}
	}
</script>

<style scoped>
	.header{
		background: #FFFFFF;
		padding: 10rpx 30rpx 20rpx;
	}
	.header-search{
		background: #F5F5F5;
		height: 60rpx;
		border-radius: 30px;
		margin-bottom: 16rpx;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.search-text{
		color: #999999;
		font-size: 11pt;
		margin-left: 16rpx;
	}
	
	.scroll-view{
		white-space: nowrap;
		position: relative;
	}
	.scroll-item{
		display: inline-block;
	}
	
	.goods-item-box{
		padding: 40rpx 30rpx;
		background: #FFFFFF;
		margin-top: 14rpx;
	}
	.goods-item{
		display: flex;
		justify-content: space-between;
		margin-bottom: 50rpx;
	}
	.goods-img{
		width: 250rpx;
		height: 250rpx;
		border-radius: 10rpx;
		display: block;
		margin-right: 34rpx;
	}
	.goods-content{
		flex: 1;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
	}
	.goods-name{
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		color: #000000;
		font-size: 11pt;
		letter-spacing: 2rpx;
	}
	.goods-price{
		color: #BC0100;
		font-size: 10pt;
		font-weight: 600;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}
	.price{
		font-size: 15pt;
		margin-left: 4rpx;
	}
	.shareEarn{
		background:linear-gradient(90deg,rgba(241,74,46,1),rgba(188,1,0,1));
		width: 146rpx;
		height: 74rpx;
		text-align: center;
		color: #FFFFFF;
		font-size: 8pt;
		font-weight: 300;
		letter-spacing: 2rpx;
		border-radius: 40rpx 0 0 40rpx;
		padding: 4rpx 0 0 4rpx;
		box-sizing: border-box;
	}
	.shareEarn-price-box{
		font-size: 22rpx;
	}
	.shareEarn-price{
		font-weight: 600;
		font-size: 26rpx;
		margin-left: 4rpx;
	}
	.goods-sale{
		color: #999999;
		font-size: 9pt;
	}
</style>
