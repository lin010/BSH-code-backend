<template>
	<view class="order_lisat_container">
		<view class="tui-searchbox">
			<view class="tui-search-input">
				<!-- #ifdef APP-PLUS || MP -->
				<icon type="search" :size='13' color='#333'></icon>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<view>
					<com-icons type="search" :size='16' color='#333333'></com-icons>
				</view>
				<!-- #endif -->
				<input type="search" placeholder="请输入订单编号查询" placeholder-class="tui-input-plholder" class="tui-input"
					v-model.trim="key" @confirm='search' />
				<!-- #ifdef APP-PLUS || MP -->
				<icon type="clear" :size='13' color='#bcbcbc' @tap="cleanKey" v-show="key"></icon>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<view @tap="cleanKey" v-show="key">
					<tui-icon name="close-fill" :size='16' color='#bcbcbc'></tui-icon>
				</view>
				<!-- #endif -->
			</view>
			<view class="tui-cancle" @tap="search">搜索</view>
		</view>
		<view class="order_status">
			<view v-for="(item,index) in table" :key='index' @click="change(index)">
				<text :class="selecIndex==index?'active':''">{{item}}</text>
			</view>
		</view>
		<view class="goods_list">
			<view class="item" v-for="(item,index) in orderList" :key='index'>
				<view class="item_header">
					<image :src="item.avatar_url" mode=""
						style="width: 50rpx;height: 50rpx;display: block;float: left;border-radius: 50%;"></image>
					<view style="float: left;margin-left: 20rpx;
			overflow: hidden;text-overflow:ellipsis;white-space: nowrap;width: 50%;">
						{{item.nickname}}
					</view>
					<view style="float: right;">
						{{item.mobile}}
					</view>
				</view>
				<view class="ordermessage">
					<view style="margin: 20rpx 0;font-size: 30rpx;">
						<text>订单编号：</text>
						<text style="color: #000;font-size: 28rpx;">{{item.order_no}}</text>
						<text
							style="display: inline-block;float: right;border: 1rpx solid rgb(237,237,237);width: 100rpx;text-align: center;border-radius: 10rpx;"
							@click="copyText(item.order_no)">复制</text>
					</view>
					<view style="font-size: 30rpx;margin: 0 0 20rpx 0;">
						<text>下单时间：</text>
						<text style="color: #000;">{{item.created_at}}</text>
					</view>
				</view>
				<view class="goods_detail">
					<view class="goods_detail_price">
						<view style="float: right;" v-if='status==0'>
							<text style="color: red;">未支付</text>
						</view>
						<view style="float: right;" v-if='status==1'>
							<text>实付：</text>
							<text style="color: red;">{{item.pay_price}}现金</text>	
							<text style="color: red;" v-if="Number(item.integral_deduction_price)>0">+{{item.integral_deduction_price}}{{currencyAlias.red_envelope_alias}}</text>
							<text style="color: red;" v-if="Number(item.score_deduction_price)>0">+{{item.score_deduction_price}}{{currencyAlias.integral_alias}}</text>
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import tuiIcon from "@/components/icon/icon";
	import tuiTag from "@/components/tag/tag";
	export default {
		components: {
			tuiIcon,
			tuiTag,
		},
		data() {
			return {
				img_url: this.$api.img_url,
				key: "",
				scrollTop: 0,
				status: 0,
				table: [
					"待支付",
					"已支付",
				],
				selecIndex: 0,
				form: {
					status: 0, //状态： 1已支付  0未支付
					page: 1,
					keyword: '',
				},
				orderList: [],
				page_count: '',
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		onLoad() {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
			}
			this.getorderList()
		},
		methods: {
			cleanKey: function() { //清空搜索
				this.key = ''
				this.form.keyword = ''
				this.form.page = 1
				this.orderList = []
				this.getorderList()
			},
			search() { //搜索
				this.form.keyword = this.key
				this.form.page = 1
				this.orderList = []
				this.getorderList()
			},
			change(index) {
				this.selecIndex = index
				this.status=index
				this.form.status=index
				this.form.page = 1
				this.orderList = []
				this.getorderList()
			},
			getorderList() {
				let that = this;
				this.$http.request({ //获取订单列表
					url: this.$api.moreShop.getcollectionlist,
					method: 'POST',
					data: this.form,
					showLoading: true
				}).then(res => {
					if (res.code == 0) {
						if (res.data.list.length == 0) return false
						let list = res.data.list;
						var arr = this.orderList.concat(list)
						this.orderList = arr
						this.page_count = res.data.pagination.page_count;
					} else {
						that.$http.toast(res.msg);
					}
				});
			},
			copyText(text) {
				let _self = this;
				// #ifdef H5
				return new Promise((resolve, reject) => {
					let copy = document.createElement("input"); // 创建一个input框获取需要复制的文本内容
					copy.value = text;
					let appDiv = document.getElementsByClassName('order_lisat_container')[0];
					appDiv.appendChild(copy);
					copy.select();
					document.execCommand("Copy");
					_self.$http.toast("复制成功")
					copy.remove()
					resolve(true);
				})
				// #endif

				// #ifndef H5
				uni.setClipboardData({
					data: text,
					success: function() {
						_self.$http.toast("复制成功")
					}
				});
				// #endif
			},
		},
		onPageScroll(e) {
			this.scrollTop = e.scrollTop;
		},
		onReachBottom() {
			if (this.form.page == this.page_count) {
				return false;
			}
			this.form.page = this.form.page + 1
			this.getorderList();
		},
	}
</script>

<style lang="less" scoped>
	.order_lisat_container {
		width: 100%;
		height: 100%;
	}

	.tui-searchbox {
		width: 100%;
		padding: 30rpx;
		background: #fff;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		position: fixed;
		/* #ifdef H5 */
		top: 80rpx;
		/* #endif */
		/* #ifdef  MP ||APP-PLUS */
		top: 0rpx;
		/* #endif */
		left: 0;
		z-index: 9999;
	}

	.tui-search-input {
		width: 90%;
		height: 66rpx;
		border-radius: 35rpx;
		padding: 0 30rpx;
		box-sizing: border-box;
		background: #f2f2f2;
		display: flex;
		align-items: center;
		flex-wrap: nowrap;
	}

	.tui-input {
		flex: 1;
		color: #333;
		padding: 0 16rpx;
		font-size: 11pt;
	}

	.tui-input-plholder {
		font-size: 11pt;
		color: #b2b2b2;
	}

	.tui-cancle {
		color: #888;
		font-size: 11pt;
		padding-left: 30rpx;
		flex-shrink: 0;
	}

	.tui-history-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 30rpx 0;
	}

	.tui-icon-delete {
		padding: 10rpx;
	}

	.tui-search-title {
		font-size: 11pt;
		font-weight: bold;
	}

	.tui-hot-header {
		padding: 30rpx 0;
	}

	.tui-tag-class {
		display: inline-block;
		margin-bottom: 20rpx;
		margin-right: 20rpx;
		font-size: 9pt !important;
	}

	.tui-history-content {
		display: flex;
		flex-wrap: wrap;
	}

	.order_status {
		width: 100%;
		height: 100rpx;
		background: #fff;
		position: fixed;
		/* #ifdef H5*/
			top: 200rpx;
		/* #endif */
		/* #ifdef APP-PLUS*/
			top: 105rpx;
		/* #endif */		
		/* #ifdef  MP  */
			top: 125rpx;
		/* #endif */
		display: flex;
		justify-content: space-evenly;
		z-index: 999;
	}

	.order_status view text {
		display: block;
		width: 100%;
		height: 90rpx;
		line-height: 90rpx;
	}

	.goods_list {
		width: 100%;
		overflow: hidden;
		margin-top: 220rpx;
		margin-bottom: 100rpx;
	}

	.item {
		width: 100%;
		overflow: hidden;
		padding: 20rpx;
		box-sizing: border-box;
		background: #fff;
		margin-top: 20rpx;
	}

	.item_header {
		width: 100%;
		overflow: hidden;
		margin-bottom: 20rpx;
	}

	.ordermessage {
		width: 100%;
		overflow: hidden;
		border: 1rpx solid rgb(237, 237, 237);
		border-left: none;
		border-right: none;
	}

	.goods_detail {
		width: 100%;
		overflow: hidden;
		margin: 15rpx 0;
	}

	.goods_detail_message {
		width: 100%;
		overflow: hidden;
	}

	.goods_detail_message image {
		width: 200rpx;
		height: 200rpx;
		display: block;
		float: left;
	}

	.goods_detail_message_right {
		width: 70%;
		float: left;
		padding: 0 0 0 10rpx;
		box-sizing: border-box;
	}

	.goods_detail_price {
		width: 100%;
		overflow: hidden;
		margin: 10rpx 0;
	}

	.active {
		color: rgb(255, 113, 4);
		border-bottom: 5rpx solid rgb(255, 113, 4);
	}
	.scroll-Ys {width: 700rpx;overflow: hidden;margin: 0 auto;background: #fff;border-radius: 20rpx;}
	.popup-more{width: 700rpx;height: 500rpx;background: #fff;border-radius: 30rpx;}
	.popup-more-title{width: 100%;height: 80rpx;text-align: center;line-height: 80rpx;font-size: 30rpx;color: #000;border-bottom: 1rpx solid rgb(181,181,181);}
	.popup-more-popup-list{width: 100%;overflow: hidden;padding: 0 10rpx;margin-bottom: 20rpx;}
	.popup-more-popup-item{width: 100%;overflow: hidden;border-top: 2rpx solid #f2f2f2;margin: 5rpx 0;}
	.popup-more-popup-item image{width: 60rpx;height: 60rpx;float: left;margin-top: 20rpx;margin-right: 20rpx;}
	.popup-more-popup-item-name{float: left;height: 100rpx;line-height: 100rpx;font-size: 25rpx;width: 100%;text-align: center;font-size: 30rpx;}
	.popup-more-popup-item-peopeliNum{height: 100rpx;float: left;font-size: 25rpx;width: 250rpx;margin-left: 30rpx;}
	.popup-more-popup-item-peopeliNum text:nth-of-type(2){display: block;width: 100%;height: 50rpx;text-align: center;line-height: 50rpx;}
	.popup-more-go-popup{height: 60rpx;float: right;background: rgb(255, 51, 11);width: 130rpx;color: #fff;text-align: center;line-height: 60rpx;font-size: 28rpx;margin-top: 20rpx;}
</style>
