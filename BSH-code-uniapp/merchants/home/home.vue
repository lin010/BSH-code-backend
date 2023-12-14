<template>
	<view class="shop-container">
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
				<input type="text" placeholder="输入商家名、品类或商圈" class="index1_content_top_r_input" disabled
					@click="search"></input>
			</view>
		</view>
		<!---->
		<view class="shop-type">
			<cg-swiper :swiperList="catorylist" :swiperDots='true' :autoplay='false' @clickItem="clickItem"></cg-swiper>
		</view>
		<!-- <view class="shop-table">
			<view v-for="(item,index) in shoptable" :key='index' :class="currentIndex==index?'active':''" @click="selectTable(index)">
				<text>{{item}}</text>
				<text></text>
			</view>
		</view> -->
		<view class="shop_table_list">
			<view class="shop_table_item" v-for="(item,index) in shopList" :key='index' @click="shopdetail(item.id)">
				<view class="shop_table_item_left">
					<image :src="item.cover_url" mode="aspectFit"></image>
				</view>
				<view class="shop_table_item_right">
					<view class="shop_table_item-name">
						{{item.name}}
					</view>
					<view class="shop_table_item-score">
						<view class="iconfont iconwujiaoxing" v-for="(i,index) in Number(item.score)"
							style="color: #FFA600;" :key='index'></view>
						<view class="point" style="font-size: 28rpx;color: red;margin-left: 10rpx;">{{item.score}}
						</view>
					</view>
					<view class="shop_table_item_notice">
						{{item.remark}}
					</view>
					<view @click.stop="toAddr(item)" class="shop_table_item-location">
						<image :src="img_url+'dao_location.png'" mode="" style="width: 35rpx;height: 35rpx;"></image>
						<text style="margin: 0 10rpx;">{{item.region_name}}</text>
						<text>{{item.distance_format}}</text>
					</view>
				</view>
			</view>
			<!--加载loadding-->
			<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
			<main-nomore :visible="!pullUpOn" bgcolor="#FFFFFF"></main-nomore>
			<!--加载loadding-->
		</view>
		<view class="shop_table_list" v-if="!loading && shopList.length==0">
			<view class="logo" style="width: 350rpx;height: 300rpx;margin: 100rpx auto;">
				<image :src="img_url+'shop_new_home.png'" mode="aspectFit"
					style="display: block;width: 150rpx;height: 150rpx;margin: 0 auto;"></image>
				<text
					style="display: block;width: 100%;text-align: center;margin-top: 20rpx;font-size: 30rpx;color: #FF7104;">暂无门店</text>
			</view>
		</view>
		<hans-tabber style="position:fixed;bottom:0;width:100%;left:0;right:0;"></hans-tabber>
		<citySelect @back_city="back_city" v-if="show"></citySelect>
		<backTop :src="backTop.src"  :scrollTop="backTop.scrollTop"></backTop>
	</view>
</template>

