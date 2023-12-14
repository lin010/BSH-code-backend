<template>
	<view class="income-root">
		<!-- #ifdef H5 -->
		<view class="asset_types" style="width: 100%;height: 120rpx;background: #f4f4f4;position: fixed;top: 80rpx;left: 0;z-index: 999;">
		<!--#endif -->
		
		<!-- #ifdef MP-WEIXIN || APP-PLUS -->
		<view class="asset_types" style="width: 100%;height: 120rpx;background: #f4f4f4;position: fixed;top: 0rpx;left: 0;z-index: 999;">
		<!--#endif -->	
		
			<view class="asset_types_select" style="width: 220rpx;background: #fff;height: 70rpx;border-radius: 15rpx;line-height: 70rpx;padding-left: 40rpx;margin-top: 25rpx;margin-left: 20rpx;color: #000;box-sizing: border-box;" @click="selectasset" >
				 {{asseText}}
				<image :src="img_url+'/upstrong.png'" mode="" style="display: block;width: 36rpx;height: 36rpx;position: absolute;top: 45rpx;left: 190rpx;"></image>
			</view>
		</view>
		
		<!-- #ifdef H5 -->
		<view class="income-root-header" style="width: 100%;overflow: hidden;position: fixed;top: 200rpx;left: 0;">
		<!--#endif -->
		
		<!-- #ifdef MP-WEIXIN || APP-PLUS -->
		<view class="income-root-header" style="width: 100%;overflow: hidden;position: fixed;top: 118rpx;left: 0;">
		<!--#endif -->
			<view class="pick-time">
				<view class="pick-time-detail">
					<picker mode="date" :value="date"  @change="bindDateChange" fields='month'>
						<view class="uni-input">{{date ? date : "日期"}}</view>
					 </picker>
					<image :src="img_url+'/upstrong.png'" mode=""></image>
					<view style="font-size: 26rpx;color: #A0A0A0;">
						<text style="margin-right: 20rpx;">收入￥{{detailed_count.income}}</text>
						<text>支出￥{{detailed_count.expenditure}}</text>
					</view>
				</view>
				<view style="float: right;height: 85rpx;margin-right: 20rpx;" @click="reset">
					<text style="display: block;width: 100rpx;height: 60rpx;background:#FF7104;text-align: center;line-height: 60rpx;
					margin-top: 25rpx;color: #fff;border-radius: 15rpx;">重置</text>
				</view>
				<view style="float: right;height: 85rpx;margin-right: 30rpx;" @click="screening">
					<text style="display: block;width: 100rpx;height: 60rpx;background:#FF7104;text-align: center;line-height: 60rpx;
					margin-top: 25rpx;color: #fff;border-radius: 15rpx;">筛选</text>
				</view>
			</view>
		</view>
		
		<view style="margin-top: 250rpx;margin-bottom: 30rpx;">
			<view class="detail-box" v-for="(item,index) in list" :key='index'>
				<view class="detail-item-box">
					<view class="price flex flex-x-between">
						<view v-if="item.type==1">收入：<text :style="{color: '#FF7104'}">{{item.income}}</text></view>
						<view v-if="item.type==2">支出：<text :style="{color: '#FF7104'}">{{item.income}}</text></view>
						<view>剩余金额： <text :style="{color: '#FF7104'}">{{item.money}}</text> </view>
					</view>
					<view class="explanation">
						说明：{{item.desc}}
					</view>
					<view class="time">{{item.created_at}}</view>
				</view>
			</view>
		</view>
		
		<!--加载loadding-->
		<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="!pullUpOn"></main-nomore>
		
		<unipopup ref="popup" type="bottom">
			<view class="popup-detail">
				<view class="popup-detail-header" style="border-bottom: 1px solid #eee;">
					请选择类型
				</view>
				
				<view class="search" style="">
					<input @confirm="search" confirm-type="search" v-model="keyword" type="text" placeholder="搜索" />
					<icon @click="search" type="search" size="18" style="padding:0 20rpx;"/>
				</view>
				
				<view class="popup-detail-list">
					<view :class="selectIndex==index?'active':''" v-for="(item,index) in incomeList" :key='index'  @click="select(index,item.type)">
						{{item.name}}
					</view>
				</view>
			</view>
		</unipopup>
		
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
	import unipopup from '@/components/uni-popup/uni-popup';
	export default {
		components: {
			unipopup
		},
		data() {
			return {
				img_url: this.$api.img_url,
				list: [],
				// status: 0,
				page: 1,
				page_count:'',
				textColor: '',
				date: "",
				popup:false,
				keyword: '',
				incomeList:[
					{name:'全部', type:''},
					{name: '管理员操作', type: 'admin'},
					{name: '提现', type: 'cash'},
					{name: '门店收益', type: 'checkout'},
					{name: '推荐门店收益', type: 'store'},
					{name: '股东分红', type: 'boss'},
					{name: '推荐酒店收益', type: 'hotel_commission'},
					{name: '酒店收益', type: 'hotel_3r_commission'},
					{name: '商品收益', type: 'goods'},
					{name: '话费直推收益', type: 'addcredit'},
					{name: '话费收益', type: 'addcredit_3r'},
					{name: '礼包收益', type: 'giftpacks_commission'},
					{name: '区域分红商品收益', type: 'region_goods'},
					{name: '区域分红门店收益', type: 'region_checkout'},
					{name: '推荐小程序订单收益', type: 'smart_shop_order'},
					{name: '小程序订单收益', type: 'smart_shop_order_3r'},
					{name: '推荐小程序订单收益', type: 'smart_shop_cyorder'},
					{name: '小程序订单收益', type: 'smart_shop_cyorder_3r'},
					{name: '业绩分配奖励收益', type: 'perform_distribution_award_order'},
					{name: '云仓库存佣金', type: 'stock'},
					{name: '云仓直推佣金', type: 'direct_push'},
					{name: '云仓平级佣金', type: 'same_level'},
					{name: '云仓级差佣金', type: 'poor'},
					{name: '云仓利润佣金', type: 'poor_profit'},
				],
				selectIndex:0,
				updated_at:'',//时间
				source_type:'',//类型
				detailed_count:{
					income:0,
					expenditure:0
				},
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
				pullUpOn:true,
				loadding:false,
				currencyAlias:{
					balance_alias: '',
					red_envelope_alias: '',
					integral_alias: '',
					silver_beans_alias: '',
				},
			};
		},
		onLoad(options) {
			let currencyAliasConfig = uni.getStorageSync('currencyAlias');
			if(currencyAliasConfig){
				JSON.parse(currencyAliasConfig);
				this.currencyAlias = JSON.parse(currencyAliasConfig);
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
			if(options&&options.name=='income'){
				this.asseText="收益明细"
				this.selectassetIndex=4
			}
			if (uni.getStorageSync('mall_config')) {
				this.textColor = this.globalSet('textCol');
			}
			this.getList()
		},
		methods: {
			selectasset(){
				this.$refs.asset.open()
			},
			assetlink(item,index){
				this.$refs.asset.close()
				if(item.type=='balance'){
					uni.navigateTo({
						url:'../../../pages/user/balance/details?name='+item.type
					})
				}
				if(item.type=='total_score'){
					uni.navigateTo({
						url:'../../../pages/user/integral/integral?name='+item.type
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
					return
				}
			},
			bindDateChange: function(e) { //点击选择年月
				let time=e.target.value		
				this.pullUpOn=true
			    this.date = time.split('-')[0]+'年'+time.split('-')[1]+'月'
				this.page=1
				this.list=[]
				this.page_count=''
				this.updated_at=this.date
				this.getList()
			},		
			search(){
				this.pullUpOn=true
				this.page=1
				this.list=[]
				this.page_count=''
				this.getList()
				this.$refs.popup.close()
			},
			getList() {
				this.loadding = true;
				this.$http.request({
					url: this.$api.income.list,
					data: {
						page: this.page,
						updated_at:this.updated_at,
						source_type:this.source_type,
						keyword: this.keyword
						// status: '',
					},
				}).then((res) => {
					this.loadding = false;
					if (res.code == 0) {
						this.detailed_count=res.data.detailed_count
						if (res.data.list.length == 0){
							this.pullUpOn=false
							return false
						}
						let list = res.data.list
						var arr = this.list.concat(list)
						this.list = arr
						this.page_count = res.data.pagination.page_count
						
					} else {
						this.$http.toast(res.msg);
					}
				})
			},
			screening(){ //点击筛选
				this.$refs.popup.open()
			},
			select(index,type){//点击筛选选择
				this.$refs.popup.close()
				this.selectIndex=index
				this.page=1
				this.list=[]
				this.page_count=''
				if(this.date=='日期'){
					this.updated_at=''
				}else{
					this.updated_at=this.date
				}
				this.source_type=type
				this.getList()
			},
			reset(){
				this.page=1
				this.list=[]
				this.date="";
				this.keyword = '';
				this.page_count=''
				this.updated_at=''
				this.source_type=''
				this.selectIndex=0
				this.getList()
			}
		},
		onReachBottom: function(e) {
			
			if(this.loadding){
				return
			}
			
			if (this.page >= this.page_count) {
				this.pullUpOn=false
				return
			}
			this.page = this.page + 1
			this.getList();
		},
	}
</script>

<style lang="scss" scoped>
	.search{background:#F9F9F9;display:flex;align-items: center;border-radius:70rpx;height:70rpx;margin:20rpx 20rpx;padding:0 30rpx;border:1px solid #eee;}
	.search input{flex-grow: 1;font-size:28rpx;}
	
	.detail-box {
		padding: 0 30rpx;

		.detail-item-box {
			background: #FFFFFF;
			margin-top: 20rpx;
			border-radius: 10rpx;
			padding: 30rpx 20rpx;

			.time {
				border-top: 1px solid #F3F3F3;
				padding-top: 16rpx;
				line-height: 50rpx;
			}

			.price {
				padding: 0 0 16rpx 0;
				border-bottom: 1px solid #F3F3F3;
			}

			.explanation {
				font-size: 30rpx;
				padding: 16rpx 0 16rpx 0;
			}
		}
	}
	.nothing {
		padding-top: 200rpx;
		text-align: center;
		letter-spacing: 1px;

	}
	.income-root{width: 100%;overflow: hidden;}
	.pick-time{width: 100%;height: 110rpx;background: rgb(244,244,244);}
	.pick-time-detail{width: 420rpx;height: 60rpx;float: left;margin: 20rpx 30rpx;border-radius: 15rpx;font-weight: bold;color: #000;position: relative;}
	.pick-time-detail image{display: block;width: 36rpx;height: 36rpx;position: absolute;top: 7rpx;left: 185rpx;}
	.popup-detail{width: 100%;background: #fff;min-height: 400rpx;border-radius: 0 0 20rpx 20rpx;padding-bottom: 30rpx;}
	.popup-detail-header{width: 100%;height: 80rpx;line-height: 80rpx;padding: 0 20rpx;font-size: 30rpx;color: #000;font-weight: bold;}
	.popup-detail-list{justify-content: space-around;width: 100%;overflow: hidden;margin-bottom: 30rpx;display: flex;flex-wrap: wrap;}
	.popup-detail-list > view{width: 220rpx;height: 70rpx;line-height: 70rpx;text-align: center;float: left;margin: 20rpx 0rpx 0px 0rpx;padding:0 10rpx;border-radius: 10rpx;font-size: 26rpx;border:1px solid #eee;word-break: keep-all; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;}
	.active{background: rgb(222,59,45);color: #fff;}
	.actove{background: rgb(222,59,45);color: #fff;}
</style>