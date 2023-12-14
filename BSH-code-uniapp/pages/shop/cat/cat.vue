<template>
	<view>
		<!-- 二级和三级分类样式 -->
		<tabar :current="current" :background="background"></tabar>
		<view class="container" v-if="catStyle == 2 || catStyle == 3">
			<!-- 左边活动选项 -->
			<scroll-view scroll-y scroll-with-animation class="tab-view" :scroll-top="scrollTop" :style="{height:height+'px',top:top+'px'}">
				<view v-for="(item,index) in leftTabbar" :key="index" class="tab-bar-item"
				 :data-current="index" @tap.stop="swichNav(index,item.id)">
					<text :class="[currentTab==index ? 'active' : '']" :style="{background:currentTab==index ? textColor :''}">{{item.name}}</text>
				</view>
				<view style="width: 100%;height: 120rpx;"></view>
			</scroll-view>
			<block v-for="(item,index) in leftTabbar" :key="index">
				<scroll-view scroll-y class="right-box" :style="{height:height+'px',top:top+'px'}" v-if="currentTab==index">
					<!--内容部分 start 自定义可删除-->
					<view class="page-view">
						<!-- 轮播图 -->
						<swiper indicator-dots autoplay circular :interval="5000" :duration="150" class="swiper">
							<block v-for="(items,indexs) in leftTabbar" :key='indexs'>
								<swiper-item v-if="items.advert_pic != ''" @tap.stop="detail(items.advert_url)">
									<image :src="items.advert_pic" class="slide-image" />
								</swiper-item>
							</block>
						</swiper>
						<!-- <image :src="item.advert_pic" mode="widthFix" style="width: 100%;max-height: 320rpx;"></image> -->
						
						<!-- 右边商品楼层 -->
						<view class="class-box">
							<!-- 二级分类样式 -->
							<view class="class-item" v-if="catStyle == 2">
								<view class="g-container">
									<view class="g-box" v-for="(pitem,index) in leftTabbar[currentTab].child" @tap.stop="productList($event,pitem.id,leftTabbar[currentTab].id)" :data-key="pitem.name" :key="index">
										<image :src="pitem.pic_url" class="g-image" />
										<view class="g-title">{{pitem.name}}</view>
									</view>
								</view>
							</view>
							
							<!-- 三级分类样式 -->
							<view class="class-item" v-for="(citem,index) in leftTabbar[currentTab].child" v-if="catStyle == 3" :key="index">
								<view class="class-name">{{citem.name}}</view>
								<view class="g-container">
									<view class="g-box" v-for="(pitem,i) in citem.child" :key="i" @tap.stop="productList($event,pitem.id,leftTabbar[currentTab].id)" :data-key="pitem.name">
										<image :src="pitem.pic_url" class="g-image" />
										<view class="g-title">{{pitem.name}}</view>
									</view>
								</view>
							</view>
							<view style="width: 100%;height: 120rpx;"></view>
						</view>
					</view>
					<!--内容部分 end 自定义可删除-->
				</scroll-view>
			</block>
			
			<main-tabbar></main-tabbar>
			<placeholder :placeholderHeight='placeholderHeight3'></placeholder>
		</view>
		
		<!-- 一级分类样式 -->
		<view class="container" v-if="catStyle == 1">
			<view class="tui-searchbox">
				<view class="tui-search-input" @tap="search">
					<!-- #ifdef APP-PLUS || MP -->
					<icon type="search" size='13' color='#999'></icon>
					<!-- #endif -->
					<text class="tui-search-text">搜索商品</text>
				</view>
			</view>
			<!-- 左边活动选项 -->
			<scroll-view scroll-y scroll-with-animation class="tab-view" :scroll-top="scrollTop" :style="{height:height+'px',top:top+'px'}">
				<view v-for="(item,index) in leftTabbar" :key="index" class="tab-bar-item"
				 :data-current="index" @tap.stop="swichNav(index,item.id)">
					<text :class="[currentTab==index ? 'active' : '']" :style="{background:currentTab==index ? textColor :''}">{{item.name}}</text>
				</view>
				
				<view style="width: 100%;height: 120rpx;"></view>
			</scroll-view>
			
			<block v-for="(item,index) in leftTabbar" :key="index">
				<scroll-view scroll-y class="right-box" :style="{height:height+'px',top:top+'px'}" v-if="currentTab==index">
					<!--内容部分 start 自定义可删除-->
					<view class="page-view">
						<!-- 轮播图 -->
						<swiper indicator-dots autoplay circular :interval="5000" :duration="150" class="swiper">
							<swiper-item v-for="(items,index) in leftTabbar" @tap.stop="detail(items.advert_url)" :key="index">
								<image :src="items.advert_pic" class="slide-image" />
							</swiper-item>
						</swiper>
						
						<view class="class-box">
							<!-- 右边商品楼层 -->
							<view class="pro-item" v-for="(items,indexs) in rightTabbar" :key='indexs' @tap="navTo(items.id)">
								<view class="pro-left">
									<image class="pro-img" :src="items.cover_pic"></image>
								</view>
								<view class="pro-right">
									<view class="pro-name">{{items.name}}</view>
									<view class="pro-content">
										<view>
											<view class="pro-price" :style="{color:textColor}">&yen;{{items.price}}</view>
											<view class="pro-sale" v-if="items.is_show_sales == 1">
												<text>{{items.sales || 0}}</text>
											</view>
										</view>
										<view class="cart-icon" :style="{border:'1px solid'+textColor}">
											<view class="iconfont icon-gouwuche" :style="{color:textColor}"></view>
										</view>
									</view>
								</view>
							</view>
							<view style="width: 100%;height: 120rpx;"></view>
						</view>
					</view>
					<!--内容部分 end 自定义可删除-->
				</scroll-view>
			</block>
			
			<main-tabbar></main-tabbar>
			<main-loading :visible="loading"></main-loading>
			<placeholder :placeholderHeight='placeholderHeight3'></placeholder>
		</view> 
	</view>
