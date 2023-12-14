<template>
	<view class="video_box">
		<video
			:class="className"
			:id="`video_${src}`"
			:ref="`video_${src}`"
			:src="src"
			loop
			:show-play-btn="false"
			:show-center-play-btn="false"
			:controls="false"
			:enable-progress-gesture="false"
			:object-fit="fit"
			:autoplay="play"
			@timeupdate="onScheduleChange"
			@waiting="onWaiting"
			@loadedmetadata="loadEndPlay"
			@ended="videoEndPlay"
		></video>
		<!-- 播放按钮 -->
		<view class="play_btn" @click="onPlay">
			<view class="icon_play iconfont icon-ziyuan" v-if="playState == 1000"></view>
		</view>
		<!-- <view class="play_schedule">
			<view class="schedule" :style="{ width: schedule + '%' }"><view v-if="progressDrag" class="progress_drag_dot"></view></view>
		</view> -->
	</view>
</template>

<script>
export default {
	props: {
		src: {
			type: String,
			default: ''
		},
		size:{
			type: String,
			default: 'normal'
		},
		play: {
			type: Boolean,
			default: false
		},
		poster: {
			//视频封面的图片
			type: String,
			default: ''
		},
		progress: {
			type: Number,
			default: 0
		},
		progressValue: {
			type: Number,
			default: 0
		},
		fit: {
			//视频填充样式
			type: String,
			default: 'fill'
		},
	},
	data() {
		return {
			img_url: this.$api.img_url,
			
			schedule: 0,
			// 1000 待播放
			// 2000 播放中
			// 3000 播放缓冲中
			playState: 1000,
			// 是否拖动进度
			progressDrag: false,
			// 视频总长度
			duration: 0,
			videoCtx:''
		};
	},
	watch: {
		play(val) { //查看是否滑动到了当前视频，如果是则播放，不是则暂停并跳回一开始的时间
			if (val) {
				setTimeout(() => {
					this.videoCtx.play();
					this.playState = 2000;
				}, 200);
			} else {
				setTimeout(() => {
					this.videoCtx.pause();
					this.videoCtx.seek(0);
					this.playState = 1000;
					this.schedule = 0;
				},100);
			}
		},
		progress(val) {
			if (this.play) {
				if (val == 0) {
					this.progressDrag = true;
				} else {
					this.schedule = val;
				}
			}
		},
		progressValue(val) {
			if (this.play) {
				this.schedule = val;
				this.progressDrag = false;
				this.videoCtx.seek(this.duration * (val / 100));
			}
		}
	},
	computed:{
		className(){
			if(this.size == 'small'){
				return 'video_file_small';
			}else if(this.size == 'medium'){
				return 'video_file_medium';
			}else{
				return 'video_file';
			}
		}
	},
	mounted() {
		this.videoCtx = uni.createVideoContext(`video_${this.src}`,this);
		if(this.play){
			this.playState = 2000;
		}
	},
	methods: {
		loadEndPlay(e){ //视频加载完成事件
			this.$emit('loadedmetadata',e);
		},
		videoEndPlay(e){ //视频播放完成事件
			this.$emit('ended',e);
		},
		onScheduleChange(e) { //进度条
			this.$emit('timeupdate',e);
			this.duration = e.detail.duration;
			if (!this.progressDrag) {
				this.schedule = (e.detail.currentTime / e.detail.duration) * 100;
			}
		},
		onPlay() { //视频播放暂停事件
			if (this.playState == 2000) {
				this.videoCtx.pause();
				this.playState = 1000;
			} else if (this.playState == 1000) {
				this.videoCtx.play();
				this.playState = 2000;
			} else if (this.playState == 3000) {
				this.videoCtx.play();
				this.playState = 2000;
			}
		},
		// 视频进入缓冲中
		onWaiting() {
			this.playState = 3000;
			console.log('-----------');
		}
	}
};
</script>

<style lang="scss" scoped>
// @import '@/style/mixin.scss';
.video_box {
	position: relative;
}
.video_file {
	width: 750rpx;
	height: calc(100vh - var(--window-bottom));
}
.video_file_small {
	width: 650rpx;
	height: 85vh;
}
.video_file_medium{
	width: 690rpx;
	height: 90vh;
}
.play_schedule {
	position: absolute;
	bottom: 6rpx;
	left: 0;
	height: 2rpx;
	width: 100%;
	background-color: rgba(255, 255, 255, 0.2);
	z-index: 3;
}
.schedule {
	background-color: #fff;
	height: 2rpx;
	width: 0%;
	position: relative;
}
.progress_drag_dot {
	position: absolute;
	right: -8rpx;
	top: -6rpx;
	width: 16rpx;
	height: 16rpx;
	border-radius: 50%;
	background-color: #fff;
}
.play_btn {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	display: flex;
	justify-content: center;
	align-items: center;
}
.icon_play {
	width: 90rpx;
	height: 90rpx;
	color: #FFFFFF;
	font-size: 90rpx;
}
</style>
