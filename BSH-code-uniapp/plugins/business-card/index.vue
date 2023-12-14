<template>
	<view class="index-root">
		<main-tabbar :tabBar="tabbar"></main-tabbar>
		<block v-if="detail_data">
			<view class="box">
				<image class="avatar-img" :src="detail_data.head_img || img_url+'images/business/business-default.jpg'" mode="aspectFill"></image>
				<!-- <image class="avatar-img" src="../../static/images/business/business-default.jpg" mode=""></image> -->
				
				<view class="main">
					<view class="btn-list flex flex-col">
						<view class="btn-item" @tap="navTo('/plugins/business-card/business-list')">
							<view class="iconfont icon-mingpian1 btn-item-icon"></view>
						</view>
						<block v-if="detail_data.role_id == 1 || detail_data.role_id == 2">
							<view class="btn-item" @tap="navTo('/plugins/business-card/analysis/index')">
								<view class="iconfont icon-quanxian btn-item-icon btn-item-icon2"></view>
							</view>
						</block>
						<view class="btn-item" @tap="navTo('/plugins/business-card/log/log')">
							<view class="iconfont icon-gudong1 btn-item-icon"></view>
						</view>
						<!-- <view v-else style="height: 100rpx;"></view> -->
					</view>
					
					<view class="business-info">
						<view class="top">
							<view class="flex flex-x-between flex-y-center">
								<view class="userinfo">
									<view class="username">{{detail_data.full_name}}</view>
									<block v-if="detail_data">
										<view class="job"><text class="text">{{detail_data.position.name}}</text></view>
									</block>
								</view>
								<view class="compant-avatar-box flex flex-x-center flex-y-center">
									<image class="compant-avatar" :src="detail_data.company_logo || img_url+'images/business/business-default.jpg'" mode=""></image>
								</view>
							</view>
							
							<view class="detail-info">
								<view class="detail-item flex">
									<view class="detail-icon-box">
										<text class="detail-icon iconfont icon-shouye"></text>
									</view>
									<view class="detail-text">{{detail_data.company_name}}</view>
								</view>
								<view class="detail-item flex">
									<view class="detail-icon-box">
										<text class="detail-icon iconfont icon-dianhua21"></text>
									</view>
									<view class="detail-text">{{detail_data.mobile}}</view>
								</view>
								<view class="detail-item flex">
									<view class="detail-icon-box">
										<text class="detail-icon iconfont icon-address"></text>
									</view>
									<view class="detail-text">{{detail_data.company_address}}</view>
								</view>
							</view>
						</view>
						
						<view class="bottom flex">
							<view class="bottom-item flex-x-center flex-y-center">
								<view class="bottom-icon iconfont icon-kanguo" style="margin-top: 10rpx;"></view>
								<view>人气{{detail_data.visitors}}</view>
							</view>
							<view class="bottom-item flex-x-center flex-y-center">
								<text class="bottom-icon iconfont icon-dianzan1"></text>
								<view>靠谱{{detail_data.likes}}</view>
							</view>
						</view>
					</view>
					
					<view class="more-btn flex flex-y-center flex-x-center" @tap="showMoreInfo">
						<view v-if="!is_show_more">展开全部名片信息</view>
						<view v-else>收起以下名片信息</view>
						<text class="more-icon iconfont icon-xiala2" :class="{rotate:is_show_more}"></text>
					</view>
					<view class="more-info" :class="{hide:!is_show_more}" :style="{height: infoHeight +'px'}">
						<!-- <view class="more-info-item flex flex-x-between">
							<view class="left flex">
								<view class="title">手机号码</view>
								<view class="content">{{detail_data.mobile}}</view>
							</view>
							<view class="right" @tap="clipboard($event,detail_data.mobile)">复制</view>
						</view> -->
						<!-- <view class="more-info-item flex flex-x-between">
							<view class="left flex">
								<view class="title">微信账号</view>
								<view class="content">15015756796</view>
							</view>
							<view class="right">复制</view>
						</view> -->
						<view class="more-info-item flex flex-x-between" v-if="detail_data.landline">
							<view class="left flex">
								<view class="title">座机号码</view>
								<view class="content">{{detail_data.landline}}</view>
							</view>
							<view class="right" @tap="clipboard($event,detail_data.landline)" :style="{color:textColor}">复制</view>
						</view>
						<view class="more-info-item flex flex-x-between" v-if="detail_data.email">
							<view class="left flex">
								<view class="title">邮箱账号</view>
								<view class="content">{{detail_data.email}}</view>
							</view>
							<view class="right" @tap="clipboard($event,detail_data.email)" :style="{color:textColor}">复制</view>
						</view>
						<view class="more-info-item mark-name flex flex-x-between" v-if="detail_data.company_name">
							<view class="left flex">
								<view class="title">公司名称</view>
								<view class="content">{{detail_data.company_name}}</view>
							</view>
							<view class="right" @tap="clipboard($event,detail_data.company_name)" :style="{color:textColor}">复制</view>
						</view>
						<view class="more-info-item mark flex flex-x-between" v-if="detail_data.company_address">
							<view class="left flex">
								<view class="title">公司地址</view>
								<view class="content">{{detail_data.company_address}}</view>
							</view>
							<view class="right" @tap="clipboard($event,detail_data.company_address)" :style="{color:textColor}">复制</view>
						</view>
					</view>
					
					<view :class="{mar:!is_show_mar}" class="session flex flex-x-between">
						<view class="session-item" @tap="share" :style="{color:textColor,'border-color':textColor}">分享名片</view>
						<view class="session-item col" @tap="clipboard($event,detail_data.mobile,'http')" :style="{background:textColor,'border-color':textColor}">复制电话号码</view>
					</view>
					
					<view class="avater-list flex flex-y-center flex-x-between">
						<view class="flex flex-y-center" v-if="history_list.length != 0">
							<block v-for="(item,index) in history_list" :key='index'>
								<image class="avater-item" @tap="navTo(`/plugins/business-card/index?id=${item.my_card_id}`)"
								:src="item.user.avatar_url || img_url+'images/business/business-default.jpg'" mode=""></image>
							</block>
							
							<text class="avater-more iconfont icon-gengduo"></text>
						</view>
						<view v-else></view>
						<view @tap="businessLike">
							<text v-if="!detail_data.is_like" class="like-icon iconfont icon-shoucang1"></text>
							<text v-else class="like-icon iconfont icon-shoucang" :style="{color: textColor}"></text>
						</view>
					</view>
					
					<view class="data flex flex-x-between">
						<view class="text">最近{{detail_data.browsing_history.total}}人浏览</view>
						<view class="text">靠谱：{{detail_data.likes}}</view>
					</view>
				</view>
				<block v-if="detail_data.videos">
					<view class="common-box">
						<view class="title">视频展示</view>
						<video class="video" :src="detail_data.videos" controls></video>
					</view>
				</block>
				
				<block>
					<view class="common-box introduction" v-if="detail_data.introduction">
						<view class="title">我的简介</view>
						<view class="content">
							{{detail_data.introduction}}
						</view>
						<view class="label flex">
							<block v-if="detail_data.tag">
								<view @tap="clickLike(item.id,index)" v-for="(item,index) in detail_data.tag" :key='index' :class="{'label-item_active':item.is_like}" class="label-item flex flex-y-center">
									<view class="label-text flex flex-y-center">{{item.name}}</view> <text class="label-item-icon iconfont icon-dianzan"></text>
									{{item.likes}}
								</view>
							</block>
						</view>
					</view>
				</block>
				
				<block v-if="detail_data.images.length != 0">
					<view class="common-box">
						<view class="title">图片展示</view>
						<view>
							<image class="common-img" :src="item" mode="widthFix" v-for="(item,index) in detail_data.images" :key='index'></image>
						</view>
					</view>
				</block>
			</view>
			
			<!-- 名片信息弹窗 -->
			<view class="popUp" :class="{'popUp-show':is_popUp_show}">
				<view class="content-box">
					<view class="header-box">
						<image class="avatar-img" :src="detail_data.head_img || img_url+'images/business/business-default.jpg'" mode=""></image>
						<view class="userinfo-box flex flex-x-between flex-y-center">
							<view class="left">
								<view class="flex flex-y-bottom">
									<view class="username">{{detail_data.full_name}}</view>
									<block v-if="detail_data">
										<view>{{detail_data.position.name}}</view>
									</block>
								</view>
								<view>{{detail_data.mobile}}</view>
							</view>
							<image class="right" :src="detail_data.company_logo || img_url+'images/business/business-default.jpg'" mode=""></image>
						</view>
					</view>
					<view class="sava-btn" @tap="clipboard($event,detail_data.mobile,'http')" :style="{background:textColor}">复制手机号码</view>
					<view class="seesee" @tap="popUpHide">看看再说 ></view>
				</view>
			</view>
			
			<view class="hover-btn">
				<view class="btn-item" :style="{background:textColor}">
					<!-- #ifndef MP-WEIXIN -->
					<text class="btn-icon iconfont icon-kefu2" @tap="authorization"></text>
					<!-- #endif -->
					
					<!-- #ifdef MP-WEIXIN --> 
					<text class="btn-icon iconfont icon-kefu2"></text>
					<!-- {{detail.is_auth}} -->
					<block v-if="detail_data.is_auth">
						<view class="authorization" @tap="goSever"></view>
					</block>
					<block v-else>
						<button class="authorization" open-type="getPhoneNumber" @getphonenumber="onGetPhoneNumber"></button>
					</block>
					<!-- #endif -->
				</view>
				<view class="btn-item" @tap="plus" :style="{background:textColor}">
					<text class="btn-icon iconfont icon-hao"></text>
				</view>
			</view>
		</block>
		<view style="height: 100rpx;width: 100%;"></view>
		
		<!-- 输入授权手机弹窗 -->
		<com-modal :show="is_modal_shouquan" radius='10rpx' padding='30rpx 30rpx' width="76%" :custom='true' @cancel="hidePop('phone')">
			<view class="auth-box secretOrder-box">
				<view class="auth-avatar" :style="{background: 'url('+ detail_data.head_img || img_url+'images/business/business-default.jpg' +');'}"></view>
				<view class="auth-h2">你的手机号码</view>
				
				<view class="inp-box">
					<input class="secretOrder-inp" style="margin-bottom: 10rpx;" v-model="auth_mobile" placeholder="请输入授权手机" type="text" />
				</view>
				<view class='auth-tips' v-if="is_auth_mobile">*手机号不能为空</view>
				
				<view class="inp-box flex flex-x-between flex-y-center" style="margin-top: 40rpx;">
					
					<input class="secretOrder-inp" v-model="auth_code" placeholder="请输入验证码" type="text" />
					<view class="auth-getcode" @tap="getCode" v-if="timing == 60" style="position: relative;overflow: hidden;">
						<view :style="{color:textColor}">获取验证码</view>
						<view style="width: 100%;height: 100%;position: absolute;top: 0;left: 0;opacity: 0.1;" :style="{background:textColor}"></view>
					</view>
					<view class="auth-getcode" v-else>获取验证码({{timing}})</view>
				</view>
				<view class='auth-tips' v-if="is_auth_code">{{code_tips}}</view>
				
				<view class="flex flex-x-between" style="margin: 46rpx 0 10rpx;">
					<view class="btn-common auth-cancel" @tap="hidePop">取消授权</view>
					<view class="btn-common auth-sure" @tap="sureAuth" :style="{background:textColor}">确认授权</view>
				</view>
			</view>
		</com-modal>
		<!-- 输入授权手机弹窗 -->
		
		<!--底部选择层-->
		<com-bottom-popup :show="popupShow" @close="hidePopup" borderRadius="0 0 0 0">
			<view class="share-list flex">
				<!-- #ifdef MP-WEIXIN -->
				<view class="flex flex-y-center flex-col" style="position: relative;">
					<image class="share-img" :src="img_url + '/chat/wxImg.png'" mode=""></image>
					<view>微信好友</view>
					<button class="share-btn" open-type="share">分享</button>
				</view>
				<!-- #endif -->
				<view class="flex flex-y-center flex-col" @tap="navTo('/plugins/business-card/code/code')">
					<image class="share-img" :src="img_url + '/chat/wxCode.png'" mode=""></image>
					<view>名片码</view>
				</view>
			</view>
			<view class="cancel" @tap="hidePopup">取消</view>
		</com-bottom-popup>
		<!--底部选择层-->
		
		<!-- 输入创建命令弹窗 -->
		<com-modal :show="is_modal" padding='40rpx 40rpx' width="80%" :custom='true'>
			<view class="secretOrder-box">
				<view class="title">添加命令</view>
				<view class="tips">*您还没有名片，请先创建名片。</view>
				<view class="inp-box">
					<input class="secretOrder-inp" v-model="order" placeholder="请输入添加命令" type="text" />
				</view>
				<view class="wrong" v-if="is_wrong">*输入添加命令有误。</view>
				<view class="btn-list flex">
					<view style="margin-right: 40rpx;" @tap="hidePop('create')">取消</view>
					<view @tap="sure">确定</view>
				</view>
			</view>
		</com-modal>
		<!-- 输入创建命令弹窗 -->
		
	</view>
