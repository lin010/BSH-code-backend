<template>
	
	<view >
		
		<!--分享引导框开始-->
		<view v-if="guide_enable" id="guide_box"  @touchmove.stop.prevent="moveHandle">
		    <image :src="plugins_img_url+'/live_weixin.png'" mode="aspectFit" style="position:absolute;right:-130rpx;"></image>
			<image @click="guide_enable=false" :src="plugins_img_url+'/live_weixin_btn.png'" mode="aspectFit" style="width:400rpx;position:absolute;right:0rpx;top:310rpx;"></image>
		</view>
		<!--分享引导框结束-->
		
		<view class="giftbagDetail-app">
			<view class="tui-banner-swiper">
				<swiper :duration="150" :style="{height:scrollH + 'px'}" @change="bannerChange">
					<block v-for="(item,b_index) in detail.pic_url" :key="b_index">
						<swiper-item :data-index="b_index+1">
							<image :src="item.pic_url" mode="aspectFill" class="tui-slide-image" :style="{height:scrollH+'px'}" @click="enlarge(b_index)"/>
						</swiper-item>
					</block>
				</swiper>
				<jx-tag class="tui-tag-class" type="translucent" shape="circle" size="small">{{bannerIndex+1}}/{{bannerLength}}</jx-tag>
			</view>
			<view class="time-money">
				<view class="time-money-left" v-if="detail.group_enable">
					<view style="height: 60rpx;line-height: 60rpx;">
						<text style="font-weight: bold;">￥</text>
						<text style="font-size: 48rpx;font-weight: bold;">{{detail.group_price}}</text>
					</view>
					<view>
						<text style="font-size: 30rpx;color: #fff;width: 120rpx;background: #007AFF;line-height: 44rpx;text-align: center;border-radius: 30rpx;">拼团价</text>
						<text style="font-size: 28rpx;color: #fff;margin-left: 10rpx;">原价</text>
						<text style="font-size: 28rpx;color: #fff;text-decoration: line-through;">￥{{detail.price}}</text>
					</view>
				</view>
				<view class="time-money-left" v-else>
					<view style="display:flex;align-items:center;height:100%;font-size: 48rpx;">
						<text style="font-weight: bold;">￥</text>
						<text style="font-weight: bold;">{{detail.price}}</text>
					</view>
				</view>
				<view class="time-money-right">
					<view style="margin-top: 10rpx;" v-if="!detail.is_finished">距离活动结束</view>
					<view style="margin-top: 10rpx;" v-if="detail.is_finished">活动已结束</view>
					<view>{{expired_at}}</view>
				</view>
			</view>
			<view class="giftbag-title">
				<view style="width: 80%;color: #000;font-weight: bold;padding: 0 20rpx;font-size: 31rpx;border-right:1rpx solid rgb(238,238,238);">
					<text v-if="detail.group_enable" style="display: inline-block;;width: 120rpx;height: 50rpx;line-height: 48rpx;color: rgb(255,71,121);text-align: center;border: 1rpx solid rgb(255,71,121);border-radius: 30rpx;margin-right: 10rpx;">{{detail.group_num}}人团</text>
					{{detail.title}}
				</view>
				<view style="width: 20%;font-size: 30rpx;" @click="poster(1)">
					<image :src="img_url+'/new-share.png'" mode="" style="display: block;width: 50rpx;height: 50rpx;margin: 0 auto;"></image>
					<text style="display: block;width: 100%;text-align: center;color:rgb(255,71,83);">分享</text>
				</view>
			</view>
			<view class="giftbag-describe">
				{{detail.descript}}
				有<text style="background: rgb(217,217,217);color: #000;border-radius: 30rpx;display: inline-block;min-width: 90rpx;text-align: center;">{{detail.view_num}}</text>人浏览，
				<text style="background: rgb(221,82,77);color: #fff;border-radius: 30rpx;display: inline-block;min-width: 90rpx;text-align: center;">{{detail.sold_num}}</text>人参与
			</view>
			<view class="warm-prompt" v-if="detail.group_enable == 1">
				<text style="display: block;color: rgb(255,71,83);padding-left: 10rpx;position: absolute;z-index: 1;background: #fff;right: 50rpx;top:-26rpx;height: 50rpx;border: 1rpx solid rgb(255,71,83);border-radius: 10rpx;text-align: center;line-height: 52rpx;">友情提示！</text>
				拼团发起后，<text style="color: rgb(255,68,0);">{{detail.group_hour_expired}}小时内</text>完成<text  style="color: rgb(255,68,0);">{{detail.group_num}}人</text>组团即拼团成功，否则拼团失败，拼团金额返回用户支付帐户
			</view>
			
			<!-- 定时器滚动拼单记录 -->
			<view style="padding-top:10rpx;height:290rpx;overflow: hidden;"  v-if="join_groups.length > 0">
				<view :animation="animationData" class="spellgroup-list">					
					<view class="spellgroup-item" v-for="(item, index) in join_groups" :key='index'>
						<image :src="item.avatar_url" mode="" style="border-radius: 50%;"></image>
						<view class="spellgroup-item-name">
							{{item.nickname}}
						</view>
						<view class="spellgroup-item-peopeliNum">
							<view style="text-align: right;width: 100%;height: 50rpx;line-height: 50rpx;">
								还差<text style="color: red;">{{item.need_num}}</text>人拼成</view>
							<text style="text-align: right;display: block;">{{item.expired_text}}</text>
						</view>
						<view class="go-spellgroup" @click="jumpToGroup(item.group_id)">
							去拼单
						</view>
					</view>
					
				</view>
			</view>			
			<view class="select-check">
				<template v-for="(item,index) in table">
					<text v-if="index != 1 || detail.group_enable" :key='index' :class="selectIndex==index?'active':'actove'" @click="select(index)">
						{{item}}
					</text>
				</template>
			</view>
			<view class="package-content" v-if="selectIndex==0">
				<view class="package-content-title">
					套餐包括：
				</view>
				<view class="tui-product-img tui-radius-all" style="margin-bottom: 50rpx;">
					<jyf-parser :html="detail.detail"></jyf-parser>
				</view>
			</view>
			<view class="Spell-group-records" v-if="selectIndex==1">
				<!--<view class="Spell-group-records-header">
					
					<text style="color: #000;font-size: 30rpx;">最近拼团记录</text>
					<text style="float: right;font-size: 25rpx;min-width: 120rpx;background:rgb(0,122,255);height: 45rpx;line-height: 45rpx;color: #fff;text-align: center;
					margin-top: 20rpx;border-radius: 30rpx;">
						共{{detail.sold_num}}
					</text>
				</view>-->
				<view class="Spell-group-records-item" v-for="(item,index) in spellgroup" :key='index'>
					<image :src="item.avatar_url" mode=""></image>
					<view style="width: 65%;">
						<text style="color: #000;font-size: 28rpx;margin: 5rpx 0;">{{item.nickname}}</text>
						<text style="color: rgb(255,153,0);font-size: 28rpx;">{{item.created_at}}发起</text>
					</view>
					<view>
						<text v-if="item.status == 'success'" class="Spell-group-item-finished" style="">
							已完成
						</text>
						<text @click="jumpToGroup(item.id)" v-if="item.status == 'sharing'" class="Spell-group-item-sharing" style="">
							去拼团
						</text>
						<text v-if="item.status == 'closed'" class="Spell-group-item-closed" style="">
							已关闭
						</text>
					</view>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
				</view>
			</view>
			<!-- <view class="Spell-group-polite" v-if="selectIndex==2">
				<view class="Spell-group-polite-header">
					发起拼团可得以下礼品：
				</view>
				<view class="Spell-group-polite-item" v-for="item in 3" :key=''>
					<view class="Spell-group-polite-item-detail">
						<image src="http://yingmlife-1302693724.cos.ap-guangzhou.myqcloud.com/uploads/images/original/20210802/98d72971fdda19357649d7e0ab24a589.jpeg" class="logo"></image>
						<view class="Spell-group-polite-item-detail-price">
							<view class="Spell-group-polite-item-detail-price-num">
								<text style="font-size: 45rpx;font-weight: bold;">￥21</text>
								<text style="font-size: 30rpx;">x11</text>
							</view>
							<view class="Spell-group-polite-item-detail-name">
								果汁
							</view>
						</view>
					</view>
					<view class="Spell-group-polite-item-title">
						<view style="font-size: 30rpx;color: #000;font-weight: bold;">
							<image :src="img_url+'/dao_location.png'"  mode=""></image>
							<text>亚马逊食人鱼</text>
						</view>
						<view style="font-size: 30rpx;color: #000;">
							<image :src="img_url+'/cell-phone_new.png'" mode=""></image>
							<text>18888888888</text>
						</view>
					</view>
					<view class="Spell-group-polite-item-describe">
						到店核销领取
					</view>
				</view>
			</view> -->
			
			<com-modal :button="button" :show="modal" @click="handleClick" @cancel="hide" title="提示" content="您没有设置支付密码,是否需要跳转设置？"></com-modal>
			
			<unipopup ref="popup" type="bottom">
				<view class="spell-pay-type" >
					<view class="spell-pay-type-title">支付方式</view>
					<view class="giftbagDetail-service" v-if="detail.allow_currency=='integral'" >
						<jx-list-cell :arrow="false" padding="0" :lineLeft="false">
							<view class="jx-cell-header" style="height: 80rpx;">
								<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">需使用{{currencyAlias.red_envelope_alias}}支付</view>
							</view>
							<view class="jx-cell-header" style="margin-left: 350rpx;color: #FF5A0E;height: 80rpx;">
								<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">{{moneyMessage.integral_deduction_price}}{{currencyAlias.red_envelope_alias}}</view>
							</view>
						</jx-list-cell>		
					</view>
					<view class="popup-bottom"  v-if="detail.allow_currency=='integral'">
						<text>剩余{{currencyAlias.red_envelope_alias}}：{{moneyMessage.user_integral}}</text>
						<text @click="buy">去支付</text>
					</view>
					<view class="giftbagDetail-service" v-if="detail.allow_currency=='money'">
						<radio-group @change="radioChange">
					        <view  v-for="(item, index) in payitems" :key="index" style="width: 100%;height: 100rpx;line-height: 100rpx;padding: 0 20rpx;box-sizing: border-box;">
								<view style="float: left;">{{item.name}}</view>
								<view style="float: right;">
					                <radio :value="item.value" :checked="index === current" />
					            </view>
					        </view>
					    </radio-group>
					</view>
					<view class="giftbagDetail-service" v-if="detail.allow_currency=='money'&&current==0">
						<jx-list-cell :arrow="false" padding="0" :lineLeft="false">
							<view class="jx-cell-header" style="height: 80rpx;">
								<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">需使用{{currencyAlias.balance_alias}}支付</view>
							</view>
							<view class="jx-cell-header" style="margin-left: 350rpx;color: #FF5A0E;height: 80rpx;">
								<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">{{moneyMessage.group_price}}元</view>
							</view>
						</jx-list-cell>		
					</view>
					<view class="giftbagDetail-service" v-if="detail.allow_currency=='money'&&current==1">
						<jx-list-cell :arrow="false" padding="0" :lineLeft="false">
						<!-- #ifdef H5||MP -->
							<view class="jx-cell-header" style="height: 80rpx;">
								<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">需使用微信支付</view>
							</view>
						<!-- #endif -->	
						<!-- #ifdef APP-PLUS -->
							<view class="jx-cell-header" style="height: 80rpx;">
								<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">需使用支付宝支付</view>
							</view>
						<!-- #endif -->	
							<view class="jx-cell-header" style="margin-left: 350rpx;color: #FF5A0E;height: 80rpx;">
								<view class="jx-cell-title" style="font-size: 28rpx;line-height: 80rpx;margin-left: 20rpx;">{{moneyMessage.group_price}}元</view>
							</view>
						</jx-list-cell>		
					</view>
					<view class="popup-bottom"  v-if="detail.allow_currency=='money'&&current==0">
						<text>剩余{{currencyAlias.balance_alias}}：{{moneyMessage.balance}}</text>
						<text @click="buy">去支付</text>
					</view>
					<view class="popup-bottom"  v-if="detail.allow_currency=='money'&&current==1">				
						<text   style="width: 360rpx;height: 80rpx;background: red;text-align: center;line-height: 80rpx;border-radius: 30rpx;
								margin-left: 240rpx;color: #fff;margin-top: 10rpx;"  @click="buy">去支付</text>	
					</view>
				</view>
			</unipopup>
			
			<com-payment-password :H5Bottom="h5Bottom" ref="paymentPassword" :show="cashFlag" :value="paymentPwd" :digits="6"
				@submit="checkPwd" @checkSafePwd="safePasswork"></com-payment-password>
				
			<unipopup ref="popupShare" type="center">
				<view class="popup-detail">
					<view class="popup-detail-title">
						分享链接
					</view>
					<view class="popup-detail-link">
						{{url}}
					</view>
					<view class="select-type">
						<button type="default" @click="deleted">取消</button>
						<button type="default"  style="background: red;color: #fff;"  v-clipboard:copy="url" v-clipboard:success="(type) => paste('success')" v-clipboard:error="(type) => paste('error')">复制链接</button>
					</view>
				</view>
			</unipopup>
			
			<view class="bottom" v-if="!detail.is_finished">
				<view class="bottom-back" @click="back">
					<image :src="img_url+'/new_bac.png'" mode=""></image>
					<text>返回</text>
				</view>
				<view class="bottom-order" @click="order">
					<image :src="img_url+'/new_ord.png'" mode=""></image>
					<text>订单</text>
				</view>
				<view class="bottom-buy" v-if="detail.group_enable">
					<view style="background: rgb(253,188,2);width: 50%;height: 85rpx;margin-top: 25rpx;border-radius: 50rpx 0 0 50rpx;" @click="singleBuy">
						<text style="font-size: 30rpx;font-weight: bold;">￥{{detail.price}}</text>
						<text style="font-size: 28rpx;">单独购买</text>
					</view>
					<view style="background: rgb(255,71,83);width: 50%;height: 85rpx;margin-top: 25rpx;border-radius: 0 50rpx 50rpx 0;" @click="gospellbuy">
						<text style="font-size: 30rpx;font-weight: bold;">￥{{detail.group_price}}</text>
						<text style="font-size: 28rpx;">我要开团</text>
					</view>
				</view>
				<view class="bottom-buy" v-else style="display:flex;align-items: center;justify-content: flex-end;">
					<view style="display:inline-block;background: rgb(255,71,83);width: 80%;height: 85rpx;line-height:85rpx;border-radius: 50rpx;" @click="singleBuy">
						<text style="font-size: 35rpx;">立即下单</text>
					</view>
				</view>
			</view>
			<view class="bottom" v-if="detail.is_finished">
				<view class="bottom-back" @click="back">
					<image :src="img_url+'/new_bac.png'" mode=""></image>
					<text>返回</text>
				</view>
				<view class="bottom-order" @click="order">
					<image :src="img_url+'/new_ord.png'" mode=""></image>
					<text>订单</text>
				</view>
				<view class="bottom-buy">
					<view style="background: #ddd;width: 100%;text-align:center;line-height:85rpx;height: 85rpx;margin-top: 25rpx;border-radius: 50rpx;">
						已结束
					</view>
				</view>
			</view>
		</view>
		
		<view class="goods-qrcode-modal" v-if="showPoster">
			<view class="goods-qrcode-body flex-col">
				
				<view class="goods-qrcode2 flex flex-y-center flex-x-center">
					<view class="codeImg_box">
						<!-- #ifdef H5 -->
						<view class="goods-qrcode-box">
							<image :src="poster_url" class="goods-qrcode" mode='aspectFit'></image>
						</view>
						<!-- #endif -->
						
						<!-- #ifdef MP-WEIXIN -->
						<view class="goods-qrcode-box" @longpress="saveImage(poster_url)">
							<image :src="poster_url" class="goods-qrcode" mode='aspectFit'></image>
						</view>
						<!-- #endif -->
						
						<!-- #ifdef APP-PLUS -->
						<view class="goods-qrcode-box" @longpress="appSaveImg(poster_url)">
							<image :src="poster_url" class="goods-qrcode" mode='aspectFit'></image>
						</view>
						<!-- #endif -->
					</view>
					<view class="saveCode-btn">长按图片保存至本地</view>
				</view>
				
				<view class="goods-qrcode-close" @click="poster(-1)">
					<view style="width: 50rpx;height: 50rpx;text-align: center;background-color: #ADADAD;color: #FFFFFF;border-radius: 50%;line-height: 50rpx;"
					 class="iconfont icon-guanbi"></view>
				</view>
			</view>
		</view>
		
	</view>
	
