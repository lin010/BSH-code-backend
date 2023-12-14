<template>
	<view>
		<web-view :src="url"></web-view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				url: '',
				}
		},
		onLoad(option) {
			var url = decodeURIComponent(option.url);
			this.url = url.replace('http','https');
		},
		methods: {
			
		}
	}
</script>

<style>

</style>
