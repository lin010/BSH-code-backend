<template>
	<view @touchmove.stop.prevent>
		<view class="jx-modal-box" :style="{width:width,padding:padding,borderRadius:radius}" :class="[(fadein || show)?'jx-modal-normal':'jx-modal-scale',show?'jx-modal-show':'']">
			<view v-if="!custom">
				<view class="jx-modal-title" v-if="title">{{title}}</view>
				<view class="jx-modal-content" :class="[title?'':'jx-mtop']" :style="{color:color,fontSize:size+'rpx'}">{{content}}</view>
				<view class="jx-modalBtn-box" :class="[button.length!=2?'jx-flex-column':'']">
					<block v-for="(item,index) in button" :key="index">
						<!-- <button class="jx-modal-btn" :class="['jx-'+(item.type || 'primary')+(item.plain?'-outline':''),button.length!=2?'jx-btn-width':'',button.length>2?'jx-mbtm':'',shape=='circle'?'jx-circle-btn':'']"
						 :hover-class="'jx-'+(item.plain?'outline':(item.type || 'primary'))+'-hover'" :data-index="index" @tap="handleClick">{{item.text || "确定"}}</button> -->
						 
						 <button class="jx-modal-btn" :class="[button.length!=2?'jx-btn-width':'',button.length>2?'jx-mbtm':'',shape=='circle'?'jx-circle-btn':'']"
						 :style="{color:item.plain?item.col:'#000',background:item.plain?'#ffffff':item.col,border:'1px solid'+item.col}"
						  hover-class="none" :data-index="index" @tap="handleClick">{{item.text || "确定"}}</button>
					</block>
				</view>
			</view>
			<view v-else>
				<slot></slot>
			</view>
		</view>
		<view class="jx-modal-mask" :class="[show?'jx-mask-show':'']" @tap="handleClickCancel"></view>
	</view>
</template>

<script>
	export default {
		name: "modal",
		props: {
			diyBtn:{
				type: Boolean,
				default: true
			},
			//是否显示
			show: {
				type: Boolean,
				default: false
			},
			width: {
				type: String,
				default: "84%"
			},
			padding: {
				type: String,
				default: "40rpx 64rpx"
			},
			radius: {
				type: String,
				default: "24rpx"
			},
			//标题
			title: {
				type: String,
				default: ""
			},
			//内容
			content: {
				type: String,
				default: ""
			},
			//内容字体颜色
			color: {
				type: String,
				default: "#999"
			},
			//内容字体大小 rpx
			size: {
				type: Number,
				default: 28
			},
			//形状 circle, square
			shape: {
				type: String,
				default: 'square'
			},
			button: {
				type: Array,
				default: function() {
					return [{
						text: "取消",
						type: "red",
						plain: true //是否空心
					}, {
						text: "确定",
						type: "red",
						plain: false
					}]
				}
			},
			//点击遮罩 是否可关闭
			maskClosable: {
				type: Boolean,
				default: true
			},
			//自定义弹窗内容
			custom: {
				type: Boolean,
				default: false
			},
			//淡入效果，自定义弹框插入input输入框时传true
			fadein: {
				type: Boolean,
				default: false
			}
		},
		data() {
			return {

			};
		},
		methods: {
			handleClick(e) {
				if (!this.show) return;
				const dataset = e.currentTarget.dataset;
				this.$emit('click', {
					index: Number(dataset.index)
				});
			},
			handleClickCancel() {
				if (!this.maskClosable) return;
				this.$emit('cancel');
			}
		}
	}
</script>

