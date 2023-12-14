<template>
	<view class="jx-cell-class jx-list-cell" :class="{'jx-cell-arrow':arrow,'jx-cell-last':last,'jx-line-left':lineLeft,'jx-line-right':lineRight,'jx-radius':radius}" :hover-class="hover?'jx-cell-hover':''"
	 :style="{background: bgcolor,fontSize: size+'rpx',color:color,padding:padding}" :hover-stay-time="150" @tap="handleClick">
		<slot></slot>
	</view>
</template>

<script>
	export default {
		name: "jxListCell",
		props: {
			 //是否有箭头
			arrow: {
				type: Boolean,
				default: false
			},
			//是否有点击效果
			hover: {
				type: Boolean,
				default: true 
			},
			//left 30rpx
			lineLeft:{
				type: Boolean,
				default: true
			},
			//right 30rpx
			lineRight:{
				type: Boolean,
				default: false
			},
			padding:{
				type:String,
				default:"26rpx 30rpx"
			},
			last: {
				type: Boolean,
				default: false //最后一条数据隐藏线条
			},
			radius:{
				type:Boolean,
				default:false
			},
			bgcolor: {
				type: String,
				default: "#fff" //背景颜色
			},
			size: {
				type: Number,
				default: 32 //字体大小
			},
			color: {
				type: String,
				default: "#333" //字体颜色
			},
			index: {
				type: Number,
				default: 0
			}
		},
		methods: {
			handleClick() {
				this.$emit('click', {
					index: this.index
				});
			}
		}
	}
</script>

<style>
	.jx-list-cell {
		position: relative;
		width: 100%;
		box-sizing: border-box;
		overflow: hidden;
		display: flex;
		align-items: center;
	}
	.jx-radius{
		border-radius: 20rpx;
		overflow: hidden;
	}

	.jx-cell-hover {
		background: #f7f7f9 !important;
	}

	.jx-list-cell::after {
		content: '';
		position: absolute;
		/* border-bottom: 1rpx solid #eaeef1; */
		-webkit-transform: scaleY(0.5);
		transform: scaleY(0.5);
		bottom: 0;
		right: 0;
		left:0;
	}
	.jx-line-left::after{
		left: 30rpx !important;
	}
	
	.jx-line-right::after{
		right: 30rpx !important;
	}

	.jx-cell-last::after {
		border-bottom: 0 !important;
	}

	.jx-list-cell.jx-cell-arrow:before {
		content: " ";
		height: 11px;
		width: 11px;
		border-width: 2px 2px 0 0;
		border-color: #b2b2b2;
		border-style: solid;
		-webkit-transform: matrix(0.5, 0.5, -0.5, 0.5, 0, 0);
		transform: matrix(0.5, 0.5, -0.5, 0.5, 0, 0);
		position: absolute;
		top: 50%;
		margin-top: -7px;
		right: 30rpx;
	}
</style>
