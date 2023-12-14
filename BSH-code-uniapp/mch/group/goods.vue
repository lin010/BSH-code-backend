<template>
	<view class="container">
		<!--header-->
		<view class="tui-header-box" :style="{height:height+'px',background:'rgba(255,255,255,'+opcity+')'}">
			<view class="tui-header" :style="{paddingTop:top+'px', opacity:opcity}">
				拼团
			</view>
			<view class="tui-header-icon" :style="{marginTop:top+'px'}">
				<view class="tui-icon tui-icon-arrowleft tui-icon-back" :style="{color:opcity>=1?'#000':'#fff',background:'rgba(0, 0, 0,'+iconOpcity+')'}"
				 @tap="back"></view>
				<view class="tui-icon tui-icon-more-fill  tui-icon-ml" :style="{color:opcity>=1?'#000':'#fff',background:'rgba(0, 0, 0,'+iconOpcity+')',fontSize:'20px'}"
				 @tap.stop="openMenu"></view>
				<tui-badge type="red" size="small">5</tui-badge>
			</view>
		</view>
		<!--header-->

		<!--banner-->
		<view class="tui-banner-swiper">
			<swiper :autoplay="true" :interval="5000" :duration="150" :circular="true" :style="{height:scrollH + 'px'}" @change="bannerChange">
				<block v-for="(item,index) in banner" :key="index">
					<swiper-item :data-index="index" @tap.stop="previewImage">
						<image :src="item" class="tui-slide-image" :style="{height:scrollH+'px'}" />
					</swiper-item>
				</block>
			</swiper>
			<tui-tag type="translucent" shape="circleLeft" size="small">{{bannerIndex+1}}/{{banner.length}}</tui-tag>
		</view>
		<!--banner-->

		<view class="tui-pro-detail">
			<view class="tui-product-title tui-border-radius">
				<view class="tui-pro-pricebox tui-padding">
					<view class="tui-pro-price">
						<view>￥<text class="tui-price">49</text>.00</view>
						<tui-tag size="small" :plain="true" type="high-green" shape="circle">新品</tui-tag>
					</view>
					<view class="tui-collection tui-size" @tap="collecting">
						<view class="tui-icon tui-icon-collection" :class="['tui-icon-'+(collected?'like-fill':'like')]" :style="{color:collected?'#ff201f':'#333',fontSize:'20px'}"></view>
						<view class="tui-scale" :class="[collected?'tui-icon-red':'']">收藏</view>
					</view>
				</view>
				<view class="tui-original-price" style="color: #999;">
					价格
					<text class="tui-line-through">￥199.00</text>
				</view>
				<view class="tui-pro-titbox">
					<view class="tui-pro-title">谈判官明星同款耳坠韩国气质简约显脸瘦的耳环女百搭个性长款耳钉 个性水滴耳环【A2】</view>
					<button open-type="share" class="tui-share-btn tui-share-position">
						<tui-tag type="gray" tui-tag-class="tui-tag-share tui-size" shape="circleLeft" size="small">
							<view class="tui-icon tui-icon-partake" style="color:#999;font-size:11pt"></view>
							<!-- <tui-icon name="partake" color="#999" size="15"></tui-icon> -->
							<text class="tui-share-text tui-gray">分享</text>
						</tui-tag>
					</button>
				</view>
				<view class="tui-padding" style="color: #999;">
					<view class="tui-sub-title tui-size">此商品将于2019-06-28,10点结束闪购特卖，时尚美饰联合专场</view>
					<view class="tui-sale-info tui-size">
						<view>快递：0.00</view>
						<view>月销2000</view>
						<view>浙江杭州</view>
					</view>
				</view>
			</view>

			<!-- 拼团 -->
			<view class="group-box">
				<view class="group-title flex flex-y-center">
					<view>{{groupData.nowPeople}}人在拼团，可直接参与</view>
					<view class="group-more" @tap="groupMore">查看更多></view>
				</view>
				<swiper class="group-swiper" autoplay="true" disable-touch='true' circular='true' interval="5000" vertical="true"
				 duration="1000">
					<swiper-item v-for="(item,index) in groupData.list" :key='index'>
						<view @tap="show8(index,indexs)" class="group-item" v-for="(items,indexs) in item.children" :key='indexs'>
							<view class="group-item-border flex flex-y-center flex-x-between">
								<view class="group-item-left flex flex-y-center">
									<image class="group-item-img" :src="items.pic_url" mode=""></image>
									<view class="group-item-name">{{items.userName}}</view>
								</view>
								<view class="group-item-right flex flex-y-center">
									<view class="group-detail">
										<view>还差<text class="nums-people">{{items.people}}人</text>拼成</view>
										<view class="flex">
											<text style="margin-right: 10rpx;">还剩</text>
											<tui-countdown :time="items.countdown" color="#000000" colonColor="#000000" bcolor="transparent"
											 bgcolor="transparent"></tui-countdown>
										</view>
									</view>
									<view class="goSpell">去拼单</view>
								</view>
							</view>
						</view>
					</swiper-item>
				</swiper>
			</view>
			<!-- 模态弹窗 -->
			<com-modal :show="modal8" @cancel="hide8" :custom="true">
				<view class="tui-modal-custom">
					<view class="tui-modal-title flex flex-x-center flex-y-center">
						参与<view class="tui-modal-title-name">{{m_username}}</view>的拼单
					</view>
					<view class="tui-modal-quota flex flex-x-center">
						仅剩{{m_people}}个名额，
						<tui-countdown :time="m_countdown" color="#000000" colonColor="#000000" bcolor="transparent"
						 bgcolor="transparent"></tui-countdown>
						后结束
					</view>
					<view class="tui-tips-img-box flex flex-x-center">
						<view style="position: relative;">
							<view class="fighter">
								<image :src="m_pic_url" class="tui-tips-img"></image>
							</view>
							<view class="fighter-text">拼主</view>
						</view>
						<view>
							<image :src="img_url+'images/chat/fail.png'" class="tui-tips-img"></image>
						</view>
					</view>
					<tui-button type="danger" @click="handleClick8">参与拼单</tui-button>
				</view>
			</com-modal>
			
			<!-- 模态弹窗2 —— 查看更多 -->
			<com-modal :show="modalMore" @cancel="hide8" :custom="true" padding="0rpx 0rpx">
				<view class="tui-modalMore-title">正在拼团</view>
				<view class="tui-modal-custom2-box">
					<scroll-view scroll-y="true" class="tui-modal-custom2">
						<view class="tui-modalMore-item" v-for="(item,index) in moreList" @tap="show8(index)" :key='index'>
							<view class="tui-modalMore-item-box flex flex-x-between flex-y-center">
								<view class="flex flex-y-center">
									<view class="tui-modalMore-img-box">
										<image class="tui-modalMore-img" :src="img_url+'chat/time.jpeg'" mode=""></image>
									</view>
									<view style="text-align: left;">
										<view class="tui-modalMore-name flex flex-y-center">
											<view class="tui-modalMore-name2">{{item.userName}}</view>
											&nbsp;还差{{item.people}}人
										</view>
										<view class="tui-modalMore-time flex">
											<text style="margin-right: 8rpx;">还剩</text>
											<tui-countdown :time="item.countdown" color="#989898" colonColor="#000000" bcolor="transparent"
											 bgcolor="transparent"></tui-countdown>
										</view>
									</view>
								</view>
								<view class="goSpellList">去拼单</view>
							</view>
						</view>
					</scroll-view>
					<!-- 白色渐变遮罩层 -->
					<view class="maskLayer"></view>
				</view>
			</com-modal>

			<view class="tui-basic-info tui-mtop tui-radius-all">
				<view class="tui-list-cell" @tap="showPopup">
					<view class="tui-bold tui-cell-title">已选</view>
					<view class="tui-selected-box">个性水滴耳环【A2】,1个，可选服务</view>
					<tui-icon name="more-fill" :size="20" class="tui-right" color="#666"></tui-icon>
				</view>
				<view class="tui-list-cell" @tap="showPopup">
					<view class="tui-bold tui-cell-title">送至</view>
					<view class="tui-addr-box">
						<view class="tui-addr-item">北京朝阳区三环到四环之间</view>
						<view class="tui-addr-item">今日23:59前完成下单，预计6月28日23:30前发货，7月1日24:00前送达</view>
					</view>
					<tui-icon name="more-fill" :size="20" class="tui-right" color="#666"></tui-icon>
				</view>
				<view class="tui-list-cell tui-last">
					<view class="tui-bold tui-cell-title">运费</view>
					<view class="tui-selected-box">在线支付免运费</view>
				</view>
				<view class="tui-guarantee">
					<view class="tui-guarantee-item">
						<tui-icon name="circle-selected" :size="14" color="#999"></tui-icon>
						<text class="tui-pl">可配送海外</text>
					</view>
					<view class="tui-guarantee-item">
						<tui-icon name="circle-selected" :size="14" color="#999"></tui-icon>
						<text class="tui-pl">店铺发货&售后</text>
					</view>
					<view class="tui-guarantee-item">
						<tui-icon name="circle-selected" :size="14" color="#999"></tui-icon>
						<text class="tui-pl">7天无理由退货</text>
					</view>
					<view class="tui-guarantee-item">
						<tui-icon name="circle-selected" :size="14" color="#999"></tui-icon>
						<text class="tui-pl">闪电退款</text>
					</view>
					<view class="tui-guarantee-item">
						<tui-icon name="circle-selected" :size="14" color="#999"></tui-icon>
						<text class="tui-pl">极速审核</text>
					</view>
				</view>
			</view>

			<view class="tui-cmt-box tui-mtop tui-radius-all">
				<view class="tui-list-cell tui-last tui-between">
					<view class="tui-bold tui-cell-title">评价</view>
					<view @tap="common">
						<text class="tui-cmt-all">查看全部</text>
						<view class="tui-icon tui-icon-more-fill" style="color:#ff201f; font-size: 20px;"></view>
						<!-- <tui-icon name="more-fill" size="20" color="#ff201f"></tui-icon> -->
					</view>
				</view>

				<view class="tui-cmt-content tui-padding">
					<view class="tui-cmt-user">
						<image :src="img_url+'images/news/avatar_2.jpg'" class="tui-acatar"></image>
						<view>z***9</view>
					</view>
					<view class="tui-cmt">物流很快，很适合我的风格❤</view>
					<view class="tui-attr">颜色：叠层钛钢流苏耳环（A74）</view>
				</view>

				<view class="tui-cmt-btn">
					<tui-tag type="black" :plain="true" tui-tag-class="tui-tag-cmt" @tap="common">查看全部评价</tui-tag>
				</view>
			</view>

			<view class="nomore-box">
				<main-nomore text="宝贝详情" :visible="true" bgcolor="#f7f7f7"></main-nomore>
			</view>
			<view class="tui-product-img tui-radius-all">
				<image :src="'https://www.thorui.cn/img/detail/'+(index+1)+'.jpg'" v-for="(img,index) in 20" :key="index" mode="widthFix"></image>
			</view>
			<main-nomore text="已经到最底了" :visible="true" bgcolor="#f7f7f7"></main-nomore>
			<view class="tui-safearea-bottom"></view>
		</view>

		<!--底部操作栏-->
		<view class="tui-operation">
			<view class="tui-operation-left tui-col-5">
				<view class="tui-operation-item" hover-class="opcity" :hover-stay-time="150">
					<tui-icon name="kefu" :size="22" color='#333'></tui-icon>
					<view class="tui-operation-text tui-scale-small">客服</view>
				</view>
				<view class="tui-operation-item" hover-class="opcity" :hover-stay-time="150">
					<tui-icon name="shop" :size="22" color='#333'></tui-icon>
					<view class="tui-operation-text tui-scale-small">店铺</view>
				</view>
				<view class="tui-operation-item" hover-class="opcity" @tap="navTo" :hover-stay-time="150">
					<tui-icon name="cart" :size="22" color='#333'></tui-icon>
					<view class="tui-operation-text tui-scale-small">购物车</view>
					<tui-badge type="danger" size="small">9</tui-badge>
				</view>
			</view>
			<view class="tui-operation-right tui-right-flex tui-col-7 tui-btnbox-4">
				<view class="tui-flex-1">
					<view class="" @tap="showPopup">
						<view class="alone flex flex-col flex-x-center">
							<view>￥39</view>
							单独购买
						</view>
					</view>
				</view>
				<view class="tui-flex-1">
					<view class="multiplayer flex flex-col flex-x-center" @tap="submit(1)">
						<view>￥9.97</view>
						发起拼单
					</view>
				</view>
			</view>
		</view>


		<!--底部操作栏--->

		<!--顶部下拉菜单-->
		<tui-top-dropdown tui-top-dropdown="tui-top-dropdown" bgcolor="rgba(76, 76, 76, 0.95)" :show="menuShow" :height="0"
		 @close="closeMenu">
			<view class="tui-menu-box tui-padding tui-ptop">
				<view class="tui-menu-header" :style="{paddingTop:top+'px'}">
					功能直达
				</view>
				<view class="tui-menu-itembox">
					<block v-for="(item,index) in topMenu" :key="index">
						<view class="tui-menu-item" hover-class="tui-opcity" :hover-stay-time="150" @tap="common">
							<view class="tui-badge-box">
								<tui-icon :name="item.icon" color="#fff" :size="item.size"></tui-icon>
								<tui-badge type="red" tui-badge-class="tui-menu-badge" size="small" v-if="item.badge">{{item.badge}}</tui-badge>
							</view>
							<view class="tui-menu-text">{{item.text}}</view>
						</view>
					</block>
				</view>
				<view class="tui-icon tui-icon-up" style="color: #fff; font-size: 26px;" @tap.stop="closeMenu"></view>
				<!-- <tui-icon name="up" color="#fff" size="26" class="tui-icon-up" @tap.stop="closeMenu"></tui-icon> -->
			</view>

		</tui-top-dropdown>
		<!---顶部下拉菜单-->

		<!--底部选择层-->
		<com-bottom-popup :show="popupShow" @close="hidePopup">
			<view class="tui-popup-box">
				<view class="tui-product-box tui-padding">
					<image src="https://www.thorui.cn/img/product/11.jpg" class="tui-popup-img"></image>
					<view class="tui-popup-price">
						<view class="tui-amount tui-bold">￥49.00</view>
						<view class="tui-number">编号:4373299399393</view>
					</view>
				</view>
				<scroll-view scroll-y class="tui-popup-scroll">
					<view class="tui-scrollview-box">
						<view class="tui-bold tui-attr-title">颜色</view>
						<view class="tui-attr-box">
							<view class="tui-attr-item" @tap="specCol(index)" :class="specCol_current == index?'tui-attr-active':''" v-for="(item,index) in specification.color"
							 :key='index'>
								{{item}}
							</view>
						</view>

						<view class="tui-number-box tui-bold tui-attr-title">
							<view class="tui-attr-title">数量</view>
							<tui-numberbox :max="99" :min="1" :value="value" @change="change"></tui-numberbox>
						</view>
						<view class="tui-bold tui-attr-title">尺寸</view>
						<view class="tui-attr-box">
							<view class="tui-attr-item" :class="sizeCut_current == index?'tui-attr-active':''" v-for="(item,index) in specification.size"
							 @tap="sizeCut(index)">
								{{item}}
							</view>
						</view>

						<view class="tui-bold tui-attr-title">
							保障服务
						</view>
						<view class="tui-attr-box">
							<view class="tui-attr-item tui-attr-active">
								半年掉钻保 ￥4.0
							</view>
						</view>

						<view class="tui-bold tui-attr-title">
							只换不修
						</view>
						<view class="tui-attr-box">
							<view class="tui-attr-item" @tap="insuranceCut(index)" :class="insuranceCut_current == index?'tui-attr-active':''"
							 v-for="(item,index) in specification.insurance" :key='index'>
								{{item}}
							</view>
						</view>
					</view>
				</scroll-view>
				<view class="tui-operation tui-operation-right tui-right-flex tui-popup-btn">
					<!-- <view class="tui-flex-1">
						<tui-button type="red" shape="circle" size="mini" @click="hidePopup(1)">加入购物车</tui-button>
					</view> -->
					<view class="tui-flex-1">
						<tui-button type="red" shape="circle" size="mini" @click="submit">确定</tui-button>
					</view>
				</view>
				<view class="tui-icon tui-icon-close-fill tui-icon-close" style="color: #999;font-size:16pt" @tap="hidePopup"></view>
				<!-- <tui-icon name="close-fill" color="#999" class="tui-icon-close" size="20" @tap="hidePopup"></tui-icon> -->
			</view>
		</com-bottom-popup>
		<!--底部选择层-->

	</view>
