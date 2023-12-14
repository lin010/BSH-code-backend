<template>
	<view class="app">
		<image :src="'http://d.zaix.ru/mJSh.png'" class="jx-bg"></image>
		<view class="container">
			<view class="title">
				<view class="headline">{{'兰亭社区团购'}}</view>
				<view class="subhead">{{'一切以为客户创造价值为目的'}}</view>
			</view>
			<view class="store flex-y-center">
				<image :src="store_avatar" class="store-avatar"></image>
				<view class="store-info flex-grow-1">
					<view class="store-name">{{'员村友客来便利店'}}</view>
					<view class="store-msg">{{'兴盛你我他，优选人人夸'}}</view>
				</view>
				<view class="store-hot flex-y-center">
					<view class="fans flex-col flex-y-center">
						<view class="num">{{'76'}}</view>
						<view class="text">{{'粉丝数'}}</view>
					</view>
					<view class="exponent flex-col flex-y-center">
						<view class="num">{{'6196'}}</view>
						<view class="text">{{'购买指数'}}</view>
					</view>
				</view>
			</view>
			<view class="search">
				<search
					:message="'大家都在搜“王者荣耀云限定”'"
					:text-align="'center'"
					frame-color="#FFFFFF"
					text-color="#999999"
					:border-radius="30"
					padding="0"
					innerPadding="0"
					style="height: 60rpx;"
				>
				</search>
			</view>
			<view class="advertising">
				<image class="advertising-img" src="http://d.zaix.ru/mJZw.png"></image>
			</view>
			<view class="goods-list">
				<view class="goods-item" v-for="(item,i) in 3" :key="i">
					<view class="goods-supplier">{{'本商品由华氏严选专供'}}</view>
					<view class="goods-cover">
						<image :src="store_avatar" class="goods-img"></image>
						<view class="label flex flex-y-center">
							<view class="text">{{'核桃 松软 早餐优选'}}</view>
						</view>
					</view>
					<view class="goods-introduce flex">
						<view class="flex-grow-1">
							<view class="goods-name">{{ '广州酒家利口福核桃包' }}</view>
						</view>
						<view class="goods-popularity flex flex-grow-0">
							<view class="line"></view>
							<view class="num-text flex-col flex-y-center">
								<view class="num">5.4万</view>
								<view class="text">人气值</view>
							</view>
						</view>
					</view>
					
					<view class="time-msg flex-x-between">
						<view class="time">
							<view>预售时间：{{ '07月30日' }}</view>
							<view>提货时间：{{ '07月31日' }}</view>
						</view>
						<view class="flex-col">
							<view>限量{{ '10500' }}份</view>
							<view class="onsale-item flex" v-if="0 == 0">
								<view>今日已售</view>
								<view class="num">{{ '2390' }}</view>
								<view>份</view>
							</view>
							<view class="onsale-item flex" v-else>
								<view>{{'7月30日'}}</view>
								<view>{{'0'}}点开抢</view>
							</view>
						</view>
					</view>
					<view class="goods-price flex-x-between flex-y-center">
						<view class="flex">
							<view class="dot">¥</view>
							<view class="current-price">{{ '7.99' }}</view>
							<view class="original-price">¥{{ '12.5' }}</view>
						</view>
						<view class="btn join flex-x-center flex-y-center" v-if="0 == 0">
							<view class="text">加入购物车</view>
						</view>
						<view class="btn doom flex-col flex-x-center flex-y-center" v-else>
							<view class="text">提前加入购物车</view>
							<view class="num">9396人想购买</view>
						</view>
					</view>
					<!-- 在售时显示 -->
					<view class="user-record flex-x-between flex-y-center" v-if="0 == 0">
						<view class="flex">
							<view class="user-avatar" v-for="(item,i) in 5">
								<image :src="store_avatar"class="user-avatar-img"></image>
							</view>
						</view>
						<view class="iconfont icon-xiala"></view>
					</view>
					<!-- 在售时显示 -->
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		components:{
			search: () => import('@/components/search/search.vue')
		},
		data() {
			return {
				store_avatar: 'http://jxmall.sinbel.top/web//uploads/images/original/20200730/b565d9a2635e48ddf47200f67602c3cb.jpg',
			};
		}
	}
</script>

