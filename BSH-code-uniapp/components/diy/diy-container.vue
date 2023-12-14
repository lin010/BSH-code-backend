<template>
	<view class="diy-box">
		<search v-if="temp.key == 'search'" :frameColor="temp.background" :textColor="temp.textColor" :borderRadius="temp.radius"
		 :innerFrameColor="temp.color"></search>

		<nav-icon v-if="temp.key == 'nav'" :list="temp.navs" :background="temp.background" :listNums="temp.navs.length"
		 :rowNums="temp.rows" :textColor="temp.color"></nav-icon>

		<!-- <rubik v-if="temp.key == 'rubik'" :imageData='temp.list' :types="temp.style" :space="temp.space"></rubik> -->

		<notice v-if="temp.key == 'notice'" :value="temp"></notice>

		<commodity v-if="temp.key == 'goods'" :productData="temp.list" :listStyle="temp.listStyle" :displayStyle="temp.goodsStyle"
		 :buyBtnStyle="temp.buyBtnStyle" :buyBtnText="temp.buyBtnText" :buyBtnColor="temp.buttonColor" :buyBtnBool="temp.showBuyBtn"
		 :productNameBool="temp.showGoodsName" :priceBool="temp.showGoodsPrice" :showGoodsLevelPrice="temp.showGoodsLevelPrice"
		 :showGoodsOriginalPrice="temp.showGoodsOriginalPrice" :originalPriceLabel="temp.originalPriceLabel" :priceLabel="temp.priceLabel"
		 :levelPriceLabel="temp.levelPriceLabel" :subscriptBool="temp.showGoodsTag" :subscriptIcon="temp.goodsTagPicUrl" :styleTitle='title'></commodity>

		<diy-video v-if="temp.key == 'video'" :value="temp"></diy-video>

		<placeholder v-if="temp.key == 'empty'" :placeholderBackground="temp.background" :placeholderHeight="temp.height"></placeholder>

		<diy-appointing v-if="temp.key == 'appointing'" :value="temp"></diy-appointing>

		<diy-link v-if="temp.key == 'link'" :value="temp"></diy-link>

		<banners v-if="temp.key == 'banner'" :bannerData='temp' @change='change'></banners>

		<diy-copyright v-if="temp.key == 'copyright'" :value="temp"></diy-copyright>

		<diy-image-text v-if="temp.key == 'image-text'" :value="temp"></diy-image-text>

		<diy-map v-if="temp.key == 'map'" :value="temp"></diy-map>

		<thematic v-if="temp.key == 'topic'" :thematicData='temp'></thematic>

		<!-- 图片魔方 -->
		<view class="adBoxs" v-if="temp.key == 'rubik'">
			<rubik :types="temp.style" :image-data="temp.list" :hotspotData="temp.hotspot"></rubik>
		</view>
		<!-- 图片魔方 -->

		<!-- 广告弹窗 -->
		<diy-modal v-if="temp.key == 'modal'" :modalData="temp"></diy-modal>

		<!-- 领取积分 -->
		<diy-take-score v-if="temp.key == 'take-score'" :value="temp"></diy-take-score>
		
		<!-- 倒计时 -->
		<diy-timer v-if="temp.key == 'timer'" :value="temp"></diy-timer>
		
		<!-- 好店推荐 -->
		<diy-mch v-if="temp.key == 'mch'" :value="temp"></diy-mch>
		
		<!-- 快捷导航 -->
		<diy-quick-nav :page-id="pageId" v-if="temp.key == 'quick-nav'" :value="temp"></diy-quick-nav>
	
		<!-- 背景 -->
		<block v-if="temp.key == 'background'">
			<view style="position:absolute;left:0;top:0;width:100%;height:100%;background:red;z-index:-1">
				333333333333
			</view>
		</block>
	</view>
</template>

<script>
	export default {
		components: {
			banners: resolve => {
				require(['@/components/banners/banners.vue'], resolve)
			},
			search: resolve => {
				require(['@/components/search/search.vue'], resolve)
			},
			rubik: resolve => {
				require(['@/components/rubik/rubik.vue'], resolve)
			},
			thematic: resolve => {
				require(['@/components/thematic/thematic.vue'], resolve)
			},
			navIcon: resolve => {
				require(['@/components/navIcon/navIcon.vue'], resolve)
			},
			notice: resolve => {
				require(['@/components/notice/notice.vue'], resolve)
			},
			placeholder: resolve => {
				require(['@/components/placeholder/placeholder.vue'], resolve)
			},
			commodity: resolve => {
				require(['@/components/commodity/commodity.vue'], resolve)
			},


			diyImageText: resolve => {
				require(['@/components/diy/diy-image-text.vue'], resolve)
			},
			diyCopyright: resolve => {
				require(['@/components/diy/diy-copyright.vue'], resolve)
			},
			diyMap: resolve => {
				require(['@/components/diy/diy-map.vue'], resolve)
			},
			diyModal: resolve => {
				require(['@/components/diy/diy-modal.vue'], resolve)
			},
			diyTakeScore: resolve => {
				require(['@/components/diy/diy-take-score.vue'], resolve)
			},
			diyTimer: resolve => {
				require(['@/components/diy/diy-timer.vue'], resolve)
			},
			diyMch: resolve => {
				require(['@/components/diy/diy-mch.vue'], resolve)
			},
			diyQuickNav: resolve => {
				require(['@/components/diy/diy-quick-nav.vue'], resolve)
			},
		},
		props: {
			dataList: {
				type: Array,
				default: () => []
			},
			diyData: {
				type: Object,
				default: () => {}
			},
			title:{
				type: String,
				default: () => {}
			},
			pageId:{
				type: Number,
				default: 0
			}
		},
		data() {
			return {
				temp: {},
				bargainKeys: ['appointing', 'seckill'],
				current: 0
			};
		},
		watch: {
			diyData: {
				handler() {
					let temp = {
						...this.diyData.data,
						key: this.diyData.id
					};
					this.$set(this, "temp", temp)
					//console.log(this.temp, temp.key);
				},
				immediate: true,
				deep: true
			}
		},
		methods: {
			change(e) {
				if (this.temp.style == 1 && this.temp.fill == 1) {
					this.current = e;
				}
			},
		}
	}
</script>

<style lang="scss" scoped>
	.adBoxs {
		overflow: hidden;
	}
</style>
