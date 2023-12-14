<template>
	<view class="container">
		<!--header-->
		<view class="tui-header-box">
			<view class="tui-header" :style="{width:width+'px',height:height+'px'}">
				<view class="tui-back" :style="{marginTop:arrowTop+'px'}" @tap="back">
					<tui-icon name="arrowleft" :size="28" color="#000"></tui-icon>
				</view>
				<view :style="{marginTop:arrowTop+'px'}" class="search-box">
					<view class="tui-searchbox tui-search-mr" @tap="search(searchKey)">
						<!-- :style="{marginTop:inputTop+'px'}" -->
						<!-- #ifdef APP-PLUS || MP -->
						<icon type="search" :size='13' color='#999'></icon>
						<!-- #endif -->
						<text class="tui-search-text" v-if="!searchKey">搜索商品</text>
						<view class="tui-search-key" v-if="searchKey">
							<view class="tui-key-text">{{searchKey}}</view>
							<tui-icon name="shut" :size='12' color='#fff'></tui-icon>
						</view>
					</view>
					<!-- 切换商品列表样式 -->
					<view class="tui-top-item switchStyle" @tap="screen" data-index="2">
						<tui-icon :name="isList?'manage':'listview'" :size="isList? 22 : 18" :bold="isList?false:true" color="#333"></tui-icon>
					</view>
					<!-- 切换商品列表样式 -->
				</view>
			</view>
		</view>
		<!--header-->

		<!--list-->
		<view class="tui-product-list" :style="{marginTop:(dropScreenH+20)+'px'}">
		<!-- <view class="tui-product-list" style="margin-top:70rpx"> -->
			<view class="tui-product-container" :style="{width: isList ? '100%' : '49.2%'}">
				<block v-for="(item,index) in productList" :key="index" v-if="(index+1)%2!=0 || isList">
					<!-- <template is="productItem" data="{{item,index:index,isList:isList}}" /> -->
					<!--商品列表-->
					<view class="tui-pro-item" :class="[isList?'tui-flex-list':'']" hover-class="hover" :hover-start-time="150" @tap="detail(item.id)">
						<image :src="item.cover_pic" class="tui-pro-img" :class="[isList?'tui-proimg-list':'']"
						 mode="widthFix" />
						<view class="tui-pro-content">
							<view class="tui-pro-tit">{{item.name}}</view>
							<view>
								<view class="tui-pro-price flex flex-y-center">
									<view class="tui-sale-price" :style="{color:textColor}">￥{{item.price}}</view>
									<view class="tui-factory-price">￥{{item.original_price}}</view>
								</view>
								<view class="tui-pro-pay" v-if="item.is_show_sales == 1">{{item.sales || 0}}人付款</view>
							</view>
						</view>
					</view>
					<!--商品列表-->
				</block>
			</view>
			<view class="tui-product-container" v-if="!isList">
				<block v-for="(item,index) in productList" :key="index" v-if="(index+1)%2==0">
					<!-- <template is="productItem" data="{{item,index:index}}" /> -->
					<!--商品列表-->
					<view class="tui-pro-item" :class="[isList?'tui-flex-list':'']" hover-class="hover" :hover-start-time="150" @tap="detail(item.id)">
						<image :src="item.cover_pic" class="tui-pro-img" :class="[isList?'tui-proimg-list':'']"
						 mode="widthFix" />
						<view class="tui-pro-content">
							<view class="tui-pro-tit">{{item.name}}</view>
							<view>
								<view class="tui-pro-price flex flex-y-center">
									<view class="tui-sale-price" :style="{color:textColor}">￥{{item.price}}</view>
									<view class="tui-factory-price">￥{{item.original_price}}</view>
								</view>
								<view class="tui-pro-pay" v-if="item.is_show_sales == 1">{{item.sales || 0}}人付款</view>
							</view>
						</view>
					</view>
					<!--商品列表-->
				</block>
			</view>
		</view>
		<!--list-->

		<!--加载loadding-->
		<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="pullUpOn" bgcolor="#f7f7f7"></main-nomore>
		<!--加载loadding-->
		
		<main-tabbar></main-tabbar>
	</view>
