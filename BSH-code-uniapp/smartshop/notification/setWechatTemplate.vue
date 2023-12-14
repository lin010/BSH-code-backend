<template>
	<view class="app">
		<com-nav-bar  title="设置公众号提醒" :status-bar="true" ></com-nav-bar>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				ss_store_id: 0,
				ss_mch_id: 0,
			}
		},
		onLoad(options) {
			this.ss_mch_id = options.ss_mch_id ? options.ss_mch_id : 0;
			this.ss_store_id = options.ss_store_id ? options.ss_store_id : 0;
			this.setNotification();
		},
		onShow() {},
		methods: {
			setNotification(){
				this.$http.request({
					url: this.$api.smartshop.notification.setWechatTpl,
					showLoading: true,
					data: {
						ss_mch_id: this.ss_mch_id,
						ss_store_id: this.ss_store_id
					}
				}).then((res) => {
					if (res.code == 0) {
						if(res.wechat_subscribe == 0){
							uni.showModal({
								title: '提示',
								content: '请关注兰亭平台后操作',
								confirmText: '去关注',
								showCancel: false,
								success: function (res) {
									location.href = "https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzI3MTIzMjAyOQ==&scene=#wechat_redirect";
								}
							});
						}else{
							uni.showModal({
								title: '提示',
								content: '设置成功',
								confirmText: '去关注',
								showCancel: false,
								success: function (res) {
									location.reload();
								}
							});
						}
					}else if(res.code != -1) {
						uni.showModal({
							title: '错误提示',
							content: res.msg,
							showCancel: false,
							success: function (res) {
								location.reload();
							}
						});
					}
				})
			}
		}
	}
</script>

<style scoped>
	
</style>
