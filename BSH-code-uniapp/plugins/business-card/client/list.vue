<template>
	<view class="client-root">
		<main-tabbar :tabBar="tabbar"></main-tabbar>
		<!-- 搜索框 -->
		<view class="search" @tap="navTo('/plugins/business-card/client/search?type=clentList')">
			<search message="查询他的名字、昵称" frameColor="#ffffff"
			 innerFrameColor="#F5F5F5" textColor="#999999" :borderRadius="50"></search>
		</view>
		
		<!-- 漏斗图 -->
		<view class="funnel flex flex-col flex-y-center">
			<block v-if="detail_data">
				<view class="floor flex flex-y-center">
					<view class="left-triangle"></view>
					<view class="center-rectangle">总客户数:{{detail_data.header_stat.client_total || 0}}</view>
					<view class="right-triangle"></view>
				</view>
				<view class="floor flex flex-y-center">
					<view class="left-triangle left-triangle2"></view>
					<view class="center-rectangle center-rectangle2">跟进中:{{detail_data.header_stat.follow_total || 0}}</view>
					<view class="right-triangle right-triangle2"></view>
				</view>
				<view class="floor flex flex-y-center">
					<view class="left-triangle left-triangle3"></view>
					<view class="center-rectangle center-rectangle3">成交数:{{detail_data.header_stat.deal_total || 0}}</view>
					<view class="right-triangle right-triangle3"></view>
				</view>
			</block>
		</view>
		
		<!-- 数据列表 -->
		<view class="detail-data flex flex-x">
			<block v-if="detail_data">
				<view class="data-item">
					<view class="col" :style="{color:textColor}">{{detail_data.header_stat.fans_total || 0}}人</view>
					<view>粉丝人数</view>
				</view>
				<view class="data-item">
					<view class="col" :style="{color:textColor}">{{detail_data.header_stat.team_total || 0}}人</view>
					<view>.总人数</view>
				</view>
				<view class="data-item">
					<view class="col" :style="{color:textColor}">{{detail_data.header_stat.team_order_count || 0}}人</view>
					<view>订单数量</view>
				</view>
				<view class="data-item">
					<view class="col" :style="{color:textColor}">{{detail_data.header_stat.team_order_total || 0}}元</view>
					<view>订单金额</view>
				</view>
			</block>
		</view>
		
		<scroll-view scroll-x="true" class="type-box">
			<view class="type-item" :style="{color:index == type_index?textColor:''}" v-for="(item,index) in type_list" :key='index' @tap="switchType(index)">{{item}}</view>
		</scroll-view>
		
		<view class="type2-box flex" v-if="type_index == 0">
			<view class="type2-item"  @tap="switchType2(index)" v-for="(item,index) in type2_list" :key='item'>
				<text class="type2-text bor" :style="{'border-bottom-color':index == type2_index?textColor:''}">
					{{item}}
					<block v-if="detail_data">
						<text v-if="index == 0">({{detail_data.stat.direct_push_total}}人)</text>
						<text v-else>({{detail_data.stat.space_push_total}}人)</text>
					</block>
				</text>
			</view>
		</view>
		
		<view class="goods-list-box">
			<block v-if="client_list.length != 0">
				<view class="goods-item flex flex-x-between flex-y-center" v-for="(item,index) in client_list" :key='index' >
					<view class="left flex flex-y-center" @tap="navTo(`/plugins/business-card/client/detail?user_id=${item.user_id}`)">
						<image class="goods-img" :src="item.avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
						
						<view class="detail">
							<view class="shop-name">{{item.nickname}}</view>
							<!-- <view class="text">15次互动来自名片</view> -->
						</view>
					</view>
					<view class="right flex flex-col flex-y-center">
						<view class="btn" @tap="call(item.mobile)" style="margin-right: 20rpx;" v-if="item.status == 1">打电话</view>
						<view class="btn" @tap="show(item.user_id,index)" v-if="type2_index == 0">商机</view>
						<view style="width: 130rpx;" v-if="type2_index == 0">{{item.status_name}}</view>
					</view>
				</view>
			</block>
			<view class="nomore" v-else>暂无更多数据</view>
		</view>
		
		<view style="height: 100rpx;width: 100%;"></view>
		
		<com-modal :show="is_modal" padding='30rpx 30rpx' width="80%" :custom='true' @cancel='hide("onlyHide")'>
			<view class="business-box">
				<view class="title">添加为</view>
				
				<view class="list-box">
					<view :class="{col:index == select_index}" class="list-item" v-for="(item,index) in business_list" @tap.stop="select(index)" :key='index'>{{item}}</view>
				</view>
				
				<view class="confim-btn" @tap.stop="hide">确定添加</view>
			</view>
		</com-modal>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				type_list:['全部客户','意向客户','比较客户','待成交','已成交'],
				type_index:0,
				type2_list:['直客户','间客户'],
				type2_index:0,
				is_modal:false,
				business_list:['意向客户','比较客户','待成交客户','已成交客户'],
				select_index:0,
				
				keywords:'',
				user_id:0,
				clent_id:'', //客户的user_id
				client_index:'',
				detail_data:'',
				client_list:[],
				page:1,
				is_no_more:false,
				textColor:"",
				
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
						active: true,
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
		onLoad(options) {
			this.user_id = options.user_id;
			this.keywords = options.keywords;
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
			
			this.getData();
		},
		onReachBottom(){
			this.getData();
		},
		methods:{
			getData(){
				if(this.is_no_more){
					this.$http.toast('没有更多数据了');
					return;
				}
				this.$http.request({
					url:this.$api.plugin.business.my_client,
					method:'post',
					showLoading:true,
					data:{
						user_id:this.user_id,
						flag:this.type2_index + 1,
						user_type:this.type_index,
						keywords:this.keywords,
						page:this.page,
					}
				}).then(res => {
					if(res.code == 0){
						this.detail_data = res.data;
						this.client_list.push(...res.data.list);
						
						if(res.data.pagination.page_count > this.page){
							this.page++;
						}else{
							this.is_no_more = true;
						}
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
			switchType2(index){
				this.type2_index = index;
				this.initData();
				this.getData();
			},
			switchType(index){
				this.type_index = index;
				this.initData();
				this.getData();
			},
			initData(){
				this.page = 1;
				this.client_list = [];
				this.is_no_more = false;
			},
			hide(str){
				this.is_modal = false;
				if(str == 'onlyHide') return;
				this.$http.request({
					url:this.$api.plugin.business.business,
					method:'post',
					showLoading:true,
					data:{
						user_type: this.select_index+1,
						user_id:this.clent_id
					}
				}).then(res => {
					if(res.code == 0){
						this.$http.toast('添加成功');
						var clent_str = '';
						switch(this.select_index+1) {
						     case 1:
						        clent_str = '意向客户';
						        break;
						     case 2:
						        clent_str = '比较客户';
						        break;
							case 3:
							   clent_str = '待成交客户';
							   break;
							case 4:
							   clent_str = '已成交客户';
							   break;
						} 
						this.client_list[this.client_index].status_name = clent_str;
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			select(index){
				this.select_index = index;
			},
			show(id,index){
				this.clent_id = id;
				this.client_index = index;
				this.is_modal = true;
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
	page{
		background: #FFFFFF !important;
	}
	
	// 漏斗图
	.funnel{
		color: #FFFFFF;
		padding: 54rpx 0;
		background: #FFFFFF;
		
		.floor{
			height: 56rpx;
			margin-bottom: 20rpx;
			overflow: hidden;
			
			.left-triangle{
				width: 0; height: 0;
				border-color: #FF6565 #FF6565 transparent transparent;
				border-width: 30rpx 18rpx 30rpx 18rpx;
				border-style: solid;
			}
			.center-rectangle{
				background: #FF6565;
				width: 380rpx;
				height: 60rpx;
				font-size: 26rpx;
				line-height: 60rpx;
				text-align: center;
			}
			.right-triangle{
				width: 0; height: 0;
				border-color: #FF6565 transparent transparent  #FF6565;
				border-width: 30rpx 18rpx 30rpx 18rpx;
				border-style: solid;
			}
			
			.left-triangle2{
				border-color: #F12625 #F12625 transparent transparent;
			}
			.center-rectangle2{
				width: 294rpx;
				background: #F12625;
			}
			.right-triangle2{
				border-color: #F12625 transparent transparent  #F12625;
			}
			
			.left-triangle3{
				border-color: #BB0100 #BB0100 transparent transparent;
			}
			.center-rectangle3{
				width: 210rpx;
				background: #BB0100;
			}
			.right-triangle3{
				border-color: #BB0100 transparent transparent  #BB0100;
			}
			
		}
	}
	
	// 数据列表
	.detail-data{
		border-top: 1px solid #F3F3F3;
		background: #FFFFFF;
		
		.data-item{
			width: 25%;
			text-align: center;
			font-size: 24rpx;
			color: #333333;
			padding: 34rpx 0;
		}
		.col{
			font-weight: 600;
			color: #BC0100;
			font-size: 28rpx;
		}
	}
	
	.type-box{
		height: 104rpx;
		white-space: nowrap;
		background: #FFFFFF;
		
		.type-item{
			width: 24%;
			border: 1px solid #F2F2F2;
			text-align: center;
			line-height: 100rpx;
			display: inline-block;
		}
		.col{
			color: #BC0100;
		}
	}
	
	.type2-box{
		background: #FFFFFF;
		
		.type2-item{
			width: 50%;
			height: 120rpx;
			text-align: center;
			line-height: 120rpx;
			font-size: 28rpx;
			color: #000000;
			
			.type2-text{
				padding: 0 10rpx 8rpx;
			}
			.bor{
				border-bottom: 2px solid transparent;
			}
		}
	}
	
	.goods-list-box{
		background: #FFFFFF;
		
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
				width: 110rpx;
				height: 40rpx;
				border-radius: 40rpx;
				text-align: center;
				line-height: 40rpx;
				font-size: 24rpx;
				margin-bottom: 4rpx;
			}
		}
	}
	
	// 弹窗
	.business-box{
		padding: 42rpx 56rpx 24rpx;
		
		.title{
			font-size: 36rpx;
			color: #000;
			text-align: center;
			letter-spacing: 2px;
			margin-bottom: 38rpx;
		}
		
		.list-box{
			
			.list-item{
				height: 70rpx;
				border-radius: 40rpx;
				border: 1px solid #BC0100;
				color: #BC0100;
				font-size: 30rpx;
				text-align: center;
				line-height: 70rpx;
				margin-bottom: 30rpx;
			}
		}
		
		.confim-btn{
			color: #FFFFFF;
			background: #BC0100;
			width: 304rpx;
			height: 82rpx;
			text-align: center;
			line-height: 82rpx;
			border-radius: 50rpx;
			margin: 60rpx auto 0;
		}
		.col{
			background: #BC0100;
			color: #FFFFFF !important;
			border: 0;
		}
	}
	
	.nomore{
		text-align: center;
		padding: 40rpx;
	}
</style>
