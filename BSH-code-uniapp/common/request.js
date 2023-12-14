//公共js,以及基本方法封装 nvue里使用
import {
	isWeChat
} from '@/utils/util.js';
import {user} from './api.js';
import $bridge from './bridge.js';

const fetch = {
	toast: function(tips) {
		uni.showToast({
			title: tips || "出错啦~",
			icon: 'none',
			duration: 2000
		})
	},
	setToken: function(token) {
		uni.setStorageSync("token", token)
	},
	getToken() {
		return uni.getStorageSync("token")
	},
	isLogin: function() {
		return uni.getStorageSync("token") ? true : false
	},
	logout: function() {
		uni.removeStorageSync("token");
		uni.removeStorageSync('userInfo');
		fetch.toast('登出成功')
		uni.reLaunch({
			url: '/pages/index/index'
		})
	},
	// 获取当前环境
	getPlatform() {
		let platform = 'h5';
		// #ifdef H5
		platform = isWeChat() ? 'wechat' : 'h5';
		// #endif
		// #ifdef MP-WEIXIN
		platform = 'mp-wx'
		// #endif
		// #ifdef MP-ALIPAY	
		platform = 'mp-ali'
		// #endif
		// #ifdef MP-BAIDU
		platform = 'mp-bd'
		// #endif
		// #ifdef MP-TOUTIAO
		platform = 'mp-tt'
		// #endif
		// #ifdef APP-PLUS
		platform = 'app'
		// #endif
		return platform;
	},
	getUrlParam: function(name, pageObj) {

		var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)')
		// let url = window.location.href.split('#')[0]
		let url = window.location.href;
		let search = url.split('?')[1]
		if (search) {
			var r = search.substr(0).match(reg)
			if (r !== null) {
				return unescape(r[2])
			}
			return null
		} else {
			return null
		}
	},
	request: function(requestData) {

		let currPage;
		let pages = getCurrentPages();
		// #ifdef MP-WEIXIN || APP-PLUS
		if(pages.length > 0){
			currPage = pages[pages.length - 1].$vm; // 当前页路由
		}
		// #endif
		// #ifdef H5
		let prevPage = pages[pages.length - 1]; // 上一级路由
		currPage = pages[pages.length - 1]; // 当前页路由
		// #endif

		let platform = fetch.getPlatform();
		let url = requestData.url,
			postData = requestData.data,
			method = requestData.method,
			showLoading = requestData.showLoading,
			isformData=requestData.isformData?'multipart/form-data; boundary=XXX':'application/json'
		//接口请求
		method = (method === 'post' || method === 'POST') ? 'POST' : 'GET';
		if(!url) return false;
		if (showLoading) {
			uni.showLoading({
				mask: true,
				title: '请稍候...'
			})
		}
		const access_token = fetch.getToken() || "";
		var city=uni.getStorageSync('x-city-name') || "广州"
		var header={
			'content-type': isformData,
			// 'x-mall-id': uni.getStorageSync("mall_id") || 4,
			'x-mall-id': uni.getStorageSync("mall_id") || 5,
			'x-access-token': access_token,
			// 'x-access-token': 'amkzkqVxm7OS5EQEwtTXN6-gUKhC7Wi4',
			'x-parent-id': uni.getStorageSync('pid') || -1,
			'x-source': uni.getStorageSync('source') || 0,
			'x-app-platform': platform,
			'x-city-id':uni.getStorageSync('x-city-id')||-1,
			// 'x-city-name':encodeURIComponent(city),
			'x-longitude':uni.getStorageSync("x-longitude"),
			'x-latitude':uni.getStorageSync('x-latitude'),
			'x-sub-mch-id': uni.getStorageSync("x-sub-mch-id") || 0,
			'x-man-mch-id': uni.getStorageSync("x-man-mch-id")?uni.getStorageSync("x-man-mch-id"):'',
		}
		// #ifdef MP-WEIXIN
		const accountInfo = wx.getAccountInfoSync();
		header['x-mp-appid'] = accountInfo.miniProgram.appId;
		header['x-mp-version'] = accountInfo.miniProgram.version;
		// #endif
		return new Promise((resolve, reject) => {
			uni.request({
				url: url,
				data: postData,
				header: header,
				method: method,
				dataType: 'json',
				success: (res) => {
					if (res.statusCode === 500) {
						fetch.toast("服务错误~")
						reject(res)
					}
					showLoading && uni.hideLoading();
					
					/* if(typeof res.data['clean_bind_parent'] != "undefined" && 
						res.data.clean_bind_parent == 1){
						uni.removeStorageSync("pid");
					} */
					
					if (res.data.code == -1) {
						// #ifdef H5
						let preUrl = prevPage.$route.fullPath;
						uni.setStorageSync("_login_pre_url", preUrl);
						// #endif
						uni.removeStorageSync("token")
						uni.removeStorageSync("initMenus")
						if(uni.getStorageSync('userInfo')){
							uni.removeStorageSync('userInfo');
						}
						
						uni.removeStorageSync('addressID')
						
						uni.showModal({
							title: "提示",
							content: "您还未登录，去登录吧~",
							confirmText: "去登录",
							showCancel:false,
							success: (res) => {
								if (res.confirm) {
									uni.navigateTo({
										url: '/pages/public/login'
									});
								}else{
									
									let _currRoute = ''
									// #ifdef H5
									_currRoute = currPage.route;
									// #endif
									// #ifdef MP-WEIXIN || APP-PLUS
									_currRoute = currPage.__route__;
									// #endif
									if(_currRoute.indexOf("cart") == -1 && _currRoute.indexOf("goods") == -1){
										uni.navigateBack();
									}
								}
							}
						});
					} else if (res.data.code == 2) {
						uni.navigateTo({
							url: `/pages/public/bind`
						})
					} else if( res.data.code == 6 ){			
						// #ifdef H5
						let preUrl = prevPage.$route.fullPath;
						uni.setStorageSync("_login_pre_url", preUrl);
						// #endif
						
						uni.navigateTo({
							url: '/pages/public/bindParent'
						});
					}else if( res.data.code ==999){						
						fetch.toast(res.data.msg)						
					}	

					if(typeof res.data.clean_header_sub_mch_id != "undefined" 
						&& res.data.clean_header_sub_mch_id == 1){
						uni.removeStorageSync("x-sub-mch-id")
					}

					resolve(res.data)
				},
				fail: (res) => {
					console.log(res)
					fetch.toast("网络不给力，请稍后再试~")
					reject(res)
				}
			})
		})
	},
	// 上传头像
	uploadFile: function(requestData) {
		let {
			serverUrl,
			file,
			fileKeyName
		} = requestData;
		let platform = fetch.getPlatform();
		const access_token = fetch.getToken() || "";

		return new Promise((resolve, reject) => {
		
			uni.uploadFile({
				url: serverUrl,
				name: fileKeyName,
				header: {
					'x-mall-id': uni.getStorageSync("mall_id") || 5,
					// 'x-access-token': '_HyYN8d_1Q-TH2ItV7ct28oMSjmXcYmM',
					'x-access-token': access_token,
					'x-parent-id': uni.getStorageSync('parentId') || -1,
					'x-app-platform': platform
				},
				formData: {},
				filePath: file,
				success: function(res) {
					if (res.statusCode === 500) {
						fetch.toast("服务错误~")
						reject(res)
					}
					resolve(JSON.parse(res.data))
				},
				fail: function(err) {
					fetch.toast("网络不给力，请稍后再试~")
					reject(err)
				}
			})

		})

	},
	setUserInfo: function(bool) {
		// this.loading = bool;
		fetch.request({
				url: user.userInfo,
				method: 'POST'
			})
			.then(res => {
				// this.loading = false;
				if (res.code == 0) {
					this.userInfo = res.data;
					let recommend_id = res.data.parent_id||0;
					uni.setStorageSync('userInfo', JSON.stringify(res.data));
					uni.setStorageSync('recommend_id', recommend_id);
					if(!recommend_id){
						uni.navigateTo({
							url:'/pages/public/bindParent'
						})
					}
				} else {
					uni.removeStorageSync('userInfo');
				}
			});
	}
}

module.exports = {
	request: fetch.request,
	uploadFile: fetch.uploadFile,
	toast: fetch.toast,
	isLogin: fetch.isLogin,
	setToken: fetch.setToken,
	getToken: fetch.getToken,
	getUrlParam: fetch.getUrlParam,
	logout: fetch.logout,
	setUserInfo: fetch.setUserInfo,
	getPlatform: fetch.getPlatform
}
