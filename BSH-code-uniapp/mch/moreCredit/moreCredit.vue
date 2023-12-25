<template>
	<view v-if="!allowActive">
		<view class="select-isv">
			<!-- <view style="color:red;">话费充值功能维护中，请稍后！</view> -->
			<view style="color:red;">请选择您的号码类型！</view>
			<text>
				<picker :value="allowPickerIndex" @change="allowPickerChange" range-key="label" :range="allowTypes" style="width:100%">
					<view>选择</view>
				</picker>
			</text>
		</view>
	</view>
	<view v-else class="moreCreadit-app">
		<view class="select-isv-active">
			<text>您选择</text>
			<text v-if="allowActive=='yidong'">移动</text>
			<text v-if="allowActive=='liantong'">联通</text>
			<text v-if="allowActive=='dianxin'">电信</text>
			<text @click="allowActive=''" class="iconfont icon-right" style="font-size: 32rpx;"></text>
		</view>
		<view class="moreCreadit_header">
			<view class="text">
				<input type="text" v-model="form.mobile" placeholder="请输入电话号码" @focus="_input" />
				<image :src="img_url+'delete_error.png'" mode="" style="width: 30rpx;height: 30rpx;
				display: block;position: absolute;right: 30rpx;top: 25rpx;z-index: 999;" @click.stop="deleteint"
				 v-if="form.mobile.length>0&&mobileShow"></image>
			</view>
			<!-- #ifdef APP-PLUS || MP -->
				<image :src="plugins_img_url+'/phonwcontance.png'" mode="" style="display: block;width: 60rpx;height: 60rpx;position: absolute;right: 40rpx;top: 70rpx;"
				@click="getPhonelist"></image>
			<!-- #endif -->
		</view>
		<view class="moreCreadit_detail">
			<view class="moreCreadit_detail-num" v-if="show">
				<!-- <view class="moreCreadit_detail-num_title" style="margin:10rpx 0;">
					<text @click="allowActive='yidong'" :class="allowActive == 'yidong' ? 'typeActive' : ''">移动</text>
					<text @click="allowActive='liantong'" :class="allowActive == 'liantong' ? 'typeActive' : ''">联通</text>
					<text @click="allowActive='dianxin'" :class="allowActive == 'dianxin' ? 'typeActive' : ''">电信</text>
				</view> -->
				<!-- <view class="moreCreadit_detail-num_title" style="margin:10rpx 0;">
					<text v-for="(item,index) in type" :key='index' :class="typeIndex==index?'typeActive':''" @click="typeSelect(index,item)">{{item}}</text>
				</view> -->
				<view class="moreCreadit_detail-num_list">
					<view :class="selectIndex==index?'active':'moreCreadit_detail-num_list-item'"
						v-for="(item,index) in cList" :key='index' @click="select(item,index)">
						<view style="margin: 15rpx 0 10rpx 0;">
							<text style="color:rgb(255, 113, 4);font-size: 38rpx;font-weight: bold;">{{item.price}}</text>
							<text style="color:rgb(255, 113, 4);font-size: 25rpx;">元</text>
						</view>
						<view style="font-size: 25rpx;color: #9E9E9E;">
							<!-- 送{{getSendNum(item.price)}}{{currencyAlias.silver_beans_alias}} -->
						</view>
					</view>
				</view>
			</view>
			<view class="recharge" v-if="show">
				<button type="default" @click="checkrecharge">立即充值</button>
			</view>
		</view>
		<view class="notice" v-if="show">
			<text style="display: block;width: 100%;height: 60rpx;line-height: 60rpx;font-size: 28rpx;color: #000;font-weight: bold;">温馨提示</text>
			<view class="notice-item" >
				<view>
					1：充值前请核对充值号码
				</view>
				<view v-if="typeIndex==0">
					2：充值后24小时内到账，月底月初对账期间不支持充值
				</view>
				<view v-if="typeIndex==1">
					2：充值后24小时内到账，月底月初对账期间不支持充值
				</view>
				<!-- <view v-if="typeIndex==0">
					3：快充赠送充值金额30%{{currencyAlias.silver_beans_alias}}
				</view>
				<view v-if="typeIndex==1">
					3：新用户第一次充值送100%{{currencyAlias.silver_beans_alias}}，每个用户第一次送{{currencyAlias.silver_beans_alias}}上限100，超过上限部分赠送订单金额50%{{currencyAlias.silver_beans_alias}}
				</view>
				<view>
					4：{{currencyAlias.red_envelope_alias}}充值不返{{currencyAlias.silver_beans_alias}}
				</view> -->
				<view>
					3：如对本次充值相关内容有疑问,请联系客服
				</view>
				<view>
					4：暂不支持携号转网的手机号充值
				</view>
			</view>
		
		</view>
		
		<view class="recharge_list">
			<jx-list-cell :arrow="true" padding="0" :lineLeft="false" @click="href">
				<view class="jx-cell-header" style="height: 80rpx;margin: 10rpx 0;">
					<view class="jx-cell-title" style="font-weight: 700;line-height: 80rpx;font-size: 28rpx;padding-left: 20rpx;">当天充值记录</view>
				</view>
				<view class="jx-cell-header" style="height: 80rpx;margin: 10rpx 0;">
					<view class="jx-cell-title" style="font-weight: 700;line-height: 80rpx;font-size: 28rpx;padding-left: 70rpx;margin-left: 350rpx;color: gray;">全部</view>
				</view>
			</jx-list-cell>
			<view class="recharge_list-title">
				<text>手机号</text>
				<text>充值金额</text>
				<text>充值时间</text>
				<text>充值状态</text>
			</view>
			<view class="recharge-item" v-for="(item,index) in creditStatusList" :key='index'>
				<text style="color: #000;">{{item.mobile}}</text>
				<text>{{item.order_price}}</text>
				<text>{{item.created_at}}</text>
				<!-- <text v-if="item.pay_status=='paid'&&item.order_status=='success'" style="color: green;">充值成功</text>
				<text v-if="item.pay_status=='paid'&&item.order_status=='processing'" style="color: red;">充值中...</text>
				<text v-if="item.pay_status=='paid'&&item.order_status=='fail'" style="color: gray;">充值失败</text> -->
				<!-- <text v-if="item.pay_status=='refunding'" style="color: red;">退款中...</text>
				<text v-if="item.pay_status=='refund'" style="color: gray;">已退款</text> -->		
				<text v-if="item.pay_status=='unpaid'" style="color: gray;">未支付</text>
				<text v-else style="color: green;">充值成功</text>	
			</view>
		</view>	
		<com-bottom-popup :show="popupShow" @close="hidePopup">
			<view class="recharge_detail">
				<view class="recharge_money">
					<text style="color: rgb(255, 113, 4)">￥</text>
					<text
						style="font-size: 50rpx;font-weight: bold;color: rgb(255, 113, 4);">{{form.order_price}}</text>
				</view>
				<jx-list-cell :arrow="false" padding="0" :lineLeft="false">
					<view class="jx-cell-header"
						style="height: 80rpx;width: 90%;margin: 0 auto;border-bottom: 1rpx solid #F8FAF9;">
						<view class="jx-cell-title" style="line-height: 80rpx;font-size: 30rpx;float: left;">订单信息</view>
						<view class="jx-cell-title" style="line-height: 80rpx;font-size: 30rpx;float: right;">手机充值
						</view>
					</view>
				</jx-list-cell>
				<jx-list-cell :arrow="false" padding="0" :lineLeft="false">
					<view class="jx-cell-header"
						style="height: 80rpx;width: 90%;margin: 0 auto;border-bottom: 1rpx solid #F8FAF9;">
						<view class="jx-cell-title" style="line-height: 80rpx;font-size: 30rpx;float: left;">手机号</view>
						<view class="jx-cell-title" style="line-height: 80rpx;font-size: 30rpx;float: right;">
							{{form.mobile}}
						</view>
					</view>
				</jx-list-cell>
				<view class="pay-type">
					<radio-group @change="radioChange">
						<view :hidden="index == 0" class="lables" v-for="(item, index) in items" :key="item.value">
							<view style="float: right;height: 60rpx;line-height: 60rpx;">
								<radio :value="item.value" :checked="index === current" color='rgb(255, 113, 4)'/>
							</view>
							<view style="float: left;height: 60rpx;line-height: 60rpx;font-size: 30rpx;">{{item.name}}</view>
						</view>
					</radio-group>
				</view>
				<jx-list-cell :arrow="false" padding="0" :lineLeft="false" v-if="current==0">
					<view class="jx-cell-header"
						style="height: 80rpx;width: 90%;margin: 0 auto;border-bottom: 1rpx solid #F8FAF9;">
						<view class="jx-cell-title" style="line-height: 80rpx;font-size: 30rpx;float: left;">需支付现金</view>
						<view class="jx-cell-title"
							style="line-height: 80rpx;font-size: 30rpx;float: right;margin-right: 30rpx;color: rgb(255, 113, 4);">{{form.order_price}}元</view>
					</view>
				</jx-list-cell>
				<jx-list-cell :arrow="false" padding="0" :lineLeft="false" v-if="current==1">
					<view class="jx-cell-header"
						style="height: 80rpx;width: 90%;margin: 0 auto;border-bottom: 1rpx solid #F8FAF9;">
						<view class="jx-cell-title" style="line-height: 80rpx;font-size: 30rpx;float: left;">需支付</view>
						<view class="jx-cell-title"
							style="line-height: 80rpx;font-size: 30rpx;float: right;margin-right: 30rpx;color: rgb(255, 113, 4);">{{redbag}}{{currencyAlias.red_envelope_alias}}</view>
					</view>
				</jx-list-cell>
				<view class="sumbit" v-if="current==0">
					<button type="default" @click="paysubnit">立即支付</button>
					<image :src="img_url+'/artice_logo.png'" mode="widthFix"></image>
				</view>
				<view class="sumbit" v-if="current==1">
					<button type="default" @click="sumbit">立即支付</button>
					<image :src="img_url+'/artice_logo.png'" mode="widthFix"></image>
				</view>
			</view>
		</com-bottom-popup>
		<unipopup ref="popup" type="bottom">
			<view class="popup_view">
				<txl @ev="evFunc" :datas="datas" name="employeeName" :index="true"></txl>
			</view>
		</unipopup>
	</view>
	
