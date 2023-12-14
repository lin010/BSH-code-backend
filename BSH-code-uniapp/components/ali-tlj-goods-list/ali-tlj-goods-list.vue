<template>
	<view>
		<view v-if="detail_mode" class="detail-app">
			<view style="position:relative;width:100%;height:100%;">
				<view class="detail-app-mask"></view>
				<view class="detail-app-con">
					<template v-if="detail">
						<view class="con" style="padding-bottom:30rpx;">
							<text class="con-title">{{detail.title}}</text>
							<text style="font-size:50rpx;color:rgb(255, 113, 4)">¥{{detail.reserve_price}}</text>
							<image :src="detail.pict_url" mode="aspectFill"></image>
						</view>
						<view class="con" style="padding-bottom:30rpx;">
							<view class="con-url">
								<image :src="plugins_img_url+'/web-link.png'" mode="aspectFill" style=""></image>
								<text>{{detail.url}}</text>
							</view> 
							<view class="con-copy">
								<text @click="copyUrl">点击复制</text>
							</view>
							<view style="margin-top:30rpx;color:#000;text-align:center;font-size:30rpx;">请点击复制链接，在浏览器打开</view>
						</view>
					</template>
					<view @click="hideDetail" style="color:red;background:#eee;padding:20rpx 0;text-align:center;">
						<text >关闭</text>
					</view>
				</view>
			</view>
		</view>
		
		<view class="container" >
			<view class="container-goodsList">
				<template v-for="(item,index) in list">
					<view class="goodsList-item" @click="showDetail(item)">
						<view class="goodsList-item-img">
							<image :src="item.pict_url" mode="aspectFill"></image>
						</view>
						<view class="goodsList-item-title">
							{{item.title}}
						</view>
						<view class="money_num">
							<view class="money" style="width: 50%;color: rgb(255, 113, 4);">
								<text style="font-size: 26rpx;">￥</text>
								<text style="font-size: 28rpx;">{{item.reserve_price}}</text>
							</view>
							<view style="width: 50%;font-size: 24rpx;line-height: 50rpx;text-align: right;">
								<text>30天售{{item.volume}}件</text>
							</view>
						</view>
						<view class="send" style="position: relative;">
							<view class="send_imag"></view>
							<text style="font-size: 27rpx;position: absolute;top: 11rpx;left: 80rpx;color: #fff;">999{{currencyAlias.silver_beans_alias}}</text>
						</view>
					</view>
				</template>
				<!-- <view class="no-more" v-if="list.length==0">
					<image :src="img_url+'/giftOrder_logo.png'" mode=""></image>
					<text>暂无服务内容</text>
				</view> -->
			</view>
		</view>
	</view>
</template>

