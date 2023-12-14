<template>
	<view class="orderList-app">
		<view class="orderList-status">
			<view v-for="(item,index) in list" :key='index' :class="switchIndex==index?'active':''" @click="checkIndex(index,item.status)">
				{{item.title}}
			</view>
		</view>
		<view class="giftList" style="margin-bottom: 80rpx;">
			<view class="giftList-item" v-for="(item,index) in orderList" :key='index' @click="linkTo(item.order_id)">
				<view class="giftList-item-left">
					<image :src="item.cover_pic" mode="" class="giftLogo"></image>
					<view class="giftList-item-product">
						<view class="giftList-item-product-title">
							{{item.title}}
						</view>
						<view class="giftList-item-product-detail">
							{{item.descript}}
						</view>
						<view class="giftList-item-product-num">
							<image :src="img_url+'/gift-icon.png'" mode="" ></image>
							<text>内含{{item.item_num}}款</text>
						</view>
					</view>
				</view>
				<view class="giftList-item-right">
					<text>{{item.order_price}}元</text>
					<text>去使用</text>
				</view>
			</view>		
		</view>
		<view class="no-more" v-if="orderList.length==0">
			<image :src="img_url+'/giftOrder_logo.png'" mode=""></image>
			<text>暂无订单信息哦</text>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				list:[
					{
						title:'待使用',
					},
					{
						title:'已使用',
					},
					{
						title:'售后',
					},
				],
				switchIndex:0,
				orderList:[],
				page_count:"",
				form:{
					status:'paid',//状态：unpaid待付款，paid待使用， refund售后，completed已使用
					page:1,
				}
			};
		},
		onLoad() {
			this.getOrderList()
		},
		methods:{
			checkIndex(index,status){//选择状态
				this.switchIndex=index
				if(index==0){
					this.orderList=[]
					this.page_count='',
					this.form={
						status:'paid',//状态：unpaid待付款，paid待使用， refund售后，completed已使用
						page:1,
					}
				}
				if(index==1){
					this.orderList=[]
					this.page_count='',
					this.form={
						status:'completed',//状态：unpaid待付款，paid待使用， refund售后，completed已使用
						page:1,
					}
				}
				if(index==2){
					this.orderList=[]
					this.page_count='',
					this.form={
						status:'refund',//状态：unpaid待付款，paid待使用， refund售后，completed已使用
						page:1,
					}
				}
				this.getOrderList()
			},
			linkTo(order_id){//跳转到订单详情
				uni.navigateTo({
					url:'../giftOrderDetail/giftOrderDetail?order_id='+order_id
				})
			},
			getOrderList(){//获取订单列表
				this.$http
					.request({
						url: this.$api.package.orderList,
						method: 'POST',
						data:this.form,
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							if(res.data.list.length==0)return false
							let list= res.data.list;
							var arr=this.orderList.concat(list)
							this.orderList =arr
							this.page_count = res.data.pagination.page_count;
						}else{
							this.$http.toast(res.msg);
						}
				});
			},
		},
		onReachBottom() {
			if(this.form.page==this.page_count){
				return false;
			} 		
			this.form.page=this.form.page+1
			this.getOrderList();
		},
	}
</script>

<style lang="less">
	.orderList-app{width: 100%;overflow: hidden;}
	.orderList-status{width: 100%;height: 80rpx;display: flex;justify-content: space-evenly;}
	.orderList-status view{font-size: 26rpx;line-height: 80rpx;text-align: center;}
	.active{color: rgb(255, 113, 4);border-bottom: 6rpx solid  rgb(255, 113, 4);font-weight: bold;}
	.no-more{width: 200rpx;height: 280rpx;position: absolute;top: 0;left: 0;right: 0;margin: auto;bottom: 0;
	font-size: 26rpx;text-align: center;color: #808080;}
	.no-more image{width: 200rpx;height: 200rpx;}
	.giftList-item{width: 98%;overflow: hidden;display: flex;justify-content: space-evenly;margin: 10rpx auto;padding: 20rpx 0rpx;
	background: #FFFFFF;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.4);border-radius: 20rpx;}
	.giftList-item-left{width: 70%;display: flex;justify-content: space-between;}
	.giftLogo{width: 200rpx;height: 200rpx;display: block;}
	.giftList-item-product{width: 300rpx;}
	.giftList-item-product-title{font-size: 30rpx;color: #000;font-weight: bold;overflow: hidden;text-overflow:ellipsis;white-space: nowrap;}
	.giftList-item-product-detail{font-size: 25rpx;margin: 10rpx 0;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;
	  overflow: hidden;}
	.giftList-item-product-num{font-size: 26rpx;margin-top: 20rpx;}
	.giftList-item-product-num image{width: 33rpx;height: 33rpx;float: left;margin: 5rpx 5rpx 0 0;}
	.giftList-item-right{width: 22%;height: 100%;}
	.giftList-item-right text{display: block;width: 100%;text-align: center;}
	.giftList-item-right text:nth-of-type(1){color: #FF5A0E;font-weight: bold;margin: 35rpx 0 55rpx 0;font-size: 28rpx;}
	.giftList-item-right text:nth-of-type(2){background: #FF5A0E;border-radius: 30rpx;font-size: 25rpx;height: 50rpx;line-height: 50rpx;color: #fff;}
</style>