<script>
	import citySelect from '@/components/linzq-city/linzq-city.vue';
	import cgSwiper from '@/components/cg-swiper/cg-swiper.vue';
	import hansTabber from '../../components/hans-tabbar/hans-tabbar.vue';
	import backTop from '@/components/back-top/back-top.vue';
	export default {
		components: {
			citySelect,
			cgSwiper,
			hansTabber,
			backTop
		},
		data() {
			return {
				loading: true,
				img_url: this.$api.img_url,
				city: '',
				show: false, //城市选择显示或者影藏
				swiperList: [{
						img: 'https://liangcang-material.alicdn.com/prod/upload/3916be5165cb4d4aadae49a5a34400e6.jpg',
						text: 1
					},
					{
						img: 'https://liangcang-material.alicdn.com/prod/upload/1ab0ce94f41c4e7ca4126d5d1b6717f6.jpg',
						text: 2
					},
					{
						img: 'https://liangcang-material.alicdn.com/prod/upload/7438aaf338ce4a90b30a2806a3fee785.jpg',
						text: 3
					},
					{
						img: 'https://liangcang-material.alicdn.com/prod/upload/3916be5165cb4d4aadae49a5a34400e6.jpg',
						text: 1
					},
					{
						img: 'https://liangcang-material.alicdn.com/prod/upload/1ab0ce94f41c4e7ca4126d5d1b6717f6.jpg',
						text: 2
					},
					{
						img: 'https://liangcang-material.alicdn.com/prod/upload/7438aaf338ce4a90b30a2806a3fee785.jpg',
						text: 3
					},
					{
						img: 'https://liangcang-material.alicdn.com/prod/upload/3916be5165cb4d4aadae49a5a34400e6.jpg',
						text: 1
					},
					{
						img: 'https://liangcang-material.alicdn.com/prod/upload/1ab0ce94f41c4e7ca4126d5d1b6717f6.jpg',
						text: 2
					},
					{
						img: 'https://liangcang-material.alicdn.com/prod/upload/7438aaf338ce4a90b30a2806a3fee785.jpg',
						text: 3
					},
					{
						img: 'https://liangcang-material.alicdn.com/prod/upload/3916be5165cb4d4aadae49a5a34400e6.jpg',
						text: 1
					},
					{
						img: 'https://liangcang-material.alicdn.com/prod/upload/1ab0ce94f41c4e7ca4126d5d1b6717f6.jpg',
						text: 2
					},
					{
						img: 'https://liangcang-material.alicdn.com/prod/upload/7438aaf338ce4a90b30a2806a3fee785.jpg',
						text: 3
					}


				],
				shoptable: ['同城好店', '同城好店', '同城好店'],
				currentIndex: 0, //table默认选项
				catorylist: [],
				form: {
					cat_id: '',
					city_id: "",
					region_id: '',
					keyword: '',
					sort_by: '',
					distance: '',
					page: 1,
				},
				page_count: 0, //总页数
				shopList: [],
				backTop: {
					src: '../../static/back-top/top.png',
					scrollTop: 0
				},
				loadding: false,
				pullUpOn: true,
			}
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
			if (!uni.getStorageSync("shopCity")) {
				this.getmyLOcation()
			} else {
				this.getcatory()
			}
		},
		methods: {
			toAddr(item){
				uni.navigateTo({
					url:'../shopDetail/shopDetail?name='+item.name+'&business_hours='+item.business_hours+'&address='+item.address+'&latitude='+item.latitude+'&longitude='+item.longitude
				});
			},
			search() { //点击搜索
				uni.navigateTo({
					url: '../shopSearch/shopSearch'
				})
			},
			setCITY() { //选择城市
				this.show = true
			},
			back_city(e) { //城市选择回显
				if (e !== 'no') {
					this.city = e.name;
					this.show = false;
					this.form.page = 1
					this.page_count = ''
					this.shopList = []
					this.form.city_id = e.parent_id
					this.form.region_id = e.id
					uni.setStorageSync('shopCity', {
						city: e.name,
						city_id: e.parent_id,
						region_id: e.id
					})
					this.getshopList()
				} else {
					this.show = false;
				}
			},
			selectTable(index) { //table切换选择
				this.currentIndex = index
			},
			shopdetail(id) {
				uni.navigateTo({
					url: '../detail/detail?store_id=' + id
				})
			},
			clickItem(e) {
				console.log(e)
				uni.setStorageSync('current', 1)
				uni.navigateTo({
					url: '../citywide/citywide?cat_id=' + e.id
				})
			},
			getmyLOcation() {
				let that = this
				
				//#ifdef H5
				that.$unifylocation.locationH5()
				setTimeout(() => {
					if(uni.getStorageSync('x-longitude-new'),uni.getStorageSync('x-latitude-new')){
						console.log(uni.getStorageSync('x-longitude-new'),uni.getStorageSync('x-latitude-new'))
						uni.setStorageSync('x-longitude',uni.getStorageSync('x-longitude-new'))
						uni.setStorageSync('x-latitude',uni.getStorageSync('x-latitude-new'))
						console.log(uni.getStorageSync('x-longitude'),uni.getStorageSync('x-latitude'))
					}
				}, 500)
				setTimeout(() => {
					this.getcatory()
				}, 1000)
				// #endif
				
				// #ifndef H5
				that.$unifylocation.locationMp()
				setTimeout(() => {
					if(uni.getStorageSync('x-longitude-new'),uni.getStorageSync('x-latitude-new')){
						console.log(uni.getStorageSync('x-longitude-new'),uni.getStorageSync('x-latitude-new'))
						uni.setStorageSync('x-longitude',uni.getStorageSync('x-longitude-new'))
						uni.setStorageSync('x-latitude',uni.getStorageSync('x-latitude-new'))
						console.log(uni.getStorageSync('x-longitude'),uni.getStorageSync('x-latitude'))
					}
				}, 500)
				setTimeout(() => {
					this.getcatory()
				}, 1000)
				// #endif
			},
			getcatory() { //获取分类
				this.$http
					.request({
						url: this.$api.moreShop.getcategorylist,
						method: 'POST',
						data: '',
						showLoading: true
					})
					.then(res => {
						if (res.code == 0) {
							if (!uni.getStorageSync("shopCity")) {
								this.catorylist = res.data.list
								// this.city = res.city_data.district
								// this.form.city_id = res.city_data.city_id
								// this.form.region_id = res.city_data.district_id
								// uni.setStorageSync('shopCity', {
								// 	city: res.city_data.district,
								// 	city_id: res.city_data.city_id,
								// 	region_id: res.city_data.district_id
								// })
								this.city = '附近的'
								this.form.city_id = ''
								this.form.region_id = ''
								this.getshopList()
							} else {
								this.catorylist = res.data.list
								this.city = uni.getStorageSync("shopCity").city
								this.form.city_id = uni.getStorageSync("shopCity").city_id
								this.form.region_id = uni.getStorageSync("shopCity").region_id
								this.getshopList()
							}
						} else {
							this.$http.toast(res.msg);
						}
					});
			},
			getshopList() { //获取门店列表
				this.loading = true;
				this.$http.request({
					url: this.$api.moreShop.getshoplistall,
					method: 'POST',
					data: this.form,
				}).then(res => {
					this.loading = false;
					if (res.code == 0) {
						if (res.data.list.length == 0) return false
						let list = res.data.list;
						var arr = this.shopList.concat(list)
						this.shopList = arr
						this.page_count = res.data.pagination.page_count;
						this.pullUpOn=false
					} else {
						this.$http.toast(res.msg);
					}
				});
			},

		},
		onPageScroll(e) {
			this.backTop.scrollTop = e.scrollTop;
		},
		onReachBottom() {
			this.loadding = true;
			this.pullUpOn = true;
			if (this.form.page == this.page_count) {
				this.loadding = false;
				this.pullUpOn = false;
				return false;
			}
			this.form.page = this.form.page + 1
			this.getshopList()
		}
	}
