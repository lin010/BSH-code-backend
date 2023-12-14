<template>
	<view class="page_box">
		<view class="header" :style="{'background':'#FF7104'}">
			<view class="head-content flex flex-x-between flex-y-center">
				<view class="flex flex-y-center">
					<image class="avatar" :src="avatar_url || img_url+'images/my/mine_def_touxiang_3x.png'" mode=""></image>
					<view class="content">
						<view>已连续签到{{cuntinueDays}}天</view>
						<!-- <view class="content-bottom">连续签到5天可获得10积分 </view> -->
					</view>
				</view>
				<view class="integral flex">
					<view class="integral-icon flex flex-x-center flex-y-center" :style="{'background':'#FF7104'}">
						<view class="iconfont icon-xingxing" style="color: #FFFFFF;"></view>
					</view>
					{{score}}
				</view>
			</view>
			
			<view class="content_box">
				<view class="calendar">
					<view class="bar flex flex-x-center flex-y-center">
						<view class="bar-child flex flex-x-between flex-y-center">
							<!-- 年月 -->
							<view class="previous" @click="handleCalendar(0)">
								<view class="triangle-left triangle" :style="{'--textColor':'#FF7104'}"></view>
							</view>
							<view class="date">{{ cur_year || '--' }} 年 {{ cur_month || '--' }} 月</view>
							<view class="next" @click="handleCalendar(1)">
								<view class="triangle-right triangle" :style="{'--textColor':'#FF7104'}"></view>
							</view>
						</view>
					</view>
				
					<!-- 显示星期 -->
					<view class="week flex flex-y-center">
						<view class="week-item flex flex-x-center flex-y-center" v-for="(item, index) in weeks_ch" :key="index">{{ item }}</view>
					</view>
				
					<!-- 日历表 -->
					<view class="myDateTable">
						<view v-for="(item, j) in days" :key="j" class="dateCell flex flex-x-center flex-y-center">
							<view v-if="item.date == undefined || item.date == null" class="cell"><text :decode="true">&nbsp;&nbsp;</text></view>
							<view v-else>
								<!-- 已签到日期 -->
								<view v-if="item.is_sign" class="cell is-sign" :style="{'background':'#FF7104'}">
									<text>{{ item.day }}</text>
								</view>
								<!-- 漏签 -->
								<view class="cell forget-sign" v-else-if="
										cur_year < toYear || (cur_year == toYear && cur_month < toMonth) || (cur_year == toYear && cur_month == toMonth && item.date < today)
									">
									<!-- 小程序不兼容这个 v-else-if="(new Date(cur_year+'-'+cur_month+'-'+item.date))<(new Date())"> -->
									<text>{{ item.day }}</text>
								</view>
								<!-- 今日未签到-->
								<!-- <view @click="clickSignUp(item.date, 1)" class="cell whiteColor bgBlue" v-else-if="item.date == today && cur_month == toMonth && cur_year == toYear">
										<text>签到</text>
									</view> -->
								<!-- 当前日期之后 -->
								<view class="cell" v-else>
									<text>{{ item.day }}</text>
								</view>
							</view>
						</view>
					</view>
				</view>
				<!-- 签到按钮 -->
				<view class="flex flex-x-center flex-y-center sign-box">
					<button @tap="onSign" :disabled="isSign" class=" sign-btn" :class="{'cu-btn':isSign}" :style="{'--textColor':textColor}">{{ isSign ? '已签到' : '点击签到' }}</button>
				</view>
				
				<view class="rules">
					<view>签到规则说明：</view>
					<jyf-parser :html="rich_text"></jyf-parser>
				</view>
				
				<view class="guessLike">
					猜你喜欢
				</view>
				<view>
					<commodity :listStyle='listStyle' key-value='cart' :displayStyle='displayStyle' :productData='productData'></commodity>
				</view>
			</view>
		</view>
		
		<!-- 签到成功弹窗 -->
		<view class="pop-ups flex flex-y-center flex-x-center" v-if="is_show" @tap='hide'>
			<view class="content-box flex flex-y-center">
				<view class="content" :style="{ background: 'url('+img_url+'images/sign-in/success_bg.png)'}">
					<view class="up flex flex-y-center">
						<view class="iconfont icon-xuanzhong success" :style="{'--textColor':textColor}"></view>
						<view>签到成功</view>
					</view>
					<view class="tips">明天记得再来喔！</view>
				</view>
				<view class="iconfont icon-47guanbi close" @tap='hide'></view>
			</view>
		</view>
	</view> 
</template>

