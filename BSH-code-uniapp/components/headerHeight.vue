<template>
	<view :style="{height: statusBarHeight+ 'px'}">
		
	</view>
</template>
 
<script>
	export default {
		data() {
			return {
				statusBarHeight:'',
				barHeight:'',
			};
		},
		created() {
			let that=this
			uni.getSystemInfo({
				success: (res) => {
					that.statusBarHeight = res.statusBarHeight
					// #ifdef MP-WEIXIN
							let menu = wx.getMenuButtonBoundingClientRect();
					  
							that.barHeight = menu.bottom + menu.top - res.statusBarHeight * 2;
					// #endif
				}
			})
		}
	}
</script>

<style lang="less">

</style>