<style lang="scss" scoped>
.app{
	height: 100%;
	background-color: #F7F7F7;
	position: relative;
}
.jx-bg {
	position: absolute;
	top: 0;
	width: 100%;
	height: 300rpx;
	background-size: 100%;
	background-repeat: no-repeat;
}
.container{
	position: relative;
	padding: 0 20rpx;
	
	.title{
		color: #FFFFFF;
		padding-top: 24rpx;
		margin-bottom: 32rpx;
		
		.headline{
			font-size: 46rpx;
			font-weight: normal;
			line-height: 60rpx;
		}
		.subhead{
			font-weight: 400;
			font-size: 25rpx;
			line-height: 66rpx;
		}
	}
	
	.store{
		background-color: #FFFFFF;
		height: 167rpx;
		border-radius:7rpx;
		padding: 28rpx 20rpx;
		
		.store-avatar{
			width:100rpx;
			height:110rpx;
			border-radius:5rpx;
			margin-right: 14rpx;
		}
		
		.store-info{
			line-height: 48rpx;
			.store-name{
				color: #000000;
				font-size:30rpx;
				font-weight: 400;
			}
			.store-msg{
				font-size:25rpx;
				font-weight:400;
				color: #A5A5A5;
			}
		}
		
		.store-hot{
			height: 100%;
			border-left: 1rpx solid #E6E6E6;
			
			.fans{
				margin: 0 16rpx 0 26rpx;
			}
			.num{
				color: $jx-color-primary;
				font-size:30rpx;
				font-weight:bold;
			}
			.text{
				color: #A5A5A5;
				font-size:25rpx;
				font-weight:400;
			}
		}
	}

	.search{
		overflow: hidden;
		margin: 32rpx 0;
		background-color: #FFFFFF;
		height: 60rpx;
		border: 1rpx solid #E6E6E6;
		border-radius: 30rpx;
	}
	
	.advertising{
		height: 207rpx;
		margin-bottom: 60rpx;
		
		.advertising-img{
			width: 100%;
			height: 100%;
			background-size: 100%;
		}
	}
	
	.goods-list{
		margin-bottom: 80rpx;
		.goods-item{
			padding: 20rpx 20rpx 0;
			background-color: #FFFFFF;
			border-radius: 10rpx;
			margin-bottom: 20rpx;
		}
		
		.goods-supplier{
			color: #3D3D3D;
			font-size: 26rpx;
			margin-bottom: 14rpx;
			text-align: center;
		}
		
		.goods-cover{
			width: 100%;
			height: 460rpx;
			position: relative;
			.goods-img{
				width: 100%;
				height: 100%;
				background-size: 100%;
			}
			
			.label{
				position: absolute;
				left: 0;
				bottom: 0;
				width:344rpx;
				height:60rpx;
				background:rgba(0,0,0,0.3);
				border-radius: 0 50rpx 0 0;
				color: #FFFFFF;
				.text{
					margin-left: 10rpx;
					font-size: 24rpx;
				}
			}
		}
		
		.goods-introduce {
			padding: 30rpx 0;
			line-height: 60rpx;
			border-bottom: 1rpx solid #f2f2f2;
		}
		
		.goods-name {
			font-size: 35rpx;
			color: #000000;
			font-weight: 600;
		}
		
		.goods-popularity {
			.line {
				width: 2rpx;
				height: 90rpx;
				background-color: #e6e6e6;
			}
		
			.num-text {
				margin-left: 36rpx;
				margin-right: 10rpx;
				line-height: 44rpx;
				.num {
					color: #bc0100;
					font-size: 32rpx;
				}
				.text {
					color: #a5a5a5;
					font-size: 26rpx;
				}
			}
		}
		
		.time-msg {
			padding: 28rpx 0;
			color: #bc0100;
			font-size: 26rpx;
			line-height: 48rpx;
			
			.flex-col{
				align-items: flex-end;
			}
			.onsale-item {
				color: #000000;
				font-size: 27rpx;
			}
		}
		
		.goods-price{
			margin-bottom: 30rpx;
			.flex {
				align-items: baseline;
				color: #BC0100;
			}
			.dot {
				font-size: 30rpx;
			}
			.current-price {
				font-size: 60rpx;
				margin-right: 30rpx;
			}
			.original-price {
				color: #8D8D8D;
				font-size: 24rpx;
				text-decoration: line-through;
			}
			
			.btn{
				width: 290rpx;
				height: 84rpx;
				border-radius: 42rpx;
				color: #FFFFFF;
				font-size: 28rpx;
				
				&.join{
					background-color: #BC0100;
				}
				
				&.doom{
					background-color: #0D9DFF;
				}
				
				.num{
					color: #0D9DFF;
					font-size: 21rpx;
					width: 170rpx;
					height: 28rpx;
					line-height: 28rpx;
					background-color: #89C9FF;
					border-radius: 14rpx;
					text-align: center;
				}
			}
		}
		
		.user-record{
			padding: 20rpx 0;
			background-color: #FFFFFF;
			border-top: 1rpx solid #F2F2F2;
			
			.user-avatar{
				width:43rpx;
				height:43rpx;
				border-radius:50%;
				overflow: hidden;
				margin-left: -5rpx;
				
				.user-avatar-img{
					width: 100%;
					height: 100%;
					background-size: cover;
					background-repeat: no-repeat;
				}
			}
			.iconfont{
				font-size: 36rpx;
				line-height: 25rpx;
			}
		}
	}
}
</style>
