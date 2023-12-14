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
				
				<input type="search" placeholder="请输入商品名"  placeholder-class="tui-input-plholder" class="tui-input" v-model.trim="key" @confirm='search'/>
				
				<!-- #ifdef APP-PLUS || MP -->
				<icon type="clear" :size='13' color='#bcbcbc' @tap="cleanKey" v-show="key"></icon>
				<!-- #endif -->
				
				<!-- #ifdef H5 -->
				<view @tap="cleanKey" v-show="key"><tui-icon name="close-fill" :size='16' color='#bcbcbc'></tui-icon></view>
				<!-- #endif -->
				
			</view>
			<view class="tui-cancle" @tap="search">搜索</view>
		</view>
		
		<template v-if="showList">
			
			<view class="sort">
				<view style="position: relative;width: 30%;text-align: center;" 
				:class="selecSort==0?'actove':''"     @click="sortselect(0)">
					<text>{{priceSort}}</text>
					<image :src="img_url+'unmy-hotel.png'" mode="" style="width: 35rpx;height: 35rpx;display: block;position: absolute;top: 35rpx;right: -20rpx;"></image>
				</view>
				<view style="position: relative;width: 30%;text-align: center;"
				:class="selecSort==1?'actove':''"     @click="sortselect(1)">
					<text>{{salesort}}</text>
					<image :src="img_url+'unmy-hotel.png'" mode="" style="width: 35rpx;height: 35rpx;display: block;position: absolute;top: 35rpx;right: -20rpx;"></image>
				</view>
			</view>
			
			<unipopup ref="popupSortprice" type="top">
				<view  class="goods_sort">
					<view @click="sortSecprice(index,item)" :class="sortpriceIndex==index?'sortClass':''" v-for="(item,index) in sortType" :key='index'
					style="text-align: center;height: 100rpx;line-height: 100rpx;border-bottom: 0.5px solid #f2f5f9;">{{item}}</view>
				</view>
			</unipopup>
			<unipopup ref="popupSortsale" type="top">
				<view  class="goods_sort">
					<view @click="sortSecsale(index,item)" :class="sortsaleIndex==index?'sortClass':''" v-for="(item,index) in sortType" :key='index'
					style="text-align: center;height: 100rpx;line-height: 100rpx;border-bottom: 0.5px solid #f2f5f9;">{{item}}</view>
				</view>
			</unipopup>
			
			<AliTljGoodsList></AliTljGoodsList>
		</template>
	</view>
</template>

<script>
	import tuiIcon from "@/components/icon/icon";
	import tuiTag from "@/components/tag/tag";
	import tuiButton from "@/components/extend/button/button"
	import tuiListCell from "@/components/list-cell/list-cell"
	import unipopup from '@/components/uni-popup/uni-popup'
	import AliTljGoodsList from "@/components/ali-tlj-goods-list/ali-tlj-goods-list";
	export default {
		components: {
			AliTljGoodsList,
			tuiButton,
			tuiListCell,
			tuiIcon,
			tuiTag,
			unipopup
		},
		data() {
			return {
				img_url: this.$api.img_url,
				showList: false,
				key: "",
				sort:[
					"价格排序",
					"销量排序"
				],
				popupSortprice:false,//排序的弹窗显示
				popupSortsale:false,//排序的弹窗显示
				priceSort:'价格排序',
				salesort:'销量排序',
				sortType:[],
				sortprice:[
					"默认",
					"价格由低到高",
					"价格由高到低",
				],
				sortsale:[
					"默认",
					"销量由低到高",
					"销量由高到低",
				],
				selecSort:null,
				sortpriceIndex:'',
				sortsaleIndex:'',
				order:'',
				orderBy:'',
				
				searchParam:{
					cat: 0,
					page: 0,
					kw: ''
				}
			};
		},
		onLoad() {
			let that = this;
		},
		methods:{
			cleanKey: function() { //清空搜索
				this.key = '';
				this.showList = false;
			},
			search(){
				if(!this.key || this.key.length <= 0){
					this.$http.toast("请输入关键词搜索");
					return;
				}
				this.showList = true;
				let that = this;
				setTimeout(function(){
					uni.$emit("ali_search_action:kw", {kw:that.key});
				}, 500);
			},
			sortselect(index){ //排序
				this.selecSort=index
				if(index==0){
					this.$refs.popupSortprice.open();
					this.$refs.popupSortsale.close();
					this.sortType=this.sortprice
					this.sortsaleIndex='',
					this.salesort='销量排序'
					this.order='price'
					this.orderBy=''
				}
				if(index==1){
					this.$refs.popupSortsale.open();
					this.$refs.popupSortprice.close();
					this.sortType=this.sortsale
					this.sortpriceIndex='',
					this.priceSort='价格排序'
					this.order='sale'
					this.orderBy=''
				}
			},
			sortSecprice(index,item){
				this.sortpriceIndex=index
				this.$refs.popupSortprice.close();
				if(item=='全部'){
					this.priceSort='价格排序'
				}else{
					this.priceSort=item
				}
				if(index==0){
					this.orderBy=''
				}
				if(index==1){
					this.orderBy="asc"
				}
				if(index==2){
					this.orderBy='desc'
				}
			},
			sortSecsale(index,item){
				this.sortsaleIndex=index
				this.$refs.popupSortsale.close();
				if(item=='全部'){
					this.salesort='销量排序'
				}else{
					this.salesort=item
				}
				if(index==0){
					this.orderBy=''
				}
				if(index==1){
					this.orderBy="asc"
				}
				if(index==2){
					this.orderBy='desc'
				}
			},
		},
		onReachBottom() {
			uni.$emit("ali_search_action:page");
		}
	}
</script>

<style lang="less">
	.container{width: 100%;overflow: hidden;}
	.tui-searchbox {
		width: 100%;
		padding: 30rpx;
		background: #fff;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		position: fixed;
		/* #ifdef H5 */
		top: 80rpx;
		/* #endif */
		/* #ifdef  MP  */
		top: 0rpx;
		/* #endif */
		left: 0;
		z-index: 9999;
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
	.tui-history-content{
		display: flex;
		flex-wrap: wrap;
	}
	.sort{width: 100%;height: 100rpx;display: flex;justify-content: space-evenly;line-height: 100rpx;position: fixed;
	/* #ifdef H5 */
	top: 200rpx;
	/* #endif */
	/* #ifdef  MP||APP-PLUS  */
	top: 120rpx;
	/* #endif */
	left: 0;
	z-index: 9999;
	background: #fff;
	}
	.goods_sort{width: 100%;z-index: 99;background: #fff;
	/* #ifdef H5 */
	height: 300rpx;
	margin-top: 210rpx;
	/* #endif */
	/* #ifdef  MP||APP-PLUS  */
	height: 310rpx;
	margin-top: 215rpx;
	/* #endif */
	
	}
	.actove{color: rgb(255, 113, 4)}
	.sortClass{color: #FF7104;font-weight: bold;background: url('https://dev.mingyuanriji.cn/web/static/yellow-right.png')no-repeat;background-size: 5%;
	background-position: 90% 50% ;}
	
</style>
