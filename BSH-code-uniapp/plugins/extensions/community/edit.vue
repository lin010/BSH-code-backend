<template>
	<view class="app">
		<image src="http://jxmall.sinbel.top/web//uploads/images/original/20200608/4435d1fa5753cad4d44f2be3fb8cb511.jpg" mode="aspectFill" class="jx-bg"></image>
		<view class="head">
			<view class="avatar flex-col flex-y-center">
				<image :src="'http://m.imeitou.com/uploads/allimg/2019022710/tje4m25ynyx.jpg'" class="avatar-img"></image>
				<view class="user-id">ID: 02043</view>
				<view class="state">
					<span>营业中</span>
					<switch checked color="#DD8281" @change="storeStateChange" style="transform: scale(0.4);" />
				</view>
			</view>
		</view>
		<view class="info">
			<view class="info-title flex-x-between">
				<view class="title-name">个人资料</view>
				<view class="edit font-size-28rpx" @click="showEdit(1)">编辑</view>
			</view>
			<view class="line associated-user">
				<view class="marking font-size-24rpx">关联用户</view>
				<view class="text font-size-28rpx">{{ '小可爱' }}</view>
			</view>
			<view class="line colonel-name">
				<view class="marking font-size-24rpx">团长名称</view>
				<view class="text font-size-28rpx">{{ '武江区茗鼎茶行武江区茗鼎茶行' }}</view>
			</view>
			<view class="line phone">
				<view class="marking font-size-24rpx">手机号码</view>
				<view class="text font-size-28rpx">{{ '15015756796' }}</view>
			</view>
			<view class="line community-name">
				<view class="marking font-size-24rpx">社区名称</view>
				<view class="text font-size-28rpx">太阳城芙蓉湾</view>
			</view>
		</view>
		<!-- <com-modal :show="true" :custom="true" width="90%" padding="0" v-if="!!editForm" @cancel="showEdit(-1)"> -->
		<com-modal :show="true" :custom="true" width="90%" padding="0" v-if="!!editForm">
			<view class="form">
				<view class="form-head flex-x-center">
					<view class="form-title">修改个人资料</view>
					<view class="form-cancel" @click="showEdit(-1)">
						<view class="iconfont icon-guanbi"></view>
					</view>
				</view>
				<view class="form-body">
					<view class="form-lable">
						<view class="marking font-size-24rpx">团长名称</view>
						<input class="font-size-28rpx" type="text" maxlength="12" value="" placeholder="请输入团长名称" />
					</view>
					<view class="form-lable">
						<view class="marking font-size-24rpx">手机号码</view>
						<input class="font-size-28rpx" type="number" maxlength="13" value="" placeholder="请输入手机号码" />
					</view>
					<view class="form-lable">
						<view class="marking font-size-24rpx">群分享二维码(点击修改)</view>
						<view class="upload flex">
							<view class="upload-img flex-col flex-x-center flex-y-center">
								<view class="iconfont icon-xiangji"></view>
								<view>上传图片</view>
							</view>
							<com-upload
								key="logo"
								diy-key="store_logo"
								:serverUrl="serverUrl"
								:limit="1"
								@complete="result"
								@remove="remove"
								:width="170"
								:height="170"
								style="position: absolute;"
								:style="{ opacity: dataForm.store_logo ? 1 : 0 }"
							></com-upload>
						</view>
					</view>
				</view>
				<view class="btn submit">确认修改</view>
			</view>
		</com-modal>
	</view>
</template>

<script>
export default {
	data() {
		return {
			serverUrl: this.$api.default.upload,
			dataForm: {},
			editForm: null
		};
	},
	onLoad() {},
	methods: {
		showEdit(id){
			id == -1 ? this.editForm = null : this.editForm = JSON.parse(JSON.stringify(this.dataForm));
		},
		storeStateChange(e) {
			console.log(e.detail.value ? '营业中' : '休息中');
		},
		result(e) {
			// 图片上传结果
			this.dataForm[e.key] = e.imgArr[0];
		},
		remove(e) {
			//移除图片
			let index = e.index;
		},
	}
};
</script>

<style lang="scss" scoped>
.app {
	height: 100%;
	background-color: #f7f7f7;
	position: relative;
}
.head {
	padding-top: 30rpx;

	.avatar {
		color: #ffffff;
		position: relative;
		.avatar-img {
			width: 110rpx;
			height: 110rpx;
			border-radius: 50%;
			margin-bottom: 10rpx;
		}
		.user-id {
			font-size: 26rpx;
			line-height: 60rpx;
		}
		.state {
			position: absolute;
			right: 0;
			top: -20rpx;
			font-size: 23rpx;
		}
	}
}
.info {
	position: relative;
	margin: 0 30rpx;
	background-color: #ffffff;
	border-radius: 15rpx;
	line-height: 60rpx;
	padding: 60rpx 0;
	.info-title {
		padding: 8rpx 39rpx;
		font-size: 30rpx;
		color: #000000;
		.title-name {
			font-weight: bold;
		}
		.edit {
			color: #999999;
			font-weight: 400;
		}
	}
	.line {
		padding: 12rpx 39rpx;
		border-top: 1rpx solid #f2f2f2;
	}
}
.form {
	padding: 0 30rpx 46rpx;
	line-height: 60rpx;
	color: #000000;
	.form-head {
		padding: 24rpx 0;
		position: relative;
		
		.form-title {
			font-size: 31rpx;
			font-weight: bold;
		}
		
		.form-cancel{
			position: absolute;
			top: 16rpx;
			right: -12rpx;
			width:35rpx;
			height:35rpx;
			border-radius: 50%;
			background-color: #DCDADD;
			color: #FFFFFF;
			text-align: center;
			line-height: 35rpx;
			.iconfont{
				font-size: 12px;
			}
		}
	}
	.form-body {
		margin-bottom: 40rpx;

		.form-lable {
			border-top: 1rpx solid #f2f2f2;
			padding: 36rpx 0;
		}
	}
	.btn {
		text-align: center;
		line-height: 90rpx;
		border-radius: 45rpx;
		background-color: $jx-color-primary;
		color: #ffffff;
	}
}
.upload {
	position: relative;

	.upload-img {
		position: relative;
		background-color: #ffffff;
		width: 170rpx;
		height: 170rpx;
		color: #bfbfbf;
		border: 2rpx dotted #bfbfbf;

		.iconfont {
			font-size: 16pt;
		}
	}
}
.marking {
	color: $jx-color-primary;
}
.text {
	color: #000000;
}
.font-size-28rpx {
	font-size: 28rpx;
}
.font-size-24rpx {
	font-size: 24rpx;
}
.jx-bg {
	position: absolute;
	top: 0;
	width: 100%;
	height: 280rpx;
}
</style>
