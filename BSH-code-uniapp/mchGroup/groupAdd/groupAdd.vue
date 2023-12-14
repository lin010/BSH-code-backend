<template>
	<view class="content" v-if="!isVerifying">
		<view class="enter_before">
			<view class="Merchants_join_container">
				<view class="jojin_list">
					<view class="jojin_item">
						<view style="width: 35%;">
							<text style="margin: 0 5rpx;color: red;">*</text>
							<text style="color: #000;">门店名称</text>
						</view>
						<view style="width: 64%;">
							<input type="text" v-model="form.name" placeholder="请填写"
								style="line-height: 120rpx;display: block;height: 120rpx;border: none;text-align: right;" />
						</view>
					</view>
					<view class="jojin_item">
						<view style="width: 35%;">
							<text style="margin: 0 5rpx;color: red;">*</text>
							<text style="color: #000;">负责人姓名</text>
						</view>
						<view style="width: 64%;">
							<input type="text" v-model="form.realname" placeholder="请填写"
								style="line-height: 120rpx;display: block;height: 120rpx;border: none;text-align: right;" />
						</view>
					</view>
					<view class="jojin_item">
						<view style="width: 35%;">
							<text style="margin: 0 5rpx;color: red;">*</text>
							<text style="color: #000;">门店位置</text>
						</view>
						<view style="width: 55%;">
							<input type="text" value="" v-model="form.address" disabled
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
							<text style="color: #000;">负责人手机号</text>
						</view>
						<view style="width: 64%;">
							<input type="number" value="" v-model="form.mobile"  placeholder="请填写"
								style="line-height: 120rpx;display: block;height: 120rpx;border: none;text-align: right;" />
						</view>
					</view>
					<view class="jojin_item">
						<view style="width: 35%;">
							<text style="margin: 0 5rpx;color: red;">*</text>
							<text style="color: #000;">输入验证码</text>
						</view>
						<view style="width: 35%;">
							<input type="number" value="" v-model="form.captcha"
								style="line-height: 60rpx;display: block;height: 60rpx;border: none;text-align: center;background: #eaeaea;margin-top: 30rpx;color: #000;" />
						</view>
						<view style="width: 35%;">
							<button type="default" v-if="!countDown" @tap="getCode" style="background:#eee;color:#666;" class="codeBtn">
								获取验证码
							</button>
							<button type="default" v-else style="background:#eee;color:#666;" class="codeBtn">
								{{countDown}}
							</button>
						</view>
					</view>
					
				</view>
			</view>
		
			<view class="join_container">
				<view class="jojin_list">
					<view class="jojin_item">
						<view style="width: 35%;">
							<text style="margin: 0 5rpx;color: red;">*</text>
							<text style="color: #000;">营业执照名称</text>
						</view>
						<view style="width: 64%;">
							<input type="text" v-model="form.license_name" placeholder="请填写"
								style="line-height: 120rpx;display: block;height: 120rpx;border: none;text-align: right;" />
						</view>
					</view>
					<view class="jojin_item">
						<view style="width: 35%;line-height: 180rpx;">
							<text style="margin: 0 5rpx;color: red;">*</text>
							<text style="color: #000;">营业执照图片</text>
						</view>
						<view style="width: 64%;">
							<image :src="form.license_pic != '' ? form.license_pic : (plugins_img_url + '/check_created.png')" @tap="uploadImg" data-id="0" style="display: block;width: 250rpx;height: 150rpx;float: right;margin: 20rpx 0;"></image>
						</view>
					</view>
					<view class="jojin_item">
						<view style="width: 35%;">
							<text style="margin: 0 5rpx;color: red;">*</text>
							<text style="color: #000;">店铺折扣</text>
						</view>
						<view style="width: 64%;display:flex;justify-content: flex-end;">
							<input type="digit" min="0" v-model="form.zk" placeholder="请输入"
								style="border-bottom:1px solid #ddd;width:100rpx;line-height: 120rpx;display: block;height: 120rpx;border: none;text-align: center" />
							 <text style="color:gray;" v-if="form.zk != ''">折</text>
						</view>
					</view>
				</view>
				<view style="text-align: center;background:white;margin-top:10rpx;padding:50rpx 0;">
					<button @click="doSubmit" type="default" style="background:rgb(255, 113, 4) ;width:80%;display:inline-block;color:white;border-radius: 100rpx;">
						点击保存
					</button>
				</view>
			</view>
		
		</view>
		
	</view>
	<view class="results-app" v-else>
		<view class="message">
			<image :src="img_url+'/review.png'" mode=""></image>
			<text style="color:#666">管理人审核中</text>
			<text style="color:gray;">24小时内通过审核，请耐心等候</text>
		</view>
	</view>