</template>

<script>
	import { isWeChat } from '@/utils/util.js';
	const thorui = require("@/components/common/tui-clipboard/tui-clipboard.js");
	var timer;
	var obj={
		avatar_url: "http://thirdwx.qlogo.cn/mmopen/vi_32/e7gAEg1NEkPy8Tluq5xTSYnnibKTbvYC9hGJP0CdeMqRLRfzoTE45mErLOicGaFG4uDaMD6jvqCesqC42BnZf81w/132",
		id: "user_k1",
		nickname: "七件事客服"
	};
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				popupShow: false,
				is_show_more:false,
				is_show_mar:false,
				is_popUp_show:false,
				is_modal_shouquan:false,
				is_request:false,
				detail_data:'',
				history_list:[],
				auth_mobile:'',
				is_auth_mobile:false,
				auth_code:'',
				is_auth_code:false,
				code:'',//获取验证码
				code_tips:'*验证码不能为空',
				timing:60,
				infoHeight:0,//名片信息展开的高度
				
				id:0,//登陆时候，获取到自己的user_id
				
				is_modal:false,
				is_wrong:false,
				order:'',
				
				is_my:true, //判断是否是自己的名片
				
				ordinaryH:0,
				addressH:0,
				user_storage:'',
				textColor:'',
				options_id:'',
				share_img:'',//分享的图片
				share_title:'',//分享的标题
				
				tabbar:[
					{
						active: true,
						active_color: "rgb(188, 1, 0)",
						active_icon: "http://jxmall.sinbel.top/web//uploads/images/original/20200610/25498a7029149193dc88bbd527f82eef.png",
						color: "#888",
						icon: "http://jxmall.sinbel.top/web//uploads/images/original/20200610/3e8ecb9657d2a258285b957186ad9eac.png",
						open_type: "redirect",
						text: "首页",
						url: "/plugins/business-card/index",
					},
					{
						active_color: "rgb(188, 1, 0)",
						active_icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200806/900b3f657fdd4a0ab7588c44ae3d4999.png",
						color: "#888",
						icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200806/b8352b721c91af6e669a0443973c7570.png",
						open_type: "redirect",
						text: "雷达",
						url: "/plugins/business-card/log/log",
					},
					{
						active_color: "rgb(188, 1, 0)",
						active_icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200806/164f085e0a13520c0d87a556cb6a82c7.png",
						color: "#888",
						icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200806/fce84bdbe8b0ce9ace336147cbc98402.png",
						open_type: "redirect",
						text: "消息",
						url: "/plugins/business-card/message/list",
					},
					{
						active_color: "rgb(188, 1, 0)",
						active_icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200806/24affe41873699280b881f6b3fe0dcd9.png",
						color: "#888",
						icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200806/53df1b6c503338b28e32daa7f37b08d5.png",
						open_type: "redirect",
						text: "客户",
						url: "/plugins/business-card/client/list",
					},
					{
						active_color: "rgb(188, 1, 0)",
						active_icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200610/4b5259cb3a8ee275acef5e7a6ad4bd7b.png",
						color: "#888",
						icon: "http://jxmall.sinbel.top/web/uploads/images/thumbs/20200610/2626dc5ddbb8ab3a7799aecdf7ec4c75.png",
						open_type: "redirect",
						text: "我的",
						url: "/plugins/business-card/my/index",
					}
				]
			};
		},
		onLoad(options) {
			this.options_id = options.id;
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
			this.user_storage = JSON.parse(uni.getStorageSync('userInfo')).user_id;
		},
		onShow() {
			if(this.options_id){
				this.getData(this.options_id);
			}else{
				this.getData('');
			}
		},
		methods:{
			previewImage(url) { //图片预览
				uni.previewImage({
					urls: [url],
				});
			},
			getCode(){ //获取验证码
				if(this.auth_mobile == ''){
					this.is_auth_mobile = true;
					return;
				}
				
				var timer = setInterval(()=>{
					if(this.timing == 0){
						clearInterval(timer);
						this.timing = 60;
					}else{
						this.timing--;
					}
				},1000);
				
				this.$http.request({
					url:this.$api.default.phoneCode,
					method:'post',
					data:{
						mobile:this.auth_mobile
					}
				}).then(res => {
					if(res.code == 0){
						
					}
				})
			},
			hidePopup(num) { //关闭底部弹窗
				this.popupShow = false;
				if (num == 1) {
					this.$http.toast("购物车加入成功");
				}
			},
			authorization(){
				if(this.detail_data.is_auth == 1){
					this.hidePop()
				}else if(this.detail_data.is_auth == 0){
					this.is_modal_shouquan = true;
				}else if(this.detail_data.is_auth == 2){
					this.$http.request({
						url: this.$api.plugin.business.customer_service,
						method:'post',
						showLoading:true,
					}).then(res =>{
						if(res.code == 0){
							// #ifdef H5
								window.location.href = JSON.parse(uni.getStorageSync('mall_config')).mall_setting.setting.web_service_url;
							//#endif
							
							// #ifdef APP-PLUS
								uni.navigateTo({
									url: '/pages/customerService/customerService'
								})
							//#endif
						}else{
							this.$http.toast(res.msg);
						}
					})
				}
			},
			goSever(){
				uni.navigateTo({
					url:'/pages/customerService/customerService'
				})
			},
			share(){ //分享名片
				if(!this.is_my) {
					this.$http.toast('这不是你的名片喔！');
					return;
				}
				
				this.popupShow = true;
			},
			// 创建名片
			hidePop(str){
				this.is_auth_mobile = false;
				this.is_auth_code = false;
				this.is_modal_shouquan = false;
				
				if(str == 'phone'){
					this.is_modal = false;
				}else if(str == 'create'){
					this.is_modal = false;
					this.navBack();
				}else{
					console.log('进来了')
					obj.avatar_url = this.detail_data.company_logo;
					uni.setStorageSync('other_info', JSON.stringify(obj));
					// #ifdef H5
					window.location.href = JSON.parse(uni.getStorageSync('mall_config')).mall_setting.setting.web_service_url;
					//#endif
					
					// #ifdef APP-PLUS
						uni.navigateTo({
							url: '/pages/customerService/customerService'
						})
					//#endif
				}
			},
			onGetPhoneNumber(e){ //调起用户授权手机号
				if(e.detail.errMsg=="getPhoneNumber:fail user deny"){//用户拒绝授权
					uni.navigateTo({
						url: '/pages/customerService/customerService'
					})
				}else{//允许授权
					this.$http.request({
						url: this.$api.plugin.business.customer_service,
						method:'post',
						data:{
							iv: e.detail.iv,
							encryptedData: e.detail.encryptedData
						}
					}).then(res => {
						uni.navigateTo({
							url: '/pages/customerService/customerService'
						})
					})
				} 
			},
			sureAuth(){ //手机号授权
				if(this.auth_mobile == ''){
					this.is_auth_mobile = true;
					return;
				}
				if(this.auth_code == ''){
					this.is_auth_code = true;
					return;
				}
				if(this.is_request) return;
				this.is_request = true;
				
				this.$http.request({
					url: this.$api.plugin.business.customer_service,
					method:'post',
					data:{
						mobile:this.auth_mobile,
						captcha:this.auth_code,
					}
				}).then(res =>{
					this.is_request = false;
					if(res.code == 0){
						this.is_modal_shouquan = false;
						obj.avatar_url = this.detail_data.company_logo;
						uni.setStorageSync('other_info', JSON.stringify(obj));
						
						window.location.href = JSON.parse(uni.getStorageSync('mall_config')).mall_setting.setting.web_service_url;
					}else{
						this.is_auth_code = true;
						this.code_tips = `*${res.msg}`;
					}
				})
			},
			sure(){ //跳转去创建名片
				if(this.is_request) return;
				this.is_request = true;
				
				this.$http.request({
					url:this.$api.plugin.business.edit,
					method:'post',
					showLoading:true,
					data:{
						card_token:this.order,
					}
				}).then(res=>{
					this.is_request = false;
					if(res.code == 1){
						this.is_wrong = true;
					}else{
						this.is_modal = false;
						uni.setStorageSync('commonData',res.data);
						if(this.id == 0){
							if(this.detail_data){
								uni.navigateTo({
									url:`/plugins/business-card/my/edit?val=edit&id=${this.detail_data.id}`
								})
							}else{
								uni.redirectTo({
									url:`/plugins/business-card/my/edit?val=create`
								})
							}
						}else{
							if(res.data.id){
								uni.navigateTo({
									url:`/plugins/business-card/my/edit?val=edit&id=${res.data.id}`
								})
							}else{
								uni.redirectTo({
									url:`/plugins/business-card/my/edit?val=create`
								})
							}
						}
					}
				})
			},
			plus(){
				if(this.id != 0){
					this.is_modal = true;
					return;
				}
				uni.navigateTo({
					url:`/plugins/business-card/my/edit?val=edit&id=${this.detail_data.id}`
				});
			},
			businessLike(){//名片点赞
				if(this.detail_data.is_like){
					this.detail_data.is_like = false;
					this.detail_data.likes--;
				}else{
					this.detail_data.is_like = true;
					this.detail_data.likes++;
				}
				this.$http.request({
					url:this.$api.plugin.business.like,
					method:'post',
					data:{
						id:this.detail_data.id
					}
				}).then(res =>{
					console.log(res,'resss');
				})
			},
			clickLike(id,index){
				var tagDetail = this.detail_data.tag[index];
				if(tagDetail.is_like){
					tagDetail.is_like = false;
					tagDetail.likes = parseInt(tagDetail.likes) - 1;
					this.likeTags(id);
				}else{
					tagDetail.is_like = true;
					tagDetail.likes = parseInt(tagDetail.likes) + 1;
					this.likeTags(id);
				}
			},
			likeTags(id){
				this.$http.request({
					url:this.$api.plugin.business.likeTag,
					method:'post',
					data:{
						id
					}
				}).then(res => {
					console.log(res,'ressssss');
				})
			},
			getData(id){
				this.$http.request({
					url:this.$api.plugin.business.my,
					data:{
						id
					},
					showLoading:true,
					method:'post'
				}).then(res => {
					if(res.code == 0){
						this.share_img = res.data.app_share_pic;
						this.share_title = res.data.app_share_title;
						
						if(this.user_storage == res.data.user_id){
							this.is_popUp_show = false;
						}else{
							setTimeout(()=>{
								this.is_popUp_show = true;
							},300)
						}
						this.detail_data = res.data;
						
						this.history_list=[]; //初始化一下
						this.detail_data.browsing_history.list.forEach((item,index) => {
							if(index <= 7){
								this.history_list.push(item);
							}
						})
						var user_id = JSON.parse(uni.getStorageSync('userInfo')).user_id;
						this.detail_data.user_id != user_id ? this.is_my = false : '';
						
						console.log(this.detail_data,'this.detail_data2222');
						// #ifdef H5
						var obj ={
							app_share_desc: this.detail_data.app_share_desc,
							app_share_pic: this.detail_data.app_share_pic,
							app_share_title: this.detail_data.app_share_title
						};
						if(this.id){
							this.$wechatSdk.initShareUrl(obj,`source=7`);
						}else{
							this.$wechatSdk.initShareUrl(obj,`source=7&id=${res.data.id}`);
						}
						// #endif
					}else if(res.code == 3){
						this.is_modal = true;
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			clipboard(event,data,val) {//复制文本
				if(val == 'http'){
					this.$http.request({
						url:this.$api.plugin.business.add_track,
						method:'post',
						data:{
							track_user_id:this.detail_data.user_id,
							model_id:this.detail_data.id,
							track_type:8,
						}
					}).then(res => {
						console.log(res,'ressss');
					})
				}
				thorui.getClipboardData(data, (res) => {
					setTimeout(()=>{
						this.popUpHide();
					},500);
					// #ifdef H5 || MP-ALIPAY
					if (res) {
						this.$http.toast("复制成功");
					} else {
						this.$http.toast("复制失败")
					}
					// #endif
				},event)
			},
			showMoreInfo(){
				var that = this;
				let infoM = uni.createSelectorQuery().select(".mark-name");
				infoM.boundingClientRect(function(data) {
					that.ordinaryH = data.height;
				}).exec();
				let infoC = uni.createSelectorQuery().select(".mark");
				infoM.boundingClientRect(function(data) {
					that.addressH = data.height;
				}).exec();
				
				setTimeout(()=>{
					var num = 1;
					if(this.detail_data.landline){
						num++;
					}
					if(this.detail_data.email){
						num++;
					}
					if(num == 1){
						this.infoHeight = this.ordinaryH + this.addressH;
					}else{
						this.infoHeight = (this.ordinaryH*num) + this.addressH;
					}
					
					if(this.is_show_more){
						this.infoHeight = 0;
					}
					this.is_show_more = !this.is_show_more;
					setTimeout(()=>{ //防止添加margin时候出现抖动
						this.is_show_mar = !this.is_show_mar;
					},150)
				},150)
			},
			popUpHide(){
				this.is_popUp_show = false;
			},
			navTo(url){
				this.popupShow = false;
				uni.navigateTo({
					url
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	page{
		background: #FFFFFF;
	}
	.box{
		position: relative;
		
		.avatar-img{
			position: absolute;
			top: 0;
			left: 0;
			display: block;
			height: 750rpx;
			width: 100%;
		}
	}
	
	.main{
		position: relative;
		z-index: 2;
		
		.btn-list{
			padding: 18rpx 40rpx;
			align-items: flex-end;
			
			.btn-item{
				width: 80rpx;
				height: 80rpx;
				background:rgba(0,0,0,0.5);
				border-radius: 50%;
				margin-bottom: 20rpx;
				position: relative;
				
				.btn-item-icon{
					font-size: 50rpx;
					color: #FFFFFF;
					position: absolute;
					left: 50%;
					top: 50%;
					transform: translate(-50%,-50%);
				}
				.btn-item-icon2{
					font-size: 46rpx;
				}
			}
		}
		
		.business-info{
			margin: 270rpx auto 0;
			width: 690rpx;
			border-radius: 6rpx;
			background: #FFFFFF;
			overflow: hidden;
			box-shadow:0px 3px 7px 0px rgba(0, 0, 0, 0.1);
			
			.top{
				padding: 60rpx 60rpx 20rpx;
				color: #000000;
				
				.username{
					font-size: 44rpx;
				}
				.job{
					font-size: 24rpx;
				}
				
				.compant-avatar-box{
					// background: #F7F7F7;
					border-radius: 6rpx;
					width: 90rpx;
					height: 90rpx;
					
					.compant-avatar{
						width: 80rpx;
						height: 80rpx;
						display: block;
					}
				}
				
				.detail-info{
					margin-top: 54rpx;
					
					.detail-item{
						margin-bottom: 8rpx;
						
						.detail-icon-box{
							margin-right: 20rpx;
							
							.detail-icon{
								font-size: 32rpx;
							}
						}
						.detail-text{
							color: #737373;
							font-size: 24rpx;
							padding-top: 4rpx;
						}
					}
				}
			}
			
			.bottom{
				height: 100rpx;
				border-top: 4rpx solid #F2F2F2;
				
				.bottom-item{
					width: 50%;
					color: #737373;
					font-size: 24rpx;
					
					.bottom-icon{
						margin-right: 14rpx;
					}
				}
			}
		}
		
		.more-btn{
			text-align: center;
			color: #737373;
			font-size: 24rpx;
			margin-top: 50rpx;
			
			.more-icon{
				position: relative;
				top: 6rpx;
				font-size: 30rpx;
				margin-left: 16rpx;
				transition: all 0.4s;
			}
			.rotate{
				transform: rotate(180deg);
				transform-origin: center center;
				top: 0;
			}
		}
		.mar{
			margin-top: 30rpx;
		}
		.more-info{
			padding: 0 30rpx;
			height: 600rpx;
			transition: all 0.5s;
			overflow: hidden;
			
			.more-info-item{
				border-bottom: 1px solid #F2F2F2;
				padding: 42rpx 20rpx;
				// height: 124rpx;
				// box-sizing: border-box;
				
				.left{
					font-size: 26rpx;
					color: #636363;
					
					.title{
						margin-right: 86rpx;
					}
					.content{
						width: 370rpx;
					}
				}
				.right{
					font-size: 24rpx;
					color: #BC0100;
				}
				.compang_img{
					flex: 1;
					width: 300rpx;
					height: 300rpx;
					border-radius: 10rpx;
				}
			}
		}
		.hide{
			height: 0;
		}
		
		.session{
			padding: 0 30rpx 40rpx;
			margin-top: 40rpx;
			
			.session-item{
				width: 330rpx;
				height: 68rpx;
				border-radius: 40rpx;
				text-align: center;
				line-height: 66rpx;
				font-size: 30rpx;
				border: 1px solid #BC0100;
				color: #BC0100;
			}
			.col{
				background: #BC0100;
				color: #FFFFFF;
			}
		}
		
		.avater-list{
			padding: 0 30rpx;
			
			.avater-item{
				width: 60rpx;
				height: 60rpx;
				border-radius: 6rpx;
				margin-right: 10rpx;
			}
			.avater-more{
				color: #848692;
				margin-left: 10rpx;
			}
			.like-icon{
				font-size: 40rpx;
			}
		}
		
		.data{
			padding: 10rpx 10rpx;
			font-size: 24rpx;
			
			.text{
				transform: scale(0.8);
			}
		}
	}
	
	.common-box{
		
		.title{
			background: #F7F7F7;
			padding: 0 30rpx;
			height: 90rpx;
			line-height: 90rpx;
			color: #737373;
			font-size: 30rpx;
		}
		.video{
			width: 100%;
		}
		.common-img{
			width: 100%;
			display: block;
		}
	}
	.introduction{
		
		.content{
			padding: 30rpx;
			font-size: 24rpx;
			color: #000000;
			text-indent: 54rpx;
			line-height: 40rpx;
		}
		.label{
			flex-wrap: wrap;
			padding: 0 30rpx 10rpx;
			
			.label-item{
				background: #DD807F;
				color: #FFFFFF;
				border-radius: 8rpx;
				font-size: 26rpx;
				padding: 4rpx 22rpx;
				margin-right: 10rpx;
				margin-bottom: 10rpx;
				
				.label-text{
					
					&::after{
						content: "";
						display: block;
						height: 30rpx;
						width: 4rpx;
						background: #FFFFFF;
						margin: 0 12rpx;
					}
				}
				
				.label-item-icon{
					font-size: 28rpx;
					margin-right: 10rpx;
				}
			}
			.label-item_active{
				background: #BC0100;
			}
		}
	}
	
	// 弹窗
	.popUp{
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0,0,0,0.5);
		z-index: 999999;
		transition: all 0.8s;
		transform: translateY(-100%);
		
		.content-box{
			display: inline-block;
			background: #FFFFFF;
			border-radius: 10rpx;
			padding: 30rpx;
			position: absolute;
			top: 15%;
			left: 50%;
			transform: translateX(-50%);
			
			.header-box{
				position: relative;
				border-radius: 10rpx;
				overflow: hidden;
				margin-bottom: 26rpx;
				
				.userinfo-box{
					background: rgba(0,0,0,0.7);
					padding: 20rpx 22rpx;
					position: absolute;
					bottom: 0;
					width: 100%;
					box-sizing: border-box;
					
					.left{
						color: #FFFFFF;
						font-size: 24rpx;
						
						.username{
							font-size: 30rpx;
							margin-right: 20rpx;
						}
					}
					.right{
						width: 70rpx;
						height: 70rpx;
						border-radius: 8rpx;
					}
				}
				.avatar-img{
					width: 508rpx;
					height: 508rpx;
					display: block;
				}
			}
			.sava-btn{
				background: #BC0100;
				color: #FFFFFF;
				font-size: 30rpx;
				height: 85rpx;
				border-radius: 10rpx;
				line-height: 85rpx;
				text-align: center;
				letter-spacing: 1px;
				margin-bottom: 28rpx;
			}
			.seesee{
				color: #979797;
				font-size: 24rpx;
				text-align: center;
			}
		}
	}
	.popUp-show{
		transform: translateY(0);
	}
	
	// 悬浮按钮
	.hover-btn{
		position: fixed;
		top: 50%;
		right: 0;
		z-index: 9;
		
		.btn-item{
			background: #BC0100;
			border-radius: 50%;
			width: 80rpx;
			height: 80rpx;
			color: #FFFFFF;
			text-align: center;
			line-height: 80rpx;
			margin-bottom: 20rpx;
			position: relative;
			
			.mobile{
				position: absolute;
				top: 0;
				opacity: 0;
			}
			
			.btn-icon{
				font-size: 50rpx;
			}
			
			.authorization{
				position: absolute;
				top: 0;
				z-index: 99;
				width: 76rpx;
				height: 76rpx;
				opacity: 0;
			}
		}
	}
	
	// 输入密令弹窗
	.secretOrder-box{
		.title{
			font-size: 40rpx;
			color: #000000;
		}
		.tips{
			margin-bottom: 30rpx;
			font-size: 24rpx;
			color: #BC0100;
		}
		.inp-box{
			border-bottom: 1px solid #F3F3F3;
			padding-bottom: 10rpx;
			letter-spacing: 2px;
		}
		.wrong{
			color: #bc0100;
			font-size: 24rpx;
			margin-bottom: 30rpx;
		}
		.secretOrder-inp{
			color: #000000;
			flex: 1;
		}
		.btn-list{
			color: #BC0100;
			justify-content: flex-end;
		}
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
	/*底部选择弹层*/
	
	// 授权手机弹窗
	.auth-box{
		
		.auth-avatar{
			width: 508rpx;
			height: 508rpx;
			display: block;
			background-size: cover !important;
		}
		.auth-h2{
			font-size: 36rpx;
			color: #000000;
			margin: 26rpx 0 40rpx;
			letter-spacing: 1px;
		}
		.auth-getcode{
			font-size: 24rpx;
			border-radius: 40rpx;
			padding: 14rpx 34rpx;
		}
		.btn-common{
			width: 227rpx;
			height: 86rpx;
			text-align: center;
			line-height: 86rpx;
			border-radius: 10rpx;
			color: #FFFFFF;
			letter-spacing: 1px;
		}
		.auth-cancel{
			background: #B3B0AF;
		}
		.auth-sure{
			background: #BC0100;
		}
		.auth-tips{
			font-size: 12px;
			color: #bc0100;
			letter-spacing: 1px;
		}
	}
	// 授权手机弹窗
	
	.share-btn{
		position: absolute;
		width: 100%;
		height: 100%;
		z-index: 3;
		opacity: 0;
	}
</style>
