<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" :title="title" :status-bar="true" background-color="#BC0100" :border="false" color="#ffffff"></com-nav-bar>
		<view class="jx-addr-box">
			<form :report-submit="true">
				<jx-list-cell :hover="false" padding="0">
					<view class="jx-line-cell jx-border-bottom" style="align-items: baseline;">
						<view class="jx-title">收货人</view>
						<view>
							<view class="jx-content-name jx-border-bottom">
								<input placeholder-class="jx-phcolor" v-model="user_name" class="jx-input" name="name" placeholder="请输入收货人姓名" maxlength="15" type="text" />
							</view>
							<view class="flex">
								<jx-tag class="jx-tag" :class="sex == '1' ? 'active' : ''" type="white" @click="tagChange('1')">先生</jx-tag>
								<jx-tag class="jx-tag" :class="sex == '2' ? 'active' : ''" type="white" @click="tagChange('2')">女士</jx-tag>
							</view>
						</view>
					</view>
				</jx-list-cell>
				<jx-list-cell :hover="false" padding="0">
					<view class="jx-line-cell jx-border-bottom">
						<view class="jx-title">电话</view>
						<input placeholder-class="jx-phcolor" v-model="mobile" class="jx-input" name="mobile" placeholder="请输入手机号码" maxlength="11" type="number" />
					</view>
				</jx-list-cell>

				<picker :value="value" mode="multiSelector" @change="picker" @columnchange="columnPicker" :range="multiArray">
					<jx-list-cell :arrow="true" padding="0">
						<view class="jx-line-cell jx-border-bottom">
							<view class="jx-title"><text class="jx-title-city-text">地址</text></view>
							<input placeholder-class="jx-phcolor" class="jx-input" disabled name="city" placeholder="选择收货地址" v-model="text" maxlength="50" type="text" />
						</view>
					</jx-list-cell>
				</picker>

				<block v-if="is_town == 1">
					<picker @change="bindPickerChange" :value="index" :range="array">
						<jx-list-cell :arrow="true" padding="0">
							<view class="jx-line-cell jx-border-bottom">
								<view class="jx-title"><text class="jx-title-city-text">选择乡镇</text></view>
								<input
									placeholder-class="jx-phcolor"
									class="jx-input"
									disabled
									name="city"
									placeholder="请选择乡镇"
									v-model="town_text"
									maxlength="50"
									type="text"
								/>
							</view>
						</jx-list-cell>
					</picker>
				</block>

				<jx-list-cell :hover="false" padding="0">
					<view class="jx-line-cell">
						<view class="jx-title">门牌号</view>
						<input placeholder-class="jx-phcolor" v-model="address_details" class="jx-input" name="address" placeholder="例：5号楼203室" maxlength="50" type="text" />
					</view>
				</jx-list-cell>

				<view v-if="changeType == 'edit'" class="delete-address" @tap="deleteAddress">删除收货地址</view>
				<!-- 提交地址 -->
				<view class="jx-addr-save"><jx-button type="danger" shape="circle" height="88rpx" @tap="saveAddress">提交</jx-button></view>
			</form>
		</view>
	</view>
</template>

