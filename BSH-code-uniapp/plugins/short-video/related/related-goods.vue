<template>
	<view>
		<!-- 搜索框 -->
		<!-- <view class="search" @tap="navTo('/pages/search/search')">
			<search
				:message="message"
				:textAlign="textAlign"
				:frameColor="frameColor"
				:innerFrameColor="innerFrameColor"
				:textColor="textColor"
				:borderRadius="borderRadius"
			></search>
		</view> -->
		
		<!-- 分类 -->
		<scroll-view scroll-x="true" class="scroll-box flex">
			<view class="scroll-item" v-for="(item,index) in arr" :key='index' @tap='switchCate(index)'>
				<view class="scroll-item-child" :style="{'border-bottom':index == indexs?'2px solid'+textColors:'',color:index == indexs?textColors:''}">{{item}}</view>
			</view>
		</scroll-view>
		
		<view class="placeholder"></view>
		
		<!-- 商品 -->
		<view class="goods-box">
			<view class="goods-item flex" v-for="(item,index) in goodsData" :key='item.name'>
				<image class="goods-cover" :src="item.cover_pic" mode=""></image>
				<view class="goods-content flex flex-col flex-x-between">
					<view class="goods-name over2">{{item.name}}</view>
					<view class="flex flex-x-between flex-y-center">
						<view class="goods-price" :style="{color:textColors}">
							<text style="font-size: 26rpx;margin-right: 4rpx;">&yen;</text>
							{{item.price}}
						</view>
						<view class="select-goods" @tap="selectGoods(item.id,item.name)" :style="{background:textColors}">
							<view>选择商品</view>
							<view>赚&nbsp;&yen;<text style="font-size: 26rpx;margin-left: 2rpx;font-weight: 600;">{{item.earned}}</text></view>
						</view>
					</view>
					<view class="sales">已售:{{item.virtual_sales}}件</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import search from '@/components/search/search.vue';
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				// 搜索框数据
				message:'大家都在搜“MAC口红”',
				textAlign:'center',
				frameColor:'#FFFFFF',
				innerFrameColor:'#EFECEA',
				textColor:'#999999',
				borderRadius:70,
				textColors:'',
				
				arr:[],//顶部分类名字的数据
				cateList:[], //顶部分类的完整数据
				goodsData:[],//商品数据
				page:1,
				indexs:0,
				page_count:0,
			};
		},
		components: {
			search,
		},
		onLoad() {
			this.textColors = this.globalSet('textCol');
			this.getTopList();
		},
		onReachBottom() {
			this.page++;
			console.log(this.page,'this.page')
			if(this.page <= this.page_count){
				this.getGoodsList(this.cateList[this.indexs].id);
			}
		},
		methods:{
			getTopList(){
				this.$http.request({
					url:this.$api.category.leftlist,
				}).then((res)=>{
					if(res.code == 0){
						this.cateList = res.data.list;
						this.getGoodsList(this.cateList[this.indexs].id);
						res.data.list.map(item => {
							this.arr.push(item.name);
						})
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			getGoodsList(id){
				this.$http.request({
					url:this.$api.plugin.video.index,
					showLoading:true,
					method:'post',
					data:{
						cat_id:id,
						page:this.page
					}
				}).then(res => {
					if(res.code == 0){
						this.page_count = res.data.page_count;
						this.goodsData.push(...res.data.list);
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			selectGoods(id,name){
				uni.redirectTo({
					url:`/plugins/short-video/release-video/release-video?goods_id=${id}&goods_name=${name}`
				})
				console.log(id,'idddd')
			},
			navTo(url){
				uni.navigateTo({
					url
				})
			},
			switchCate(index){
				this.indexs = index;
				this.page = 1;
				this.goodsData = [];
				this.getGoodsList(this.cateList[index].id);
			}
		}
	}
</script>

<style lang="scss" scoped>
	.scroll-box{
		white-space: nowrap;
		background: #FFFFFF;
		padding-left: 10rpx;
		padding-top: 10rpx;
		position: fixed;
		top: 0;
		height: 78rpx;
		
		.scroll-item{
			display: inline-block;
			margin-right: 20rpx;
			padding: 6rpx 20rpx 6rpx;
			font-size: 32rpx;
			color: #000000;
			letter-spacing: 1px;
		}
		.scroll-item-child{
			padding-bottom: 8rpx;
		}
	}
	.placeholder{
		height: 78rpx;
	}
	
	.goods-box{
		background: #FFFFFF;
		margin-top: 30rpx;
		padding: 14rpx 30rpx;
		
		.goods-item{
			padding: 20rpx 0;
			
			.goods-cover{
				width: 250rpx;
				height: 250rpx;
				border-radius: 10rpx;
				margin-right: 34rpx;
			}
			
			.goods-content{
				flex: 1;
				
				.goods-name{
					color: #000000;
				}
				.goods-price{
					font-size: 42rpx;
					color: #BC0100;
					font-weight: 600;
				}
				.select-goods{
					background: linear-gradient(90deg,#F14A2E,#BC0100);;
					border-radius: 50rpx 0px 0px 50rpx;
					color: #FFFFFF;
					font-size: 24rpx;
					text-align: center;
					padding: 10rpx 20rpx 10rpx 32rpx;
					line-height: 32rpx;
				}
				.sales{
					font-size: 24rpx;
					color: #999999;
				}
			}
		}
	}
</style>
