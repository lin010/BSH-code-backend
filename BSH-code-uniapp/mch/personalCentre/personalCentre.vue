<template>
	
	<view v-if="!loading" class="personalCenter" >

		<view class="personalCenter-top">
			<image :src="store.cover_url" class="personal-logo"></image>
			<view class="personal_nicken_ID">
				<view class="personal_nicken">{{store.name}}</view>
				<view style="display: flex;">
					<view class="personal_id">ID:{{store.mch_id}}</view>
					<view class="store-change" style="margin-left: 50rpx;background: #fff;color: #000;width: 150rpx;text-align: center;
					border-radius: 15rpx;font-size: 30rpx;" v-if="change" @click="outchange">
						退出管理
					</view>
				</view>
			</view>
			<!-- 分享的店铺  -->
			<!-- #ifdef MP-WEIXIN || H5 -->
			<view class="share-shop">
				<view class="tui-collection tui-size" @click.stop="poster()">
					<view class="tui-icon-collection iconfont icon-qrcode"></view>
					<view class="tui-scale">分享</view>
				</view>
			</view>
			<!-- #endif -->
			<!-- 分享店铺  -->
		</view>
		<view class="jx-content-box" style="margin-top: 20rpx;">
			<view class="jx-header-btm">
				<view class="jx-btm-item">
					商户后台地址
				</view>
				<view class="linkcopy" style="font-size: 24rpx;width: 30%;overflow:hidden;
	text-overflow:ellipsis;
	white-space:nowrap">
					{{link}}
				</view>
				<view class="jx-btm-item last">
					<text
						style="background:  #FF7104;width: 130rpx;font-size: 30rpx;border-radius: 10rpx;text-align: center;color: #fff;"
						@click="copyText(link)">点击复制</text>
				</view>
			</view>
		</view>
		<view class="jx-content-box" style="margin-top: 20rpx;">
			<view class="jx-header-btm">
				<view class="jx-btm-item">
					当前商品
				</view>
				<view class="jx-btm-item">
					{{stat.goods_num}}件
				</view>
				<view class="jx-btm-item last">
					<text
						style="background:  #FF7104;width: 130rpx;font-size: 30rpx;border-radius: 10rpx;text-align: center;color: #fff;"
						@click="href(7)">查看商品</text>
				</view>
			</view>
		</view>
		<view class="jx-content-box" style="margin-top: 20rpx;">
			<view class="jx-header-btm">
				<view class="jx-btm-item">
					当前{{currencyAlias.balance_alias}}
				</view>
				<view class="jx-btm-item">
					{{stat.account_money}}元
				</view>
				<view class="jx-btm-item last">
					<text
						style="background: #FF7104;width: 130rpx;font-size: 30rpx;border-radius: 10rpx;text-align: center;color: #fff;margin-bottom: 20rpx;"
						@click="href(5)">明细</text>
					<text
						style="background: #FF7104;width: 130rpx;font-size: 30rpx;border-radius: 10rpx;text-align: center;color: #fff;"
						@click="href(6)">提现</text>
				</view>
			</view>
		</view>
		<view class="jx-content-box" style="margin-top: 20rpx;">
			<view class="jx-header-btm">
				<view class="jx-btm-item">
					待结算金额
				</view>
				<view class="jx-btm-item">
					{{stat.fz_account_money}}元
				</view>
				<view class="jx-btm-item last">
					<text  @click="href(3)"
						style="background: #FF7104;width: 130rpx;font-size: 30rpx;border-radius: 10rpx;text-align: center;color: #fff;margin-bottom: 10rpx;margin-top: 10rpx;"
						>明细</text>
				</view>
			</view>
		</view>		
		<!-- <view class="jx-content-box">
			<view class="jx-header-btm">
				<view class="jx-btm-item">
					<view class="jx-btm-num">0笔</view>
					<view class="jx-btm-text">今日订单</view>
				</view>
				<view class="jx-btm-item">
					<view class="jx-btm-num">{{stat.order_num}}笔</view>
					<view class="jx-btm-text">历史订单</view>
				</view>
			</view>
		</view> -->
		<!-- #ifdef MP-WEIXIN || H5 -->
		<view class="personalCenter-item" @click="href(1)">
			<jx-list-cell :arrow="true" padding="0" :lineLeft="false">
				<view class="jx-cell-header" style="height: 100rpx;">
					<view class="jx-cell-title" style="font-weight: 700;">我的首页</view>
				</view>
			</jx-list-cell>
		</view>
		<!-- #endif -->
		<view class="personalCenter-item" @click="myOrder">
			<jx-list-cell padding="0" :lineLeft="false" :arrow="true">
				<view class="jx-cell-header" style="height: 100rpx;">
					<view class="jx-cell-title" style="font-weight: 700;">订单管理</view>
				</view>
			</jx-list-cell>
		</view>
		<view class="personalCenter-item" @click="mchGroup" v-if="mch_group_id > 0">
			<jx-list-cell padding="0" :lineLeft="false" :arrow="true">
				<view class="jx-cell-header" style="height: 100rpx;">
					<view class="jx-cell-title" style="font-weight: 700;">分店管理</view>
				</view>
			</jx-list-cell>
		</view>
		<view class="personalCenter-item" @click="href(2)">
			<jx-list-cell :arrow="true" padding="0" :lineLeft="false" >
				<view class="jx-cell-header" style="height: 100rpx;">
					<view class="jx-cell-title" style="font-weight: 700;">二维码收款</view>
				</view>
			</jx-list-cell>
		</view>
		<view class="personalCenter-item"  @click="href(4)" style="margin-bottom: 100rpx;">
			<jx-list-cell :arrow="true" padding="0" :lineLeft="false">
				<view class="jx-cell-header" style="height: 100rpx;">
					<view class="jx-cell-title" style="font-weight: 700;">商户设置</view>
				</view>
			</jx-list-cell>
		</view>
	
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
						<view class="goods-qrcode-box" @longpress="saveImage(poster_url)">
							<image :src="poster_url" class="goods-qrcode" mode='aspectFit'></image>
						</view>
						<!-- #endif -->

						<!-- #ifdef APP-PLUS -->
						<view class="goods-qrcode-box" @longpress="appSaveImg(poster_url)">
							<image :src="poster_url" class="goods-qrcode" mode='aspectFit'></image>
						</view>
						<!-- #endif -->
					</view>
					<view class="saveCode-btn">长按图片保存至本地</view>
				</view>

				<view class="goods-qrcode-close" @click="poster(-1)">
					<view style="width: 50rpx;height: 50rpx;text-align: center;background-color: #ADADAD;color: #FFFFFF;border-radius: 50%;line-height: 50rpx;"
						class="iconfont icon-guanbi"></view>
				</view>
			</view>
		</view>
	</view>
	<view v-else></view>
