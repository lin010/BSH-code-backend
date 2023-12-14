<template>
	<view class="app">
		<view class="container">
			<view class="img-msg flex-col flex-y-center">
				<!-- <view class="img"></view> -->
				<image class="img" :src="img_url+'images/cart/cart-nothing.png'" mode=""></image>
				<view class="msg">感谢您对商品的评价，祝您生活愉快~</view>
			</view>
			<!-- #ifdef H5 || MP -->
			<view class="btn" @click="toIndex" :style="{'background-color':textColor}">
				返回上一页
			</view>
			<!--#endif -->
			<!-- #ifdef APP-PLUS -->
			<view class="btn" @click="toIndex" :style="{'background-color':textColor}">
				返回订单列表
			</view>
			<!--#endif -->
		</view>
		<view class="guessLike">
			猜你喜欢
		</view>
		<view style="padding: 0 20rpx;">
			<commodity :listStyle='listStyle' key-value='cart' :displayStyle='displayStyle' :productData='productData'></commodity>
		</view>
	</view>
</template>

<script>
	import commodity from '@/components/commodity/commodity';
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				//猜你喜欢数据
				listStyle: 2, //1为大图 2为一行一个 3为一行两个 4一行三个 5为左右滑动
				displayStyle: 1, //1为常规 2为边框 3为居中显示 4为边框居中
				productData: [],
				textColor:''
			}
		},
		components:{
			commodity
		},
		onLoad() {
			this.textColor = this.globalSet('textCol');
			this.getRecommend();
		},
		methods: {
			getRecommend() { //获取推荐商品
				this.loading = true;
				this.$http.request({
					url: this.$api.goods.recommend,
					data: {
						page: 1,
						type:'order_comment'
					}
				}).then((res) => {
					this.loading = false;
					if (res.code == 0) {
						this.productData = res.data.list;
					}
				})
			},
			toIndex(){
				let pages = getCurrentPages();
				// #ifdef MP 
				if(pages.length > 0){
					let currPage = pages[pages.length - 1].$vm;
					let prevPage = pages[pages.length - 2].$vm; //上一个页面
				}
				// #endif
				// #ifdef H5
				let currPage = pages[pages.length - 1];
				let prevPage = pages[pages.length - 2]; //上一个页面
				// #endif
				// #ifdef H5||MP
				if(!!prevPage){	//如果能获取到上一页的路由
					console.log(currPage.route);
					console.log(prevPage.route);
					uni.redirectTo({
						url: '/'+prevPage.route
					})
				}else{
					uni.redirectTo({
						url: '/pages/index/index'
					})
				}
				// #endif
				// #ifdef APP-PLUS
					uni.redirectTo({
						url: '../list'
					})
				// #endif	
			},
			
		}
	}
</script>

<style lang="scss" scoped>
	.app{
		background-color: #F7F7F7;
		height: 100%;
		
		.container{
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			font-size: 9pt;
			padding: 140rpx 30rpx 0;
			
			.img-msg{
				
				margin-bottom: 50rpx;
				.img{
					width: 187rpx;
					height: 187rpx;
					margin-bottom: 35rpx;
				}
				
				.msg{
					color: #808080;
				}
			}
			
			.btn{
				width: 100%;
				height:90rpx;
				background-color: #BC0100;
				color: #FFFFFF;
				text-align: center;
				line-height: 90rpx;
				border-radius:45rpx;
			}
		}
	}
	/* 猜你喜欢 */
	.guessLike {
		text-align: center;
		font-weight: 700;
		font-size: 12pt;
		color: #000000;
		padding: 50rpx 0;
	}
</style>
