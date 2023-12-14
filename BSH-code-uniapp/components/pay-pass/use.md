### 组件开篇 （mobile-calendar-simple）
<h4>支持微信小程序、支付宝小程序、H5，其他未测试，理论支持</h4>
### 使用方法
<h5><payPass ref="verify" @finish="getCode"></payPass></h5>
```javascript
  <script>
  	import payPass from '../../components/pay-pass/pay-pass.vue'
  	export default {
  		components:{
  			payPass
  		},
  		data() {
  			return {
  				
  			}
  		},
  		onLoad() {
  			
  		},
  		methods: {
  			getCode:function(e){
  				console.log(e);//输入内容
  			},
  			yzbtn:function(){
  				this.$refs.verify.show();
  			},
  		}
  	}
  </script>
```


