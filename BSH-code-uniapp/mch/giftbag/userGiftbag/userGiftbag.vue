<template>
	
	<view class="userGiftbag-app">
		
		<view class="userGiftbag-product-detail">
			<image :src="seviceDetail.cover_pic" mode="aspectFill" @click="pricewImg(seviceDetail.cover_pic)"></image>
			<view style="padding-left:20rpx;display:flex;flex-direction:column;justify-content:space-around;width:420rpx ;" @click="linkgoods(seviceDetail.goods_id)">
				<view style="font-size: 26rpx;">
					{{seviceDetail.name}}
				</view>
				<view style="color:red;">{{seviceDetail.goods_price}}元</view>
			</view>
		</view>
		
		<view class="userGiftbag-qrcode" style="text-align:center;margin-top:20rpx;padding:20rpx 0;background:white;">
			<view>
				<view style="display:inline-block;padding:15rpx;border:1px solid #ddd;">
					<image :src="file_path" mode="" style="width:370rpx;height:370rpx;"></image>
				</view>
			</view>
			<view v-if="seviceDetail.is_available==1" class="info" style="color:green;">
				{{seviceDetail.infos}}
			</view>
			<view v-else class="info"  style="color:#840000;">
				已失效
			</view>
		</view>
		
		<view class="userGiftbag-store-detail">
			<view style="border-bottom:1px solid #ddd;padding-bottom: 20rpx;">门店信息</view>
			<view style="display:flex;margin-top:20rpx;">
				<image :src="seviceDetail.cover_url" mode=""></image>
				<view class="giftOrderDetail-detail-product">
					<text>{{seviceDetail.store_name}}</text>
					<view class="shop_table_score">
						<view class="iconfont iconwujiaoxing" style="color: #FFA600;" v-for="item in  Number(seviceDetail.score)" :key='item'></view>
						<view class="point" style="font-size: 28rpx;color: red;margin-left: 10rpx;">{{seviceDetail.score}}</view>
					</view>
					<view style="font-size:30rpx;color:#1582AD" @click="gotoShop(seviceDetail.store_id)">访问门店 > </view>
				</view>
				<image style="margin-top:70rpx;margin-right:60rpx;width:55rpx;height:50rpx;" :src="img_url+'/samecitycheck.png'" mode=""
				@click="location(seviceDetail.latitude,seviceDetail.longitude,seviceDetail.address)"></image>
				
			</view>
			
		</view>
	</view>
	
	<!--
	<view class="userGiftbag-app">
		
		<view class="giftbagCapacity-item">
			<image :src="seviceDetail.cover_pic" mode="" class="logo"></image>
			<view class="giftbagCapacity-product">
				<view>
					<image :src="img_url+'/gift_star.png'" mode="" class="star"></image>
					<text style="font-size: 25rpx;">{{seviceDetail.score}}</text>
					<text style="margin: 10rpx 0rpx 0 8rpx;height: 30rpx;background: #F4F4F4;width: 5rpx;"></text>
					<text style="width: 370rpx;font-size: 30rpx;color: #000;padding-left: 10rpx;">{{seviceDetail.name}}</text>
				</view>
				<view style="font-size: 26rpx;">
					{{seviceDetail.store_name}}
				</view>
				<view style="margin-top: 50rpx;">
					<image :src="img_url+'/gift_tishi.png'" mode="" style="float: left;margin: 15rpx 10rpx 0 0;width: 30rpx;height: 27rpx;"></image>
					<text style="width: 370rpx;font-size: 25rpx;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 1;overflow: hidden;">{{seviceDetail.infos}}</text>
				</view>
				<view class="location_div" @click.stop="location(seviceDetail.latitude,seviceDetail.longitude,seviceDetail.address)">
					<image :src="img_url+'/gift_location.png'" mode="" class="loc"></image>
					<text>{{seviceDetail.distance_format}}</text>
				</view>
			</view>
		</view>
		<view class="btn" v-if="seviceDetail.is_available==0" style="background: #C0C0C0;">
			已使用完，或者已失效
		</view>
		<view class="btn" @click="showQrde" v-if="seviceDetail.is_available==1">
			点击展示二维码
		</view>
		<unipopup ref="popupgo" type="center">
			
			<view class="Qrcode">
				<image :src="file_path" mode=""></image>
			</view>
		</unipopup>
		
	
	</view>
	-->
</template>

