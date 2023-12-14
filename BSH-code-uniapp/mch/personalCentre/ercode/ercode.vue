<template>
	<view :class="show?'ercode':'active'" id="poster">
		<image :src="plugins_img_url+'/112233.jpg'" mode=""  class="bac" ></image>
		<view class="main_body">
			<view class="main">
				<view class="shop-name">
					<text style="width: 300rpx;display: block;margin: 0 auto;font-size: 34rpx;color: red;text-align: center;
					overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{message.store.name}}</text>
				</view>
				<view class="qrcode_imag" style="width: 100%;overflow: hidden;"></view>
				<image :src="ercodeLogo.qrcode" mode="" style="width: 310rpx;height: 310rpx;display: block;margin: 0 auto;"></image>
			</view>
			<!-- #ifdef H5 -->
			<view class="btn" @click="capture" v-if="show">
				点击生成图片
			</view>	
			<!-- #endif -->
			<!-- #ifdef H5 -->
			<view class="btn" @click="download" v-if="show" style="background: none;border: 1rpx solid #FF7104;color: #000;
			width: 450rpx;height: 70rpx;border-radius: 40rpx;text-align: center;line-height: 70rpx;margin: 50rpx auto 120rpx;">
				点击下载二维码
			</view>	
			<!-- #endif -->
			<!-- #ifdef MP ||APP-PLUS -->
			<view class="btn" @click="download" v-if="show" style="background: none;border: 1rpx solid #FF7104;color: #000;
			width: 450rpx;height: 70rpx;border-radius: 40rpx;text-align: center;line-height: 70rpx;margin: 220rpx auto 120rpx;">
				点击下载二维码
			</view>	
			<!-- #endif -->
			<view class="goods-qrcode-modal" v-if="showPoster">
				<view class="goods-qrcode-body flex-col">
					<!-- 整一个图片包括二维码都是后台给的图片 -->
					<view class="goods-qrcode2 flex flex-y-center flex-x-center">
						<view class="codeImg_box">
							<!-- #ifdef H5 -->
							<view class="goods-qrcode-box">
								<image :src="poster_url" class="goods-qrcode" mode='aspectFit'></image>
							</view>
							<!-- #endif -->
							
							<!-- #ifdef MP-WEIXIN -->
							<view  class="goods-qrcode-box" @longpress="saveImage(poster_url)">
								<image :src="poster_url" class="goods-qrcode" mode='aspectFit'></image>
							</view>
							<!-- #endif -->
						</view>
						<view class="saveCode-btn">长按图片保存至本地</view>
					</view>
					
					<view class="goods-qrcode-close" @click="closePost">
						<view style="width: 50rpx;height: 50rpx;text-align: center;background-color: #ADADAD;color: #FFFFFF;border-radius: 50%;line-height: 50rpx;"
						 class="iconfont icon-guanbi"></view>
					</view>
				</view>
			</view>
			<view class="cover" v-if="coverShow">
				<view>
					<image :src="cover_url" mode="widthFix"></image>
					<text style="display: block;width: 100%;font-size: 30rpx;text-align: center;margin-top: 50rpx;">长按图片保存至本地</text>
					<text style="display: block;width: 40%;font-size: 30rpx;line-height: 60rpx;text-align: center;margin: 50rpx auto 0;border-radius: 20rpx;
					border: 1rpx solid #FF7104;" @click="cancle">取消</text>
				</view>
			</view>
		</view>	
	</view>
</template>

