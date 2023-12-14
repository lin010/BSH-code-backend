<template>
	<view class="chat-root">
		<com-nav-bar @clickLeft="back" left-icon="back" :fixed="true" title="聊天" :status-bar="true" background-color="#ffffff" :border="false" color="#000000"></com-nav-bar>
		
		<!-- <view style="height: 30rpx;width: 100%;"></view> -->
		<scroll-view scroll-y="true" class="scroll-box" :scroll-with-animation="true" :scroll-top="scrollTop" :scroll-into-view="scrollToView">
			<block v-if="msg_list.length > 0">
				<view class="row" v-for="(item,index) in msg_list" :key='index' :id="'msg'+item.msg.id">
					<!-- 我的消息 -->
					<block class="my" v-if="item.type == user_id">
						<view class="my-msg flex" v-if="item.msg.type == 'text'">
							<view class="my-content">
								<rich-text :nodes="item.msg.content.text"></rich-text>
							</view>
							<image class="my-avatar" :src="item.msg.userinfo.avatar_url" mode=""></image>
						</view>
						<view class="my-msg flex" v-if="item.msg.type == 'img'">
							<image class="my-msg-img" :src="img_url+'ceshi/ceshi.png'" mode=""></image>
							<image class="my-avatar" :src="img_url+'ceshi/ceshi.png'" mode=""></image>
						</view>
					</block>
					
					<!-- 别人的消息 -->
					<block class="other" v-if="item.type == other_info.id">
						<view class="my-msg flex other-msg" v-if="item.msg.type == 'text'">
							<image class="my-avatar" :src="item.msg.userinfo.avatar_url" mode=""></image>
							<view class="my-content">
								<rich-text :nodes="item.msg.content.text"></rich-text>
							</view>
						</view>
						<view class="my-msg flex other-msg"v-if="item.msg.type == 'img'">
							<image class="my-avatar" :src="img_url+'ceshi/ceshi2.jpg'" mode=""></image>
							<image class="my-msg-img" :src="img_url+'ceshi/ceshi.png'" mode=""></image>
						</view>
					</block>
				</view>
			</block>
		</scroll-view>
		

		<!-- 底部输入框 -->
		<view class="input-box flex flex-y-center flex-x-between">
			<view class="iconfont icon-tianjia inp-icon"></view>
			<view class="inp flex flex-y-center">
				<!-- <input type="text" value="" style="padding: 0 10rpx;font-size: 28rpx;"/> -->
				<textarea auto-height="true" v-model="textMsg" class="inp-content" />
				</view>
			<view class="send-btn" @tap="send" :style="{background:textColor}">
				发送
			</view>
		</view>
	</view>
</template>

