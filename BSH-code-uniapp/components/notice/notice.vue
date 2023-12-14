<template>
	<view class="notice" @click="openDialog">
		<view class="diy-notice flex flex-y-center" :style="{'background':data.background,'color': data.textColor}">
			<image :src="data.icon" style="width: 36rpx;height: 36rpx;margin-right: 24rpx;"></image>
			<view class="title">{{data.name}}</view>
			<view class="content flex">
				<view class="scorll-view" style="margin: 0 12rpx;">
					<!-- <pre class="msg">
						{{data.content}}
					</pre> -->
					{{data.content}}
				</view>
			</view>
			<view class="iconfont icon-xiala" style="width: 20rpx;line-height: 36rpx;"></view>
		</view>
		<view class="notice-dialog" v-if="showDialog" :class="showDialog ? 'fixed' : ''">
			<view class="content" style="width: 1200rpx;overflow: hidden;">
				<image :src="data.headerUrl" style="width: 100%;height: 300rpx;display: block;overflow: hidden;"></image>
				<view class="dialog-content">
					<view class="msg" style="margin-bottom: 24px;max-height: 600px;overflow-y: auto;">{{data.content}}</view>
					<view class="btn">
						<view class="text" :style="{'width': data.btnWidth+'px','line-height':data.btnHeight+'px','border-radius': data.btnRadius+'px','color':data.btnTextColor,'background': data.btnColor}">{{data.btnText}}</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			value: Object
		},
		data() {
			return {
				data: {},
				showDialog: false
			}
		},
		created() {
			this.data = JSON.parse(JSON.stringify(this.value));
			if(this.data.icon){
				this.data.icon = this.checkHttpUrl(this.data.icon);
			}
			if(this.data.headerUrl){
				this.data.headerUrl = this.checkHttpUrl(this.data.headerUrl);
			}
			//console.log(this.data)
		},
		methods: {
			openDialog() {
				this.showDialog = !this.showDialog;
			}
		}
	}
</script>

<style lang="scss" scoped>
	.notice{
		overflow: hidden;
	}
	.diy-notice {
		height: 80rpx;
		padding: 0 24rpx;
		flex-direction: row;
		justify-content: space-between;
		
		.title {
			flex-grow: 0;
			width: auto;
		}
		
		.content {
			margin: 0px 24rpx;
			flex-grow: 1;
			font-size: 11pt;
			flex-direction: row;
			overflow-x: hidden;
			
			.scorll-view {
				width: 0;
				flex-grow: 1;
				transform: translateX(100%);
				animation: rolling 12s linear infinite;
				.msg{
					// transform: translateX(100%);
					// animation: rolling 12s linear infinite;
				}
			}

			view {
				white-space: nowrap;
				// overflow-x: hidden;
			}
		}
	}

	@keyframes rolling {
		0% {
			transform: translateX(100%);
		}

		100% {
			transform: translateX(-170%);
		}
	}

	.notice-dialog {
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		// border-radius: 80px;
		zoom: 0.5;
		font-size: 28px;
		position: relative;
		z-index: 999999;

		&.fixed {

			position: fixed;
			top: 0px;
			left: 0;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.content {
			border-radius: 30rpx;
			overflow: hidden;

			.dialog-content {
				// width: 100%;
				max-height: 800px;
				background-color: #FFFFFF;
				padding: 48rpx;

				.msg {
					word-break: break-all;
				}

				.btn {
					display: flex;
					align-items: center;
					justify-content: center;

					.text {
						text-align: center;
					}
				}
			}
		}
	}
</style>
