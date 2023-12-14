<template>
	<view class="container">
		<view class="tui-searchbox">
			<view class="tui-search-input">
				<!-- #ifdef APP-PLUS || MP -->
				<icon type="search" :size='13' color='#333'></icon>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<view>
					<!-- <tui-icon name="search" :size='16' color='#333333'></tui-icon> -->
					<com-icons type="search" :size='16' color='#333333'></com-icons>
				</view>
				<!-- #endif -->
				<input confirm-type="search" placeholder="请输入酒店名" :focus="true" auto-focus placeholder-class="tui-input-plholder"
				 class="tui-input" v-model.trim="key" @confirm='search'/>
				<!-- #ifdef APP-PLUS || MP -->
				<icon type="clear" :size='13' color='#bcbcbc' @tap="cleanKey" v-show="key"></icon>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<view @tap="cleanKey" v-show="key"><tui-icon name="close-fill" :size='16' color='#bcbcbc'></tui-icon></view>
				<!-- #endif -->
			</view>
			<view class="tui-cancle" @tap="search">搜索</view>
		</view>
 
		<view class="tui-search-history" v-if="history.length>0">
			<view class="tui-history-header">
				<view class="tui-search-title">搜索历史</view>
				<tui-icon name="delete" :size='14' color='#333' @tap="openActionSheet" class="tui-icon-delete"></tui-icon>
			</view>
			<view class="tui-history-content">
				<view v-for="(item,index) in history" :key="index" @tap='navTo(item)'>
					<tui-tag type="gray" shape="square">{{item}}</tui-tag>
				</view>
			</view>
		</view>
		
		<view class="tui-search-history">
			<view class="tui-history-header">
				<view class="tui-search-title">行政区商圈</view>
			</view>
			<view class="tui-history-content">
				<view v-for="(item,index) in city" :key="index" @tap='linkTo(item.name,item.parent_id)'>
					<tui-tag type="gray" shape="square">{{item.name}}</tui-tag>
				</view>
			</view>
		</view>
		
		<tui-actionsheet :show="showActionSheet" :tips="tips" @click="itemClick" @cancel="closeActionSheet"></tui-actionsheet>
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
				searchVal:'',
				history: [],
				key: "",
				showActionSheet: false,
				tips: "确认清空搜索历史吗？",
				city:uni.getStorageSync('citymessage'),
				region:"",
			}
		},
		onLoad(options) {
			console.log(options)
			var that = this;
			this.key = options.key;			
			uni.getStorage({
				key:'search-history',
				success(res){
					that.history.push(...res.data);
				},
			})
			this.form=JSON.parse(options.form)
			this.region=options.region
		},
		onShow() {
			
		},
		methods: {
			navTo(name){  //历史搜索
				let that=this
				this.form.keyword=name
				that.$http
					.request({
						url: this.$api.hotel.searchhotel,
						method: 'POST',
						data:this.form,
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							if(res.data.history==1){
								uni.navigateTo({
									url:'../searchList/searchList?search_id='+res.data.search_id+"&searchKey="+name+"&region="+this.region+"&city_id="+this.form.city_id
								})
							}else if(res.data.history==0){
								uni.navigateTo({
									url:'../searchList/searchList?prepare_id='+res.data.prepare_id+"&searchKey="+name+"&region="+this.region+"&city_id="+this.form.city_id
								})
							}
						}else{
							this.$http.toast(res.msg);
						}
				});	
			},
			linkTo(name,id){ //行政商圈搜索
				var that = this;
				that.form.city_id=id
				that.$http
					.request({
						url: this.$api.hotel.searchhotel,
						method: 'POST',
						data:this.form,
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							if(res.data.history==1){
								uni.navigateTo({
									url:'../searchList/searchList?search_id='+res.data.search_id+"&searchKey="+name+"&region="+this.region+"&city_id="+this.form.city_id
								})
							}else if(res.data.history==0){
								uni.navigateTo({
									url:'../searchList/searchList?prepare_id='+res.data.prepare_id+"&searchKey="+name+"&region="+this.region+"&city_id="+this.form.city_id
								})
							}
						}else{
							this.$http.toast(res.msg);
						}
				});	
			},
			search(){ //输入搜索
				if(!this.key){
					this.$http.toast('请输入酒店名');
					return;
				};
				var that = this;
				this.form.keyword=this.key
				uni.setStorageSync('searchtext',this.key)
				that.$http
					.request({
						url: this.$api.hotel.searchhotel,
						method: 'POST',
						data:this.form,
						showLoading: true
					})
					.then(res => {
						if(res.code==0){

							if(res.data.history==1){
								uni.navigateTo({
									url:'../searchList/searchList?search_id='+res.data.search_id+"&searchKey="+this.key+"&region="+this.region+"&city_id="+this.form.city_id
								})
							}else if(res.data.history==0){
								uni.navigateTo({
									url:'../searchList/searchList?prepare_id='+res.data.prepare_id+"&searchKey="+this.key+"&region="+this.region+"&city_id="+this.form.city_id
								})
							}
						}else{
							this.$http.toast(res.msg);
						}
				});		
				this.history.push(this.key);
				this.history = Array.from(new Set(this.history)); //去重
				uni.setStorage({ //将搜索记录存入缓存
					key:'search-history',
					data:that.history
				})
			},
			back: function() {
				this.navBack();
			},
			cleanKey: function() {
				this.key = ''
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
					    key: 'search-history',
					});
				}
			},
		}
	}
</script>

<style>
	page {
		color: #333;
		background: #fff;
	}

	.container {
		padding: 0 30rpx 30rpx 30rpx;
		box-sizing: border-box;
	}

	.tui-searchbox {
		padding: 30rpx 0;
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
</style>