<template>
	<div class="wrapper" :style="'top:'+statusBarHeight+'px'">
		<div class="header">
			<input class="input" @input="onInput" placeholder="中文/拼音/首字母" v-model="searchValue" style="margin: 0 auto;">
		</div>
		<scroll-view class="calendar-list" scroll-y="true" :scroll-into-view="scrollIntoId">
			<view v-if="disdingwei" id="hot">
				<!-- 定位模块 -->
				<view class="dingwei">
					<view class="dingwei_Tips">
						当前定位
					</view>
					<view class="dingwei_city">
						<view class="dingwei_city_one">
							{{position}}
						</view>
						<view class="dingweis_div" @click="getWarpweft">
							<image class="dingweis" :src="img_url+'dao_location.png'" mode=""></image>
							<text>{{po_tips}}</text>
						</view>
					</view>
				</view>

				<!-- 最近模块 -->
				<view class="dingwei" v-if="Visit.length>=0">
					<view class="dingwei_Tips">
						最近访问
					</view>
					<view class="dingwei_city dingwei_city_zuijin">
						<view class="dingwei_city_one toright" v-for="(item,index) in Visit" v-if="index<1" @click="back_city(item)" :key='index' style="font-size: 26rpx;">
							{{item.cityName}}
						</view> 
					</view>
				</view>

			</view>


			<!-- 城市列表 -->
			<view v-if="searchValue == ''" v-for="(item, index) in list" :id="getId(index)" :key="index">
				<view class="letter-header">{{ getId(index) }}</view>
				<view class="city-div" v-for="(city, i) in item" :key="i" @click="back_city(city)">
					<text class="city">{{ city.cityName }}</text>
				</view>
			</view>
			<!-- 搜索结果 -->
			<view class="city-div" v-for="(item, index) in searchList" @click="back_city(item)" :key='index'>
				<text class="city" style="font-size: 30rpx;">{{ item.cityName }}</text>
			</view>
		</scroll-view>

		<!-- 右侧字母 -->
		<view class="letters" v-if="searchValue == ''">
			<view class="letters-item" @click="scrollTo('hot')">最近</view>
			<view class="letters-item" v-for="item in letter" :key="item" @click="scrollTo(item)">{{ item }}</view>
		</view>

		<!-- 选中之后字母 -->
		<view class="mask" v-if="showMask">
			<view class="mask-r">{{selectLetter}}</view>
		</view>
		<view class="bottomclose" @click="close">
			关闭
		</view>
		<unipopup ref="popupcity" type="bottom">
			<view class="searchList-app-sort">
				<scroll-view scroll-y scroll-with-animation style="white-space:nowrap;height: 600rpx;">
					<view v-for="(item,index) in countryList" :key="index" class="tab-bar-item" @click="selectCountry(item)">
						<text>{{item.name}}</text>
					</view>
				</scroll-view>
			</view>
		</unipopup>
	</div>
</template>