<script>
export default {
	components: {
		jxButton: () => import('@/components/extend/button/button'),
		jxListView: () => import('@/components/list-view/list-view'),
		jxListCell: () => import('@/components/list-cell/list-cell'),
		jxTag: () => import('@/components/tag/tag')
	},
	data() {
		return {
			loading: false,
			title: '',
			changeType: '',
			id: -1,
			addressData: {
				user_name: '',
				mobile: '',
				address_details: '',
				address_name: '',
				address: '',
				province_id: '',
				city_id: '',
				area_id: ''
			},
			user_name: '', //用户名
			mobile: '', //电话
			address_details: '', //详细地址
			sex: '',
			selectList: '',
			multiArray: [], //picker数据
			value: [0, 0, 0],
			text: "",
			id: "", //判断是新增还是修改
			provice: "",
			city: "",
			district: "",
			proviceId: "",
			cityId: "",
			districtId: "",
			is_shake:false,
			
			index:0,
			array: [],
			town_data:'',
			town_id:0,
			town_text:'',
			is_town:0,
		};
	},
	onLoad(options) {
		this.initData(options);
		this.getCity();
	},
	methods: {
		initData(options) {
			let title = '新增地址';
			if (options.type === 'edit') {
				title = '编辑地址';
				// this.getAddressDetail(options.id);
			} else {
				setTimeout(() => {
					this.loading = false;
				}, 1 * 1000);
			}
			this.changeType = options.type;
			this.title = title;
		},
		tagChange(type){
			// console.log('标签操作');
			this.sex = type;
		},
		getAddressDetail(id){
			this.$http.request({
				// url:this.$api.user.addressDetail,
				showLoading:true,
				data:{ id }
			}).then((res)=>{
				if(res.code == 0){
					// this.user_name = res.data.name;
					// this.mobile = res.data.mobile;
					// this.text = res.data.province + res.data.city + res.data.district;
					// this.address_details = res.data.detail;
					// this.proviceId = res.data.province_id;
					// this.provice = res.data.province;
					// this.cityId = res.data.city_id;
					// this.city = res.data.city;
					// this.districtId = res.data.district_id;
					// this.district = res.data.district;
					// this.town_id = res.data.town_id;
					// this.town_text = res.data.town;
				}
			})
		},
		bindPickerChange(e) {
			this.index = e.target.value;
			this.town_text = this.town_data[this.index].name;
			this.town_id = this.town_data[this.index].id;
		},
		getDistrict(){
			this.$http.request({
				url:this.$api.district.town_list,
				data:{
					district_id:this.districtId
				}
			}).then(res => {
				if(res.code == 0){
					this.town_data = res.list;
					this.array = res.list.map(val => val.name);
				}
			})
		},
		getCity() { //请求省市区数据
			this.$http.request({
				url: this.$api.user.addressInfo,
				method: 'post',
			}).then((res) => {
				// 处理数据
				var provinceArr = [];
				var cityArr = [];
				var districtArr = [];
				for (var key in res.data) { //分为三个数组
					if (res.data[key].level == 'province' || res.data[key].level == 'city') {
						this.$set(res.data[key], 'children', [])
					}
					if (res.data[key].level == 'province') {
						provinceArr.push(res.data[key])
					}
					if (res.data[key].level == 'city') {
						cityArr.push(res.data[key])
					}
					if (res.data[key].level == 'district') {
						districtArr.push(res.data[key])
					}
				}
				this.multiArray = [provinceArr, cityArr, districtArr];
		
				cityArr.forEach((item, index) => {
					districtArr.forEach((items, index) => {
						if (item.id == items.parent_id) {
							item.children.push(items);
						}
					})
				})
		
				provinceArr.forEach((item, index) => {
					cityArr.forEach((items, indexs) => {
						if (item.id == items.parent_id) {
							item.children.push(items);
						}
					})
				})
				this.selectList = provinceArr;
				
				this.multiArray = [
					this.toArr(this.selectList),
					this.toArr(this.selectList[0].children),
					this.toArr(this.selectList[0].children[0].children)
				];
				
			})
		},
		deleteAddress(){ //删除地址
			var that = this;
			uni.showModal({
			    content: '确定要删除该地址吗?',
				confirmColor:'#BC0100',
			    success: function (res) {
			        if (res.confirm) {
						that.$http.request({
							url:that.$api.user.addressDelete,
							data:{
								id:that.id
							}
						}).then(res => {
							if(res.code == 0){
								that.$http.toast(res.msg);
								setTimeout(()=>{
									this.navBack();
								},1000)
							}
						})
					}
			    }
			});
		},
		saveAddress() { //保存地址
			if(this.is_shake){ //防抖动
				return;
			}else{
				this.is_shake = true;
			}
			
			this.$http.request({
				url: this.$api.user.addressSave,
				method: 'post',
				data: {
					id: this.id,
					name: this.userName,
					province_id: this.proviceId,
					province: this.provice,
					city_id: this.cityId,
					city: this.city,
					district_id: this.districtId,
					district: this.district,
					mobile: this.phone,
					detail: this.detailed,
					town_id:this.town_id,
					town:this.town_text,
				}
			}).then((res) => {
				if (res.code == 0) {
					this.$http.toast('添加成功');
					setTimeout(() => {
						this.navBack();
					}, 1000)
				}
			})
		},
		picker(e) {
			let value = e.detail.value;
			if (this.selectList.length > 0) {
				this.provice = this.selectList[value[0]].name; //获取省
				this.city = this.selectList[value[0]].children[value[1]].name; //获取市
				this.district = this.selectList[value[0]].children[value[1]].children[value[2]].name; //获取区
				this.text = this.provice + " " + this.city + " " + this.district;
				this.proviceId = this.selectList[value[0]].id; //获取省id
				this.cityId = this.selectList[value[0]].children[value[1]].id; //获取市id
				this.districtId = this.selectList[value[0]].children[value[1]].children[value[2]].id; //获取区id
			}
			
			this.getDistrict();//获取镇
			console.log(this.districtId,'districtId');
		},
		toArr(object) {
			let arr = [];
			for (let i in object) {
				arr.push(object[i].name);
			}
			return arr;
		},
		columnPicker(e) {
			//第几列 下标从0开始
			let column = e.detail.column;
			//第几行 下标从0开始
			let value = e.detail.value;
			if (column === 0) {
				this.multiArray = [
					this.multiArray[0],
					this.toArr(this.selectList[value].children),
					this.toArr(this.selectList[value].children[0].children)
				];
				this.value = [value, 0, 0]
			} else if (column === 1) {
				this.multiArray = [
					this.multiArray[0],
					this.multiArray[1],
					this.toArr(this.selectList[this.value[0]].children[value].children)
				];
				this.value = [this.value[0], value, 0]
			}
		},
		back(){
			this.navBack();
		}
	}
};
</script>

