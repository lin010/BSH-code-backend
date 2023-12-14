<template>
	<view class='modal-root' @touchmove.stop.prevent="moveHandle" v-if="is_show">
		<view class="model-box">
			<swiper class="swiper-box" :indicator-dots="true" circular="true" :autoplay="true" :interval="3000" :duration="1000">
				<swiper-item v-for="(item,index) in modalData.list" :key='index' @click.stop="navTo(item.link.url,item.link.openType)">
					<view class="swiper-item" :style="{background:`url(${item.picUrl})no-repeat`}"></view>
				</swiper-item>
			</swiper>
			<view class="moadl-close-box" @click="close">
				<view class="moadl-close iconfont icon-guanbi"></view>
			</view>
		</view>
	</view>
</template>

<script>
	export default{
		name:'diyModal',
		props:{
			modalData:{
				type:Object,
				default:()=>{}
			}
		},
		data(){
			return{
				  is_show:false
			}
		},
		
		created(){
			
			if(uni.getStorageSync("times") == 1){
				this.is_show = false;
			}else{
				this.is_show=true;
			}
			
			this.modalData.times == 1 ? uni.setStorageSync('times', '1') : uni.setStorageSync('times', '0')
			
		},
		mounted() {
		
	 
		},
		methods:{
			moveHandle(){},//防止遮罩层下的页面还能滚动
			navTo(url,type){
				var that = this;
				if(url){
					if (type == 'navigate') {
						uni.navigateTo({
							url,
							success(){
								that.modalData.opened = false;
							}
						})
					}else if(type == 'web'){
						window.location.href = decodeURIComponent(url.split('=')[1]);
					}else{
						uni.makePhoneCall({
						    phoneNumber: url.split('=')[1], 
							success: (res)=>{
								this.$http.toast('打电话回调成功！')
							}
						});
					}
				}
			},
			close(){ //关闭弹窗
		
			this.is_show=false
			 
			
				 
			}
		}
	}
</script>

<style scoped>
	.modal-root{
		background: rgba(0,0,0,0.2);
		width: 100%;
		height: 100%;
		position: fixed;
		top: 0;
		left: 0;
		z-index: 999999;
	}
	.model-box{
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%,-50%);
		width: 100%;
	}
	
	.swiper-box{
		width: 560rpx;
		height: 650rpx;
		margin: auto;
		border-radius: 10rpx;
		overflow: hidden;
	}
	.swiper-item{
		height: 100%;
		background-size: cover !important;
		background-position: center !important;
	}
	.moadl-close-box{
		display: flex;
		justify-content: center;
	}
	.moadl-close{
		text-align: center;
		border: 2rpx solid #FFFFFF;
		color: #FFFFFF;
		display: inline-block;
		border-radius: 50%;
		width: 56rpx;
		height: 56rpx;
		line-height: 56rpx;
		margin: 30rpx auto;
	}
</style>
