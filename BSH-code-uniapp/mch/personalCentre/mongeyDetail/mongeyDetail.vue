<template>
	<view class="app">
		<!--header-->
		<view class="app-header-box">
			<com-nav-bar @clickLeft="back" left-icon="back" title="账户明细" :status-bar="true"></com-nav-bar>
			<view class="tabble">
				<text @click="tabbleCheck(1)" :class="switchIndex==1?'active':''">收入</text>
				<text @click="tabbleCheck(2)" :class="switchIndex==2?'active':''">支出</text>
			</view>
			<view class="pick-time">
				<view class="pick-time-detail">
					<picker mode="date" :value="date"  @change="bindDateChange" fields='month'>
					    <view class="uni-input">{{date}}</view>
					 </picker>
					<image :src="img_url+'/upstrong.png'" mode=""></image>
				</view>
				<view style="float: right;height: 85rpx;margin-right: 30rpx;" @click="reset">
					<text style="display: block;width: 100rpx;height: 60rpx;background:#FF7104;text-align: center;line-height: 60rpx;
					margin-top: 15rpx;color: #fff;border-radius: 15rpx;">重置</text>
				</view>
			</view>
		</view>
		<!--header-->
		<view class="items">
			<view class="item" v-for="(item, i) in dataList" :key="i">
				<view class="item-left">
					<view class="desc margin-bottom text-12-pt">{{ item.desc }}</view>
					<view class="date-time text-10-pt">{{ item.format_date }}</view>
				</view>
				<view class="item-right">
					<view class="update margin-bottom text-12-pt" :style="{color:item.type == 1 ?'#FF7104':''}">{{ updateStatus(item.type, item.money) }}</view>
				</view>
			</view>
		</view>
		<view class="items" v-if='dataList.length==0'>
			<text style="display: block;width: 100%;overflow: hidden;text-align: center;font-size: 24rpx;margin-top: 50rpx;color: #999;">-----&nbsp;&nbsp;&nbsp;没有更多了&nbsp;&nbsp;&nbsp;----</text>
		</view>
	</view>
</template>

<script>
export default {
	data() {
		return {
			img_url: this.$api.img_url,
			date: "全部",
			dataList: [],
			page:1,
			type:'in',//in收入，out支出
			textColor:'#bc0100',
			page_count:'',
			switchIndex:1,
			created_at:'',
			flag:false,
		};
	},
	onLoad() {
		if(uni.getStorageSync('mall_config')){
			this.textColor = this.globalSet('textCol');
		}
		this.getDateList();
	},
	methods: {
		back() {
			this.navBack();
		},
		updateStatus(type, data) {
			return type == 1 ? `+${data}` : `-${data}`;
		},
		getDateList() {	
			if(this.flag) return
			this.flag=true
			this.$http
				.request({
					url: this.$api.moreShop.getaccountList,
					method: 'POST',
					data: {
						page:this.page,
						type:this.type,
						created_at:this.created_at,
					},
					showLoading: true
				})
				.then(res => {
					if (res.code === 0) {
						this.flag=false
						if(res.data.list.length==0)return false
						let list= res.data.list;
						var arr=this.dataList.concat(list)
						this.dataList =arr
						this.page_count= res.data.pagination.page_count;
					}
				});
		},
		tabbleCheck(index){
			this.switchIndex=index
			this.pullUpOn=true
			this.dataList=[]
			this.page=1
			this.page_count=''
			if(index==1){
				this.type='in'
				this.getDateList();
			}
			if(index==2){
				this.type='out'
				this.getDateList();
			}
		},
		bindDateChange: function(e) { //点击选择年月
			let time=e.target.value		
		    this.date = time.split('-')[0]+'年'+time.split('-')[1]+'月'
			this.created_at=time.split('-')[0]+'年'+time.split('-')[1]+'月'
			this.page=1
			this.dataList=[]
			this.getDateList()			
		},
		reset(){//重置
			this.page=1
			this.dataList=[]
			this.created_at=''
			this.date='全部'
			this.getDateList()	
		}
		
	},
	onReachBottom() {
		if(this.page==this.page_count){
			return false;
		} 		
		this.page=this.page+1
		this.getDateList();
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
	width: 100%;
	display: flex;
	padding: 0 30rpx;
	box-sizing: border-box;
	flex-direction: column;

	.item {
		display: flex;
		font-size: 9pt;
		padding: 30rpx 0;
		border-bottom: 1rpx solid #F7F7F9;

		.item-left {
			flex: 1;
			max-width: 70%;

			.desc {
				overflow:hidden;  
				text-overflow:ellipsis;  
				white-space: normal;  
				display:-webkit-box;  
				-webkit-box-orient:vertical;  
				-webkit-line-clamp:3;/*规定最多显示两行*/ 
				font-size: 10pt;
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
.tabble{width: 100%;height: 80rpx;display: flex;justify-content: space-evenly;}
.tabble text{display: block;height:80rpx;line-height: 80rpx;width: 50%;text-align: center;font-weight: bold;}
.active{background: #FF7104;color: #fff;}
.pick-time{width: 100%;height: 95rpx;background: rgb(244,244,244);}
.pick-time-detail{width: 300rpx;height: 60rpx;float: left;margin: 20rpx 30rpx;border-radius: 15rpx;font-weight: bold;color: #000;position: relative;}
.pick-time-detail image{display: block;width: 36rpx;height: 36rpx;position: absolute;top: 7rpx;left: 185rpx;}
</style>

