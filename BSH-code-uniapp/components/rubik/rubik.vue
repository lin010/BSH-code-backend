<template>
	<view class="rubik-root">
		<view v-if="types == 0" class="type1">
			<image class="img" v-for="(item,index) in imageData" @tap="navTo(item.link.new_link_url,item.link.open_type)" :key='index'
			 :src="item.pic_url" mode="widthFix"></image>
		</view>

		<view v-else :class="calssNames">
			<view v-for="(item,index) in imageData" :key='index' class="backgroundSize" @tap="navTo(imageData[index].link.new_link_url,imageData[index].link.open_type)"
			 :style="{'background':'url('+imageData[index].pic_url+')',width:imageData[index].width,height:imageData[index].height,left:imageData[index].left,top:imageData[index].top}"></view>
			<!-- <view class="backgroundSize" @tap="navTo(imageData[1].link.value,imageData[1].link.open_type)" :style="{'background':'url('+imageData[1].pic_url+')',width:imageData[1].width,height:imageData[1].height,left:imageData[1].left,top:imageData[1].top}"></view> -->
		</view>
		
		<block v-if="hotspotData.length != 0">
			<view v-for="(item,index) in hotspotData" :key='index' class="hotspot" @tap="navTo(hotspotData[index].link.new_link_url,hotspotData[index].link.open_type)"
			 :style="{width:hotspotData[index].width+'rpx',height:hotspotData[index].height+'rpx',left:hotspotData[index].left+'rpx',top:hotspotData[index].top+'rpx'}"></view>
		</block>
	</view>
</template>

<script>
	export default {
		name: 'rubik',
		data() {
			return {
				calssNames: '',
			}
		},
		props: {
			types: { //图片魔方样式
				type: Number,
				default: 0
			},
			imageData: { //每个图片的位置数据
				type: Array,
				default: () => []
			},
			space: {
				type: Number,
				default: 0
			},
			hotspotData:{
				type: Array,
				default: () => []
			}
		},
		created() {
			if (this.types == 1 || this.types == 2 || this.types == 3) {
				this.calssNames = 'type3'
			} else if (this.types == 4 || this.types == 5) {
				this.calssNames = 'type5'
			} else if (this.types == 6) {
				this.calssNames = 'type7'
			} else {
				this.calssNames = 'type8'
			}
		},
		methods: {
			navTo(url, type) {
				if (type == 'navigate') {
					if (url) {
						uni.navigateTo({
							url
						})
					}
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
			},
		}
	}
</script>

<style>
	.rubik-root{
		position: relative;
	}
	.type1 {
		width: 100%;
	}

	.type1 .img {
		width: 100%;
		display: block;
	}

	.type3 {
		width: 100%;
		height: 360rpx;
		position: relative;
	}

	.type5 {
		height: 240rpx;
		position: relative;
	}

	.type7 {
		height: 188rpx;
		position: relative;
	}

	.type8 {
		height: 372rpx;
		position: relative;
	}

	.backgroundSize {
		background-size: cover !important;
		background-position: center center !important;
		background-repeat: no-repeat !important;
		position: absolute;
	}
	
	.hotspot{
		position: absolute;
		z-index: 2;
	}
</style>
