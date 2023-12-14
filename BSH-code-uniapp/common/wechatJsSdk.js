import {
	request
} from './request.js'
import api from './api.js'
var jweixin = require('jweixin-module');
export default {
	getUrl() {
		let pages = getCurrentPages();
		let curPage = pages[pages.length - 1];
		let url = curPage.$route.fullPath;
		return url.substring(1,url.length);
	},
	
	//判断是否在微信中  
	isWechat: function() {
		var ua = window.navigator.userAgent.toLowerCase();
		if (ua.match(/micromessenger/i) == 'micromessenger') {
			// console.log('是微信客户端')
			return true;
		} else {
			// console.log('不是微信客户端')
			return false;
		}
	},
	//初始化sdk配置  
	initJssdk: function(callback, url) {
		// 这是我这边封装的 request 请求工具，实际就是 uni.request 方法。
		// let root_url = encodeURIComponent(window.location.href.split('#')[0]);
		let root_url = window.location.href.split('#')[0];
		request({
			url: api.wechat.jssdk_config,
			data: {
				url: root_url
			}
		}).then((res) => {
			if (res.code == 0) {			
				let sdk = res.data.config;
				jweixin.config({
					debug: sdk.debug,
					appId: sdk.appId,
					timestamp: sdk.timestamp,
					nonceStr: sdk.nonceStr,
					signature: sdk.signature,
					jsApiList: sdk.jsApiList,
				});
				//配置完成后，再执行分享等功能  
				if (callback) {
					callback(sdk);
				}
				jweixin.ready(function() {
				   jweixin.hideMenuItems({
				       menuList: [
						   "menuItem:share:appMessage",
						   "menuItem:share:timeline",
						   "menuItem:share:qq",
						   "menuItem:share:weiboApp",
						   "menuItem:favorite",
						   "menuItem:share:facebook",
						   "menuItem:share:QZone",
						   "menuItem:editTag",
						   "menuItem:delete",
						   //"menuItem:copyUrl",
						   "menuItem:originPage",
						   "menuItem:readMode",
						   "menuItem:openWithQQBrowser",
						   "menuItem:openWithSafari",
						   "menuItem:share:email",
						   "menuItem:share:brand"
					   ] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮
				   });
				});
			}

		});
	},
	//在需支付
	pay: function(data, url, paramas) {
		url = url ? url : '/pages/user/user';
		if (!this.isWechat()) {
			return;
		}
		//每需要重新初始化
		jweixin.config({
		  debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
		  appId: data.appId, // 必填，公众号的唯一标识
		  timestamp: data.timestamp, // 必填，生成签名的时间戳
		  // timestamp: data.timeStamp, // 必填，生成签名的时间戳
		  nonceStr: data.nonceStr, // 必填，生成签名的随机串
		  signature: data.signType,// 必填，签名
		  jsApiList: [] // 必填，需要使用的JS接口列表
		});
		
		jweixin.ready(function() {
			jweixin.chooseWXPay({
				// appId: data.appid,
				appId: data.appId,
				timestamp: data.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
				nonceStr: data.nonceStr, // 支付签名随机串，不长于 32 位
				// package: data.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=\*\*\*）
				package: data.wxPackage,
				signType: data.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
				paySign: data.paySign, // 支付签名
				success: function(res) {
					// 支付成功后的回调函数
					uni.redirectTo({
						url: url
					})
				},
				fail: function(e) {
					uni.showToast({
						title: '支付失败!'
					});
					setTimeout((res) => {
						uni.redirectTo({
							url: url
						})
		
					})
				}
		
			});
		});
	},
	share: function(param, diy) {

		if (!this.isWechat()) {
			//return;
		}
		let title = '兰亭全新上线',
			desc = '优惠多多',
			link = '',
			imgUrl = 'https://www.dogedoge.com/assets/new_logo.min.png';
		if (diy) {
			title = diy.app_share_title || '兰亭全新上线';
			desc = diy.app_share_desc || '优惠多多';
			imgUrl = diy.app_share_pic || 'https://www.dogedoge.com/assets/new_logo.min.png'
		}

		// 获取当前 URL
		let url = window.location.href.split("#")[0];
		
		// 截取 URL ?code 前面 URL
		url = url.split('?code')[0];
		// 获取 当前商城id
		let mall_id = uni.getStorageSync("mall_id");
		if(mall_id.length <= 0 || isNaN(mall_id)){
			mall_id = 5;
		}

		// 获取当前用户
		let user = uni.getStorageSync("userInfo") ? JSON.parse(uni.getStorageSync("userInfo")) : {};
		// 判断 param 是否存在 存在则把参数里面字符截取出来 追加在 url 里
		if (param) {
			param = param.split('&mall_id')[0];
			url = `${url}#/${param}`;
		}
		
		// param 存在则 mall_id 前为 & 否则 mall_id 前为 ?
		url = param ? `${url}&mall_id=${mall_id}` : `${url}#/?mall_id=${mall_id}`;

		// 如果当前用户数据存在 则获取当前用户的id 追加在 url 末尾 为 pid的值
		if (user.user_id) {
			url = url + `&pid=${user.user_id}`
		}
		// console.log(url,"分享url");
		//每需要重新初始化
		this.initJssdk(function(sdk) {
		console.log(url);
			jweixin.ready(() => {
				// 分享到朋友圈
				// jweixin.updateTimelineShareData({
				// 	title: '测试分享', // 分享标题
				// 	link: url,
				// 	imgUrl: 'https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_92x30dp.png',
				// 	success: function() {
				// 		// 用户确认分享后执行的回调函数
				// 	},
				// 	cancel: function() {
				// 		// 用户取消分享后执行的回调函数
				// 	},
				// });

				// 分享给朋友
				jweixin.updateAppMessageShareData({
					title: title, // 分享标题
					desc: desc, // 分享描述
					link: url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
					imgUrl: imgUrl, // 分享图标
					success: function(e) {
						console.log(e);
						// 用户点击了分享后执行的回调函数
					}
				});

			})

		}, '')
	},
	/**
	 * param
	 *	data: '分享的数据'
	 * 	url: '自定义的后缀添加url'
	 * msg 获取分享url
	 */
	initShareUrl(data,url){
		if (!this.isWechat()) {
			return;
		}
		let mall_id = uni.getStorageSync("mall_id");
		let user = uni.getStorageSync("userInfo") ? JSON.parse(uni.getStorageSync("userInfo")) : {};
		// let _url = `${window.location.href.split("#")[0]}#/${this.getUrl()}`;
		
		// if(this.getUrl().indexOf('?') == -1 && url){
		// 	_url = `${_url}?${url}`;
		// }else{
		// 	if(url){
		// 		_url = `${_url}&${url}`;
		// 	}
		// }
		// if(_url.indexOf('?') == -1){
		// 	user.user_id ? _url = _url + '?pid='+user.user_id : '';
		// }else{
		// 	user.user_id ? _url = _url + '&pid='+user.user_id : '';
		// }				
		this.shareUrl(data,url);
	},
	//在需要自定义分享的页面中调用
	shareUrl: function(data, url) {
		url = url ? url : window.location.href;
		if (!this.isWechat()) {
			return;
		}
		var pid = uni.getStorageSync("userInfo") ? JSON.parse(uni.getStorageSync("userInfo")).user_id : 0;
		url = url + '&pid=' + pid;
		//每次都需要重新初始化配置，才可以进行分享  
		this.initJssdk(function(signData) {
			jweixin.ready(function() {
				var shareData = {
					title: data && data.app_share_title ? data.app_share_title : signData.site_name,
					desc: data && data.app_share_desc ? data.app_share_desc : signData.site_description,
					link: url,
					imgUrl: data && data.app_share_pic ? data.app_share_pic : signData.site_logo,
					success: function(res) {
						//用户点击分享后的回调，这里可以进行统计，例如分享送金币之类的  
						// post('/api/member/share');
					},
					cancel: function(res) {}
				};
				//分享给朋友接口 
				jweixin.updateAppMessageShareData({
					title: data && data.app_share_title ? data.app_share_title : signData.site_name,
					desc: data && data.app_share_desc ? data.app_share_desc : signData.site_description,
					link: url,
					imgUrl: data && data.app_share_pic ? data.app_share_pic : signData.site_logo,
					success: function(res) {
						
					}
				});
				//分享到朋友圈接口  
				jweixin.updateTimelineShareData(shareData);
			});
		}, url);
	},
	//在需要定位页面调用
	location: function(callback) {
		if (!this.isWechat()) {
			console.log('不是微信客户端')
			return;
		}
		// console.info('定位')
		this.initJssdk(function(res) {
			jweixin.ready(function() {
				console.info('定位ready')
				jweixin.getLocation({
					type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
					success: function(res) {
						// console.log(res);
						callback(res)
					},
					fail: function(res) {
						console.log(res)
					},
					// complete:function(res){
					//     console.log(res)
					// }
				});
			});
		});
	},

}
