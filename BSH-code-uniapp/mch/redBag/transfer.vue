<template>
	<view class="app">
		<view class="container" v-if="setting">
			<view class="text-input-desc m-top-20">
				<view class="money">
					<view class="dot font-size-16">¥</view>
					<input
						class="input font-size-12"
						type="number"
						v-model="dataForm.price"
						maxlength="20"
						placeholder-style="color:#BFBFBF"
						:placeholder="`最高可转账${income}`"
					/>
					<view class="text font-size-9" @click="max()" :style="{color:'#FF7104'}">全部转账</view>
				</view>
				<view class="sum font-size-9 m-top-20">
					可转账数量：
					<span class="red" :style="{color:'#FF7104'}">{{ income }}</span>
				</view>
			</view>
			<view class="text-input-desc m-top-20">
				<view class="money">
					<view class="dot font-size-16">接收人</view>
					<input
						class="input font-size-12"
						type="text"
						v-model="dataForm.mobile"
						maxlength="20"
						placeholder-style="color:#BFBFBF"
						placeholder="请输入接收人的手机号码"
					/>
					<view class="text font-size-9" @click="serachMobileUser()" :style="{color:'#FF7104'}">查找用户</view>
				</view>
				<view class="sum font-size-9 m-top-20"  v-if="receiver_user.id">
					<view class="content-head">
						<view class="super">
							<image :src="receiver_user.avatar_url" mode="aspectFill" class="acatar"></image>
							<view class="userinfo">
								<view class="username over1"> {{ receiver_user.nickname }}</view>
								<view class="tel">
									<span>账号ID:</span>
									{{ receiver_user.id }}
								</view>
								<view class="tel">
									<span>手机号:</span>
									{{ receiver_user.mobile }}
								</view>
							</view>
						</view>
					</view>
				</view>
			</view>

			<view class="fee-desc m-top-20 font-size-9">
				<view class="title">费用说明</view>
				<view class="fee">
					<view class="handling-fee">
						<view class="sum" :style="{color:'#FF7104'}">{{ setting.cash_service_fee }}%</view>
						<view class="desc">手续费费率</view>
					</view>
				</view>
			</view>

			<view class="bottom">
				<view class="btn submit font-size-9" @click="dataFormSubmit" :style="{background:'#FF7104'}"><span>确认转账</span></view>
			</view>
		</view>
		
		<view class="bottom bottom-log">
			<view class="btn submit font-size-9" @click="toTransferLog" :style="{background:'#5b5558'}"><span>转账日志</span></view>
		</view>
		<com-modal :button="button" :show="modal" @click="handleClick" @cancel="hide" title="提示" content="您没有设置支付密码,是否需要跳转设置？"></com-modal>
		
		<com-payment-password ref="paymentPassword" :show="cashFlag" :value="paymentPwd" :digits="6"
		@submit="checkPwd" @checkSafePwd="safePasswork"></com-payment-password>
		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
import tuiListCell from "@/components/list-cell/list-cell"
export default {
	components: {
		tuiListCell
	},
	data() {
		return {
			loading: false,
			dataForm: {
				price: '',
				mobile: '',
				receiver_user_id:0,
			},
			receiver_user:{},
			setting: null,
			user_info: null,
			income: 888,
			imgArr: [],
			cashFlag: false,
			// 支付密码
			paymentPwd: '',
			is_transaction_password:true,//是否设置过支付密码
			modal:false,//模态弹窗
			modal2:false,//模态弹窗
			textCol:'#bc0100',
			button:[],
			button2:[],
			default_bank:'',
			startYear: 1950,
			endYear: 2050,
			setDateTime: "",
			value: [0, 0],
			text: "",
			multiArray: [], //picker数据
			currencyAlias:{
				balance_alias: '',
				red_envelope_alias: '',
				integral_alias: '',
				silver_beans_alias: '',
			},
			
		};
	},
	onLoad() {
		this.textCol = this.globalSet('textCol');
		this.button = this.globalSet('btnCol','确定');
		this.button2 = this.globalSet('btnCol','确定');
		
		let currencyAliasConfig = uni.getStorageSync('currencyAlias');
		if(currencyAliasConfig){
			JSON.parse(currencyAliasConfig);
			this.currencyAlias = JSON.parse(currencyAliasConfig);
			uni.setNavigationBarTitle({
				  title: this.currencyAlias.red_envelope_alias+'转账'
			});
		}
	},
	onShow() {
		this.initSetting();
	},
	computed: {
	
	},
	methods: {
		toTransferLog(){
			uni.navigateTo({
				url:'/mch/redBag/transfer-log'
			})
		},
		hide() {
			this.modal = false;
		},
		hide2() {
			this.modal2 = false;
		},
		handleClick(e) {
			let index = e.index;
			if (index === 0) {
				this.modal = false;
			} else {
				uni.navigateTo({
					url:'/pages/user/payment/password'
				})
			}
			this.hide();
		},
		handleClick2(e) {
			let index = e.index;
			if (index === 0) {
				this.modal2 = false;
			} else {
				uni.navigateTo({
					url:'/pages/user/info'
				})
			}
			this.hide2();
		},
		checkPwd(e) {
			// this.paymentPwd = e.value;
			return;
			
		},

		dataFormSubmit() {
			let self = this;
			let { price, mobile} = self.dataForm;
			
			if(!this.is_transaction_password){
				this.modal = true;
				return;
			}

			if (Number(self.income) < Number(price)) {
				self.$http.toast(`您的可转账${self.currencyAlias.red_envelope_alias}不足以转账`);
				return;
			}
			
			self.cashFlag = true;
			// 调用弹出函数
			self.$refs.paymentPassword.modalFun('show');
			return;
		},
		safePasswork(e){
			this.dataForm.transaction_password = e.passwork;
			
			uni.showLoading({
				title: '校验安全密码中'
			});
			this.$http.request({
				url: this.$api.plugin.extensions.integral_transfer.submit,
				method: 'POST',
				data: this.dataForm,
				showLoading: true
			}).then(res => {
				this.$http.toast(res.msg);
				this.$refs.paymentPassword.modalFun('hide');
				if (res.code == 0) {
					this.dataForm.price = '';
					this.text = '';
					this.initSetting();
					this.$http.toast(res.msg);
				}else{
					this.$http.toast(res.msg);
				}
			});
		},
		initSetting() {
			this.loading = true;
			this.$http
				.request({
					url: this.$api.plugin.extensions.integral_transfer.setting,
				})
				.then(res => {
					this.loading = false;
					if (res.code == 0) {
						let { setting, user_info  } = res.data;
						this.setting = setting;
						this.user_info = user_info;
						this.income = user_info.static_integral;
						this.is_transaction_password = res.data.user_info.is_transaction_password;
					} else {
						this.$http.toast(res.msg);
						setTimeout(() => {
							uni.navigateBack();
						}, 1000 * 2);
					}
				});
		},
		serachMobileUser(){
			this.loading = true;
			this.$http
				.request({
					url: this.$api.plugin.extensions.integral_transfer.mobileUser,
					data: {mobile:this.dataForm.mobile},
				})
				.then(res => {
					this.loading = false;
					if (res.code == 0) {
						this.receiver_user = res.data.user;
						this.dataForm.receiver_user_id = res.data.user.id;
					} else {
						this.$http.toast(res.msg);
					}
				});
		},
		max() {
			this.dataForm.price = Number(this.income);
		},
	}
};
</script>

