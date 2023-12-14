<template>
	<view>
		<view class="status-bar"></view>
		<view class="header">
			<image class="header-bg" :src="img_url + 'graphic/graphicBg.png'" mode="widthFix"></image>
			<view style="height: 160rpx;"></view>
			<view class="header-nav-box">
				<view class="header-nav">
					<image class="header-icon" @tap="navTo" :src="img_url+'graphic/release.png'" mode=""></image>
					<view>直播</view>
					<view class="header-active">图文</view>
					<view>视频</view>
				</view>
				<view class="header-search">
					<view class="header-search-icon iconfont icon-search"></view>
					搜索喜欢内容吧~
				</view>
			</view>
			
			<view class="header-swiper">
				<swiper class="swiper-box" circular="true" :indicator-dots="true" :autoplay="false" :interval="3000" :duration="1000">
					<swiper-item>
						<view class="swiper-item">
							<image class="swiper-img" src="https://aecpm.alicdn.com/simba/img/TB14ab1KpXXXXclXFXXSutbFXXX.jpg_q50.jpg"></image>
						</view>
					</swiper-item>
					<swiper-item>
						<view class="swiper-item">
							<image class="swiper-img" src="https://aecpm.alicdn.com/simba/img/TB1CWf9KpXXXXbuXpXXSutbFXXX.jpg_q50.jpg"></image>
						</view>
					</swiper-item>
				</swiper>
			</view>
		</view>
		
		<view class="category">
			<scroll-view scroll-x="true" class="scroll-box" >
				<view class="scroll-item" :class="{'scroll-item-active':switchCateIndex == index}" @tap="switchCate(index)" v-for="(item,index) in cateData" :key='index'>{{item}}</view>
			</scroll-view>
		</view>
		
		<view class="content">
			<view class="content-item" @tap.stop="navTo('content')" v-for="(item,index) in contentData" :key='index'>
				<image class="content-item-img" :src="item.pic_url" mode="widthFix"></image>
				<view class="content-bottom">
					<view class="content-name">{{item.name}}</view>
					<view class="content-operat">
						<view class="content-userinfo content-common">
							<image class="avatar" :src="img_url+'images/product/1.jpg'" mode=""></image>
							<view class="username">兔兔</view>
						</view>
						<view class="content-like content-common" @tap.stop="like(index)">
							<view class="like-icon iconfont icon-shoucang1" v-if="item.is_like != 1"></view>
							<view class="like-icon like-icon-avtive iconfont icon-shoucang" v-else></view>
							<view class="like-num">3.9w</view>
						</view>
					</view>
				</view>
			</view>
		</view>
		
		<main-tabbar></main-tabbar>
		
		<view class="open-album" @tap.stop="openAlbum">
			<view class="open-icon iconfont icon-plus"></view>
		</view>
		<view style="height: 20rpx;"></view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				switchCateIndex:0, //分类切换index
				cateData:['精选','美妆','穿搭','发型','母婴生活','家居生活','旅游','美食'],
				contentData:[
					{
						name:'夏天请赐兔兔一杯茶红桃兔|饮品摄影|快乐拍照哈哈哈哈哈',
						pic_url:'//gw.alicdn.com/bao/uploaded/i4/1776898091/TB243vhbXgkyKJjSspoXXcOPpXa_!!1776898091.png_500x500q90.jpg',
						is_like:0,
					},
					{
						name:'糖秋千新品拍摄（饮品ins风）',
						pic_url:'//img13.static.yhbimg.com/goodsimg/2019/12/17/11/02742b8d6daec55236e34049c50fafdc1f.jpg?imageMogr2/thumbnail/235x314/position/center/quality/60/format/webp',
						is_like:0,
					},
					{
						name:'不以香气诱人，更以神思为境#IN LIGHT#',
						pic_url:'//cbu01.alicdn.com/img/ibank/2016/117/633/3068336711_1179464981.360x360.jpg_.webp',
						is_like:0,
					},
					{
						name:'2019夏季韩版大码女装宽松女士短袖T恤女蝙蝠衫学生上衣一件代发',
						pic_url:'//cbu01.alicdn.com/img/ibank/2020/577/947/14420749775_149729206.360x360.jpg_.webp',
						is_like:0,
					},
					{
						name:'HOT TOYS 复仇者联盟3：无限战争 战争机器MARK Ⅳ 1:6比例合金珍藏人偶',
						pic_url:'//img10.static.yhbimg.com/goodsimg/2018/07/31/13/019445cd91cfa99019d7c220eb2376054f.jpg?imageMogr2/thumbnail/235x314/position/center/quality/60/format/webp',
						is_like:0,
					},
					{
						name:'不以香气诱人，更以神思为境#IN LIGHT#',
						pic_url:'//cbu01.alicdn.com/img/ibank/2020/577/947/14420749775_149729206.360x360.jpg_.webp',
						is_like:0,
					},
					{
						name:'R39   (独家首发) Letme x R39 合作款T恤',
						pic_url:'//img12.static.yhbimg.com/goodsimg/2019/11/22/16/02672c63e642fe873f8ec23724a171fe23.jpg?imageMogr2/thumbnail/235x314/position/center/quality/60/format/webp',
						is_like:0,
					}
				]
			}
		},
		methods: {
			like(index){ //点赞
				this.contentData[index].is_like == 0 ? this.contentData[index].is_like = 1 : this.contentData[index].is_like = 0
			},
			navTo(name){
				if(name == 'content'){
					uni.navigateTo({
						url:'/pages/picture/release'
					})
				}else{
					this.$http.toast('打开相册')
				}
			},
			switchCate(index){ //分类切换
				this.switchCateIndex = index;
			},
			openAlbum(){ //打开相册
				console.log(41213);
				// uni.chooseImage({
				//     count: 1, //默认9
				//     sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
				//     sourceType: ['album'], //从相册选择
				//     success: function (res) {
				//         console.log(res.tempFilePaths);
				//     }
				// });
				uni.navigateTo({
					url:'/pages/picture/label'
				})
			}
		}
	}
