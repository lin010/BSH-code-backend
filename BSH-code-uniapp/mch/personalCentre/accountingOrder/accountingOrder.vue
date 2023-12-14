<template>
	<view class="accountingOrder_app">
		<view class="accountingOrder_title">
			手动输入核销码或扫码进行核销
		</view>
		<view class="accountingOrder_int">
			<input type="text" value="" v-model.trim="code" disabled placeholder="暂时不支持输入核销,请扫一扫核销"/>
			<button type="default" @click='btn' disabled>确定</button>
		</view>
		<view class="accountingOrder_scan" @click='ercodeBtn'>
			<image :src="img_url+'/shop-scan.png'" mode=""></image>
		</view>
		<view class="personalCenter-item" style="background:white;">
			<jx-list-cell  padding="0" :lineLeft="false">
				<view class="jx-cell-header">
					<view class="jx-cell-title">最近核销记录</view>
					<!-- <view class="jx-cell-sub">查看全部订单</view> -->
				</view>
			</jx-list-cell>
			<!-- <view class="personalCenter-item-list" v-for="(item,index) in list" :key='index'>
				<image :src="item.goods_info.goods_attr.cover_pic" mode=""></image>
				<view class="personalCenter-item-list-message">
					<view class="buyName" >
						购买人：{{item.nickname}}
					</view>
					<view class="personalCenter-item-list-message-title">
						{{item.goods_info.goods_attr.name}}
						x{{item.goods_info.goods_attr.number}}
					</view>
					<view class="personalCenter-item-list-message-time">
						{{item.format_date}}
					</view>
				</view>				
			</view> -->
			<view class="hexiao-list" v-for="(item,index) in list" :key='index' style="padding-left:20rpx;width:100%;font-size:25rpx;height:150rpx;">
				<view style="width: 60%;">
					<view style="height: 50rpx;line-height: 50rpx;">{{item.descript}}</view>
					<view style="height: 50rpx;line-height: 50rpx;">用户：{{item.pay_user_name}}</view>
					<view style="height: 50rpx;line-height: 50rpx;">手机：{{item.mobile}}</view>
				</view>
				<view style="width:40%;line-height:150rpx">
					{{item.created_at}}
				</view>	
			</view>
		</view>
	</view>
</template>

<script>
	import jxListCell from '@/components/list-cell/list-cell';
	//#ifdef H5
		var jweixin = require('jweixin-module');
	//#endif
	export default {
		components: {
			jxListCell
		},
		data() {
			return {
				code:'',//核销code
				img_url: this.$api.img_url,
				form:{
					page:1
				},
				list:[],
				page_count:''
			}
		},
		onLoad() {
		//#ifdef H5
			this.$wechatSdk.initJssdk(function(signData){
				console.log(signData)
			});
		//#endif
			this.getList()
		},
		methods: {
			ercodeBtn(){
				//#ifdef H5
					jweixin.scanQRCode({
					  needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
					  scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
					  success: function (res) {
						var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
					  }
					});
				//#endif
				// #ifdef MP-WEIXIN || APP-PLUS
						uni.scanCode({
							onlyFromCamera: true,
						    success: function (res) {
						      
						    }
						});
				// #endif
			},			
			btn(){ //点击确认核销码
				if(this.code.length<=0){
					this.$http.toast("核销码不能为空")
					return
				}
				uni.navigateTo({
					url:'../../newmoreShop/newmoreShop?code='+this.code
				})	
			},
			getList(){
				// this.$http
				// 	.request({
				// 		url: this.$api.moreShop.getclerkList,
				// 		method: 'POST',
				// 		data:this.form,
				// 		showLoading: true,
				// 	})
				// 	.then(res => {
				// 		if(res.code==0){
				// 			console.log(res.data)
				// 			if(res.data.list.length==0)return false
				// 			let arr= res.data.list;
				// 			this.list=this.list.concat(arr)
				// 			this.page_count= res.data.pagination.page_count;
				// 		}else{
				// 			this.$http.toast(res.msg);
				// 		}
				// 	});	
				this.$http
					.request({
						url: this.$api.default.verification,
						method: 'POST',
						data:this.form,
						showLoading: true,
					})
					.then(res => {
						if(res.code==0){
							console.log(res.data)
							if(res.data.list.length==0)return false
							let arr= res.data.list;
							this.list=this.list.concat(arr)
							this.page_count= res.data.pagination.page_count;
						}else{
							this.$http.toast(res.msg);
						}
					});	
			}
		},
		onReachBottom() {
			if(this.form.page==this.page_count){
				return false;
			}
			this.form.page=this.form.page+1
			this.getList()
		},
	}
