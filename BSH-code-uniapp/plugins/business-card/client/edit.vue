<template>
	<view class="edit-root">
		<view class="public-header">基础资料</view>
		
		<view class="basis-box">
			<view class="avatar-box flex flex-x-between flex-y-center">
				<view>来源</view>
				<view class="bottom flex flex-y-center">
					<view>{{detail_data.from}}</view>
				</view>
			</view>
			
			<view class="common flex flex-y-center flex-x-between">
				<view>姓名：</view>
				<input class="common-inp" type="text" v-model="name" placeholder="请输入名字"/>
			</view>
			
			
				<picker @change="bindPickerChange" :value="index" :range="array">
					<view class="avatar-box flex flex-x-between flex-y-center" style="padding: 40rpx 0;">
						<view>性别</view>
						<view class="bottom flex flex-y-center">
							<view>{{sex}}</view>
							<text class="common-icon iconfont icon-xiala"></text>
						</view>
					</view>
				</picker>
			
			
			<view class="common flex flex-y-center flex-x-between">
				<view>手机号：</view>
				<input class="common-inp" type="text" v-model="mobile"  placeholder="请输入手机号"/>
			</view>
			
			<view class="common flex flex-y-center flex-x-between">
				<view>邮箱：</view>
				<input class="common-inp" type="text" v-model="eMail" placeholder="请输入邮箱"/>
			</view>
			
			<view class="common flex">
				<view>详细地址：</view>
				<textarea class="common-text" v-model="address" placeholder="请输入详细地址" auto-height='true'/>
			</view>
			
			<picker mode="date" :value="date" :start="startDate" :end="endDate" @change="bindDateChange">
				<view class="avatar-box flex flex-x-between flex-y-center" style="padding: 40rpx 0;border-bottom: 0;">
					<view>生日</view>
						<view class="bottom flex flex-y-center">
							<view class="uni-input">{{date}}</view>
							<text class="common-icon iconfont icon-xiala"></text>
						</view>
				</view>
			</picker>
			
			<!-- <view class="common flex flex-x-between flex-y-center" style="border-bottom: 0;">
				<view>屏蔽Ta的信息推送</view>
				<switch :checked="is_checked" @change="changeSwitch" color='#BC0100' style="transform: scale(0.7);"/>
			</view> -->
		</view>
		
		<view class="public-header">备注</view>
		<view class="basis-box">
			<view class="common flex" style="border-bottom: 0;">
				<view>备注：</view>
				<textarea class="common-text" v-model="remark" placeholder="请输入备注" auto-height='true'/>
			</view>
		</view>
		
		<view class="submit-btn" @tap="submit">提交设置</view>
		
		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	export default {
		data() {
			const currentDate = this.getDate({
				format: true
			})
			return {
				mobile:'',
				name:'',
				eMail:'',
				address:'',
				remark:'',
				sex:'',
				user_id:0,
				is_checked:false,
				is_push:0,
				
				loading:false,
				
				// 默认数据
				detail_data:'',
				
				// 性别选择
				array: ['男', '女'],
				index: 0,
				
				// 时间选择器
				date: currentDate,
			};
		},
		computed: {
			startDate() {
				return this.getDate('start');
			},
			endDate() {
				return this.getDate('end');
			}
		},
		onLoad(options) {
			this.user_id = options.id;
			this.getDetail();
		},
		methods:{
			submit(){ //提交信息的设置修改
				this.loading = true;
				this.$http.request({
					url:this.$api.plugin.business.setting,
					method:'post',
					data:{
						id:this.user_id,
						basic_info:{
							sex:this.sex,
							mobile:this.mobile,
							email:this.eMail,
							address:this.address,
							birthday:this.date,
							is_push:this.is_push,
							remark:this.remark,
							full_name:this.name
						}
					}
				}).then(res => {
					this.loading = false;
					if(res.code == 0){
						var obj = {
							sex:this.sex,
							mobile:this.mobile,
							email:this.eMail,
							address:this.address,
							birthday:this.date,
							is_push:this.is_push,
							remark:this.remark,
							full_name:this.name
						};
						uni.setStorageSync('basic_info',JSON.stringify(obj)); //减少请求，将旧数据替换为修改了的数据
						this.navBack();
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			getDetail(){ //获取默认的数据
				this.detail_data = JSON.parse(uni.getStorageSync('basic_info'));
				this.name = this.detail_data.full_name;
				this.mobile = this.detail_data.mobile;
				this.eMail = this.detail_data.email;
				this.address = this.detail_data.address;
				this.is_push = this.detail_data.is_push;
				this.checked(this.detail_data.is_push);
				this.remark = this.detail_data.remark;
				this.sex = this.detail_data.sex;
				this.date = this.detail_data.birthday;
			},
			checked(num){
				if(num == 0){
					this.is_checked = false;
				}else{
					this.is_checked = true;
				}
			},
			bindPickerChange(e) {
				this.index = e.target.value
				this.sex = this.array[this.index];
			},
			bindDateChange: function(e) {
				this.date = e.target.value
			},
			getDate(type) {
				const date = new Date();
				let year = date.getFullYear();
				let month = date.getMonth() + 1;
				let day = date.getDate();
	
				if (type === 'start') {
					year = year - 60;
				} else if (type === 'end') {
					year = year + 5;
				}
				month = month > 9 ? month : '0' + month;
				day = day > 9 ? day : '0' + day;
				return `${year}-${month}-${day}`;
			},
			changeSwitch(e){ //switch按钮
				if(e.detail.value){
					this.is_push = 1;
				}else{
					this.is_push = 0;
				}
				this.checked(this.is_push);
			}
		}
	}
</script>

<style lang="scss" scoped>
	.public-header{
		color: #808080;
		font-size: 30rpx;
		padding: 34rpx;
	}
	
	.basis-box{
		background: #FFFFFF;
		padding: 0 30rpx;
		color: #000000;
		font-size: 30rpx;
		
		.avatar-box{
			padding: 42rpx 0;
			border-bottom: 1px solid #E0E0E0;
			
			.bottom{
				color: #000;
			}
			.avatar-img{
				width: 104rpx;
				height: 104rpx;
				border-radius: 50%;
				display: block;
			}
			.common-icon{
				font-size: 50rpx;
				margin-left: 10rpx;
				color: #A9A9A9;
				line-height: 54rpx;
				position: relative;
				top: 4rpx;
			}
		}
		
		.common{
			padding: 46rpx 0;
			border-bottom: 1px solid #E0E0E0;
			
			.common-inp{
				text-align: right;
				color: #000;
			}
			.common-text{
				text-align: right;
				color: #000;
				flex: 1;
			}
		}
	}
	
	.label{
		padding: 50rpx 30rpx;
		background: #FFFFFF;
		
		.label-list{
			flex-wrap: wrap;
			flex: 1;
			
			.label-item{
				padding: 4rpx 14rpx;
				background: #E49999;
				font-size: 30rpx;
				color: #FFFFFF;
				margin-right: 10rpx;
				margin-bottom: 10rpx;
			}
		}
	}
	
	.submit-btn{
		background: #BC0100;
		border-radius: 50rpx;
		height: 90rpx;
		text-align: center;
		line-height: 90rpx;
		color: #FFFFFF;
		margin: 88rpx 30rpx;
	}
</style>