<script>

	export default {
		name:"AliTljGoodsList",
		components: {},
		props:{},
		mounted() {
			
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			
			console.log("mounted");
			let that = this;
			uni.$on('ali_search_action:cat', function(data){
				console.log("ali_search_action:cat");
				that.form.cat_id = data.cat;
				that.form.page = 1;
				that.list = [];
				that.getgoodList();
			});
			uni.$on('ali_search_action:page', function(data){
				console.log("ali_search_action:page");
				that.form.page = that.form.page + 1;
				that.getgoodList();
			});
			uni.$on('ali_search_action:kw', function(data){
				console.log("ali_search_action:kw");
				that.form.kw = data.kw;
				that.list = [];
				that.getgoodList();
			});
		},
		destroyed(){
			console.log("destroyed");
			uni.$off("ali_search_action:cat");
			uni.$off("ali_search_action:kw");
			uni.$off("ali_search_action:page");
		},
		data() {
			return {
				plugins_img_url:this.$api.plugins_img_url,
				img_url: this.$api.img_url,
				detail_mode: 0,
				detail: {},
				form: {
					page: '',
					cat_id: '',
					kw: ''
				},
				list: [],
				page_count: '',
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		methods: {
			showDetail(item){
				this.detail_mode = 1;
				this.detail = item;
			},
			hideDetail(){
				this.detail_mode = 0;
			},
			copyUrl(){
				uni.setClipboardData({
				    data: this.detail.url,
				    success: function () {
				        
				    }
				});
			},
			getgoodList() { //获取淘礼金商品
				let that=this;
				this.$http.request({
					url: this.$api.taolijin.getgoodsList,
					method: 'post',
					showLoading: true,
					data: this.form
				}).then((res) => {
					if (res.code == 0) {
						if(res.data.no_special_id == 1){
							let ali_id = res.data.ali_id;
							uni.$on('ali_auth_status', function(data){
								that.bindSpecialId(ali_id);
							})
							uni.navigateTo({
								url: '/ali/taolijin/auth/auth?ali_id=' + ali_id
							})
							return;
						}
						if (res.data.list.length == 0) 
							return false
						let goodslist = res.data.list;
						var arr = this.list.concat(goodslist)
						this.list = arr
						this.page_count = res.data.pagination.page_count;
					} else {
						this.$http.toast(res.msg);
					}
				})
			},
			bindSpecialId(ali_id) { //私域用户关系绑定
				let that = this;
				this.$http.request({
					url: this.$api.taolijin.bindSpecialId,
					method: 'post',
					showLoading: true,
					data: {ali_id: ali_id}
				}).then((res) => {
					if (res.code == 0) {
						that.getgoodList();
					} else {
						that.$http.toast(res.msg);
					}
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
	
	.container-goodsList {
		width: 95%;
		overflow: hidden;
		display: flex;
		justify-content: space-between;
		/* #ifdef H5 */
		margin: 210rpx auto 60rpx;
		/* #endif */
		/* #ifdef MP||APP-PLUS */
		margin: 220rpx auto 60rpx;
		/* #endif */
		flex-wrap: wrap;
	}

	.goodsList-item {
		width: 48%;
		overflow: hidden;
		border-radius: 15rpx;
		box-shadow: 0px 0px 10px #eee;
		background: #FFFFFF;
		margin-top: 20rpx;
	}

	.goodsList-item-img {
		width: 100%;
		overflow: hidden;
	}

	.goodsList-item-img image {
		width: 100%;
	}

	.goodsList-item-title {
		width: 100%;
		padding: 0 10rpx;
		font-size: 28rpx;
		color: #000;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 1;
		overflow: hidden;
	}

	.money_num {
		width: 100%;
		padding: 0 10rpx;
		box-sizing: border-box;
		display: flex;
		justify-content: space-between;
	}

	.send {
		padding: 0 10rpx;
		box-sizing: border-box;
	}

	.send_imag {
		min-width: 240rpx;
		display: inline-block;
		text-align: center;
		font-size: 24rpx;
		height: 80rpx;
		background: url(@/mch/img/song.png);
		background-repeat: no-repeat;
		background-size: 100% 70%;
		color: #fff;
	}

	.no-more {
		width: 200rpx;
		height: 280rpx;
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		margin: auto;
		bottom: 0;
		font-size: 26rpx;
		text-align: center;
		color: #808080;
	}

	.no-more image {
		width: 200rpx;
		height: 200rpx;
	}
	
	
	.detail-app {
		width:100%;
		height:100%;
		position: fixed;
		left:0;
		top:0;
		z-index:999999;
	}
	.detail-app-mask{filter:alpha(Opacity=80);-moz-opacity:0.5;opacity: 0.5;position:absolute;left:0;top:0;width:100%;height:100%;background:black;}
	.detail-app-con{position:absolute;left:5%;top:10%;width: 90%;background:#F1F1F1;}
	.con{background:white;display:flex;justify-content: center;flex-direction: column;text-align:center;align-items:center;}
	.con > image{margin-top:30rpx;width:350rpx;height:350rpx;}
	.con-title{margin-top:30rpx;color:#000;text-align:center;display:inline-block;width:80%;}
	.con-url{border-radius:10rpx;height:100rpx;margin-top:30rpx;border:1px solid #ddd;width:80%;display:flex;justify-content: flex-start;background:#fafafa}
	.con-url > image{margin-left:20rpx;margin-top:15rpx;width:82rpx;height:70rpx;}
	.con-url > text{height:97rpx;line-height:97rpx;width:499rpx;padding-left:20rpx;text-align:left;display:inline-block;text-decoration:underline;color:#3194d0;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;}
	.con-copy{}
	.con-copy > text{display:inline-block;background:white;border:1px solid rgb(255, 113, 4);color:rgb(255, 113, 4);width:260rpx;height:70rpx;line-height:66rpx;border-radius:70rpx;margin-top:30rpx;}
	
</style>
