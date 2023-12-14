<template>
	<view class="">
		<view class="orderBox flex flex-y-center" v-if="orderShow">
			<view>
				<image class="avatar" :src="rendering.pic_url" mode=""></image>
			</view>
			<view class="msg">
				{{rendering.name}} {{rendering.time}}分钟前拼单成功!
			</view>
		</view>
	</view>
</template>

<script>
	export default{
		name:'order-popup',
		props:{
			orderPopupData:{
				type:Array,
				default:()=>[]
			}
		},
		data(){
			return{
				rendering:{},
				orderShow:false
			}
		},
		created() {
			var index = 0;
			setInterval(()=>{
				this.orderShow = !this.orderShow;
				if(this.orderShow){
					this.rendering = this.orderPopupData[index];
					index++;
					if(index>=this.orderPopupData.length){
						index = 0;
					}
				}
			},4000)
		}
	}
</script>

<style>
	.orderBox{
		background: rgba(0,0,0,0.8);
		max-width: 500rpx;
		padding: 10rpx 16rpx 10rpx 10rpx;
		border-radius: 6rpx;
	}
	.avatar{
		width: 44rpx;
		height: 44rpx;
		border-radius: 50%;
		display: block;
		margin-right: 10rpx;
	}
	.msg{
		font-size: 9pt;
		color: white;
		white-space: nowrap;
		text-overflow: ellipsis;
		overflow: hidden;
	}
</style>
