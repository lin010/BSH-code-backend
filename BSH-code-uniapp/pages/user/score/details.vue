<template>
	<view class="app">
		<!--header-->
		<view class="app-header-box"><com-nav-bar @clickLeft="back" left-icon="back" :title="`${currencyAlias.integral_alias}明细`" :status-bar="true"></com-nav-bar></view>
		
		<!--header-->
		<view class="items" v-if="dataList && dataList.length">
			<view class="item" v-for="(item, i) in dataList" :key="i">
				<view class="item-left">
					<view class="desc margin-bottom text-12-pt">{{ item.desc }}</view>
					<view class="date-time text-10-pt">{{ item.created_at }}</view>
				</view>
				<view class="item-right">
					<view class="update margin-bottom text-12-pt" :style="{color:item.type == 1 ?'#FF7104':''}">{{ updateStatus(item.type, item.score) }}</view>
					 <!-- :class="item.type == 1 ? 'add' : 'dot'" -->
					<view class="sum text-10-pt" :style="{color:'#FF7104'}">账户{{currencyAlias.integral_alias}}：{{ item.current_score }}</view>
				</view>
			</view>
		</view>
		<view class="items" v-else><main-nomore text="暂无记录" :visible="true" bgcolor="transparent"></main-nomore></view>

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
			
			
			// ceshi_arr:[
			// 	{
					
			// 	}
			// ]
			ceshi:{
				val0:'',
				val1:'',
				val2:'',
			},
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
		if(uni.getStorageSync('mall_config')){
			this.textColor = this.globalSet('textCol');
		}
		this.getDateList(_status, true);
	},
	methods: {
		back() {
			this.navBack();
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
					url: this.$api.user.scoreDetails,
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
	margin: 0 30rpx;
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
</style>
