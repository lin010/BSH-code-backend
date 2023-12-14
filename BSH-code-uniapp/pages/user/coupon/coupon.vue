<template>
	<view class="app">
		<view class="app-header">
			<view class="tui-mtop">
				<com-tabs :tabs="tabs" :currentTab="currentTab" selectedColor="#FF7104" sliderBgColor="#FF7104" color="#000000"
				 @change="change" :sliderWidth="60" itemWidth="50%"></com-tabs>
			</view>
		</view>
		<view class="app-body">
			<view class="coupon-items" v-if="dataList && dataList.length">
				<view class="coupon-item" v-for="(item,index) in dataList" :key="index" :style="{background: 'url('+couponImg+')'}">

					<view class="coupon-item-right">
						<view class="coupon-item-price">
							<view v-if="item.type == 2">
								&yen;<text class="coupon-item-price-int">{{sub_price_int(item.sub_price)}}</text>.{{sub_price_float(item.sub_price)}}
							</view>
							<view v-else>
								<text class="coupon-item-price-int">{{(item.discount)*10}}</text>折
							</view>
						</view>
						<view class="coupon-item-condition">
							满{{item.coupon_data.min_price}}可用
						</view>
					</view>
					<view class="coupon-item-left">
						<view class="coupon-item-name">{{item.coupon_data.name}}</view>
						<view class="coupon-item-time">
							<view v-if="item.coupon_data.expire_type == 2">
								<view class="">
									开始时间: {{item.coupon_data.begin_at}}
								</view>
								<view>
									过期时间: {{item.coupon_data.end_at}}
								</view>
							</view>
							<view v-else>还剩{{item.coupon_data.expire_day}}天过期</view>
							<view class="coupon-item-receive" :style="{background:textColor}" @click="toCat(item.coupon_data)" v-if="dataStatus==0">
								<view class="scale">去使用</view>
							</view>
							<image v-else-if="item.type == 1" class="tag" :src="img_url+'coupon-gray-used-tag.png'" mode="aspectFill"></image>
							<!-- <image v-else class="tag" src="@/static/coupon-gray-tag.png" mode="aspectFill"></image> -->
							<image v-else class="tag" :src="img_url+'coupon-gray-tag.png'" mode="aspectFill"></image>
							<!-- 对未使用的优化 -->
							<block v-if="currentTab==1">
								<image v-if="item.is_use==0" class="tag" :src="img_url+'coupon-gray-tag.png'" mode="aspectFill"></image>
								<image v-else-if="item.is_use==1" class="tag" :src="img_url+'coupon-gray-used-tag.png'" mode="aspectFill"></image>
							</block>
							
						</view>
						<!-- <image v-else class="tag" src="@/static/coupon-gray-tag.png" mode="aspectFill"></image> -->
						<!-- <view class="coupon-item-sub iconfont icon-yilingqu" v-if="item.is_receive != 0"></view> -->
					</view>
				</view>
			</view>
			<view class="coupon-items" v-else>
				<main-nomore text="暂无优惠券" :visible="true" bgcolor="transparent"></main-nomore>
			</view>
		</view>

		<!-- <view class="app-footer flex flex-y-center flex-x-center" @tap="navTo">
			<view>领取更多优惠券 </view>
			<image class="app-footer-icon" src="/static/images/icon/arrow-down.png" mode=""></image>
		</view> -->

		<!--加载loadding-->
		<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="!pullUpOn" bgcolor="#FFFFFF"></main-nomore>
		<main-loading :visible="loading"></main-loading>
		<!--加载loadding-->
	</view>
</template>

