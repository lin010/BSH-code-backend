<template>
	<view class="sellingHot_container">
		<vouchers :list='goods_ist'></vouchers>
		<backTop :src="backTop.src"  :scrollTop="backTop.scrollTop"></backTop>
		<!--加载loadding-->
		<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="!pullUpOn" bgcolor="#FFFFFF"></main-nomore>
		<!--加载loadding-->
	</view>
</template>

<script>
	import vouchers from '@/components/vouchers.vue';
	import backTop from '@/components/back-top/back-top.vue';
	export default{
		components: {
			vouchers,
			backTop
		},
		data(){
			return{
				goods_ist:[],
				page_count:'',
				page:1,
				backTop: {
					src: '../../static/back-top/top.png',
					scrollTop: 0
				},
				pullUpOn:true,
				loadding:false,
			}
		},
		onLoad() {
			this.getList()
		},
		methods:{
			getList() { //首页商品
				this.$http.request({
					url: this.$api.default.getvoucherList,
					method: 'GET',
					data:{
						page:this.page
					},
				}).then((res) => { 
					if(res.code==0){
						if(res.data.list.length==0)return false
						let list= res.data.list;
						var arr=this.goods_ist.concat(list)
						this.goods_ist =arr
						this.page_count = res.data.page_count;
						this.pullUpOn=true
					}else{
						this.$http.toast(res.msg);
					}
				})
			},
		},
		onPageScroll(e) {
			this.backTop.scrollTop = e.scrollTop;
		},
		onReachBottom() {
			this.pullUpOn=true
			this.loadding=true
			if(this.page==this.page_count){
				this.pullUpOn=false
				this.loadding=false
				return false;
			} 		
			this.page=this.page+1
			this.getList();
		},
	}
</script>

<style lang="less" scoped>
	
</style>
