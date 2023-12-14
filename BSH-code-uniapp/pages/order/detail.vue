<template>
	<view class="container">

		<com-nav-bar @clickLeft="back" left-icon="back" :title="title_text" :status-bar="true" :background-color="navBg" :border="false" :color="navCol"></com-nav-bar>

		<view class="jx-mybg-box">
			<image :src="bg_url" class="jx-my-bg"></image>
			<view class="jx-room" v-if="detail && detail.id">
				<view class="jx-order-status" v-if="detail.order_type=='offline_baopin'||detail.order_type=='offline_normal'">
					<view class="text-container">
						<view class="text" v-if="is_show"></view>
						<view class="msg" v-if="detail.status != 0"></view>
						<view class="msg" v-else></view>
					</view>
					
				</view>
				
				<view class="jx-order-status" v-if="detail.order_type=='express_baopin'||detail.order_type=='express_normal'">
					<template v-if="detail.cancel_status == 1">
						<view class="icon iconfont icon-47guanbi"></view>
						<view class="text-container">
							<view class="text">已关闭</view>
							<view class="msg">{{detail.cancel_at}}</view>
						</view>
					</template>
					<template v-else>
						<view class="icon iconfont icon-daifukuan3" v-if="detail.status == 0"></view>
						<view class="icon iconfont icon-fahuo" v-else-if="detail.status == 1"></view>
						<view class="icon iconfont icon-daifahuo" v-else-if="detail.status == 2"></view>
						<view class="icon iconfont icon-daipingjia1" v-else-if="detail.status == 3"></view>
						<view class="icon iconfont icon-yiwancheng" v-else-if="detail.status > 5"></view>
						<view class="text-container">
							<view class="text" v-if="is_show">{{detail.status_text}}</view>
							<view v-else>售后申请</view>
							<view class="msg" v-if="detail.status != 0">{{detail.status==2&&detail.expand_num==1?'14天后自动收货':statusText[detail.status]}}</view>
							<view class="msg" v-else>{{detail.cancel_at}}后关闭订单</view>
						</view>
						<view class="btn" v-if="is_show">
							<!-- <view @click.stop="goComment(detail.id)">
								<tui-button type="white" width="148rpx" height="56rpx" :size="26" shape="circle" :style="{color: textColor+'!important'}"
								 v-show="btnText[detail.status]">{{btnText[detail.status]}}</tui-button>
							</view> -->
						</view>
					</template>
				</view>

				<view class="jx-order-user jx-radius" v-if="messageShow">
					<view class="jx-address view">
						<view class="jx-flex-box">
							<view class="jx-icon-img iconfont icon-shouhuodizhi"></view>
							<view class="jx-addr">
								<view class="jx-addr-userinfo">{{detail.name}}<text class="jx-addr-tel">{{detail.mobile}}</text></view>
								<view class="jx-addr-text">{{detail.address}}</view>
							</view>
						</view>
					</view>
				</view>
				<view class="jx-order-item">
					<view class="jx-goods-list jx-radius" v-if="detail.order_goods_list && detail.order_goods_list.length > 0">
						<block v-for="(item,index) in detail.order_goods_list" :key="item.id">
							<view class="jx-goods-item" @click="toGoodsDateil(item.goods_id)">
								<image :src="item.goods_info.pic_url" lazy-load="true" class="jx-goods-img"></image>
								<view class="jx-goods-center">
									<view class="jx-goods-name">{{item.goods_info.name}}</view>
									<view class="jx-goods-attr">{{item.goods_info.attr_list[0].attr_name}}</view>
								</view>
								<view class="jx-price-right">
									<view class="price">¥{{item.goods_info.total_original_price}}</view>
									<view class="num">x{{item.goods_info.num}}</view>
									<view class="btn"  v-if="detail.order_type!='offline_baopin'&&detail.order_type!='offline_normal'">
										<view v-if="item.refund_status == 0 && !showRefund(detail.status)" @click.stop="goRefund(item.id)">
											<tui-button type="black" :plain="true" width="80rpx" height="32rpx" :size="24" shape="circle" style="color: #808080 !important;margin-left: 30rpx;">
												{{detail.status > 1 ? '退换' : item.goods_info.is_refund ? '退款中' : '退款'}}
											</tui-button>
										</view>

										<view style="margin-top:30rpx;" v-if="item.refund_status == 10">售后待处理</view>
										<view style="margin-top:30rpx;" v-else-if="item.refund_status == 11">退款已同意</view>
										<view style="margin-top:30rpx;" v-else-if="item.refund_status == 12">退款退货中</view>
										<view style="margin-top:30rpx;" v-else-if="item.refund_status == 20">已退款</view>
										<view style="margin-top:30rpx;" v-else-if="item.refund_status == 21">退款已拒绝</view>
										<!-- <view style="margin-top:30rpx;" v-if="item.diy_refund_status == 1">退款中</view>
										<view style="margin-top:30rpx;" v-else-if="item.diy_refund_status == 2">已退款</view>
										<view style="margin-top:30rpx;" v-else-if="item.diy_refund_status == 3">退款退货中</view>
										<view style="margin-top:30rpx;" v-else-if="item.diy_refund_status == 4">已退款退货</view>
										<view style="margin-top:30rpx;" v-else-if="item.diy_refund_status == 5">换货中</view>
										<view style="margin-top:30rpx;" v-else-if="item.diy_refund_status == 6">换货完成</view> -->

										<view v-if="item.refund_status == 1 && !undone(detail.status)">{{'售后中'}}</view>
									</view>
								</view>
							</view>
