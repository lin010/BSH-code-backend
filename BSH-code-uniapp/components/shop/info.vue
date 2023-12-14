<template>
	<view class="info">
		<image :src="params.cover_url" class="logo"></image>
		<view class="info_r">
			<view class="info_r_top">
				<view class="info_r_top_name">{{params.name}}</view>
				<view class="info_r_top_right" @tap="toDetail(params.mch_id,params.store_id)">
					<view>进店看看</view>
					<view class="info_r_top_right_r"></view>
				</view>
			</view>
			<view class="info_r_bottom">
				<view class="index1_content_shop_block_r_m_star">
					  <view class="iconfont iconwujiaoxing" v-for="i in (Number(params.score))" style="color: #FFA600;"></view>
					  <view class="point">{{params.score}}分</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props:{
			params:{
				type: Object,
			}
		},
		data() {
			return {
				
			};
		}, 
		created() {
			console.log(this.params)
		},
		methods:{
			toDetail(id,store_id){
				let that=this
				uni.setStorage({
					key:'mch_id',
					data:id,
					success(){
						uni.navigateTo({
							url:'/merchants/detail/detail?store_id='+store_id
						})
					}
				})
				
			}
		},
		mounted(){
			
		},
		destroyed(){
			
		}
	}
</script>

<style>
	/* @import url("@/static/font-icon/iconfont1.css"); */
	@import url("@/plugins/font-icon/iconfont1.css");
	.info{display: flex;background-color: #fff;margin-top: 8px;margin-bottom: 8px;padding: 10px;}
	.logo{width: 61px;
	height: 61px;
	background: #FFFFFF;
	border-radius: 50%;}
	.info_r{margin-left: 10px;flex: 1;}
	.info_r_top{display: flex;align-items: center;}
	.info_r_top_name{
		font-size: 16px;
		color: #333;
	    flex: 1;
	}
	.info_r_top_right{display: flex;color: #53DEDB;font-size: 14px;align-items: center;}
	.info_r_top_right_r{width: 8px;height: 8px;border-top:1px #53DEDB solid;border-right:1px #53DEDB solid;transform: rotate(45deg);margin-left: 5px;}
	.index1_content_shop_block_r_m{margin: 8px 0px;display: flex;}
	.index1_content_shop_block_r_m_star{flex: 1;display: flex;align-items: center;}
	.index1_content_shop_block_r_m_aver{
	font-size: 11px;
	font-weight: bold;
	color: #787878;
	margin-left: 20px;
	}
	.index1_content_shop_block_r_m_dist{
	font-size: 11px;
	font-weight: bold;
	color: #787878;
	margin-left: 30px;
	}
</style>
