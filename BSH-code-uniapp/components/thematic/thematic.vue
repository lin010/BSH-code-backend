<template>
	<view>
		<view class="topic-box" v-if="topicData.style == 'normal'">
			<image v-if="topicData.count == 1" class="left-img" :src="topicData.logo_1" mode=""></image>
			<image v-else class="left-img left-img2" :src="topicData.logo_2" mode=""></image>
			<view class="right-content">
				<view class="right-content-item" v-for="(item,index) in topicData.topic_list" :key='index'>
					<image class="right-content-item-icon" :src="topicData.icon" mode=""></image>
					<view class="right-content-item-text">{{item.title}}</view>
				</view>
			</view>
		</view>
		
		<view v-else>
			<view class="layout-box" v-for="(item,index) in topicData.topic_list" :key='index'>
				<view v-if="item.layout == 0" class="layout1" @tap="navTo(item.id)">
					<view class="layout1-left">
						<view>{{item.title}}</view>
						<view class="read-count">{{item.read_count}}人浏览</view>
					</view>
					<image class="layout1-img" :src="item.cover_pic" mode=""></image>
				</view>
				<view class="layout2" v-if="item.layout == 1 ||  item.layout == 2" @tap="navTo(item.id)">
					<view>{{item.title}}</view>
					<image class="layout2-img" :src="item.cover_pic" mode=""></image>
					<view class="read-count">{{item.read_count}}人浏览</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default{
		props:{
			topicData:{
				type:Object,
				default:()=>{}
			}
		},
		methods:{
			navTo(id){
				this.$emit('click',id)
			}
		}
	}
</script>

<style scoped>
	.topic-box{
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 0 10rpx;
	}
	.left-img{
		width: 104rpx;
		height: 32rpx;
		margin-right: 20rpx;
	}
	.left-img2{
		height: 50rpx;
	}
	
	.right-content{
		flex: 1;
	}
	.right-content-item{
		display: flex;
		align-items: center;
	}
	.right-content-item-icon{
		width: 54rpx;
		height: 28rpx;
		margin-right: 10rpx;
	}
	.right-content-item-text{
		width: 500rpx;
		font-size: 11pt;
		color: #000000;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	
	.layout1{
		padding: 10rpx 20rpx;
		display: flex;
		background: #FFFFFF;
		justify-content: space-between;
		margin-bottom: 12rpx;
		border-radius: 10rpx;
	}
	.layout1-left{
		flex: 1;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
	}
	.layout1-img{
		width: 268rpx;
		height: 202rpx;
		margin-bottom: 20rpx;
	}
	.read-count{
		color: #888;
		font-size: 10pt;
	}
	
	.layout2{
		padding: 24rpx;
		background: #FFFFFF;
		margin-bottom: 12rpx;
		border-radius: 10rpx;
	}
	.layout2-img{
		width: 100%;
		height: 350rpx;
	}
</style>