<style lang="scss" scoped>
.app {
	background-color: #f2f2f2;
	min-height: 100%;
	color: #000000;

.super {
					position: relative;
					background-color: #ffffff;
					border-radius: 8rpx;
					margin: 0 30rpx;
					display: flex;
					align-items: center;

					.acatar {
						width: 122rpx;
						height: 122rpx;
						border-radius: 50%;
						margin-right: 30rpx;
					}

					.userinfo {

						.username,
						.desc,
						.tel {
							line-height: 48rpx;
						}

						.username {
							color: #000000;
							font-weight: bold;
							font-size: 30rpx;
						}

						.desc,
						.tel {
							color: #bc0100;
							font-size: 9pt;
						}

						.iconfont {
							font-size: 10pt;
							margin-right: 8rpx;
						}
					}
				}

	.text-input-desc,
	.fee-desc,
	.withdraw-type {
		background-color: #ffffff;
	}

	.text-input-desc {
		padding: 20rpx 30rpx;
		display: flex;
		flex-direction: column;

		.money {
			display: flex;
			align-items: center;
			padding: 30rpx 0;
			border-bottom: 1rpx solid #f2f2f2;

			.dot {
				margin-right: 20rpx;
				font-weight: 600;
			}

			.input {
				flex: 1;
				border-bottom: 0;
			}

			.text {
				color: #bc0100;
				font-weight: 600;
			}
		}

		.sum {
			color: #333333;

			.red {
				color: #bc0100;
			}
		}
	}

	.fee-desc {
		.fee {
			display: flex;
			padding: 10rpx 0;

			.handling-fee,
			.labor-service-fee {
				height: 170rpx;
				flex: 1;
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
			}

			.handling-fee {
				// border-right: 1rpx solid #F2F2F2;
			}

			.sum,
			.desc {
				line-height: 40rpx;
			}

			.sum {
				color: #bc0100;
				font-weight: 600;
				font-size: 11pt;
				margin-bottom: 8rpx;
			}
		}
	}

	.withdraw-type {
		.items {
			background-color: #f2f2f2;

			.item {
				background-color: #ffffff;
				padding: 0 36rpx;

				&:first-child {
					margin-top: 0;
				}
			}

			.type {
				padding: 20rpx 0;
				display: flex;
				align-items: center;

				.logo {
					margin-right: 16rpx;
					width: 53rpx;
					height: 53rpx;
				}

				.name {
					flex: 1;
				}
			}

			.attach {
				padding-bottom: 20rpx;
				position: relative;

				.upload-img {
					width: 130rpx;
					height: 130rpx;
					color: #bfbfbf;
					border: 1rpx dotted #bfbfbf;
					z-index: 10;

					.iconfont {
						font-size: 12pt;
					}
				}
			}
		}
	}

	.bottom {
		margin: 60rpx 0;
		padding: 0 30rpx;

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
	.bottom-log{
		margin:0 ;
	}

	.title {
		padding: 0 30rpx;
		line-height: 60rpx;
		border-bottom: 1rpx solid #f2f2f2;
	}
}

.input {
	font-size: 10pt;
	margin-top: 10rpx;
	border-bottom: 2rpx solid #f2f2f2;
	height: 70rpx;
	padding-left: 20rpx;
}

.checked {
	color: #bc0100;
}

.check {
	color: #9e9e9e;
}

.font-size-16 {
	font-size: 16pt;
}

.font-size-12 {
	font-size: 12pt;
}

.font-size-9 {
	font-size: 9pt;
}

.m-top-20 {
	margin-top: 20rpx;
}

.m-top-12 {
	margin-top: 12rpx;
}
</style>
