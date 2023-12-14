<template>
	<view class="diy-link" :style="cStyle">
		<view :style="style" @click="openUrl(data.link.new_link_url)">
			<view class="title" :style="{marginLeft: data.textLeft + 'rpx'}">{{data.title}}</view>
			<view class="arrow iconfont icon-xiala" v-if="data.arrowsSwitch" style="width: 36rpx;line-height: 36rpx;"></view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			value: Object
		},
		data() {
			return {
				data: {}
			}
		},
		created() {
			this.data = JSON.parse(JSON.stringify(this.value));
		},
		computed: {
			cStyle() {
				return `color: ${this.data.color};` +
					`background: ${this.data.background};`
			},
			style() {
				if (this.data.picSwitch) {
					return `background-image: url('${this.data.picUrl}');` +
						`background-position: ${this.data.picLeft}rpx 0;`;
				} else {
					return ``;
				}
			}
		},
		methods:{
			openUrl(url){
				uni.navigateTo({
					url
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.diy-link {
		width: 100%;
		height: 72rpx;
		font-size: 11pt;
		display: flex;
		align-items: center;

		&>view {
			width: 100%;
			height: 36rpx;
			background-repeat: no-repeat;
			background-size: contain;
			
			display: flex;
			align-items: center;
		}

		.title {
			flex-grow: 1;
			overflow-x: hidden;
			white-space: nowrap;
		}

		.arrow {
			width: 12rpx;
			margin: 0 24rpx;
		}
	}
</style>