<!-- 							<view class="personalCenter-item" v-if="item.shopExpress">
								<jx-list-cell :arrow="true" padding="0" :lineLeft="false" @click="href(item.orderGoodsConsumeVerification.id
)">
									<view class="jx-cell-header">
										<view class="jx-cell-title" style="font-weight: 500;">到店消费商品查看</view>
										<view class="jx-cell-sub">使用</view>
									</view>
								</jx-list-cell>
							</view> -->
						</block>
					</view>
					<!--店铺信息-->
					<!-- v-if="item.=='offline_normal'" -->
					<view class="er-code" v-if="detail.order_type=='offline_normal'">
						<view class="code_logo">
							<view class="mask" v-if="showCode">
								<image :src="img_url+'/gou.png'" mode=""></image>
							</view>
							<image :src="codeDetail.file_path" mode="" style="border: 1rpx solid #337766;"></image>
							<text>核销码：{{codeDetail.code}}</text>
							<text></text>
						</view>
					</view>
					<info :params="params.mch" v-if="params.is_mch==1"></info>
					<!-- <view class="personalCenter-item" v-if="detail.order_type=='offline_baopin'">
													<jx-list-cell :arrow="true" padding="0" :lineLeft="false" 
													@click="offlineTo(detail.id)">
														<view class="jx-cell-header">
															<view class="jx-cell-title" style="font-weight: 500;">去使用</view>
															<view class="jx-cell-sub">使用</view>
														</view>
													</jx-list-cell>
												</view> -->
					<view class="jx-order-info jx-radius">
						<view class="jx-info-flex jx-size24">
							<view class="jx-info-text">订单编号</view>
							<view class="jx-text">{{detail.order_no}}</view>
							<view class="btn" @click.stop="copyText(detail.order_no)">
								<tui-button type="black" :plain="true" width="80rpx" height="32rpx" :size="24" shape="circle" style="color: #808080 !important;">复制</tui-button>
							</view>
						</view>
						<view class="jx-info-flex jx-size24">
							<view class="jx-info-text">创建时间</view>
							<view class="jx-text">{{detail.created_at}}</view>
						</view>
						<view class="jx-info-flex jx-size24" v-if="detail.pay_at">
							<view class="jx-info-text">支付时间</view>
							<view class="jx-text">{{detail.pay_at}}</view>
						</view>
						<view class="jx-info-flex jx-size24" v-if="detail.send_at">
							<view class="jx-info-text">发货时间</view>
							<view class="jx-text">{{detail.send_at}}</view>
						</view>
						<view class="jx-info-flex jx-size24" v-if="detail.status == 3">
							<view class="jx-info-text">确认收货</view>
							<view class="jx-text">{{detail.confirm_at}}</view>
						</view>
						<view class="jx-info-flex jx-size24" v-if="detail.status == 5">
							<view class="jx-info-text">取消时间</view>
							<view class="jx-text">{{detail.cancel_at}}</view>
						</view>
						<view class="jx-info-flex jx-size24" v-if="detail.status >= 2">
							<view class="jx-info-text">配送方式</view>
							<view class="jx-text">{{detail.send_type_text}}</view>
						</view>

						<view class="jx-info-flex jx-size24  jx-border-top">
							<view class="jx-info-text">买家留言</view>
							<view class="jx-text">{{detail.remark || '无'}}</view>
						</view>
						
						<view class="jx-info-flex jx-size24 last jx-border-top">
							<view class="jx-info-text">商家留言</view>
							<view class="jx-text">{{detail.seller_remark || '无'}}</view>
						</view>
					</view>
					<view class="jx-goods-info jx-radius">
						<view class="jx-price-flex jx-size24">
							<view>订单金额</view>
							<view class="jx-price">¥{{detail.total_goods_original_price}}</view>
						</view>
						<view class="jx-price-flex jx-size24" v-if="detail.member_deduction_price_total != 0">
							<view>会员抵扣</view>
							<view class="jx-price">- ¥{{detail.member_deduction_price_total}}</view>
						</view>
						<view class="jx-price-flex jx-size24" v-if="detail.score_deduction_price != 0">
							<view>{{currencyAlias.integral_alias}}抵扣</view>
							<view class="jx-price">- ¥{{detail.score_deduction_price}}</view>
						</view>
						<view class="jx-price-flex  jx-size24">
							<view>{{currencyAlias.red_envelope_alias}}</view>
							<view class="jx-price">- ¥{{detail.coupon_discount_price}}</view>
						</view>
						<view class="jx-price-flex  jx-size24" v-if="false">
							<view>{{currencyAlias.red_envelope_alias}}</view>
							<view class="jx-price">¥0.00</view>
						</view>
						<view class="jx-price-flex  jx-size24">
							<view>{{currencyAlias.silver_beans_alias}}</view>
							<view class="jx-price">- ¥{{detail.shopping_voucher_use_num}}</view>
						</view>
						<view class="jx-price-flex  jx-size24" v-if="messageShow">
							<view>运费</view>
							<view class="jx-price">+ ¥{{detail.express_price}}</view>
						</view>
						<view class="jx-price-flex jx-size32 jx-border-top">
							<view class="tui-flex-shrink">实付款</view>
							<view class="jx-goods-price jx-primary-color" :style="{color:textColor}">
								<view class="jx-size-24">¥</view>
								<view class="jx-price-large">{{detail.total_pay_price.slice(0,-3)}}</view>
								<view class="jx-size-24">{{detail.total_pay_price.slice(-3)}}</view>
							</view>
						</view>
					</view>
				</view>
				<block v-if="detail.cancel_status==0&&is_show&&detail.order_type!='offline_baopin'&&detail.order_type!='offline_normal'">
					<view class="jx-tabbar tui-order-btn" v-if="detail.status != 8">
						<view class="jx-btn-mr" v-if="detail.status == 5">
							<view class="btns" @click="deleteOrderById(detail.id)">删除订单</view>
						</view>
						<view class="jx-btn-mr" v-if="detail.status == 0">
							<view class="btns" @click="cancel(detail.id)">取消订单</view>
						</view>
						<view class="jx-btn-mr" v-if="detail.status == 0">
							<view class="btns" @click="pay(detail.id)" :style="{color:textColor,border: '1px solid '+textColor}">去支付</view>
						</view>
						<view class="jx-btn-mr" v-if="detail.status == 1&&active_status!=1">
							<view class="btns" @click="remindSendById(detail.id)" :style="{color:textColor,border: '1px solid '+textColor}">提醒发货</view>
						</view>
						<view class="jx-btn-mr" v-if="detail.status == 2">
							<view class="btns" style="color:#8F8D8E,border: 1px solid #8F8D8E" @tap="toPage(detail.id)">查看物流</view>
						</view>
						<view class="jx-btn-mr" v-if="detail.status == 2">
							<view class="btns" style="color:#8F8D8E,border: 1px solid #8F8D8E" @tap="extended(detail.id)">延长收货</view>
						</view>
						<view class="jx-btn-mr" v-if="detail.status == 2">
							<view class="btns" @click="confirm(detail.id)" :style="{color:textColor,border: '1px solid '+textColor}">确认收货</view>
						</view>
						<view class="jx-btn-mr" v-if="detail.status == 3">
							<view class="btns" @click="goComment(detail.id)" :style="{color:textColor,border: '1px solid '+textColor}">去评价</view>
						</view>
						<view class="jx-btn-mr" v-if="detail.status == 4">
							<view class="btns" :style="{color:textColor,border: '1px solid '+textColor}">撤销申请</view>
						</view>
						<!-- <view class="jx-btn-mr" v-if="detail.status >= 2 && detail.status != 5 && detail.status != 4">
							<tui-button type="black" :plain="true" width="148rpx" height="56rpx" :size="26" shape="circle">加入购物车</tui-button>
						</view> -->
					</view>
				</block>
				<view class="guessLike">
					猜你喜欢
				</view>
				<view style="padding: 0 20rpx;">
					<commodity :listStyle='listStyle' key-value='cart' :displayStyle='displayStyle' :productData='productData'></commodity>
				</view>
				<view style="height: 120rpx;"></view>
			</view>
		</view>

		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	import tuiButton from "@/components/extend/button/button";
	import commodity from '@/components/commodity/commodity';
	import info from '@/components/shop/info';
	import jxListCell from '@/components/list-cell/list-cell';
	export default {
		components: {
			tuiButton,
			commodity,
			info,
			jxListCell
		},
		data() {
			return {
				img_url: this.$api.img_url,
				webURL: "https://www.thorui.cn/wx/static/images/mall/order/",
				//1-待付款 2-付款成功 3-待收货 4-订单已完成 5-交易关闭
				status: 1,
				height: 64, //header高度
				top: 0, //标题图标距离顶部距离
				detail: '',
				statusText: {
					'0': '30分钟后订单自动关闭',
					'1': '买家已付款',
					'2': '7天后自动收货',
					'3': '交易完成',
					'5': '订单已取消'
				},
				btnText: {
					'0': '去支付',
					'1': '提醒发货',
					'2': '查看物流',
					'3': '去评价',
				},
				loading: false,
				is_show: true,
				textColor:'#bc0100',
				bg_url:'',
				navBg:'',
				navCol:'',
				active_status : '',
				//猜你喜欢数据
				listStyle: 2, //1为大图 2为一行一个 3为一行两个 4一行三个 5为左右滑动
				displayStyle: 1, //1为常规 2为边框 3为居中显示 4为边框居中
				productData: [],
				title_text:'',
				params:{},
				messageShow:true,//地址，订单号显示
				codeDetail:'',
				poup:'',
				timer:'',
				showCode:false,
				begin:false,
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
				sign: ''
			}
		},
		onLoad: function(options) {
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.navBg = this.globalSet('navBg');
			this.navCol = this.globalSet('navCol');
			
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			
			// 初始化数据
			if (options.orderId) {
				this.getDetail(options.orderId,true);
				this.getCode(options.orderId)
			}
			if(options.active_status){
				this.active_status = options.active_status;
			}
			this.getRecommend();
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
				success: (res) => {
					this.width = obj.left || res.windowWidth;
					this.height = obj.top ? (obj.top + obj.height + 8) : (res.statusBarHeight + 44);
					this.top = obj.top ? (obj.top + (obj.height - 32) / 2) : (res.statusBarHeight + 6);
				}
			})
		},
		computed: {
			undone() {
				return (order_state) => {
					if (order_state == 5 || order_state == 7 || order_state == 8) {
						return false;
					} else {
						return true;
					}
				}
			},
			showRefund() {
				return (value) => {
					let _notShow = [0, 4, 5, 8];
					let temp;
					value == 0 ? temp = true : temp = _notShow.find(v => v == value);
					return temp ? true : false;
				}
			}
		},
		methods: {
			getCode(id){
				let that=this
				that.$http.request({
					url: that.$api.moreShop.getOrdercode,
					data: {
						id:id,
						// #ifdef H5
						route: '/h5/#/mch/newmoreShop/newmoreShop',
						// #endif
						// #ifdef MP-WEIXIN || APP-PLUS
						route:'mch/newmoreShop/newmoreShop',
						// #endif
					}
				}).then((res) => {
					if (res.code == 0) {
						that.codeDetail=res.data
						that.timer=setInterval(() => {
							that.getResult(that.codeDetail.id)
						},6000);
					}
				})
			},
			getResult(id) {
				if(!this.begin)return false
				if(	this.poup=="已核销")return false
				this.$http.request({
					url: this.$api.default.getOrdercodestatus,
					method: 'POST',
					data: {
						id:id,
					},
				}).then(res => {
					if (res.code == 0) {
						let reult = res.data
						if (reult.clerk_status && reult.clerk_status == 1) {
							this.poup="已核销"
							this.showCode=true
						}else{
							this.poup="待核销"
							this.showCode=false
						}
					}
				})
			},
			offlineTo(id){ //跳转到爆破核销页面
				// uni.navigateTo({
				// 	url:'../verification/verification?id='+id
				// })
				uni.navigateTo({
					url:'../verification/canceldetail/canceldetail?id='+id
				})
			},
			toPage(orderId,oddNum){
				uni.navigateTo({
					url: `./logistics/index?orderId=${orderId}&odd=${oddNum}`
				})
			},
			getRecommend() { //获取推荐商品
				this.loading = true;
				this.$http.request({
					url: this.$api.goods.recommend,
					data: {
						page: 1,
						type:'order_pay'
					}
				}).then((res) => {
					this.loading = false;
					if (res.code == 0) {
						this.productData = res.data.list;
					}
				})
			},
			back(){
				this.navBack();
			},
			toGoodsDateil(id) {
				//判断是否为拼团订单
				if(this.active_status){
					uni.navigateTo({
						url: `/mch/group-buy/good?proId=${id}`
					})
				}else{
					uni.navigateTo({
						url: `../goods/detail?proId=${id}`
					})
				}
				
			},
			remindSendById(id) {
				this.$http.request({
					url: this.$api.order.remindSend,
					method: 'POST',
					data: {
						order_id: id
					},
					showLoading: true
				}).then(res => {
					this.$http.toast(res.msg);
				})
			},
			deleteOrderById(id) {
				this.$http.request({
					url: this.$api.order.deleteOrder,
					method: 'POST',
					data: {
						order_id: id
					},
					showLoading: true
				}).then(res => {
					this.$http.toast(res.msg);
					if (res.code === 0) {
						setTimeout(() => {
							uni.redirectTo({
								url: `./list`
							})
						}, 1000)
					}
				})
			},
			goComment(id) {
				uni.navigateTo({
					url: `./comment/edit?id=${id}`
				})
			},
			goRefund(id) {
				uni.navigateTo({
					url: `./refund/type?id=${id}`
				})
			},
			confirm(id) {
				this.$http.request({
					url: this.$api.order.confirm,
					method: 'POST',
					data: {
						order_id: id
					}
				}).then(res => {
					this.$http.toast(res.msg);
					if (res.code === 0) {
						this.getDetail(id, false);
					}
				})
			},
			cancel(id) {
				this.$http.request({
					url: this.$api.order.cancel,
					method: 'POST',
					data: {
						order_id: id
					}
				}).then(res => {
					this.$http.toast(res.msg);
					if (res.code === 0) {
						this.getDetail(id, false);
					}
				})
			},
			pay(id) {
				uni.navigateTo({
					url: `/pages/order/pay?orderId=${id}`
				})
			},
			getDetail(id, bool) {
				this.loading = bool;
				this.$http.request({
					url: this.$api.order.detail,
					method: 'POST',
					data: {
						id
					},
				}).then(res => {
					this.loading = false
					if (res.code === 0) {
						if(res.data.is_need_address==0){
							this.messageShow=false
						}
						this.detail = res.data.detail;
						this.params=res.data
						this.is_show = this.detail.order_goods_list.some(v => v.diy_refund_status == 0);
						if(this.detail.order_type=='offline_normal'){
							this.begin=true
						}
						if(this.detail.order_type=='offline_baopin'||this.detail.order_type=='offline_normal'){
							this.title_text = '核销订单';
						}else{
							switch(this.detail.status){
								case 0:
									this.title_text = '待付款';
									break;
								case 1:
									this.title_text = '待发货';
									break;
								case 2:
									this.title_text = '待收货';
									break;
								case 3:
									this.title_text = '待评价';
									break;
							}
						}
						for(let i=0;i<this.detail.order_goods_list.length;i++){
							this.detail.order_goods_list[i].shopExpress=false
							if(this.detail.order_goods_list[i].is_on_site_consumption==1&&this.detail.is_pay==1){
								this.detail.order_goods_list[i].shopExpress=true
							}
						}
					}
				})
			},
			copyText(text) {

				let _self = this;
				// #ifdef H5
				return new Promise((resolve, reject) => {
					let copy = document.createElement("input"); // 创建一个input框获取需要复制的文本内容
					copy.value = text;
					let appDiv = document.getElementsByClassName('container')[0];
					appDiv.appendChild(copy);
					copy.select();
					document.execCommand("Copy");
					_self.$http.toast("复制成功")
					copy.remove()
					resolve(true);
				})
				// #endif

				// #ifndef H5
				uni.setClipboardData({
					data: text,
					success: function() {
						_self.$http.toast("复制成功")
					}
				});
				// #endif

				// uni.getClipboardData({
				// 	success: function(res) {
				// 		console.log(res.data);
				// 	}
				// });
			},
			getStatusText: function(status) {
				return ["等待买家付款", "买家已付款", "7天后自动收货", "交易完成", ""][status - 1]
			},
			getReason: function(status) {
				return ["剩余时间", "等待卖家发货", "还剩X天XX小时自动确认", "", "超时未付款，订单自动取消"][status - 1]
			},
			href(id){ //进入核销页面
				uni.navigateTo({
					url:'./verification/verification?id='+id
				})
			},
			extended(id){ //延长收货
				this.$http.request({
					url: this.$api.order.extendedOrider,
					method: 'POST',
					data: {
						id: id
					}
				}).then(res => {
					if (res.code === 0) {
						this.$http.toast("延长收货时间成功");
						this.getDetail(id, false);
					}else{
						this.$http.toast(res.msg);
					}
				})
			}
		},
		onUnload() {
			if(this.timer!=null) {  
			    clearInterval(this.timer);  
			    this.timer = null;  
			}  
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		// padding-bottom: 118rpx;
	}
	.personalCenter-item{width: 100%;overflow: hidden;margin: 20upx 0;border-top: 1rpx solid #f3f3f3;}
	.jx-cell-header {
		width: 100%;
		height: 100rpx;
		padding: 0 40rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-bottom: 1rpx solid #f3f3f3;
	}
	.jx-cell-title {
		font-size: 11pt;
		line-height: 30rpx;
		font-weight: 400;
		color: #333;
		font-weight: 600;
		letter-spacing: 1px;
	}
	.jx-cell-sub {
		font-size: 9pt;
		font-weight: 400;
		color: #999;
		padding-right: 10rpx;
	}
	.jx-mybg-box {
		width: 100%;
		position: relative;

		.jx-my-bg {
			position: absolute;
			width: 100%;
			height: 340rpx;
			display: block;
		}

		.jx-room {
			position: relative;
			top: 80rpx;

			.jx-order-status {
				padding: 0 30rpx;
				display: flex;
				color: #FFFFFF;
				margin-bottom: 30rpx;
				align-items: center;

				.icon {
					margin-left: 20rpx;
					font-size: 100rpx;
					line-height: 120rpx;
				}

				.text-container {
					margin-left: 20rpx;
					flex: 1;

					.text {
						font-size: 13pt;
						line-height: 60rpx;
					}

					.msg {
						font-size: 9pt;
						margin-top: 10rpx;
					}
				}
			}

			.jx-order-user {
				background-color: #ffffff;
				margin: 0 30rpx;

				.view {
					padding: 26rpx 30rpx;
					border-bottom: 1rpx solid #F2F2F2;

					&:last-child {
						border-bottom: 0;
					}
				}
			}

		}
	}

	.jx-icon-img {
		width: 72rpx;
		line-height: 72rpx;
		font-size: 72rpx;
		flex-shrink: 0;
	}

	.jx-flex-box {
		width: 100%;
		display: flex;
		align-items: center;
	}

	.jx-radius {
		border-radius: 16rpx;
	}

	.jx-border-top {
		border-top: 1rpx solid #EDEDED;
		padding-top: 30rpx;
	}

	.jx-logistics {
		display: flex;
		flex-direction: column;
		justify-content: center;
		padding: 0 24rpx 0 20rpx;
		box-sizing: border-box;

		.jx-logistics-text {
			font-size: 11pt;
			line-height: 32rpx;
		}

		.jx-logistics-time {
			font-size: 9pt;
			line-height: 24rpx;
			padding-top: 16rpx;
			color: #666;
		}
	}

	.jx-addr {
		display: flex;
		flex-direction: column;
		justify-content: center;
		padding-left: 20rpx;
		box-sizing: border-box;

		.jx-addr-userinfo {
			font-size: 11pt;
			line-height: 30rpx;
			font-weight: bold;
		}

		.jx-addr-text {
			font-size: 9pt;
			line-height: 30rpx;
			padding-top: 20rpx;
		}

		.jx-addr-tel {
			padding-left: 40rpx;
		}
	}

	.jx-order-item {
		margin-top: 20rpx;
		border-radius: 10rpx;
		overflow: hidden;
		padding: 0 30rpx;

		&>uni-view {
			margin-bottom: 20rpx;
		}

		.jx-goods-list {
			background-color: #FFFFFF;
		}

		.jx-price {
			color: #333333;
		}

		.jx-order-info {
			background-color: #FFFFFF;
			padding: 30rpx;

			.jx-info-flex {
				display: flex;
				align-items: center;
			}

			.jx-info-text {
				margin-right: 30rpx;
			}

			.jx-text {
				color: #333333;
				flex: 1;
			}
		}
	}

	.jx-goods-item {
		width: 100%;
		padding: 20rpx 30rpx;
		box-sizing: border-box;
		display: flex;
		justify-content: space-between;

		.jx-goods-img {
			width: 180rpx;
			height: 180rpx;
			display: block;
			flex-shrink: 0;
		}

		.jx-goods-center {
			flex: 1;
			padding: 20rpx;
			box-sizing: border-box;
		}
	}

	.jx-goods-name {
		max-width: 310rpx;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
		font-size: 9pt;
		line-height: 32rpx;
	}

	.jx-goods-attr {
		font-size: 9pt;
		color: #888888;
		line-height: 32rpx;
		padding-top: 36rpx;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
	}

	.jx-price-right {
		text-align: right;
		font-size: 9pt;
		color: #999999;
		line-height: 30rpx;
		padding-top: 20rpx;

		.price {
			margin-bottom: 20rpx;
		}

		.num {
			margin-bottom: 20rpx;
			font-weight: bold;
		}
	}

	.jx-goods-price {
		// width: 100%;
		display: flex;
		align-items: flex-end;
		justify-content: flex-end;
		font-size: 9pt;
	}

	.jx-size-24 {
		font-size: 9pt;
		line-height: 24rpx;
	}

	.jx-price-large {
		font-size: 12pt;
		line-height: 30rpx;
	}

	.jx-goods-info {
		margin-top: 20rpx;
		width: 100%;
		padding: 30rpx;
		box-sizing: border-box;
		background: #fff;
	}

	.jx-price-flex {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.jx-size24 {
		padding-bottom: 30rpx;
		font-size: 9pt;
		line-height: 24rpx;
		color: #888;

		&.last {
			padding-bottom: 0;
		}
	}

	.jx-size32 {
		font-size: 12pt;
		line-height: 32rpx;
		font-weight: 500;
	}

	.jx-primary-color {
		color: #EB0909;
	}

	.jx-tabbar {
		width: 100%;
		height: 98rpx;
		background: #fff;
		position: fixed;
		left: 0;
		bottom: 0;
		display: flex;
		align-items: center;
		justify-content: flex-end;
		font-size: 9pt;
		box-shadow: 0 0 1px rgba(242, 242, 242, 1);
		padding-bottom: env(safe-area-inset-bottom);
		z-index: 999;
	}

	.jx-btn-mr {
		margin-right: 30rpx;
	}
	.btns{
		width: 148rpx;
		height: 56rpx;
		border-radius: 60rpx;
		text-align: center;
		line-height: 56rpx;
		border: 1px solid #8c8c8d;
		color: #8c8c8d;
	}
	/* 猜你喜欢 */
	.guessLike {
		text-align: center;
		font-weight: 700;
		font-size: 12pt;
		color: #000000;
		padding: 50rpx 0;
	}
	
	.er-code{width: 100%;height: 500rpx;box-shadow: 0 0 1px rgba(242, 242, 242, 0.8);background: #fff;}
	.code_logo{width: 400rpx;height: 400rpx;margin: 0 auto;position: relative;}
	.code_logo image{width: 400rpx;height: 400rpx;display: block;}
	.code_logo text{display: block;width: 100%;height: 50rpx;text-align: center;line-height: 50rpx;background: #3F536E;color: #fff;}
	.mask{
		width: 400rpx;
		height: 400rpx;
		position: absolute;
		top: 0;
		left: 0;
		z-index: 999;
		background: rgba(0,0,0,0.6);
	}
	.mask image{
		width: 150rpx;
		height: 150rpx;
		display: block;
		position: absolute;
		left: 0;
		right: 0;
		top: 0;
		bottom: 0;
		margin: auto;
		
	}
</style>
