<template>
	<view class="msg-list-root">
		<main-tabbar :tabBar="tabbar"></main-tabbar>
		<block v-if="msg_list.length != 0">
			<view v-for="(item,index) in msg_list">
				<view class="msg-item flex" v-for="(mItem,mIndex) in item" @tap="navTo(mItem.msg.otherInfo)">
					<block v-if="str('user_1')">
						<!-- user_id == str(mItem.msg.otherInfo.id) -->
						<image class="avatar" :src="mItem.msg.userinfo.avatar_url" mode=""></image>
						<view class="msg-content">
							<view class="flex flex-x-between">
								<view class="name">{{mItem.msg.userinfo.username}}</view>
								<view>{{time(mItem.msg.time)}}</view>
							</view>
							<view class="over1">{{mItem.msg.content.text}}</view>
						</view>
					</block>
					
					<block v-else>
						<image class="avatar" :src="mItem.msg.otherInfo.avatar_url" mode=""></image>
						<view class="msg-content">
							<view class="flex flex-x-between">
								<view class="name">{{mItem.msg.otherInfo.nickname}}</view>
								<view>{{time(mItem.msg.time)}}</view>
							</view>
							<view class="over1">{{mItem.msg.content.text}}</view>
						</view>
					</block>
				</view>
			</view>
		</block>
		<view v-else style="text-align: center;margin-top: 300rpx;">还没有聊天记录，过去和别人聊天吧</view>
		<view style="height: 100rpx;width: 100%;"></view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				msg_list:'',
				user_id: '',
				userInfo:'',
				
				tabbar:[
					{
						active_color: "rgb(188, 1, 0)",
						active_icon: "http://jxmall.sinbel.top/web//uploads/images/original/20200610/25498a7029149193dc88bbd527f82eef.png",
						color: "#888",
						icon: "http://jxmall.sinbel.top/web//uploads/images/original/20200610/3e8ecb9657d2a258285b957186ad9eac.png",
						open_type: "redirect",
						text: "首页",
						url: "/plugins/business-card/index",
					},
					{
						active_color: "rgb(188, 1, 0)",
						active_icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200806/900b3f657fdd4a0ab7588c44ae3d4999.png",
						color: "#888",
						icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200806/b8352b721c91af6e669a0443973c7570.png",
						open_type: "redirect",
						text: "雷达",
						url: "/plugins/business-card/log/log",
					},
					{
						active: true,
						active_color: "rgb(188, 1, 0)",
						active_icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200806/164f085e0a13520c0d87a556cb6a82c7.png",
						color: "#888",
						icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200806/fce84bdbe8b0ce9ace336147cbc98402.png",
						open_type: "redirect",
						text: "消息",
						url: "/plugins/business-card/message/list",
					},
					{
						active_color: "rgb(188, 1, 0)",
						active_icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200806/24affe41873699280b881f6b3fe0dcd9.png",
						color: "#888",
						icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200806/53df1b6c503338b28e32daa7f37b08d5.png",
						open_type: "redirect",
						text: "客户",
						url: "/plugins/business-card/client/list",
					},
					{
						active_color: "rgb(188, 1, 0)",
						active_icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200610/4b5259cb3a8ee275acef5e7a6ad4bd7b.png",
						color: "#888",
						icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200610/2626dc5ddbb8ab3a7799aecdf7ec4c75.png",
						open_type: "redirect",
						text: "我的",
						url: "/plugins/business-card/my/index",
					}
				]
			};
		},
		onLoad() {
			if(uni.getStorageSync('userInfo')){
				this.userInfo = JSON.parse(uni.getStorageSync('userInfo'));
				this.user_id = 'user_'+this.userInfo.user_id;
			}
			this.getMsgList();
		},
		computed:{
			time(){
				return function(val){
					var date = new Date(val);
					var year = date.getFullYear();
					var month = date.getMonth()+1;
					var day = date.getDate();
					var hour = date.getHours();
					var minute = date.getMinutes();
					var second = date.getSeconds();
					return `${this.add0(month)}月${this.add0(day)}日 ${this.add0(hour)}:${this.add0(minute)}`;
				}
			},
			str(){
				return function(val){
					return val.split('_')[1];
				}
			}
		},
		methods:{
			add0(val){
				return val<10?'0'+val:val;
			},
			navTo(val){
				uni.setStorage({
				    key: 'other_info',
				    data: JSON.stringify(val),
				    success: function () {}
				});
				uni.navigateTo({
					url:`/plugins/business-card/message/detail`
				});
			},
			getMsgList(){
				this.$http.request({
					url:this.$api.plugin.business.message_list,
					method:'post',
					showLoading:true,
					data:{
						user_id:this.userInfo.user_id,
						target_user_id:0
					}
				}).then(res => {
					if(res.code == 0){
						res.data.list.forEach(item => {
							item.splice(0,item.length-1);
						})
						this.msg_list = res.data.list;
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	page{
		background: #FFFFFF;
	}
	
	.msg-item{
		padding: 12rpx 30rpx 0;
		
		.avatar{
			width: 100rpx;
			height: 100rpx;
			border-radius: 6rpx;
			margin-right: 20rpx;
		}
		.msg-content{
			flex: 1;
			font-size: 24rpx;
			color: #909090;
			border-bottom: 1px solid #F2F2F2;
			padding: 2rpx 0 30rpx;
			
			.name{
				font-size: 30rpx;
				color: #000000;
				margin-bottom: 6rpx;
			}
		}
	}
</style>
