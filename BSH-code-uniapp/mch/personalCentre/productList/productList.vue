<template>
	<view class="productList-app">
		<view class="mian">
			<shopProduct v-for='(item,index) in list' :item='item' :key='index'></shopProduct>
		</view>
		<!--加载loadding-->
		<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="!pullUpOn" bgcolor="#FFFFFF"></main-nomore>
		<!--加载loadding-->
	</view>
</template>

<script>
	import shopProduct from '../../../components/shopProduct.vue'
	export default {
		components:{
			shopProduct
		},
		data() {
			return {
				list:[],
				form:{
					store_id:'',
					cat_id:'',
					keyword:'',
					label:'',
					page:1,
					limit:'',
				},
				page_count:'',
				pullUpOn:true,
				loadding:false,
			};
		},
		onLoad() {
			this.getGoodsList()
		},
		methods:{
			getGoodsList(){ //获取商户商品
				let routes = getCurrentPages(); // 获取当前打开过的页面路由数组			
				let curRoute = routes[routes.length - 1].route //获取当前页面路由				
				let curParam = routes[routes.length - 1].options; //获取路由参数
				this.form.store_id=curParam.mch_id
				this.$http
					.request({
						url: this.$api.moreShop.getmchgods,
						method: 'POST',
						data:this.form
					})
					.then(res => {
						if(res.code==0){
							if(res.data.list.length==0){
								this.pullUpOn=false
								return false
							}
							let list= res.data.list;
							var arr=this.list.concat(list)
							this.list =arr
							this.page_count = res.data.pagination.page_count;
							this.pullUpOn=true
						}else{
							this.$http.toast(res.msg);
						}
					});
			}
		},
		onReachBottom() {
			this.pullUpOn=true
			this.loadding=true
			if(this.form.page==this.page_count){
				this.pullUpOn=false
				this.loadding=false
				return false;
			} 		
			this.form.page=this.form.page+1
			this.getGoodsList();
		},
	}
</script>

<style lang="less" scoped>
	.productList-app{width: 100%;overflow: hidden;
	padding: 0 30rpx;box-sizing: border-box;}
	.mian{width: 100%;overflow: hidden;display: flex;justify-content: space-between;flex-wrap: wrap;}
	.none{width: 100%;}
</style>
