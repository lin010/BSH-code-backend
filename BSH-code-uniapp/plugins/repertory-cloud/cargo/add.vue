<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="我要补货" :status-bar="true" :background-color="navBg" :border="false"
		:color="navCol"></com-nav-bar>
		<view class="list">
			<view class="items" v-if="list.length>0">
				<view class="item flex flex-y-center" v-for="(item, index) in list" :key="index">
					<image :src="item.cover_pic" class="goods-cover"></image>
					<view class="item-goods-info">
						<view class="goods-name over1 margin-bottom-20rpx">{{ item.goods_name }}</view>
						<view class="goods-nums flex margin-bottom-20rpx">
							<view class="goods-num" :style="{color:textColor,border:'1px solid'+textColor}">库存货：{{ item.stock_num }}</view>
						</view>
						<view class="goods-cost flex-x-between">
							<view class="flex">
								<view class="flex" style="margin-right: 20rpx;">
									<view class="text">补货价:</view>
									<view class="price flex" :style="{color:textColor}">
										<span class="dot">￥</span>
										<span>{{ item.price || 0 }}</span>
									</view>
								</view>
								<view class="flex">
									<view class="text">利润:</view>
									<view class="price flex" :style="{color:textColor}">
										<span class="dot">￥</span>
										<span>{{ item.origin_price-item.price }}</span>
									</view>
								</view>
							</view>
							<view class="num-box flex">
								<view class="declined iconfont icon-jianhao" @click.stop="sub(item,index)"></view>
								<view class="sum" @tap="show(item,index)">{{ item.buy_num }}</view>
								<view class="add iconfont icon-jiahao" @click.stop="add(item,index)" :style="{color:textColor}"></view>
							</view>
							<!-- <view class="btn flex-x-center flex-y-center jx-primary-btn" @click="changeSendState('111')">
								<view>立即发货</view>
							</view> -->
						</view>
					</view>
				</view>
			</view>

			<view class="items" v-else>
				<main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore>
			</view>
		</view>

		<view class="fixed flex flex-y-center">
			<view class="total-box flex flex-grow-1 flex-x-between flex-y-center">
				<view class="flex">
					<view>合计:</view>
					<view class="total-prices flex" :style="{color:textColor}">
						<view class="dot">¥</view>
						<view>{{ total_price }}</view>
					</view>
				</view>
				<view class="btn" @click="orderSubmit" :style="{background:textColor}">确定补货</view>
			</view>
		</view>
		
		<!-- modal弹窗输入数量 -->
		<com-modal :show="modal9" @cancel="hide" padding="30rpx 40rpx" :custom="true" :fadein="true">
			<view class="tui-modal-custom">
				<view class="tui-prompt-title">请输入数量</view>
				<input placeholder="请输入数量" class="tui-input" type="number" v-model="inp_num"/>
				<view class="tui-modal-operate">
					<view @tap="handleClick" style="font-size: 34rpx;">取消</view>
					<view class="tui-modal-confirm" style="font-size: 34rpx;" @tap="handleClick('confirm')">确定</view>
				</view>
			</view>
		</com-modal>
		
		<com-bottom-popup :show="showPay" @close="changePay('cancel')">
			<view class="popup-model">

				<view class="popup-model-title flex flex-x-center flex-y-center">
					<view>确认收货</view>
				</view>
				<view class="flex-col flex-y-center">
					<view class="text">支付金额</view>
					<view class="pay-prices flex">
						<view class="dot">¥</view>
						<view>{{ total_price }}</view>
					</view>
				</view>
				<view class="pay-list">
					<view class="pay-type wechat flex flex-x-between" @click="payTypeChange('WECHAT_PAY')">
						<view class="flex flex-y-center">
							<view class="icon">
								<image src="../../images/pay/wechat.png" class="icon-img"></image>
							</view>
							<view class="text">微信支付</view>
						</view>
						<view class="checke iconfont icon-quanquan" :class="pay_type == 'WECHAT_PAY' ? 'checked icon-dagou1' : ''"></view>
					</view>
					<view class="pay-type balance flex flex-x-between" @click="payTypeChange('BALANCE_PAY')">
						<view class="flex flex-y-center">
							<view class="icon">
								<image src="../../images/pay/balance.png" class="icon-img"></image>
							</view>
							<view class="text">账户{{currencyAlias.balance_alias}}</view>
						</view>
						<view class="checke iconfont icon-quanquan" :class="pay_type == 'BALANCE_PAY' ? 'checked icon-dagou1' : ''"></view>
					</view>
				</view>
				<view class="btn" @tap="confirm">确认支付</view>
			</view>
		</com-bottom-popup>

	</view>
