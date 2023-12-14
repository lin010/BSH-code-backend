<template>
	<view class="scekill-slider">
		<view class="title-wrap">
			<view class="title-time">
				<view class="title">限时秒杀</view>
				<view class="curTime">{{showTime}}点场</view>
				<view class="time-countdown">
					<tui-countdown :time="countdown" bgcolor="#CB943B" :width="48" :height="36" bcolor="#fff" color="#fff" colonColor="#fff"
					 :colonSize="10" class="countdown"></tui-countdown>
				</view>
			</view>
			<view class="other">
				更多
				<view class="iconfont icon-xiala" style="line-height: 40rpx;"></view>
			</view>
		</view>
		<scroll-view class="scroll-view_H items" scroll-x="true">
			<view class="item" v-for="(good,good_index) in goods" :key="good_index">
				<view class="good-cover">
					<image :src="good.img" mode="scaleToFill" lazy-load="true"></image>
				</view>
				<view class="good-info">
					<view class="new-pic">
						<span class="dot">¥</span>
						<span class="cur-pic">{{good.curPrice}}</span>
						<span class="dot">起</span>
					</view>
					<view class="original-pic">
						<del>
							<span class="dot">¥</span>
							<span class="pic">{{good.price}}</span>
						</del>
					</view>
				</view>
			</view>
		</scroll-view>
	</view>
</template>

<script>
	import tuiCountdown from "@/components/countdown/countdown"
	import moment from 'moment';
	export default {
		name: 'seckillSlider',
		props: {
			goods: { // 商品列表
				type: Array,
				default: () => []
			},
			countdown: { // 剩余事件 秒
				type: Number,
				default: 2000
			},
			period: { // 当前秒杀时间段   毫秒 或者 秒   必须
				type: Number,
				default: 0
			}
		},
		components: {
			tuiCountdown
		},
		data() {
			return {
				showTime: 0
			}
		},
		mounted() {
			this.showTime = moment(this.period).format("HH:mm");
		},
		methods: {

		}
	}
</script>

<style lang="scss" scoped>
	.title-wrap {
		display: flex;
		align-items: center;
		padding: 10px;

		.title-time {
			display: flex;
			align-items: center;
			color: #000000;
			height: 48rpx;

			.title {

				font-size: 13pt;
				margin-right: 12rpx;
				font-weight: 500;
			}

			.curTime {
				font-size: 10pt;
				font-weight: 700;
				margin-right: 10rpx;
			}

			.time-countdown {}
		}

		.other {
			flex: 1;
			display: flex;
			justify-content: flex-end;
			align-items: center;
			color: #808080;
			font-size: 9pt;
		}
	}

	.scroll-view_H {
		white-space: nowrap;

		& ::-webkit-scrollbar {
			width: 0;
			height: 0;
			background-color: transparent;
		}
	}

	.items {
		padding: 0 20rpx;
		overflow: hidden;
		box-sizing: border-box;

		.item {
			display: inline-block;
			height: 300rpx;
			width: 200rpx;

			.good-cover {

				height: 200rpx;
				padding: 0 10rpx;

				image {
					width: 100%;
					height: 100%;
				}
			}

			.good-info {
				display: flex;
				align-items: center;
				justify-content: center;

				.dot {
					font-size: 24rpx;
				}

				.new-pic {
					color: #E93B3D;
					font-size: 32rpx;
					font-weight: 900;
				}

				.original-pic {
					color: #999;
					font-size: 9pt;
					margin-left: 10rpx;
					margin-top: 6rpx;
				}
			}
		}
	}
</style>
