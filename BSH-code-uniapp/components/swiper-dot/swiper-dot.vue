<template>
	<view class="dot-box">
		<view class="dot-item" v-for="(item,index) in length" :key='index' :class="{'dot-item-active':swiperCurrent == index}"
		:style="{width:width+'rpx',height:height+'rpx','border-radius':radius}"></view>
	</view>
</template>

<script>
	export default{
		props:{
			length:{
				type:Number,
				default:0
			},
			swiperCurrent:{
				type:Number,
				default:0
			},
			width:{
				type:Number,
				default:10
			},
			height:{
				type:Number,
				default:10
			},
			radius:{
				type:String,
				default:'50%'
			}
		},
		data(){
			return{
				
			}
		},
	}
</script>

<style scoped>
	.dot-box{
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.dot-item{
		background: #C8C8C8;
		margin-right: 6rpx;
		margin-top: 15rpx;
	}
	.dot-item-active{
		background: #BC0100;
	}
</style>
