import $http from '@/common/request.js';
import $api from '@/common/api.js';
import base from '@/config/baseUrl';
import { getPlatform } from '@/common/request.js';

// #ifdef MP-WEIXIN
// 微信小程序分享
export const wxShare = function (title,path,img) {
	
	let mall_id = uni.getStorageSync("mall_id");
	let token = uni.getStorageSync("token");
	let shareInfo = {
		title: title || base.share.title,
		imageUrl: img || '',
	};
	
	if(path && typeof(path) == "string"){
		shareInfo.path = path;
	}else if(path === undefined){
		shareInfo.path = base.share.path;
	}
	// if (shareInfo.path.indexOf("?") >= 0) {
	// 	shareInfo.path += "&mall_id=" + mall_id;
	// }else{
	// 	shareInfo.path += "?mall_id=" + mall_id;
	// }
	// console.log(this.$route.path);
	shareInfo.path += "&mall_id=5";
	// let curr_path = this.$route.path;
	let curr_path = path;
	if (token) {
		let userInfo = JSON.parse(uni.getStorageSync("userInfo"));
		if(curr_path.indexOf("goods/detail") != -1){ // 分享商品详情才有
			shareInfo.path += "&pid=" + userInfo.user_id;
			//console.log('pid=',userInfo.user_id);
		}
	}
	//console.log(shareInfo,'shareInfo');
	return shareInfo;
}
// #endif

//支付（APP微信支付、APP支付宝支付、微信小程序支付）
export const setPay = function(payInfo, callback) {
	
	let platform = getPlatform();
	// 等接口里面的参数
	let payData = {
		success: function(res) {
			callback && callback({
				success: true,
				data: res
			});
			//console.log('success:' + JSON.stringify(res));
		},
		fail: function(err) {
			callback && callback({
				success: false,
				data: err
			});
			//console.log('fail:' + JSON.stringify(err));
		}
	};
	if (platform == 'mp-wx') {
		// 小程序
		// payData.provider = 'wxpay';
		// payData.timeStamp = payInfo.timestamp;
		// payData.nonceStr = payInfo.nonceStr;
		// payData.package = payInfo.package;
		// payData.signType = payInfo.signType;
		// payData.paySign = payInfo.paySign;
		payData.provider = 'wxpay';
		payData.timeStamp = payInfo.timeStamp;
		payData.nonceStr = payInfo.nonceStr;
		payData.package = payInfo.wxPackage;
		payData.signType = payInfo.signType;
		payData.paySign = payInfo.paySign;
	} else if (platform == 'wxpay') {
		// app微信
		payData.provider = 'wxpay';
		payData.orderInfo = data;
	} else if (platform == 'alipay') {
		// app 支付宝
		payData.provider = 'alipay';
		payData.orderInfo = data;
	} else if (platform == 'baidu') {
		payData.provider = 'baidu';
		payData.orderInfo = data;
	}
	//console.log("支付参数", payData);
	
	uni.requestPayment(payData);
		
}

export const checkHttpUrl = function(domain){
	let http = /^http:\/\/.*/i.test(domain.toLowerCase());
	let https = /^https:\/\/.*/i.test(domain.toLowerCase());
	if (!http && !https) {
	   domain = base.domainUrl + domain;
	}
	return domain;
}

export const navBack = function(){
	//返回方法
	let routes = getCurrentPages();
	if(routes.length == 1){
		uni.redirectTo({
			url: '/pages/index/index'
		})
	}else{
		uni.navigateBack();
	}
}

export const globalSet = function(str,val='重新申请'){
	if(str == 'textCol'){	//红色字&红按钮
		return JSON.parse(uni.getStorageSync('mall_config')).global_color.global_text_color;
	}else if(str == 'imgUrl'){	
		return JSON.parse(uni.getStorageSync('mall_config')).top_pic_url;
	}else if(str == 'couponImg'){	//红包背景图
		return JSON.parse(uni.getStorageSync('mall_config')).global_color.coupon_pic_url;
	}else if(str == 'navBg'){	//tabBar背景色
		return JSON.parse(uni.getStorageSync('mall_config')).navbar.top_background_color;
	}else if(str == 'navCol'){	//tabBar颜色
		return JSON.parse(uni.getStorageSync('mall_config')).navbar.top_text_color;
	}else if(str == 'btnCol'){	
		var global_text_color =JSON.parse(uni.getStorageSync('mall_config')).global_color.global_text_color;
		return [{
				text: "取消",
				plain: true,
				col:global_text_color,
			}, {
				text: val,
				plain: false,
				col:global_text_color,
			}];
	}
}

export const dateFormat  = function(time){
	var dates = new Date(time*1000);
	var y = dates.getFullYear(),
		m = dates.getMonth() + 1,
		d = dates.getDate(),
		h = dates.getHours(),
		mm = dates.getMinutes(),
		s = dates.getSeconds();
	return y+'-'+add0(m)+'-'+add0(d)+' '+add0(h)+':'+add0(mm)+':'+add0(s);
}
function add0(val){
	return val<10?'0'+val:val;
}

export const beforeOnLoad  = function(options){
	// #ifdef MP-WEIXIN
	if(typeof options['scene'] != "undefined"){
		var scene = decodeURIComponent(options.scene), i, arr;
		for (i = 0; i < scene.split('&').length; i++){
			arr = scene.split('&')[i].split('=');
			options[arr[0]] = arr[1];
		}
	}
	// #endif
}

export const getLocation = function(fn){
	uni.getLocation({
	    type: 'gcj02',
	    success: function (res) {
	        if(typeof fn == "function"){
	        	fn.call(this, res);
	        }
	    },
		fail: function(){
			
		}
	});
}

export const chooseLocation = function(fn){
	uni.chooseLocation({
		type: 'gcj02',
		success: function(res) {
			if(typeof fn == "function"){
				fn.call(this, res);
			}
		},
		fail: function(){
			
		}
	})
}