<script>
	import commodity from '@/components/commodity/commodity';
	import shoproModal from '@/components/shopro-modal/shopro-modal.vue';
	import jyfParser from "@/components/jyf-parser/jyf-parser";
	export default {
		components: {
			commodity,
			shoproModal,
			jyfParser
		},
		data() {
			return {
				days: [],
				cuntinueDays: 0,
				score: '',
				cur_year: 0, //当前选的年
				cur_month: 0, //当前选的月
				cur_day: 0, //当前选择的天
				today: parseInt(new Date().getDate()), //本日
				toMonth: parseInt(new Date().getMonth() + 1), //本月
				toYear: parseInt(new Date().getFullYear()), //本年
				weeks_ch: ['日', '一', '二', '三', '四', '五', '六'],
				isSign: false,
				avatar_url:'', //头像地址
				rich_text:'',
				is_show:false, //控制弹窗是否显示
				
				listStyle: 2, //1为大图 2为一行一个 3为一行两个 4一行三个 5为左右滑动
				displayStyle: 1, //1为常规 2为边框 3为居中显示 4为边框居中
				productData: [],
				
				img_url: this.$api.img_url,
				textColor:'red'
			};
		},
		onLoad() {
			this.textColor = this.globalSet('textCol');
			this.cur_year = this.toYear;
			this.cur_month = this.toMonth;
			this.cur_day = this.today;
			this.getSignList();
			this.getRecommend();
			this.getUserInfo();
		},
		methods: {
			hide(){
				this.is_show = false;
			},
			getUserInfo() { //获取用户信息
				this.$http.request({
					url: this.$api.user.userInfo,
					method: 'POST',
				}).then(res => {
					if (res.code == 0) {
						this.score = res.data.total_score;
						this.avatar_url = res.data.avatar;
					}
				})
			},
			getRecommend() { //获取猜你喜欢
				this.loading = true;
				this.$http.request({
					url: this.$api.goods.recommend,
					showLoading:true,
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
			// 当前签到记录
			getSignList(str) {
				let month = this.cur_month.toString().padStart(2, '0');
				let today = this.cur_day.toString().padStart(2, '0');
				let query = `${this.cur_year}-${month}`;
				this.$http.request({
					url: this.$api.plugin.sign.index,
					method: 'post',
					showLoading:true,
					data:{
						month: query
					}
				}).then((res)=>{
					if(res.code == 0){
						var date = `${query}-${today}`;
						let emptyDays = this.calculateEmptyGrids();
						this.cuntinueDays = res.data.continue_day; //连续天数
						this.days = [...emptyDays, ...res.data.sign_in_day];
						this.rich_text = res.data.config.rule;
						
						if(str != 'NotRefresh'){
							res.data.sign_in_day.forEach((item)=>{ //判断是否已经签到
								if(item.date == date){
									this.isSign = item.is_sign
								}
							})
						}
					}else{
						this.$http.toast(res.msg);
						setTimeout(()=>{
							this.navBack();
						},1000)
					}
				})
			},
			// 选择日期
			// selSign() {
			// 	console.log(123);
			// 	let selToday =
			// 		`${this.toYear}-${this.toMonth.toString().padStart(2, '0')}-${this.today.toString().padStart(2, '0')}`;
			// 	let newDay =
			// 		`${this.cur_year}-${this.cur_month.toString().padStart(2, '0')}-${this.cur_day.toString().padStart(2, '0')}`;
			// 	if (selToday === newDay) {
			// 		let day = this.days.find(item => {
			// 			return item.date === selToday;
			// 		});
			// 		this.isSign = day.is_sign;
			// 	}
			// },
			// 计算当月1号前空了几个格子
			calculateEmptyGrids() {
				let that = this;
				let emptyDays = [];
				const firstDayOfWeek = new Date(Date.UTC(that.cur_year, that.cur_month - 1, 1)).getDay();
				if (firstDayOfWeek > 0) {
					for (let i = 0; i < firstDayOfWeek; i++) {
						var obj = {
							day: null,
							is_sign: false
						};
						emptyDays.push(obj);
					}
				}
				return emptyDays;
			},
			//签到
			onSign() {
				let today =`${this.cur_year}-${this.toMonth.toString().padStart(2, '0')}-${this.today.toString().padStart(2, '0')}`;
				
				this.$http.request({
					url: this.$api.plugin.sign.user_sign_in,
					method: 'post',
					showLoading:true,
				}).then((res) => {
					if(res.code == 0){
						this.getUserInfo();
						this.is_show = true;
						this.cuntinueDays++;
						this.days.forEach((item)=>{
							if(item.date == today){
								this.$set(item,'is_sign',true);
							}
						})
						this.isSign = true;
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
			// 切换控制年月，上一个月，下一个月
			handleCalendar(type) {
				const cur_year = parseInt(this.cur_year);
				const cur_month = parseInt(this.cur_month);
				let newMonth = cur_month;
				let newYear = cur_year;
				if (type === 0) {
					//上个月
					newMonth = cur_month - 1;
					if (newMonth < 1) {
						newYear = cur_year - 1;
						newMonth = 12;
					}
				} else {
					newMonth = cur_month + 1;
					if (newMonth > 12) {
						newYear = cur_year + 1;
						newMonth = 1;
					}
				}
				this.cur_year = newYear;
				this.cur_month = newMonth;
				this.getSignList('NotRefresh');
				this.$emit('dateChange', this.cur_year + '-' + this.cur_month); //传给调用模板页面去拿新数据
			},

			// 补签(暂时没用到)
			clickSignUp(date, type) {
				//0补签，1当日签到
				var str = '签到';
				if (type == 0) {
					//如果不需要补签功能直接在这阻止不执行后面的代码就行。
					str = '补签';
				}
				this.$emit('clickChange', this.cur_year + '-' + this.cur_month + '-' + date); //传给调用模板页面
			}
		}
	};
</script>

<style lang="scss">
	.header{
		background: #07BEB4;
		height: 380rpx;
		padding: 52rpx 0 0 48rpx;
		color: #FFFFFF;
		box-sizing: border-box;
		position: relative;
		
		.avatar{
			width: 130rpx;
			height: 130rpx;
			border-radius: 50%;
			margin-right: 28rpx;
		}
		.content{
			font-size: 34rpx;
			
			.content-bottom{
				font-size: 24rpx;
				color:rgba(254,254,254,1);
				opacity:0.65;
			}
		}
		.integral{
			background: #FFFFFF;
			// width: 210rpx;
			height: 66rpx;
			border-radius: 50rpx 0 0 50rpx;
			color: #000000;
			padding: 10rpx 30rpx;
			box-sizing: border-box;
			font-size: 30rpx;
			font-weight: 600;
			
			.integral-icon{
				background: #07BEB4;
				height: 46rpx;
				width: 46rpx;
				border-radius: 50%;
				margin-right: 18rpx;
			}
		}
	}
	/* 补签 */
	.resign-box {
		background: #fff;
		padding: 30rpx 35rpx 40rpx;

		.title-box {
			.resign-title {
				font-size: 32rpx;
				font-weight: 600;
			}

			.resign-card-num {
				color: #e3ad5b;
				font-size: 32rpx;
				font-weight: 600;
			}
		}

		.invite-box {
			width: 677rpx;
			height: 118rpx;
			background: rgba(233, 184, 103, 0.2);
			border-radius: 59rpx;
			padding: 0 25rpx;
			margin-top: 40rpx;

			.invite-tag {
				width: 80rpx;
				height: 80rpx;
				border-radius: 50%;
			}

			.invite-notice {
				font-size: 24rpx;
				font-weight: 500;
				color: rgba(227, 173, 91, 1);
			}

			.invite-btn {
				width: 119rpx;
				height: 50rpx;
				background: linear-gradient(90deg, rgba(233, 180, 97, 1), rgba(238, 204, 137, 1));
				border-radius: 25rpx;
				font-size: 24rpx;
				font-weight: 500;
				padding: 0;
				color: rgba(255, 255, 255, 0.9);
			}
		}
	}
	
	.content_box{
		position: absolute;
		width: 96%;
		left: 50%;
		top: 240rpx;
		transform: translateX(-50%);
	}
	// 日历
	.calendar {
		background: #fff;
		box-shadow:2px 2px 9px 1px rgba(9,63,121,0.15);
		border-radius: 10rpx;
		overflow: hidden;
		
		// 年月日
		.bar {
			height: 100rpx;
			font-size: 30rpx;
			
			.bar-child{
				width: 90%;
				background: #F3F2F2;
				border-radius: 60rpx;
				padding: 10rpx 24rpx;
			}

			.bar-btn {
				background: none;

				.cuIcon-back,
				.cuIcon-right {
					color: #c4c4c4;
				}
			}

			.date {
				font-weight: 600;
				color: #000000;
			}
		}

		// 星期
		.week {
			.week-item {
				font-size: 24rpx;
				font-weight: 500;
				color: rgba(153, 153, 153, 1);
				flex: 1;
			}
		}

		// 日历表
		.myDateTable {
			display: flex;
			flex-wrap: wrap;

			.dateCell {
				width: (100% / 7);
				height: 80rpx;
				font-size: 26rpx;
				font-family: DINPro;
				font-weight: 400;
				color: rgba(51, 51, 51, 1);

				.cell {
					display: flex;
					justify-content: center;
					align-items: center;
				}
			}
		}
	}

	.is-sign {
		width: 44rpx;
		height: 44rpx;
		// background: #07BEB4;
		border-radius: 50%;
		// box-shadow: 0 0 4rpx 4rpx #07BEB4;
		color: #fff;
	}

	// .forget-sign {
	// 	color: #dd524d;
	// }

	// 签到按钮
	.sign-box {
		height: 140rpx;
		width: 100%;

		.sign-btn {
			width: 96%;
			height: 90rpx;
			background: #FF7104;
			border-radius: 80rpx;
			font-size: 36rpx;
			font-weight: 500;
			color: rgba(255, 255, 255, 0.9);
			letter-spacing: 4rpx;
		}
		.cu-btn{
			background: #b8bcb0;
		}
	}

	.sign-modal-box {
		width: 520rpx;
		background: #fff;
		border-radius: 20rpx;
		left: 50%;
		top: 50%;
		transform: translate(-50%, -50%);
		position: absolute;
		overflow: hidden;

		.modal-head {
			height: 320rpx;
			// background:linear-gradient(177deg,rgba(233,180,97,1) 0%,rgba(238,204,137,1) 100%);
			align-items: center;
			justify-content: center;
			position: relative;

			.modal-bg {
				width: 100%;
				height: 100%;
			}

			.sign-tag {
				width: 70rpx;
				height: 70rpx;
				// background: #ccc;
				position: absolute;
				top: 50rpx;
				left: 50%;
				transform: translateX(-50%);
			}

			.sign-num-box {
				font-size: 30rpx;
				color: #fff;
				position: absolute;
				top: 150rpx;
				left: 50%;
				transform: translateX(-50%);

				.sign-num {
					font-size: 36rpx;
					font-weight: 600;
					padding: 0 10rpx;
				}
			}
		}

		.modal-content {
			padding: 30rpx 0;

			.sign-success {
				font-size: 34rpx;
				font-family: PingFang SC;
				font-weight: bold;
				color: rgba(227, 173, 91, 1);
			}

			.sign-score {
				font-size: 26rpx;
				font-family: PingFang SC;
				font-weight: 500;
				color: rgba(153, 153, 153, 1);
				padding-top: 16rpx;
			}
		}

		.modal-bottom {
			padding-bottom: 47rpx;

			.confirem-btn {
				width: 300rpx;
				height: 70rpx;
				background: linear-gradient(90deg, rgba(233, 180, 97, 1), rgba(238, 204, 137, 1));
				border-radius: 35rpx;
				padding: 0;
				font-size: 28rpx;
				font-family: PingFang SC;
				font-weight: 500;
				color: rgba(255, 255, 255, 0.9);
			}
		}
	}

	.pad {
		padding: 0 30rpx;
	}
	
	.triangle{
		width: 0;
		height: 0;
		border-style: solid;
	}
	.triangle-left {
		border-width: 10rpx 18rpx 10rpx 0rpx;
		border-color: transparent #FF7104 transparent transparent;
	}
	.triangle-right {
		border-width: 10rpx 0rpx 10rpx 18rpx;
		border-color: transparent transparent transparent #FF7104;
	}
	
	// 活动规则
	.rules{
		background: #FFFFFF;
		border-radius: 10rpx;
		box-shadow:7px 10px 9px 2px rgba(9,64,121,0.15);
		padding: 28rpx 28rpx 28rpx 54rpx;
		box-sizing: border-box;
		margin-top: 20rpx;
		color: #9A989A;
		font-size: 26rpx;
	}
	
	// 签到成功弹窗
	.pop-ups{
		background: rgba(0,0,0,0.2);
		width: 100%;
		height: 100%;
		position: fixed;
		top: 0;
		left: 0;
		z-index: 999999;
		
		.content-box{
			width: 364rpx;
			flex-direction: column;
			
			.content{
				width: 364rpx;
				height: 404rpx;
				background: #FFFFFF;
				border-radius: 20rpx;
				padding-top: 20rpx;
				background-size: cover !important;
				
				.up{
					flex-direction: column;
					color: #1C1C1C;
					font-size: 34rpx;
					font-weight: 600;
					letter-spacing: 2rpx;
				}
				
				.success{
					font-size: 100rpx;
					color: #07BEB4;
				}
			}
			.tips{
				text-align: center;
				margin-top: 14rpx;
				font-weight: 600;
				font-size: 30rpx;
				color: #1C1C1C;
				letter-spacing: 2rpx;
			}
		}
		.close{
			color: #FFFFFF;
			font-size: 54rpx;
			margin-top: 50rpx;
		}
	}
	
	
	/* 猜你喜欢 */
	.guessLike {
		text-align: center;
		font-weight: 700;
		font-size: 12pt;
		color: #000000;
		padding: 50rpx 0 20rpx;
	}
</style>
