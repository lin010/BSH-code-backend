<template>
	<view class="citywide-container">
		<!--搜索-->
		<view class="index1_content_top" :style="{background:'#FF7104'}" id="index1_content_top">
			<template v-if="form.distance == ''">
				<view style="width: 20%;color: #fff;font-size: 30rpx;font-weight: bold;overflow: hidden;
				text-overflow:ellipsis;white-space: nowrap;" @click="setCITY">
					{{city != ''?city:'加载中...'}}
				</view>
				<view class="index1_content_top_l_r"></view>
			</template>
			<template v-else>
				<view
					style="width: 20%;color: #fff;font-size: 30rpx;font-weight: bold;overflow: hidden; text-overflow:ellipsis;white-space: nowrap;">
					距离{{form.distance}}公里内
				</view>
			</template>
			<view class="index1_content_top_r">
				<view class="iconfont iconsousuo"></view>
				<input type="text" placeholder="输入商家名、品类或商圈" class="index1_content_top_r_input" v-model="keyword"
					disabled @click="search"></input>
			</view>
		</view>
		<!---->
		<view class="citywide-screening">
			<view @click="sorting(0)" style="margin: 0 auto;">
				<text>{{catory}}</text>
				<image :src="img_url+'unmy-hotel.png'" mode=""></image>
			</view>
			<view @click="sorting(1)" style="margin: 0 auto;">
				<text>{{ai}}</text>
				<image :src="img_url+'unmy-hotel.png'" mode=""></image>
			</view>
			<view @click="sorting(2)" style="margin: 0 auto;">
				<text>{{addressloc}}</text>
				<image :src="img_url+'unmy-hotel.png'" mode=""></image>
			</view>
		</view>
		<view class="citywide_list">
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
					<view class="shop_table_item-location">
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
		<view class="citywide_list" v-if="!loading && shopList.length==0">
			<view class="logo" style="width: 350rpx;height: 300rpx;margin: 100rpx auto;">
				<image :src="img_url+'shop_new_home.png'" mode="aspectFit"
					style="display: block;width: 150rpx;height: 150rpx;margin: 0 auto;"></image>
				<text
					style="display: block;width: 100%;text-align: center;margin-top: 20rpx;font-size: 30rpx;color: #FF7104;">暂无门店</text>
			</view>
		</view>

		<unipopup ref="popupSort" type="top">
			<view class="searchList-app-sort" v-if="currentINdex==0">
				<scroll-view scroll-y scroll-with-animation class="left-box">
					<template v-for="(item,index) in catoryList">
						<view :key="index" :class="item.id == form.cat_id ? 'actove' : 'tab-bar-item' "
							@click="selectzero(index,item)">
							<text>{{item.name}}</text>
						</view>
					</template>
				</scroll-view>
				<scroll-view scroll-y scroll-with-animation class="right-box">
					<!-- <view v-for="(item,index) in sortlist" :key="index" class="tab-bar-item"  style="border-bottom: 1rpx solid rgb(242,245,249);">
							<text  style="width: 100%;height: 100%;text-align: left;margin-left: 30rpx;">{{item}}</text>
						</view> -->
				</scroll-view>
			</view>
			<view class="searchList-app-sort" v-if="currentINdex==1">
				<view v-for="(item,index) in sortlist" :key='index' :class="selectIndexone==index?'active':''"
					@click="selectone(index,item)">{{item}}</view>
			</view>
			<view class="searchList-app-sort" v-if="currentINdex==2">
				<view v-for="(item,index) in sortlist" :key='index' :class="selectIndextwo==index?'active':''"
					@click="selecttwo(index,item)">{{item}}</view>
			</view>
		</unipopup>
		<citySelect @back_city="back_city" v-if="show"></citySelect>
		<hans-tabber style="position:fixed;bottom:0;width:100%;left:0;right:0;"></hans-tabber>
		<backTop :src="backTop.src"  :scrollTop="backTop.scrollTop"></backTop>
	</view>
</template>

