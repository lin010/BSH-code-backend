<template>
	<view class="shopDetail">
		<view class="map">
			<map style="width: 100%; height: 200px;" :latitude="form.latitude" :longitude="form.longitude"
				:markers="form.markers"></map>
		</view>
		<view class="shopDetail-message">
			<view class="shopDetail-message-item">
				<view style="width: 10%;text-align: center;">•</view>
				<view style="width: 90%;display: flex;justify-content: space-evenly;flex-wrap: wrap;">
					<text>营业信息</text>
					<text>{{form.business_hours}}</text>
				</view>
			</view>
			<view class="shopDetail-message-item">
				<view style="width: 10%;text-align: center;">•</view>
				<view style="width: 90%;display: flex;justify-content: space-evenly;flex-wrap: wrap;">
					<text>地址</text>
					<text>{{form.address}}</text>
				</view>
			</view>
		</view>
		<view class="shopDetail_location" @click="location">
			<image :src="img_url+'dao_location.png'" mode=""
				style="width: 60rpx;height: 60rpx;display: block;float: left;"></image>
			<text style="display: block;float: right;line-height: 60rpx;color: #000;">导航</text>
		</view>
		<view class="tui-searchbox">
			<view class="tui-search-input">
				<!-- #ifdef APP-PLUS || MP -->
				<icon type="search" :size='13' color='#333'></icon>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<view>
					<com-icons type="search" :size='16' color='#333333'></com-icons>
				</view>
				<!-- #endif -->
				<input confirm-type="search" placeholder="请输入商户名" :focus="true" auto-focus
					placeholder-class="tui-input-plholder" class="tui-input" v-model.trim="key" @confirm='search' />
				<!-- #ifdef APP-PLUS || MP -->
				<icon type="clear" :size='13' color='#bcbcbc' @tap="cleanKey" v-show="key"></icon>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<view @tap="cleanKey" v-show="key">
					<tui-icon name="close-fill" :size='16' color='#bcbcbc'></tui-icon>
				</view>
				<!-- #endif -->
			</view>
			<view class="tui-cancle" @tap="search">搜索</view>
		</view>
		<view class="shop_table_list">
			<view class="shop_table_item" v-for="(item,index) in shopList" :key='index' @click="link(item.id)">
				<view class="shop_table_item_left">
					<image :src="item.cover_url" mode="aspectFit"></image>
				</view>
				<view class="shop_table_item_right">
					<view class="shop_table_item-name">
						{{item.name}}
					</view>
					<view class="shop_table_item-score">
						<view class="iconfont iconwujiaoxing" v-for="(i,index) in  Number(item.score)"
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
	</view>
</template>

<script>
	import tuiIcon from "@/components/icon/icon";
	import tuiTag from "@/components/tag/tag";
	export default {
		components: {
			tuiIcon,
			tuiTag,
		},
		data() {
			return {
				key: "",
				img_url: this.$api.img_url,
				form: {},
				shopList: [],
				page_count: '',
				datas: {
					cat_id: '',
					city_id: uni.getStorageSync("shopCity").city_id,
					region_id: uni.getStorageSync("shopCity").region_id,
					keyword: '',
					sort_by: '',
					distance: '',
					page: 1,
				},
				loadding: false,
				pullUpOn: true,
			};
		},
		onLoad(options) {
			let that = this
			this.form = {
				business_hours: options.business_hours,
				address: options.address,
				latitude: options.latitude,
				longitude: options.longitude,
				markers: [{
					id: 1,
					latitude: options.latitude, //纬度
					longitude: options.longitude, //经度
					iconPath: that.img_url + 'newshop_loca.png'
				}],
			}
			uni.setNavigationBarTitle({
				title: options.name
			});
			this.getshopList();
		},
		methods: {
			cleanKey: function() { //清空搜索
				this.key = ''
			},
			search() {
				this.shopList = [];
				this.page_count = '',
					this.datas.page = 1
				this.datas.keyword = this.key
				this.getshopList()
			},
			getshopList() { //获取门店列表
				this.$http
					.request({
						url: this.$api.moreShop.getshoplistall,
						method: 'POST',
						data: this.datas,
					})
					.then(res => {
						if (res.code == 0) {
							if (res.data.list.length == 0) return false
							let list = res.data.list;
							var arr = this.shopList.concat(list)
							this.shopList = arr
							this.page_count = res.data.pagination.page_count;
							this.pullUpOn = true;
						} else {
							this.$http.toast(res.msg);
						}
					});
			},
			link(id) {
				uni.redirectTo({
					url: '../detail/detail?store_id=' + id
				})
			},
			location() {
				let lat = this.form.latitude;
				let lnt = this.form.longitude;
				let addrress = this.form.address;
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
		},
		onReachBottom() {
			this.loadding = true;
			this.pullUpOn = true;
			if (this.datas.page == this.page_count) {
				this.loadding = false;
				this.pullUpOn = false;
				return false;
			}
			this.datas.page = this.datas.page + 1
			this.getshopList()
		},
	}
</script>

<style lang="less" scoped>
	@import url("../../plugins/font-icon/iconfont1.css");

	.shopDetail {
		width: 100%;
		overflow: hidden;
	}

	.shopDetail-message {
		margin: 10rpx 0 20rpx 0;
		padding: 20rpx;
		box-sizing: border-box;
	}

	.shopDetail-message-item {
		width: 100%;
		overflow: hidden;
		margin: 30rpx 0;
		display: flex;
		justify-content: space-evenly;
	}

	.shopDetail-message-item view text {
		display: block;
		width: 100%;
		margin-bottom: 5rpx;
		color: #000;
		font-size: 30rpx;
	}

	.shopDetail_location {
		width: 20%;
		margin: 0 auto;
		overflow: hidden;
	}

	.map {
		width: 100%;
		overflow: hidden;
		margin: 20rpx 0;
	}

	.tui-searchbox {
		padding: 30rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
	}

	.tui-search-input {
		width: 90%;
		height: 66rpx;
		border-radius: 35rpx;
		padding: 0 30rpx;
		box-sizing: border-box;
		background: #f2f2f2;
		display: flex;
		align-items: center;
		flex-wrap: nowrap;
	}

	.tui-input {
		flex: 1;
		color: #333;
		padding: 0 16rpx;
		font-size: 11pt;
	}

	.tui-input-plholder {
		font-size: 11pt;
		color: #b2b2b2;
	}

	.tui-cancle {
		color: #888;
		font-size: 11pt;
		padding-left: 30rpx;
		flex-shrink: 0;
	}

	.tui-history-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 30rpx 0;
	}

	.tui-icon-delete {
		padding: 10rpx;
	}

	.tui-search-title {
		font-size: 11pt;
		font-weight: bold;
	}

	.tui-hot-header {
		padding: 30rpx 0;
	}

	.tui-tag-class {
		display: inline-block;
		margin-bottom: 20rpx;
		margin-right: 20rpx;
		font-size: 9pt !important;
	}

	.tui-history-content {
		display: flex;
		flex-wrap: wrap;
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
