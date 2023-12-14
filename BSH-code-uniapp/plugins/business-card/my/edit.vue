<template>
	<view class="edit-root">
		<view class="public-header">基础资料</view>

		<view class="basis-box">
			<view class="avatar-box flex flex-x-between flex-y-center">
				<view>头像</view>
				<view class="flex flex-y-center">
					<com-upload v-if="showEditImg" ref="upload" :serverUrl="serverUrl" @complete="result" :limit="1" @remove="remove"
					 :width="120" :height="120"></com-upload>
					<image v-else class="avatar-img" :src="dataForm.avatar || img_url+'images/my/mine_def_touxiang_3x.png'" @click="changAcatar"></image>
					<text class="common-icon iconfont icon-xiala"></text>
				</view>
			</view>

			<view class="common flex flex-y-center">
				<view><text style="color: #BC0100;margin-right: 6rpx;">*</text>手机号：</view>
				<input class="common-inp" type="text" v-model="mobile" />
			</view>

			<view class="common flex flex-y-center">
				<view><text style="color: #BC0100;margin-right: 6rpx;">*</text>姓名：</view>
				<input class="common-inp" type="text" v-model="name" />
			</view>

			<block v-if="val == 'create'">
				<picker @change="bindPickerChange" :value="department_index" :range="department_array">
					<view class="avatar-box flex flex-x-between flex-y-center" style="padding: 40rpx 0;">
						<view class="flex">
							<text style="color: #BC0100;margin-right: 6rpx;">*</text>部门：
							<view v-if="department_id != '-1'">{{department_array[department_index]}}</view>
							<view v-else>请选择职位</view>
						</view>
						<view class="flex flex-y-center">
							<text class="common-icon iconfont icon-xiala"></text>
						</view>
					</view>
				</picker>
			</block>
			<block v-else>
				<view class="avatar-box flex flex-x-between flex-y-center" style="padding: 40rpx 0;">
					<view class="flex">
						<text style="color: #BC0100;margin-right: 6rpx;">*</text>部门：
						<view v-if="detail_data">{{detail_data.department.name}}</view>
					</view>
				</view>
			</block>

			<block v-if="val == 'create' && department_id != '-1'">
				<picker @change="bindPickerChange2" :value="position_index" :range="position_array_show">
					<view class="avatar-box flex flex-x-between flex-y-center" style="padding: 40rpx 0;">
						<view class="flex">
							<text style="color: #BC0100;margin-right: 6rpx;">*</text>职位：
							<view v-if="position_id != '-1'">{{position_array_show[position_index]}}</view>
							<view v-else>请选择职位</view>
						</view>
						<view class="flex flex-y-center">
							<text class="common-icon iconfont icon-xiala"></text>
						</view>
					</view>
				</picker>
			</block>

			<block v-else>
				<view v-if="detail_data" class="avatar-box flex flex-x-between flex-y-center" style="padding: 40rpx 0;">
					<view class="flex">
						<text style="color: #BC0100;margin-right: 6rpx;">*</text>职位：
						<view>{{detail_data.position.name}}</view>
					</view>
				</view>

			</block>

			<view class="common flex flex-y-center">
				<view>座机：</view>
				<input class="common-inp" type="text" v-model="landline" />
			</view>

			<view class="common flex flex-y-center">
				<view>邮箱：</view>
				<input class="common-inp" type="text" v-model="eMail" />
			</view>

			<view class="common flex flex-y-center">
				<view>公司：</view>
				<view>{{company_name}}</view>
			</view>

			<view class="common flex">
				<view>地址：</view>
				<view style="flex: 1;">{{company_address}}</view>
				<!-- <textarea v-model="company_address" placeholder="" auto-height='true'/> -->
			</view>
		</view>

		<view class="public-header">个性专栏</view>
		<view class="basis-box">
			<view class="common flex flex-y-center">
				<view>个性签名：</view>
				<input class="common-inp" type="text" v-model="introduction" />
			</view>

			<view class="avatar-box flex flex-y-center" style="padding: 40rpx 0;border-bottom: 0;">
				<view>视频简介：</view>
				<view @tap="chooseVideo" class="addVideo-box flex flex-y-center flex-x-center" v-if="video_url == ''">
					<text class="common-icon iconfont icon-plus" style="margin-left: 0;font-size: 56rpx;"></text>
				</view>

				<view class="video-box" v-else>
					<video style="width: 100%;" :src="video_url" controls></video>
					<view class="video-delete flex flex-x-center" @tap="videoDelete">
						<text class="iconfont icon-iconjian delete-icon"></text>
					</view>
				</view>

			</view>
		</view>

		<view class="public-header flex flex-x-between flex-y-center">
			<view>自定义标签</view>
			<!-- <view>8/10</view> -->
		</view>
		<view class="label flex">
			<view>我的标签：</view>
			<view class="label-list flex flex-y-center">
				<view class="label-item" v-for="(item,index) in show_label_list" :key='item.name'>
					{{item.name}}
					<view class="label-delete flex flex-x-center flex-y-center" @tap="labelDelete(index,item.id)">
						<text class="iconfont icon-iconjian" style="font-size: 24rpx;line-height: 10rpx;"></text>
					</view>
				</view>
				<view class="label-item" @tap="showPop">+增加标签</view>
			</view>
		</view>

		<com-modal :show="is_modal" padding='30rpx 30rpx' width="80%" :custom='true' @cancel='hidePop'>
			<view class="label-pop">
				<view class="title">添加标签</view>
				<view class="label-inp">
					<input type="text" v-model="label_text" :focus='is_focus' />
				</view>
				<view class="label-tips" v-if="tips != ''">*{{tips}}~</view>
				<view class="label-btn flex flex-y-center">
					<view style="margin-right: 30rpx;" @tap="hidePop">取消</view>
					<view @tap="labelConfim">确定</view>
				</view>
			</view>
		</com-modal>

		<view class="public-header">图文介绍</view>
		<view class="basis-box">
			<view class="avatar-box flex flex-x-between flex-y-center" style="padding: 40rpx 0;border-bottom: 0;">
				<view>图文介绍：</view>
				<block v-if="detail_data.images">
					<view class="flex flex-y-center" style="flex: 1;">
						<com-upload ref="upload" :value='detail_data.images' :serverUrl="serverUrl" @complete="result($event,'graphic')"
						 :limit="8" @remove="remove('graphic')" :width="120" :height="120"></com-upload>
					</view>
				</block>
				<block v-else>
					<view class="flex flex-y-center" style="flex: 1;">
						<com-upload ref="upload" :value='arr_list' :serverUrl="serverUrl" @complete="result($event,'graphic')" :limit="8" @remove="remove('graphic')"
						 :width="120" :height="120"></com-upload>
					</view>
				</block>
			</view>
		</view>

		<view class="submit-btn" @tap="sumbit">
			<view v-if="val == 'create'" :style="{background:textColor}">提交创建</view>
			<view v-else :style="{background:textColor}">提交编辑</view>
		</view>

		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	var commonData;
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				loading: false,
				mobile: '',
				name: '',
				landline: '',
				eMail: '',
				address: '',
				introduction: '',
				company_name: '',
				company_address: '',
				img_list: [],

				// 初始化的数据
				val: '', //判断是编辑还是创建
				id: '', //名片id

				// 标签
				show_label_list: [],
				delete_list: [],
				is_modal: false,
				label_text: '',
				is_focus: false,
				tips: '',

				// 上传头像
				showEditImg: false,
				serverUrl: this.$api.default.upload,
				dataForm: {
					avatar: ''
				},

				// 上传视频
				video_url: '',

				// 部门选择器
				department_array: [],
				department_index: 0,
				department_id: '-1',

				position_array: [],
				position_array_show: [],
				position_index: 0,
				position_id: '-1',
				arr: ["http://jxmall.com/web//uploads/images/original/20200729/3652c5a25148e5c0dca2422980073dd9.jpeg"],

				// 编辑接口请求回来的用户数据
				detail_data: '',
				arr_list:[],
				textColor:'',
			};
		},
		onLoad(options) {
			this.val = options.val;
			if (this.val == 'create') {
				uni.setNavigationBarTitle({
					title: '创建名片'
				});
				commonData = uni.getStorageSync('commonData');
				this.company_name = commonData.company_name;
				this.company_address = commonData.company_address;
				JSON.parse(commonData.company_img).forEach(item => {
					this.arr_list.push(item.company_img)
				})

				commonData.department_list.forEach(item => {
					this.department_array.push(item.name);
				})
				this.getPosition();
			} else {
				this.id = options.id;
				this.getEditData(options.id)
			}
			
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
		},
		methods: {
			labelDelete(index, id) { //删除标签
				if (this.show_label_list[index].id) {
					this.delete_list.push(this.show_label_list[index]);
				}
				this.show_label_list.splice(index, 1);
			},
			chooseVideo() {
				uni.chooseVideo({
					maxDuration: 60,
					count: 1,
					success: (responent) => {
						this.loading = true;
						let videoFile = responent.tempFilePath;
						this.$http.uploadFile({
							serverUrl: this.$api.default.upload,
							file: videoFile,
							fileKeyName: 'file'
						}).then(res => {
							this.loading = false;
							if (res.code == 0) {
								this.video_url = res.data.url;
							} else {
								this.$http.toast(res.msg);
							}
						})
					}
				})
			},
			videoDelete() {
				this.video_url = '';
			},
			getEditData(id) {
				this.$http.request({
					url: this.$api.plugin.business.edit,
					method: 'post',
					showLoading: true,
					data: {
						id
					}
				}).then(res => {
					if (res.code == 0) {
						this.detail_data = res.data;
						this.dataForm.avatar = res.data.head_img;
						this.mobile = res.data.mobile;
						this.name = res.data.full_name;
						this.landline = res.data.landline;
						this.eMail = res.data.email;
						this.address = res.data.address;
						this.introduction = res.data.introduction;
						this.company_name = res.data.company_name;
						this.company_address = res.data.company_address;
						this.video_url = res.data.videos;
						this.img_list.push(...res.data.images);
						commonData = res.data;
						res.data.department_list.forEach(item => {
							this.department_array.push(item.name);
						})
						res.data.tag.forEach(item => {
							this.show_label_list.push(item);
						})
					} else {
						this.$http.toast(res.msg);
					}
				})
			},
			showPop() {
				this.is_modal = true;
				this.is_focus = true;
				this.tips = '';
				this.label_text = '';
			},
			hidePop() {
				this.is_tips = '';
				this.is_modal = false;
			},
			labelConfim() { //确定添加标签
				if (this.label_text == '') {
					this.tips = '输入不可以为空';
					return;
				} else {
					var obj = {
						'name': this.label_text,
					}
					if (this.show_label_list.length == 0) {
						this.show_label_list.push(obj);
						this.is_modal = false;
					} else {
						let temp = this.show_label_list.find(item => item.name == this.label_text);
						if (temp) {
							this.tips = '不能输入相同的标签';
						} else {
							this.show_label_list.push(obj);
							this.is_modal = false;
						}
					}
				}
			},
			getPosition() {
				this.position_array = [];
				this.position_array_show = [];
				commonData.position_list.forEach(item => {
					if (item.bcpid == this.department_id) {
						this.position_array.push(item);
					}
				})
				this.position_array.forEach(item => {
					this.position_array_show.push(item.name)
				})
			},
			sumbit() { //提交创建
				var url;
				if (this.val == 'create') {
					url = this.$api.plugin.business.create;
				} else {
					url = this.$api.plugin.business.doEdit;
				}

				if (this.department_id == '-1') {
					if (this.val == 'create') {
						this.$http.toast('请选择部门');
						return;
					} else {
						this.department_id = this.detail_data.department.id;
					}
				}
				if (this.position_id == '-1') {
					if (this.val == 'create') {
						this.$http.toast('请选择职位');
						return;
					} else {
						this.position_id = this.detail_data.position.id;
					}

				};
				this.show_label_list.forEach(item => {
					if (!item.id) {
						this.delete_list.push(item);
					}
				})
				if(this.img_list.length == 0){
					this.img_list.push(...this.arr_list);
				}

				this.$http.request({
					url: url,
					method: 'post',
					showLoading: true,
					data: {
						head_img: this.dataForm.avatar,
						full_name: this.name,
						position_id: this.position_id,
						department_id: this.department_id,
						mobile: this.mobile,
						email: this.eMail,
						wechat_qrcode: '',
						address: this.address,
						landline: this.landline,
						introduction: this.introduction,
						images: this.img_list,
						videos: this.video_url,
						tag: this.delete_list,
						id: this.id,
					}
				}).then(res => {
					this.$http.toast(res.msg);
					if (res.code == 0) {
						if (this.val == 'create') {
							setTimeout(() => {
								uni.redirectTo({
									url: `/plugins/business-card/index`
								})
							}, 1000)
						} else {
							setTimeout(() => {
								uni.redirectTo({
									url: `/plugins/business-card/index`
								})
							}, 1000)
						}
					}
				})
			},
			result(e, name) {
				if (name == 'graphic') {
					if (e.status == 1) {
						this.img_list = e.imgArr;
					}
				} else {
					this.dataForm.avatar = e.imgArr[0];
				}
			},
			remove(e, name) { //移除图片
				let index = e.index;
			},
			changAcatar() {
				this.$http.toast("上传头像");
				this.showEditImg = true;
				this.$nextTick(() => {
					//this.$refs['upload'].$el.click()
					this.$refs['upload'].chooseImage();
				})
			},
			bindPickerChange(e) { //部门选择弹窗，确定按钮
				this.department_index = e.target.value;
				this.department_id = commonData.department_list[this.department_index].id;
				this.getPosition();
			},
			bindPickerChange2(e) { //职位选择弹窗，确定按钮c
				this.position_index = e.target.value;
				this.position_id = this.position_array[this.position_index].id;
			},
		}
	}
