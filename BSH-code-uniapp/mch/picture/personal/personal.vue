<template>
	<view>
		<view class="cover-img-box">
			<image class="cover-img" :src="img_url+'ceshi/ceshi1.jpg'" mode="widthFix"></image>
			<view class="cover-edit" @tap="edit">
				<view class="cover-icon iconfont icon-shezhi"></view>
				<view class="cover-edit-text">编辑</view>
			</view>
		</view>
		
		<view style="padding: 0 30rpx;">
			<view class="userinfo-box">
				<view class="avatar" :style="{background: 'url('+img_url+'ceshi/ceshi.png)'}"></view>
				<view class="userinfo2-box">
					<view class="user2-title">七件事社交电商软件开发</view>
					<view class="vipId">会员ID：1349486</view>
					<view class="user2-label-box">
						<view class="user2-label">
							<view class="user2-label-icon-box">
								<view class="user2-label-icon iconfont icon-dianpu"></view>
							</view>
							<view>七件事商城</view>
						</view>
					</view>
				</view>
			</view>
			
			<view class="income-box">
				<view class="income-box-item">
					<view class="money">100.56元</view>
					<view class="source">昨日收益</view>
				</view>
				<view class="income-box-item">
					<view class="money">100.56元</view>
					<view class="source">本周收益</view>
				</view>
				<view class="income-box-item">
					<view class="money">1384元 </view>
					<view class="source">本月收益</view>
				</view>
				<view class="income-box-item">
					<view class="money">1564.94元</view>
					<view class="source">累计收益</view>
				</view>
			</view>
			
			<view class="signature">
				<view class="signature-title">个性签名:</view>
				有一颗热爱工作热爱生活的心，只要有勇闯的冲劲，没有克 服不了的困难!
			</view>
			
			<view class="fan">
				<view class="fan-item"><text class="fan-num">0</text> 关注</view>
				<view class="fan-item"><text class="fan-num">197</text> 粉丝</view>
				<view class="fan-item"><text class="fan-num">13</text> 获赞与收藏</view>
			</view>
			
			<view class="tabs">
				<view class="tabs-item" :class="{'tabs-item-active':tabsIndex == index}" 
				v-for="(item,index) in tabList" :key='index' @tap="switchTabs(index)">{{item}}</view>
			</view>
			
			<view class="content-list-box">
				<view class="content-list-item" v-for="item in 3">
					<view class="item-img":style="{background: 'url('+img_url+'ceshi/ceshi.png)no-repeat'}"></view>
					<view class="item-name">不以香气诱人，更以神思为境#IN LIGHT#哈哈哈哈哈哈</view>
					<view class="item-like">
						<view class="item-like-left">
							<image class="item-like-avatar" :src="img_url+'ceshi/ceshi.png'" mode=""></image>
							<view class="over1">INLIGHT美食摄影</view>
						</view>
						<view class="item-like-left">
							<view class="item-like-right-icon iconfont icon-shoucang1"></view>
							<view class="item-like-right-num">1.2 万</view>
						</view>
					</view>
				</view>
			</view>
		</view>
		
		<br><br>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				tabList:['我的作品','我喜欢的'],
				tabsIndex:0,
			}
		},
		methods: {
			attention(){ //关注事件
				this.$http.toast('点击了关注')
			},
			switchTabs(index){ //tab切换
				this.tabsIndex = index;
			},
			edit(){
				this.$http.toast('点击了编辑')
			}
		}
	}
</script>

