<template>
	<view class="tui-safe-area">
		<view v-if="mchList.length > 0" class="tui-address">
			<block v-for="(item,index) in mchList" :key="index">
				<tui-list-cell @tap="chooseMch(item)" padding="0" style="margin-bottom:20rpx">
					<view class="tui-address-flex">
						<view class="tui-address-left">
							<view class="tui-address-main">
								<view class="tui-address-name tui-ellipsis">{{item.name}}</view>
								<view class="tui-address-tel"></view>
							</view>
							<view class="tui-address-detail"></view>
						</view>
						<view class="tui-address-imgbox"> 
							<view class="tui-address-btn"></view>
						</view>
					</view>
				</tui-list-cell>
			</block>
		</view>
		<view v-else class="list-empty"><main-nomore text="暂无数据" :visible="true" bgcolor="#fff"></main-nomore></view>
		
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
				mchList: []
			}
		},
		onLoad() {
			this.loadMchs();
		},
		methods: {
			chooseMch(item){
				uni.setStorageSync("x-sub-mch-id", item.id);
				uni.navigateTo({
					url:'../../personalCentre'
				})
			},
			loadMchs(){
				this.$http.request({
					showLoading: true,
					url: this.$api.moreShop.subAccountMchList,
					method: 'POST',
					data: {}
				}).then(res => {
					if (res.code === 0) {
						this.mchList = res.data.mch_list;
					}else{
						this.$http.toast(res.msg);
					}
				});
			}
		}
	}
</script>

<style>
	
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
	
	.tui-address-btn{
		text-align: center;
		font-weight: 400;
	}
</style>
