<template>
	<view class="app">
		<com-nav-bar @clickLeft='back' :fixed="true" left-icon="back" title="区域代理中心" :status-bar="true" :background-color="navBg" :border="false" :color="navCol"></com-nav-bar>
		<view class="container">
			<image v-if="is_area==1" :src="bg_url" mode="aspectFill" class="jx-bg"></image>
			<!-- <view class="content" v-if="data"> -->
			<view class="content" v-if="is_area==1">
				<view class="content-head">
					<view class="user-info">
						<image :src="avatar_url" mode="aspectFill" class="acatar"></image>
						<view class="userinfo">
							<view class="username">{{ nickname }}</view>
							<view class="desc">账号ID: {{user_id}}</view>
							<!-- <view class="level">区域等级: {{level_name}}</view> -->
							<view class="level">区域等级: {{level_name}}</view>
						</view>
					</view>
				</view>
				<view class="content-bottom">
					<view class="income margin-bottom-30rpx">
						<view class="status flex">
							<view class="text-money flex flex-column-x-center">
								<view class="text">未结算</view>
								<view class="money">{{ frozen_price }}元</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">已结算</view>
								<view class="money">{{ is_price }}元</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">昨日收益</view>
								<view class="money">{{ yesterday_price }}元</view>
							</view>
							<view class="text-money flex flex-column-x-center">
								<view class="text">总收益</view>
								<view class="money">{{ total_price }}元</view>
							</view>
						</view>
					</view>
				<!-- 	<view class="team-benefits margin-bottom-30rpx">
						<view class="text-icon flex flex-column-x-center" @click="">
							<view class="icon iconfont icon-tuandui"></view>
							<view class="text">我的团队</view>
						</view>
						<view class="text-icon flex flex-column-x-center" @click="">
							<view class="icon iconfont icon-icon"></view>
							<view class="text">我的收益</view>
						</view>
						<view class="text-icon flex flex-column-x-center" @click="">
							<view class="icon iconfont icon-shouyi"></view>
							<view class="text">收益明细</view>
						</view>
					</view> -->
					<view class="activation-code-list  margin-bottom-30rpx flex flex-x-between" style="display: none;">
						<view>我的激活码</view>
						<navigator url="./interests/activation-code-list">
							<view class="click">立即查看></view>
						</navigator>
					</view>
					<view class="order">
						<view class="order-tabs flex">
							<block v-for="(item, i) in orderListTabs.length" :key="i">
								<view class="tab flex-grow-1 flex-x-center flex-y-center" :style="{color:currentTabIndex == i ?textColor:''}" @click="tabChange(i)">
									<span class="name" :style="{'border-bottom':currentTabIndex == i ?'1px solid'+textColor:''}">{{ orderListTabs[i] }}</span>
								</view>
							</block>
						</view>
						<view class="order-list" v-if="list && list.length > 0">
							<view class="item" v-for="(item, i) in list" :key="i">
								<view class="user-status">
									<image :src="item.avatar_url" mode="aspectFill" class="acatar"></image>
									<view class="name-datetime">
										<view class="name">
											{{ item.nickname }}
											<view class="id" :style="{color:textColor,border:'1px solid'+textColor}">ID:{{ item.user_id }}</view>
										</view>
										<view class="datetime">下单时间:{{ item.created_at }}</view>
									</view>
									<view class="status-text" :style="{background:textColor,border:'1px solid'+textColor}">{{ item.status }}</view>
								</view>
								<view class="info">
									<view class="mark">
										<view class="goods-name">商品名称: {{ item.goods_name }}</view>
										<view class="order-id">订单编号: {{ item.order_no }}</view>
									</view>
									<view class="money">
										<view class="order-money">商品金额: {{ item.goods_price }}元</view>
										<view class="commission" :style="{color:textColor}">佣金：{{ item.price }} 元</view>
									</view>
								</view>
							</view>
						</view>
						<view class="order-list" v-else>
							<main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore>
						</view>
					</view>
				</view>
			</view>
			
			<view class="from" v-if='is_apply==1'>
				<view class="from-item flex">
					姓名:
					<input class="from-input flex-grow-1" type="text" v-model="realname" placeholder="请输入您的真实姓名" placeholder-class="from-input-placeholder" />
				</view>
				<view class="from-item flex">
					手机号:
					<input class="from-input flex-grow-1" type="text" v-model="mobile" placeholder="请输入您的手机号码" placeholder-class="from-input-placeholder" />
				</view>
				<picker :value="multiArrayValue" mode="multiSelector" @change="picker" @columnchange="columnPicker" :range="multiArray">
					<view class="from-item flex">
						所属区域:
						<input class="from-input flex-grow-1" type="text" v-model="area_address" disabled="true" placeholder="请选择您所在的区域"
						 placeholder-class="from-input-placeholder" />
						<span class="icon-left iconfont icon-xiala"></span>
					</view>
				</picker>
				<picker @change="bindPickerChange" :value="townshipIndex" :range="townshipArrry">
					<view class="from-item flex">
						选择乡镇:
						<input class="from-input flex-grow-1" type="text" v-model="town" disabled="true" placeholder="请选择乡镇"
						 placeholder-class="from-input-placeholder" />
						<span class="icon-left iconfont icon-xiala"></span>
					</view>
				</picker>

				<view class="from-item flex">
					申请协议:
					<text class="flex-grow-1" style="background-color: #f0f0f0;height: 600rpx;border-radius: 10rpx;padding: 20rpx;margin: 0 10rpx;">
						{{protocol}}
					</text>
				</view>

				<view class="from-item flex">
					同意协议:
					<checkbox-group @change="agreeChange">
						<label style="display: flex;margin-left: 20rpx;">
							<view>
								<checkbox value="1"/>
							</view>
							<view>同意协议</view>
						</label>
					</checkbox-group>
				</view>
				<view class="submit btn flex-x-center flex-y-center" @click="applySubmit" :style="{background:textColor}"><span>确认提交</span></view>
			</view>
		</view>
		
		<com-modal :show="is_modal" @click='define' :button='button' padding='30rpx 30rpx' width="80%" :custom='false' title='申请失败' :content='reason' ></com-modal>
		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				loading: false,
				data: null,
				orderListTabs: ['全部', '待结算', '已结算', '已无效'],
				currentTabIndex: 0,
				list: [],
				page: 1,
				is_no_more: false,
				parent_agent: null,
				frozen_price: '',
				is_price: '',
				total_price: '',
				yesterday_price: '',
				level_name: '',
				nickname: '',
				avatar_url: '',
				user_id: '',
				// picker数据
				multiArray: [],
				multiArrayValue: [0, 0, 0],
				area_address: '',
				// 乡镇
				townshipIndex: 0,
				townshipArrry: [],
				is_apply: 0,
				is_area: 0,
				town: '',
				is_agree: 0,
				realname: '',
				mobile: '',
				protocol: '',//申请协议
				is_modal:false,
				button:[{
						text: "取消",
						type: "red",
						plain: true //是否空心
					}, {
						text: "重新申请",
						type: "red",
						plain: false
					}],
					reason:'',
					textColor:'#bc0100',
					bg_url:'',
					button:[],
					navBg:'',
					navCol:'',
			};
		},
		onReachBottom: function(e) {
			if(this.is_apply != 1){
				this.getList();
			}
		},
		onLoad() {
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.button = this.globalSet('btnCol');
			this.navBg = this.globalSet('navBg');
			this.navCol = this.globalSet('navCol');
			
			this.getInfo();
			this.getList();
			this.getCity();
		},
		methods: {
			define(e){
				this.is_modal = false;
				if(e.index == 0){
					this.navBack();
				}
			},
			hide(){
				this.is_modal = false;
			},
			applySubmit() {
				if (this.is_agree == 0) {
					uni.showModal({
						title: '提示',
						content: '请勾选同意协议'
					})
					return;
				}
				if (this.area_address == '' || this.town == '') {
					uni.showModal({
						title: '提示',
						content: '请选择区域地址'
					})
					return;
				}
				this.$http.request({
						url: this.$api.plugin.area.apply,
						method: 'POST',
						data: {
							realname: this.realname,
							mobile: this.mobile, //类型
							address: this.area_address + ' ' + this.town,
						}
					})
					.then(res => {
						this.loading = false;
						if (res.code == 0) {
							this.$http.toast(res.msg);
							setTimeout(()=>{
								this.navBack();
							},2000)
						} else {
							uni.showToast({
								title: res.msg
							});
						}
					});
			},
			agreeChange: function(e) {
		
				if (e.detail.value.length > 0) {
					this.is_agree = 1;
				} else {
					this.is_agree = 0;
				}
			},
			tabChange: function(index) {
				this.currentTabIndex = index;
				this.list = [];
				this.page = 1;
				this.is_no_more = false;
				this.getList();
			},
			getInfo() {
				this.loading = true;
				this.$http.request({
						url: this.$api.plugin.area.info
					}).then(res => {
						this.loading = false;
						if (res.code == 0) {
							this.parent_agent = res.data.parent_agent;
							this.frozen_price = res.data.frozen_price;
							this.is_price = res.data.is_price;
							this.total_price = res.data.total_price;
							this.yesterday_price = res.data.yesterday_price;
							this.avatar_url = res.data.avatar_url;
							this.nickname = res.data.nickname;
							this.level_name = res.data.level_name;
							this.is_area = res.data.is_area;
							this.user_id = res.data.user_id;
						}
						if (res.code == 1) {
							this.protocol = res.data.protocol
							this.is_area = res.data.is_area;
							this.is_apply = res.data.is_apply;
							if(res.data.status == 2){
								this.is_modal=true;
								this.reason = res.data.reason;
							}
						}
					});
			},
			getList() {
				this.loading = true;
				if (this.is_no_more) {
					uni.showToast({
						title: '没有更多数据'
					});
					this.loading = false;
					return;
				}
				this.$http
					.request({
						url: this.$api.plugin.area.log_list,
						data: {
							page: this.page,
							status: this.currentTabIndex //类型
						}
					})
					.then(res => {
						this.loading = false;
						if (res.code == 0) {
							if (this.page == 1) {
								this.list = res.data.list;
							} else {
								this.list.concat(res.data.list);
							}
							if (this.page < res.data.pagination.page_count) {
								this.page++;
							} else {
								this.is_no_more = true;
							}
						} else {
							uni.showToast({
								title: res.msg
							});
						}
					});
			},
			bindPickerChange(e) {
				this.index = e.target.value;
				this.town = this.town_data[this.index].name;
				this.town_id = this.town_data[this.index].id;
			},
			picker(e) {
				let value = e.detail.value;
				if (this.selectList.length > 0) {
					this.provice = this.selectList[value[0]].name; //获取省
					this.city = this.selectList[value[0]].children[value[1]].name; //获取市
					this.district = this.selectList[value[0]].children[value[1]].children[value[2]].name; //获取区
					this.area_address = this.provice + ' ' + this.city + ' ' + this.district;
					this.proviceId = this.selectList[value[0]].id; //获取省id
					this.cityId = this.selectList[value[0]].children[value[1]].id; //获取市id
					this.districtId = this.selectList[value[0]].children[value[1]].children[value[2]].id; //获取区id
				}

				this.getDistrict(); //获取镇
				// console.log(this.districtId, 'districtId');
			},
			columnPicker(e) {
				//第几列 下标从0开始
				let column = e.detail.column;
				//第几行 下标从0开始
				let value = e.detail.value;
				if (column === 0) {
					this.multiArray = [this.multiArray[0], this.toArr(this.selectList[value].children), this.toArr(this.selectList[
						value].children[0].children)];
					this.multiArrayValue = [value, 0, 0];
				} else if (column === 1) {
					this.multiArray = [this.multiArray[0], this.multiArray[1], this.toArr(this.selectList[this.multiArrayValue[0]].children[
						value].children)];
					this.multiArrayValue = [this.multiArrayValue[0], value, 0];
				}
			},
			toArr(object) {
				let arr = [];
				for (let i in object) {
					arr.push(object[i].name);
				}
				return arr;
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

						this.multiArray = [this.toArr(this.selectList), this.toArr(this.selectList[0].children), this.toArr(this.selectList[
							0].children[0].children)];
					});
			},
			getDistrict() {
				this.$http
					.request({
						url: this.$api.district.town_list,
						data: {
							district_id: this.districtId
						}
					})
					.then(res => {
						if (res.code == 0) {
							this.town_data = res.list;
							this.townshipArrry = res.list.map(val => val.name);
						}
					});
			},
			back() {
				this.navBack();
			}
		},
		filters: {
			brokerage(type, money) {
				let text = type > 1 ? '间推佣金' : '直推佣金';
				return `${text}: ${money}元`;
			}
		},
		onPullDownRefresh() {
			setTimeout(() => {
				uni.stopPullDownRefresh();
				// this.getDateList(_status);
			}, 1000);
		}
	};
