<template>
	<view class="diy-timer">
		<view style="font-size: 0;">
			<image v-if="data.picUrl" :src="data.picUrl" mode="scaleToFill" style="width:100%"></image>
		</view>
		<view @click="openUrl" class="bg" :style="'background-image: url('+data.bottomBg+');'" style="display: flex;align-items: center;">
			<view>
				<view>距离活动开始还有</view>
				<view>{{remainTimeText}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	var countdownFun = function(end_time){
		var date = new Date();
		var timestamp =parseInt(date.getTime()/1000);
		var text = "", time = (end_time - timestamp);
		if(time <= 0){
			text = '0'+'天'+'0'+"时"+'0'+'分'+'0'+'秒';
		}else{
			let days, hours, minutes, seconds;
			days 	= parseInt(time / 60 / 60 / 24 , 10); //计算剩余的天数
			hours 	= parseInt(time / 60 / 60 % 24 , 10) < 10 ? "0" + parseInt(time / 60 / 60 % 24 , 10) : parseInt(time  / 60 / 60 % 24 , 10); //计算剩余的小时 
			minutes = parseInt(time / 60 % 60, 10)<10 ? "0" + parseInt(time / 60 % 60, 10) : parseInt(time / 60 % 60, 10);//计算剩余的分钟 
			seconds = parseInt(time % 60, 10)<10 ? "0" + parseInt(time  % 60, 10) : parseInt(time  % 60, 10);//计算剩余的秒数 								
			text = days + "天" + hours + "时" + minutes + '分' + seconds + "秒";
		}
		return text;
	};
	export default {
		props: {
			value: {
				type:Object,
				default:()=>{}
			},
		},
		data() {
			return {
				remainTimeText: '00天00时00分00秒',
				data:{}
			}
		},
		computed:{
			
		},
		created() {
			this.data = JSON.parse(JSON.stringify(this.value));
			let dt = new Date(this.data.endDateTime), that = this, endTime = dt.getTime()/1000;
			setInterval(function(){
				that.remainTimeText = countdownFun(endTime);
			}, 1000);
		},
		methods: {
			openUrl(){
				let url = this.data.link.url;
				if (url.split('=')[0] == '/pages/web/web?url') {
					//#ifdef H5
					window.location.href = decodeURIComponent(url.split('=')[1]);
					//#endif
					
					//#ifdef MP-WEIXIN
						uni.navigateTo({
							url:'/webview/webview?url=' + url.split('=')[1]
						})
					//#endif
					
				}else if(url.split('=')[0] == 'tel?tel'){
					uni.makePhoneCall({
					    phoneNumber: url.split('=')[1], 
						success: (res)=>{
							this.$http.toast('打电话回调成功！')
						}
					});
				}else if(url.split('=')[0] == 'app?app_id'){
					let appId=params[0].value
					let path=params[1].value
					//#ifdef MP-WEIXIN
						 wx.navigateToMiniProgram({
							appId: appId,
							path: path,
							// extraData: {  //参数
							//     mall: '1'
							// },
							envVersion: 'release',// 打开正式版
							success(res) {
								   // 打开成功
							},
							fail: function (err) {
								console.log(err);
						    }
						})
					//#endif
					//#ifdef H5
						this.$http.toast('请前往小程序访问');
					//#endif
				}else{
					uni.navigateTo({
						url
					})
				}
			}
		}
	}
</script>

<style>
	.diy-timer{}
	.diy-timer .bg {
		height: 140rpx;
		padding: 0 50rpx;
		color: #fff;
		background-size: 100% 100%;
		background-position: center;
		background-repeat: no-repeat;
	}
</style>
