<template>
	<view class="tui-addr-box">
		<form :report-submit="true">
			<tui-list-cell :hover="false" padding="0">
				<view class="tui-line-cell">
					<view class="tui-title">收货人</view>
					<input placeholder-class="tui-phcolor" v-model="userName" class="tui-input" name="name" placeholder="请输入收货人姓名"
					 maxlength="15" type="text" @focus="check(1)" style="box-sizing: border-box;padding-left: 10rpx;background: rgb(242, 242, 242);height: 80rpx;"/>
					 <image :src="img_url+'delete_error.png'" mode="" style="width: 30rpx;height: 30rpx;
					 display: block;position: absolute;right: 50rpx;top: 50rpx;" @click.stop="deleteint('userName')"
					 v-if="userName.length>0&&showclear.userNameSHOw"></image>
				</view>
			</tui-list-cell>
			<tui-list-cell :hover="false" padding="0">
				<view class="tui-line-cell">
					<view class="tui-title">手机号码</view>
					<input placeholder-class="tui-phcolor" v-model="phone" class="tui-input" name="mobile" placeholder="请输入收货人手机号码"
					 maxlength="11" type="number"   @focus="check(2)" style="box-sizing: border-box;padding-left: 10rpx;background: rgb(242, 242, 242);height: 80rpx;"/>
					 <image :src="img_url+'delete_error.png'" mode="" style="width: 30rpx;height: 30rpx;
					 display: block;position: absolute;right: 50rpx;top: 50rpx;" @click.stop="deleteint('phone')"
					 v-if="phone.length>0&&showclear.numberSHOw"></image>
				</view>
			</tui-list-cell>

			<picker :value="value" mode="multiSelector" @change="picker" @columnchange="columnPicker" :range="multiArray">
				<tui-list-cell :arrow="true" padding="0">
					<view class="tui-line-cell">
						<view class="tui-title"><text class="tui-title-city-text">所在城市</text></view>
						<input placeholder-class="tui-phcolor" class="tui-input" disabled name="city" placeholder="请选择城市" v-model="text"
						 maxlength="50" type="text"  style="box-sizing: border-box;padding-left: 10rpx;background: rgb(242, 242, 242);height: 80rpx;"/>
					</view>
				</tui-list-cell>
			</picker>

			<!-- <block v-if="is_town == 1">
				<picker @change="bindPickerChange" :value="index" :range="array">
					<tui-list-cell :arrow="true" padding="0">
						<view class="tui-line-cell">
							<view class="tui-title"><text class="tui-title-city-text">选择乡镇</text></view>
							<input placeholder-class="tui-phcolor" class="tui-input" disabled name="city" placeholder="请选择乡镇" v-model="town_text"
							 maxlength="50" type="text" />
						</view>
					</tui-list-cell>
				</picker>
			</block> -->

			<tui-list-cell :hover="false" padding="0">
				<view class="tui-line-cell">
					<view class="tui-title" style="margin-bottom: 65rpx;">收货地址</view>
					<textarea placeholder-class="tui-phcolor" v-model="detailed" class="tui-input" name="address" placeholder="请输入详细的收货地址"
					 type="text" style="height: 120rpx;width: 450rpx;
					 padding: 5rpx 60rpx 0 5rpx;box-sizing: border-box;background: rgb(242, 242, 242)" 
					 @focus="check(3)"/>
					 
					 <image :src="plugins_img_url+'/arrder_loc.png'" mode="" style="width: 50rpx;height: 50rpx;
					 display: block;position: absolute;right: 30rpx;top: 60rpx;" @click.stop="chooseAddress"
					 ></image>
					 
					 <image :src="img_url+'delete_error.png'" mode="" style="width: 30rpx;height: 30rpx;
					 display: block;position: absolute;right: 105rpx;top: 70rpx;" @click.stop="deleteint('detailed')"
					 v-if="detailed.length>0&&showclear.detailedSHOw"></image>
				</view>
			</tui-list-cell>
			<!-- 默认地址 -->
			<tui-list-cell :hover="false" padding="0">
				<view class="tui-swipe-cell">
					<view>设为默认地址</view>
					<switch :checked='is_default == 1' :color="textColor" class="tui-switch-small" @change="switchChange" />
				</view>
			</tui-list-cell>
			<!-- 默认地址 -->
			<view v-if="id != 0" class="delete-address" :style="{color:'#FF7104'}" @tap="deleteAddress">
				删除收货地址
			</view>
			<!-- 保存收货地址 -->
			<view class="tui-addr-save">
				<view class="save-btn" :style="{background:'#FF7104'}" @click="saveAddress()">保存收货地址</view>
				<!-- <tui-button type="danger" shape='circle' height="88rpx">保存收货地址</tui-button> -->
			</view>
		</form>
	</view>
