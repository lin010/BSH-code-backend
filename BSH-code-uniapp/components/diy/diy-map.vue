<template>
	<view class="diy-map">
		<view :style="'padding-top: '+data.marginTop+'px; background-color: '+data.marginTopColor">
			<view :style="cContainerStyle" class="map-container">
				<map :style="cMapStyle" scale="15" :longitude="longitude" :latitude="latitude" :markers="[{latitude:latitude,longitude:longitude}]"></map>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			value: Object
		},
		data() {
			return {
				data: {},
				longitude:0,
				latitude:0,
			}
		},
		created() {
			this.data = JSON.parse(JSON.stringify(this.value));
			this.longitude=this.data.location.split(',')[1];
			this.latitude=this.data.location.split(',')[0];
		},
		methods: {

		},
		computed: {
			cContainerStyle() {
				let style = `padding: ${this.data.paddingY}rpx ${this.data.paddingX}rpx;` +
					`background-color: ${this.data.backgroundColor};` +
					`background-image: url(${this.data.backgroundPicUrl});`;
				return style;
			},
			cMapStyle() {
				// let style = `height: ${this.data.height}px;` +
				// 	`background-image: url(${this.mapDemoPic});`;
				let style = `width: 100%;height: ${this.data.height}rpx;`;
				return style;
			},
		}
	}
</script>

<style lang="scss" scoped>

</style>
