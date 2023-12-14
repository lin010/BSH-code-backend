<template>
	<view class="join_container">
		<view class="jojin_list">
			<view class="jojin_item">
				<view style="width: 35%;">
					<text style="margin: 0 5rpx;color: red;">*</text>
					<text style="color: #000;">营业执照名称</text>
				</view>
				<view style="width: 64%;">
					<input type="text" v-model="params.license_name" placeholder="请填写您的营业执照名称"
						style="line-height: 120rpx;display: block;height: 120rpx;border: none;text-align: right;" />
				</view>
			</view>
			<view class="jojin_item">
				<view style="width: 35%;line-height: 180rpx;">
					<text style="margin: 0 5rpx;color: red;">*</text>
					<text style="color: #000;">营业执照图片</text>
				</view>
				<view style="width: 64%;">
					<image :src="params.license_pic?params.license_pic:plugins_img_url+'/check_created.png'" @tap="uploadImg" data-id="0" style="display: block;width: 250rpx;height: 150rpx;float: right;margin: 20rpx 0;"></image>
				</view>
			</view>
			<view class="jojin_item">
				<view style="width: 35%;">
					<text style="margin: 0 5rpx;color: red;">*</text>
					<text style="color: #000;">店铺折扣</text>
				</view>
				<!-- <view style="width: 64%;">
					<input type="digit" v-model.trim="params.settle_discount" placeholder="请填写店铺折扣"
						style="width: 200rpx;margin-top: 20rpx;float: right;line-height: 80rpx;display: block;height: 80rpx;border: none;text-align: center;background: rgb(223, 223, 223);font-size: 28rpx;color: #000;" />
				</view> -->
				<view style="width: 64%;">
					
					<view style="display:flex;float:right;height:118rpx;line-height:118rpx;">
						
						<picker :range="count_1"  @change="changecount_1" style="
							width: 100rpx;
							border: none;text-align: center;
							font-size: 30rpx;color: #000;font-weight:bold">
							{{count_1_set?count_1_set:"设置"}}
						</picker>
						<text style="font-size: 50rpx;height:118rpx;line-height:90rpx;color: gray;text-align: center;width: 30rpx;">.</text>
						<picker :range="count_2"  @change="changecount_2" style="
							width: 100rpx;
							border: none;text-align: center;
							font-size: 30rpx;color: #000;">
							{{count_2_set?count_2_set:"设置"}}
						</picker>
					</view>
					
				</view>
			</view>
		</view>
		<view class="content_footer">
			<view class="content_footer_top">
				<view class="iconfont iconxuanzhong" :style="{color:status==1?background:''}" @tap="agreeBtn"></view>
				<view class="content_footer_top_m">我已阅读并同意</view>
				<view class="content_footer_top_r" :style="{color: background}" @click="popupShow">《兰亭商城开店说明》</view>
			</view>
			<view class="btncheck">
				<button class="sumbit" :style="{background:background}" @tap="backTo">上一步</button>
				<button class="sumbit" :style="{background:background}" @tap="sumbit">提交</button>
			</view>
		</view>
		<uni-popup ref="popup" type="center">
			<scroll-view scroll-y="true"  class="scroll-Ys">
				<view class="html" v-html="agreement">
					<rich-text :nodes="agreement"></rich-text>
				</view>
			</scroll-view>
		</uni-popup>
	</view>
</template>
<style>
	@import url("../../plugins/font-icon/iconfont1.css");
	.join_container{width: 100%;overflow: hidden;}
	.jojin_list {width: 100%;overflow: hidden;}
	.jojin_item {
		width: 98%;
		overflow: hidden;
		margin: 10rpx auto 0;
		padding: 0 20rpx;
		line-height: 120rpx;
		background: #fff;
		box-sizing: border-box;
		display: flex;
		justify-content: space-between;
	}
	.content_footer {
		width: 98%;
		margin: 5px auto;
		background: #FFFFFF;
		box-shadow: 0px 1px 1px 1px rgba(2, 58, 63, 0.16);
		border-radius: 3px;
		padding: 20px 5px 50px;
	}
	
	.sumbit {
		width: 40%;
		margin: 15rpx auto;
		height: 80rpx;
		border: 0px solid rgba(4, 0, 0, 0.39);
		box-shadow: 0px 1px 2px 0px rgba(4, 76, 102, 0.71);
		border-radius: 30px;
		line-height: 80rpx;
		color: #fff;
		font-size: 14px;
		font-weight: bold;
	}
	.content_footer_top {
		font-size: 14px;
		display: flex;
		color: #6B6B6B;
		padding: 0px 0px 10px 15px;
	}
	.btncheck{width: 100%;overflow: hidden;display: flex;justify-content: space-evenly;}
	.scroll-Ys {
		width: 80%;
		overflow: hidden;
		margin: 0 auto;
		background: #fff;
		border-radius: 20rpx;
	}
	.html {
		width: 100%;
		height: 500rpx;
		margin: 0 auto;
		border-radius: 20rpx;
		font-size: 30rpx;
		text-align: center;
		line-height: 60rpx;
		padding: 20rpx;
	}