</template>

<script>
	import tuiButton from "@/components/extend/button/button"
	import tuiListView from "@/components/list-view/list-view"
	import tuiListCell from "@/components/list-cell/list-cell"
	export default {
		components: {
			tuiButton,
			tuiListView,
			tuiListCell
		},
		data() {
			return {
				lists: ["公司", "家", "学校", "其他"],
				plugins_img_url: this.$api.plugins_img_url,
				img_url: this.$api.img_url,
				userName: '', //用户名
				phone: '', //电话
				detailed: '', //详细地址
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
				is_default: 0, //是否设置为默认地址,
				is_shake: false,

				index: 0,
				array: [],
				town_data: '',
				town_id: 0,
				town_text: '',
				is_town: 0, //判断是否显示乡镇

				mall_config: '',
				textColor:'#bc0100',
				
				form:'',
				
				showclear:{
					userNameSHOw:false,
					numberSHOw:false,
					detailedSHOw:false,
				},
			}
		},
		onLoad(options) {
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
			}
			
			this.mall_config = JSON.parse(uni.getStorageSync('mall_config'));
			this.is_town = this.mall_config.mall_setting.setting.is_town;

			this.id = options.id;
			this.type=options.type
			this.form = options.form;
			this.getCity();
			if (this.id != 0) {
				this.detailAddress();
			}
			if(options.type==0){
				uni.setNavigationBarTitle({
					title:'编辑地址'
				})
			}else{
				uni.setNavigationBarTitle({
					title:'新增地址'
				})
			}
		},
		methods: {
			// bindPickerChange(e) {
			// 	this.index = e.target.value;
			// 	this.town_text = this.town_data[this.index].name;
			// 	this.town_id = this.town_data[this.index].id;
			// },
			check(index){
				if(index==1){
					this.showclear.userNameSHOw=true
					this.showclear.numberSHOw=false
					this.showclear.detailedSHOw=false
				}
				if(index==2){
					this.showclear.userNameSHOw=false
					this.showclear.numberSHOw=true
					this.showclear.detailedSHOw=false
				}
				if(index==3){
					this.showclear.userNameSHOw=false
					this.showclear.numberSHOw=false
					this.showclear.detailedSHOw=true
				}
			},
			chooseAddress(){
				var that = this;
				this.chooseLocation(function(res){
					console.log(res)
					that.detailed = res.name
				});
			},
			switchChange(e) { //切换是否是默认地址
				if (e.detail.value) {
					this.is_default = 1;
				} else {
					this.is_default = 0;
				}
			},
			deleteint(item){
				if(item=='userName'){
					this.userName=''
				}
				if(item=='phone'){ 
					this.phone=''
				}
				if(item=='detailed'){ 
					this.detailed=''
				}
			},
			
			getCity() { //请求省市区数据
				uni.showLoading({
					title: '加载中'
				});
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
					setTimeout(function () {
					    uni.hideLoading();
					}, 1500);

				})
			},
			deleteAddress() { //删除地址
				var that = this;
				uni.showModal({
					content: '确定要删除该地址吗?',
					confirmColor: this.textColor,
					success: function(res) {
						if (res.confirm) {
							that.$http.request({
								url: that.$api.user.addressDelete,
								data: {
									id: that.id
								}
							}).then(res => {
								if (res.code == 0) {
									that.$http.toast(res.msg);
									if(uni.getStorageSync('addressID')==that.id){
										uni.removeStorageSync('addressID')
									}
									setTimeout(() => {
										uni.navigateBack();
									}, 1000)
								}
							})
						}
					}
				});
			},
			saveAddress() { //保存地址
				if (this.is_shake) { //防抖动
					return;
				} else {
					this.is_shake = true;
				}
				if(this.userName.length<2){
					this.$http.toast('收货人名字最少两位');
					this.is_shake = false;
					return
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
						is_default: this.is_default,
						// town_id: this.town_id,
						// town: this.town_text,
					}
				}).then((res) => {
					if (res.code == 0) {
						if(this.type==0){
							this.$http.toast('保存成功');
						}else{
							this.$http.toast('添加成功');
						}
						setTimeout(() => {
							if(this.form == 'submit'){
								uni.redirectTo({
									url:'/pages/order/submit?addressId='+res.data.id
								})
							}else{
								this.navBack();
							}
						}, 1000)
					} else {						
						this.$http.toast(res.msg);
						this.is_shake = false;
					}
				})
			},
			detailAddress() { //地址详情接口
				this.$http.request({
					url: this.$api.user.addressDetail,
					data: {
						id: this.id
					}
				}).then((res) => {
					if (res.code == 0) {
						this.userName = res.data.name;
						this.phone = res.data.mobile;
						this.text = res.data.province + res.data.city + res.data.district;
						this.detailed = res.data.detail;
						this.proviceId = res.data.province_id;
						this.provice = res.data.province;
						this.cityId = res.data.city_id;
						this.city = res.data.city;
						this.districtId = res.data.district_id;
						this.district = res.data.district;
						this.is_default = res.data.is_default;
						// this.town_id = res.data.town_id;
						// this.town_text = res.data.town;
						this.getDistrict();
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

				// this.getDistrict(); //获取镇
				// console.log(this.districtId, 'districtId');
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
		}
	}
</script>

<style>
	uni-picker .uni-picker-action.uni-picker-action-confirm {
		color: #BC0100 !important;
	}

	.tui-addr-box {
		padding: 20rpx 0;
	}

	.tui-line-cell {
		width: 100%;
		padding: 24rpx 30rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		position: relative;
	}

	.tui-title {
		width: 180rpx;
		font-size: 11pt;
	}

	.tui-title-city-text {
		width: 180rpx;
		height: 40rpx;
		display: block;
		line-height: 46rpx;
	}

	.tui-input {
		width: 450rpx;
	}

	.tui-input-city {
		flex: 1;
		height: 40rpx;
		font-size: 11pt;
		padding-right: 30rpx;
	}

	.tui-phcolor {
		color: #ccc;
		font-size: 11pt;
	}

	.tui-cell-title {
		font-size: 11pt;
	}

	.tui-addr-label {
		margin-left: 70rpx;
	}

	.tui-label-item {
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

	.tui-label-active {
		background: #E41F19;
		border-color: #E41F19;
		color: #fff;
	}

	.tui-swipe-cell {
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

	.tui-switch-small {
		transform: scale(0.8);
		transform-origin: 100% center;
	}

	/* #ifndef H5 */
	.tui-switch-small .wx-switch-input {
		margin: 0 !important;
	}

	/* #endif */

	/* #ifdef H5 */
	>>>uni-switch .uni-switch-input {
		margin-right: 0 !important;
	}

	/* #endif */

	.tui-addr-save {
		padding: 24rpx;
		margin-top: 100rpx;
	}

	.tui-del {
		padding: 24rpx;
	}

	.delete-address {
		color: #BC0100;
		background: #FFFFFF;
		padding: 24rpx 30rpx;
		margin-top: 40rpx;
	}
	.save-btn{
		height: 88rpx;
		line-height: 88rpx;
		text-align: center;
		color: #FFFFFF;
	}
</style>
