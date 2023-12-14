<template>
	<view class="my-root">
		<main-tabbar :tabBar="tabbar"></main-tabbar>
		<block v-if="detail_data">
			<view class="header flex">
				<image class="avatar-img" :src="detail_data.user_data.avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
				<view class="detail">
					<view class="shop-name">{{detail_data.user_data.nickname}}</view>
					<view class="common">职位：{{detail_data.user_data.position_name}}</view>
					<view class="flex common">
						推荐码:
						<view class="code" @tap="navTo('/plugins/business-card/code/code')" :style="{background:textColor}">
							<view class="code-content">
								<text class="code-icon iconfont icon-fenxiangcopy"></text>二维码
							</view>
						</view>
					</view>
				</view>
				
				<view class="set-icon iconfont icon-shezhi" @tap="navTo(`/plugins/business-card/my/edit?id=${detail_data.user_data.id}`)"></view>
			</view>
		</block>
		
		<view class="features flex">
			<view class="fea-item flex flex-col flex-y-center" @tap="share">
				<view class="top" :style="{background:textColor}">
					<text class="top-icon iconfont icon-zhuanfa"></text>
				</view>
				<view>发名片</view>
				<!-- #ifdef MP-WEIXIN -->
				<button class="share-btn" open-type="share">分享</button>
				<!-- #endif -->
			</view>
			
			<view class="fea-item flex flex-col flex-y-center" @tap="navTo('/plugins/business-card/index')">
				<view  class="top" :style="{background:textColor}">
					<text class="top-icon iconfont icon-chakan"></text>
				</view>
				<view>名片预览</view>
			</view>
			<view class="fea-item flex flex-col flex-y-center" @tap="navTo('/plugins/business-card/client/list')">
				<view  class="top" :style="{background:textColor}">
					<text class="top-icon iconfont icon-kehu"></text>
				</view>
				<view>我的客户</view>
			</view>
			<view class="fea-item flex flex-col flex-y-center" @tap="navTo('/plugins/business-card/my/clue')">
				<view  class="top" :style="{background:textColor}">
					<text class="top-icon iconfont icon-xiansuoQ"></text>
				</view>
				<view>我的线索</view>
			</view>
		</view>
		
		<view class="timeTab-box flex flex-x-between flex-y-center">
			<view class="timeTab-item" :style="{color:index == time_index?textColor:'','border-color':index == time_index?textColor:''}" @tap="switchTime(index)" v-for="(item,index) in time_list" :key='index'>{{item}}</view>
		</view>
		<view style="height: 16rpx;background: #F7F7F7;"></view>
		
		<view class="content flex flex-x-between">
			<view class="content-item flex flex-col flex-x-center flex-y-center">
				<view class="title">新增客户</view>
				<view :style="{color:textColor}">{{detail_data.new_client_total}}</view>
			</view>
			<view class="content-item flex flex-col flex-x-center flex-y-center">
				<view class="title">浏览量</view>
				<view :style="{color:textColor}">{{detail_data.browse_total}}</view>
			</view>
			<view class="content-item flex flex-col flex-x-center flex-y-center">
				<view class="title">新增线索</view>
				<view :style="{color:textColor}">{{detail_data.new_clue_total}}</view>
			</view>
			<view class="content-item flex flex-col flex-x-center flex-y-center">
				<view class="title">订单数</view>
				<view :style="{color:textColor}">{{detail_data.team_order_count}}</view>
			</view>
			<view class="content-item flex flex-col flex-x-center flex-y-center">
				<view class="title">订单金额</view>
				<view :style="{color:textColor}">{{detail_data.team_order_total}}</view>
			</view>
			<view class="content-item flex flex-col flex-x-center flex-y-center">
				<view class="title">下单人数</view>
				<view :style="{color:textColor}">{{detail_data.order_user_total}}</view>
			</view>
			<view class="content-item flex flex-col flex-x-center flex-y-center">
				<view class="title">意向客户</view>
				<view :style="{color:textColor}">{{detail_data.intent_total}}</view>
			</view>
			<view class="content-item flex flex-col flex-x-center flex-y-center">
				<view class="title">比较客户</view>
				<view :style="{color:textColor}">{{detail_data.compare_total}}</view>
			</view>
			<view class="content-item flex flex-col flex-x-center flex-y-center">
				<view class="title">待成交客户</view>
				<view :style="{color:textColor}">{{detail_data.clinch_total}}</view>
			</view>
		</view>
		
		<view class="clue-list">
			<view class="clue-title flex" :style="{color:textColor}">
				新增线索
				<text class="clue-icon iconfont icon-tuandui2"></text>
			</view>
			
			<view class="goods-list-box">
				<view class="goods-item flex flex-x-between flex-y-center" v-for="(item,index) in new_clue_list" :key='index'>
					<view class="left flex flex-y-center" @tap="navTo('/plugins/business-card/client/detail')">
						<image class="goods-img" :src=" item.user_data.avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
						
						<view class="detail">
							<view class="shop-name over1">{{item.user_data.nickname}}</view>
							<!-- <view class="text">15次互动来自名片</view> -->
						</view>
					</view>
					<view class="right">
						<view class="btn">
							<view class="trans" @tap="call(item.user_data.mobile)">
								<text class="iconfont icon-shouji2" style="font-size: 28rpx;"></text>
								一键拨号
							</view>
						</view>
						<view>{{item.time}}</view>
					</view>
				</view>
			</view>
		</view>
		
		<view style="height: 100rpx;width: 100%;"></view>
	</view>