<script>
	import unipopup from '@/components/uni-popup/uni-popup';
	export default {
		components: {unipopup},
		props: {},
		data() {
			return {
				img_url: this.$api.img_url,
				statusBarHeight: this.statusBarHeight,
				ImgUrl: this.ImgUrl,
				letter: [],
				selectLetter: '',
				searchValue: '',
				scrollIntoId: '',
				list: [],
				tId: null,
				searchList: [],
				showMask: false,
				disdingwei: true,
				Visit: [], //最近访问
				position: uni.getStorageSync("homeCity")?uni.getStorageSync("homeCity"):'附近的', 
				seconds: 3,
				po_tips: '重新定位',
				popupcity:false,
				countryList:[],
				parentname:'',
			}
		},

		created() {
			//获取存储的最近访问
			var that = this
			that.getcity()
			uni.getStorage({
				key: 'home_Visit_key',
				success: function(res) {
					that.Visit = res.data
				}
			});
			// //获取定位 经度纬度
			// that.getWarpweft()
		},
		methods: {
			getcity(){
				this.$http
					.request({
						url: this.$api.hotel.getcitylist,
						method: 'POST',
						data:'',
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							this.citys=res.data
							var mu = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'w', 'x', 'y',
								'z'
							];
							var tmp = [];
							for (var i = 0; i < mu.length; i++) {
								var item = mu[i];
								for (var j = 0; j < this.citys.length; j++) {
									var py = this.citys[j].py.toLowerCase();
									if (py.substring(0, 1) == item) {
										if (tmp.indexOf(item) == -1) {
											this.list[i] = [this.citys[j]];
											tmp.push(item);
											this.letter.push(item.toUpperCase());
										} else {
											this.list[i].push(this.citys[j]);
										}
									}
								}
							}
						}else{
							this.$http.toast(res.msg);
						}
				});	
			},
			
			getcounty(id){
				this.$http
					.request({
						url: this.$api.moreShop.getlocationcity,
						method: 'get',
						data:{
							id:id
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){
							this.countryList=res.data.list
							this.countryList.unshift({
								id:'',
								level:'',
								name:'全部',
								parent_id:res.data.list[0].parent_id,
							})
						}else{
							this.$http.toast(res.msg);
						}
				});	
			},
			getId(index) {
				return this.letter[index];
			},
			scrollTo(letter) {
				this.showMask = true
				this.selectLetter = letter == 'hot' ? '最' : letter
				setTimeout(() => {
					this.showMask = false
				}, 300);
				this.scrollIntoId = letter;
			},


			query(source, text) {
				let res = [];
				var self = this;
				res = source.filter(item => {
					const arr = [];
					let isHave = false;
					Object.keys(item).forEach(prop => {
						const itemStr = item[prop];
						self.isString(itemStr) &&
							itemStr.split(',').forEach(val => {
								arr.push(val);
							});
					});
					arr.some(val => {
						isHave = new RegExp('^' + text).test(val);
						return isHave;
					});
					return isHave;
				});
				// console.log(JSON.stringify(res));
				return res;
			},

			isString(obj) {
				return typeof obj === 'string';
			},

			onInput(e) {
				const value = e.target.value;
				// console.log(value);
				if (value !== '' && this.citys && this.citys.length > 0) {
					const queryData = this.query(this.citys, String(value).trim());
					this.searchList = queryData;
					this.disdingwei = false
				} else {
					this.searchList = [];
					this.disdingwei = true
				}
			},

			back_city(item) {
				if (item) {
					this.parentname=item.cityName
					this.getcounty(item.id)
					this.$refs.popupcity.open()
					this.Visit.unshift(item)
					this.searchValue = "";
					this.disdingwei = true
					var arr = this.Visit
					//数组去重
					function distinct(arr) {
						let newArr = []
						for (let i = 0; i < arr.length; i++) {
							if (newArr.indexOf(arr[i]) < 0) {
								newArr.push(arr[i])
							}
						}
						return newArr
					}
					this.Visit = distinct(arr)
				
					uni.setStorage({
						key: 'home_Visit_key',
						data: this.Visit
					});
				} else {
					this.$emit('back_city', 'no');
				}

			},
			close(){
				this.$emit('back_city', 'no');
			},
			selectCountry(item){ //选择县区
				if(item.name=='全部'){
					item.name=this.parentname
					this.$emit('back_city', item);
				}else{
					this.$emit('back_city', item);
				}
			},
			getWarpweft() {
				var that = this
				uni.removeStorageSync('homeCity')
				this.$emit('back_city', "no");
			}
		}
	};
</script>

