import Vue from 'vue'
import Vuex from 'vuex'
import fetch from '../common/request.js'

Vue.use(Vuex)

const store = new Vuex.Store({
	state: {
		version: "1.4.2",
		isLogin: uni.getStorageSync("token") ? true : false,
		openid: "",
		userInfo: { // 用户信息
			longitude: null, // 经度
			latitude: null, // 纬度
			locationCity: '' // 定位城市
		}
	},
	mutations: {
		login(state, payload) {
			if (payload) {
				state.mobile = payload.mobile;
				state.memberId = payload.memberId;
			}
			state.isLogin = true;
		},
		logout(state) {
			state.mobile = "";
			state.memberId = 0;
			state.isLogin = false;
		},
		setOpenid(state, openid) {
			state.openid = openid;
		},
		// 设置用户信息
		setUserInfo(state, payload) {
			for (let i in payload) {
				for (let j in state.userInfo) {
					if (i === j) {
						state.userInfo[j] = payload[i]
					}
				}
			}
		},
		setTabbar(state,tabbars){
			state.tabbars = tabbars;
		}
	},
	actions: {
		async setTabbar(){
			console.log(Vue,"vuex");
		}
	}
})

export default store
