<template>
	<view class="app">
		<view class="tui-set-box">
			<tui-list-cell padding="0" :lineLeft="false" :arrow="true" @click="href(1)">
				<view class="tui-list-cell tui-info-box" v-if="userInfo">
					<image :src="userInfo.avatar" class="tui-avatar"></image>
					<view>{{userInfo.nickname}}</view>
				</view>
			</tui-list-cell>
			<tui-list-cell padding="0" :lineLeft="false" :arrow="true" @click="href(2)">
				<view class="tui-list-cell">
					地址管理
				</view>
			</tui-list-cell>
			<view class="tui-mtop">
				<!-- <tui-list-cell padding="0" :lineLeft="false" :arrow="true">
					<view class="tui-list-cell">
						账户与安全
					</view>
				</tui-list-cell> -->
				<tui-list-cell padding="0" :lineLeft="false" :arrow="true" @click="href(3)">
					<view class="tui-list-cell">
						支付密码设置
					</view>
				</tui-list-cell>
				<tui-list-cell padding="0" :lineLeft="false" :arrow="false">
					<view class="flex flex-x-between flex-y-center" style="width: 100%;">
						<view class="tui-list-cell">
							消息提醒设置
						</view>
						<switch :checked="is_open" @change="openMsg" color='#FF7104' style="transform: scale(0.7)"/>
					</view>
				</tui-list-cell>
			</view>
			<!-- <view class="tui-mtop">
				<tui-list-cell padding="0" :lineLeft="false" :arrow="true" @tap="href(4)">
					<view class="tui-list-cell">
						关于我们
					</view>
				</tui-list-cell>
				<tui-list-cell padding="0" :lineLeft="false" :arrow="true">
					<view class="tui-list-cell">
						意见反馈
					</view>
				</tui-list-cell>
			</view> -->

			<view class="tui-exit" v-if="is_login">
				<view class="exit-btn" :style="{background:'#FF7104'}" @click="logout">
					退出登录
				</view>
				<!-- <tui-button type="danger" height="88rpx" @click="logout" :style="{background:textColor}">退出登录</tui-button> -->
			</view>
		</view>
	</view>
</template>

<script>
	import tuiListCell from "@/components/list-cell/list-cell"
	import tuiButton from "@/components/extend/button/button"
	export default {
		components: {
			tuiListCell,
			tuiButton
		},
		data() {
			return {
				userInfo: null,
				is_login: true,
				is_open:false,
				
				textColor:'#bc0100',
			}
		},
		onLoad() {
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
			}
			this.getUser();
			this.is_login = this.$http.isLogin();
			this.getSetting();
		},
		methods: {
			openMsg(e){
				this.is_open = e.detail.value;
				this.$http.request({
					url:this.$api.update_setting,
					showLoading:true,
					method:'post',
					data:{
						setting_key:'wechat_notice',
						"data":{
							"is_open":this.is_open ? 1 : 0
						}
					}
				}).then(res => {
					if(res.code == 0){
						this.$http.toast(res.msg);
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			getSetting(){
				this.$http.request({
					url:this.$api.get_setting,
					showLoading:true,
					data:{
						setting_key:'wechat_notice'
					}
				}).then(res => {
					if(res.code == 0){
						res.data.data.is_open == 0 ? this.is_open = false : this.is_open = true;
					}else if(res.code == 98){
						this.is_open = true;
					}
				})
			},
			getUser() {
				// this.$http.jxmall({
				// 	_mall_id: 1,
				// 	r: 'api/user/user-info'
				// }).then(res => {
				// 	this.userInfo = res.data
				// }).catch(err => {
				// 	console.log(err)
				// })
			},
			logout() {
				uni.removeStorageSync("token");
				uni.removeStorageSync('userInfo');
				uni.clearStorageSync();
				this.$http.toast('登出成功')
				uni.reLaunch({
					url: '/pages/index/index'
				})
			},
			href(page) {
				let url = "";
				switch (page) {
					case 1:
						url = "./info"
						break;
					case 2:
						url = "./address/list"
						break;
					case 3:
						url = "/pages/user/payment/password"
						break;
					case 4:
						url = "/pages/about/about"
						break;
					default:
						break;
				}
				uni.navigateTo({
					url: url
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.tui-set-box {
		padding-bottom: 20rpx;
		color: #333;
	}

	.tui-list-cell {
		display: flex;
		align-items: center;
		padding: 24rpx 30rpx;
		font-size: 30rpx;
	}

	.tui-info-box {
		font-size: 34rpx;
	}

	.tui-avatar {
		width: 140rpx;
		height: 140rpx;
		margin-right: 20rpx;
		border-radius: 100rpx;
		border: 1rpx solid #000000;
	}

	.tui-mtop {
		margin-top: 20rpx;
	}

	.tui-exit {
		padding: 100rpx 24rpx;
	}
	.exit-btn{
		text-align: center;
		padding: 20rpx 0;
		color: #FFFFFF;
	}
</style>
