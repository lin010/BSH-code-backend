<template>
	<view class="root" :class="listStyle == 2 || listStyle == 3 ?'flex':''" :style="{background: keyValue == 'cart' ? 'transparent' : '#ffffff'}">
		<view class="select1" v-if="listStyle == 1" v-for="(item,index) in productData" :key='index' @tap.stop="navTo(item.id)">
			<view class="select1_box" :style="{'border':displayStyle == 2 || displayStyle == 4?'1px solid #E0E0E0':''}">
				<image class="select1_proImg" :src="item.cover_pic" mode="widthFix"></image>
				<view class="select1_proDetails">
					<view class="select1_proName" v-if="showGoodsName == '1'">{{item.name}}</view>
					<view class="select1_price flex flex-y-center" :class="displayStyle ==3 || displayStyle ==4?'flex-x-center':'flex-x-between'">
						<view>
							<view v-if="showGoodsOriginalPrice" class="select1_original_price">{{originalPriceLabel}} &yen;{{item.original_price}}</view>
							<view v-if="showGoodsPrice == '1'" :style="{color:textColor}">{{priceLabel}} &yen;{{item.price}}</view>
							<view v-if="showGoodsLevelPrice == '1'" class="select1_level_price">{{levelPriceLabel}} &yen;{{item.level_price}}</view>
						</view>
						
						<!-- <view v-else></view> -->
						<view v-if="buyBtns == 'pic'">
							<image class="select1_buyBtn1" :class="buyBtn()?'':'hide'" :src="buyBtnPic"
							 mode=""></image>
						</view>
						<view v-else>
							<view v-if="buyBtnStyle == 1" class="select1_buyBtn2" :class="buyBtn()?'':'hide'" :style="{color: '#ffffff',background:buttonColor}">{{buyBtnText}}</view>
							<view v-if="buyBtnStyle == 2" class="select1_buyBtn3" :class="buyBtn()?'':'hide'" :style="{color: buttonColor,border:`1px solid ${buttonColor}`}">{{buyBtnText}}</view>
							<view v-if="buyBtnStyle == 3" class="select1_buyBtn5" :class="buyBtn()?'':'hide'" :style="{color: '#ffffff',background:buttonColor}">{{buyBtnText}}</view>
							<view v-if="buyBtnStyle == 4" class="select1_buyBtn4" :class="buyBtn()?'':'hide'" :style="{color: buttonColor,border:`1px solid ${buttonColor}`}">{{buyBtnText}}</view>
						</view>
					</view>
				</view>
				<!-- 角标 -->
				<image class="subscript" v-if="subscriptIcon" :class="subscript()?'':'hide'" :src="subscriptIcon" mode="widthFix"></image>
			</view>
		</view>

		<view class="select2" v-if="listStyle == -1" v-for="(item,index) in productData" :key='index' @tap.stop="navTo(item.id)">
			<view class="select2_box" :style="{'border':displayStyle == 2 ?'1px solid #eeeeee':''}">
				<view class="flex">
					<image class="select2_proImg" :src="item.cover_pic" mode="scaleToFill"></image>
					<view class="select2_proDetails flex-col">
						<view class="select2_proName" v-if="showGoodsName == '1'">{{item.name}}</view>
						<view v-else></view>
						<view class="select1_price flex flex-y-center" :class="displayStyle ==3 || displayStyle ==4?'flex-x-center':'flex-x-between'">
							<view>
								<view v-if="showGoodsOriginalPrice" class="select1_original_price">{{originalPriceLabel}} &yen;{{item.original_price}}</view>
								<view v-if="showGoodsPrice == '1'" :style="{color:textColor}">{{priceLabel}} &yen;{{item.price}}</view>
								<view v-if="showGoodsLevelPrice == '1'" class="select1_level_price">{{levelPriceLabel}} &yen;{{item.level_price}}</view>
							</view>
							<view v-if="buyBtns == 'pic'">
								<image class="select1_buyBtn1" :class="buyBtn()?'':'hide'" :src="buyBtnPic"
								 mode=""></image>
							</view>
							<view v-else>
								<view v-if="buyBtnStyle == 1" class="select1_buyBtn2" :class="buyBtn()?'':'hide'" :style="{color: '#ffffff',background:buttonColor}">{{buyBtnText}}</view>
								<view v-if="buyBtnStyle == 2" class="select1_buyBtn3" :class="buyBtn()?'':'hide'" :style="{color: buttonColor,border:`1px solid ${buttonColor}`}">{{buyBtnText}}</view>
								<view v-if="buyBtnStyle == 3" class="select1_buyBtn5" :class="buyBtn()?'':'hide'" :style="{color: '#ffffff',background:buttonColor}">{{buyBtnText}}</view>
								<view v-if="buyBtnStyle == 4" class="select1_buyBtn4" :class="buyBtn()?'':'hide'" :style="{color: buttonColor,border:`1px solid ${buttonColor}`}">{{buyBtnText}}</view>
							</view>
						</view>
					</view>
					<!-- 角标 -->
					<image class="subscript" v-if="subscriptIcon" :class="subscript()?'':'hide'" :src="subscriptIcon" mode="widthFix"></image>
				</view>
			</view>
		</view>

		<view class="select3" v-if="listStyle == 2" v-for="(item,index) in productData" :key='index' @tap.stop="navTo(item.id)">
			<view class="select1_box select3_box" :style="{'border':displayStyle == 2 || displayStyle == 4?'1px solid #efefef':''}">
				<image class="select3_proImg" :src="item.cover_pic" mode="aspectFill"></image>
				<view class="select1_proDetails">
					<view class="select2_proName" v-if="showGoodsName == '1'">{{item.name}}</view>
					<view class="select1_price flex flex-y-center" :class="displayStyle ==3 || displayStyle ==4?'flex-x-center':'flex-x-between'">
						<view v-if="!styleShow&&!styleShowBUY">
							<view v-if="showGoodsOriginalPrice" class="select1_original_price">{{originalPriceLabel}} &yen;{{item.original_price}}</view>
							<view v-if="showGoodsPrice == '1'" :style="{color:'#FF7104'}">{{priceLabel}} &yen;{{item.price}}</view>
							<view v-if="showGoodsLevelPrice == '1'" class="select1_level_price">{{levelPriceLabel}} &yen;{{item.level_price}}</view>
						</view>
						<view v-if="styleShow">
							<view v-if="showGoodsOriginalPrice" class="select1_original_price">
							{{originalPriceLabel}} &yen;{{item.original_price}}
							</view>
							<view v-if="showGoodsPrice == '1'" :style="{color:'#FF7104'}">
							{{priceLabel}}&nbsp;{{item.price}}
							<text style="font-size: 25rpx;color: red;transform: scale(0.83);display: inline-block;">{{currencyAlias.red_envelope_alias}}</text>
							</view>
							<view v-if="showGoodsLevelPrice == '1'" class="select1_level_price">
							{{levelPriceLabel}}&nbsp;{{item.level_price}}
							<text style="font-size: 25rpx;color: red;margin-left: 10rpx;">{{currencyAlias.red_envelope_alias}}</text>
							</view>
						</view>
						<view v-if="styleShowBUY">
							<view v-if="showGoodsOriginalPrice" class="select1_original_price">{{originalPriceLabel}} &yen;{{item.original_price}}</view>
							<view v-if="showGoodsPrice == '1'" :style="{color:'#FF7104'}">{{priceLabel}} &yen;{{item.price}}</view>
							<view v-if="showGoodsLevelPrice == '1'" class="select1_level_price">{{levelPriceLabel}} &yen;{{item.level_price}}</view>
							<view style="width: 100%;margin: 10rpx 20rpx;">
								<text style="display: block;width: 220rpx;height: 60rpx;background: rgb(255,40,38);text-align: center;line-height: 60rpx;color: #fff;
								border-radius: 30rpx;margin:  auto;">{{currencyAlias.silver_beans_alias}}兑换</text>
							</view>
						</view>				
						<view v-if="buyBtns == 'pic'&&!styleShowBUY">
							<image class="select1_buyBtn1" :class="buyBtn()?'':'hide'" :src="buyBtnPic"
							 mode=""></image>
						</view>
						<view v-if="buyBtns != 'pic'">
							<view v-if="buyBtnStyle == 1" class="select1_buyBtn2" :class="buyBtn()?'':'hide'" :style="{color: '#ffffff',background:buttonColor}">{{buyBtnText}}</view>
							<view v-if="buyBtnStyle == 2" class="select1_buyBtn3" :class="buyBtn()?'':'hide'" :style="{color: buttonColor,border:`1px solid ${buttonColor}`}">{{buyBtnText}}</view>
							<view v-if="buyBtnStyle == 3" class="select1_buyBtn5" :class="buyBtn()?'':'hide'" :style="{color: '#ffffff',background:buttonColor}">{{buyBtnText}}</view>
							<view v-if="buyBtnStyle == 4" class="select1_buyBtn4" :class="buyBtn()?'':'hide'" :style="{color: buttonColor,border:`1px solid ${buttonColor}`}">{{buyBtnText}}</view>
						</view>
					</view>
				</view>
				<!-- 角标 -->
				<image class="subscript" v-if="subscriptIcon" :class="subscript()?'':'hide'" :src="subscriptIcon" mode="widthFix"></image>
			</view>
		</view>

		<view class="select4" v-if="listStyle == 3" v-for="(item,index) in productData" :key='index' @tap.stop="navTo(item.id)">
			<view class="select1_box" :style="{'border':displayStyle == 2 || displayStyle == 4?'1px solid #E0E0E0':''}">
				<image class="select4_proImg" :src="item.cover_pic" mode="scaleToFill"></image>
				<view class="select1_proDetails">
					<view class="select2_proName" v-if="showGoodsName == '1'">{{item.name}}</view>
					<view class="select1_price flex flex-y-center" style="font-size: 28rpx;" :class="displayStyle ==3 || displayStyle ==4?'flex-x-center':'flex-x-between'">
						<view>
							<view v-if="showGoodsOriginalPrice" class="select1_original_price">{{originalPriceLabel}} &yen;{{item.original_price}}</view>
							<view class="pic" v-if="showGoodsPrice == '1'" :style="{color:textColor}">{{priceLabel}} &yen;{{item.price}}</view>
							<view v-if="showGoodsLevelPrice == '1'" class="select1_level_price">{{levelPriceLabel}} &yen;{{item.level_price}}</view>
						</view>
						<view v-if="buyBtns == 'pic'">
							<image class="select1_buyBtn1" :class="buyBtn()?'':'hide'" :src="buyBtnPic"
							 mode=""></image>
						</view>
						<view v-else>
							<view v-if="buyBtnStyle == 1" class="select1_buyBtn2" :class="buyBtn()?'':'hide'" :style="{color: '#ffffff',background:buttonColor}">{{buyBtnText}}</view>
							<view v-if="buyBtnStyle == 2" class="select1_buyBtn3" :class="buyBtn()?'':'hide'" :style="{color: buttonColor,border:`1px solid ${buttonColor}`}">{{buyBtnText}}</view>
							<view v-if="buyBtnStyle == 3" class="select1_buyBtn5" :class="buyBtn()?'':'hide'" :style="{color: '#ffffff',background:buttonColor}">{{buyBtnText}}</view>
							<view v-if="buyBtnStyle == 4" class="select1_buyBtn4" :class="buyBtn()?'':'hide'" :style="{color: buttonColor,border:`1px solid ${buttonColor}`}">{{buyBtnText}}</view>
						</view>
					</view>
				</view>
				<!-- 角标 -->
				<image class="subscript" v-if="subscriptIcon" :class="subscript()?'':'hide'" :src="subscriptIcon" mode="widthFix"></image>
			</view>
		</view>
		<view class="select4" v-if="listStyle == 3 && productData.length%2 == 1"></view>

		<scroll-view class="scroll-view_H" scroll-x="true">
			<view class="select5" v-if="listStyle == 0" v-for="(item,index) in productData" :key='index'>
				<view class="select5_box" @tap.stop="navTo(item.id)" :style="{'border':displayStyle == 2 || displayStyle == 4?'1px solid #E0E0E0':''}">
					<image class="select4_proImg" :src="item.cover_pic" mode="scaleToFill"></image>
					<view class="select5_proDetails">
						<view class="select5_proName" v-if="showGoodsName == '1'">{{item.name}}</view>
						<view class="select1_price flex flex-y-center" :class="displayStyle ==3 || displayStyle ==4?'flex-x-center':'flex-x-between'">
							<view v-if="showGoodsOriginalPrice" class="select1_original_price">{{originalPriceLabel}} &yen;{{item.original_price}}</view>
							<view v-if="showGoodsPrice == '1'" :style="{color:textColor}">{{priceLabel}} &yen;{{item.price}}</view>
							<view v-if="showGoodsLevelPrice == '1'" class="select1_level_price">{{levelPriceLabel}} &yen;{{item.level_price}}</view>
						</view>
					</view>
					<!-- 角标 -->
					<image class="subscript" v-if="subscriptIcon" :class="subscript()?'':'hide'" :src="subscriptIcon" mode="widthFix"></image>
				</view>
			</view>
		</scroll-view>

	</view>
