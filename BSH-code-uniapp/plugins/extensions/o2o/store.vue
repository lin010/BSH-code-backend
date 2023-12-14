<template>
	<view class="app">
		<div class="container">
			<image src="https://pic.downk.cc/item/5ecbaea9c2a9a83be591029f.jpg" mode="aspectFill" class="jx-bg"></image>
			<com-nav-bar @clickLeft="back" left-icon="back" title="" :status-bar="true" background-color="transparent" :border="false" color="#ffffff"></com-nav-bar>
			<view class="content">
				<view class="content-head">
					<view class="store-info">
						<view class="store-name">{{ '赵记传承 (新市店)' }}</view>
						<view class="store-delivery-time">
							<span class="iconfont icon-paisong" style="color: #F4AA0B;margin-right: 10rpx;"></span>
							<span class="text">配送约{{ '30' }}分钟</span>
						</view>
						<view class="store-notice">
							公告: {{ '新西兰车厘子是世界NO.1，这一点都不夸张！千万别拿全球顶级的新西兰车厘子和美国、智利比哦！品质和口感都没法比的！' }}
						</view>
						<view class="store-coupons flex">
							<view class="coupon flex flex-x-center flex-y-center" v-for="(item, i) in 2" :key="i">
								<view class="money">{{ '200' }}</view>
								<view class="text">领</view>
							</view>
						</view>
						<view class="store-discount flex">
							<view class="discount" v-for="(item, i) in 3" :key="i">{{ 33 + i }}减{{ 3 + i }}</view>
						</view>
						<image class="store-avatar" src="http://yingmlife-1302693724.cos.ap-guangzhou.myqcloud.com/uploads/images/original/20201216/15262b999e48acc5891864e3f2463cb0.jpg" mode="aspectFill"></image>
					</view>
				</view>
				<view class="content-bottom">
					<view class="menu flex">
						<view class="menu-item active"><view class="menu-title">点菜</view></view>
						<view class="menu-item"><view class="menu-title">评价</view></view>
						<view class="menu-item"><view class="menu-title">商家</view></view>
					</view>
					<view class="ads">
						<view class="box"><image src="../../images/extensions/o2o/shuiguotu.png" mode="aspectFill" class="ads-img"></image></view>
					</view>
					<view class="classify">
						<view class="page-body" :style="'height:' + height + 'px'">
							<scroll-view class="nav-left" scroll-y :style="'height:' + height + 'px'" :scroll-top="scrollLeftTop" scroll-with-animation>
								<view
									class="nav-left-item"
									@click="categoryClickMain(index)"
									:key="index"
									:class="index == categoryActive ? 'active' : ''"
									v-for="(item, index) in classifyData"
								>
									<span class="name">{{ item.name }}</span>
								</view>
							</scroll-view>
							<scroll-view class="nav-right" scroll-y :scroll-top="scrollTop" @scroll="scroll" :style="'height:' + height + 'px'" scroll-with-animation>
								<view v-for="(foods, index) in classifyData" :key="index" class="classify-box">
									<view :id="i == 0 ? 'first' : ''" class="nav-right-item" v-for="(item, i) in foods.foods" :key="i" @click="cart(item.name)">
										<image :src="item.icon" />
										<view class="item-info flex flex-col flex-grow-1">
											<view class="name flex-grow-1">{{ item.name }}</view>
											<view class="size">已售:{{ '189' }}件</view>
											<view class="amount">
												<span class="dot">¥</span>
												<span>{{ '169' }}</span>
											</view>
											<view class="abs-btn">
												<view class="flex">
													<span class="declined iconfont icon-jianhao" @click.stop="decrease()"></span>
													<span class="sum">{{ '111' }}</span>
													<span class="add iconfont icon-jiahao" @click.stop="add()"></span>
												</view>
											</view>
										</view>
									</view>
								</view>
							</scroll-view>
						</view>
					</view>
				</view>
			</view>
		</div>
		<view class="fixed">
			<view class="box flex-y-center flex-x-between">
				<view class="text" v-if="0 == sum"><span>未选购商品</span></view>
				<view class="text flex-y-center" style="color: #000000;" v-else>
					<span>支付:</span>
					<span class="sum">¥{{ '170.00' }}</span>
				</view>
				<view class="pay">
					<view class="btn flex-x-center flex-y-center" :class="sum == 0 ? 'ban' : 'submit'"><span>去支付</span></view>
				</view>
			</view>
		</view>

		<com-bottom-popup :show="showBottomPopup" @close="hideModal">
			<view class="popup-model">
				<view class="summary flex">
					<image src="http://jxmall.sinbel.top/web//uploads/images/original/20200608/bbe6ddad872e2eb47b78adf895df98c6.jpg" class="summary-img" mode="aspectFill"></image>
					<view class="price flex-col">
						<view class="amount bold flex-grow-1">
							<span class="dot">¥</span>
							{{ '145' }}
						</view>
						<view class="stock gray flex-grow-1">库存:{{ '40' }}</view>
						<!-- <view class="norm">已选:{{}}</view> -->
						<view class="norm gray flex-grow-1">请选择规格</view>
					</view>
				</view>
				
				<view class="popup-box">
					<!-- 规格 -->
					<view>
						<!-- <view v-if="!goodsData.use_attr">
							<block v-if="goodsData.attr_list">
								<view class="bold attr-title">{{goodsData.attr_list[0].attr_list[0].attr_group_name}}</view>
								<view class="attr-box">
									<view class="attr-item attr-active">
										{{goodsData.attr_list[0].attr_list[0].attr_name}}
									</view>
								</view>
							</block>
						</view>
						<view v-for="(item,index) in goodsData.attr_groups" :key='index' v-else>
							<view class="bold attr-title">{{item.attr_group_name}}</view>
							<view class="attr-box">
								<view class="attr-item" @tap="specCol(item.attr_group_id,items.attr_id,items)" :class="queryAttr(items) ?'attr-active':''"
								 v-for="(items,indexs) in item.attr_list" :key='indexs'>
									{{items.attr_name}}
								</view>
							</view>
						</view> -->
					</view>
					<!-- 规格-->
					<view class="number-box bold attr-title flex-y-center flex-x-between">
						<view class="attr-title">购买数量</view>
						<!-- <jx-numberbox :max="99" :min="1" :value="value" @change="change"></jx-numberbox> -->
						<numberbox :max="99" :min="1" :value="1" @change="change"></numberbox>
					</view>
				</view>

				<view class="popup-btn"><view class="sure-btn" @click="buy">立即下单</view></view>
			</view>
		</com-bottom-popup>
	</view>
