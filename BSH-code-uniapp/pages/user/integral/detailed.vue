<template>
	<view class="app">
		<!--header-->
		<!-- <view class="app-header-box"><com-nav-bar @clickLeft="back" left-icon="back" title="积分明细" :status-bar="true"></com-nav-bar></view> -->
		<!--header-->
		<block v-if="dataList && dataList.length">
			<view class="items" v-for="(item, i) in dataList" :key="i">
				<view class="item" >
					<view class="item-left">
						<view class="update margin-bottom text-12-pt" :style="{color:item.type == 1 ?textColor:''}">{{item.money}}</view>
						<view class="sum text-10-pt" :style="{color:textColor}">{{currencyAlias.integral_alias}}券：{{item.before_money}}</view>
					</view>
					<view class="item-right">
						<view class="date-time text-10-pt">{{ item.created_at|formatDate}}</view>
					</view>
				</view>
				<view class="desc margin-bottom text-10-pt">{{ item.desc}}</view>
			</view>
		</block>
		
		<view class="items" v-else>
			<main-nomore text="暂无数据" :visible="true" bgcolor="transparent"></main-nomore>
		</view>
		
		<!--加载loadding-->
		<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="!pullUpOn" bgcolor="#fafafa"></main-nomore>
		<main-loading :visible="loading"></main-loading>
		<!--加载loadding-->
	</view>
</template>

<script>
const _status = 'refresh';
export default {
	data() {
		return {
			record_id : '',	//有效券id
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
			currencyAlias:{
				balance_alias: '',
				red_envelope_alias: '',
				integral_alias: '',
				silver_beans_alias: '',
			},
		};
	},
	filters: {
	  // 秒级时间戳转标准时间格式
	  formatDate: function (value) {
		let date = new Date(value*1000);
		let y = date.getFullYear();
		let MM = date.getMonth() + 1;
		MM = MM < 10 ? ('0' + MM) : MM;
		let d = date.getDate();
		d = d < 10 ? ('0' + d) : d;
		let h = date.getHours();
		h = h < 10 ? ('0' + h) : h;
		let m = date.getMinutes();
		m = m < 10 ? ('0' + m) : m;
		let s = date.getSeconds();
		s = s < 10 ? ('0' + s) : s;
		return y + '-' + MM + '-' + d + ' ' + h + ':' + m + ':' + s;
	  }
	},
	onLoad(e) {
		let currencyAliasConfig = uni.getStorageSync('currencyAlias');
		if(currencyAliasConfig){
			JSON.parse(currencyAliasConfig);
			this.currencyAlias = JSON.parse(currencyAliasConfig);
		}
		this.record_id = e.id;
		console.log(this.record_id);
		// if(uni.getStorageSync('mall_config')){
		// 	this.textColor = this.globalSet('textCol');
		// }
		this.getDate();
	},
	methods: {
		back() {
			this.navBack();
		},
		updateStatus(type, data) {
			return type == 1 ? `+${data}` : `-${data}`;
		},
		
		// 获取列表数据
		getDate() {
			this.loading = true;
			this.$http
				.request({
					url: this.$api.user.integral_deduct_list,	//有效券详情
					method: 'POST',
					data: {
						record_id : this.record_id,
						controller_type:0
					}
				})
				.then(res => {
					this.loading = false;
					if (res.code === 0) {
						this.dataList = res.data.deduct.list;
						// let { list, pagination } = res.data;
						// this.dataList = status != 'refresh' ? this.dataList.concat(list) : list;
						// this.pages = pagination;
						// this.pullUpOn = true;
					}
				});
		}
	},
	// 下拉刷新
	onPullDownRefresh() {
		
	},
	// 上拉加载更多
	onReachBottom() {
		
	}
};
</script>

<style lang="scss" scoped>
.app {
	min-height: 100%;
	background-color: #f7f7f7;
	// color: #000000;

	.app-header-box {
		border-bottom: 4rpx solid #f7f7f7;
	}
}

.items {
	box-sizing: border-box;
	width: 750rpx;
	padding: 0 30rpx 10rpx;
	margin-bottom: 20rpx;
	display: flex;
	flex-direction: column;
	background-color: #fff;
	.item {
		display: flex;
		font-size: 9pt;
		padding: 10rpx 0;
		.item-left {
			flex: 1;
			max-width: 50%;
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
			.date-time {
				color: #bdbdbd;
				font-size: 10pt;
			}
		}
	}
}
.desc {
	color: #bdbdbd;
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
</style>