<style lang="scss" scoped>
.app {
	height: 100%;
	background-color: #f7f7f7;
}
.jx-addr-box {
	padding: 20rpx 0;
}

.jx-line-cell {
	width: 100%;
	padding: 24rpx 0;
	box-sizing: border-box;
	display: flex;
	align-items: center;
	margin: 0 30rpx;
	
	.jx-content-name{
		padding-bottom: 28rpx;
		margin-bottom: 16rpx;
	}
	
	.jx-tag{
		font-size: 28rpx;
		width: 143rpx;
		height: 58rpx;
		text-align: center;
		margin-right: 20rpx;
		border: 1rpx solid #D8D8D8;
		
		&.active{
			background-color: $jx-color-primary !important;
			color: #FFFFFF !important;
		}
	}
}

.jx-border-bottom{
	border-bottom: 2rpx solid #F2F2F2;
}

.jx-title {
	width: 180rpx;
	font-size: 11pt;
}

.jx-title-city-text {
	width: 180rpx;
	height: 40rpx;
	display: block;
	line-height: 46rpx;
}

.jx-input {
	width: 500rpx;
}

.jx-input-city {
	flex: 1;
	height: 40rpx;
	font-size: 11pt;
	padding-right: 30rpx;
}

.jx-phcolor {
	color: #ccc;
	font-size: 11pt;
}

.jx-cell-title {
	font-size: 11pt;
}

.jx-addr-label {
	margin-left: 70rpx;
}

.jx-label-item {
	width: 76rpx;
	height: 40rpx;
	border: 1rpx solid rgb(136, 136, 136);
	border-radius: 6rpx;
	font-size: 10pt;
	text-align: center;
	line-height: 40rpx;
	margin-right: 20rpx;
	display: inline-block;
	transform: scale(0.9);
}

.jx-label-active {
	background: #e41f19;
	border-color: #e41f19;
	color: #fff;
}

.jx-swipe-cell {
	width: 100%;
	display: flex;
	justify-content: space-between;
	align-items: center;
	background: #fff;
	padding: 10rpx 24rpx;
	border-radius: 6rpx;
	overflow: hidden;
	font-size: 11pt;
}

.jx-switch-small {
	transform: scale(0.8);
	transform-origin: 100% center;
}

/* #ifndef H5 */
.jx-switch-small .wx-switch-input {
	margin: 0 !important;
}

/* #endif */

/* #ifdef H5 */
>>> uni-switch .uni-switch-input {
	margin-right: 0 !important;
}

/* #endif */

.jx-addr-save {
	padding: 24rpx;
	margin-top: 100rpx;
}

.jx-del {
	padding: 24rpx;
}

.delete-address {
	color: #bc0100;
	background: #ffffff;
	padding: 24rpx 30rpx;
	margin-top: 40rpx;
}
</style>
