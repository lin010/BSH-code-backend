<template>
	<view class="container">
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
				<input confirm-type="search" placeholder="请输入店铺名" :focus="true" auto-focus
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

		<template v-if="shopList.length <= 0">
			<view class="tui-search-history" v-if="history.length>0" style="padding: 30rpx;box-sizing: border-box;">
				<view class="tui-history-header">
					<view class="tui-search-title">搜索历史</view>
					<tui-icon name="delete" :size='14' color='#333' @tap="openActionSheet" class="tui-icon-delete">
					</tui-icon>
				</view>
				<view class="tui-history-content">
					<view v-for="(item,index) in history" :key="index" @tap='navTo(item)'>
						<tui-tag type="gray" shape="square">{{item}}</tui-tag>
					</view>
				</view>
			</view>
		</template>

		<view class="shop_table_list" style="margin-top:20rpx;">
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
		<view class="shop_table_list" v-if="show">
			<view class="logo" style="width: 350rpx;height: 300rpx;margin: 100rpx auto;">
				<image :src="img_url+'shop_new_home.png'" mode="aspectFit"
					style="display: block;width: 150rpx;height: 150rpx;margin: 0 auto;"></image>
				<text
					style="display: block;width: 100%;text-align: center;margin-top: 20rpx;font-size: 30rpx;color: #FF7104;">暂无门店</text>
			</view>
		</view>


		<tui-actionsheet :show="showActionSheet" :tips="tips" @click="itemClick" @cancel="closeActionSheet">
		</tui-actionsheet>
	</view>

</template>

<script>
	import tuiIcon from "@/components/icon/icon"
	import tuiTag from "@/components/tag/tag"
	import tuiActionsheet from "@/components/actionsheet/actionsheet"

	export default {
		components: {
			tuiIcon,
			tuiTag,
			tuiActionsheet
		},
		data() {
			return {
				img_url: this.$api.img_url,
				searchVal: '',
				history: [],
				key: "",
				showActionSheet: false,
				tips: "确认清空搜索历史吗？",
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
				show: '',
				loadding: false,
				pullUpOn: true,
			}
		},
		onLoad(options) {
			var that = this;
			this.key = options.key;
			uni.getStorage({
				key: 'search-shop-history',
				success(res) {
					that.history.push(...res.data);
				},
			})
		},
		methods: {
			navTo(name) { //历史搜索
				this.key = name;
				this.search();
				/* this.page_count=0;//总页数
				this.shopList=[];
				this.form.page=1;
				this.city=uni.getStorageSync("shopCity").city
				this.form.keyword=name
				this.form.city_id=uni.getStorageSync("shopCity").city_id
				this.form.region_id=uni.getStorageSync("shopCity").region_id
				this.getshopList() */
			},
			search() { //输入搜索
				if (!this.key) {
					this.$http.toast('请输入店铺名');
					return;
				};
				var that = this;
				this.history.push(this.key);
				this.history = Array.from(new Set(this.history)); //去重
				uni.setStorage({ //将搜索记录存入缓存
					key: 'search-shop-history',
					data: that.history
				})
				this.page_count = 0; //总页数
				this.shopList = [];
				this.form.page = 1;
				//this.city=uni.getStorageSync("shopCity").city
				this.form.keyword = this.key
				//this.form.city_id=uni.getStorageSync("shopCity").city_id
				//this.form.region_id=uni.getStorageSync("shopCity").region_id
				this.getshopList()
			},
			back: function() {
				this.navBack();
			},
			cleanKey: function() {
				this.key = '';
				this.shopList = [];
			},
			closeActionSheet: function() {
				this.showActionSheet = false
			},
			openActionSheet: function() {
				this.showActionSheet = true
			},
			itemClick: function(e) {
				let index = e.index;
				if (index == 0) {
					this.showActionSheet = false;
					this.history = [];
					uni.removeStorage({ //清空缓存
						key: 'search-shop-history',
					});
				}
			},
			getshopList() { //获取门店列表
				this.$http
					.request({
						url: this.$api.moreShop.getshoplistall,
						method: 'POST',
						data: this.form,
					}).then(res => {
						this.loading = false;
						if (res.code == 0) {
							if (res.data.list.length == 0) {
								this.show = true
								return false
							}
							this.show = false
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
			shopdetail(id) { //店铺详情
				uni.navigateTo({
					url: '../detail/detail?store_id=' + id
				})
			},

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

<style>
	@import url("../../plugins/font-icon/iconfont1.css");
	page {
		color: #333;
		background: #fff;
	}

	.container {
		/* padding: 0 30rpx 30rpx 30rpx; */
		box-sizing: border-box;
	}

	.tui-searchbox {
		padding: 30rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
	}

	.tui-search-input {
		width: 100%;
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
		padding: 30rpx;
		box-sizing: border-box;
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
		background-color: #f2f2f2;
	}

	.shop_table_item {
		width: 95%;
		margin: 15rpx auto;
		background: #fff;
		display: flex;
		justify-content: space-evenly;
		overflow: hidden;
		border-radius: 20rpx;
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