</script>

<style scoped>
	.accountingOrder_app{width:100%;height: 100%;
		background: url(https://dev.mingyuanriji.cn/web/static/shop_verification.jpg)no-repeat; 
	background-size: cover;}
	.buyName{font-size: 28rpx;}
	.accountingOrder_title{padding: 30rpx 30rpx 0 30rpx;font-size: 30rpx;width: 100%;color: #fff;}
	.accountingOrder_int{width: 100%;overflow: hidden;padding: 0 30rpx;margin-top: 20rpx;box-sizing: border-box;}
	.accountingOrder_int input{width: 75%;height: 80rpx;background: #fff;border-radius: 20rpx;padding-left: 15rpx;float: left;}
	.accountingOrder_int button{height: 80rpx;outline: none;border: none;width: 20%;text-align: center;line-height: 80rpx;
	font-size: 30rpx;font-weight: bold;color: #000;}
	.accountingOrder_scan{width: 200rpx;height: 200rpx;margin: 100rpx auto;}
	.accountingOrder_scan image{width: 200rpx;height:200rpx;margin: 0 auto;}
	.personalCenter-item{width: 100%;overflow: hidden;margin: 20upx 0;}
	.jx-content-box {
		width: 100%;
		padding: 0 30rpx;
		box-sizing: border-box;
		margin-top: 20rpx;
	}
	.jx-header-btm {
		border-radius: 12rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: space-between;
		color: #000;
		background: #ffffff;
		margin-bottom: 20rpx;
		box-shadow: 1rpx 1rpx 5rpx #888;
	}
	
	.jx-btm-item {
		flex: 1;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		padding: 40rpx;
		position: relative;
	/* 
		&::after {
			content: '';
			border-right: 1rpx solid #b3b3b3;
			width: 1px;
			height: 100rpx;
			position: absolute;
			right: 0;
			top: 50%;
			transform: translateY(-50%);
		}
	
		&:last-child {
			&::after {
				display: none;
			}
		} */
	}
	.jx-cell-header {
		width: 100%;
		height: 100rpx;
		padding: 0 40rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-bottom: 1rpx solid #ddd;
	}
	.jx-cell-title {
		font-size: 30rpx;
		line-height: 30rpx;
		color: #333;
		font-weight: 600;
		letter-spacing: 1px;
	}
	.jx-cell-sub {
		font-size: 9pt;
		font-weight: 400;
		color: #999;
		padding-right: 10rpx;
	}
	.personalCenter-item-list{width: 100%;overflow: hidden;display: flex;padding: 0 20rpx;box-sizing: border-box;margin-bottom: 20rpx;
	border-bottom: 1rpx solid #C0C4CC;}
	.personalCenter-item-list image{width: 110rpx;height: 110rpx;display: block;margin: 20rpx 10rpx 15rpx 0;}
	.personalCenter-item-list-message-title{width: 520rpx;height: 80rpx;
	  overflow: hidden;
	  text-overflow: ellipsis;
	  display: -webkit-box;
	  -webkit-line-clamp: 2;
	  -webkit-box-orient: vertical;
	  font-size: 25rpx;
	}
	.personalCenter-item-list-message-time{font-size: 25rpx;}
	
	
	.hexiao-list{width: 100%;overflow: hidden;display: flex;justify-content: space-evenly;box-sizing: border-box;border-bottom: 1rpx solid #F1F1F1;}
	.hexiao-list:last-child{border-bottom:none;}
</style>
