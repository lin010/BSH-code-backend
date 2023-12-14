<template>
	<view class="ercode_container">
		
		<unipopup ref="popup" type="center">
			<view class="popup_view">
				<view class="popup_title">
					请选择兑换城市(必选)
				</view>
				<view class="popup-city">
					<text :class="selectINdex==index?'active':''"   v-for="(item,index) in cityList" :key='index' @click="select(index,item.codenum)">
						{{item.name}}
					</text>
				</view>
				<view class="sure" @click="sure">确定</view>
			</view>
		</unipopup>
		<view v-if="errorShow" >
			<view style="background:white;padding:100rpx 0;display:flex;flex-direction: column;align-items: center;">
				<icon type="warn" size="90"/>
				<text style="font-size:70rpx;color:#CC3311;margin-top:10rpx;">{{errorText}}</text>
			</view>
			<view style="text-align: center;color:#555;margin-top:30rpx;">
				<view>请刷新页面重试</view>
				<view>仍然无法兑换，请联系客服进行处理</view>
			</view>
		</view>
		
		<view v-if="successShow" >
			<view style="background:white;padding:100rpx 0;display:flex;flex-direction: column;align-items: center;">
				<icon type="success" size="90" />
				<text style="font-size:70rpx;color:green;margin-top:10rpx;">兑换成功</text>
			</view>
			<view @click="openLa" style="margin-top:50rpx;text-align:center;">
				<text style="background:white;border-radius:90rpx;width:410rpx;height:90rpx;line-height:90rpx;border:1px solid rgb(253, 109, 100);color:rgb(253, 109, 100);display: inline-block;">打开中石化小程序使用</text>
			</view>
		</view>
		
		
	</view>
</template>

