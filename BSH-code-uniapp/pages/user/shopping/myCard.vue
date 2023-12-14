<template>
	<view class="shopping-coupon">
		<view class="shopping-main">
			<view class="shopping-tools shopping-coupon-list">
				<view class="tui-mtop">
					<tabs :type="tabs" v-model="currentTab" @change="change" :blockLine="true" itemColor="#FF7104" lineColor="#FF7104"></tabs>
				</view>
				<view class="tui-mtop">
					<tabs :type="tabsStatus" v-model="currentStatus" @change="changeStatus" :blockLine="true" itemColor="#FF7104" lineColor="#FF7104" :tabLine="false"></tabs>
				</view>
				<view v-if="list && list.length>0" class="coupon-list">
					<view v-for="item in list" class="coupon-list-item">
						<view class="item-nickname" v-if="item.picker">领取人：{{item.picker.nickname}}</view>
						<view class="coupon-list-item-main">
							<view class="item-left">
								<view class="item-income">面值：￥{{item.integral_setting.integral_num}} <text class="gray">（发放：{{item.integral_setting.period}}/{{item.integral_setting.period_unit=='month'?'月':'周'}}）</text></view>
								<view class="item-no">卡号：{{item.serialize_no}}</view>
								<view v-if="currentTab==1 && item.status!=1" class="item-time">过期时间:{{item.expire_time|formatDate}}</view>
								<view v-if="item.status==1" class="item-time">领取时间:{{item.updated_at|formatDate}}</view>
							</view>
							<view class="item-right">
								<view class="item-status" v-if="item.status == 0">未充值</view>
								<view class="item-status green" v-if="item.status == 1">已充值</view>
								<view class="item-status red" v-if="item.status == 2">已过期</view>
								<view class="item-status red" v-if="item.status == -1">禁用</view>
								<!-- <view class="item-button" v-clipboard:copy="'卡号：'+item.serialize_no+',卡密：'+item.use_code" v-clipboard:success="(type) => paste('success')" v-clipboard:error="(type) => paste('error')">复制兑换码</view> -->
							</view>
						</view>
					</view>
				</view>
				<view class="list-empty" v-else><main-nomore text="暂无数据" :visible="true" bgcolor="#fff"></main-nomore></view>
				
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				currentStatus:0,
				currentTab: 0,
				tabs: [{
					name: "永久金豆"
				}, {
					name: "有效金豆"
				}],
				tabsStatus: [{
					name: "全部状态"
				},{
					name: "未充值"
				}, {
					name: "已充值"
				}, {
					name: "已过期"
				}],
				tab_list:['固定金豆','动态购金豆'],
				static_integral : 0,	//永久券数值
				dynamic_integral : 0,	//有效券数值
				list:[],
				page:1,
				is_no_more:false,	//假设有更多数据
				textColor:'',
				queryFlag : true,	//默认是可以发起请求
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		// 加一个卷值的计算属性
		computed:{
			get_static_integral () {
			    let get_static_integral = this.static_integral;
			    return get_static_integral;
			},
			get_dynamic_integral () {
			    let get_dynamic_integral = this.dynamic_integral;
			    return get_dynamic_integral;
			},
		},
		
		filters: {
		  // 秒级时间戳转标准时间格式
		  formatDate: function (value) {
			let date = new Date(value*1000);
			let y = date.getFullYear();
			let MM = date.getMonth() + 1;
			MM = MM < 10 ? ('0' + MM) : MM;
			let d = date.getDate();
			d = d < 10 ? ('0' + d) : d;
			let h = date.getHours();
			h = h < 10 ? ('0' + h) : h;
			let m = date.getMinutes();
			m = m < 10 ? ('0' + m) : m;
			let s = date.getSeconds();
			s = s < 10 ? ('0' + s) : s;
			return y + '-' + MM + '-' + d + ' ' + h + ':' + m + ':' + s;
		  }
		},
		
		onLoad(){
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
				uni.setNavigationBarTitle({
				  title: "我的"+this.currencyAlias.red_envelope_alias
				});
				this.tabs= [{
					name: "永久"+this.currencyAlias.red_envelope_alias
				}, {
					name: "有效"+this.currencyAlias.red_envelope_alias
				}];
				this.tab_list = ['固定'+this.currencyAlias.red_envelope_alias,'动态购'+this.currencyAlias.red_envelope_alias];
			}
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
			}
			this.getList();	//获取列表数据
		},
		onReachBottom:function(e){
			if(this.queryFlag && (!this.is_no_more)){	//上一次请求完，且有更多数据
				this.getList();
			}
		},
		methods:{
			changeStatus(e) {
				if(!this.queryFlag){	//上一个请求完成的时候才可以执行
					return
				}

				this.currentStatus = e;
				//console.log(e)
				// 初始化数据
				this.list = [];
				this.page = 1;
				this.is_no_more = false;
				this.getList();
			},
			// tab的点击事件
			change(e) {
				if(!this.queryFlag){	//上一个请求完成的时候才可以执行
					return
				}
				this.currentTab = e;
				this.currentStatus = 0;
				// 初始化数据
				this.list = [];
				this.page = 1;
				this.is_no_more = false;
				this.getList();
			},
			paste(type) {
				if (type==='success') {
					uni.showToast({ title:'复制成功' })
				} else {
					uni.showToast({ title:'复制失败' })
				}
			},
			// 封装请求数据--获取列表数据
			getList(){
				if(this.is_no_more){
					uni.showToast({
						title:'暂无更多数据'
					})
					return;
				}
				this.queryFlag = false;	//不能重复发起请求了
				uni.showLoading({
					title:'加载中'
				})
				let type = this.currentTab+1;
				
				this.$http.request({
					url:this.$api.user.my_shopping_card,
					method: 'POST',
					data:{
						page:this.page,
						type:this.currentTab,
						status:this.currentStatus
					}
				}).then((res)=>{
					//console.log(res);
					uni.hideLoading();
					this.queryFlag = true;	//可以再次发起请求了
					if(res.code == 0){
						let resList = res.data.list;
						let pageSize = res.data.page_size;
						let page = res.data.page;
						this.list = this.list.concat(resList);
						// 判断 是否有整10条数据
						if(resList.length==10 && ((res.data.page_size*res.data.page) < res.data.count)){
						  this.page++;
						}else{
							this.is_no_more=true;
						}
					}else{
						uni.showToast({
							title:res.msg
						})
					}
				})
			},
			
		}
	}
