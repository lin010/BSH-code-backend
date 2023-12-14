<template>
	<view class="app">
		<view class="app-header">
			<view class="rate-text">
				综合评价:
				<jx-rate class="rate" :current="4.5" :size="14" normal="#ccc" active="#DDB052" :disabled="false"></jx-rate>
			</view>
			<view class="tags">
				<view class="tag" v-for="(item, i) in tags" :key="i">
					<jx-tag v-if="item.count" type="gold" :shape="'circle'">{{ item.name }}({{ item.count }})</jx-tag>
				</view>
			</view>
		</view>
		<view class="app-body">
			<view class="items">
				<view class="item m-bottom-20" v-for="(item, i) in dataList" :key="i">
					<view class="user m-bottom-20 flex center-x">
						<image class="avatar" :src="item.avatar" mode="aspectFill"></image>
						<view class="name">{{ item.nickname }}</view>
						<jx-rate class="rate" :current="item.score" :size="14" normal="#ccc" active="#DDB052" :disabled="false"></jx-rate>
					</view>
					<view class="content" :class="item.pic_url.length ? 'm-bottom-20' : ''">{{ item.content }}</view>
					<view class="images flex"><image class="img" :src="img" mode="aspectFill" v-for="(img, index) in item.pic_url" :key="index"></image></view>
				</view>
			</view>
		</view>

		<!--加载loadding-->
		<main-loadmore :visible="loadding" :index="3" type="red"></main-loadmore>
		<main-nomore :visible="!pullUpOn" bgcolor="#fafafa"></main-nomore>
		<main-loading :visible="loading"></main-loading>
		<!--加载loadding-->
	</view>
</template>

<script>
const _status = 'refresh';
import jxRate from '@/components/rate/rate';
import jxTag from '@/components/tag/tag';
export default {
	components: {
		jxRate,
		jxTag
	},
	data() {
		return {
			loadding: false,
			pullUpOn: true,
			loading: false,
			goodsId: -1, // 商品id
			dataList: [], // 数据列表
			tags: [], // 评论标签列表
			pages: {
				total_count: 1,
				page_count: 1,
				pageSize: 20,
				current_page: 1
			}
		};
	},
	onLoad(options) {
		this.goodsId = options.goodsId;
		this.getDateList(_status, true);
	},
	onShow() {},
	methods: {
		getDateList(status, bool) {
			this.loading = bool ? true : false;
			let { current_page, pageSize } = this.pages;

			this.$http
				.request({
					url: this.$api.goods.comment,
					method: 'POST',
					data: {
						goods_id: this.goodsId,
						page: current_page,
						limit: pageSize
					}
				})
				.then(res => {
					this.loading = false;
					if (res.code === 0) {
						let { comment_count, comments, pagination } = res.data;
						this.tags = comment_count;
						this.dataList = status != 'refresh' ? this.dataList.concat(comments) : comments;
						this.pullUpOn = true;
					}
				});
		}
	},
	computed: {
		totalPage() {
			let temp = {};
			let { pageSize } = this.pages;
			temp = this.tags.find(v => {
				if (v.index == 0) {
					return v;
				}
			});
			return Math.ceil(temp.count / pageSize);
		}
	},
	onPullDownRefresh() {
		(this.pages = {
			current_page: 1,
			pageSize: 20,
			page_count: 1,
			total_count: 0
		}),
			setTimeout(() => {
				uni.stopPullDownRefresh();
				this.getDateList(_status);
			}, 1000);
	},
	onReachBottom() {
		this.loadding = true;
		this.pullUpOn = true;
		let { current_page } = this.pages;
		setTimeout(() => {
			this.loadding = false;
			this.pullUpOn = false;
			if (current_page >= this.totalPage) return;
			this.pages.current_page++;
			this.getDateList();
		}, 1000);
	}
};
</script>

<style lang="scss" scoped>
.app {
	min-height: 100%;
	background-color: #f7f7f7;
	padding: 30rpx;
	color: #000000;

	.app-header {
		font-size: 11pt;
		margin-bottom: 40rpx;
		.rate-text {
			margin-bottom: 20rpx;
		}
		.tags {
			display: flex;
			.tag {
				margin-right: 10rpx;
			}
		}
	}
	.app-body {
		.items {
			.item {
				background-color: #ffffff;
				padding: 20rpx 30rpx;
				border-radius: 20rpx;
				font-size: 11pt;

				.user {
					.avatar {
						margin-right: 10rpx;
					}
				}
				.content {
					margin-left: 77rpx;
					font-size: 9pt;
				}
				.images {
					.img {
						width: 180rpx;
						height: 180rpx;
						border-radius: 5rpx;
						margin-left: 8rpx;
					}
				}
			}
		}
	}

	.rate {
		margin-left: 10rpx;
	}

	.avatar {
		width: 67rpx;
		height: 67rpx;
		border-radius: 50%;
	}
}
.m-bottom-20 {
	margin-bottom: 20rpx;
}
.center-x {
	align-items: center;
}
.flex {
	display: flex;
}
</style>
