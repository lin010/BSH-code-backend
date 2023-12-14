<!-- 这个是拼团详情 -->
<template>
	<view class="detail-root">
		<view class="cart-bg">
			<image class="cart-bg-img" :src="img_url+'cartBg.png'" mode="widthFix"></image>
		</view>
		
		<view class="content-root">
			<view class="good-box flex">
				<image class="good-img" :src="groupShowData.goods.cover_pic" mode=""></image>
				
				<view class="good-detail flex flex-col flex-x-between">
					<view class="over2">{{groupShowData.goods.name}}</view>
					<view class="flex flex-col bottom">
						<view class="group-people-num" :style="{color:textColor,border:'1px solid'+textColor}">{{groupShowData.people}}人团</view>
						<view class="flex flex-x-between flex-y-center price-detail">
							<view class="price" :style="{color:textColor}">
								&yen;{{groupShowData.group_buy_price}}
								<text class="delete-price">&yen;{{groupShowData.goods.original_price}}</text>
							</view>
							<view class="num">×1</view>
						</view>
					</view>
				</view>
			</view>
			
			<view class="time">
				<view class="flex flex-y-center tiem-detail">
					<view style="color: #000000;font-size: 26rpx;">剩余时间：</view>
					
					<view class="tui-countdown">
						<tui-countdown @end="endOfTime" :time="countDownTime" :height="32" :width="36" :size="24" :color="textColor" :borderColor="textColor" colonColor="#e41f19" :scale="true"></tui-countdown>
					</view>
				</view>
			</view>
			
			<view class="group-people flex">
				<view class="people-item bor" v-for="(item,index) in groupShowData.activeItems">
					<image class="item-img" :src="item.user.avatar_url" mode=""></image>
					<view class="leader" v-if="item.is_creator==1" :style="{background:textColor}">团长</view>
				</view>
				<view class="people-item bor" v-for="(item,index) in distancePeople">
					<view class="symbol">?</view>
				</view>
				
			</view>
			
			<view v-if="groupShowData.status==1" class="">
				<view class="tips">还差 <text class="num" :style="{color:textColor}">{{distancePeople}}位</text> 好友即可完成拼团</view>
				<view v-if="!is_share" class="join-btn" :style="{background:textColor}" @tap='join'>邀请好友</view>
				
				<view v-if="is_share" class="join-btn" :style="{background:textColor}" @tap='goJoin'>加入拼团</view>
			</view>
			
			<!-- 拼团成功/失败 -->
			<view v-if="groupShowData.status==2" class="after-group">
				<view class="tips">
					<image class="" src="http://jx.shuzixiangdao.com/web/static/images/group-by/group_buy_success.png" mode=""></image>
					恭喜您，拼团成功啦！
				</view>
				<view class="after-time">时间：{{groupShowData.end_at}}</view>
			</view>
			<view v-if="groupShowData.status==3" class="after-group">
				<view class="tips">
					<image class="" src="http://jx.shuzixiangdao.com/web/static/images/group-by/group_buy_fail.png" mode=""></image>
					人数未满，拼团失败了！
				</view>
				<view class="after-time">时间：{{groupShowData.end_at}}</view>
			</view>
			<!-- 点击邀请好友，分享/生成海报 -->
			<view v-if="groupShowData.status==2||groupShowData.status==3" class="join-btn" :style="{background:textColor}" @tap='toGroupGood'>查看更多拼团信息</view>
			
			
			<view class="guessLike">猜你喜欢</view>
			<view style="padding: 0 20rpx;">
				<commodity :listStyle='listStyle' key-value='cart' :displayStyle='displayStyle' :productData='productData'></commodity>
			</view>
			
		</view>
		
		<!--底部选择层 邀请好友 -->
		<com-bottom-popup :show="popupShow" @close="hidePopup" borderRadius="30rpx 30rpx 0 0">
			<view class="bottom-popup-title">快把拼团分享给好友，邀请好友一起加入吧~</view>
			<view class="share-list flex">
				<!-- #ifdef MP-WEIXIN -->
				<!-- <view class="flex flex-y-center flex-col" style="position: relative;" v-if="is_wechat"> -->
				<view class="flex flex-y-center flex-col" style="position: relative;" >
					<image class="share-img" :src="img_url + '/chat/wxImg.png'" mode=""></image>
					<view style="color: #000000;font-size: 24rpx;">发给好友</view>
					<button class="share-btn" open-type="share">分享</button>
				</view>
				<!-- #endif -->
				<view class="flex flex-y-center flex-col" @tap="navTo('/mch/group-buy/poster')">
					<image class="share-img" :src="img_url + '/chat/wxCode.png'" mode=""></image>
					<view style="color: #000000;font-size: 24rpx;">生成海报</view>
				</view>
			</view>
		</com-bottom-popup>
		<!--底部选择层-->
		
		
		<!--底部选择层，渲染普通购买/拼团对应数据 use_attr:1多规格 -->
		<com-bottom-popup :show="popupShow2" @close="hidePopup2">
			<view class="tui-popup-box" style="border-radius: 20rpx;">
				<view v-if="goodsData.use_attr == 1" class="tui-product-box tui-padding">
					<image :src="selectData.pic_url?selectData.pic_url:goodsData.cover_pic" class="tui-popup-img"></image>
					<view class="tui-popup-price">
						<view class="tui-amount tui-bold">¥{{is_index==1?selectData.group_buy_price:selectData.price}}</view>
						<view class="tui-number">已选:{{strName}}</view>
						<view class="tui-number">库存:{{selectData.stock}}</view>
					</view>
				</view>
				<view v-else class="tui-product-box tui-padding">
					<image :src="goodsData.cover_pic" class="tui-popup-img"></image>
					<view class="tui-popup-price" v-if="goodsData.attr_list">
						<!-- 如果是默认规格 -->
						<block v-if="!goodsData.attr_groups">
							<view class="tui-amount tui-bold">¥{{is_index==2?selectData.price:selectData.group_buy_price}}</view>
							<view class="tui-number">已选:默认</view>
						</block>
						<block v-else>
							<view class="tui-amount tui-bold">¥{{goodsData.attr_list[0].price}}</view>
							<view class="tui-number">已选:{{goodsData.attr_list[0].attr_list[0].attr_name}}</view>
						</block>
					</view>
				</view>
				<scroll-view scroll-y class="tui-popup-scroll">
					<view class="tui-scrollview-box">
						<!-- 规格 -->
						<view>
							<view v-if="!goodsData.use_attr">-
								<block v-if="goodsData.attr_list">
									<view class="tui-bold tui-attr-title">{{goodsData.attr_list[0].attr_list[0].attr_group_name}}</view>
									<view class="tui-attr-box">
										<view class="tui-attr-item tui-attr-active">
											{{goodsData.attr_list[0].attr_list[0].attr_name}}
										</view>
									</view>
								</block>
							</view>
							<view v-for="(item,index) in goodsData.attr_groups" :key='index' v-else>
								<view class="tui-bold tui-attr-title">{{item.attr_group_name}}</view>
								<view class="tui-attr-box">
									<view class="tui-attr-item" @tap="specCol(item.attr_group_id,items.attr_id,items)" :class="queryAttr(items) ?'tui-attr-active':''"
									 v-for="(items,indexs) in item.attr_list" :key='indexs'>
										{{items.attr_name}}
									</view>
								</view>
							</view>
						</view>
						<!-- 规格-->
		
						<view class="tui-number-box tui-bold tui-attr-title">
							<view class="tui-attr-title">数量</view>
							<!-- 这里最大与显示数量不能超当前规格库存  拼团时商品数量只能为1 -->
							<tui-numberbox v-if="is_index==1" :max="1" :min="1" :value="1" :disabled="true"></tui-numberbox>
							<tui-numberbox v-if="is_index==2" :max="selectData.stock" :min="1" :value="value<selectData.stock?value:selectData.stock" @change="change"></tui-numberbox>
						</view>
		
					</view>
				</scroll-view>
				<view class="tui-operation tui-operation-right tui-right-flex tui-popup-btn">
					<view class="sure-btn" :style="{background:textColor}" @tap="determine">确定</view>
				</view>
				<view class="tui-icon tui-icon-close-fill tui-icon-close" style="color: #999;font-size:16pt" @tap="hidePopup"></view>
			</view>
		</com-bottom-popup>
		
		
	</view>
