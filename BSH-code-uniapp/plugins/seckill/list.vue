<template>
	<view class="app">
		<view class="tabs" :class="ceiling ? 'fixed' : '' ">
			<view class="time-tab">
				<view class="seckill-tab">
					<span class="span">最后疯抢</span>
					<span class="em">已开抢</span>
					<span class="hot-icon"></span>
				</view>
				<view class="seckill-tab" v-for="(tab,tab_index) in seckillTabs" :key="tab_index" :class=" tab.id == '1' ? 'cur' : '' ">
					<span class="span">{{tab.data}}</span>
					<span class="em">{{tab.msg}}</span>
				</view>
			</view>
		</view>
		<view class="seckill-items">
			<view class="seckill-item" v-for="(item,item_index) in goods" :key="item_index">
				<view class="item">
					<view class="good-cover">
						<image :src="item.img" class="img"></image>
					</view>
					<view class="good-info">
						<view class="good-title">{{item.goodName}}</view>
						<view class="good-type">{{item.msg}}</view>
						<view class="sale">
							<view class="pics">
								<span class="cur-pic">
									<span class="dot">¥</span>
									<strong class="money">{{item.curPrice}}</strong>
								</span>
								<span class="pic">¥{{item.price}}</span>
							</view>
							<view class="detail" @click="goGoodDetail(item.id)">
								<button type="default">去抢购</button>
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	const tabs = [{
			id: "1",
			data: 1586592000000,
			msg: "抢购中"
		},
		{
			id: "2",
			data: 1586599200000,
			msg: "即将开始"
		}, {
			id: "3",
			data: 1586606400000,
			msg: "即将开始"
		}, {
			id: "4",
			data: 1586613600000,
			msg: "即将开始"
		}, {
			id: "5",
			data: 1586620800000,
			msg: "即将开始"
		},
	]
	const goodList = []

	import moment from 'moment';
	export default {
		data() {
			return {
				ceiling: false,
				scrollTop: 50,
				seckillTabs: [],
				goods: []
			}
		},
		onLoad() {
			
			this.seckillTabs = JSON.parse(JSON.stringify(tabs));
			let _self = this;
			let good;
			for (let i = 1; i <= 10; i++) {
				good = {
					id: i,
					goodName: '商品测试' + i,
					msg: '顺丰包邮',
					curPrice: 100,
					price: 200,
					img: 'http://m.360buyimg.com/babel/s333x333_jfs/t1/33728/10/12924/112345/5cf61386E2db7f1f0/81f13416b1522681.jpg!q70.dpg'
				}
				_self.goods.push(good);
			}
		},
		onPageScroll(e){
			this.ceiling = e.scrollTop >= this.scrollTop ? true : false;
		},
		mounted() {
		},
		watch: {
			seckillTabs() {
				this.seckillTabs.forEach(tab => {
					tab.data = moment(tab.data).format("HH:mm");
				})
			},
		},
		methods: {
			goGoodDetail(id){
				uni.navigateTo({
					url:`./good?id=${id}`
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	body {
		line-height: 1.2;
	}

	.tabs {
		top: 0;
		left: 0;
		right: 0;
		width: 100%;
		height: 120rpx;
		z-index: 998;
		max-width: 54rem;
		margin: 0 auto;
		font-size: 0;

		&.fixed {
			position: fixed;
			top: 0;
			box-shadow: 0 10rpx 16rpx 0 rgba(0, 0, 0, .06);
		}

		.time-tab {
			background-color: #fff;
			position: relative;
			white-space: nowrap;
			font-size: 0;
			font-family: arial;
			overflow-x: scroll;
			
			&::-webkit-scrollbar {
				width: 0;
				height: 0;
				background-color: transparent;
			}
			
			.seckill-tab {
				vertical-align: top;
				display: inline-block;
				height: 100%;
				padding: 30rpx 20rpx 10rpx 30rpx;
				overflow: hidden;
				text-align: center;
				font-size: 0;
				letter-spacing: 0;
				line-height: 28rpx;
				color: #333;

				&.cur {
					color: #ff4142;

					.em {
						background: linear-gradient(270deg, #ff4500, #ff1451);
						border-radius: 16rpx;
						padding: 4rpx;
						color: #fff;
						margin-top: 8rpx;
					}
				}

				.span {
					display: block;
					font-size: 11pt;
					line-height: 28rpx;
					font-family: JDZhengHT-EN-Bold;
					font-weight: 700;
				}

				.em {
					display: inline-block;
					margin-top: 10rpx;
					font-size: 9pt;
					letter-spacing: 0;
					text-align: center;
					line-height: 24rpx
				}

				.hot-icon {
					background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAYCAYAAACIhL/AAAAAAXNSR0IArs4c6QAABbRJREFUSA2FV12IVlUUXfd+H9NDMxXaj01jamYYWWIkggQl4cMw5JAE9TBQ0d+TkT0EFv1IZE8VJfRQQ2JoRWLhwCAmNRopmeCE9TCQg6A2gpVj6kPMzPfd1tpn7/vd0akO3rPP2Xuttff5uXc+s+LBO7vRxIcoii4gQ2q0BZ9MVp6K1TR8hmdsJp8wM/LzixQYo8Yo8nwQ9XxXtnvfqSRyeZ8VPUtOEszi2EwwijFHKkZ+K9KLmVZw4GmjIFtA8OXXOPeFhd9tljeYfwvytteyvXvH5K02sbqSAN0SLtiZoCe8LKnTrWgjOJ5jcY0vgeBXdJ1qxviKNWvsnkJjYqRYvWpNFaIxC/QWhChI1U5LKJwKkqkWkly2exoaP3CXxEo+/YLERgiWoQPN5lfFA6ue0zRaKjASyms7QFstWP5pYjYp67Vk4qmVfJuYyzrTE4+P0bVID8vak+Xc0XerO5kKjGKEtx1wYmlCzBWtCC/SOCUQqOnEKnMbuiNnupzxjE+tnsby2ZjWJFnkVLGtWL26U1SiPFFZGMV2HAB+OwE83+eZiBHsyquAz78BftgPvPEiHXLymTsf6H0EWHoPMIe6I78ARw4DX2wHJicT5tU3gXvv4/g/2sf9wHZysqwDjcZGIp+u25FYIq3SE2qVNa7ItkI+HyquHeIiy52edS2waTMwazZw8Dvg8EHgjqXAY88AN88H3npdbOAQ/b+fae3uQw8D42eBfUMpLu2RER+beaLo7t5Yt5psToBqLM+Hc+1qk04aGxvOO7tzDGx8G7juBmDDOu7ajwImifUbgO5e4NivPJFPgT2DLWlhetcCp08DH7yfOMZTIrZ0j2uYmOjlpUy+tJMaO0iBuPCRtAVOpGtmAYtuB37icR455EKSoMbWj4CpKWDFyuS3BSkZY5FTkRib9UlctwI9eVlPkqkwolDZCtFw9EnklkWJ9fMwbeDlIn78T97jk8TcmjDqxQmt8BqtylWA/ORamF4S5Y+qI9G8hcAne5KM14e8IqQdWeAFnv0jCQYurPzzFvAKXA+c8funWEXGJnZSdAYvLIpO3sHiIkHtrZcl1WTgBo+oqqa/z9U2pTeUTS+VHaHHAya/mt5k+S4tLnARMzA72+mEr/OHwhjJt0WsXMaJUWDdo0KnR+Lt/Mzs2McBJxI5zhdAbTbf5HIhjMVC5D83nh7xo0Ib+zR2T+HQiMVmGMv5x46VVFssSz4fm8ilGGY57tS7VzAYWckRbc6NwE1ziTnGEGMmpc7jIVf6xXcNhxE7yg8af/KoxbbaJLogkGFDU0tBzS+cA/SCLF4CrLw/SLTEPf5s0jzAb2N5vwVxzRItTfqq+QXRk2GwjrZsFybwHr93vDAODvI04XDKRhLiN70EbN4KvLwJ2L+X37YxYNlyfqzvAr79GhjYSbx0o3FsRxhzt3GsmlqxWQNtbbvy7Mujp1jcFoOZToixCJGMSBvuFtBG9jl55QXg+yFgOb95fU8CHR3Azs+Ad1g0qdaVOyStaWKOoS/8aQFbst27TxmyWLOsE43JEV7ujrS1dFd/UVsSQQ3uVhgF3G9D3piOq4Hz51NSJZT/X/mMWRNOOmxJ5wJ/QCzWD1gqMsXAMM8l72Oupq3CBNnFDsbKUqYZ/BJREnLO/5WshE3HY5qHQ349VpMX5ob/DWgy1he/rq1AUbPBowNcxXoyWaQcQQxrKHWtWBRgCX2iIo0vYACC4wGTZCzCsnoyFgesz4aGBmitefaYEtezbA2Kxjai03GnZTIQUNoklmzIWDJhIu72Mj73xPgRl4B8BY+1xp1rFecRmVbLBocHUK8tZqJ+rqiRIhTjv9ZY4lYRXR6IHRfIjtsG6tiCH1i6Srrl6EftCt656cU5U2bmVqxd0YW/J3up1kPBhdzFTtr2lFBFkmeJlNiTm/WYyXoscEV2kQvgX69MX/n//W/nP2B5+/DPma+aAAAAAElFTkSuQmCC);
					width: 1rem;
					height: 24rpx;
					background-size: contain;
					background-position: 50%;
					position: relative;
					top: .1rem;
					display: inline-block;
					margin-left: 10rpx;
					background-repeat: no-repeat;
				}
			}
		}
	}

	.seckill-items {
		padding-top: 40rpx;
		overflow: hidden;

		.seckill-item {
			position: relative;
			width: 700rpx;
			min-height: 280rpx;
			background: #fff;
			border-radius: 24rpx;
			margin: 20rpx auto 0;
			overflow: hidden;

			.item {
				padding: 20rpx;
				display: flex;

				.good-cover {
					width: 240rpx;
					height: 240rpx;

					.img {
						width: 100%;
						height: 100%;
						background-size: 100%;
					}
				}

				.good-info {
					display: flex;
					flex-direction: column;
					flex: 1;

					.good-title {
						color: #000000;
						font-weight: 400;
						line-height: 1.2;
						font-size: 11pt;
					}

					.good-type {
						margin-top: 16rpx;
						color: #F55C48;
						font-size: 9pt;
					}

					.sale {
						display: flex;
						flex: 1;
						align-items: flex-end;

						.pics {
							flex: 1;
							font-size: 0;

							.cur-pic {

								color: #F2270C;
								display: flex;
								align-items: center;
								.dot {
									font-size: 9pt;
								}
								.money {
									font-size: 17pt;
								}
							}

							.pic {
								margin-left: 8rpx;
								color: #bfbfbf;
								font-size: 9pt;
								text-decoration: line-through;
							}
						}

						.detail {
							
							button {
								font-size: 28rpx;
								background: linear-gradient(270deg, #ff1451, #ff4500);
								color: #fff;
								width: 160rpx;
								height: 80rpx;
								border-radius: 8rpx;
							}
						}
					}
				}
			}
		}
	}
</style>
