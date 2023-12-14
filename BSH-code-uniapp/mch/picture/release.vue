<template>
	<view>
		<view class="status-bar"></view>
		
		<view class="nav">
			<view class="nav-back iconfont icon-fanhui" @tap="back"></view>
			<view class="nav-userinfo" @tap="navTo('avatar')">
				<image class="nav-avatar" :src="img_url+'ceshi/ceshi.png'" mode=""></image>
				<view>系七七咩</view>
			</view>
		</view>
		
		<view>
			<swiper class="swiper" :style="{height:swiperHeight+'px'}"
			circular="true" :interval="3000" :duration="1000" @change='swiperChange'>
				<swiper-item v-for="(item,index) in swiperList" :key='index'>
					<view class="swiper-item">
						<image class="swiper-img" :src="item" mode="widthFix"></image>
					</view>
				</swiper-item>
			</swiper>
			
			<swiper-dot :length='swiperList.length' :swiperCurrent='swiperCurrent'></swiper-dot>
		</view>
		
		<view class="content-box">
			<view class="related-pro-box" @tap="navTo">
				<view class="related-pro-box-flex">
					<view class="related-pro-icon-box">
						<view class="related-pro-icon iconfont icon-gouwuche"></view>
					</view>
					<view class="related-pro-name">英伦风复古连衣裙</view>
				</view>
			</view>
			<view class="label">
				<view class="label-title">个性标签</view>
				<view class="label-item-box">
					<view class="related-pro-box label-pro-box" v-for="item in 3">
						<view class="related-pro-box-flex">
							<view class="related-pro-icon-box label-pro-icon-box">
								<view class="related-pro-icon label-pro-icon">#</view>
							</view>
							<view class="related-pro-name label-pro-name">英伦风复古连衣裙</view>
						</view>
					</view>
				</view>
			</view>
			<view class="text">
				<view class="text-title">深圳玩乐好去处  无门槛卡丁车极速体验</view>
				<view class="text-content">
					工作学习压力一大，就想进行一些竞速运动。从小爱玩跑跑卡丁车的我，当然不能放过线下体验当一个真车手！位于南山区的K1speed卡
				</view>
				<view class="text-time">
					编辑于 04-15
				</view>
			</view>
		</view>
		
		<view class="line"></view>
		
		<view class="common-box">
			<view class="common-release-box">
				<view class="common-total">共153条评论</view>
				<view class="common-release">
					<image class="common-avatar" :src="img_url+'ceshi/ceshi.png'" mode=""></image>
					<view class="common-input">
						<input type="text" value="" placeholder="说点什么吧，让TA也能看看你的足迹" placeholder-style="color:#808080;font-size:11pt;"/>
					</view>
				</view>
			</view>
			
			<view class="common-list-box">
				<view class="common-list-item" v-for="item in 2">
					<image class="user-avatar" :src="img_url+'ceshi/ceshi2.jpg'" mode=""></image>
					<view class="common-content">
						<view class="common-username">李溪芮是值得期待的</view>
						<view class="common">我一直被你的头发吸引了哈哈哈哈哈啊哈大声说拜拜 <text class="common-time">4-20</text></view>
						<!-- 回复的评论 -->
						<view class="reply" v-for="item in 2">
							<image class="reply-img" :src="img_url+'ceshi/ceshi1.jpg'" mode=""></image>
							<view class="reply-right">
								<view class="common-username">花心女人最好命</view>
								<view class="common">发尾的头发真的太调皮了！哈哈哈哈哈哈哈啊哈哈哈哈哈<text class="common-time">4-20</text></view>
							</view>
						</view>
						
						<view class="common-more" @tap="unfold">展开5条回复</view>
					</view>
					<view class="common-like">
						<view class="common-like-icon icon-shoucang1 iconfont"></view>
						<!-- <view class="common-like-icon-active icon-shoucang iconfont"></view> -->
						<view>1.5w</view>
					</view>
				</view>
			</view>
		</view>
		
		
		<br><br>
	</view>
</template>

<script>
	import swiperDot from "@/components/swiper-dot/swiper-dot.vue";
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				swiperHeight:0, //轮播图高度，随图片自适应
				swiperCurrent:0, //轮播图当前索引
				swiperList:[
					this.$api.img_url+'ceshi/ceshi.png',
					this.$api.img_url+'ceshi/ceshi.png',
					this.$api.img_url+'ceshi/ceshi.png',
				]
			}
		},
		components:{
			swiperDot
		},
		onReady() {
			this.getSwiperHeight();
		},
		methods: {
			swiperChange(e){ //获取当前swiper的index
				this.swiperCurrent = e.detail.current;
			},
			getSwiperHeight(){ //根据图片高度来给swiper设置高度
				var that = this;
				setTimeout(()=>{
					let info = uni.createSelectorQuery().select(".swiper-img");
					console.log(info,'info');
					info.boundingClientRect(function(data) {
						console.log(data.height,'height');
						that.swiperHeight = data.height;
					}).exec()
				},700)
			},
			navTo(name){
				if(name == 'avatar'){
					uni.navigateTo({
						url:'/pages/picture/personal/personal'
					})
				}
			},
			unfold(){ //展开评论
				this.$http.toast('展开评论')
			},
			back(){ //返回上一级
				this.navBack();
			}
		}
	}
