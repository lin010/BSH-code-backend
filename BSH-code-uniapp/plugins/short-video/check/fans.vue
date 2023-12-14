<template>
	<view class="fans-root">
		<com-nav-bar :fixed='true' @clickLeft="back" left-icon="back" :title="'查看粉丝（'+ num +'）'" :status-bar="true"></com-nav-bar>
		
		<block v-if="fans_list > 0">
			<view class="fans-list">
				<view class="fans-item flex flex-x-between flex-y-center" v-for="(item,index) in fans_list" :key='index'>
					<view class="flex flex-y-center">
						<image class="avatar" :src="item.avatar_url" mode=""></image>
						<view>
							<view style="color: #000000;">{{item.nickname}}</view>
							<view class="signature">{{item.signature_content || '该用户暂无个性签名'}}</view>
						</view>
					</view>
					<block v-if='userInfo.user_id != item.user_id'>
						<view class="attention-btn attention-btn-c" v-if="item.is_follow == 1">
							<text v-if="is_fans == 1" @tap='attention(item.user_id,item.is_follow,index)'>互相关注</text>
							<text v-else @tap='attention(item.user_id,item.is_follow,index)'>已关注</text>
						</view>
						<view class="attention-btn" v-else @tap='attention(item.user_id,item.is_follow,index)'>关注</view>
					</block>
				</view>
			</view>
		</block>
		<view class="default" v-else>还有没有粉丝，快去发多点视频吸粉吧！</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				num:20,
				page:1,
				user_id:'',
				fans_list:[],
				
				userInfo:'',//获取本地的用户信息
			};
		},
		onLoad(options) {
			if(options.user_id){
				this.user_id = options.user_id;
			}
			this.userInfo = JSON.parse(uni.getStorageSync('userInfo'));
			this.getList();
		},
		methods:{
			attention(id,is_follow,index){ //关注用户
				this.$http.request({
					url:this.$api.plugin.video.operation_follow,
					method:'post',
					data:{
						user_id:id,
						type:is_follow?'unfollow':'follow'
					}
				}).then(res =>{
					if(res.code == 0){
						this.fans_list[index].is_follow = !this.fans_list[index].is_follow;
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			back(){
				this.navBack();
			},
			getList(){
				this.$http.request({
					url:this.$api.plugin.video.follow_list,
					method:'post',
					data:{
						page:this.page,
						user_id:this.user_id,
						follow_type:'fans'
					}
				}).then(res => {
					if(res.code == 0){
						this.fans_list.push(...res.data.list);
						this.num = this.fans_list.length;
					}else{
						this.$http.toast(res.msg);
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.fans-list{
		margin-top: 20rpx;
		background: #FFFFFF;
		padding: 0 30rpx;
	}
	.fans-item{
		padding: 26rpx 0;
		border-bottom: 1px solid #F3F3F3;
		
		&:last-of-type{
			border-bottom: 0;
		}
		
		.avatar{
			width: 100rpx;
			height: 100rpx;
			border-radius: 50%;
			display: block;
			margin-right: 14rpx;
		}
		.signature{
			font-size: 24rpx;
			color: #818181;
		}
		.attention-btn{
			background: #BC0100;
			width: 152rpx;
			height: 60rpx;
			border-radius: 6rpx;
			color: #FFFFFF;
			font-size: 28rpx;
			text-align: center;
			line-height: 60rpx;
		}
		.attention-btn-c{
			background: #B8B8B8;
		}
	}
	.default{
		background: #FAFAFA;
		text-align: center;
		margin-top: 200rpx;
	}
</style>