</template>

<script>
	import tuiIcon from "@/components/icon/icon"
	import tuiTag from "@/components/tag/tag"
	import tuiBadge from "@/components/badge/badge"
	import tuiButton from "@/components/button/button"
	import tuiTopDropdown from "@/components/top-dropdown/top-dropdown"
	import tuiNumberbox from "@/components/numberbox/numberbox";
	import tuiCountdown from "@/components/countdown/countdown";
	import moment from 'moment';
	const goods = {
					nowPeople: 2,
					list: [
						{
							children:[
								{
									pic_url: '',
									userName: '小桃1号',
									people:1,
									countdown: 1586860125
								},
								{
									pic_url: '',
									userName: '这是一个很长很长的名字',
									people:2,
									countdown: 1586867886
								},
							]
						},
						{
							children:[
								{
									pic_url: '',
									userName: '小明',
									people:2,
									countdown: 1586874537
								},
								{
									pic_url: '',
									userName: '小桃2号',
									people:3,
									countdown: 1586879303
								},
							]
						}
					],
				}
	
	export default {
		components: {
			tuiIcon,
			tuiTag,
			tuiBadge,
			tuiButton,
			tuiTopDropdown,
			tuiNumberbox,
			tuiCountdown
		},
		data() {
			return {
				img_url: this.$api.img_url,
				height: 64, //header高度
				top: 0, //标题图标距离顶部距离
				scrollH: 0, //滚动总高度
				opcity: 0,
				iconOpcity: 0.5,
				banner: [
					"https://www.thorui.cn/img/product/11.jpg",
					"https://www.thorui.cn/img/product/2.png",
					"https://www.thorui.cn/img/product/33.jpg",
					"https://www.thorui.cn/img/product/4.png",
					"https://www.thorui.cn/img/product/55.jpg",
					"https://www.thorui.cn/img/product/6.png",
					"https://www.thorui.cn/img/product/7.jpg",
					"https://www.thorui.cn/img/product/8.jpg"
				],
				specification: {
					'color': ['五角星钻耳线', '米子珍珠耳线', '花朵镶钻耳线', '扇子珍珠流苏耳线', '扇子珍珠流苏耳线耳线'],
					'size': ['5cm', '8cm', '12cm', '16cm', '18cm'],
					'insurance': ['三月意外换￥2.0', '半年意外换￥2.0']
				},
				groupData: {},//拼团数据

				specCol_current: 0,
				sizeCut_current: 0,
				insuranceCut_current: 0,
				bannerIndex: 0,
				topMenu: [{
					icon: "message",
					text: "消息",
					size: 26,
					badge: 3
				}, {
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
					badge: 2
				}, {
					icon: "kefu",
					text: "客服小蜜",
					size: 26,
					badge: 0
				}, {
					icon: "feedback",
					text: "我要反馈",
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
				value: 1,
				collected: false,
				nowTime:0,
				
				modal8: false,
				modalMore: false,
				moreList:[],
				// 模态窗口一数据
				m_username:'',
				m_people:0,
				m_countdown:0,
				m_pic_url:'',
				
				
			}
		},
		onLoad: function(options) {
			this.convertData();
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
		methods: {
			//转换数据
			convertData(){
				//转换传回来的数据
				var nowTime = Math.round(new Date().getTime()/1000);
				this.groupData = goods;
				this.groupData.list.forEach((item,index)=>{
					return item.children.map((items,indexs)=>{
						items.countdown = items.countdown - nowTime;
					})
				})
				
				this.groupData.list.forEach((item)=>{
					if(this.groupData.list.length >=1){
						this.moreList = this.moreList.concat(item.children)
					}
				});
			},
			handleClick8() {
				this.$http.toast("你点击了自定义按钮");
				this.hide8();
				this.popupShow = true;
			},
			show8(index,indexs) {
				if(typeof indexs == 'number'){
					this.m_username = this.groupData.list[index].children[indexs].userName;
					this.m_people = this.groupData.list[index].children[indexs].people;
					this.m_countdown = this.groupData.list[index].children[indexs].countdown;
					this.m_pic_url = this.groupData.list[index].children[indexs].pic_url;
				}else{
					this.m_username = this.moreList[index].userName;
					this.m_people = this.moreList[index].people;
					this.m_countdown = this.moreList[index].countdown;
					this.m_pic_url = this.moreList[index].pic_url;
				}
				this.modal8 = true;
				this.modalMore = false;
			},
			hide8() {
				this.modal8 = false;
				this.modalMore = false;
			},
			shows(){
				uni.showModal({
					title:'1231231',
					content:`<view>1231</view>`,
					showCancel:false
				})
			},
			//拼团查看更多
			groupMore() {
				this.modalMore = true;
			},
			specCol(index) {
				this.specCol_current = index;
			},
			sizeCut(index) {
				this.sizeCut_current = index;
			},
			insuranceCut(index) {
				this.insuranceCut_current = index;
			},
			navTo() {
				uni.navigateTo({
					url: '/pages/cart/index'
				})
			},
			bannerChange: function(e) {
				this.bannerIndex = e.detail.current
			},
			previewImage: function(e) {
				let index = e.currentTarget.dataset.index;
				uni.previewImage({
					current: this.banner[index],
					urls: this.banner
				})
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
			showPopup: function() {
				this.popupShow = true
			},
			hidePopup: function(num) {
				this.popupShow = false;
				if (num == 1) {
					this.$http.toast("购物车加入成功")
				}
			},
			change: function(e) {
				this.value = e.value
			},
			collecting: function() {
				this.collected = !this.collected
			},
			common: function() {
				this.$http.toast("功能开发中~")
			},
			submit(num) {
				if (num == 1) {
					this.popupShow = true;
					return;
				}
				this.popupShow = false
				uni.navigateTo({
					url: '/pages/order/submit'
				})
			},
			coupon() {
				uni.navigateTo({
					url: '../mall-extend/coupon/coupon'
				})
			}
		},
		onPageScroll(e) {
			let scroll = e.scrollTop <= 0 ? 0 : e.scrollTop;
			let opcity = scroll / this.scrollH;
			if (this.opcity >= 1 && opcity >= 1) {
				return;
			}
			this.opcity = opcity;
			this.iconOpcity = 0.5 * (1 - opcity < 0 ? 0 : 1 - opcity)
		}
	}
</script>

<style>
	/* icon 也可以使用组件*/
	@import "../style/icon.css";
	page {
		background: #f7f7f7;
	}

	.container {
		padding-bottom: 110rpx;
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
		right: -4px;
	}

	.tui-icon-ml {
		margin-left: 20rpx;
	}

	.tui-icon {
		border-radius: 16px;
	}


	.tui-icon-back {
		height: 32px !important;
		width: 32px !important;
		display: block !important;
	}

	.tui-header-icon .tui-icon-more-fill {
		height: 20px !important;
		width: 20px !important;
		padding: 6px !important;
		display: block !important;
	}

	.tui-banner-swiper {
		position: relative;
	}

	.tui-banner-swiper .tui-tag-class {
		position: absolute;
		color: #fff;
		bottom: 30rpx;
		right: 0;
	}

	.tui-slide-image {
		width: 100%;
		display: block;
	}

	/*顶部菜单*/

	.tui-menu-box {
		box-sizing: border-box;
	}

	.tui-menu-header {
		font-size: 12pt;
		color: #fff;
		height: 32px;
		display: flex;
		align-items: center;
	}

	.tui-top-dropdown {
		z-index: 9999 !important;
	}

	.tui-menu-itembox {
		color: #fff;
		padding: 40rpx 10rpx 0 10rpx;
		box-sizing: border-box;
		display: flex;
		flex-wrap: wrap;
		font-size: 10pt;
	}

	.tui-menu-item {
		width: 22%;
		height: 160rpx;
		border-radius: 24rpx;
		display: flex;
		align-items: center;
		flex-direction: column;
		justify-content: center;
		background: rgba(0, 0, 0, 0.4);
		margin-right: 4%;
		margin-bottom: 4%;
	}

	.tui-menu-item:nth-of-type(4n) {
		margin-right: 0;
	}

	.tui-badge-box {
		position: relative;
	}

	.tui-badge-box .tui-badge-class {
		position: absolute;
		top: -8px;
		right: -8px;
	}

	.tui-msg-badge {
		top: -10px;
	}

	.tui-icon-up {
		position: relative;
		display: inline-block;
		left: 50%;
		transform: translateX(-50%);
	}

	.tui-menu-text {
		padding-top: 12rpx;
	}

	.tui-opcity .tui-menu-text,
	.tui-opcity .tui-badge-box {
		opacity: 0.5;
		transition: opacity 0.2s ease-in-out;
	}

	/*顶部菜单*/

	/*内容 部分*/

	.tui-padding {
		padding: 0 30rpx;
		box-sizing: border-box;
	}

	/* #ifdef H5 */
	.tui-ptop {
		padding-top: 44px;
	}

	/* #endif */

	.tui-size {
		font-size: 9pt;
		line-height: 24rpx;
	}

	.tui-gray {
		color: #999;
	}

	.tui-icon-red {
		color: #ff201f;
	}

	.tui-border-radius {
		border-bottom-left-radius: 24rpx;
		border-bottom-right-radius: 24rpx;
		overflow: hidden;
	}

	.tui-radius-all {
		border-radius: 24rpx;
		overflow: hidden;
	}

	.tui-mtop {
		margin-top: 26rpx;
	}

	.tui-pro-detail {
		box-sizing: border-box;
		color: #333;
	}

	.tui-product-title {
		background: #fff;
		padding: 30rpx 0;
	}

	.tui-pro-pricebox {
		display: flex;
		align-items: center;
		justify-content: space-between;
		color: #ff201f;
		font-size: 13pt;
		font-weight: bold;
		line-height: 44rpx;
	}

	.tui-pro-price {
		display: flex;
		align-items: center;
	}

	.tui-pro-price .tui-tag-class {
		transform: scale(0.7);
		transform-origin: center center;
		line-height: 24rpx;
		font-weight: normal;
	}

	.tui-price {
		font-size: 22pt;
	}

	.tui-original-price {
		font-size: 10pt;
		line-height: 26rpx;
		padding: 10rpx 30rpx;
		box-sizing: border-box;
	}

	.tui-line-through {
		text-decoration: line-through;
	}

	.tui-collection {
		color: #333;
		display: flex;
		align-items: center;
		flex-direction: column;
		justify-content: center;
		height: 44rpx;
	}

	.tui-scale {
		transform: scale(0.7);
		transform-origin: center center;
		line-height: 24rpx;
		font-weight: normal;
	}

	.tui-icon-collection {
		line-height: 20px !important;
		margin-bottom: 0 !important;

	}

	.tui-pro-titbox {
		font-size: 12pt;
		font-weight: 700;
		position: relative;
		padding: 0 150rpx 0 30rpx;
		box-sizing: border-box;
	}

	.tui-pro-title {
		padding-top: 20rpx;
	}

	.tui-share-btn {
		display: block;
		background: none;
		margin: 0;
		padding: 0;
		border-radius: 0;
	}

	.tui-tag-share {
		display: flex;
		align-items: center;
	}

	.tui-share-position {
		position: absolute;
		right: 0;
		top: 30rpx;
	}

	.tui-share-text {
		padding-left: 8rpx;
	}

	.tui-sub-title {
		padding: 20rpx 0;
	}

	.tui-sale-info {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding-top: 30rpx;
	}

	.tui-discount-box {
		background: #fff;
	}

	.tui-list-cell {
		position: relative;
		display: flex;
		align-items: center;
		font-size: 10pt;
		line-height: 26rpx;
		padding: 36rpx 30rpx;
		box-sizing: border-box;
	}

	.tui-right {
		position: absolute;
		right: 30rpx;
		top: 30rpx;
	}

	.tui-top40 {
		top: 40rpx !important;
	}

	.tui-bold {
		font-weight: bold;
	}

	.tui-list-cell::after {
		content: '';
		position: absolute;
		border-bottom: 1rpx solid #eaeef1;
		-webkit-transform: scaleY(0.5);
		transform: scaleY(0.5);
		bottom: 0;
		right: 0;
		left: 126rpx;
	}

	.tui-last::after {
		border-bottom: 0 !important;
	}

	.tui-tag-coupon-box {
		display: flex;
		align-items: center;
	}

	.tui-tag-coupon-box .tui-tag-class {
		margin-right: 20rpx;
	}


	.tui-cell-title {
		width: 66rpx;
		padding-right: 30rpx;
		flex-shrink: 0;
	}

	.tui-promotion-box {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		padding: 10rpx 0;
		width: 74%;
	}

	.tui-promotion-box .tui-tag-class {
		display: inline-block !important;
		transform: scale(0.8);
		transform-origin: 0 center;
	}

	/* .tui-inline-block {
		display: inline-block !important;
		transform: scale(0.8);
		transform-origin: 0 center;
	} */

	.tui-basic-info {
		background: #fff;
	}

	.tui-addr-box {
		width: 76%;
	}

	.tui-addr-item {
		padding: 10rpx;
		line-height: 34rpx;
	}

	.tui-guarantee {
		background: #fdfdfd;
		display: flex;
		flex-wrap: wrap;
		padding: 20rpx 30rpx 30rpx 30rpx;
		font-size: 9pt;
	}

	.tui-guarantee-item {
		color: #999;
		padding-right: 30rpx;
		padding-top: 10rpx;
	}

	.tui-pl {
		padding-left: 4rpx;
	}

	.tui-cmt-box {
		background: #fff;
	}

	.tui-between {
		justify-content: space-between !important;
	}

	.tui-cmt-all {
		color: #ff201f;
		padding-right: 8rpx;
	}

	.tui-cmt-content {
		font-size: 10pt;
	}

	.tui-cmt-user {
		display: flex;
		align-items: center;
	}

	.tui-acatar {
		width: 60rpx;
		height: 60rpx;
		border-radius: 30rpx;
		display: block;
		margin-right: 16rpx;
	}

	.tui-cmt {
		padding: 14rpx 0;
	}

	.tui-attr {
		font-size: 9pt;
		color: #999;
		padding: 6rpx 0;
	}

	.tui-cmt-btn {
		padding: 50rpx 0 30rpx 0;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.tui-tag-cmt {
		min-width: 130rpx;
		padding: 20rpx 52rpx !important;
		font-size: 10pt !important;
		display: inline-block;
	}

	.nomore-box {
		padding-top: 10rpx;
	}

	.tui-product-img {
		transform: translateZ(0);
	}

	.tui-product-img image {
		width: 100%;
		display: block;
	}

	/*底部操作栏*/

	.tui-col-7 {
		width: 58.33333333%;
	}

	.tui-col-5 {
		width: 41.66666667%;
	}

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

	.tui-safearea-bottom {
		width: 100%;
		height: env(safe-area-inset-bottom);
	}

	.tui-operation::before {
		content: '';
		position: absolute;
		top: 0;
		right: 0;
		left: 0;
		border-top: 1rpx solid #eaeef1;
		-webkit-transform: scaleY(0.5);
		transform: scaleY(0.5);
	}

	.tui-operation-left {
		display: flex;
		align-items: center;
	}

	.tui-operation-item {
		flex: 1;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
		position: relative;
	}

	.tui-operation-text {
		font-size: 9pt;
		color: #333;
	}

	.tui-opacity {
		opacity: 0.5;
	}

	.tui-scale-small {
		transform: scale(0.9);
		transform-origin: center center;
	}

	.tui-operation-right {
		height: 100rpx;
		/* box-sizing: border-box; */
		padding-top: 0;
	}

	.tui-right-flex {
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.tui-btnbox-4 .tui-btn-class {
		width: 90% !important;
		display: block !important;
		font-size: 11pt !important;
	}

	.tui-operation .tui-badge-class {
		position: absolute;
		top: -6rpx;
		transform: translateX(50%);
		/* #ifdef H5 */
		/* transform: translateX(50%) */
			/* #endif  */
	}

	.tui-flex-1 {
		flex: 1;
	}

	/*底部操作栏*/

	/*底部选择弹层*/

	.tui-popup-class {
		border-top-left-radius: 24rpx;
		border-top-right-radius: 24rpx;
		padding-bottom: env(safe-area-inset-bottom);
	}

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

	.tui-popup-btn .tui-btn-class {
		width: 90% !important;
		display: block !important;
		font-size: 11pt !important;
	}

	.tui-icon-close {
		position: absolute;
		top: 30rpx;
		right: 30rpx;
	}

	.tui-product-box {
		display: flex;
		align-items: flex-end;
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
	}

	.tui-amount {
		color: #ff201f;
		font-size: 15pt;
	}

	.tui-number {
		font-size: 9pt;
		line-height: 24rpx;
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
		border-radius: 32rpx;
		margin-right: 20rpx;
		margin-bottom: 20rpx;
		font-size: 10pt;
	}

	.tui-attr-active {
		background: #fcedea !important;
		color: #e41f19;
		font-weight: bold;
		position: relative;
	}

	.tui-attr-active::after {
		content: "";
		position: absolute;
		border: 1rpx solid #e41f19;
		width: 100%;
		height: 100%;
		border-radius: 40rpx;
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
	
	.alone{
		background: #f3a9a6;
		color: #FFFFFF;
		text-align: center;
		height: 100rpx;
		font-size: 10pt;
	}
	.multiplayer{
		background: #e02f25;
		color: #FFFFFF;
		text-align: center;
		height: 100rpx;
		font-size: 11pt;
	}
	/* 拼团 */
	.group-box {
		background: #FFFFFF;
		border-radius: 24rpx;
		margin-top: 26rpx;
		padding-bottom: 20rpx;
	}

	.group-title {
		font-size: 11pt;
		color: #222222;
		border-bottom: 2rpx solid #E0E0E0;
		justify-content: space-between;
		padding: 24rpx 30rpx 14rpx;
	}

	.group-more {
		color: #b0b0b0;
	}

	.group-swiper {
		height: 200rpx;
		padding: 16rpx 0px 0px;
	}

	.group-item {
		padding: 0rpx 30rpx 14rpx;
	}

	.group-item-border {
		border-bottom: 1px solid #ececec;
		padding-bottom: 14rpx;
	}

	.group-item-img {
		width: 75rpx;
		height: 75rpx;
		margin-right: 14rpx;
		border-radius: 50%;
	}

	.group-item-name {
		font-size: 11pt;
		max-width: 200rpx;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.group-detail {
		text-align: right;
		font-size: 11pt;
		color: #5b5b5b;
		margin-right: 20rpx;
		letter-spacing: 2rpx;
	}

	.nums-people {
		color: #f53939;
	}

	.goSpell {
		color: #FFFFFF;
		background: #bc0100;
		padding: 10rpx 26rpx;
		font-size: 11pt;
		border-radius: 8rpx;
	}
	/* 拼团 */
	
	/* 模态弹窗 */
	.tui-modal-custom {
		text-align: center
	}
	.tui-modal-title{
		font-size: 12pt;
		margin-bottom: 12rpx;
	}
	.tui-modal-title-name{
		max-width: 280rpx;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	.tui-modal-quota{
		font-size: 11pt;
	}
	.tui-tips-img-box{
		padding: 30rpx 0;
	}
	.tui-tips-img{
		width: 100rpx;
		height: 100rpx;
		display: block;
	}
	.fighter{
		position: relative;
		margin-right: 20rpx;
		border-radius: 50%;
		border: 2rpx solid #fca93d;
		box-sizing: border-box;
		overflow: hidden;
	}
	.fighter-text{
		background: #fca93d;
		color: #c17702;
		position: absolute;
		font-size: 9pt;
		padding: 0 6rpx 2rpx;
		top: -10rpx;
		left: -8rpx;
		border: 2rpx solid #FFFFFF;
		border-radius: 30rpx;
	}
	/* 模态弹窗 */
	
	/* 模态弹窗——更多*/
	.tui-modal-custom2 {
		text-align: center;
		max-height: 700rpx;
	}
	.tui-modal-custom2-box{
		position: relative;
	}
	.tui-modalMore-title{
		padding: 20rpx 0;
		font-size: 15pt;
		text-align: center;
		border-bottom: 2rpx solid #CCCCCC;
	}
	.tui-modalMore-item{
		padding: 10rpx 26rpx;
	}
	.tui-modalMore-item-box{
		border-bottom: 2rpx solid #E0E0E0;
		padding: 8rpx 0;
	}
	.tui-modalMore-img-box{
		margin-right: 10rpx;
	}
	.tui-modalMore-img{
		width: 80rpx;
		height: 80rpx;
		border-radius: 50%;
		overflow: hidden;
	}
	.tui-modalMore-name{
		font-size: 11pt;
	}
	.tui-modalMore-time{
		font-size: 10pt;
		color: #989898;
		font-weight: 300;
	}
	.goSpellList{
		background: #bc0100;
		color: #FFFFFF;
		border-radius: 10rpx;
		font-size: 11pt;
		padding: 10rpx 28rpx;
	}
	.tui-modalMore-name2{
		max-width: 180rpx;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		margin-right: 10rpx;
	}
	
	.maskLayer{
		width: 100%;
		background: linear-gradient(to top, #ffffff 1%,transparent 100%);
		height: 60rpx;
		position: absolute;
		bottom: 0;
		border-radius: 24rpx;
	}
	/* 模态弹窗——更多*/
</style>
