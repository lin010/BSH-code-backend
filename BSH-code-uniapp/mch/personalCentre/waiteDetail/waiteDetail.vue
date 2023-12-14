<template>
	<view class="watite-app">
		<view class="select-type">
			<text v-for="(item,index) in array" :key='index'  :class="selectIndex==index?'active':''" @click="select(index)">{{item}}</text>
		</view>
		<view class="watitecount-list">
			<view class="item" v-for="(item,index) in list" :key='index'>
				<view class="item-left">
					{{item.content}}
				</view>
				<view class="item-right">
					<text style="color: #FF7104;font-size: 30rpx;font-weight: bold;">￥{{item.price}}</text>
					<text>{{item.created_at}}</text>
				</view>
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
			return {
				array:['待结算','已结算','已取消'],
				selectIndex:0,
				form:{
					page:1,
					status:'unconfirmed',//状态：canceled已取消，success已结算，unconfirmed待结算
				},
				list:[],
				page_count:'',
				pullUpOn:true,
				loadding:false,
				flag:false
			};
		},
		onLoad() {
			this.getList()
		},
		methods:{
			select(index){
				if(this.flag)return
				this.list=[]
				this.form={
					page:1,
					status:'unconfirmed',//状态：canceled已取消，success已结算，unconfirmed待结算
				},
				this.selectIndex=index
				this.pullUpOn=true
				this.loadding=false
				if(index==0){this.form.status='unconfirmed'}
				if(index==1){this.form.status='success'}
				if(index==2){this.form.status='canceled'}
				this.page_count=''
				this.getList()
			},
			getList(){
				if(this.flag)return 
				this.flag=true
				this.$http
					.request({
						url: this.$api.moreShop.getcountList,
						method: 'POST',
						data:this.form,
					})
					.then(res => {
						if(res.code==0){
							this.flag=false
							if(res.data.list.length==0){
								this.pullUpOn=false
								return false
							}
							let list= res.data.list;
							var arr=this.list.concat(list)
							this.list =arr
							this.page_count = res.data.pagination.page_count;
							this.pullUpOn=true
						}else{
							this.$http.toast(res.msg);
						}
					});	
			}
		},
		onReachBottom() {
			this.pullUpOn=true
			this.loadding=true
			if(this.form.page==this.page_count){
				this.pullUpOn=false
				this.loadding=false
				return false;
			}
			this.form.page=this.form.page+1
			this.getList()
		},
	}
</script>

<style lang="less">
	.watite-app{width: 100%;overflow: hidden;}
	.select-type{width: 100%;display: flex;justify-content: space-evenly;margin: 20rpx 0;}
	.select-type text{width:25% ;display: block;height: 70rpx;text-align: center;line-height: 70rpx;}
	.active{background: #FF7104;color: #fff;}
	.watitecount-list{width: 100%;overflow: hidden;}
	.item{width: 100%;overflow: hidden;display: flex;justify-content: space-between;margin: 40rpx 0;border-bottom: 1rpx solid #C4C4C4;}
	.item-left{width: 60%;font-size: 26rpx;margin-bottom: 10rpx;}
	.item-right{width: 35%;font-size: 28rpx;display: flex;flex-wrap: wrap;margin-bottom: 10rpx;}
	.item-right text{display: block;width: 100%;text-align: center;}
	.item-right text:nth-of-type(2){font-size: 25rpx;}
</style>
