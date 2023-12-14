<template>
	<view>
		<view class="aboutUs">
			<view class="aboutUs-title" style="font-weight: 700;">
				<view>{{toolbarTitle}}</view>
				<!-- <view class="icon" :class="{'rotate':is_show}">
					<span v-if="toolbarData.length > 4" class="iconfont icon-xiala" @click="navTo()"></span>
				</view> -->
			</view>
			<view class="aboutUs-item flex" :class="flex == 1 ? 'flex-col left' : ''">
				<view class="aboutUs-item-box flex flex-y-center" :class="flex == 1 ? 'left' : 'flex-col'" @tap="navTo(item.link_url)"
				 v-for="(item,indexs) in toolbarData2" :key='indexs'>
					<view>
						<image class="aboutUs-item-icon" :src="item.icon_url" mode=""></image>
					</view>
					<view class="aboutUs-item-name">
						{{item.name}}
					</view>
				</view>
				<!-- <view class="extra-box" :class="is_show?'show':'hide'">
					<view class="extra-box transform-hide" :class="{'flex-col left' : flex == 1,'transform-show':is_show}">
						<view class="aboutUs-item-box flex flex-y-center" :class="flex == 1 ? 'left' : 'flex-col'" @tap="navTo(item.link_url)"
						 v-for="(item,index) in toolbarData3" :key='item.name'>
							<view>
								<image class="aboutUs-item-icon" :src="item.icon_url" mode=""></image>
							</view>
							<view class="aboutUs-item-name">
								{{item.name}}
							</view>
						</view>
					</view>
				</view> -->
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name: "toolbar",
		props: {
			toolbarData: {
				type: Array,
				default: () => {}
			},
			toolbarTitle: {
				type: String,
				default: '工具栏'
			},
			flex: {
				type: String,
				default: '2'
			},
		},
		data() {
			return {
				// rawData: [], // 储存原始数据
				toolbarData2: [], // 需要展示的数据
				// toolbarData3:[], //超过4个得数据
				is_show:false,//是否显示更多
			}
		},
		created() {
			
			this.toolbarData.forEach((item,index)=>{
				this.toolbarData2.push(item)
			})
			// console.log(this.toolbarData3)
			// if (this.toolbarData.length > 4) {
			// 	this.toolbarData.forEach((item, index) => {
			// 		if (index <= 3) {
			// 			this.toolbarData2.push(item);
			// 		}else{
						
			// 			this.toolbarData3.push(item);
			// 		}
			// 	})
			// 	this.rawData = this.toolbarData2;
			// } else {
			// 	this.toolbarData2 = this.toolbarData;
			// }
		},
		methods: {
			navTo(url) {
				if(!url){
					this.is_show = !this.is_show;
					// this.toolbarData2 = this.toolbarData2.length < this.toolbarData.length ? this.toolbarData : this.rawData;
					return;
				}
				if(url.split('=')[0] == 'tel?tel'){
					uni.makePhoneCall({
					    phoneNumber: url.split('=')[1],
						success: (res)=>{
							this.$http.toast('打电话回调成功！')
						}
					});
					return;
				}
				uni.navigateTo({
					url
				})
			},
		},
		watch:{
			toolbarData:{
				 handler(val, oldVal){
					 this.toolbarData=val
					 this.toolbarData2=[]
					 this.toolbarData.forEach((item,index)=>{
					 	this.toolbarData2.push(item)
					 })
				},
				 deep:true //true 深度监听
			}
		}
	}
</script>

<style lang="scss" scoped>
	.aboutUs {
		background: #FFFFFF;
		border-radius: 12rpx;
		margin-top: 20rpx;
		box-shadow: 0 1px 10px rgba(183, 183, 183, 0.1);
	}

	.aboutUs-title {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 0 40rpx;
		font-size: 11pt;
		color: #333;
		height: 100rpx;
		line-height: 100rpx;
		border-bottom: 1rpx solid #F3F3F3;
		font-weight: 600;
		letter-spacing: 1px;
		
		.icon{
			transform: rotate(90deg);
			display: flex;
			justify-content: center;
			align-items: center;
			transition: all 0.5s;
		}
		.iconfont{
			font-size: 18pt;
		}
	}

	.aboutUs-item {
		padding: 50rpx 0rpx 20rpx;
		flex-wrap: wrap;
		transition: all 1s;

		&.left {
			padding: 50rpx 44rpx 20rpx;
		}
	}

	.aboutUs-item-box {
		width: 25%;
		/* padding-top: 30rpx; */
		margin-bottom: 30rpx;

		&.left {
			width: auto;
			align-items: flex-start;
			border-bottom: 1rpx solid #eaeef1;

			.aboutUs-item-name {
				margin-left: 20rpx;
			}
		}
	}

	.aboutUs-item-icon {
		width: 50rpx;
		height: 50rpx;
	}

	.aboutUs-item-name {
		font-size: 9pt;
		padding-top: 4rpx;
		color: #333;
		font-weight: 400;
	}
	.extra-box{
		width: 100%;
		display: flex;
		flex-wrap: wrap;
	}
	.show{
		height: auto;
	}
	.hide{
		height: 0;
		overflow: hidden;
	}
	.transform-show{
		opacity: 1 !important;
		-webkit-transform: translateY(0) !important;
		transform: translateY(0) !important;
	}
	.transform-hide{
		opacity: 0;
		-webkit-transform: translateY(-50%);
		transform: translateY(-50%);
		-webkit-transition: 0.3s;
		transition: 0.3s;
	}
	.rotate{
		transform: rotate(270deg) !important;
		transform-origin: center center;
	}
</style>
