<!-- 这个就是评论列表页 -->
<template>
	<view class="container">
		<!-- <com-nav-bar @clickLeft="back" left-icon="back" title="商品评论" background-color="#ffffff" color="#000000"></com-nav-bar> -->
		<!-- commonList -->
		<view class="commonList">
			<view class="tab-list">
				<span v-for="its in commentCount" @click="countState(its.index)" :class="its.index==tIndex?'active_tap':''">{{its.name}}({{its.count}})</span>
			</view>
			<view class="list">
				<view v-for="(its,index) in commentsData" class="list-items">
					<view class="item-top">
						<view class="item-top-left">
							<image :lazy-load="true" mode="aspectFill" :src="its.avatar" class="avatar-img"></image>
							<span>{{its.nickname}}</span>
							<view class="item-star">
								<image v-for="(item,index) in its.score" :lazy-load="true" mode="aspectFill"
									src="http://jx.shuzixiangdao.com/web/static/images/product/commont-star.png" class="star-img">
								</image>
								
							</view>
						</view>
						<view class="item-top-time">{{its.time}}</view>
					</view>
					<view class="item-main">
						<span>{{its.content}}</span>
						<view class="img-list" v-if="its.pic_url.length">
							<image v-for="(item,index) in its.pic_url" :lazy-load="true" mode="aspectFill" @click="previewImg(item,its.pic_url)"
							:src="item" class="commont-item-img"></image>
						</view>
					</view>
					<view class="business-reply" v-if="its.reply_content">
						<view class="title">商家回复</view>
						<view>{{its.reply_content}}</view>
					</view>
				</view>
				
			</view>
		</view>
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				is_index: 1, //1是加入购物车，2是立即下单
				proId: 0, //商品id
				goodRate: '', // 商品好评率
				commentsData: [], //评论数据
				commentCount: [], //评论类型数据
				goodsData: '', //商品详情数据
				bannerLength: 0, //轮播图有多少张
				attrGroupsLength: 0, //有多少条多规格属性
				strs: [], //已选规格id拼接成字符串，进行对比
				strName: '', //已选规格名拼接成字符串
				selectArr: [], //多规格选中数组
				selectData: '', //选中的多规格不同价格图片数据
				height: 64, //header高度
				top: 0, //标题图标距离顶部距离
				scrollH: 0, //滚动总高度
				opcity: 0,
				iconOpcity: 0.5,
				insuranceCut_current: 0,
				bannerIndex: 0,
				topMenu: [{
					icon: "home",
					text: "首页",
					size: 23,
					badge: 0
				}, {
					icon: "people",
					text: "我的",
					size: 26,
					badge: 0
				}, {
					icon: "cart",
					text: "购物车",
					size: 23,
					badge: 0
				}, {
					icon: "share",
					text: "分享",
					size: 26,
					badge: 0
				}],
				menuShow: false,
				popupShow: false,
				popupShow2: false, //优惠券弹窗是否显示
				cartList: [], //购物车列表
				cartList_num: 0, //购物车列表
				c_attr_id: -1, //购物车带过来的数据，如果是-1则不是购物车跳转过来
				loading: false,
				showPoster: false,
				poster_url: '',
				
				/* 这里是列表的数据 */
				moreData : true,	//假设有更多评论列表数据
				status : 0,		//默认获取全部数据
				page : 1,		//默认请求第一列数据
				tIndex:0,//索引
				page_count:'',
			}
		},
		onLoad: function(options) {
			let {
				proId,
				pid,
				source
			} = options;
			if (pid) {
				uni.setStorageSync("pid", pid);
			}
			if (source) {
				uni.setStorageSync("source", source);
			}

			this.proId = proId;
			this.getComment(this.status,this.page);
			
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

			setTimeout(() => {
				uni.getSystemInfo({
					success: (res) => {
						this.width = obj.left || res.windowWidth;
						this.height = obj.top ? (obj.top + obj.height + 8) : (res.statusBarHeight + 44);
						this.top = obj.top ? (obj.top + (obj.height - 32) / 2) : (res.statusBarHeight + 6);
						this.scrollH = res.windowWidth
					}
				})
			}, 50)
		},
		// 这里有计算属性
		computed: {
			isReceive() {
				return function(val) {
					if (val != 0) {
						return false;
					} else {
						return true;
					}
				}
			},
			// _commentsCopy() {
			// 	let temp = []
			// 	if (!this.commentsData) {
			// 		return;
			// 	}
			// 	temp = JSON.parse(JSON.stringify(this.commentsData))
			// 	return temp.splice(0, 5);
			// }
		},
		
		/* 监听一下页面的上拉触底事件 */
		onReachBottom() {
			if(this.page==this.page_count){
				return false;
			} 		
			this.page=this.page+1
			this.getComment(this.status,this.page);
		},
		
		methods: {
			// 这就是分享海报的方法
			poster(key) {
				if (key == -1) {
					this.showPoster = false;
					return;
				}
				this.loading = true;
				this.showPoster = true;
				if (this.poster_url) {
					setTimeout(() => {
						this.loading = false;
					}, 1000)
					return;
				}
				this.$http.request({
					url: this.$api.goods.poster,
					method: 'POST',
					data: {
						goods_id: this.proId,
						source: 2
					}
				}).then(res => {
					if (res.code == 0) {
						this.poster_url = res.data.pic_url;
						setTimeout(() => {
							this.loading = false;
						}, 1000)
					}
				})
			},


			// 1.0 tab的点击事件
			countState(status){
				console.log(status)
				this.tIndex=status
				this.status = status;
				this.page = 1;
				this.page_count=''
				this.commentsData = []; //评论数据
				this.getComment(this.status,this.page);
			},
			
			
			//请求评论列表数据
			getComment(status,page) { 
				this.$http.request({
					url: this.$api.goods.comment,
					data: {
						goods_id: this.proId,
						status : status,
						page : page
					},
					showLoading: true
				}).then((res) => {
					if (res.code == 0) {
						this.commentCount = res.data.comment_count;	//tab-list
						if(res.data.comments.length==0)return false
						let list= res.data.comments;
						var arr=this.commentsData.concat(list)
						this.commentsData =arr
						this.page_count = res.data.pagination.page_count;
					}
				})
			},
			
			// 图片预览
			previewImg(item,imgArr){
				uni.previewImage({
					current: item,     		//当前图片地址
					urls: imgArr,     //所有要预览的图片的地址集合 数组形式
				});
			},
			
			back: function() {
				uni.navigateBack()
			},
			
			openMenu: function() {
				this.menuShow = true
			},
			
			closeMenu: function() {
				this.menuShow = false
			},
			
		},
		
	}
