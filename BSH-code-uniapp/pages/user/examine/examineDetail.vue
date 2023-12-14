<template>
	<view class="examine-detail-app">
		<view class="examine-detail-header">
			<view v-for="(item,index) in type" :key='index' :class="selecIndex==index?'active':''"
				@click="select(index)">
				{{item}}
			</view>
		</view>
		<view class="basic-message" v-if="selecIndex==0">
			<view>
				<text>申请人姓名</text>
				<input type="text" value="" v-model="form.detail.realname" disabled />
			</view>
			<view>
				<text>联系电话</text>
				<input type="text" value="" v-model="form.detail.mobile" disabled />
			</view>
			<view>
				<text>所受类目</text>
				<input type="text" value="" v-model="form.apply_data.store_mch_common_cat_name" disabled />
			</view>
			<view>
				<text>店铺名称</text>
				<input type="text" value="" v-model="form.apply_data.store_name" disabled />
			</view>
			<view>
				<text>省市区</text>
				<input type="text" value="" v-model="form.apply_data.districts" disabled />
			</view>
			<view>
				<text>店铺地址</text>
				<input type="text" value="" v-model="form.apply_data.store_address" disabled />
			</view>
			<view>
				<text>营业执照</text>
				<image :src="form.apply_data.license_pic" mode=""></image>
			</view>
			<view>
				<text>身份证图片</text>
				<image :src="form.apply_data.cor_pic1" mode=""></image>
				<image :src="form.apply_data.cor_pic2" mode=""></image>
			</view>
		</view>
		<view class="audit-information" v-if="selecIndex==1">
			<view class="settlement-account">
				<view
					style="text-align: center;height: 50rpx;line-height: 50rpx;font-size: 28rpx;color: #000;margin: 0 0 20rpx 0;">
					结算账号
				</view>
				<view>
					<text>银行名称</text>
					<input type="text" value="" v-model="form.apply_data.settle_bank" disabled />
				</view>
				<view>
					<text>银行卡号</text>
					<input type="text" value="" v-model="form.apply_data.settle_realname" disabled />
				</view>
				<view>
					<text>开户人姓名</text>
					<input type="text" value="" v-model="form.apply_data.settle_num" disabled />
				</view>
			</view>
			<view class="discount-apply">
				<!--
				<view class="discount-apply-select">
					<text class="discount-apply-select-title">是否特殊折扣申请</text>
					<view>
						<radio-group @change="radioChange">
							<label class="uni-list-cell uni-list-cell-pd" v-for="(item, index) in items"
								:key="item.value">
								<view>
									<radio :value="item.value" :checked="index === current" :disabled='countdisabled'/>
								</view>
								<view>{{item.name}}</view>
							</label>
						</radio-group>
					</view>
				</view>
				-->
				<view class="discount-status" v-if="current==0">
					<view class="status">
						<text class="status-lable">审核状态</text>
						<view class="status-radio">
							<radio-group @change="radioChangeTwo">
								<label class="uni-list-cell uni-list-cell-pd" v-for="(item, index) in itemTwo"
									:key="item.value">
									<view>
										<radio :value="item.value" :checked="index === currentTwo" :disabled='countdisabled'/>
									</view>
									<view>{{item.name}}</view>
								</label>
							</radio-group>
						</view>
					</view>
					<view class="special-discount-detail" style="margin-bottom: 20rpx;" v-if="seviceCount">
						<view>
							<text>店铺折扣</text>
							<input type="digit"  placeholder="请输入折扣" v-model.trim="form.apply_data.settle_discount"
								style="margin-left: 45rpx;" :disabled='countdisabled'/>
							<text>折</text>
						</view>
					</view>
					<view class="discount-results" v-if="currentTwo==1">
						<text>审核结果</text>
						<textarea value="" placeholder="请输入审核结果" v-model="form.detail.remark" :disabled='countdisabled'/>
					</view>
				</view>
				<view class="special-discount" v-if="current==1">
					<view
						style="text-align: center;height: 50rpx;line-height: 50rpx;font-size: 28rpx;color: #000;margin: 0 0 20rpx 0;">
						特殊折扣申请
					</view>
					<view class="special-discount-detail">
						<view>
							<text>特殊折扣申请</text>
							<input type="digit"  placeholder="请输入折扣" v-model.trim="form.apply_data.settle_discount" :disabled='countdisabled'/>
							<text>折</text>
						</view>
					</view>
					<view class="special-discount-notice">
						<text>说明</text>
						<textarea value="" placeholder="请输入说明" v-model="form.apply_data.settle_special_rate_remark" :disabled='countdisabled'/>
					</view>
				</view>
			</view> 
			<view class="save" @click="saveMessage" v-if="saveShow">
				保存
			</view>
		</view>

	</view>
