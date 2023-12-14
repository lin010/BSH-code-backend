<template>
	<view class="footer">
		<view class="footer_block" v-for="(item,index) in list" @tap="toUrl(item.path,index)" :style="'color:'+(index==current?background:'')">
			<view :class="item.icon" class="icon"></view>
			<view class="footer_block_txt">{{item.name}}</view>
		</view>
	</view>
</template>

<script>
	
	export default {
		props:{
			current:{
				type: Number,
				default: 0
			},
			background:{
				type:String
			}
		},
		data() {
			return {
				list:[
					{"name":"首页","path":"/pages/index/index","icon":"iconfont iconshouye"},
					// {"name":"分类","path":"/pages/shop/cat/cat","icon":"iconfont iconleimupinleifenleileibie"},					
					{"name":"分类","path":"/pages/cat/cat","icon":"iconfont iconleimupinleifenleileibie"},	
					{"name":"购物车","path":"/pages/cart/index","icon":"iconfont icongouwuche"},
					{"name":"我的","path":"/pages/user/index","icon":"iconfont iconwode"},
				]
			};
		},
		methods:{
			toUrl(url,index){
				uni.reLaunch({
					url:url
				})
			}
		},
		mounted(){
			
		},
		destroyed(){
			
		}
	}
</script>

<style>
/* @import url("@/static/font-icon/iconfont1.css"); */
@import url("@/plugins/font-icon/iconfont1.css");
.footer{position: fixed;bottom: 0px;background-color: #fff;display: flex;left: 0px;right: 0px;width: 100%;z-index: 99;padding: 4px 0px 8px 0px;
border-radius: 21px 21px 0px 0px;
border-top: 1px #eee solid;
}
.footer_block{flex: 1;text-align: center;font-size: 15px;color: #999;}
.footer_block_txt{
	font-size: 11px;
	font-weight: bold;
}
.icon{
	font-size: 25px;
	height: 29px;
}

</style>
