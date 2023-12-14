<script>
	const bsh_key = '$#K23@#J$K@#51K*()JKL4>:JM!&1*!';
	
	import $bridge from './common/bridge.js';
	import $md5 from './common/md5.js';
	export default {
		onLaunch: function() {
			
			//#ifdef H5
			if(uni.getStorageSync('x-longitude')&&uni.getStorageSync('x-latitude')){
				
			}else{
				 this.$unifylocation.locationH5()	
			}   
			// #endif
			
			// #ifndef H5
			if(uni.getStorageSync('x-longitude')&&uni.getStorageSync('x-latitude')){
				
			}else{
				this.$unifylocation.locationMp()
			}
			// #endif  


			//console.log('App Launch');
			// 初始化项目就执行自定义分享
			// this.$wechatSdk.share();
			this.initMall();
			
			// #ifdef H5
			if (this.$route.query.mall_id) {
				uni.setStorageSync('mall_id', this.$route.query.mall_id);
			}
			// #endif

			/* plus.runtime.getProperty(plus.runtime.appid, function(widgetInfo) {
				//APP更新
				//console.log('请求更新前提交的版本='+widgetInfo);
				that.$http.request({
					url: that.$api.app_update,
					data: {
						version_number: widgetInfo.version
					}
				}).then(res => {
					//console.log('更新接口返回=='+JSON.stringify(res));
					if (res.code == 0) {
						if (res.data.status == 1) { //1代表有新版本
							if (res.data.type == 0) { //0：全部更新

								uni.showModal({
									title: '提示',
									showCancel: res.data.compulsive == 0 ? true :
									false, //是否强制更新：0：否 1：是
									content: res.data.remark,
									success: function(res2) {
										if (res2.confirm) {
											plus.runtime.openURL(res.data.address);
										} else if (res2.cancel) {

										}
									}
								})
							} else if (res.data.type == 1) { //1：部分更新
								uni.showModal({
									title: '提示',
									content: res.data.remark,
									showCancel: res.data.compulsive == 0 ? true :
									false, //是否强制更新：0：否 1：是
									success: function(res2) {
										if (res2.confirm) {
											uni.navigateTo({
												url: '/pages/about/about?pageSource=2'
											})
										}
									}
								})
							}
						}
					} else {

					}
				}) 
			}); */
		},

		onShow: function(options) {
			
			this.beforeOnLoad(options);
			
			// #ifdef APP-PLUS
			let that = this;
			$bridge.getVersion(null, function(v){
				let currentVersionCode = typeof v != "undefined" ? parseInt(v.version_code) : 0;
				if(currentVersionCode <= 0) return;
				that.$http.request({
					url: that.$api.app.version_info,
					data: {platform:v.platform}
				}).then(res => {
					if (res.code == 0 && currentVersionCode < parseInt(res.data.version_code)) {
						uni.showModal({
							title: '发现新版本',
							showCancel: false,
							content: "版本："+res.data.version_name+"\n请点击确定更新",
							success: function(res2) {
								if (res2.confirm) {
									plus.runtime.openURL(res.data.download_link);
								} else if (res2.cancel) {
						
								}
							}
						})
					}
				});
			})
			// #endif
	
			// #ifdef MP-WEIXIN
			if (uni.getUpdateManager) {
				const updateManager = uni.getUpdateManager();
				updateManager.onCheckForUpdate(function(res) {
				});
				updateManager.onUpdateReady(function(res) {
					uni.showModal({
						title: "更新提示",
						content: "新版本已经准备好，是否重启应用？",
						success(res) {
							if (res.confirm) {
								updateManager.applyUpdate();
							}
						}
					});
				});
				updateManager.onUpdateFailed(function(res) {
					uni.showModal({
						title: "已经有新版本了哟~",
						content: "新版本已经上线啦~，请您删除当前小程序，重新搜索打开哟~",
						showCancel: false
					});
				});
			}
			
			//其它小程序跳转过来
			/* options['referrerInfo'] = {
				extraData: {
					auth: 'xeKLyyB5gsw99J2qCCSvGbmfUSjNQZlC',
					token: 'G4MNbAFPqDKHZuwmBqBALTgwgctmJTkI'
				}
			}; */
			if(options.referrerInfo){
				let referrerInfo = options.referrerInfo;
				if(referrerInfo.extraData && referrerInfo.extraData.auth && referrerInfo.extraData.token){
					uni.removeStorageSync("token");
					if(referrerInfo.extraData.sign){ //如果有传签名，验证签名
						let extraData = referrerInfo.extraData, sign = $md5.hex_md5(extraData.token+bsh_key+extraData.timestamp);
						if(sign == extraData.sign){
							uni.setStorageSync("token", referrerInfo.extraData.auth);
							let url = '/smartshop/order/pay?token=' + referrerInfo.extraData.token;
							setTimeout(function(){
								uni.redirectTo({
									url: url
								});
							}, 500);
						}else{
							uni.showModal({
								title: "提示",
								content: "签名验证失败",
								showCancel: false
							});
						}
					}else{
						let url = '/pages/order/pay?token=' + referrerInfo.extraData.token;
						setTimeout(function(){
							uni.redirectTo({
								url: url
							});
						}, 500);
					}
					
				}else{
					uni.removeStorageSync("auth_pay");
					uni.removeStorageSync("auth_token");
				}
			}
			
			// #endif

			// #ifdef H5
			/* if(options.query.pid){
				setTimeout(function(){
					uni.navigateTo({
						url:'/pages/user/bindUser/bindUser?pid=' + options.query.pid
					});
				}, 1000);
			} */
			// #endif
	
		},
		onHide: function() {
		
		},
		methods: {
			initMall() {
				this.$http.request({
					url: this.$api.index.config
				}).then(res => {
					if (res.code === 0) {
						let {
							cat_style,
							copyright,
							mall_setting,
							navbar,
							page_title
						} = res.data;
						uni.setStorageSync('tabbars', JSON.stringify(navbar));
						uni.setStorageSync('mall_config', JSON.stringify(res.data));
						uni.setStorageSync('currencyAlias', JSON.stringify(res.currency_alias));
						this.$nextTick();
					}
				});

			},
		}
	};
</script>

<style>

	/*每个页面公共css */
	@import './common/app.css';
	/* @import './static/font-icon/iconfont.css'; */
	@import './plugins/font-icon/iconfont.css';
	/* #ifdef H5 */
	/* //修复H5底部导航挡住内容bug */
	/* uni-app {
	height: auto;
} */

	/* //修复H5输入框上下不居中bug */
	/* .uni-input-form {
	height: 100%;
} */
	/* //去除地图上高德地图标识符 */
	.amap-copyright {
		display: none !important;
	}

	.amap-logo {
		display: none !important;
	}

	.amap-ui-control-zoom {
		width: 60rpx !important;
	}

	.amap-ui-control-zoom>* {
		width: 60rpx !important;
		height: 60rpx !important;
		line-height: 60rpx !important;
	}

	.amap-ui-control-theme-dark {
		display: none !important;
	}

	/* #endif */
</style>
