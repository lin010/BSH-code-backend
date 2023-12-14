<template>
	<view class="container">
		<!--header-->
		<view class="tui-header-box flex flex-x-between flex-y-center" :style="{height:height+'px',background:'rgba(255,255,255,'+opcity+')'}">
			<view class="tui-icon iconfont icon-fanhui" :style="{color:opcity>=1?'#000':'#fff',background:'rgba(0, 0, 0,'+iconOpcity+')'}"
			 @tap.stop="back"></view>
			 <view class="tui-header" :style="{paddingTop:top+'px', opacity:opcity}">
			 	我的
			 </view>
			<view class="tui-header-icon" :style="{marginTop:top+'px'}"></view>
		</view>
		<!--header-->
		
		<view v-if="userInfo" class="headerBg" :style="{background:avatar?'url('+avatar+')':'url('+img_url+'ceshi/ceshi1.jpg)'}">
			<com-upload ref="upload" :serverUrl="serverUrl" @complete="result" :limit="100" :width="300" :height="384" :use_type='true'></com-upload>
		</view>
		
		<view class="btn-box flex flex-x-between flex-y-center">
			<view class="avatar flex flex-x-center flex-y-center">
				<image class="avatar-img" :src="userInfo.avatar_url" mode=""></image>
			</view>
			
			<view class="right-box flex flex-y-center">
				<view v-if="is_my" @tap="navTo('/plugins/short-video/my/edit')" :style="{background:textColor}" class="attention flex flex-y-center">
					编辑资料
				</view>
				<block v-else>
					<view class="attention flex flex-y-center" :style="{background:textColor}" @tap='attentionUser' v-if="!userInfo.is_follow">
						<view class="iconfont icon-hao attention-icon"></view>
						关注
					</view>
					<view class="attention followed flex flex-y-center" @tap='attentionUser' v-else>
						已关注
					</view>
				</block>
				
				<block>
					 <!-- v-if='userInfo.goods_list && userInfo.goods_list.length > 0' -->
					<view class="attention flex flex-y-center" @tap='showRecommend' :style="{background:textColor}">
						推荐商品
						<view class="iconfont icon-sanjiaoxing attention-icon2 attention-icon" :class="{'rotate-icon':is_show_recommend}"></view>
					</view>
				</block>
			</view>
		</view>
		<!-- 推荐商品窗口 -->
		<view class="recommend" v-if="is_show_recommend">
			<view class="recommend-title flex flex-x-between">
				<view v-if="is_my">我喜欢的商品</view>
				<view v-else>TA喜欢的商品</view>
				<!-- <view>查看更多></view> -->
			</view>
			<scroll-view scroll-x="true" class="flex scroll-box flex-y-center">
				<view class="pro-item" @tap='navTo(`/pages/goods/detail?proId=${item.id}&sign=short_video&related_user_id=${userInfo.id}`)' v-for="(item,index) in userInfo.goods_list" :key='index'>
					<view class="pro-img" :style="{background:'url('+item.cover_pic+')'}"></view>
					<view class="content-box">
						<view class="pro-name over2">{{item.name}}</view>
						<view class="price-box flex flex-y-center flex-x-between">
							<view class="price"><text style="font-size: 24rpx;margin-right: 4rpx;">&yen;</text>{{item.price}}</view>
							<view class="iconfont icon-jiaru" style="line-height: 30rpx;"></view>
						</view>
					</view>
				</view>
			</scroll-view>
		</view>
		
		<!-- 个性签名和粉丝数据 -->
		<view class="signature-box">
			<view class="title">个性签名:</view>
			
			<view style="font-size: 26rpx;" v-if="userInfo.signature_content">
				{{userInfo.signature_content}}
			</view>
			<view style="font-size: 26rpx;" v-else>
				该用户没有个性签名~
			</view>
		</view>
		<view class="flex flex-y-center fans-data">
			<view class="fans-item">
				<text class="nums">{{userInfo.praise_num}}</text>
				获赞
			</view>
			<!-- <view class="fans-item">
				<text class="nums">13</text>
				收藏
			</view> -->
			<view class="fans-item" @tap="navTo(`/plugins/short-video/check/attention?user_id=${userInfo.id}`)">
				<text class="nums">{{userInfo.follow_num}}</text>
				关注
			</view>
			<view class="fans-item" @tap="navTo(`/plugins/short-video/check/fans?user_id=${userInfo.id}`)">
				<text class="nums">{{userInfo.fans_num}}</text>
				粉丝
			</view>
		</view>
		
		<!-- 作品 -->
		<com-tabs :tabs="tabs" :currentTab="currentTab" :bold='true' bottom="2rpx" itemWidth="50%"
		:sliderHeight="4" :sliderWidth="100" @change="change"></com-tabs>
		
		<view class="works flex">
			<block v-if="video_list.length != 0">
				<block v-for="(item,index) in video_list">
					<!-- item.is_delete != 1 -->
					<view v-if="isDelete(item.is_delete)" :key='index' @tap="navTo(`/plugins/short-video/video-list/video-list?user_id=${user_id}&video_type=${currentTab + 1}&video_id=${item.id}`,'calc',index)" @longpress="longpress(item.is_show_delete,index)" 
					class="works-item flex flex-y-bottom" :style="{background:'url('+item.first_frame+')'}">
						<block v-if="!item.is_show_delete">
							<view class="flex flex-y-center works-item-box" v-if="item.check_status == 1 && item.is_blacklist == 0">
								<view class="iconfont icon-shoucang1 like-icon"></view>
								<view>{{nums(item.like_num)}}</view>
							</view>
							<view class="status" @tap.stop='notPassed(item.check_status,item,index)' v-else>{{statusText(item.check_status,item.is_blacklist)}}</view>
						</block>
						<!-- 删除视频 -->
						<view class="delete-icon-box" @tap.stop='closeDelete(index)' v-else>
							<view class="delete-icon" @tap.stop='deleteVideo(item.id,index)'>
								<view class="iconfont icon-shanchu" style="color: #FFFFFF;font-size: 50rpx;"></view>
							</view>
						</view>
					</view>
				</block>
			</block>
			<block v-else>
				<view class="tips" v-if="currentTab == 0">暂无作品</view>
				<view class="tips" v-else>暂无喜欢作品</view>
			</block>
		</view>
		
		<com-bottom-popup :z_index="999999" :z_index2="999996" :show="popupShow" @close="hidePopup" borderRadius="30rpx 30rpx 0 0">
			<view class="check-box">
				<view class="title">
					<text>审核未通过</text>
					<view class="iconfont icon-guanbi close-icon" @tap='hidePopup'></view>
				</view>
				<view class="video-info flex flex-x-between flex-y-center">
					<view class="img-box" @tap='previewVideo'>
						<image class="video-img" :src="clickItem.first_frame"></image>
						<view class="mask">
							<view class="iconfont icon-ziyuan mask-icon"></view>
						</view>
					</view>
					<view class="content">
						<view class="video-title over2">
							{{clickItem.content}}
						</view>
						<view class="time-text">
							发布时间：{{date(clickItem.created_at)}}
						</view>
					</view>
				</view>
				<view class="tips-text">审核信息</view>
				<view class="outerLayer">
					<view class="fail-text">{{clickItem.check_remark}}</view>
				</view>
				<view class="btns flex flex-y-center flex-x-center">
					<view class="btn-item" @tap='deleteVideo(clickItem.id,clickIndex)' :style="{border:'1px solid '+textColor,color:textColor,'margin-right':'100rpx'}">删除视频</view>
					<view class="btn-item" @tap='navTo("/plugins/short-video/release-video/release-video")' :style="{background:textColor,color:'#ffffff'}">重新提交</view>
				</view>
			</view>
		</com-bottom-popup>
		
		<view class="preview" v-if="is_preview" @touchmove.stop.prevent>
			<view class="previewVideo-box flex flex-y-center">
				<view class="preview-title">
					视频预览
					<view class="iconfont icon-guanbi preview-icon" @tap="closePreview"></view>
				</view>
				<short-video
					:play="true"
					:src="clickItem.video_url"
					size='small'
				></short-video>
			</view>
		</view>
	</view>
