<template>
	<view class="diy-mch">
		<view class="mch-list" v-if="!data.showGoods" style="padding-left:29rpx;padding-right:29rpx;display: flex;justify-content: space-between;overflow-x: auto;">
			<view @click="jumpStore(mch)" class="mch-item" :style="cItemStyle" v-for="(mch,mchIndex) in cMchList">
				<view style="width:100%;display:flex;flex-direction: column;">
					<image :src="mch.cover_url" style="width: 215rpx;height: 215rpx;" mode="scaleToFill"></image>
					<view class="mch-name" style="text-align: center;">{{mch.name}}</view >
				</view>
			</view>
		</view>
		<view class="mch-list" v-else>
			<view class="mch-item" :style="cItemStyle" v-for="(mch,mchIndex) in cMchList">
				<view style="display:flex;justify-content: space-between;">
					<image :src="mch.cover_url" mode="scaleToFill" style="width: 100rpx;height: 100rpx;margin-right:20rpx;"></image>
					<view style="flex-grow: 1;">
						<view class="mch-name" style="font-size: 16px">{{mch.name}}</view>
						<view class='mch-info'>
							<text>商品数: {{mch.goodsNum}}</text>
							<text style="margin-left: 10rpx">已售: {{mch.orderNum}}</text>
						</view>
					</view>
					<view>
						<view @click="jumpStore(mch)" class="to-look">进店逛逛</view>
					</view>
				</view>

				<view v-if="fGoodsList(mch) && fGoodsList(mch).length" class="goods-list" style="margin-top:10rpx">
					<view  @click="jumpGoods(goods)" v-for="(goods, goodsIndex) in fGoodsList(mch)" class="goods-item">
						<view class="goods-pic" :style="goods.cover_pic?('background-image:url('+goods.cover_pic+');'):''"></view>
						<view class="goods-price">￥{{goods.price}}</view>
					</view>
				</view>
				<view v-else class="goods-list" style="height: 100rpx;color: #909399;display:flex;justify-content: center;">
					<text>暂无商品</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			value: {
				type:Object,
				default:()=>{}
			},
		},
		data() {
			return {
				data:{}
			}
		},
		computed:{
			cListStyle() {
				if(this.data.backgroundColor) {
					return `background-color:${this.data.backgroundColor};background-image:url(${this.data.backgroundPicUrl});background-size:${this.data.backgroundWidth}% ${this.data.backgroundHeight}%;background-repeat:${this.repeat};background-position:${this.position}`
				}else {
					return `background-image:url(${this.data.backgroundPicUrl});background-size:${this.data.backgroundWidth}% ${this.data.backgroundHeight}%;background-repeat:${this.repeat};background-position:${this.position}`
				}
			},
			cItemStyle() {
				let style;
				if(this.data.cardStyle == 1) {
					style = `background-color:#ffffff;`
				}else if(this.data.cardStyle == 2) {
					style = `background-color:#ffffff;border: 1rpx solid #e2e2e2;`
				}else {
					style = ''
				}
				if(this.data.showGoods) {
					style += `padding: 20rpx;`
				}else{
					style += `width:33.333%;flex-shrink: 0;`;
				}
				return style
			},
			cMchList() {
				return this.data.list && this.data.list.length ? this.data.list : [];
				
				/* const goods = {
					id: 0,
					picUrl: '',
					price: '999.00',
				};
				const item = {
					id: 0,
					name: '商户名称',
					picUrl: '',
					goodsList: [goods, goods, goods, goods, goods],
					goodsNum: 'xxx',
					orderNum: 'xxx',
					showGoodsNum: 10,
					staticGoods: false,
				};
				return [item, item, item,]; */
			},
			fGoodsList() {
				return (mch) => {
					/* if (!mch.staticGoods) {
						const goods = {
							id: 0,
							picUrl: '',
							price: '999.00',
						}
						return [goods, goods, goods];
					} */
					return mch.goodsList;
				}
			},
		},
		created() {
			this.data = JSON.parse(JSON.stringify(this.value));
			this.getMchData();
		},
		methods: {
			jumpStore(mch){
				uni.navigateTo({
					url: '/merchants/detail/detail?store_id=' + mch.store_id
				})
			},
			jumpGoods(goods){
				uni.navigateTo({
					url: '/pages/goods/detail?proId=' + goods.id
				})
			},
			getMchData(){
				let i, mchIds = [];
				for(i=0; i < this.data.list.length; i++){
					mchIds.push(this.data.list[i].id);
				}
				this.$http.request({
					url: this.$api.plugin.mch.recommand_mch_data,
					showLoading: false,
					data: {
						mch_ids:mchIds
					},
					method: "post"
				}).then(res => {
					if(res.code==0){
						let list = res.data.list;
						for(i=0; i < list.length; i++){
							this.matchMchData(list[i]);
						}
					}else{
						this.$http.toast(res.msg)
					}
				}).catch(e => {
					this.$http.toast(e)
				});
			},
			matchMchData(item){
				let i, newList = [], newItem;
				for(i=0; i < this.data.list.length; i++){
					newItem = this.data.list[i];
					if(newItem.id == item.mch_id){
						newItem['store_id'] = item.store_id;
						newItem['cover_url'] = item.cover_url;
						newItem.goodsNum = item.goods_num;
						newItem.orderNum = item.order_num;
						newItem.goodsList = item.goods_list;
						this.data.list[i] = newItem;
						break;
					}
				}
				this.$forceUpdate();
			}
		}
	}
</script>

<style>
	.diy-mch{}
	.diy-mch .mch-list {
		padding: 9rpx;
	}
	
	.diy-mch .mch-item {
		padding-bottom: 16rpx;
		margin-bottom: 12rpx;
		border-radius: 8rpx;
	}

	.diy-mch .mch-pic,
	.diy-mch .goods-pic {
		display: inline-block;
		background-repeat: no-repeat;
		background-position: center;
		background-size: 210rpx 210rpx;
		background-color: #d6d6d6;
	}

	.diy-mch .mch-name {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.diy-mch .mch-info {
		color: #B8B8B8;
		font-size: 24rpx;
	}

	.diy-mch .goods-list {
		overflow-x: auto;
		overflow-y: hidden;
		display:flex;
	}

	.diy-mch .goods-item {
		background: #fff;
		position: relative;
		height: 226rpx;
		margin-right:10rpx;
	}
	.diy-mch .goods-item:nth-child(3){margin-right:0}
	.diy-mch .goods-pic{width: 226rpx;height: 226rpx;}

	.diy-mch .goods-price {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		color: #ff4544;
		text-align: center;
		position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
		height: 50rpx;
		line-height: 50rpx;
		background-color: rgba(255, 255, 255, .8)
	}

	.diy-mch .mch-item .to-look {
		height: 60rpx;
		width: 150rpx;
		border: 1rpx solid #E7E7E7;
		border-radius: 30rpx;
		text-align: center;
		line-height: 58rpx;
		color: #353535;
		font-size: 24rpx;
	}
</style>