</template>

<script>
	import commodity from '@/components/commodity/commodity';
	import tuiCountdown from "@/components/tui-countdown/tui-countdown"
	import tuiNumberbox from "@/components/numberbox/numberbox"
	import { isWeChat } from '@/utils/util.js';
	export default {
		components: {
			commodity,
			tuiCountdown,
			tuiNumberbox
		},
		data() {
			return {
				img_url: this.$api.img_url,
				listStyle: 2, //1为大图 2为一行一个 3为一行两个 4一行三个 5为左右滑动
				displayStyle: 1, //1为常规 2为边框 3为居中显示 4为边框居中
				productData: [],
				groupShowData : {},
				distancePeople : '',	//满团还差几人
				countDownTime:'1',		//假设拼团倒计时还没有结束
				// 底部弹窗
				popupShow: false,
				is_wechat:true, // 判断是否在微信环境
				is_index : 1,
				detailId : '',	//该拼团id
				is_share : 0,	//默认不是分享
				goods_id : '',	//拼团商品id
				
				timeList: [1000, 2000, 3000, 20, 240000,60],
				
				// 商品详情
				popupShow2 : false,	//默认不弹出规格弹窗
				goodsData: '', //商品详情数据
				strs: [], //已选规格id拼接成字符串，进行对比
				strName: '', //已选规格名拼接成字符串
				sales: 0,
				selectArr: [], //多规格选中数组
				selectData: '', //选中的多规格不同价格图片数据
				textColor:'#bc0100',	//全局字体颜色
				
			}
		},
		onLoad(e) {
			this.textColor = this.globalSet('textCol');
			// 获取全局设置的样式
			let navBg = this.globalSet('navBg');
			let navCol = this.globalSet('navCol');
			console.log(navCol);
			if(navBg&&navCol){
				uni.setNavigationBarColor({
					frontColor: navCol,
					backgroundColor: navBg,
					animation: {
						duration: 500,
						timingFunc: 'easeIn'
					}
				});
			}
			if(e.goods_id){
				this.goods_id = e.goods_id;
				this.getGoodsDetail();
			}
			
			//分享来的时候才有pid		-存parentId
			if(e.pid){	
				try {
				    uni.setStorageSync('parentId', e.pid);
					this.is_share = e.is_share;
				} catch (e) {
				    // error
				}
			}
			this.detailId = e.detailId;
			console.log(this.detailId);
			
			// #ifdef H5
			this.is_wechat = isWeChat();
			console.log(isWeChat(),'isWeChat()');
			// #endif
			
			this.groupShow(this.detailId);
			this.getRecommend();
			
			let local = location.href; 
			// http://localhost:81/#/mch/group-buy/detail?detailId=107
			let shareUrl = local+'&is_index=1';		//也许还要加上登录的信息
			console.log(local);
		},
		
		/**
		 * 条件编译：1.团id, 2、是拼团 3、H5/小程序分享
		 * */
		
		
		methods: {
			
			// 0.1 一键复制链接
			copyUrl(){
				console.log('一键复制链接');
			},
			
			
			// 0.2 邀请好友
			join(){
				this.popupShow = true;
			},
			
			// 0.2.1 加入该团-跳转到订单预览的页面
			goJoin(){
				// 1.弹窗选规格
				this.popupShow2 = true;
			},
			
			
			// 0.3 查看更多拼团--跳转对应的拼团商品
			toGroupGood(){
				uni.navigateTo({
					url:'/mch/group-buy/good?proId='+this.goods_id
				})
			},
			
			hidePopup(num) { //关闭底部弹窗
				this.popupShow = false;
				if (num == 1) {
					this.$http.toast("购物车加入成功");
				}
			},
			// 关闭规格弹窗
			hidePopup2(){
				this.popupShow2 = false;
			},
			
			
			//加入拼团，点击确定按钮
			determine() {
				this.popupShow2 = false;
				if (this.goodsData.use_attr == 0) {
					if (this.goodsData.attr_list[0].stock == 0) {
						this.$http.toast('该商品已没有库存!');
						return;
					}
				} else {
					if (this.selectData.stock == 0) {
						this.$http.toast('该商品已没有库存!');
						return;
					}
				}
				/* 这里的逻辑可能有点问题 */
				if (this.is_index == 1) {  // is_index == 1开团
					/* 这里要记录下拼团价格 */
					var goods_attr_id = this.goodsData.attr_groups ? this.selectData.id : this.goodsData.attr_list[0].id;
					// 设置订单数据缓存
					uni.setStorage({
						key: 'orderData',
						data: [{
							num: 1,
							goods_attr_id: goods_attr_id,
							id: this.goods_id,	//商品id
							cart_id: 0
						}],
						fail() {
							console.log('存入本地失败');
						}
					});
					// 然后在去订单预览的页面
					uni.navigateTo({
						url:'/mch/group-buy/submit?is_index=1&goods_id='+this.goods_id+'&active_id='+this.detailId
					})
					
					// uni.navigateTo({
					// 	url: '/mch/group-buy/submit?goods_id='+this.proId+'&is_index=1&active_id='+this.active_id	//这个是拼团购买流
					// })
					
				}
			},
			
			queryAttr(items) {
				let temp = this.selectArr.find(v => v.attr_id == items.attr_id);
				return temp ? true : false;
			},
			specCol(attr_group_id, attr_id, items) {
				this.strs = [];
				this.selectArr.forEach((item, index) => {
					if (item.attr_group_id == attr_group_id) {
						this.selectArr.splice(index, 1, {
							'attr_group_id': attr_group_id,
							...items
						})
					}
				})
				//拼接所选属性名
				var arr = [];
				this.selectArr.forEach((item) => {
					arr.push(item.attr_name);
				})
				this.strName = arr.join('， ');
			
				// console.log(this.selectArr, 'this.selectArr');
				this.skuCommon();
			},
			
			//2.0 请求商品详情数据
			getGoodsDetail() {
				this.loading = true;
				this.$http.request({
					url: this.$api.plugin.group.detail,
					data: {
						id: this.goods_id
					},
				}).then((res) => {
					this.loading = false;
					if (res.code == 0) {
						this.goodsData = res.data.goods;
						this.sales = (this.goodsData.sales * 1) + (this.goodsData.group_buy.cumulative_sales * 1);
						// #ifdef H5
						this.$wechatSdk.initShareUrl(res.data.share_data, 'source=3');
						// #endif
			
						if (this.goodsData.attr_groups) {
							this.goodsData.attr_groups.forEach((item) => {
								this.selectArr.push({
									'attr_group_id': item.attr_group_id,
									...item.attr_list[0]
								})
							})
						}else{	//默认规格
							this.selectData = this.goodsData.attr_list[0];
						}
						
			
						var arr = [];
						this.selectArr.forEach((item) => {
							arr.push(item.attr_name);
						})
						this.strName = arr.join('， ');
						this.skuCommon();
					} else {
						uni.showModal({
							content: res.msg,
							showCancel: false,
							confirmColor: this.textColor,
							success: function(res) {
								if (res.confirm) {
									uni.navigateBack();
								}
							}
						});
					}
				})
			},
			
			skuCommon() {
				this.selectArr.forEach(item => this.strs.push(item.attr_id));
			
				this.goodsData.attr_list.forEach((item) => {
					if (item.sign_id == this.strs.join(':')) {
						this.selectData = item;
					}
				})
			},
			
			
			//2.2 猜你喜欢
			getRecommend() { 
				this.loading = true;
				this.$http.request({
					url: this.$api.goods.recommend,
					data: {
						page: 1
					}
				}).then((res) => {
					this.loading = false;
					if (res.code == 0) {
						this.productData = res.data.list;
					}
				})
			},
			
			// 2.0 获取拼团详情
			groupShow(id) {
				this.$http.request({
					url: this.$api.plugin.group.group_show,
					method: 'post',
					data: {
						id: id,
					}
				}).then((res) => {
					if (res.code == 0) {
						console.log(res.data);
						this.groupShowData = res.data;
						this.distancePeople = parseInt(this.groupShowData.people) - parseInt(this.groupShowData.activeItems.length);
						// 倒计时
						let endAtTime = this.getUnixTime(this.groupShowData.end_at);
						this.countDownTime = endAtTime - parseInt(new Date().getTime()/1000);
						console.log('this.countDownTime:'+this.countDownTime);
						
						// console.log(parseInt(this.groupShowData.people));
						// console.log(parseInt(this.groupShowData.activeItems.length));
						// console.log('this.distancePeople:'+this.distancePeople);
					}
					
				})
			},
			
			// 2.页面点击跳转
			navTo(url) {
				uni.navigateTo({
				    url: '/mch/group-buy/poster?detail_id='+this.detailId+'&goods_id='+this.goods_id
				});
			},
			
			// 2019-06-17 23:11:54转时间戳
			getUnixTime(dateStr){
			    var newstr = dateStr.replace(/-/g,'/'); 
			    var date =  new Date(newstr); 
			    var time_str = date.getTime().toString();
			    return time_str.substr(0, 10);
			},
			
		}
	}
