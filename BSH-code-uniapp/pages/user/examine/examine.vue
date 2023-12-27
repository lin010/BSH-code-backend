<template>
	<view class="examine_app">
		<view class="search">
			<input type="search" v-model="form.keyword" placeholder="关键字搜索 店铺名、手机号"/>
			<view class="search_check" @click="search">
				搜索
			</view>
		</view>
		<view class="examine-status">
			<view v-for="(item,index) in status" :key='index' @click="swichIndex(index,item.status)"
			:class="statusIndex==index?'active':''">
				{{item.title}}
			</view>
		</view>
		<view class="examine-list">
			<view class="examine-item" v-for="(item,index) in list" :key='index' @click="linkTo(item.id)">
				<view class="examine-item-title">
					<view class="examine-item-title-name">
						{{item.store_name}}
					</view>
					<view class="examine-item-title-name-time-status">
						<text style="margin-left: 25rpx;">提交时间：{{item.created_at}}</text>
						<text v-if="item.status=='passed'" style="background: #16AB60;">已通过</text>
						<text v-if="item.status=='refused'" style="background: red;">未通过</text>
						<text v-if="item.status=='verifying'" style="float: right;">去审核</text>
					</view>
				</view>
				<view class="userMessage">
					<view>
						<image :src="item.store_logo" mode=""></image>
					</view>
					<view class="flex flex-y-center flex-x-center">
						<view class="flex-col" style="">
							<text>{{item.realname}}</text>
							<text style="font-size: 26upx;">{{item.role_type}}</text>
						</view>
					</view>
					<view>
						<text>{{item.mobile}}</text>
					</view>
				</view>
				<view class="recommend">
					<text>我的供货商:{{item.parent_nickname}}</text>
					<text>{{item.parent_mobile}}</text>
					<text>{{item.parent_role_type}}</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				status:[
					{
						title:'待审核',
						status:'verifying'
					},
					{
						title:'通过',
						status:'passed'
					},
					{
						title:'未通过',
						status:'refused'
					}
				],
				statusIndex:0,
				form:{
					page:1,
					status:'verifying',
					keyword:''
				},
				list:[],
				page_count:'',
				setStatus:'verifying',
			};
		},
		onLoad() {
			this.getAuditList() 
		},
		methods:{
			swichIndex(index,status){ //选择状态
				this.statusIndex=index
				this.setStatus=status
				this.form={
					page:1,
					status:status,//0待审核1通过2未通过
				}
				this.list=[]
				this.page_count=''
				this.getAuditList()
			},
			getAuditList(){
				this.$http
					.request({
						url: this.$api.moreShop.getAudit,
						method: 'GET',
						data:this.form,
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							console.log(res.data)
							if(res.data.list.length==0)return false
							let list= res.data.list;
							var arr=this.list.concat(list)
							this.list=arr
							this.page_count= res.data.pagination.page_count;
						}
				});
			},
			search(){
				let keyword=this.form.keyword
				let status=this.setStatus
				this.form={
					page:1,
					status:status,
					keyword:keyword
				}
				this.list=[]
				this.page_count=''
				this.getAuditList()
			},
			linkTo(id){ //跳转到详情
				uni.navigateTo({
					url:'./examineDetail?id='+id+"&status="+this.setStatus
				})
			}
		},
		onReachBottom() {
			if(this.form.page==this.page_count){
				return false;
			} 		
			this.form.page=this.form.page+1
			this.getAuditList();
		}
	}
</script>

<style lang="less">
	.examine_app{width: 100%;overflow: hidden;}
	.search{overflow: hidden;width: 600rpx;margin: 0 auto;}
	.search input{width: 450rpx;height: 80rpx;display: inline-block;margin: 35rpx 0;border-radius: 30rpx 0 0 30rpx;
	padding-left: 30rpx;font-size: 26rpx;background: rgb(242, 242, 242);float: left;}
	.search_check{float: left;margin: 35rpx 0;display: inline-block;font-size: 28rpx;color: #000;border-radius: 0 30rpx 30rpx 0;
	background:rgb(255, 113, 4) ;height: 78rpx;width: 100rpx;text-align: center;line-height: 78rpx;color: #fff;}
	.examine-status{width: 100%;overflow: hidden;display: flex;justify-content: space-evenly;background: #fff;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.3)}
    .examine-status view{height: 60rpx;margin: 10rpx 0;font-size: 30rpx;}
	.active{color: #ff7104;border-bottom: 4rpx solid #ff7104;}
	.examine-list{width: 100%;overflow: hidden;margin-bottom: 80rpx;}
	.examine-item{width: 92%;height: 310rpx;background: #fff;margin: 30rpx auto;border-radius: 20rpx;
	box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.3);padding: 10rpx;}
	.examine-item-title{width: 100%;overflow: hidden;}
	.examine-item-title-name{font-size: 28rpx;color: #000;text-align: center;margin: 5rpx 0 10rpx 0;white-space: nowrap;
	text-overflow: ellipsis;
	overflow: hidden;}
	.examine-item-title-name-time-status {font-size: 25rpx;}
	.examine-item-title-name-time-status text{display: inline-block;}
	.examine-item-title-name-time-status text:nth-of-type(2){background: #ff7104;color: #fff;width: 100rpx;text-align: center;border-radius: 30rpx;margin-left: 20rpx;}
	.examine-item-title-name-time-status text:nth-of-type(3){float: right;height: 50rpx;width: 100rpx;background: #ff7104;line-height: 50rpx;text-align: center;border-radius: 30rpx;color: #fff;}
	.userMessage{width: 100%;overflow: hidden;display: flex;justify-content: space-between;margin-top: 10rpx}
	.userMessage > view{width: 33%;text-align: center;}
	.userMessage image{width: 120rpx;height: 120rpx;border-radius: 50%;}
	.userMessage > view > text{display: block;height: 120rpx;text-align: center;line-height: 120rpx;color: #000;}
	.recommend{width: 100%;overflow: hidden;display: flex;justify-content: space-between;}
	.recommend text{display: inline-block;font-size: 25rpx;height: 60rpx;line-height: 60rpx;width: 33%;text-align: center;
	white-space: nowrap;
	text-overflow: ellipsis;
	overflow: hidden;
	}
</style>

