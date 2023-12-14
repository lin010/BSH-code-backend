<template>
	<view class="root">
		<scroll-view v-if="couponStyle == 1" scroll-x="true" class="scroll-view_H">
			<view v-for="(item,index) in couponData" :key='index' class="scroll-view-item_H" :style="{'background': 'url('+picUrl(item.receive)+')'}">
				<view class="flex">
					<navigator url="">
						<view class="left">
							<view class="price">&yen;{{item.price}}</view>
							<view class="condition">{{item.condition}}</view>
						</view>
					</navigator>
					<navigator url="">
						<view class="right" v-if="item.receive == 1">已领取</view>
						<view class="right" v-else>未领取</view>
					</navigator>
				</view>
			</view>
		</scroll-view>

		<view class="couponBox" v-if="couponStyle == 2">
			<view class="coupon_title flex flex-y-bottom">
				<view class="coupon_title_name">优惠券</view>
				<view class="coupon_title_center" @click="navTo">领券中心</view>
			</view>
			<scroll-view scroll-x="true" class="scroll-view_H">
				<view :style="{'background': 'url('+picUrl(item.receive)+')100% 100%'}" class="scroll-view-item_H" v-for="(item,index) in couponData" :key='index'>
					<view class="flex">
						<navigator url="">
							<view class="left">
								<view class="price">&yen;{{item.price}}</view>
								<view class="condition">{{item.condition}}</view>
							</view>
						</navigator>
						<navigator url="">
							<view class="right" v-if="item.receive == 1" @click.stop="receive(index,item.receive)">已领取</view>
							<view class="right" v-else @click.stop="receive(index,item.receive)">未领取</view>
						</navigator>
					</view>
				</view>
			</scroll-view>
		</view>

		<view v-if="couponStyle == 3">
			<view class="couponStyle3 flex" :style="{'background':'url('+backgroundUrl(item.receive)+') no-repeat 100% 100%'}"
			 v-for="(item,index) in couponData" :key='index'>
				<view class="coupon-left">
					<view class="shopName">{{item.shopName}}></view>
					<view class="coupon_product_list flex">
						<view class="coupon_product_item" v-for="(items,indexs) in item.productList" :key='indexs'>
							<image class="coupon_product_img" :src="items.pic_url" mode=""></image>
							<view class="coupon_product_name">&yen;{{items.pro_price}}</view>
						</view>
					</view>

				</view>
				<view class="coupon-right flex flex-col">
					<view class="coupon_price" :class="item.receive?'coupon_price_received':''"><text class="coupon_price_symbol coupon_price_symbol_received">&yen;</text>{{item.coupon_price}}</view>
					<view class="coupon_condition" :class="item.receive?'coupon_condition_received':''">{{item.coupon_condition}}</view>
					<view class="coupon_btn" :class="item.receive?'coupon_btn_received':''">
						<text v-if="!item.receive" @click="getCoupon(index,item.receive)">立即领取</text>
						<text v-else @click="getCoupon(index,item.receive)">去使用</text>
					</view>
				</view>
			</view>
		</view>

		<view v-if="couponStyle == 4">
			<view class="coupon-item" :style="{background: 'url('+couponImg+')'}" v-for="(item,index) in couponData">
				<view class="coupon-item-right">
					<view class="coupon-item-price">
						<view v-if="item.type == 2">
							&yen;<text class="coupon-item-price-int">{{sub_price_int(item.sub_price)}}</text>.{{sub_price_float(item.sub_price)}}
						</view>
						<view v-else>
							<text class="coupon-item-price-int">{{(item.discount)*10}}</text>折
						</view>
					</view>
					<view class="coupon-item-condition">
						买满{{item.min_price}}可用
					</view>
				</view>
				<view class="coupon-item-left">
					<view class="coupon-item-name">{{item.name}}</view>
					<view class="coupon-item-time">
						<view v-if="item.expire_type == 2">{{item.begin_at}}~{{item.end_at}}</view>
						<view v-else>还剩{{item.expire_day}}天过期</view>
						<view class="coupon-item-receive" v-if="item.is_receive == 0" @tap="receive(index,item.id)" :style="{background:textColor}">领取</view>
						<view class="coupon-item-receive coupon-item-received" v-else>已领取</view>
					</view>
					<!-- <view class="coupon-item-sub iconfont icon-yilingqu" v-if="item.is_receive != 0"></view> -->
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name: "coupon",
		props: {
			couponStyle: {
				type: Number,
				default: 2
			},
			couponData: {
				type: Array,
				default: () => {}
			}
		},
		data() {
			return {
				pic_url1: 'http://store.hzstorm.com/web/statics/images/home-page/icon-coupon-index.png',
				pic_url2: 'http://store.hzstorm.com/web/statics/images/home-page/icon-coupon-no.png',
				sub_price: '125.23',
				img_url: this.$api.img_url,
				couponImg:'https://jx.shuzixiangdao.com/web/static/couponBg.png',	//默认背景图
				textColor: '#bc0100',
			}
		},
		mounted() {
			let couponImg = this.globalSet('couponImg');
			couponImg?this.couponImg = couponImg:'';
			this.textColor = this.globalSet('textCol');
		},
		// onLoad() {
		// 	this.sub_price = a;
			
		// },
		computed: {
			sub_price_int() {
				return function(val) {
					var arr = val.split('.');
					return arr[0];
				}
			},
			sub_price_float() {
				return function(val) {
					var arr = val.split('.');
					if (arr.length != 2) {
						return '00'
					} else {
						return arr[1];
					}
				}
			},
		},
		methods: {
			//领取优惠券
			getCoupon(index, n) {
				this.$emit('getCoupon', {
					'index': index,
					'receive': n
				})
			},
			picUrl(num) {
				if (num == 1) {
					return this.pic_url2;
				} else {
					return this.pic_url1;
				}
			},
			backgroundUrl(val) {
				if (val) {
					return '../../static/images/search/couponReceived.png';
				} else {
					return '../../static/images/search/coupon.png';
				}
			},
			navTo() {
				this.$emit('click', {});
			},
			receive(index, id) {
				this.$emit('receiveClick', {
					index,
					id
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.scroll-view_H {
		white-space: nowrap;
	}

	.scroll-view-item_H {
		display: inline-block;
		width: 256rpx;
		height: 130rpx;
		background-size: cover !important;
		padding: 0px 12rpx;
		box-sizing: border-box;
		margin-right: 6rpx;

		.flex {
			height: 100%;
		}
	}

	.left {
		width: 176rpx;
		color: white;
		text-align: center;

		.price {
			font-size: 38rpx;
			height: 84rpx;
			line-height: 84rpx;
		}

		.condition {
			font-size: 24rpx;
		}
	}

	.right {
		width: 56rpx;
		height: 100%;
		writing-mode: tb-rl;
		text-align: center;
		color: white;
		font-size: 24rpx;
		letter-spacing: 8rpx;
	}

	.couponBox {
		background: #FFFFFF;
		padding: 30rpx 16rpx;
		box-sizing: border-box;
	}

	.couponStyle3 {
		background-size: 100% 100%;
		height: 240rpx;
		padding: 18rpx 16rpx 0px;
		box-sizing: border-box;
		width: 94%;
		margin: 0 auto 20rpx;
		border-radius: 20rpx;
		justify-content: space-between;
	}

	.coupon-left {
		width: 480rpx;
	}

	.coupon_title {
		padding-bottom: 20rpx;
		justify-content: space-between;
	}

	.coupon_title_name {
		font-weight: 600;
		font-size: 34rpx;
		letter-spacing: 2rpx;
	}

	.coupon_title_center {
		font-size: 28rpx;
		color: #8c8c8c;
	}

	.coupon_product_item {
		margin-right: 24rpx;
	}

	.shopName {
		margin-bottom: 10rpx;
	}

	.coupon_product_img {
		width: 140rpx;
		height: 130rpx;
		border-radius: 10rpx;
		display: block;
	}

	.coupon_product_name {
		font-size: 26rpx;
		text-align: center;
		color: #939393;
	}

	.coupon-right {
		width: 178rpx;
		justify-content: space-between;
		padding: 20rpx 0px 32rpx;
	}

	.coupon_price {
		text-align: center;
		font-size: 38rpx;
		color: #ff333a;
		font-weight: 700;
	}

	.coupon_price_symbol {
		font-size: 34rpx;
		font-weight: 500;
		margin-right: 4rpx;
	}

	.coupon_condition {
		font-size: 26rpx;
		text-align: center;
		color: #ff333a;
	}

	.coupon_btn {
		width: 156rpx;
		margin: 0 auto;
		font-size: 24rpx;
		background: #ff333a;
		color: #FFFFFF;
		text-align: center;
		letter-spacing: 4rpx;
		font-weight: 300;
		padding: 6rpx 0;
		border-radius: 26rpx;
	}

	.coupon_price_received,
	.coupon_symbol_received,
	.coupon_condition_received {
		color: #b0b0b0;
	}

	.coupon_btn_received {
		color: #FFFFFF;
		background: #b0b0b0;
	}

	.coupon-item {
		background-size: cover !important;
		width: 92%;
		margin: auto;
		margin-bottom: 20rpx;
		height: 190rpx;
		display: flex;
	}

	.coupon-item-right {
		color: #FFFFFF;
		width: 240rpx;
		height: 100%;
		display: flex;
		align-items: center;
		flex-direction: column;
		justify-content: center;
	}

	.coupon-item-price {
		font-weight: 700;
		margin: 0 0 8rpx;
	}

	.coupon-item-price-int {
		font-size: 17pt;
		margin: 0 2rpx;
	}

	.coupon-item-condition {
		font-size: 9pt;
		font-weight: 600;
	}

	.coupon-item-left {
		flex: 1;
		color: #000000;
		display: flex;
		justify-content: space-around;
		flex-direction: column;
		padding: 6rpx 20rpx;
		position: relative;
	}

	.coupon-item-name {
		font-size: 10pt;
	}

	.coupon-item-time {
		font-size: 9pt;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.coupon-item-receive {
		background: #BC0100;
		border-radius: 30rpx;
		// padding: 6rpx 26rpx;
		line-height: 40rpx;
		min-width: 100rpx;
		text-align: center;
		color: #FFFFFF;
		font-size: 8pt;
		letter-spacing: 2rpx;
	}

	.coupon-item-received {
		background: transparent;
		border: 2rpx solid #BC0100;
		color: #BC0100;
	}

	.coupon-item-sub {
		position: absolute;
		color: #BC0100;
		top: 0;
		right: 0;
		font-size: 60rpx;
		line-height: 60rpx;
	}
</style>

<!-- 样式三的数据 -->
<!-- couponData:[
	{
		receive:true, //true为已领取 false为未领取
		shopName:'华为旗舰店',
		productList:[
			{
				pic_url:'http://img.alicdn.com/imgextra/i1/742634735/TB2.peMXwCN.eBjSZFoXXXj0FXa_!!742634735.jpg',
				pro_price:'50'
			},
			{
				pic_url:'http://img.alicdn.com/imgextra/i4/2142747419/O1CN01FWniwm24fwucTTgIY_!!2142747419.jpg',
				pro_price:'320'
			},
			{
				pic_url:'http://store.hzstorm.com/web/uploads/image/store_1/07cd6e2851b960a2738835d2effb4f4c5c37f533.jpg',
				pro_price:'96'
			},
		],
		coupon_price:'100',
		coupon_condition:'满9000可用'
	},
	{
		receive:false, //true为已领取 false为未领取
		shopName:'oppo旗舰店',
		productList:[
			{
				pic_url:'http://img.alicdn.com/imgextra/i1/742634735/TB2.peMXwCN.eBjSZFoXXXj0FXa_!!742634735.jpg',
				pro_price:'50'
			},
			{
				pic_url:'http://img.alicdn.com/imgextra/i4/2142747419/O1CN01FWniwm24fwucTTgIY_!!2142747419.jpg',
				pro_price:'320'
			},
			{
				pic_url:'http://store.hzstorm.com/web/uploads/image/store_1/07cd6e2851b960a2738835d2effb4f4c5c37f533.jpg',
				pro_price:'96'
			},
		],
		coupon_price:'50',
		coupon_condition:'满900可用'
	},
], -->
