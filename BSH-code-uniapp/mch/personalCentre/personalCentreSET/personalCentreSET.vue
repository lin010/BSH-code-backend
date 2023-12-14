<template v-cloak>
	<view class="app">
		<view class="user-data">
			<view class="box user-basis">
				<view class="title">
					<span>基础资料</span>
				</view>
				<view class="content">
					<view class="item avatar">
						<view class="msg">头像</view>
						<view class="btn">
							<view class="edit-avatar">
								<com-upload v-if="showEditImg" ref="upload" :serverUrl="serverUrl" @complete="result" :limit="1" @remove="remove" :width="120" :height="120"></com-upload>
								<image v-else class="img" :src="dataForm.avatar || img_url+'images/my/mine_def_touxiang_3x.png'" @click="changAcatar"></image>
								<!-- #ifdef APP-PLUS || H5-->
								<view class="iconfont icon-xiala" @click="changAcatar"></view>
								<!-- #endif -->
							</view>
						</view>
					</view>
					<view class="item phone">
						<view class="msg">手机号:</view>
						<view>{{dataForm.mobile || '请设置你的手机号码'}}</view>
						<view class="btn">
							<view class="edit">
								<span @click="openUrl('../personalCentreSETPhone/personalCentreSETPhone')">
									修改
								</span>
							</view>
						</view>
					</view>
					<view class="item nickname">
						<view class="msg">商户名:</view>
						<!-- <view v-if="!editNickname">{{userInfo.nickname || '请设置你的昵称'}}</view> -->
						<view class="btn">
							<input class="text" type="text" v-model="dataForm.nickname" placeholder="请设置您的姓名" style="width: 440rpx;"/>
							<view class="edit"></view>
						</view>
					</view>
				</view>
			</view>
			<view class="box user-alipay">			
				<view class="content">
					<view class="item password">
						<view class="msg">密码:</view>
						<view>* * * * * *</view>
						<view class="btn">
							<view class="edit">
								<span @click="openUrl('../personalCentreSETPassWorde/personalCentreSETPassWorde')">
									修改密码
								</span>
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="operate">
			<button class="btn" type="warn" :style="{backgroundColor: textColor || ''}" @click="dataSubmit" :disabled="editStatic">确认修改</button>
		</view>
		<main-datetime ref="dateTime" :type="2" :startYear="startYear" :endYear="endYear" cancelColor="#888" color="#5677fc"
		 :setDateTime="setDateTime" @confirm="changeDateTime"></main-datetime>
		 
		 <main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				dataForm: {
					avatar: '',
					mobile: '',
					nickname: '',
				},
				startYear: 1980,
				endYear: 2030,
				setDateTime: "",
				address: '',
				serverUrl: this.$api.default.upload,
				showEditImg: false,
				editStatic: true,
				loading: false,
				textColor:''
			}
		},
		onLoad() {
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
			}
			this.getUserInfo();
		},
		watch:{
			dataForm: {
				deep: true,
				handler(newVal, val) {
					this.editStatic = false;
				},
			}
		},
		methods: {
			dataSubmit() {
				var nicknameRegExp = /^[\u4e00-\u9fa5a-z0-9]+$/gi;
				if(!nicknameRegExp.test(this.dataForm.nickname)){
					this.$http.toast("您输入的姓名格式有误");
					return;
				}
				if(!(this.dataForm.nickname.length >=2 && this.dataForm.nickname.length <= 15)){
					this.$http.toast("字数限制2~15");
					return;
				}
				// this.$http.request({
				// 	url: this.$api.user.update,
				// 	method: 'POST',
				// 	data: {
				// 		nickname: this.dataForm.nickname,
				// 		birthday: this.dataForm.birthday,
				// 		avatar: this.dataForm.avatar
				// 	},
				// 	showLoading: true
				// }).then(res => {
				// 	if (res.code == 0) {
				// 		this.$http.toast("保存成功");
				// 		this.showEditImg = false;
				// 		setTimeout(() => {
				// 			this.navBack();
				// 		},1000*2)
				// 	}else{
				// 		this.$http.toast(res.msg);
				// 	}
				// }).catch(err => {
				// 	console.log(err)
				// })
			},
			changAcatar() {
				
				this.$http.toast("上传头像");
				this.showEditImg = true;
				this.$nextTick(() => {
					//this.$refs['upload'].$el.click()
					this.$refs['upload'].chooseImage();
				})
			},
			result: function(e) {
				// 图片上传结果
				this.dataForm.avatar = e.imgArr[0];
				this.editStatic = false;
			},
			remove: function(e) {
				//移除图片
				let index = e.index
			},
			changeDateTime(e) {
				this.$set(this.dataForm, "birthday", e.result)
			},
			getUserInfo() {
				this.loading = true;
				this.$http.request({
					url: this.$api.user.userInfo,
					method: 'POST',
				}).then(res => {
					this.loading = false;
					if (res.code == 0) {
						this.dataForm = res.data;
						this.getAddressList();
					}
				}).catch(err => {
					console.log(err);
				})
			},
			getAddressList() {
				this.$http.request({
					url: this.$api.user.address,
					method: 'POST'
				}).then(res => {
					if (res.code === 0) {
						if(res.data.list && res.data.list.length){
							this.address = res.data.list[0].user_address;
						}
					}
				})
			},
			openUrl(url) {
				uni.navigateTo({
					url
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.app {
		background-color: #FFFFFF;
	}

	.user-data {
		min-width: 100%;
		background-color: #FFFFFF;
		min-height: 100%;

		.title {
			color: #808080;
			background-color: #F7F7F7;
			padding: 0 30rpx;
			line-height: 100rpx;
			border-top: 1rpx solid #E0E0E0;
			border-bottom: 1rpx solid #E0E0E0;
			font-size: 11pt;

		}

		.box {
			color: #000000;
			font-size: 11pt;
			font-weight: 400;

			.content {

				.item {
					height: 130rpx;
					line-height: 40rpx;
					display: flex;
					align-items: center;
					margin: 0 30rpx;
					box-sizing: border-box;
					border-bottom: 1rpx solid #E0E0E0;
					font-size: 11pt;

					&:last-child {
						border-bottom: 0;
					}

					&.bankcard-username {
						border-bottom: 1rpx solid #E0E0E0 !important;
					}

					.msg {
						font-weight: 400;
						margin-right: 10rpx;
					}

					input {
						font-size: 11pt;
					}

					.uni-input-placeholder {
						color: #E0E0E0;
					}

					.btn {
						flex: 1;
						display: flex;

						.edit-avatar {
							flex: 1;
							display: flex;
							align-items: center;
							justify-content: flex-end;

							.img {
								width: 100rpx;
								height: 100rpx;
								border-radius: 50px;
							}

							.iconfont {
								margin-left: 24rpx;
								line-height: 48rpx;
								font-size: 20pt;
								width: 36rpx;
								color: #ABABAB;
							}
						}

						.edit {
							flex: 1;
							font-size: 9pt;
							text-align: end;
							color: #999999;
							font-weight: 400;
						}

						.text {
							overflow: hidden;
							text-overflow: ellipsis;
							white-space: nowrap;
							max-width: 230px;
						}
					}
				}
			}
		}
	}

	.operate {
		padding: 60rpx 40rpx;

		.btn {

			border-radius: 50rpx;
			background-color: #BC0100;
			font-size: 12pt;
		}
	}
</style>