</script>

<style lang="scss" scoped>
	.public-header {
		color: #808080;
		font-size: 30rpx;
		padding: 34rpx;
	}

	.basis-box {
		background: #FFFFFF;
		padding: 0 30rpx;
		color: #000000;
		font-size: 30rpx;

		.avatar-box {
			padding: 16rpx 0;
			border-bottom: 1px solid #E0E0E0;

			.avatar-img {
				width: 104rpx;
				height: 104rpx;
				border-radius: 50%;
				display: block;
			}

			.common-icon {
				font-size: 50rpx;
				margin-left: 20rpx;
				color: #A9A9A9;
				line-height: 54rpx;
			}
		}

		.common {
			padding: 46rpx 0;
			border-bottom: 1px solid #E0E0E0;

			.common-inp {
				flex: 1;
			}

			.company_img {
				border-radius: 10rpx;
				width: 300rpx;
				height: 300rpx;
				margin-left: 20rpx;
			}
		}
	}

	.label {
		padding: 50rpx 30rpx;
		background: #FFFFFF;

		.label-list {
			flex-wrap: wrap;
			flex: 1;

			.label-item {
				padding: 4rpx 14rpx;
				background: #E49999;
				font-size: 30rpx;
				color: #FFFFFF;
				margin-right: 20rpx;
				margin-bottom: 10rpx;
				position: relative;

				.label-delete {
					position: absolute;
					top: -12rpx;
					right: -10rpx;
					background: #EB0909;
					border-radius: 50%;
					width: 28rpx;
					height: 28rpx;
				}
			}
		}
	}

	.submit-btn {
		background: #BC0100;
		border-radius: 50rpx;
		height: 90rpx;
		text-align: center;
		line-height: 90rpx;
		color: #FFFFFF;
		margin: 88rpx 30rpx;
		overflow: hidden;
	}

	//添加标签弹窗
	.label-pop {
		color: #000000;

		.title {
			font-size: 38rpx;
			font-weight: 600;
		}

		.label-inp {
			border-bottom: 1px solid #000;
			padding-bottom: 6rpx;
			margin: 40rpx 0 10rpx;
		}

		.label-tips {
			font-size: 24rpx;
			color: #BC0100;
			letter-spacing: 1px;
		}

		.label-btn {
			font-size: 32rpx;
			color: #BC0100;
			justify-content: flex-end;
			margin-top: 30rpx;
		}
	}

	.addVideo-box {
		border: 1px solid #E6E6E6;
		border-radius: 14rpx;
		width: 120rpx;
		height: 120rpx;
	}

	.video-box {
		position: relative;
		flex: 1;

		.video-delete {
			position: absolute;
			width: 36rpx;
			height: 36rpx;
			border-radius: 50%;
			background: #EB0909;
			color: #FFFFFF;
			top: -10rpx;
			right: -10rpx;
			z-index: 99;

			.delete-icon {
				font-size: 26rpx;
			}
		}
	}
</style>
