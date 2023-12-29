<template v-cloak>
	<view class="app">
		<view class="user-data">
			<view class="box user-basis">
				<view class="title">
					<span>基础资料</span>
				</view>
				<view class="content">
					<view class="avatar-upload">
						<view class="msg">头像</view>
						<view class="">
							<image class="img" :src="dataForm.avatar?dataForm.avatar:img_url+'images/my/mine_def_touxiang_3x.png'"
								@tap="chooseImage()"
								style="width: 120rpx;height: 120rpx;float: right;display: block;border-radius: 50%;">
							</image>
						</view>
					</view>
					<view class="item phone">
						<view class="msg">手机号:</view>
						<view>{{dataForm.mobile || '请设置你的手机号码'}}</view>
						<view class="btn">
							<view class="edit">
								<span @click="openUrl('mobile')">
									修改
								</span>
							</view>
						</view>
					</view>
					<view class="item nickname">
						<view class="msg">昵称:</view>
						<!-- <view v-if="!editNickname">{{userInfo.nickname || '请设置你的昵称'}}</view> -->
						<view class="btn">
							<input class="text" type="text" v-model="dataForm.nickname" placeholder="请设置您的昵称"
								style="width: 440rpx;" />
							<view class="edit"></view>
						</view>
					</view>
					<view class="item nickname">
						<view class="msg">姓名:</view>
						<view class="btn">
							<input class="text" type="text" v-model="dataForm.realname" placeholder="请设置您的姓名"
								style="width: 440rpx;" />
							<view class="edit"></view>
						</view>
					</view>
					<view class="item nickname">
						<view class="msg">身份证号码:</view>
						<view class="btn">
							<input class="text" type="text" v-model="dataForm.cert_no" placeholder="请设置您的身份证号码"
								style="width: 440rpx;" />
							<view class="edit"></view>
						</view>
					</view>
					<view class="item">
						<view class="msg">身份证有效期: </view>
						<view class="btn">
							<radio-group style="display: flex;" @change="radioChange">
								<label class="uni-list-cell uni-list-cell-pd">
										<radio value="0" :checked="dataForm.cert_validity_type === 0"/>
									<text>非长期有效</text>
								</label>
								<label class="uni-list-cell uni-list-cell-pd" style="margin-left: 10px;">
										<radio value="1" :checked="dataForm.cert_validity_type === 1"/>
									<text>长期有效</text>
								</label>
							</radio-group>
							
							<view class="edit"></view>
						</view>
					</view>
					<view class="item">
						<view class="msg">有效期开始日期: </view>
						<view @click="showDateTimeCB" :style="{color: dataForm.cert_begin_date ? '' : '#999999'}">
							{{dataForm.cert_begin_date || '点我设置身份证有效期开始日期'}}</view>
					</view>
					<view class="item">
						<view class="msg">有效期截止日期: </view>
						<view @click="showDateTimeCE" :style="{color: dataForm.cert_end_date ? '' : '#999999'}">
							{{dataForm.cert_end_date || '点我设置身份证有效期截止日期'}}</view>
					</view>
					<view class="item birthday">
						<view class="msg">生日: </view>
						<view @click="showDateTime" :style="{color: dataForm.birthday ? '' : '#999999'}">
							{{dataForm.birthday || '点我设置您的生日'}}</view>
					</view>
					<view class="item address">
						<view class="msg">地址: </view>
						<view class="btn">
							<view class="text" :style="{color: address ? '' : '#999999'}">{{address || '快去添加第一个地址吧'}}
							</view>
							<view class="edit">
								<span @click="openUrl('./address/list')">
									修改地址
								</span>
							</view>
						</view>
					</view>

				</view>
			</view>
		</view>
		<view class="operate">
			<button class="btn" type="warn" :style="{backgroundColor: '#FF7104' || ''}" @click="dataSubmit"
				:disabled="editStatic">确认修改</button>
		</view>
		<kps-image-cutter @ok="onok" @cancel="oncancle" :url="url" :fixed="true" :Width="300" :Height="300">

		</kps-image-cutter>
		<main-datetime ref="dateTime" :type="2" :startYear="startYear" :endYear="endYear" cancelColor="#888"
			color="#5677fc" :setDateTime="setDateTime" @confirm="changeDateTime"></main-datetime>
		<main-datetime ref="dateTimeCB" :type="2" :startYear="startYear" :endYear="endYear" cancelColor="#888"
			color="#5677fc" :setDateTime="setDateTimeCB" @confirm="changeDateTimeCB"></main-datetime>
		<main-datetime ref="dateTimeCE" :type="2" :startYear="startYear" :endYear="endYear" cancelColor="#888"
			color="#5677fc" :setDateTime="setDateTimeCE" @confirm="changeDateTimeCE"></main-datetime>
		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	import kpsImageCutter from "@/components/ksp-image-cutter/ksp-image-cutter.vue";
	export default {
		components: {
			kpsImageCutter
		},
		data() {
			return {
				url: "",
				img_url: this.$api.img_url,
				dataForm: {
					avatar: '',
					mobile: '',
					nickname: '',
					birthday: '',
					
					realname: '',
					cert_validity_type: '',
					cert_no: '',
					cert_begin_date: '',
					cert_end_date: '',
				},
				startYear: 1950,
				endYear: 2050,
				setDateTime: "",
				setDateTimeCB: "",
				setDateTimeCE: "",
				address: '',
				serverUrl: this.$api.default.upload,
				editStatic: true,
				loading: false,
				textColor: '',
			}
		},
		onLoad() {
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
			}
			this.getUserInfo();
		},
		watch: {
			dataForm: {
				deep: true,
				handler(newVal, val) {
					this.editStatic = false;
				},
			}
		},
		methods: {
			radioChange(e){
				this.$set(this.dataForm, "cert_validity_type", e.detail.value)
			},
			chooseImage() {
				uni.chooseImage({
					success: (res) => {
						// 设置url的值，显示控件						 
						this.url =res.tempFiles[0].path	
					}
				});
			},
			onok(ev) {			
				let that=this
				that.dataForm.avatar= ev.path;
				that.url = "";
				var requestData = {
					serverUrl: that.$api.default.upload,
					fileKeyName: "file",
					file:that.dataForm.avatar
				}
				uni.showLoading({
					title: "正在上传"
				})
				that.$http.uploadFile(requestData).then(function(res) {
					uni.hideLoading()
					if(res.code==0){
						that.dataForm.avatar=res.data.thumb_url
					}else{
						that.$http.toast(res.msg);
					}
				})
				
			},
			oncancle() {
				// url设置为空，隐藏控件
				this.url = "";
			},
			dataSubmit() {
				if (!(this.dataForm.nickname.length >= 2 && this.dataForm.nickname.length <= 15)) {
					this.$http.toast("昵称字数限制2~15");
					return;
				}
				this.$http.request({
					url: this.$api.user.update,
					method: 'POST',
					data: {
						nickname: this.dataForm.nickname,
						birthday: this.dataForm.birthday,
						avatar: this.dataForm.avatar,
						realname: this.dataForm.realname,
						cert_validity_type: this.dataForm.cert_validity_type,
						cert_no: this.dataForm.cert_no,
						cert_begin_date: this.dataForm.cert_begin_date,
						cert_end_date: this.dataForm.cert_end_date,
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.$http.toast("保存成功");
						this.showEditImg = false;
						setTimeout(() => {
							this.navBack();
						}, 1000 * 2)
					} else {
						this.$http.toast(res.msg);
					}
				}).catch(err => {
					console.log(err)
				})
			},
			openUrl(url) {
				uni.navigateTo({
					url
				})
			},
			changeDateTime(e) {
				this.$set(this.dataForm, "birthday", e.result)
			},
			showDateTime() {
				this.$refs.dateTime.show()
			},
			changeDateTimeCB(e) {
				this.$set(this.dataForm, "cert_begin_date", e.result)
			},
			showDateTimeCB() {
				this.$refs.dateTimeCB.show()
			},
			changeDateTimeCE(e) {
				this.$set(this.dataForm, "cert_end_date", e.result)
			},
			showDateTimeCE() {
				this.$refs.dateTimeCE.show()
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
						if (res.data.list && res.data.list.length) {
							this.address = res.data.list[0].user_address;
						}
					}
				})
			}
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

	.avatar-upload {
		height: 130rpx;
		line-height: 40rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin: 0 30rpx;
		box-sizing: border-box;
		border-bottom: 1rpx solid #E0E0E0;
		font-size: 11pt;
	}
</style>
