<template>
	<view class="content">
		<button @click="tui">推流</button>
		<button @click="la">拉流</button>
		
		<main-tabbar></main-tabbar>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				tabBar: null, // 底部导航
				title: 'Hello',
			}
		},
		onLoad() {
		},
		methods: {
			tui(){
				uni.navigateTo({
					url:'./open-live-pusher'
				})
			},
			la(){
				uni.navigateTo({
					url:'./player'
				})
			}
		}
	}
</script>

<style>
	.content {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
	}

	.logo {
		height: 200rpx;
		width: 200rpx;
		margin-top: 200rpx;
		margin-left: auto;
		margin-right: auto;
		margin-bottom: 50rpx;
	}

	.text-area {
		display: flex;
		justify-content: center;
	}

	.title {
		font-size: 36rpx;
		color: #8f8f94;
	}
</style>