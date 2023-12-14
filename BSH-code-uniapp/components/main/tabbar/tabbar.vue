<template>
	<!-- <view v-if="is_show_tabbar" class="jx-tabbar" :class="{'jx-tabbar-fixed':isFixed,'jx-unlined':unlined,'jx-shadow':is_shadow}" :style="{background:backgroundColor}">
		<block v-for="(item,index) in tabBarItems" :key="index">
			<view class="jx-tabbar-item" :class="{'jx-item-hump': item.hump}" :style="{backgroundColor:item.hump ? tabBarItems.top_background_color : 'none'}"
			 @tap="tabbarSwitch(index,item.hump,item.url,item.verify)">
				<view class="jx-icon-box" :class="{'jx-tabbar-hump':item.hump}">
					<image class="img" :src="item.active ? item.active_icon : item.icon" :class="[item.hump?'':'jx-tabbar-icon']"></image>
					<view :class="[item.isDot?'jx-badge-dot':'jx-badge']" :style="{color:badgeColor,backgroundColor:badgeBgColor}"
					 v-if="item.num">{{item.isDot?"":item.num}}</view>
				</view>
				<view class="jx-text-scale" :class="{'jx-text-hump':item.hump}" :style="{color: item.active ? item.active_color : item.color}">{{item.text}}</view>
			</view>
		</block>
		<view :class="{'jx-hump-box':hump}" v-if="hump && !unlined"></view>
	</view> -->
	
	<!-- 由于nvue绑定class只支持数组语法，所以全部将对象语法改成数组 -->
	<view>
		<view v-if="is_show_tabbar" class="jx-tabbar" :class="[isFixed?'jx-tabbar-fixed':'',unlined?'jx-unlined':'',is_shadow?'jx-shadow':'']" :style="{background:backgroundColor}">
			<block v-for="(item,index) in tabBarItems" :key="index">
				<view class="jx-tabbar-item" :class="[item.hump?'jx-item-hump': '']" :style="{backgroundColor:item.hump ? tabBarItems.top_background_color : 'none'}"
				 @tap="tabbarSwitch(index,item.hump,item.url,item.verify)">
					<view class="jx-icon-box" :class="[item.hump?'jx-tabbar-hump':'']">
						<image class="img" :src="item.active ? item.active_icon : item.icon" :class="[item.hump?'':'jx-tabbar-icon']"></image>
						<view :class="[item.isDot?'jx-badge-dot':'jx-badge']" :style="{color:badgeColor,backgroundColor:badgeBgColor}"
						 v-if="item.num">{{item.isDot?"":item.num}}</view>
					</view>
					<view class="jx-text-scale" :class="[item.hump?'jx-text-hump':'']" :style="{color: item.active ? item.active_color : item.color}">{{item.text}}</view>
				</view>
			</block>
			<view :class="[hump?'jx-hump-box':'']" v-if="hump && !unlined"></view>
		</view>
	</view>
</template>

<script>
	export default {
		name: "jxTabbar",
		props: {
			//当前索引
			current: {
				type: Number,
				default: 4
			},
			//字体颜色
			color: {
				type: String,
				default: "#000000"
			},
			//字体选中颜色
			selectedColor: {
				type: String,
				default: "#5677FC"
			},
			//背景颜色
			// backgroundColor: {
			// 	type: String,
			// 	default: "#FFFFFF"
			// },
			//是否需要中间凸起按钮
			hump: {
				type: Boolean,
				default: false
			},
			//固定在底部
			isFixed: {
				type: Boolean,
				default: true
			},
			//tabbar
			// "pagePath": "/pages/my/my", 页面路径
			// "text": "thor", 标题
			// "iconPath": "thor_gray.png", 图标地址
			// "selectedIconPath": "thor_active.png", 选中图标地址
			// "hump": true, 是否为凸起图标
			// "num": 2,   角标数量
			// "isDot": true,  角标是否为圆点
			// "verify": true  是否验证  （如登录）
			tabBar: {
				type: Array,
				default () {
					return []
				}
			},
			//角标字体颜色
			badgeColor: {
				type: String,
				default: "#fff"
			},
			//角标背景颜色
			badgeBgColor: {
				type: String,
				default: "#F74D54"
			},
			unlined: {
				type: Boolean,
				default: false
			}

		},
		data() {
			return {
				tabBarItems: null,
				is_show_tabbar: false,
				//背景颜色
				backgroundColor:'',
				is_shadow:false,
			};
		},
		mounted() {
			let pages = getCurrentPages();
			if(pages.length > 0){
				var pageUrl = (pages[pages.length - 1]).route;
			}
			
			let tabbars = uni.getStorageSync('mall_config') ? JSON.parse(uni.getStorageSync('mall_config')).navbar : null;
			let temp;
			if (!tabbars) {
				this.initMall().then(result => {
					this.backgroundColor = result.bottom_background_color;
					this.is_shadow = result.shadow;
					temp = result;
					temp.navs.forEach((item, i) => {
						item.index = i;
						if (item.url.indexOf(pageUrl) != -1) {
							item.active = true;
							this.is_show_tabbar = true;
						}
					})
					this.tabBarItems = temp.navs;
					return;
				})
			}else{
				if(pageUrl == 'plugins/short-video/index'){
					this.backgroundColor = 'transparent';
					this.is_shadow = false;
				}else{
					this.backgroundColor = tabbars.bottom_background_color;
					this.is_shadow = tabbars.shadow;
				}
			}
			let navs = this.tabBar.length > 0 ? this.tabBar : tabbars.navs;
			navs.forEach((item, i) => {
				item.index = i;
				if (item.url.indexOf(pageUrl) != -1) {
					item.active = true;
					this.is_show_tabbar = true;
				}
			})
	//----------------------------------------------店铺的下面显示tabbar		
			if(pageUrl == 'pages/shop/shop'){
				this.is_show_tabbar = true;
			}
	//-------------------------------------------------------------------
			this.tabBarItems = navs;
			// #ifdef H5
			let mall_id = this.$http.getUrlParam("mall_id");
			// #endif
			
		},
		methods: {
			tabbarSwitch(index, hump, url, verify) {
				if(url == '/plugins/short-video/index'){
					url = url + '?from=short-video'
				}
				uni.redirectTo({
					url
				})
			},
			initMall() {
				let temp = uni.getStorageSync('mall_config') ? JSON.parse(uni.getStorageSync('mall_config')).navbar : null;
				return new Promise((resolve, reject) => {
					if (temp) {
						resolve(temp);
						return;
					}
					this.$http.request({
						url: this.$api.index.config,
					}).then(res => {
						if (res.code === 0) {
							let {
								cat_style,
								copyright,
								mall_setting,
								navbar,
								page_title
							} = res.data;
							uni.setStorageSync('mall_config',JSON.stringify(res.data));
							resolve(navbar);
						}
					})
				})
			}
		}
	}
