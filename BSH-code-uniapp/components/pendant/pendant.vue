<template>
	<view class="pendantRoot" :class="shiftBool?'':'opacity'" @tap="navTo(pendant_url)">
		<image class="pendant-img" :src="pendant_pic_url" mode=""></image>
	</view>
</template>

<script>
	export default{
		name:"pendant",
		props:{
			pendant_pic_url:{
				type:String,
				default:""
			},
			pendant_url:{
				type:String,
				default:""
			},
			shiftBool:{
				type:Boolean,
				default:true
			},
			jumpType:{
				type:Number,
				default:1
			}
			
		},
		methods:{
			navTo(url){
				switch(this.jumpType){
					case 1:
					uni.navigateTo({
						url
					});
					break;
					
					case 2:
					uni.makePhoneCall({
					    phoneNumber: '13535014139',
						success: (res)=>{
							this.$http.toast('打电话回调成功！')
						}
					});
					break;
				}
				
				if(this.jumpType == 1){
					uni.navigateTo({
						url
					})
				}
			}
		}
	}
</script>

<style>
	.pendantRoot{
		position: fixed;
		top: 60%;
		right: 10rpx;
		transition: all 1s;
	}
	.pendant-img{
		width: 120rpx;
		height: 120rpx;
	}
	.opacity{
		opacity: 0.5;
		right: -50rpx;
	}
</style>
