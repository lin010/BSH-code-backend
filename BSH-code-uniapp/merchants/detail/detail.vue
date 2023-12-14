<template>
	<view class="shop_detail_container">		
		<view class="shop_detail_header">
			<view  style="display:flex;justify-content: space-evenly;">
				<image :src="detail.logo" mode="scaleToFill" style="width: 150rpx;height: 150rpx;margin-top: 10rpx;"></image>
				<view class="shop_detail_header_name" style="flex-grow:1;width: 70%;position: relative;">
					<view style="margin-left:20rpx;display:flex;flex-direction:column;justify-content:center;flex-wrap: wrap;">
						<view style=" color: #000;font-weight: bold;;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;width: 335rpx;overflow: hidden;">
							{{detail.name}}
						</view>
						<view class="shop_table_score">
							<view class="iconfont iconwujiaoxing" v-for="(i,index) in Number(detail.score)"
								style="color: #FFA600;" :key='index'></view>
							<view class="point" style="font-size: 28rpx;color: red;margin-left: 10rpx;">{{detail.score}}</view>
						</view>
						<view   :class="unfoldShow?'shopProduct':'shopProductHidden'">
							店铺介绍：{{description}}
						</view>
						<view style="font-size: 24rpx;position: absolute;right: 0;bottom: 0;color: rgb(255, 166, 0);"  v-if="!unfoldShow&&description.length>8"     @click="unfold(1)">
							+展开
						</view>
					</view>
				</view>
				<view @click="jumpToPay" style="position: absolute;top: 20rpx;right: 40rpx;width: 25%;text-align:right;padding: 0 20rpx;display:flex; justify-content:flex-end;line-height:150rpx;">
					<image :src="img_url+'pay_mch.png'" mode="scaleToFill" style="margin-top:57rpx;width: 39rpx;height:39rpx;"></image>
					<view style="margin-left:10rpx;color:rgb(255, 166, 0);font-size:30rpx;">付款</view>
				</view>
			</view>
			<view style="font-size: 24rpx;color: rgb(255, 166, 0);width: 100%;text-align: center;height: 50rpx;line-height: 50rpx;"  v-if="unfoldShow"     @click="unfold(2)">
				+收起
			</view>
		</view>
		
		<view class="shop_detail_header">
			<scroll-view class="s-c-list-x" scroll-x="true" v-if="detail.pic_urls.length > 0" >
				<image :src="item" mode="aspectFit" class="s-c-l-item" v-for="(item,index) in detail.pic_urls" :key='item' style="border-radius: 30rpx;" @click="pricewImg(index)"></image>
			</scroll-view>
			<view class="shop_details-hours-detail" >
				<view class="shop_details-hours">
					营业时间：{{detail.business_hours}}
				</view>
				<view class="shop_details-detail"  @click="link(detail.business_hours,detail.address,detail.latitude,detail.longitude)">
					<text style="margin-right: 5rpx;">商户详情</text>		
					<image :src="img_url+'arrow-right-seracher.png'" mode=""></image>
				</view>
			</view>
			<view class="shop_details-address" >
				<view style="width: 65%;font-size: 30rpx;color: #000; overflow: hidden;padding-top: 10rpx;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;"> 
					地址：{{detail.address}}
				</view>
				<view style="width: 15%;text-align: center;font-size: 30rpx;color: #000;" @click="callphone(detail.phone)">
					<image :src="img_url+'iphone-my-new.png'" mode="" style="width: 60rpx;height: 60rpx;display: block;margin: 0 auto;"></image>
					<text>电话</text>
				</view>
				<view style="width: 15%;text-align: center;font-size: 30rpx;color: #000;" @click="location(detail.latitude,detail.longitude,detail.address)">
					<image :src="img_url+'dao_location.png'" mode="" style="width: 60rpx;height: 60rpx;display: block;margin: 0 auto;"></image>
					<text>导航</text>
				</view>
			</view>
			<view class="shop_detail_table">
				<view v-for="(item,index) in table" :key='index' @click="tableSelect(index)" :class="tableIndex==index?'active':''">
					<text>{{item}}</text>
					<text></text>
				</view>
			</view>
		</view>
		<view class="shop_detail_goods" v-if="shopList.length > 0">
			<view class="shop_detail_goods_item" v-for="(item,index) in shopList" :key='index' @click="hotlink(item.goods_id,item.id,item.mch_baopin_id,)">
				<view class="shop_detail_goods_item_left">
					<image :src="item.cover_pic" mode=""></image>
				</view>
				<view class="shop_detail_goods_item_center">
					<view style="height: 48%;color: #000;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;">{{item.name}}</view>
					<view style="height: 25%;margin-top: 45rpx;">
						<text style="color:rgb(234,51,63) ;font-weight: bold;margin-right: 10rpx;">￥{{item.price}}</text>
						<text  style="color:rgb(184,183,183) ;font-size: 26rpx;text-decoration: line-through;">￥{{item.original_price}}</text>
					</view>
				</view>
				<view class="shop_detail_goods_item_lright">
					<text>抢购</text>
				</view>
			</view>
		</view>
		<view style="text-align:center;" v-else>
			暂无数据
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				table:["展柜商品","店内商品"],
				tableIndex:0,
				detail:'',
				page_count:0,//总页数
				shopList:[],
				page:1,
				store_id:'',
				unfoldShow:false,
				description:''
			};
		},
		onLoad(options) {
			this.beforeOnLoad(options);
			if(options.store_id){
				this.store_id=options.store_id
				this.getDetail(options.store_id)
				this.gethotgoods(this.page,options.store_id)
			}
			if(options.sid){
				this.store_id=options.sid
				this.getDetail(options.sid)
				this.gethotgoods(this.page,options.sid)
			}
			if(options.pid){
				uni.setStorageSync("pid",options.pid)
			}
		},
		onShow() {
			this.getCartList()
		},
		methods:{
			getCartList() { //获取购物车列表
				this.$http.request({
					url: this.$api.cart.list,
					method: 'GET'
				}).then((res) => { 
					if(res.code==0){
					
					}else{
						
					}
				})
			},	
			getDetail(store_id){ //获取商户详情
				this.$http
					.request({
						url: this.$api.moreShop.getshopnewdetail,
						method: 'POST',
						data:{
							store_id:store_id
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							this.detail=res.data.detail
							if(this.detail.description.length>8){
								this.description=this.detail.description.substr(0,8)+'...'
							}else{
								this.description=this.detail.description
							}
							uni.setNavigationBarTitle({
							　　title:this.detail.name
							});
						}else{
							this.$http.toast(res.msg);
						}
				});	
			},
			tableSelect(index){//table选择
				this.tableIndex=index
				if(index==0){
					this.page_count=0;//总页数
					this.shopList=[];
					this.page=1;
					this.gethotgoods(this.page,this.store_id)
				}
				if(index==1){
					this.page_count=0;//总页数
					this.shopList=[];
					this.page=1;
					this.getgoods(this.page,this.store_id)
				}
			},
			jumpToPay(){ //跳到付款页
				uni.navigateTo({
					url:'../../mch/personalCentre/ercode/payPages/payPages?id=' + this.detail.mch_id
				})
			},
			link(business_hours,address,latitude,longitude){ //跳转到商户详情页面
				uni.navigateTo({
					url:'../shopDetail/shopDetail?name='+this.detail.name+'&business_hours='+business_hours+'&address='+address+'&latitude='+latitude+'&longitude='+longitude
				})
			},
			location(lat, lnt, addrress) {
			
				// #ifdef H5
				window.location.href = 'https://apis.map.qq.com/tools/poimarker?type=0&marker=coord:' + lat + ',' + lnt +
					';addr:' + addrress + '&referer=myapp&key=O3DBZ-IFH3W-KKIRN-RZPNQ-AOSH3-EGB5N'
				// #endif
			
				// #ifdef MP-WEIXIN || APP-PLUS
				uni.openLocation({
					latitude: Number(lat),
					longitude: Number(lnt),
					name: addrress,
					address: addrress,
					success: function() {
			
					}
				});
				// #endif
			},
			callphone(phone){ //联系电话				
				uni.makePhoneCall({
				 	// 手机号
				    phoneNumber: phone, 				
					// 成功回调
					success: (res) => {
						
					},				
					// 失败回调
					fail: (res) => {
						
					}					
				 })
			},	
			gethotgoods(page,store_id){ //获取爆品商品
				this.$http
					.request({
						url: this.$api.moreShop.gethotListnew,
						method: 'POST',
						data:{
							store_id:store_id,
							page:page
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							console.log(res)
							if(res.data.list.length==0)return false
							let list= res.data.list;
							var arr=this.shopList.concat(list)
							this.shopList=arr
							this.page_count = res.data.pagination.page_count;
						}else{
							this.$http.toast(res.msg);
						}
				});	
			},
			getgoods(page,store_id){ //获取爆品商品
				this.$http
					.request({
						url: this.$api.moreShop.getgoodsnew,
						method: 'POST',
						data:{
							store_id:store_id,
							page:page
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							if(res.data.list.length==0)return false
							let list= res.data.list;
							var arr=this.shopList.concat(list)
							this.shopList=arr
							this.page_count = res.data.pagination.page_count;
						}else{
							this.$http.toast(res.msg);
						}
				});	
			},
			hotlink(goods_id,id,hotid){//点击跳转详情
				if(goods_id&&hotid){
					uni.navigateTo({
						url:'../../pages/goods/detail?proId='+goods_id+"&mch_baopin_id="+hotid
					})
				}else{
					uni.navigateTo({
						url: "../../pages/goods/detail?proId=" + id
					})
				}
				console.log(goods_id,id,hotid)
			},
			pricewImg(index){  //点击放大图片
				let photoList = this.detail.pic_urls.map(item => {
				                  return item;
				    });
				              uni.previewImage({
				                  current: index,     // 当前显示图片的链接/索引值
				                  urls: photoList,    // 需要预览的图片链接列表，photoList要求必须是数组
				                  loop:true   // 是否可循环预览
				              });
			},
			unfold(index){
				if(index==1){
					this.unfoldShow=true
					this.description=this.detail.description
				}
				if(index==2){
					this.unfoldShow=false
					if(this.detail.description.length>8){
						this.description=this.detail.description.substr(0,8)+'...'
					}else{
						this.description=this.detail.description
					}
				}
			}
			
			
		},
		onReachBottom() {
			if(this.tableIndex==0){
				if(this.page==this.page_count){
					return false;
				} 		
				this.page=this.page+1
				this.gethotgoods(this.page,this.store_id)
			}
			if(this.tableIndex==1){
				if(this.page==this.page_count){
					return false;
				} 		
				this.page=this.page+1
				this.getgoods(this.page,this.store_id)
			}
		},
	}
