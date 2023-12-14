<template>
	<view style="padding: 8rpx 0">
		<view class="placeholderBox" :style="{'height':placeholderHeight+'rpx','background':placeholderBackground}"></view>
	</view>
	<!-- <view class="placeholderBox">123</view> -->
</template>

<script>
	export default{
		name:'placeholder',
		props:{
			placeholderHeight:{
				type:Number,
				default:20
			},
			placeholderBackground:{
				type:String,
				default:'transparent'
			}
		}
	}
</script>

<style>
	.placeholderBox{
		width: 100%;
		background: #bc0100;
	}
</style>
