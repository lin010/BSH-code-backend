<template>
	<view class="canceldetail-app">
		<view class="cancle-code">
			<view class="cancle-code-title">
				请向门店出示二维码进行核销！
			</view>
			<view class="code">
				<view class="mask" v-if="showCode">
					<image :src="img_url+'/gou.png'" mode=""></image>
				</view>
				<image :src="codeDetail.file_path" mode=""></image>
			</view>
			<view class="code-number">
				核销码：{{codeDetail.code}}
			</view>
			<view class="cancle-moreshop" @click="moreShop">
				查看附近门店
			</view>
		</view>
		<view class="canceldetail-message">
			<view class="canceldetail-item">
				<view class="canceldetail-item-title" style="line-height: 150rpx;">产品</view>
				<view class="goods">
					{{detail.order_goods_list[0].goods_info.name}}
				</view>
				<view class="canceldetail-item-name-image" style="margin-top: 15rpx;">
					<image :src="detail.order_goods_list[0].goods_info.pic_url" mode=""></image>
				</view>
			</view>
			<view class="canceldetail-item">
				<view class="canceldetail-item-title">订单号</view>
				<view style="font-size: 30rpx;">{{detail.order_no}}</view>
			</view>
			<view class="canceldetail-item">
				<view class="canceldetail-item-title">核销</view>
				<view style="font-size: 30rpx;">爆品专区</view>
			</view>
			<view class="canceldetail-item">
				<view class="canceldetail-item-title">核销时间</view>
				<view style="font-size: 30rpx;">{{detail.send_at}}</view>
			</view>
			<view class="canceldetail-item">
				<view class="canceldetail-item-title">有效时间</view>
				<view style="color: red;font-size: 30rpx;">永久</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				codeDetail:'',
				showCode:false,
				detail:'',
				timer:'',
				id:''
			};
		},
		onLoad(options) {
			let that = this
			if (options.id) {
				that.id=options.id
				that.getCode(options.id)
				that.getDetail(options.id)
				// that.timer=setInterval(() => {
				// 	that.getResult(options.id)
				// },1500);
			}
		},
		methods: {
			getCode(id) { //获取二维码
				let that=this
				that.$http.request({
					url: that.$api.moreShop.getOrdercode,
					data: {
						id: id,
						// #ifdef H5
						route: '/h5/#/mch/newmoreShop/newmoreShop',
						// #endif
						// #ifdef MP-WEIXIN || APP-PLUS
						route:'mch/newmoreShop/newmoreShop',
						// #endif
					}
				}).then((res) => {
					if (res.code == 0) {
						that.codeDetail = res.data
						that.timer=setInterval(() => {
							that.getResult(that.codeDetail.id)
						},1500);
					} else {
						that.$http.toast(res.msg);
					}
				})
			},
			getDetail(id) { //获取商品信息
				this.$http.request({
					url: this.$api.order.detail,
					method: 'POST',
					data: {
						id
					},
					showLoading: true,
				}).then(res => {
					if (res.code === 0) {
						this.detail=res.data.detail					
					}
				})
			},
			getResult(id) { //轮询核销结果
				if(this.showCode)return false
				this.$http.request({
					url: this.$api.default.getOrdercodestatus,
					method: 'POST',
					data: {
						id:id,
					},
				}).then(res => {
					if (res.code == 0) {
						let reult = res.data
						if (reult.clerk_status && reult.clerk_status == 1) {
							this.showCode=true
						}else{
							this.showCode=false
						}
					}
				})
			},
			moreShop(){
				uni.navigateTo({
					url:'../verification?id='+this.id
				})
			}
		},
		onUnload() {
			if(this.timer!=null) {  
			    clearInterval(this.timer);  
			    this.timer = null;  
			}  
		},
	}
</script>

<style lang="less">
	.canceldetail-app {
		width: 100%;
		overflow: hidden;
		padding-top: 30rpx;
	}

	.cancle-code {
		width: 90%;
		overflow: hidden;
		box-shadow: 0 0 1px rgba(255, 255, 255, 1);
		background: #fff;
		margin: 0 auto;
		border-radius: 20rpx;
	}

	.cancle-code-title {
		width: 100%;
		overflow: hidden;
		font-weight: bold;
		text-align: center;
		color: #000;
		padding-top: 30rpx;
	}

	.code {
		width: 350rpx;
		height: 350rpx;
		margin: 20rpx auto;
		position: relative;
	}

	.code image {
		width: 350rpx;
		height: 350rpx;
		display: block;
	}

	.code-number {
		width: 100%;
		overflow: hidden;
		text-align: center;
		font-weight: bold;
		color: #000;
		margin: 10rpx auto;
		font-size: 30rpx;
	}

	.cancle-moreshop {
		width: 300rpx;
		height: 80rpx;
		background: #FF7104;
		margin: 20rpx auto 30rpx;
		border-radius: 35rpx;
		text-align: center;
		line-height: 80rpx;
		color: #fff;
	}

	.canceldetail-message {
		width: 90%;
		overflow: hidden;
		box-shadow: 0 0 1px rgba(255, 255, 255, 0.8);
		background: #fff;
		margin: 30rpx auto 30rpx;
		border-radius: 20rpx;
		padding: 0 20rpx;
	}

	.canceldetail-item-title {
		color: #000;
	}

	.canceldetail-item {
		width: 100%;
		min-height: 100rpx;
		color: #000;
		display: flex;
		justify-content: space-between;
		border-bottom: 1rpx solid #BBBBBB;
	}

	.canceldetail-item-name {
		width: 300rpx;
		color: #000;
	}

	.canceldetail-item-name-image {
		width: 120rpx;
		height: 120rpx;
	}

	.canceldetail-item-name-image image {
		display: block;
		width: 120rpx;
		height: 120rpx;
	}

	.canceldetail-item:nth-of-type(1) {
		height: 150rpx;
	}

	.canceldetail-item:nth-of-type(2),
	.canceldetail-item:nth-of-type(3),
	.canceldetail-item:nth-of-type(4),
	.canceldetail-item:nth-of-type(5),
		{
		line-height: 100rpx;
	}

	.canceldetail-item:nth-of-type(5) {
		margin-bottom: 20rpx;
	}

	.goods {
		width: 300rpx;
		height: 150rpx;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 3;
		-webkit-box-orient: vertical;
		padding-top: 8rpx;
	}

	.nocancel {
		width: 90%;
		height: 90rpx;
		margin: 30rpx auto 50rpx;
		text-align: center;
		background: rgb(7, 190, 180);
		color: #fff;
		line-height: 90rpx;
		border-radius: 30rpx;
	}

	.mask {
		width: 350rpx;
		height: 350rpx;
		position: absolute;
		top: 0;
		left: 0;
		z-index: 999;
		background: rgba(0, 0, 0, 0.6);
	}

	.mask image {
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
</style>