</script>

<style lang="scss" scoped>
	/* icon 也可以使用组件*/
	page {
		background-color: #FFFFFF !important;
	}

	.red {
		color: #BC0100;
	}

	.tui-header-box {
		width: 100%;
		position: fixed;
		left: 0;
		top: 0;
		z-index: 9998;
	}

	.tui-header {
		width: 100%;
		font-size: 15pt;
		line-height: 18px;
		font-weight: 500;
		height: 32px;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.tui-header-icon {
		position: fixed;
		top: 0;
		left: 10px;
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		height: 32px;
		transform: translateZ(0);
		z-index: 99999;
	}



	.tui-header-icon .tui-badge {
		background: #e41f19 !important;
		position: absolute;
		right: -5px;
	}

	.tui-icon {
		border-radius: 16px;

		&.iconfont {
			line-height: 32px;
			width: 32px;
			font-size: 20px;
			display: flex;
			justify-content: center;
		}

		margin-right: 10rpx;
	}
	/*顶部菜单*/

	/*内容 部分*/

	.tui-padding {
		padding: 0 30rpx;
		box-sizing: border-box;
	}

	.padding {
		padding: 0 30rpx 0 20rpx;
		box-sizing: border-box;
	}

	.padding {
		padding: 0 20rpx 0 30rpx;
		box-sizing: border-box;
	}

	/* #ifdef H5 */
	.commonList {
		// padding-top: 44px;
	}
	/* #endif */


	.tui-gray {
		color: #808080;
		background-color: #FFFFFF !important;
	}
	/* 这里写评论页面 */
	.commonList {
		padding-top: 20rpx;
		.tab-list{
			position: fixed;
			z-index: 100;
			top: 44px;
			box-sizing: border-box;
			width: 100%;
			padding: 30rpx;
			height: 88rpx;
			display: flex;
			align-items: center;
			
			font-size: 22rpx;
			font-family: Source Han Sans CN Regular, Source Han Sans CN Regular-Regular;
			font-weight: 400;
			text-align: left;
			color: #2d2d2d;
			line-height: 48rpx;
			background-color: #fff;
			span{
				box-sizing: border-box;
				height: 48rpx;
				display: block;
				padding: 0 16rpx;
				margin-right: 12rpx;
				border-radius: 48rpx;
				overflow: hidden;
				background-color: #f5e7cd;
			}
		}
		/* #ifdef MP-WEIXIN || APP-PLUS */
		.tab-list {
			position: fixed;
			top: 0;
		}
		/* #endif */
		// 列表
		.list{
			box-sizing: border-box;
			width: 750rpx;
			margin-top: 50rpx;
			padding: 0 30rpx;
			// padding-top: 88rpx;
			.list-items{
				width: 690rpx;
				padding-bottom: 38rpx;
				border-bottom: 1px solid #F6F6F6;				
				.item-top{
					display: flex;
					align-items: center;
					justify-content: space-between;
					padding: 20rpx 0;
					.item-top-left{
						display: flex;
						align-items: center;
						.avatar-img{
							display: block;
							width: 67rpx;
							height: 67rpx;
							border-radius: 67rpx;
						}
						>span{
							font-size: 22rpx;
							font-family: Source Han Sans CN Regular, Source Han Sans CN Regular-Regular;
							font-weight: 400;
							text-align: left;
							color: #2d2d2d;
							line-height: 42rpx;
							margin: 0 38rpx 0 12rpx;
						}
						.item-star{
							display: flex;
							align-items: center;
							.star-img{
								display: block;
								width: 22rpx;
								height: 22rpx;
								margin-right: 4rpx;
							}
						}
					}
					.item-top-time{
						font-size: 20rpx;
						font-family: Source Han Sans CN Regular, Source Han Sans CN Regular-Regular;
						font-weight: 400;
						text-align: left;
						color: #999999;
						line-height: 42rpx;
					}
					
				}
				.item-main{
					box-sizing: border-box;
					width: 690rpx;
					font-size: 22rpx;
					font-family: Source Han Sans CN Regular, Source Han Sans CN Regular-Regular;
					font-weight: 400;
					text-align: left;
					color: #2d2d2d;
					line-height: 34rpx;
					letter-spacing: 1rpx;
					.main-text{
						
					}
					.img-list{
						display: flex;
						flex-wrap: wrap;
						padding: 20rpx 0;
						.commont-item-img{
							width: 134rpx;
							height: 100rpx;
							border-radius: 5rpx;
							margin-right: 6rpx;
						}
					}
				}
			}
		}
	}
	
	// 商家回复
	.business-reply{
		background: #F6F6F6;
		border-radius: 10rpx;
		padding: 20rpx;
		box-sizing: border-box;
		font-size: 24rpx;
		
		.title{
			font-size: 30rpx;
			color: #4A4633;
			font-weight: 700;
			letter-spacing: 1px;
			margin-bottom: 18rpx;
		}
	}
	.active_tap{
		color: #fff;
	}
</style>