</template>

<script>
	import commodity from "@/components/commodity/commodity.vue";
	import tabar from '@/components/tabar.vue'
	export default {
		components:{
			commodity,
			tabar
		},
		data() {
			return {
				current:1,
				background:"#53DEDB",
				id:-1,//跳转指定分类id
				//空白占位符高度
				placeholderHeight3:70,
				// cur:0,//默认索引
				leftTabbar:[], //左边tab数据
				rightTabbar:[], //右边tab数据
				catStyle:1,  //分类样式 9是一级  4是二级 7是三级
				height: 0, //scroll-view高度
				top: 0,
				currentTab: 0, //预设当前项的值
				scrollTop: 0 ,//tab标题的滚动条位置
				loading: false,
				textColor:'#bc0100',
				mch_id:""
			}
		},
		onLoad: function(options) {
			var mch_id=uni.getStorageSync('mch_id')
			this.mch_id=mch_id
			if(options.id){
				this.id = options.id;
			}
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
			this.getLeftData();
			setTimeout(() => {
				uni.getSystemInfo({
					success: (res) => {
						let header = 0;
						let top = 0;
						//#ifdef H5
						top = 44;
						//#endif
						this.height = res.windowHeight - uni.upx2px(header);
						this.top = top + uni.upx2px(header);
					}
				});
			}, 50)
		},
		onShow() {
			this.tabCurrent = 1;
		},
		methods: {
			getRightData(cat_id){//获取右边的数据
			    return false;
				this.loading = true;
				this.$http.request({
					url:this.$api.goods.rightList,
					data:{
						cat_id
					},
				}).then((res)=>{
					this.loading = false;
					if(res.code == 0){
						this.rightTabbar = res.data.list;
					}
				})
			},
			getLeftData(){//获取左边tab数据
				this.loading = true;
				var mch_id=this.mch_id
				this.$http.request({
					url:this.$api.moreShop.getmchgodscats,
					data:{mch_id},
					method:'post'
				}).then((res)=>{
					this.loading = false;
					if(res.code == 0){
						this.leftTabbar = res.data.list;
						this.leftTabbar.forEach((item,index) => {
							if(this.id == item.id){
								this.currentTab = index;
							}
						})
						this.catStyle = 2
						this.id == -1 ? this.getRightData(res.data.list[0].id) : this.getRightData(this.id);
					}
				})
			},
			navTo(id){
				uni.navigateTo({
					url:'/pages/goods/detail?proId='+id
				})
			},
			// 点击标题切换当前页时改变样式
			swichNav(index,id) {
				this.rightTabbar = [];
				// this.cur = index;
				this.currentTab = index;
				this.checkCor();
				this.getRightData(id);
			},
			//判断当前滚动超过一屏时，设置tab标题滚动条。
			checkCor: function() {
				let that = this;
				//这里计算按照实际情况进行修改，动态数据要进行动态分析
				//思路：窗体高度/单个分类高度 200rpx 转px计算 =>得到一屏幕所显示的个数，结合后台传回分类总数进行计算
				//数据很多可以多次if判断然后进行滚动距离计算即可
				if (that.currentTab > 7) {
					that.scrollTop = 500
				} else {
					that.scrollTop = 0
				}
			},
			//轮播图跳转事件
			detail(url) {
				uni.navigateTo({
					url
				})
			},
			//右边楼层商品跳转事件
			productList(e,cartId,id) {
				let key = e.currentTarget.dataset.key;
				uni.navigateTo({
					url: `/pages/shop/goods/list?searchKey=${key}&cat_id=${cartId}`
				})
			},
			//搜索框跳转事件
			search: function() {
				uni.navigateTo({
					url: '/pages/search/search'
				})
			}
		}
	}
</script>

