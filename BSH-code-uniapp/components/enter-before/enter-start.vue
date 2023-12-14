<template>
	<view class="Merchants_join_container">
		<view class="jojin_list">
			<view class="jojin_item">
				<view style="width: 35%;">
					<text style="margin: 0 5rpx;color: red;">*</text>
					<text style="color: #000;">店铺名称</text>
				</view>
				<view style="width: 64%;">
					<input type="text" v-model="params.store_name" placeholder="请输入您的店铺名称"
						style="line-height: 120rpx;display: block;height: 120rpx;border: none;text-align: right;" />
				</view>
			</view>
			<view class="jojin_item">
				<view style="width: 35%;">
					<text style="margin: 0 5rpx;color: red;">*</text>
					<text style="color: #000;">申请人姓名</text>
				</view>
				<view style="width: 64%;">
					<input type="text" v-model="params.realname" placeholder="请填写您的真实姓名"
						style="line-height: 120rpx;display: block;height: 120rpx;border: none;text-align: right;" />
				</view>
			</view>
			<view class="jojin_item">
				<view style="width: 35%;">
					<text style="margin: 0 5rpx;color: red;">*</text>
					<text style="color: #000;">选择商铺位置</text>
				</view>
				<view style="width: 55%;">
					<input type="text" value="" v-model="addresss" disabled
						style="line-height: 120rpx;display: block;height: 120rpx;border: none;text-align: right;" />
				</view>
				<view style="width: 10%;">
					<image :src="img_url+'dao_location.png'" mode="" @tap="chooseAddress"
						style="display: block;width: 60rpx;height: 60rpx;margin: 30rpx 0 0 5rpx;"></image>
				</view>
			</view>
			
			
			
			<view class="jojin_item">
				<view style="width: 35%;">
					<text style="margin: 0 5rpx;color: red;">*</text>
					<text style="color: #000;">选择省市区</text>
				</view>
				<view style="width: 64%;">
					<picker  mode="multiSelector" @change="picker"  :value="value" @columnchange="columnPicker" :range="multiArray"
					style="float: left;">	
						<view style="width: 450rpx;height: 120rpx;">
							<view class="index1_content_top_l_name" style="height: 120rpx;line-height: 120rpx;text-align: right;">{{text.length<=0?'请选择':text}}</view>
						</view>
					</picker>
				</view>
			</view>
			
			
			
			
			
			<view class="jojin_item">
				<view style="width: 35%;">
					<text style="margin: 0 5rpx;color: red;">*</text>
					<text style="color: #000;">申请人手机号</text>
				</view>
				<view style="width: 64%;">
					<input type="number" value="" v-model="params.mobile"
						style="line-height: 120rpx;display: block;height: 120rpx;border: none;text-align: right;" />
				</view>
			</view>
			<view class="jojin_item">
				<view style="width: 35%;">
					<text style="margin: 0 5rpx;color: red;">*</text>
					<text style="color: #000;">输入验证码</text>
				</view>
				<view style="width: 35%;">
					<input type="number" value="" v-model="params.captcha"
						style="line-height: 60rpx;display: block;height: 60rpx;border: none;text-align: center;background: #C0C4CC;margin-top: 30rpx;color: #000;" />
				</view>
				<view style="width: 35%;">
					<button type="default" v-if="!countDown" @tap="getCode" style="background:rgb(255, 113, 4) ;'"
						class="codeBtn">
						获取验证码
					</button>
					<button type="default" v-else style="background:rgb(255, 113, 4) ;'" class="codeBtn">
						{{countDown}}
					</button>
				</view>
			</view>
			<view class="jojin_item">
				<view style="width: 35%;">
					<text style="margin: 0 5rpx;color: red;">*</text>
					<text style="color: #000;">选择消费类型</text>
				</view>
				<view style="width: 50%;display: flex;">
					<picker :range="cats_arr1" :value="catsIndex" @change="changeCart" style="
		flex: 1;
		font-size: 15px;
		height: 60rpx;
		line-height: 60rpx;
		margin-top: 30rpx;
		text-align: center;
		background: rgb(223,223,223);
		color: #000;
	">
						{{params.store_mch_common_cat_id?cat_name:"请选择"}}
					</picker>
					<view class="block_bb1_r" :style="'border-top:'+'10px '+background+' solid'"></view>
				</view>
			</view>
		</view>
		<view class="content_footer">
			<button class="sumbit" :style="{background:background}" @tap="sumbit">下一步</button>
		</view>
	</view>
