<template>
	<view class="client-root">
		
		<scroll-view scroll-x="true" class="type-box">
			<view class="type-item" :class="{col:index == type_index}" v-for="(item,index) in type_list" @tap="switchType(index)">{{item}}</view>
		</scroll-view>
		
		<view class="type2-box flex" v-if="type_index == 0">
			<view class="type2-item"  @tap="switchType2(index)" v-for="(item,index) in type2_list" :key='item'>
				<text class="type2-text" :class="{bor:index == type2_index}">
					{{item}}
					<block v-if="detail_data">
						<text v-if="index == 0">({{detail_data.stat.direct_push_total}}人)</text>
						<text v-else>({{detail_data.stat.space_push_total}}人)</text>
					</block>
				</text>
			</view>
		</view>
		
		<view class="goods-list-box">
			<block v-if="clue_list.length != 0">
				<view class="goods-item flex flex-x-between flex-y-center" v-for="(item,index) in clue_list">
					<view class="left flex flex-y-center" @tap="navTo(`/pages/business-card/client/detail?id=${item.user_id}`)">
						<image class="goods-img" :src="item.avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
						
						<view class="detail">
							<view class="shop-name">{{item.nickname}}</view>
							<!-- <view class="text">15次互动来自名片</view> -->
						</view>
					</view>
					<view class="right">
						<view class="flex">
							<view class="btn" @tap="call(item.mobile)" style="margin-right: 20rpx;" v-if="item.status == 1">拨号</view>
							<view class="btn" @tap="show(item.user_id)">商机</view>
						</view>
						<view style="margin-right: 10rpx;">{{item.status_name}}</view>
					</view>
				</view>
			</block>
			<view class="nomore" v-else>暂无更多数据</view>
		</view>
		
		<com-modal :show="is_modal" padding='30rpx 30rpx' width="80%" :custom='true' @cancel="hide('cancel')">
			<view class="business-box">
				<view class="title">添加为</view>
				
				<view class="list-box">
					<view :class="{col:index == select_index}" class="list-item" v-for="(item,index) in business_list" @tap="select(index)" :key='index'>{{item}}</view>
				</view>
				
				<view class="confim-btn" @tap="hide('sure')">确定添加</view>
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
				type2_list:['直推客户','间推客户'],
				type2_index:0,
				is_modal:false,
				business_list:['意向客户','比较客户','待成交客户','已成交客户'],
				select_index:0,
				user_id:0,
				other_id:'',
				detail_data:'',
				clue_list:[],
				page:1,
				is_no_more:false,
			};
		},
		onLoad(options) {
			this.user_id = options.user_id;
			
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
					url:this.$api.plugin.business.my_clue,
					method:'post',
					showLoading:true,
					data:{
						// user_id:this.user_id,
						flag:this.type2_index + 1,
						user_type:this.type_index,
						page:this.page,
					}
				}).then(res => {
					if(res.code == 0){
						this.detail_data = res.data;
						this.clue_list.push(...res.data.list);
						
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
				this.clue_list = [];
				this.is_no_more = false;
			},
			hide(str){
				this.is_modal = false;
				if(str == 'cancel') return;
				
				this.$http.request({
					url:this.$api.plugin.business.business,
					method:'post',
					showLoading:true,
					data:{
						user_type: this.select_index + 1,
						user_id: this.other_id,
					}
				}).then(res => {
					if(res.code == 0){
						this.$http.toast('添加成功');
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			select(index){
				this.select_index = index;
			},
			show(id){
				this.is_modal = true;
				this.other_id = id;
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
		background: #FFFFFF;
	}
	
	// 漏斗图
	.funnel{
		color: #FFFFFF;
		margin: 54rpx 0;
		background: #FFFFFF;
		
		.floor{
			height: 56rpx;
			margin-bottom: 20rpx;
			
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
		
		.type-item{
			width: 25%;
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
				border-bottom: 2px solid #BC0100;
			}
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
				}
			}
		}
		
		.right{
			color: #C2C2C2;
			font-size: 24rpx;
			text-align: right;
			
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
		margin-top: 40rpx;
	}
</style>