</style>
<script>
	import {isEmpty} from '../../common/validate.js'
	export default {
		props:{
			applyInfo:{
				type:Object,
				required:true,
			}
		},
		data() {
			return {
				img_url: this.$api.img_url,
				plugins_img_url:this.$api.plugins_img_url,
				background: 'rgb(255, 113, 4)',
				status: 0, //0 未同意,1 同意
				agreement:'',
				params: {
					license_name:'',//企业名称
					// license_num:'',//营业执照编号
					license_pic:'',//营业执照图片
					cor_realname:'',//法人姓名
					cor_num:'',//法人身份证号码 	
					cor_pic1:'',//法人身份证正面
					cor_pic2:'',//法人身份证反面
//-----------------------------------------------下面是选填					
					settle_num:'',//银行卡号码
					settle_realname:'',//银行卡开户人
					settle_bank:'',//银行名称
					settle_discount:'',//店铺折扣
				},
				picture: { //图片上传回显
					license_pic: this.$api.test_url + "/images/shop/license_pic.png",
					cor_pic1: this.$api.test_url + "/images/shop/cor_pic1.png",
					cor_pic2: this.$api.test_url + "/images/shop/cor_pic2.png",
				},
				count_1:['1','2','3','4','5','6','7','8','9'],
				count_2:['0','1','2','3','4','5','6','7','8','9'],
				count_1_set:'8',
				count_2_set:'0'
			}
		},
		created() {
			let that=this
			that.$http.request({
				 url:that.$api.moreShop.getTExt,
				 data:{},
				 method:'post',
				 }).
				then(function(res){
					if(res.code==0){
						var str = res.data.agreement.replace(/[\n]/, "<br/>");
						str = str.replace("[\s]", "&nbsp;");
						that.agreement=str;
					}
			})
			setTimeout(()=>{
				this.params.license_name=this.applyInfo.license_name
				// this.params.license_num=this.applyInfo.license_num
				this.params.license_pic=this.applyInfo.license_pic
				this.params.cor_realname=this.applyInfo.cor_realname
				this.params.cor_num=this.applyInfo.cor_num
				this.params.cor_pic1=this.applyInfo.cor_pic1
				this.params.cor_pic2=this.applyInfo.cor_pic2
				this.params.settle_num=this.applyInfo.settle_num 
				this.params.settle_realname=this.applyInfo.settle_realname
				this.params.settle_bank=this.applyInfo.settle_bank
				// this.params.settle_discount=this.applyInfo.settle_discount
				
				this.count_1_set=String(this.applyInfo.settle_discount).split('.')[0]
				this.count_2_set=String(this.applyInfo.settle_discount).split('.')[1]
				if(!this.count_1_set){
					this.count_1_set = "8";
				}
				if(!this.count_2_set){
					this.count_2_set = "0";
				}
			},1000)
			
			
		},
		methods: {
			changecount_1(e) { //下拉选择商户分类
				var index = e.detail.value
				this.count_1_set = this.count_1[index]
			},
			changecount_2(e) { //下拉选择商户分类
			console.log(e)
				var index = e.detail.value
				this.count_2_set = this.count_2[index]
			},
			
			alert(txt) { //弹窗提示
				uni.showToast({
					title: txt,
					icon: 'none'
				})
			},
			sumbit: function() {
				if (isEmpty(this.params.license_name)) return this.alert('请填写营业执照名称')
				if (isEmpty(this.params.license_pic)) return this.alert('请上传营业执照图片')
				
				// if (isEmpty(this.params.settle_discount)) return this.alert('请输入折扣数')
				
				
				if (isEmpty(this.count_1_set)) return this.alert('请选择折扣数')
				if (isEmpty(this.count_2_set)) return this.alert('请选择折扣数')
				this.params.settle_discount=this.count_1_set+"."+this.count_2_set
				
				if(this.status==0)return this.alert('请阅读协议后勾选')
				var that = this
				that.$http.request({
					url: that.$api.merchants.Filllicense,
					data:that.params,
					method: 'post',
					showLoading: true
				}).
				then(function(res) {
					if (res.code == 0) {
						that.$emit("returnStatus",3)
						//location.reload()
						
					} else {
						that.$http.toast(res.msg)
					}
				})
			},
			agreeBtn() {
				this.status = this.status == 1 ? 0 : 1
			},
			popupShow(){
				this.$refs.popup.open()
			},
			backTo(){
				this.$emit("returnStatus", 1)
			},
			uploadImg(e) { //上传图片
				var id = e.currentTarget.dataset.id
				var that = this
				uni.chooseImage({
					count: 1,
					success: function(res) {
						var file = res.tempFiles[0].path
						var requestData = {
							serverUrl: that.$api.default.upload+'&width=6000&height=6000&type=1',
							fileKeyName: "file",
							file: file,
						}
						uni.showLoading({
							title: "正在上传"
						})
						that.$http.uploadFile(requestData).then(function(res) {						
							uni.hideLoading()
							if(res.code==0){
								var url = res.data.url
								if (id == 0) {
									that.picture.license_pic = url
									that.params.license_pic = url
								}else if(id == 1){
									that.picture.cor_pic1 = url
									that.params.cor_pic1 = url
								}else if(id == 2){
									that.picture.cor_pic2 = url
									that.params.cor_pic2 = url
								}  
								that.$forceUpdate()
							}else{
								that.$http.toast(res.msg)
							}
						})
					}
				})
			
			},
		},
	}
</script>