</script>

<style scoped>
	.jx-tabbar {
		width: 100%;
		height: 100rpx;
		/* #ifndef APP-PLUS-NVUE */
		display: flex;
		align-items: center;
		justify-content: space-between;
		/* #endif */
		position: relative;
	}

	.jx-tabbar-fixed {
		position: fixed;
		z-index: 99999;
		left: 0;
		bottom: 0;
		/* #ifndef APP-PLUS-NVUE */
		padding-bottom: env(safe-area-inset-bottom);
		/* #endif */
		box-sizing: content-box;
		flex-direction: row;
	}

	.jx-tabbar::before {
		/* #ifndef APP-PLUS-NVUE */
		content: '';
		width: 100%;
		/* border-top: 1rpx solid #dadada; */
		position: absolute;
		top: 0;
		left: 0;
		transform: scaleY(0.5);
		transform-origin: 0 100%;
		/* #endif */
	}

	.jx-tabbar-item {
		height: 100%;
		/* #ifndef APP-PLUS-NVUE */
		flex: 1;
		display: flex;
		text-align: center;
		align-items: center;
		flex-direction: column;
		justify-content: space-between;
		box-sizing: border-box;
		/* #endif */
		position: relative;
		padding: 10rpx 0;
	}

	.jx-icon-box {
		position: relative;
	}

	.jx-item-hump {
		height: 98rpx;
		z-index: 2;
	}

	.jx-tabbar-icon {
		width: 48rpx;
		height: 48rpx;
		/* #ifndef APP-PLUS-NVUE */
		display: block;
		/* #endif */
	}

	.jx-hump-box {
		width: 120rpx;
		height: 120rpx;
		background-color: #FFFFFF;
		position: absolute;
		left: 50%;
		transform: translateX(-50%);
		top: -50rpx;
		border-radius: 50%;
		z-index: 1;
	}

	.jx-hump-box::before {
		/* #ifndef APP-PLUS-NVUE */
		content: '';
		height: 200%;
		width: 200%;
		border: 1rpx solid #B2B2B2;
		position: absolute;
		top: 0;
		left: 0;
		transform: scale(0.5) translateZ(0);
		transform-origin: 0 0;
		border-radius: 100%;
		/* #endif */
	}


	.jx-unlined::before {
		border: 0 !important
	}

	.jx-tabbar-hump {
		width: 100rpx;
		height: 100rpx;
		position: absolute;
		left: 50%;
		transform: translateX(-50%) rotate(0deg);
		top: -40rpx;
		/* #ifndef APP-PLUS-NVUE */
		transition: all 0.2s linear;
		/* #endif */
	}

	.jx-tabbar-hump .img {
		width: 100rpx;
		height: 100rpx;
		/* #ifndef APP-PLUS-NVUE */
		display: block;
		/* #endif */
	}

	.jx-hump-active {
		/* #ifndef APP-PLUS-NVUE */
		transform: translateX(-50%) rotate(135deg);
		/* #endif */
	}

	.jx-text-hump {
		position: absolute;
		bottom: 10rpx;
	}

	.jx-text-scale {
		font-weight: bold;
		transform: scale(0.8);
		font-size: 25rpx;
		line-height: 28rpx;
		transform-origin: center 100%;
	}

	.jx-badge {
		position: absolute;
		font-size: 24rpx;
		height: 30rpx;
		/* #ifdef H5 */
		min-width: 30rpx;
		/* #endif */
		/* #ifndef H5 */
		min-width: 20rpx;
		/* #endif */
		padding: 0 6rpx;
		border-radius: 40rpx;
		right: 0;
		top: -5rpx;
		transform: translateX(60%);
		/* #ifndef APP-PLUS-NVUE */
		display: flex;
		align-items: center;
		justify-content: center;
		/* #endif */
	}

	.jx-badge-dot {
		position: absolute;
		height: 16rpx;
		width: 16rpx;
		border-radius: 50%;
		right: -4rpx;
		top: -4rpx;
	}
	.jx-shadow{
		box-shadow: 0px 0px 5px 0px #eee;
	}
</style>
