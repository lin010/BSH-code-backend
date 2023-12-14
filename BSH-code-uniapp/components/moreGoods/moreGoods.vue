<template>
	<view class="root flex" :style="{'padding':'0px'+px(20)}">
		<view class="select3" v-for="(item,index) in productData" @tap="navTo(item.id)">
			<view class="select1_box select3_box" @tap="navTo(item.id)" :style="{'border':displayStyle == 2 || displayStyle == 4?'1px solid #E0E0E0':''}">
				<image class="select3_proImg" :src="item.pic_url" mode="widthFix"></image>
				<view class="select1_proDetails">
					<view class="select2_proName" v-if="productNameBool">{{item.productName}}</view>
					<view class="select1_price flex" :class="displayStyle ==3 || displayStyle ==4?'flex-x-center':'flex-x-between'">
						<view v-if="priceBool">&yen;{{item.price}}</view>
						<view v-else></view>
						<image @tap.stop="addCart" v-if="buyBtnStyle === 1" class="select1_buyBtn1" :class="buyBtn()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/cat.png" mode=""></image>
						<image @tap.stop="addCart" v-if="buyBtnStyle === 2" class="select1_buyBtn1" :class="buyBtn()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/add.png" mode=""></image>
						<view @tap.stop="addCart" v-if="buyBtnStyle === 3" class="select1_buyBtn2" :class="buyBtn()?'':'hide'">购买</view>
						<view @tap.stop="addCart" v-if="buyBtnStyle === 4" class="select1_buyBtn3" :class="buyBtn()?'':'hide'">购买</view>
						<view @tap.stop="addCart" v-if="buyBtnStyle === 5" class="select1_buyBtn4" :class="buyBtn()?'':'hide'">购买</view>
						<view @tap.stop="addCart" v-if="buyBtnStyle === 6" class="select1_buyBtn5" :class="buyBtn()?'':'hide'">购买</view>
					</view>
				</view>
				<!-- 角标 -->
				<image class="subscript" v-if="subscriptStyle === 1" :class="subscript()?'':'hide'" :src="img_url+'subImg/hot.png'" mode=""></image>
				<image class="subscript" v-if="subscriptStyle === 2" :class="subscript()?'':'hide'" :src="img_url+'subImg/new.png'" mode=""></image>
				<image class="subscript" v-if="subscriptStyle === 3" :class="subscript()?'':'hide'" :src="img_url+'subImg/sale.png'" mode=""></image>
				<image class="subscript" v-if="subscriptStyle === 4" :class="subscript()?'':'hide'" :src="img_url+'subImg/recommend.png'" mode=""></image>
				<image class="subscript" v-if="subscriptStyle === 5" :class="subscript()?'':'hide'" :src="subscriptIcon"></image>
			</view>
		</view>
		<!-- 加载更多 -->
		<main-loadmore :visible="visible"></main-loadmore>
		<!-- 加载更多 -->
	</view>
</template>

