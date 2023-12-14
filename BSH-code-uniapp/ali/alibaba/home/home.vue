<template>
	<view class="ali-home-app">
		<view class="ali-home-app-header">
			<liuyuno-tabs :tabData="tabs" :defaultIndex="defaultIndex" @tabClick='tabClick' />
		</view>
		<!-- <seckill></seckill> -->
		<view class="ali-home-app-header-catory-two" v-if="typeList.length>0">		
			<view v-for="(item,index) in typeList" :key='index' v-if="index<10" @click="checkCatory(item)">
				<image :src="item.cover_url" mode=""></image>
				<text>{{item.name}}</text>
			</view>
		</view>
		<view :class="typeList.length>0?'ali-home-app-product':'ali-home-app-products'">
			<view class="product-item" v-for="(item,index) in goodsList" :key='index' @click="link(item.id)">
				<image :src="item.cover_url" mode="scaleToFill" class="product-item-logo"></image>
				<view class="product-item-name">{{item.name}}</view>
				<view class="product-item-money-buy">
					<view class="product-item-money">
						
						<text style="color:#FF7104;font-size: 28rpx;width: 100%;">兑换价￥{{item.price}}</text>
						<text style="font-size: 28rpx;width: 100%;margin: 20rpx;background: #FF7104;height: 60rpx;text-align: center;line-height: 60rpx;border-radius: 30rpx;color: #fff;">{{currencyAlias.silver_beans_alias}}兑换</text>
					</view>
				</view>
			</view>
		</view>
		<backTop :src="backTop.src"  :scrollTop="backTop.scrollTop"></backTop>
		<!--加载loadding-->
		<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="!pullUpOn" bgcolor="#FFFFFF"></main-nomore>
		<!--加载loadding-->
	</view>
