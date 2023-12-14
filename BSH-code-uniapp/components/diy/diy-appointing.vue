<template>
	<view class="diy-appointing">
		<view class="goods-list" :class="['goods-list-'+data.listStyle,cListFlex]">
			<view v-for="(item,i) in cList" style="padding: 10rpx;" :style="cItemStyle" :key="i">
				<view class="goods-item" :class="['goods-cover-'+data.goodsCoverProportion,cGoodsFlex]" :style="cGoodsStyle">
					<!-- <view class="goods-pic" :style="cGoodsPicStyle(item.picUrl)"></view> -->
					<view class="goods-pic" :style="{'background-image': item.picUrl,'background-size': data.fill === 1 ? 'cover' : 'contain'}"></view>
					<view v-if="data.showGoodsTag" class="goods-tag" :style="'background-image: url('+data.goodsTagPicUrl+');'"></view>
					<view class="goods" :style="cGoodsInfoStyle">
						<view class="goods-name" :class="data.listStyle===-1?'goods-name-static':''">
							<template v-if="data.showGoodsName">{{item.name}}</template>
						</view>
						<view class="btn-last-bottom">
							<view class="goods-price" :style="cPriceStyle">
								<template v-if="item.price=='0.00' || item.price==0">免费</template>
								<template v-else>￥{{item.price}}</template>
							</view>
							<view v-if="cShowBuyBtn" style="padding: 0 10rpx;height: 24px;">
								<tui-button v-if="data.buyBtnStyle===1" class="buy-btn" type="danger" size="small" style="color: #FFFFFF;">{{data.buyBtnText}}</tui-button>
								<tui-button v-if="data.buyBtnStyle===2" class="buy-btn" type="danger" size="small" :plain="true">{{data.buyBtnText}}</tui-button>
								<tui-button v-if="data.buyBtnStyle===3" class="buy-btn" shape="circle" type="danger" size="small" style="color: #FFFFFF;">{{data.buyBtnText}}</tui-button>
								<tui-button v-if="data.buyBtnStyle===4" class="buy-btn" shape="circle" type="danger" size="small" :plain="true">{{data.buyBtnText}}</tui-button>
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import tuiButton from "@/components/button/button"
	export default {
		components: {
			tuiButton
		},
		props: {
			value: Object,
		},
		data() {
			return {
				data: {}
			}
		},
		created() {
			this.data = JSON.parse(JSON.stringify(this.value))
		},
		methods: {},
		computed: {
			cList() {
				if (!this.data.list || !this.data.list.length) {
					const item = {
						id: 0,
						name: '演示商品名称',
						picUrl: '',
						price: '300.00',
					};
					return [item, item];
				} else {
					return this.data.list;
				}
			},
			cListFlex() {
				if (this.data.listStyle === -1) {
					return 'dir-top';
				} else {
					return 'dir-left';
				}
			},
			cItemStyle() {
				if (this.data.listStyle === 2) {
					return 'width: 50%;';
				} else {
					return 'width: 100%;';
				}
			},
			cGoodsStyle() {
				let style = 'border-radius:5rpx;';
				if (this.data.goodsStyle === 2 || this.data.goodsStyle === 4) {
					style += 'border: 1rpx solid #e2e2e2;';
				}
				return style;
			},
			cGoodsInfoStyle() {
				let style = '';
				if (this.data.listStyle !== -1) {
					style += 'padding:20rpx;';
				}
				if (this.data.goodsStyle === 3 || this.data.goodsStyle === 4) {
					style += 'text-align: center;';
				}
				return style;
			},
			cPriceStyle() {
				let style = '';
				if (this.data.goodsStyle === 3 || this.data.goodsStyle === 4) {
					style += 'text-align: center;width: 100%;';
				}
				return style;
			},
			cGoodsFlex() {
				if (this.data.listStyle === -1) {
					return 'dir-left box-first';
				} else {
					return 'dir-top';
				}
			},
			cShowBuyBtn() {
				return this.data.goodsStyle !== 3 &&
					this.data.goodsStyle !== 4 &&
					this.data.showBuyBtn;
			},
		},
	}
</script>

<style lang="scss" scoped>
	.diy-appointing {

		.goods-list {

			font-size: 11pt;
			flex-wrap: wrap;
			background-color: #fff;
			padding: 10rpx;

			.goods-item {
				position: relative;

				.goods-tag {
					position: absolute;
					top: 0;
					left: 0;
					width: 64rpx;
					height: 64rpx;
					background-position: center;
					background-size: cover;
				}

				.goods-pic {
					width: 100%;
					height: 706rpx;
					background-color: #e2e2e2;
					background-position: center;
					background-size: cover;
					background-repeat: no-repeat;
				}

				.goods-name {
					margin-bottom: 18rpx;
					white-space: nowrap;
					overflow: hidden;
					text-overflow: ellipsis;
				}
			}

			.goods-cover-3-2 {
				.goods-pic {
					height: 470rpx !important;
				}
			}
		}

		.goods-list-2 {
			.goods-pic {
				height: 343rpx !important;
			}
		}

		.goods-list--1 {

			.goods-pic {
				width: 200rpx !important;
				height: 200rpx !important;
				margin-right: 20rpx;
			}

			.goods-item {
				margin-bottom: 20rpx !important;

				&:last-child {
					margin-bottom: 0;
				}
			}
		}

		.goods-name-static {
			height: 130rpx;
			padding: 10rpx 0;
			white-space: normal;
			overflow: hidden;
			text-overflow: ellipsis;
			display: -webkit-box;
			-webkit-line-clamp: 3;
			-webkit-box-orient: vertical;
			word-break: break-all;
		}

		.goods-price {
			color: #bc0100;
			line-height: 48rpx;
		}

		.buy-btn {
			border-color: #bc0100;
			color: #bc0100;
			padding: 0 20rpx;
			height: 48rpx;
			line-height: 50rpx;
			font-size: 28rpx;

			&.is-round {
				border-radius: 24rpx;
			}
		}

		.btn-last-bottom {
			display: flex;
			align-items: flex-end;

			&>* {
				width: 0;
				height: auto;
				flex-grow: 1;
				flex-shrink: 1;
			}

			&>*:last-child {
				width: auto;
				flex-grow: 0;
				flex-shrink: 0;
			}
		}

		.dir-top {
			display: flex;
			flex-direction: column;
		}

		.dir-left {
			display: flex;
			flex-direction: row;
		}

		.box-first {

			.goods {
				width: 0;
				height: auto;
				flex-grow: 1;
				flex-shrink: 1;
			}
		}

	}
</style>
