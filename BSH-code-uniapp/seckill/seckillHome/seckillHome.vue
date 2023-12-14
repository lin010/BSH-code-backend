<template>
	<view class="seckillHome-container">
		
		<template v-if="!is_seckill">
			<view style="padding:30px 0px;text-align:center;color:gray;">暂无活动</view>
		</template>
		<template v-else>
			<!--
			<view class="down" v-if="show">
				<text style="display: block;width: 100%;height: 120rpx;color: #fff;font-size: 40rpx;text-align: center;position: absolute;
				top: 0;left: 0;right: 0;bottom: 0;margin: auto;font-weight: bold;">秒杀活动已结束,敬待下次开启</text>
			</view>
			-->
			
			<view class="seckillHome_header">
				<image :src="pic_url" mode="widthFix" style="width: 100%;display: block;"></image>
			</view>
			<view class="main">
				<view class="main_top">
					<view style="width: 45%;display: flex;justify-content: space-evenly;margin-left: 50rpx;">
						<view style="width: 37%;text-align: center;">
							<image :src="plugins_img_url+'/sss.jpg'" mode="widthFix" style="width: 120rpx;display: block;height: 116rpx;margin-top: 30rpx;"></image>
						</view>
						<view style="width: 45%" v-if="status==1">
							<view  style="width: 100%;display: block;text-align: center;margin-top: 45rpx;font-size: 30rpx;color: #ff4f11">热卖中</view>
							<view  style="width: 100%;display: block;text-align: center;font-size: 30rpx;">不能错过</view>
						</view>
						<view style="width: 45%" v-if="status==0">
							<view  style="width: 100%;display: block;text-align: center;margin-top: 45rpx;font-size: 30rpx;color:#333;">未开始</view>
							<view  style="width: 100%;display: block;text-align: center;font-size: 30rpx;">敬请期待</view>
						</view>
						<view style="width: 45%" v-if="status==2"> 
							<view  style="width: 100%;display: block;text-align: center;margin-top: 58rpx;font-size: 30rpx;color:gray;">已结束</view>
						</view>
					</view>
					
					<view  v-if="status==2 || status==1" style="margin-top:45rpx;width: 58%;font-size: 30rpx;color: #000;">
						<view>{{start_time}}开始</view>
						<view>{{end_time}}结束</view>
					</view>
					<view  v-if="status==0" style="margin-top:45rpx;width: 58%;font-size: 30rpx;color: #000;">
						<view>{{start_time}}开始</view>
					</view>
				</view>
				<view class="list">
					<view class="item" v-for="(item,index) in list" :key='index'>
						<view class="logo" style="width: 210rpx;height: 210rpx;margin-top: 10rpx;position: relative;">
							<image :src="item.cover_pic" mode=""></image>
							<image :src="plugins_img_url+'/qiangg.png'" mode=""  v-if="item.count==1"
							style="position: absolute;top: 0;left: 0;"></image>
						</view>
						<view class="item_right">
							<view class="item_name">
								{{item.name}}
							</view>
							<view class="item_tag">
								限时秒杀
							</view>
							<view class="retail_price" style="font-size: 30rpx;margin: 15rpx 0;color: #000;text-decoration: line-through;">
								零售价：￥{{item.original_price}}
							</view>
							<view class="price_go">
								<view class="kill_price" style="width:100%;display: flex;justify-content: space-between;" >
									<view style="font-size: 30rpx;color: rgb(254,0,0);width: 26%;">
										秒杀价：
									</view>
									<view style="font-size: 30rpx;color: rgb(254,0,0);font-weight: bold;width: 74%;">
										<text>{{item.score_deduction_price}}{{currencyAlias.integral_alias}}+{{item.seckill_price}}运营费</text>
									</view>
								</view>
							</view> 
							
							<view class="go_buy" v-if="status == 1 || status == 0">
								<view class="zhezhao" v-if="item.count==1"></view>
								<view class="buy" @click.prevent="linkTo(item.goods_id)">
									<view style="text-align: center;color: #fff;font-size: 30rpx;line-height: 40rpx;">
										去抢购
									</view>
									<view  style="width: 90%;margin: 0 auto;display: flex;justify-content: space-between;">
										<view style="width: 55%;padding: 15rpx 0;">
											 <progress :percent="item.count" :show-info='false' stroke-width="5" font-size='10' activeColor="red" />
										</view>
										<view style="width: 40%;font-size: 24rpx;color: #fff;">
											{{item.count}}%
										</view>
									</view>
								</view>
							</view>
							
						</view>
					</view>
				</view>
			</view>
		</template>
		
	</view>
