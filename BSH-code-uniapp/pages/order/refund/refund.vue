<template>
	<view class="app">
		<view class="container" v-if="data">
			<view class="image-text">
				<image class="image" :src="data.goods_info.pic_url" mode="aspectFit"></image>
				<view class="goods-info">
					<view class="goods-name">{{ data.goods_info.name }}</view>
					<view class="goods-attr-name">{{ data.goods_info.attr_list[0].attr_group_name }}:{{ data.goods_info.attr_list[0].attr_name }}</view>
				</view>
			</view>
			<view class="drawers">
				<view class="drawer" v-if="dataForm.type == 0">
					<view class="select">
						<view class="name">货物状态</view>
						<view class="text" @click="showModal('is_receipt')">
							<view class="msg" :class="dataForm.is_receipt > -1 ? 'checked' : ''">
								{{ gargoStatus[dataForm.is_receipt] ? gargoStatus[dataForm.is_receipt].value : '请选择' }}
							</view>
							<view class="iconfont icon-xiala right" :style="{'--textColor':textColor}"></view>
						</view>
					</view>
				</view>
				<view class="drawer">
					<view class="select">
						<view class="name">退款原因</view>
						<view class="text" @click="showModal('reason')">
							<view class="msg" :class="dataForm.reason > -1 ? 'checked' : ''">
								{{ reasonStatus[dataForm.reason] ? reasonStatus[dataForm.reason].value : '请选择' }}
							</view>
							<view class="iconfont icon-xiala right"></view>
						</view>
					</view>
				</view>
			</view>

			<view class="refund-info">
				<view class="price">
					<view>退款金额:</view>
					<view class="red" :style="{'--textColor':textColor}">¥{{ dataForm.refund_total_price }}</view>
				</view>
				<view class="price">
					<view>退款购物/{{currencyAlias.integral_alias}}卷:</view>
					<view class="red" :style="{'--textColor':textColor}">¥{{ dataForm.integral_score_price }}</view>
				</view>
				<view class="desc">
					<span>不可修改,最多¥{{ dataForm.refund_total_price }}</span>
				</view>
				<view class="explain" v-if="dataForm.type == 0">
					<view>退款说明:</view>
					<view class="input"><input type="text" v-model="dataForm.remark" maxlength="100" placeholder="选填" /></view>
				</view>
				<view class="drawer" v-else>
					<view class="select">
						<view class="name">退货方式</view>
						<view class="text" @click="showModal('refund_type')">
							<view class="msg" :class="dataForm.refund_type > -1 ? 'checked' : ''">
								{{ refundTypes[dataForm.refund_type] ? refundTypes[dataForm.refund_type].value : '请选择' }}
							</view>
							<view class="iconfont icon-xiala right"></view>
						</view>
					</view>
				</view>
			</view>

			<view class="explain" v-if="dataForm.type == 1" style="">
				<view>退款说明:</view>
				<view class="input"><input type="text" v-model="dataForm.remark" maxlength="100" placeholder="选填" /></view>
			</view>

			<view class="upload">
				<view class="title">上传凭证</view>
				<view class="btn">
					<view v-if="dataForm.pic_list.length == 0" class="img flex-col flex-x-center flex-y-center">
						<view class="iconfont icon-xiangji"></view>
						<view>上传凭证</view>
					</view>
					<!-- <view v-else>
						<com-upload
							ref="upload"
							:serverUrl="serverUrl"
							:limit="3"
							@complete="result"
							@remove="remove"
							:width="180"
							:height="180"
						></com-upload>
					</view> -->
					<com-upload
						ref="upload"
						:serverUrl="serverUrl"
						:limit="3"
						@complete="result"
						@remove="remove"
						:width="180"
						:height="180"
						:style="uploadStyle"
					></com-upload>
				</view>
			</view>

			<view class="btn-text submit" :style="{'background':'#07BEB4'}" @click="dataFormSubmit">提交</view>
		</view>

		<com-bottom-popup :show="bottomPopup" :radius="false" @close="hideModal">
			<view class="select-items" v-if="items">
				<view class="items">
					<view class="items-title jx-border-bottom">{{ items.name }}</view>
					<view class="item jx-border-bottom" v-for="(item, i) in items.data" :key="i" @click="change(items.key, item.key)">
						<view class="name">{{ item.value }}</view>
						<view class="key"><view class="iconfont" :class="dataForm[items.key] == item.key ? 'icon-dagou1' : 'icon-quanquan'" :style="{'--textColor':textColor}"></view></view>
					</view>
				</view>
				<view class="btn-text close" :style="{'background':'#07BEB4'}" @click="hideModal">确定</view>
			</view>
		</com-bottom-popup>

		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	import {
		isEmpty
	} from '../../../common/validate.js'
