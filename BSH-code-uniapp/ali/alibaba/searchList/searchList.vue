<template>
	<view class="ali-home-app">
		<view class="ali-home-app-product">
			<view class="product-item" v-for="(item,index) in goodsList" :key='index' @click="link(item.id)">
				<image :src="item.cover_url" mode="scaleToFill" class="product-item-logo"></image>
				<view class="product-item-name">{{item.name}}</view>
				<view class="product-item-money-buy">
					<view class="product-item-money">
						<!--
						<text style="font-size: 28rpx;width: 100%;color:#c0c0c0;">原价￥{{item.origin_price}}</text>
						-->
						<text style="color:#FF7104;font-size: 28rpx;width: 100%;">兑换价￥{{item.price}}</text>
						<text
							style="font-size: 28rpx;width: 100%;margin: 20rpx;background: #FF7104;height: 60rpx;text-align: center;line-height: 60rpx;border-radius: 30rpx;color: #fff;">{{currencyAlias.silver_beans_alias}}兑换</text>
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
	import backTop from '@/components/back-top/back-top.vue';
	export default {
		components: {
			backTop
		},
		data() {
			return {
				form:{
					page:1,
					ali_cat_id:'',
				},
				goodsList:[],
				page_count:'',
				pullUpOn:true,
				loadding:false,
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
		onLoad(options) {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
				
			}
			if(options&&options.name&&options.ali_cat_id){
				this.form.ali_cat_id=options.ali_cat_id
				this.getHomegoods()
				uni.setNavigationBarTitle({
					title:options.name
				})
			}
		},
		methods: {
			link(id) { //进入商品详情页
				uni.navigateTo({
					url: '../detail/detail?id=' + id
				})
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
						this.pullUpOn=true
					} else {
						this.$http.toast(res.msg);
					}
				});
			}
		},
		onPageScroll(e) {
			this.backTop.scrollTop = e.scrollTop;
		},
		onReachBottom() {
			this.loadding = true;
			this.pullUpOn = true;
			if(this.form.page==this.page_count){
				this.loadding = false
				this.pullUpOn=false
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
	.ali-home-app-product {
		width: 95%;
		overflow: hidden;
		margin: 30rpx auto 80rpx;
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
