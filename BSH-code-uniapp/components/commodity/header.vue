<template>
	<view class="header" :style="{'background':backgroundCol}">
		<view class="conter">
			<view class="logo">兰亭</view>
			<view class="bulletin">
				<view class="icon"></view>
				<swiper autoplay="true" vertical="true" circular="true" disable-touch="true" class="bulletin-text-swiper">
					<swiper-item v-for="(item,i) in texts" :key="i" class="item" catchtouchmove="catchTouchMove">
						<view class="text">{{item.text}}</view>
					</swiper-item>
				</swiper>
			</view>
			<view class="msgs"></view>
		</view>
	</view>
</template>

<script>
	export default {
		name: 'diyHeader',
		props: {
			textList: {
				type: Array,
				default: () => []
			},
			backgroundCol:{
				type: String,
				default: '#ffffff'
			}
		},
		data() {
			return {
				texts: []
			}
		},
		created() {
			this.texts = [{
					id: 1,
					text: "嗨，下午好。"
				},
				{
					id: 2,
					text: "加油，努力"
				},
				{
					id: 3,
					text: "当自强"
				},
			]
		},
		methods: {
			catchTouchMove(){
				return false;
			}
		}
	}
</script>

<style lang="scss" scoped>
	.header {

		height: 80rpx;

		.conter {

			height: 100%;
			display: flex;
			align-items: center;

			.logo {
				flex-basis: 100rpx;
				text-align: center;
				line-height: 50rpx;
			}

			.bulletin {
				flex: 1;
				display: flex;
				align-items: center;
				font-size: 9pt;
				height: 50rpx;
				border-radius: 28rpx;
				background-color: #F0F0F3;

				.icon {
					margin-left: 10rpx;
				}

				.bulletin-text-swiper {

					flex: 1;
					height: 100%;

					.item {
						display: flex;
						align-items: center;
						.text {
							margin-left: 10rpx;
							font-size: 9pt;
						}
					}
				}
			}

			.msgs {

				flex-basis: 60rpx;
			}
		}
	}
</style>
