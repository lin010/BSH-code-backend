<template>
	<view class="root" :class="listStyle == 3 || listStyle == 4 ?'flex':''" 
	:style="{'padding':listStyle == 3 || listStyle == 4 ?'0px '+px(10):''}">
		<view class="select1" v-if="listStyle == 1" v-for="(item,index) in productData">
			<view class="select1_box" :style="{'border':displayStyle == 2 || displayStyle == 4?'1px solid #E0E0E0':''}">
				<navigator :url="item.url">
					<view class="select1_proImg_box">
						<image class="select1_proImg" :src="item.pic_url" mode="widthFix"></image>
						<view class="select1_endTime flex flex-y-center" v-if="countdownBool">
							<text class="select1_endTime_left">秒杀</text>
							<text>距离结束仅剩:{{time}}</text>
						</view>
					</view>
					<view class="select1_proDetails">
						<view class="select1_proName" v-if="productNameBool">{{item.productName}}</view>
						<view class="select1_price flex" :class="displayStyle ==3 || displayStyle ==4?'flex-x-center':'flex-x-between'">
							<view v-if="priceBool">
								秒杀价:
								<text class="select1_secondsKill_price">&yen;{{item.original_price}}</text>
								<text class="select1_original_price" v-if="displayStyle == 1 || displayStyle == 2">&yen; {{item.secondsKill_price}}</text>
							</view>
							<view v-else></view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 1" class="select1_buyBtn2" :class="buyBtn()?'':'hide'">马上秒</view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 2" class="select1_buyBtn3" :class="buyBtn()?'':'hide'">马上秒</view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 3" class="select1_buyBtn4" :class="buyBtn()?'':'hide'">马上秒</view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 4" class="select1_buyBtn5" :class="buyBtn()?'':'hide'">马上秒</view>
						</view>
					</view>
					<!-- 角标 -->
					<image class="subscript" v-if="subscriptStyle === 1" :class="subscript()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/rx.png" mode=""></image>
					<image class="subscript" v-if="subscriptStyle === 2" :class="subscript()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/xp.png" mode=""></image>
					<image class="subscript" v-if="subscriptStyle === 3" :class="subscript()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/zk.png" mode=""></image>
					<image class="subscript" v-if="subscriptStyle === 4" :class="subscript()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/tj.png" mode=""></image>
					<image class="subscript" v-if="subscriptStyle === 5" :class="subscript()?'':'hide'" :src="subscriptIcon"></image>
				</navigator>
			</view>
		</view>
		
		<view class="select2" v-if="listStyle == 2" v-for="(item,index) in productData">
			<view class="select2_box" :style="{'border':displayStyle == 2 ?'1px solid #eeeeee':''}">
				<navigator :url="item.url" class="flex">
					<image class="select2_proImg" :src="item.pic_url" mode="widthFix"></image>
					<view class="select2_proDetails flex-col">
						<view class="select2_proName" v-if="productNameBool">{{item.productName}}</view>
						<view class="select2_endTime" v-if="countdownBool">距结束仅剩:<text style="color: #bc0100;">{{time}}</text></view>
						<view class="select1_price flex" :class="displayStyle ==3 || displayStyle ==4?'flex-x-center':'flex-x-between'">
							<view v-if="priceBool" class="select2_secondsKill_box">
								秒杀价:&yen;
								<text class="select2_secondsKill_price">{{item.original_price}}</text>
								<view class="select2_original_price" v-if="displayStyle == 1 || displayStyle == 2">
									售价:&yen; {{item.secondsKill_price}}
								</view>
							</view>
							<view v-else></view>
							<!-- <image @tap.stop="addCart" v-if="buyBtnStyle === 1" class="select1_buyBtn1" :class="buyBtn()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/cat.png" mode=""></image> -->
							<!-- <image @tap.stop="addCart" v-if="buyBtnStyle === 2" class="select1_buyBtn1" :class="buyBtn()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/add.png" mode=""></image> -->
							<view @tap.stop="addCart" v-if="buyBtnStyle === 1" class="select1_buyBtn2 select2_buyBtn2" :class="buyBtn()?'':'hide'">马上秒</view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 2" class="select1_buyBtn3 select2_buyBtn2" :class="buyBtn()?'':'hide'">马上秒</view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 3" class="select1_buyBtn4 select2_buyBtn2" :class="buyBtn()?'':'hide'">马上秒</view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 4" class="select1_buyBtn5 select2_buyBtn2" :class="buyBtn()?'':'hide'">马上秒</view>
						</view>
					</view>
					
					<!-- 角标 -->
					<image class="subscript" v-if="subscriptStyle === 1" :class="subscript()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/rx.png" mode=""></image>
					<image class="subscript" v-if="subscriptStyle === 2" :class="subscript()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/xp.png" mode=""></image>
					<image class="subscript" v-if="subscriptStyle === 3" :class="subscript()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/zk.png" mode=""></image>
					<image class="subscript" v-if="subscriptStyle === 4" :class="subscript()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/tj.png" mode=""></image>
					<image class="subscript" v-if="subscriptStyle === 5" :class="subscript()?'':'hide'" :src="subscriptIcon"></image>
				</navigator>
			</view>
		</view>
		
		<view class="select3" v-if="listStyle == 3" v-for="(item,index) in productData">
			<view class="select1_box" :style="{'border':displayStyle == 2 || displayStyle == 4?'1px solid #E0E0E0':''}">
				<navigator :url="item.url">
					<view class="select1_proImg_box">
						<image class="select1_proImg" :src="item.pic_url" mode="widthFix"></image>
						<view class="select3_endTime flex flex-y-center flex-x-center" v-if="countdownBool">
							<text>距结束仅剩:{{time}}</text>
						</view>
					</view>
					<view class="select1_proDetails">
						<view class="select3_proName" v-if="productNameBool">{{item.productName}}</view>
						<view class="select1_price flex" :class="displayStyle ==3 || displayStyle ==4?'flex-x-center':'flex-x-between'">
							<view v-if="priceBool">
								秒杀价:&yen;
								<text class="select1_secondsKill_price">{{item.original_price}}</text>
								<view class="select2_original_price" :style="{'text-align':displayStyle == 3 || displayStyle == 4?'center':''}">
									售价: <text style="text-decoration: line-through;">&yen; {{item.secondsKill_price}}</text>
								</view>
							</view>
							<view v-else></view>
							<!-- <image @tap.stop="addCart" v-if="buyBtnStyle === 1" class="select1_buyBtn1" :class="buyBtn()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/cat.png" mode=""></image>
							<image @tap.stop="addCart" v-if="buyBtnStyle === 2" class="select1_buyBtn1" :class="buyBtn()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/add.png" mode=""></image>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 3" class="select1_buyBtn2" :class="buyBtn()?'':'hide'">购买</view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 4" class="select1_buyBtn3" :class="buyBtn()?'':'hide'">购买</view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 5" class="select1_buyBtn4" :class="buyBtn()?'':'hide'">购买</view>
							<view @tap.stop="addCart" v-if="buyBtnStyle === 6" class="select1_buyBtn5" :class="buyBtn()?'':'hide'">购买</view> -->
						</view>
					</view>
					<!-- 角标 -->
					<image class="subscript" v-if="subscriptStyle === 1" :class="subscript()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/rx.png" mode=""></image>
					<image class="subscript" v-if="subscriptStyle === 2" :class="subscript()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/xp.png" mode=""></image>
					<image class="subscript" v-if="subscriptStyle === 3" :class="subscript()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/zk.png" mode=""></image>
					<image class="subscript" v-if="subscriptStyle === 4" :class="subscript()?'':'hide'" src="http://store.hzstorm.com/web/statics/mch/images/tj.png" mode=""></image>
					<image class="subscript" v-if="subscriptStyle === 5" :class="subscript()?'':'hide'" :src="subscriptIcon"></image>
				</navigator>
			</view>
		</view>
		
	</view>
