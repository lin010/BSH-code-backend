<template>
	<view class="giftOrderDetail-app">
		<view class="giftOrderDetail-detail">
			<image :src="giftpacks.cover_pic" mode=""></image>
			<view class="giftOrderDetail-detail-product">
				<text>{{giftpacks.title}}</text>
				<text>{{giftpacks.descript}}</text>
			</view>
		</view>
		<view class="order-detail">
			<view>
				订单号：{{order_detail.order_sn}}
			</view>
			<view v-if="giftpacks.allow_currency=='integral'" style="color: red;">
				已支付：{{order_detail.integral_deduction_price}}
			</view>
			<view v-if="giftpacks.allow_currency=='money'" style="color: red;">
				已支付：{{order_detail.pay_price}}
			</view>
			<view>
				日期 : {{order_detail.created_at}}
			</view>
		</view>
		<view class="product">
			<view class="product-title">
				服务内容
			</view>
			<view class="product-item" v-for="(item,index) in severList" :key='index' @click="linkQrcode(order_detail.order_id,item.pack_item_id)">
				<image :src="item.cover_pic" mode="" class="product-item_img"></image>
				<view class="product-item-detail">
					<view class="product-item-name-loc">
						<view class="product-item-name">
							<text>{{item.name}}</text>
						</view>
						<view class="product-item-loc" @click.stop="location(item.latitude,item.longitude,item.address)">
							<image :src="img_url+'/gift_location.png'" mode="" class="loc"></image>
							<text>{{item.distance_format}}</text>
						</view>	
					</view>
					<view class="score">
						<image :src="img_url+'/gift_star.png'" mode=""></image>
						<text style="font-size: 25rpx;">{{item.score}}</text>
					</view>
					<view class="product-item-detail-user">
						{{item.store_name}}
					</view>
					<view style="overflow: hidden;margin: 15rpx 0;">
						<image :src="img_url+'/gift_tishi.png'" mode="" style="float: left;margin: 8rpx 10rpx 0 0;width: 30rpx;height: 27rpx;"></image>
						<text style="width: 370rpx;font-size: 25rpx;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 1;overflow: hidden;">{{item.infos}}</text>
					</view>
					<image :src="img_url+'/gift_rifht.png'" mode="" class="right_logo"></image>
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
				giftpacks:'',
				order_detail:'',
				severList:[],
				page:1,
				page_count:'',
				order_id:""
			};
		},
		onLoad(options) {
			this.order_id=options.order_id
			if(options&&options.order_id){
				this.orderDetail(options.order_id)
				this.severcieList(options.order_id)
			}
		},
		methods:{
			linkQrcode(order_id,pack_item_id){
				console.log(order_id,pack_item_id)
					uni.navigateTo({
						url:'../userGiftbag/userGiftbag?order_id='+order_id+"&pack_item_id="+pack_item_id
					})
			},
			location(lat,lnt,address){//导航
				// #ifdef H5
				window.location.href='https://apis.map.qq.com/tools/poimarker?type=0&marker=coord:'+lat+','+lnt+';addr:'+address+'&referer=myapp&key=O3DBZ-IFH3W-KKIRN-RZPNQ-AOSH3-EGB5N'
				// #endif
			
				// #ifdef MP-WEIXIN || APP-PLUS
				uni.openLocation({
				 	latitude:Number(lat),
				 	longitude:Number(lnt),
				 	name:address,
				 	address:address,
				 	success: function () {
						
				 	}
				 });
				 // #endif
			},
			orderDetail(order_id){  //获取礼包订单详情
				this.$http
					.request({
						url: this.$api.package.getorderDetail,
						method: 'POST',
						data:{
							order_id:order_id
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							this.giftpacks=res.data.giftpacks
							this.order_detail=res.data.order_detail
						}else{
							this.$http.toast(res.msg);
						}
				});
			},
			severcieList(order_id){//服务内容
				let that=this
				that.$http
					.request({
						url: that.$api.package.getserviceList,
						method: 'POST',
						data:{
							page:that.page,
							order_id:order_id
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							if(res.data.list.length==0)return false
							let list= res.data.list;
							var arr=that.severList.concat(list)
							that.severList =arr
							that.page_count = res.data.pagination.page_count;
						}else{
							that.$http.toast(res.msg);
						}
				});
			}
		},
		onReachBottom() {
			if(this.page==this.page_count){
				return false;
			} 		
			this.page=this.page+1
			this.severcieList(this.order_id);
		},
	}
</script>

<style lang="less">
	.giftOrderDetail-app{width: 100%;overflow: hidden;}
	.giftOrderDetail-detail{width: 100%;margin: 10rpx auto 0;overflow: hidden;background: #fff;padding: 20rpx;}
	.giftOrderDetail-detail image{width: 240rpx;height: 200rpx;display: block;float: left;}
	.giftOrderDetail-detail-product{float: left;width: 450rpx;margin-left: 10rpx;overflow: hidden;}
	.giftOrderDetail-detail-product text{display: block;width: 100%;}
	.giftOrderDetail-detail-product text:nth-of-type(1){overflow: hidden;text-overflow:ellipsis;white-space: nowrap;color: #000;font-size: 30rpx;}
	.giftOrderDetail-detail-product text:nth-of-type(2){overflow: hidden;font-size: 25rpx;}
	.order-detail{width: 100%;padding:20rpx;background: #fff;margin: 8rpx 0;font-size: 26rpx;color: #000;}
	.product{width: 100%;overflow: hidden;padding: 20rpx 0;}
	.product-title{width: 100%;height: 80rpx;text-align: center;background: #fff;line-height: 80rpx;font-size: 30rpx;color: #000;}
	.product-item{width: 100%;overflow: hidden;background: #fff;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.4);padding: 20rpx 10rpx;margin-top: 5rpx;}
	.product-item_img{width: 240rpx;height: 200rpx;display: block;float: left;}
	.product-item-detail{float: left;overflow: hidden;width: 470rpx;margin-left: 10rpx;position: relative;}
	.product-item-name-loc{width: 100%;height: 44rpx;}
	.product-item-name{color: #000;font-size: 30rpx;font-weight: bold;float: left;width: 300rpx;height: 44rpx;
	overflow: hidden;text-overflow:ellipsis;white-space: nowrap;}
	.product-item-loc{float: left;font-size: 25rpx;height: 44rpx;line-height: 44rpx;margin-left: 10rpx;}
	.loc{width: 16rpx;height: 24rpx;display: block;float: left;margin-top: 10rpx;}
	.score image{width: 24rpx;height: 23rpx;margin-right: 10rpx;}
	.product-item-detail-user{font-size: 26rpx;margin: 5rpx 0;}
	.right_logo{width: 32rpx;height: 38rpx;display: block;position: absolute;top: 80rpx;right: 0;}
</style>
