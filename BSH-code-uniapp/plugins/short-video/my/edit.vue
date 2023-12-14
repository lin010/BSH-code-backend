<template>
	<view class="edit-root">
		<view class="form-data">
			<view class="avatar-box flex flex-x-between flex-y-center">
				<view>头像</view>
				<view class="flex flex-y-center">
					<com-upload v-if="showEditImg" ref="upload" :serverUrl="serverUrl" @complete="result" :limit="1" @remove="remove" :width="120" :height="120"></com-upload>
					<image v-else class="avatar-img" :src="dataForm.avatar_url || img_url+'images/my/mine_def_touxiang_3x.png'" @click="changAcatar"></image>
					<text class="common-icon iconfont icon-xiala"></text>
				</view>
			</view>
			
			<view class="phone-box flex flex-y-center">
				<view>手机号：</view>
				<view>13533333333</view>
			</view>
			<!-- <view class="phone-box flex flex-y-center">
				<view>门店：</view>
				<view>七件事门店</view>
			</view> -->
			<view class="phone-box flex flex-y-center">
				<view class="phone-box-left">个性签名：</view>
				
				<view style="flex: 1;">
					<input placeholder="请输入个性签名" v-model="dataForm.signature_content" />
				</view>
			</view>
			<!-- <view class="phone-box flex flex-y-center">
				<view class="phone-box-left2">地址：</view>
				<view style="flex: 1;">广东省佛山市禅城区季华西路133号绿岛湖 国际中心A4座1304</view>
			</view> -->
		</view>
		
		<view class="submit-btn" @tap='submit'>提交编辑</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				// 上传头像
				showEditImg: false,
				serverUrl: this.$api.default.upload,
				dataForm: {
					avatar_url:'',//头像
					signature_content:'',//个性签名
				},
				mobile:'',
			};
		},
		onLoad() {
			this.getUserInfo();
		},
		methods:{
			getUserInfo(){ //获取用户信息
				this.$http.request({
					url:this.$api.plugin.video.user_list,
					method:'post',
				}).then(res => {
					if(res.code == 0){
						this.dataForm.avatar_url = res.data.avatar_url;
						this.dataForm.signature_content = res.data.signature_content;
						this.mobile = res.data.mobile;
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			submit(){
				this.$http.request({
					url:this.$api.plugin.video.edit_user,
					method:'post',
					data:this.dataForm
				}).then(res => {
					if(res.code == 0){
						this.$http.toast('编辑成功');
						setTimeout(()=>{
							this.navBack();
						},1000)
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			result(e,name) {
				if(name == 'graphic'){
					if(e.status == 1){
						this.img_list = e.imgArr;
					}
				}else{
					this.dataForm.avatar_url = e.imgArr[0];
				}
			},
			remove(e,name) { //移除图片
				let index = e.index;
			},
			changAcatar() {
				this.$http.toast("上传头像");
				this.showEditImg = true;
				this.$nextTick(() => {
					this.$refs['upload'].$el.click()
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.form-data{
		margin-top: 20rpx;
		background: #FFFFFF;
		padding: 0 30rpx;
		color: #000000;
		font-size: 30rpx;
	}
	
	.avatar-box{
		padding: 16rpx 0;
		border-bottom: 1px solid #E1E1E1;
		
		.avatar{
			width: 104rpx;
			height: 104rpx;
			border-radius: 50%;
		}
	}
	.phone-box{
		padding: 40rpx 0;
		border-bottom: 1px solid #E1E1E1;
		
		&:last-of-type{
			border-bottom: 0;
		}
		.phone-box-left{
			width: 154rpx;
		}
		.phone-box-left2{
			width: 100rpx;
		}
	}
	
	.submit-btn{
		color: #FFFFFF;
		background: #BC0100;
		width: 690rpx;
		height: 90rpx;
		border-radius: 60rpx;
		margin: 120rpx auto;
		text-align: center;
		line-height: 90rpx;
		font-size: 26rpx;
	}
	
	.avatar-box{
		padding: 16rpx 0;
		border-bottom: 1px solid #E0E0E0;
		
		.avatar-img{
			width: 104rpx;
			height: 104rpx;
			border-radius: 50%;
			display: block;
		}
		.common-icon{
			font-size: 50rpx;
			margin-left: 20rpx;
			color: #A9A9A9;
			line-height: 54rpx;
		}
	}
</style>
