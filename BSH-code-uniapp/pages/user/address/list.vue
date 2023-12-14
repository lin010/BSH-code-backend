<template>
	<view class="tui-safe-area">
		<view class="address-nothing" v-if="addressList.length == 0">没有收货地址，快去添加一个吧~</view>
		<view class="tui-address" v-else>
			<block v-for="(item,index) in addressList" :key="index">
				<tui-list-cell padding="0" @tap="navTo(item)">
					<view class="tui-address-flex">
						<view class="tui-address-left">
							<view class="tui-address-main">
								<view class="tui-address-name tui-ellipsis">{{item.name}}</view>
								<view class="tui-address-tel">{{item.mobile}}</view>
							</view>
							<view class="tui-address-detail">
								<view class="tui-address-label" v-if="item.is_default==1">默认</view>
								<!-- <view class="tui-address-label" v-if="index!=2">{{["公司","住宅","其它"][index]}}</view> -->
								<text>{{item.user_address}}</text>
							</view>
						</view>
						<view class="tui-address-imgbox" @click.stop="editAddr(item.id)">
							<view class="iconfont icon-pinglun"></view>
						</view>
					</view>
				</tui-list-cell>
			</block>
		</view>

		<!-- 新增地址 -->
		<view class="tui-address-new">
			<view class="add-btn" @click="editAddr(0)" :style="{background:'#FF7104'}">+ 新增收货地址</view>
		</view>
	</view>
</template>

<script>
	import tuiButton from "@/components/extend/button/button"
	import tuiListCell from "@/components/list-cell/list-cell"
	export default {
		components: {
			tuiButton,
			tuiListCell
		},
		data() {
			return {
				img_url: this.$api.img_url,
				addressList: [],
				name: '',
				textColor:'#bc0100',
			}
		},
		onLoad(options) {
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
			}
			this.name = options.name;
		},
		onShow: function() {
			
			this.getList();
		},
		methods: {
			navTo(item) {
				console.log(item)
				let pages = getCurrentPages(),currPage,prevPage;
				uni.setStorageSync('addressID',item.id)
				// #ifdef MP || APP-PLUS
				if(pages.length > 0){
					currPage = pages[pages.length - 1].$vm;
					prevPage = pages[pages.length - 2].$vm; //上一个页面
					console.log(prevPage,'prevPageprevPage');
				}
				// #endif
				
				// #ifdef H5
				currPage = pages[pages.length - 1];
				prevPage = pages[pages.length - 2]; //上一个页面
				// #endif
				
				prevPage.user_address = item;
				if (this.name == 'picker_address') {
					uni.setStorageSync('picker_address', prevPage.user_address);
				}

				if (this.name == 'cart' || this.name == 'picker_address') {
					// this.$emit('submit');
					uni.navigateBack()
					// uni.redirectTo({
					// 	url:'/pages/order/submit?addressId='+item.id
					// })
				}
			},
			getList() {
				this.$http.request({
					url: this.$api.user.address,
					method: 'post',
					showLoading: true,
				}).then((res) => {
					console.log(res, 'resss');
					if (res.code == 0) {
						this.addressList = res.data.list;
					}
				})
			},
			editAddr(id) {
				if(id==0){
					uni.navigateTo({
						url: "/pages/user/address/edit?id=" + id+"&type="+1
					})
				}else{
					uni.navigateTo({
						url: "/pages/user/address/edit?id=" + id+"&type="+0
					})
				}
			}
		}
	}
</script>

<style>
	.address-nothing {
		color: #b3b3b3;
		text-align: center;
		position: absolute;
		top: 30%;
		left: 50%;
		transform: translateX(-50%);
		width: 100%;
	}

	.tui-address {
		width: 100%;
		padding-top: 20rpx;
		padding-bottom: 160rpx;
	}

	.tui-address-flex {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.tui-address-main {
		width: 600rpx;
		height: 70rpx;
		display: flex;
		font-size: 12pt;
		line-height: 86rpx;
		padding-left: 30rpx;
	}

	.tui-address-name {
		max-width: 340rpx;
		height: 60rpx;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.tui-address-tel {
		margin-left: 10rpx;
	}

	.tui-address-detail {
		font-size: 9pt;
		word-break: break-all;
		padding-bottom: 25rpx;
		padding-left: 25rpx;
		padding-right: 120rpx;
	}

	.tui-address-label {
		padding: 5rpx 8rpx;
		flex-shrink: 0;
		background: #e41f19;
		border-radius: 6rpx;
		color: #fff;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 10pt;
		line-height: 25rpx;
		transform: scale(0.8);
		transform-origin: center center;
		margin-right: 6rpx;
	}

	.tui-address-imgbox {
		width: 80rpx;
		height: 100rpx;
		position: absolute;
		display: flex;
		justify-content: center;
		align-items: center;
		right: 10rpx;
	}

	.tui-address-img {
		width: 40rpx;
		height: 40rpx;
	}

	.tui-address-new {
		width: 100%;
		position: fixed;
		left: 0;
		bottom: 0;
		z-index: 999;
		padding: 20rpx 25rpx 30rpx;
		box-sizing: border-box;
		background: #fafafa;
	}

	.tui-safe-area {
		padding-bottom: env(safe-area-inset-bottom);
	}
	.add-btn{
		text-align: center;
		line-height: 88rpx;
		height: 88rpx;
		color: #FFFFFF;
	}
</style>
