<template>
	<view class="app">
		<!-- #ifdef H5 -->
		<view class="asset_types" style="width: 100%;height: 120rpx;background: #f4f4f4;position: fixed;top: 80rpx;left: 0;z-index: 999;">
		<!--#endif -->
		<!-- #ifdef MP-WEIXIN || APP-PLUS -->
		<view class="asset_types" style="width: 100%;height: 120rpx;background: #f4f4f4;position: fixed;top: 0rpx;left: 0;z-index: 999;">
		<!--#endif -->	
			<view class="asset_types_select" style="width: 220rpx;background: #fff;height: 70rpx;border-radius: 15rpx;line-height: 70rpx;padding-left: 50rpx;margin-top: 25rpx;margin-left: 20rpx;color: #000;box-sizing: border-box;"
			@click="selectasset" >
			     {{asseText}}
				<image :src="img_url+'/upstrong.png'" mode="" style="display: block;width: 36rpx;height: 36rpx;position: absolute;top: 45rpx;left: 190rpx;"></image>
			</view>
		</view>
		
		
		
		<view class="items" v-if="dataList && dataList.length">
			<view class="item" v-for="(item, i) in dataList" :key="i">
				<view class="item-left">
					<view class="desc margin-bottom text-12-pt">{{ item.desc }}</view>
					<view class="date-time text-10-pt">{{ item.created_at }}</view>
				</view>
				<view class="item-right">
					<view class="update margin-bottom text-12-pt" :style="{color:item.type == 1 ?'#FF7104':''}">{{ updateStatus(item.type, item.money) }}</view>
					 <!-- :class="item.type == 1 ? 'add' : 'dot'" -->
					<view class="sum text-10-pt" :style="{color:'#FF7104'}">账户{{currencyAlias.balance_alias}}：{{ item.balance }}</view>
				</view>
			</view>
		</view>
		<view class="items" v-else><main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore></view>

		<!--加载loadding-->
		<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="!pullUpOn" bgcolor="#FFFFFF"></main-nomore>
		<main-loading :visible="loading"></main-loading>
		<!--加载loadding-->
		<unipopup ref="asset" type="bottom">
			<view class="popup-detail">
				<view class="popup-detail-header">
					请选择资产类型
				</view>
				<view class="popup-detail-list">
					<view :class="selectassetIndex==index?'actove':''" v-for="(item,index) in assetList" :key='index'  @click="assetlink(item,index)">
						{{item.name}}
					</view>
				</view>
			</view>
		</unipopup>
	</view>
</template>

