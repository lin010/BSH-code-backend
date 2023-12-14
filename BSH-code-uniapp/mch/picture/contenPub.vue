<template>
	<view class="container">
		<view class="border"></view>
		<view class="jx-box-upload">
			<com-upload :serverUrl="serverUrl" :width="190" :height="190" @complete="result" @remove="remove"></com-upload>
		</view>
		
		<view class="title">
			<input class="title-inp" maxlength='20' placeholder-style="font-size:13pt" type="text" value="" placeholder="填写标题会有更多点赞哦~"/>
		</view>
		
		<view class="text">
			<!-- <textarea value="" placeholder="asd" @blur='blur' v-model="textContent"/> -->
			<editor class="text-editor" placeholder="asdas" @blur='blur'></editor>
		</view>
		
		<view class="release" @tap="navTo">
			发布内容
		</view>
	</view>
</template>

<script>
	//实际使用需要传入上传地址，以及上传接口返回数据进行调整组件
	export default {
		data() {
			return {
				imageData: [],
				textContent:'',
				//上传地址
				serverUrl:"http://upload.cn"
			}
		},
		onLoad() {
			console.log(this.textContent,'textContent');
		},
		methods: {
			navTo(){
				uni.navigateTo({
					url:'/pages/picture/release'
				})
			},
			blur(e){
				console.log(e,'textContent');
			},
			result: function(e) {
				console.log(e)
				this.imageData = e.imgArr;
			},
			remove: function(e) {
				//移除图片
				console.log(e)
				let index = e.index
			}
		}
	}
</script>

<style scoped>
	page{
		background: #fff;
	}
	.container {
		padding: 20rpx 0 0rpx 0;
		box-sizing: border-box;
	}

	.jx-box-upload{
		padding-left: 30rpx;
		box-sizing: border-box;
		margin-top: 18rpx;
	}
	
	.border{
		border-bottom: 2rpx solid #F2F2F2;
	}
	.tui-upload-add{
		background: #FFFFFF !important;
	}
	.title-inp{
		font-size: 13pt;
	}
	
	.title{
		border-bottom: 2rpx solid #F2F2F2;
		margin: 30rpx 30rpx 0;
		padding: 20rpx 0 26rpx;
	}
	.text{
		padding: 20rpx 30rpx 0;
	}
	.text-editor{
		height: 680rpx;
		/* border: 2rpx solid #000000; */
	}
	.release{
		background: #BC0100;
		color: #FFFFFF;
		text-align: center;
		padding: 20rpx 0;
		width: 690rpx;
		margin: auto;
		border-radius: 60rpx;
		margin-top: 30rpx;
	}
</style>