</template>
<style>
	.Merchants_join_container {
		width: 100%;
		overflow: hidden;
	}

	.jojin_list {
		width: 100%;
		overflow: hidden;
	}

	.jojin_item {
		width: 98%;
		overflow: hidden;
		margin: 10rpx auto 0;
		padding: 0 20rpx;
		line-height: 120rpx;
		background: #fff;
		box-sizing: border-box;
		display: flex;
		justify-content: space-between;
	}

	.codeBtn {
		width: 180rpx;
		height: 60rpx;
		line-height: 60rpx;
		background: rgb(83, 222, 219);
		border-radius: 3px;
		font-size: 25rpx;
		float: right;
		margin-left: 10rpx;
		color: #fff;
		margin-top: 30rpx;
	}

	.content_footer {
		width: 98%;
		margin: 5px auto;
		background: #FFFFFF;
		box-shadow: 0px 1px 1px 1px rgba(2, 58, 63, 0.16);
		border-radius: 3px;
		padding: 20px 5px 50px;
		color: #fff;
	}

	.sumbit {
		width: 90%;
		margin: 0px auto;
		height: 40px;
		border: 0px solid rgba(4, 0, 0, 0.39);
		box-shadow: 0px 1px 2px 0px rgba(4, 76, 102, 0.71);
		border-radius: 13px;
		line-height: 40px;
		color: #fff;
		font-size: 32rpx;
		font-weight: bold;
	}