<script>
const _status = 'refresh';
import unipopup from '@/components/uni-popup/uni-popup';
export default {
	components: {
		unipopup
	},
	data() {
		return {
			img_url: this.$api.img_url,
			loadding: false,
			pullUpOn: true,
			loading: false,
			dataList: [],
			pages: {
				total_count: 1,
				page_count: 1,
				pageSize: 20,
				current_page: 1
			},
			textColor:'#bc0100',
			
			asset:false,
			assetList:[
				{
					name:'余额',
					type:'balance'
				},
				{
					name:'积分',
					type:'total_score'
				},
				{
					name:'金豆',
					type:'redBag'
				},
				{
					name:'红包',
					type:'shopping_voucher'
				},
				{
					name:'收益明细',
					type:'income'
				},
			],
			asseText:'',
			selectassetIndex:0,
			 currencyAlias:{
				balance_alias: '',
				red_envelope_alias: '',
				integral_alias: '',
				silver_beans_alias: '',
			},
		};
	},
	onLoad(options) {
		if(options&&options.name=='balance'){
			this.asseText="余额"
			this.selectassetIndex=0
		}
		if(uni.getStorageSync('mall_config')){
			this.textColor = this.globalSet('textCol');
		}
		let currencyAliasConfig = uni.getStorageSync('currencyAlias');
		if(currencyAliasConfig){
			JSON.parse(currencyAliasConfig);
			this.currencyAlias = JSON.parse(currencyAliasConfig);
			uni.setNavigationBarTitle({
			      title: this.currencyAlias.balance_alias+'明细'
			    });
			this.asseText =this.currencyAlias.balance_alias;
			this.assetList = [
				{
					name:this.currencyAlias.balance_alias,
					type:'balance'
				},
				{
					name:this.currencyAlias.integral_alias,
					type:'total_score'
				},
				{
					name:this.currencyAlias.red_envelope_alias,
					type:'redBag'
				},
				{
					name:this.currencyAlias.silver_beans_alias,
					type:'shopping_voucher'
				},
				{
					name:'收益明细',
					type:'income'
				},
			];
		}
		this.getDateList(_status, true);
	},
	methods: {
		selectasset(){
			this.$refs.asset.open()
		},
		assetlink(item,index){
			this.$refs.asset.close()
			if(item.type=='balance'){
				return
			}
			if(item.type=='total_score'){								
				uni.navigateTo({
					url:'../integral/integral?name='+item.type
				})
			}
			if(item.type=='redBag'){
				uni.navigateTo({
					url:'../../../mch/redBag/redBag?name='+item.type
				})
			}
			if(item.type=='shopping_voucher'){
				uni.navigateTo({
					url:'../../../mch/vouchers/vouchers?name='+item.type
				})
			}
			if(item.type=='income'){
				uni.navigateTo({
					url:'../../../plugins/extensions/income/income?name='+item.type
				})
			}
		},
		updateStatus(type, data) {
			return type == 1 ? `+${data}` : `-${data}`;
		},
		getDateList(status, bool) {
			this.loading = bool ? true : false;
			if (status == 'refresh') {
				this.pages = {
					current_page: 1,
					pageSize: 20,
					page_count: 1,
					total_count: 0
				};
			}
			let { current_page, pageSize } = this.pages;

			this.$http
				.request({
					url: this.$api.user.balanceDetails,
					method: 'POST',
					data: {
						page: current_page,
						limit: pageSize
					}
				})
				.then(res => {
					this.loading = false;
					if (res.code === 0) {
						let { list, pagination } = res.data;
						this.dataList = status != 'refresh' ? this.dataList.concat(list) : list;
						this.pages = pagination;
						this.pullUpOn = true;
					}
				});
		}
	},
	onPullDownRefresh() {
		setTimeout(() => {
			uni.stopPullDownRefresh();
			this.getDateList(_status);
		}, 1000);
	},
	onReachBottom() {
		this.loadding = true;
		this.pullUpOn = true;
		let { current_page, page_count } = this.pages;
		setTimeout(() => {
			this.loadding = false;
			if (current_page >= page_count) {
				this.pullUpOn = false;
				return;
			}
			this.pages.current_page++;
			this.getDateList();
		}, 1000);
	}
};
</script>

<style lang="scss" scoped>
.app {
	min-height: 100%;
	background-color: #ffffff;
	color: #000000;

	.app-header-box {
		border-bottom: 4rpx solid #f7f7f7;
	}
}

.items {
	margin: 120rpx 30rpx 0 30rpx;
	display: flex;
	flex-direction: column;

	.item {
		display: flex;
		font-size: 9pt;
		padding: 30rpx 0;
		border-bottom: 1rpx solid #ececec;

		.item-left {
			flex: 1;
			max-width: 50%;

			.desc {
				overflow: hidden;
				text-overflow: ellipsis;
				white-space: nowrap;
			}
			.date-time {
				color: #bdbdbd;
				font-size: 10pt;
			}
		}

		.item-right {
			flex: 1;
			text-align: end;
			.update {
				&.add {
					color: #bc0100;
				}
			}
			.sum {
				color: #bc0100;
			}
		}
	}
}
.margin-bottom {
	margin-bottom: 10rpx;
}
.text-10-pt {
	font-size: 10pt;
}
.text-12-pt {
	font-size: 12pt;
}
.popup-detail{width: 100%;background: #fff;min-height: 400rpx;border-radius: 0 0 20rpx 20rpx;padding-bottom: 30rpx;}
	.popup-detail-header{width: 100%;height: 80rpx;line-height: 80rpx;padding: 0 20rpx;font-size: 30rpx;color: #000;font-weight: bold;}
	.popup-detail-list{width: 100%;overflow: hidden;margin-bottom: 30rpx;}
	.popup-detail-list view{min-width: 180rpx;height: 70rpx;line-height: 70rpx;text-align: center;
	float: left;margin: 20rpx 0rpx 0 40rpx;border-radius: 10rpx;font-size: 26rpx;font-weight: bold;}
	.actove{background: rgb(222,59,45);color: #fff;}
</style>
