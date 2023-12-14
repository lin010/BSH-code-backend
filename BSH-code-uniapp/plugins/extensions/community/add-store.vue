<template v-cloak>
	<view class="app">
		<view class="user-data">
			<view class="box user-basis">
				<view class="title"><span>账号资料</span></view>
				<view class="content">
					<view class="item">
						<view class="msg">登录账号*:</view>
						<!-- <view>{{dataForm.mobile}}</view> -->
						<view class="btn">
							<input class="text" type="text" v-model="dataForm.nickname" />
							<view class="edit"><span>设置登录账号</span></view>
						</view>
					</view>
					<view class="item">
						<view class="msg">登录密码*:</view>
						<view class="btn">
							<input class="text" type="text" v-model="dataForm.password" maxlength="20" />
							<view class="edit"><span>设置登录密码</span></view>
						</view>
					</view>
					<view class="item">
						<view class="msg">姓名*:</view>
						<view class="btn">
							<input class="text" type="text" v-model="dataForm.username" maxlength="10" />
							<view class="edit"><span>填写姓名</span></view>
						</view>
					</view>
					<view class="item">
						<view class="msg">手机号码*:</view>
						<view class="btn">
							<input class="text" type="text" v-model="dataForm.phone" maxlength="11" />
							<view class="edit"><span>设置手机号</span></view>
						</view>
					</view>
				</view>
			</view>
			<view class="box user-alipay">
				<view class="title"><span>门店信息</span></view>
				<view class="content">
					<view class="item">
						<view class="msg">门店名称*:</view>
						<view class="btn">
							<input class="text" type="text" v-model="dataForm.store_name" maxlength="20" />
							<view class="edit"><span>设置门店名称</span></view>
						</view>
					</view>
					<view class="item">
						<view class="msg">所属类型*:</view>
						<view class="btn">
							<view class="text">{{ typeArray[dataForm.store_type] }}</view>
							<view class="edit">
								<picker :range="typeArray" mode="selector" @change="typeChange">
									<span>请选择类型</span>
									<span class="tail"></span>
								</picker>
							</view>
						</view>
					</view>
					<view class="item">
						<view class="msg">所在区域*:</view>
						<view class="btn">
							<view class="text">{{ dataForm.store_region }}</view>
							<view class="edit">
								<picker :value="multiArrayValue" mode="multiSelector" @change="regionChange" @columnchange="regionColumnPicker" :range="multiArray">
									<span>请选择区域</span>
									<span class="tail"></span>
								</picker>
							</view>
						</view>
					</view>
					<view class="item">
						<view class="msg">详细地址*:</view>
						<view class="btn">
							<input class="text" type="text" v-model="dataForm.store_addr_detail" maxlength="11" />
							<view class="edit"><span>请填写详细地址</span></view>
						</view>
					</view>
					<view class="item">
						<view class="msg">营业时间*:</view>
						<view class="btn">
							<view class="text">{{ dataForm.store_business_hours }}</view>
							<view class="edit">
								<picker mode="time" :value="dataForm.store_business_hours" @change="timeChange" data-key="store_business_hours">
									<span>选择营业时间</span>
									<span class="tail"></span>
								</picker>
							</view>
						</view>
					</view>
					<view class="item">
						<view class="msg">结束时间*:</view>
						<view class="btn">
							<view class="text">{{ dataForm.store_end_hours }}</view>
							<view class="edit">
								<picker mode="time" :value="dataForm.store_end_hours" @change="timeChange" data-key="store_end_hours">
									<span>选择结束时间</span>
									<span class="tail"></span>
								</picker>
							</view>
						</view>
					</view>
					<view class="item">
						<view class="msg">门店logo*:</view>
						<view class="btn">
							<view class="edit">
								<view class="upload">
									<view class="upload-img flex-col flex-x-center flex-y-center" @click="">
										<view class="iconfont icon-xiangji"></view>
										<view>上传logo</view>
										<view class="hint">建议尺寸：200*200</view>
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
										:style="{opacity: dataForm.store_logo ? 1 : 0}"
									></com-upload>
								</view>
							</view>
						</view>
					</view>
					<view class="item">
						<view class="msg">门店banner*:</view>
						<view class="btn">
							<view class="edit">
								<view class="upload">
									<view class="upload-img flex-col flex-x-center flex-y-center" @click="" style="width: 248rpx;">
										<view class="iconfont icon-xiangji"></view>
										<view>上传banner</view>
										<view class="hint">建议尺寸：410*150</view>
									</view>
									<com-upload
										key="banner"
										diy-key="store_banner"
										:serverUrl="serverUrl"
										:limit="1"
										@complete="result"
										@remove="remove"
										:width="248"
										:height="170"
										style="position: absolute;"
										:style="{opacity: dataForm.store_banner ? 1 : 0}"
									></com-upload>
								</view>
							</view>
						</view>
					</view>
					<view class="item flex-col">
						<view class="msg">门店简介*:</view>
						<view class="btn">
							<view class="edit">
								<textarea class="textarea margin-top-20px" v-model="dataForm.store_profile" placeholder="填写100字以上的门店简介（*必填）" maxlength="100" />
								<!-- <span>{{dataForm.store_profile}}/100</span> -->
								<span class="num">0/100</span>
							</view>
						</view>
					</view>
				</view>
			</view>
			<view class="box user-bankcard">
				<view class="title"><span>资质证件</span></view>
				<view class="content">
					<view class="item flex-col">
						<view class="msg">营业执照*:</view>
						<view class="btn">
							<view class="edit">
								<view class="upload margin-top-20px">
									<view class="upload-img flex-col flex-x-center flex-y-center" style="width: 100%;height: 300rpx;">
										<view class="iconfont icon-xiangji"></view>
										<view>上传营业执照</view>
									</view>
									<com-upload
										key="license"
										diy-key="license"
										:serverUrl="serverUrl"
										:limit="1"
										@complete="result"
										@remove="remove"
										diy-style="width: 100%;height: 300rpx;"
										style="position: absolute;width: 100%;"
										:style="{opacity: dataForm.license ? 1 : 0}"
									></com-upload>
								</view>
							</view>
						</view>
					</view>
					<view class="item flex-col">
						<view class="msg">法人身份证正面*:</view>
						<view class="btn">
							<view class="edit">
								<view class="upload margin-top-20px">
									<view class="upload-img flex-col flex-x-center flex-y-center" style="width: 100%;height: 300rpx;">
										<view class="iconfont icon-xiangji"></view>
										<view>上传法人身份证正面</view>
									</view>
									<com-upload
										key="front_certificate"
										diy-key="front_certificate"
										:serverUrl="serverUrl"
										:limit="1"
										@complete="result"
										@remove="remove"
										diy-style="width: 100%;height: 300rpx;"
										style="position: absolute;width: 100%;"
										:style="{opacity: dataForm.front_certificate ? 1 : 0}"
									></com-upload>
								</view>
							</view>
						</view>
					</view>
					<view class="item flex-col">
						<view class="msg">法人身份证反面*:</view>
						<view class="btn">
							<view class="edit">
								<view class="upload margin-top-20px">
									<view class="upload-img flex-col flex-x-center flex-y-center" style="width: 100%;height: 300rpx;">
										<view class="iconfont icon-xiangji"></view>
										<view>上传法人身份证反面</view>
									</view>
									<com-upload
										key="verso_certificate"
										diy-key="verso_certificate"
										:serverUrl="serverUrl"
										:limit="1"
										@complete="result"
										@remove="remove"
										diy-style="width: 100%;height: 300rpx;"
										style="position: absolute;width: 100%;"
										:style="{opacity: dataForm.verso_certificate ? 1 : 0}"
									></com-upload>
								</view>
							</view>
						</view>
					</view>
					<view class="item flex-col">
						<view class="msg">其他说明：</view>
						<view class="btn">
							<view class="edit">
								<textarea class="textarea margin-top-20px" v-model="dataForm.describe" placeholder="填写你的说明备注:" maxlength="100" />
								<!-- <span>{{dataForm.describe }}/100</span> -->
							</view>
						</view>
					</view>

					<view class="item deal">
						<label class="flex-y-center" style="font-size: 9pt;">
							<radio class="deal-radio" color="#BC0100" :checked="dataForm.deal" @click="dealState()" />
							<text>阅读并同意</text>
							<text style="color:#BC0100;">《门店协议》</text>
						</label>
					</view>
				</view>
			</view>
		</view>
		<view class="operate"><button class="btn" type="warn" @click="dataSubmit">确认修改</button></view>
		<main-datetime
			ref="dateTime"
			:type="2"
			:startYear="startYear"
			:endYear="endYear"
			cancelColor="#888"
			color="#5677fc"
			:setDateTime="setDateTime"
			@confirm="changeDateTime"
		></main-datetime>

		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
