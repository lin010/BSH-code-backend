<template>
	<view class="log-root">
		<main-tabbar :tabBar="tabbar"></main-tabbar>
		<view class="tab flex flex-x-between">
			<view class="tab-item" v-for="(item,index) in tab_list" :key='index' @tap="switchTab(index)">
				<text :class="{activity:index == tab_index}" :style="{color:index == tab_index?textColor:'','border-bottom-color':index == tab_index?textColor:''}">{{item}}</text>
			</view>
		</view>
		
		<block v-if="tab_index == 0">
			<block v-if="showData.length > 0">
				<view class="main" v-for="(item,index) in showData" :key='index'>
					<view class="date">{{item.date}}</view>
					
					<view class="log-item" v-for="(itemL,indexL) in item.list" :key='indexL'>
						<view class="top flex flex-y-center flex-x-between">
							<view class="flex flex-y-center" @tap="navTo(`/plugins/business-card/client/detail?user_id=${itemL.user_id}`)">
								<image class="avatar" :src="itemL.user_data.avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
								<view class="companyName over1">{{itemL.user_data.nickname}}</view>
								<view class="flex time">
									<view class="time-icon iconfont icon-shouji1"></view>
									<view>{{itemL.time}}</view>
								</view>
							</view>
							<view class="btn" @tap="goChat(itemL.user_data,itemL.user_id)" :style="{background:textColor}">私聊</view>
						</view>
						
						<view class="bottom"><text :style="{color:textColor}">{{itemL.text}}{{itemL.action_name}}</text>你的<text :style="{color:textColor}">{{itemL.type_name}}</text>，你成功吸引了TA</view>
					</view>
				</view>
			</block>
			<block v-else>
				<view style="text-align: center;margin-top: 200rpx;">暂时没有更多行为</view>
			</block>
		</block>
		
		<!-- <com-modal :show="is_modal" padding='30rpx 30rpx' width="80%" :custom='true'>
			<view class="business-box">
				<view class="title">添加为</view>
				
				<view class="list-box">
					<view :class="{col:index == select_index}" class="list-item" v-for="(item,index) in business_list" @tap="select(index)" :key='index'>{{item}}</view>
				</view>
				
				<view class="confim-btn" @tap="hide">确定添加</view>
			</view>
		</com-modal> -->
		
		
		<!-- 轨迹 -->
		<block v-if="tab_index == 1">
			<view class="timeTab-box flex flex-x-between flex-y-center">
				<view class="timeTab-item" :style="{color:index == time_index?textColor:'','border-color':index == time_index?textColor:''}" @tap="switchTime(index)" v-for="(item,index) in time_list" :key='index'>{{item}}</view>
			</view>
			<view style="height: 16rpx;background: #F7F7F7;"></view>
			
			<view class="content flex flex-x-between">
				<view class="content-item flex flex-col flex-x-center flex-y-center" v-for="(item,index) in track_data.list" :key='index'>
					<view class="title">{{item.name}}</view>
					<view :style="{color:textColor}">{{item.total}}</view>
				</view>
			</view>
			
			<view class="goods-list-box">
				<view class="title" :style="{color:textColor}">热度排行</view>
				
				<view class="goods-list">
					<view class="goods-item flex flex-y-center" v-for="(item,index) in track_data.hot_list" :key='index'>
						<view>{{index+1}}</view>
						<image class="goods-img" :src="item.image" mode=""></image>
						<view class="goods-info flex flex-col flex-x-between">
							<view>
								<view class="goods-name">{{item.name}}</view>
								<!-- <view class="spec">色号：白色</view> -->
							</view>
							<view class="flex flex-x-between">
								<view>浏览量{{item.total}}</view>
								<view>销量{{item.sales}}</view>
							</view>
						</view>
					</view>
				</view>
			</view>
		</block>
		<view style="height: 100rpx;width: 100%;"></view>
		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	var timeArr = [];
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				loading:false,
				tab_list:['行为','轨迹'],
				tab_index:0,
				business_list:['意向客户','比较客户','待成交客户','已成交客户'],
				time_list:['汇总','昨天','7天','15天','30天'],
				time_index:0,
				select_index:0,
				showData:[],
				// 轨迹数据
				track_data:'',
				// 分页
				is_no_more:false,
				page:1,
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
						active: true,
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
			this.getlist();
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
		},
		onReachBottom(e){
			if(this.tab_index == 0){
				this.getlist();
			}
		},
		methods:{
			goChat(obj,id){
				this.$http.request({
					url:this.$api.plugin.business.follow,
					method:'post',
					data:{
						log_type:4,
						remark:'',
						user_id:id,
					}
				}).then(res=>{
					console.log(res,'resssssssss');
				})
				
				obj.id = 'user_'+id;
				uni.setStorageSync('other_info', JSON.stringify(obj));
				uni.navigateTo({
					url:'/plugins/business-card/message/detail',
				})
			},
			getlist(){ //请求行为数据
				if(this.is_no_more){
					uni.showToast({
						title:'暂无更多数据'
					})
					return;
				}
				this.loading = true;
				this.$http.request({
					url:this.$api.plugin.business.behavior,
					method:'post',
					data:{
						limit:6,
						page:this.page,
					}
				}).then(res => {
					this.loading = false;
					if(res.code == 0){
						res.data.list.forEach(item => {
							timeArr.push(item.date);
						})
						
						timeArr = Array.from(new Set(timeArr));
						
						if(this.showData.length == 0){
							timeArr.forEach((item,index) => {
								let obj = {
									date:item,
									list:[]
								}
								this.showData.push(obj);
							})
						}else{
							timeArr.forEach((item,index) => {
								if(this.showData.map(o=>o.date).indexOf(item) == '-1'){
									let obj = {
										date:item,
										list:[]
									}
									this.showData.push(obj);
								}
							})
						}
						
						this.showData.forEach((item,index) => {
							res.data.list.forEach((ritem,rindex) => {
								if(ritem.date == item.date){
									item.list.push(ritem);
								}
							})
						})
						
						if(res.data.pagination.page_count>=this.page){
						  this.page++;
						}else{
							this.is_no_more=true;
						}
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			getTrack(){ //请求轨迹数据
				this.loading = true;
				this.$http.request({
					url:this.$api.plugin.business.track,
					method:'post',
					data:{
						time_type: this.time_index - 1,
						page:this.page,
					}
				}).then(res => {
					this.loading = false;
					if(res.code == 0){
						this.track_data = res.data;
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			switchTab(index){
				this.tab_index = index;
				this.page = 1;
				this.is_no_more = false;
				if(this.tab_index == 0){
					this.showData = [];
					this.getlist();
				}else{
					this.getTrack();
				}
			},
			select(index){
				this.select_index = index;
			},
			switchTime(index){
				this.time_index = index;
				this.page = 1;
				this.is_no_more = false;
				this.getTrack();
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
	// page{
	// 	background: #FFFFFF;
	// }
	.tab{
		background: #FFFFFF;
		padding: 36rpx 0;
		margin-bottom: 30rpx;
		
		.tab-item{
			width: 50%;
			text-align: center;
		}
		.activity{
			border-bottom: 2px solid #BC0100;
			padding-bottom: 14rpx;
			color: #BC0100;
		}
	}
	
	.main{
		padding: 0rpx 30rpx 30rpx;
		
		.date{
			font-size: 30rpx;
			color: #000000;
			margin-bottom: 30rpx;
		}
		.log-item{
			background: #FFFFFF;
			padding: 20rpx 30rpx;
			margin-bottom: 30rpx;
			border-radius: 10rpx;
			
			&:last-child{
				margin-bottom: 0;
			}
			
			.top{
				
				.avatar{
					width: 56rpx;
					height: 56rpx;
					border-radius: 50%;
					margin-right: 16rpx;
				}
				.companyName{
					width: 240rpx;
					color: #000000;
					font-size: 28rpx;
				}
				.time{
					color: #8B8B8B;
					font-size: 24rpx;
					margin-left: 10rpx;
					
					.time-icon{
						font-size: 28rpx;
					}
				}
				.btn{
					background: #BC0100;
					border-radius: 30rpx;
					padding: 6rpx 36rpx;
					color: #FFFFFF;
					font-size: 24rpx;
				}
			}
			
			.bottom{
				margin-top: 20rpx;
				font-size: 28rpx;
				color: #000000;
				letter-spacing: 1px;
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
	
	// 轨迹样式
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
			width: 158rpx;
			height: 158rpx;
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
	.goods-list-box{
		background: #FFFFFF;
		padding-top: 20rpx;
		
		.title{
			color: #BC0100;
			font-size: 30rpx;
			padding: 0 30rpx 20rpx;
		}
		
		.goods-list{
			
			.goods-item{
				padding: 50rpx 24rpx;
				border-top: 1px solid #F2F2F2;
				
				.goods-img{
					width: 154rpx;
					height: 154rpx;
					border-radius: 10rpx;
					margin: 0 20rpx;
				}
				
				.goods-info{
					color: #999999;
					font-size: 24rpx;
					flex: 1;
					height: 154rpx;
				}
				.goods-name{
					color: #000;
					font-size: 30rpx;
					margin-bottom: 6rpx;
				}
			}
		}
	}
</style>