</template>

<script>
	import { isWeChat } from '@/utils/util.js';
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				time_list:['昨天','7天','15天','30天','汇总'],
				time_index:0,
				detail_data:'',
				new_clue_list:[],
				textColor:'',
				share_img:'',//分享的图片
				share_title:'',//分享的标题
				
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
						active: true,
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
			this.getData();
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
		},
		methods:{
			share(){
				// #ifdef H5
					if(!isWeChat()){
						this.$http.toast('只能在微信环境下分享喔')
					}else{
						this.$http.toast('公众号请点击右上角进行分享！')
					}
					return;
				// #endif
				
				uni.share({
				    provider: 'weixin',
				    scene: "WXSceneSession",
				    type: 5,
				    imageUrl: 'https://img-cdn-qiniu.dcloud.net.cn/uniapp/app/share-logo@3.png',
				    title: '兰亭名片',
				    miniProgram: {
				        id: 'gh_27e28292b19d',
				        path: 'plugins/business-card/my/index?source=7',
				        type: 2,
				        webUrl: 'http://uniapp.dcloud.io'
				    },
				    success: ret => {
				        console.log(JSON.stringify(ret));
				    }
				});
			},
			getData(){
				this.$http.request({
					url:this.$api.plugin.business.center,
					method:'post',
					showLoading:true,
					data:{
						time_type:this.time_index
					}
				}).then(res => {
					if(res.code == 0){
						this.detail_data = res.data;
						this.new_clue_list = res.data.new_clue_list.list;
						this.share_img = res.data.user_data.app_share_pic;
						this.share_title = res.data.user_data.app_share_title;
						
						// #ifdef H5
						let _url = `plugins/business-card/index?id=${this.detail_data.user_data.id}&source=7`;
						this.$wechatSdk.share(_url,{
							app_share_desc:res.data.user_data.app_share_desc,
							app_share_pic:this.share_img,
							app_share_title:this.share_title,
						});
						// #endif
						
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			call(mobile){
				uni.makePhoneCall({
				    phoneNumber: mobile,  //需要拨打的电话
					success: (res)=>{
						console.log('打电话回调成功！');
					}
				});
			},
			switchTime(index){
				this.time_index = index;
				this.getData();
			},
			navTo(url){
				uni.navigateTo({
					url
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.my-root{
		background: #FFFFFF;
	}
	.header{
		border-bottom: 1px solid #F2F2F2;
		border-top: 1px solid #F2F2F2;
		padding: 40rpx 36rpx;
		position: relative;
		
		.avatar-img{
			width: 120rpx;
			height: 120rpx;
			border-radius: 50%;
			margin-right: 20rpx;
		}
		
		.detail{
			font-size: 24rpx;
			color: #000;
			
			.shop-name{
				font-weight: 700;
				font-size: 30rpx;
			}
			.common{
				margin-bottom: 4rpx;
			}
			.code{
				color: #FFFFFF;
				background: #BC0100;
				padding: 0rpx 14rpx;
				border-radius:50rpx;
				letter-spacing: 1px;
				margin-left: 10rpx;
				
				.code-content{
					transform: scale(0.8);
				}
				.code-icon{
					font-size: 30rpx;
					margin-right: 4rpx;
				}
			}
		}
		
		.set-icon{
			color: #000;
			position: absolute;
			top: 20rpx;
			right: 44rpx;
		}
	}
	
	.features{
		padding: 40rpx 0;
		justify-content: space-around;
		
		.fea-item{
			width: 25%;
			font-size: 26rpx;
			text-align: center;
			position: relative;
			
			.top{
				width: 54rpx;
				height: 54rpx;
				background: #BC0100;
				border-radius: 6rpx;
				color: #FFFFFF;
				margin-bottom: 6rpx;
				line-height: 54rpx;
				
				.top-icon{
					font-size: 40rpx;
				}
			}
			
			.share-btn{
				position: absolute;
				top: 0;
				left: 50%;
				transform: translateX(-50%);
				width: 80rpx;
				height: 100rpx;
				opacity: 0;
				z-index: 3;
			}
		}
	}
	
	
	.timeTab-box{
		padding: 20rpx 30rpx;
		background: #FFFFFF;
		border-top: 1px solid #F2F2F2;
		
		.timeTab-item{
			width: 110rpx;
			height: 50rpx;
			text-align: center;
			line-height: 50rpx;
			border: 1px solid #C0C0C0;
			border-radius: 16rpx;
			font-size: 24rpx;
		}
		.active{
			border: 1px solid #BC0100;
			color: #BC0100;
		}
	}
	.content{
		background: #FFFFFF;
		padding: 30rpx;
		flex-wrap: wrap;
		
		.content-item{
			width: 216rpx;
			height: 216rpx;
			border: 1px solid #C0C0C0;
			font-size: 32rpx;
			color: #BC0100;
			margin-bottom: 18rpx;
			
			.title{
				color: #8F8F8F;
				font-size: 24rpx;
			}
		}
	}
	
	// 线索
	.clue-list{
		
		.clue-title{
			color: #BC0100;
			font-size: 30rpx;
			padding: 26rpx 30rpx;
			border-bottom: 1px solid #F2F2F2;
			
			.clue-icon{
				margin-left: 2rpx;
				font-size: 34rpx;
			}
		}
		
		.goods-list-box{
			
			.goods-item{
				padding: 30rpx 40rpx;
				border-top: 1px solid #F3F3F3;
			}
			
			.left{
				
				.goods-img{
					width: 120rpx;
					height: 120rpx;
					border-radius: 50%;
					margin-right: 20rpx;
				}
				
				.detail{
					color: #BC0100;
					font-size: 24rpx;
					
					.shop-name{
						font-size: 30rpx;
						color: #000000;
						font-weight: 600;
						letter-spacing: 1px;
						width: 370rpx;
					}
				}
			}
			
			.right{
				color: #C2C2C2;
				font-size: 24rpx;
				text-align: center;
				
				.btn{
					background: #BC0100;
					color: #FFFFFF;
					height: 40rpx;
					border-radius: 40rpx;
					text-align: center;
					line-height: 40rpx;
					font-size: 24rpx;
					margin-bottom: 4rpx;
					letter-spacing: 1px;
					
					.trans{
						transform: scale(0.8);
					}
				}
			}
		}
	}
</style>