</template>

<script>
import classifyData from './classify.data.js';
import Numberbox from "@/components/numberbox/numberbox";
export default {
	components :{
		Numberbox
	},
	data() {
		return {
			sum: 170,
			name: 'wkiwi',
			height: 0,
			categoryActive: 0,
			scrollTop: 0,
			scrollLeftTop: 0,
			// scrollHeight: 0,
			classifyData: classifyData,
			arr: [0, 584, 1168, 1752, 2336, 2805, 3274, 3858, 4442, 4911, 5380, 5734, 6203, 6672, 7017], //初始值，后边计算会根据手机适配覆盖
			leftItemHeight: 51, //49行会计算出新值进行覆盖
			navLeftHeight: 0, //左边scroll-view 内层nav的总高度
			diff: 0, //左边scroll-view 内层nav的总高度与视口之差
			tabBarHeight: 0, //如果此页面为Tab页面，自己改变高度值,,一般tab高度为51
			showBottomPopup: false
		};
	},
	created() {
		//如果你的分类数据为后台异步获取请	将下方代码放置你的数据回调中
		// this.$nextTick(()=>{
		// 	this.getHeightList();
		// })
	},
	onLoad: function() {
		this.height = uni.getSystemInfoSync().windowHeight - this.tabBarHeight;
	},
	onReady() {
		this.getHeightList();
	},
	methods: {
		buy() {
			this.showBottomPopup = false;
		},
		decrease() {
			console.log('减少');
		},
		add() {
			console.log('增加');
			this.showBottomPopup = true;
		},
		hideModal() {
			this.showBottomPopup = false;
		},
		change(e) {
			this.value = e.value;
		},
		getHeightList() {
			let self = this;
			let selectorQuery = uni.createSelectorQuery();
			selectorQuery.selectAll('.nav-left-item').boundingClientRect(function(rects) {
				self.leftItemHeight = rects[0].height;
				self.navLeftHeight = self.leftItemHeight * classifyData.length;
				self.diff = self.navLeftHeight - self.height;
			});
			selectorQuery
				.selectAll('.classify-box')
				.boundingClientRect(function(rects) {
					let arr = [0];
					let top = 0;
					rects.forEach(function(rect) {
						// rect.id      // 节点的ID
						// rect.dataset // 节点的dataset
						// rect.left    // 节点的左边界坐标
						// rect.right   // 节点的右边界坐标
						// rect.top     // 节点的上边界坐标
						// rect.bottom  // 节点的下边界坐标
						// rect.width   // 节点的宽度
						// rect.height  // 节点的高度
						top += rect.height;
						arr.push(top);
					});
					// console.log(arr);
					self.arr = arr;
				})
				.exec();
		},
		scroll(e) {
			let self = this;
			if (this.timeoutId) {
				clearTimeout(this.timeoutId);
			}
			this.timeoutId = setTimeout(function() {
				//节流
				self.scrollHeight = e.detail.scrollTop + 1 + self.height / 2;
				//+1不要删除，解决最后一项某种情况下翻到底部，左边按钮并不会切换至最后一个
				//若想使切换参考线为屏幕顶部请删除 self.height/2
				for (let i = 0; i < self.arr.length; i++) {
					let height1 = self.arr[i];
					let height2 = self.arr[i + 1];
					if (!height2 || (self.scrollHeight >= height1 && self.scrollHeight < height2)) {
						self.categoryActive = i;
						self.diff > 0 && (self.scrollLeftTop = Math.round((self.categoryActive * self.diff) / (classifyData.length - 1)));
						return false;
					}
				}
				self.categoryActive = 0;
				self.timeoutId = undefined;
			}, 10);
		},
		categoryClickMain(index) {
			this.categoryActive = index;
			this.scrollTop == this.arr[index] ? (this.scrollTop = this.scrollTop + 1) : (this.scrollTop = this.arr[index]); //防止两次相等造成点击不触发滚动时间
		},
		cart: function(text) {
			uni.showToast({
				title: text,
				icon: 'none'
			});
		},
		back(){
			this.navBack();
		}
	},
	components: {}
};
</script>

