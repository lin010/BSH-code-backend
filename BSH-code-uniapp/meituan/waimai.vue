<template>
	<view class="check-status">
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				data: ''
			}
		},
		onShow() {
			uni.showLoading({
				title: '请稍后'
			});
			let that = this;
			
			
			
			uni.getLocation({
				type: 'gcj02',
				success: function (res) {
					that.loginFree(res.longitude, res.latitude);
				}
			});
		},
		methods: {
			loginFree(lng, lat){
				let that = this;
				this.$http.request({
					url: this.$api.meituan.loginFree,
					showLoading: false,
					method: 'post',
					data: {longitude: lng, latitude: lat}
				}).then(res => {
					if(res.status == 0){
						this.data = res.data || '';
						let input, form = document.createElement('form'), body = document.body;
						form.setAttribute('method', 'post');
						form.setAttribute('action', this.data.loginFreeUrl);
						input = document.createElement('input');
						input.name = "accessKey"
						input.type = "hidden";
						input.setAttribute('value', this.data.accessKey);
						form.appendChild(input);
						input = document.createElement('input');
						input.name = "content"
						input.type = "hidden";
						input.setAttribute('value', this.data.content);
						form.appendChild(input);
						body.appendChild(form);
						form.submit();
					}else{
						uni.showModal({
							showCancel: false,
							content: res.msg,
							success() {
								uni.navigateBack();
							}
						})
					}
				});
			}
		}
	}
</script>

<style scoped>
	.check-status{height:100vh;display: flex;flex-direction:column;align-items: center;justify-content: center;}
	.check-status .txt{font-size: 45rpx;color:royalblue;}
	.check-status .txt:last-child{font-size:32rpx;margin-top:20rpx;margin-bottom:300rpx;color:red}
</style>