<template>
	<view class="app">
		<image src="https://pic.downk.cc/item/5eccd9e9c2a9a83be50140b6.png" mode="aspectFill" class="jx-bg"></image>
		<view class="container">
			<view class="video-list">
				<view class="video-box" v-for="(item, i) in 4" :key="i">
					<view class="box-head flex-y-center">
						<image :src="url" class="user-avatar"></image>
						<view class="user-nickname">{{ '七件事社交电商' }}</view>
					</view>
					<view class="box-body">
						<view class="video"><diy-video :value="video" diy-style="height: 372rpx;"></diy-video></view>
					</view>
					<view class="box-tail">
						<view class="flex-y-center" style="margin-bottom: 30rpx;">
							<jx-tag class="tag" size="" type="red">NEW</jx-tag>
							<view class="video-name ellipsis">{{'论拿铁拉花的正确制作方式！'}}</view>
						</view>
						<view class="flex-x-bottom" style="line-height: 36rpx;">
							<view class="flex-y-center" :class="((i+1) % 2) == 0 ? '' : 'active'" style="margin-right: 30rpx;">
								<view v-if="((i+1) % 2) == 0" class="iconfont icon-shoucang1"></view>
								<view v-else class="iconfont icon-shoucang"></view>
								<view class="icon-text">{{'25.6w'}}</view>
							</view>
							<view class="flex-y-center" @click="videoShare()">
								<view class="iconfont icon-fenxiangcopy"></view>
								<view class="icon-text">分享</view>
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>
		<main-tabbar></main-tabbar>
	</view>
</template>

<script>
export default {
	components: {
		jxTag: () => import('@/components/tag/tag.vue')
	},
	data() {
		return {
			url: 'http://jxmall.sinbel.top/web//uploads/images/original/20200609/67bf8d50edd6168e5cf96f02cff6c93e.png',
			video: {
				pic_url: 'http://jxmall.sinbel.top/web//uploads/images/original/20200604/59330a0fbec8f577f67a08e67c9ecdae.jpg',
				url: 'http://jxmall.sinbel.top/web//uploads/video/original/20200610/0c870cfdead148f488c92017f4816715.mp4'
			}
		};
	},
	onLoad() {
		
	},
	methods:{
		videoShare(){
			console.log("点击分享");
		}
	}
};
</script>

<style lang="scss" scoped>
.app {
	height: 100%;
	background-color: #f8f6f9;
	position: relative;
}
.jx-bg {
	position: absolute;
	top: 0;
	width: 100%;
	height: 240rpx;
	// background:linear-gradient(145deg,rgba(188,1,0,1),rgba(230,20,19,1),rgba(228,127,83,1));
}
.container {
	color: #ffffff;
	position: relative;
	margin: 0 30rpx;

	.video-list {
		margin-top: 30rpx;
	}
	.video-box {
		height: 640rpx;
		background: #ffffff;
		border-radius: 15rpx;
		margin-bottom: 20rpx;
		padding: 20rpx;

		.box-head {
			margin-bottom: 12rpx;

			.user-avatar {
				width: 72rpx;
				height: 72rpx;
				border-radius: 50%;
				margin-right: 12rpx;
			}
			.user-nickname {
				color: #000000;
				font-size: 34rpx;
				line-height: 60rpx;
				font-weight: 400;
			}
		}

		.box-body {
			margin-bottom: 30rpx;
			.video {
				height: 372rpx;
				border-radius: 15rpx;
				overflow: hidden;
			}
		}
		
		.box-tail{
			color: #333333;
			font-size: 24rpx;
			.video-name{
				margin-left: 6rpx;
				font-size:32rpx;
				max-width: 496rpx;
				color: #010101;
			}
			.active{
				color: #BC0100;
			}
			.icon-text{
				margin-left: 6rpx;
				line-height: 42rpx;
			}
		}
		.tag {
			background: rgba(188, 1, 0, 0.15) !important;
			color: #bc0100;
			width: 80rpx;
			height: 40rpx;
			padding: 10rpx;
			font-size: 26rpx;
		}
	}
}
</style>
