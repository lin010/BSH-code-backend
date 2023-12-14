<template>
	<view class="app">
		<view class="current-locat font-size-14px flex-y-center">
			<view class="orientation flex-y-center flex-grow-1">
				<!-- <view class="icon iconfont icon-address"></view> -->
				<com-icons class="input-uni-icon" type="location-filled" size="18" color="#BC0100" />
				<view class="text">广州市白云区金岭南街19号</view>
			</view>
			<view class="refresh flex-y-center">
				<com-icons class="input-uni-icon" type="refreshempty" size="26" color="#BC0100" />
				<view class="text">重新定位</view>
			</view>
		</view>
		<view class="stores">
			<view class="stores-title font-size-14px flex-y-center">
				<view class="text flex-grow-1">附近门店:</view>
				<navigator url="./other-region">
					<view class="flex-y-center">
						<view>选择其他区域的门店</view>
						<view class="icon flex-y-center"><com-icons class="input-uni-icon" type="arrowright" size="18" color="#BC0100" /></view>
					</view>
				</navigator>
			</view>
			<view class="items">
				<view class="store-item" v-for="(item, i) in 4" :key="i" @click="checkedStore(i)">
					<view class="store-info flex">
						<image :src="url" class="store-img" mode="aspectFill"></image>
						<view class="store-detail">
							<view class="flex-y-center">
								<view class="store-name flex-grow-1">员村友客来便利店</view>
								<com-icons class="input-uni-icon" type="phone-filled" size="15" color="#BC0100" />
							</view>
							<view class="store-number">门店编号：440106100227</view>
							<view class="store-distance flex-y-center">
								<view class="flex-grow-1">
									<text class="store-num">77</text>
									<text>人购买过</text>
								</view>
								<view class="flex-grow-0">
									<text>距您</text>
									<text class="store-num">323m</text>
								</view>
							</view>
						</view>
					</view>
					<view class="store-addr-detail">地址：广东省广州市天河区广州市天河区员村友客来便利店</view>
				</view>
			</view>
		</view>
		<com-modal :show="true" :custom="true" v-if="!!clickStore" padding="0">
			<view class="modal">
				<view class="flex-col flex-y-center" style="padding:40rpx 54rpx 0">
					<view class="modal-title">温馨提示</view>
					<view class="modal-content">您确定将{{ clickStore.name }}设为新的自提点吗?</view>
				</view>
				<view class="btns flex">
					<view class="btn cancel flex-grow-1" @click="cancel()">取消</view>
					<view class="btn confirm flex-grow-1" @click="confirm()">确定</view>
				</view>
			</view>
		</com-modal>
	</view>
</template>

<script>
const url =
	'data:image/png;base64,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';
export default {
	data() {
		return {
			url: url,
			clickStore: null
		};
	},
	onLoad() {
		// console.log(!!this.clickStore);
	},
	methods: {
		confirm() {
			let temp = JSON.parse(JSON.stringify(this.clickStore));
			this.clickStore = null;
		},
		cancel() {
			this.clickStore = null;
		},
		checkedStore(id) {
			this.clickStore = {
				name: '员村友客来便利店'
			};
		}
	}
};
</script>

<style lang="scss" scoped>
.app {
	height: 100%;
	background-color: #ffffff;
}
.current-locat {
	padding: 20rpx 30rpx;
	line-height: 60rpx;
	color: #000000;
}
.stores {
	background-color: #ffffff;
	box-shadow: 0rpx -4rpx 5rpx 0rpx rgba(0, 0, 0, 0.11);
	border-radius: 55rpx;
	padding: 30rpx;

	.stores-title {
		color: #bc0100;
		.text {
			color: #949494;
		}

		.icon {
			margin-left: 22rpx;
			width: 35rpx;
			height: 35rpx;
			border-radius: 50%;
			box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.1);
		}
	}

	.store-item {
		padding: 30rpx 0;
		border-bottom: 1rpx solid #f2f2f2;

		&:last-child {
			border-bottom: 0;
		}

		.store-detail {
			color: #000000;
			font-size: 30rpx;
			line-height: 60rpx;
			flex-grow: 1;
		}
		.store-name {
			font-size: 31rpx;
		}
		.store-number {
			font-size: 27rpx;
			color: #898989;
		}
		.store-img {
			width: 179rpx;
			height: 184rpx;
			border-radius: 10rpx;
			margin-right: 22rpx;
		}
		.store-distance {
			font-size: 27rpx;
		}
		.store-num {
			color: #bc0100;
		}
	}

	.store-addr-detail {
		color: #898989;
		font-size: 27rpx;
		line-height: 39rpx;
	}
}
.modal {
	font-size: 31rpx;
	.modal-title {
		color: #000000;
		line-height: 60rpx;
		margin-bottom: 30rpx;
	}
	.modal-content {
		color: #767676;
		text-align: center;
		line-height: 44rpx;
	}
	.btns {
		margin-top: 55rpx;
		width: 100%;
		border-top: 1rpx solid #f2f2f2;
	}
}
.btn {
	text-align: center;
	line-height: 100rpx;
	border-right: 1rpx solid #f2f2f2;

	&.cancel {
		color: #000000;
	}
	&.confirm {
		color: #566696;
	}

	&:last-child {
		border-right: 0;
	}
}
</style>
