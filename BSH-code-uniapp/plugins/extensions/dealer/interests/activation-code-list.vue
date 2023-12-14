<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="我的激活码" :status-bar="true" background-color="#ffffff" :border="true" color="#000000"></com-nav-bar>
		<view class="container">
			<view class="tabs flex">
				<view class="tab flex-grow-1 flex-x-center flex-y-center" :style="{color:item.type == type ?textColor:''}" @click="switchTab(item.type)" v-for="(item, i) in tabs" :key="i">
					<span class="text" :style="{'border-bottom':item.type == type ?'1px solid'+textColor:''}">{{ item.name }}</span>
				</view>
			</view>
			<view class="list">
				<view class="item flex flex-x-between flex-y-center" v-for="(item, i) in dataList" :key="i">
					<view class="code">{{ item.text }}</view>
					<view class="btn click" :style="{background:textColor}" v-if="item.type == 0" @click="copy(item.text)">点击复制</view>
					<view class="btn use" v-if="item.type == 1">已使用</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
const clipboard = require('@/components/common/tui-clipboard/tui-clipboard.js');
export default {
	data() {
		return {
			tabs: [],
			dataList: [],
			type: 1,
			
			textColor:'#bc0100',
			bg_url:'',
		};
	},
	onLoad() {
		this.textColor = this.globalSet('textCol');
		this.bg_url = this.globalSet('imgUrl');
		
		this.tabs = [
			{
				name: '省级代理',
				type: 1
			},
			{
				name: '市级代理',
				type: 2
			},
			{
				name: '县级代理',
				type: 3
			},
			{
				name: '门店代理',
				type: 4
			}
		];
		let temp = [];
		for (let i = 0; i < 9; i++) {
			temp.push({
				id: i,
				text: `aSdF13${i}${i + 1}`,
				type: i == 8 ? 1 : 0
			});
		}
		this.dataList = temp;
	},
	methods: {
		switchTab(type) {
			this.type = type;
		},
		copy(data) {
			let temp = data;
			clipboard.getClipboardData(data, res => {
				// #ifdef H5 || MP-ALIPAY
				if (res) {
					this.$http.toast('复制成功');
				} else {
					this.$http.toast('复制失败');
				}
				// #endif
			});
		},
		back(){
			this.navBack();
		}
	}
};
</script>

<style lang="scss">
.app {
	width: 100%;
	height: 100%;
	background-color: #f7f7f7;
}
.container {
	color: #333333;
	.tabs,
	.list {
		background-color: #ffffff;
	}
	.tabs {
		margin-bottom: 20rpx;

		.tab {
			height: 100rpx;
			border-right: 1rpx solid #f2f2f2;

			.text {
				position: relative;
			}

			&:last-child {
				border-right: 0;
			}
		}
	}
	.list {
		padding: 0 30rpx;

		.item {
			height: 100rpx;
			border-bottom: 1rpx solid #f2f2f2;

			&:last-child {
				border-bottom: 0;
			}
		}
	}
	.btn {
		width: 152rpx;
		height: 48rpx;
		border-radius: 24rpx;
		font-size: 9pt;
		line-height: 48rpx;
		text-align: center;
		color: #ffffff;
		&.click {
			background-color: #bc0100;
		}
		&.use {
			background-color: #999999;
		}
	}
}
</style>
