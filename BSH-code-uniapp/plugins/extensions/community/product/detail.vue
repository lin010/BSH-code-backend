<template>
	<view class="app">
		<com-nav-bar @clickLeft="back" left-icon="back" title="商品详情" :status-bar="true" background-color="#FFFFFF" :border="true" color="#000000"></com-nav-bar>
		<com-tabs
			:tabs="navbar"
			:height="88"
			bottom="12rpx"
			color="#424242"
			selectedColor="#BC0100"
			sliderBgColor="#BC0100"
			:currentTab="currentTab > 1 ? 0 : currentTab"
			@change="tabsChange"
			itemWidth="50%"
		></com-tabs>
		<view class="goods" v-if="currentTab == 0">
			<view class="goods-imgs">
				<!--banner-->
				<view class="jx-banner-swiper">
					<swiper :autoplay="true" :interval="5000" :duration="150" :circular="true" :style="{ height: scrollH + 'px' }" @change="bannerChange">
						<!-- <swiper :autoplay="true" :interval="5000" :duration="150" :circular="true" style="height: 750rpx;" @change="bannerChange"> -->
						<block v-for="(item, b_index) in goodsData.pic_list" :key="b_index">
							<swiper-item :data-index="b_index"><image :src="item.pic_url" class="jx-slide-image" /></swiper-item>
						</block>
					</swiper>
					<jx-tag type="translucent" shape="circle" size="small">{{ bannerIndex + 1 }}/{{ bannerLength }}</jx-tag>
				</view>
				<!--banner-->
			</view>
			<view class="goods-state flex-x-between flex-y-center" :class="goods_state == 0 ? 'presell' : 'onsale'">
				<view class="flex">
					<view class="dot">¥</view>
					<view class="current-price">{{ '7.99' }}</view>
					<view class="original-price">¥{{ '12.5' }}</view>
				</view>
				<view class="flex-col flex-y-center">
					<view class="msg">距离本商品结束还剩:</view>
					<view class="tag-count-down flex-x-center">
						<jx-countdown
							:time="2000"
							:width="48"
							:height="40"
							bcolor="transparent"
							:color="goodsStateClass"
							:colon-color="goodsStateClass"
							:size="32"
							:colon-size="32"
							style="align-items: unset;"
						></jx-countdown>
					</view>
				</view>
			</view>
			<view class="goods-info">
				<view class="goods-introduce flex">
					<view class="flex-grow-1">
						<view class="goods-name">{{ '广州酒家利口福核桃包' }}</view>
					</view>
					<view class="goods-popularity flex flex-grow-0">
						<view class="line"></view>
						<view class="num-text flex-col flex-y-center">
							<view class="num">5.4万</view>
							<view class="text">人气值</view>
						</view>
					</view>
				</view>
				<view class="time-msg flex-x-between">
					<view class="time">
						<view>预售时间：{{ '07月30日' }}</view>
						<view>提货时间：{{ '07月31日' }}</view>
					</view>
					<view class="flex-col">
						<view>限量{{ '10500' }}份</view>
						<view class="onsale-item flex" v-if="goods_state == 1">
							<view>今日已售</view>
							<view class="num">{{ '2390' }}</view>
							<view>份</view>
						</view>
					</view>
				</view>
			</view>
			
			<!-- 规格 / 评价 -->
			<view class="specification" v-if="goods_state == 1">
				<view class="basic-info margin-top-7px">
					<view class="jx-list-cell" @click="showPopup">
						<view class="jx-bold jx-cell-title">已选</view>
						<view class="selected-box" v-if="goodsData.use_attr == 1">{{ strName }}</view>
						<view class="selected-box" v-else>
							<block v-if="goodsData.attr_list">{{ goodsData.attr_list[0].attr_list[0].attr_name }}</block>
						</view>
						<view class="iconfont icon-xiala"></view>
					</view>
					<view class="jx-list-cell last" style="padding: 12rpx 30rpx;" v-if="goodsData.service_list != 0">
						<view class="jx-bold jx-cell-title">服务</view>
						<view class="selected-box">
							<view class="tag line" v-for="(item, index) in goodsData.service_list" :key="index">
								<view class="iconfont icon-dagou" style=""></view>
								<view class="text">{{ item }}</view>
							</view>
						</view>
					</view>
				</view>

				<view class="assess-content margin-top-7px" v-if="commentsData && commentsData.length">
					<view class="jx-list-cell last jx-between">
						<view class="jx-bold user-assess-title">用户评价({{ commentsData.length }})</view>
						<view class="flex-y-center" @click="common(4)">
							<text class="tui-cmt-all">{{ goodRate }}%满意</text>
							<view class="iconfont icon-xiala"></view>
						</view>
					</view>
					<view class="tui-cmt-content tui-padding">
						<view class="tags">
							<jx-tag v-for="(item, i) in commentCount" :key="i" class="tag" type="gold" :shape="'circle'">{{ item.name }}({{ item.count }})</jx-tag>
						</view>
						<scroll-view class="user-assess-list flex" scroll-x="true" enable-flex="ture">
							<view class="items flex">
								<view class="item" v-for="(item, i) in _commentsCopy" :key="i">
									<view class="user flex-y-center">
										<image :src="item.avatar" class="acatar"></image>
										<view>{{ item.nickname }}</view>
										<jx-rate class="rate" :current="item.score" :size="14" normal="#ccc" active="#DDB052" :disabled="false"></jx-rate>
									</view>
									<view class="assess">{{ item.content }}</view>
								</view>
								<view class="item last" @click="common(4)">
									查看更多评价
									<span class="icon iconfont icon-gengduo1"></span>
								</view>
							</view>
						</scroll-view>
					</view>
				</view>
			</view>
			<!-- 规格 / 评价 -->

			<view class="goods-detail">
				<view class="title flex flex-x-center flex-y-center">
					<view class="str"></view>
					<view class="text">商品详情</view>
					<view class="str"></view>
				</view>
				<view class="goods-furnish flex">
					<view class="goods-furnish-item">供应商：罗志强经营部</view>
					<view class="goods-furnish-item">品牌：广州酒家</view>
					<view class="goods-furnish-item">规格：500g/袋</view>
					<view class="goods-furnish-item">产地：中国</view>
				</view>
			</view>
			<view class="goods-list-imgs"><image :src="item.pic_url" mode="widthFix" class="goods-item-img" v-for="(item, i) in goodsData.pic_list"></image></view>
		</view>
		<view class="purchased" v-else>
			<view class="goods-statistics flex flex-x-center flex-y-center">
				<view>目前共</view>
				<view class="num">{{ '1873' }}</view>
				<view>人参与购买，商品共销售</view>
				<view class="num">{{ '2397' }}</view>
				<view>份</view>
			</view>
			<view class="purchased-list">
				<view class="purchased-item flex flex-y-center" v-for="(item, i) in 9">
					<image :src="''" class="user-avatar"></image>
					<view class="purchased-user flex-grow-1">
						<view class="purchased-user-name">{{ '糯*' }}</view>
						<view class="purchased-time">{{ '2020-06-13 10:51:23' }}</view>
					</view>
					<view class="purchased-quantity flex">
						<view class="num">{{ '1' }}</view>
						<view class="text">份</view>
					</view>
				</view>
			</view>
		</view>

		<!--底部操作栏-->
		<view class="jx-operation">
			<view class="jx-operation-left jx-col-5">
				<view class="jx-operation-item" hover-class="opcity" :hover-stay-time="150">
					<view class="iconfont icon-kefu2"></view>
					<view class="jx-operation-text jx-scale-small">客服</view>
				</view>
				<view class="jx-operation-item" hover-class="opcity" :hover-stay-time="150">
					<view class="iconfont icon-dianpu"></view>
					<view class="jx-operation-text jx-scale-small" @click="">店铺</view>
				</view>
				<view class="jx-operation-item" hover-class="opcity" @click="" :hover-stay-time="150">
					<view class="iconfont icon-gouwuche"></view>
					<view class="jx-operation-text jx-scale-small">购物车</view>
					<view class="red-dots">{{ '2' }}</view>
				</view>
			</view>
			<view class="jx-operation-right jx-right-flex jx-col-7 jx-btnbox-4" v-if="goods_state == 0">
				<view class="jx-btn circle early-join" style="height: 80%;" @click="addShoppingCart()">提前加入购物车</view>
			</view>
			<view class="jx-operation-right jx-right-flex jx-col-7 jx-btnbox-4" v-else>
				<view class="jx-btn radius-left show-popup" style="height: 80%;" @click="showPopup(1)">加入购物车</view>
				<view class="jx-btn radius-right submit" style="height: 80%;" @click="showPopup(2)">立即下单</view>
			</view>
		</view>
		<!--底部操作栏-->
	</view>