</template>

<script>
	import tuiIcon from "@/components/icon/icon";
	import tuiDrawer from "@/components/drawer/drawer";
	import tuiTopDropdown from "@/components/top-dropdown/top-dropdown";
	export default {
		components: {
			tuiIcon,
			tuiDrawer,
			tuiTopDropdown
		},
		data() {
			return {
				statusBarHeight:0,//状态栏高度
				searchKey: "", //搜索关键词
				width: 200, //header宽度
				height: 64, //header高度
				inputTop: 0, //搜索框距离顶部距离
				arrowTop: 0, //箭头距离顶部距离
				dropScreenH: 0, //下拉筛选框距顶部距离
				isList: false, //是否以列表展示  | 列表或大图
				drawer: false,
				cat_id:0,
				productList: [],
				page: 1,
				loadding: false,
				pullUpOn:false,
				page_count:0,//总页数
				carryOut:true,//请求完成
				searchKey:'',//搜索内容的字段
				textColor:'#bc0100',
				mch_id:""
			}
		},
		onLoad: function(options) {
			this.textColor = this.globalSet('textCol');
			this.mch_id=options.mch_id;
			this.cat_id = options.cat_id;
			options.cat_id ? this.cat_id = options.cat_id : this.cat_id = '';
			this.searchKey = options.searchKey;
			this.getData();
			
			let obj = {};
			// #ifdef MP-WEIXIN
			obj = wx.getMenuButtonBoundingClientRect();
			// #endif
			// #ifdef MP-BAIDU
			obj = swan.getMenuButtonBoundingClientRect();
			// #endif
			// #ifdef MP-ALIPAY
			my.hideAddToDesktopMenu();
			// #endif
			
			uni.getSystemInfo({
				success: (res) => {
					this.statusBarHeight = res.statusBarHeight;
					this.width = obj.left || res.windowWidth;
					this.height = obj.top ? (obj.top + obj.height + 8) : (res.statusBarHeight + 44);
					this.inputTop = obj.top ? (obj.top + (obj.height - 30) / 2) : (res.statusBarHeight + 7);
					this.arrowTop = obj.top ? (obj.top + (obj.height - 32) / 2) : (res.statusBarHeight + 6);
					this.searchKey = options.searchKey || "";
					this.dropScreenH = this.height - 10;
					// this.dropScreenH = this.height * 750 / res.windowWidth + 186;
				}
			})
		},
		methods: {
			getData(){ //获取数据
				this.loadding = true;
				var mch_id=uni.getStorageSync('mch_id')
				var label="产地";
				this.$http.request({
					url:this.$api.moreShop.getmchgods,
					// showLoading:true,
					data:{
						cat_id:this.cat_id,
						// cat_id:0,
						keyword:this.searchKey,
						mch_id:mch_id,
						page:this.page,
						limit:6
					}
				}).then((res)=>{
					if(res.code == 0){
						this.productList = this.productList.concat(res.data.list);
						this.page_count = res.data.page_count;
						this.loadding = false;
						if(this.page >= res.data.page_count){
							this.pullUpOn = true;
						}
					}
				})
			},
			screen(e) {
				let index = e.currentTarget.dataset.index;
				if (index == 0) {
					this.showDropdownList();
				} else if (index == 1) {
					this.tabIndex = 1
				} else if (index == 2) {
					this.isList = !this.isList
				} else if (index == 3) {
					this.drawer = true
				}
			},
			closeDrawer() {
				this.drawer = false
			},
			back() {
				if (this.drawer) {
					this.closeDrawer()
				} else {
					let routes = getCurrentPages();
					if(routes.length == 1){
						uni.redirectTo({
							url: '/pages/index/index'
						})
					}else{
						uni.navigateBack();
					}
				}
			},
			search(key) {
				/* uni.navigateTo({
					url: '/pages/search/search?key='+key
				}) */
				uni.redirectTo({
					url: '/pages/search/search?key='+key
				})
			},
			detail(id) {
				uni.navigateTo({
					url: '/pages/goods/detail?proId='+id
				})
			}
		},
		onReachBottom() {
			this.page++;
			if(this.page <= this.page_count){
				this.getData();
			}
			// else{
			// 	this.pullUpOn = true;
			// }
		}
	}
