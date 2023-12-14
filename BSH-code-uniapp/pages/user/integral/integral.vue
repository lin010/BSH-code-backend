<template>
	<view class="shopping-coupon">
		<!-- #ifdef H5 -->
		<view class="asset_types" style="width: 100%;height: 120rpx;background: #f4f4f4;position: fixed;top: 80rpx;left: 0;z-index: 999;">
		<!--#endif -->
		<!-- #ifdef MP-WEIXIN || APP-PLUS -->
		<view class="asset_types" style="width: 100%;height: 120rpx;background: #f4f4f4;position: fixed;top: 0rpx;left: 0;z-index: 999;">
		<!--#endif -->	
			<view class="asset_types_select" style="width: 220rpx;background: #fff;height: 70rpx;border-radius: 15rpx;line-height: 70rpx;padding-left: 50rpx;margin-top: 25rpx;margin-left: 20rpx;color: #000;box-sizing: border-box;"
			@click="selectasset" >
			     {{asseText}}
				<image :src="img_url+'/upstrong.png'" mode="" style="display: block;width: 36rpx;height: 36rpx;position: absolute;top: 45rpx;left: 190rpx;"></image>
			</view>
		</view>
		
		
		
		
		
		
		
		<view class="shopping-main">
			<view class="shopping-coupon-group">
				<!-- <view class="shopping-coupon-item">
					<text class="coupon-item-num">{{current_integral}}</text>
					<text class="coupon-item-title">总{{currencyAlias.integral_alias}}</text>
				</view> -->
				<view class="shopping-coupon-item">
					<text class="coupon-item-num">{{get_static_integral}}</text>
					<text class="coupon-item-title">永久{{currencyAlias.integral_alias}}券</text>
				</view>
				<view class="shopping-coupon-item">
					<text class="coupon-item-num">{{get_dynamic_integral}}</text>
					<text class="coupon-item-title">限时{{currencyAlias.integral_alias}}券</text>
				</view>
			</view>
			<view class="shopping-tools">
				<view class="shopping-tools-item" @click="toMyCard">
					<text>我的{{currencyAlias.integral_alias}}券</text>
					<i class="iconfont icon-xiala i-icon"></i>
				</view>
				<view class="shopping-tools-item" @click="toSendPlan">
					<text>{{currencyAlias.integral_alias}}券发放计划</text>
					<i class="iconfont icon-xiala i-icon"></i>
				</view>
				
				<view class="shopping-tools-item" @click="toRechargeRecord">	
					<text>{{currencyAlias.integral_alias}}券充值明细</text>
					<i class="iconfont icon-xiala i-icon"></i>
				</view>
				<view class="shopping-tools-item" @click="toScoreDetails">
					<text>{{currencyAlias.integral_alias}}劵明细</text>
					<i class="iconfont icon-xiala i-icon"></i>
				</view>
			</view>
			
			<view class="shopping-tools shopping-coupon-list">
				<view class="tui-mtop">
					<!-- <com-tabs :tabs="tabs" :currentTab="currentTab" @change="change" :selectedColor="textColor" :sliderBgColor="textColor"  itemWidth="50%" bgColor="#fff"></com-tabs> -->
					<tabs :type="tabs" v-model="currentTab" @change="change" :blockLine="true" itemColor="#FF7104" lineColor='#FF7104'></tabs>
				</view>
				<view v-if="list && list.length>0" class="coupon-list">
					<view v-for="item in list" class="coupon-list-item">
						<view class="item-time">{{item.created_at|formatDate}}</view>
						<view class="coupon-list-item-main">
							<view class="item-left" style="width: 50%;">
								<view class="item-income">收入：¥{{item.money}}</view>
								<view :class="[currentTab==0?'item-explain':'item-time']">{{item.desc}}</view>
								<view v-if="currentTab==1" class="item-expire-time">过期时间:{{item.expire_time|formatDate}}</view>
							</view>
							<view class="item-right" style="width: 40%;">
								<!-- 详情也要相加 -->
								<view class="item-money" style="text-align: left;">{{currencyAlias.integral_alias}}券：¥{{item.total_money}}</view>
								<view v-if="currentTab==1" class="item-button" @click="todetailed(item.id)">查看详情</view>
							</view>
						</view>
					</view>
				</view>
				<view class="list-empty" v-else><main-nomore text="暂无数据" :visible="true" bgcolor="#fff"></main-nomore></view>
				
			</view>
			<view class="exchange-btn" @click="toRechargeCard">{{currencyAlias.integral_alias}}券充值</view>
		</view>
		<unipopup ref="asset" type="bottom">
			<view class="popup-detail">
				<view class="popup-detail-header">
					请选择资产类型
				</view>
				<view class="popup-detail-list">
					<view :class="selectassetIndex==index?'actove':''" v-for="(item,index) in assetList" :key='index'  @click="assetlink(item,index)">
						{{item.name}}
					</view>
				</view>
			</view>
		</unipopup>
	</view>
