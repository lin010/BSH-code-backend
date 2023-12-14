<template>
	<view class="oilrecord-container">
		<!-- <view class="oilrecord_type">
			<view v-for="(item,index) in type" :key='index' style="width: 25%;text-align: center;" @click="selectType(index)">
				<text style="display: inline-block;width: 60%;height: 80rpx;margin-top: 10rpx;line-height: 80rpx;" :class="selectIndex==index?'active':''">{{item}}</text>
			</view>
		</view> -->
		<view class="oilrecord_list">
			<view class="oilrecord_item" v-for="(item,index) in list" :key='index'>
				<view style="display: flex;justify-content: space-evenly;flex-wrap: wrap;width: 70%;">
					<text style="display: block;width: 100%;font-size: 30rpx;">{{item.order_price}}加油券</text>
					<text style="display: block;width: 100%;font-size: 26rpx;margin: 10rpx 0;color: red;">{{item.pay_price}}现金+{{item.integral_deduction_price}}{{currencyAlias.red_envelope_alias}}</text>
					<text style="display: block;width: 100%;font-size: 26rpx;">{{item.created_at}}</text>
				</view>
				<view style="width: 30%;text-align: right;line-height: 140rpx;">
					<text v-if="item.order_status=='unconfirmed'" style="display: inline-block;width: 120rpx;height: 60rpx;background: rgb(255, 113, 4);line-height: 60rpx;text-align: center;font-size: 30rpx;color: #fff;border-radius: 15rpx;" @click="linkTO(item.id)">查看</text>
					<text v-if="item.order_status=='wait'" style="display: inline-block;width: 120rpx;height: 60rpx;background: rgb(255, 113, 4);line-height: 60rpx;text-align: center;font-size: 30rpx;color: #fff;border-radius: 15rpx;"@click="linkTO(item.id)">查看</text>
					<text v-if="item.order_status=='finished'" style="display: inline-block;width: 120rpx;height: 60rpx;background: rgb(255, 113, 4);line-height: 60rpx;text-align: center;font-size: 30rpx;color: #fff;border-radius: 15rpx;" @click="linkTO(item.id)">查看</text>				
					<text v-if="item.order_status=='fail'" style="color:  #CC000;">{{item.status_text}}</text>
					<text v-if="item.order_status=='refund'" style="color:  #CC000;">{{item.status_text}}</text>
					<text v-if="item.order_status=='refunding'" style="color:  #CC000;">{{item.status_text}}</text>
					
					
					
					<text 
					v-if="item.order_status!='unconfirmed'&&item.order_status!='wait'&&item.order_status!='fail'&&item.order_status!='refund'&&item.order_status!='refunding'&&item.order_status!='finished'">
					{{item.status_text}}
					</text>
				</view>
			</view>
		</view>
		<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="!pullUpOn" bgcolor="#fafafa"></main-nomore>
		
	</view>
</template>

<script>
	export default{
		data(){
			return{
				// type:["全部","办理中","已完成"],
				selectIndex:0,
				form:{
					page:1,
				},
				page_count:0,//总页数
				pullUpOn:true,
				loadding:false,
				list:[],
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			}
		},
		onLoad() {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			this.getProductpreview()
		},
		methods:{
			selectType(index){ //table选择状态
				this.selectIndex=index
			},
			getProductpreview(){//获取记录列表0
				this.$http.request({
					url: this.$api.oil.getoilOrderlist,
					method: 'POST',
					data:this.form,
				}).then(res => {
					if (res.code == 0) {
						if(res.data.list.length==0){
							this.pullUpOn = false;
							return false
						}
						let list= res.data.list;
						var arr=this.list.concat(list)
						this.list =arr
						this.page_count = res.data.pagination.page_count;
						this.pullUpOn = true;
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			linkTO(id){
				uni.navigateTo({
					url:'../ercode/ercode?id='+id
				})
			}
		},
		onReachBottom() {
			this.pullUpOn=true
			this.loadding=true
			if(this.form.page==this.page_count){
				this.pullUpOn = false;
				this.loadding=false
				return false;
			} 		
			this.form.page=this.form.page+1
			this.getProductpreview();
		},
	}
</script>

<style lang="less" scoped>
	.oilrecord-container{width: 100%;overflow: hidden;}
	.oilrecord_type{width: 100%;height: 100rpx;background: #fff;display: flex;justify-content: space-evenly;}
	.active{color:rgb(37,130,234) ;border-bottom: 4rpx solid rgb(37,130,234);}
	.oilrecord_list{width: 100%;overflow: hidden;}
	.oilrecord_item{width: 100%;overflow: hidden;margin-bottom: 10rpx;display: flex;justify-content: space-evenly;padding: 30rpx ;background: #fff;box-sizing: border-box;}
</style>