</script>

<style lang="less" scoped>
	@import url("../../plugins/font-icon/iconfont1.css");
	.shop_detail_container{width: 100%;overflow: hidden;}
	.shop_detail_header{width: 95%;overflow: hidden;background: #fff;margin: 20rpx auto;border-radius: 20rpx;padding: 10rpx;box-sizing: border-box;}
	.shop_detail_header_name{overflow: hidden;font-size: 35rpx;}
	.shop_table_score{width: 100%;overflow: hidden;flex: 1;display: flex;align-items: center;margin:15rpx 0;}
	.s-c-list-x{white-space: nowrap;width: 100%;height: 230rpx;color:#fff;overflow: hidden;margin: 20rpx 0;}
	.s-c-list-x .s-c-l-item{display: inline-block; width: 350rpx;height: 230rpx;margin-right: 20rpx;text-align: center; line-height: 230rpx;font-size: 100rpx;border-radius: 20rpx;}
	.shop_details-hours-detail{width: 100%;overflow: hidden;display: flex;justify-content: space-between;margin: 25rpx 0;padding-right: 10rpx;box-sizing: border-box;}
	.shop_details-hours{font-size: 28rpx;color: #000;padding-top: 5rpx;}
	.shop_details-detail{font-size: 28rpx;display: flex;justify-content: space-evenly;}
	.shop_details-detail image{width: 35rpx;height: 35rpx;display: inline-block;margin-top: 5rpx;}
	.shop_details-address{width: 100%;overflow: hidden;display: flex;justify-content: space-between;margin: 40rpx 0 10rpx 0;}
	.shop_detail_table{width: 100%;overflow: hidden;display: flex;justify-content: space-evenly;margin: 30rpx 0 15rpx 0;}
	.shop_detail_table view{display: flex;justify-content: space-evenly;flex-wrap: wrap;width: 30%;}
	.shop_detail_table view text{display:block;}
	.shop_detail_table view text:nth-of-type(1){width: 100%;text-align: center;margin-bottom: 5rpx;}
	.active{color: #FF7104}
	.active text:nth-of-type(2){background: #FF7104;width: 60rpx;height: 4rpx;}
	.shop_detail_goods{width: 95%;overflow: hidden;background: #fff;margin: 20rpx auto 100rpx;border-radius: 20rpx;padding: 20rpx;box-sizing: border-box;}
	.shop_detail_goods_item{width: 100%;overflow: hidden;display: flex;justify-content: space-evenly;margin-bottom: 50rpx;}
	.shop_detail_goods_item_left{width: 30%;height: 200rpx;}
	.shop_detail_goods_item_left image{width: 100%;height: 200rpx;display: block;}
	.shop_detail_goods_item_center{width: 55%;padding:0 15rpx;box-sizing: border-box;}
	.shop_detail_goods_item_lright{width: 15%;height: 200rpx;line-height: 200rpx;}
	.shop_detail_goods_item_lright text{display: inline-block;background: rgb(234,51,63);width: 100%;height: 60rpx;line-height: 60rpx;border-radius: 20rpx;font-size: 28rpx;text-align: center;color: #fff;}
	.shopProduct{width: 460rpx;font-size: 28rpx;color: #000;overflow: hidden;}
	.shopProductHidden{width: 460rpx;font-size: 28rpx;color: #000;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;}
</style>
