<template>
	<view class="withdrawal-app">
		<view class="withdrawal-user-money">
			<text>可提现金额（元）</text>
			<text>￥{{information.account_money}}</text>
		</view>
		<view class="withdrawal-money-input">
			<view class="withdrawal-money-input-title">
				提现金额
			</view>
			<view class="withdrawal-money-input-num">
				<view class="withdrawal-money-icon">￥</view>
				<view class="withdrawal-money-num">
					<input type="digit" value="" v-model.trim="money" />
				</view>
				<view class="withdrawal-money-num-all" @click="checkAll">
					全部提现
				</view>
			</view>
			<view class="withdrawal-money-notic">
				手续费每笔{{information.withdraw_fee}}元,提现后立即到帐
			</view>
		</view>
		<view class="withdrawal-sure">
			<button type="default" @click='deposit'>确认提现</button>
		</view>
		<view class="withdrawal-item">
			<jx-list-cell :arrow="true" padding="0" :lineLeft="false" @click="link">
				<view class="jx-cell-header">
					<view class="jx-cell-title">提现记录</view>
				</view>
			</jx-list-cell>
		</view>
		<passkeyborad :show="show" @close="checkout" :money="money" :count='information.withdraw_fee'></passkeyborad>
	</view>
</template>

<script>
	import {
		isEmpty
	} from '../../../common/validate.js'
	import jxListCell from '@/components/list-cell/list-cell';
	import passkeyborad from '@/components/yzc-paykeyboard/yzc-paykeyboard.vue'
	export default {
		components: {
			jxListCell,
			passkeyborad
		},
		data() {
			return {
				information: '',
				money: '', //金额
				show: false, //密码输入弹出
				flag:false
			};
		},
		onShow() {
			this.$http.request({
				url: this.$api.moreShop.getaccountInfo,
				method: 'POST',
				showLoading: true
			}).then(res => {
				if (res.code == 0) {
					this.information = res.data
				}
			})
			this.show = false
		},
		methods: {
			link() { //跳到流水明细页面
				uni.navigateTo({
					url:'./withdrawal_detail'
				})
			},
			deposit() { //确认提现
				var that = this
				if(that.flag){
					return
				}
				that.flag=true
				if (isEmpty(that.money) || that.money <= 0) {
					uni.showToast({
						title: '提现金额不能为空或者小于0',
						icon: 'none'
					});
					setTimeout(function() {
						uni.hideToast();
						that.flag=false
					}, 2000);
					return
				}else{
					that.flag=false
				}
				if (that.information.is_pwd_set == 0) {
					that.$http.toast('未设置支付密码')
					setTimeout(function() {
						if (isEmpty(that.information.mobile)) {
							uni.navigateTo({
								url: '../personalCentreSETPassWorde/personalCentreSETPassWorde'
							})
							that.flag=false
						} else {
							uni.navigateTo({
								url: '../personalCentreSETPassWorde/personalCentreSETPassWorde?phone=' +
									that.information.mobile
							})
							that.flag=false
						}
					}, 2000)
				} else {
					if (isEmpty(that.information.efps_bank.paper_openBank)||isEmpty(that.information.efps_bank.paper_settleAccount)||isEmpty(that.information.efps_bank.paper_settleAccountNo)) {
						that.$http.toast('未设置结账信息')
						setTimeout(function() {
							uni.navigateTo({
								url: '../countSet/countSet'
							})
							that.flag=false
						}, 1500)
					} else {
						that.show = true
					}
				}
			},
			checkAll() { //全部提现
				this.money = String(this.information.account_money)
			},
			checkout() { //关闭提现输入密码弹窗
				this.show = false
			}
		}
	}
</script>

<style lang="less">
	.withdrawal-app {
		width: 100%;
		height: 100%;
		background: url(https://dev.mingyuanriji.cn/web/static/withdrawal-back.jpg)no-repeat; 
		background-size: cover;
	}

	.withdrawal-user-money {
		width: 100%;
		overflow: hidden;
		display: flex;
		flex-wrap: wrap;
	}

	.withdrawal-user-money text {
		display: block;
		width: 100%;
		overflow: hidden;
		text-align: center;
		color: #fff;
	}

	.withdrawal-user-money text:nth-of-type(1) {
		font-size: 35rpx;
		margin-top: 50rpx;
	}

	.withdrawal-user-money text:nth-of-type(2) {
		font-size: 80rpx;
		font-weight: bold;
	}

	.withdrawal-money-input {
		width: 90%;
		height: 300rpx;
		margin: 80rpx auto;
		background: #fff;
		border-radius: 20rpx;
		box-shadow: 0 3rpx 20rpx rgba(183, 183, 183, 0.5);
		box-sizing: border-box;
		padding: 20rpx;
	}

	.withdrawal-money-input-title {
		width: 100%;
		overflow: hidden;
		color: #000;
	}

	.withdrawal-money-input-num {
		width: 100%;
		overflow: hidden;
		display: flex;
		justify-content: space-between;
		margin-top: 30rpx;
	}

	.withdrawal-money-icon {
		line-height: 70rpx;
		color: #000;
		font-weight: bold;
		font-size: 40rpx;
	}

	.withdrawal-money-num {
		width: 65%;
		line-height: 70rpx;
		border-bottom: 1rpx solid #B0B0B0;
		height: 70rpx;
	}
	.withdrawal-money-num input{
		width: 100%;
		line-height: 70rpx;
		height: 70rpx;
		display: block;
	}

	.withdrawal-money-num-all {
		width: 25%;
		background:#FF7104;
		text-align: center;
		height: 60rpx;
		line-height: 60rpx;
		border-radius: 30rpx;
		color: #fff;
		font-size: 30rpx;
	}

	.withdrawal-money-notic {
		width: 100%;
		overflow: hidden;
		font-size: 28rpx;
		margin-top: 40rpx;
	}

	.withdrawal-sure {
		width: 80%;
		height: 80rpx;
		background:#FF7104;
		margin: 0 auto;
		border-radius: 10rpx;
	}

	.withdrawal-sure button {
		width: 100%;
		height: 100%;
		outline: none;
		border: none;
		background:#FF7104;
		line-height: 80rpx;
		text-align: center;
		color: #fff;
	}

	.withdrawal-item {
		width: 100%;
		overflow: hidden;
		margin: 60upx 0 0 0;
	}

	.jx-content-box {
		width: 100%;
		padding: 0 30rpx;
		box-sizing: border-box;
		margin-top: 20rpx;
	}

	.jx-header-btm {
		border-radius: 12rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: space-between;
		color: #000;
		background: #ffffff;
		margin-bottom: 20rpx;
		// box-shadow: 1rpx 1rpx 5rpx #888;
	}

	.jx-btm-item {
		flex: 1;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		padding: 40rpx;
		position: relative;

		&::after {
			content: '';
			border-right: 1rpx solid #b3b3b3;
			width: 1px;
			height: 100rpx;
			position: absolute;
			right: 0;
			top: 50%;
			transform: translateY(-50%);
		}

		&:last-child {
			&::after {
				display: none;
			}
		}
	}

	.jx-cell-header {
		width: 100%;
		height: 100rpx;
		padding: 0 40rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-bottom: 1rpx solid #f3f3f3;
	}

	.jx-cell-title {
		font-size: 9pt;
		line-height: 30rpx;
		color: #333;
		font-weight: 600;
		letter-spacing: 1px;
	}

	.jx-cell-sub {
		font-size: 9pt;
		font-weight: 400;
		color: #999;
		padding-right: 10rpx;
	}
</style>
