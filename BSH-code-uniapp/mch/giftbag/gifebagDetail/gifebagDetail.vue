<template>
	<view class="giftbagDetail-app">
		<view class="tui-header-box" :style="{height:height+'px',background:'rgba(255,255,255,'+opcity+')'}">
			<view class="tui-header" :style="{paddingTop:top+'px', opacity:opcity}">
				商品详情
			</view>
			<view class="tui-header-icon" :style="{marginTop:top+'px'}">
				<view class="tui-icon iconfont icon-fanhui" :style="{color:opcity>=1?'#000':'#fff',background:'rgba(0, 0, 0,'+iconOpcity+')'}"
				 @tap.stop="back"></view>
				<view class="tui-icon iconfont icon-gengduo" :style="{color:opcity>=1?'#000':'#fff',background:'rgba(0, 0, 0,'+iconOpcity+')',fontSize:'20px'}"
				 @tap.stop="openMenu"></view>
				
			</view>
		</view>
		<view class="tui-banner-swiper">
			<image :src="detail.cover_pic" mode="widthFix" style="width: 100%;"></image>
			<!-- <swiper :duration="150" :style="{height:scrollH + 'px'}" @change="bannerChange">
				<swiper-item :data-index="0" v-if="goodsData.video_url">
					<video id='swiperVideo' :enable-progress-gesture='false' :src="goodsData.video_url" loop autoplay muted controls style="width: 750rpx;" :style="{height:scrollH+'px'}"></video>
				</swiper-item>
				<block v-for="(item,b_index) in detail.cover_pic" :key="b_index">
					<swiper-item :data-index="b_index+1">
						<image :src="item.cover_pic" mode="aspectFill" class="tui-slide-image" :style="{height:scrollH+'px'}" />
					</swiper-item>
				</block>
			</swiper>
			<jx-tag class="tui-tag-class" type="translucent" shape="circle" size="small">{{bannerIndex+1}}/{{bannerLength}}</jx-tag> -->
		</view>
		<view class="tui-pro-detail">
			<view class="price">
				<text>￥{{detail.price}}</text>
				<text>起</text>
			</view>
			<!-- <view class="type-logo">
				<image :src="img_url+'/love-sort.png'" mode="" ></image>
				<image :src="img_url+'/erqode-share.png'" mode="" ></image>
			</view> -->
		</view>	
		<view class="giftbag-title">
			{{detail.title}}
		</view>
		<view class="giftbagDetail-service">
			<jx-list-cell :arrow="true" padding="0" :lineLeft="false" @click="href(detail.id)">
				<view class="jx-cell-header" style="height: 80rpx;">
					<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">内含{{detail.item_count}}项服务</view>
				</view>
			</jx-list-cell>
		</view>
		<view v-if="detail.group_enable==0" style="width: 100%;height: 300rpx;"></view>
		<view class="giftbagDetail-service" v-if="detail.group_enable==1">
			<jx-list-cell :arrow="true" padding="0" :lineLeft="false" @click='morespellgroup(detail.id)'>
				<view class="jx-cell-header" style="height: 80rpx;">
					<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">{{detail.group_num}}人在拼单，可直接参与</view>
				</view>
				<view class="jx-cell-header" style="height: 80rpx;margin-left: 280rpx;color: #000;">
					<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">更多</view>
				</view>
			</jx-list-cell>
		</view>
		<view class="spellgroup-list" v-if="detail.group_enable==1">
			<view class="spellgroup-item" v-for="(item,index) in group_list" :key='index'>
				<image :src="item.avatar_url" mode="" style="border-radius: 50%;"></image>
				<view class="spellgroup-item-name">
					{{item.nickname}}
				</view>
				<view class="spellgroup-item-peopeliNum">
					<view style="text-align: right;width: 100%;height: 50rpx;line-height: 50rpx;">
						还差
						<text style="color: red;">{{item.still_need_num}}</text>
					人拼成</view>
					<text style="text-align: right;display: block;">剩余{{item.expired_at}}</text>
				</view>
				<button type="default"  class="go-spellgroup" @click="gospellgroup(item.id)">
					去拼团
				</button>
			</view>
		</view>
		<tui-top-dropdown tui-top-dropdown="tui-top-dropdown" bgcolor="rgba(76, 76, 76, 0.95)" :show="menuShow" :height="0"
		 @close="closeMenu">
			<view class="tui-menu-box tui-padding tui-ptop">
				<view class="tui-menu-header" :style="{paddingTop:top+'px'}">
					功能直达
				</view>
				<view class="tui-menu-itembox">
					<block v-for="(item,index) in topMenu" :key="index">
						<view class="tui-menu-item" hover-class="tui-opcity" :hover-stay-time="150" @tap="common(index)">
							<view class="tui-badge-box">
								<tui-icon :name="item.icon" color="#fff" :size="item.size"></tui-icon>
								<tui-badge type="red" tui-badge-class="tui-menu-badge" size="small" v-if="item.badge">{{item.badge}}</tui-badge>
							</view>
							<view class="tui-menu-text">{{item.text}}</view>
						</view>
					</block>
				</view>
				<view class="tui-icon tui-icon-up" style="color: #fff; font-size: 26px;" @tap.stop="closeMenu"></view>
			</view>
		
		</tui-top-dropdown>
		<view class="bottom">
			<view @click="linkTO(1)" style="width: 20%;display: flex;justify-content: space-evenly;flex-wrap: wrap;">
				<image :src="img_url+'/gifh_home_logo.png'" mode="" ></image>
				<text>首页</text>
			</view>
			<view @click="linkTO(2)" style="width: 20%;display: flex;justify-content: space-evenly;flex-wrap: wrap;">
				<image :src="img_url+'/gift_service_logo.png'" mode="" ></image>
				<text>客服</text>
			</view>
			<view class="buytype">
				<view  :class="detail.group_enable==1?'active':'actove'" @click="gobuy(detail.id)">
					<text style="display: block;width: 100%;line-height: 50rpx;text-align: center;">￥{{detail.price}}</text>
					<text style="display: block;width: 100%;line-height: 50rpx;text-align: center;">单独购买</text>
				</view>
				<view style="width: 40%;height: 100rpx;background: #ff330b;float: left;color: #fff;" v-if="detail.group_enable==1" @click="gospellbuy(detail.id)">
					<text style="display: block;width: 100%;line-height: 50rpx;text-align: center;">拼后￥{{detail.group_price}}</text>
					<text style="display: block;width: 100%;line-height: 50rpx;text-align: center;">发起拼单</text>
				</view>
			</view>
		</view>
		<unipopup ref="popup" type="center">
			<scroll-view scroll-y="true"  class="scroll-Ys">
				<view class="spellgroup-more">
					<view class="spellgroup-more-title">
						可参与的拼单
					</view>
					<view class="spellgroup-more-spellgroup-list">
						<view class="spellgroup-more-spellgroup-item" v-for="(item,index) in productList" :key='index'>
							<image :src="item.avatar_url" mode=""></image>
							<view class="spellgroup-more-spellgroup-item-name">
								{{item.nickname}}
							</view>
							<view class="spellgroup-more-spellgroup-item-peopeliNum">
								<view style="text-align: right;width: 100%;height: 50rpx;line-height: 50rpx;">
									还差
									<text style="color: red;">{{item.still_need_num}}</text>
									人拼成
								</view>
								<text style="text-align: right;display: block;">剩余{{item.expired_at}}</text>
							</view>
							<button type="default"  class="go-spellgroup"  @click="join(item.id)">
								去拼团
							</button>
						</view>
					</view>
				</view>
			</scroll-view>
		</unipopup>
		<unipopup ref="popupgo" type="center">
			<view class="gospellgroup-detail">
				<view class="gospellgroup-detail-title">
					参与{{group_info.nickname}}的拼团
				</view>
				<view class="gospellgroup-detail-poornum">
					仅剩/还剩<text style="color: red;">{{group_info.still_need_num}}</text>个名额,{{group_info.expired_at}}后结束
				</view>
				<view class="gospellgroup-detail-people">
					<view class="gospellgroup-detail-people-item" v-for="(item,index) in join_list" :key='index'>
						<image :src="item.avatar_url" mode=""></image>
						<text v-if="item.is_owner==1">拼主</text>
					</view>
				</view>
				<view class="participate" @click="join(group_info.id)">
					参与拼单
				</view>
			</view>
		</unipopup>
		<com-modal :button="button" :show="modal" @click="handleClick" @cancel="hide" title="提示" content="您没有设置支付密码,是否需要跳转设置？"></com-modal>
		<com-payment-password ref="paymentPassword" :show="cashFlag" :value="paymentPwd" :digits="6"
		@submit="checkPwd" @cancel="togglePayment" @checkSafePwd="safePasswork"></com-payment-password>
	</view>