<script>
	
	import unipopup from '@/components/uni-popup/uni-popup';
	export default {
		components: {
			unipopup
		},
		data() {
			return {
				img_url: this.$api.img_url,
				seviceDetail:'',
				file_path:'',
				order_id:'',
				pack_item_id:'',
				timer:'',
				flag:true
			};
		},
		onLoad(options) {
			let that=this
			if(options&&options.order_id&&options.pack_item_id){
				that.order_id=options.order_id
				that.pack_item_id=options.pack_item_id
				// that.timer=setInterval(() => {
				// 	that.getDetail(options.order_id,options.pack_item_id);
				// },1500);
				that.getDetail(options.order_id,options.pack_item_id);
			}
		},
		methods:{
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
			linkgoods(id){ //去到商品详情
				uni.navigateTo({
					url:'../../../pages/goods/detail?proId='+id
				})
			},
			gotoShop(store_id){
				uni.navigateTo({
					url:'../../../merchants/detail/detail?store_id='+store_id
				})
			},
			pricewImg(url){  //点击放大图片
				let that=this
				let photoList=[]
				photoList[0]=url
				uni.previewImage({
				    current: 0,     // 当前显示图片的链接/索引值
				    urls: photoList,    // 需要预览的图片链接列表，photoList要求必须是数组
				});            
			},
			showQrde(){//点击展示二维码
			
				
			
				// #ifdef H5
				let route = '/h5/#/mch/newmoreShop/newmoreShop';
				// #endif
				
				// #ifdef MP-WEIXIN || APP-PLUS
				let route = 'mch/newmoreShop/newmoreShop';
				// #endif
				
				let that=this
				that.$http
					.request({
						url: that.$api.package.generateQRcode,
						method: 'POST',
						data:{
							order_id:that.order_id,//订单ID
							pack_item_id:that.pack_item_id,//大礼包物品ID
							route_with_param:route,//带参数的前端路由
						},
					})
					.then(res => {
						if(res.code==0){
							//that.$refs.popupgo.open()
							this.file_path=res.data.file_path
						}else{
							that.$http.toast(res.msg);
						}
				});
			},
			getDetail(order_id,pack_item_id){//点击展示二维码
			    if(this.flag){
					let that=this
					that.$http
						.request({
							url: that.$api.package.getitemdetail,
							method: 'POST',
							data:{
								order_id:order_id,//订单ID
								pack_item_id:pack_item_id,//大礼包物品ID
							},
						})
						.then(res => {
							if(res.code==0){
								this.seviceDetail=res.data.detail
								if(this.seviceDetail.is_available==0){
									this.flag=false
								}else{
									that.showQrde();
								}
							}else{
								that.$http.toast(res.msg);
							}
					});
				}
			}
		},
		onUnload() {
			if(this.timer!=null) {  
			    clearInterval(this.timer);  
			    this.timer = null;  
			}  
		}
	}
</script>

<style lang="less" scoped>
@import url("../../../plugins/font-icon/iconfont1.css");
.userGiftbag-app{width: 100%;overflow: hidden;}
.userGiftbag-store-detail{width: 100%;margin: 20rpx auto 0;overflow: hidden;background: #fff;padding: 20rpx;}
.userGiftbag-store-detail image{width: 240rpx;height: 200rpx;display: block;}
.giftOrderDetail-detail-product{width: 450rpx;margin-left: 10rpx;overflow: hidden;}
.giftOrderDetail-detail-product text{display: block;width: 100%;}
.giftOrderDetail-detail-product text:nth-of-type(1){overflow: hidden;text-overflow:ellipsis;white-space: nowrap;color: #000;font-size: 30rpx;}
.giftOrderDetail-detail-product text:nth-of-type(2){overflow: hidden;font-size: 25rpx;}
.shop_table_score{width: 100%;overflow: hidden;flex: 1;display: flex;align-items: center;margin:15rpx 0;}

.userGiftbag-product-detail{display:flex;margin:20rpx;padding:20rpx;background:white;}
.userGiftbag-product-detail image{width: 200rpx;height: 150rpx;}

.userGiftbag-qrcode{}
.userGiftbag-qrcode .info{display:inline-block;width:350rpx;padding:16rpx 0;font-size:28rpx;color:gray;margin-top:10rpx;border-radius:26rpx;}
</style>	

	
<!--
<style lang="less">

	
	
	
	.userGiftbag-app{width: 100%;overflow: hidden;}
	.giftbagCapacity-item{width: 98%;overflow: hidden;margin: 10rpx auto;padding: 20rpx 10rpx;
	background: #FFFFFF;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.4);border-radius: 20rpx;}
	.logo{width: 240rpx;height: 200rpx;display: block;float: left;}
	.giftbagCapacity-product{float: left;width: 450rpx;margin-left: 20rpx;position: relative;}
	.giftbagCapacity-product text{display: inline-block;height: 48rpx;line-height: 48rpx;float: left;}
	.giftbagCapacity-product text:nth-of-type(3){overflow: hidden;text-overflow:ellipsis;white-space: nowrap;}
	.star{width:30rpx ;height: 27rpx;float: left;margin-top: 10rpx;}
	.location_div{font-size: 28rpx;position: absolute;right: 0;top: 50rpx;}
	.location_div text{display: block;width: 100%;text-align: center;font-size: 25rpx;height: 40rpx;line-height: 40rpx;}
	.loc{width: 23rpx;height: 35rpx;display: block;margin: 0 auto;}
	.btn{width: 80%;height: 80rpx;background: rgb(255, 113, 4);color: #fff;text-align: center;line-height: 80rpx;margin: 50rpx auto 0;border-radius: 40rpx;}
	.Qrcode{width: 400rpx;height: 400rpx;}
	.Qrcode image{width: 400rpx;height: 400rpx;display: block;}
</style>
-->