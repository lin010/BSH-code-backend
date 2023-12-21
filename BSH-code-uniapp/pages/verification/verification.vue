<template>
	<view class="verification-app">
		<view class="address-list">
			<view class="address_location">
				<image :src="img_url+'/ver_location_1.png'" @tap="chooseAddress"></image>
				<input type="text" v-model="address" placeholder="请选择你的定位" disabled />
				<image :src="img_url+'/erroDelete.png'" mode="" @click="deleteAdd"></image>
			</view>
			<view class="shopList">
				<view class="shopItem" v-for="(item,index) in list" :key='index' @click="positionLo(item.address,item.latitude,item.longitude)">
				<!-- 	<image :src="img_url+'/ver_location.png'" mode="" class="img_loc"></image> -->
					<view class="shopItem_message">
						<image :src="item.cover_url" mode="" class="shop_logo"></image>
						<view class="_message">
							<view class="title_message">
								{{item.name}}
							</view>
							<view class="address_message">
								<text :selectable="true">{{item.city}}{{item.address}}</text>
							</view>
							<view class="addess_num">
								距离{{item.distance_format}}
							</view>
						</view>
					</view>
					<image :src="img_url+'/right_check.png'" mode="" class="img_right"></image>
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
				codeDetail:'',
				address:"",
				form:{
					id:'',
					longitude:'',
					latitude:'',
					page:1
				},
				list:[],
				page_count:''
			};
		},
		onLoad(options) {
			let that=this
			if(options.id){
				that.id=options.id
				//#ifdef H5
				   if (this.$http.getPlatform() == 'wechat') {
					   $wechatSdk.location(function(res){
							if (uni.getStorageSync('x-longitude') && uni.getStorageSync('x-latitude')) {
								uni.setStorageSync('x-longitude-new', res.longitude)
								uni.setStorageSync('x-latitude-new', res.latitude)
							} else {
								uni.setStorageSync('x-longitude', res.longitude)
								uni.setStorageSync('x-latitude', res.latitude)
								uni.setStorageSync('flag', true)
							}
							that.form.id=options.id
							that.form.latitude=res.latitude
							that.form.longitude=res.longitude
							that.getshopList(that.form)
					   })
				   }else{
						that.form.id=options.id
						that.getLocationData()
						// that.getshopList(that.form)
				   }
				// #endif
				// #ifdef MP-WEIXIN || APP-PLUS
					that.form.id=options.id
					that.getLocationData()
					// that.getshopList(that.form)
				// #endif
			}
		},
		methods:{
			chooseAddress(){ //选择定位
				var that = this
				that.list=[]
				that.form.page=1
				uni.removeStorageSync('x-longitude')
				uni.removeStorageSync('x-latitude')
				uni.chooseLocation({
					success: function(res) {
						that.address=res.address
						that.form.id=that.id
						that.form.longitude=String(res.longitude)
						that.form.latitude=String(res.latitude)
						that.getshopList(that.form)
						console.log(that.form)
					}
				})
			},
			deleteAdd(){ //删除定位
				this.address=''				
			},
			getshopList(form){ //获取商铺地址
				this.$http.request({
					url: this.$api.moreShop.getshopList,
					method: 'POST',
					data:form,
					showLoading: true,
				}).then(res => {
					if (res.code == 0) {
						if(uni.getStorageSync("x-longitude")||uni.getStorageSync("x-latitude")){
							this.address=res.city_data.sel_city+res.city_data.district+res.city_data.street
						}
						if(res.data.list.length==0)return false
						let arr= res.data.list;
						this.list=this.list.concat(arr)
						this.page_count= res.data.pagination.page_count;
						// this.list=res.data.list
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			getLocationData(){
				var that=this
				uni.getLocation({
					type:'gcj02',
					success(res) {
						var longitude=res.longitude
						var latitude=res.latitude
						that.form.latitude=String(latitude)
						that.form.longitude=String(longitude)
						uni.setStorageSync('x-longitude',res.longitude)
						uni.setStorageSync('x-latitude',res.latitude)
						that.getshopList(that.form)
					}
				})
			},
			positionLo(addrress,lat,lot){ //点击导航				
				// #ifdef H5
				window.location.href = 'https://apis.map.qq.com/tools/poimarker?type=0&marker=coord:' + lat + ',' + lot +
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
			}
		},
		onReachBottom() {
			if(this.form.page==this.page_count){
				return false;
			}
			this.form.id=this.id
			this.form.page=this.form.page+1
			this.getshopList(this.form)
		},
	}
</script>

<style lang="less" scoped>
	.verification-app{width: 100%;}
	.address-list{width: 100%;overflow: hidden;margin-top: 10rpx;background: #fff;box-shadow: 0 3rpx 20rpx rgba(183, 183, 183, 0.6);padding: 0 20rpx;box-sizing: border-box;}
	.address_location{width: 100%;height: 120rpx;border-bottom: 1rpx solid #BFBFBF;}
	.address_location image:nth-of-type(1){float: left;display: block;width: 50rpx;height: 50rpx;margin-top: 30rpx;}
	.address_location image:nth-of-type(2){float: right;display: block;width: 50rpx;height: 50rpx;margin-top: 30rpx;}
	.address_location input{float: left;height: 120rpx;width: 580rpx;padding-left: 20rpx;font-size: 30rpx;}
	.shopList{width: 100%;overflow: hidden;}
	.shopItem{width: 100%;overflow: hidden;border-bottom: 1rpx solid #C4C4C4;}
	.img_loc{width: 50rpx;height: 50rpx;display: block;margin-top: 80rpx;float: left;}
	.img_right{width: 50rpx;height: 50rpx;display: block;margin-top: 80rpx;float: right;}
	.shopItem_message{float: left;display: flex;overflow: hidden;width: 600rpx;margin-left: 10rpx;}
	.shop_logo{width: 140rpx;height: 140rpx;display: block;margin: 40rpx 0;}
	.title_message{width: 410rpx;word-break: break-all;text-overflow: ellipsis;overflow: hidden;display: -webkit-box;
		-webkit-line-clamp: 2;-webkit-box-orient: vertical;color: #000;font-size: 32rpx;margin: 10rpx 0 5rpx 10rpx;}
	.address_message{width: 410rpx;word-break: break-all;text-overflow: ellipsis;overflow: hidden;display: -webkit-box;
		-webkit-line-clamp: 2;-webkit-box-orient: vertical;color: #000;font-size: 28rpx;margin: 10rpx 0 5rpx 10rpx;}
		.addess_num{width: 410rpx;word-break: break-all;text-overflow: ellipsis;overflow: hidden;display: -webkit-box;
		-webkit-line-clamp: 2;-webkit-box-orient: vertical;color: #000;font-size: 28rpx;margin: 10rpx 0 5rpx 10rpx;color: red;}
	.tui-pro-title text{
		display: block;
		user-select: text;
		-webkit-user-select: text;
		width: 100%;
		overflow: hidden;
	}
</style>