</template>

<script>
	import unipopup from '@/components/uni-popup/uni-popup';
	export default {
		components: {
			unipopup
		},
		data() {
			return {
				img_url: this.$api.img_url,
				currentTab: 0,
				tabs: [{
					name: "永久{{currencyAlias.integral_alias}}券"
				}, {
					name: "限时{{currencyAlias.integral_alias}}券"
				}],
				
				tab_list:['固定{{currencyAlias.integral_alias}}券','动态{{currencyAlias.integral_alias}}券'],
				current_integral : 0,	//当前数值
				static_integral : 0,	//永久券数值
				dynamic_integral : 0,	//有效券数值
				list:[],
				page:1,
				is_no_more:false,	//假设有更多数据
				textColor:'',
				queryFlag : true,	//默认是可以发起请求
				userInfo: {
					balance: 0,
					score: 0,
					coupon: 0
				},
				asset:false,
				assetList:[
					{
						name:'余额',
						type:'balance'
					},
					{
						name:'{{currencyAlias.integral_alias}}',
						type:'total_score'
					},
					{
						name:'金豆',
						type:'redBag'
					},
					{
						name:'红包',
						type:'shopping_voucher'
					},
					{
						name:'收益明细',
						type:'income'
					},
				],
				asseText:'',
				selectassetIndex:0,
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
			get_current_integral(){
				let get_current_integral = this.current_integral;
				return get_current_integral;
			},
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
		
		onLoad(options){
			if(options&&options.name=='total_score'){
				this.asseText="{{currencyAlias.integral_alias}}"
				this.selectassetIndex=1
			}
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
			}
			// 初始化数据
			if (uni.getStorageSync('userInfo')) {
				this.userInfo = JSON.parse(uni.getStorageSync('userInfo'));
			}
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
				uni.setNavigationBarTitle({
					  title: this.currencyAlias.integral_alias+'明细'
					});
				this.asseText =this.currencyAlias.integral_alias;
				this.tabs= [{
					name: "永久"+this.currencyAlias.integral_alias+"券"
				}, {
					name: "限时"+this.currencyAlias.integral_alias+"券"
				}];
				this.tab_list = ['固定'+this.currencyAlias.integral_alias+'券','动态'+this.currencyAlias.integral_alias+'券'];
				this.assetList = [
					{
						name:this.currencyAlias.balance_alias,
						type:'balance'
					},
					{
						name:this.currencyAlias.integral_alias,
						type:'total_score'
					},
					{
						name:this.currencyAlias.red_envelope_alias,
						type:'redBag'
					},
					{
						name:this.currencyAlias.silver_beans_alias,
						type:'shopping_voucher'
					},
					{
						name:'收益明细',
						type:'income'
					},
				];
			}
			
			this.getList();	//获取列表数据
		},
		onReachBottom:function(e){
			if(this.queryFlag && (!this.is_no_more)){	//上一次请求完，且有更多数据
				this.getList();
			}
		},
		methods:{
			selectasset(){
				this.$refs.asset.open()
			},
			assetlink(item,index){
				this.$refs.asset.close()
				if(item.type=='balance'){
					uni.navigateTo({
						url:'../balance/details?name='+item.type
					})
				}
				if(item.type=='total_score'){
					return
				}
				if(item.type=='redBag'){
					uni.navigateTo({
						url:'../../../mch/redBag/redBag?name='+item.type
					})
				}
				if(item.type=='shopping_voucher'){
					uni.navigateTo({
						url:'../../../mch/vouchers/vouchers?name='+item.type
					})
				}
				if(item.type=='income'){
					uni.navigateTo({
						url:'../../../plugins/extensions/income/income?name='+item.type
					})
				}
			},
			toMyCard(){
				uni.navigateTo({
					url:'/pages/user/integral/myCard'
				})
			},
			// 0.1 跳转分发计划页面
			toSendPlan(){
				uni.navigateTo({
					url:'/pages/user/integral/sendPlan'
				})
			},
			// 跳转充值{{currencyAlias.integral_alias}}明细
			toRechargeRecord(){
				uni.navigateTo({
					url:'/pages/user/integral/rechargeRecord'
				})
			},
			// 跳转其他{{currencyAlias.integral_alias}}明细
			toScoreDetails(){
				uni.navigateTo({
					url:'/pages/user/score/details'
				})
			},
			// 0.2 跳转充值卡
			toRechargeCard(){
				uni.navigateTo({
					url:'/pages/user/integral/rechargeCard'
				})
			},
			
			// tab的点击事件
			change(e) {
				if(!this.queryFlag){	//上一个请求完成的时候才可以执行
					return
				}
				console.log(e)
				this.currentTab = e;
				// 初始化数据
				this.list = [];
				this.page = 1;
				this.is_no_more = false;
				this.getList();
			},
			
			// 0.2 点击跳转明细
			todetailed(id){
				// console.log(id);
				uni.navigateTo({
				    url: './detailed?id='+id
				});
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
					url:this.$api.user.integral_center,
					method: 'POST',
					data:{
						page:this.page,
						type:type,
						controller_type:0
					}
				}).then((res)=>{
					console.log(res);
					uni.hideLoading();
					this.queryFlag = true;	//可以再次发起请求了
					if(res.code == 0){
						let resList = res.data.integral_list.list;
						this.list = this.list.concat(resList);
						this.current_integral = res.data.wallet.score;	//总积分
						this.static_integral = res.data.wallet.static_score;	//永久券额
						this.dynamic_integral = res.data.wallet.dynamic_score;	//限时券额
						// 判断 是否有整10条数据
						if(resList.length==10){
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
		padding-bottom: 20rpx;
		background: #F7F7F7;
		.shopping-main{
			box-sizing: border-box;
			width: 690rpx;
			margin: 120rpx 30rpx 0 30rpx;
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
						color:#FF7104;
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
					border-bottom: 1rpx solid #f7f7f7;
					.iconfont{
						font-size: 16pt;
					}
				}
				.shopping-tools-item:last-child{
					border-bottom: 0;
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
						.item-time{
							font-size: 22rpx;
							font-family: Source Han Sans CN;
							font-weight: 400;
							color: #999999;
							line-height: 37rpx;
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
							.item-right{
								display: flex;
								flex-direction: column;
								// align-items: center;
								.item-button{
									width: 145rpx;
									height: 40rpx;
									text-align: center;
									background:rgb(10, 217, 206);
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
		background:#FF7104;
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
	.popup-detail{width: 100%;background: #fff;min-height: 400rpx;border-radius: 0 0 20rpx 20rpx;padding-bottom: 30rpx;}
	.popup-detail-header{width: 100%;height: 80rpx;line-height: 80rpx;padding: 0 20rpx;font-size: 30rpx;color: #000;font-weight: bold;}
	.popup-detail-list{width: 100%;overflow: hidden;margin-bottom: 30rpx;}
	.popup-detail-list view{min-width: 180rpx;height: 70rpx;line-height: 70rpx;text-align: center;
	float: left;margin: 20rpx 0rpx 0 40rpx;border-radius: 10rpx;font-size: 26rpx;font-weight: bold;}
	.actove{background: rgb(222,59,45);color: #fff;}
</style>
