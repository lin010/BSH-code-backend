<template>
	<view class="jx-container" @click.stop="chooseImage" :style="{opacity:use_type?0:1}">
		<view class="jx-upload-box">
			<!-- <view class="jx-image-item" v-for="(item,index) in imageList" :key="index" :style="{'width': width+'rpx','height': height+'rpx','margin': limit !== 1 || 0}"> -->
			<block v-if="!use_type">
				<view class="jx-image-item" v-for="(item,index) in imageList" :key="index" :style="!!diyStyle ? `${diyStyle}margin: ${limit !== 1 || 0};` : `width: ${width}rpx;height:${height}rpx;margin: ${limit !== 1 || 0}`">
					<!-- <image :src="item" class="jx-item-img" @tap.stop="previewImage(index)" mode="aspectFill" :style="{'width': width+'rpx','height': height+'rpx'}"></image> -->
					<image :src="item" class="jx-item-img" @tap.stop="previewImage(index)" mode="aspectFill" :style="!!diyStyle ? `${diyStyle}` : `width: ${width}rpx;height:${height}rpx;`"></image>
					<view v-if="!forbidDel" class="jx-img-del" @tap.stop="delImage(index)"></view>
					<view v-if="statusArr[index]!=1" class="jx-upload-mask">
						<view class="jx-upload-loading" v-if="statusArr[index]==2"></view>
						<text class="jx-tips">{{statusArr[index]==2?'上传中...':'上传失败'}}</text>
						<view class="jx-mask-btn" v-if="statusArr[index]==3" @tap.stop="reUpLoad(index)" hover-class="jx-hover"
						 :hover-stay-time="150">重新上传</view>
					</view>
				</view>
			</block>
			<!-- <view v-if="isShowAdd" class="jx-upload-add" @tap.stop="chooseImage" style="z-index: 3;" :style="{'width': width+'rpx','height': height+'rpx'}"> -->
			<view v-if="isShowAdd" class="jx-upload-add" @tap.stop="chooseImage" style="z-index: 3;" :style="!!diyStyle ? `${diyStyle}` : `width: ${width}rpx;height:${height}rpx;`">
				<view class="jx-upload-icon jx-icon-plus" v-if="is_style"></view>
				<view class="upload-img flex-col flex-x-center flex-y-center" @tap="addImg" v-else>
					<view class="iconfont icon-xiangji"></view>
					<view style="font-size: 30rpx;">添加图片</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name: 'jxUpload',
		props: {
			// 用于重复上传
			use_type:{
				type: Boolean,
				default: false
			},
			// 可用于判断单页使用多个当前组件时
			diyKey: {
				type: String,
				default: ""
			},
			//初始化图片路径
			value: {
				type: Array,
				default () {
					return []
				}
			},
			//禁用删除
			forbidDel: {
				type: Boolean,
				default: false
			},
			//禁用添加
			forbidAdd: {
				type: Boolean,
				default: false
			},
			//服务器地址
			serverUrl: {
				type: String,
				default: ''
			},
			//限制数
			limit: {
				type: Number,
				default: 9
			},
			//项目名，默认为 file
			fileKeyName: {
				type: String,
				default: "file"
			},
			diyStyle:{
				type: String,
				default: ''
			},
			width: {
				type: Number,
				default: 220
			},
			height: {
				type: Number,
				default: 220
			},
			is_style:{
				type: Boolean,
				default: true
			}
		},
		watch:{
			value(){
				this.imageList = [...this.value];
				for (let item of this.imageList) {
					this.statusArr.push("1")
				}
			}
		},
		data() {
			return {
				//图片地址
				imageList: [],
				//上传状态：1-上传成功 2-上传中 3-上传失败
				statusArr: []
			}
		},
		created() {
			this.imageList = [...this.value];
			for (let item of this.imageList) {
				this.statusArr.push("1")
			}
		},
		computed: {
			isShowAdd() {
				let isShow = true;
				if(!this.use_type){
					if (this.forbidAdd || (this.limit && this.imageList.length >= this.limit)) {
						isShow = false;
					}
				}
				return isShow
			}
		},
		methods: {
			addImg(){
				this.$emit('addImg',{})
			},
			// 重新上传
			reUpLoad(index) {
				this.$set(this.statusArr, index, "2")
				this.change()
				this.uploadImage(index, this.imageList[index]).then(() => {
					this.change()
				}).catch(() => {
					this.change()
				})
			},
			change() {
				let status = ~this.statusArr.indexOf("2") ? 2 : 1
				if (status != 2 && ~this.statusArr.indexOf("3")) {
					// 上传失败
					status = 3
				}
				// console.log(status,this.imageList);
				this.$emit('complete', {
					status: status,
					key: this.diyKey ? this.diyKey : -1,
					imgArr: this.imageList
				})
			},
			chooseImage: function() {
				let _this = this;
				if(!this.use_type){
					if(this.imageList.length >= _this.limit){
						return;
					}
				}
				uni.chooseImage({
					count: _this.limit - _this.imageList.length,
					success: function(e) {
						let imageArr = [];
						for (let i = 0; i < e.tempFilePaths.length; i++) {
							let len = _this.imageList.length;
							if (len >= _this.limit) {
								uni.showToast({
									title: `最多可上传${_this.limit}张图片`,
									icon: "none"
								});
								break;
							}
							let path = e.tempFilePaths[i]
							imageArr.push(path)
							_this.imageList.push(path)
							_this.statusArr.push("2")
						}
						_this.change()

						let start = _this.imageList.length - imageArr.length
						for (let j = 0; j < imageArr.length; j++) {
							let index = start + j
							// 	console.log({
							// 	serverUrl: _this.$api.default.upload,
							// 	file: imageArr[j],
							// 	fileKeyName: _this.fileKeyName
							// });
							_this.$http.uploadFile({
								serverUrl: _this.$api.default.upload+'&width=3000&height=3000&type=1',
								file: imageArr[j],
								fileKeyName: _this.fileKeyName
							}).then(res => {
								// console.log(res,"成功了");
								if(res.code==0){
									_this.uploadImage(index,res).then(() => {
										_this.change()
									}).catch(() => {
										_this.change()
									})
								}else{
									uni.showToast({
										title: '图片太大，请重新上传',
										icon: 'none'
									});
									setTimeout(function() {
										uni.hideToast();
									}, 2000);
								}
							}).catch(() => {
								_this.$set(_this.statusArr, index, "3")
								_this.change()
							})
						}
					}
				})
			},
			uploadImage: function(index,result) {
				let _this = this;
				return new Promise((resolve, reject) => {
					
					if(result.code % 100 === 0){
						// 上传成功 d.url 为上传后图片地址，以实际接口返回为准
						result.data.url && (_this.imageList[index] = result.data.url)
						_this.$set(_this.statusArr, index, result.data.url ? "1" : "3")
					}else {
						// 上传失败
						_this.$set(_this.statusArr, index, "3")
					}
					resolve(index)
				})
			},
			delImage: function(index) {
				this.imageList.splice(index, 1)
				this.statusArr.splice(index, 1)
				this.$emit("remove", {
					index: index
				})
				this.change()
			},
			previewImage: function(index) {
				if (!this.imageList.length) return;
				uni.previewImage({
					current: this.imageList[index],
					loop: true,
					urls: this.imageList
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	@font-face {
		font-family: 'jxUpload';
		src: url(data:application/font-woff;charset=utf-8;base64,d09GRgABAAAAAATcAA0AAAAAByQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABGRlRNAAAEwAAAABoAAAAciR52BUdERUYAAASgAAAAHgAAAB4AKQALT1MvMgAAAaAAAABCAAAAVjxvR/tjbWFwAAAB+AAAAEUAAAFK5ibpuGdhc3AAAASYAAAACAAAAAj//wADZ2x5ZgAAAkwAAADXAAABAAmNjcZoZWFkAAABMAAAAC8AAAA2FpiS+WhoZWEAAAFgAAAAHQAAACQH3QOFaG10eAAAAeQAAAARAAAAEgwAACBsb2NhAAACQAAAAAwAAAAMAEoAgG1heHAAAAGAAAAAHwAAACABEgA2bmFtZQAAAyQAAAFJAAACiCnmEVVwb3N0AAAEcAAAACgAAAA6OMUs4HjaY2BkYGAAYo3boY/i+W2+MnCzMIDAzb3qdQj6fwPzf+YGIJeDgQkkCgA/KAtvAHjaY2BkYGBu+N/AEMPCAALM/xkYGVABCwBZ4wNrAAAAeNpjYGRgYGBl0GJgZgABJiDmAkIGhv9gPgMADTABSQB42mNgZGFgnMDAysDA1Ml0hoGBoR9CM75mMGLkAIoysDIzYAUBaa4pDA7PGJ9xMjf8b2CIYW5gaAAKM4LkANt9C+UAAHjaY2GAABYIVmBgAAAA+gAtAAAAeNpjYGBgZoBgGQZGBhBwAfIYwXwWBg0gzQakGRmYnjE+4/z/n4EBQksxSf6GqgcCRjYGOIeRCUgwMaACRoZhDwCiLwmoAAAAAAAAAAAAAAAASgCAeNpdjkFKw0AARf/vkIR0BkPayWRKQZtYY90ohJju2kOIbtz0KD1HVm50UfEmWXoAr9ADOHFARHHzeY//Fx8Ci+FJfIgdJFa4AhgiMshbrCuIsLxhFJZVs+Vl1bT1GddtbXTC3OhohN4dg4BJ3zMJAnccyfm468ZzHXddrH9ZKbHzdf9n/vkY/xv9sPQXgGEvBrHHwst5kTbXLE+YpYVPkxepPmW94W16UbdNJd6f3SAzo5W7m1jaKd+8ZZIvk5nlKw9SK6Wle7BLS3f/bTzQLmfAF2T1NsQAeNp9kD1OAzEQhZ/zByQSQiCoXVEA2vyUKRMp9Ailo0g23pBo1155nUg5AS0VB6DlGByAGyDRcgpelkmTImvt6PObmeexAZzjGwr/3yXuhBWO8ShcwREy4Sr1F+Ea+V24jhY+hRvUf4SbuFUD4RYu1BsdVO2Eu5vSbcsKZxgIV3CKJ+Eq9ZVwjfwqXMcVPoQb1L+EmxjjV7iFa2WpDOFhMEFgnEFjig3jAjEcLJIyBtahOfRmEsxMTzd6ETubOBso71dilwMeaDnngCntPbdmvkon/mDLgdSYbh4FS7YpjS4idCgbXyyc1d2oc7D9nu22tNi/a4E1x+xRDWzU/D3bM9JIbAyvkJI18jK3pBJTj2hrrPG7ZynW814IiU68y/SIx5o0dTr3bmniwOLn8owcfbS5kj33qBw+Y1kIeb/dTsQgil2GP5PYcRkAAAB42mNgYoAALjDJyIAOWMGiTIxMjMxsKak5qSWpbFmZiRmJ+QAmgAUIAAAAAf//AAIAAQAAAAwAAAAWAAAAAgABAAMABAABAAQAAAACAAAAAHjaY2BgYGQAgqtL1DlA9M296nUwGgA+8QYgAAA=) format('woff');
		font-weight: normal;
		font-style: normal;
	}

	.jx-upload-icon {
		font-family: "jxUpload" !important;
		font-style: normal;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		padding: 10rpx;
	}

	.jx-icon-delete:before {
		content: "\e601";
	}

	.jx-icon-plus:before {
		content: "\e609";
	}

	.jx-upload-box {
		width: 100%;
		display: flex;
		flex-wrap: wrap;
	}

	.jx-upload-add {
		width: 220rpx;
		height: 220rpx;
		font-size: 50rpx;
		font-weight: 100;
		color: #B3B3B3;
		background-color: #FFFFFF;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 0;
		border: 2rpx solid #E6E6E6;
		border-radius: 15rpx;
	}

	.jx-image-item {
		width: 220rpx;
		height: 220rpx;
		position: relative;
		margin-right: 20rpx;
		margin-bottom: 16rpx;
	}

	.jx-image-item:nth-of-type(4n) {
		margin-right: 0;
	}

	.jx-item-img {
		width: 220rpx;
		height: 220rpx;
		display: block;
	}

	.jx-img-del {
		width: 36rpx;
		height: 36rpx;
		position: absolute;
		right: -12rpx;
		top: -12rpx;
		background: #EB0909;
		border-radius: 50%;
		color: white;
		font-size: 34rpx;
		z-index: 999;
	}

	.jx-img-del::before {
		content: '';
		width: 16rpx;
		height: 1px;
		position: absolute;
		left: 10rpx;
		top: 18rpx;
		background: #fff;
	}

	.jx-upload-mask {
		width: 100%;
		height: 100%;
		position: absolute;
		left: 0;
		top: 0;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: space-around;
		padding: 40rpx 0;
		box-sizing: border-box;
		background: rgba(0, 0, 0, 0.6);
	}

	.jx-upload-loading {
		width: 28rpx;
		height: 28rpx;
		border-radius: 50%;
		border: 2px solid;
		border-color: #B2B2B2 #B2B2B2 #B2B2B2 #fff;
		animation: jx-rotate 0.7s linear infinite;
	}

	@keyframes jx-rotate {
		0% {
			transform: rotate(0);
		}

		100% {
			transform: rotate(360deg);
		}
	}

	.jx-tips {
		font-size: 26rpx;
		color: #fff;
	}

	.jx-mask-btn {
		padding: 2rpx 10rpx;
		border-radius: 40rpx;
		text-align: center;
		font-size: 24rpx;
		color: #fff;
		border: 1rpx solid #fff;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.jx-hover {
		opacity: 0.5;
	}
	
	.upload-img {
		width: 180rpx;
		height: 180rpx;
		color: #bfbfbf;
		// border: 1rpx dotted #bfbfbf;
		z-index: 10;
	
		.iconfont {
			font-size: 16pt;
		}
	}
</style>