export default {
	data() {
		return {
			data: '',
			serverUrl: this.$api.default.upload,
			images: [],
			bottomPopup: false,
			items: null,
			dataForm: {
				order_detail_id: 0,
				type: 0,
				is_receipt: -1,
				reason: -1,
				refund_price: '',
				refund_type: -1,
				remark: '',
				pic_list: [],
				textColor:'',
				integral_score_price:'',
				refund_total_price:''
			},
			loading: false,
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
		this.dataForm.type = options.status;
		if (options.id) {
			this.getDetail(options.id, true);
		}
	},
	methods: {
		dataFormSubmit() {
			let temp = JSON.parse(JSON.stringify(this.dataForm));
			// 判断退货方式是否为-1 为-1 则改为0
			temp.refund_type = temp.refund_type < 0 ? 0 : temp.refund_type;
			// temp.reason 修改数字为 退款原因数组对应数据
			if(isEmpty(this.reasonStatus[temp.reason])){
				return this.$http.toast('未选择退款原因');
			}
			temp.reason = this.reasonStatus[temp.reason].value;
			console.log(temp);
			this.$http
				.request({
					url: this.$api.order.refundSubmit,
					method: 'POST',
					data: temp,
					showLoading: true
				})
				.then(res => {
					this.$http.toast(res.msg);
					if (res.code == 0) {
						setTimeout(() => {
							uni.redirectTo({
								url: './success'
							});
						}, 1000);
					}
				});
		},
		getDetail(id, bool) {
			this.loading = bool;
			this.$http
				.request({
					url: this.$api.order.refundData,
					method: 'POST',
					data: {
						order_detail_id: id
					}
				})
				.then(async res => {
					this.loading = false;
					if (res.code === 0) {
						let { detail } = res.data;
						this.data = detail;
						this.dataForm.order_detail_id = detail.order_detail_id;
						this.dataForm.refund_price = detail.refund_price;
						this.dataForm.integral_score_price = detail.use_score > 0 ? detail.use_score : detail.integral_deduction_price;
						this.dataForm.refund_total_price = detail.refund_total_price;
						this.dataForm.integral_score_price = this.dataForm.integral_score_price.lastIndexOf('.') > 0 ? this.dataForm.integral_score_price : (this.dataForm.integral_score_price + '.00');
					}
				});
		},
		change(key, value) {
			this.dataForm[key] = value;
		},
		showModal(key) {
			let temp = {};
			switch (key) {
				case 'is_receipt':
					temp.key = key;
					temp.name = '货物状态';
					temp.data = this.gargoStatus;
					break;
				case 'reason':
					temp.key = key;
					temp.name = '退款原因';
					temp.data = this.reasonStatus;
					break;
				case 'refund_type':
					temp.key = key;
					temp.name = '退款原因';
					temp.data = this.refundTypes;
					break;
			}
			this.items = temp;
			this.bottomPopup = true;
		},
		hideModal() {
			this.bottomPopup = false;
		},
		result: function(e) {
			// 图片上传结果
			let temp = JSON.parse(JSON.stringify(this.dataForm));
			temp.pic_list = e.imgArr;
			this.dataForm = temp;
		},
		remove: function(e) {
			//移除图片
			let index = e.index;
		}
	},
	computed: {
		uploadStyle(){
			let style = this.dataForm.pic_list.length > 0 ? "opacity: 1; position: relative" : "opacity: 0; position: absolute";
			return style;
		},
		refundTypes() {
			let temp = [];
			let statusTexts = ['快递'];
			statusTexts.forEach((item, i) => {
				temp.push({
					key: `${i}`,
					value: item
				});
			});
			return temp;
		},
		reasonStatus() {
			if(!this.data){
				return ''
			}
			let temp = [];
			let statusTexts = this.data.refund_reason_list;
			statusTexts.forEach((item, i) => {
				temp.push({
					key: `${i}`,
					value: item
				});
			});
			return temp;
		},
		gargoStatus() {
			let temp = [];
			let statusTexts = ['未收到货', '已收到货'];
			statusTexts.forEach((item, i) => {
				temp.push({
					key: `${i}`,
					value: item
				});
			});
			return temp;
		}
	}
};
</script>

<style lang="scss" scoped>
.app {
	height: 100%;
	background-color: #f7f7f7;

	.container {
		font-size: 11pt;
		color: #333333;

		.image-text,
		.drawers,
		.refund-info,
		.upload {
			background-color: #ffffff;
			margin-bottom: 20rpx;
		}

		.image-text {
			padding: 20rpx 30rpx;
			display: flex;

			.image {
				max-width: 160rpx;
				height: 160rpx;
				margin-right: 20rpx;
			}

			.goods-info {
				color: #333333;

				.goods-name {
					font-size: 10pt;
					margin-bottom: 10rpx;
				}

				.goods-attr-name {
					font-size: 9pt;
					color: #b4b4b4;
				}
			}
		}

		.drawer {
			border-bottom: 2rpx solid #f2f2f2;
			padding: 10rpx 30rpx;

			.select {
				display: flex;
				align-items: center;
				justify-content: space-between;

				.text {
					color: #999999;
					display: flex;
					align-items: center;

					.msg {
						margin-right: 10rpx;

						&.checked {
							color: #333333;
						}
					}

					.iconfont {
						width: 32rpx;
						font-size: 16pt;
					}
				}
			}

			.showText {
				padding: 10rpx 0;
			}
		}

		.refund-info {
			padding: 0 30rpx;

			.price,
			.msg,
			.explain {
				line-height: 60rpx;
			}

			.price {
				display: flex;

				.red {
					margin-left: 20rpx;
					color: var(--textColor);
				}
			}

			.desc {
				margin: 30rpx 0;
				color: #999999;
				font-size: 9pt;
			}

			.explain {
				padding: 0;
				margin: 0;
			}

			.drawer {
				padding: 0;
			}
		}

		.upload {
			padding: 20rpx 30rpx;

			.btn {
				margin-top: 20rpx;
				display: flex;
				position: relative;

				.img {
					width: 180rpx;
					height: 180rpx;
					color: #bfbfbf;
					border: 1rpx dotted #bfbfbf;

					.iconfont {
						font-size: 16pt;
					}
				}
			}
		}

		.explain {
			display: flex;
			align-items: center;
			padding: 20rpx 30rpx;
			margin: 60rpx 0 20rpx;
			background-color: #ffffff;

			.input {
				margin-left: 10rpx;
				flex: 1;
			}
		}
	}

	.select-items {
		min-height: 778rpx;
		color: #010101;
		display: flex;
		flex-direction: column;

		.items {
			flex: 1;
		}

		.items-title {
			text-align: center;
			line-height: 140rpx;
		}

		.item {
			display: flex;
			justify-content: space-between;
			padding: 20rpx 30rpx;

			.iconfont {
				font-size: 16pt;
				color: var(--textColor);

				&.icon-quanquan {
					color: #a1a1a1;
				}
			}
		}
	}

	input {
		font-size: 11pt;
		height: 44rpx;
	}

	.jx-border-bottom {
		border-bottom: 2rpx solid #f2f2f2;
	}

	.btn-text {
		margin: 80rpx 30rpx;
		background-color: #07BEB4;
		text-align: center;
		line-height: 90rpx;
		border-radius: 45rpx;
		color: #ffffff;
		font-size: 9pt;
	}
}
</style>