</template>

<script>
export default {
	components: {
		jxTag: () => import('@/components/tag/tag.vue'),
		jxCountdown: () => import('@/components/countdown/countdown')
	},
	data() {
		return {
			goods_state: 0, // 商品状态 0预售 1直播
			currentTab: 0,
			navbar: [
				{
					name: '基本信息'
				},
				{
					name: '购买记录'
				}
			],
			goodsData: null,
			bannerIndex: 0,
			bannerLength: 0, //轮播图有多少张
			scrollH: 0 //滚动总高度
		};
	},
	onLoad() {
		let temp = {};
		temp.pic_list = [
			{
				pic_url: 'http://jxmall.sinbel.top/web//uploads/images/original/20200608/bbe6ddad872e2eb47b78adf895df98c6.jpg'
			},
			{
				pic_url: 'http://jxmall.sinbel.top/web//uploads/images/original/20200608/8f7ff2fbba88d1480d580b044a340974.jpg'
			},
			{
				pic_url: 'http://jxmall.sinbel.top/web//uploads/images/original/20200609/67bf8d50edd6168e5cf96f02cff6c93e.png'
			}
		];
		this.goodsData = temp;
		this.bannerLength = this.goodsData.pic_list.length;
		let obj = {};
		// #ifdef MP-WEIXIN
		obj = wx.getMenuButtonBoundingClientRect();
		// #endif
		// #ifdef MP-BAIDU
		obj = swan.getMenuButtonBoundingClientRect();
		// #endif
		// #ifdef MP-ALIPAY
		my.hideAddToDesktopMenu();
		// #endif

		uni.getSystemInfo({
			success: res => {
				this.width = obj.left || res.windowWidth;
				this.height = obj.top ? obj.top + obj.height + 8 : res.statusBarHeight + 44;
				this.top = obj.top ? obj.top + (obj.height - 32) / 2 : res.statusBarHeight + 6;
				this.scrollH = res.windowWidth;
			}
		});
	},
	computed: {
		goodsStateClass() {
			if (this.goods_state == 0) {
				return '#0092FF';
			}
			return '#BC0100';
		}
	},
	methods: {
		addShoppingCart() {
			console.log('加入购物车');
		},
		bannerChange(e) {
			this.bannerIndex = e.detail.current;
		},
		tabsChange(e) {
			this.currentTab = e.index;
		},
		back() {
			this.navBack();
		}
	}
};
</script>