<script>
	import unipopup from '@/components/uni-popupoil/uni-popup';
	export default {
		components:{
			unipopup,
		},
		data() {
			return {
				plugins_img_url: this.$api.plugins_img_url,
				detail:'',
				cityList:[
					{
						name:'广东',
						codenum:'1941',
					},
					{
						name:'广西',
						codenum:'2088',
					},
				],
				selectINdex:null,
				form:{
					id:'',
					use_province:'',//省份：2088（广西），1941（广东）
				},
				useDialogVisible: false,
				errorText: "",
				errorShow: false,
				successShow: false
			};
		},
		onLoad(options) {
			
			this.beforeOnLoad(options);
			console.log(options);
			if(options.mpOpen){
				this.mpOpen(options.province);
			}else{
				this.form.id = options.id;
				this.getOrder();
			}
			
		},
		methods:{
			getOrder(){
				let that = this;
				this.$http.request({
					url: this.$api.oil.getoilOrderdetail,
					method: 'POST',
					data: {id:this.form.id},
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						if(res.data.order_status == "finished"){ //已通过兑换码兑换中石化加油券
							that.detail = res.data;
							that.successShow = true;
						}else if(res.data.order_status == "wait"){ //已生成兑换码，兑换
							that.form.use_province = res.data.province_id;
							that.exchange();
						}else{
							that.useDialogVisible = true;
							that.$refs.popup.open();
						}
					} else {
						that.$http.toast(res.msg);
					}
				});
			},
			select(index,use_province){ //选择城市
				this.selectINdex=index
				this.form.use_province=use_province
			},
			sure(){ //点击确定
				if(this.selectINdex==null){
					this.$http.toast('请选择城市');
					return
				}else{
					this.$refs.popup.close()
					this.getcode()
				}
			},
			getcode(){ //获取二维码
				let i, province = ""; 
				for(i=0; i < this.cityList.length; i++){
					if(this.cityList[i].codenum == this.form.use_province){
						province = this.cityList[i].name;
					}
				}
				let that = this;
				uni.showModal({
				    title: '提示',
				    content: '你确定要兑换【'+province+'】的加油券吗？一旦兑换，将无法更改！',
				    success: function (res) {
				        if (!res.confirm) {
							that.$refs.popup.open();
							return;
						}
				        that.$http.request({
				        	url: that.$api.oil.getoilcode,
				        	method: 'POST',
				        	data:that.form,
				        	showLoading: true
				        }).then(res => {
				        	if (res.code == 0) {
								that.exchange();
				        	} else {
				        		that.$http.toast(res.msg);
				        	}
				        });
				    }
				});
			},
			exchange(){ //执行兑换操作
				let that = this;
				this.$http.request({
					url: this.$api.oil.getoilcode,
					method: 'POST',
					data:this.form,
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						that.detail=res.data;
						that.successShow = true;
					} else {
						that.errorText = res.msg;
						that.errorShow = true;
					}
				});
			},
			copy(serialize_no){
				let that=this
				uni.showToast({ title:'复制成功' })
				wx.setClipboardData({
					data:"兑换码："+that.detail.couponCode,
					success:function(res){
						wx.getClipboardData({
							success:function(result){
								console.log(result.data)
							}
						})
					}
				})
			},
			paste(type) {
				if (type==='success') {
					uni.showToast({ title:'复制成功' })
				} else {
					uni.showToast({ title:'复制失败' })
				}
			},
			openLa(){

				// #ifdef H5
				if(this.detail.province_id == 1941){ //广东
					location.href = 'weixin://dl/business/?t=SB1PWMRBaAe';
				}else{ //广西
					location.href = 'weixin://dl/business/?t=FwPuOXouEzs';
				}
				// #endif
				
				// #ifdef MP-WEIXIN
				uni.navigateTo({
				    url: "/oil/ercode/ercode?mpOpen=1&province=" + this.detail.province_id
				});
				// #endif
				
				// #ifdef APP-PLUS
				uni.showToast({ title:'业务暂未实现' })
				// #endif
			},
			mpOpen(province){
				let options = {
					//path: "/",
					envVersion: 'release',// 打开正式版
					extraData: {},
					success(res) {},
					fail: function (err) {console.log("error", err);}
				};
				console.log(province);
				if(province == 1941){ //广东
					options['appId'] = "wx32fe2eb8b2fa221f";
				}
				if(province == 2088){ //广西
					options['appId'] = "wxbfe24664b9cf5b3b";
				}
				
				if(typeof options['appId'] == "undefined"){
					uni.showToast({ title:'来源参数错误' });
					return;
				}
				
				uni.showModal({
				    title: '提示',
				    content: '点确定打开中石化小程序',
					showCancel: false,
				    success: function (res) {
				        wx.navigateToMiniProgram(options);
				    }
				});
			},
			
			/* goTowp(){ //跳到兑换小程序
				// #ifdef H5||APP-PLUS
				this.$http.toast('请使用小程序打开本页面')
				// #endif
				
				//#ifdef MP-WEIXIN
				let appId=this.detail.mpwx_app_id
				let path=this.detail.mpwx_path
				 wx.navigateToMiniProgram({
					appId: appId,
					path: path,
					extraData: {
						mobile: this.detail.mobile,
						couponCode: this.detail.couponCode
					},
					envVersion: 'release',// 打开正式版
					success(res) {
						   // 打开成功
					},
					fail: function (err) {
						console.log(err);
					}
				})
				//#endif
			} */
		}
	}
</script>

<style lang="less">
	.ercode_container{width: 100%;overflow: hidden;position: relative;}
	.code{width: 100%;height: 100%;position: absolute;top: 0;left: 0;}
	.codeImg{width: 400rpx;height: 400rpx;margin: 80rpx auto 0;}
	.btn{background: none;width: 180rpx;font-size: 28rpx;height: 60rpx;line-height: 60rpx;color: #fff;background: rgb(255, 113, 4);margin: 0 auto;text-align: center;border-radius: 15rpx;}
	.popup_view{width: 500rpx;height: 450rpx;background: #fff;border-radius: 30rpx;}
	.popup_title{width: 100%;height: 120rpx;text-align: center;line-height: 120rpx;font-size: 32rpx;color: #000;}
	.popup-city{width: 100%;overflow: hidden;display: flex;justify-content: space-evenly;margin: 40rpx 0 80rpx 0;}
	.popup-city text{display: block;width: 200rpx;height: 80rpx;text-align: center;line-height: 80rpx;border-radius: 20rpx;color: #000;font-weight: bold;}
	.active{background: #de3b2d;color: #fff!important;}
	.sure{width: 90%;height: 80rpx;background: red;text-align: center;line-height: 80rpx;margin: 0 auto;border-radius: 20rpx;color: #fff;}
</style>
