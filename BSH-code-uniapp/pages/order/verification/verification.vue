<template>
	<view class="verification-app">
		<block>
			<view class="jx-goods-item">
				<image :src="verificationMessage.detail.goods_info.goods_attr.cover_pic" lazy-load="true" class="jx-goods-img"></image>
				<view class="jx-goods-center">
					<view class="jx-goods-name">{{verificationMessage.detail.goods_info.goods_attr.name}}</view>
				</view>
				<view class="jx-price-right">
					<view class="price">¥{{verificationMessage.detail.total_original_price}}</view>
					<view class="num">x{{verificationMessage.detail.num}}</view>
				</view>
			</view>
		</block>
		<view class="verification-code">
			<view class="ercode">
				<view class="mask" v-if="show">
					<image :src="img_url+'/gou.png'" mode=""></image>
				</view>
				<image :src="verificationMessage.url" mode="" class="ercode-img"></image>
			</view>
			<view class="verification-edite-code">
				核销码：{{verificationMessage.code?verificationMessage.code:"xxxxxxxxxx"}}
			</view>
			<view class="user_unuser">
				<text>{{poup}}</text>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				verificationMessage: {}, //核销信息
				id: '',
				poup:"",
				timer:'',
				show:false,//遮罩显示
				img_url: this.$api.img_url,
			}
		},
		onLoad(options) {
			this.id = options.id
			this.$http.request({
				url: this.$api.moreShop.getexpenseMessage,
				method: 'POST',
				data: {
					id: options.id,
					// #ifdef H5
					route: '/h5/#/mch/newmoreShop/newmoreShop',
					// #endif
					// #ifdef MP-WEIXIN || APP-PLUS
					route:'mch/newmoreShop/newmoreShop',
					// #endif
				},
				showLoading: true
			}).then(res => {
				if (res.code == 0) {
					this.verificationMessage = res.data
				}
			})
			this.timer=setInterval(() => {
				this.getResult()
			},2500);
		},
		
		methods: {
			getResult() {
				if(	this.poup=="已核销")return false
				this.$http.request({
					url: this.$api.moreShop.verificationinfo,
					method: 'POST',
					data: {
						id: this.id,
					},
				}).then(res => {
					if (res.code == 0) {
						let reult = res.data
						if (reult.is_used && reult.is_used == 1) {
							this.poup="已核销"
							this.show=true
						}else{
							this.poup="待核销"
							this.show=false
						}
					}
				})
			}
		},
		onUnload() {
			if(this.timer!=null) {  
			    clearInterval(this.timer);  
			    this.timer = null;  
			}  
		}
	}
</script>

<style scoped lang="scss">
	.verification-app {
		width: 100%;
		height: 100%;
		background: url(../../../plugins/images/verification_back.jpg)no-repeat;
		background-size: cover;
		padding-top: 30rpx;
	}

	.jx-goods-item {
		width: 100%;
		padding: 20rpx 30rpx;
		box-sizing: border-box;
		display: flex;
		justify-content: space-between;
		border: 1rpx solid #999;
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
		}
	}

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

	.jx-price-right {
		text-align: right;
		font-size: 9pt;
		color: #999999;
		line-height: 30rpx;
		padding-top: 20rpx;

		.price {
			margin-bottom: 20rpx;
		}

		.num {
			margin-top: 30rpx;
			font-weight: bold;
			color: #000;
		}
	}

	.verification-code {
		width: 100%;
		overflow: hidden;
		margin: 20rpx 0;
		position: relative;
	}

	.ercode {
		width: 300rpx;
		height: 300rpx;
		margin: 50rpx auto 0;
		position: relative;
	}
	.mask{
		width: 300rpx;
		height: 300rpx;
		position: absolute;
		top: 0;
		left: 0;
		z-index: 999;
		background: rgba(0,0,0,0.4);
	}
	.mask image{
		width: 150rpx;
		height: 150rpx;
		display: block;
		position: absolute;
		left: 0;
		right: 0;
		top: 0;
		bottom: 0;
		margin: auto;
		
	}
	.ercode-img{
		width: 300rpx;
		height: 300rpx;
		display: block;
	}
	.verification-edite-code {
		width: 100%;
		margin: 40rpx 0;
		height: 80rpx;
		text-align: center;
		line-height: 80rpx;
		color:#00675d ;
	}
	.user_unuser{width: 100%;overflow: hidden;text-align: center;font-size: 42rpx;color: #000;font-weight: bold;}
</style>
