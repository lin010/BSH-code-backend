<template>
	<view class="cart-root">
		<!-- 渐变头部导航栏 -->
		<view :class="['headNav',showBool?'show':'hide']" :style="{'opacity':opacity}">
			购物车
			<view class="headNav-child">
				<block v-if="cartList.length != 0">
					<view class="cart-edit-text" @tap="cartEdit" v-if="isEdit">编辑</view>
					<view class="cart-edit-text" @tap="cartEdit" v-else>完成</view>
				</block>
			</view>
		</view>

		<view :class="['status_bar2',showBool?'show':'hide']" :style="{'opacity':opacity,'background':'#ffffff'}"></view>
		<view class="cart-bg" :style="{height:cartList.length == 0?'140rpx':'250rpx','background-image':'url('+bg_url+')'}">
		</view>
		
			<!-- #ifdef MP-WEIXIN || APP-PLUS -->
			<view class="cart-title" :style="{'padding-top':statusBarHeight+5+'px'}">
			<!-- #endif -->
			<!-- #ifdef H5 -->
			<view class="cart-title">
			<!-- #endif -->
			购物车
			<view class="cart-edit">
				<block v-if="cartList.length != 0">
					<view class="cart-edit-text" @tap="cartEdit" v-if="isEdit">编辑</view>
					<view class="cart-edit-text" @tap="cartEdit" v-else>完成</view>
				</block>
			</view>
		</view>

		<view class="cart-content" v-if="cartList.length != 0">
			<view class="cart-content-item" v-for="(item,index) in cartList" :key="index">
				

				<view class="cart-item-pro">
					<view class="select-btn-box select-btn2" :style="{color:'#FF7104'}" @tap="swtichSelect(index,item.is_not_can_buy,item.not_can_buy_reason)">
						<view class="icon iconfont icon-dagou1" v-if="item.isSelect"></view>
						<view class="select-btn" v-else></view>
					</view>

					<view class="cart-pro-img">
						<image @tap="navTo(item.goods_id)" class="cart-pro-img-child" :src="item.attrs.pic_url" v-if="item.attrs.pic_url"></image>
						<image @tap="navTo(item.goods_id)" class="cart-pro-img-child" :src="item.goods.cover_pic" v-else></image>
					</view>
					<view class="cart-pro-content">
						<view class="cart-pro-name" @tap="navTo(item.goods_id,item.attr_id)">
							
							{{item.goods.name}}
						</view>
						<view class="cart-sku" v-if="item.attrs">
							<view class="cart-sku-text">{{attrCom(item.attrs)}}</view>
							
						</view>
						<view class="cart-sku" style="background: #FFFFFF;" v-else></view>
						<view class="cart-pro-price-box">
							<view class="cart-pro-price">
								<view class="symbol">&yen;</view>
								<view v-if="item.attrs">{{item.attrs.price}}</view>
								<view v-else>{{item.goods.price}}</view>
							</view>
							<view class="cart-pro-number">
								<view class="iconfont icon-iconjian cart-pro-symbol" @tap="reduce(index,item.num,item.is_not_can_buy,item.not_can_buy_reason)"></view>
								<view class="cart-pro-number-inp" @tap="show9(index,item.num,item.attrs.stock)">{{item.num}}</view>
								<view class="iconfont icon-plus cart-pro-symbol" @tap="increase(index,item.num,item.is_not_can_buy,item.not_can_buy_reason,item.attrs.stock)"></view>
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>

		<view class="cart-content nothing" v-else>
			<image class="nothing-img" :src="img_url+'images/cart/cart-nothing.png'" mode=""></image>
			<view class="nothing-text">购物车还没有商品哦~</view>
			<view class="nothing-go" :style="{color:'#FF7104',border:'2px solid'+'#FF7104'}" @tap="goShop">去逛逛</view>
		</view>

		<view class="cart-content failure" v-if="stock.length != 0">
			<view class="failure-title">
				<view class="failure-num">失效宝贝{{stock.length}}件</view>
				<view class="failure-empty" @tap="failureEmpty">清空失效宝贝</view>
			</view>
			<view class="cart-content-item" v-for="(item,index) in stock" :key='index'>
				<view class="cart-item-pro">
					<view class="failure-tag">失效</view>
					<view class="cart-pro-img">
						<image class="cart-pro-img-child" :src="item.attrs.pic_url" v-if="item.attrs.pic_url"></image>
						<image class="cart-pro-img-child" :src="item.goods.cover_pic" v-else></image>
					</view>
					<view class="cart-pro-content failure-pro-content">
						<view class="cart-pro-name failure-pro-name">
							{{item.goods.name}}
						</view>
						<view class="stock-insufficient">
							{{item.tipsText}}
						</view>
					</view>
				</view>
			</view>
		</view>
		<com-modal :show="modal2" @click="handleClick2" @cancel="hide2" content="确定清空失效宝贝吗？" width='75%' color="#333" :size="32"
		 padding="40rpx 40rpx"></com-modal>

		<view class="guessLike" v-if="productData.length != 0">
			猜你喜欢
		</view>
		<view style="padding: 0 20rpx;">
			<commodity :listStyle='listStyle' key-value='cart' :displayStyle='displayStyle' :productData='productData'></commodity>
		</view>

		<!-- 合计 -->
		<view class="total-box" v-if="isEdit">
			<view class="total-left">
				<view class="select-btn-box select-btn2" :style="{color: '#FF7104'}" @tap="allSelect">
					<view class="icon iconfont icon-dagou1" v-if="isAllSelect"></view>
					<view class="select-btn" v-else></view>
				</view>
				<view class="selectAll">全选</view>
			</view>
			<view class="total-right">
				合计：
				<view class="total-price">&yen;{{totalPrice}}</view>
				<view class="calculation" @tap="settlement">
					结算({{selectArr.length}})
				</view>
			</view>
		</view>

		<!-- 删除 -->
		<view class="total-box" v-else>
			<view class="total-left">
				<view class="select-btn-box select-btn2" :style="{color: '#FF7104'}" @tap="allSelect">
					<view class="icon iconfont icon-dagou1" v-if="isAllSelect"></view>
					<view class="select-btn" v-else></view>
				</view>
				<view class="selectAll">全选</view>
			</view>
			<view class="total-right">
				<view class="calculation delete-calculation"  @tap="deleteCart">
					删除
				</view>
			</view>
		</view>

		<!-- modal弹窗输入数量 -->
		<com-modal :show="modal9" v-if="inputGoods" @cancel="hide" padding="30rpx 40rpx" :custom="true" :fadein="true">
			<view class="tui-modal-custom">
				<view class="tui-prompt-title">请输入数量</view>
				<input placeholder="请输入数量" class="tui-input" type="number" v-model="inputGoods.goodsNum" />
				<view class="tui-modal-operate">
					<view @tap="handleClick" style="font-size: 34rpx;">取消</view>
					<view class="tui-modal-confirm" style="font-size: 34rpx;" @tap="handleClick('confirm',inputGoods.id)">确定</view>
				</view>
			</view>
		</com-modal>

		<main-tabbar></main-tabbar>
		<main-loading :visible="loading"></main-loading>
		<placeholder :placeholderHeight='placeholderHeight3'></placeholder>
	</view>
