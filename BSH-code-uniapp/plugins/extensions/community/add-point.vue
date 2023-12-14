<template>
	<view class="app">
		<com-nav-bar :fixed="false" :border="false" color="#333333" background-color="#FFFFFF" @clickLeft="toStores" @clickRight="search">
			<block slot="left">
				<view class="city">
					<image src="../../images/extensions/community/position.png" class="icon"></image>
					<view class="uni-nav-bar-text">
						<text>{{ '附近门店' }}</text>
					</view>
				</view>
			</block>
			<view class="input-view">
				<com-icons class="input-uni-icon" type="search" size="22" color="#666666" />
				<input confirm-type="search" class="nav-bar-input" type="text" placeholder="搜索门店名称、编号" @confirm="confirm" />
			</view>
			<view slot="right">
				<text class="uni-nav-bar-right-text">搜索</text>
			</view>
		</com-nav-bar>
		<view class="content">
			<view class="hint">
				<p>更改您的提货门店:</p>
				<p>请属入你要搜索的门店名称、编号，或通过附近门店查找！</p>
			</view>
		</view>
	</view>
</template>

<script>
export default {
	data() {
		return {};
	},
	methods: {
		toStores(){
			uni.navigateTo({
				url:'/plugins/extensions/community/nearby-stores'
			})
		},
		search(){
			console.log("搜索");
		},
		confirm() {
			uni.showToast({
				title: '搜索'
			});
		}
	}
};
</script>

<style lang="scss" scoped>
.app {
	height: 100%;
	background-color: #ffffff;
}
.city {
	/* #ifndef APP-PLUS-NVUE */
	display: flex;
	/* #endif */
	flex-direction: column;
	align-items: center;
	color: #BC0100;
	// justify-content: flex-start;
	/* width: 160rpx;
 */
	margin-left: 4px;
	line-height: 48rpx;
	
	.icon{
		width: 36rpx;
		height: 32rpx;
	}
	
	.uni-nav-bar-text{
		font-size: 9pt;
		height: 44rpx;
		transform: scale(0.9);
	}
}
.input-view {
	/* #ifndef APP-PLUS-NVUE */
	display: flex;
	/* #endif */
	flex-direction: row;
	/* width: 500rpx;
 */
	flex: 1;
	background-color: #f8f8f8;
	height: 30px;
	border-radius: 15px;
	padding: 0 15px;
	flex-wrap: nowrap;
	margin: 7px 0;
	line-height: 30px;

	.input-uni-icon {
		line-height: 30px;
	}

	.nav-bar-input {
		height: 30px;
		line-height: 30px;
		/* #ifdef APP-PLUS-NVUE */
		width: 370rpx;
		/* #endif */
		padding: 0 5px;
		font-size: 28rpx;
		background-color: #f8f8f8;
	}
}
.content{
	padding: 20rpx;
	.hint{
		color: #999999;
		font-size:9pt;
		line-height:48rpx;
	}
}
.uni-nav-bar-right-text{
	color: #999999;
	font-size: 28rpx;
}
</style>
