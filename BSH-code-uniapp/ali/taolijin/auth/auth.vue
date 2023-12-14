<template>
	<view>
		<!--
		<web-view :src="auth_url" v-if="auth_url" sandbox="allow-pointer-lock allow-presentation allow-top-navigation-by-user-activation allow-top-navigation allow-scripts allow-forms allow-same-origin"></web-view>
		-->
		<view class="auth-info" v-if="auth_url">
			<image :src="plugins_img_url+'tb.jpg'"  mode="aspectFill"></image>
			
			<view class="auth-copy-url" style="padding-bottom:30rpx;">
				<view class="con-url">
					<image :src="plugins_img_url+'/web-link.png'" mode="aspectFill" style=""></image>
					<text>{{auth_url}}</text>
				</view> 
				<view class="con-copy">
					<text @click="copyUrl">复制链接</text>
				</view>
			</view>
			<view style="width:100%;font-size:28rpx;text-align:center;background:white;padding:30rpx 0;">
				<view>请复制链接，黏贴到浏览器进行授权</view>
				<view>授权成功，请点击<text @click="authOk" class="auth-finished">我已授权</text></view>
			</view>
			
		</view>
		
	</view>
</template>

<script>

	export default {
		data() {
			return {
				plugins_img_url:this.$api.plugins_img_url,
				img_url: this.$api.img_url,
				ali_id: 0,
				auth_url: ''
			}
		},
		onLoad(option) {
			this.beforeOnLoad(option);
			this.ali_id = option.ali_id;
			
			this.getAuth(true);
	
			//TODO 测试，记得删掉
			/* uni.$emit('ali_auth_status', {msg:'授权成功'});
			uni.navigateBack({
			    delta: 1
			}); */
		},
		onHide(){},
		methods: {
			copyUrl(){
				uni.setClipboardData({
				    data: this.auth_url,
				    success: function () {}
				});
			},
			authOk(){
				uni.$emit('ali_auth_status', {msg:'授权成功'});
				uni.navigateBack({
				    delta: 1
				});
			},
			getAuth(showLoading){
				let that = this;
				this.$http.request({
					url: this.$api.taolijin.getAuthInfo,
					method: 'post',
					showLoading: showLoading,
					data: {ali_id: this.ali_id}
				}).then((res) => {
					if (res.code == 0) {
						if(res.data.need_auth == 1){
							that.auth_url = that.$api.host + "/web/index.php?r=plugin/taolijin/auth/ali-auth&ali_id="+that.ali_id + "&user_id="+res.data.user_id;
							//console.log(that.auth_url);
						}else{
							uni.showToast({
							    title: '您已授权成功',
							    duration: 2000
							});
							that.authOk();
						}
					} else {
						that.$http.toast(res.msg);
					}
				})
			}
		}
	}
</script>

<style>
.auth-info{background:white;padding-top:80rpx;display:flex;flex-direction: column;align-items: center;}
.auth-info image{width:200rpx;height:200rpx;}
.auth-copy-url{}
.auth-copy-url{display:flex;justify-content: center;flex-direction: column;text-align:center;align-items:center;}
.auth-copy-url > image{margin-top:30rpx;width:350rpx;height:350rpx;}
.con-title{margin-top:30rpx;color:#000;text-align:center;display:inline-block;width:80%;}
.con-url{padding-right:20rpx;border-radius:10rpx;height:110rpx;margin-top:30rpx;border:1px solid #ddd;width:80%;display:flex;justify-content: flex-start;background:white}
.con-url > image{margin-left:20rpx;margin-top:15rpx;width:100rpx;height:80rpx;}
.con-url > text{height:97rpx;line-height:97rpx;width:499rpx;padding-left:20rpx;text-align:left;display:inline-block;text-decoration:underline;color:#3194d0;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;}
.con-copy > text, .con-auth-succ > text{display:inline-block;border:1px solid rgb(255, 113, 4);color:rgb(255, 113, 4);width:260rpx;height:70rpx;line-height:66rpx;border-radius:70rpx;margin-top:30rpx;}
.con-auth-succ > text{background:#3194d0;color:white;border:1px solid #3194d0}
.auth-finished{color:green;margin-left:10rpx;text-decoration: underline;}
</style>