<style lang="scss" scoped>
.app {
	height: 100%;
}
.jx-banner-swiper {
	position: relative;

	.jx-slide-image {
		width: 100%;
		height: 750rpx;
		display: block;
	}
}
.jx-banner-swiper .tui-tag-class {
	position: absolute;
	color: #fff;
	bottom: 30rpx;
	right: 30rpx;
}
.goods {
	background-color: #f2f2f2;
	.goods-state {
		padding: 14rpx 20rpx;
		color: #ffffff;
		width: 100%;
		height: 105rpx;
		background-size: 100%;
		background-repeat: no-repeat;

		.flex {
			align-items: baseline;
		}
	}
	.dot {
		font-size: 30rpx;
	}
	.current-price {
		font-size: 60rpx;
		margin-right: 30rpx;
	}
	.original-price {
		font-size: 24rpx;
		text-decoration: line-through;
	}
	.msg {
		font-size: 24rpx;
		margin-bottom: 10rpx;
	}
	.tag-count-down {
		width: 260rpx;
		height: 40rpx;
		background: #ffffff;
		border-radius: 20rpx;
	}

	.goods-info {
		background-color: #ffffff;
		padding: 0 20rpx;
		margin-bottom: 15rpx;

		.goods-introduce {
			padding: 30rpx 0;
			line-height: 60rpx;
			border-bottom: 1rpx solid #f2f2f2;
		}

		.goods-name {
			font-size: 35rpx;
			color: #000000;
			font-weight: 600;
		}

		.goods-popularity {
			.line {
				width: 2rpx;
				height: 90rpx;
				background-color: #e6e6e6;
			}

			.num-text {
				margin-left: 36rpx;
				margin-right: 10rpx;
				line-height: 44rpx;
				.num {
					color: #bc0100;
					font-size: 32rpx;
				}
				.text {
					color: #a5a5a5;
					font-size: 26rpx;
				}
			}
		}

		.time-msg {
			padding: 28rpx 0;
			color: #bc0100;
			font-size: 26rpx;
			line-height: 48rpx;
			
			.flex-col{
				align-items: flex-end;
			}
			
			.onsale-item {
				color: #000000;
				font-size: 27rpx;
			}
		}
	}

	.goods-detail {
		background-color: #ffffff;
		height: 100%;
		.title {
			padding: 37rpx 0;
			color: #000000;
			font-weight: 600;

			.text {
				margin: 0 42rpx;
			}
		}
		.str {
			height: 2rpx;
			width: 178rpx;
			background-color: #f2f2f2;
		}

		.goods-furnish {
			color: #000000;
			font-size: 28rpx;
			margin: 0 20rpx 46rpx;
			border-top: 1rpx solid #f2f2f2;
			border-left: 1rpx solid #f2f2f2;
			border-radius: 6rpx;
			flex-wrap: wrap;

			.goods-furnish-item {
				width: 50%;
				padding: 30rpx 0rpx 30rpx 24rpx;
				border-right: 1rpx solid #f2f2f2;
				border-bottom: 1rpx solid #f2f2f2;
			}
		}
	}
	.goods-list-imgs {
		margin-bottom: 120rpx;
		.goods-item-img {
			width: 100%;
		}
	}
}