<script>
	export default{
		name:'commodity',
		components:{
		},
		data(){
			return{
				img_url: this.$api.img_url,
				visible:false,
				requesting:false,
				productData:[{
						id: 1,
						pic_url: 'http://img.alicdn.com/imgextra/i1/742634735/TB2.peMXwCN.eBjSZFoXXXj0FXa_!!742634735.jpg',
						url: '/pages/goods/detail',
						productName: '梅湾街春装新款百搭修身高领打底衫女长袖针织衫套头毛衣女羊毛衫',
						price: 139
					},
					{
						id: 2,
						pic_url: 'http://img.alicdn.com/imgextra/i3/112394247/O1CN01VvYEyA1hFAakp4yWf_!!0-item_pic.jpg',
						url: '/pages/search/search',
						productName: '直筒牛仔裤女2019春季新款高腰宽松短裤毛边不规则热裤太平鸟女装',
						price: 329
					},
					{
						id: 3,
						pic_url: 'http://img.alicdn.com/imgextra/i4/263817957/O1CN01UqnchQ28eLzBYpy1L_!!263817957.jpg',
						url: '/pages/search/search',
						productName: '韩都衣舍2019韩版女装夏装新款宽松显瘦毛边chic牛仔短裤HO8250魭',
						price: 106
					},
					{
						id: 4,
						pic_url: 'http://img.alicdn.com/imgextra/i4/2142747419/O1CN01FWniwm24fwucTTgIY_!!2142747419.jpg',
						url: '/pages/search/search',
						productName: '南极人被子冬被加厚保暖春秋冬季被芯双人太空调被单人冬天棉被褥',
						price: 98
					},
					{
						id: 5,
						pic_url: 'http://store.hzstorm.com/web/uploads/image/store_1/c3adeebe55e5ff989c6570e6de391464774d670f.jpg',
						url: '/pages/search/search',
						productName: '网红同款灰色牛仔裤女春秋2019高腰显瘦九分小脚裤',
						price: 80
					},
					{
						id: 6,
						pic_url: 'http://store.hzstorm.com/web/uploads/image/store_1/07cd6e2851b960a2738835d2effb4f4c5c37f533.jpg',
						url: '/pages/search/search',
						productName: '2019早春复古豹纹漏肩上衣韩版半身裙两件套',
						price: 280
					}
				],
			}
		},
		props:{
			displayStyle:{
				type:Number,
				default:1
			},
			productNameBool:{
				type:Boolean,
				default:true
			},
			priceBool:{
				type:Boolean,
				default:true
			},
			buyBtnBool:{
				type:Boolean,
				default:true
			},
			buyBtnStyle:{
				type:Number,
				default:1
			},
			subscriptStyle:{
				type:Number,
				default:1
			},
			subscriptIcon:{
				type:String,
				default:''
			},
			subscriptBool:{
				type:Boolean,
				default:false
			},
			page:{
				type:Number,
				default:1
			}
		},
		watch:{
			page(){
				this.visible = true;
				if(!this.requesting){
					this.requesting = true;
					this.getData();
				}
			}
		},
		methods:{
			getData(){
				setTimeout(()=>{
					this.visible=false;
					this.requesting=false;
					this.productData = this.productData.concat(this.productData);
				},2000)
			},
			//页面跳转
			navTo(id){
				uni.navigateTo({
					url:'/pages/goods/detail'
				})
			},
			//判断购物车按钮是否存在
			buyBtn(){
				if(this.buyBtnBool){
					if(this.displayStyle ==1 || this.displayStyle ==2){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			},
			//判断角标是否存在
			subscript(){
				if(this.subscriptBool){
					return true;
				}else{
					return false;
				}
			},
			addCart(){
				console.log(123);
			},
			px(num){
				return uni.upx2px(num) + 'px';
			},
		}
	}
</script>

<style scoped>
	.root{
		/* padding: 0px 20rpx; */
		/* background: white; */
		flex-wrap: wrap;
		justify-content: space-between;
	}
	/* 样式一 */
	.select1_box{
		background: white;
		border-radius: 10rpx;
		margin-bottom: 40rpx;
		position: relative;
		overflow: hidden;
	}
	.select1_proDetails{
		padding: 24rpx;
		box-sizing: border-box;
	}
	.select1_proImg{
		width: 100%;
		max-height: 712rpx;
	}
	.select3_proImg{
		width: 100%;
		max-height: 348rpx;
	}
	.select1_proName{
		overflow: hidden;
		text-overflow:ellipsis;
		white-space: nowrap;
		font-size: 28rpx;
		margin-bottom: 24rpx;
	}
	.select1_price{
		color: #bc0100;
		font-size: 32rpx;
	}
	
	/* 购物车按钮样式 */
	.select1_buyBtn1{
		width: 50rpx;
		height: 50rpx;
	}
	.select1_buyBtn2{
		padding: 6rpx 30rpx;
		border-radius: 50rpx;
		background-color: #bc0100;
		color: #fff;
		max-width: 200rpx;
		font-size: 26rpx;
	}
	.select1_buyBtn3{
		padding: 4rpx 30rpx;
		border: 1px solid #bc0100;
		border-radius: 4rpx;
		background-color: #fff;
		color: #bc0100;
		max-width: 200rpx;
	}
	.select1_buyBtn4{
		padding: 4rpx 30rpx;
		border: 1px solid #bc0100;
		border-radius: 50rpx;
		background-color: #fff;
		color: #bc0100;
		max-width: 200rpx;
	}
	.select1_buyBtn5{
		padding: 4rpx 30rpx;
		border-radius: 4rpx;
		background-color: #bc0100;
		color: #fff;
		max-width: 200rpx;
	}
	
	.select5_buyBtn2{
		padding: 4rpx 18rpx;
		border-radius: 50rpx;
		background-color: #bc0100;
		color: #fff;
		max-width: 200rpx;
		font-size: 24rpx;
	}
	.select5_buyBtn3{
		padding: 4rpx 18rpx;
		border: 1px solid #bc0100;
		border-radius: 4rpx;
		background-color: #fff;
		color: #bc0100;
		max-width: 200rpx;
		font-size: 24rpx;
	}
	.select5_buyBtn4{
		padding: 4rpx 18rpx;
		border: 1px solid #bc0100;
		border-radius: 50rpx;
		background-color: #fff;
		color: #bc0100;
		max-width: 200rpx;
		font-size: 24rpx;
	}
	.select5_buyBtn5{
		padding: 4rpx 18rpx;
		border-radius: 4rpx;
		background-color: #bc0100;
		color: #fff;
		max-width: 200rpx;
		font-size: 24rpx;
	}
	/* 购物车按钮样式 */
	
	.subscript{
		position: absolute;
		top: 0px;
		left: 0px;
		width: 62rpx;
		height: 36rpx;
	}
	
	/* 样式二 */
	.select2_box{
		position: relative;
		margin-bottom: 40rpx;
		border-bottom: 1px solid #EEEEEE;
	}
	.select2_proImg{
		width: 200rpx;
		max-height: 200rpx;
	}
	.select2_proDetails{
		padding: 14rpx 24rpx 20rpx;
		flex:1;
		justify-content: space-between;
	}
	.select2_proName{
		font-size: 28rpx;
		margin-bottom: 20rpx;
		text-overflow: -o-ellipsis-lastline;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
	}
	
	/* 样式三 */
	.select3{
		width: 348rpx;
		/* margin-right: 14rpx; */
		box-sizing: border-box;
	}
	.root .select3:nth-of-type(2n){
		margin-right: 0px;
	}
	.select3_box{
		margin-bottom: 20rpx;
	}
	
	/* 样式四 */
	.select4{
		width: 33.3%;
		padding: 0px 10rpx;
		box-sizing: border-box;
	}
	.select4_proImg{
		width: 100%;
		max-height: 220rpx;
	}
	
	/* 样式五 */
	/* .scroll-view_H{
		white-space: nowrap;
	}
	.scroll-view_H ::-webkit-scrollbar {
		width: 0;
		height: 0;
		background-color: transparent;
	} */
	.select5_box{
		background: white;
		border-radius: 10rpx;
		position: relative;
		overflow: hidden;
	}
	.select5{
		width: 33.3%;
		margin-right: 20rpx;
		box-sizing: border-box;
		display: inline-block;
	}
	.select5_proDetails{
		padding: 16rpx;
		box-sizing: border-box;
	}
	.select5_proName{
		font-size: 28rpx;
		margin-bottom: 20rpx;
		white-space: normal;
		text-overflow: -o-ellipsis-lastline;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
	}
	
	.flex-x-between{
		justify-content: space-between;
	}
	.hide{
		display: none;
	}
</style>
