<template>
	<view class="app" v-if="dataForm">
		<view class="goods">
			<view class="goods-items">
				<view class="item" v-for="(item, i) in dataForm.list" :key="i">
					<view class="image-score">
						<image class="img" :src="item.pic_url" mode="aspectFill"></image>
						<view class="score">
							<view class="desc">描述相符</view>
							<view class="star"><jx-rate :current="item.grade_level" @change="change" :detail-id="item.order_detail_id"></jx-rate></view>
						</view>
					</view>
					<view class="text-image">
						<view class="text">
							<textarea class="textarea" v-model="item.content" maxlength="100" placeholder="商品满足你的期待吗？说说你的使用心得,分享给想买的他们吧!"></textarea>
						</view>
						<view class="upload">
							<!-- <view v-if="item.pic_list.length == 0" class="upload-img flex-col flex-x-center flex-y-center" @click="upload(item.order_detail_id)">
								<view class="iconfont icon-xiangji"></view>
								<view>添加图片</view>
							</view> -->
							<com-upload
								:key="i"
								:ref="`upload${item.order_detail_id}`"
								:serverUrl="serverUrl"
								:limit="3"
								@complete="result"
								@remove="remove"
								:width="180"
								:height="180"
								:is_style="false"
								@addImg="addImg(item.order_detail_id)"
							></com-upload>
							<!-- :style="{ opacity: item.pic_list.length ? 1 : 0, position: item.pic_list.length ? '' : 'absolute' }" -->
						</view>
					</view>
				</view>
			</view>
			<view class="btn submit" @click="submit" :style="{'background-color':textColor}">确认发布</view>
		</view>
	</view>
</template>

<script>
import jxRate from '@/components/rate/rate.vue';
export default {
	components: {
		jxRate
	},
	data() {
		return {
			detail: null,
			dataForm: null,
			serverUrl: this.$api.default.upload,
			uploadId: 0,
			changeId: 0,
			textColor:''
		};
	},
	onLoad(options) {
		this.textColor = this.globalSet('textCol');
		if (options.id) {
			this.getDetail(options.id, true);
		}
	},
	computed: {
		finalData() {
			let temp = {
				order_id: 0,
				commentData: []
			};
			let { order_id, list } = JSON.parse(JSON.stringify(this.dataForm));
			list.forEach(v => {
				delete v.pic_url;
			});
			temp.order_id = order_id;
			temp.commentData = list;
			return temp;
		},
		dataList() {
			let arr = {
				order_id: -1,
				list: []
			};
			arr.order_id = this.detail.id;
			this.detail.order_goods_list.forEach((item, i) => {
				let temp = {
					pic_url: item.goods_info.pic_url,
					order_detail_id: item.id,
					grade_level: 5,
					content: '',
					pic_list: []
				};
				arr.list.push(temp);
			});
			return arr;
		}
	},
	methods: {
		submit() {
			let { order_id, commentData } = this.finalData;
			this.$http.request({
					url: this.$api.order.commentSubmit,
					method: 'POST',
					data: {
						order_id,
						commentData
					},
					showLoading: true
				})
				.then(res => {
					this.$http.toast(res.msg);
					if (res.code == 0) {
						setTimeout(() => {
							uni.redirectTo({
								url: './success'
							});
						}, 1000);
					}
				});
		},
		addImg(id){
			// 根据id上传指定商品的评论图片
			this.uploadId = id;
			// 	this.$refs[`upload${id}`][0].$el.click();
		},
		getDetail(id, bool) {
			this.$http.request({
					url: this.$api.order.detail,
					data: {
						id
					},
					showLoading: bool
				})
				.then(res => {
					if (res.code === 0) {
						let { detail } = res.data;
						this.$set(this, 'detail', detail);
						this.detail = detail;
						this.dataForm = this.dataList;
					}
				});
		},
		change(e) {
			let temp = this.dataForm.list.find(v => {
				if (v.order_detail_id == e.detailId) {
					return v;
				}
			});
			temp.grade_level = e.index;
		},
		result(e) {
			// 图片上传结果
			if (e.status != 1) {
				return;
			}
			let temp = this.dataForm.list.find(v => {
				if (v.order_detail_id == this.uploadId) {
					return v;
				}
			});
			temp.pic_list = e.imgArr;
		},
		remove(e) {
			//移除图片
			let index = e.index;
		}
	},
};
</script>

<style lang="scss" scoped>
.app {
	height: 100%;
	background-color: #f7f7f7;

	.goods {
		padding: 20rpx;

		.goods-items {
			padding: 10rpx;
			color: #333333;
			font-size: 11pt;

			.item {
				padding: 30rpx 16rpx;
				background-color: #ffffff;
				border-radius: 20rpx;
				margin-bottom: 20rpx;

				.image-score {
					display: flex;
					margin-bottom: 30rpx;

					.img {
						width: 120rpx;
						height: 120rpx;
						margin-right: 16rpx;
					}

					.score {
						display: flex;
						align-items: center;

						.desc {
							margin-right: 36rpx;
						}
					}
				}

				.text-image {
					.text {
						.textarea {
							width: 100%;
							height: 140rpx;
							font-size: 10pt;
						}

						.uni-textarea-placeholder {
							color: #999999;
						}
					}

					.upload {
						margin-top: 20rpx;
						display: flex;
						position: relative;

						.upload-img {
							width: 180rpx;
							height: 180rpx;
							color: #bfbfbf;
							border: 1rpx dotted #bfbfbf;
							z-index: 10;

							.iconfont {
								font-size: 16pt;
							}
						}
					}
				}
			}
		}
	}

	.btn {
		line-height: 90rpx;
		text-align: center;
		border-radius: 45rpx;
		font-size: 9pt;
		margin: 40rpx 0;

		&.submit {
			color: #ffffff;
			background-color: #bc0100;
		}
	}
}
</style>