<script>
	import citySelect from '@/components/linzq-city/linzq-city.vue';
	import hansTabber from '../../components/hans-tabbar/hans-tabbar.vue';
	import unipopup from '@/components/uni-popup/uni-popup';
	import backTop from '@/components/back-top/back-top.vue';
	export default {
		components: {
			citySelect,
			hansTabber,
			unipopup,
			backTop
		},
		data() {
			return {
				loading: true,
				//catory:'全部分类',
				ai: '智能排序',
				addressloc: '距离排序',
				img_url: this.$api.img_url,
				city: '',
				keyword: '',
				show: false, //城市选择显示或者影藏
				currentIndex: 0, //table默认选项
				popupSort: false,
				sortlist: [],
				smart: [ //智能排序
					"智能排序",
					"好评优先",
					"发现新店",
				],
				distance: [ //距离排序
					"不限距离",
					"1km",
					"3km",
					"5km",
					"7km",
					"10km",
				],
				selectIndexzero: 0, //分类筛选
				selectIndexone: 0, //智能排序选择
				selectIndextwo: 0, //距离排序选择
				currentINdex: 1, //控制两个筛选条件下的显示
				form: {
					cat_id: '0',
					city_id: "",
					region_id: '',
					keyword: '',
					sort_by: '',
					distance: '',
					page: 1,
				},
				page_count: 0, //总页数
				shopList: [],
				catoryList: [], //分类
				backTop: {
					src: '../../static/back-top/top.png',
					scrollTop: 0
				},
				loadding: false,
				pullUpOn: true,
			};
		},
		computed: {
			catory: function() {
				var i, str = '全部分类';
				for (i = 0; i < this.catoryList.length; i++) {
					if (this.catoryList[i].id == this.form.cat_id) {
						str = this.catoryList[i].name;
						break;
					}
				}
				return str;
			}
		},
		onLoad(options) {
			if (uni.getStorageSync("shopCity")) {
				this.getcat()
				// this.getcatory()
				if (options.cat_id) {
					this.city = uni.getStorageSync("shopCity").city
					this.form.cat_id = options.cat_id
					this.form.city_id = uni.getStorageSync("shopCity").city_id
					this.form.region_id = uni.getStorageSync("shopCity").region_id
					
					
					this.getshopList()
				}
				if (!options.cat_id) {
					this.city = uni.getStorageSync("shopCity").city
					this.form.city_id = uni.getStorageSync("shopCity").city_id
					this.form.region_id = uni.getStorageSync("shopCity").region_id
					this.getshopList()
				}
			} else {
				// this.getmyLOcation()
				if (options.cat_id) {
					this.form.cat_id = options.cat_id
					this.getmyLOcation()
				}
				if (!options.cat_id) {
					this.getmyLOcation()
				}
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
		methods: {
			search() { //搜索
				uni.navigateTo({
					url: '../shopSearch/shopSearch?key=' + this.keyword
				})
			},
			shopdetail(id) { //店铺详情
				uni.navigateTo({
					url: '../detail/detail?store_id=' + id
				})
			},
			getcat(){
				this.$http
					.request({
						url: this.$api.moreShop.getcategorylist,
						method: 'POST',
						data: '',
						showLoading: true
					})
					.then(res => {
						if (res.code == 0) {
							this.catoryList = res.data.list
							this.catoryList.unshift({
								id: '0',
								mall_id: '',
								name: '全部分类',
								pic_url: '',
							})
						} else {
							this.$http.toast(res.msg);
						}
					});
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
							this.catoryList = res.data.list
							this.catoryList.unshift({
								id: '0',
								mall_id: '',
								name: '全部分类',
								pic_url: '',
							})
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
			setCITY() { //选择城市
				this.show = true
			},
			back_city(e) { //城市选择回显
				if (e !== 'no') {
					this.$refs.popupSort.close();
					this.selectIndexzero = 0; //分类筛选
					this.selectIndexone = 0; //智能排序选择
					this.selectIndextwo = 0; //距离排序选择
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
			sorting(index) { //点击排序显示弹窗
				this.currentINdex = index
				this.$refs.popupSort.open();
				if (index == 0) {
					this.sortlist = this.smart
					this.selectIndexone = 0
					this.selectIndextwo = 0
				}
				if (index == 1) {
					this.sortlist = this.smart
					this.selectIndexzero = 0
					this.selectIndextwo = 0
				}
				if (index == 2) {
					this.sortlist = this.distance
					this.selectIndexzero = 0
					this.selectIndexone = 0
				}
			},
			selectzero(index, item) { //分类选择
				this.$refs.popupSort.close();
				this.selectIndexzero = index
				console.log(item)
				//this.catory=item.name
				// this.city = uni.getStorageSync("shopCity").city
				if (item.name == '全部分类') {
					this.form.cat_id = ''
				} else {
					this.form.cat_id = item.id
				}
				this.page_count = 0
				this.form.page = 1
				this.shopList = []
				if(uni.getStorageSync("shopCity")){
					this.form.city_id = uni.getStorageSync("shopCity").city_id
					this.form.region_id = uni.getStorageSync("shopCity").region_id
					this.city = uni.getStorageSync("shopCity").city
				}else{
					this.form.city_id = ''
					this.form.region_id = ''
					this.city = '附近的'
				}
				this.getshopList()
			},
			selectone(index, item) { //智能选择
				this.$refs.popupSort.close();
				this.selectIndexone = index
				this.ai = item
				if (item == '智能排序') {
					this.form.sort_by = ''
				} else if (item == '好评优先') {
					this.form.sort_by = 'score'
				} else if (item == '发现新店') {
					this.form.sort_by = 'new'
				}
				this.page_count = 0
				this.form.page = 1
				this.shopList = []
				if(uni.getStorageSync("shopCity")){
					this.form.city_id = uni.getStorageSync("shopCity").city_id
					this.form.region_id = uni.getStorageSync("shopCity").region_id
				}else{
					this.form.city_id = ''
					this.form.region_id = ''
				}
				this.getshopList()
			},
			selecttwo(index, item) { //距离选择
				this.$refs.popupSort.close();
				this.selectIndextwo = index
				this.addressloc = item
				if (item == '不限距离') {
					this.form.distance = ''
				} else if (item == '1km') {
					this.form.distance = 1
				} else if (item == '3km') {
					this.form.distance = 3
				} else if (item == '5km') {
					this.form.distance = 5
				} else if (item == '7km') {
					this.form.distance = 7
				} else if (item == '10km') {
					this.form.distance = 10
				}
				this.page_count = 0
				this.form.page = 1
				this.shopList = []
				if(uni.getStorageSync("shopCity")){
					this.form.city_id = uni.getStorageSync("shopCity").city_id
					this.form.region_id = uni.getStorageSync("shopCity").region_id
				}else{
					this.form.city_id = ''
					this.form.region_id = ''
				}
				this.getshopList()
			},
			getshopList() { //获取门店列表
				this.loading = true;
				this.$http
					.request({
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
							this.pullUpOn=true
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
		},
	}
</script>

<style lang="less" scoped>
	@import url("../../plugins/font-icon/iconfont1.css");

	.citywide-container {
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

	.citywide-screening {
		width: 100%;
		height: 100rpx;
		border-bottom: 1rpx solid rgb(242, 245, 249);
		display: flex;
		justify-content: space-between;
		position: fixed;
		z-index: 999;
		/* #ifdef H5 */
		top: -10rpx;
		/* #endif */
		/* #ifdef MP */
		top: 0rpx;
		/* #endif */
		left: 0;
	}

	.citywide-screening view {
		width: 50%;
		text-align: center;
		line-height: 100rpx;
		font-size: 28rpx;
		position: relative;
	}

	.citywide-screening view image {
		width: 35rpx;
		height: 35rpx;
		display: block;
		position: absolute;
		top: 35rpx;
		right: 30rpx;
	}

	.citywide-screening {
		width: 100%;
		overflow: hidden;
		background: #fff;
		margin-top: 108rpx;
	}

	.searchList-app-sort {
		width: 100%;
		overflow: hidden;
		background: #fff;
		/* #ifdef H5 */
		margin-top: 105rpx;
		/* #endif */
		/* #ifdef MP||APP-PLUS */
		margin-top: 205rpx;
		/* #endif */
	}

	.searchList-app-sort view {
		width: 100%;
		height: 100rpx;
		text-align: center;
		font-size: 30rpx;
		line-height: 100rpx;
		border-bottom: 1rpx solid rgb(242, 245, 249);
	}

	.active {
		color: #FF7104;
		background: url(https://dev.mingyuanriji.cn/web/static/yellow-right.png) no-repeat;
		background-size: 5%;
		background-position: 90% 50%;
	}

	.citywide_list {
		width: 100%;
		overflow: hidden;
		/* #ifdef H5 */
		margin: 105rpx 0 116rpx 0;
		/* #endif */
		/* #ifdef MP||APP-PLUS */
		margin: 210rpx 0 116rpx 0;
		/* #endif */
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




	.tab-bar-item {
		width: 100%;
		height: 110rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 9pt;
		color: #444;
		font-weight: 400;
	}

	.actove {
		width: 100%;
		height: 110rpx;
		background: #FF7104;
		color: #fff;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 9pt;
		font-weight: 400;
	}

	.left-box {
		width: 33.5%;
		position: fixed;
		left: 0;
		z-index: 10;
		background: rgb(242, 245, 249);
		height: 600rpx;
	}

	.right-box {
		width: 66.5%;
		position: fixed;
		box-sizing: border-box;
		left: 33.5%;
		background: #fff;
		height: 600rpx;
	}
</style>