<style scoped>
	page {
		background: #fcfcfc;
	}

	/* 左侧导航布局 start*/

	/* 隐藏scroll-view滚动条*/

	::-webkit-scrollbar {
		width: 0;
		height: 0;
		color: transparent;
	}

	.tui-searchbox {
		width: 100%;
		height: 92rpx;
		padding: 0 30rpx;
		box-sizing: border-box;
		background: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
		position: fixed;
		left: 0;
		top: 0;
		/* #ifdef H5 */
		top: 44px;
		/* #endif */
		z-index: 100;
	}

	.tui-searchbox::after {
		content: '';
		position: absolute;
		border-bottom: 1rpx solid #d2d2d2;
		-webkit-transform: scaleY(0.5);
		transform: scaleY(0.5);
		bottom: 0;
		right: 0;
		left: 0;
	}

	.tui-search-input {
		width: 100%;
		height: 60rpx;
		background: #f1f1f1;
		border-radius: 30rpx;
		font-size: 11pt;
		color: #999;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.tui-search-text {
		padding-left: 16rpx;
	}

	.tab-view {
		/* height: 100%; */
		width: 200rpx;
		position: fixed;
		left: 0;
		z-index: 10;
		background: #FFFFFF;
	}

	.tab-bar-item {
		width: 200rpx;
		height: 110rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 10pt;
		color: #444;
		font-weight: 400;
	}

	.active {
		color: #FFFFFF;
		background: linear-gradient(to right,#f24f34,#c90a0c);
		padding: 8rpx 26rpx;
		border-radius: 50rpx;
	}

	/* .active::before {
		content: "";
		position: absolute;
		border-left: 8rpx solid #E41F19;
		height: 30rpx;
		left: 0;
	} */

	/* 左侧导航布局 end*/

	.right-box {
		width: 100%;
		position: fixed;
		padding-left: 220rpx;
		box-sizing: border-box;
		left: 0;
	}

	.page-view {
		width: 100%;
		overflow: hidden;
		padding-top: 20rpx;
		padding-right: 20rpx;
		box-sizing: border-box;
		padding-bottom: env(safe-area-inset-bottom);
	}

	.swiper {
		width: 100%;
		height: 220rpx;
		border-radius: 12rpx;
		overflow: hidden;
		transform: translateZ(0);
	}

	/* #ifdef APP-PLUS || MP */
	.swiper .wx-swiper-dot {
		width: 8rpx;
		height: 8rpx;
		display: inline-flex;
		background: none;
		justify-content: space-between;
	}

	.swiper .wx-swiper-dot::before {
		content: '';
		flex-grow: 1;
		background: rgba(255, 255, 255, 0.8);
		border-radius: 16rpx;
		overflow: hidden;
	}

	.swiper .wx-swiper-dot-active::before {
		background: #fff;
	}

	.swiper .wx-swiper-dot.wx-swiper-dot-active {
		width: 16rpx;
	}

	/* #endif */

	/* #ifdef H5 */
	>>>.swiper .uni-swiper-dot {
		width: 8rpx;
		height: 8rpx;
		display: inline-flex;
		background: none;
		justify-content: space-between;
	}

	>>>.swiper .uni-swiper-dot::before {
		content: '';
		flex-grow: 1;
		background: rgba(255, 255, 255, 0.8);
		border-radius: 16rpx;
		overflow: hidden;
	}

	>>>.swiper .uni-swiper-dot-active::before {
		background: #fff;
	}

	>>>.swiper .uni-swiper-dot.uni-swiper-dot-active {
		width: 16rpx;
	}

	/* #endif */

	.slide-image {
		width: 100%;
		height: 220rpx;
	}

	.class-box {
		padding-top: 30rpx;
	}

	.class-item {
		background: #fff;
		width: 100%;
		box-sizing: border-box;
		padding: 20rpx;
		margin-bottom: 20rpx;
		border-radius: 12rpx;
	}

	.class-name {
		font-size: 9pt;
	}

	.g-container {
		/* padding-top: 20rpx; */
		display: flex;
		display: -webkit-flex;
		justify-content: flex-start;
		flex-direction: row;
		flex-wrap: wrap;
	}

	.g-box {
		width: 33.3333%;
		text-align: center;
		padding-top: 40rpx;
	}

	.g-image {
		width: 120rpx;
		height: 120rpx;
	}

	.g-title {
		font-size: 9pt;
	}
	
	.pro-item{
		background: #FFFFFF;
		display: flex;
		justify-content: space-between;
		border-bottom: 2rpx solid #f6f6f6;
		padding: 10rpx 20rpx 10rpx 10rpx;
	}
	.pro-left{
		overflow: hidden;
		/* border-radius: 30rpx; */
		width: 160rpx;
		height: 160rpx;
	}
	.pro-img{
		width: 160rpx;
		height: 160rpx;
	}
	.pro-right{
		flex: 1;
		display: flex;
		flex-direction: column;
		justify-content: space-around;
		padding-left: 16rpx;
		box-sizing: border-box;
	}
	.pro-name{
		font-size: 9pt;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
		overflow: hidden;
		line-height: 30rpx;
	}
	.pro-content{
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.pro-price{
		color: #bc0100;
		font-size: 11pt;
		line-height: 40rpx;
	}
	.pro-sale{
		font-size: 7pt;
		color: #b3b3b3;
	}
	.cart-icon{
		border: 2rpx solid #bc0100;
		border-radius: 50%;
		width: 46rpx;
		height: 46rpx;
		text-align: center;
		line-height: 50rpx;
		color: #bc0100;
	}
</style>