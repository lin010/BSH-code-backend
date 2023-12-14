<template>
	<view>
		<image class="cover-pic" :src="img_url+'ceshi/ceshi.png'" mode="widthFix"></image>
		
		<view class="la-content">
			<!-- 关联商品 -->
			<view class="la-content-item">
				<view class="up" @tap="navTo('goods')">
					<view class="la-content-item-left">
						<view class="left-icon">
							<view class="pro-icon iconfont icon-gouwuche"></view>
						</view>
						<view>关联商品</view>
					</view>
					<view class="la-content-item-right iconfont icon-xiala"></view>
				</view>
				<view class="label">
					<view class="label-child">
						<view class="label-icon-box">
							<view class="label-icon iconfont icon-gouwuche"></view>
						</view>
						<view class="label-text">英伦风复古连衣裙</view>
					</view>
					<view class="close-icon iconfont icon-guanbi1" @tap.stop="deleteLabel"></view>
				</view>
			</view>
			<!-- 添加标签 -->
			<view class="la-content-item">
				<view class="up">
					<view class="la-content-item-left">
						<view class="left-icon">
							<view class="pro-icon iconfont icon-biaoqian"></view>
						</view>
						<view>添加标签</view>
					</view>
					<view class="la-content-item-right iconfont icon-xiala"></view>
				</view>
				<view class="label" v-for="item in 3">
					<view class="label-child">
						<view class="label-icon-box">
							<view class="label-icon iconfont icon-biaoqian"></view>
						</view>
						<view class="label-text">英伦风复古连衣裙</view>
					</view>
					<view class="close-icon iconfont icon-guanbi1" @tap.stop="deleteLabel"></view>
				</view>
			</view>
			<!-- 关联门店 -->
			<view class="la-content-item" @tap="navTo('store')">
				<view class="up">
					<view class="la-content-item-left">
						<view class="left-icon">
							<view class="pro-icon iconfont icon-dianpu"></view>
						</view>
						<view>关联门店</view>
					</view>
					<view class="la-content-item-right iconfont icon-xiala"></view>
				</view>
				<view class="label">
					<view class="label-child">
						<view class="label-icon-box">
							<view class="label-icon iconfont icon-dianpu"></view>
						</view>
						<view class="label-text">英伦风复古连衣裙</view>
					</view>
					<view class="close-icon iconfont icon-guanbi1" @tap.stop="deleteLabel"></view>
				</view>
			</view>
			<!-- 添加地址 -->
			<view class="la-content-item" @tap="navTo('address')">
				<view class="up">
					<view class="la-content-item-left">
						<view class="left-icon">
							<view class="pro-icon iconfont icon-dizhi"></view>
						</view>
						<view>添加地址</view>
					</view>
					<view class="la-content-item-right iconfont icon-xiala"></view>
				</view>
				<view class="label">
					<view class="label-child">
						<view class="label-icon-box">
							<view class="label-icon iconfont icon-dizhi"></view>
						</view>
						<view class="label-text">英伦风复古连衣裙</view>
					</view>
					<view class="close-icon iconfont icon-guanbi1" @tap.stop="deleteLabel"></view>
				</view>
			</view>
			
			<view class="nextStep" @tap="navTo">下一步</view>
		</view>
		
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				// list:[
				// 	{
				// 		icon:'icon-gouwuche',
				// 		name:'关联商品'
				// 	},
				// 	{
				// 		icon:'icon-biaoqian',
				// 		name:'添加标签'
				// 	},
				// 	{
				// 		icon:'icon-dianpu',
				// 		name:'关联门店'
				// 	},
				// 	{
				// 		icon:'icon-shouhuodizhi',
				// 		name:'添加地址'
				// 	}
				// ]
			}
		},
		onLoad() {
			this.$http.toast('关联商品、门店、地址，只可三选一!')
		},
		methods: {
			deleteLabel(){ //删除标签
				this.$http.toast('删除标签')
			},
			navTo(name){
				switch(name) {
					case 'goods':
						this.navigateTo('/pages/picture/link/goods');
						break;
					case 'store':
						this.navigateTo('/pages/picture/link/store');
						break;
					case 'address':
						this.navigateTo('/pages/picture/link/address');
						break;
					default:
						this.navigateTo('/pages/picture/contenPub');
				}
			},
			navigateTo(url){
				uni.navigateTo({
					url
				})
			}
		}
	}
</script>

<style scoped>
	.cover-pic{
		width: 100%;
		display: block;
	}
	.la-content{
		background: #FFFFFF;
		padding: 0 30rpx;
	}
	.la-content-item{
		padding: 30rpx 0;
		border-bottom: 2rpx solid #F2F2F2;
	}
	.la-content-item-left{
		display: flex;
		align-items: center;
		font-weight: 700;
		color: #000000;
	}
	.up{
		display: flex;
		align-items: center;
		justify-content: space-between;
	}
	.left-icon{
		width: 58rpx;
		height: 58rpx;
		background: #BC0100;
		border-radius: 50%;
		text-align: center;
		line-height: 62rpx;
		color: #FFFFFF;
		margin-right: 10rpx;
		font-weight: 500;
	}
	.pro-icon{
		font-size: 30rpx;
	}
	.la-content-item-right{
		font-size: 50rpx;
		line-height: 50rpx;
		color: #AAAAAA;
	}
	.nextStep{
		background: #BC0100;
		padding: 20rpx 0;
		width: 690rpx;
		border-radius: 60rpx;
		margin: 65rpx 0;
		color: #FFFFFF;
		text-align: center;
		font-size: 11pt;
	}
	.label{
		display: inline-block;
		position: relative;
		margin-right: 16rpx;
	}
	.label-child{
		background: #F2F2F2;
		border-radius: 25rpx;
		color: #5795E2;
		font-size: 11pt;
		padding: 4rpx 20rpx;
		margin-top: 20rpx;
		display: flex;
		align-items: center;
		letter-spacing: 2rpx;
		max-width: 690rpx;
	}
	.label-icon-box{
		border-radius: 50%;
		width: 30rpx;
		height: 30rpx;
		background: #5795E2;
		text-align: center;
		line-height: 32rpx;
		margin-right: 6rpx;
		position: relative;
	}
	.label-icon{
		font-size: 20rpx;
		color: #FFFFFF;
		position: absolute;
		left: 50%;
		top: 50%;
		transform: translate(-50%,-50%) scale(0.85);
	}
	.label-text{
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
		max-width: 620rpx;
	}
	.close-icon{
		font-size: 30rpx;
		text-align: center;
		line-height: 32rpx;
		color: #CCCCCC;
		position: absolute;
		top: 4rpx;
		right: -14rpx;
	}
</style>