<style scoped>
	.jx-modal-box {
		position: fixed;
		left: 50%;
		top: 50%;
		margin: auto;
		background: #fff;
		z-index: 9999998;
		transition: all 0.3s ease-in-out;
		opacity: 0;
		box-sizing: border-box;
		visibility: hidden;
	}

	.jx-modal-scale {
		transform: translate(-50%, -50%) scale(0);
	}

	.jx-modal-normal {
		transform: translate(-50%, -50%) scale(1);
	}

	.jx-modal-show {
		opacity: 1;
		visibility: visible;
	}

	.jx-modal-mask {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: rgba(0, 0, 0, 0.2);
		z-index: 9999996;
		transition: all 0.3s ease-in-out;
		opacity: 0;
		visibility: hidden;
	}

	.jx-mask-show {
		visibility: visible;
		opacity: 1;
	}

	.jx-modal-title {
		text-align: center;
		font-size: 34rpx;
		color: #333;
		padding-top: 20rpx;
		font-weight: bold;
	}

	.jx-modal-content {
		text-align: center;
		color: #999;
		font-size: 28rpx;
		padding-top: 20rpx;
		padding-bottom: 60rpx;
	}

	.jx-mtop {
		margin-top: 30rpx;
	}

	.jx-mbtm {
		margin-bottom: 30rpx;
	}

	.jx-modalBtn-box {
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: space-between
	}

	.jx-flex-column {
		flex-direction: column;
	}

	.jx-modal-btn {
		width: 46%;
		height: 68rpx;
		line-height: 68rpx;
		position: relative;
		border-radius: 10rpx;
		font-size: 24rpx;
		overflow: visible;
		margin-left: 0;
		margin-right: 0;
	}

	.jx-modal-btn::after {
		content: "";
		position: absolute;
		width: 200%;
		height: 200%;
		-webkit-transform-origin: 0 0;
		transform-origin: 0 0;
		-webkit-transform: scale(0.5, 0.5);
		transform: scale(0.5, 0.5);
		left: 0;
		top: 0;
		border-radius: 20rpx;
	}

	.jx-btn-width {
		width: 80% !important;
	}

	.jx-primary {
		background: #5677fc;
		color: #fff;
	}

	.jx-primary-hover {
		background: #4a67d6;
		color: #e5e5e5;
	}

	.jx-primary-outline {
		color: #5677fc;
		background: none;
	}

	.jx-primary-outline::after {
		border: 1px solid #5677fc;
	}

	.jx-danger {
		background: #ed3f14;
		color: #fff;
	}

	.jx-danger-hover {
		background: #d53912;
		color: #e5e5e5;
	}

	.jx-danger-outline {
		color: #ed3f14;
		background: none;
	}

	.jx-danger-outline::after {
		border: 1px solid #ed3f14;
	}

	.jx-red {
		background: #e41f19;
		color: #fff;
	}

	.jx-red-hover {
		background: #c51a15;
		color: #e5e5e5;
	}

	.jx-red-outline {
		color: #e41f19;
		background: none;
	}

	.jx-red-outline::after {
		border: 1px solid #e41f19;
	}

	.jx-warning {
		background: #ff7900;
		color: #fff;
	}

	.jx-warning-hover {
		background: #e56d00;
		color: #e5e5e5;
	}

	.jx-warning-outline {
		color: #ff7900;
		background: none;
	}

	.jx-warning-outline::after {
		border: 1px solid #ff7900;
	}

	.jx-green {
		background: #19be6b;
		color: #fff;
	}

	.jx-green-hover {
		background: #16ab60;
		color: #e5e5e5;
	}

	.jx-green-outline {
		color: #19be6b;
		background: none;
	}

	.jx-green-outline::after {
		border: 1px solid #19be6b;
	}

	.jx-white {
		background: #fff;
		color: #333;
	}

	.jx-white-hover {
		background: #f7f7f9;
		color: #666;
	}

	.jx-white-outline {
		color: #333;
		background: none;
	}

	.jx-white-outline::after {
		border: 1px solid #333;
	}

	.jx-gray {
		background: #ededed;
		color: #999;
	}

	.jx-gray-hover {
		background: #d5d5d5;
		color: #898989;
	}

	.jx-gray-outline {
		color: #999;
		background: none;
	}

	.jx-gray-outline::after {
		border: 1px solid #999;
	}

	.jx-outline-hover {
		opacity: 0.6;
	}

	.jx-circle-btn {
		border-radius: 40rpx !important;
	}

	.jx-circle-btn::after {
		border-radius: 80rpx !important;
	}
</style>