export default {
	data() {
		return {
			dataForm: {
				username: '',
				password: '',
				nickname: '',
				phone: '',
				store_name: '',
				store_type: -1,
				store_region: '',
				store_addr_detail: '',
				store_business_hours: '',
				store_end_hours: '',
				store_logo: '',
				store_banner: '',
				store_profile: '',
				license: '',
				front_certificate: '',
				verso_certificate: '',
				describe: '',
				deal: false
			},
			startYear: 1980,
			endYear: 2030,
			setDateTime: '',
			address: '',
			serverUrl: this.$api.default.upload,
			showEditImg: false,
			loading: false,
			typeArray: ['自营业', '水果', '杂货', '蛋糕店'],
			// 区域选择
			multiArray: [],
			multiArrayValue: [0, 0, 0],
			multiText: ''
		};
	},
	onLoad() {
		this.getCity();
	},
	watch: {
	},
	methods: {
		dataSubmit() {},
		dealState() {
			this.dataForm.deal = !this.dataForm.deal;
		},
		timeChange(e){
			let key = e.currentTarget.dataset.key;
			this.dataForm[key] = e.detail.value;
		},
		regionChange(e) {
			let value = e.detail.value;
			if (this.selectList.length > 0) {
				this.provice = this.selectList[value[0]].name; //获取省
				this.city = this.selectList[value[0]].children[value[1]].name; //获取市
				this.district = this.selectList[value[0]].children[value[1]].children[value[2]].name; //获取区
				this.multiText = this.provice + ' ' + this.city + ' ' + this.district;
				this.proviceId = this.selectList[value[0]].id; //获取省id
				this.cityId = this.selectList[value[0]].children[value[1]].id; //获取市id
				this.districtId = this.selectList[value[0]].children[value[1]].children[value[2]].id; //获取区id
			}
			this.dataForm.store_region = this.multiText;
		},
		regionColumnPicker(e) {
			//第几列 下标从0开始
			let column = e.detail.column;
			//第几行 下标从0开始
			let value = e.detail.value;
			if (column === 0) {
				this.multiArray = [this.multiArray[0], this.toArr(this.selectList[value].children), this.toArr(this.selectList[value].children[0].children)];
				this.multiArrayValue = [value, 0, 0];
			} else if (column === 1) {
				this.multiArray = [this.multiArray[0], this.multiArray[1], this.toArr(this.selectList[this.multiArrayValue[0]].children[value].children)];
				this.multiArrayValue = [this.multiArrayValue[0], value, 0];
			}
		},
		typeChange(e) {
			let temp = JSON.parse(JSON.stringify(this.dataForm));
			temp.store_type = e.detail.value;
			this.dataForm = temp;
		},
		changAcatar() {
			// #ifndef APP-PLUS || H5
			this.$http.toast('除APP其他无法更换头像');
			return;
			// #endif
			this.$http.toast('上传头像');
			this.showEditImg = true;
			this.$nextTick(() => {
				this.$refs['upload'].$el.click();
			});
		},
		result: function(e) {
			// 图片上传结果
			this.dataForm[e.key] = e.imgArr[0];
		},
		remove: function(e) {
			//移除图片
			let index = e.index;
		},
		changeDateTime(e) {
			this.$set(this.dataForm, 'birthday', e.result);
		},
		showDateTime() {
			this.$refs.dateTime.show();
		},
		getCity() {
			//请求省市区数据
			this.$http
				.request({
					url: this.$api.user.addressInfo,
					method: 'post'
				})
				.then(res => {
					// 处理数据
					var provinceArr = [];
					var cityArr = [];
					var districtArr = [];
					for (var key in res.data) {
						//分为三个数组
						if (res.data[key].level == 'province' || res.data[key].level == 'city') {
							this.$set(res.data[key], 'children', []);
						}
						if (res.data[key].level == 'province') {
							provinceArr.push(res.data[key]);
						}
						if (res.data[key].level == 'city') {
							cityArr.push(res.data[key]);
						}
						if (res.data[key].level == 'district') {
							districtArr.push(res.data[key]);
						}
					}
					this.multiArray = [provinceArr, cityArr, districtArr];

					cityArr.forEach((item, index) => {
						districtArr.forEach((items, index) => {
							if (item.id == items.parent_id) {
								item.children.push(items);
							}
						});
					});

					provinceArr.forEach((item, index) => {
						cityArr.forEach((items, indexs) => {
							if (item.id == items.parent_id) {
								item.children.push(items);
							}
						});
					});
					this.selectList = provinceArr;

					this.multiArray = [this.toArr(this.selectList), this.toArr(this.selectList[0].children), this.toArr(this.selectList[0].children[0].children)];
				});
		},
		toArr(object) {
			let arr = [];
			for (let i in object) {
				arr.push(object[i].name);
			}
			return arr;
		}
	}
};
</script>