</script>

<style scoped>
	page,body{
		background: #FFFFFF;
	}
	.status-bar{
		height: var(--status-bar-height);
	}
	/* 导航栏 */
	.nav{
		background: #FFFFFF;
		display: flex;
		align-items: center;
		padding: 10rpx 20rpx;
	}
	.nav-back{
		font-size: 40rpx;
		margin-right: 20rpx;
	}
	.nav-userinfo{
		display: flex;
		align-items: center;
		font-weight: 600;
		color: #000000;
		font-size: 11pt;
	}
	.nav-avatar{
		width: 75rpx;
		height: 75rpx;
		border-radius: 50%;
		margin-right: 20rpx;
	}
	
	/* 轮播图 */
	.swiper{
		width: 100%;
	}
	.swiper-img{
		width: 100%;
		display: block;
	}
	.dot-box{
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.dot-item{
		width: 10rpx;
		height: 10rpx;
		border-radius: 50%;
		background: #C8C8C8;
		margin-right: 6rpx;
		margin-top: 15rpx;
	}
	.dot-item-active{
		background: #BC0100;
	}
	/* 内容 */
	.content-box{
		padding: 65rpx 30rpx 0;
	}
	.related-pro-box{
		background: #E5E5E5;
		border-radius: 30rpx;
		display: inline-block;
	}
	.related-pro-box-flex{
		display: flex;
		align-items: center;
		padding: 6rpx 24rpx;
	}
	.related-pro-icon-box{
		border-radius: 50%;
		background: #FFA312;
		width: 30rpx;
		height: 30rpx;
		position: relative;
		margin-right: 10rpx;
	}
	.related-pro-icon{
		color: #FFFFFF;
		font-size: 20rpx;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%,-50%) scale(0.85);
	}
	.related-pro-name{
		font-size: 10pt;
		color: #FFFFFF;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		max-width: 600rpx;
	}
	
	.label-item-box{
		display: flex;
		flex-wrap: wrap;
	}
	.label-pro-box{
		margin: 0 8rpx 20rpx 0;
	}
	.label-title{
		font-weight: 700;
		font-size: 10pt;
		color: #000000;
		padding: 40rpx 0 24rpx;
	}
	.label-pro-icon-box{
		background: #5795E2;
	}
	.label-pro-icon{
		font-size: 30rpx;
		left: 52%;
	}
	.label-pro-name{
		color: #5795E2;
	}
	
	.text-title{
		font-weight: 700;
		margin-top: 70rpx;
		color: #000000;
		font-size: 14pt;
		margin-bottom: 20rpx;
	}
	.text-content{
		font-size: 11pt;
		line-height: 60rpx;
	}
	.text-time{
		color: #999999;
		font-size: 9pt;
		margin-top: 20rpx;
	}
	
	.line{
		width: 100%;
		border-bottom: 2rpx solid #F2F2F2;
		margin: 50rpx 30rpx;
	}
	
	/* 评论 */
	.common-release-box{
		padding: 0 30rpx 20rpx;
		border-bottom: 2rpx solid #F2F2F2;
	}
	.common-total{
		font-size: 10pt;
		font-weight: 700;
		color: #000000;
		letter-spacing: 2rpx;
		margin-bottom: 30rpx;
	}
	.common-release{
		display: flex;
		align-items: center;
	}
	.common-avatar{
		width: 66rpx;
		height: 66rpx;
		border-radius: 50%;
		margin-right: 20rpx;
	}
	.common-input{
		background: #F2F2F2;
		border-radius: 50rpx;
		flex: 1;
		padding: 10rpx 30rpx;
	}
	.common-list-item{
		display: flex;
		justify-content: space-between;
		padding: 30rpx;
		border-bottom: 2rpx solid #F2F2F2;
	}
	.user-avatar{
		width: 68rpx;
		height: 68rpx;
		border-radius: 50%;
		margin-right: 20rpx;
	}
	.common-content{
		flex: 1;
	}
	.common-username{
		color: #999999;
		font-size: 10pt;
		margin-bottom: 4rpx;
	}
	.common{
		font-size: 10pt;
		color: #000000;
		letter-spacing: 2rpx;
	}
	.reply{
		padding: 30rpx 0 0;
		display: flex;
	}
	.reply-img{
		width: 40rpx;
		height: 40rpx;
		border-radius: 50%;
		margin-right: 16rpx;
	}
	.reply-right{
		flex: 1;
	}
	.reply-username{
		font-size: 10pt;
		color: #999999;
	}
	.common-time{
		margin-left: 36rpx;
		color: #999999;
		font-size: 9pt;
		font-weight: 500;
	}
	.common-more{
		color: #BC0100;
		font-size: 10pt;
		letter-spacing: 6rpx;
		font-weight: 600;
		margin-top: 30rpx;
		padding-left: 60rpx;
	}
	.common-like{
		font-size: 9pt;
		display: flex;
		flex-direction: column;
		align-items: center;
		margin-left: 8rpx;
	}
	.common-like-icon{
		font-size: 34rpx;
		margin-bottom: 2rpx;
	}
	.common-like-icon-active{
		color: #BC0100;
		font-size: 36rpx;
	}
</style>
