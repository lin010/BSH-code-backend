<template>
	<view>
		<view class="line"></view>
		
		<view class="main-box">
			<view class="tui-box-upload">
				<view class="content-tips">修改背景图:</view>
				<com-upload :serverUrl="serverUrl" :width="190" :height="190" @complete="result" @remove="remove"></com-upload>
			</view>
			
			<view class="signature">
				<view class="signature-title">个性签名:</view>
				<input class="signature-inp" type="text" value="" placeholder="填写属于你自己的个性签名吧!" 
				placeholder-style="color:#CCCCCC;font-size:10pt"/>
			</view>
			
			<view class="subBtn" @tap="submit">确认编辑</view>
		</view>
	</view>
</template>

<script>
	//实际使用需要传入上传地址，以及上传接口返回数据进行调整组件
	export default {
		data() {
			return {
				//上传地址
				serverUrl:"http://upload.cn"
			}
		},
		methods: {
			result: function(e) {
				console.log(e)
				this.imageData = e.imgArr;
			},
			remove: function(e) {
				//移除图片
				console.log(e)
				let index = e.index
			},
			submit(){
				this.$http.toast('提交')
			}
		}
	}
</script>

<style scoped>
	page{
		background: #FFFFFF;
	}
	.line{
		border-bottom: 2rpx solid #F7F7F7;
	}
	.main-box{
		padding: 0 30rpx;
	}
	.tui-box-upload{
		box-sizing: border-box;
		margin-top: 18rpx;
		padding-bottom: 38rpx;
		border-bottom: 2rpx solid #F7F7F7;
	}
	.content-tips{
		font-weight: 600;
		font-size: 10pt;
		color: #000000;
		margin: 40rpx 0 30rpx;
	}
	.signature{
		padding: 40rpx 0 0;
	}
	.signature-title{
		font-weight: 600;
		font-size: 10pt;
		color: #000000;
		letter-spacing: 2rpx;
		margin-bottom: 20rpx;
	}
	.signature-inp{
		border-bottom: 2rpx solid #F7F7F7;
		padding-bottom: 10rpx;
	}
	.subBtn{
		background: #BC0100;
		border-radius: 50rpx;
		padding: 26rpx 0;
		color: #FFFFFF;
		font-size: 10pt;
		text-align: center;
		letter-spacing: 4rpx;
		margin-top: 200rpx;
	}
</style>