</template>

<script>
	import jxListCell from '@/components/list-cell/list-cell';
	import {isEmpty} from '../../common/validate.js';
	import txl from '@/components/yt-txl/index.vue';
	import unipopup from '@/components/uni-popup/uni-popup';
	// #ifdef H5
	var jweixin = require('jweixin-module');
	// #endif
	// #ifdef MP-WEIXIN || APP-PLUS
	import {
		setPay
	} from '@/config/utils.js'
	// #endif
	export default {
		components: {
			jxListCell,
			txl,
			unipopup
		},
		data() {
			return {
				plugins_img_url: this.$api.plugins_img_url,
				img_url: this.$api.img_url,
				selectIndex: 0, //默认选中快充或者慢充金额列表的第一个
				list: [], //快充或者慢充金额列表
				popupShow: false,
				items: [ //支付方式选择
					{
						value: '现金支付',
						name: '现金支付'
					}, 
					{
						value: '金豆支付',
						name: '金豆支付'
					},
				],
				current: 1, //支付选择
				form: {
					mobile: '',
					order_price: '',
					integral_deduction_price:'',
					product_id:"",
					plateform_id: "",
					pay_type:2,//1  现金 2金豆
				},
				creditStatusList:{},//充值记录
				order_id:'',//订单ID
				redbag:'',//金豆
				type:[],
				typeIndex:0,//tabble的切换样式
				moneyList:'',//快充和慢充的详情
				payData:'',//支付信息
				show:true,//显示影藏
				mobileShow:false,
				phoneList:[],//通讯录
				datas:[				
					{
					    "employeeName": "",
						"phone":''	,
					},
				],
				use_red_envelopes:'',
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
				allowActive: '',
				allowPickerIndex: -1,
				allowTypes: [{value: 'yidong', label: '移动'}, {value: 'liantong', label: '联通'}, {value: 'dianxin', label: '电信'}]
			};
		},
		computed: {
			cList(){
				let list = [], allowActive = this.allowActive;
				this.list.forEach(item => {
					if(item.allow && item.allow.indexOf(allowActive) != -1){
						list.push(item);
					}
				});
				return list;
			}
		},
		onLoad(options){
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
				this.tems = [ //支付方式选择
					{
					value: '现金支付',
					name: '现金支付'
					}, 
					{
					value: this.currencyAlias.red_envelope_alias+'支付',
					name: this.currencyAlias.red_envelope_alias+'支付'
					},
				];
			}
		},
		onShow() { 
			this.creditStatus()
		},
		methods: {
			allowPickerChange(e){
				this.allowActive = this.allowTypes[e.detail.value].value;
			},
			evFunc(data){
				this.$refs.popup.close()
				this.form.mobile=data.phone
			},
			getPhonelist(){//获取手机通讯录
				var that = this
				that.phoneList=[]
				that.datas=[]
				// 获取通讯录对象
				  // #ifdef MP-WEIXIN
						 wx.chooseContact({
						      success: function (res) {
								that.form.mobile=res.phoneNumber
						        console.log(res.phoneNumber, '成功回调')
						      },
						      fail(res) {
						        console.log(res, '错误回调')
						      },
						      complete(res) {
						        console.log(res, '结束回调')
						      }
						   })
				   // #endif
				// #ifdef APP-PLUS 
				plus.contacts.getAddressBook( plus.contacts.ADDRESSBOOK_PHONE, function( addressbook ) {  //获取通讯录
				    addressbook.find(["displayName","phoneNumbers"],function(contacts){   //查找通讯录的数据  
				        that.phoneList = contacts 
						console.log(contacts)
						for(let i=0;i<that.phoneList.length;i++){
							for(let j=0;j<that.phoneList[i].phoneNumbers.length;j++){
								that.datas.push({
									employeeName:that.phoneList[i].displayName,
									phone:that.phoneList[i].phoneNumbers[j].value
								})
							}
						}
						that.$refs.popup.open()
				    }, function () {   //获取通讯录数据成功
				    },{multiple:true});  
				}, function ( e ) {  //获取通讯录数据失败
				    
				});
				  // #endif
			},
			_input(){
				this.mobileShow=true
			},
			deleteint(){//删除输入号码
				this.form.mobile=''
			},
			getSendNum(val){
				if(this.typeIndex == 0){
					val = val * (3/10);
				}else{
					val = val <= 100 ? val : 100;
				}
				return val.toFixed(0);
			},
			typeSelect(index,item){//选择快充还是慢充
				this.typeIndex=index
				if(this.moneyList.enable_fast==0){
					this.typeIndex=1
				}
				if(item=='快充'){
					this.selectIndex=0
					this.list=this.moneyList.FastCharging
						this.form.order_price=this.list[0].price
						this.form.integral_deduction_price=this.list[0].redbag_num
						this.redbag=this.list[0].redbag_num
						this.form.product_id=this.list[0].product_id
						this.form.plateform_id=this.list[0].plateform_id
				}
				if(item=='慢充'){
					this.selectIndex=0
					this.list=this.moneyList.SlowCharge
						this.form.order_price=this.list[0].price
						this.form.integral_deduction_price=this.list[0].redbag_num
						this.redbag=this.list[0].redbag_num
						this.form.product_id=this.list[0].product_id
						this.form.plateform_id=this.list[0].plateform_id
				}
				console.log(this.form)
			},
			select(item, index) { //选择充值金额
				this.selectIndex = index
				this.form.order_price = item.price
				this.form.product_id=item.product_id
				this.form.plateform_id=item.plateform_id
				this.form.integral_deduction_price = item.redbag_num
				this.redbag=item.redbag_num
				console.log(this.form)
			},
			checkrecharge() { //打开弹窗
				if (isEmpty(this.form.mobile)) return this.alert('请填写充值的号码')
				if (this.form.mobile.length>11) return this.alert('手机号码错误，请重新输入')
				let list = this.cList, proId = list[this.selectIndex].goods_id;
				this.$http.request({
					url: this.$api.goods.detail,
					showLoading: true,
					data: {id: proId}
				}).then((res) => {
					if (res.code == 0) {
						let attr = res.data.goods.attr_list; 
						this.toConfirmOrder(proId, attr[0].id);
					} else {
						uni.showModal({
							content: res.msg,
							showCancel: false,
							confirmColor: '#BC0100'
						});
					}
				});
				//this.popupShow = true
			},
			toConfirmOrder(proId, goods_attr_id){
				uni.setStorage({
					key: 'orderData',
					data: [{
						num: 1,
						goods_attr_id: goods_attr_id,
						id: proId,
						cart_id: 0,
						mch_id: 0
					}],
					fail() {
						console.log('存入本地失败');
					}
				});
				let mobile = this.form.mobile;
				this.$http.request({
					url: this.$api.cart.addCart,
					method: 'post',
					showLoading: true,
					data: {
						goods_id: proId,
						attr: goods_attr_id,
						num: 1,
						mch_id: 0,
						mch_baopin_id: 0,
						buy_now:1,//立即下单传1
					}
				}).then((res) => {
					if (res.code == 0) {
						let url ='/pages/order/submit?sign=addcredit&mobile='+mobile+'&nav_id=' + JSON.stringify([{id:proId,num:1}])+"&mch_id=0&user_address_id=0"+"&use_score=0"+"&use_integral=0"+"&list="+String(res.data.cart_id);
						uni.navigateTo({
							url
						})
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			hidePopup() { //关闭弹窗
				this.popupShow = false;
			},
			radioChange: function(evt) { //选择支付方式
				for (let i = 0; i < this.items.length; i++) {
					if (this.items[i].value === evt.detail.value) {
						this.current = i;
						break;
					}
				}
			},
			alert(txt) { //弹窗提示
				uni.showToast({
					title: txt,
					icon: 'none'
				})
			},
			sumbit() {//生成充值订单
				if (this.form.mobile.length>11) return this.alert('手机号码错误，请重新输入')
				this.form.pay_type=2
				this.$http.request({ 
					url: this.$api.morecredit.creditOrder,
					method: 'POST',
					data: this.form,
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						let datas = {
							order_no: res.data.order_no,
							order_price: res.data.order_price
						}
						this.order_id=res.data.order_id
						this.paycredit(datas)
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			paycredit(datas){ //支付
				this.$http.request({
					url: this.$api.morecredit.creditpay,
					method: 'POST',
					data: datas,
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.popupShow=false
						uni.navigateTo({
							url:'./creditResults?order_id='+this.order_id
						})
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			paysubnit(){ //现金支付生成订单
				if (this.form.mobile.length>11) return this.alert('手机号码错误，请重新输入')
				this.form.pay_type=1
				this.$http.request({
					url: this.$api.morecredit.creditOrder,
					method: 'POST',
					data: this.form,
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.getpaypreiew(res.data.order_no)
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			getpaypreiew(order_no){//支付预处理
				this.$http.request({
					url: this.$api.morecredit.paypreiew,
					method: 'POST',
					data:{
						order_no:order_no,
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						console.log(res)
						this.payData = res.data;
						// #ifdef H5 ||MP
							this.confirmPay()
						// #endif
						// #ifdef APP-PLUS
						    this.getalipay()
						// #endif
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			// 请求支付接口
			confirmPay(){
				let that=this
						// #ifdef H5
								that.$http.request({
									url: that.$api.moreShop.wechatpay,
									showLoading: true,
									method: 'post',
									data: {
										union_id: that.payData.union_id,
										stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
										wx_type:'wechat'//公众号：wechat  小程序：mp-wx
									}
								}).then(res=>{
									if(res.code==0){
											that.$wechatSdk.pay(res.data,'/pages/index/index');
									}else{
										that.$http.toast(res.msg);	
									}
								})
						// #endif
						// #ifdef MP-WEIXIN || APP-PLUS
								that.$http.request({
									url: that.$api.moreShop.wechatpay,
									showLoading: true,
									method: 'post',
									data: {
										union_id: that.payData.union_id,
										stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
										wx_type:'mp-wx'//公众号：wechat  小程序：mp-wx
									}
								}).then(res=>{
									if(res.code==0){
										setPay(res.data, (result) => {
											let _url = '/pages/index/index'
											if (result.success) {
												that.$http.toast("支付成功")
											} else {
												that.$http.toast("未支付")
												_url = '/mch/moreCredit/moreCredit'
											}
												setTimeout(() => {
													// #ifdef APP-PLUS
														uni.navigateTo({
															url: _url
														})
													// #endif
													// #ifdef H5||MP-WEIXIN
														uni.redirectTo({
															url: _url
														})
													// #endif
												},1000)														
										});
									}else{
										that.$http.toast(res.msg);	
									}
								})
						// #endif			
			},			
			getalipay(){
				let that=this
				that.$http.request({
					url: that.$api.moreShop.alipay,
					showLoading: true,
					method: 'post',
					data: {
						union_id:that.payData.union_id,
						stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
					}
				}).then(res=>{
					if(res.code==0){
						// #ifdef APP-PLUS
							uni.navigateTo({
								url: '/pages/order/alipayWeb?url=' + res.data.codeUrl
							})
						// #endif
						 // #ifdef H5
						let url=res.data.codeUrl
						location.href=url
						// #endif
					}else{
						that.$http.toast(res.msg)
					}
				})
			},
			creditStatus(){ //充值记录
				this.$http.request({
					url: this.$api.morecredit.creditStatus,
					method: 'get',
					data: {
						plateforms_id:1,
						is_list:1
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.creditStatusList=res.data.list
						this.moneyList=res.data.money_list
						this.form.mobile=res.data.mobile
						this.use_red_envelopes=res.data.use_red_envelopes
						if(this.use_red_envelopes>0){
							this.items=[
								{
									value: '现金支付',									
									name: '现金支付'
								},
								{
									value: this.currencyAlias.red_envelope_alias+'支付',									
									name: this.currencyAlias.red_envelope_alias+'支付'
								}
							]
						}else{
							this.items=[
								{
									value: '现金支付',									
									name: '现金支付'
								}
							]
						}
						if (isEmpty(this.form.mobile)){
							this.mobileShow=false
						}else{
							this.mobileShow=true
						}
						if(this.moneyList.enable_fast==1){
							this.typeIndex=0
							if(this.moneyList.enable_slow==1){
								this.list=this.moneyList.FastCharging
								this.type=["快充","慢充"]
								this.show=true
							}else{
								this.list=this.moneyList.FastCharging
								this.type=["快充"]
								this.show=true
							}
						}
						if(this.moneyList.enable_fast==0){
							this.typeIndex=1
							if(this.moneyList.enable_slow==1){							
								this.list=this.moneyList.SlowCharge
								this.type=["慢充"]
								this.show=true
							}else{
								this.list=[]
								this.type=[]
								this.show=false
							}
						}
						if (isEmpty(this.form.order_price)){
							this.form.order_price=this.list[0].price
							this.form.integral_deduction_price=this.list[0].redbag_num
							this.form.product_id=this.list[0].product_id
							this.form.plateform_id=this.list[0].plateform_id
							this.redbag=this.list[0].redbag_num
						}
						console.log(this.form)
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			href(){
				uni.navigateTo({
					url:'./rechargeList'
				})
			}
		}
	}
</script>

<style lang="less">
	page{background:white;}
	.select-isv{
		height:80vh;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		color:gray;
		
		text{font-weight:bold;margin-top:6%;width:50%;padding:5%;text-align:center;border: 1px solid rgb(255, 113, 4);border-radius: 20rpx; color:rgb(255, 113, 4)}
	}
	
	.select-isv-active{
		display: flex;
		align-items: center;
		padding:30rpx;
		border-bottom:1px solid #eee;
		text:first-child{flex-grow: 1;font-weight: bold;}
	}
	
	.moreCreadit-app {
		width: 100%;
		overflow: hidden;
	}

	.moreCreadit_header {
		width: 100%;
		/* height: 420rpx; */
		// background: rgb(255, 113, 4);
		// border-radius: 0 0 35rpx 35rpx;
		padding: 20rpx 20rpx 0 20rpx;
		box-sizing: border-box;
	}

	.title {
		color: #fff;
		font-size: 40rpx;
		margin-top: 10rpx;
		margin-bottom: 10rpx;
	}

	.text {
		color: #fff;
		font-size: 38rpx;
		/* #ifdef H5 */
			width: 95%;
		/* #endif */
		/* #ifdef  MP || APP-PLUS */
		width: 80%;
		/* #endif */
		border: 1rpx solid rgb(112,170,214);
		/* #ifdef H5 */
			margin: 40rpx auto 0;
		/* #endif */
		/* #ifdef  MP || APP-PLUS */
		margin: 40rpx 0 0 20rpx;
		/* #endif */
		height: 80rpx;
		line-height: 80rpx;
		border-radius: 20rpx;
		padding-left: 20rpx;
		box-sizing: border-box;
		position: relative;
	}

	.text input {
		width: 100%;
		height: 80rpx;
		line-height: 80rpx;
		line-height: 80rpx;
		font-size: 32rpx;
		color: #000;
	}

	.moreCreadit_detail {
		margin:0 10%;
		margin-bottom:50upx;
		overflow: hidden;
		/* margin: -220rpx auto 0; */
		/* border-radius: 20rpx 20rpx 0 0; */
		/* background: url('https://dev.mingyuanriji.cn/web/static//Morecredit.png')no-repeat; */
		/* background-size: 100%; */
	}

	.moreCreadit_detail-num {
		width: 100%;
		overflow: hidden;
		background: #fff;
		margin-top: 60rpx;
	}

	.moreCreadit_detail-num_title {
		width: 100%;
		height: 60rpx;
		line-height: 60rpx;
		padding: 0 20rpx;
		display: flex;justify-content: space-evenly;
		margin-bottom: 5rpx;
	}

	.moreCreadit_detail-num_title text {
		display: block;
		width: 120rpx;
		height: 60rpx;
		text-align: center;
		color: #272727;
		font-size: 35rpx;
		font-weight: bold;
	}
	.typeActive{ border-bottom: 4rpx solid rgb(255, 113, 4);}
	.moreCreadit_detail-num_list {
		width: 100%;
		overflow: hidden;
		display: flex;
		justify-content: space-around;
		flex-wrap: wrap;
		padding: 0 20rpx;
		box-sizing: border-box;
	}

	.moreCreadit_detail-num_list-item {
		width: 30%;
		text-align: center;
		height: 160rpx;
		background: #F7F7FF;
		margin: 30rpx 5rpx;
		box-sizing: border-box;
	}

	.active {
		width: 30%;
		text-align: center;
		height: 160rpx;
		background: #F7F7FF;
		margin: 30rpx 5rpx;
		border: 4rpx dashed rgb(255, 113, 4);
		box-sizing: border-box;
	}

	.moreCreadit_detail-num_list-item view {
		margin: 10rpx 0;
	}

	.recharge {
		width: 100%;
		height: 80rpx;
		margin: 50rpx auto 0;
	}

	.recharge button {
		width: 100%;
		height: 100%;
		text-align: center;
		background: rgb(255, 113, 4);
		line-height: 80rpx;
		outline: none;
		border: none;
		color: #fff;
		font-size: 35rpx;
	}

	.recharge_detail {
		width: 100%;
		min-height: 600rpx;
		padding: 50rpx 0 0 0;
	}

	.recharge_money {
		width: 100%;
		height: 100rpx;
		text-align: center;
		line-height: 100rpx;
	}

	.sumbit {
		width: 100%;
		overflow: hidden;
	}

	.sumbit button {
		width: 90%;
		height: 80rpx;
		font-size: 30rpx;
		line-height: 80rpx;
		outline: none;
		border: none;
		background: rgb(255, 113, 4);
		color: #fff;
		margin-top: 80rpx;
	}

	.sumbit image {
		display: block;
		width: 40%;
		margin: 20rpx auto 0;
	}

	.pay-type {
		width: 90%;
		overflow: hidden;
		margin: 0 auto;
	}

	.lables {
		width: 100%;
		height: 60rpx;
		margin: 10rpx 0;
	}
	.recharge_list{
		width: 100%;
		margin: 20rpx 0 60rpx 0;
	}
	.recharge_list-title{
		display: flex;
		justify-content: space-evenly;
		font-size: 28rpx;
		margin: 10rpx 0;
	}
	.recharge_list-title text{
		display: block;
		width: 25%;
		text-align: center;
		color: #ff7104;
	}
	.recharge-item{
		display: flex;
		justify-content: space-evenly;
		margin: 35rpx 0;
	}
	.recharge-item text{
		display: block;
		font-size: 25rpx;
		width: 25%;
		text-align: center;
	}
	.notice{width: 100%;overflow: hidden;padding: 0 20rpx;box-sizing: border-box;margin: 20rpx 0;}
	.notice-item{width: 100%;font-size: 26rpx;}
	.notice-item view{margin: 5rpx 0;}
	
	
	
	.popup_view{width: 100%;height:1000rpx;background: rgb(248,248,248);border-radius: 15rpx;padding: 30rpx  20rpx;box-sizing: border-box;}
	
</style>