</template>
<script>
	import liuyunoTabs from "@/components/liuyuno-tabs/liuyuno-tabs.vue";
	import backTop from '@/components/back-top/back-top.vue';
	import seckill from '@/components/seckill.vue';
	export default {
		components: {
			liuyunoTabs,
			backTop,
			seckill
		},
		data() {
			return {
				tabs: [], //一级分类
				defaultIndex: 0,
				typeList: [], //二级分类
				form:{
					page:1,
					ali_cat_id:'', 
					recommend:'',
				},
				goodsList:[],
				page_count:'',
				pullUpOn:true,
				loadding: false,
				backTop: {
					src: '../../../static/back-top/top.png',
					scrollTop: 0
				},
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		onLoad() {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
				uni.setNavigationBarTitle({
				  title: this.currencyAlias.silver_beans_alias+'区'
				});
			}
			this.getclassify()
		},
		methods: {
			tabClick(e) { //点击切换table
				console.log(e)
				for (let i = 0; i < this.tabs.length; i++) {
					if (e.index == i) {
						this.typeList = this.tabs[i].children
					}
				}
				this.form.ali_cat_id=e.item.ali_cat_id
				this.form.page=1
				if(e.item.name=='每日推荐'){
					this.form.recommend=1
				}else{
					this.form.recommend=0
				}
				this.goodsList=[]
				this.getHomegoods()
			},
			link(id) { //进入商品详情页
				uni.navigateTo({
					url: '../detail/detail?id=' + id
				})
			},
			getclassify() { //获取分类
				this.$http.request({
					url: this.$api.taolijin.getClassify,
					method: 'POST',
					data: '',
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.tabs = res.data
						this.tabs.unshift({
							name:"每日推荐",
							ali_cat_id:'',
							children:[],
							cover_url:'',
						})
						console.log(this.tabs)
						this.form.ali_cat_id=this.tabs[0].ali_cat_id
						this.form.recommend=1
						this.getHomegoods()
						this.typeList = this.tabs[0].children
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			getHomegoods() { //获取分类商品
				this.$http.request({
					url: this.$api.taolijin.getHomegoods,
					method: 'POST',
					data: this.form,
				}).then(res => {
					if (res.code == 0) {
						if(res.data.list.length==0)return false
						let list= res.data.list;
						var arr=this.goodsList.concat(list)
						this.goodsList =arr
						this.page_count = res.data.pagination.page_count;
						this.pullUpOn = true;
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			checkCatory(item){ //点击二级分类
				uni.navigateTo({
					url:'../searchList/searchList?name='+item.name+"&ali_cat_id="+item.ali_cat_id
				})
			}
		},
		onPageScroll(e) {
			this.backTop.scrollTop = e.scrollTop;
		},
		onReachBottom() {
			this.loadding = true;
			this.pullUpOn = true;
			if(this.form.page==this.page_count){
				this.pullUpOn = false;
				this.loadding=false
				return false;
			} 		
			this.form.page=this.form.page+1
			this.getHomegoods();
		},
	}
</script>

<style lang="less">
	.ali-home-app {
		width: 100%;
		overflow: hidden;
	}
	/* #ifdef H5*/
		.ali-home-app-header {
			width: 100%;
			overflow: hidden;
			background: #fff;
			position: fixed;
			top: 78rpx;
			left: 0;
			z-index: 999;
		}
	/* #endif */
	/* #ifdef MP|| APP-PLUS */
		.ali-home-app-header {
			width: 100%;
			overflow: hidden;
			background: #fff;
			position: fixed;
			top: 0rpx;  
			left: 0;
			z-index: 999;
		}
	/* #endif */
	/* #ifdef H5 */
	.ali-home-app-header-catory-two{
		width: 96%;
		background: #fff;
		margin: 108rpx auto 20rpx;
		overflow: hidden;
		display: flex;
		justify-content: space-evenly;
		flex-wrap: wrap;
		border-radius: 30rpx;
	}
	/* #endif */
	/* #ifdef MP || APP-PLUS*/
	.ali-home-app-header-catory-two{
		width: 96%;
		background: #fff;
		margin: 108rpx auto 20rpx;
		overflow: hidden;
		display: flex;
		/* #ifdef H5 || MP */
		justify-content: space-evenly;
		/* #endif */
		/* #ifdef APP-PLUS */
		justify-content: space-between;
		/* #endif */
		flex-wrap: wrap;
		border-radius: 30rpx;
	}
	/* #endif */
	.ali-home-app-header-catory-two view{
		width: 17%;
		overflow: hidden;
		margin: 10rpx 0 20rpx 0;
	}
	.ali-home-app-header-catory-two view image{
		width: 100%;
		height: 122rpx;
		border-radius: 50%;
	}
	.ali-home-app-header-catory-two view text{
		width: 100%;
		font-size:24rpx;
		display: block;
		text-align: center;
		overflow: hidden;
		text-overflow:ellipsis;
		white-space: nowrap;
	}
	.ali-home-app-product {
		width: 95%;
		overflow: hidden;
		margin: 50rpx auto 80rpx;
		display: flex;
		justify-content: space-between;
		flex-wrap: wrap;
	}
	.ali-home-app-products{
		width: 95%;
		overflow: hidden;
		/* #ifdef H5||MP*/
		margin: 100rpx auto 80rpx;
		/* #endif */
		/* #ifdef APP-PLUS*/
			margin: 100rpx auto 80rpx;
		/* #endif */
		display: flex;
		justify-content: space-between;
		flex-wrap: wrap;
	}
	.product-item {
		width: 48%;
		overflow: hidden;
		margin: 10rpx 0;
		border-radius: 10rpx;
		box-shadow: 0px 0px 10px #eee;
		border: 1px solid rgb(239, 239, 239);
	}

	.product-item-logo {
		width: 100%;
		height: 320rpx;
		display: block;
	}

	.product-item-name {
		width: 100%;
		height: 84rpx;
		overflow: hidden;
		-webkit-line-clamp: 2;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		text-overflow: ellipsis;
		font-size: 28rpx;
		margin-bottom: 24rpx;
		padding: 0 10rpx;
		color: #000000;
		box-sizing: border-box;
	}

	.product-item-money-buy {
		width: 100%;
		overflow: hidden;
		margin-bottom: 15rpx;
		padding: 0 10rpx;
		box-sizing: border-box;
	}

	.product-item-money {
		color: red;
		font-size: 28rpx;
		display: flex;
		justify-content: space-between;
		flex-wrap: wrap;
	}
</style>
