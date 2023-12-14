<template>
	<view>
		<view class="content_body">
					<!--
					 <image :src="this.$api.test_url+'/images/shop/resiger_title.png'" class="content_body_title"></image>
					 -->
					 <view class="content_body_block">
						 <view class="content_body_block_l">
							 <view class="content_body_block_l_t1">
								 <view class="content_body_block_l_t" :style="{background:background}">实例</view>
							 </view>
							 <view class="content_body_block_l_b">花柚鲜花店</view>
						 </view>
						 <view class="content_body_block_r">
							  <view class="content_body_block_r_t">1、店铺名称</view>
							  <view class="content_body_block_r_b">请输入您线下店铺的名称</view>
						 </view>
					 </view>
					 <view class="content_body_block">
						 <view class="content_body_block_l">
							 <view class="content_body_block_l_t1">
								 <view class="content_body_block_l_t" :style="{background:background}">实例</view>
							 </view>
							 <view class="content_body_block_l_b">张富贵</view>
						 </view>
						 <view class="content_body_block_r">
							  <view class="content_body_block_r_t">2、申请人姓名</view>
							  <view class="content_body_block_r_b">请填写你的真实姓名</view>
						 </view>
					 </view>
					 <view class="content_body_block">
						 <view class="content_body_block_l">
							 <view class="content_body_block_l_t1">
								 <view class="content_body_block_l_t" :style="{background:background}">实例</view>
							 </view>
							 <view class="content_body_block_l_b">130608XXXXX888</view>
						 </view>
						 <view class="content_body_block_r">
							  <view class="content_body_block_r_t">3、申请人手机号</view>
							  <view class="content_body_block_r_b">手机号码共11位数字。其他为无效输入.</view>
						 </view>
					 </view>
					 <view class="content_body_block">
						 <view class="content_body_block_l">
						 	<view class="content_body_block_l_t1">
						 		<view class="content_body_block_l_t" :style="{background:background}">实例</view>
						 	</view>
						 		<view class="content_body_block_l_b">美容美发,餐饮美食,酒店名宿,服饰鞋帽</view>
						 </view>
							 <view class="content_body_block_r">
								  <view class="content_body_block_r_t">4、店铺消费类型</view>
								  <view class="content_body_block_r_b">选择你的店铺的消费类型，以方便消费者快速搜索到你的店铺</view>
							 </view>
					 </view>
					 <view class="content_body_block">
					 	<view class="content_body_block_l">
					 			<view class="content_body_block_l_t1">
					 				<view class="content_body_block_l_t" :style="{background:background}">更多</view>
					 			</view>
					 	</view>
					 	<view class="content_body_block_r">
					 			<view class="content_body_block_r_t">5、更多资料...</view>
					 			<view class="content_body_block_r_b"></view>
					 	</view>
					 </view>
		</view>
		<view class="content_footer">
			 <button class="sumbit" :style="{background:background}" @tap="sumbit">我准备好了,开始填写</button>
		</view>
	</view>
</template>

<script>
	export default {
		// props: {
		// 	background: { //控制是否显示
		// 		type: String,
		// 		default: true
		// 	},
		// },
		data() {
			return {
              background:'rgb(255, 113, 4)',
			}
		},
		methods:{
			sumbit:function(){
				this.$emit("returnStatus",1)
			}
		}
	}
</script>

<style>
.content_body{
	width: 98%;
	margin: 5px auto;
	background: #FFFFFF;
	box-shadow: 0px 1px 1px 1px rgba(2, 58, 63, 0.16);
	border-radius: 3px;
	padding: 12px 5px 5px;
}
.content_body_title{
	width: 180px;
	height: 29px;
	margin-bottom: 20px;

}
.content_body_block{
	display: flex;
	margin-bottom: 20px;
	padding: 0px 10px;
}
.content_body_block_l{
	width: 40%;
}
.content_body_block_l_t{
	padding: 0px 2px;
    font-size: 12px;
	border-top-left-radius: 4px;
	border-bottom-right-radius: 4px;
	font-weight: bold;
	color: #fff;
	
}
.content_body_block_l_b{
	font-size: 10px;
	font-weight: 400;
	color: #353535;

}
.content_body_block_r{
	width: 60%;
	padding-left: 15px;
}
.content_body_block_r_t{
	font-size: 16px;
	font-weight: bold;
	color: #000000;
	opacity: 0.9;

}
.content_body_block_l_t1{display: flex;}
.content_body_block_r_b{
	font-size: 10px;
	font-weight: 400;
	color: #6B6B6B;

}
.content_body_block_l_b image{
	width: 115px;
	height: 73px;
}
.content_footer{
	width: 98%;
	margin: 5px auto;
	background: #FFFFFF;
	box-shadow: 0px 1px 1px 1px rgba(2, 58, 63, 0.16);
	border-radius: 3px;
	padding: 20px 5px 50px;
}
.sumbit{
	width: 90%;
	margin: 0px auto;
	border: 0px solid rgba(4, 0, 0, 0.39);
	box-shadow: 0px 1px 2px 0px rgba(4, 76, 102, 0.71);
	border-radius: 13px;
    line-height: 32px;
	color: #fff;
	font-size: 14px;
	font-weight: bold;
}
</style>
