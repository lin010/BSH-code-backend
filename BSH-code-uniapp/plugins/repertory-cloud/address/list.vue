<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="发货地址" :status-bar="true" background-color="#BC0100" :border="false"
		 color="#ffffff"></com-nav-bar>
		<view class="container">
			<view class="list">

				<view class="item flex flex-x-between flex-y-center" v-for="(item,i) in 9" :key="i" @click="chooseAddress(item)">
					<view class="item-info">
						<view class="address-detail over1">{{'广东省广州市白云区江夏村松猫岭街6号'}}</view>
						<view class="user flex">
							<view class="user-name">{{'梁瑞文(女士)'}}</view>
							<view class="user-phone">{{'18666666891'}}</view>
						</view>
					</view>
					<view class="edit" @click.stop="edit('edit','1')">
						<!-- <image :src="`${$api.plugins_img_url}/images/repertory-cloud/edit.png`" class="img-edit"></image> -->
						<image src="../../images/repertory-cloud/edit.png" class="img-edit"></image>
					</view>
				</view>

			</view>
			<view class="fixed flex flex-x-center flex-y-center">
				<view class="btn add flex-grow-1" @click="edit('add')">添加收货人</view>
			</view>
		</view>

		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				manageType: '',
				loading: false,
				dataList: []
			};
		},
		onLoad(options) {
			let {
				type
			} = options;
			this.manageType = type + '';
		},
		methods: {
			chooseAddress(data) {
				if (this.manageType == 'click') {
					// 这里存入选择的地址回退上一页
					uni.setStorageSync("_temp_address", "111");
					uni.navigateBack();
					return;
				}
			},
			edit(type, id) {
				uni.navigateTo({
					url: `/plugins/repertory-cloud/address/edit?type=${type}&id=${id}`
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.app {
		line-height: 100%;
		color: #F7F7F7;
	}

	.container {
		position: relative;
		margin-top: 20rpx;
		background-color: #FFFFFF;
		height: 100%;

		.item {
			padding: 32rpx 30rpx 22rpx;
			border-bottom: 1rpx solid #F2F2F2;

			.item-info {
				color: #000000;
				font-size: 36rpx;
				line-height: 40rpx;

				.address-detail {
					margin-bottom: 26rpx;
					max-width: 570rpx;
				}

				.user {
					color: #999999;
					font-size: 28rpx;
				}

				.user-name {
					margin-right: 24rpx;
				}
			}

			.edit {
				width: 44rpx;
				height: 42rpx;
                   z-index: 3;
				.img-edit {
					width: 100%;
					height: 100%;
				}
			}
		}
	}

	.fixed {
		position: fixed;
		left: 0;
		right: 0;
		bottom: 0;
		height: 150rpx;
		padding: 0 29rpx;
		background-color: #FFFFFF;
		border-top: 1rpx solid #F2F2F2;
		z-index: 3;

		.btn {
			height: 90rpx;
			line-height: 90rpx;
			font-size: 26rpx;
			text-align: center;
			border-radius: 45rpx;

			&.add {
				background-color: $jx-color-primary;
			}
		}
	}
</style>
