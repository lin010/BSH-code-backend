<template>
	<view>
		<!-- 搜索框 -->
		<view class="search" @tap="navTo('/pages/search/search')">
			<search
				:message="message"
				:textAlign="textAlign"
				:frameColor="frameColor"
				:innerFrameColor="innerFrameColor"
				:textColor="textColor"
				:borderRadius="borderRadius"
			></search>
		</view>
		
		<!-- 分类 -->
		<scroll-view scroll-x="true" class="scroll-box flex">
			<view class="scroll-item" v-for="(item,index) in arr" @tap='switchCate(index)'>
				<view :class="{'scroll-item-active' : index == indexs}">{{item}}</view>
			</view>
		</scroll-view>
		
		<!-- 商品 -->
		<view class="goods-box">
			<view class="goods-item flex" v-for="item in 3">
				<image class="goods-cover" :src="img_url+'ceshi/ceshi.png'" mode=""></image>
				<view class="goods-content flex flex-col flex-x-between">
					<view class="goods-name over2">花西子极细三角眉笔/持久防水防汗</view>
					<view class="flex flex-x-bottom flex-y-center">
						<view class="select-goods">选择店铺</view>
					</view>
					<view class="sales">门店地址：广州市白云区机场汇创意 产业园C4栋201</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import search from '@/components/search/search.vue';
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				// 搜索框数据
				message:'大家都在搜“MAC口红”',
				textAlign:'center',
				frameColor:'#FFFFFF',
				innerFrameColor:'#EFECEA',
				textColor:'#999999',
				borderRadius:70,
				
				arr:['洗护','美妆','护理','家电','服装','家具','生活用品','服装','生活用品','护理'],
				indexs:0,
			};
		},
		components: {
			search,
		},
		methods:{
			navTo(url){
				uni.navigateTo({
					url
				})
			},
			switchCate(index){
				this.indexs = index;
			}
		}
	}
</script>

<style lang="scss" scoped>
	.scroll-box{
		white-space: nowrap;
		background: #FFFFFF;
		padding-left: 10rpx;
		
		.scroll-item{
			display: inline-block;
			margin-right: 20rpx;
			padding: 6rpx 20rpx 6rpx;
			font-size: 32rpx;
			color: #000000;
			letter-spacing: 1px;
		}
		.scroll-item-active{
			color: #BC0100;
			font-weight: 600;
			// border-bottom: 3px solid #BC0100;
			padding-bottom: 8rpx;
			
			&::after{
				content: '';
				display: block;
				width: 50rpx;
				height: 3px;
				background: #BC0100;
				margin: 4rpx auto 0;
			}
		}
	}
	
	.goods-box{
		background: #FFFFFF;
		margin-top: 10rpx;
		padding: 14rpx 30rpx;
		
		.goods-item{
			padding: 20rpx 0;
			
			.goods-cover{
				width: 250rpx;
				height: 250rpx;
				border-radius: 10rpx;
				margin-right: 34rpx;
			}
			
			.goods-content{
				flex: 1;
				
				.goods-name{
					color: #000000;
				}
				.goods-price{
					font-size: 42rpx;
					color: #BC0100;
					font-weight: 600;
				}
				.select-goods{
					background: linear-gradient(90deg,#F14A2E,#BC0100);;
					border-radius: 50rpx;
					color: #FFFFFF;
					font-size: 24rpx;
					text-align: center;
					padding: 10rpx 26rpx;
					line-height: 32rpx;
				}
				.sales{
					font-size: 24rpx;
					color: #999999;
				}
			}
		}
	}
</style>
