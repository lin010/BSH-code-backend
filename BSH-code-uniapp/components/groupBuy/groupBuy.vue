<template>
	<view class="groupRoot" :class="listStyle == 3 || listStyle == 4 ?'flex':''" 
	:style="{'padding':listStyle == 3 || listStyle == 4 ?'0px '+px(20):''}">
		<view class="select1" v-if="listStyle == 1" v-for="(item,index) in productData" :key='index' @tap="navTo(item.id)">
			<view class="select1_box" :style="{'border':displayStyle == 2 || displayStyle == 4?'1px solid #E0E0E0':''}">
				<image class="select1_proImg" :src="item.cover_pic" mode="widthFix"></image>
				<view class="select1_proDetails">
					<view class="select1_proName" v-if="productNameBool">{{item.productName}}</view>
					<view class="select1_price flex" :class="displayStyle ==3 || displayStyle ==4?'flex-x-center':'flex-x-between'">
						<view v-if="priceBool">
							<text class="select1_propleNums">2人团</text>&yen;
							<text class="select1_groupPrice">{{item.price}}</text>
							<text class="select1_original_price">&yen;{{item.original_price}}</text>
						</view>
						<view v-else></view>
						<view @tap.stop="addCart" v-if="buyBtnStyle === 3" class="select1_buyBtn2" :class="buyBtn()?'':'hide'">去拼团</view>
						<view @tap.stop="addCart" v-if="buyBtnStyle === 4" class="select1_buyBtn3" :class="buyBtn()?'':'hide'">去拼团</view>
						<view @tap.stop="addCart" v-if="buyBtnStyle === 5" class="select1_buyBtn4" :class="buyBtn()?'':'hide'">去拼团</view>
						<view @tap.stop="addCart" v-if="buyBtnStyle === 6" class="select1_buyBtn5" :class="buyBtn()?'':'hide'">去拼团</view>
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
		
		<view class="select2" v-if="listStyle == 2" v-for="(item,index) in productData" @tap="navTo(item.id)">
			<view class="select2_box" :style="{'border':displayStyle == 2 ?'1px solid #eeeeee':''}">
				<view class="flex">
					<image class="select2_proImg" :src="item.cover_pic" mode="widthFix"></image>
					<view class="select2_proDetails flex-col">
						<view class="select2_proName" v-if="productNameBool">{{item.productName}}</view>
						<view class="select1_price flex flex-y-bottom" :class="displayStyle ==3 || displayStyle ==4?'flex-x-center':'flex-x-between'">
							<view v-if="priceBool">
								<view><text class="select2_propleNums">{{item.g_peopleNums}}人团&yen;</text>{{item.price}}</view>
								<view class="select2_original_price">单买价：&yen;{{item.original_price}}</view>
							</view>
							<view v-else></view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 3" class="select2_buyBtn2" :class="buyBtn()?'':'hide'">去拼团</view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 4" class="select1_buyBtn3" :class="buyBtn()?'':'hide'">去拼团</view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 5" class="select1_buyBtn4" :class="buyBtn()?'':'hide'">去拼团</view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 6" class="select1_buyBtn5" :class="buyBtn()?'':'hide'">去拼团</view>
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
		</view>
		
		<view class="select3" v-if="listStyle == 3" v-for="(item,index) in productData">
			<view class="select1_box select3_box" @tap="navTo(item.id)" :style="{'border':displayStyle == 2 || displayStyle == 4?'1px solid #E0E0E0':''}">
				<image class="select3_proImg" :src="item.cover_pic" mode="widthFix"></image>
				<view class="select1_proDetails">
					<view class="select2_proName" v-if="productNameBool">{{item.productName}}</view>
					<view class="select1_price flex" :class="displayStyle ==3 || displayStyle ==4?'flex-x-center':'flex-x-between'">
						<view v-if="priceBool">
							<view>
								<text class="select3_group_people">2人团&yen;</text>
								<text class="select3_group_price">{{item.price}}</text>
							</view>
							<view class="select3_original_price">单买价:{{item.original_price}}</view>
						</view>
						<view v-else></view>
						<!-- <image @tap.stop="addCart" v-if="buyBtnStyle === 1" class="select1_buyBtn1" :class="buyBtn()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/cat.png" mode=""></image>
						<image @tap.stop="addCart" v-if="buyBtnStyle === 2" class="select1_buyBtn1" :class="buyBtn()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/add.png" mode=""></image>
						<view @tap.stop="addCart" v-if="buyBtnStyle === 3" class="select2_buyBtn2" :class="buyBtn()?'':'hide'">购买</view>
						<view @tap.stop="addCart" v-if="buyBtnStyle === 4" class="select1_buyBtn3" :class="buyBtn()?'':'hide'">购买</view>
						<view @tap.stop="addCart" v-if="buyBtnStyle === 5" class="select1_buyBtn4" :class="buyBtn()?'':'hide'">购买</view>
						<view @tap.stop="addCart" v-if="buyBtnStyle === 6" class="select1_buyBtn5" :class="buyBtn()?'':'hide'">购买</view> -->
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
	</view>
</template>

<script>
	export default{
		name:'commodity',
		props:{
			productData:{
				type:Array,
				default:()=>[]
			},
			listStyle:{
				type:Number,
				default:1
			},
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
		},
		data(){
			return{
				img_url: this.$api.img_url,
			}
		},
		methods:{
			//页面跳转
			navTo(id){
				console.log('/pages/group/good&id='+id);
				uni.navigateTo({
					url:'/pages/group/good'
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
				this.$http.toast('已加入购物车')
			},
			px(num){
				return uni.upx2px(num) + 'px';
			},
		}
	}
</script>

<style>
	.groupRoot{
		padding: 0px 20rpx;
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
	.select1_propleNums{
		font-size: 28rpx;
		margin-right: 6rpx;
	}
	.select1_groupPrice{
		font-size: 45rpx;
		margin-left: 8rpx;
	}
	.select1_original_price{
		color: #aaaaaa;
		text-decoration: line-through;
		font-size: 28rpx;
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
		font-size: 32rpx;
	}
	.select2_buyBtn2{
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
	.select2_propleNums{
		font-size: 26rpx;
	}
	.select2_original_price{
		color: rgb(170, 170, 170);
		font-size: 24rpx;
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
	.select3_original_price{
		color: #aaaaaa;
		font-size: 28rpx;
	}
	.select3_group_people{
		font-size: 28rpx;
	}
	.select3_group_price{
		font-size: 38rpx;
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
	.scroll-view_H{
		white-space: nowrap;
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
