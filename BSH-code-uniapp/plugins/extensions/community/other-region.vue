<template>
	<view class="app">
		<com-status-bar />
		<view class="map-container">
			<map
				id="myMap"
				style="width: 100%; height: 950rpx;"
				:scale="scale"
				:latitude="latitude"
				:longitude="longitude"
				:show-compass="true"
				:markers="markers"
				:controls="controls"
				@updated="updated"
				@controltap="controltap"
				@callouttap="callouttap"
				@regionchange="regionchange"
			></map>
			<!--  -->
		</view>
		<view class="search">
			<view class="input">
				<view class="input-container flex-x-center flex-y-center">
					<com-icons class="input-uni-icon" type="search" size="20" color="#666666" style="margin-right: 10rpx;"/>
					<view>搜索地点</view>
				</view>
			</view>
			<view class="items">
				<view class="item" v-for="(item,i) in storeData" :key="i" @click="checkedStore(item)">
					<view class="store-name">{{item.name}}</view>
					<view class="store-address flex-y-center">
						<view>{{item.distance}}m</view>
						<view class="interval"></view>
						<view>{{item.address}}</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
export default {
	data() {
		return {
			latitude: 24.448329402,
			longitude: 118.084790343,
			scale: '',
			storeData: [
				{
					id: 1,
					name: '汇.创意产业园(广州市白云区)',
					address: '白云区金岭南街19号',
					distance: 50,
					tel: '12345678900',
					latitude: 24.448329402,
					longitude: 118.084790343
				},
				{
					id: 2,
					name: 'YOU+国际青年社区(广州百信社区)',
					address: '白云区机场路1600号汇创意产业园C1栋',
					distance: 98,
					tel: '12345678900',
					latitude: 24.4558,
					longitude: 118.12266,
				}
			],
			markers: [
				//门店在地图上的标记 以下字段必填
				{
					id: 1,
					latitude: 24.4483294023427,
					longitude: 118.08479034393311,
					iconPath: require('../../images/extensions/community/map-mark.png')
				},
				{
					id: 2,
					latitude: 24.4558,
					longitude: 118.12266,
					iconPath: require('../../images/extensions/community/map-mark.png')
				}
			],
			controls: [
				{
					id: 1,
					position: {
						left: 320,
						top: 390,
						width: 45,
						height: 45
					},
					iconPath: require('../../images/extensions/community/map-location.png'),
					clickable: true
				}
			],
			scale: 15, //地图缩放程度
			distanceL: ''
		};
	},
	onLoad() {},
	methods: {
		checkedStore(data){
			this.latitude = data.latitude;
			this.longitude = data.longitude;
		},
		controltap(e) {
			let self = this;
			// #ifdef H5
			this.$wechatSdk.location(res => {
				// console.log(res, '111');
				self.latitude = res.latitude;
				self.longitude = res.longitude;
				return;
			});
			// #endif
			this.getLocation(function(res){
				console.log(res);
			});
		},
		updated() {
			console.log('111');
			let self = this;
			wx.getLocation({
				type: 'gcj02', //返回可以用于wx.openLocation的经纬度
				success: function(res) {
					self.latitude = res.latitude;
					self.longitude = res.longitude;
					// self.nearDistance(self.markers, self.latitude, self.longitude);
				},
				fail: function(res) {
					console.log(res, 'updated');
				}
			});
		},
		// 改变视野时改变经纬度
		regionchange(e) {
			let self = this;
			// 使用 wx.createMapContext 获取 map 上下文 this必须要写 不然没办法得到回调！！！！。
			var mapCtx = wx.createMapContext('myMap', this);
			mapCtx.getCenterLocation({
				success: function(res) {
					let centerLatitude = res.latitude;
					let centerLongitude = res.longitude;
					// self.nearDistance(self.markers, centerLatitude, centerLongitude);
				},
				fail: function(res) {
					console.log(res, 'regionchange');
				}
			}); //获取当前地图的中心经纬度
		},
		// 点击气泡
		callouttap(e) {
			console.log('点击了气泡');
		},
		// 两点间距离
		distance(la1, lo1, la2, lo2) {
			var La1 = (la1 * Math.PI) / 180.0;
			var La2 = (la2 * Math.PI) / 180.0;
			var La3 = La1 - La2;
			var Lb3 = (lo1 * Math.PI) / 180.0 - (lo2 * Math.PI) / 180.0;
			var s = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(La3 / 2), 2) + Math.cos(La1) * Math.cos(La2) * Math.pow(Math.sin(Lb3 / 2), 2)));
			s = s * 6378.137; //地球半径
			s = Math.round(s * 10000) / 10000;
			return s;
		},
		// 计算最近的距离
		nearDistance(array, centerLatitude, centerLongitude) {
			let temp = [];
			for (let i = 0, l = array.length; i < l; i++) {
				const element = array[i];
				let d = this.distance(element.latitude, element.longitude, centerLatitude, centerLongitude);
				temp.push(d);
			}
			this.distanceL = Math.min.apply(null, temp);
		}
	}
};
</script>

<style lang="scss" scoped>
.app {
	height: 100%;
}
.search{
	background-color: #FFFFFF;
	border-top-left-radius: 30rpx;
	border-top-right-radius: 30rpx;
	
	.input{
		padding: 30rpx 12rpx;
		.input-container{
			background-color: #EFEDEE;
			height: 64rpx;
			line-height: 64rpx;
			width: 100%;
			color: #666666;
		}
	}
	.items{
		padding-left: 38rpx;
		
		.item{
			padding: 24rpx 0;
			border-bottom: 1rpx solid #EDEAEB;
			height: 144rpx;
			line-height: 48rpx;
			
			&:last-child{
				border-bottom: 0;
			}
			
			.store-name{
				color: #000000;
			}
			.store-address{
				color: #888568;
				font-size: 24rpx;
			}
		}
	}
	
	.interval{
		margin: 0 10rpx;
		width: 3rpx;
		height: 24rpx;
		background-color: #888568;
	}
}
</style>