</template>

<script>
	export default{
		name:'secondsKill',
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
			endTime:{
				type:Number,
				default:0
			},
			countdownBool:{
				type:Boolean,
				default:true
			}
		},
		data(){
			return{
				time:''
			}
		},
		created() {
			var time;
			time = setInterval(()=>{
				if(this.time != '00:00:00'){
					this.time = this.formatDuring(this.endTime);
				}else{
					clearInterval(time);
				}
			},1000)
			
		},
		methods:{
			//时间戳转换时分秒
			formatDuring(time) {
				var nowTime=new Date().getTime();
				time = time - nowTime;
				if( time <= 0 ){
					return '00:00:00'
				}else{
					var	hours = parseInt((time % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
						minutes = parseInt((time % (1000 * 60 * 60)) / (1000 * 60)),
						seconds = parseInt((time % (1000 * 60)) / 1000);
					
					hours = hours < 10 ? ('0' + hours) : hours;
					minutes = minutes < 10 ? ('0' + minutes) : minutes;
					seconds = seconds < 10 ? ('0' + seconds) : seconds;
					return hours + ":" + minutes + ":" + seconds;
				}
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
		padding: 0px 20rpx;
		background: white;
		flex-wrap: wrap;
	}
	/* 样式一 */
	.select1_box{
		background: white;
		border-radius: 0 0 10rpx 10rpx;
		margin-bottom: 40rpx;
		position: relative;
	}
	.select1_proDetails{
		padding: 24rpx;
		box-sizing: border-box;
	}
	.select1_proImg{
		width: 100%;
		max-height: 712rpx;
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
		align-items: flex-end;
	}
	.select1_secondsKill_price{
		font-size: 48rpx;
	}
	.select1_original_price{
		font-size: 28rpx;
		color: #aaaaaa;
		text-decoration: line-through;
	}
	.select1_proImg_box{
		position: relative;
	}
	.select1_endTime{
		padding: 0px 20rpx;
		height: 80rpx;
		color: white;
		font-size: 28rpx;
		justify-content: space-between;
		background: -webkit-linear-gradient(left,#f44,#ff8b8b);
		background: -webkit-gradient(linear,left top,right top,from(#f44),to(#ff8b8b));
		background: -moz-linear-gradient(left,#f44,#ff8b8b);
		background: linear-gradient(90deg,#f44,#ff8b8b);
	}
	.select1_endTime_left{
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
	.select2_buyBtn2{
		font-size: 28rpx;
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
		width: 64rpx;
		height: 64rpx;
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
		padding: 0rpx 24rpx 20rpx;
		flex:1;
		justify-content: space-between;
	}
	.select2_proName{
		font-size: 28rpx;
		/* margin-bottom: 20rpx; */
		text-overflow: -o-ellipsis-lastline;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 1;
		-webkit-box-orient: vertical;
	}
	.select2_original_price{
		font-size: 30rpx;
		color: #aaaaaa;
		margin-top: 6rpx;
	}
	.select2_endTime{
		font-size: 24rpx;
	}
	.select2_secondsKill_box,.select2_original_price{
		font-size: 24rpx;
	}
	.select2_secondsKill_price{
		font-size: 38rpx;
	}
	
	/* 样式三 */
	.select3{
		width: 50%;
		padding: 0px 10rpx;
		box-sizing: border-box;
	}
	.select3_proName{
		font-size: 28rpx;
		margin-bottom: 20rpx;
		text-overflow: -o-ellipsis-lastline;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
	}
	.select3_endTime{
		padding: 0px 20rpx;
		height: 44rpx;
		color: white;
		font-size: 28rpx;
		background: -webkit-linear-gradient(left,#f44,#ff8b8b);
		background: -webkit-gradient(linear,left top,right top,from(#f44),to(#ff8b8b));
		background: -moz-linear-gradient(left,#f44,#ff8b8b);
		background: linear-gradient(90deg,#f44,#ff8b8b);
	}
	
	.flex-x-between{
		justify-content: space-between;
	}
	.hide{
		display: none;
	}
</style>