<script>
	// import JMessage from '@/common/jmessage-wxapplet-sdk-1.4.3.min.js';
	// const JIM = new JMessage();
	
	export default {
		data() {
			return {
				img_url: this.$api.img_url,
				textMsg:'',
				scrollTop:0,
				scrollToView:'',
				other_info:'',//聊天对方的信息
				unread_msg:[],
				msg_list:[],
				user_id: '',
				userInfo:'',
			};
		},
		onShow() {
			this.scrollTop = 9999999;
		},
		onLoad() {
			if(uni.getStorageSync('userInfo')){
				this.userInfo = JSON.parse(uni.getStorageSync('userInfo'));
				this.user_id = this.userInfo.user_id;
			}
			if(uni.getStorageSync('mall_config')){
				this.textColor = this.globalSet('textCol');
			}
			
			if(uni.getStorageSync('other_info')){
				this.other_info = JSON.parse(uni.getStorageSync('other_info'));
				if(this.other_info.avatar_url == ''){
					this.other_info.avatar_url = this.img_url+'images/business/business-default.jpg';
				}
			}else{
				this.$http.toast('找不到用户');
				setTimeout(()=>{
					this.navBack();
				},1000)
			}
			this.getMsgList();
			this.getConversation(); //离线监听
			console.log(JIM.isLogin(),'JIM.isLogin()');
			if(!JIM.isLogin()){
				this.getInitData();
			}
		},
		onUnload(){
			JIM.loginOut();
		},
		methods:{
			back(){ //返回事件，并将聊天数据发送给后端
				this.navBack();
				var arr;
				if(this.msg_list.length > 10){
					arr = this.msg_list.slice(-10);
				}
				this.$http.request({
					url:this.$api.plugin.business.message_add,
					method:'post',
					data:{
						user_id: this.userInfo.user_id,
						target_user_id: this.other_info.id.split("_")[1],
						form_data: arr ? arr : this.msg_list
					}
				}).then(res => {
					if(res.code != 0){
						this.$http.toast(res.msg);
					}
				});
				
				uni.removeStorage({ //清除存在缓存的未读信息
				    key: this.other_info.id,
				    success: function (res) {
				        console.log('success');
				    }
				});
			},
			getMsgList(){ //获取历史记录
				var that = this;
				this.$http.request({
					url:this.$api.plugin.business.message_list,
					method:'post',
					showLoading:true,
					data:{
						user_id: this.userInfo.user_id,
						target_user_id: this.other_info.id.split("_")[1],
					}
				}).then(res => {
					if(res.code == 0){
						if(res.data.list.length > 0){
							this.msg_list.push(...res.data.list);
						}
						
						if(uni.getStorageSync(this.other_info.id)){ //获取缓存的数据，看是否有新数据
							var history_arr = JSON.parse(uni.getStorageSync(this.other_info.id));
							history_arr.forEach((item,index)=>{
								item.msg.userinfo.avatar_url = that.other_info.avatar_url;
								item.msg.userinfo.username = that.other_info.nickname;
								item.msg.id = this.msg_list.length + 2;
							});
							this.msg_list.push(...history_arr);
						}
					}
				})
			},
			getInitData(){ //初始化极光
				this.$http.request({
					url:this.$api.plugin.business.message_init,
					method:'post',
					showLoading:true,
				}).then(res => {
					if(res.code == 0){
					console.log(res,'ressss');
						JIM.init({ //极光初始化
							"appkey" : res.data.app_key,
							"random_str" : res.data.random_str,
							"signature" : res.data.signature,
							"timestamp" : res.data.timestamp,
							"flag" : 0
						}).onSuccess((data)=>{
							console.log('初始化成功');
							this.jmRegister(this.user_id,this.userInfo.nickname,'my');
							this.jmRegister(this.other_info.id,this.other_info.nickname,'other');
						}).onFail((data)=>{
							this.$http.toast('初始化失败');
						});
					}
				})
			},
			jmRegister(userId,name,str){ //注册极光聊天
				console.log(userId,name,str,'userId,name,str');
				JIM.register({
					'username' : str == 'my'?'user_' + userId : userId,
					'password' : 'jx888888',
					'nickname' : name,
					'is_md5' : false,
					'extras' : {'key1':'val1','key2':'val2'},
					'address' : '深圳'
				}).onSuccess((data)=>{
					console.log('注册成功');
					if(str == 'my'){
						this.jmLogin(this.userInfo);
					}
				}).onFail((data)=>{
					if(data.code == 882002){
						console.log('用户已存在，直接跳登录');
						if(str == 'my'){
							this.jmLogin(this.userInfo);
						}
					}else{
						this.$http.toast(data.message);
					}
				});
			},
			jmLogin(){ //登录极光聊天
			console.log(this.user_id,'this.user_idjmLoginjmLoginjmLogin');
				JIM.login({
				    'username' : 'user_'+this.user_id,
				    'password' : 'jx888888',
				}).onSuccess((data)=>{
					console.log('登录成功');
					this.getMsg();
				}).onFail(function(data){
					console.log(data,'onFail');
					this.$http.toast('聊天登录失败');
				});
			},
			send(){
				if(!this.textMsg){return;}
				
				JIM.sendSingleMsg({ //发送信息
				    'target_username' : this.other_info.id,
				    'content' : this.textMsg,
					'need_receipt':false
				}).onSuccess((data)=>{
				    console.log(data,'发送成功');
					this.addMsg(this.user_id,data.ctime_ms,this.userInfo.nickname,this.userInfo.avatar,this.textMsg);
					this.textMsg = '';
				}).onFail((data)=>{
					this.$http.toast('发送失败');
					console.log(data,'onFail');
				}).onTimeout((data)=>{
				    if (data.response_timeout) {
				        // do something when response timeout
				    } else {
				        // do something when request timeout
				    }
				}).onAck((data)=>{});
			},
			getMsg(){ //接收数据
				JIM.onMsgReceive((data)=>{
					console.log(data,'接收数据');
					this.judgeId(data.messages,'online');
				});
			},
			addMsg(type,time,username,avatar_url,text){
				var length = this.msg_list.length;
				if(avatar_url == ''){
					avatar_url = this.img_url+'images/business/business-default.jpg';
				}
				let msg = {type,msg:{id:length+1,type:'text',time,userinfo:{username,avatar_url,id:this.userInfo.user_id},otherInfo:this.other_info,content:{text}}};
				this.msg_list.push(msg);
				console.log(this.msg_list,'msg_list');
				
				this.$nextTick(()=>{ //滚动到底
					this.scrollToView = 'msg'+msg.msg.id
				});
			},
			getConversation(){ //接受离线消息
				JIM.onSyncConversation((data) => {
					console.log(data,'离线消息');
					this.judgeId(data[0].msgs,'offline');
				});
			},
			judgeId(val,str){
				val.forEach((item,index) => {
					if(this.other_info.id == item.content.from_id){
						this.addMsg(item.content.from_id,item.ctime_ms,this.other_info.nickname,this.other_info.avatar_url,item.content.msg_body.text);
					}else{
						if(this.user_id != item.content.from_id){
							let msg = {type:item.content.from_id,msg:{id:0,time:item.ctime_ms,type:item.content.msg_type,userinfo:{username:'',avatar_url:''},content:{text:item.content.msg_body.text}}};
							if(uni.getStorageSync(item.content.from_id)){
								var arr2 = JSON.parse(uni.getStorageSync(item.content.from_id));
								arr2.push(msg);
								uni.setStorageSync(item.content.from_id,JSON.stringify(arr2));
							}else{
								var arrs = [];
								arrs.push(msg);
								uni.setStorageSync(item.content.from_id,JSON.stringify(arrs));
							}
						}
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.scroll-box{
		padding: 0rpx 20rpx 0;
		position: absolute;
		width: 100%;
		/* bottom: 50px; */
		/* top: calc(var(--status-bar-height) + 88rpx + 20px); */
		height:100%;
		padding-bottom: 100rpx;
		box-sizing: border-box;
		// height: calc(100% - 50px - 44px - var(--status-bar-height) - 20px);
	}
	.my-msg{
		color: #000000;
		justify-content: flex-end;
		margin-bottom: 20rpx;
		align-items: flex-start;
		
		.my-avatar{
			width: 70rpx;
			height: 70rpx;
			border-radius: 6rpx;
			flex-shrink: 0;
		}
		.my-content{
			background: #f37e8b;
			margin-right: 10rpx;
			padding: 14rpx;
			border-radius: 6rpx;
			font-size: 28rpx;
		}
		.my-msg-img{
			width: 200rpx;
			height: 300rpx;
			margin-right: 10rpx;
			border-radius: 6rpx;
		}
	}
	
	.other-msg{
		justify-content: flex-start;
		
		.my-avatar{
			margin-right: 10rpx;
		}
		.my-content{
			background: #FFFFFF;
		}
	}
	
	// 底部输入框
	.input-box{
		position: fixed;
		bottom: 0;
		background: #F7F7F7;
		width: 100%;
		border-top: 1px solid #e3e3e3;
		padding: 12rpx 20rpx;
		box-sizing: border-box;
		height: 100rpx;
		
		.inp-icon{
			font-size: 50rpx;
		}
		.inp{
			background: #FFFFFF;
			border-radius: 6rpx;
			width: 500rpx;
			padding: 10rpx 0;
		}
		.send-btn{
			height: 60rpx;
			padding: 0 20rpx;
			line-height: 60rpx;
			background: #BC0100;
			border-radius: 6rpx;
			font-size: 28rpx;
			color: #FFFFFF;
		}
		.inp-content{
			padding: 0 10rpx;
			font-size: 28rpx;
		}
	}
	
</style>