</template>

<script>
	import tuiIcon from "@/components/icon/icon"
	import tuiTopDropdown from "@/components/top-dropdown/top-dropdown"
	import jxTag from "@/components/tag/tag"
	import jxListCell from '@/components/list-cell/list-cell';
	import unipopup from '@/components/uni-popup/uni-popup';
	export default {
		components: {
			tuiIcon,
			jxTag,
			tuiTopDropdown,
			jxListCell,
			unipopup
		},
		data() {
			return {
				img_url: this.$api.img_url,
				opcity:0,
				top: 50, //标题图标距离顶部距离
				height: 64, //header高度
				iconOpcity: 0.5,
				menuShow: false,
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
					},
					
				],
				// bannerIndex: 0,
				scrollH: 0, //滚动总高度
				// bannerLength: 0, //轮播图有多少张
				serviceLink:'',//客服
				detail:'',
				group_list:[],
				modal:false,//模态弹窗
				button:[],
				cashFlag: false,
				paymentPwd: '',
				is_transaction_password:true,//是否设置过支付密码
				group_id:'',
				productList:[],
				group_info:'',//拼团信息
				join_list:'',//参与人员的信息
			}
		},
		onLoad(options) {
			
			let obj = {};
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
			this.getService()
			this.button = this.globalSet('btnCol','确定');
			if(options&&options.pack_id){
				this.packageDetail(options.pack_id)
			}
		},
		onShow() {
			this.initSetting()
		},
		methods:{
			linkTO(index){
				if(index==1){
					uni.navigateTo({
						url:'../../../pages/index/index'
					})
				}
				if(index==2){
					location.href = this.serviceLink
				}
			},
			getService() { //获取客服链接
				this.$http
					.request({
						url: this.$api.moreShop.getservice,
						method: 'POST',
						showLoading: true
					})
					.then(res => {
						if (res.code == 0) {
							this.serviceLink = res.data
						}
					});
			},
			back() {
				this.navBack();
			},
			openMenu: function() {
				this.menuShow = true
			},
			closeMenu: function() {
				this.menuShow = false
			},
			common: function(index) {
				switch (index) {
					case 0:
						uni.navigateTo({
							url: '../../../pages/index/index'
						})
						break;
					case 1:
						uni.navigateTo({
							url: '../../../pages/user/index'
						})
						break;
					case 2:
						uni.navigateTo({
							url: '../../../pages/cart/index'
						})
						break;
				}
			},
			// bannerChange: function(e) {
			// 	if(this.goodsData.video_url){
			// 		var ctx = uni.createVideoContext('swiperVideo', this);
			// 		if(e.detail.current == 0){
			// 			ctx.play();
			// 		}else if(e.detail.current == 1){
			// 			ctx.pause();
			// 		}
			// 	}
			// 	this.bannerIndex = e.detail.current
			// },
			href(id){ //服务内容
				uni.navigateTo({
					url:'../giftbagCapacity/giftbagCapacity?pack_id='+id
				})
			},
			gobuy(id){//单独购买
				uni.navigateTo({
					url:'../giftsubmit/giftsubmit?pack_id='+id
				})
			},
			gospellbuy(id){//拼单购买
				this.$http.request({
					url: this.$api.package.createspell,
					method: 'POST',
					data: {
						pack_id:id
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.group_id=res.data.group_id
						if(!this.is_transaction_password){
							this.modal = true;
							return;
						}
						this.cashFlag=true
						this.$refs.paymentPassword.modalFun('show');	
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			pay(group_id,trade_pwd){ //金豆拼单支付
				this.$http.request({
					url: this.$api.package.payspellMoney,
					method: 'POST',
					data: {
						group_id:group_id,
						trade_pwd:trade_pwd,
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.$refs.paymentPassword.modalFun('hide');
						this.$refs.popupgo.close();
						this.$refs.popup.close();
						this.$http.toast('拼单成功');
						if(res.data.finished==1){
							setTimeout(()=>{
								uni.navigateTo({
									url:'../orderList/orderList'
								})
							},2000)
						}else{
							setTimeout(()=>{
								uni.navigateTo({
									url:'../spelldetail/spelldetail?group_id='+group_id+"&pack_id="+this.detail.id
								})
							},2000)
						}
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			hide() {
				this.modal = false;
			},
			handleClick(e) {
				let index = e.index;
				if (index === 0) {
					this.modal = false;
				} else {
					uni.navigateTo({
						url:'../../../pages/user/payment/password'
					})
				}
				this.hide();
			},
			safePasswork(e){ //密码校验
				this.paymentPwd = e.passwork;
				this.pay(this.group_id,this.paymentPwd)
			},
			checkPwd(e) { //输入密码
				console.log(e)
			},
			morespellgroup(id){//更多拼团
				 this.$refs.popup.open()
				 this.$http.request({
				 	url: this.$api.package.getgroupList,
				 	method: 'POST',
				 	data: {
						page:1,
				 		pack_id:id
				 	},
				 	showLoading: true
				 }).then(res => {
				 	if (res.code == 0) {
				 		this.productList=res.data.list
						for(let i=0;i<this.productList.length;i++){
							var timestamp =parseInt( new Date().getTime()/1000)
							let time=this.productList[i].expired_at-timestamp
							let days=''
							let hours=''
							let minutes=''
							let seconds=''
							let timers=setInterval(()=>{
								time--
								if(time<=0){
									this.productList[i].expired_at='00'+'天'+'00'+"："+'00'+'：'+'00'
									clearInterval(timers)
									return 
								}
								days = parseInt(time / 60 / 60 / 24 , 10); //计算剩余的天数
								hours = parseInt(time / 60 / 60 % 24 , 10)<10?"0"+parseInt(time / 60 / 60 % 24 , 10):parseInt(time  / 60 / 60 % 24 , 10); //计算剩余的小时 
								minutes = parseInt(time / 60 % 60, 10)<10?"0"+parseInt(time / 60 % 60, 10):parseInt(time / 60 % 60, 10);//计算剩余的分钟 
								seconds = parseInt(time % 60, 10)<10?"0"+parseInt(time  % 60, 10):parseInt(time  % 60, 10);//计算剩余的秒数 								
								this.productList[i].expired_at=days+'天'+hours+"："+minutes+'：'+seconds
							},1000)
						}	
				 	} else {
				 		this.$http.toast(res.msg);
				 	}
				 });
			},
			gospellgroup(group_id){//获取平团详情点击参与拼团
				this.$refs.popupgo.open()
				this.$http.request({
					url: this.$api.package.espellDetail,
					method: 'POST',
					data: {
						group_id:group_id
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.group_info=res.data.group_info
						this.join_list=res.data.join_list
						var timestamp =parseInt( new Date().getTime()/1000)
						let time=this.group_info.expired_at-timestamp
						let days=''
						let hours=''
						let minutes=''
						let seconds=''
						let timers=setInterval(()=>{
							time--
							if(time<=0){
								this.group_info.expired_at='00'+'天'+'00'+"："+'00'+'：'+'00'
								clearInterval(timers)
								return 
							}
							days = parseInt(time / 60 / 60 / 24 , 10); //计算剩余的天数
							hours = parseInt(time / 60 / 60 % 24 , 10)<10?"0"+parseInt(time / 60 / 60 % 24 , 10):parseInt(time  / 60 / 60 % 24 , 10); //计算剩余的小时 
							minutes = parseInt(time / 60 % 60, 10)<10?"0"+parseInt(time / 60 % 60, 10):parseInt(time / 60 % 60, 10);//计算剩余的分钟 
							seconds = parseInt(time % 60, 10)<10?"0"+parseInt(time  % 60, 10):parseInt(time  % 60, 10);//计算剩余的秒数 								
							this.group_info.expired_at=days+'天'+hours+"："+minutes+'：'+seconds
						},1000)
						
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			packageDetail(pack_id){
				this.$http.request({
					url: this.$api.package.packageDetail,
					method: 'POST',
					data: {
						pack_id:pack_id
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.detail=res.data.detail
						this.group_list=res.data.group_list
						for(let i=0;i<this.group_list.length;i++){
							var timestamp =parseInt( new Date().getTime()/1000)
							let time=this.group_list[i].expired_at-timestamp
							let days=''
							let hours=''
							let minutes=''
							let seconds=''
							let timers=setInterval(()=>{
								time--							
								if(time<=0){
									this.group_list[i].expired_at='00'+'天'+'00'+"："+'00'+'：'+'00'
									clearInterval(timers)									
									return
								}
								days = parseInt(time / 60 / 60 / 24 , 10); //计算剩余的天数
								hours = parseInt(time / 60 / 60 % 24 , 10)<10?"0"+parseInt(time / 60 / 60 % 24 , 10):parseInt(time  / 60 / 60 % 24 , 10); //计算剩余的小时 
								minutes = parseInt(time / 60 % 60, 10)<10?"0"+parseInt(time / 60 % 60, 10):parseInt(time / 60 % 60, 10);//计算剩余的分钟 
								seconds = parseInt(time % 60, 10)<10?"0"+parseInt(time  % 60, 10):parseInt(time  % 60, 10);//计算剩余的秒数 								
								this.group_list[i].expired_at=days+'天'+hours+"："+minutes+'：'+seconds
							},1000)
						}
					} else {
						this.$http.toast(res.msg);
					}
				});
			},			
			initSetting() { //获取是否设置支付密码
				this.$http
					.request({
						url: this.$api.plugin.extensions.cash.setting
					})
					.then(res => {
						if (res.code == 0) {
							this.is_transaction_password = res.data.user_info.is_transaction_password;
						} else {
							this.$http.toast(res.msg);
						}
					});
			},
			join(id){//加入拼团
				this.$http
					.request({
						url: this.$api.package.joinespell,
						method: 'POST',
						data:{
							group_id:id,
						},
						showLoading: true
					})
					.then(res => {
						if (res.code == 0) {
							this.group_id=id
							if(!this.is_transaction_password){
								this.modal = true;
								return;
							}
							this.cashFlag=true
							this.$refs.paymentPassword.modalFun('show');
						}else{
							this.$http.toast(res.msg);
						}
					});
			}
		},
	}
</script>

<style lang="less" scoped>
	.giftbagDetail-app{width: 100%;overflow: hidden;}
	.tui-header-box {width: 100%;position: fixed;left: 0;top: 0;z-index: 9998;}
	.tui-header {width: 100%;font-size: 15pt;line-height: 18px;font-weight: 500;height: 32px;display: flex;align-items: center;justify-content: center;}
	.tui-header-icon {position: fixed;top: 0;left: 10px;display: flex;align-items: flex-start;justify-content: space-between;height: 32px;transform: translateZ(0);z-index: 99999;}
	.tui-header-icon .tui-badge {background: #e41f19 !important;position: absolute;right: -5px;}
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
	.tui-menu-box {box-sizing: border-box;margin-top: 50px;}
	.tui-menu-header {font-size: 12pt;color: #fff;height: 32px;display: flex;align-items: center;padding: 0 10px;}
	.tui-top-dropdown {z-index: 9999 !important;}
	.tui-menu-itembox {color: #fff;padding: 40rpx 10rpx 0 10rpx;box-sizing: border-box;display: flex;flex-wrap: wrap;font-size: 10pt;}
	.tui-menu-item {width: 22%;height: 160rpx;border-radius: 24rpx;display: flex;align-items: center;flex-direction: column;justify-content: center;background: rgba(0, 0, 0, 0.4);margin-right: 4%;margin-bottom: 4%;}
	.tui-menu-item:nth-of-type(4n) {margin-right: 0;}
	.tui-badge-box {position: relative;}
	.tui-badge-box .tui-badge-class {position: absolute;top: -8px;right: -8px;}
	.tui-icon-up {position: relative;display: inline-block;left: 50%;transform: translateX(-50%);}
	.tui-menu-text {padding-top: 12rpx;}
	.tui-opcity .tui-menu-text,
	.tui-opcity .tui-badge-box {opacity: 0.5;transition: opacity 0.2s ease-in-out;}
	.tui-banner-swiper {position: relative;}
	.tui-banner-swiper .tui-tag-class {position: absolute;color: #fff;bottom: 40rpx;right: 40rpx;}
	.tui-slide-image {width: 100%;display: block;}
	.tui-pro-detail{width: 100%;display: flex;justify-content: space-between;margin: 0 auto;background: #fff;padding:0 2.5%;}
	.price{line-height: 130rpx;}
	.price text{display: inline-block;}
	.price text:nth-of-type(1){font-size: 40rpx;font-weight: bold;color: #FF5A0E;}
	.price text:nth-of-type(2){font-size: 26rpx;color: #FF5A0E;font-weight: bold;}
	.type-logo image{width: 100rpx;height: 100rpx;margin: 15rpx 15rpx 10rpx 15rpx;}
	.giftbag-title{width: 100%;background: #fff;padding: 0 2.5%;height:90rpx ;color: #000;font-size: 30rpx;font-weight: bold;
	display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;overflow: hidden;}
	.giftbagDetail-service{width: 100%;overflow: hidden;background: #fff;margin-top: 10rpx;}
	.bottom{width: 100%;height: 100rpx;background: #fff;position: fixed;bottom: 0;left: 0;
	display: flex;justify-content: space-evenly;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.4)}
	.bottom view:nth-of-type(3){width: 60%;height: 100%;}
	.bottom view image{width: 32rpx;height: 32rpx;display: block;margin: 10rpx auto;}
	.bottom view text{display: block;width: 100%;text-align: center;font-size: 25rpx;}
	.spellgroup-list{width: 100%;overflow: hidden;background: #fff;margin-bottom: 300rpx;padding: 0 10rpx;}
	.spellgroup-item{width: 100%;overflow: hidden;border-top: 2rpx solid #f2f2f2;margin: 5rpx 0;}
	.spellgroup-item image{width: 60rpx;height: 60rpx;float: left;margin-top: 20rpx;margin-right: 20rpx;}
	.spellgroup-item-name{float: left;height: 100rpx;line-height: 100rpx;font-size: 25rpx;width: 200rpx;
	overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
	.spellgroup-item-peopeliNum{height: 100rpx;float: left;font-size: 25rpx;width: 250rpx;margin-left: 60rpx;}
	.spellgroup-item-peopeliNum text:nth-of-type(2){display: block;width: 100%;height: 50rpx;text-align: right;line-height: 50rpx;}
	.go-spellgroup{height: 60rpx;float: right;background: rgb(255, 51, 11);width: 130rpx;color: #fff;text-align: center;line-height: 60rpx;font-size: 25rpx;margin-top: 20rpx;}
	.spellgroup-more{width: 700rpx;height: 500rpx;background: #fff;border-radius: 30rpx;}
	.spellgroup-more-title{width: 100%;height: 80rpx;text-align: center;line-height: 80rpx;font-size: 30rpx;color: #000;border-bottom: 1rpx solid rgb(181,181,181);}
	.spellgroup-more-spellgroup-list{width: 100%;overflow: hidden;padding: 0 10rpx;margin-bottom: 20rpx;}
	.spellgroup-more-spellgroup-item{width: 100%;overflow: hidden;border-top: 2rpx solid #f2f2f2;margin: 5rpx 0;}
	.spellgroup-more-spellgroup-item image{width: 60rpx;height: 60rpx;float: left;margin-top: 20rpx;margin-right: 20rpx;}
	.spellgroup-more-spellgroup-item-name{float: left;height: 100rpx;line-height: 100rpx;font-size: 25rpx;width: 150rpx;
	overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
	.spellgroup-more-spellgroup-item-peopeliNum{height: 100rpx;float: left;font-size: 25rpx;width: 250rpx;margin-left: 30rpx;}
	.spellgroup-more-spellgroup-item-peopeliNum text:nth-of-type(2){display: block;width: 100%;height: 50rpx;text-align: center;line-height: 50rpx;}
	.spellgroup-more-go-spellgroup{height: 60rpx;float: right;background: rgb(255, 51, 11);width: 130rpx;color: #fff;text-align: center;line-height: 60rpx;font-size: 28rpx;margin-top: 20rpx;}
	.gospellgroup-detail{width: 600rpx;height: 450rpx;background: #fff;border-radius: 30rpx;padding-top: 20rpx;}
	.gospellgroup-detail-title{width: 100%;height: 80rpx;text-align: center;line-height: 80rpx;font-size: 28rpx;color: #000;}
	.gospellgroup-detail-poornum{width: 100%;height: 70rpx;text-align: center;line-height: 70rpx;font-size: 25rpx;color: #000;}
	.gospellgroup-detail-people{width: 60%;height: 80rpx;justify-content: space-evenly;display: flex;margin: 20rpx auto;}
	.gospellgroup-detail-people-item{width: 80rpx;height: 80rpx;position: relative;}
	.gospellgroup-detail-people-item image{width: 80rpx;height: 80rpx;display: inline-block;}
	.gospellgroup-detail-people-item text{display: block;position: absolute;top: -10rpx;right: -10rpx;font-size: 25rpx;background: rgb(255,207,121);text-align: center;width: 60rpx;height: 32rpx;line-height: 30rpx;color: #fff;border-radius: 8rpx;}
	.participate{width: 360rpx;height: 60rpx;line-height: 60rpx;text-align: center;background: #ff330b;margin: 50rpx auto;color: #fff;border-radius: 30rpx;font-size: 28rpx;}
	.scroll-Ys {width: 700rpx;overflow: hidden;margin: 0 auto;background: #fff;border-radius: 20rpx;}
	.active{width: 40%;height: 100rpx;background:rgb(255,113,4);float: left;color: #fff;margin-left: 90rpx;}
	.actove{width: 40%;height: 100rpx;background:rgb(255,113,4);float: left;color: #fff;margin-left: 270rpx;}
</style>