</template>

<script>
	import shortVideo from "@/components/shortVideo/short_video.vue";
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				height: 64, //header高度
				top: 0, //标题图标距离顶部距离
				scrollH: 0, //滚动总高度
				opcity: 0,
				iconOpcity: 0.4,
				user_id:0,
				userInfo:'',
				is_my:'',//判断是否是自己
				is_attention:false,//是否关注
				page:1,//页数
				sendPage:1,//请求接口的页数
				is_show_delete:false,//显示删除按钮
				
				is_show_recommend:false,
				tabs: [{
					name: "TA的作品"
				}, {
					name: "喜欢的作品"
				}],
				currentTab:0,
				textColor:'#bc0100',
				video_list:[],//用户发布的视频
				page_count:0,
				
				// 上传头像
				showEditImg: false,
				serverUrl: this.$api.default.upload,
				avatar:'',
				count:0,
				popupShow: false,
				clickItem:'',
				clickIndex:'',
				
				is_preview:false,
			}
		},
		components:{
			shortVideo
		},
		computed:{
			nums(){
				return function(val){
					if(val > 10000){
						return (val/10000).toFixed(1)+'w';
					}else{
						return val
					}
				}
			},
			statusText(){
				return function(check_status,is_blacklist){
					if(is_blacklist == 1){
						return '被拉入黑名单!'
					}else if(check_status == 0){
						return '审核中!'
					}else if(check_status == 2){
						return '审核不通过!'
					}
				}
			},
			isDelete(val){
				return function(val){
					if(val == 1 && this.currentTab == 1){
						return true;
					}else if(val == 1 && this.currentTab == 0){
						return false;
					}else{
						return true;
					}
				}
			},
		},
		onShow() {
			this.getUserInfo();
		},
		onLoad(options) {
			this.textColor = this.globalSet('textCol');
			if(options.id){
				this.user_id = options.id;
			}else{
				this.user_id = JSON.parse(uni.getStorageSync('userInfo')).user_id;
			}
			this.getVideo();
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
		onReachBottom(){
			this.page++;
			if(this.page <= this.page_count){
				this.getVideo();
			}
		},
		methods: {
			date(time){
				return this.dateFormat(time);
			},
			hidePopup() { //关闭底部弹窗
				this.popupShow = false;
			},
			notPassed(status,val,index){
				if(status == 2){
					this.popupShow = true;
					this.clickItem = val;
					this.clickIndex = index;
				}
			},
			deleteVideo(id,index){ //删除视频
				this.$http.request({
					url:this.$api.plugin.video.delete_video,
					method:'post',
					data:{
						video_id:id
					}
				}).then(res => {
					if(res.code == 0){
						this.video_list.splice(index,1);
						this.popupShow = false;
					}
					this.$http.toast(res.msg);
				})
			},
			closeDelete(index){ //关闭删除视频的按钮
				this.video_list[index].is_show_delete = false;
			},
			longpress(bool,index){
				this.video_list[index].is_show_delete = true;
			},
			result(e,name) {
				if(name == 'graphic'){
					if(e.status == 1){
						this.img_list = e.imgArr;
					}
				}else{
					this.avatar = e.imgArr[this.count];
					if(this.avatar.indexOf("https://jx.shuzixiangdao.com") != -1){
						this.count++;
					}
					this.$http.request({
						url: this.$api.plugin.video.edit_user,
						method:'post',
						data:{
							background:this.avatar
						}
					}).then(res => {
						if(res.code == 0){
							
						}
					})
				}
			},
			editInfo(){
				this.$http.request({
					url:this.$api.plugin.video.edit_user,
					method:'post',
					data:{
						background:this.avatar
					}
				}).then(res => {
					console.log(res,'ressssssss');
				})
			},
			changAcatar() {
				this.$http.toast("上传头像");
				this.showEditImg = true;
				this.$nextTick(() => {
					this.$refs['upload'].$el.click()
				})
			},
			getUserInfo(){ //获取用户信息
				this.$http.request({
					url:this.$api.plugin.video.user_list,
					method:'post',
					data:{
						user_id:this.user_id
					}
				}).then(res => {
					if(res.code == 0){
						var info='';
						this.userInfo = res.data;
						info = JSON.parse(uni.getStorageSync('userInfo'));
						info.user_id == this.userInfo.id?this.is_my=true:this.is_my=false;
						this.avatar = res.data.background;
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			getVideo(){
				this.$http.request({
					url:this.$api.plugin.video.video_list,
					method:'post',
					showLoading:true,
					data:{
						page:this.page,
						// user_id:this.userInfo.id,
						user_id:this.user_id,
						video_type:this.currentTab + 1,
					}
				}).then(res => {
					if(res.code == 0){
						this.page_count = res.data.pagination.page_count;
						this.video_list.push(...res.data.list);
						this.video_list.forEach(item => {
							this.$set(item,'is_show_delete',false);
						})
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			attentionUser(){ //点击关注
				this.$http.request({
					url:this.$api.plugin.video.operation_follow,
					method:'post',
					data:{
						user_id:this.userInfo.id,
						type:this.userInfo.is_follow?'unfollow':'follow'
					}
				}).then(res => {
					if(res.code == 0){
						this.userInfo.is_follow = !this.userInfo.is_follow;
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			navTo(url,str,index){
				if(str == 'calc'){
					if(index + 1 <= 10){
						this.sendPage = 1;
					}else{
						this.sendPage = parseInt((index+1)/10) + 1;
					}
					url = url + `&page=${this.sendPage}`;
				}
				uni.navigateTo({
					url
				})
			},
			showRecommend(){ //是否展示推荐商品
				this.is_show_recommend = !this.is_show_recommend;
			},
			change(e) {
				this.currentTab = e.index;
				this.video_list = [];
				this.page = 1;
				this.getVideo(this.currentTab+1);
			},
			back() {
				this.navBack();
			},
			previewVideo(){ //视频预览
				this.is_preview = true;
				this.popupShow = false;
			},
			closePreview(){//关闭预览
				this.is_preview = false;
			},
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

<style lang="scss" scoped>
	.container{
		background: #ffffff;
		height: 100%;
	}
	
	.tui-header-box {
		width: 100%;
		position: fixed;
		left: 0;
		top: 0;
		z-index: 99998;
		padding: 0 10rpx;
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
		flex: 1;
		padding-right: 20rpx;
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

	.tui-icon {
		border-radius: 16px;

		&.iconfont {
			line-height: 32px;
			width: 32px;
			font-size: 20px;
			display: flex;
			justify-content: center;
			position: absolute;
			left: 20rpx;
			top: 56rpx;
			z-index: 99999;
		}

		margin-right: 10rpx;
	}
	
	.headerBg{
		width: 750rpx;
		height: 384rpx;
		background-size: cover !important;
	}
	.headerBg-box{
		position: relative;
		
		.avatar-img{
			position: absolute;
			top: 0;
			z-index: 10;
		}
	}
	
	.btn-box{
		padding: 0 10rpx 0 36rpx;
		background: #FFFFFF;
		
		.avatar{
			width: 180rpx;
			height: 180rpx;
			border-radius: 50%;
			background: #FFFFFF;
			position: relative;
			top: -16rpx;
			
			.avatar-img{
				width: 94%;
				height: 94%;
				border-radius: 50%;
				display: block;
			}
		}
		.right-box{
			color: #FFFFFF;
			font-size: 28rpx;
			letter-spacing: 2px;
			margin-bottom: 16rpx;
			
			.attention{
				background: #BC0100;
				padding: 10rpx 34rpx;
				border-radius: 10rpx;
				margin-right: 20rpx;
				height: 55rpx;
			}
			.followed{
				background: #B8B8B8;
			}
			.attention-icon{
				font-size: 44rpx;
				position: relative;
				top: 1px;
				margin-right: 2rpx;
			}
			.attention-icon2{
				font-size: 28rpx;
				line-height: 28rpx;
				transform: rotate(0deg);
				transition: 0.5s all;
				transform-origin: center center;
			}
			.rotate-icon{
				transform: rotate(180deg);
			}
		}
	}
	
	.recommend{
		color: #A0A0A0;
		background: #FFFFFF;
		
		.recommend-title{
			font-size: 26rpx;
			padding: 0 30rpx;
			margin-bottom: 10rpx;
		}
		
		.scroll-box{
			white-space: nowrap;
			padding-left: 30rpx;
			box-sizing: border-box;
			
			.pro-item{
				width: 220rpx;
				display: inline-block;
				margin-right: 16rpx;
				border-radius: 10rpx;
				overflow: hidden;
				
				.pro-img{
					width: 100%;
					height: 170rpx;
					background-size: cover !important;
				}
				.content-box{
					background: #F7F7F7;
					padding: 10rpx 8rpx;
				}
				.pro-name{
					color: #000000;
					font-size: 26rpx;
					white-space: normal;
					height: 76rpx;
				}
				.price-box{
					font-size: 30rpx;
					color: #DC0D0D;
					margin-top: 4rpx;
				}
			}
		}
	}
	
	.signature-box{
		border-top: 1px solid #F4F4F4;
		border-bottom: 1px solid #F4F4F4;
		padding: 34rpx 30rpx 24rpx;
		color:#000000;
		background: #FFFFFF;
				
		.title{
			font-size: 30rpx;
			margin-bottom: 20rpx;
		}
	}
	
	.fans-data{
		padding: 30rpx 30rpx 40rpx;
		font-size: 30rpx;
		color: #000000;
		background: #FFFFFF;
		
		.fans-item{
			margin-right: 28rpx;
			
			.nums{
				color: #BC0100;
				font-weight: 600;
				margin-right: 10rpx;
			}
		}
	}
	
	.works{
		padding-top: 30rpx;
		flex-wrap: wrap;
		min-height: 400rpx;
		background: #FFFFFF;
		padding-bottom: 40rpx;
		
		.works-item{
			width: 246rpx;
			height: 330rpx;
			margin-right: 4rpx;
			margin-bottom: 4rpx;
			background-size: cover !important;
			background-position: center center;
			position: relative;
			
			.works-item-box{
				color: #FFFFFF;
				font-size: 26rpx;
				margin-left: 6rpx;
				margin-bottom: 4rpx;
			}
			.like-icon{
				font-size: 28rpx;
				position: relative;
				top: 4rpx;
			}
			.status{
				background: rgba(0,0,0,0.55);
				letter-spacing: 1px;
				text-align: center;
				line-height: 64rpx;
				position: absolute;
				font-size: 22rpx;
				color: #FFFFFF;
				height: 64rpx;
				width: 100%;
				bottom: 0;
				left: 0;
			}
			.delete-icon-box{
				width: 246rpx;
				height: 330rpx;
				background: rgba(0,0,0,0.4);
				position: absolute;
				top: 0;
				left: 0;
				
				.delete-icon{
					transform: translate(-50%,-50%);
					background: rgba(0,0,0,0.3);
					border-radius: 50%;
					text-align: center;
					line-height: 90rpx;
					position: absolute;
					height: 90rpx;
					width: 90rpx;
					left: 50%;
					top: 50%;
				}
			}
		}
		.tips{
			text-align: center;
			width: 100%;
		}
	}
	
	.check-box{
		
		.title{
			font-size: 36rpx;
			color: #000000;
			position: relative;
			text-align: center;
			height: 90rpx;
			line-height: 90rpx;
			border-bottom: 1px solid #F2F2F2;
			margin-bottom: 40rpx;
			
			.close-icon{
				position: absolute;
				top: 20rpx;
				right: 30rpx;
				font-size: 30rpx;
				line-height: 30rpx;
			}
		}
		.video-info{
			padding: 0 30rpx 30rpx;
			
			.img-box{
				position: relative;
				
				.mask{
					position: absolute;
					top: 0;
					left: 0;
					background: rgba(0,0,0,0.35);
					width: 204rpx;
					height: 154rpx;
					text-align: center;
					line-height: 154rpx;
					
					.mask-icon{
						font-size: 60rpx;
						color: #FFFFFF;
					}
				}
			}
			.video-img{
				width: 204rpx;
				height: 154rpx;
				border-radius: 10rpx;
				display: block;
			}
			.content{
				margin-left: 22rpx;
				flex: 1;
				
				.video-title{
					color: #000000;
					font-size: 34rpx;
					margin-bottom: 8rpx;
				}
				.time-text{
					color: #666666;
					font-size: 20rpx;
				}
			}
		}
		.tips-text{
			font-size: 28rpx;
			color: #000000;
			letter-spacing: 2px;
			padding: 0 30rpx 14rpx;
		}
		.outerLayer{
			padding: 0 30rpx;
		}
		.fail-text{
			background: #F7F7F7;
			color: #666666;
			font-size: 24rpx;
			padding: 14rpx 12rpx;
		}
		.btns{
			padding: 60rpx 100rpx;
			
			.btn-item{
				width: 222rpx;
				height: 68rpx;
				border-radius: 60rpx;
				text-align: center;
				line-height: 68rpx;
				font-size: 28rpx;
			}
		}
	}
	
	// 视频预览
	.preview{
		width: 100%;
		height: calc(100vh - var(--window-bottom));
		background: #000000;
		position: fixed;
		top: 0;
		z-index: 99999;
		
		.previewVideo-box{
			height: 100%;
			flex-direction: column;
			justify-content: flex-end;
			
			.preview-title{
				color: #FFFFFF;
				position: relative;
				letter-spacing: 2px;
				width: 100%;
				margin-bottom: 20rpx;
				text-align: center;
				
				.preview-icon{
					font-size: 30rpx;
					position: absolute;
					right: 48rpx;
					top: 60%;
					transform: translateY(-50%);
					line-height: 30rpx;
				}
			}
		}
	}
</style>