.specification {
	background-color: #ffffff;
	margin-bottom: 14rpx;
	
	.assess-content {
		background-color: #FFFFFF;
		padding-bottom: 40rpx;
	
		.msg {
			text-align: center;
		}
		
		.user-assess-title {
			padding-right: 30rpx;
			flex-shrink: 0;
			font-weight: 500;
			color: #2D2D2D;
			font-size: 28rpx;
		}
		
		.user-assess-list {
		
			white-space: nowrap;
		
			& ::-webkit-scrollbar {
				width: 0;
				height: 0;
				background-color: transparent;
			}
		
			.item {
				background-color: #F8F8F8;
				padding: 40rpx 30rpx;
				color: #2D2D2D;
				display: inline-block;
				margin-right: 30rpx;
				min-width: 600rpx;
					
				&.last {
					min-width: 60rpx;
					line-height: 0;
					writing-mode: vertical-rl;
					font-size: 10pt;
					padding: 20rpx 50rpx;
				}
					
				.user {
					margin-bottom: 10rpx;
					
					.acatar {
						width: 80rpx;
						height: 80rpx;
						border-radius: 100rpx;
						margin-right: 16rpx;
					}
					
					.rate {
						margin-left: 40rpx;
					}
				}
					
				.assess {
					padding: 14rpx 0;
				}
					
				.icon {
					font-size: 10pt;
				}
			}
		}
	}
	
	.selected-box {
		color: #000000;
		font-weight: 400;
		display: flex;
		flex-wrap: wrap;
		line-height: 40rpx;

		.tag {
			display: flex;
			margin-right: 20rpx;

			.iconfont {
				font-size: 12pt;
				color: #cf4f4e;
				margin-right: 10rpx;
			}
		}
	}

	.jx-list-cell {
		display: flex;
		align-items: center;
		font-size: 10pt;
		line-height: 100rpx;
		padding: 0 30rpx;
		box-sizing: border-box;
		border-bottom: 1rpx solid #e6e6e6;

		&.last {
			border-bottom: 0;
		}

		.iconfont {
			color: #9c9c9c;
			font-size: 18pt;
			flex: 1;
			text-align: end;
		}
	}

	.jx-cell-title {
		padding-right: 30rpx;
		flex-shrink: 0;
		color: #9c9c9c;
		font-weight: 400;
	}

	.jx-between {
		justify-content: space-between !important;
	}

	.jx-bold {
		font-weight: bold;
	}
	
	.line{
		line-height: 60rpx;
	}
}

