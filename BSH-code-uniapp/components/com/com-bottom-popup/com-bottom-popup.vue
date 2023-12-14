<template>
	<view @touchmove.stop.prevent>
		<view class="jx-popup-class jx-bottom-popup" :class="[show ? 'jx-popup-show' : '']" :style="{'z-index':z_index,background:bgcolor,'border-radius':borderRadius,height:height?height+'rpx':'auto'}">
			<slot></slot>
		</view>
		<view class="jx-popup-mask" :style="{'z-index':z_index2}" :class="[show?'jx-mask-show':'']" v-if="mask" @tap="handleClose"></view>
	</view>
</template>

<script>
	export default {
		name: "jxBottomPopup",
		props: {
			//是否需要mask
			mask: {
				type: Boolean,
				default: true
			},
			//控制显示
			show: {
				type: Boolean,
				default: false
			},
			//背景颜色
			bgcolor: {
				type: String,
				default: "#fff"
			},
			//高度 rpx
			height: {
				type: Number,
				default: 0
			},
			borderRadius:{
				type:String,
				default:'26rpx 26rpx 0 0'
			},
			z_index:{
				type:Number,
				default:999
			},
			z_index2:{
				type:Number,
				default:998
			}
		},
		methods: {
			handleClose() {
				if (!this.show) {
					return;
				}
				this.$emit('close', {});
			}
		}
	}
</script>

<style>
	.jx-bottom-popup {
		width: 100%;
		position: fixed;
		left: 0;
		right: 0;
		bottom: 0;
		/* z-index: 999; */
		visibility: hidden;
		transform: translate3d(0, 100%, 0);
		transform-origin: center;
		transition: all 0.3s ease-in-out;
		min-height: 20rpx;
	}

	.jx-popup-show {
		transform: translate3d(0, 0, 0);
		visibility: visible;
	}

	.jx-popup-mask {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: rgba(0, 0, 0, 0.6);
		/* z-index: 998; */
		transition: all 0.3s ease-in-out;
		opacity: 0;
		visibility: hidden;
	}

	.jx-mask-show {
		opacity: 1;
		visibility: visible;
	}
</style>
