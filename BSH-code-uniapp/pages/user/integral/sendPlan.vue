<template>
	<view class="shopping-coupon">
		<view v-if="list && list.length>0" class="shopping-main">
			<view v-for="item in list" class="shopping-coupon-group">
				<view class="top-left">{{item.type==1?'永久':'有效'}}</view>
				<block v-for="(its,index) in get_status_list">
					<view v-if="index==item.status" class="top-right">{{its}}</view>
				</block>
				<view class="coupon-item-left">
					<view class="item-money">¥<text>{{item.integral_num|parseInt}}</text>.00</view>
					<view class="item-name">{{currencyAlias.integral_alias}}劵</view>
				</view>
				<view class="coupon-item-right">
					<view class="item-title">{{item.desc}}</view>
					<view class="item-plan">
						发放计划：送{{item.period}}{{item.period_unit=='month'?'月':'周'}}，每{{item.period_unit=='month'?'个月':'周'}}送{{item.integral_num}}{{currencyAlias.integral_alias}}，
						<!-- 永久无需渲染有效期 -->
						<text v-if="item.type!=1">有效期{{item.effective_days}}天</text>
						已发放：{{item.finish_period}}{{item.period_unit=='month'?'个月':'周'}}   {{alreadyIntegral(item.finish_period,item.integral_num)}}{{currencyAlias.integral_alias}} 
					</view>
				</view>
			</view>
		</view>
		<view class="items" v-else><main-nomore text="暂无数据" :visible="true" bgcolor="transparent"></main-nomore></view>
		
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				list:[],
				status_list : [],	//发放状态
				type_list : [],		//红包类型
				page:1,
				is_no_more:false,	//假设有更多数据
				queryFlag : true,	//默认是可以发起请求
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		// 计算发送状态与券类型
		computed:{
			get_status_list () {
			    let get_status_list = this.status_list;
			    return get_status_list;
			},
			get_type_list () {
			    let get_type_list = this.type_list;
			    return get_type_list;
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
		  },
		  // 取整
		  parseInt: function (value) {
			return parseInt(value);
		  },
		},
		
		onLoad(){
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			this.getList();
		},
		onReachBottom:function(e){
			if(this.queryFlag && (!this.is_no_more)){	//上一次请求完，且有更多数据
				this.getList();
			}
		},
		
		methods:{
			//计算累计发放积分 
			alreadyIntegral(e,f){
				return (parseFloat(e)*parseFloat(f)).toFixed(2);
			},
			// 0.1 获取列表数据
			getList(){
				if(this.is_no_more){
					uni.showToast({
						title:'暂无更多数据'
					})
					return;
				}
				this.queryFlag = false;	
				uni.showLoading({
					title:'加载中'
				})
				this.$http.request({
					url:this.$api.user.integral_plan,	//红包发放计划
					method: 'POST',
					data:{
						page:this.page,
					}
				}).then((res)=>{
					console.log(res);
					uni.hideLoading();
					this.queryFlag = true;	//可以再次发起请求了
					if(res.code == 0){
						this.status_list = res.data.status_list;
						this.type_list = res.data.type_list;
						let resList = res.data.plan.list;
						this.list = this.list.concat(resList);
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
			margin: 0 30rpx;
			.shopping-coupon-group{
				position: relative;
				width: 100%;
				margin-top: 20rpx;
				box-sizing: border-box;
				padding: 0 26rpx;
				display: flex;
				justify-content:space-around;
				height: 160rpx;
				background: #FFFFFF;
				border-radius: 10rpx;
				display: flex;
				align-items: center;
				justify-content: space-between;
				.coupon-item-left{
					color: #BC0100;
					display: flex;
					flex-direction: column;
					align-items: center;
					padding-top: 20rpx;
					.item-money{
						font-size: 38rpx;
						font-family: Source Han Sans CN;
						font-weight: bold;
						color: #BC0100;
						line-height: 38rpx;
						display: flex;
						align-items: flex-end;
						text{
							font-size: 54rpx;
							line-height: 54rpx;
						}
					}
				}
				.coupon-item-right{
					margin-left: 20rpx;
					.item-title{
						font-size: 32rpx;
						font-family: Source Han Sans CN;
						font-weight: 400;
						color: #000000;
						line-height: 58rpx;
					}
					.item-plan{
						font-size: 18rpx;
						font-family: Source Han Sans CN;
						font-weight: 400;
						color: #999999;
						line-height: 30rpx;
					}
				}
				.top-left, .top-right{
					position: absolute;
					top: 0;
					font-size: 22rpx;
					font-family: Source Han Sans CN;
					font-weight: 400;
					color: #FFFFFF;
					line-height: 30rpx;
					height: 30rpx;
					padding:  0 15rpx;
					background: #BC0200;
				}
				.top-left{
					left: 0;
					border-radius: 10rpx 0 10rpx 0;
				}
				.top-right{
					right: 0;
					border-radius: 0 10rpx 0 10rpx;
				}
				
			}
			
		}
	}
	
	
	
	.nothing{
		padding-top: 200rpx;
		text-align: center;
		letter-spacing: 1px;
		
	}
</style>