</template>
<style lang="less" scoped>
	.goods-qrcode-modal {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 1000;
		background: rgba(0, 0, 0, 0.5);
		opacity: 1;
		transition: opacity 250ms;
		z-index: 9999;
	}
	.goods-qrcode-body {
		background: #ffffff;
		height: 100%;
		border-radius: 10rpx;
		padding: 30rpx;
	}
	.goods-qrcode-body .goods-qrcode-box {
		height: 100%;
		position: relative;
		box-shadow: 0 0 15rpx rgba(0, 0, 0, 0.15);
	}
	.goods-qrcode2{
		position: relative;
		height: 1300rpx;
		flex-direction: column;
		margin-top: 40rpx;
	}
	.codeImg_box {
		width: 92%;
		height: 82%;
		margin-bottom: 20rpx;
	}
	.saveCode-btn{
		color: #939292;
		padding: 10px 20px;
		border-radius: 10px;
	}
	
	.goods-qrcode {
		width: 100%;
		height: 100%;
		background: #fffffff;
		background-size: 100%;
	}
	.goods-qrcode-close {
		position: absolute;
		/* #ifdef H5 */
		top: 100rpx;
		/* #endif */
		/* #ifdef MP-WEIXIN ||APP-PLUS */
		top: 100rpx;
		/* #endif */
		right: 40rpx;
		padding: 15rpx;
	}
	
	.Spell-group-item-finished,.Spell-group-item-sharing,.Spell-group-item-closed{
		width: 120rpx;margin-top: 30rpx;height: 45rpx;font-size: 25rpx;line-height: 45rpx;text-align: center;border-radius: 30rpx;
	}
	.Spell-group-item-finished{background: rgb(76,217,100);color: #fff;}
	.Spell-group-item-sharing{background: #007AFF;color: #fff;}
	.Spell-group-item-closed{background: #aaa;color: #fff;}
	
	.spellgroup-list{width: 100%;overflow: hidden;background: #fff;padding: 0 20rpx;box-sizing: border-box;}
	.spellgroup-item{width: 100%;overflow: hidden;border-bottom: 2rpx dashed #f2f2f2;height:130rpx;margin-top:15rpx;}
	.spellgroup-item image{width: 60rpx;height: 60rpx;float: left;margin-top: 20rpx;margin-right: 20rpx;}
	.spellgroup-item-name{float: left;height: 100rpx;line-height: 100rpx;font-size: 25rpx;width: 170rpx;
	overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
	.spellgroup-item-peopeliNum{height: 100rpx;float: left;font-size: 25rpx;width: 250rpx;margin-left: 60rpx;}
	.spellgroup-item-peopeliNum text:nth-of-type(2){display: block;width: 100%;height: 50rpx;text-align: right;line-height: 50rpx;}
	.go-spellgroup{
		float: right;
		background: #ff4753;
		color:white;
		width: 130rpx;
		text-align: center;
		height: 70rpx;
		line-height:70rpx;
		margin-top:13rpx;
		border-radius:3rpx;
		font-size:25rpx
	}
	
	#guide_box{z-index:999999;left:0rpx;top:0rpx;background:black;filter:alpha(Opacity=90);-moz-opacity:0.9;opacity: 0.9;position:fixed;width:100%;height:100%;}
	.giftbagDetail-app{width: 100%;overflow: hidden;}
	.tui-banner-swiper{width: 100%;overflow: hidden;position: relative;}
	.tui-banner-swiper .tui-tag-class {position: absolute;color: #fff;bottom: 40rpx;right: 40rpx;}
	.tui-slide-image {width: 100%;display: block;}
	// .tui-banner-swiper image{display: block;width: 100%;}
	.time-money{width: 100%;height: 140rpx;padding: 10rpx 20rpx;box-sizing: border-box;background: rgb(255,71,83);display: flex;justify-content: space-between;}
	.time-money-left view:nth-of-type(1){color: rgb(255,255,0);}
	.time-money-left view text{display: inline-block;}	
	.time-money-right{text-align: right;color: #fff;}
	.giftbag-title{width: 100%;overflow: hidden;display: flex;justify-content: space-between;margin: 20rpx 0;}
	.giftbag-describe{width: 80%;font-size: 29rpx;padding-left: 20rpx;}
	.warm-prompt{width: 95%;min-height: 150rpx;position: relative;margin: 40rpx auto 0;border-radius: 15rpx;border: 1rpx solid rgb(255,71,83);padding: 30rpx 15rpx;font-size: 28rpx;}
	.select-check{width: 100%;display: flex;justify-content: space-evenly;margin: 30rpx 0;background: rgb(239,239,244);height: 90rpx;}
	.actove{display: block;height: 100%;width: 30%;text-align: center;line-height: 90rpx;color: #000;}
	.active{color: rgb(221,82,77);border-bottom: 1rpx solid rgb(221,82,77);display: block;height: 100%;width: 30%;text-align: center;line-height: 90rpx;}
	.package-content{width: 95%;overflow: hidden;margin: 0 auto 150rpx;}
	.package-content-title{color: #000;font-size: 30rpx;font-weight: bold;margin-bottom: 20rpx;}
	.package-content-item{width: 100%;overflow: hidden;margin-top: 20rpx;}
	.package-content-item image{width: 100%;display: block;}
	.package-content-item text{display: block;width: 100%;overflow: hidden;margin-top: 20rpx;font-size: 30rpx;color: #000;}
	.Spell-group-records{width: 95%;overflow: hidden;margin: 0 auto 150rpx;}
	.Spell-group-records-header{width: 100%;height: 80rpx;line-height: 80rpx;}
	.Spell-group-records-header text{display: inline-block;}
	.Spell-group-records-item{width: 100%;height: 140rpx;padding: 20rpx 0;display: flex;justify-content: space-evenly;border-bottom: 1rpx dashed rgb(221,221,221);}
	.Spell-group-records-item:last-child{border-bottom: none;}
	.Spell-group-records-item image{display: block;width: 100rpx;height: 100rpx;border-radius: 50%;}
	.Spell-group-records-item view text{display: block;}
	.Spell-group-polite{width: 95%;overflow: hidden;margin: 0 auto 150rpx;}
	.Spell-group-polite-header{font-size: 30rpx;color: #000;border-bottom: 1rpx solid rgb(221,221,221);height: 80rpx;line-height: 80rpx;}
	.Spell-group-polite-item{width: 100%;overflow: hidden;background: #fff;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.2);margin: 20rpx 0;border-radius: 20rpx}
	.Spell-group-polite-item-detail{background: rgb(254,167,182);height: 150rpx;display: flex;justify-content: space-evenly;}
	.Spell-group-polite-item-detail .logo{width: 120rpx;height: 120rpx;display: block;margin-top: 15rpx;}
	.Spell-group-polite-item-detail-price{margin-top: 15rpx;color: #fff;width: 70%;}
	.Spell-group-polite-item-detail-price text{display: inline-block;}
	.Spell-group-polite-item-detail-price-num{width: 100%;text-align: right;}
	.Spell-group-polite-item-detail-name{font-size: 30rpx;width: 100%;text-align: right;}
	.Spell-group-polite-item-title{width: 100%;display: flex;justify-content: space-between;background: #fff;margin-top: 20rpx;padding: 0 20rpx;}
	.Spell-group-polite-item-title image{display: inline-block;width: 50rpx;height: 50rpx;float: left;}
	.Spell-group-polite-item-describe{font-size: 26rpx;margin: 30rpx 0;padding: 0 20rpx;}
	.spell-pay-type{width: 100%;min-height: 700rpx;background: #fff;border-radius: 30rpx 30rpx 0 0;}
	.spell-pay-type-title{width: 100%;line-height: 80rpx;text-align: center;color: #000;}
	.popup-bottom{position: absolute;left: 0;bottom: 0;width: 100%;height: 100rpx;box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.4);background: #fff;}
	.popup-bottom text{display: inline-block;}
	.popup-bottom text:nth-of-type(1){line-height: 100rpx;margin-left: 50rpx;font-size: 30rpx;color: #FF5A0E;font-weight: bold;}
	.popup-bottom text:nth-of-type(2){width: 260rpx;height: 80rpx;background: red;text-align: center;line-height: 80rpx;border-radius: 30rpx;
	margin-left: 80rpx;color: #fff;}
	.bottom{width: 100%;height: 120rpx;padding: 0 20rpx;box-sizing: border-box;background: #fff;position: fixed;left: 0;bottom: 0;z-index: 1;display: flex;justify-content: space-evenly;}
	.bottom view image{width: 50rpx;height: 50rpx;display: block;margin: 15rpx auto 5rpx;}
	.bottom-back,.bottom-order{width: 20%;text-align: center;font-size: 29rpx;}
	.bottom-buy{display: flex;justify-content: space-between;width: 60%;}
	.bottom-buy text{display: block;text-align: center;color: #fff;}
	.giftbagDetail-service{box-sizing: border-box;}
	.popup-detail{width: 550rpx;height: 400rpx;background: #fff;border-radius: 30rpx;}
	.popup-detail-title{width:550rpx;text-align: center;height: 80rpx;line-height: 80rpx;color: #000;}
	.popup-detail-link{width:550rpx;overflow: hidden;font-size: 25rpx;color: #000;background:#fafafa ;margin: 35rpx 0;padding: 0 10rpx;box-sizing: border-box;
	overflow:hidden;text-overflow:ellipsis;white-space:nowrap;line-height: 80rpx;}
	.select-type{width: 100%;height: 80rpx;display: flex;justify-content: space-evenly;margin: 80rpx 0 0 0;}
	.select-type button{width: 40%;height: 80rpx;text-align: center;line-height: 80rpx;font-size: 28rpx;}
	
</style>


<script>
	import unipopup from '@/components/uni-popup/uni-popup';
	import jxTag from "@/components/tag/tag"
	import jxListCell from '@/components/list-cell/list-cell'; 
	import jyfParser from "@/components/jyf-parser/jyf-parser";
	// #ifdef H5
	var jweixin = require('jweixin-module');
	// #endif
	// #ifdef MP-WEIXIN || APP-PLUS
	import {setPay} from '@/config/utils.js'
	// #endif
	var iTimer = null, animTimer = null, iTimer2 = null;
	var countdownFun = function(expired_at){
		var date = new Date();
		var timestamp =parseInt(date.getTime()/1000);
		var text = "", time = (expired_at-timestamp);
		if(time <= 0){
			text = '0'+'天'+'0'+"时"+'0'+'分'+'0'+'秒';
		}else{
			let days, hours, minutes, seconds;
			days 	= parseInt(time / 60 / 60 / 24 , 10); //计算剩余的天数
			hours 	= parseInt(time / 60 / 60 % 24 , 10) < 10 ? "0" + parseInt(time / 60 / 60 % 24 , 10) : parseInt(time  / 60 / 60 % 24 , 10); //计算剩余的小时 
			minutes = parseInt(time / 60 % 60, 10)<10 ? "0" + parseInt(time / 60 % 60, 10) : parseInt(time / 60 % 60, 10);//计算剩余的分钟 
			seconds = parseInt(time % 60, 10)<10 ? "0" + parseInt(time  % 60, 10) : parseInt(time  % 60, 10);//计算剩余的秒数 								
			text = days + "天" + hours + "时" + minutes + '分' + seconds + "秒";
		}
		return text;
	};
	
	export default {
		components: {
			unipopup,
			jxListCell,
			jxTag,
			jyfParser
		},
		data() {
			return {
				animationData: [],
				
				guide_enable: false,
				h5Bottom:true,
				pack_id:'',
				plugins_img_url: this.$api.plugins_img_url,
				img_url: this.$api.img_url,
				selectIndex:0,//table选项
				table:['活动介绍','拼团记录'],
				// table:['活动介绍','拼团记录','拼团有礼']
				detail:{is_finished:0},
				productList:[],
				expired_at:'',
				spellgroup:[],//拼团记录
				page_count:'',
				page:1,
				modal:false,//模态弹窗
				button:[],
				cashFlag: false,
				paymentPwd: '',
				is_transaction_password:true,//是否设置过支付密码
				popup:false,
				payitems: [
					{
				        value: '余额支付',
				        name: '余额支付'
				    },
					// #ifdef H5 ||MP
				    {
				        value: '微信支付',
				        name: '微信支付'
				    }
					// #endif
					// #ifdef APP-PLUS
					{
					    value: '支付宝支付',
					    name: '支付宝支付'
					}
					// #endif
				],
				current: 0,
				moneyMessage:"",
				group_id:'',//团id
				popupShare:false,
				url:'',
				bannerLength:0,
				bannerIndex: 0,
				height:'',
				scrollH:'',
				join_groups: [],
				anim_groups: [],
				anim_poi: 0,
				pos_i:0,
				showPoster: false,
				poster_url: '',
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			}
		},
		onLoad(options) {
			this.beforeOnLoad(options);
			if(options&&options.pack_id){
				this.pack_id=options.pack_id
				this.getpackageListitem(options.pack_id)
				this.morespellgroup(options.pack_id)
			}
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
				this.payitems =[
					{
				        value: this.currencyAlias.balance_alias+'支付',
				        name: this.currencyAlias.balance_alias+'支付'
				    },
					// #ifdef H5 ||MP
				    {
				        value: '微信支付',
				        name: '微信支付'
				    }
					// #endif
					// #ifdef APP-PLUS
					{
					    value: '支付宝支付',
					    name: '支付宝支付'
					}
					// #endif
				];
			}
			this.button = this.globalSet('btnCol','确定');
		},
		onShow() {
			
			/* uni.redirectTo({
				url:"../spelldetail/spelldetail?pack_id=4&group_id=198&type=1"
			}); */
			
			this.initSetting()
			let routes = getCurrentPages(); // 获取当前打开过的页面路由数组
			let curRoute = routes[routes.length - 1].route //获取当前页面路由
			let curParam = routes[routes.length - 1].options; //获取路由参数
			if(curParam&&curParam.pack_id){
				this.packageDetail(curParam.pack_id)
			}
			let obj = {};
			// #ifdef MP-WEIXIN
			obj = wx.getMenuButtonBoundingClientRect();
			// #endif
			setTimeout(() => {
				uni.getSystemInfo({
					success: (res) => {
						this.height = obj.top ? (obj.top + obj.height + 8) : (res.statusBarHeight + 44);
						this.scrollH = res.windowWidth
					}
				})
			}, 50)
			
			var animation = uni.createAnimation({
				duration: 1000,
				timingFunction: 'ease',
			})
			this.animation = animation
			let that = this;
			
			let i = 0, x = 1;
			let timeflah= setInterval(()=> {
				i = i + 146 * 2;
				let H = that.join_groups.length * 146;
				if(i >= H){
					i = 0;
				}
				animation.translateY("-" + i + "rpx").step();
				that.animationData = animation.export();
			}, 3000);
		},
		methods:{
			poster(key) {
				if (key == -1) {
					this.showPoster = false;
					return;
				}
				if (this.poster_url) {
					this.showPoster = true;
					return;
				}
				this.$http.request({
					url: this.$api.package.getPackageDetailShare,
					method: 'POST',
					data: {
						pack_id:this.pack_id
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.showPoster = true;
						this.poster_url = res.data.pic_url;
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			saveImage(url) { //保存图片
				var that = this;
				uni.authorize({
					/* 这个就是保存相册的 */
					scope: 'scope.writePhotosAlbum',
					success() {
						/* 保存图片方法 */
						that.saveImg(url);
					},
					complete(res) {
						/* 这里判断一下如果没有授权重新打开设置选项 */
						uni.getSetting({
							success(res) {
								if (!res.authSetting['scope.writePhotosAlbum']) {
									/* 打开设置的方法 */
									that.opensit();
								}
							}
						});
					}
				});
			},
			opensit() {
				uni.showModal({
					content: '由于您还没有允许保存图片到您相册里,请点击确定去允许授权',
					success: function(res) {
						if (res.confirm) {
							/* 这个就是打开设置的API*/
							uni.openSetting({
								success(res) {
									console.log(res.authSetting);
								}
							});
						} else if (res.cancel) {
							uni.showModal({
								cancelText: '依然取消',
								confirmText: '重新授权',
								content: '很遗憾你点击了取消，请慎重考虑',
								success: function(res) {
									if (res.confirm) {
										uni.openSetting({
											success(res) {
												console.log(res.authSetting);
											}
										});
									} else if (res.cancel) {
										console.log('用户不授权');
									}
								}
							});
						}
					}
				});
			},
			saveImg(url) {
				var that = this;
				/* 获取图片信息 */
				uni.getImageInfo({
					src: url,
					success: function(image) {
						/* 保存图片到相册 */
						uni.saveImageToPhotosAlbum({
							filePath: image.path,
							success: function() {
								that.$http.toast('保存成功');
							},
							fail(res){
								that.$http.toast('保存失败,请稍后重试');
							}
						});
					}
				});
			},
			appSaveImg(url){
				let that = this;
				/* 保存图片到相册 */
				uni.saveImageToPhotosAlbum({
					filePath: url,
					success: function() {
						that.$http.toast('保存成功');
					},
					fail(res){
						that.$http.toast('保存失败,请稍后重试');
					}
				});
			},			
			invitation(){ //分享
				
				// #ifdef H5
				//this.guide_enable = true;
				//this.$refs.popupShare.open()
				//let pid=JSON.parse(uni.getStorageSync('userInfo')).user_id
				//this.url=window.location.href+"&pid="+pid+"&type="+1
				// #endif
				
			},
			enlarge(index){ //点击主图放大
				  let photoList = this.detail.pic_url.map(item => {
				                    return item.pic_url;
				      });
				                uni.previewImage({
				                    current: index,     // 当前显示图片的链接/索引值
				                    urls: photoList,    // 需要预览的图片链接列表，photoList要求必须是数组
				                    loop:true   // 是否可循环预览
				                });
			},
			moveHandle(){
				return null;
			},
			jumpToGroup(group_id){
				uni.navigateTo({
					url:"../spelldetail/spelldetail?pack_id="+this.pack_id+"&type=1&group_id="+group_id
				});
			},
			select(index){ //table切换
				this.selectIndex=index
			},
			back(){ //点击返回按钮
				uni.redirectTo({
					url:"../giftList/giftList"
				})
			},
			order(){ //点击订单按钮
				uni.redirectTo({
					url:'../orderList/orderList'
				})
			},
			bannerChange: function(e) {
				this.bannerIndex = e.detail.current
			},
			packageDetail(pack_id){ //获取大礼包详情
				let i=0,  that = this;
				
				this.$http.request({
					url: this.$api.package.packageDetail,
					method: 'POST',
					data: {
						pack_id:pack_id
					},
					showLoading: true
				}).then(res => {
					
					if (res.code == 0) {
						
						// #ifdef H5
						this.$wechatSdk.share("mch/giftbag/gifebagDetail/newgifebagDetail?pack_id=" + pack_id, {
							app_share_title: res.data.detail.title,
							app_share_desc: res.data.detail.descript,
							app_share_pic: res.data.detail.cover_pic
						});
						// #endif
						
						if(res.data.my_group.has_group==1){
							var my_group = res.data.my_group;
							uni.showModal({
							    content: '你正在参与活动，去邀请好友吧',
							    success: function (res) {
							        if (res.confirm) {
										that.jumpToGroup(my_group.group_id);
							        }
							    }
							});
						}
						this.detail=res.data.detail
						this.bannerLength=res.data.detail.pic_url.length
						this.expired_at=res.data.detail.expired_at;
						let expired_time = this.expired_at;
						this.join_groups = res.data.join_groups;
						for(i=0; i < this.join_groups.length; i++){
							this.join_groups[i]['expired_text'] = '0天0时0分0秒';
						}
						if(iTimer != null){
							clearInterval(iTimer);
						}
						if(animTimer != null){
							clearInterval(animTimer );
						}
						iTimer = setInterval(function(){
							that.expired_at= countdownFun(expired_time);
							
							for(i=0; i < that.join_groups.length; i++){
								that.join_groups[i]['expired_text'] = countdownFun(that.join_groups[i]['expired_at']);
							}
						}, 1000);
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			getpackageListitem(pack_id){ //获取礼包套餐类容
				this.$http.request({
					url: this.$api.package.packageListitem,
					method: 'POST',
					data: {
						page:this.page,
						pack_id:pack_id
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						this.productList =res.data.list
					} else {
						this.$http.toast(res.msg);
					}
				});
			},
			morespellgroup(pack_id){//更多拼团
				 this.$http.request({
				 	url: this.$api.package.getgroupList,
				 	method: 'POST',
				 	data: {
						page:this.page,
				 		pack_id:pack_id
				 	},
				 	showLoading: true
				 }).then(res => {
				 	if (res.code == 0) {
						if(res.data.list.length==0)return false
						let list= res.data.list, i;
						var arr=this.spellgroup.concat(list)
						this.spellgroup =arr
						this.page_count = res.data.pagination.page_count;
				 	} else {
				 		this.$http.toast(res.msg);
				 	}
				 });
			},
			singleBuy(){ //单独购买
				uni.navigateTo({
					url:'../giftsubmit/giftsubmit?pack_id='+this.pack_id
				})
			},
			gospellbuy(){ //创建拼团
				let that = this;
				this.$http.request({
					url: this.$api.package.createspell,
					method: 'POST',
					data: {
						pack_id:this.pack_id
					},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						that.$refs.popup.open();
						that.moneyMessage=res.data
						that.group_id=res.data.group_id
					} else {
						that.$http.toast(res.msg);
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
			hide() { //影藏
				this.modal = false;
			},
			handleClick(e) { //没有设置密码
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
			checkPwd(e) { //输入密码
				console.log(e)
			},
			safePasswork(e){ //密码校验
				this.paymentPwd = e.passwork;
				this.payMoney(this.group_id,this.paymentPwd)
			},
			radioChange: function(evt) {
				for (let i = 0; i < this.payitems.length; i++) {
					if (this.payitems[i].value === evt.detail.value) {
						this.current = i;
						console.log(this.current )
						break;
					}
				}
			},
			buy(){ //点击去支付弹出输入密码框
				if(this.current==0){
					if(!this.is_transaction_password){
						this.modal = true;
						return;
					}
					this.cashFlag=true
					this.$refs.paymentPassword.modalFun('show');
				}else{
					this.payMoney(this.group_id,this.paymentPwd)
				}	
			},
			payMoney(group_id,trade_pwd){ //支付
				if(this.detail.allow_currency=='integral'){ //金豆支付
					this.$http.request({
						url: this.$api.package.payMoneybag,
						method: 'POST',
						data: {
							group_id:group_id,
							trade_pwd:trade_pwd
						},
						showLoading: true
					}).then(res => {
						if (res.code == 0) {
							this.$refs.paymentPassword.modalFun('hide');
							this.$http.toast('支付成功');
							var jumpUrl = "";
							if(res.data.finished == 1){
								jumpUrl = '../giftOrderDetail/giftOrderDetail?order_id=' + res.data.order_id;
							}else{
								jumpUrl = '../spelldetail/spelldetail?pack_id='+this.pack_id+"&group_id="+group_id;
							}
							uni.navigateTo({
								url:jumpUrl
							});
						} else {
							this.$http.toast(res.msg);
						}
					});
				}
				if(this.detail.allow_currency=='money'&&this.current==0){ //余额支付
					this.$http.request({
						url: this.$api.package.paybalancebag,
						method: 'POST',
						data: {
							group_id:group_id,
							trade_pwd:trade_pwd
						},
						showLoading: true
					}).then(res => {
						if (res.code == 0) {
							this.$refs.paymentPassword.modalFun('hide');
							this.$http.toast('支付成功');
							var jumpUrl = "";
							if(res.data.finished == 1){
								jumpUrl = '../giftOrderDetail/giftOrderDetail?order_id=' + res.data.order_id;
							}else{
								jumpUrl = '../spelldetail/spelldetail?pack_id='+this.pack_id+"&group_id="+group_id;
							}
							uni.navigateTo({
								url:jumpUrl
							});
						} else {
							this.$http.toast(res.msg);
							this.paymentPwd=''
							this.$refs.paymentPassword.clearAll();
						}
					});
				}			
				if(this.detail.allow_currency=='money'&&this.current==1){ //第三方支付
					this.$http.request({
						url: this.$api.package.paywechatcreatedbag,
						method: 'POST',
						data: {
							group_id:group_id,
						},
					}).then(res => {
						if (res.code == 0) {
							var union_id=res.data.union_id
							// #ifdef H5 ||MP							
							this.getWchat(union_id)
							// #endif
							// #ifdef APP-PLUS
							this.getalipay(union_id)
							// #endif
						} else {
							this.$http.toast(res.msg);
						}
					});
				}
			},
			getWchat(union_id){ //第三方支付
				// #ifdef H5 
				var url="https://dev.mingyuanriji.cn/h5/#/mch/spelldetail/spelldetail?pack_id="+this.pack_id+"&group_id="+this.group_id
				this.$http.request({
					url: this.$api.package.paywechatbag,
					method: 'POST',
					data: {
						union_id:union_id,
						stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
						wx_type:'wechat',//公众号：wechat  小程序：mp-wx
						redirect_url:url
					},
					showLoading: true
				}).then(res => {
					this.$refs.paymentPassword.modalFun('hide');
					if (res.code == 0) {
						this.$wechatSdk.pay(res.data,url);
					} else {
						this.$http.toast(res.msg);
					}
				});
				// #endif
				// #ifdef MP-WEIXIN || APP-PLUS
					var url="/mch/spelldetail/spelldetail?pack_id="+this.pack_id+"&group_id="+this.group_id
					this.$http.request({
						url: this.$api.package.paywechatbag,
						method: 'POST',
						data: {
							union_id:union_id,
							stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
							wx_type:'mp-wx',//公众号：wechat  小程序：mp-wx
							redirect_url:url
						},
						showLoading: true
					}).then(res => {
						this.$refs.paymentPassword.modalFun('hide');
						if (res.code == 0) {
							setPay(res.data, (result) => {
								if (result.success) {
									this.$http.toast("支付成功")
									setTimeout(() => {
										uni.redirectTo({
											url: url
										})
									},500)
								} else {
									this.$http.toast("支付失败")
								}													
							});
						} else {
							this.$http.toast(res.msg);
						}
					});
				// #endif	
			},
			getalipay(union_id){
				let that=this
				that.$http.request({
					url: that.$api.moreShop.alipay,
					showLoading: true,
					method: 'post',
					data: {
						union_id:union_id,
						stands_mall_id:JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id!=null?JSON.parse(uni.getStorageSync('mall_config')).stands_mall_id:5,
					}
				}).then(res=>{
					if(res.code==0){
						// #ifdef APP-PLUS
							uni.navigateTo({
								url: '/pages/order/alipayWeb?url=' + res.data.codeUrl
							})
						// #endif
						 // #ifdef H5
						let url=res.data.codeUrl
						location.href=url
						// #endif
					}else{
						that.$http.toast(res.msg)
					}
				})
			},
			deleted(){
				// #ifdef H5
				 this.$refs.popupShare.close()
				 // #endif
			},
			paste(type) {
				
				// #ifdef H5
				if (type==='success') {
					this.$http.toast('复制成功');
					this.$refs.popupShare.close()
				} else {
					this.$http.toast('复制失败');
				}
				// #endif
			},
		
		
		},
		onReachBottom() {
			if(this.selectIndex==1){
				if(this.page==this.page_count){
					return false;
				} 		
				this.page=this.page+1
				this.morespellgroup(this.pack_id);
			}
		},
	}
</script>