<style scoped>
	.wrapper {
		position: fixed;
		z-index: 999999;
		background: #ffffff;
		height: 100%;
		width: 100%;
		top: 0px;
		left: 0px;
	}

	.mask {
		position: absolute;
		bottom: 0upx;
		top: 83upx;
		left: 0upx;
		right: 0upx;
		width: 750upx;
		display: flex;
		justify-content: center;
		align-items: center;
		background: rgba(0, 0, 0, 0);
	}

	.mask-r {
		height: 120upx;
		width: 120upx;
		border-radius: 60upx;
		display: flex;
		background: rgba(0, 0, 0, 0.5);
		justify-content: center;
		align-items: center;
		font-size: 40upx;
		color: #FFFFFF
	}

	.content {
		height: 100%;
		width: 100%;
		background-color: #ffffff;
	}

	.header {
		height: 85upx;
		display: flex;
		justify-content: flex-start;
		align-items: center;
	}


	.back_div {
		width: 65upx;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.back_img {
		width: 35upx;
		height: 35upx;
	}

	.input {
		font-size: 28upx;
		width: 620upx;
		height: 55upx;
		border-radius: 40upx;
		background-color: #F5F5F5;
		padding-left: 20upx;
		padding-right: 20upx;
		box-sizing: border-box;
	}

	.title {
		font-size: 30upx;
		color: white;
	}

	.show {
		left: 0;
		width: 100%;
		transition: left 0.3s ease;
	}

	.hide {
		left: 100%;
		width: 100%;
		transition: left 0.3s ease;
	}


	.title {
		font-size: 30upx;
		color: white;
	}

	.calendar-list {
		position: absolute;
		top: 83upx;
		bottom: 0upx;
		width: 100%;
		background-color: #FFFFFF;
	}

	.letters {
		position: absolute;
		right: 30upx;
		bottom: 0px;
		width: 50upx;
		top: 180upx;
		color: #2f9bfe;
		text-align: center;
		font-size: 24upx;
		margin-bottom: 100rpx;
	}

	.letters-item {
		margin-bottom: 5upx;
	}

	.letter-header {
		height: 45upx;
		font-size: 22upx;
		color: #333333;
		padding-left: 24upx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		background-color: #ebedef;

	}

	.city-div {
		width: 660upx;
		height: 85upx;
		margin-left: 24upx;
		border-bottom-width: 0.5upx;
		border-bottom-color: #ebedef;
		border-bottom-style: solid;
		display: flex;
		align-items: center;
		margin-right: 35upx;
	}

	.city {
		font-size: 28upx;
		color: #000000;
		padding-left: 30upx;
	}

	.dingwei {
		width: 100%;
		padding-top: 25upx;
		box-sizing: border-box;
		margin-bottom: 26upx;
	}

	.dingwei_Tips {
		margin-left: 24upx;
		margin-bottom: 24upx;
		font-size: 24upx;
		color: #A5A5A5;
	}

	.dingwei_city {
		width: 100%;
		height: 60upx;
		padding-left: 55upx;
		padding-right: 70upx;
		box-sizing: border-box;
		display: flex;
		justify-content: space-between;
	}

	.dingwei_city_one {
		width: 260upx;
		height: 60upx;
		background-color: #F5F5F5;
		border-radius: 10upx;
		font-size: 32upx;
		color: #333333;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.dingweis_div {
		display: flex;
		align-content: flex-end;
		align-items: center;
		font-size: 24upx;
		color: #FD5745;
		margin-right: 30rpx;
	}

	.dingweis {
		width: 32upx;
		height: 32upx;
	}

	.dingwei_city_zuijin {
		display: flex;
		justify-content: flex-start;
	}

	.toright {
		margin-right: 25upx;
	}
	.searchList-app-sort {
		width: 100%;
		height: 600rpx;
		background: #fff;
		border-radius: 30rpx 30rpx 0 0;
	}
	.tab-bar-item {
		width: 100%;
		height: 80rpx;
		box-sizing: border-box;
		font-size: 30rpx;
		color: #000;
		text-align: center;
		line-height: 80rpx;
		border-bottom: 1rpx solid rgb(235,237,239);
	}
	.bottomclose{width: 100%;height: 100rpx;position: fixed;bottom: 0;left: 0;text-align: center;line-height: 100rpx;
	font-size: 34rpx;font-weight: bold;color: #fff;background: rgb(255, 113, 4);}
</style>
