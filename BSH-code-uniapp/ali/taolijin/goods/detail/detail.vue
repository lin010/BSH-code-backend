<template>
	<view class="detail-app">
		<template v-if="detail">
			<view class="con" style="padding-bottom:30rpx;">
				<text class="con-title">{{detail.name}}</text>
				<text style="font-size:50rpx;color:rgb(255, 113, 4)">¥{{detail.price}}</text>
				<image :src="detail.cover_pic" mode="aspectFill"></image>
			</view>
			<view class="con" style="padding-bottom:30rpx;">
				<view class="con-url">
					<image :src="plugins_img_url+'/web-link.png'" mode="aspectFill" style=""></image>
					<text>{{click_url}}</text>
				</view> 
				<view class="con-copy">
					<text @click="copyUrl">点击复制</text>
				</view>
				<view style="margin-top:30rpx;color:#000;text-align:center;font-size:30rpx;">请点击复制链接，在浏览器打开</view>
			</view>
		</template>
	</view>
</template>
<style lang="less" scoped>
	.detail-app {
		width: 100%;
		height:100%;
		background:#F1F1F1;
	}
	.con{margin-top:20rpx;background:white;display:flex;justify-content: center;flex-direction: column;text-align:center;align-items:center;}
	.con > image{margin-top:30rpx;width:350rpx;height:350rpx;}
	.con-title{margin-top:30rpx;color:#000;text-align:center;display:inline-block;width:80%;}
	.con-url{border-radius:10rpx;height:100rpx;margin-top:30rpx;border:1px solid #ddd;width:80%;display:flex;justify-content: flex-start;background:#fafafa}
	.con-url > image{margin-left:20rpx;margin-top:15rpx;width:70rpx;height:70rpx;}
	.con-url > text{height:97rpx;line-height:97rpx;width:499rpx;padding-left:20rpx;text-align:left;display:inline-block;text-decoration:underline;color:#3194d0;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;}
	.con-copy{}
	.con-copy > text{display:inline-block;background:white;border:1px solid rgb(255, 113, 4);color:rgb(255, 113, 4);width:260rpx;height:70rpx;line-height:66rpx;border-radius:70rpx;margin-top:30rpx;}
</style>

<script>
	export default {
		components: {

		},
		data() {
			return {
				plugins_img_url:this.$api.plugins_img_url,
				img_url: this.$api.img_url,
				id: 0,
				click_url: '',
				detail: null
			}
		},
		onLoad(options) {
			this.beforeOnLoad(options);
			if (options && options.id) {
				this.id = options.id
				this.getDetail()
			}
		},
		onShow() {
			let obj = {};
		},
		methods: {
			copyUrl(){
				uni.setClipboardData({
				    data: this.click_url,
				    success: function () {
				        
				    }
				});
			},
			getDetail() { //获取商品详情
				let that = this;
				this.$http.request({
					url: this.$api.taolijin.getgoodsDetail,
					method: 'post',
					showLoading: true,
					data: {
						id: this.id
					}
				}).then((res) => {
					if (res.code == 0) {
						if(res.data.auth_req != 0){
							uni.navigateTo({
								url: '/ali/taolijin/auth/auth?ali_id=' + res.data.detail.ali_id
							})
							return;
						}
						that.detail = res.data.detail;
						that.click_url = that.detail.extra_data.origin.click_url;
					} else {
						this.$http.toast(res.msg);
					}
				})
			}
		}
	}
</script>
