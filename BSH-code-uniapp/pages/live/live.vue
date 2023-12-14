<template>
	<view>
		<view class="live_box" v-if="data_length === true">
			<view class="live" v-for="(item,i) in data_list" :key="i" @click="goLive(item.roomid)">
				<view class="live_img"><image class="img" :src="item.share_img" mode="aspectFill"></image></view>
				<view class="live_info">
					<view class="live_info_name">主播名称：{{item.anchor_name}}</view>
					<view class="live_info_start_time">开播时间：{{item.start_time}}</view>
				</view>
			</view>
		</view>
		<view class="live_box" v-else>
			<view class="live_msg">暂无数据</view>
		</view>
		
	</view>

</template>

<script>
	export default {
		data() {
			return {
				data_list:[],
				data_length:true
			}
		},
		onLoad() {
			this.getLive();
		},
		onShow() {
		 let livePlayer = requirePlugin('live-player-plugin');
		},
		methods: {
			//获取直播数据11
			getLive(){
				this.$http.request({
						url: this.$api.live.getlive
					}).then(res => {
						res.data.forEach((el,i) => {
							var date = new Date(el.start_time * 1000);
							var Y = date.getFullYear() + '-';
							var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
							var D = date.getDate() + ' ';
							var h = date.getHours() + ':';
							var m = date.getMinutes() + ':';
							var s = date.getSeconds(); 
							el.start_time = Y+M+D+h+m+s;
						});
						this.data_list = res.data
						this.data_length = this.data_list.length === 0 ? false : true;
						console.log(this.data_length);
					}).catch(err => {
						console.log(err);
					})
			},
			goLive(roomid){
				uni.navigateTo({
					url: `plugin-private://wx2b03c6e691cd7370/pages/live-player-plugin?room_id=${roomid}`
				});
			}
		}
	}
</script>

<style lang="scss">
	.live_box{
		width: 100%;
		height: 100%;
		display: flex;
		flex-wrap: wrap;
		padding: 10rpx;
		.live{
			width: 47%;
			height: 350rpx;
			border-radius: 20rpx;
			overflow: hidden;
			background: #FFFFFF;
			margin-left: 1.8%;
			margin-top: 20rpx;
			.live_img{
				width: 100%;
				height: 70%;
				display: block;
				.img{
					width: 100%;
					height: 100%;
				}
			}
			.live_info{
				width: 100%;
				height: 30%;
				.live_info_name{
					font-size: 27rpx;
					font-weight: 700;
					margin-left: 10rpx;
					margin-top: 15rpx;
				}
				.live_info_start_time{
					font-size: 22rpx;
					color: #A3A3A3;
					margin-left: 10rpx;
					margin-top: 5rpx;
				}
			}
		}
		.live_msg{
			width: 100%;
			margin: 0 auto;
			margin-top: 480rpx;
			text-align: center;
		}
	}

</style>
