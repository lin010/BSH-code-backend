<template>
	<view class="hotGoods-list">
		<view class="product-item" v-for="(item,index) in hotlist"  :key='index' @click="hotlink(item.goods_id,item.mch_baopin_id)">
			<image :src="item.cover_pic" mode="widthFix" class="product-item-logo"></image>
			<view class="product-item-name">{{item.name}}</view>
			<view class="product-item-money-buy">
				<view class="product-item-money">
					<text style="color: #c0c0c0;font-size: 28rpx;">官方价￥{{item.original_price}}</text>
					<text style="color: #FF7104;font-size: 28rpx;">会员价{{item.price}}</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				hotlist:[],
				form:{
					mch_id:uni.getStorageSync('mch_id'),
					page:1,
					keyword:'',
					sort_prop:'',//排序字段： goods_id按商品ID，virtual_sales按销售，goods_stock按库存，sort按排序
					sort_type:1,//排序方式：0降序，1升序
				},
				page_count:'',
			};
		},
		onLoad() {
			this.getHotgoods(this.form)
		},
		methods:{
			getHotgoods(form){
				let that = this
				that.$http.request({
					url: that.$api.moreShop.gethotgoods,
					data:form ,
					method: 'post',
					showLoading: true
				}).
				then(function(res) {
					if(res.data.list.length==0)return false
					let arr= res.data.list;
					that.hotlist=that.hotlist.concat(arr)
					that.page_count= res.data.pagination.page_count;
				})
			},
			hotlink(id,hotid){
				uni.navigateTo({
					url:'../../goods/detail?proId='+id+"&mch_baopin_id="+hotid
				})
			}
		},
		onReachBottom() {
			if(this.form.page==this.page_count){
				return false;
			}
			this.form.page=this.form.page+1
			this.getHotgoods(this.form)
		},
	}
</script>

<style lang="less">
	.hotGoods-list {
		width: 100%;		
		overflow: hidden;
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
		padding: 0 5rpx;
		color: #000000;
	}

	.product-item-money-buy {
		width: 100%;
		overflow: hidden;
		margin-bottom: 15rpx;
		padding: 0 5rpx;
	}

	.product-item-money {
		color: red;
		font-size: 28rpx;
		display: flex;
		justify-content: space-between;
		flex-wrap: wrap;
	}
</style>
