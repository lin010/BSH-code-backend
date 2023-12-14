<template>
	<view>
		<view class="container" >
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
					<input type="search" placeholder="请输入商品名"  placeholder-class="tui-input-plholder"
					 class="tui-input" v-model.trim="key" @tap='search'/>
					<!-- #ifdef APP-PLUS || MP -->
					<icon type="clear" :size='13' color='#bcbcbc' @tap="cleanKey" v-show="key"></icon>
					<!-- #endif -->
					<!-- #ifdef H5 -->
					<view @tap="cleanKey" v-show="key"><tui-icon name="close-fill" :size='16' color='#bcbcbc'></tui-icon></view>
					<!-- #endif -->
				</view>
				<view class="tui-cancle" @tap="orderlist">订单</view>
			</view>
			<view class="change-list">
				<liuyuno-tabs :tabData="cats" :defaultIndex="defaultIndex" @tabClick='tabClick' />
			</view>
		</view>
		<AliTljGoodsList ></AliTljGoodsList>
	</view>
</template>

<script>
	import tuiIcon from "@/components/icon/icon";
	import tuiTag from "@/components/tag/tag";
	import tuiButton from "@/components/extend/button/button"
	import tuiListCell from "@/components/list-cell/list-cell"
	import liuyunoTabs from "@/components/liuyunotabs/liuyuno-tabs.vue";
	import AliTljGoodsList from "@/components/ali-tlj-goods-list/ali-tlj-goods-list";
	export default {
		components: {
			AliTljGoodsList,
			tuiButton,
			tuiListCell,
			tuiIcon,
			tuiTag,
			liuyunoTabs
		},
		data() {
			return {
				plugins_img_url:this.$api.plugins_img_url,
				img_url: this.$api.img_url,
				cats:[],
				key: "",
				defaultIndex: 0
			}
		},
		onLoad() {
			this.getCatList();
		},
		methods: {
			getCatList(){
				let that = this;
				this.$http.request({
					url: this.$api.taolijin.getCatList,
					method: 'post',
					showLoading: true
				}).then((res) => {
					if (res.code == 0) {
						that.cats = res.data.list;
						if(that.cats.length > 0){
							uni.$emit("ali_search_action:cat", {cat:parseInt(that.cats[0].id)});
						}
					} else {
						that.$http.toast(res.msg);
					}
				})
			},
			tabClick(item){
				this.defaultIndex = item.index;
				uni.$emit("ali_search_action:cat", {cat:parseInt(this.cats[item.index].id)});
			},
			cleanKey: function() { //清空搜索
				this.key = ''
			},
			search(){
				uni.navigateTo({
					url:'/ali/taolijin/searchList/searchList'
				});
			},
			orderlist(){ //订单列表页面
				uni.navigateTo({
					url:'/ali/taolijin/orderList/orderList'
				});
			}
		},
		onReachBottom() {
			uni.$emit("ali_search_action:page");
		},
	}
</script>
<style lang="scss" scoped>
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
	.change-list{
		width: 100%;
		overflow: hidden;
		background: #fff;
		position: fixed;
		height: 95rpx;
		/* #ifdef H5 */
		top: 200rpx;
		/* #endif */
		/* #ifdef MP|| APP-PLUS */
		top:120rpx;
		/* #endif */
		left: 0;
		z-index: 999;		
	}
</style>