.purchased {
	background-color: #ffffff;
	padding: 26rpx 20rpx 140rpx;
	height: 100%;

	.goods-statistics {
		margin-bottom: 38rpx;
		background-color: #faf5f4;
		height: 80rpx;
		border-radius: 5rpx;
		color: #424242;
		font-size: 30rpx;
		line-height: 60rpx;
		.num {
			color: #bc0100;
			margin: 0 10rpx;
		}
	}

	.purchased-item {
		margin-bottom: 48rpx;
		.user-avatar {
			width: 80rpx;
			height: 80rpx;
			border-radius: 50%;
			margin-right: 24rpx;
		}

		.purchased-user {
			font-size: 26rpx;
			.purchased-user-name {
				color: #929292;
			}
			.purchased-time {
				color: #0a0909;
			}
		}

		.purchased-quantity {
			font-size: 28rpx;
			color: #0a0909;
		}
	}
}
.num {
	color: #bc0100;
}
/*底部操作栏*/
.jx-col-7 {
	width: 58.33333333%;
}

.jx-col-5 {
	width: 41.66666667%;
}

.jx-operation {
	width: 100%;
	height: 120rpx;
	/* box-sizing: border-box; */
	background: rgba(255, 255, 255, 0.98);
	position: fixed;
	display: flex;
	align-items: center;
	justify-content: space-between;
	z-index: 10;
	bottom: 0;
	left: 0;
	padding-bottom: env(safe-area-inset-bottom);
}

.jx-safearea-bottom {
	width: 100%;
	height: env(safe-area-inset-bottom);
}

.jx-operation::before {
	content: '';
	position: absolute;
	top: 0;
	right: 0;
	left: 0;
	border-top: 1rpx solid #eaeef1;
	-webkit-transform: scaleY(0.5);
	transform: scaleY(0.5);
}

.jx-operation-left {
	display: flex;
	align-items: center;
}

.jx-operation-item {
	flex: 1;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	position: relative;

	.iconfont {
		font-size: 44rpx;
		line-height: 48rpx;
		color: #6a6a6a;
	}

	.red-dots {
		position: absolute;
		top: -12rpx;
		left: 32rpx;
		transform: translateX(75%);
		font-size: 7pt;
		line-height: 30rpx;
		// padding: 2rpx 6rpx;
		width: 30rpx;
		height: 30rpx;
		text-align: center;
		border-radius: 50px;
		background: #bc0100;
		color: #fff;
	}
}

.jx-operation-text {
	font-size: 9pt;
	color: #6b6b6b;
}

.jx-opacity {
	opacity: 0.5;
}

.jx-scale-small {
	transform: scale(0.9);
	transform-origin: center center;
}

.jx-operation-right {
	height: 94rpx;
	/* box-sizing: border-box; */
	padding: 0 10rpx;
}

.jx-btn {
	height: 100%;
	width: 100%;
	border-radius: 0px;
	display: flex;
	flex: 1;
	align-items: center;
	justify-content: center;
	color: #ffffff;
	font-size: 11pt;

	&.circle {
		border-radius: 40rpx;
	}

	&.radius-left {
		border-radius: 100rpx 0 0 100rpx;
	}

	&.radius-right {
		border-radius: 0 100rpx 100rpx 0;
	}

	&.early-join {
		background: linear-gradient(-76deg, rgba(123, 187, 252, 1.56), rgba(13, 157, 255, 1.56), rgba(70, 167, 255, 1.56), rgba(129, 186, 248, 1.56));
	}

	&.show-popup {
		background: linear-gradient(-90deg, rgba(221, 176, 82, 1), rgba(239, 206, 127, 1));
	}

	&.submit {
		background-color: #bc0100;
	}
}

.jx-right-flex {
	display: flex;
	align-items: center;
	justify-content: center;
}

.jx-btn {
	width: 100%;
	position: relative;
	border: 0 !important;
	border-radius: 0px;
	display: flex;
}

.jx-operation .jx-badge-class {
	position: absolute;
	top: -6rpx;
	/* #ifdef H5 || APP-PLUS*/
	transform: translateX(50%);
	/* #endif  */
}

.jx-flex-1 {
	flex: 1;
}
/*底部操作栏*/

.margin-top-7px {
	margin-top: 14rpx;
}

.presell {
	background-image: url(../../../images/extensions/community/presell-bg.png);
}
.onsale {
	background-image: url(../../../images/extensions/community/onsale-bg.png);
}
</style>
