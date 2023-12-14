<template>
	<view class="diy-copyright" @click="openUrl(data.link.new_link_url)">
		<view style="padding: 10rpx;background: transparent" flex="main:center cross:center">
			<view>
				<view v-if="data.pic_url" style="text-align: center;">
					<image :src="data.pic_url" style="width: 160rpx;height: 50rpx;">
				</view>
				<view class="text">{{data.description}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			value: {
				type:Object,
				default:()=>{}
			},
		},
		data() {
			return {
				data:{}
			}
		},
		created() {
			this.data = JSON.parse(JSON.stringify(this.value));
		},
		methods: {
			openUrl(url){
				uni.navigateTo({
					url
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
.diy-copyright{
	.text{
		font-size: 8pt;
		text-align: center;
		color: #c5c5c5;
		letter-spacing: 4rpx;
	}
}
</style>