<style scoped>
	page{
		background: #FFFFFF;
	}
	.cover-img-box{
		position: relative;
	}
	.cover-img{
		width: 100%;
		display: block;
	}
	.cover-edit{
		position: absolute;
		top: 30rpx;
		right: 30rpx;
		color: #FFFFFF;
		display: flex;
		font-size: 10pt;
		align-items: center;
	}
	.cover-icon{
		font-size: 28rpx;
		line-height: 28rpx;
		margin-right: 8rpx;
	}
	.cover-edit-text{
		padding-bottom: 6rpx;
	}
	.userinfo-box{
		display: flex;
		padding: 36rpx 0;
		border-bottom: 2rpx solid #F2F2F2;
	}
	.avatar{
		width: 120rpx;
		height: 120rpx;
		border-radius: 50%;
		background-size: cover !important;
		background-position: center !important;
		margin-right: 32rpx;
	}
	.userinfo{
		flex: 1;
	}
	.userinfo-top{
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.userinfo-top-item{
		text-align: center;
		font-size: 11pt;
		color: #000000;
	}
	.userinfo-top-item-name{
		font-size: 10pt;
	}
	.userinfo-bottom{
		background: #BC0100;
		color: #FFFFFF;
		border-radius: 40rpx;
		text-align: center;
		padding: 8rpx 0;
		font-size: 11pt;
		width: 90%;
		margin: 25rpx auto 0;
	}
	
	.userinfo2-box{
		color: #000000;
	}
	.user2-title{
		font-weight: 700;
		font-size: 13pt;
	}
	.vipId{
		margin: 10rpx 0;
		font-size: 10pt;
	}
	.user2-label-box{
		background: #CCCCCC;
		border-radius: 30rpx;
		display: inline-block;
	}
	.user2-label{
		color: #FFFFFF;
		display: flex;
		align-items: center;
		font-size: 8pt;
		padding: 6rpx 20rpx;
		letter-spacing: 2rpx;
	}
	.user2-label-icon-box{
		width: 30rpx;
		height: 30rpx;
		border-radius: 50%;
		background: #BC0100;
		position: relative;
		margin-right: 10rpx;
	}
	.user2-label-icon{
		font-size: 20rpx;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%,-50%) scale(0.85);
	}
	
	.income-box{
		padding: 20rpx 0rpx;
		display: flex;
		justify-content: space-around;
		border-bottom: 2rpx solid #F2F2F2;
	}
	.income-box-item{
		text-align: center;
	}
	.money{
		color: #BC0100;
		font-size: 10pt;
		font-weight: 700;
		letter-spacing: 2rpx;
		margin-bottom: 4rpx;
	}
	.source{
		font-size: 8pt;
		letter-spacing: 2rpx;
	}
	
	
	.signature{
		padding: 30rpx 0;
		font-size: 9pt;
		color: #000000;
		letter-spacing: 2rpx;
		border-bottom: 2rpx solid #F2F2F2;
	}
	.signature-title{
		font-size: 11pt;
		margin-bottom: 20rpx;
	}
	
	.fan{
		display: flex;
		padding: 30rpx 0;
		color: #000000;
		font-size: 10pt;
		font-weight: 700;
	}
	.fan-item{
		margin-right: 36rpx;
	}
	.fan-num{
		color: #BC0100;
		font-weight: 700;
		margin-right: 10rpx;
		font-size: 11pt;
	}
	
	.tabs{
		display: flex;
		justify-content: space-around;
	}
	.tabs-item{
		padding: 30rpx 0;
		font-size: 10pt;
		color: #000000;
		letter-spacing: 2rpx;
	}
	.tabs-item-active{
		border-bottom: 4rpx solid #BC0100;
	}
	
	.content-list-box{
		padding: 30rpx 0;
		display: flex;
		justify-content: space-between;
		flex-wrap: wrap;
	}
	.content-list-item{
		border-radius: 10rpx;
		overflow: hidden;
		width: 340rpx;
		margin-bottom: 30rpx;
	}
	.item-img{
		background-size: cover !important;
		width: 100%;
		height: 458rpx;
	}
	.item-name{
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		margin: 12rpx 0;
		color: #000000;
		font-size: 11pt;
		padding: 0 10rpx;
	}
	.item-like{
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 0 10rpx;
	}
	.item-like-left{
		display: flex;
		align-items: center;
		color: #000000;
		font-size: 8pt;
	}
	.item-like-avatar{
		width: 35rpx;
		height: 35rpx;
		border-radius: 50%;
		margin-right: 6rpx;
	}
	.item-like-right-icon{
		margin-right: 6rpx;
		font-size: 28rpx;
		line-height: 28rpx;
	}
	.item-like-right-num{
		margin-bottom: 2rpx;
	}
</style>