</style>
<script>
	import {
		isMobile
	} from '@/utils/util.js';
	export default {
		props: {
			applyInfo: {
				type: Object,
				required: true,
			}
		},
		data() {
			return {
				img_url: this.$api.img_url,
				background: 'rgb(255, 113, 4)',
				params: {
					store_name: '', //店铺名称
					store_mch_common_cat_id: "", //行业ID
					store_longitude: '', //经度
					store_latitude: '', //纬度
					captcha: '', //短信验证码
					realname: '', //申请人姓名
					mobile: '', //申请人手机号
					store_province_id:'',//省ID
					store_city_id:"",//市id
					store_district_id:'',//区id
					store_address:'',//具体地址
				},
				cats: [], //分类列表
				cats_arr1: [], //分类列表
				catsIndex: 0,
				agreement: '',
				countDown: '',
				cat_name: '',
				addresss: '', //商铺地址
				text:'',
				value:[0,0,0],
				multiArray: [], //picker数据
				selectList:[],
				provice: "",
				city: "",
				district: "",
				proviceId: "",
				cityId: "",
				districtId: "",
			}
		},
		created() {
			setTimeout(() => {
				console.log(this.applyInfo)
				this.params.store_name = this.applyInfo.store_name
				this.params.store_mch_common_cat_id = this.applyInfo.store_mch_common_cat_id
				this.params.store_longitude = this.applyInfo.store_longitude
				this.params.store_latitude = this.applyInfo.store_latitude
				this.params.store_province_id=this.applyInfo.store_province_id
				this.params.store_city_id=this.applyInfo.store_city_id
				this.params.store_district_id=this.applyInfo.store_district_id
				this.params.realname = this.applyInfo.realname
				this.params.mobile = this.applyInfo.mobile
				this.addresss = this.applyInfo.store_address
				this.params.store_address =this.applyInfo.store_address
				this.text=this.applyInfo.province_name+this.applyInfo.city_name+this.applyInfo.district_name
			}, 1000)
			this.getCity()
		},
		methods: {
			alert(txt) { //弹窗提示
				uni.showToast({
					title: txt,
					icon: 'none'
				})
			},
			sumbit: function() {
				if (!this.params.store_name) return this.alert('请输入店铺名称')
				if (!this.params.realname) return this.alert('请填写您的真实姓名')
				if (!this.addresss) return this.alert('请选择地址')				
				if (!this.params.store_province_id) return this.alert('请选择省市区')
				if (!this.params.store_city_id) return this.alert('请选择省市区')
				if (!this.params.store_district_id) return this.alert('请选择省市区')
				if (!this.params.mobile) return this.alert('请填写您的手机号')
				if (!this.params.mobile.match(/1\d{10}/)) return this.alert('手机号错误')
				if (!this.params.captcha) return this.alert('请填写验证码')
				if (!this.params.store_mch_common_cat_id) return this.alert('请选择店铺消费类型')
				var that = this
				that.$http.request({
					url: that.$api.merchants.Fillbasic,
					data: that.params,
					method: 'post',
					showLoading: true
				}).
				then(function(res) {
					if (res.code == 0) {
						that.$emit("returnStatus", 2)
					} else {
						that.$http.toast(res.msg)
					}
				})
			},
			changeCart(e) { //下拉选择商户分类
				var index = e.detail.value
				this.params.store_mch_common_cat_id = this.cats[index]['id']
				this.cat_name = this.cats[index]['name']
			},
			chooseAddress() { ////获取地址
				var that = this
				this.chooseLocation(function(res){
					that.addresss = res.name
					that.params.store_address = res.name
					that.params.store_latitude = res.latitude
					that.params.store_longitude = res.longitude
				});
			},
			getCat: function() { //获取店铺消费类型
				var that = this
				that.$http.request({
					url: that.$api.moreShop.getcategorylist,
					data: {},
					method: 'post',
				}).
				then(function(res) {
					that.cats = res.data.list
					if (that.params.store_mch_common_cat_id.length > 0) {
						for (var i = 0; i < that.cats.length; i++) {
							if (that.params.store_mch_common_cat_id == that.cats[i].id) {
								that.cat_name = that.cats[i].name
							}
						}
					}
					var cats = res.data.list
					var cats_arr1 = []
					for (var i = 0; i < cats.length; i++) {
						cats_arr1.push(cats[i]['name'])
					}
					that.cats_arr1 = cats_arr1
					var data = {
						"detail": {
							"value": 0
						}
					}
					that.changeCart(data) //默认第一个
				})
			},
			getCode() { //获取验证码
				if (!isMobile(this.params['mobile'])) {
					this.$http.toast('请输入手机号后在获取验证码');
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
						mobile: _self.params['mobile']
					},
					method: 'POST'
				}).then((res) => {
					this.$http.toast(res.msg);
				}).catch(err => {
					console.log(err);
				})
			},
			picker(e){
				let value = e.detail.value;
				if (this.selectList.length > 0) {
					this.provice = this.selectList[value[0]].name; //获取省
					this.district = this.selectList[value[0]].children[value[1]].children[value[2]].name; //获取区
					this.city = this.selectList[value[0]].children[value[1]].name; //获取区
					this.proviceId = this.selectList[value[0]].id; //获取省id
					this.cityId = this.selectList[value[0]].children[value[1]].id; //获取市id
					this.districtId = this.selectList[value[0]].children[value[1]].children[value[2]].id; //获取区id
					this.text = this.provice + " " + this.city+" "+this.district;
					this.params.store_province_id=this.proviceId
					this.params.store_city_id=this.cityId
					this.params.store_district_id=this.districtId
				}
			},
			columnPicker(e){
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
			getCity() { //请求省市区数据
				this.$http.request({
					url: this.$api.moreShop.getCity,
					method: 'post',
					showLoading:true,
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
					this.multiArray = [provinceArr, cityArr,districtArr];
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
			toArr(object) {
				let arr = [];
				for (let i in object) {
					arr.push(object[i].name);
				}
				return arr;
			},
		
		
		
		},
		mounted() {
			this.getCat()
		},
	}
</script>
