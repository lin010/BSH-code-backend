<template>
	<view>
		<scroll-view class="scroll-view" scroll-x="true">
			<view :class="['scroll-item','scroll-item'+index,switchIndex == index?'scroll-item-active':'']" :id="item.id" v-for="(item,index) in cateList" @tap="switchs($event,index)" :key='item.id'>{{item.name}}</view>
			<view class="scroll-line" :style="{left:x+'px'}"></view>
		</scroll-view>
	</view>
</template>

<script>
	export default{
		data(){
			return{
				x:0,
				switchIndex:0,
			}
		},
		props:{
			cateList:{
				type:Array,
				default:()=>[]
			}
		},
		mounted() {
			setTimeout(()=>{
				var width,lineWidth;
				//获取元素宽度
				width = this.getWidth(".scroll-item0");
				//获取下划线宽度
				lineWidth = this.getWidth(".scroll-line");
				this.x = (width/2) - (lineWidth/2);
			},1000)
		},
		methods:{
			switchs(e,index){
				// console.log(e,'eeeee');
				this.switchIndex = index;
				var width,lineWidth;
				//获取元素宽度
				width = this.getWidth(".scroll-item"+index);
				//获取下划线宽度
				lineWidth = this.getWidth(".scroll-line");
				this.x = e.target.offsetLeft + (width/2) - (lineWidth/2);
				this.$emit('click',e)
			},
			getWidth(name){
				var width;
				let info = uni.createSelectorQuery().select(name);
				info.boundingClientRect(function(data) {
					width = data.width;
				}).exec()
				return width;
			}
		}
	}
</script>

<style scoped>
	.scroll-view{
		white-space: nowrap;
	}
	.scroll-item{
		display: inline-block;
		padding: 0 20rpx 16rpx;
	}
	.scroll-item-active{
		color: #BC0100;
		font-weight: 600;
	}
	.scroll-line{
		position: absolute;
		width: 50rpx;
		border-bottom: 4rpx solid #BC0100;
		bottom: 0;
		left: 0;
		transition: all 0.3s;
	}
	
	/* 隐藏滚动条 */
	scroll-view ::-webkit-scrollbar {
		width: 0;
		height: 0;
		background-color: transparent;
	}
</style>
