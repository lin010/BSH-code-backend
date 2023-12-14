<template>
	<view class="giftbagCapacity-list">
		<view class="giftbagCapacity-item" v-for="(item,index) in productList" :key='index'>
			<image :src="item.cover_pic" mode="" class="logo"></image>
			<view class="giftbagCapacity-product">
				<view>
					<image :src="img_url+'/gift_star.png'" mode="" class="star"></image>
					<text style="font-size: 25rpx;">{{item.score}}</text>
					<text style="margin: 10rpx 0rpx 0 8rpx;height: 30rpx;background: #F4F4F4;width: 5rpx;"></text>
					<text style="width: 370rpx;font-size: 30rpx;color: #000;padding-left: 10rpx;">{{item.store_name}}</text>
				</view>
				<view style="font-size: 26rpx;width: 350rpx;">
					{{item.name}}
				</view>
				<view style="margin-top: 50rpx;">
					<image :src="img_url+'/gift_tishi.png'" mode="" style="float: left;margin: 15rpx 10rpx 0 0;width: 30rpx;height: 27rpx;"></image>
					<text style="width: 370rpx;font-size: 25rpx;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 1;overflow: hidden;">{{item.infos}}</text>
				</view>
				<view class="location_div" @click.stop="location(item.latitude,item.longitude,item.address)">
					<image :src="img_url+'/gift_location.png'" mode="" class="loc"></image>
					<text>{{item.distance_format}}</text>
				</view>
			</view>
		</view>
		<view class="no-more" v-if="productList.length==0">
			<image :src="img_url+'/giftOrder_logo.png'" mode=""></image>
			<text>暂无服务内容</text>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				page:1,
				page_count:'',
				productList:[]
			};
		},
		onLoad(options) {
			if(options&&options.pack_id){
				this.getpackageListitem(options.pack_id)
			}
		},
		methods:{
			getpackageListitem(pack_id){ //获取礼包类容
				this.$http.request({
					url: this.$api.package.packageListitem,
					method: 'POST',
					data: {
						page:this.page,
						pack_id:pack_id
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						if(res.data.list.length==0)return false
						let list= res.data.list;
						var arr=this.productList.concat(list)
						this.productList =arr
						this.page_count = res.data.pagination.page_count;
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			location(lat,lnt,address){//导航
				window.location.href='https://apis.map.qq.com/tools/poimarker?type=0&marker=coord:'+lat+','+lnt+';addr:'+address+'&referer=myapp&key=O3DBZ-IFH3W-KKIRN-RZPNQ-AOSH3-EGB5N'
			},
		},
		onReachBottom() {
			if(this.page==this.page_count){
				return false;
			} 		
			this.page=this.page+1
			this.packageList();
		},
	}
</script>

<style lang="less">
	.giftbagCapacity-app{width: 100%;overflow: hidden;}
	.giftbagCapacity-item{width: 98%;overflow: hidden;margin: 10rpx auto;padding: 20rpx 10rpx;
	background: #FFFFFF;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.4);border-radius: 20rpx;}
	.logo{width: 240rpx;height: 200rpx;display: block;float: left;}
	.giftbagCapacity-product{float: left;width: 450rpx;margin-left: 20rpx;position: relative;}
	.giftbagCapacity-product text{display: inline-block;height: 48rpx;line-height: 48rpx;float: left;}
	.giftbagCapacity-product text:nth-of-type(3){overflow: hidden;text-overflow:ellipsis;white-space: nowrap;}
	.star{width:30rpx ;height: 27rpx;float: left;margin-top: 10rpx;}
	.location_div{font-size: 28rpx;position: absolute;right: 0;top: 90rpx;}
	.location_div text{display: block;width: 100%;text-align: center;font-size: 25rpx;height: 40rpx;line-height: 40rpx;}
	.loc{width: 23rpx;height: 35rpx;display: block;margin: 0 auto;}
	.no-more{width: 200rpx;height: 280rpx;position: absolute;top: 0;left: 0;right: 0;margin: auto;bottom: 0;
	font-size: 26rpx;text-align: center;color: #808080;}
	.no-more image{width: 200rpx;height: 200rpx;}
</style>