</script>
<style lang="less" scoped>
	@import url("../../plugins/font-icon/iconfont1.css");

	.shop-container {
		width: 100%;
		overflow: hidden;
	}

	.index1_content_top {
		width: 100%;
		display: flex;
		align-items: center;
		padding: 15px 15px 10px 15px;
		position: fixed;
		top: 0;
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

	.shop-type {
		width: 100%;
		overflow: hidden;
		/* #ifdef H5*/
			margin-top: 40rpx;
		/* #endif */
		/* #ifdef APP-PLUS||MP */
			margin-top: 108rpx;
		/* #endif */
	}

	.shop-table {
		width: 100%;
		height: 100rpx;
		display: flex;
		justify-content: space-evenly;
		background: rgb(244, 244, 244);
	}

	.shop-table view {
		width: 30%;
		margin-top: 20rpx;
		display: flex;
		justify-content: space-between;
		flex-wrap: wrap;
	}

	.shop-table view text {
		display: block;
		margin: 0 auto;
		width: 100%;
		text-align: center;
	}

	.shop-table view text:nth-of-type(2) {
		width: 80rpx;
		height: 5rpx;
	}

	.active {
		color: #FF7104;
	}

	.active text:nth-of-type(2) {
		background: #FF7104;
	}

	.shop_table_list {
		width: 100%;
		overflow: hidden;
		margin-bottom: 116rpx;
	}

	.shop_table_item {
		width: 95%;
		margin: 15rpx auto;
		background: #fff;
		display: flex;
		justify-content: space-evenly;
		overflow: hidden;
	}

	.shop_table_item_left {
		width: 30%;
		height: 200rpx;
		margin: 25rpx 0;
	}

	.shop_table_item_left image {
		width: 100%;
		display: block;
		height: 180rpx;
	}

	.shop_table_item_right {
		width: 64%;
		padding-left: 25rpx;
		box-sizing: border-box;
		margin: 15rpx 0 20rpx 0;
		position: relative;
	}

	.shop_table_item-name {
		width: 100%;
		font-size: 30rpx;
		color: #000;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 1;
		-webkit-box-orient: vertical;
	}

	.shop_table_item_notice {
		font-size: 27rpx;
		color: rgb(255, 166, 0);
		margin-top: 15rpx;
		background: rgb(247, 236, 230);
		width: 80%;
		text-align: center;
		border-radius: 1rpx;
	}

	.shop_table_item-score {
		width: 100%;
		overflow: hidden;
		flex: 1;
		display: flex;
		align-items: center;
		margin-top: 10rpx;
	}

	.shop_table_item-location {
		width: 100%;
		overflow: hidden;
		text-align: right;
		font-size: 28rpx;
		margin-top: 20rpx;
	}

	.shop_table_item-location image {
		display: inline-block;
		position: relative;
		top: 6rpx;
	}
</style>
