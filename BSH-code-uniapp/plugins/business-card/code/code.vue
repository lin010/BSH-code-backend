<template>
	<view class="app">
		<view class="goods-qrcode-modal">
			<view class="goods-qrcode-body flex-col">
				<view class="flex-grow-1" style="position: relative;">
					<view style="width: 100%;height: 100%;">
						<!-- #ifdef H5 -->
						<view class="goods-qrcode-box">
							<image :src="poster_url" class="goods-qrcode" mode="widthFix"></image>
						</view>
						<!-- #endif -->
						
						<!-- #ifdef MP-WEIXIN -->
						<view class="goods-qrcode-box">
							<image :src="poster_url" class="goods-qrcode" mode="widthFix" @longpress="saveImage(poster_url)"></image>
						</view>
						<!-- #endif -->
						
						<!-- #ifdef APP-PLUS -->
						<view class="goods-qrcode-box">
							<image :src="poster_url" class="goods-qrcode" mode="widthFix" @longpress="appSaveImg(poster_url)"></image>
						</view>
						<!-- #endif -->
						
					</view>
				</view>
				<view class="flex-grow-0 flex-col flex-y-center" style="padding: 30rpx 60rpx">
					<view>长按图片保存至本地</view>
				</view>
			</view>
		</view>
		<!--底部选择层-->
		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				poster_url: '',
				loading: false
			}
		},
		onLoad() {
			this.getData();
		},
		methods: {
			saveImage(url) { //保存图片
				var that = this;
				uni.authorize({
					/* 这个就是保存相册的 */
					scope: 'scope.writePhotosAlbum',
					success() {
						/* 保存图片方法 */
						that.saveImg(url);
					},
					complete(res) {
						/* 这里判断一下如果没有授权重新打开设置选项 */
						uni.getSetting({
							success(res) {
								if (!res.authSetting['scope.writePhotosAlbum']) {
									/* 打开设置的方法 */
									that.opensit();
								}
							}
						});
					}
				});
			},
			opensit() {
				uni.showModal({
					content: '由于您还没有允许保存图片到您相册里,请点击确定去允许授权',
					success: function(res) {
						if (res.confirm) {
							/* 这个就是打开设置的API*/
							uni.openSetting({
								success(res) {
									console.log(res.authSetting);
								}
							});
						} else if (res.cancel) {
							uni.showModal({
								cancelText: '依然取消',
								confirmText: '重新授权',
								content: '很遗憾你点击了取消，请慎重考虑',
								success: function(res) {
									if (res.confirm) {
										uni.openSetting({
											success(res) {
												console.log(res.authSetting);
											}
										});
									} else if (res.cancel) {
										console.log('用户不授权');
									}
								}
							});
						}
					}
				});
			},
			saveImg(url) {
				var that = this;
				/* 获取图片信息 */
				uni.getImageInfo({
					src: url,
					success: function(image) {
						/* 保存图片到相册 */
						uni.saveImageToPhotosAlbum({
							filePath: image.path,
							success: function() {
								that.$http.toast('保存成功');
								console.log('save success');
							},
							fail(res) {
								that.$http.toast('保存失败，请稍后重试');
								console.log(res);
							}
						});
					}
				});
			},
			appSaveImg(url){
				let that = this;
				/* 保存图片到相册 */
				uni.saveImageToPhotosAlbum({
					filePath: url,
					success: function() {
						that.$http.toast('保存成功');
					},
					fail(res){
						that.$http.toast('保存失败,请稍后重试');
					}
				});
			},
			getData(){
				this.loading = true;
				this.$http.request({
					url: this.$api.plugin.business.poster,
					method: 'POST'
				}).then(res => {
					if(res.code == 0){
						this.poster_url = res.data.pic_url;
						setTimeout(() => {
							this.loading = false;
						},1000)
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.app {
		height: 100%;
		background-color: #FFFFFF;
	}

	.goods-qrcode-modal {
		width: 100%;
		height: 100%;
	}

	.goods-qrcode-body {
		background: #ffffff;
		border-radius: 10rpx;
		padding: 40rpx;
	}

	.goods-qrcode-body .goods-qrcode-box {
		height: 100%;
		position: relative;
		box-shadow: 0 0 15rpx rgba(0, 0, 0, 0.15);
		border-radius: 22rpx;
		overflow: hidden;
	}

	.goods-qrcode {
		width: 100%;
		height: 100%;
		display: block;
		background: #fffffff;
	}

	.goods-qrcode-close {
		position: absolute;
		top: 40rpx;
		right: 40rpx;
		padding: 15rpx;
	}
</style>