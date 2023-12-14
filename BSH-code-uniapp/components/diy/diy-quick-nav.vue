<template>
	<view class="diy-quick-nav">
		
		<block v-if="data.navStyle == 1">
			<tui-fab @click="fabClick" :right="10" :btn-list="cButtonList"></tui-fab>
		</block>
		
		<block v-if="data.navStyle == 2">
			<view class="fixed-bottom-bar">
				<image @click="share" :src="img_url+'share2022.jpg'"></image>
			</view>
		</block>
		
		<view class="goods-qrcode-modal" v-if="showPoster">
			<view class="goods-qrcode-body flex-col">
				<view class="goods-qrcode2 flex flex-y-center flex-x-center">
					<view class="codeImg_box">
						<view class="goods-qrcode-box">
							<image :src="poster_url" class="goods-qrcode" mode='scaleToFill'></image>
						</view>
					</view>
					<view class="saveCode-btn">长按图片保存至本地</view>
				</view>
				
				<view class="goods-qrcode-close" @click="showPoster=false">
					<view style="width: 50rpx;height: 50rpx;text-align: center;background-color: #ADADAD;color: #FFFFFF;border-radius: 50%;line-height: 50rpx;"
					 class="iconfont icon-guanbi"></view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import tuiFab from '@/components/tui-fab/tui-fab';
	export default {
		props: {
			value: {
				type:Object,
				default:()=>{}
			},
			pageId:{
				type: Number,
				default: 0
			}
		},
		components: {
			tuiFab
		},
		data() {
			return {
				data:{},
				showPoster: false,
				poster_url: '',
				img_url: this.$api.img_url,
			}
		},
		computed:{
			cPicUrl(){
				let picUrl = '';
				if(this.data.share){
					picUrl = this.data.share.pic_url;
				}
				return picUrl;
			},
			cButtonList(){
				let list = [];
				if(this.data.share){
					if(this.data.share.opened){
						list.push({
							tag: 'share',
							color: '#fff',
							bgColor: '#5677fc',
							text: '分享'
						});
					}
				}
				return list;
			}
		},
		created() {
			this.data = JSON.parse(JSON.stringify(this.value));
			let that = this;
		},
		methods: {
			fabClick(e){
				let list = this.cButtonList;
				if(list[e.index].tag == "share"){
					this.share();
				}
			},
			//微信分享海报
			posterShareWx(){
				wx.downloadFile({
					url: this.poster_url,
					success: (res) => {
						wx.showShareImageMenu({
							path: res.tempFilePath
						})
					},
					fail: (res) => {
						uni.showToast({
							title: JSON.stringify(res),
							icon: 'none'
						});
					}
				 })
			},
			share(){
				let that = this;
				this.$http.request({
					url: this.$api.plugin.diy.page.poster,
					method: 'POST',
					data: {
						page_id: this.pageId,
						pic_url: this.cPicUrl
					},
					showLoading: true
				}).then(res => {
					if(res.code == 0){
						this.poster_url = res.data.pic_url;
						// #ifdef H5
						this.showPoster = true;
						// #endif
						// #ifdef MP-WEIXIN
						this.posterShareWx();
						// #endif
					}else{
						this.$http.toast(res.msg);
					}
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
	
	.fixed-bottom-bar{
		position:absolute;
		left:0;
		bottom:30rpx;
		width:100%;
	}
	.fixed-bottom-bar image{width:710rpx;height:97rpx;margin-left:20rpx;}

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
		background: #fffffff;
		background-size: 100%;
	}
	
	.goods-qrcode-close {
		position: absolute;
		top: 40rpx;
		/* #ifdef MP-WEIXIN */
		top: 150rpx;
		/* #endif */
		right: 40rpx;
		padding: 15rpx;
	}
</style>