</script>

<style lang="scss" scoped>
	.shopping-coupon{
		width: 750rpx;
		background: #F7F7F7;
		.shopping-main{
			box-sizing: border-box;
			width: 690rpx;
			margin: 0 30rpx;
			.shopping-coupon-group{
				width: 100%;
				margin-top: 20rpx;
				box-sizing: border-box;
				display: flex;
				justify-content:space-around;
				height: 160rpx;
				background: #FFFFFF;
				border-radius: 10rpx;
				.shopping-coupon-item{
					display: flex;
					flex-direction: column;
					align-items: center;
					justify-content:center;
					.coupon-item-num{
						font-size: 46rpx;
						font-family: Source Han Sans CN;
						font-weight: bold;
						color:rgb(10, 217, 206);
						line-height: 37rpx;
					}
					.coupon-item-title{
						font-size: 30rpx;
						font-family: Source Han Sans CN;
						font-weight: 400;
						color: #333333;
						line-height: 37rpx;
						margin-top: 20rpx;
					}
				}
			}
			.shopping-tools{
				box-sizing: border-box;
				margin-top: 20rpx;
				width: 690rpx;
				// height: 160rpx;
				background: #FFFFFF;
				border-radius: 10rpx;
				.shopping-tools-item{
					box-sizing: border-box;
					height: 80rpx;
					padding: 26rpx;
					display: flex;
					align-items: center;
					justify-content: space-between;
					.iconfont{
						font-size: 16pt;
					}
				}
				.shopping-tools-item:first-child{
					border-bottom: 1rpx solid #f7f7f7;
				}
				.list-empty{
					overflow: hidden;
				}
				
			}
			.shopping-coupon-list{
				margin-bottom: 90rpx;
				.coupon-list{
					.coupon-list-item{
						box-sizing: border-box;
						width: 690rpx;
						padding: 20rpx 26rpx;
						border-bottom:1rpx solid #f7f7f7;
						.item-nickname{
							color: #0ad9ce;
						}
						.item-time{
							font-size: 22rpx;
							font-family: Source Han Sans CN;
							font-weight: 400;
							color: #999999;
							line-height: 37rpx;
						}
						.item-no{
							font-size: 22rpx;
							color: red;
						}
						.coupon-list-item-main{
							display: flex;
							align-items: flex-start;
							justify-content: space-between;
							
							font-size: 28rpx;
							font-family: Source Han Sans CN;
							font-weight: 400;
							color: #333333;
							line-height: 60rpx;
							.item-expire-time{
								font-size: 18rpx;
								font-family: Source Han Sans CN;
								font-weight: 400;
								color: #999999;
								line-height: 36rpx;
							}
							.item-left{
								.item-income{
									.gray{
										color:#888888;
									}
								}
							}
							.item-right{
								display: flex;
								flex-direction: column;
								align-items: center;
								.item-status{
									font-size: 22rpx;
									color:#999;
									&.red{
										color:red;
									}
									&.green{
										color:green;
									}
								}
								.item-button{
									padding:0 8px;
									height: 40rpx;
									text-align: center;
									background: rgb(10, 217, 206);
									border-radius: 20rpx;
									font-size: 22rpx;
									font-family: Source Han Sans CN;
									font-weight: 400;
									color: #FFFFFF;
									line-height: 40rpx;
								}
							}
						}
					}
				}
			}
			
			
		}
	}
	
	.exchange-btn{
		position: fixed;
		z-index: 10;
		bottom: 0;
		left: 0;
		width: 750rpx;
		height: 90rpx;
		background: rgb(10, 217, 206);
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFFFFF;
		line-height: 90rpx;
		text-align: center;
		letter-spacing: 2rpx;
	}
	
	
	
	
	.tab{
		background: #FFFFFF;
		
		.tab-item{
			width: 50%;
			text-align: center;
			font-size: 32rpx;
			color: #000000;
			border-top: 1px solid #F3F3F3;
			padding: 28rpx 0;
			letter-spacing: 2rpx;
		}
		.border{
			border-right: 1px solid #F3F3F3;
		}
		.cut{
			background:rgb(10, 217, 206);
			color: #FFFFFF;
		}
	}
	
	.detail-box{
		padding: 0 30rpx;
		
		.detail-item-box{
			background: #FFFFFF;
			margin-top: 20rpx;
			border-radius: 10rpx;
			padding: 30rpx 20rpx;
			
			.time{
				border-bottom: 1px solid #F3F3F3;
				padding-bottom: 16rpx;
			}
			.price{
				padding: 16rpx 0;
				border-bottom: 1px solid #F3F3F3;
			}
			.explanation{
				padding: 16rpx 0 0;
			}
		}
	}
	
	.nothing{
		padding-top: 200rpx;
		text-align: center;
		letter-spacing: 1px;
		
	}
</style>