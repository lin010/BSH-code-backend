<template>
	<view class="vouchers-container">
		<view class="goods" v-if="list">
			<view v-for="(item,index) in list" :key="index" class="goods_item" @click="linkurl(item.id)">
				<image :src="item.cover_pic" mode="aspectFill"></image>
				<view class="item_name">
					{{item.name}}
				</view>
				<view class="money_num">
					<view class="money" style="width: 50%;color: rgb(255, 113, 4);">
						<text style="font-size: 26rpx;">￥</text>
						<text style="font-size: 28rpx;">{{item.price}}</text>
					</view>
					<view style="width: 50%;font-size: 24rpx;line-height: 50rpx;text-align: right;">
						<text>{{item.sales}}</text>
					</view>
				</view>
				<view class="send" style="position: relative;">
					<view class="send_imag"></view>
					<text style="font-size: 27rpx;position: absolute;top: 11rpx;left: 80rpx;color: #fff;">{{item.got_shopping_voucher_num}}{{currencyAlias.silver_beans_alias}}</text>	
				</view>
			</view>
		</view>
		<main-loadmore :visible="loading" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="!pullUpOn" bgcolor="#FFFFFF"></main-nomore>
	</view>
</template>

<script>
	export default {
		name:"vouchers",
		props:{
			page: Number
		},
		data() {
			return {
				img_url: this.$api.img_url,
				plugins_img_url: this.$api.plugins_img_url,
				loading: false,
				pullUpOn: true,
				loading: false,
				page_count:'',
				list: '',
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		watch:{
			page(val, old){
				this.getList(val);
			}
		},
		mounted() {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			this.getList(this.page);
		},
		methods:{
			getList(page) { //首页商品
				if(page == 1){
					this.list = [];
					this.page_count = 0;
				}
				if(page == this.page_count){
					this.pullUpOn = false;
					return;
				} 	
				console.log("page", page)
				this.loading = true;
				this.$http.request({
					url: this.$api.default.getvoucherList,
					method: 'GET',
					data:{
						page: page
					},
				}).then((res) => { 
					this.loading = false;
					if(res.code == 0){
						if(res.data.list.length > 0){
							var arr = this.list ? this.list.concat(res.data.list) : res.data.list;
							this.list = arr
							this.page_count = res.data.page_count;
							this.pullUpOn = true;
						}
					}else{
						this.loading = false;
						this.$http.toast(res.msg);
					}
				})
			},
			linkurl(id){
				uni.navigateTo({
					url:'/pages/goods/detail?proId='+id
				})
			}
		},
	}
</script>

<style lang="less" scoped>
	.vouchers-container{width: 100%;overflow: hidden;background: #fff;margin-bottom: 20rpx;}
	.vouchers-type{width: 100%;height: 80rpx;display: flex;justify-content: space-evenly;}
	.vouchers-type view{width: 30%;}
	.vouchers-type view text{display: block;width: 80%;margin: 0 auto;height: 60rpx;margin: 0 auto;text-align: center;line-height: 60rpx;}
	.active{color: rgb(255, 113, 4);font-weight: bold;border-bottom: 5rpx solid rgb(255, 113, 4);}
	.goods{width: 94%;background: #fff;display: flex;justify-content: space-between;flex-wrap: wrap;margin: 0 auto;}
	.goods_item{width: 49%;border:1rpx solid rgb(239,239,239) ;height: 550rpx;border-radius: 15rpx;margin-top: 15rpx;}
	.goods_item image{width: 100%;height: 340rpx;display: block;}
	.item_name{font-size: 29rpx;color: #000;padding: 0 10rpx;box-sizing: border-box; overflow: hidden;margin: 10rpx 0;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;}
  .money_num{width: 100%;padding: 0 10rpx;box-sizing: border-box;display: flex;justify-content: space-between;}
  .send{padding: 0 10rpx;box-sizing: border-box;}
  .send_imag{min-width: 240rpx;display:inline-block;text-align: center;font-size: 24rpx;height: 80rpx;background: url(../mch/img/song.png);background-repeat: no-repeat;background-size: 100% 70%;color: #fff;}
</style>