</template>

<script>
	import {
		isEmpty
	} from '../../../common/validate.js'
	export default {
		data() {
			return {
				id: '',
				type: ["基本信息", '审核信息'],
				selecIndex: 0,
				form: {
					detail: {
						realname: "测试",
						mobile: "mobile",
						status: "refused",
						remark: "",
						is_special_discount: ''
					},
					apply_data: {
						store_name: "",
						store_mch_common_cat_id: "",
						store_address: "",
						license_num: "",
						license_name: "",
						license_pic: "",
						cor_num: "",
						cor_pic1: "",
						cor_pic2: "",
						cor_realname: "",
						settle_bank: "",
						settle_num: "",
						settle_realname: "",
						settle_discount: "",
						store_mch_common_cat_name: "",
						districts: ""
					}
				},
				items: [{
						value: '否',
						name: '否'
					},
					{
						value: '是',
						name: '是'
					}
				],
				current: 0,
				itemTwo: [{
						value: '审核通过',
						name: '审核通过'
					},
					{
						value: '审核不通过',
						name: '审核不通过'
					}
				],
				currentTwo: 0,
				message: {},
				saveShow: false, //保存按钮显示
				seviceCount: true, //服务费
				countdisabled:false,//是否输入折扣和点击radio
				
			}
		},
		onLoad(options) {
			if (options && options.id) {
				this.id = options.id
				this.getexamineDetail(options.id)
			}
			if (options && options.status) {
				if (options.status == 'verifying') {
					this.saveShow = true
				}
			}
		},
		methods: {
			select(index) { //选项
				this.selecIndex = index
			},
			getexamineDetail(id) { //获取审核信息
				this.$http
					.request({
						url: this.$api.moreShop.getexamineDetail,
						method: 'GET',
						data: {
							id: id
						},
						showLoading: true
					})
					.then(res => {
						if (res.code == 0) {
							this.form = res.data
							if(res.data.detail.is_special_discount==0){
								this.current = 0
								this.seviceCount = true
							}else if(res.data.detail.is_special_discount==1){
								this.current = 1
								this.seviceCount = false
							}
							if(res.data.detail.status=='passed'){
								this.currentTwo = 0
								this.countdisabled=true
							}else if(res.data.detail.status=='refused'){
								this.currentTwo = 1
								this.countdisabled=true
							}
						}
					});
			},
			radioChange(evt) { //是否折扣申请
				for (let i = 0; i < this.items.length; i++) {
					if (this.items[i].value === evt.detail.value) {
						this.current = i;
						console.log(this.current)
						break;
					}
				}
			},
			radioChangeTwo(evt) { //审核状态选择
				for (let i = 0; i < this.itemTwo.length; i++) {
					if (this.itemTwo[i].value === evt.detail.value) {
						this.currentTwo = i;
						console.log(this.currentTwo)
						if (this.currentTwo == 1) {
							this.seviceCount = false
						} else {
							this.seviceCount = true
						}
						break;
					}
				}
			},
			saveMessage() { //保存审核信息
				if (this.current == 0) {
					if (this.currentTwo == 0) {
						if (this.form.apply_data.settle_discount < 0) {
							uni.showToast({
								title: '店铺折扣最低不能小于7',
								icon: 'none'
							});
							setTimeout(function() {
								uni.hideToast();
							}, 2000);
							return
						}
						if (this.form.apply_data.settle_discount > 9.7) {
							uni.showToast({
								title: '店铺折扣不能大于9.7',
								icon: 'none'
							});
							setTimeout(function() {
								uni.hideToast();
							}, 2000);
							return
						}
					}
					this.message = {
						id: this.id,
						is_special_discount: this.current, //是否特殊折扣申请 0、否 1、是 
						special_rate: this.form.apply_data.settle_discount, //0-9
						status: this.currentTwo == 0 ? 'passed' : 'refused', //审核状态 passed、通过 refused、不通过 如果传is_special_discount=0 必传 
						remark: this.form.detail.remark, //不通过原因
					}
				}
				if (this.current == 1) {
					if (this.form.apply_data.settle_discount <= 7) {
						uni.showToast({
							title: '店铺折扣最低不能小于7',
							icon: 'none'
						});
						setTimeout(function() {
							uni.hideToast();
						}, 2000);
						return
					}
					if (this.form.apply_data.settle_discount > 9.7) {
						uni.showToast({
							title: '店铺折扣不能大于9.7',
							icon: 'none'
						});
						setTimeout(function() {
							uni.hideToast();
						}, 2000);
						return
					}
					if (isEmpty(this.form.apply_data.settle_special_rate_remark)) {
						uni.showToast({
							title: '请填写特殊申请原因',
							icon: 'none'
						});
						setTimeout(function() {
							uni.hideToast();
						}, 2000);
						return
					}
					this.message = {
						id: this.id,
						is_special_discount: this.current, //是否特殊折扣申请 0、否 1、是 
						special_rate:this.form.apply_data.settle_discount, //折扣 0-10 不能超出范围
						settle_special_rate_remark: this.form.apply_data.settle_special_rate_remark, //特殊申请原因
					}
				}
				this.$http
					.request({
						url: this.$api.moreShop.saveMessage,
						method: 'POST',
						data: this.message,
						showLoading: true
					})
					.then(res => {
						if (res.code == 0) {
							uni.redirectTo({
								url: './examine'
							})
						} else {
							this.$http.toast(res.msg);
						}
					});
			}
		}
	}
