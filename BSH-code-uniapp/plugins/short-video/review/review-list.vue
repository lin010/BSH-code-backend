<template>
	<view>
		<com-tabs :tabs="tabs" :currentTab="currentTab" :bold='true' bottom="14rpx" itemWidth="50%"
		:sliderHeight="6" :sliderWidth="50" @change="change" :height='100' :size="32"></com-tabs>
		
		<view class="review-item-box">
			<view class="review-item">
				<image class="review-item-left" :src="img_url+'ceshi/ceshi.png'" mode=""></image>
				<view class="review-item-right">
					<view class=""></view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				tabs: [{
					name: "审核中"
				}, {
					name: "未通过"
				}],
				currentTab:0
			};
		},
		methods:{
			change(e) {
				this.currentTab = e.index;
			},
		}
	}
</script>

<style lang="scss">
	.review-item-box{
		margin-top: 10rpx;
		background: #FFFFFF;
		
		.review-item{
			border-bottom: 1px solid #F2F2F2;
			padding: 30rpx;
			
			.review-item-left{
				width: 204rpx;
				height: 154rpx;
				border-radius: 6rpx;
			}
		}
	}
</style>