</template>

<script>
	export default {
		name: 'commodity',
		props: {
			keyValue: {
				type: String,
				default: ''
			},
			productData: {
				type: Array,
				default: () => []
			},
			// 1为一行一个 -1为列表模式 2为一行两个 3一行三个 0为左右滑动
			listStyle: {
				type: Number,
				default: 1
			},
			// 1为常规 2为边框 3为居中显示 4为边框居中
			displayStyle: {
				type: Number,
				default: 1
			},
			// 是否显示商品名，1为显示，0为不显示
			showGoodsName: {
				type: String,
				default: '1'
			},
			// 是否显示商品市场价格，1为显示，0为不显示
			showGoodsOriginalPrice: {
				type: Boolean,
				default: false
			},
			// 商品市场价格名称
			originalPriceLabel: {
				type: String,
				default: ''
			},
			// 是否显示商品官方价格，1为显示，0为不显示
			showGoodsPrice: {
				type: String,
				default: '1'
			},
			// 商品价格名称
			priceLabel: {
				type: String,
				default: ''
			},
			// 是否显示商品会员价格，1为显示，0为不显示
			showGoodsLevelPrice: {
				type: String,
				default: '0'
			},
			// 商品会员价格名称
			levelPriceLabel: {
				type: String,
				default: ''
			},
			// 是否显示购物车按钮，1为显示，0为不显示
			showBuyBtn: {
				type: Boolean,
				default: true
			},
			buyBtnPic:{ //购物车按钮图片路径
				type: String,
				default: 'http://www.mingyuanriji.cn/web/statics/img/mall/goods/cart_active.png'
			},
			buyBtns:{ //购物车按钮样式类型
				type: String,
				default: 'pic'
			},
			// 按钮颜色
			buyBtnColor: {
				type: String,
				default: '#bc0100'
			},
			// 购物车按钮样式。1为购物车 2为加号 3为文字样式1 4为文字样式2 5为文字样式3 6为文字样式4
			buyBtnStyle: {
				type: Number,
				default: 1
			},
			// 角标为自定义的时候才需要传
			subscriptIcon: {
				type: String,
				default: ''
			},
			// 是否显示角标，1为显示，0为不显示
			showGoodsTag: {
				type: String,
				default: '1'
			},
			buyBtnText: { //购买按钮文字
				type: String,
				default: '购买'
			},
			buttonColor:{
				type:String,
				default:'#bc0100'
			},
			styleTitle:{
				type:String,
			}
		},
		data(){
			return{
				textColor:'#bc0100',
				styleShow:false,
				styleShowBUY:false,
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			}
		},
		created() {
			if(this.styleTitle){
				if(this.styleTitle.indexOf(this.currencyAlias.red_envelope_alias+'兑换专区')!=-1 ){
					this.styleShow=true
				}else{
					this.styleShow=false
				}
			}
			if(this.styleTitle){
				if(this.styleTitle.indexOf(thissilver_beans_alias+'兑换区')!=-1 ){
					this.styleShowBUY=true
				}else{
					this.styleShowBUY=false
				}
			}
		},
		mounted() {
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
		},
		onLoad(options) {
			console.log(this.$route.query);
		},
		methods: {
			//页面跳转
			navTo(id) {
				//获取传递过来的参数
				//#ifdef H5
				var nav_id = this.$route.query;
				//#endif
				
				uni.navigateTo({
					url: `/pages/goods/detail?proId=${id}`
				})
			},
			//判断购物车按钮是否存在
			buyBtn() {
				if (this.showBuyBtn == 1) {
					if (this.displayStyle == 1 || this.displayStyle == 2) {
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			},
			//判断角标是否存在
			subscript() {
				if (this.showGoodsTag == 1) {
					return true;
				} else {
					return false;
				}
			},
			px(num) {
				return uni.upx2px(num) + 'px';
			},

		}
	}
</script>

<style scoped>
	.pic {
		max-width: 55px;
		overflow: hidden;
		text-overflow: ellipsis;
		flex: 1;
	}

	.root {
		padding:20rpx;
		/* background: white; */
		flex-wrap: wrap;
		justify-content: space-between;
		background-color: #FFFFFF;
	}

	/* 样式一 */
	.select1_box {
		background: white;
		border-radius: 10rpx;
		margin-bottom: 20rpx;
		position: relative;
		overflow: hidden;
	}

	.select1_proDetails {
		padding: 24rpx;
		box-sizing: border-box;
	}

	.select1_proImg {
		width: 100%;
		max-height: 712rpx;
	}

	.select3_proImg {
		width: 100%;
		max-height: 348rpx;
	}

	.select1_proName {
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		font-size: 28rpx;
		margin-bottom: 24rpx;
	}

	.select1_price {
		font-size: 28rpx;
	}
	.select1_original_price{
		font-size:28rpx;
		color: #c0c0c0;
	}
	.select1_level_price {
		font-size: 24rpx;
		color: red;
	}

	/* 购物车按钮样式 */
	.select1_buyBtn1 {
		width: 44rpx;
		height: 44rpx;
		display: block;
	}

	.select1_buyBtn2 {
		padding: 6rpx 24rpx;
		background-color: #bc0100;
		color: #fff;
		max-width: 200rpx;
		font-size: 9pt;
	}

	.select1_buyBtn3 {
		padding: 4rpx 24rpx;
		border-radius: 4rpx;
		background-color: #fff;
		color: #bc0100;
		max-width: 200rpx;
		font-size: 9pt;
	}

	.select1_buyBtn4 {
		padding: 4rpx 24rpx;
		font-size: 9pt;
		border: 1px solid #bc0100;
		border-radius: 50rpx;
		background-color: #fff;
		color: #bc0100;
		max-width: 200rpx;
	}

	.select1_buyBtn5 {
		padding: 4rpx 30rpx;
		border-radius: 40rpx;
		font-size: 9pt;
		background-color: #bc0100;
		color: #fff;
		max-width: 200rpx;
	}

	.select5_buyBtn2 {
		padding: 4rpx 18rpx;
		border-radius: 50rpx;
		background-color: #bc0100;
		color: #fff;
		max-width: 200rpx;
		font-size: 24rpx;
	}

	.select5_buyBtn3 {
		padding: 4rpx 18rpx;
		border: 1px solid #bc0100;
		border-radius: 4rpx;
		background-color: #fff;
		color: #bc0100;
		max-width: 200rpx;
		font-size: 24rpx;
	}

	.select5_buyBtn4 {
		padding: 4rpx 18rpx;
		border: 1px solid #bc0100;
		border-radius: 50rpx;
		background-color: #fff;
		color: #bc0100;
		max-width: 200rpx;
		font-size: 24rpx;
	}

	.select5_buyBtn5 {
		padding: 4rpx 18rpx;
		border-radius: 4rpx;
		background-color: #bc0100;
		color: #fff;
		max-width: 200rpx;
		font-size: 24rpx;
	}

	/* 购物车按钮样式 */

	.subscript {
		position: absolute;
		top: 0px;
		left: 0px;
		width: 62rpx;
		/* height: 36rpx; */
	}

	/* 样式二 */
	.select2_box {
		position: relative;
		margin: 20rpx 0;
		/* border-bottom: 1px solid #EEEEEE; */
	}

	.select2_proImg {
		width: 200rpx;
		max-height: 200rpx;
		border-radius: 10rpx;
	}

	.select2_proDetails {
		padding: 14rpx 24rpx 20rpx;
		flex: 1;
		justify-content: space-between;
	}

	.select2_proName {
		height: 84rpx;
		font-size: 28rpx;
		margin-bottom: 20rpx;
		text-overflow: -o-ellipsis-lastline;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		overflow: hidden;
		text-overflow: ellipsis;
		color: #000000;
	}

	/* 样式三 */
	.select3 {
		width: 49%;
		/* height: 580rpx;*/
		/* width: 348rpx; */
		/* margin-right: 14rpx; */
		box-sizing: border-box;
	}

	.root .select3:nth-of-type(2n) {
		margin-right: 0px;
	}

	.select3_box {
		margin-bottom: 20rpx;
	}

	/* 样式四 */
	.select4 {
		width: 33.3%;
		padding: 0px 10rpx;
		box-sizing: border-box;
	}

	.select4_proImg {
		width: 100%;
		height: 220rpx;
	}

	/* 样式五 */
	.scroll-view_H {
		white-space: nowrap;
	}

	/* .scroll-view_H ::-webkit-scrollbar {
		width: 0;
		height: 0;
		background-color: transparent;
	} */

	.select5_box {
		background: white;
		border-radius: 10rpx;
		position: relative;
		overflow: hidden;
	}

	.select5 {
		width: 33.3%;
		margin-right: 20rpx;
		box-sizing: border-box;
		display: inline-block;
		padding-top: 16rpx;
	}

	.select5_proDetails {
		padding: 16rpx 16rpx 8rpx;
		box-sizing: border-box;
	}

	.select5_proName {
		font-size: 28rpx;
		white-space: normal;
		text-overflow: -o-ellipsis-lastline;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		color: #000000;
		height: 84rpx;
	}

	.flex-x-between {
		justify-content: space-between;
	}
	.flex-y-center{
		align-items: center;
	}

	.hide {
		display: none;
	}
</style>
