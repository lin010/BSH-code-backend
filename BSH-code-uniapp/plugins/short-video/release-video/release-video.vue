<template>
	<view class="release-root">
		<view class="video-content">
			<view class="title flex flex-x-between flex-y-bottom">
				<input type="text" v-model="sendData.title" @input='monitor("title")' maxlength='20' style="width: 80%;" placeholder="填写标题会有更多点赞哦~"
				 placeholder-style="color:#959595" />
				<view class="wordLimit">{{title_num}}/20</view>
			</view>
			
			<view class="detail-box">
				<textarea @input='monitor("detail")' v-model="sendData.content" class="detail-Introduction" maxlength='50'
				 placeholder="请输入视频的简介" placeholder-style="color:#959595" />
				<view class="detail-wordLimit wordLimit">{{title_detail_num}}/50</view>
			</view>
			
			<view @tap="chooseVideo" class="addVideo-box flex flex-y-center flex-x-center" v-if="sendData.video_url == ''">
				<text class="common-icon iconfont icon-plus" style="margin-left: 0;font-size: 56rpx;"></text>
			</view>
			
			<view class="video-box" v-else>
				<video :show-center-play-btn='false'  id="short-video" :src="sendData.video_url" :controls='false' @tap="previewVideo"
				style="width: 200rpx;height: 200rpx;border-radius: 16rpx;"></video>
				<view class="video-delete flex flex-x-center" @tap="videoDelete">
					<text class="iconfont icon-iconjian delete-icon"></text>
				</view>
			</view>
			
			<view class="select-tab flex">
				<view class="select-tab-item" v-for="(item,index) in sendData.label_id" :key='index'>
					{{item.name}}
					<view class="delete-tab flex flex-x-center flex-y-center" @tap='deleteTab(index)'>
						<view class="iconfont icon-guanbi delete-icon"></view>
					</view>
				</view>
			</view>
		</view>
		
		<view class="tab-box">
			<view class="title">推荐标签</view>
			<view class="tab-item-box flex">
				<view class="tab-item" :class="[lableBool(item)?'tabs-item_active':'']" :style="{'background-color':lableBool(item)?textColor:''}"
				@tap='selsectLable(item,index)' v-for="(item,index) in lable_list" :key='item.name'>{{item.name}}</view>
			</view>
			<view class="diy-tab flex flex-x-between flex-y-center">
				<view class="diy-tab-box flex flex-x-between" :class="{'diy-tab-box_active':createLabel}">
					<input @input='searchLable' @focus='focus' v-model="createLabel" type="text" class="diy-tab-inp" maxlength="6" placeholder="请输自定义标签，最多6个字" placeholder-style="font-size:26rpx">
					<view class="relatedTag" v-if="is_focus && createLabel">
						 <scroll-view scroll-y="true" class="related-scroll">
						 	<block v-if="search_lable_list.length != 0">
								<view class="relatedTag-item" @tap="chooseTag(item.id,item.name)" v-for="(item,index) in search_lable_list" :key='item.id'>{{item.name}}</view>
							</block>
							<block v-else>
								<view class="search-tips">暂无相同标签~</view>
							</block>
						 </scroll-view>
					</view>
					<view v-if="is_focus && createLabel" @tap="emptyContent" class="iconfont icon-47guanbi close-tag"></view>
				</view>
				<view class="tab-limit">{{sendData.label_id.length || 0}}/{{allow_create_num}}</view>
				<view class="add-btn" v-if="createLabel" :style="{'background-color':createLabel?textColor:''}" @tap='addLable'>确认添加</view>
				<view class="add-btn" v-else>确认添加</view>
			</view>
		</view>
		
		<view class="related">
			<view @tap='navTo("/plugins/short-video/related/related-goods","release-goods")' class="related-item flex flex-x-between flex-y-center">
				<view class="related-item-left flex flex-y-center">
					<view class="left-icon iconfont icon-gouwuche" :style="{background:sendData.goods_name?textColor:''}"></view>
					<view class="left-title">关联商品</view>
				</view>
				<view class="related-item-right flex flex-y-center">
					<view class="right-title over1" v-if="sendData.goods_name">{{sendData.goods_name}}</view>
					<view class="right-title over1" v-else>选择商品</view>
					<view class="iconfont icon-xiala right-icon" v-if="!sendData.goods_name"></view>
					<view class="iconfont icon-guanbi right-icon" v-else style="font-size: 20rpx;margin: 4rpx 10rpx 0;" @tap.stop="clearRelated('goods')"></view>
				</view>
			</view>
			<!-- <view @tap='navTo("/plugins/short-video/related/related-shops")' class="related-item flex flex-x-between flex-y-center">
				<view class="related-item-left flex flex-y-center">
					<view class="left-icon iconfont icon-dianpu"></view>
					<view class="left-title">关联店铺</view>
				</view>
				<view class="related-item-right flex flex-y-center">
					<view class="right-title over1">选择店铺</view>
					<view class="iconfont icon-xiala right-icon"></view>
				</view>
			</view> -->
			<view @tap='releaseAddress' class="related-item flex flex-x-between flex-y-center" style="border-bottom: 0;">
				<view class="related-item-left flex flex-y-center">
					<view class="left-icon iconfont icon-dizhi" :style="{background:sendData.related_address?textColor:''}"></view>
					<view class="left-title">关联地址</view>
				</view>
				<view class="related-item-right flex flex-y-center">
					<view class="right-title over1" v-if="sendData.related_address">{{sendData.related_address}}</view>
					<view class="right-title over1" v-else>选择地址</view>
					<view class="iconfont icon-xiala right-icon" v-if="!sendData.related_address"></view>
					<view class="iconfont icon-guanbi right-icon" v-else style="font-size: 20rpx;padding: 4rpx 10rpx 0;" @tap.stop="clearRelated('address')"></view>
				</view>
			</view>
		</view>
		
		<view class="footer">
			<view class="flex flex-y-center footer-left">
				<view v-if="is_show_select" class="iconfont icon-xuanzhong select-icon" @tap='switchBtn' style="margin-right: 10rpx;color: #BC0100;"></view>
				<view v-else class="iconfont icon-quanquan select-icon" @tap='switchBtn' style="margin-right: 10rpx;"></view>
				
				<view>
					阅读并同意
					<text :style="{color: textColor}" >《短视频发布协议》</text>
					<!-- <text style="color: #BC0100;" @tap="navTo('放协议的路径，暂无，之后有注释出来就可以了，判断已经做好','protocol')">《短视频发布协议》</text> -->
				</view>
			</view>
			<view class="relrase-btn" @tap="releaseVideo" :style="{'background-color':textColor}">发布短视频</view>
			 <!-- @tap='navTo("/plugins/short-video/review/under-review")' -->
		</view>
		
		<view class="preview" v-if="is_preview" @touchmove.stop.prevent>
			<view class="previewVideo-box flex flex-y-center">
				<view class="preview-title">
					视频预览
					<view class="iconfont icon-guanbi preview-icon" @tap="closePreview"></view>
				</view>
				<short-video
					:play="true"
					:src="sendData.video_url"
					size='medium'
				></short-video>
			</view>
		</view>
		<main-loading :visible="loading"></main-loading>
	</view>
