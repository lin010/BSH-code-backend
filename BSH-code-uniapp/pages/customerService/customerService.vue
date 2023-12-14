<template>
	<view>
		<web-view :src='service_url'></web-view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				service_url:''
			};
		},
		onLoad() {
			this.service_url = JSON.parse(uni.getStorageSync('mall_config')).mall_setting.setting.web_service_url;
		}
	}
</script>

<style lang="scss">

</style>