<script>
	const _status = 'refresh';
	import moment from 'moment';
	import coupon from '@/components/coupon/coupon';
	import tuiButton from "@/components/extend/button/button"
	export default {
		components: {
			tuiButton
		},
		data() {
			return {
				img_url: this.$api.img_url,
				loadding: false,
				pullUpOn: true,
				loading: false,
				couponStyle: 4,
				dataList: [],
				pages: {
					total_count: 1,
					page_count: 1,
					pageSize: 20,
					current_page: 1
				},
				dataStatus: 0,
				currentTab: 0,
				tabs: [{
					name: "未使用"
				}, {
					name: "不可用"
				}],
				textColor:'#bc0100',
				couponImg:'https://jx.shuzixiangdao.com/web/static/couponBg.png',	//默认背景图
			}
		},
		onLoad() {
			// 获取缓存的优惠券背景图--如果有就赋值
			let couponImg = this.globalSet('couponImg');
			couponImg?this.couponImg = couponImg:'';
			
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
			this.getDateList("refresh", true)
		},
		computed: {
			sub_price_int() {
				return function(val) {
					var arr = val.split('.');
					return arr[0];
				}
			},
			sub_price_float() {
				return function(val) {
					var arr = val.split('.');
					if (arr.length != 2) {
						return '00'
					} else {
						return arr[1];
					}
				}
			}
		},
		methods: {
			toCat(data) {
				uni.navigateTo({
					url: '../../cat/cat'
				})
			},
			
			// 获取优惠券列表
			getDateList(status, bool) {
				if(status == 'refresh'){
					this.dataList = [];
				}
				this.loading = bool ? true : false;
				let {
					current_page,
					pageSize
				} = this.pages;

				this.$http.request({
					url: this.$api.coupon.user_coupon_list,
					data: {
						page: current_page,
						status: this.dataStatus
					}
				}).then(res => {
					this.loading = false;
					if (res.code === 0) {
						// 记录一下返回页面总数
						if(res.data.pagination){
							this.pages.page_count = res.data.pagination.page_count;
						}
						// 优化一下时间-只保留年月日
						if(res.data.list.length>0){
							res.data.list.forEach((item) => {
								item.coupon_data.begin_at = item.coupon_data.begin_at.trim().split(" ")[0];
								item.coupon_data.end_at = item.coupon_data.end_at.trim().split(" ")[0];
							})
						}
						
						this.dataList = status != 'refresh' ? this.dataList.concat(res.data.list) : res.data.list;
						this.pullUpOn = true;
					}
				})
			},
			change(e) {
				// 应该是要数据初始化的
				this.dataList = [];
				this.pages =  {
					total_count: 1,
					page_count: 1,
					pageSize: 20,
					current_page: 1
				};
				
				this.currentTab = e.index;
				this.dataStatus = this.currentTab != 0 ? 2 : 0;
				this.getDateList(_status, true);
			},
			timeFormat(date) {
				return moment(date).format("YYYY-MM-DD");
			},
			navTo() {
				uni.navigateTo({
					url: '/pages/coupon/list'
				})
			}
		},
		
		// 页面触底加载更多
		onReachBottom() {
			console.log(this.pages);
			
			
			this.loadding = true
			this.pullUpOn = true
			let {
				current_page,
				page_count
			} = this.pages;
			setTimeout(() => {
				this.loadding = false
				if (current_page >= page_count) {
					this.pullUpOn = false
					return;
				}
				this.pages.current_page++;
				this.getDateList();
			}, 1000)
		},
	}
</script>

<style lang="scss" scoped>
	.app {
		height: 100%;
		background-color: #FFFFFF;
	}

	.app-header {}

	.app-body {

		// padding: 24rpx 28rpx;
		background-color: #FFFFFF;

		.coupon-items {

			padding: 30rpx 0;

			.coupon-item {
				background-size: cover !important;
				width: 92%;
				margin: auto;
				margin-bottom: 20rpx;
				height: 190rpx;
				display: flex;

				.coupon-item-right {
					color: #FFFFFF;
					width: 240rpx;
					height: 100%;
					display: flex;
					align-items: center;
					flex-direction: column;
					justify-content: center;
				}

				.coupon-item-price {
					font-weight: 700;
					margin: 0 0 8rpx;
				}

				.coupon-item-price-int {
					font-size: 17pt;
					margin: 0 2rpx;
				}

				.coupon-item-condition {
					font-size: 9pt;
					font-weight: 600;
				}

				.coupon-item-left {
					flex: 1;
					color: #000000;
					display: flex;
					justify-content: space-around;
					flex-direction: column;
					padding: 6rpx 20rpx;
					position: relative;

					.tag {
						width: 70rpx;
						height: 30rpx;
						font-size: 21px;
						position: absolute;
						top: 0;
						right: 0;
					}
				}

				.coupon-item-name {
					font-size: 10pt;
				}

				.coupon-item-time {
					font-size: 9pt;
					display: flex;
					justify-content: space-between;
					align-items: center;
				}

				.coupon-item-receive {
					background: #BC0100;
					border-radius: 30rpx;
					// padding: 6rpx 26rpx;
					line-height: 40rpx;
					min-width: 100rpx;
					text-align: center;
					color: #FFFFFF;
					font-size: 8pt;
					letter-spacing: 2rpx;

					.scale {
						transform: scale(0.8);
					}
				}

				.coupon-item-received {
					background: transparent;
					border: 2rpx solid #BC0100;
					color: #BC0100;
				}

				.coupon-item-sub {
					position: absolute;
					color: #BC0100;
					top: 0;
					right: 0;
					font-size: 60rpx;
					line-height: 60rpx;
				}
			}
		}
	}

	.app-footer {
		position: fixed;
		bottom: 0px;
		background: #FFFFFF;
		width: 100%;
		text-align: center;
		padding: 26rpx 0;
		border-top: 1px solid #e8e0e0;
		z-index: 9;
		font-size: 12pt;

		.app-footer-icon {
			width: 30rpx;
			height: 30rpx;
			transform: rotate(-90deg);
			margin-top: 4rpx;
		}
	}
</style>
