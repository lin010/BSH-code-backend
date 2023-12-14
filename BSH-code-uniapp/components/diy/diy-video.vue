<template>
	<view class="diy-video" :style="boxStyle">
		<img :src="data.pic_url" style="width: 100%;height:100%;" v-if="!showVideo" @click="videoPlay(data.url)">
		<video class="video" v-show="showVideo" id="myVideo" :src="data.url" :poster="data.pic_url" :show-center-play-btn="false" @error="videoErrorCallback" controls style="width: 100%;height:100%;"></video>
	</view>
</template>

<script>
	export default {
		props: {
			value: Object,
			diyStyle: {
				type: String,
				default: ''
			}
		},
		data() {
			return {
				data: {},
				showVideo: false,
				audioAction: {
					method: 'pause'
				},
				videoContext: {}
			}
		},
		created() {
			this.data = JSON.parse(JSON.stringify(this.value));
			// #ifndef MP-ALIPAY
			this.videoContext = uni.createVideoContext('myVideo')
			// #endif
		},
		computed:{
			boxStyle(){
				if(!!this.diyStyle){
					return this.diyStyle;
				}
				return '';
			}
		},
		methods: {
			videoErrorCallback(e){
				// uni.showModal({
				// 	content: e.target.errMsg,
				// 	showCancel: false
				// })
			},
			videoPlay() {
				this.showVideo = true;
				this.videoContext.play();
			}
		}
	}
</script>

<style lang="scss" scoped>
	.diy-video {
		width: 100%;
		height: 400rpx;
		background: #353535;
	}
</style>
