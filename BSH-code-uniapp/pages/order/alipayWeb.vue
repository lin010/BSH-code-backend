<template>
	<web-view v-if="url != ''" :src="url"></web-view>
</template>

<script>
	export default {
		data() {
			return {
				url: ''
			}
		},
		onLoad(options) {
			this.url = options.url;
		},
		methods: {
			
		}
	}
</script>

<style>
</style>