</script>

<style lang="scss" scoped>
	.app {
		min-height: 100%;
		background-color: #f7f7f7;

		.container {
			color: #ffffff;
			position: relative;

			.jx-bg {
				position: absolute;
				top: 0;
				width: 100%;
				height: 402rpx;
			}

			.content {
				font-size: 0;
				position: relative;
				color: #ffffff;

				.content-head {

					// padding-bottom: 100rpx;
					.withdraw {
						position: absolute;
						right: 0;

						.icon-text {
							align-items: center;
							background-color: #ec7676;
							font-size: 9pt;
							line-height: 54rpx;
							padding: 0 20rpx;
							border-bottom-left-radius: 27rpx;
							border-top-left-radius: 27rpx;

							.icon {
								font-size: 11pt;
								line-height: 26px;

								&.icon-tixian {
									margin-left: 10rpx;
								}
							}

							.text {
								margin-left: 6rpx;
							}
						}
					}

					.user-info {
						position: relative;
						margin-bottom: 20rpx;
						border-radius: 8rpx;
						padding: 30rpx;
						display: flex;
						align-items: center;

						.acatar {
							width: 122rpx;
							height: 122rpx;
							border-radius: 50%;
							margin-right: 30rpx;
							background-color: #ffffff;
						}

						.userinfo {

							.username,
							.desc,
							.level {
								// line-height: 48rpx;
								line-height: 44rpx;
							}

							.username {
								font-weight: 400;
								font-size: 11pt;
							}

							.desc,
							.level {
								font-size: 9pt;
							}

							.iconfont {
								font-size: 10pt;
								margin-right: 8rpx;
							}
						}
					}
				}

				.content-bottom {
					color: #ffffff;
					font-size: 9pt;
					padding: 0 30rpx;

					.income {
						.status {
							font-size: 12pt;

							.text {
								font-size: 10pt;
							}
						}
					}

					.team-benefits {
						padding: 30rpx 0;
						color: #212121;
						background-color: #ffffff;
						display: flex;
						border-radius: 10rpx;
						// box-shadow: 0 8rpx 11rpx 3rpx rgba(8, 1, 3, 0.07);

						.icon {
							font-size: 18pt;
							color: #bc0100;
							line-height: 56rpx;
						}

						.text {
							font-size: 11pt;
						}
					}

					.activation-code-list {
						color: #333333;
						font-size: 11pt;
						padding: 20rpx 26rpx;
						background-color: #ffffff;
						border-radius: 10rpx;

						.click {
							font-size: 9pt;
							color: #6a6a6a;
						}
					}

					.order {
						color: #333333;
						background-color: #ffffff;
						border-radius: 15rpx;

						.order-tabs {
							border-bottom: 1rpx solid #f3f3f3;

							.tab {
								height: 100rpx;
								font-size: 11pt;
								border-right: 1rpx solid #f2f2f2;

								.name {
									position: relative;
								}

								&.active {
									color: #bc0100;

									.name {
										&::before {
											content: '';
											position: absolute;
											bottom: -8rpx;
											height: 4rpx;
											width: 100%;
											background-color: #bc0100;
										}
									}
								}

								&:last-child {
									border-right: 0;
								}
							}
						}

						.order-list {
							height: 100%;

							.item {
								padding: 20rpx;

								border-bottom: 1rpx solid #f3f3f3;

								.user-status {
									display: flex;
									align-items: center;
									position: relative;
									margin-bottom: 16rpx;

									.acatar {
										width: 100rpx;
										height: 100rpx;
										border-radius: 50%;
										margin-right: 16rpx;
									}

									.name-datetime {
										.name {
											display: flex;
											line-height: 37rpx;

											.id {
												margin-left: 12rpx;
												color: #bc0100;
												padding: 0 10rpx;
												font-size: 9pt;
												transform: scale(0.8);
												line-height: 32rpx;
												border: 1rpx solid rgba(188, 1, 0, 1);
												border-radius: 14rpx;
											}
										}

										.datetime {
											font-size: 9pt;
										}
									}

									.status-text {
										position: absolute;
										top: 0;
										right: 0;
										padding: 0 10rpx;
										background-color: #bc0100;
										border: 1rpx solid #bc0100;
										border-radius: 18rpx;
										color: #ffffff;
										font-size: 9pt;
										transform: scale(0.8);
									}
								}

								.info {
									display: flex;
									font-size: 9pt;

									.mark {
										flex: 1;

										.goods-name,
										.order-id {
											overflow: hidden;
											text-overflow: ellipsis;
											white-space: nowrap;
											max-width: 420rpx;
										}
									}

									.money {
										.commission {
											color: #bc0100;
										}
									}
								}
							}
						}
					}
				}
			}

			.from {
				margin: 20upx 30upx;
				padding: 51upx 30upx;
				border-radius: 39upx;
				background-color: #ffffff;
				color: #000000;

				.from-item {
					font-size: 13.5pt;
					margin-bottom: 68upx;
					position: relative;
				}

				.from-input {
					font-size: 10.5pt;
					height: 50upx;
					line-height: 50upx;
					border-bottom: 1upx solid #cccccc;
					margin-left: 26upx;
				}

				.from-input-placeholder {
					font-size: 10.5pt;
					color: #989696;
				}

				.icon-left {
					position: absolute;
					right: 0;
					width: 32upx;
					font-size: 15pt;
					color: #666666;
				}

				.submit {
					color: #ffffff;
					height: 78upx;
				}
			}
		}
	}

	.btn {
		font-size: 34upx;
		border-radius: 39upx;
	}

	.margin-bottom-30rpx {
		margin-bottom: 30upx;
	}

	.flex {
		display: flex;
	}

	.flex-column-x-center {
		flex: 1;
		flex-direction: column;
		align-items: center;
	}
</style>
