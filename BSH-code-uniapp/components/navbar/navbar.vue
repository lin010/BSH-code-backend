<template>
	<view class="navbar_box flex flex-y-center" style="background: #FFFFFF;">
		<view class="navbar_list flex flex-col flex-y-center" @tap="navTo(item.url)"
		v-for="(item,index) in navbarData" :key='index'>
			<image v-if="item.state != 1" class="navbar_list_img" :src="item.pic_url" mode=""></image>
			<image v-else class="navbar_list_img" :src="item.pic_url_checked" mode=""></image>
			<text class="navbar_list_name" :style="{'color':item.state != 1 ?textCol:textCol_checked}">{{item.name}}</text>
		</view>
		
	</view>
</template>

<script>
	export default{
		name:'navbar',
		props:{
			navbarBackground:{
				type:String,
				default:'#ffffff'
			},
			textCol:{
				type:String,
				default:'#000000'
			},
			textCol_checked:{
				type:String,
				default:'#bc0100'
			},
			navbarData:{
				type:Array,
				default:()=>[]
			}
		},
		data(){
			return{
			}
		},
		methods:{
			navTo(url){
				uni.navigateTo({
				    url: url
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
	.navbar_box{
		width: 100%;
		height: 100rpx;
		position: fixed;
		bottom: 0px;
		padding-top: 4rpx;
		justify-content: space-around;
		z-index: 9999;
	}
	.navbar_list{
		padding: 0px 40rpx;
	}
	.navbar_list_img{
		width: 48rpx;
		height: 48rpx;
	}
	.navbar_list_name{
		font-size: 26rpx;
	}
</style>