</template>

<script>
	import {
		isMobile
	} from '@/utils/util.js';
	import {isEmpty} from '../../common/validate.js'
	export default {
		components: {

		},
		data() {
			return {
				isVerifying: false,
				img_url: this.$api.img_url,
				plugins_img_url:this.$api.plugins_img_url,
				text:'',
				value:[0,0,0],
				multiArray: [], //picker数据
				selectList:[],
				form: {
					name: '',
					realname: '',
					address: '',
					latitude: '',
					longitude: '',
					provice: '',
					provice_id: '',
					district: '',
					district_id: '',
					city: '',
					city_id: '',
					mobile: '',
					captcha: '',
					license_name: '',
					license_pic: '',
					zk: ''
				},
				countDown: 0
			};
		},
		onLoad(options) { //
			this.getCity()
			uni.setNavigationBarTitle({
				title:options.title
			})
			if(options&&options.mch_apply_id){
				this.getapplyInfo(options.mch_apply_id)
			}
		},
		methods: {
			alert(txt) { //弹窗提示
				uni.showToast({
					title: txt,
					icon: 'none'
				})
			},
			doSubmit(){			
				if (isEmpty(this.form.name)) return this.alert('请填写门店名称')
				if (isEmpty(this.form.realname)) return this.alert('请填写负责人姓名')
				if (isEmpty(this.form.latitude)) return this.alert('请设置门店位置')
				if (isEmpty(this.form.longitude)) return this.alert('请设置门店位置')
				if (isEmpty(this.form.address)) return this.alert('请设置门店位置')
				if (isEmpty(this.form.provice_id)) return this.alert('请选择省市区')
				if (isEmpty(this.form.mobile)) return this.alert('请填写负责人手机号')
				if (isEmpty(this.form.captcha)) return this.alert('请填写验证码')
				if (isEmpty(this.form.license_name)) return this.alert('请填写营业执照名称')
				if (isEmpty(this.form.license_pic)) return this.alert('请上传营业执照图片')
				if (isEmpty(this.form.zk)) return this.alert('请填写店铺折扣')
				
				let that = this;
				this.$http.request({
					url: this.$api.merchants.groupAddItem,
					data:this.form,
					method: 'post',
					showLoading: true
				}).then(function(res) {
					if (res.code == 0) {
						that.isVerifying = true;
					} else {
						that.$http.toast(res.msg)
					}
				})
			},
			chooseAddress() { ////获取地址
				var that = this;
				that.chooseLocation(function(res){
					that.form.address = res.name;
					that.form.latitude = res.latitude;
					that.form.longitude = res.longitude;
				});
			},
			picker(e){
				let value = e.detail.value;
				if (this.selectList.length > 0) {
					this.form.provice = this.selectList[value[0]].name; //获取省
					this.form.district = this.selectList[value[0]].children[value[1]].children[value[2]].name; //获取区
					this.form.city = this.selectList[value[0]].children[value[1]].name; //获取区
					this.form.provice_id = this.selectList[value[0]].id; //获取省id
					this.form.city_id = this.selectList[value[0]].children[value[1]].id; //获取市id
					this.form.district_id = this.selectList[value[0]].children[value[1]].children[value[2]].id; //获取区id
					this.text = this.form.provice + " " + this.form.city+" "+this.form.district;
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
			getCode() { //获取验证码
				if (!isMobile(this.form['mobile'])) {
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
						mobile: _self.form['mobile']
					},
					method: 'POST'
				}).then((res) => {
					this.$http.toast(res.msg);
				}).catch(err => {
					console.log(err);
				})
			},
			uploadImg(e) { //上传图片
				var id = e.currentTarget.dataset.id
				var that = this
				uni.chooseImage({
					count: 1,
					success: function(res) {
						var file = res.tempFiles[0].path
						var requestData = {
							serverUrl: that.$api.default.upload+'&width=6000&height=6000&type=1',
							fileKeyName: "file",
							file: file,
						}
						uni.showLoading({
							title: "正在上传"
						})
						that.$http.uploadFile(requestData).then(function(res) {						
							uni.hideLoading()
							if(res.code==0){
								var url = res.data.url
								that.form.license_pic = url;
								that.$forceUpdate()
							}else{
								that.$http.toast(res.msg)
							}
						})
					}
				})
			},
			getapplyInfo(mch_apply_id){
				this.$http.request({
					url: this.$api.merchants.getapplyInfo,
					method: 'POST',
					data: {
						mch_apply_id:mch_apply_id
					},
					showLoading: true
				}).then(res => {
					if(res.code==0){
						let detail=res.data
						this.form.name=detail.store_name
						this.form.realname=detail.realname
						this.form.address=detail.store_address
						this.form.latitude=detail.store_latitude
						this.form.longitude=detail.store_longitude
						this.text=detail.province_name+detail.city_name+detail.district_name
						this.form.provice_id=detail.store_province_id
						this.form.city_id=detail.store_city_id
						this.form.district_id=detail.store_district_id
						this.form.mobile=detail.bind_mobile
						this.form.license_name=detail.license_name
						this.form.license_pic=detail.license_pic
						this.form.zk=detail.settle_discount
					}else{
						this.$http.toast(res.msg);
					}
				});
			}
		
		}
	}
