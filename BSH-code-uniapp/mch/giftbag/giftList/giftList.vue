<template>
	<view class="giftList-app">
		<!--搜索-->
		<view class="index1_content_top" :style="{background:'#FF7104'}" id="index1_content_top">
			<view style="width: 20%;color: #fff;font-size: 30rpx;font-weight: bold;overflow: hidden;
			text-overflow:ellipsis;white-space: nowrap;" @click="setCITY">
				<view v-if="city != ''">{{city}}</view>
				<view v-else>加载中...</view>
			</view>
			<view class="index1_content_top_l_r"></view>
			<view class="index1_content_top_r">
				<view class="iconfont iconsousuo"></view>
				<input type="text" placeholder="输入大礼包名" class="index1_content_top_r_input" v-model="keywords"
					@confirm="search"></input>
			</view>
		</view>
		<!---->
		<view style="width: 100%;height: 120rpx;"></view>
		
		
		
		<view class="giftList-item" v-for="(item,index) in productList" :key='index' @click="linkDetail(item.id)">
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
						<text>含{{item.item_num}}款服务</text>
					</view>
					<view class="giftList-item-product-num" style="font-size: 25rpx;">
						<text>库存:{{item.max_stock}}</text>
						<text style="margin-left: 10rpx;">已售:{{item.sold_num}}</text>
					</view>
				</view>
			</view>
			<view class="giftList-item-right">
				<text>{{item.price}}元</text>
				<text>立即抢购</text>
			</view>
		</view>
		<view class="no-more" v-if="productList.length==0">
			<image :src="img_url+'/giftOrder_logo.png'" mode=""></image>
			<text>暂无服务内容</text>
		</view>
		<citySelect @back_city="back_city" v-if="show"></citySelect>
		<backTop :src="backTop.src"  :scrollTop="backTop.scrollTop"></backTop>
	</view>
</template>

<script>
	import backTop from '@/components/back-top/back-top.vue';
	import citySelect from '@/components/linzq/linzq-city.vue';
	export default {
		components: {
			backTop,
			citySelect
		},
		data() {
			return {
				img_url: this.$api.img_url,
				page:1,
				productList:[],
				page_count:'',
				backTop: {
					src: '../../../static/back-top/top.png',
					scrollTop: 0
				},
				city: '',
				show:false,
				city_id:'',
				keywords:'',
				district_id:'',
			};
		},
		onReady() {
			var info = uni.getSystemInfoSync()
			var window_height = info.windowHeight
			var query = uni.createSelectorQuery()
			var that = this
			query.select("#index1_content_top").boundingClientRect(function(res) {
				var view_height = window_height - res.height
				that.height = view_height
			}).exec()
		},
		onLoad() {
			this.getmyLOcation()
		},
		methods:{
			linkDetail(id){//跳转到大礼包详情
				uni.navigateTo({
					url:'../gifebagDetail/newgifebagDetail?pack_id='+id
				})
			},
			back_city(e) { //城市选择回显
			console.log(e)
				if (e !== 'no') {
					this.city_id=e.parent_id
					this.district_id=e.id
					this.page=1
					this.productList=[]
					this.page_count=''
					this.packageList()
					this.city = e.name;
					this.show = false;
				} else {
					this.show = false;
				}
			},
			setCITY() { //选择城市
				this.show = true
			},
			packageList(){ //获取大礼包列表
				this.$http.request({
					url: this.$api.package.getpackageList,
					method: 'get',
					data: {
						page:this.page,
						city_id:this.city_id,
						keywords:this.keywords,
						district_id:this.district_id
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.city=res.data.city_name
						uni.setStorageSync("giftCity",res.data.city_name)
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
			getmyLOcation() {
				let that = this				
				//#ifdef H5
				that.$unifylocation.locationH5()
				setTimeout(() => {
					if(uni.getStorageSync('x-longitude-new'),uni.getStorageSync('x-latitude-new')){
						uni.setStorageSync('x-longitude',uni.getStorageSync('x-longitude-new'))
						uni.setStorageSync('x-latitude',uni.getStorageSync('x-latitude-new'))
					}
				}, 500)
				setTimeout(() => {
					this.packageList()
				}, 1000)
				// #endif
				
				// #ifndef H5
				that.$unifylocation.locationMp()
				setTimeout(() => {
					if(uni.getStorageSync('x-longitude-new'),uni.getStorageSync('x-latitude-new')){
						uni.setStorageSync('x-longitude',uni.getStorageSync('x-longitude-new'))
						uni.setStorageSync('x-latitude',uni.getStorageSync('x-latitude-new'))
					}
				}, 500)
				setTimeout(() => {
					this.packageList()
				}, 1000)
				// #endif
			},
			search() { //搜索
				this.page = 1
				this.productList = []
				this.page_count=''
				this.packageList()
			},
		
		
		},
		onPageScroll(e) {
			console.log(e)
			this.backTop.scrollTop = e.scrollTop;
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
	@import url("../../../plugins/font-icon/iconfont1.css");
	.giftList-app{width: 100%;overflow: hidden;}
	.index1_content_top {
		width: 100%;
		display: flex;
		align-items: center;
		padding: 15px 15px 10px 15px;
		position: fixed;	
		/* #ifdef H5 */
			top: 78rpx;
		/* #endif */
		/* #ifdef  MP  */
			top: 0rpx;
		/* #endif */
		left: 0;
		z-index: 999;
	}
	
	.index1_content_top_l {
		display: flex;
		font-size: 13px;
		font-weight: bold;
		color: #131313;
	}
	
	.index1_content_top_l_r {
		margin-left: 5px;
		width: 8px;
		height: 8px;
		border-top: 2px #FFF solid;
		border-right: 2px #fff solid;
		transform: rotate(135deg);
		margin-top: 3px;
		margin-bottom: 8px;
	}
	
	.index1_content_top_r {
		width: 60%;
		height: 29px;
		background: #F4F4F4;
		border-radius: 14px;
		font-size: 14px;
		margin-left: 15px;
		display: flex;
		align-items: center;
		padding-left: 15px;
	}
	
	.index1_content_top_r_input {
		width: 100%;
		font-size: 14px;
	}
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
	.giftList-item-right text:nth-of-type(1){color: #FF5A0E;font-weight: bold;margin: 35rpx 0 20rpx 0;}
	.giftList-item-right text:nth-of-type(2){background: #FF5A0E;border-radius: 30rpx;font-size: 25rpx;height: 50rpx;line-height: 50rpx;color: #fff;}
	.no-more{width: 200rpx;height: 280rpx;position: absolute;top: 0;left: 0;right: 0;margin: auto;bottom: 0;
	font-size: 26rpx;text-align: center;color: #808080;}
	.no-more image{width: 200rpx;height: 200rpx;}
</style>
