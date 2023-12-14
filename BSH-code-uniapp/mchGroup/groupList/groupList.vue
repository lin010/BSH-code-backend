<template>
	<view class="groupList-app">
		<view class="table">
			<text v-for="(item,index) in table" :key='index'  :class="selectIndex==index?'active':''"    @click="changeType(index)">
				{{item}}
			</text>
		</view>
		<view class="groupList">
			<view class="groupList-item" v-for="(item,index) in groupList" :key='index' >
				<image :src="item.cover_url" mode="" class="itemLogo"></image>
				<view class="groupList-item-info" style="width:490rpx;">
					<view>
						<view class="groupList-item-title">
							{{item.name}}
						</view>
						<view class="groupList-item-detail">
							<view style="width: 100%;overflow: hidden;">{{item.province}} {{item.city}} {{item.district}}</view>
							<view style="width: 100%;overflow: hidden;">{{item.address}}</view>
							<view style="width: 100%;overflow: hidden;margin: 10rpx 0;" v-if="item.remark!=undefined">拒绝原因：{{item.remark}}</view>
						</view>
					</view>
					<view class="groupList-item-btn" style="">
						<text style="font-size: 30rpx;">{{item.mobile}}</text>
						<text @click="manaStore(item)"  v-if="selectIndex==0">进入管理</text>
						<text @click="manaStorechange(item)"  v-if="selectIndex==1">查看修改</text>
					</view>
				</view>
			</view>		
		</view>
		<view class="no-more" v-if="groupList.length==0">
			<image :src="img_url+'/giftOrder_logo.png'" mode=""></image>
			<text>暂无店铺</text>
		</view>
		<view @click="groupAdd" class="add-item-button">添加门店</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				groupList:[],
				page_count:"",
				form:{
					page:1,
				},
				table:['已通过','未通过'],
				selectIndex:0,
				url:this.$api.merchants.groupItemList,
			};
		},
		onLoad() {
			this.getGroupList(this.url)
		},
		methods:{
			manaStore(item){
				uni.setStorageSync('x-man-mch-id',item.mch_id)							
				uni.navigateTo({
					url:'../../mch/personalCentre/personalCentre'
				})
			},
			manaStorechange(item){
				console.log(item)
				uni.navigateTo({
					url:'../groupAdd/groupAdd?title='+'修改资料'+"&mch_apply_id="+item.mch_apply_id
				})
			},
			changeType(index){ //tabble切换
				this.selectIndex=index
				this.form.page=1
				this.groupList=[]
				if(index==0){
					this.url=this.$api.merchants.groupItemList
					this.getGroupList(this.url)
				}
				if(index==1){
					this.url=this.$api.merchants.grouprefuseItemList
					this.getGroupList(this.url)
				}
			},
			getGroupList(url){//获取订单列表
				this.$http.request({
					url: url,
					method: 'POST',
					data: this.form,
					showLoading: true
				}).then(res => {
					if(res.code==0){
						if(res.data.list.length==0)
							return false
						let list = res.data.list;
						var arr = this.groupList.concat(list)
						this.groupList = arr
						this.page_count = res.data.pagination.page_count;
					}else{
						this.$http.toast(res.msg);
					}
				});
			},
			groupAdd(){
				uni.navigateTo({
					url:'../groupAdd/groupAdd?title='+'添加门店'
				})
			}
		},
		onReachBottom() {
			if(this.form.page==this.page_count){
				return false;
			} 		
			this.form.page=this.form.page+1
			this.getGroupList(this.url)
		},
	}
</script>

<style lang="less">
	.groupList-app{width: 100%;overflow: hidden;padding-bottom:80rpx;}
	.no-more{width: 200rpx;height: 280rpx;position: absolute;top: 0;left: 0;right: 0;margin: auto;bottom: 0;
	font-size: 26rpx;text-align: center;color: #808080;}
	.no-more image{width: 200rpx;height: 200rpx;}
	
	.groupList{
		width: 100%;
		overflow: hidden;
		margin-bottom: 80rpx;
		margin-top: 110rpx;
	}
	.groupList-item{width: 98%;overflow: hidden;display: flex;justify-content: flex-start;margin: 10rpx auto;padding: 20rpx 20rpx;
	background: #FFFFFF;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.4);border-radius: 20rpx;}
	.groupList-item-info{display: flex;flex-direction: column;padding-left:20rpx;justify-content: space-between;}
	.itemLogo{width: 200rpx;height: 200rpx;}
	.groupList-item-title{font-size: 30rpx;color: #000;font-weight: bold;overflow: hidden;text-overflow:ellipsis;white-space: nowrap;}
	.groupList-item-detail{font-size: 25rpx;margin: 10rpx 0;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 1;overflow: hidden;}
	.groupList-item-btn{display:flex;justify-content: space-between;}
	.groupList-item-btn > text:nth-of-type(2){padding:0 30rpx;display: inline-block;text-align: center;background: #FF5A0E;border-radius: 30rpx;font-size: 30rpx;height: 60rpx;line-height: 60rpx;color: #fff;}

	.add-item-button{color:white;position: fixed;width:100%;left:0;bottom:0;height:120rpx;line-height:120rpx;text-align:center;background:#ff5a0e;}
	.table{width: 100%;height: 100rpx;background: #fff;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.4);display: flex;
	justify-content: space-evenly;line-height: 100rpx;
	position: fixed;
	/* #ifdef  MP ||APP-PLUS */
		top: 0rpx;
	/* #endif */
	/* #ifdef  H5 */
		top: 83rpx;
	/* #endif */
	left: 0;
	
	
	
	}
	.table text{display: block;width: 100rpx;text-align: center;height: 90rpx;line-height: 90rpx;}
	.active{color:#FF5A0E ;border-bottom: 4rpx solid #FF5A0E;}



</style>

