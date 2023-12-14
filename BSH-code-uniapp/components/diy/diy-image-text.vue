<template>
	<view class="diy-image-text">
		<view v-if="data.content" v-html="data.content"></view>
		<view v-else flex="main:center" style="line-height: 500rpx;color: rgb(173, 177, 184);">图文详情</view>
	</view>
</template>

<script>
	export default {
		props: {
			value: Object,
		},
		data() {
			return {
				data: {}
			}
		},
		created() {
			this.data = JSON.parse(JSON.stringify(this.value));
		},
		methods: {

		}
	}
</script>

<style lang="scss" scoped>
	.diy-image-text {
		width: 100%;
		height: 500rpx;
		overflow: hidden;
		overflow-y: auto;
		font-size: 11pt;
		color: #000000;
	}
</style>