<style lang="scss" scoped>
.app {
	background-color: #ffffff;
}

.user-data {
	min-width: 100%;
	background-color: #ffffff;
	min-height: 100%;

	.title {
		color: #808080;
		background-color: #f7f7f7;
		padding: 0 30rpx;
		line-height: 100rpx;
		border-top: 1rpx solid #e0e0e0;
		border-bottom: 1rpx solid #e0e0e0;
		font-size: 11pt;
	}

	.box {
		color: #000000;
		font-size: 11pt;
		font-weight: 400;

		.content {
			.item {
				// min-height: 130rpx;
				padding: 41rpx 0;
				line-height: 40rpx;
				display: flex;
				margin: 0 30rpx;
				box-sizing: border-box;
				border-bottom: 1rpx solid #e0e0e0;
				font-size: 11pt;

				&:last-child {
					border-bottom: 0;
				}

				&.bankcard-username {
					border-bottom: 1rpx solid #e0e0e0 !important;
				}

				.msg {
					font-weight: 400;
					margin-right: 10rpx;
				}

				input {
					font-size: 11pt;
				}

				.uni-input-placeholder {
					color: #e0e0e0;
				}

				.btn {
					flex-grow: 1;
					display: flex;

					.edit {
						flex-grow: 1;
						font-size: 9pt;
						text-align: end;
						color: #999999;
						font-weight: 400;
						position: relative;

						span {
							position: relative;
						}
						.textarea {
							text-align: justify;
							padding: 20rpx 12rpx;
							width: 100%;
							height: 200rpx;
							background: rgba(242, 242, 242, 0.5);
							border-radius: 10rpx;
							font-size: 9pt;
						}
						.num {
							position: absolute;
							bottom: 0;
							right: 8rpx;
						}
					}

					.text {
						overflow: hidden;
						text-overflow: ellipsis;
						white-space: nowrap;
						max-width: 230px;
					}
				}
				
				.deal-radio{
					transform: scale(.7);
				}
			}
		}

		.upload {
			display: flex;
			justify-content: flex-end;
			position: relative;

			.upload-img {
				position: relative;
				background-color: #ffffff;
				width: 170rpx;
				height: 170rpx;
				color: #bfbfbf;
				border: 2rpx dotted #bfbfbf;
				// z-index: 10;

				.hint {
					position: absolute;
					bottom: -40rpx;
					right: -24rpx;
					white-space: nowrap;
					// text-align: center;
					transform: scale(0.8);
				}

				.iconfont {
					font-size: 16pt;
					margin-bottom: 30rpx;
				}
			}
		}
	}
}
.tail {
	margin-left: 32rpx;
	&::after {
		content: ' ';
		height: 10px;
		width: 10px;
		border-width: 2px 2px 0 0;
		border-color: #b2b2b2;
		border-style: solid;
		-webkit-transform: matrix(0.5, 0.5, -0.5, 0.5, 0, 0) translateY(20%);
		transform: matrix(0.5, 0.5, -0.5, 0.5, 0, 0) translateY(20%);
		position: absolute;
		top: 50%;
		margin-top: -7px;
		right: 0;
	}
}

.operate {
	padding: 60rpx 40rpx;

	.btn {
		border-radius: 50rpx;
		background-color: #bc0100;
		font-size: 12pt;
	}
}
.margin-top-20px {
	margin-top: 40rpx;
}
</style>
