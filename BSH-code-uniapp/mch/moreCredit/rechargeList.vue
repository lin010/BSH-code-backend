<template>
	<view class="recharge_list">
		<view class="header">
			<view class="pick-time">
				<picker mode="date" :value="date"  @change="bindDateChange" fields='day'>
				    <view class="uni-input" style="text-align: center;">{{date}}</view>
				 </picker>
				 <image :src="img_url+'/upstrong.png'" mode=""></image>
			</view>
			<view class="recharge_list-title">
				<text>手机号</text>
				<text>充值金额</text>
				<text>充值时间</text>
				<text>充值状态</text>
			</view>
		</view>
		<view class="recharge-list">
			<view class="recharge-item" v-for="(item,index) in creditStatusList" :key='index'>
				<text style="color: #000;">{{item.mobile}}</text>
				<text>{{item.order_price}}</text>
				<text>{{item.created_at}}</text>
			<!-- 	<text v-if="item.pay_status=='paid'&&item.order_status=='success'" style="color: green;">充值成功</text>
				<text v-if="item.pay_status=='paid'&&item.order_status=='processing'" style="color: red;">充值中...</text>
				<text v-if="item.pay_status=='paid'&&item.order_status=='fail'" style="color: gray;">充值失败</text>
				<text v-if="item.pay_status=='unpaid'" style="color: gray;">未支付</text>			
				<text v-if="item.pay_status=='refunding'" style="color: red;">退款中...</text>
				<text v-if="item.pay_status=='refund'" style="color: gray;">已退款</text> -->
				<text v-if="item.pay_status=='unpaid'" style="color: gray;">未支付</text>
				<text v-else style="color: green;">充值成功</text>
			</view>
		</view>
		<!--加载loadding-->
		<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="!pullUpOn" bgcolor="#FFFFFF"></main-nomore>
		<!--加载loadding-->
	</view>	
</template>

<script>
	export default {
		data() {
			return{
				img_url: this.$api.img_url,
				creditStatusList:[],
				page_count:'',
				page:1,
				date:'全部',
				recharge_time:'',
				pullUpOn:true,
				loadding:false,
			}
		},
		onLoad() {
			this.rechagelist()
		},
		methods:{
			rechagelist(){ //充值记录
				this.$http.request({
					url: this.$api.morecredit.creditStatus,
					method: 'get',
					data: {
						plateforms_id:1,
						page:this.page,
						recharge_time:this.recharge_time,
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						if(res.data.length==0)return false
						let list= res.data.list;
						var arr=this.creditStatusList.concat(list)
						this.creditStatusList =arr
						this.page_count= res.data.pagination.page_count;
						this.pullUpOn=true
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			bindDateChange: function(e) { //点击选择年月
				let time=e.target.value	
				this.recharge_time=time
			    this.date = time.split('-')[0]+'年'+time.split('-')[1]+'月'+time.split('-')[2]+'日'
				this.creditStatusList=[];
				this.page_count='';
				this.page=1;
				this.rechagelist()
			},	
		},
		onReachBottom() {
			this.pullUpOn=true
			this.loadding=true
			if(this.page==this.page_count){
				this.pullUpOn=false
				this.loadding=false
				return false;
			} 		
			this.page=this.page+1
		}
	}
</script>

<style lang="less" scoped>
	.recharge_list{
		width: 100%;
		overflow: hidden;
		margin: 20rpx 0 60rpx 0;
	}
	.header{
		width: 100%;
		overflow: hidden;
		position: fixed;
	/* #ifdef H5 */		
		top: 88rpx;
	/* #endif */
	/* #ifdef MP  */
		top: 0rpx;
	/* #endif */	
		left: 0;
	}
	.pick-time{
		width: 300rpx;
		height: 80rpx;
		line-height: 80rpx;
		padding:0 20rpx;
		box-sizing: border-box;
	}
	.pick-time image{display: block;width: 36rpx;height: 36rpx;position: absolute;top: 20rpx;left: 280rpx;}
	.recharge_list-title{
		display: flex;
		justify-content: space-evenly;
		font-size: 28rpx;
		margin: 10rpx 0;
	}
	.recharge_list-title text{
		display: block;
		width: 25%;
		text-align: center;
		color: #ff7104;
	}
	.recharge-list{
		width: 100%;
		overflow: hidden;
		margin-top: 100rpx;
	}
	.recharge-item{
		display: flex;
		justify-content: space-evenly;
		margin: 35rpx 0;
	}
	.recharge-item text{
		display: block;
		font-size: 25rpx;
		width: 25%;
		text-align: center;
	}
</style>
