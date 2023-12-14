<template v-cloak>
	<view class="app">
		<view class="center">
			<view class="item">
				<view class="title">手机号码</view>
				<view class="input-btn">
					<input class="input" type="number" v-model.number="dataForm.mobile" placeholder="请输入手机号码" disabled/>
					<view class="btn" @click="editePhone">{{'编辑'}}</view>
				</view>
			</view>
			<view class="item">
				<view class="title">验证码</view>
				<view class="input-btn">
					<input class="input" type="number" v-model.number="dataForm.captcha" placeholder="请输入验证码" />
					<view class="btn" @click="getCode">{{countDown || '发送验证码'}}</view>
				</view>
			</view>
			<view class="item">
				<view class="title">设置密码</view>
				<view class="input-btn">
					<input class="input" type="number" v-model="dataForm.withdraw_pwd" maxlength="6" placeholder="请输入6位数支付密码" style="-webkit-text-security:disc" />
				</view>
			</view>
			<view class="item">
				<view class="title">确认密码</view>
				<view class="input-btn">
					<input class="input" type="number" v-model="confirm_password" maxlength="6" placeholder="请输入6位数支付密码" style="-webkit-text-security:disc"/>
				</view>
			</view>
			<view class="item last">
				<view class="title"></view>
				<view class="jx-btn" @click="dataSubmit">确认提交</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		isNullOrEmpty,
		isMobile
	} from '@/utils/util.js';
	export default {
		data() {
			return {
				data: {},
				dataForm: {
					mobile: '',
					captcha: '',
					withdraw_pwd: '',
				},
				countDown: '',
				textCol:'',
				confirm_password: ''
			}
		},
		onLoad(options) {
			this.textCol = this.globalSet('textCol');
			let urltitle=options.phone
			if(urltitle){
				this.dataForm.mobile=options.phone
			}
		},
		methods: {
			dataSubmit(){
				if(!isNullOrEmpty(this.dataForm.mobile)){
					this.$http.toast("手机号必须填哦")
					return
				}
				if(!isNullOrEmpty(this.dataForm.captcha)){
					this.$http.toast("验证码必须填写哦")
					return
				}
				if(!isNullOrEmpty(this.dataForm.withdraw_pwd) || this.dataForm.withdraw_pwd.length !== 6){
					this.$http.toast("请输入6位数字密码")
					return
				}
				if(this.confirm_password !== this.dataForm.withdraw_pwd){
					this.$http.toast("请确认和上面的密码一致")
					return 
				}
				this.dataForm.mobile=String(this.dataForm.mobile)
				this.$http.request({
					url: this.$api.moreShop.setwithdrawpwd,
					method: 'POST',
					data: this.dataForm
				}).then(res => {
					if(res.code == 0){
						this.$http.toast("保存成功");
						setTimeout(() => {
							this.navBack();
						},1000 * 2)
					}else{
						this.$http.toast(res.msg);
					}
				})
				
			},
			editePhone(){ //更改手机号码
				if(uni.getStorageSync('userInfo')){
					uni.navigateTo({
						url:'../editePhone/editePhone?phone='+this.dataForm.mobile+"&title="+"验证绑定手机"
					})
				}else{
					uni.navigateTo({
						url:'../editePhone/editePhone'
					})
				}
			},
			getCode() {
				if (!isMobile(this.dataForm.mobile)) {
					this.$http.toast("请先输入手机号码");
					return;
				}
				let _self = this;
				_self.countDown = 60;
				let temp = setInterval(() => {
					_self.countDown--
					if (_self.countDown <= 0) {
						clearInterval(temp);
					}
				}, 1000)
				_self.$http.request({
					url: _self.$api.default.phoneCode,
					data: {
						mobile: _self.dataForm.mobile,
					},
					method: 'POST'
				}).then((res) => {
					if(res.code == 0){
						_self.$http.toast("发送成功");
					}else{
						_self.$http.toast(res.msg);
					}
				}).catch(err => {
					console.log(err);
				})
			},
		},
	}
</script>

<style lang="scss" scoped>
	.app {
		background-color: #FFFFFF;
		height: 100%;
		width: 100%;
		display: flex;

		.center {
			flex: 1;
			display: flex;
			flex-direction: column;
			padding: 100rpx 30rpx 0;

			.item {

				padding: 0 30rpx;
				font-size: #000000;
				border-bottom: 2rpx solid #E0E0E0;

				&:last-child {
					border-bottom: 0;
				}

				.title {

					line-height: 60rpx;
					font-size: 13pt;
					padding: 20rpx 0;
				}

				.input-btn {

					margin-bottom: 20rpx;
					display: flex;
					.input {
						font-size: 11pt;
						flex: 1;
					}
					.uni-input-placeholder {
						color: #E0E0E0;
					}
					.btn {
						flex-basis: 180rpx;
						position: relative;
						color: #FF7104;
						font-size: 11pt;
						text-align: center;
						&::before {
							color: #E0E0E0;
							content: "|";
							position: absolute;
							left: -20rpx;
						}
					}
				}
				
				.jx-btn{
					text-align: center;
					height: 90rpx;
					line-height: 90rpx;
					background-color: #FF7104;
					border-radius:45rpx;
					font-size:9pt;
					color: #FFFFFF;
				}
				
				&.last{
					
					span{
						margin-left: 30rpx;
						color: #000000;
						font-size: 8pt;
					}
				}
			}
		}
	}
</style>
