import {
	getPlatform
} from '@/common/request.js';

import { getLocation} from '@/config/utils'

// #ifdef H5
import wechatSdk from '@/common/wechatJsSdk.js';
// #endif
export default {
	getMapDistanceApi(lng1, lat1, lng2, lat2) { //计算两点之间的距离
		var radLat1 = lat1 * Math.PI / 180.0;
		var radLat2 = lat2 * Math.PI / 180.0;
		var a = radLat1 - radLat2;
		var b = lng1 * Math.PI / 180.0 - lng2 * Math.PI / 180.0;
		var s = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(a / 2), 2) +
			Math.cos(radLat1) * Math.cos(radLat2) * Math.pow(Math.sin(b / 2), 2)));
		s = s * 6378.137; // EARTH_RADIUS;
		s = Math.round(s * 10000) / 10000;

		s = s * 1000

		if (isNaN(s)) {
			return 0;
		}
		return s;
	},
	getLocationDataH5() { //公众号定位
		let that = this
		if (getPlatform() == 'wechat') {
			wechatSdk.location(function(res) {
				if (uni.getStorageSync('x-longitude') && uni.getStorageSync('x-latitude')) {
					uni.setStorageSync('x-longitude-new', res.longitude)
					uni.setStorageSync('x-latitude-new', res.latitude)
				} else {
					uni.setStorageSync('x-longitude', res.longitude)
					uni.setStorageSync('x-latitude', res.latitude)
					uni.setStorageSync('flag', true)
				}
			})
		} else {
			that.locationMp()
		}
	},
	getLocationData() { //微信或者APP定位
		var that = this
		getLocation(function(res){
			if (uni.getStorageSync('x-longitude') && uni.getStorageSync('x-latitude')) {
				uni.setStorageSync('x-longitude-new', res.longitude)
				uni.setStorageSync('x-latitude-new', res.latitude)
			} else {
				uni.setStorageSync('x-longitude', res.longitude)
				uni.setStorageSync('x-latitude', res.latitude)
				uni.setStorageSync('flag', true)
			}
		});
	},
	locationH5() {
		this.getLocationDataH5()
		var time = (parseInt(new Date().getTime() / 1000) + 7200) - parseInt(new Date().getTime() / 1000)
		let temp = setInterval(() => {
			time--
			if (time <= 0) {
				this.getLocationDataH5()
				clearInterval(temp);
			} else {

			}
		}, 1000)
	},
	locationMp() {
		this.getLocationData()
		var time = (parseInt(new Date().getTime() / 1000) + 7200) - parseInt(new Date().getTime() / 1000)
		let temp = setInterval(() => {
			time--
			if (time <= 0) {
				this.getLocationData()
				clearInterval(temp);
			} else {

			}
		}, 1000)
	},
}