</template>

<script>
	import commodity from '@/components/commodity/commodity';
	import placeholder from "@/components/placeholder/placeholder.vue";
	export default {
		components: {
			commodity,
			placeholder,
		},
		data() {
			return {
				img_url: this.$api.img_url,
				modal2: false,
				modal9: false, //控制modal弹窗是否显示
				goodsIndex: "", //数量数据的索引
				selectArr: [], //已选中数组
				cartList: [], //购物车列表数据
				stock: [], //库存为0的数据
				isEdit: true, //切换编辑
				isSelect: false, //选项框是否选中
				isAllSelect: false, //全选选项框是否选中
				placeholderHeight3: 200,
				showBool: false, // 固定定位状态栏占位
				// 渐变头部导航栏数据
				opacity: 0,
				finallyObj: '', //修改最后得到得数据
				//商品数据
				listStyle: 2, //1为大图 2为一行一个 3为一行两个 4一行三个 5为左右滑动
				displayStyle: 1, //1为常规 2为边框 3为居中显示 4为边框居中
				productData: [],
				inputGoods: '', // 输入数量
				loading: false,
				tipsText: '',

				textColor: '#bc0100',
				bg_url:'',
				
				statusBarHeight:0//状态栏高度
			}
		},
		onShow() {
			this.isEdit = true;
			this.getCartList(); //获取购物车列表数据
		},
		onLoad() {
			let res = uni.getSystemInfoSync();
			this.statusBarHeight = res.statusBarHeight;
			this.textColor = this.globalSet('textCol');
			this.bg_url = this.globalSet('imgUrl');
			this.getRecommend(); //获取推荐商品数据
		},
		onHide() {
			this.selectArr = [];
		},
		onPageScroll(e) {
			if (e.scrollTop > 0) {
				this.showBool = true;
			} else {
				this.showBool = false;
			}
			this.opacity = e.scrollTop / 50;
		},
		computed: {
			attrCom() {
				return function(val) {
					var strs = '';
					val.attr.forEach((item) => {
						strs = strs + item.attr_name;
					})
					return strs;
				}
			},
			totalPrice() {
				var total = 0;
				this.selectArr.forEach((item) => {
					total = total + (item.attrs.price * item.num);
				})
				return total.toFixed(2);
			},
		},
		methods: {
			navTo(id, attr_id) {
				this.selectArr = [];
				uni.navigateTo({
					url: `/pages/goods/detail?proId=${id}&attr_id=${attr_id}`
				})
			},
			modify(list, bool = true) { //修改数据请求
				this.$http.request({
					url: this.$api.cart.modify,
					method: 'post',
					data: {
						list
					}
				}).then((res) => {
					if (res.code == 0) {
						if (bool) {
							this.cartList = [];
							this.getCartList();
						}
					}
				})
			},
			goShop() {
				uni.navigateTo({
					url: '/pages/index/index'
				})
			},
			show9(index, num, stock) {
				this.modal9 = true;
				this.inputGoods = {
					goodsNum: num,
					stock: stock,
				}
				this.goodsIndex = index;
			},
			hide() {
				this.inputGoods = '';
			},
			handleClick2(e) {
				let index = e.index;
				if (index != 0) {
					var arr = JSON.parse(JSON.stringify(this.stock));
					arr.forEach((item) => {
						delete item.attrs;
						delete item.cart_id;
						delete item.id;
						delete item.goods;
						item.num = 0;
					});
					arr = JSON.parse(JSON.stringify(arr).replace(/attr_id/g, 'attr'));
					this.stock = [];
					this.modify(arr, false);
				}
				this.hide2()
			},
			hide2() {
				this.modal2 = false
			},
			handleClick(name, num) { //通过input修改商品数量
				if (name == 'confirm') {
					if (this.inputGoods.goodsNum < 1) {
						this.$http.toast("您输入的数量不对");
					} else if (this.inputGoods.goodsNum > this.inputGoods.stock) {
						this.$http.toast("该宝贝不能购买更多");
					} else {
						this.cartList[this.goodsIndex].num = this.inputGoods.goodsNum;
						this.modify([{
							goods_id: this.cartList[this.goodsIndex].goods_id,
							attr: this.cartList[this.goodsIndex].attrs.id,
							num: this.cartList[this.goodsIndex].num
						}], false);
					}
				}
				this.hide();
			},
			increase(index, num, is_not_can_buy, msg, stock) { //商品数量增加
				if (is_not_can_buy == 1) {
					this.$http.toast(msg);
					return;
				}

				if (this.cartList[index].num >= stock) {
					this.$http.toast('该宝贝不能购买更多');
					return;
				}
				this.cartList[index].num++;
				this.modify([{
					goods_id: this.cartList[index].goods_id,
					attr: this.cartList[index].attrs.id,
					num: this.cartList[index].num
				}], false);
			},
			reduce(index, num, is_not_can_buy, msg) { //商品数量减少
				if (is_not_can_buy == 1) {
					this.$http.toast(msg);
					return;
				}
				if (this.cartList[index].num <= 1) {
					this.$http.toast('商品已经是最少的咯~');
					return;
				}
				this.cartList[index].num--;
				this.modify([{
					goods_id: this.cartList[index].goods_id,
					attr: this.cartList[index].attrs.id,
					num: this.cartList[index].num
				}], false);
			},
			changeData(name1, name2, del) { //更改数据
				var obj = JSON.parse(JSON.stringify(this.selectArr));
				obj.forEach((item, index) => { //删除多余数据
					if (del == 'delete') {
						delete item.cart_id;
					}
					delete item.id;
					delete item.attrs;
					delete item.goods;
					delete item.isSelect;
					delete item.is_not_can_buy;
					delete item.not_can_buy_reason;
				})
				//更改字段名
				var a = JSON.parse(JSON.stringify(obj).replace(/goods_id/g, name1));
				this.finallyObj = JSON.parse(JSON.stringify(a).replace(/attr_id/g, name2));
			},
			settlement() { //去结算
				if (this.selectArr == 0) return;
				this.changeData('id', 'goods_attr_id');
				// this.selectArr = [];
				let lo=[]
				uni.setStorageSync('orderData', this.finallyObj);
				for(let i=0;i<this.selectArr.length;i++){
					if(lo.indexOf(this.selectArr[i].goods_id)==-1){
						// lo.push(this.selectArr[i].goods_id)
						lo.push({id:this.selectArr[i].goods_id,num:this.selectArr[i].num})
					}
				}
				let catid=[]
				for(let i=0;i<this.finallyObj.length;i++){
					if(catid.indexOf(this.finallyObj[i].cart_id)==-1){
						// lo.push(this.selectArr[i].goods_id)
						catid.push(this.finallyObj[i].cart_id)
					}
				}
				// let local=lo.join()
//--------------------------------------------------------------------------------------				
//--------------------------------------------------------------------------------------------				
				uni.navigateTo({
					url: '/pages/order/submit?nav_id='+JSON.stringify(lo)+"&user_address_id=0"+"&use_score=0"+"&use_integral=0"+"&list="+String(catid)

				})
			},
			deleteCart() { //购物车删除
				let that=this
				uni.showModal({
				    title: '提示',
				    content: '确定要删除吗？',
				    success: function (res) {
				        if (res.confirm) {
						   that.selectArr.forEach((item) => {
						   	item.num = 0;
						   })	
						   that.changeData('goods_id', 'attr', 'delete');
				           that.modify(that.finallyObj);
				           that.selectArr = [];
				        } else if (res.cancel) {
				          
				        }
				    }
				});
			},
			getCartList() { //获取购物车列表
				this.loading = true;
				if(this.cartList.length==0){
					this.isEdit=true
				}
				this.$http.request({
					url: this.$api.cart.list,
					method: 'GET'
				}).then((res) => {
					this.loading = false;
					if (res.code == 0) {
						this.cartList = [];
						this.stock = [];
						res.data.list.forEach((item) => {
							if (item.goods.status == 1) {
								if (item.attrs.stock > 0) {
									this.cartList.push(item);
								} else {
									item.tipsText = '商品库存不足';
									this.stock.push(item);
								}
							} else {
								item.tipsText = '商品已下架';
								this.stock.push(item);
							}
						})

						this.cartList.forEach((item) => {
							this.$set(item, 'isSelect', false);
						})
						
						this.isAllSelect = false;
					}
				})
			},
			getRecommend() {
				this.loading = true;
				this.$http.request({
					url: this.$api.goods.recommend,
					data: {
						page: 1
					}
				}).then((res) => {
					this.loading = false;
					if (res.code == 0) {
						this.productData = res.data.list;
					}
				})
			},
			cartEdit() { //编辑切换
				this.isEdit = !this.isEdit;
				if(this.isEdit){ //从删除商品切换回正常购物后，删除已达限购商品
					this.selectArr.forEach((item,index) => {
						if(item.is_not_can_buy == 1){
							item.isSelect = false;
							this.selectArr.splice(index,1)
						}
					})
				}
			},
			cartMsg() {
				this.$http.toast('点击了信息');
			},
			swtichSelect(index, is_not_can_buy, msg) { //商品选项框事件
				if(this.isEdit){
					if (is_not_can_buy == 1) {
						this.$http.toast(msg);
						return;
					}
				}
				this.cartList[index].isSelect = !this.cartList[index].isSelect;
				//将选中的商品添加入数组，取消选中的删除
				if (this.cartList[index].isSelect) {
					this.selectArr.push(this.cartList[index]);
				} else {
					this.selectArr.forEach((item, index) => {
						if (!item.isSelect) {
							this.selectArr.splice(index, 1);
						}
					})
				}
				if (this.selectArr.length == this.cartList.length) {
					this.isAllSelect = true;
				} else {
					this.isAllSelect = false;
				}
				
			},
			allSelect() { //全选选项框
				console.log(this.isEdit,'isEdit');
				this.isAllSelect = !this.isAllSelect;
				this.cartList.forEach((item, index) => {
					if (item.is_not_can_buy == 1 && this.isEdit) { //判断是否被限购了，如果被限购则不选中
						item.isSelect = false;
					} else {
						item.isSelect = this.isAllSelect;
						this.selectArr.push(item);
					}
				})

				this.selectArr = Array.from(new Set(this.selectArr));
				if (!this.isAllSelect) {
					this.selectArr = [];
				}
				console.log(this.selectArr)
			},
			failureEmpty() { //清空失效商品
				this.modal2 = true;
			}
		}
	}