<script>
	//#ifdef H5
	import html2canvas from 'html2canvas'
	//#endif
	export default {
		data() {
			return {
				ercodeLogo: {},
				message: {},
				showPoster: false,
				poster_url:"",
				img_url: this.$api.img_url,
				plugins_img_url: this.$api.plugins_img_url,
				show:true,
				coverShow:false,
				cover_url:''
			}
		},
		onLoad() {
			// #ifdef H5
			var route = '/h5/#/mch/personalCentre/ercode/payPages/payPages';
			// #endif
			
			// #ifdef MP-WEIXIN || APP-PLUS
			var route = 'mch/personalCentre/ercode/payPages/payPages';
			// #endif
			
			this.$http.request({
				url: this.$api.moreShop.checkOrder,
				method: 'POST',
				data: {
					route: route
				},
				showLoading: true
			}).then(res => {
				if (res.code == 0) {
					this.ercodeLogo = res.data
				}
			})
			if (uni.getStorageSync('mchMessage')) {
				this.message = uni.getStorageSync('mchMessage')
			}
		},
		methods: {
			//#ifdef H5
			capture() {
				this.show=false
				setTimeout(()=>{
					window.pageYoffset = 0;
					document.documentElement.scrollTop = 0;
					document.body.scrollTop = 0;
					html2canvas(
					    document.getElementById('poster'), 
					    { 
							height: document.getElementById('poster').clientHeight+90,
							scale: 3,
							dpi: window.devicePixelRatio * 3,
							useCORS: true
						 }
					).then( canvas => {
								this.poster_url = canvas.toDataURL('image/png', 1);
								if(this.poster_url.length>0){								
									this.showPoster=true
								}
					});
				},500)

			},
			//#endif
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
							},
							fail(res){
								that.$http.toast('保存失败,请稍后重试');
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
			closePost(){
				this.showPoster = false;
				this.show=true
			},
			download(){ //下载图片
				// #ifdef H5
				var route = '/h5/#/mch/personalCentre/ercode/payPages/payPages';
				// #endif
				
				// #ifdef MP-WEIXIN || APP-PLUS
				var route = 'mch/personalCentre/ercode/payPages/payPages';
				// #endif
				this.$http.request({
					url: this.$api.moreShop.downloadCode,
					method: 'POST',
					data: {
						route: route
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						let that=this
						// #ifdef H5
							that.cover_url=res.data.pic_url
							if(that.cover_url.length>0){
								that.coverShow=true
							}
						// #endif
						// #ifdef MP-WEIXIN
						uni.downloadFile({
							    url:res.data.pic_url,
							    success: (res) => {
							        if (res.statusCode === 200) {
										that.saveImage(res.tempFilePath)
									}
								}
						});
							// #endif
						// #ifdef APP-PLUS
						uni.downloadFile({
							    url:res.data.pic_url,
							    success: (res) => {
							        if (res.statusCode === 200) {
										that.saveImg(res.tempFilePath); 
									}
								}
						});
							// #endif	
						
					}else{
						that.$http.toast(res.msg);
					}
				})
			},
			cancle(){
				this.coverShow=false
			}
		}
	}
</script>

<style scoped lang="less">
	.ercode {
		width: 100%;
		height: 100%;
		position: relative;
	}
	.active{
		width: 100%;
		height: 90%;
		position: relative;
	}
	.bac{width: 100%;height: 1240rpx;display: block;}
	.main_body{width: 100%;overflow: hidden;position: absolute;top: 595rpx;z-index: 999;left: 0;}
	.main{width: 100%;height:500rpx;}
	/* #ifdef H5 ||APP-PLUS*/
.shop-name{width: 100%;overflow: hidden;text-align: center;color: #000 ;font-weight: bold;font-size: 36rpx;overflow: hidden;
text-overflow:ellipsis;
white-space: nowrap;margin-bottom: 48rpx;}
	/* #endif */
	/* #ifdef  MP */
	.shop-name{width: 100%;overflow: hidden;text-align: center;color: #000 ;font-weight: bold;font-size: 36rpx;overflow: hidden;
	text-overflow:ellipsis;
	white-space: nowrap;margin-bottom: 48rpx;}
	/* #endif */
	.notice{width: 100%;overflow: hidden;text-align: center;color: #fff;font-weight: bold;font-size: 36rpx;margin: 40rpx 0 0 0;}
	.btn {
		width: 450rpx;
		background: #FF7104;
		height: 70rpx;
		border-radius: 40rpx;
		text-align: center;
		color: #fff;
		line-height: 70rpx;
		margin: 200rpx auto 0rpx;
	}
	.goods-qrcode-modal {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 1000;
		background: rgba(0, 0, 0, 0.5);
		opacity: 1;
		transition: opacity 250ms;
		z-index: 9999;
	}
	
	.goods-qrcode-body {
		background: #ffffff;
		height: 100%;
		border-radius: 10rpx;
		padding: 30rpx;
	}
	
	.goods-qrcode-body .goods-qrcode-box {
		height: 100%;
		position: relative;
		box-shadow: 0 0 15rpx rgba(0, 0, 0, 0.15);
	}
	.goods-qrcode2{
		position: relative;
		height: 1300rpx;
		flex-direction: column;
		margin-top: 40rpx;
	}
	.codeImg_box {
		width: 92%;
		height: 82%;
		margin-bottom: 20rpx;
	}
	.saveCode-btn{
		color: #939292;
		padding: 10px 20px;
		border-radius: 10px;
	}
	
	.goods-qrcode {
		width: 100%;
		height: 100%;
		background: #ffffff;
		background-size: cover;
	}
	
	.goods-qrcode-close {
		position: absolute;
		top: 40rpx;
		/* #ifdef MP-WEIXIN ||APP-PLUS */
		top: 150rpx;
		/* #endif */
		right: 40rpx;
		padding: 15rpx;
	}
	
	
	
	
	
	
	
	.cover{
		width: 100%;
		height: 100%;
		position: fixed;
		top: 0;
		left: 0;
		z-index: 999;
		background: #fff;
	}
	.cover view{
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		height: 600rpx;
		margin: auto;
		width: 500rpx;
	}
	.cover image{width: 320rpx;display: block;height: 350rpx;margin: 0 auto;}
</style>

