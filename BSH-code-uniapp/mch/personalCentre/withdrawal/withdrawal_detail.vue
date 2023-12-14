<template>
	<view class="withdrawal_detail-app">
		<view class="items" v-if="dataList && dataList.length">
			<view class="item" v-for="(item, i) in dataList" :key="i">
				<view class="item-left">
						<text style="color: #000;font-size: 28rpx;">申请金额：￥{{item.money}}</text>
						<text style="font-size: 25rpx;margin-top: 5rpx;">手续费：￥{{item.service_charge}}</text>
						<text style="font-size: 28rpx;color: #FF7104;margin-top: 5rpx;">实际到账金额：￥{{item.fact_price}}</text>
						<text style="font-size: 25rpx;margin: 5rpx 0;">时间：{{item.created_at}}</text>
						<text style="font-size: 25rpx;margin-top: 5rpx;">
							备注：{{item.content}}
						</text>
				</view>
				<view class="item-right">
					<text v-if="item.status==0" style="background: #C8C8C8;color: #fff;">待处理</text>
					<text v-if="item.status==1&&item.transfer_status==0" style="background: #C8C8C8;color: #fff;">待转账</text>
					<text v-if="item.status==1&&item.transfer_status==1" style="background: #19BE6B;color: #fff;">已转账</text>
					<text v-if="item.status==1&&item.transfer_status==2" style="background: red;color: #fff;">拒绝转账</text>
					<text v-if="item.status==2" style="background: red;color: #fff;">已驳回</text>
				</view>
			</view>
		</view>
		<view class="items" v-else>
			<main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore>
		</view>
	</view>
</template>
<script>
	export default{
		data(){
			return{
				dataList: [],
				page:1,
				page_count:'',
			}
		},
		onLoad() {
			this.getwithdrawal_detail()
		},
		methods:{
			getwithdrawal_detail(){
				this.$http
					.request({
						url: this.$api.moreShop.withdrawal_detail,
						method: 'GET',
						data: {
							page:this.page,
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							if(res.data.list.length==0)return false
							let list= res.data.list;
							var arr=this.dataList.concat(list)
							this.dataList =arr
							this.page_count= res.data.pagination.page_count;
						}else{
							this.$http.toast(res.msg);
						}
					});
			}
		},
		onReachBottom() {
			if(this.page==this.page_count){
				return false;
			} 		
			this.page=this.page+1
			this.getwithdrawal_detail();
		}
	}
</script>

<style lang="less">
	.withdrawal_detail-app{width: 100%;overflow: hidden;}
	.items{width: 100%;overflow: hidden;margin-bottom: 80rpx;}
	.item{width: 100%;overflow: hidden;margin: 25rpx 0;padding:20rpx;display: flex;justify-content: space-between;
	background: #fff;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.3);border-radius: 30rpx;}
	.item-left{width: 70%;overflow: hidden;display: flex;justify-content: space-between;flex-wrap: wrap;}
	.item-left text{display: block;width: 100%;overflow: hidden;}
	.item-right{width: 25%;overflow: hidden;}
	.item-right text{width: 100%;height: 50rpx;font-size: 28rpx;line-height: 50rpx;margin-top: 30rpx;text-align: center;display: block;border-radius: 20rpx;}
</style>
