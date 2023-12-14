<template>
	<view class="agreement-app">
		<view class="html" >
			<rich-text :nodes="agreementMessage"></rich-text>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				agreementMessage:''
			};
		},
		onLoad() {
			let that=this
			that.$http.request({
				 url:that.$api.moreShop.getTExt,
				 data:{},
				 method:'post',
				 }).
				then(function(res){
					if(res.code==0){
						console.log(res.data.agreement)
						var str = res.data.agreement.replace(/[\n]/, "<br/>");
						str = str.replace("[\s]", "&nbsp;");
						that.agreementMessage=str;
					}
			})
		}
	}
</script>

<style lang="less">
	.agreement-app{width: 100%;overflow: hidden;}
	.html{width: 100%;overflow: hidden;}
</style>
