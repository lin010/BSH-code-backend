<template>
	<view>
		<!-- 缺省页 -->
		<view class="default-box" v-if="collectLists.length == 0">
			<image class="default-img" :src="img_url + 'collect.png'" mode=""></image>
			<view class="default-text">当前还没有收藏哦，快去收藏你喜欢的商品吧~</view>
		</view>
		<!-- 缺省页 -->
		
		<view class="collect-item" v-for="(item,index) in collectLists" @tap="navTo(item.goods_id)" :key='index' v-else>
			<image class="collect-item-img" :src="item.cover_pic" mode=""></image>
			<view class="collect-item-content">
				<view class="collect-item-title">{{item.goods_name}}</view>
				<view class="collect-item-price" :style="{color:'#FF7104'}">
					<view>&yen;<text class="price">{{item.price}}</text></view>
					<view class="collect-item-delete" @tap.stop="collectDelete(item.collect_id,index)">
						<view class="collect-item-icon iconfont icon-shanchu"></view>
					</view>
				</view>
			</view>
		</view>
		
		<main-nomore :visible="pullUpOn" bgcolor="#f7f7f7"></main-nomore>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				collectLists:[], //收藏列表
				page_count:0,
				page:1,
				pullUpOn:false,
				textColor:'#bc0100'
			}
		},
		onLoad(){
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
			}
			this.collectList();
		},
		onReachBottom(){
			this.page++;
			if(this.page <= this.page_count){
				this.collectList();
			}else{
				this.pullUpOn = true;
			}
		},
		methods:{
			navTo(id){
				uni.navigateTo({
					url:`/pages/goods/detail?proId=${id}`
				})
			},
			collectList(){ //请求收藏列表
				this.$http.request({
					url:this.$api.collect.list,
					method:'post',
					showLoading:true,
					data:{
						page:this.page,
						type:'goods'
					}
				}).then(res=>{
					console.log(res,'resssssss')
					if(res.code == 0){
						this.collectLists = this.collectLists.concat(res.data.list);
						this.page_count = res.data.page_count;
					}
				})
			},
			collectDelete(id,index){ //删除收藏
				this.$http.request({
					url:this.$api.collect.deletes,
					data:{
						type:'goods',
						id
					}
				}).then((res)=>{
					if(res.code == 0){
						this.$http.toast('删除成功');
						this.collectLists.splice(index,1);
					}
				})
			}
		}
		//导航栏自定义按钮
		// onNavigationBarButtonTap(e) {
		// 	console.log(e, 'eeeeee');
		// 	if(e.index == 0){
		// 		if(document.getElementsByClassName('uni-btn-icon')[1].innerText == '编辑'){
		// 			document.getElementsByClassName('uni-btn-icon')[1].innerText = '完成'
		// 		}else{
		// 			document.getElementsByClassName('uni-btn-icon')[1].innerText = '编辑'
		// 		}
		// 	}
		// }
	}
</script>

<style scoped>
	page,body{
		background: #FFFFFF;
	}
	.collect-item{
		background: #FFFFFF;
		padding: 20rpx 30rpx;
		display: flex;
		justify-content: space-between;
		border-bottom: 2rpx solid #F2F2F2;
	}
	.collect-item-img{
		width: 188rpx;
		height: 188rpx;
	}
	.collect-item-content{
		flex: 1;
		font-size: 10pt;
		padding: 10rpx 0 10rpx 20rpx;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
	}
	.collect-item-title{
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
		overflow: hidden;
		font-weight: 600;
		color: #000000;
	}
	.collect-item-price{
		display: flex;
		justify-content: space-between;
		color: #BC0100;
	}
	.price{
		font-size: 11pt;
		font-weight: 700;
		margin-left: 2rpx;
	}
	.collect-item-delete{
		border-radius: 50%;
		width: 42rpx;
		height: 42rpx;
		background: #ebebeb;
		position: relative;
	}
	.collect-item-icon{
		position: absolute;
		font-size: 26rpx;
		line-height: 26rpx;
		top: 50%;
		left: 50%;
		transform: translate(-50%,-50%);
		color: #000000;
	}
	
	.default-box{
		display: flex;
		flex-direction: column;
		align-items: center;
		border-top: 2rpx solid #F2F2F2;
		padding-top: 86rpx;
	}
	.default-img{
		width: 187rpx;
		height: 187rpx;
		margin-bottom: 35rpx;
	}
	.default-text{
		font-size: 10pt;
		color: #808080;
		letter-spacing: 2rpx;
	}
</style>
