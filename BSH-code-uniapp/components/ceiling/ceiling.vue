<template>
	<view class="ceiling-root">
		<scroll-view scroll-x="true" :scroll-left='scrollPosition-widthHalf' scroll-with-animation='true' class="cel-scroll">
			<view class="cel-scroll-child" v-for="(item,index) in list" @tap="cut(index,item.title)" :class="index == 6 ? 'qwe':''"
			 :key='index'>
				<view class="flex-cel flex-cel-col flex-cel-y">
					<view class="cel-title" :style="{color:indexs == index?textColor:''}" :class="indexs == index?'active-title':''">{{item.title}}</view>
					<view class="samll-text" :style="{color:indexs == index?textColor:''}">{{item.sub_title}}</view>
				</view>
			</view>
			<view class="border" :style="{left:moveLeft+'px',border:'1px solid'+textColor}"></view>
		</scroll-view>

		<scroll-view v-if="!styleBool" scroll-x="true" :scroll-left='scrollPosition-widthHalf' scroll-with-animation='true'
		 class="cel-scroll cel-scroll2" :style="{top:height}">
			<view class="cel-scroll-child cel-scroll-child2" v-for="(item,index) in dataList" @tap="cut(index,item.title)"
			 :class="index == 6 ? 'qwe':''" :key='index'>
				<view class="flex-cel flex-cel-col flex-cel-y cel-content">
					<view class="cel-title" :class="indexs == index?'active-title':''" :style="{color:indexs == index?textColor:''}">{{item.title}}</view>
				</view>
			</view>
			<view class="border" :style="{left:moveLeft+'px',border:'1px solid'+textColor}"></view>
		</scroll-view>

		<swiper :indicator-dots="false" :interval="3000" :current='indexs' :duration="500" :style="{height:swiperHeight+'px'}"
		 @change='change'>
			<swiper-item v-for="(sItem,sIndex) in dataList" :key='sIndex'>
				<commodity key-value='cart' :listStyle='listStyle' :productNameBool='productNameBool' :priceBool='priceBool'
				 :buyBtnBool='buyBtnBool' :subscriptBool='subscriptBool' :buyBtnStyle='buyBtnStyle' :displayStyle='displayStyle'
				 :productData='sItem.goods_list'></commodity>
			</swiper-item>
		</swiper>

		<!--加载loadding-->
		<view style="height: 50rpx;">
			<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
			<main-nomore :visible="!pullUpOn" bgcolor="transparent"></main-nomore>
		</view>
		<!--加载loadding-->

	</view>
</template>

