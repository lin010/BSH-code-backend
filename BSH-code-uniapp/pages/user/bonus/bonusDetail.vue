<template>
	<view class="bonusDetail_app">
		<view class="bonusDetail_all">
			<view class="bonusDetail_all_title">
				<text>总资产</text>
				<image src="../../../plugins/images/eyes.png" mode=""></image>
			</view>
			<view class="bonusDetail_all_money">
				{{userMessage.total_price}}
			</view>
			<view class="bonusDetail_ID">
				ID:BSH{{userMessage.user_id}}
			</view>
			<!-- <view class="withdrawal">
				前往提现
			</view> -->
		</view>
		<view class="bonusDetail_list">
			<view class="bonusDetail_item" v-for="(item,index) in list" :key='index'>
				<view class="bonusDetail_item_left">
					<text>兰亭金豆</text>
					<text>{{item.awards_cycle}}奖金池到账</text>
				</view>
				<view class="bonusDetail_item_right">
					<text>+{{item.money}}</text>
					<text>{{item.payment_time}}</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data(){
			return{
				userMessage:'',
				list:[],
				page:1,
				page_count:"",
			}
		},
		onLoad() {
			this.getbouns()
		},
		methods:{
			getbouns(){
				this.$http
					.request({
						url: this.$api.moreShop.getbouns,
						method: 'POST',
						showLoading: true,
						data:{
							page:this.page,
							limit:8
						}
					})
					.then(res => {
						if (res.code == 0) {
							this.userMessage=res.data.list.user_bonus
							if(res.data.list.bonus_log.length==0)return false
							let arr=res.data.list.bonus_log;
							this.list=this.list.concat(arr)
							this.page_count= res.data.pagination.page_count;
						}
				});
			}
		},
		onReachBottom() {
			if(this.page==this.page_count){
				return false;
			}
			this.page=this.page+1
			this.getbouns()
		},
	}
</script>

<style lang="less">
	.bonusDetail_app{width: 100%;overflow: hidden;}
	.bonusDetail_all{width: 95%;height: 300rpx;background: url(https://dev.mingyuanriji.cn/web/static/bonus_detail.png)no-repeat;background-size: cover;margin: 0 auto;border-radius: 20rpx;position: relative;}
	.bonusDetail_all_title{width: 100%;height: 50rpx;font-size: 30rpx;color: #000;padding: 20rpx;}
	.bonusDetail_all_title text{display: block;float: left;color: #FFFFFF;}
	.bonusDetail_all_title image{display: block;float: left;width: 28rpx;height: 19rpx;margin: 13rpx 0 0 25rpx;}
	.bonusDetail_all_money{width: 100%;height: 80rpx;margin: 40rpx 0 0 0;padding: 0 30rpx;font-size: 60rpx;color: #fff;}
	.bonusDetail_ID{width: 100%;overflow: hidden;font-size: 28rpx;padding: 0 20rpx;margin: 30rpx 0 0 0;color: #fff;}
	.withdrawal{width: 160rpx;height: 60rpx;border: 1rpx solid #fff;font-size: 28rpx;text-align: center;line-height: 58rpx;border-radius: 15rpx;color: #fff;position: absolute;top: 180rpx;right: 30rpx;}
	.bonusDetail_list{width: 100%;overflow: hidden;margin-top: 40rpx;}
	.bonusDetail_item{width: 100%;height: 150rpx;padding: 20rpx;display: flex;justify-content: space-between;border-bottom: 1rpx solid #C8C8C8;}
	.bonusDetail_item_left{width: 65%;}
	.bonusDetail_item_right{width: 35%;}
	.bonusDetail_item_left text{display: block;}
	.bonusDetail_item_left text:nth-of-type(1){font-size: 28rpx;color: #000;}
	.bonusDetail_item_left text:nth-of-type(2){font-size: 25rpx;}
	.bonusDetail_item_right text{display: block;text-align: right;}
	.bonusDetail_item_right text:nth-of-type(1){font-size: 28rpx;color: red;}
	.bonusDetail_item_right text:nth-of-type(2){font-size: 25rpx;}
</style>
