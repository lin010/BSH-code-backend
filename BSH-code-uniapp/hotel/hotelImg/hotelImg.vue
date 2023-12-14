<template>
	<view class="hotelLimg-container">
		<view class="header">
			<text>全部{{total_count}}张</text>
		</view>
		<view class="hotelLimg_list">
			<image :src="item.pic_url" mode="aspectFill" v-for="(item,index) in imgList" :key='index'></image>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				id:'',
				imgList:[],
				page_count:'',
				page:1,
				total_count:''
			};
		},
		onLoad(options) {
			if(options&&options.hotel_id){
				this.id=options.hotel_id
				this.getImg(options.hotel_id)
			}
		},
		methods:{
			getImg(hotel_id){
				this.$http
					.request({
						url: this.$api.hotel.getimgList,
						method: 'POST',
						data:{
							hotel_id:hotel_id,
							page:this.page
						},
						showLoading: true
					})
					.then(res => {
						if(res.code==0){							
							if(res.data.list.length==0)return false
							let list= res.data.list;
							var arr=this.imgList.concat(list)
							this.imgList =arr
							this.page_count = res.data.pagination.page_count;
							this.total_count=res.data.pagination.total_count;
						}else{
							this.$http.toast(res.msg);
						}
				});
			}
		},
		onReachBottom() {
			if(this.page==this.page_count){
				return false;
			} 		
			this.page=this.page+1
			this.getImg(this.id);
		},
	}
</script>

<style lang="less">
	.hotelLimg-container{width: 100%;overflow: hidden;}
	.header{width: 100%;overflow: hidden;padding: 0 30rpx;box-sizing: border-box;margin: 30rpx 0;}
	.header text{display: block;width: 200rpx;height: 80rpx;background: rgb(245,245,249);line-height: 80rpx;text-align: center;font-size: 30rpx;color: #ff7104;border-radius: 15rpx;}
	.hotelLimg_list{width: 95%;overflow: hidden;margin:0 auto 100rpx;display: flex;justify-content: space-evenly;flex-wrap: wrap;}
	.hotelLimg_list image{width: 330rpx;height: 330rpx;display: block;margin-top: 30rpx;}
</style>
