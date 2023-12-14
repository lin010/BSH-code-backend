<template>
	<view class="app">
		<view class="container">
			<com-nav-bar @clickLeft="back" left-icon="back" title="我要激活" :status-bar="true" background-color="#ffffff" :border="false" color="#000000"></com-nav-bar>
			<view class="input-text flex-grow-1">
				<div class="title">激活码</div>
				<input class="input" type="text" v-model="activation_key" maxlength="12" placeholder="请输入激活码" placeholder-class="input-placeholder-alter"/>
			</view>
			
			<view class="bottom">
				<view class="btn submit" @click="dataFormSubmit()" :style="{background:textColor}">
					<span>确认激活</span>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				activation_key: '',
				textColor:'#bc0100',
				bg_url:'',
			};
		},
		onLoad() {
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
		},
		methods:{
			dataFormSubmit(){
				console.log("确认激活");
			},
			back(){
				this.navBack();
			}
		}
	}
</script>

<style lang="scss" scoped>
.app{
	height: 100%;
}
.container{
	height: 100%;
	background-color: #F7F7F7;
	
	.input-text{
		padding: 0 30rpx;
	}
	.title,.input{
		padding-left: 30rpx;
	}
	
	.title{
		font-size:38rpx;
		font-weight:400;
		color:#000000;
		line-height:120rpx;
	}
	
	.input{
		padding-bottom: 26rpx;
		border-bottom: 2rpx solid #E0E0E0;
		height: 64rpx;
	}
	.input-placeholder-alter{
		font-size: 10pt;
		font-weight: 400;
		color: #E0E0E0;
		line-height: 46rpx;
	}
	
	.bottom {
		margin-bottom: 205rpx;
		padding: 0 30rpx;
	
		.btn {
			line-height: 90rpx;
			text-align: center;
			border-radius: 45rpx;
			font-size: 13pt;
	
			&.submit {
				background-color: #BC0100;
				color: #FFFFFF;
			}
		}
	}
}
</style>
