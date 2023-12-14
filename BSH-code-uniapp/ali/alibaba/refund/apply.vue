<template>
	<view class="app">
		<view class="container" v-if="data">
			
			<view class="image-text">
				<image class="image" :src="data.goods_info.pic_url" mode="aspectFit"></image>
				<view class="goods-info">
					<view class="goods-name">{{ data.goods_info.name }}</view>
					<view class="goods-attr-name">{{data.goods_info.sku_labels}}</view>
				</view>
			</view>
			
			<view class="drawers">
				<view class="drawer">
					<view class="select">
						<view class="name">货物状态</view>
						<view class="text">{{aliStatusText}}</view>
					</view>
				</view>
				<view class="drawer">
					<view class="select">
						<view class="name">退款原因</view>
						<view v-if="data.refund_status == 'none'" class="text" @click="showModal('reason')">
							<view class="msg" :class="dataForm.reason > -1 ? 'checked' : ''">
								{{ reasonStatus[dataForm.reason] ? reasonStatus[dataForm.reason].value : '请选择' }}
							</view>
							<view class="iconfont icon-xiala right"></view>
						</view>
						<view v-else class="text">{{data.refund_data.description}}</view>
					</view>
				</view>
				<view class="drawer"  v-if="data.is_refund == 1 || data.refund_status != 'none'">
					<view class="select">
						<view class="name">退款状态</view>
						<view class="text" v-if="data.is_refund == 1 " style="color:green">{{refundStatusText}}</view>
						<view class="text" v-else-if="data.refund_status == 'apply'" style="color:red">{{refundStatusText}}</view>
						<view class="text" v-else-if="data.refund_status == 'agree'" style="color:#00428a">{{refundStatusText}}</view>
						<view class="text" v-else>{{refundStatusText}}</view>
					</view>
				</view>
			</view>
			
			<view class="refund-info">
				<view class="price">
					<view>退红包:</view>
					<view class="red" :style="{'--textColor':textColor}">¥{{ dataForm.refund_shopping_voucher_num }}</view>
				</view>
			</view>
			
			<view class="explain" style="">
				<view>退款说明:</view>
				<view class="input">
					<input v-if="data.refund_status == 'none'" type="text" v-model="dataForm.remark" maxlength="100" placeholder="选填" />
					<text v-else>{{data.refund_data.remark}}</text>
				</view>
			</view>
			
			<!-- 退款状态 -->
			<template v-if="data.is_refund == 0 && data.refund_status=='none'">
				<view class="btn-text submit" :style="{'background':'#07BEB4'}" @click="dataFormSubmit">提交</view>
			</template>
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
			order_detail_id: 0,
			data: '',
			serverUrl: this.$api.default.upload,
			images: [],
			bottomPopup: false,
			items: null,
			dataForm: {
				order_detail_id: 0,
				ali_status: '',
				is_receipt: -1,
				reason: -1,
				refund_shopping_voucher_num: '',
				refund_type: -1,
				remark: '',
				pic_list: [],
				textColor:''
			},
			loading: false
		};
	},
	onLoad(options) {
		this.textColor = this.globalSet('textCol');
		if (options.id) {
			this.order_detail_id = options.id;
			this.getDetail(options.id, true);
		}
		if(options.ali_status){
			this.dataForm.ali_status = options.ali_status;
		}
	},
	methods: {
		
		dataFormSubmit() {
			let temp = JSON.parse(JSON.stringify(this.dataForm));
			// temp.reason 修改数字为 退款原因数组对应数据
			if(isEmpty(this.reasonStatus[temp.reason])){
				return this.$http.toast('未选择退款原因');
			}
			temp['order_detail_id'] = this.order_detail_id;
			temp.reason = this.reasonStatus[temp.reason].value;
			this.$http
				.request({
					url: this.$api.taolijin.refundApply,
					method: 'POST',
					data: temp,
					showLoading: true
				})
				.then(res => {
					this.$http.toast(res.msg);
					/* if (res.code == 0) {
						setTimeout(() => {
							uni.redirectTo({
								url: './success'
							});
						}, 1000);
					} */
					this.getDetail(this.order_detail_id, true);
				});
		},
		getDetail(id, bool) {
			this.loading = bool;
			this.$http.request({
				url: this.$api.taolijin.refundData,
				method: 'POST',
				data: {
					order_detail_id: id
				}
			}).then(async res => {
				this.loading = false;
				if (res.code === 0) {
					let { detail } = res.data;
					this.data = detail;
					this.dataForm.order_detail_id = detail.order_detail_id;
					this.dataForm.refund_shopping_voucher_num = detail.refund_shopping_voucher_num;
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
	},
	computed: {
		refundStatusText(){
			let statusText = '';
			if(this.data.is_refund == 1){
				statusText = '已退款';
			}else{
				if(this.data.refund_status == 'apply'){
					statusText = '申请中';
				}else if(this.data.refund_status == 'refused'){
					statusText = '拒绝退款';
				}else if(this.data.refund_status == 'agree'){
					statusText = '已同意，待打款';
				}else if(this.data.refund_status == 'finished'){
					statusText = '已完成';
				}
			}
			return statusText;
		},
		aliStatusText(){
			let statusText = '', allStatus = {};
			allStatus = {
				//waitbuyerpay: '未付款',
				waitbuyerpay: '异常',
				waitsellersend: '待发货',
				waitbuyerreceive: '待收货',
				confirm_goods: '已收货',
				success: '交易成功',
				cancel: '已取消',
				terminated: '交易终止'
			};
			return typeof allStatus[this.dataForm.ali_status] != "undefined" ? allStatus[this.dataForm.ali_status] : '';
		},
		reasonText(){
			let text = '请选择';
			if(this.dataForm.reason > -1){
				let i, temp = [];
				for(i=0; i < this.data.refund_reason_list.length; i++){
					if(this.data.refund_reason_list[i].id == this.dataForm.reason){
						text = this.data.refund_reason_list[i].name;
						break;
					}
				}
			}
			return text;
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