</template>

<script>
	export default {
		components: {
			search: () => import('@/components/search/search')
		},
		data() {
			return {
				store_avatar: 'http://jxmall.sinbel.top/web//uploads/images/original/20200730/b565d9a2635e48ddf47200f67602c3cb.jpg',
				total_price: '0.00',
				goods_num: 10,
				showPay: false,
				list: [],
				payment: [],
				pay_type: 'BALANCE_PAY',
				order_id: 0,
				modal9: false,//控制modal弹窗是否显示
				item:'',
				index:'',
				inp_num:'',//输入框输入的数量
				
				textColor:'#bc0100',
				bg_url:'',
				button:[],
				navBg:'',
				navCol:'',
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		onLoad(options) {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.button = this.globalSet('btnCol');
			this.navBg = this.globalSet('navBg');
			this.navCol = this.globalSet('navCol');
			
			this.getList()
		},
		methods: {
			handleClick(str) {
				if(str == 'confirm'){
					this.item.buy_num = this.inp_num;
					this.list[this.index] = this.item;
					this.getPrice()
				}
				this.hide();
			},
			add(item, index) {
				item.buy_num++;
				this.list[index] = item;
				this.getPrice()
			},
			hide() {
				this.modal9 = false;
			},
			show(obj,index) {
				this.modal9 = true;
				this.item = obj;
				this.index = index;
			},
			back(){
				this.navBack();
			},
			confirm() {
				this.pay()
				this.changePay('cancel');
			},
			payTypeChange(type) {
				this.pay_type = type;
			},
			changePay(type) {
				type == 'cancel' ? this.showPay = false : this.showPay = true;
			},
			sub(item, index) {
				if (item.buy_num <= 0) {
					this.$http.toast("数量已经为0");
					return;
				}
				item.buy_num--;
				this.list[index] = item
				this.getPrice()
			},
			getPrice() {
				this.total_price = 0;
				for (let i in this.list) {
					let item = this.list[i];
					this.total_price += item['buy_num'] * item['price'];
				}
			},
			orderSubmit() {
				
				if (this.list.length <= 0) {
					uni.showToast({
						title: '请选择商品'
					})
					return;
				}
				uni.showLoading({
					title:'正在下单'
				})
				let goods_list = [];
				for (let i in this.list) {
					let item = this.list[i];
					if (item['buy_num'] > 0) {
						let newItem = {
							goods_id: item['goods_id'],
							num: item['buy_num']
						}
						goods_list.push(newItem)
					}
				}
				if (goods_list.length == 0) {
					uni.showToast({
						title: '请选择商品'
					})
					return
				}
				this.$http
					.request({
						url: this.$api.plugin.stock.fill_order_submit,
						method: 'POST',
						data: {
							order_detail: goods_list
						}
					})
					.then(res => {
						uni.hideLoading()
						this.loading = false;
						if (res.code == 0) {
							this.showPay = true;
							this.order_id = res.data.order_id
						} else {
							uni.showToast({
								title: res.msg
							});
						}
					});
			},
			pay: function() {
				if (this.order_id == 0) {
					uni.showToast({
						title: '请先提交订单'
					})

					return;
				}
				uni.showLoading({
					title: '正在支付'
				})
				this.$http
					.request({
						url: this.$api.plugin.stock.pay_data,
						data: {
							order_id: this.order_id,
							pay_type: this.pay_type
						}
					})
					.then(res => {
						this.loading = false;
						uni.hideLoading();
						if (res.code == 0) {
							if (this.pay_type == 'BALANCE_PAY') {
								uni.showToast({
									title: res.msg
								})
							}
							if (this.pay_type == 'WECHAT_PAY') {
								let rs_config = res.data.config;
								let config = {
									appid: rs_config.appId,
									timeStamp: rs_config.timestamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
									nonceStr: rs_config.nonceStr, // 支付签名随机串，不长于 32 位
									package: rs_config.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=\*\*\*）
									signType: rs_config.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
									paySign: rs_config.paySign, // 支付签名
								}
								this.$wechatSdk.pay(config,'/plugins/repertory-cloud/index')
							}
						} else {
							uni.showModal({
								title: '提示',
								content: res.msg
							})
						}
					});

			},
			getList() {
				this.loading = true;
				this.$http
					.request({
						url: this.$api.plugin.stock.stock_goods_list,
					})
					.then(res => {
						this.loading = false;
						if (res.code == 0) {
							this.list = res.data.list;
						} else {
							uni.showToast({
								title: res.msg
							});
						}
					});
			},
			getPayment() {
				this.loading = true;
				this.$http
					.request({
						url: this.$api.plugin.stock.payment,
					})
					.then(res => {
						this.loading = false;
						if (res.code == 0) {
							this.payment = res.data.payment;
						} else {
							uni.showToast({
								title: res.msg
							});
						}
					});
			},
		}
	};
</script>

<style lang="scss" scoped>
	.app {
		height: 100%;
		background-color: #f5f5f5;
	}

	.list {
		margin: 10rpx 0 140rpx;
		background-color: #ffffff;

		.item {
			padding: 24rpx 30rpx;
			border-bottom: 1rpx solid #f2f2f2;
			line-height: 38rpx;

			&:last-child {
				border-bottom: 0;
			}

			.goods-cover {
				width: 168rpx;
				height: 168rpx;
				border-radius: 5rpx;
				margin-right: 18rpx;
			}

			.item-goods-info {
				flex-grow: 1;

				.goods-name {
					color: #333333;
					max-width: 450rpx;
					font-size: 36rpx;
					line-height: 44rpx;
				}

				.goods-nums {
					color: #bc0100;
					font-size: 18rpx;
					line-height: 18rpx;

					.goods-num {
						padding: 7rpx 9rpx;
						border-radius: 5rpx;
						border: 1rpx solid #bc0100;
						margin-right: 18rpx;
					}
				}

				.goods-cost {
					.text {
						color: #666666;
						font-size: 18rpx;
					}

					.price {
						color: #bc0100;
						font-size: 24rpx;
						align-items: baseline;
					}

					.dot {
						font-size: 18rpx;
						line-height: 18rpx;
					}

					.num-box {
						height: 20px;
						line-height: 20px;

						.declined {
							color: #cdcdcd;
						}

						.add {
							color: #bc0100;
						}

						.sum {
							padding: 0 16rpx;
							color: #333333;
						}

						.iconfont {
							font-size: 20px;
						}
					}
				}
			}
		}
	}

	.popup-model {
		.popup-model-title {
			font-size: 30rpx;
			height: 100rpx;
			line-height: 100rpx;
			color: #1F1F1F;
			border-bottom: 1rpx solid #E0E0E0;
			margin-bottom: 32rpx;
		}

		.pay-prices {
			color: $jx-color-primary;
			font-size: 62rpx;
			align-items: baseline;

			.dot {
				font-size: 40rpx;
			}
		}

		.pay-type {
			padding: 20rpx 30rpx;

			.icon {
				width: 36rpx;
				height: 36rpx;
				margin-right: 24rpx;

				.icon-img {
					width: 100%;
					height: 100%;
					background-size: 100%;
				}
			}

			.checke {
				color: #8A8A8A;
				line-height: 20px;
				font-size: 20px;

				&.checked {
					color: $jx-color-primary;
				}
			}
		}

		.btn {
			margin: 50rpx 30rpx;
			background-color: $jx-color-primary;
			height: 90rpx;
			line-height: 90rpx;
			text-align: center;
			border-radius: 45rpx;
			color: #FFFFFF;
			font-size: 26rpx;
		}

		.text {
			color: #333333;
			font-size: 26rpx;
			line-height: 60rpx;
		}
	}

	.fixed {
		position: fixed;
		left: 0;
		right: 0;
		bottom: 0;
		height: 136rpx;
		background-color: #ffffff;

		.total-box {
			padding: 0 30rpx;
			color: #000000;
			font-size: 28rpx;
			line-height: 60rpx;

			.total-prices {
				margin-left: 10rpx;
				color: $jx-color-primary;
				font-size: 44rpx;
				font-weight: bold;
				align-items: baseline;
			}

			.dot {
				font-size: 32rpx;
				margin-right: 14rpx;
			}
		}

		.btn {
			width: 208rpx;
			height: 80rpx;
			line-height: 80rpx;
			text-align: center;
			background: $jx-color-primary;
			border-radius: 40rpx;
			color: #FFFFFF;
		}
	}

	.margin-bottom-20rpx {
		margin-bottom: 10px;
	}
	
	/* modal弹窗 */
	.tui-modal-custom {
		/* text-align: center */
	}
	.tui-prompt-title {
		padding-bottom: 20rpx;
		font-size: 34rpx;
		color: #000000;
	}
	.tui-input {
		margin: 30rpx 0rpx;
		border-bottom: 2rpx solid #BC0100;
		/* padding-bottom: 20rpx; */
		font-size: 32rpx;
		caret-color:#BC0100;
	}
	.tui-modal-operate{
		display: flex;
		justify-content: flex-end;
		font-size: 11pt;
		color: #BC0100;
	}
	.tui-modal-confirm{
		margin: 0 30rpx 0 70rpx;
	}
</style>
