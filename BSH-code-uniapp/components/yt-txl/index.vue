<template>
	<view class="yt-txl-container">
		<view class="search">
			<input type="text" @input="inputFunc" class="s-input" placeholder="搜索" />
		</view>
		<view class="scroll" :style="index ? '' :'right:25upx'">
			<scroll-view :scroll-into-view="to" scroll-y style="width: 100%;height: 100%;">
				<view :id="o.key" v-for="(o,i) in resource" :key="i">
					<view class="p" >{{o.key}}</view>
					<view @click="clickFunc(item)" v-for="(item,index) in o.data" class="info" :key="index">
						<view :style="'background:'+color" class="icon">
							{{item[name].slice(0,1)}}
						</view>
						<view class="item">
							<text class="name">{{item[name]}}</text>
						</view>
					</view>
				</view>
			</scroll-view>
		</view>
		<view class="flag" v-if="index">
			<scroll-view scroll-y="true" :show-scrollbar="false" class="flag-scroll" style="width: 100%;height: 100%;">
				<view @click="toFunc(o.key)" class="flag-key" v-for="(o,i) in resource" :key="i">
					{{o.key}}
				</view>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	'use strict';

	let pinyin = new(require('./pinyin'))({
		charCase: 0
	});

	export default {
		props: {
			datas: {
				type: Array,
				default () {
					return [];
				}
			},
			name: {
				type: String,
				default () {
					return "name";
				}
			},
			index: {
				type: Boolean,
				default: true
			},
			color: {
				type:String,
				default:"#f44336"
			}
		},
		data() {
			return {
				resource: [],
				chars: [],
				to: "",
				cache:[]
			};
		},
		watch: {
			datas(r) {
				if (!(r instanceof Array)) {
					console.log("the props datas type must be array")
					return
				}
				this._parseData(r)
			}
		},
		methods: {
			toFunc(o) {
				this.to = o
			},
			clickFunc(item){
				this.$emit("ev",item)
			},
			inputFunc(r){ //搜索功能
				if(!r.detail.value) {
					this.resource = this.cache
					return
				}
				
				let temp = []
				this.cache.forEach(o => {
					o.data.forEach(item =>{
						if(item[this.name].indexOf(r.detail.value) > -1){ // 匹配到
							// 确定当前这个元素的key是谁
							let key = o.key
							// 找到temp中的key
							let index = 0 //下标
							let find = false // 数据是否存在
							for(let d in temp){
								if(temp[d].key === o.key){
									index = d
									find = true
								}
							}
							if(find){ // 如果key已经存在，直接插入数据
								temp[index].data.push(item)
							} else { // 不存在初始化一个并存入
								temp.push({
									key:o.key,
									data:[item]
								})
							}
						}
					})
				})
				this.resource = temp
			},
			_type(val){
				return Object.prototype.toString.call(val).slice(8,-1).toLowerCase()
			},
			_parseData(r) {
				// 生成a-z的数组
				let data = [];
				this.chars = []
				for (let i = 65; i <= 90; i++) {
					let key = String.fromCharCode(i)
					data.push({"key":key,data:[]})
					this.chars.push(key)
				}
				if(this._type(r) === "array") {
					
					// 填充数据
					r.forEach(o => {
						// 找到char的位置
						data.forEach( (item,index) => {
							let a = this._parseChar(o[this.name || 'name'])
							if(item.key === a){
								data[index].data.push(o)
							}
						})
					})
				}
				// 组合最后数据并踢出没有匹配到a-z中的任意数据
				let finalData = []
				for (let i in data) {
					if(data[i].data.length > 0){
						finalData.push(data[i])
					}
				}
				this.resource = finalData
				this.cache = finalData
				finalData = null
			},
			_parseChar(name) {
				if (Object.prototype.toString.call(name).slice(8, -1) !== 'String') {
					console.error("name is not string")
					return
				}
				let chars = pinyin.getFullChars(name);
				return chars[0].toUpperCase()
			}
		},
		mounted() {
			this._parseData(this.datas)
		}
	};
</script>

<style>
	page {
		background: #f4f4f4;
	}

	.search {
		width: 100%;
		height: 120upx;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.s-input {
		width: 700upx;
		height: 80upx;
		background-size: 28rpx;
		background: #f4f4f4;
		border-radius: 15rpx;
		padding-left: 30rpx;
		color: #000;
	}

	.scroll {
		position: absolute;
		left: 25upx;
		top: 160upx;
		right: 100upx;
		bottom: 25upx;
	}

	.p {
		position: sticky;
		top: 0;
		left: 0;
		background: #f4f4f4;
		font-size: 28upx;
		margin-bottom: 10upx;
		text-indent: 40upx;
		z-index: 99998;
		font-weight: bold;
	}

	.info {
		display: flex;
		justify-content: flex-start;
		align-items: center;
		margin-bottom: 10upx;
		padding: 0upx 25upx;
		background: #fff;
	}

	.icon {
		width: 60upx;
		height: 60upx;
		border-radius: 50%;
		margin-right: 20upx;
		display: flex;
		justify-content: center;
		align-items: center;
		font-size: 30upx;
		color: #fff;
	}

	.item {
		height: 80upx;
		display: flex;
		flex-direction: column;
		justify-content: space-around;
	}

	.flag {
		width: 50upx;
		position: absolute;
		top: 160upx;
		right: 25upx;
		bottom: 25upx;
	}

	.flag-scroll {
		padding-top: 10upx;
	}

	.flag-key {
		padding: 0;
		margin: 0 auto 10upx auto;
		width: 30upx;
		height: 30upx;
		border-radius: 50%;
		color: #000;
		font-weight: bold;
		display: flex;
		justify-content: center;
		align-items: center;
		font-size: 20upx;
	}
</style>
