<template>
	<view class="app">
		<view class="box">
			<image :src="bg_url" class="jx-my-bg"></image>
			<view class="jx-room" v-if="detail">
				<view class="jx-order-status">
					<view class="icon iconfont icon-tuikuanshouhou"></view>
					<view class="text-container">
						<!-- <view class="text">{{detail.type == 0 || detail.is_receipt == 1 ? '售后退款' : '售后退货退款'}}</view> -->
						<view class="text">售后申请</view>
						<view class="msg">{{detail.status_text}}</view>
						<!-- <view class="withdraw">撤回申请</view> -->
					</view>
					<view class="btn">
						<!-- <jx-button type="white" width="148rpx" height="56rpx" :size="26" shape="circle" style="color: #BC0100 !important;">删除订单</jx-button> -->
					</view>
				</view>
				
				<view class="jx-order-item">
					<view class="jx-goods-list jx-radius">
						<block v-for="(item,index) in detail.goods_list" :key="index">
							<view class="jx-goods-item">
								<image :src="item.goods_info.pic_url" class="jx-goods-img"></image>
								<view class="jx-goods-center">
									<view class="jx-goods-name">{{item.goods_info.name}}</view>
									<view class="jx-goods-attr">{{item.goods_info.attr_list[0].attr_name}}</view>
								</view>
								<view class="jx-price-right">
									<view class="price">¥{{item.goods_info.total_original_price}}</view>
									<view class="num">x{{item.goods_info.num}}</view>
								</view>
							</view>
						</block>
					</view>
					
					<block v-if="detail.refund_address!=undefined && detail.refund_address.length==0">
					</block>
					<block v-else>
						<view class="jx-order-user jx-radius">
							<view class="jx-address view">
								<view class="jx-flex-box">
									<view class="jx-icon-img iconfont icon-fahuo"></view>
									<view class="jx-addr">
										<view class="jx-addr-userinfo">{{detail.refund_address.name || "VITA"}}<text class="jx-addr-tel">{{detail.refund_address.mobile || "12345678"}}</text></view>
										<view class="jx-addr-text">{{detail.refund_address.address || "广州省"}}</view>
									</view>
								</view>
							</view>
						</view>
					</block>
					
					<view v-if="is_show" class="show-form flex flex-col flex-x-between">
						<view class="flex">
							<view class="name">快递公司</view>
							<view>{{express}}</view>
						</view>
						<view class="flex">
							<view class="name">快递单号</view>
							<view>{{express_no}}</view>
						</view>
					</view>
					
					<block v-else>
						<view class="form" v-if="detail.is_send == 0 && detail.order_detail.refund_status == 12">
							<view class="title">请填写物流信息（未填写物流信息，会影响你的退款进度）</view>
							<view class="form-detail">
								<picker @change="bindPickerChange" :value="index" :range="express_list">
									<view class="item flex">
										快递公司：
										<view class="courier-company">
											<text v-if="express == ''" style="color: #989696;">请选择您的快递公司</text>
											<text v-else>{{express_list[index]}}</text>
											<view class="iconfont icon-right icon"></view>
										</view>
									</view>
								</picker>
								
								<view class="item flex">
									快递单号：
									<view class="courier-company">
										<input type="text" v-model="express_no" style="font-size: 24rpx;" placeholder="请输入您的快递单号">
									</view>
								</view>
								
								<view class="completed-btn" @tap='completed' :style="{'color':textColor,'border-color':textColor}">填写完成</view>
							</view>
						</view>
					</block>

					<view class="jx-order-info jx-radius">
						<view class="jx-info-flex jx-size24">
							<view class="jx-info-text">退款原因</view>
							<view class="jx-text">{{detail.reason || '无'}}</view>
						</view>
						<view class="jx-info-flex jx-size24">
							<view class="jx-info-text">退款金额</view>
							<view class="jx-text">¥{{detail.refund_price}}</view>
						</view>
						<view class="jx-info-flex jx-size24">
							<view class="jx-info-text">申请件数</view>
							<view class="jx-text">{{detail.goods_list[0].goods_info.num}}</view>
						</view>
						<view class="jx-info-flex jx-size24">
							<view class="jx-info-text">申请时间</view>
							<view class="jx-text">{{detail.created_at}}</view>
						</view>
						<view class="jx-info-flex jx-size24 last">
							<view class="jx-info-text">退款编号</view>
							<view class="jx-text">{{detail.order_no}}</view>
						</view>
					</view>
				</view>
				<!-- <view class="fixed">
					<view class="jx-btn-mr">
						<jx-button type="danger" :plain="true" width="148rpx" height="56rpx" :size="26" shape="circle" style="color: #BC0100 !important;">删除订单</jx-button>
					</view>
				</view> -->
			</view>
			
			<!-- <view class="footer flex flex-y-center">
				<view class="footer1 footer-common">撤销申请</view>
			</view> -->
		</view>
		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	import jxButton from "@/components/extend/button/button"
	export default {
		components: {
			jxButton
		},
		data() {
			return {
				img_url: this.$api.img_url,
				bg_url:'',
				textColor:'',
				detail: null,
				loading: false,
				express_list: [],
				index: -1,
				express_no:'',
				express:'',
				is_show:false,
			}
		},
		onLoad(options) {
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.getExpressList();

			if (options.id) {
				this.getDetail(options.id, true);
			}
		},
		methods: {
			completed(){ //填写物流订单号
				this.$http.request({
					url:this.$api.order.refundSend,
					method:'post',
					data:{
						id:this.detail.id,
						express: this.express,
						express_no: this.express_no
					}
				}).then(res => {
					if(res.code == 0){
						this.is_show = true;
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			bindPickerChange(e) {
				this.index = e.target.value;
				this.express = this.express_list[this.index];
			},
			getExpressList() {
				this.$http.request({
					url: this.$api.order.express_list,
					method: 'get'
				}).then(res => {
					if (res.code == 0) {
						res.data.list.forEach(item => {
							this.express_list.push(item.name)
						})
					} else {
						this.$http.toast(res.msg);
					}
				})
			},
			getDetail(id, bool) {
				this.loading = bool;
				this.$http.request({
					url: this.$api.order.refundDetail,
					data: {
						order_detail_id: id
					},
				}).then(res => {
					this.loading = false;
					if (res.code === 0) {
						let {
							detail
						} = res.data;
						this.detail = detail;
						
						if(this.detail.express == ''){
							this.is_show = false;
						}else{
							this.is_show = true;
							this.express = this.detail.express;
							this.express_no = this.detail.express_no;
						}
					}else{
						this.$http.toast(res.msg);
						setTimeout(()=>{
							uni.redirectTo();
						},1000)
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.app {

		background-color: #F7F7F7;
		height: 100%;

		.box {
			width: 100%;
			position: relative;

			.jx-my-bg {
				width: 100%;
				height: 340rpx;
				display: block;
			}

			.jx-room {
				position: absolute;
				height: 64px;
				top: 80rpx;
				padding: 0 15px;
				width: 100%;

				.jx-order-status {

					display: flex;
					color: #FFFFFF;
					margin-bottom: 40rpx;
					align-items: center;

					.icon {
						margin-left: 20rpx;
						font-size: 34pt;
						line-height: 100rpx;
					}

					.text-container {
						margin-left: 20rpx;
						flex: 1;
						position: relative;

						.text {
							font-size: 13pt;
							line-height: 60rpx;
						}

						.msg {
							font-size: 9pt;
							margin-top: 10rpx;
						}

						.withdraw {
							position: absolute;
							right: 0;
							top: 50%;
							transform: translateY(-50%);
							background: #FFFFFF;
							border-radius: 46rpx;
							color: #BC0100;
							padding: 10rpx 22rpx;
							font-size: 28rpx;
							letter-spacing: 1px;
						}
					}
				}

				.jx-order-user {
					background-color: #ffffff;

					.view {
						padding: 26rpx 30rpx;
						border-bottom: 1rpx solid #F2F2F2;

						&:last-child {
							border-bottom: 0;
						}
					}

					.jx-flex-box {
						width: 100%;
						display: flex;
						align-items: center;

						.jx-icon-img {
							width: 72rpx;
							line-height: 72rpx;
							font-size: 72rpx;
							flex-shrink: 0;
						}

						.jx-addr {
							display: flex;
							-webkit-box-orient: vertical;
							-webkit-box-direction: normal;
							flex-direction: column;
							-webkit-box-pack: center;
							justify-content: center;
							padding-left: 10px;
							box-sizing: border-box;

							.jx-addr-userinfo {
								font-size: 11pt;
								line-height: 15px;
								font-weight: bold;

								.jx-addr-tel {
									padding-left: 20px;
								}
							}

							.jx-addr-text {
								font-size: 9pt;
								line-height: 15px;
								padding-top: 20rpx;
							}
						}
					}
				}

				.jx-order-item {
					margin-top: 20rpx;
					border-radius: 10rpx;
					overflow: hidden;
					padding-bottom: 120rpx;

					&>uni-view {
						margin-bottom: 20rpx;
					}

					.jx-goods-list {
						background-color: #FFFFFF;

						.jx-goods-item {
							width: 100%;
							padding: 20rpx 30rpx;
							box-sizing: border-box;
							display: flex;
							justify-content: space-between;

							.jx-goods-img {
								width: 180rpx;
								height: 180rpx;
								display: block;
								flex-shrink: 0;
							}

							.jx-goods-center {
								flex: 1;
								padding: 20rpx;
								box-sizing: border-box;

								.jx-goods-name {
									max-width: 310rpx;
									word-break: break-all;
									overflow: hidden;
									text-overflow: ellipsis;
									display: -webkit-box;
									-webkit-box-orient: vertical;
									-webkit-line-clamp: 2;
									font-size: 9pt;
									line-height: 32rpx;
								}

								.jx-goods-attr {
									font-size: 9pt;
									color: #888888;
									line-height: 32rpx;
									padding-top: 36rpx;
									word-break: break-all;
									overflow: hidden;
									text-overflow: ellipsis;
									display: -webkit-box;
									-webkit-box-orient: vertical;
									-webkit-line-clamp: 2;
								}
							}

							.jx-price-right {
								text-align: right;
								font-size: 9pt;
								color: #999999;
								line-height: 30rpx;
								padding-top: 20rpx;

								.price {
									margin-bottom: 20rpx;
									font-size: 10pt;
								}

								.num {
									margin-bottom: 20rpx;
									font-weight: bold;
								}
							}
						}
					}

					.jx-price {
						color: #333333;
					}

					.jx-order-info {
						background-color: #FFFFFF;
						padding: 30rpx;

						.jx-info-flex {
							display: flex;
							align-items: center;
						}

						.jx-info-text {
							margin-right: 30rpx;
						}

						.jx-text {
							color: #333333;
							flex: 1;
							font-size: 10pt;
						}
					}
				}
			}
		}

		.fixed {
			width: 100%;
			height: 98rpx;
			background: #FFFFFF;
			position: fixed;
			left: 0;
			bottom: 0;
			display: flex;
			align-items: center;
			justify-content: flex-end;
			font-size: 9pt;
			box-shadow: 0 0 1px rgba(242, 242, 242, 1);
			padding-bottom: env(safe-area-inset-bottom);
			z-index: 999;
		}

		.jx-btn-mr {
			margin-right: 30rpx;
		}

		.jx-radius {
			border-radius: 16rpx;
		}

		.jx-size24 {
			padding-bottom: 30rpx;
			font-size: 9pt;
			line-height: 24rpx;
			color: #888;

			&.last {
				padding-bottom: 0;
			}
		}
	}

	.form {
		background: #FFFFFF;
		border-radius: 16rpx;
		padding: 30rpx 30rpx 0;

		.title {
			font-size: 28rpx;
			color: #000000;
		}
		.form-detail{
			padding: 36rpx;
			font-size: 24rpx;
			
			.item{
				color: #000000;
				flex: 1;
				margin-bottom: 20rpx;
			}
			.courier-company{
				border-bottom: 1px solid #CCCCCC;
				flex: 1;
				position: relative;
				padding-bottom: 10rpx;
				
				.icon{
					position: absolute;
					right: 0;
					top: 0;
				}
			}
			
			.completed-btn{
				font-size: 26rpx;
				color: #BC0100;
				border: 1px solid #BC0100;
				width: 242rpx;
				height: 58rpx;
				border-radius: 40rpx;
				text-align: center;
				line-height: 56rpx;
				margin: 40rpx auto 0;
				letter-spacing: 1px;
			}
		}
	}
	
	.show-form{
		height: 158rpx;
		background: #FFFFFF;
		border-radius: 16rpx;
		padding: 34rpx 30rpx;
		font-size: 24rpx;
		color: #000000;
		
		.name{
			margin-right: 30rpx;
			color: #949494;
		}
	}
	
	.footer{
		position: fixed;
		bottom: 0;
		background: #FFFFFF;
		width: 100%;
		height: 100rpx;
		justify-content: flex-end;
		padding-right: 40rpx;
		box-sizing: border-box;
		box-shadow: 0px 0px 6rpx 0px #eeeeee;
		
		.footer-common{
			width: 182rpx;
			height: 58rpx;
			border-radius: 30rpx;
			text-align: center;
			line-height: 56rpx;
			font-size: 26rpx;
			box-sizing: border-box;
		}
		.footer1{
			border: 1px solid #999999;
			color: #999999;
			margin-right: 20rpx;
		}
		.footer2{
			border: 1px solid #BC0200;
			color: #BC0200;
		}
	}
</style>
