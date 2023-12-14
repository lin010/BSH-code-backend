<template>
	<view class="yz-code fixed" :class="{'active':showPop}">
		<view class="pop-bg" @click="hide"></view>
		<view class="yz-count">
			<view class="yz-type">请输入提现密码</view>
			<view class="yz-ipt">
				<input type="number"   v-model="code" @blur="lostBlue" @input="getCode" :focus="focus" :maxlength="length"  @keyup.13="next()" />
				<view class="code-shwo-box">
					<view class="box centerboth" v-for="(item, index) in length" :class="code.length==index?'cur':''" :key="index">
						<text class="dot" v-if="code.length > index"></text>
						<view class="line"></view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>
<script>
	export default {
		props: {
		    length:{ 
				type: [Number, String], 
				default:6,
			}
		},
		data() {
			return {
				code:'',
				codeArr:[],
				showPop:false,
				focus:false,
			}
		},
		onLoad() {
			
		},
		methods: {
			show:function() {
				this.showPop = true;
				this.focus = true;
				return true;
			},
			hide:function() {
				this.showPop = false;
				this.focus = false;
				return false;
			},
			getCode:function(e){
				var value = e.detail.value;
				if(value.length <= this.length){
					var valueArr = value.split('');
					this.codeArr = valueArr;
				}
				if(value.length == this.length){
					this.hide();//关闭弹窗
					this.code = '';
					this.$emit("finish",value);//输入完毕并传值给页面
				}
			},
			lostBlue:function(){//失去焦点关闭弹窗
				this.hide();
			},
			next: function() {
				return this.code;
			},
		}
	}
</script>

<style scoped>
	.yz-code{
		position: fixed;
		width: 100%;
		height: 100%;
		left: 0;
		top: 0;
		z-index: -9;
		opacity: 0;
	}
	.yz-code.active{
		z-index: 90;
		opacity: 1;
	}
	.pop-bg{
		position: absolute;
		width: 100%;
		height: 100%;
		left: 0;
		top: 0;
		z-index: 1;
		background: rgba(0,0,0,0.65);
	}
	.yz-count{
		width: 90%;
		padding: 25px;
		position: relative;
		z-index: 9;
		background: #FFFFFF;
		margin: 40% auto;
		border-radius: 10rpx;
	}
	.yz-type{
		text-align: center;
		font-weight: bold;
		font-size: 30rpx;
		margin-bottom: 30rpx;
	}
	.yz-ipt{
		width: 100%;
		position: relative;
		height: 80rpx;
		margin: 20rpx 0;
	}
	.yz-ipt input{
		position: absolute;
		left: 200%;
		top: 0;
		z-index: -1;
		width: 100%;
		height: 80rpx;
		opacity: 0;
		color:transparent;
		margin-bottom: 30rpx;
	}
	.yz-ipt .havebottom{
		margin-bottom: 30rpx;
	}
	.code-shwo-box{
		position: relative;
		background: #FFFFFF;
		z-index: 9;
		display: flex;
		height: 80rpx;
		justify-content: space-around;
	}
	.code-shwo-box .box{
		width: 80rpx;
		height: 80rpx;
		border: 1px solid #969696;
		margin: 0 10rpx;
		text-align: center;
		line-height: 80rpx;
		font-size: 30rpx;
		position: relative;
		border-radius: 5rpx;
		overflow: hidden;
	}
	.code-shwo-box .box .dot{
		display: block;
		width: 10rpx;
		height: 10rpx;
		background: #000000;
		border-radius: 10rpx;
		border-radius: 50%;
		overflow: hidden;
		position: absolute;
		top: 50%;
		left: 50%;
		margin-top: -5rpx;
		margin-left: -5rpx;
	}
	.code-shwo-box .box.cur{
		border: 1px solid red;
	}
	.code-shwo-box .box .line{
		height: 50%;
		width: 1px;
		background: red;
		opacity: 0;
	}
	.code-shwo-box .box.cur .line{
		opacity: 1;
		-webkit-animation: sharkLight 1s linear infinite;
		animation: sharkLight 1s linear infinite;
	}
	@-webkit-keyframes sharkLight {
		from {
			-webkit-transform: scale(1);
			opacity: 1;
		}
		to {
			-webkit-transform: scale(0.9);
			opacity: 0;
		}
	}
</style>