<script>
	import commodity from '@/components/commodity/commodity';
	export default {
		name: 'ceiling',
		components: {
			commodity
		},
		props: {
			styleBool: { //控制是否显示
				type: Boolean,
				default: true
			},
			// searchHeight: { //搜索框高度，控制定位在哪里
			// 	type: Number,
			// 	default: 46
			// },
			statusHeight: { //状态栏高度
				type: Number,
				default: 0
			},
			list: {
				type: Array,
				default: () => []
			},
			page: {
				type: Number,
				default: 1
			}
		},
		data() {
			return {
				textColor:'#bc0100',
				dataList: [],

				loadding: false,
				pullUpOn: true,
				page_count: 99, //总页数
				title: '', //点击了的keyword
				swiperHeight: 0,

				scrollPosition: 0, //scroll的位置

				left: 0, //保存下划线初始化距离
				moveLeft: 0, //下划线实际移动距离
				width: 0,
				widthHalf: 0,
				indexs: 0,

				//商品数据
				listStyle: 2, // 1为一行一个 -1为列表模式 2为一行两个 3一行三个 0为左右滑动
				displayStyle: 1, //1为常规 2为边框 3为居中显示 4为边框居中
				productNameBool: true, //商品名，true为显示，false为不显示
				priceBool: true, //商品价格，true为显示，false为不显示
				buyBtnBool: true, //购物车按钮，true为显示，false为不显示
				buyBtnStyle: 1, //购物车按钮样式。1为购物车 2为加号 3为文字样式1 4为文字样式2 5为文字样式3 6为文字样式4
				subscriptBool: true, //角标，true为显示，false为不显示
				dataList: []
			}
		},
		watch: {
			styleBool() {
				// var that = this;
				// setTimeout(() => {
				// 	let info1 = uni.createSelectorQuery().select(".cel-scroll-child");
				// 	info1.boundingClientRect(function(data) {
				// 		that.width = data.width;
				// 	}).exec()
				// }, 1300)
			},
			page() {
				this.dataList[this.indexs].page++;
				if (this.dataList[this.indexs].page > this.page_count) {
					return;
				}
				this.getData(this.title, this.dataList[this.indexs].page);
			}
		},
		mounted() {
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
			}
			
			this.dataList = JSON.parse(JSON.stringify(this.list));
			this.title = this.dataList[0].title;
			this.dataList.map((val) => {
				return this.$set(val, 'page', 1);
			})
			this.setHeight();
			var that = this;
			let info2 = uni.createSelectorQuery().in(this).select(".cel-scroll-child");
			info2.boundingClientRect(function(data) {
				that.width = data.width;
				that.left = data.width / 2 - 13;
				that.moveLeft = data.width / 2 - 13;
				that.widthHalf = data.width / 2;
			}).exec()
		},
		computed: {
			height() {
				var px = 96 / 750 * wx.getSystemInfoSync().windowWidth;
				var pxs = this.statusHeight / 750 * wx.getSystemInfoSync().windowWidth;
				return `${px + pxs}px`;
			},
		},
		methods: {
			change(e) { //轮播图改变事件
				this.page_count = 99; //每次切换都初始化总页数
				this.pullUpOn = true;
				this.indexs = e.detail.current;
				this.title = this.list[this.indexs].title;
				this.moveLeft = this.left + (this.width * e.detail.current);
				this.scrollPosition = e.detail.current > 3 ? this.moveLeft : 0;
				this.setHeight(e.detail.current);
			},
			cut(index, title) {
				this.indexs = index;
				this.page_count = 99; //每次切换都初始化总页数
				this.pullUpOn = true;
				this.title = title;
				this.moveLeft = this.left + (this.width * index); //切换下划线位置
				this.setHeight(index);
				this.$emit('click', {
					index
				});
			},
			setHeight(index) { //计算swiper的高度
				var that = this;
				let proHeight = 580 / 750 * wx.getSystemInfoSync().windowWidth; //商品高度
				index = index ? index : 0;
				that.swiperHeight = Math.ceil(that.dataList[index].goods_list.length / 2) * proHeight;
			},
			getData(label, page) { //获取数据
				this.loadding = true;
				this.pullUpOn = true;
				this.$http.request({
					url: this.$api.goods.rightList,
					data: {
						cat_id: '',
						limit: 2,
						page,
						label,
					}
				}).then((res) => {
					if (res.code == 0) {
						this.loadding = false;
						this.dataList[this.indexs].goods_list.push(...res.data.list);
						this.setHeight(this.indexs);
						this.page_count = res.data.page_count;
						if (page >= res.data.page_count) {
							this.pullUpOn = false;
						}
					}
				})
			}
		}
	}
</script>

<style scoped>
	/* 公共样式 */
	.flex-cel {
		display: flex;
	}

	.flex-cel-col {
		display: flex;
		flex-direction: column;
	}

	.flex-cel-x {
		display: flex;
		justify-content: center;
	}

	.flex-cel-y {
		display: flex;
		align-items: center;
	}

	.scroll-box {
		display: flex;
	}

	.cel-scroll {
		white-space: nowrap;
		position: relative;
		margin-bottom: 30rpx;
	}

	.cel-scroll-child {
		display: inline-block;
		padding: 0px 24rpx 14rpx;
		position: relative;
		width: 170rpx;
		box-sizing: border-box;
	}

	.cel-scroll-child::after {
		content: "|";
		position: absolute;
		right: 0;
		top: 50%;
		transform: scale(0.9) translateY(-50%);
		color: #e2e2e2;
	}

	.border {
		position: absolute;
		bottom: 0;
		width: 48rpx;
		height: 4rpx;
		background: #bc0100;
		border-radius: 30rpx;
		border-bottom: 1px solid red;
		transition: all 0.4s;
	}

	.cel-title {
		font-size: 10pt;
		transition: all 0.2s;
	}

	.samll-text {
		font-size: 9pt;
		color: #9d9d9d;
	}

	.active {
		color: #bc0100;
	}

	.active-title {
		font-size: 11pt;
		font-weight: 700;
	}

	.qwe::after {
		content: '';
	}

	.cel-scroll2 {
		position: fixed;
		/* top: 96rpx; */
		left: 0px;
		z-index: 99;
		background: #FFFFFF;
		padding: 20rpx 0;
		border-bottom: 2rpx solid #f7f7f7;
	}

	.cel-scroll-child2 {
		width: 170rpx;
		padding: 0 24rpx 14rpx;
		box-sizing: border-box;
		display: inline-block;
		position: relative;
	}

	/* 隐藏滚动条 */
	scroll-view ::-webkit-scrollbar {
		width: 0;
		height: 0;
		background-color: transparent;
	}
</style>
