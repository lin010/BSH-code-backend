<template>
	<view class="app">
		<view class="container">
			<view class="options">
				<view class="check">
					<view class="title">充值金额</view>
					<view class="nums flex">
						<view class="num flex-x-center" v-for="(item, i) in nums" :key="i" @click="clickNum(i, item.recharge_money)" :class="active != i ? '' : 'active'">
							<view class="text">{{ item.recharge_money }}元</view>
							<view class="desc">赠送{{ item.give_money }}元</view>
						</view>
					</view>
				</view>
				<view class="num-input">
					<view class="title">其他充值金额</view>
					<input
						class="input"
						type="text"
						v-model="dataForm.input_money"
						@input="oninput"
						placeholder="自定义金额"
						placeholder-style="font-size:11pt;color: #E6E6E6"
						maxlength="8"
					/>
				</view>
			</view>

			<view class="pay-types">
				<view class="title">支付方式</view>
				<view class="types">
					<view v-for="(item, i) in payTypes" :key="i">
						<view class="type" @click="switchPayType(item.key)" v-if="item.key == 'wechat'">
							<image :src="item.logo" mode="aspectFill" class="logo"></image>
							<!-- <image :src="'https://pic.downk.cc/item/5ed76ad5c2a9a83be5643111.png'" mode="aspectFill" class="logo"></image> -->
							<!-- <view class="name">{{'微信支付'}}</view> -->
							<view class="name">{{ item.name }}</view>
							<view class="icon iconfont" :class="dataForm.pay_type == item.key ? 'icon-xuanzhong-01 checked' : 'icon-quanquan check'"></view>
						</view>
					</view>
				</view>
			</view>

			<view class="bottom">
				<view class="btn submit font-size-9" @click="dataFormSubmit()"><span>确认支付</span></view>
			</view>
		</view>

		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
export default {
	data() {
		return {
			loading: false,
			nums: [],
			dataForm: {
				money: '',
				pay_type: '',
				input_money: ''
			},
			active: 0
		};
	},
	onLoad() {
		this.initConfig();
	},
	computed: {
		payTypes() {
			let temp = [];
			for (let i = 0; i < 2; i++) {
				let obj = {
					logo: i == 0 ? 'https://pic.downk.cc/item/5ed76ad5c2a9a83be5643111.png' : 'https://pic.downk.cc/item/5ed76ad1c2a9a83be5642c6c.png',
					name: i == 0 ? '微信支付' : '支付宝支付',
					key: i == 0 ? 'wechat' : 'alipay'
				};
				temp.push(obj);
			}
			return temp;
		}
	},
	watch: {
		'dataForm.input_money'(newVal, oldVal) {
			if (!newVal.length || !/^[0-9]*$/.test(newVal) || this.active < 0) return;
			this.dataForm.money = 0;
			this.active = -1;
		}
	},
	methods: {
		dataFormSubmit() {
			// 获取 支付类型 / 选择的金额 / 输入的金额
			let { money, input_money, pay_type } = this.dataForm;

			// 如果输入的金额为 0 则使用 选择的金额
			let amount = Number(input_money) ? Number(input_money) : Number(money);

			if (amount < 1) {
				this.$http.toast('充值不能小于1');
				return;
			}
			if (!pay_type.trim().length) {
				this.$http.toast('请选择支付方式');
				return;
			}

			// 验证都通过发送支付请求
			this.$http
				.request({
					url: this.$api.payment.doPay,
					method: 'POST',
					data: {
						pay_price: amount,
						pay_type: pay_type,
						union_id: 0
					},
					showLoading: true
				})
				.then(res => {
					if (res.code == 0) {
						this.$wechatSdk.pay(res.data);
					} else {
						this.$http.toast(res.msg);
					}
				});
		},
		initConfig() {
			this.loading = true;
			this.$http
				.request({
					url: this.$api.user.recharge_config
				})
				.then(res => {
					this.loading = false;
					if (res.code == 0) {
						if (!res.data) {
							this.$http.toast('当前未开通充值功能 3s回退到上一页');
							setTimeout(() => {
								uni.redirectTo({
									url: '../index'
								});
							}, 1000 * 3);
							return;
						}
						this.nums = res.data.list;
						this.dataForm.money = this.nums[0].recharge_money;
					}
				});
		},
		switchPayType(key) {
			this.dataForm.pay_type = key;
		},
		clickNum(i, money) {
			this.active = i;
			this.dataForm.money = money;
			this.dataForm.input_money = '';
		},
		oninput(e) {
			this.$nextTick(() => {
				let val = e.target.value.toString();
				val = val.replace(/[^\d]/g, ''); //清除"数字"以外的字符
				this.dataForm.input_money = val;
			});
		}
	}
};
</script>

<style lang="scss" scoped>
.app {
	min-height: 100%;
	background-color: #f7f7f7;
}

.container {
	padding: 20rpx 30rpx;
	color: #000000;
}

.options,
.pay-types {
	background-color: #ffffff;
	border-radius: 15rpx;

	.title {
		font-size: 12pt;
		font-weight: 600;
	}
}

.options {
	padding: 20rpx 18rpx 48rpx;
	margin-bottom: 30rpx;

	.nums {
		flex-wrap: wrap;
		color: #ffffff;
		margin: 40rpx 0;

		.num {
			flex-direction: column;
			width: 198rpx;
			height: 100rpx;
			// background-color: #E6E6E6;
			margin-right: 30rpx;
			margin-bottom: 20rpx;
			border-radius: 5rpx;

			background-color: #ffffff;
			border: 1rpx solid #e6e6e6;
			color: #000000;

			&:nth-child(3n) {
				margin-right: 0;
			}

			&.active {
				// background-color: #BC0100;
				border: 1rpx solid #bc0100;
				color: #bc0100;
			}

			.text,
			.desc {
				font-size: 10pt;
			}
		}
	}

	.input {
		padding-left: 32rpx;
		width: 256rpx;
		height: 60rpx;
		border-bottom: 4rpx solid #f2f2f2;
		font-size: 15pt;
		color: #bc0100;
		font-weight: bold;
	}
}

.pay-types {
	padding: 20rpx 18rpx;

	.type {
		padding: 20rpx 0;
		display: flex;
		align-items: center;

		.logo {
			margin-right: 16rpx;
			width: 36rpx;
			height: 36rpx;
		}

		.name {
			flex: 1;
			font-size: 9pt;
		}

		.icon {
			color: #e6e6e6;

			&.checked {
				color: #bc0100;
			}
		}
	}
}

.bottom {
	margin-top: 60rpx;

	.btn {
		line-height: 90rpx;
		text-align: center;
		border-radius: 45rpx;

		&.submit {
			background-color: #bc0100;
			color: #ffffff;
		}
	}
}

.flex {
	display: flex;
}

.flex-x-center {
	display: flex;
	align-items: center;
}
</style>
