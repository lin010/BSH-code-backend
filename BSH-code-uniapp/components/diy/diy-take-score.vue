<template>
	<div class="out-box" :style="cOutStyle">
		<scroll-view scroll-y="true" :style="{background: data.background ? data.background : 'none',height: (this.window_h - this.top)+'rpx'}">
			<view class="diy-take-score" :style="cStyle">
				<image v-if="data.pic_url" :src="data.pic_url" :style="cImgStyle" mode="scaleToFill"></image>
				<view class="button-box" :style="cButtonStyle" v-if="data.isButton">
					<view>
						<text @click="takeScore"></text>
						<view class="button-box-mask"></view>
					</view>
				</view>
			</view>
		</scroll-view>
	</div>
</template>

<script>
	export default {
		props: {
			value: {
				type:Object,
				default:()=>{}
			},
		},
		components: {
			
		},
		data() {
			return {
				img_url: this.$api.img_url,
				window_h: 0,
				img_h: 0,
				top: 0,
				data:{},
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			}
		},
		computed:{
			cOutStyle(){
				let style = [];
				style.push("height:" + this.window_h + "rpx");
				style.push("margin-top:" + this.top + "rpx");
				return style.join(";");
			},
			cButtonStyle(){
				let left, top, width, height, style = [];
				left   = !isNaN(this.data.button_x) && this.data.button_x > 0 ? this.data.button_x : 0;
				top    = !isNaN(this.data.button_y) && this.data.button_y > 0 ? this.data.button_y : 0;
				width  = !isNaN(this.data.button_w) && this.data.button_w > 0 ? this.data.button_w : 0;
				height = !isNaN(this.data.button_h) && this.data.button_h > 0 ? this.data.button_h : 0;
				
				style.push("left:" + left + "rpx");
				style.push("top:" + top + "rpx");
				style.push("width:" + width + "rpx");
				style.push("height:" + height + "rpx");
				style.push("line-height:" + height + "rpx");
				return style.join(";");
			},
			cStyle(){
				let style = [];
				if(this.data.isPadding){
					
				}
				return style.join(";");
			},
			cImgStyle() {
				let style = [], img_width = this.data.img_width;
				if(img_width && !isNaN(img_width) && parseInt(img_width) > 0){
					style.push("width:" + (img_width > 750 ? 750 : img_width) + 'rpx');
				}else{
					style.push("width:90%");
				}
				
				this.img_h = this.window_h - this.top;
				if(this.data.img_height && !isNaN(this.data.img_height) && parseInt(this.data.img_height) > 0){
					style.push("height:" + this.data.img_height + 'rpx');
				}
				return style.join(";");
			},
		},
		created() {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			this.data = JSON.parse(JSON.stringify(this.value));
			uni.getSystemInfo({
				success: (res) => {
					console.log("getSystemInfo", res);
					this.window_h = ((res.screenHeight)/res.screenWidth) * 750;
					// #ifdef H5
					this.top = (res.windowTop/res.windowWidth) * 750;
					// #endif
					// #ifndef H5
					//this.top = (res.statusBarHeight/res.windowWidth) * 750;
					// #endif
				}
			})
		},
		methods: {
			openUrl(){
				uni.navigateTo({
					url: this.data.link.new_link_url
				})
			},
			takeScore(){
				let that = this;
				this.$http.request({
					url: this.$api.plugin.score.free_score_take,
					method: 'POST',
					data: {id: this.data.send_data ? this.data.send_data.id : ''},
					showLoading: true
				}).then(res => {
					if(res.code == 0){
						uni.showModal({
							title: '恭喜您',
							content: this.currencyAlias.integral_alias+'领取成功',
							showCancel: false,
							confirmText: '去使用',
							success: function (res) {
								if (res.confirm) {
									that.openUrl();
								}
							}
						});
					}else{
						this.$http.toast(res.msg);
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
.share-flow{box-shadow: 0 0 35rpx #ddd;border-radius:90rpx;width:90rpx;height:90rpx;position:fixed;right:20rpx;top:0;background:white;}
.share-flow > view{font-size:28rpx;width:100%;height:90rpx;display:flex;flex-direction: column;justify-content: center;align-items: center;}
.share-flow image{width:90rpx;height:90rpx;}

.out-box{position:fixed;left:0;top:0;background:RGBA(255, 255, 255, 0.5);width:100%;display:flex;justify-content: center;overflow-y: auto;}
.diy-take-score{position:relative;display:flex;justify-content: center;width:100%;min-height:100rpx;background:white;padding-top:0rpx;padding-bottom:0rpx;}
.diy-take-score image{width:90%;}
.button-box{position:absolute;left:0;top:0;width:200rpx;height:70rpx;line-height:70rpx;}
.button-box > view{width:100%;height:100%;position:relative;text-align:center;}
.button-box > view text{position:absolute;left:0;top:0;z-index:9;width:100%;height:100%;color:white;}
.button-box-mask{z-index:8;left:0;top:0;width:100%;height:100%;position:absolute;background: rgba(0, 144, 127, 0.0)}
</style>