</script>

<style>
.page{background-color: #e9f5f6;}
.content_top{
	width: 98%;
	margin: 5px auto;
	background: #FFFFFF;
	box-shadow: 0px 1px 1px 1px rgba(2, 58, 63, 0.16);
	border-radius: 3px;
	padding: 12px 5px 5px;
}
.content_top_body{position: relative;}
.content_top_banner{
	width: 100%;
	height: 124px;
	border-radius: 7px;
}
.content_top_body_bottom{display: flex;justify-content: space-between;padding: 15px 25px 0px;}
.content_top_body_bottom_block{width: 15%;text-align: center;}
.content_top_body_bottom_block_t{
	width: 26px;
	height: 26px;
	font-weight: bold;
	color: #fff;
	text-align: center;
	line-height: 26px;
	margin: 0px auto;
	border-radius: 3px;

}
.content_top_body_bottom_line{
	width: 67px;
	height: 1px;
    margin-top: 13px;
}
.content_top_body_bottom_block_b{
	font-size: 11px;
	font-weight: bold;
	color: #000000;
}
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
	@import url("../../plugins/font-icon/iconfont1.css");
	.join_container{width: 100%;overflow: hidden;}
	.jojin_list {width: 100%;overflow: hidden;}
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
	.content_footer {
		width: 98%;
		margin: 5px auto;
		background: #FFFFFF;
		box-shadow: 0px 1px 1px 1px rgba(2, 58, 63, 0.16);
		border-radius: 3px;
		padding: 20px 5px 50px;
	}
	
	.sumbit {
		width: 40%;
		margin: 15rpx auto;
		height: 80rpx;
		border: 0px solid rgba(4, 0, 0, 0.39);
		box-shadow: 0px 1px 2px 0px rgba(4, 76, 102, 0.71);
		border-radius: 30px;
		line-height: 80rpx;
		color: #fff;
		font-size: 14px;
		font-weight: bold;
	}
	.content_footer_top {
		font-size: 14px;
		display: flex;
		color: #6B6B6B;
		padding: 0px 0px 10px 15px;
	}
	.results-app{width: 100%;overflow: hidden;}
		.message{width: 100%;height: 550rpx;position: absolute;top: 0;right: 0;left: 0;bottom: 0;margin: auto;}
		.message image{width: 200rpx;height: 200rpx;display: block;margin: 50rpx auto 0rpx;}
		.message text{display: block;width: 100%;overflow: hidden;text-align: center;font-size: 28rpx;color: #000;margin: 5rpx 0;}
		.message button{width: 280rpx;height: 60rpx;line-height: 60rpx;font-size: 28rpx;margin: 40rpx auto;background:rgb(255, 113, 4) ;color: #fff;}
	
</style>
