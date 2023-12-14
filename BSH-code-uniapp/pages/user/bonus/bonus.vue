<template>
	<view class="bonus_app">
		<view class="bonus_header">
			<view class="bonus_header_right-che">
				<!-- #ifdef H5 ||APP-PLUS -->
				<view @click="linkSevice">客服</view>
					<!--#endif -->
				<!-- #ifdef MP-WEIXIN -->
				<view>
					<button open-type="contact" :session-from='webapp' class="buttonWeapp" @tap="goService">
						客服
					</button>
				</view>
				<!--#endif -->
				<view @click="linkDetail" v-if="detailShow">我的分红</view>
				<view @click="dialogShow=true">规则</view>
			</view>
		</view>
		<view class="bonus_message">
			<view class="bonus_message_title">
				奖金池
			</view>
			<view class="bonus_message_type">
					<view class="bonus_type_item" v-for="(item,index) in awards_list" :key='index'>
						<view class="bonus_type_item_title">
							{{item.name}}奖金池
						</view>
						<view class="bonus_type_item_num">
							<text>{{item.money}}</text>
							<text>现金金豆</text>
						</view>
					</view>
					<view class="bonus_type_item">
						<view class="bonus_type_item_title">
							总奖金池
						</view>
						<view class="bonus_type_item_num">
							<text>{{money_count}}</text>
							<text>现金金豆</text>
						</view>
					</view>
			</view>	
		</view>
		<view class="bonus_message_my" v-if="detailShow">
			<view class="bonus_message_title">
				我的分红
			</view>
			<view class="bonus_message_my_list">
				<view class="bonus_message_my_item" v-for="(item,index) in bonus_log" :key='index'>
					<view class="bonus_message_my_item-left">
						<text>补商会金豆</text>
						<text>{{item.awards_cycle}}奖金池到账</text>
					</view>
					<view class="bonus_message_my_item-right">
						<text>+{{item.money}}</text>
						<text>{{item.payment_time}}</text>
					</view>
				</view>
			</view>
			<view class="bonus_myInfor">
				<view class="bonus_myInfor_logo">
					<image :src="userMessage.avatar_url" mode=""></image>
				</view>
				<view class="bonus_myInfor_logo_name_lever">
					<text>ID:BSH{{userMessage.user_id}}</text>
					<text>我的分红：{{userMessage.total_price}}</text>
					<text>{{userMessage.level_name}}</text>
				</view>
				<view class="checkALL" @click="linkDetail">
					<button type="default">
						查看全部
					</button>
				</view>
			</view>
		</view>		
		<view class="dialog" v-if='dialogShow' @click="dialogShow=false">
			<view class="agreement">
				<view class="agreement-title">
					补商会奖金池活动
				</view>
				<!-- <view class="agreement-time">
					活动时间：2021/06/8 -2021/07/08
				</view> -->
				<view class="agreement-detail">
					方式：奖金池按照固定时间给有权利分红的所有人平分
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {isEmpty} from '../../../common/validate.js'
	export default {
		data() {
			return {
				serviceLink:'',//客服链接
				webapp: {  //小程序端连接客服需要的数据
					nickName: "",
					avatarUrl: '',
					province: '',
					city: '',
					pageUrl: '',
					pageTitle: '',
					phone: '',
				},
				dialogShow:false,
				userMessage:'',//个人信息
				awards_list:'',//奖金池列表
				bonus_log:'',//我的分红
				money_count:0,//总的可以分红的数
				detailShow:false, //控制个人信息显示
			};
		},
		onLoad() {
			this.getService()
			this.getbouns()
		},
		methods:{
			getService() { //获取客服链接
				this.$http
					.request({
						url: this.$api.moreShop.getservice,
						method: 'POST',
						showLoading: true
					})
					.then(res => {
						if (res.code == 0) {
							this.serviceLink = res.data
						}
					});
			},
			linkSevice(){ //跳转H5客服链接
				//#ifdef H5
				location.href = this.serviceLink
				//#endif
				// #ifdef APP-PLUS
				plus.runtime.openURL(this.serviceLink);//成功跳转了
				//#endif
				
			},
			goService(){ //跳转小程序客服链接
				if(uni.getStorageSync('userInfo')){
					let userMessage=JSON.parse(uni.getStorageSync('userInfo'))
					this.webapp=JSON.stringify(
						{
							nickName:userMessage.nickname,
							avatarUrl:userMessage.avatar,
							province:'',
							city:'',
							pageUrl:'pages/index/index',
							pageTitle:'',
							phone:userMessage.mobile,
						}
					)
				}
			},
			linkDetail(){ //跳转到我的分红明细
				uni.navigateTo({
					url:'./bonusDetail'
				})
			},
			getbouns(){//获取分红信息
				this.$http
					.request({ 
						url: this.$api.moreShop.getbouns,
						method: 'POST',
						showLoading: true,
						data:{
							page:1,
							limit:4
						}
					})
					.then(res => {
						if (res.code == 0) {
							this.userMessage=res.data.list.user_bonus
							if(!isEmpty(this.userMessage)){
								this.detailShow=true
							}
							this.awards_list=res.data.list.awards_list
							this.bonus_log=res.data.list.bonus_log
							this.money_count=res.data.list.money_count
						}
				});
			}
		}
	}
</script>