<style lang="scss" scoped>
.app {
	min-height: 100%;
	background-color: #f7f7f7;
}
.container {
	color: #ffffff;
	position: relative;

	.jx-bg {
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		width: 100%;
		height: 304rpx;
		opacity: 0.5;
	}
}
.content {
	position: relative;
	color: #000000;

	.content-head {
		padding: 0 20rpx;

		.store-info {
			position: relative;
			margin-top: 108rpx;
			background-color: #ffffff;
			padding: 28rpx 24rpx;
			border-radius: 25rpx;
			margin-bottom: 30rpx;

			.store-name {
				font-size: 36rpx;
				font-weight: 500;
				line-height: 60rpx;
			}
			.store-delivery-time,
			.store-delivery-time .iconfont {
				font-size: 9pt;
			}
			.store-notice {
				color: #828282;
				font-size: 9pt;
				margin-bottom: 26rpx;
				overflow: hidden;
				text-overflow: ellipsis;
				display: -webkit-box;
				-webkit-box-orient: vertical;
				-webkit-line-clamp: 2; //显示的行
			}
			.store-coupons {
				color: #ffffff;
				margin-bottom: 10rpx;
				.coupon {
					width: 136rpx;
					height: 42rpx;
					margin-right: 30rpx;
					background-image: url('../../images/extensions/o2o/youhuiquan.png');
					background-size: 100% 100%;
					background-repeat: no-repeat;
					font-size: 9pt;
					.money {
						font-weight: bold;
						margin-right: 12rpx;
					}
					.text {
						margin-left: 20rpx;
						transform: scale(0.8);
					}
				}
			}
			.store-discount {
				font-size: 9pt;
				color: #bc0100;
				.discount {
					height: 27rpx;
					padding: 0 8rpx;
					border-radius: 5rpx;
					border: 1rpx solid #bc0100;
					margin-right: 9rpx;
					line-height: 27rpx;
				}
			}

			.store-avatar {
				position: absolute;
				top: -50rpx;
				right: 20rpx;
				width: 100rpx;
				height: 100rpx;
				background: #ffffff 100% no-repeat;
			}
		}
	}

	.menu {
		padding: 0 30rpx;
		border-bottom: 2rpx solid #e6e6e6;

		.menu-item {
			width: 150rpx;
			line-height: 43rpx;

			&.active .menu-title {
				color: #bc0100;

				&::before {
					position: absolute;
					bottom: 0;
					left: 10rpx;
					content: '';
					width: 37rpx;
					height: 4rpx;
					background: #bc0100;
				}
			}
		}
		.menu-title {
			color: #3e3e3e;
			font-size: 11pt;
			padding: 10rpx 0;
			position: relative;
		}
	}

	.ads {
		padding: 20rpx 30rpx;

		.box {
			height: 250rpx;
			border-radius: 20rpx;
		}
		.ads-img {
			width: 100%;
			height: 100%;
			background-size: 100% 100%;
			background-repeat: no-repeat;
		}
	}
}
.fixed {
	position: fixed;
	bottom: 0;
	left: 0;
	right: 0;
	height: 112rpx;
	background-color: #ffffff;

	.box {
		height: 100%;
		padding: 0 16rpx;

		.text {
			font-size: 12pt;
			color: #999999;

			.sum {
				margin-left: 10rpx;
				font-size: 44rpx;
				color: #bc0100;
				font-weight: bold;
			}
		}
		.btn {
			font-size: 11pt;
			color: #ffffff;
			width: 208rpx;
			height: 80rpx;
			border-radius: 40px;

			&.submit {
				background: linear-gradient(-30deg, #d6100d, #f14822);
			}
			&.ban {
				background: #999999;
			}
		}
	}
}

.page-body {
	display: flex;
	overflow: hidden;
	margin-bottom: 120rpx;

	.nav {
		display: flex;
		width: 100%;
	}

	.nav-left {
		width: 190rpx;
		background: #ffffff;
	}

	.nav-left-item {
		height: 100rpx;
		font-size: 25rpx;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 0 20rpx;
		&.active {
			color: #ffffff;
			.name {
				width: 146rpx;
				height: 50rpx;
				line-height: 50rpx;
				text-align: center;
				background: linear-gradient(-90deg, #b92a20, #de593f);
				border-radius: 25rpx;
			}
		}
	}
	.nav-left-item:last-child {
		border-bottom: none;
	}
	.nav-right {
		margin-left: 10rpx;
		background: #ffffff;
	}
	.box {
		display: flex;
		flex-direction: column;
		overflow: hidden;
		border-bottom: 20rpx solid #f3f3f3;
		/* min-height: 100vh; */
		/*若您的子分类过少想使得每个子分类占满屏请放开上边注释 */
	}
	.box:last-child {
		border: none;
		min-height: 100vh;
	}
	.nav-right-item {
		display: flex;
		font-size: 28rpx;
		background: #ffffff;
		padding: 40rpx 0;
		margin-left: 10rpx;
		border-bottom: 1rpx solid #f2f2f2;
		height: 246rpx;

		.item-info {
			position: relative;
			margin-right: 30rpx;

			.abs-btn {
				position: absolute;
				bottom: 0;
				right: 0;
				height: 20px;
				line-height: 20px;

				.declined {
					color: #cdcdcd;
				}
				.add {
					color: #bc0100;
				}
				.sum {
					margin: 0 12rpx;
				}
				.iconfont {
					font-size: 20px;
				}
			}
		}

		.name {
			font-size: 11pt;
			width: 331rpx;
			overflow: hidden;
			text-overflow: ellipsis;
			display: -webkit-box;
			-webkit-box-orient: vertical;
			-webkit-line-clamp: 2; //显示的行
			line-height: 20px;
		}
		.size {
			font-size: 9pt;
			color: #999999;
			padding: 6rpx 0;
		}
		.amount {
			color: #bc0100;
			font-size: 13pt;
			line-height: 41rpx;
			.dot {
				font-size: 9pt;
			}
		}
	}

	.nav-right-item image {
		width: 167rpx;
		height: 167rpx;
		border-radius: 10rpx;
		margin-right: 10rpx;
	}
}

.popup-model {
	color: #000000;

	.summary {
		padding: 30rpx 29rpx;
		border-bottom: 2rpx solid #F7F7F7;
		
		.summary-img {
			width: 220rpx;
			height: 220rpx;
			border-radius: 5rpx;
			margin-right: 40rpx;
		}
		.amount {
			color: #bc0100;
			font-size: 14pt;

			.dot {
				font-size: 11pt;
				margin-right: 8rpx;
			}
		}

		.stock {
			font-size: 11pt;
		}

		.norm {
			font-size: 9pt;
		}
	}
	
	.popup-box{
		padding: 0 56rpx;
		font-size: 10pt;
		margin-bottom: 48rpx;
		
		.attr-box{
			padding: 20rpx 0;
		}
		
		.attr-item {
			max-width: 100%;
			min-width: 148rpx;
			height:65rpx;
			display: -webkit-inline-flex;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			background: #f7f7f7;
			padding: 0 26rpx;
			border-radius: 10rpx;
			margin-right: 20rpx;
			margin-bottom: 20rpx;
			font-size: 9.5pt;
		}
		
		.attr-active {
			background: #fcedea !important;
			color: #e41f19;
			font-weight: bold;
			position: relative;
			border-radius: 10rpx;
		}
		
		.attr-active::after {
			content: "";
			position: absolute;
			border: 1rpx solid #e41f19;
			width: 100%;
			height: 100%;
			border-radius: 10rpx;
			left: 0;
			top: 0;
		}
	}
	.number-box{
		padding: 20rpx 0 30rpx 0;
	}
	.attr-title {
		padding: 10rpx 0;
		color: #000000;
	}
	
	.popup-btn {
		font-size: 9pt;
		color: #ffffff;
		padding: 0 29rpx;
		margin-bottom: 81rpx;
		.sure-btn {
			height: 90rpx;
			line-height: 90rpx;
			text-align: center;
			background-color: #bc0100;
			border-radius: 45rpx;
		}
	}
}
.gray {
	color: #808080;
}
.bold {
	font-weight: bold;
}
::-webkit-scrollbar {
	/*取消小程序的默认导航条样式*/
	width: 0;
	height: 0;
	color: transparent;
}
</style>
