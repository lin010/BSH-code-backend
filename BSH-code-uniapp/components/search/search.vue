<template>
	<view class="searchBox flex flex-y-center" :style="{'background': frameColor, 'padding' : padding ? padding : ''}">
		<view class="search" :style="{'background': innerFrameColor,'color':textColor,'border-radius':px(borderRadius),'border':'1px solid transparent','justify-content':textAlign,'padding' : innerPadding ? innerPadding : ''}" @click="openUrl('/pages/search/search')">
			<view class="icon-text">
				<view class="iconfont icon-search" style="margin-right: 10rpx;position: relative;top: 4rpx;"></view>
				{{message}}
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name: "search",
		props: {
			padding:{
				type: String,
				default: ''
			},
			innerPadding:{
				type: String,
				default: ''
			},
			message: {
				type: String,
				default: "搜索"
			},
			frameColor: {
				type: String,
				default: "#eeeeee"
			},
			innerFrameColor: {
				type: String,
				default: "#ffffff"
			},
			textColor: {
				type: String,
				default: "#eeeeee"
			},
			searchIcon: {
				type: String,
				default: "../../static/images/search/searchIcon.png"
			},
			borderRadius: {
				type: Number,
				default: 20
			},
			textAlign: {
				type: String,
				default: 'left'
			},
			is_fixed:{
				type:Number,
				default:0, //0不固定 1固定
			}
		},
		created() {
			//console.log(this.message,'messagemessage');
		},
		methods: {
			px(num) {
				return uni.upx2px(num) + 'px';
			},
			openUrl(url){
				uni.navigateTo({
					url
				})
			}
		}
	}
</script>

<style scoped>
	.searchBox {
		padding: 20rpx 20rpx 16rpx;
		height: 96rpx;
		box-sizing: border-box;
	}

	.search {
		width: 100%;
		padding: 8rpx 20rpx 8rpx 20rpx;
		display: flex;
		align-items: center;
		font-size: 30rpx;
		position: relative;
		box-sizing: border-box;
	}

	.icon-text {
		font-size: 10pt;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.searchIcon {
		width: 36rpx;
		height: 36rpx;
		margin-right: 16rpx;
	}

	.search-btn {
		color: #FFFFFF;
		position: absolute;
		right: -4rpx;
		border-radius: 60rpx;
		padding: 8rpx 28rpx;
	}
</style>
