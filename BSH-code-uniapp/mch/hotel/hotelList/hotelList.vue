<template>
	<view class="container">
		<view class="to-upload" v-if="heightShow">
			<view class="to-upload-show">
				<image :src="img_url+'/hotel/hotel_loading.png'" mode=""></image>
				<text>已为你找到{{num}}家酒店</text>
				<text>请稍后.....</text>
			</view>
		</view>
		<view class="tui-product-list">
			<view class="tui-product-container" :style="{width: isList ? '100%' : '49.2%'}" v-if="productList && productList.length">
				<view class="hotel_list_item" v-for="(item,index) in productList" :key='index' @click="checkInto(item.id)">
					<image :src="item.thumb_url" mode="" class="hotel_logo"></image>
					<view class="hotel_list_item_center">
						<view class="hotel_list_item_name">
							<text>{{item.name}}</text>
							<text>{{item.type_text}}</text>
						</view>
						<view class="hotel_list_item_product">
							<text style="display: inline-block;margin-right: 10rpx;color: red;">{{item.cmt_grade}}分</text>很好 
							<text style="display: inline-block;margin:0 10rpx;color: red;">{{item.cmt_num}}+</text>点评
						</view>
						<view class="hotel_list_item_distance" v-if="item.distance!='N'">
							<text>距离你：{{item.distance}}</text>
							<text v-if="item.distance_unit=='km'">公里</text>
							<text v-if="item.distance_unit=='m'">米</text>
						</view>
						<view class="hotel_list_item_price">
							<text>￥{{item.price}}起</text>
						</view>
					</view>
				</view>
			</view>
			<view class="nomore" v-else>
				<main-nomore text="暂无记录" :visible="true" bgcolor="transparent" style='width: 100%;'>
					
				</main-nomore></view>
		</view>		
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
				id:'',
				heightShow:false,
				img_url: this.$api.img_url,
				isList: true, //是否以列表展示  | 列表或大图
				drawer: false,
				productList: [],
				page: 1,
				loadding: false,
				pullUpOn:false,
				page_count:0,//总页数
				textColor:'#bc0100',
				num:0,
				search_id:'',
				flag:1,
			}
		},
		onLoad: function(options) {
			this.textColor = this.globalSet('textCol');			
			if(options&&options.prepare_id){
				this.heightShow=true
				this.searchList(options.prepare_id)
			}
			if(options&&options.search_id){
				this.heightShow=false
				this.id=options.search_id
				this.getList(options.search_id)
			}
		},
		methods: {
			searchList(id){
				if(this.flag==1){
					this.$http
						.request({
							url: this.$api.hotel.searchfilter,
							method: 'POST',
							data:{
								prepare_id:id
							},
							showLoading: true
						})
						.then(res => {
							if(res.code==0){
								if(res.data.finished==0){
									this.num=res.data.founds
									this.searchList(id)
								}else{
									this.id=res.data.search_id
									this.heightShow=false
									this.getList(this.id)
								}
							}else{
								this.$http.toast(res.msg);
							}
					});
				}
			},
			getList(id){
				this.$http
					.request({
						url: this.$api.hotel.getrecommended,
						method: 'POST',
						data:{
							page:this.page,
							search_id:id,
							lng:'',
							lat:'',
						},
						showLoading: true,
					})
					.then(res => {
						if(res.code==0){
							if(res.data.list.length==0)return false
							let list= res.data.list;
							var arr=this.productList.concat(list)
							this.productList =arr
							this.page_count = res.data.pagination.page_count;
						}else{
							this.$http.toast(res.msg);
						}
				});
			},
			checkInto(id){
				if(uni.getStorageSync('timeStaus')){
					uni.navigateTo({
						url:'../selectRoom/selectRoom?id='+id+"&timeStaus="+JSON.stringify(uni.getStorageSync('timeStaus'))
					})
				}
			}
		},
		onReachBottom() {
			if(this.page==this.page_count){
				return false;
			} 		
			this.page=this.page+1
			this.getList(this.id);
		},
		onUnload() {
			this.flag=0
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
	.nomore{width: 100%;overflow: hidden;}
	.to-upload{width: 100%;height: 100%;position: fixed;top: 0;left: 0;z-index: 9999;background: rgb(255,255,255);}
	.to-upload-show{width: 300rpx;overflow: hidden;position: absolute;top: 0;left: 0;right: 0;bottom: 0;margin: auto;height: 230rpx;}
	.to-upload-show image{display: block;width: 120rpx;height: 120rpx;margin: 0 auto;}
	.to-upload-show text{display: block;font-size: 26rpx;color:#ED6D00 ;width: 100%;overflow: hidden;text-align: center;}
	.tui-product-list {
		display: flex;
		justify-content: space-between;
		flex-direction: row;
		flex-wrap: wrap;
		box-sizing: border-box;
	}

	.tui-product-container {
		width: 49.2%;

		margin-bottom: 60rpx;
	}
	.hotel_list_item{width: 92%;overflow: hidden;margin: 20rpx auto;background: #fff;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.1);border-radius: 30rpx;}
	.hotel_logo{width: 208rpx;height: 208rpx;display: block;float: left;margin:20rpx 0 20rpx 15rpx}
	.hotel_list_item_center{width: 450rpx;float: left;margin-top: 20rpx;}
	.hotel_list_item_name{width: 100%;overflow: hidden;display: flex;justify-content: space-between;}
	.hotel_list_item_name text:nth-of-type(1){width: 68%;height: 76rpx;font-size: 26rpx;color: #000;margin-left: 15rpx;
	display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;overflow: hidden;}
	.hotel_list_item_name text:nth-of-type(2){width: 30%;height: 76rpx;font-size: 25rpx;color: #FB4512;text-align: right;}
	.hotel_list_item_product{width: 100%;margin-left: 15rpx;font-size: 25rpx;}
	.hotel_list_item_price{width: 40%;font-size: 28rpx;font-weight: bold;float: right;text-align: right;margin: 40rpx 0 15rpx 0rpx;color: #FB4512;}
	.hotel_list_item_price text{display: inline-block;float: right;}
	.hotel_list_item_price image{width: 28rpx;height: 28rpx;display: inline-block;margin:8rpx 10rpx 0 15rpx;float: right;}
	.hotel_list_item_distance{font-size: 25rpx;margin-left: 15rpx;margin-top: 40rpx;float: left;}
</style>
