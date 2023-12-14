<template>
	<view class="">
		<view class="search" @tap.stop="navTo('/plugins/business-card/client/search?type=businessList')">
			<search message="搜索他的名字吧" textColor="#999999" :borderRadius="40" frameColor='#fff' innerFrameColor='#F5F5F5'></search>
		</view>
		
		<view class="business-list-box">
			<block v-if="list.length != 0">
				<view v-for="(item,index) in list" :key='index'>
					<view class="business-list-item flex" @tap="navTo(`/plugins/business-card/index?id=${item.id}`)">
						<view class="left" :style="{background:textColor}"></view>
						<view class="right flex flex-x-between">
							<view class="userinfo">
								<view class="company-name">{{item.company_name}}</view>
								
								<view class="username">
									<view class="username-h2">{{item.full_name}}</view>
									<view>{{item.position_name}}</view>
								</view>
								
								<view class="user-phone">
									<view>{{item.mobile}}</view>
									<view>{{item.email}}</view>
								</view>
								
								<!-- <view class="level">No.1</view> -->
							</view>
							<view class="avatar flex flex-col flex-x-between flex-y-center">
								<image class="avatar-img" :src="item.head_img || img_url+'images/my/mine_def_touxiang_3x.png'" mode=""></image>
								<view class="iconfont icon-icon_gengduo more-icon"></view>
							</view>
						</view>
					</view>
					<view class="date">{{item.created_at}}</view>
				</view>
			</block>
			
			<view v-else class="nomore">暂时没有更多名片</view>
		</view>
		
		<view class="create" @tap="showPop" :style="{background:textColor}">创建我的名片</view>
		
		<com-modal :show="is_modal" padding='40rpx 40rpx' width="80%" :custom='true' @cancel='hidePop'>
			<view class="secretOrder-box">
				<view class="title">添加命令</view>
				<view class="inp-box">
					<input class="secretOrder-inp" v-model="order" placeholder="请输入添加命令" type="text" />
				</view>
				<view class="wrong" v-show="is_wrong">*输入添加命令有误。</view>
				<view class="btn-list flex">
					<view style="margin-right: 40rpx;" @tap="hidePop">取消</view>
					<view @tap="sure">确定</view>
				</view>
			</view>
		</com-modal>
		
		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	import search from '@/components/search/search.vue';
	export default {
		components:{
			search
		},
		data() {
			return {
				img_url: this.$api.img_url,
				is_wrong:false,
				is_modal:false,
				order:'',
				loading:false,
				is_no_more:false,
				page:1,
				page_count:0,
				
				list:[],
				
				is_request:false,
				keywords:'',
				textColor:'',
			};
		},
		onLoad(options) {
			this.keywords = options.keywords;
			this.getList();
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
		},
		onReachBottom(e){
			this.page++;
			if(this.page <= this.page_count){
				this.getList();
			}
		},
		methods:{
			getList(){
				this.loading = true;
				this.$http.request({
					url:this.$api.plugin.business.index,
					method:'post',
					data:{
						keywords:this.keywords,
						page:this.page
					}
				}).then(res => {
					if(res.code == 0){
						this.list.push(...res.data.list);
						this.page_count = res.data.pagination.page_count;
					}else{
						this.$http.toast(res.msg);
					}
					this.loading = false;
				})
			},
			navTo(url){
				uni.navigateTo({
					url
				})
			},
			showPop(){
				this.is_modal = true;
			},
			hidePop(){
				this.is_modal = false;
			},
			sure(){ //跳转去创建名片
				this.loading = true;
				if(this.is_request) return;
				
				this.is_request = true;
				this.$http.request({
					url:this.$api.plugin.business.edit,
					method:'post',
					data:{
						card_token:this.order,
					}
				}).then(res=>{
					this.loading = false;
					if(res.code == 1){
						this.is_wrong = true;
					}else{
						this.is_modal = false;
						this.is_request = false;
						if(res.data.id){
							setTimeout(()=>{
								this.$http.toast('你已创建了名片！');
								
								setTimeout(()=>{
									uni.navigateTo({
										url:'/plugins/business-card/index'
									})
								},1000)
							},200)
						}else{
							uni.setStorageSync('commonData',res.data);
							uni.navigateTo({
								url:'/plugins/business-card/my/edit?val=create'
							})
						}
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	page{
		background: #fafafa;
	}
	.searchBox{
		padding: 18rpx 30rpx 23rpx;
		height: auto;
	}
	
	.business-list-box{
		padding: 44rpx 30rpx;
		
		.business-list-item{
			background: #FFFFFF;
			border-radius: 20rpx;
			overflow: hidden;
		}
		.left{
			width: 14rpx;
			background: #BC0100;
		}
		.right{
			flex: 1;
			padding: 50rpx 50rpx 40rpx;
			
			.userinfo{
				padding-top: 10rpx;
				
				.company-name{
					color: #545454;
					font-size: 26rpx;
				}
				
				.username{
					padding: 50rpx 0 42rpx;
					color: #545454;
					font-size: 24rpx;
					
					.username-h2{
						font-size: 44rpx;
						color: #000000;
						font-weight: 600;
						letter-spacing: 1px;
					}
				}
				
				.user-phone{
					color: #545454;
					font-size: 24rpx;
				}
				
				.level{
					color: #BC0100;
					font-size: 28rpx;
					margin-top: 20rpx;
				}
			}
			
			.avatar{
				
				.avatar-img{
					width: 120rpx;
					height: 120rpx;
					border-radius: 8rpx;
				}
				.more-icon{
					font-size: 70rpx;
				}
			}
		}
	}
	.date{
		padding: 40rpx 16rpx;
		color: #545454;
		font-size: 24rpx;
	}
	
	.create{
		background: #BC0100;
		border-radius: 60rpx;
		position: fixed;
		bottom: 60rpx;
		color: #FFFFFF;
		width: 650rpx;
		height: 90rpx;
		line-height: 90rpx;
		text-align: center;
		left: 50%;
		transform: translateX(-50%);
		letter-spacing: 1px;
	}
	
	// 输入密令弹窗
	.secretOrder-box{
		.title{
			font-size: 40rpx;
			color: #000000;
			margin-bottom: 30rpx;
		}
		.inp-box{
			border-bottom: 1px solid #000000;
			padding-bottom: 10rpx;
			margin-bottom: 30rpx;
		}
		.secretOrder-inp{
			color: #000000;
		}
		.wrong{
			color: #bc0100;
			font-size: 24rpx;
			margin-bottom: 30rpx;
		}
		.btn-list{
			color: #BC0100;
			justify-content: flex-end;
		}
	}
	
	.nomore{
		font-size: 30rpx;
		text-align: center;
		margin-top: 100rpx;
	}
</style>