</template>

<script>
	import shortVideo from "@/components/shortVideo/short_video.vue";
	export default {
		data() {
			return {
				textColor:'#bc0100',
				title_num:0,//标题字数限制
				title_detail_num:0,//视频简介字数限制
				
				lable_list:[],//标签列表
				search_lable_list:'',//搜索出来的标签(最多十条)
				lable_index:-1,
				user_create_num:'',
				allow_create_num:'',
				createLabel:'',//创建标签
				is_focus:true,//是否失去焦点
				address_id:'',//关联地址id
				
				//发送给后台的数据
				sendData:{
					video_url:'',// 上传视频路径
					title:'', //标题
					content:'',//视频简介
					label_id:[],//已选择得标签列表
					related_type:'',//关联的类型
					related_goods_id:'',//关联商品的id
					goods_name:'',//关联的商品名
					related_lat:'',//关联纬度
					related_lon:'',//关联经度
					// 视频长高
					video_wide:0,
					video_long:0,
					related_address:'',
					related_detailed_address:'',
				},
				
				is_show_select:false,
				loading:false,
				is_preview:false,
			};
		},
		components:{
			shortVideo
		},
		onLoad(options) {
			if(uni.getStorageSync('sendData')){
				this.sendData = JSON.parse(uni.getStorageSync('sendData'));
			}
			if(JSON.stringify(options) != '{}'){
				this.sendData.related_goods_id = options.goods_id;
				this.sendData.goods_name = options.goods_name;
				// this.address_id = options.address_id;
			}
			this.textColor = this.globalSet('textCol');
			this.getLable();
		},
		computed:{
			lableBool(){ //判断列表是否选中
				return function(obj){
					var temp = this.sendData.label_id.find(item =>item.name == obj.name);
					return temp?true:false;
				}
			}
		},
		methods:{
			emptyContent(){ //清空输入框内容
				this.createLabel = '';
			},
			chooseTag(id,name){ //自定义标签如有雷同，则可以选择添加
				this.user_create_num++;
				this.createLabel = '';
				this.sendData.label_id.push({id,name});
			},
			releaseAddress(){ //关联地址
				console.log(this.sendData,'this.sendData');
				if(this.sendData.goods_name){
					this.$http.toast('只能二选一进行关联!');
					return;
				}
				if(this.sendData.related_address) return;
				this.chooseLocation(function(res){
					this.sendData.related_lat = res.latitude;
					this.sendData.related_lon = res.longitude;
					this.sendData.related_address = res.name;
					this.sendData.related_detailed_address = res.address;
				});
			},
			releaseVideo(){
				var lableId_arr=[],send='';
				if(!this.is_show_select){
					this.$http.toast('请同意短视频发布协议');
					return;
				}
				console.log(this.sendData.label_id,'label_id');
				if(this.sendData.label_id.length == 0){
					this.$http.toast('请添加标签');
					return;
				}
				
				if(this.sendData.related_goods_id){
					this.sendData.related_type = 1;
				}else if(this.sendData.related_lat){
					this.sendData.related_type = 3;
				}else{
					this.sendData.related_type = 4;
				}
				// 给变量赋值是因为传给后台的id数字只要id不用name值，原数据操作会改变视图，所以重新赋值操作
				send = JSON.parse(JSON.stringify(this.sendData));
				send.label_id.map(item => {
					lableId_arr.push(item.id);
				})
				send.label_id = lableId_arr;
				
				this.$http.request({
					url:this.$api.plugin.video.create_video,
					method:'post',
					showLoading:true,
					data:send
				}).then(res => {
					if(res.code == 0){
						uni.removeStorageSync('sendData');
						console.log('-------');
						uni.navigateBack();
					}
					this.$http.toast(res.msg);
				})
			},
			clearRelated(str){ //清除关联
				if(str == 'goods'){
					this.sendData.goods_name = '';
					this.sendData.related_goods_id = '';
				}else if(str == 'address'){
					this.sendData.related_address = '';
					this.sendData.related_goods_id = '';
					this.sendData.related_lat = '';
					this.sendData.related_lon = '';
				}
			},
			addLable(){
				if(this.sendData.label_id.length >= this.allow_create_num){
					this.$http.toast('添加标签已达上限');
					return;
				}
				this.$http.request({
					url:this.$api.plugin.video.create_lable,
					method:'post',
					data:{
						name:this.createLabel
					}
				}).then(res => {
					if(res.code == 0){
						this.user_create_num++;
						this.createLabel = '';
						this.sendData.label_id.push(res.data);
					}
					this.$http.toast(res.msg);
				})
			},
			// blur(){
			// 	this.is_focus = false;
			// },
			focus(){
				this.is_focus = true;
			},
			searchLable(){ //搜索标签
				if(this.createLabel){
					this.getLable(this.createLabel);
				}else{
					this.search_lable_list=[];
				}
			},
			getLable(name){ //获取标签列表
				this.$http.request({
					url:this.$api.plugin.video.lable_index,
					method:'post',
					data:{
						name:name?name:'',
						is_recommend:1
					}
				}).then(res => {
					if(res.code == 0){
						if(name){
							this.search_lable_list = res.list.filter((v,index) => index <= 9);
						}else{
							this.user_create_num = res.user_create_num;
							this.allow_create_num = res.allow_create_num;
							this.lable_list.push(...res.list);
						}
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			selsectLable(item,index){ //选择标签
				if(this.sendData.label_id.length+1 > this.allow_create_num) return;
				this.lable_index = index;
				this.sendData.label_id.push(item);
				this.sendData.label_id = Array.from(new Set(this.sendData.label_id));
			},
			previewVideo(){ //视频预览
				this.is_preview = true;
			},
			closePreview(){//关闭预览
				this.is_preview = false;
			},
			navTo(url,str){
				if(str == 'release-goods'){
					if(this.sendData.related_address){
						this.$http.toast('只能二选一进行关联!');
						return;
					}
					if(this.sendData.goods_name){
						this.$http.toast('已经关联过了喔');
						return;
					}
					uni.setStorageSync('sendData', JSON.stringify(this.sendData));
					
				}
				uni.navigateTo({
					url
				})
			},
			switchBtn(){
				this.is_show_select = !this.is_show_select;
			},
			monitor(str){ //字数限制
				if(str == 'title'){
					this.title_num = this.sendData.title.length;
				}else{
					this.title_detail_num = this.sendData.content.length;
				}
			},
			chooseVideo(){ //上传视频
				uni.chooseVideo({
					maxDuration:60,
					count: 1,
					compressed:false,
					success: (responent) => {
						this.loading = true;
						this.sendData.video_wide = responent.width;
						this.sendData.video_long = responent.height;
						
						let videoFile = responent.tempFilePath;
						this.$http.uploadFile({
							serverUrl: this.$api.default.upload,
							file: videoFile,
							fileKeyName: 'file',
						}).then(res => {
							this.loading = false;
							if(res.code == 0){
								this.sendData.video_url = res.data.url;
								this.videoContext = uni.createVideoContext('short-video');
							}else{
								this.$http.toast(res.msg);
							}
						})
					}
				})
			},
			videoDelete(){ //删除视频
				this.sendData.video_url = '';
			},
			deleteTab(index){
				this.sendData.label_id.splice(index,1);
			}
		}
	}
</script>

<style lang="scss" scoped>
	page{
		background-color: #FFFFFF;
	}
	.release-root{
		background-color: #FFFFFF;
	}
	.video-content{
		border-top: 1px solid #F2F2F2;
		border-bottom: 1px solid #F2F2F2;
		padding: 30rpx 30rpx 16rpx;
		color: #000000;
		
		.title{
			border-bottom: 1px solid #F2F2F2;
			padding-bottom: 30rpx;
		}
		.detail-box{
			position: relative;
			
			.detail-Introduction{
				margin-top: 30rpx;
				width: 100%;
				height: 160rpx;
			}
			.detail-wordLimit{
				position: absolute;
				bottom: 0;
				right: 0;
			}
		}
		.wordLimit{
			color: #999999;
			font-size: 24rpx;
		}
		// 上传视频
		.addVideo-box{
			border: 1px solid #E6E6E6;
			border-radius: 14rpx;
			width: 190rpx;
			height: 190rpx;
			margin-top: 30rpx;
		}
		.common-icon{
			font-size: 50rpx;
			margin-left: 20rpx;
			color: #A9A9A9;
			line-height: 54rpx;
		}
		.video-box{
			position: relative;
			flex: 1;
			margin-top: 30rpx;
			width: 200rpx;
			height: 200rpx;
			
			.video-delete{
				position: absolute;
				width: 36rpx;
				height: 36rpx;
				border-radius: 50%;
				background: #EB0909;
				color: #FFFFFF;
				top: -10rpx;
				right: -10rpx;
				z-index: 99;
				
				.delete-icon{
					font-size: 26rpx;
				}
			}
		}
		//已选择标签
		.select-tab{
			margin-top: 20rpx;
			font-size: 26rpx;
			color: #000000;
			flex-wrap: wrap;
			
			.select-tab-item{
				background: #f7f7f7;
				border-radius: 40rpx;
				padding: 10rpx 30rpx;
				position: relative;
				font-weight: 600;
				margin-right: 16rpx;
				margin-bottom: 10rpx;
				
				.delete-tab{
					background: #BC0100;
					border-radius: 50%;
					width: 24rpx;
					height: 24rpx;
					position: absolute;
					top: -2rpx;
					right: -4rpx;
					
					.delete-icon{
						font-size: 24rpx;
						color: #FFFFFF;
						transform: scale(0.6);
						margin-top: 2rpx;
					}
				}
			}
		}
	}
	// 标签
	.tab-box{
		padding: 30rpx;
		border-bottom: 1px solid #F2F2F2;
		
		.title{
			font-size: 30rpx;
			color: #000000;
			font-weight: 600;
			letter-spacing: 1px;
			margin-bottom: 26rpx;
		}
		.tab-item-box{
			padding: 0 22rpx 10rpx;
			flex-wrap: wrap;
			border-bottom: 1px solid #F2F2F2;
			
			.tab-item{
				font-size: 24rpx;
				letter-spacing: 1px;
				background: #F7F7F7;
				height: 56rpx;
				line-height: 56rpx;
				border-radius: 40rpx;
				padding: 0 22rpx;
				margin: 0 16rpx 20rpx 0;
			}
			.tabs-item_active{
				color: #ffffff;
			}
		}
		
		.diy-tab{
			padding: 40rpx 20rpx 16rpx;
			
			.diy-tab-box{
				width: 400rpx;
				height: 72rpx;
				background: #F2F2F2;
				border: 1px solid #F2F2F2;
				padding: 16rpx 24rpx;
				border-radius: 16rpx;
				font-size: 26rpx;
				margin-right: 8rpx;
				color: #000000;
				position: relative;
				box-sizing: border-box;
				
				.diy-tab-inp{
					font-size: 26rpx;
					width: 100%;
				}
				.relatedTag{
					position: absolute;
					top: 66rpx;
					left: -1px;
					border: 1px solid #EEEEEE;
					background: #FFFFFF;
					width: 101%;
					border-radius: 0 0 16rpx 16rpx;
					box-sizing: border-box;
					border-top: 0;
					padding: 0 24rpx;
					
					.related-scroll{
						max-height: 400rpx;
					}
					.relatedTag-item{
						padding: 20rpx 0;
						
						&:first-child{
							border-top: 1px solid #EEEEEE;
						}
					}
				}
			}
			.close-tag{
				position: absolute;
				right: 14rpx;
				top: 50%;
				transform: translateY(-50%);
				font-size: 28rpx;
			}
			.add-btn{
				letter-spacing: 1px;
				color: #FFFFFF;
				font-size: 24rpx;
				height: 72rpx;
				line-height: 72rpx;
				padding: 0 36rpx;
				border-radius: 16rpx;
				background-color: #F2F2F2;
			}
			.diy-tab-box_active{
				background: #FFFFFF;
				border: 1px solid #EEEEEE;
				box-sizing: border-box;
				border-radius: 16rpx 16rpx 0 0;
			}
			.tab-limit{
				color: #999999;
				font-size: 28rpx;
				flex: 1;
				margin-right: 10rpx;
			}
		}
	}
	
	// 关联
	.related{
		border-bottom: 1px solid #F2F2F2;
		padding: 0 30rpx;
		
		.related-item{
			padding: 16rpx 0;
			border-bottom: 1px solid #F2F2F2;
			
			.left-icon{
				width: 60rpx;
				height: 60rpx;
				border-radius: 50%;
				background: #808080;
				color: #FFFFFF;
				text-align: center;
				line-height: 62rpx;
				margin-right: 24rpx;
			}
			.left-title{
				color: #000000;
				font-weight: 600;
				font-size: 30rpx;
			}
			.right-title{
				color: #BFBFBF;
				font-size: 24rpx;
				width: 350rpx;
				text-align: right;
			}
			.right-icon{
				color: #BFBFBF;
				font-size: 36rpx;
				padding-top: 4rpx;
			}
		}
	}
	
	// 底部发布视频
	.footer{
		margin-bottom: 60rpx;
		
		.footer-left{
			font-size: 28rpx;
			margin-left: 30rpx;
			margin-top: 20rpx;
			
			.select-icon{
				position: relative;
				top: 4rpx;
			}
		}
		.relrase-btn{
			width: 690rpx;
			height: 90rpx;
			border-radius: 60rpx;
			background: #BC0100;
			color: #FFFFFF;
			text-align: center;
			line-height: 90rpx;
			margin: 36rpx auto 0;
		}
	}
	.search-tips{
		text-align: center;
		color: #bbbeba;
		font-size: 28rpx;
		padding: 50rpx 0;
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