</script>

<style>
	page {
		background: #f7f7f7;
	}

	.container {
		padding-bottom: env(safe-area-inset-bottom);
	}

	/* 隐藏scroll-view滚动条*/

	::-webkit-scrollbar {
		width: 0;
		height: 0;
		color: transparent;
	}

	.tui-header-box {
		width: 100%;
		background: #fff;
		position: fixed;
		z-index: 99998;
		left: 0;
		top: 0;
	}

	.tui-header {
		display: flex;
		align-items: flex-start;
		position: relative;
	}

	.tui-back {
		margin-left: 8rpx;
		height: 32px !important;
		width: 32px !important;
		position: relative;
		top:-9rpx;
	}

	.tui-searchbox {
		width: 80%;
		height: 30px;
		margin-right: 30rpx;
		border-radius: 15px;
		font-size: 9pt;
		background: #f7f7f7;
		padding: 3px 10px;
		box-sizing: border-box;
		color: #999;
		display: flex;
		align-items: center;
		overflow: hidden;
	}

	/* #ifdef MP-WEIXIN */
	.tui-search-mr {
		margin-right: 20rpx !important;
	}

	/* #endif */
	/* #ifdef MP-BAIDU */
	.tui-search-mr {
		margin-right: 20rpx !important;
	}

	/* #endif */

	.tui-search-text {
		padding-left: 16rpx;
	}

	.tui-search-key {
		max-width: 80%;
		height: 100%;
		padding: 0 16rpx;
		margin-left: 12rpx;
		display: flex;
		align-items: center;
		border-radius: 15px;
		background: rgba(0, 0, 0, 0.5);
		color: #fff;
	}

	.tui-key-text {
		box-sizing: border-box;
		padding-right: 12rpx;
		font-size: 9pt;
		line-height: 12px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	/* 商品列表*/

	.tui-product-list {
		display: flex;
		justify-content: space-between;
		flex-direction: row;
		flex-wrap: wrap;
		box-sizing: border-box;
	}

	.tui-product-container {
		width: 49.2%;
		margin-right: 10rpx;
	}

	.tui-product-container:last-child {
		margin-right: 0;
	}

	.tui-pro-item {
		width: 100%;
		margin-bottom: 10rpx;
		background: #fff;
		box-sizing: border-box;
		/* border-radius: 12rpx; */
		overflow: hidden;
		transition: all 0.15s ease-in-out;
	}

	.tui-flex-list {
		display: flex;
		margin-bottom: 16rpx !important;
	}

	.tui-pro-img {
		width: 100%;
		display: block;
	}

	.tui-proimg-list {
		width: 260rpx;
		height: 260rpx !important;
		flex-shrink: 0;
		/* border-radius: 12rpx; */
	}

	.tui-pro-content {
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		box-sizing: border-box;
		padding: 20rpx;
	}

	.tui-pro-tit {
		color: #2e2e2e;
		font-size: 9pt;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
	}

	.tui-pro-price {
		padding-top: 18rpx;
		flex-wrap: wrap;
	}

	.tui-sale-price {
		font-size: 13pt;
		font-weight: 500;
		color: #e41f19;
		padding-right: 12rpx;
	}

	.tui-factory-price {
		font-size: 9pt;
		color: #a0a0a0;
		text-decoration: line-through;
	}

	.tui-pro-pay {
		padding-top: 10rpx;
		font-size: 9pt;
		color: #656565;
	}

	/* 商品列表*/
	
	/* .switchStyle{
		position: absolute;
		top: 50%;
		right: 20rpx;
		transform: translateY(-50%);
	} */
	.search-box{
		flex: 1;
		display: flex;
		justify-content: space-between;
		align-items: center;
		height: 30px;
	}
	.switchStyle{
		margin-right: 20rpx;
	}
</style>
