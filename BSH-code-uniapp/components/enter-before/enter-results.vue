<template>
	<view class="results-app">
		<view class="message" v-if="style=='verifying'">
			<image :src="img_url+'/review.png'" mode=""></image>
			<text>管理人员正在审核中...</text>
			<text>24小时内通过审核,请耐心等候</text>
			<button type="default" @click="checkINto(1)">去逛逛</button>
		</view>
		<view class="message" v-if="style=='refused'">
			<image :src="img_url+'/Not-through.png'" mode=""></image>
			<text>申请已拒绝</text>
			<text style="width: 70%;margin: 10rpx auto;color: red;">原因:{{message}}</text>
			<button type="default"  @click="checkINto(2)">去修改资料</button>
		</view>
		<view class="message" v-if="style=='passed'">
			<image :src="img_url+'/through.png'" mode=""></image>
			<text>恭喜你</text>
			<text>申请已通过</text>
			<button type="default"  @click="checkINto(3)">确定</button>
		</view>
	</view>
</template>

<script>
	export default {
		props:{
			type:{
				type:String,  
				required:true
			},
			msg:{
				type:String,
				required:true
			}
		},
		data() {
			return {
				img_url: this.$api.img_url,
				//style:'',  //申请状态（refused拒绝，passed通过，verifying审核中，applying申请中）
				//message:'',//原因
			};
		},
		computed: {
		    style: function () {
				return this.type;
		    },
			message: function(){
				return this.msg;
			}
	    },
		methods:{
			checkINto(index){
				if(index==1){
					uni.navigateTo({
						url:'../../pages/index/index'
					})
				}
				if(index==2){
					let that=this
					that.$http.request({
						 url:that.$api.merchants.applyreset,
						 data:{},
						 method:'post',
						 }).
						then(function(res){
							if(res.code==0){
								uni.redirectTo({
									url:'/mch/enter/enter'
								})
								
							}
					})
				}
				if(index==3){
					uni.navigateTo({
						url:'../../mch/personalCentre/personalCentre'
					})
				}
			}
		}
	}
</script>

<style lang="less">
	.results-app{width: 100%;overflow: hidden;}
	.message{width: 100%;height: 550rpx;position: absolute;top: 0;right: 0;left: 0;bottom: 0;margin: auto;}
	.message image{width: 200rpx;height: 200rpx;display: block;margin: 50rpx auto 20rpx;}
	.message text{display: block;width: 100%;overflow: hidden;text-align: center;font-size: 28rpx;color: #000;margin: 5rpx 0;}
	.message button{width: 280rpx;height: 60rpx;line-height: 60rpx;font-size: 28rpx;margin: 40rpx auto;background:rgb(255, 113, 4) ;color: #fff;}
</style>