</script>

<style lang="scss" scoped>
	.cart-bg {
		position: absolute;
		top: -160rpx;
		// top: 0rpx;
		// height: 260rpx;
		width: 100%;
		// border-radius: 50% / 20%;
		// background-color: #ccc;
	}
	
	.cart-bg-img {
		width: 100%;
	}
	
	.content-root{
		position: relative;
		z-index: 2;
		
		.good-box{
			background: #FFFFFF;
			border-radius: 10rpx;
			box-shadow: 4rpx 8rpx 16rpx 8rpx rgba(142,142,142,0.3);
			padding: 36rpx 28rpx;
			width: 91%;
			margin: 20rpx auto;
			
			.good-img{
				width: 194rpx;
				height: 194rpx;
				border-radius: 10rpx;
				margin-right: 28rpx;
			}
			.good-detail{
				flex: 1;
				color: #000;
				font-size: 28rpx;
				
				.bottom{
					align-items: flex-start;
				}
			}
			.group-people-num{
				font-size: 24rpx;
				// opacity: .1;
				border-radius: 40rpx;
				padding: 0rpx 16rpx;
				letter-spacing: 1px;
				transform: scale(0.9);
				position: relative;
				left: -2px;
			}
			.price-detail{
				width: 100%;
				
				.price{
					.delete-price{
						margin-left: 14rpx;
						color: #999999;
					}
				}
				.num{
					font-weight: 600;
					font-size: 26rpx;
				}
			}
		}
		
		.time{
			border-bottom: 1px solid #CCCCCC;
			width: 91%;
			margin: 68rpx auto 58rpx;
			position: relative;
			
			.tiem-detail{
				position: absolute;
				left: 50%;
				transform: translateX(-50%);
				top: -24rpx;
				background: #fafafa;
				padding: 0 16rpx;
				
			}
		}
		
		.group-people{
			width: 594rpx;
			margin: auto;
			flex-wrap: wrap;
			
			.people-item{
				width: 100rpx;
				height: 100rpx;
				border-radius: 50%;
				position: relative;
				margin-right: 20rpx;
				margin-bottom: 14rpx;
				
				&:nth-of-type(5n){
					margin-right: 0;
				}
				.item-img{
					width: 100%;
					height: 100%;
					border-radius: 50%;
					display: block;
				}
				.leader{
					font-size: 24rpx;
					width: 64rpx;
					color: #FFFFFF;
					text-align: center;
					position: absolute;
					bottom: -10rpx;
					left: 50%;
					transform: translateX(-50%) scale(0.9);
					letter-spacing: 1px;
				}
				.symbol{
					position: absolute;
					top: 50%;
					left: 50%;
					transform: translate(-50%,-50%);
					font-size: 46rpx;
					color: #999999;
				}
			}
			.bor{
				border: 1px dashed #CCCCCC;
			}
			
		}
		
		.tips{
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			margin-top: 40rpx;
			font-size: 28rpx;
			color: #000000;	
			image{
				width: 40rpx;
				height: 40rpx;
				display: block;
				margin-right: 22rpx;
			}
			
			.num{
				font-size: 36rpx;
				margin: 0 10rpx;
				font-weight: 600;
			}
		}
		.after-group{
			.after-time{
				font-size: 24rpx;
				font-family: Source Han Sans CN Regular, Source Han Sans CN Regular-Regular;
				font-weight: 400;
				text-align: left;
				color: #666666;
				line-height: 60rpx;
				letter-spacing: 1rpx;
				text-align: center;
			}
		}
		
		.join-btn{
			font-size: 34rpx;
			width: 578rpx;
			height: 88rpx;
			border-radius: 50rpx;
			color: #FFFFFF;
			text-align: center;
			line-height: 88rpx;
			margin: 50rpx auto;
		}
	}
	
	/* 猜你喜欢 */
	.guessLike {
		text-align: center;
		font-weight: 700;
		font-size: 12pt;
		color: #000000;
		padding: 50rpx 0;
	}
	
	/*底部选择弹层*/
	.cancel{
		background: #F1EFF2;
		text-align: center;
		padding: 20rpx 0;
	}
	.share-list{
		padding: 40rpx;
		justify-content: space-around;
	}
	.share-img{
		width: 100rpx;
		height: 100rpx;
		margin-bottom: 10rpx;
	}
	.share-btn{
		position: absolute;
		top: 0;
		left: 50%;
		transform: translateX(-50%);
		width: 100rpx;
		height: 100rpx;
		opacity: 0;
		z-index: 3;
	}
	.bottom-popup-title{
		font-size: 26rpx;
		color: #000000;
		font-weight: 600;
		text-align: center;
		margin-top: 30rpx;
	}
	/*底部选择弹层*/
	
	
	/*底部选择弹层*/
	
	.tui-popup-box {
		position: relative;
		padding: 30rpx 0 100rpx 0;
	}
	
	.tui-popup-btn {
		width: 100%;
		position: absolute;
		left: 0;
		bottom: 0;
	}
	
	.tui-icon-close {
		position: absolute;
		top: 30rpx;
		right: 30rpx;
	}
	
	.tui-product-box {
		display: flex;
		align-items: center;
		font-size: 9pt;
		padding-bottom: 30rpx;
	}
	
	.tui-popup-img {
		height: 200rpx;
		width: 200rpx;
		border-radius: 24rpx;
		display: block;
	}
	
	.tui-popup-price {
		padding-left: 20rpx;
		padding-bottom: 8rpx;
		flex: 1;
	}
	
	.tui-amount {
		color: #ff201f;
		font-size: 14pt;
	}
	
	.tui-number {
		font-size: 9pt;
		line-height: 28rpx;
		padding-top: 12rpx;
		color: #999;
	}
	
	.tui-popup-scroll {
		height: 600rpx;
		font-size: 10pt;
	}
	
	.tui-scrollview-box {
		padding: 0 30rpx 60rpx 30rpx;
		box-sizing: border-box;
	}
	
	.tui-attr-title {
		padding: 10rpx 0;
		color: #333;
	}
	
	.tui-attr-box {
		font-size: 0;
		padding: 20rpx 0;
	}
	
	.tui-attr-item {
		max-width: 100%;
		min-width: 200rpx;
		height: 64rpx;
		display: -webkit-inline-flex;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: #f7f7f7;
		padding: 0 26rpx;
		box-sizing: border-box;
		border-radius: 10rpx;
		margin-right: 20rpx;
		margin-bottom: 20rpx;
		font-size: 9.5pt;
	}
	
	.tui-attr-active {
		background: #fcedea !important;
		color: #e41f19;
		font-weight: bold;
		position: relative;
		border-radius: 10rpx;
	}
	
	.tui-attr-active::after {
		content: "";
		position: absolute;
		border: 1rpx solid #e41f19;
		width: 100%;
		height: 100%;
		border-radius: 10rpx;
		left: 0;
		top: 0;
	}
	
	.tui-number-box {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 20rpx 0 30rpx 0;
		box-sizing: border-box;
	}
	
	/*底部选择弹层*/
	.tui-operation {
		width: 100%;
		height: 100rpx;
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
	.tui-operation-right {
		height: 94rpx;
		/* box-sizing: border-box; */
		padding: 0 10rpx;
	}
	.tui-right-flex {
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.sure-btn {
		border-radius: 40rpx;
		width: 90%;
		font-size: 11pt;
		padding: 14rpx 0;
		margin: auto;
		color: #FFFFFF;
		text-align: center;
	}
	
</style>