</script>

<style scoped>
	.status-bar{
		height: var(--status-bar-height);
	}
	
	/* header */
	.header{
		position: relative;
	}
	.header-bg{
		width: 100%;
		position: absolute;
		top: 0;
	}
	.header-nav-box{
		width: 100%;
		position: fixed;
		z-index: 999;
		top: 0;
		background: #FFFFFF;
		padding-bottom: 14rpx;
	}
	.header-nav{
		width: 100%;
		height: 94rpx;
		display: flex;
		justify-content: space-around;
		padding: 20rpx 170rpx;
		font-size: 13pt;
		color: #999999;
		position: relative;
	}
	.header-active{
		color: #000000;
		border-bottom: 1pt solid #BC0100;
	}
	.header-icon{
		width: 44rpx;
		height: 44rpx;
		position: absolute;
		top: 50%;
		transform: translateY(-50%);
		left: 30rpx;
	}
	.header-search{
		background: #F5F5F5;
		border-radius: 60rpx;
		width: 690rpx;
		height: 64rpx;
		margin: auto;
		color: #999999;
		padding: 10rpx 0;
		display: flex;
		justify-content: center;
		align-items: center;
		font-size: 11pt;
	}
	.header-search-icon{
		margin-right: 10rpx;
		font-size: 30rpx;
	}
	
	.header-swiper{
		margin-top: 32rpx;
	}
	.swiper-box{
		width: 690rpx;
		height: 300rpx;
		margin: auto;
		border-radius: 25rpx;
		overflow: hidden;
	}
	.swiper-img{
		width: 100%;
		height: 300rpx;
	}
	
	/* 分类导航 */
	.category{
		padding: 30rpx 0;
	}
	.scroll-box{
		white-space: nowrap;
	}
	.scroll-item{
		display: inline-block;
		margin-right: 10rpx;
		padding: 16rpx 22rpx;
		color: #4D4D4D;
		font-size: 10pt;
		border:2rpx solid rgba(230,230,230,1);
		border-radius:10rpx;
		letter-spacing: 2rpx;
	}
	.scroll-item:first-child{
		margin-left: 10rpx;
	}
	.scroll-item-active{
		background: #BC0100;
		color: #FFFFFF;
	}
	
	/* 内容 */
	.content{
		width: 690rpx;
		margin: auto;
		column-count: 2;
		overflow: hidden;
		column-gap: 8rpx;
	}
	.content-item{
		width: 340rpx;
		border-radius: 10rpx;
		overflow: hidden;
		background: #FFFFFF;
		margin-bottom: 14rpx;
		break-inside: avoid;
	}
	.content-item-img{
		width: 100%;
		display: block;
	}
	.content-bottom{
		padding: 18rpx;
	}
	.content-name{
		font-size: 10pt;
		font-weight: 700;
		color: #282828;
		margin-bottom: 20rpx;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
	}
	.content-operat{
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.content-common{
		display: flex;
		align-items: center;
		font-size: 8pt;
		color: #000000;
	}
	.avatar{
		width: 36rpx;
		height: 36rpx;
		border-radius: 50%;
		margin-right: 8rpx;
	}
	.like-icon{
		margin-right: 6rpx;
		font-size: 30rpx;
		line-height: 30rpx;
	}
	.like-icon-avtive{
		color: #BC0100;
		font-size: 34rpx;
	}
	.like-num{
		font-size: 9pt;
		letter-spacing: 2rpx;
	}
	
	/* 打开相册悬浮按钮 */
	.open-album{
		position: fixed;
		top: 80%;
		right: 20rpx;
		background: #BC0100;
		border-radius: 50%;
		width: 78rpx;
		height: 78rpx;
		z-index: 999;
		color: #FFFFFF;
		text-align: center;
		line-height: 82rpx;
	}
	.open-icon{
		font-size: 50rpx;
	}
	
	/* 隐藏滚动条 */
	scroll-view ::-webkit-scrollbar {
		width: 0;
		height: 0;
		background-color: transparent;
	}
</style>