</script>

<style scoped>
	.status_bar {
		height: var(--status-bar-height);
		width: 100%;
		background: #FFFFFF;
	}

	.status_bar2 {
		height: var(--status-bar-height);
		width: 100%;
		position: fixed;
		top: 0;
		z-index: 99;
	}

	body,
	page {
		background-color: $uni-bg-color-grey;
	}

	.cart-root {
		position: relative;
		z-index: 1;
	}

	/* 渐变头部导航栏 */
	.headNav {
		position: fixed;
		z-index: 99;
		text-align: center;
		background: #FFFFFF;
		width: 100%;
		padding: 20rpx 0;
		box-sizing: border-box;
		border-bottom: 2rpx solid #dfdfdf;
		color: #000000;
		top: var(--status-bar-height);
	}

	.headNav-child {
		display: flex;
		align-items: center;
		position: absolute;
		top: 50%;
		transform: translateY(-50%);
		left: 30rpx;
	}

	.cart-bg {
		position: absolute;
		width: 100%;
		background-size: cover !important;
	}

	.cart-bg-img {
		width: 100%;
		height: 150rpx;
	}

	.cart-content {
		position: relative;
	}

	.cart-title {
		padding: 40rpx 0 0rpx;
		color: #FFFFFF;
		font-size: 15pt;
		font-weight: 600;
		letter-spacing: 4rpx;
		text-align: center;
		position: relative;
		margin-bottom: 40rpx;
	}

	.cart-edit-text {
		margin-left: 40rpx;
		font-weight: 600;
		font-size: 10pt;
	}

	.cart-edit {
		position: absolute;
		font-size: 11pt;
		font-weight: 500;
		display: flex;
		bottom: 0rpx;
		left: 30rpx;
		align-items: center;
	}

	.message {
		font-size: 16pt;
	}

	.cart-content {
		width: 94%;
		border-radius: 20rpx;
		margin: auto;
		background: #FFFFFF;
		padding: 30rpx 0 30rpx 20rpx;
		box-sizing: border-box;
	}

	.cart-content-item {
		margin-bottom: 30rpx;
		height: 230rpx;
	}

	.cart-item-title {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 20rpx;
	}

	.cart-item-title-left {
		display: flex;
		align-items: center;
	}

	.select-btn-box {
		width: 40rpx;
		height: 40rpx;
		position: relative;
	}

	.select-btn {
		width: 40rpx;
		height: 40rpx;
		border: 2rpx solid #cccccc;
		border-radius: 50%;
		margin-right: 20rpx;
		font-size: 18pt;
		box-sizing: border-box;
		position: absolute;
	}

	.icon {
		width: 40rpx;
		height: 40rpx;
		font-size: 16pt;
		position: absolute;
		top: 0rpx;
		left: 0rpx;
		line-height: 40rpx;
	}

	.storeName {
		font-size: 11pt;
		font-weight: 600;
	}

	.cart-item-title-right {
		font-size: 9pt;
	}

	.cart-item-pro {
		padding: 20rpx 20rpx 20rpx 0rpx;
		display: flex;
		align-items: center;
		height: 100%;
	}

	.cart-pro-img-child {
		width: 190rpx;
		height: 190rpx;
		border-radius: 8rpx;
		overflow: hidden;
		display: block;
	}

	.cart-pro-content {
		flex: 1;
		padding: 8rpx 20rpx 0;
	}

	.cart-pro-name {
		position: relative;
		font-size: 26rpx;
		font-weight: 600;
		color: #000000;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
	}

	.failure-pro-name {
		color: #a1a1a1;
		font-weight: 500;
	}

	.cart-pro-sub {
		border: 2rpx solid #BC0100;
		color: #BC0100;
		width: 54rpx;
		height: 30rpx;
		font-size: 7pt;
		border-radius: 26rpx;
		text-align: center;
		line-height: 28rpx;
		position: absolute;
		top: 2rpx;
		left: 0px;
		text-indent: 0rpx;
	}

	.cart-sku {
		font-size: 9pt;
		background: #F7F7F7;
		height: 42rpx;
		line-height: 42rpx;
		padding-left: 18rpx;
		padding-right: 18rpx;
		display: inline-block;
		position: relative;
		margin: 8rpx 0;
		border-radius: 6rpx;
	}

	.cart-sku-text {
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 1;
		overflow: hidden;
	}

	.cart-sku-arrow {
		position: absolute;
		top: 50%;
		right: 10rpx;
		transform: rotate(90deg) translate(-60%);
	}

	.cart-pro-price-box {
		display: flex;
		justify-content: space-between;
	}

	.cart-pro-price {
		color: #FF7104;
		display: flex;
		align-items: center;
	}

	.symbol {
		font-size: 9pt;
		padding-top: 8rpx;
		margin-right: 8rpx;
	}

	.cart-pro-number {
		display: flex;
		align-items: center;
	}

	.cart-pro-symbol {
		font-size: 28rpx;
		font-weight: 700;
	}

	.cart-pro-number-inp {
		background: #F7F7F7;
		min-width: 58rpx;
		height: 42rpx;
		margin: 0 10rpx;
		text-align: center;
		font-size: 11pt;
		padding: 0 10rpx;
	}

	.select-btn2 {
		margin-right: 10rpx;
	}

	/* 猜你喜欢 */
	.guessLike {
		text-align: center;
		font-weight: 700;
		font-size: 12pt;
		color: #000000;
		padding: 50rpx 0;
	}

	/* 合计 */
	.total-box {
		position: fixed;
		/* bottom: calc(100rpx + env(safe-area-inset-bottom)); */
		bottom:100rpx;
		/* padding-bottom: env(safe-area-inset-bottom); */
		background: #FFFFFF;
		width: 100%;
		padding: 16rpx 20rpx;
		border-top: 2rpx solid #dfdfdf;
		display: flex;
		align-items: center;
		justify-content: space-between;
		box-sizing: border-box;
	}

	.total-left {
		display: flex;
		align-items: center;
	}

	.selectAll {
		margin-left: 10rpx;
		font-size: 10pt;
	}

	.total-right {
		display: flex;
		align-items: center;
		font-size: 11pt;
	}

	.total-price {
		color: #FF7104;
		font-size: 12pt;
		margin-right: 10rpx;
	}

	.calculation {
		background:#FF7104;
		color: #FFFFFF;
		padding: 16rpx 40rpx;
		border-radius: 50rpx;
	}

	.delete-calculation {
		background: #FF7104;
		padding: 16rpx 50rpx;
	}

	.nothing {
		background: transparent;
		display: flex;
		flex-direction: column;
		align-items: center;
		padding: 80rpx 20rpx 20rpx;
	}

	.nothing-img {
		width: 187rpx;
		height: 187rpx;
		margin-bottom: 35rpx;
	}

	.nothing-text {
		color: #808080;
		font-size: 10pt;
		letter-spacing: 2rpx;
		margin-bottom: 35rpx;
	}

	.nothing-go {
		font-size: 10pt;
		padding: 12rpx 50rpx;
		border-radius: 50rpx;
	}

	/* 无库存样式 */
	.failure {
		margin: 30rpx auto 0;
	}

	.failure-title {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding-right: 20rpx;
		margin-bottom: 16rpx;
	}

	.failure-num {
		font-size: 11pt;
		color: #000000;
	}

	.failure-empty {
		font-size: 9pt;
		color: #BC0100;
	}

	.failure-tag {
		font-size: 8pt;
		color: #FFFFFF;
		background: #b8b8b8;
		padding: 4rpx 10rpx;
		margin-right: 10rpx;
		border-radius: 10rpx;
	}

	.stock-insufficient {
		font-size: 10pt;
		color: #000000;
		margin-top: 6rpx;
	}

	.failure-pro-content {
		height: 100%;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
	}


	/* modal弹窗 */
	.tui-modal-custom {
		/* text-align: center */
	}

	.tui-prompt-title {
		padding-bottom: 20rpx;
		font-size: 34rpx;
		color: #000000;
	}

	.tui-input {
		margin: 30rpx 0rpx;
		border-bottom: 2rpx solid #BC0100;
		/* padding-bottom: 20rpx; */
		font-size: 32rpx;
		caret-color: #BC0100;
	}

	.tui-modal-operate {
		display: flex;
		justify-content: flex-end;
		font-size: 11pt;
		color: #BC0100;
	}

	.tui-modal-confirm {
		margin: 0 30rpx 0 70rpx;
	}

	.show {
		display: block;
	}

	.hide {
		display: none;
	}

	.off-shelf {
		color: #BC0100;
		font-size: 28rpx;
	}

	.gray {
		color: #959595;
	}
</style>