</script>

<style lang="less">
	.examine-detail-app {
		width: 100%;
		overflow: hidden;
	}

	.examine-detail-header {
		width: 100%;
		overflow: hidden;
		display: flex;
		justify-content: space-evenly;
	}

	.examine-detail-header view {
		font-size: 28rpx;
		width: 150rpx;
		height: 60rpx;
		text-align: center;
		line-height: 60rpx;
		border-radius: 30rpx;
		margin: 20rpx 0;
	}

	.active {
		background: #ff7104;
		color: #fff;
		font-weight: bold;
	}

	.basic-message {
		width: 100%;
		overflow: hidden;
		margin-bottom: 100rpx;
	}

	.basic-message view {
		width: 90%;
		overflow: hidden;
		margin: 15rpx auto 0;
	}

	.basic-message view text {
		display: inline-block;
		height: 80rpx;
		line-height: 80rpx;
		font-size: 28rpx;
		color: #000;
		width: 150rpx;
		float: left;
	}

	.basic-message view input {
		display: inline-block;
		height: 80rpx;
		outline: none;
		border: none;
		line-height: 80rpx;
		float: left;
		width: 450rpx;
		padding-left: 30rpx;
		margin-left: 5rpx;
		background: #f2f2f2;
		color: #000;
		font-size: 26rpx;
	}

	.basic-message view image {
		display: block;
		margin: 5rpx 0;
		width: 100%;
	}






	.audit-information {
		width: 100%;
		overflow: hidden;
		margin-bottom: 100rpx;
	}

	.settlement-account view {
		margin: 15rpx 0;
	}

	.settlement-account {
		width: 90%;
		margin: 0 auto;
		border-radius: 30rpx;
		background: #fff;
		box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.3);
		padding: 15rpx;
	}

	.settlement-account view text {
		display: inline-block;
		height: 80rpx;
		line-height: 80rpx;
		font-size: 28rpx;
		color: #000;
		width: 180rpx;
		float: left;
	}

	.settlement-account view input {
		display: inline-block;
		height: 80rpx;
		outline: none;
		border: none;
		line-height: 80rpx;
		width: 400rpx;
		background: #f2f2f2;
		color: #000;
		font-size: 26rpx;
		padding-left: 10rpx;
		margin-left: 5rpx;
	}

	.legal-person {
		width: 90%;
		margin: 20rpx auto;
		border-radius: 30rpx;
		background: #fff;
		box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.3);
		padding: 15rpx;
	}

	.legal-person view {
		margin: 15rpx 0;
	}

	.legal-person view text {
		display: inline-block;
		height: 80rpx;
		line-height: 80rpx;
		font-size: 28rpx;
		color: #000;
		width: 180rpx;
		float: left;
	}

	.legal-person view input {
		display: inline-block;
		height: 80rpx;
		outline: none;
		border: none;
		line-height: 80rpx;
		width: 400rpx;
		background: #f2f2f2;
		color: #000;
		font-size: 26rpx;
		padding-left: 10rpx;
		margin-left: 5rpx;
	}

	.discount-apply {
		width: 90%;
		margin: 15rpx auto;
	}

	.discount-apply-select {
		width: 100%;
		height: 80rpx;
	}

	.discount-apply-select-title {
		display: block;
		float: left;
		height: 80rpx;
		font-size: 28rpx;
		color: #000;
		line-height: 80rpx;
	}

	.discount-apply-select view {
		float: left;
		font-size: 28rpx;
		line-height: 80rpx;
		margin-left: 20rpx;
	}

	.discount-apply-select view label {
		margin: 0 30rpx;
	}

	.status {
		width: 100%;
		height: 80rpx;
	}

	.discount-status {
		width: 100%;
		overflow: hidden;
		background: #fff;
		border-radius: 30rpx;
		box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.3);
		padding: 15rpx;
	}

	.status-lable {
		display: block;
		float: left;
		height: 80rpx;
		font-size: 28rpx;
		color: #000;
		line-height: 80rpx;
	}

	.status-radio {
		float: left;
		width: 475rpx;
		height: 80rpx;
		line-height: 80rpx;
		font-size: 28rpx;
		margin-left: 40rpx;
	}

	.status-radio view {
		float: left;
		margin: 0 10rpx;
	}

	.discount-results {
		width: 100%;
		height: 230rpx;
	}

	.discount-results text {
		display: inline-block;
		font-size: 28rpx;
		color: #000;
	}

	.discount-results textarea {
		display: inline-block;
		width: 450rpx;
		height: 200rpx;
		background: #f2f2f2;
		margin-left: 20rpx;
		padding: 15rpx;
		font-size: 25rpx;
	}

	.special-discount {
		width: 100%;
		overflow: hidden;
		background: #fff;
		border-radius: 30rpx;
		box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.3);
		padding: 15rpx;
		margin-top: 20rpx;
	}

	.special-discount-detail {
		width: 100%;
		height: 80rpx;
		line-height: 80rpx;
	}

	.special-discount-detail text {
		display: inline-block;
		height: 80rpx;
		float: left;
		font-size: 28rpx;
		color: #000;
	}

	.special-discount-detail input {
		display: inline-block;
		width: 200rpx;
		height: 80rpx;
		float: left;
		font-size: 26rpx;
		background: #f2f2f2;
		outline: none;
		border: nono;
		margin-left: 20rpx;
		text-align: center;
	}

	.special-discount-notice {
		width: 100%;
		height: 230rpx;
		margin-top: 20rpx;
	}

	.special-discount-notice text {
		display: inline-block;
		font-size: 28rpx;
		color: #000;
	}

	.special-discount-notice textarea {
		display: inline-block;
		width: 420rpx;
		height: 200rpx;
		background: #f2f2f2;
		margin-left: 130rpx;
		padding: 15rpx;
		font-size: 25rpx;
	}

	.save {
		width: 300rpx;
		height: 80rpx;
		text-align: center;
		line-height: 80rpx;
		margin: 50rpx auto;
		background: #ff7104;
		color: #fff;
		border-radius: 35rpx;
	}
</style>