</template>

<script>
	import jxListCell from '@/components/list-cell/list-cell';
	export default {
		components: {
			jxListCell
		},
		data() {
			return {
				mch_group_id: 0,
				userMessage: {},
				showPoster: false,
				loading: false,
				poster_url: "",
				store:'',
				stat:'',
				loading: true,
				// link:'https://www.mingyuanriji.cn/web/index.php?r=mch%2Fadmin%2Flogin',
				link:'https://h5-mingyuanriji.cn/web/index.php?r=mch%2Fadmin%2Flogin',
				change:false,
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			}
		},
		onLoad() {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
		},
		onShow() {
			this.getBaseInfo();
		},
		methods: {
			getBaseInfo(){
				let that = this;
				this.$http.request({  //获取商户基本信息
					url: this.$api.moreShop.getMchBaseInfo,
					method: 'POST', 
					showLoading: true
				}).then(res => {
					if(res.code == 0){
						this.change=uni.getStorageSync('x-man-mch-id')?true:false
						that.loading = false;
						that.mch_group_id = res.data.base_info.mch_group_id;
						that.userMessage = res.data.base_info;
						that.store=res.data.base_info.store;
						that.stat=res.data.base_info.stat;
						uni.setStorageSync("mchMessage", res.data.base_info);
						
						if(res.data.base_info.mch_status=='applying'||res.data.base_info.mch_status=='verifying'||res.data.base_info.mch_status=='refused'){
							uni.navigateTo({
								url:'/mch/enter/enter'
							})
						}
					}else{
						that.$http.toast(res.msg);
					}
				});
			},
			myOrder(){
				uni.navigateTo({
					url: './myOrder/myOrder'
				})
			},
			mchGroup(){
				uni.navigateTo({
					url: '/mchGroup/groupList/groupList'
				})
			},
			href(page) {
				if (page == 1) {
					let mch_id = this.userMessage.store.mch_id
					let that=this
					uni.setStorage({
						key: 'mch_id',
						data: mch_id,
						success() {
							uni.navigateTo({
								url: "/merchants/detail/detail?store_id=" + that.store.id
							})
						}
					})
				}
				if (page == 2) {
					uni.navigateTo({
						url: './ercode/ercode'
					})
				}
				if(page==3){
					uni.navigateTo({
						url:'./waiteDetail/waiteDetail'
					})
				}
				if (page == 4) {
					uni.navigateTo({
						url: './installCenter/installCenter',
						animationType: 'pop-in',
						animationDuration: 200
					})
				}
				if (page == 5) {
					uni.navigateTo({
						url: './mongeyDetail/mongeyDetail'
					})
				}
				if (page == 6) {
					uni.navigateTo({
						url: './withdrawal/withdrawal'
					})
				}
				if (page == 7) {
					let mch_id = this.userMessage.store.id
					uni.navigateTo({
						url: './productList/productList?mch_id=' + mch_id
					})
				}
			},
			poster(key) {
				if (key == -1) {
					this.showPoster = false;
					return;
				}
				this.loading = true;
				this.showPoster = true;
				if (this.poster_url) {
					this.loading = false;
					return;
				}
				let that = this;
				this.$http.request({
					url: this.$api.moreShop.sharePoster,
					method: 'POST',
					showLoading: true,
					data: {
						// #ifdef H5||APP-PLUS
						route: 'merchants/detail/detail?store_id='+ that.store.id,
						// #endif
						// #ifdef MP-WEIXIN
						route: 'merchants/detail/detail',
						sid:that.store.id
						// #endif
					}
				}).then(res => {
					if (res.code == 0) {
						that.poster_url = res.data.pic_url;
						that.loading = false;
					}
				})
			},
			appSaveImg(url) { //app保存图片到本地
				let that = this;
				/* 保存图片到相册 */
				uni.saveImageToPhotosAlbum({
					filePath: url,
					success: function() {
						that.$http.toast('保存成功');
					},
					fail(res) {
						that.$http.toast('保存失败,请稍后重试');
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
							},
							fail(res) {
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
			copyText(text) {
				let _self = this;
				// #ifdef H5
				return new Promise((resolve, reject) => {
					let copy = document.createElement("input"); // 创建一个input框获取需要复制的文本内容
					copy.value = text;
					let appDiv = document.getElementsByClassName('personalCenter')[0];
					appDiv.appendChild(copy);
					copy.select();
					document.execCommand("Copy");
					_self.$http.toast("复制成功")
					copy.remove()
					resolve(true);
				})
				// #endif
			
				// #ifndef H5
				uni.setClipboardData({
					data: text,
					success: function() {
						_self.$http.toast("复制成功")
					}
				});
				// #endif
			},
			outchange(){
				uni.removeStorageSync('x-man-mch-id')
				this.change=false
				this.getBaseInfo()
			},
		}
	}
</script>

<style lang="scss" scoped>
	.mana-bottom-btn{border-top:1px solid #eaeaea;padding:20rpx 20rpx;background:white;position:fixed;bottom:0;left:0;width:100%;text-align:center;}
	.mana-bottom-btn > text{border-radius:60rpx;padding:20rpx 0;width:85%;display:inline-block;background:#F24F34;font-size:30rpx;color:white;}
	.personalCenter {
		width: 100%;
		overflow: hidden;
		background: url(https://dev.mingyuanriji.cn/web/static/personalCenter_logo.jpg)no-repeat; 
		background-size: 100%;
	}

	.personalCenter-top {
		margin-top: 40rpx;
		width: 100%;
		overflow: hidden;
		padding: 20rpx;
		box-sizing: border-box;
	}

	.personal-logo {
		width: 128rpx;
		height: 128rpx;
		display: block;
		border-radius: 50px;
		float: left;
	}

	.personal_nicken_ID {
		float: left;
		margin-left: 30rpx;
		width: 400rpx;
		color: #fff;
	}

	.personal_nicken {
		margin: 10upx 0;
		width: 100%;
	}

	.personalCenter-item {
		width: 100%;
		overflow: hidden;
		margin: 20upx 0 0 0;
		padding: 0 30rpx;
		box-sizing: border-box;
	}

	.jx-content-box {
		width: 100%;
		padding: 0 30rpx;
		box-sizing: border-box;
		// margin-top: 20rpx;
	}

	.jx-header-btm {
		border-radius: 12rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: space-between;
		color: #000;
		background: #ffffff;
		margin-bottom: 20rpx;
	}

	.jx-btm-item {
		flex: 1;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		padding: 40rpx 0rpx;
		position: relative;

		&::after {
			content: '';
			// border-right: 1rpx solid #b3b3b3;
			width: 1px;
			height: 100rpx;
			position: absolute;
			right: 0;
			top: 50%;
			transform: translateY(-50%);
		}

		&:last-child {
			&::after {
				display: none;
			}
		}
	}

	.jx-btm-num {
		font-size: 12pt;
		font-weight: 600;
		position: relative;
	}

	.jx-btm-text {
		font-size: 9pt;
		opacity: 0.85;
		padding-top: 4rpx;
	}

	.jx-cell-header {
		width: 100%;
		// height: 100rpx;
		height: 70rpx;
		padding: 0 40rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-bottom: 1rpx solid #f3f3f3;
	}

	.jx-cell-title {
		font-size: 11pt;
		line-height: 30rpx;
		font-weight: 400;
		color: #333;
		font-weight: 600;
		letter-spacing: 1px;
	}

	.jx-cell-sub {
		font-size: 9pt;
		font-weight: 400;
		color: #999;
		padding-right: 10rpx;
	}

	.share-shop {
		float: right;
		padding-top: 20rpx;
	}

	.tui-collection {
		color: #333;
		display: flex;
		align-items: center;
		flex-direction: column;
		justify-content: center;
		height: 96rpx;
		width: 96rpx;
		border-radius: 100rpx;
		background: #F7F7F7;
		margin-right: 10rpx;
	}

	.tui-scale {
		transform: scale(0.7);
		transform-origin: center center;
		line-height: 24rpx;
		font-weight: normal;
	}

	.tui-icon-collection {
		line-height: 20px !important;
		margin-bottom: 0 !important;
		color: #333333;
		font-size: 20px;
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
		width: 100%;
		height: 90%;
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		margin: auto;
		border-radius: 10rpx;
		padding: 30rpx;
		box-sizing: border-box;
	}

	.goods-qrcode-body .goods-qrcode-box {
		height: 100%;
		position: relative;
		box-shadow: 0 0 15rpx rgba(0, 0, 0, 0.15);
	}

	.goods-qrcode2 {
		position: relative;
		height: 100%;
		flex-direction: column;
	}

	.codeImg_box {
		width: 100%;
		height: 95%;
		// margin-bottom: 20rpx;
	}

	.saveCode-btn {
		color: #939292;
		padding: 10px 20px;
		border-radius: 10px;
	}

	.goods-qrcode {
		width: 100%;
		height: 100%;
		background: #fffffff;
		background-size: 100%;
	}

	.goods-qrcode-close {
		position: absolute;
		top: 40rpx;
		right: 40rpx;
		padding: 15rpx;
	}
</style>