<style lang="less">
	.bonus_app{width: 100%;overflow: hidden;background: url(https://dev.mingyuanriji.cn/web/static/Prizepool.jpg)no-repeat;background-size: 100%;}
	.allmoney{position: absolute;top: 85rpx;left: 0;width: 100%;font-size: 70rpx;color: #fff;font-weight: 600;
	text-align: center;
	}
	.bonus_header{width: 100%;height: 480rpx;}
	.bonus_header_right-che{width: 130rpx;float: right;height: 200rpx;display: flex;flex-wrap: wrap;margin-top: 220rpx;}
	.bonus_header_right-che view{width: 100%;text-align: center;font-size: 25rpx;line-height: 50rpx;
	margin: 10rpx 0;height: 50rpx;color: white;border-radius: 20rpx 0 0 20rpx;background: #fe381f;}
	.buttonWeapp{width: 100%;text-align: center;font-size: 25rpx;line-height: 40rpx;
	margin: 5rpx 0;height: 40rpx;color: white;border-radius: 10rpx 0 0 10rpx;background: #fe381f;}
	.bonus_message{width: 96%;overflow: hidden;background: rgb(255,210,97);margin: 0 auto;border-radius: 20rpx;}
	.bonus_message_title{margin: 0 auto;width: 200rpx;height: 55rpx;background: red;text-align: center;line-height: 55rpx;
	border-radius: 0 0 20rpx 20rpx;font-size: 30rpx;color: white;}
	.bonus_message_type{width: 100%;overflow: hidden;margin-bottom: 20rpx;}
	.bonus_type_item{width: 30%;height: 220rpx;float: left;
	background: url(https://dev.mingyuanriji.cn/web/static/bouns_type.png)no-repeat;
	background-size: 100% 100%;margin: 20rpx 0 0rpx 18rpx;}
	.bonus_type_item_title{font-size: 25rpx;text-align: center;background: #ffea74;width: 80%;margin: 0 auto;border-radius: 0 0 15rpx 15rpx;color: red;}
	.bonus_type_item_num{text-align: center;}
	.bonus_type_item_num text{display: block;width: 100%;}
	.bonus_type_item_num text:nth-of-type(1){font-size: 36rpx;margin: 18rpx 0 10rpx 0;color: red;}
	.bonus_type_item_num text:nth-of-type(2){font-size: 25rpx;color: red;}
	.bonus_message_my{width: 95%;overflow: hidden;margin: 30rpx auto 120rpx;background: url(https://dev.mingyuanriji.cn/web/static/bonus_message_my.png)no-repeat;background-size: cover;
	border-radius: 50rpx;}
	.bonus_message_my_list{width: 95%;margin: 0 auto;overflow: hidden;}
	.bonus_message_my_item {width: 100%;overflow: hidden;display: flex;justify-content: space-between;margin: 30rpx 0;}
	.bonus_message_my_item-left{width: 65%;}
	.bonus_message_my_item-right{width: 35%;}
	.bonus_message_my_item-left text{display: block;}
	.bonus_message_my_item-left text:nth-of-type(1){font-size: 30rpx;color: #000;}
	.bonus_message_my_item-left text:nth-of-type(2){font-size: 30rpx;color: red;font-size: 25rpx;}
	.bonus_message_my_item-right text{display: block;text-align: right;}
	.bonus_message_my_item-right text:nth-of-type(1){font-size: 30rpx;color: red;}
	.bonus_message_my_item-right text:nth-of-type(2){font-size: 30rpx;font-size: 25rpx;text-align: left}
	.bonus_myInfor{width: 95%;overflow: hidden;display: flex;justify-content: space-between;margin: 0 auto 20rpx;}
	.bonus_myInfor_logo{width: 150rpx;height: 150rpx;}
	.bonus_myInfor_logo image{display: block;width: 150rpx;height: 150rpx;border-radius: 50%;}
	.bonus_myInfor_logo_name_lever text{display: block;}
	.checkALL {width: 180rpx;height: 60rpx;margin-top: 50rpx;}
	.checkALL button{width: 180rpx;font-size: 25rpx;height: 60rpx;background: #fc922b;color: #fff;text-align: center;line-height: 60rpx;}
	.bonus_myInfor_logo_name_lever text:nth-of-type(1){font-size: 26rpx;color: #7e3800;}
	.bonus_myInfor_logo_name_lever text:nth-of-type(2){font-size: 30rpx;color: #7e3800;}
	.bonus_myInfor_logo_name_lever text:nth-of-type(3){font-size: 25rpx;color:red;}
	.dialog{width: 100%;height: 100%;position: fixed;top: 0;right: 0;background: rgba(0,0,0,.5);z-index: 999;}
	.agreement{width: 80%;height: 500rpx;position: absolute;top: 0;right: 0;left: 0;bottom: 0;margin: auto;background: #fff;border-radius: 20rpx;z-index: 999;}

	.agreement-title{width: 100%;overflow: hidden;text-align: center;font-size: 30rpx;color: #000;margin: 20rpx 0;}
	.agreement-time{width: 100%;overflow: hidden;color: #000;font-size: 25rpx;padding: 0 20rpx;margin-bottom: 20rpx;}
	.agreement-detail{width: 100%;overflow: hidden;font-size: 26rpx;color: #000;padding: 0 20rpx;}
</style>