</template>

<script>
	export default{
		data(){
			return{
				is_seckill: false,
				plugins_img_url: this.$api.plugins_img_url,
				form:{
					page:1,
				},
				status: '',
				page_count:'',
				list:[],
				start_time:'',
				end_time: '',
				pic_url:'',//图片
				show:false,
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			}
		},
		onLoad(options){
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
		},
		onShow() {
			this.getseckilllist()
		},
		methods:{
			getseckilllist(){//获取秒杀列表
				this.$http.request({
					url: this.$api.seckill.getseckillList,
					method: 'POST',
					data:'',
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						if(res.data.length==0){
							this.is_seckill=false;
							return
						}
						this.status = res.data.status;
						this.is_seckill = true;
						this.list=res.data.seckillGoods
						this.start_time=res.data.start_time
						this.end_time=res.data.end_time
						this.pic_url=res.data.pic_url
						for(let i=0;i<this.list.length;i++){
							if(Number(this.list[i].virtual_stock)==0){
								this.list[i].count=0
							}else{
								this.list[i].count=this.list[i].surplus_percentage
							}						
						}
						// if(res.data.list.length==0)return false
						// let list= res.data.list;
						// var arr=this.list.concat(list)
						// this.list =arr
						// this.page_count = res.data.pagination.page_count;
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			linkTo(id){ //跳转到商品详情页
				if(this.status == 0){
					this.$http.toast("活动暂未开始");
					return;
				}
				if(this.status == 2){
					this.$http.toast("活动已结束");
					return;
				}
				uni.navigateTo({
					url:'../../pages/goods/detail?proId='+id
				})
			}
		},
		// onReachBottom() {
		// 	if(this.form.page==this.page_count){
		// 		this.pullUpOn = false;
		// 		return false;
		// 	} 		
		// 	this.form.page=this.form.page+1
		// 	this.getseckilllist();
		// },
	}
</script>
<style lang="less" scoped>
	.seckillHome-container{width: 100%;overflow: hidden;}
	.seckillHome_header{width: 100%;overflow: hidden;}
	.main{width: 100%;overflow: hidden;background:rgb(240,240,240);position: relative;top: -40rpx;z-index: 99;border-radius: 25rpx 25rpx 0 0;}
	.main_top{width: 100%;height: 120rpx;display: flex;justify-content: space-between;}
	.list{width: 100%;overflow: hidden;margin-top: 10rpx;margin-bottom: 50rpx;}
	.item{width: 95%;overflow: hidden;background: #fff;border-radius: 15rpx;margin: 0 auto 15rpx;display: flex;justify-content: space-between;padding: 25rpx 10rpx;}
	.logo image{width: 210rpx;height: 210rpx;display: block;}
	.item_right{width: 470rpx;position: relative;}
	.item_name{font-size: 32rpx;color: #000;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
	.item_tag{width: 130rpx;color: rgb(255,79,17);font-size: 24rpx;border: 1rpx solid rgb(255,79,17);border-radius: 10rpx;text-align: center;margin-top: 5rpx;}
	.price_go{width: 100%;display: flex;justify-content: space-between;margin-top: 25rpx;height: 75rpx;}
	
	.wait_buy, .finished_buy, .go_buy{width: 180rpx;border-radius: 10rpx;position: absolute;top: 80rpx;right: 0;height: 80rpx;}
	.wait_buy{text-align:center;line-height:80rpx;background:#F1F1F1;color:#999;}
	.go_buy{background: rgb(253,174,9);}
	.finished_buy{text-align:center;line-height:80rpx;background:#F1F1F1;color:#999;}
	
	.buy{width: 180rpx;border-radius: 10rpx;height: 80rpx;}
	.zhezhao{width: 180rpx;background: rgba(0, 0, 0, 0.4);border-radius: 10rpx;
	position: absolute;top:0rpx;left: 0;height: 80rpx;text-align: center;line-height: 80rpx;color: #fff;}
	.down{width: 100%;height: 100%;position: fixed;top: 0;left: 0;background: rgba(0, 0, 0, 0.5);z-index: 999;}

</style>
