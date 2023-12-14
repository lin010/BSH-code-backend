<template>
	<view :style="cContainerStyle">
		<view class="banner-swiper" v-if="data.style == 1">
			<swiper autoplay="true" @change='change' class="banner-swiper-content" :style="{'height':px(data.height)}"
			:indicator-dots='false' :interval='3000' :duration='500' circular='true'>
				<swiper-item v-for="(item,index) in data.banners" @tap="navTo(item.url)" :key="index">
					<!-- :style="{'background':bannerData.fill ==1?item.background:''}" -->
					<!-- <image class="slid1_img" :class="'banner-img '+cBannerImgClass" :style="{'border-radius':data.fill ==1?'16rpx':''}"
					 :src="item.pic_url" :mode="data.fill ? 'aspectFill' : 'scaleToFill'"></image> -->
					<view class="slid1_img" :class="'banner-img '+cBannerImgClass" :style="'background-image: url('+item.picUrl+');'"></view>
				</swiper-item>
			</swiper>
			<view class="swiper-dot-box">
				<swiper-dot :length='data.banners.length' :swiperCurrent='current' :width='30' :height="2" radius="0"></swiper-dot>
			</view>
			<view v-if="data.fill ==1" class="background-box" :style="{'height':px(data.height-80),'background':data.fill ==1?backgroundCol:''}"></view>
		</view>
		
		<view v-if="data.style == 2">
			<swiper :class="data.fill ==1?'':'swiper-box'" :autoplay="true" @change='change' :indicator-dots="false" :interval="4000" 
			:duration="500" circular='true' :style="{'height':px(data.height)}" previous-margin="80rpx" next-margin="80rpx">
				<swiper-item v-for="(item,index) in data.banners" :key='index' @tap="navTo(item.url)">
					<view class="swiper-item" :class="{big:current == index}">
						<view class="slid1_img" :style="'background-image: url('+item.picUrl+');'"></view>
					</view>
				</swiper-item>
			</swiper>
			<view class="swiper-dot-box">
				<swiper-dot :length='data.banners.length' :swiperCurrent='current' :width="30" :height="2" radius="0"></swiper-dot>
			</view>
		</view>
		
		<!-- 3d效果 -->
		<!-- <view v-if="data.style == 2">
			<swiper  @change='change' :style="{'height':px(data.height)}" :class="data.fill == 1?'copyspace':''"
			 indicator-dots="true" :interval='2000' :duration='500' circular='true' previous-margin="50rpx" next-margin="50rpx">
				<swiper-item v-for="(item,index) in data.banners" class="banner_item" :key="index" :class="current == index && 'active'"> -->
					<!-- <image class="slid2_img" :class="'banner-img '+cBannerImgClass" :style="{'height':px(data.height)}" :src="item.picUrl"></image> -->
					<!-- <view class="slid2_img" :class="'banner-img '+cBannerImgClass" :style="'background-image: url('+item.picUrl+');'"></view>
				</swiper-item>
			</swiper>
		</view> -->

	</view>
</template>

<script>
	import swiperDot from "@/components/swiper-dot/swiper-dot.vue";
	export default {
		name: "banners",
		props: {
			bannerData: {
				type: Object,
				default: () => {}
			}
		},
		components:{
			swiperDot
		},
		data() {
			return {
				data: {},
				backgroundCol: '',
				current: 0
			}
		},
		created() {
			this.backgroundCol = this.bannerData.banners[0].pic_url;
			this.data = JSON.parse(JSON.stringify(this.bannerData))
		},
		computed: {
			cContainerStyle() {
				return `height:${this.data.height}rpx;`;
			},
			cBannerImgClass() {

				if (this.data.fill == 0) {
					return 'banner-img-contain';
				}
				if (this.data.fill == 1) {
					return 'banner-img-cover';
				}
			},
		},
		methods: {
			navTo(url,type) {
				if (url) {
					if (url.split('=')[0] == '/pages/web/web?url') {
						window.location.href = decodeURIComponent(url.split('=')[1]);
					}else if(url.split('=')[0] == 'tel?tel'){
						uni.makePhoneCall({
						    phoneNumber: url.split('=')[1], 
							success: (res)=>{
								// this.$http.toast('�Ǹ�');
							}
						});
					}else{
						uni.navigateTo({
							url
						})
					}
				}
			},
			px(num) {
				return uni.upx2px(num) + 'px';
			},
			change(e) {
				this.current = e.detail.current;
				var index = e.detail.current;
				this.backgroundCol = this.bannerData.banners[index].pic_url;
				this.$emit('change', this.backgroundCol);
			}
		},
	}
</script>

<style lang="scss" scoped>
	.banner-img-contain {
		background-size: contain;
	}

	.banner-img-cover {
		background-size: cover;
	}

	.banner-swiper {
		position: relative;
	}

	uni-swiper .uni-swiper-wrapper {
		border-radius: 16rpx;
	}

	.banner-swiper-content {
		position: relative;
		z-index: 8;
	}

	.background-box {
		position: absolute;
		width: 100%;
		top: 0px;
		// background: blue;
		z-index: 6;
		border-radius: 0px 0px 60rpx 60rpx;
	}

	.banner_item {
		padding: 0px 20rpx;
		box-sizing: border-box;

		&.active {

			.slid2_img {

				transform: scale(1);
			}
		}
	}

	.background {
		padding: 20rpx 30rpx;
		box-sizing: border-box;
	}

	.slid1_img {
		width: 100%;
		height: 100%;
		background-repeat: no-repeat;
		background-position: center;
		background-size: cover;
		background-color: #FFFFFF;
	}

	.slid2_img {
		width: 100%;
		height: 100%;
		// border-radius: 16rpx;
		background-repeat: no-repeat;
		background-position: center;

		transform: scale(0.6);
		transition: all 0.3s ease;
	}
	
	.swiper-box{
		padding: 40rpx 0;
	}
	.swiper-dot-box{
		position: absolute;
		bottom: 20rpx;
		left: 50%;
		transform: translate(-50%);
		z-index: 9;
	}
	.swiper-item{
		transform: scale(0.85);
		height: 100%;
		transition: all 0.5s;
		border-radius: 10rpx;
		overflow: hidden;
	}
	.big{
		transform: scale(0.95);
	}
</style>
