// 正式服务器
// let api_host = "https://www.mingyuanriji.cn";
let api_host = "https://h5-mingyuanriji.cn";

// 测试服务器
// let api_host = "http://dev.mingyuanriji.cn";


// 本地服务器
// let api_host = "http://local.mingyuanriji.cn";
// let api_host = "http://192.168.1.188";


let api_root = api_host + '/web/index.php?r=';
let url = api_host + '/web/static/'; //图片路径
let plugins_img_url = api_host + '/web/plugins/'; //扩展页面图片路径 

//wx493ce82aab45e4da
//71b93cac952e55289cc5b3a9b03a345d
// let api_root = 'http://8.129.63.124/web/index.php?r=';
// let url = 'http://8.129.63.124/web/static/'; //图片路径
// let plugins_img_url = 'http://8.129.63.124/web/plugins/'; //扩展页面图片路径

//

// let api_root = 'http://www.mingyuanriji.cn/web/index.php?r=';
// #ifdef H5
//api_root = '/api?r='
// #endif
let api = {
	host: api_host,
	img_url: url,
	plugins_img_url: plugins_img_url,
	index: api_root + 'default/index',
	index2: api_root + 'api/index/index',
	get_setting: api_root + 'api/user-setting/get-one',
	update_setting: api_root + 'api/user-setting/update',

	default: {
		check_auth: api_root + 'api/identity/check-auth',
		login: api_root + 'api/identity/login',
		mini_login: api_root + 'api/identity/mini-login',
		wxLogin: api_root + 'api/identity/auth-login',
		wxLoginRegister: api_root + 'api/identity/wx-auth-login',
		phoneCode: api_root + 'api/identity/phone-code',
		register: api_root + 'api/identity/register',
		upload: api_root + 'api/user/upload',
		diy: api_root + 'plugin/diy/api/page/page',
		bind: api_root + 'api/identity/bind',
		bindPhone: api_root + 'api/user/bind-phone',
		authPhone: api_root + 'api/identity/auth-phone',
		parentMember: api_root + 'api/identity/parent-member',
		bindParent: api_root + 'api/identity/bind-parent',
		subscribe: api_root + 'api/identity/subscribe-status',
		getwxaddress: api_root + 'api/user/wx-user-address-save',


		getvoucherList: api_root + 'plugin/shopping_voucher/api/from-goods/list',



		//---------------------------------------------------------------统一核销	
		verification: api_root + 'api/clerkCenter/clerk/get-logs', //核销记录
		verificationProgress: api_root + 'api/clerkCenter/clerk/do-clerk', //核销
		getOrdercodestatus: api_root + 'api/clerkCenter/clerk/detail', //核销进度
		//------------------------------------------------------------------------金豆
		getredbagList: api_root + 'api/integral/integral-list', //核销进度
	},
	meituan: {
		checkStatus: api_root + 'plugin/meituan/api/index/check-status',
		loginFree: api_root + 'plugin/meituan/api/index/login-free',
	},
	vouchers: { //红包
		getvouchersList: api_root + 'plugin/shopping_voucher/api/user/log', //红包记录
	},
	app: {
		version_info: api_root + 'plugin/bsh_app/api/version/detail'
	},
	smartshop: {
		notification: {
			setWechatTpl: api_root + 'plugin/smart_shop/api/notification/set-wechat-template', //设置微信公众号通知
		},
		store: {
			pay_order_detail: api_root + 'plugin/smart_shop/api/store-pay-order/detail'
		}
	},
	pay: {
		get_pay_data: api_root + 'api/pay/get-pay-data',
	},
	income_log: {
		incomeData: api_root + 'plugin/income_log/api/index/income-data',
	},
	//-------------------------------------------------------------------------------------------淘礼金
	taolijin: {
		getAuthInfo: api_root + 'plugin/taolijin/api/auth/get-info', //获取授权信息
		getgoodsList: api_root + 'plugin/taolijin/api/goods/search', //获取淘礼金商品
		getCatList: api_root + 'plugin/taolijin/api/goods/cat-list', //获取淘礼金商品分类
		bindSpecialId: api_root + 'plugin/taolijin/api/auth/bind-ali-special-id', //获取淘礼金商品分类
		getgoodsDetail: api_root + 'plugin/taolijin/api/goods/detail', //获取淘礼金商品详情
		exchange: api_root + 'plugin/taolijin/api/exchange/integral-to-lj', //金豆兑换礼金接口
		getUrl: api_root + 'plugin/taolijin/api/exchange/get-url', //金豆兑换礼金接口
		getClassify: api_root + 'plugin/alibaba/api/distribution/get-category', //获取1688分类列表
		getHomegoods: api_root + 'plugin/alibaba/api/distribution/search-goods', //获取1688点击分类变换商品
		getDetail: api_root + 'plugin/alibaba/api/distribution/detail', //获取1688点击分类变换商品
		getpreview: api_root + 'plugin/alibaba/api/distribution-order/preview', //获取1688预览订单
		createorder: api_root + 'plugin/alibaba/api/distribution-order/do-submit', //1688生成订单
		getPayData: api_root + 'api/payCenter/payment-order-prepare/alibaba-distribution-order', //预支付获取支付信息接口
		getOrderlist: api_root + 'plugin/alibaba/api/distribution-order/order-list', //订单列表
		getOrderdetail: api_root + 'plugin/alibaba/api/distribution-order/order-details', //订单详情
		getexpress: api_root + 'plugin/alibaba/api/distribution-order/logistics', //物流详情
		refundData: api_root + 'plugin/alibaba/api/distribution-order-refund/to-refund-submit', //获取退款详情
		refundApply: api_root + 'plugin/alibaba/api/distribution-order-refund/apply' //提交退款申请
	},
	//--------------------------------------------------------------------------------------------		

	plugin: {
		//商户
		mch: {
			recommand_mch_data: api_root + 'plugin/mch/api/recommand/mch-data'
		},

		// 短视频
		video: {
			video_list: api_root + 'plugin/short_video/api/video/video-list',
			look_complete_video: api_root + 'plugin/short_video/api/video/look-complete-video',
			video_remark: api_root + 'plugin/short_video/api/video/video-remark',
			praise_video: api_root + 'plugin/short_video/api/video/praise-video',
			comment_list: api_root + 'plugin/short_video/api/comment/comment-list',
			praise_comment: api_root + 'plugin/short_video/api/comment/praise-comment',
			create_comment: api_root + 'plugin/short_video/api/comment/create-comment',
			index: api_root + 'plugin/short_video/api/goods/index',
			share_video: api_root + 'plugin/short_video/api/video/share-video',
			delete_video: api_root + 'plugin/short_video/api/video/delete-video',
			create_video: api_root + 'plugin/short_video/api/video/create-video',
			shopping_list: api_root + 'plugin/short_video/api/goods/shopping-list',
			operation_follow: api_root + 'plugin/short_video/api/video/operation-follow',
			lable_index: api_root + 'plugin/short_video/api/lable/index',
			user_video_list: api_root + 'plugin/short_video/api/user/user-video-list',
			collection_list: api_root + 'plugin/short_video/api/video/collection-list',
			user_list: api_root + 'plugin/short_video/api/user/user-list',
			edit_user: api_root + 'plugin/short_video/api/user/edit-user',
			create_lable: api_root + 'plugin/short_video/api/lable/create-lable',
			follow_list: api_root + 'plugin/short_video/api/user/follow-list',
			user_award: api_root + 'plugin/short_video/api/user/user-award',
			shopping_award: api_root + 'plugin/short_video/api/user/shopping-award',
			look_award: api_root + 'plugin/short_video/api/user/look-award',
		},
		// 名片
		business: {
			create: api_root + 'plugin/business_card/api/business-card/create',
			edit: api_root + 'plugin/business_card/api/business-card/to-edit',
			doEdit: api_root + 'plugin/business_card/api/business-card/do-edit',
			index: api_root + 'plugin/business_card/api/business-card/list',
			save: api_root + 'plugin/business_card/api/business-card/save-address-book',
			my: api_root + 'plugin/business_card/api/business-card/my',
			likeTag: api_root + 'plugin/business_card/api/business-card/like-tag',
			like: api_root + 'plugin/business_card/api/business-card/like',
			behavior: api_root + 'plugin/business_card/api/track-log/behavior',
			track: api_root + 'plugin/business_card/api/track-log/track',
			followList: api_root + 'plugin/business_card/api/business-card-customer/follow-list',
			follow: api_root + 'plugin/business_card/api/business-card-customer/follow',
			detail: api_root + 'plugin/business_card/api/business-card-customer/detail',
			setting: api_root + 'plugin/business_card/api/business-card-customer/setting',
			browsing_history: api_root + 'plugin/business_card/api/business-card-customer/browsing-history',
			team_list: api_root + 'plugin/business_card/api/business-card-customer/team-list',
			business: api_root + 'plugin/business_card/api/business-card-customer/business',
			c_ai_analysis: api_root + 'plugin/business_card/api/business-card-customer/ai-analysis',
			my_client: api_root + 'plugin/business_card/api/business-card-customer/my-client',
			center: api_root + 'plugin/business_card/api/business-card/index',
			my_clue: api_root + 'plugin/business_card/api/business-card/my-clue',
			general: api_root + 'plugin/business_card/api/radar/general',
			mall_order_stat: api_root + 'plugin/business_card/api/radar/mall-order-stat',
			add_customer_stat: api_root + 'plugin/business_card/api/radar/add-customer-stat',
			follow_customer_stat: api_root + 'plugin/business_card/api/radar/follow-customer-stat',
			interest_stat: api_root + 'plugin/business_card/api/radar/interest-stat',
			user_activity_stat: api_root + 'plugin/business_card/api/radar/user-activity-stat',
			sales_rank: api_root + 'plugin/business_card/api/radar/sales-ranking',
			hot_rank: api_root + 'plugin/business_card/api/radar/hot-ranking',
			ai_analysis: api_root + 'plugin/business_card/api/radar/ai-analysis',
			message_init: api_root + 'plugin/business_card/api/business-card/message-init',
			message_add: api_root + 'plugin/business_card/api/message-center/add',
			message_list: api_root + 'plugin/business_card/api/message-center/list',
			poster: api_root + 'plugin/business_card/api/business-card/poster',
			add_track: api_root + 'plugin/business_card/api/track-log/add-track',
			customer_service: api_root + 'plugin/business_card/api/message-center/customer-service',
		},
		score: {
			info: api_root + 'plugin/score/api/score/info',
			log_list: api_root + 'plugin/score/api/score/log-list',
			free_score_take: api_root + 'plugin/integral_card/api/free-score/take'
		},
		diy: {
			page: {
				detail: api_root + 'plugin/diy/api/page/detail',
				poster: api_root + 'plugin/diy/api/page/poster'
			}
		},
		area: {
			apply: api_root + 'plugin/area/api/area/apply',
			info: api_root + 'plugin/area/api/area/info',
			log_list: api_root + 'plugin/area/api/area/log-list',
		},
		// 签到插件
		sign: {
			index: api_root + 'plugin/sign_in/api/index/index',
			user_sign_in: api_root + 'plugin/sign_in/api/index/user-sign-in'
		},
		//拼团插件
		group: {
			getList: api_root + 'plugin/group_buy/api/index/get-list',
			to_submit_order: api_root + 'plugin/group_buy/api/order/to-submit-order', //拼团订单预览
			do_submit_order: api_root + 'plugin/group_buy/api/order/do-submit-order', //拼团订单提交
			goods_group_list: api_root + 'plugin/group_buy/api/group/list', //拼团订单提交
			detail: api_root + 'plugin/group_buy/api/index/detail', //拼团商品详情
			detail_by_order: api_root + 'plugin/group_buy/api/active-item/detail-by-order', //拿到拼单详情id
			group_show: api_root + 'plugin/group_buy/api/group/show', //开团详情
			group_order_list: api_root + 'plugin/group_buy/api/order/list', //拼团订单列表
			group_poster_share: api_root + 'plugin/group_buy/api/poster/share', //生成海报
		},
		//股东分红插件
		boss: {
			info: api_root + 'plugin/boss/api/boss/info',
			price_list: api_root + 'plugin/boss/api/boss/price-list',
			log_list: api_root + 'plugin/boss/api/boss/log-list',
			level_list: api_root + 'plugin/boss/api/boss/level-list',
			team_list: api_root + 'plugin/boss/api/boss/team-list',
			level_list: api_root + 'plugin/boss/api/boss/level-list',
		},
		agent: {
			info: api_root + 'plugin/agent/api/agent/info',
			log_list: api_root + 'plugin/agent/api/agent/log-list',
			level_list: api_root + 'plugin/agent/api/agent/level-list',
			team_list: api_root + 'plugin/agent/api/agent/team-list',
		},
		// 分销插件
		distribution: {
			info: api_root + 'plugin/distribution/api/distribution/info',
			log_list: api_root + 'plugin/distribution/api/distribution/log-list',
			rebuy_info: api_root + 'plugin/distribution/api/distribution/rebuy-info',
			rebuy_price_list: api_root + 'plugin/distribution/api/distribution/rebuy-price-list',
			team_price_list: api_root + 'plugin/distribution/api/distribution/team-price-list',
			subsidy_price_list: api_root + 'plugin/distribution/api/distribution/subsidy-price-list',
			apply: api_root + 'plugin/distribution/api/distribution/apply',
		},
		stock: {
			info: api_root + 'plugin/stock/api/stock/info',
			log_list: api_root + 'plugin/stock/api/stock/log-list',
			agent_goods: api_root + 'plugin/stock/api/stock/agent-goods',
			goods_price_log: api_root + 'plugin/stock/api/stock/goods-price-log',
			buy_goods_price: api_root + 'plugin/stock/api/stock/buy-goods-price',
			stock_goods_list: api_root + 'plugin/stock/api/stock/stock-goods-list',
			level_list: api_root + 'plugin/stock/api/stock/level-list',
			fill_order_submit: api_root + 'plugin/stock/api/stock/fill-order-submit',
			payment: api_root + 'plugin/stock/api/stock/payment',
			pay_data: api_root + 'plugin/stock/api/stock/fill-order-pay-data',
			fill_price_log: api_root + 'plugin/stock/api/stock/fill-price-log',
			team_list: api_root + 'plugin/stock/api/stock/team-list',
			agent_order_list: api_root + 'plugin/stock/api/stock/agent-order-list',
			order_confirm: api_root + 'plugin/stock/api/stock/order-confirm',
			agent_order_submit: api_root + 'plugin/stock/api/stock/agent-order-submit',
		},
		extensions: {
			info: api_root + 'api/income/info',
			poster: api_root + 'api/user/poster',
			LinkPoster: api_root + 'api/user/link-poster',
			LinkPosterNew: api_root + 'api/user/link-poster-new',
			GetIntegral: api_root + 'api/user/get-integral',
			distribution: {
				list: api_root + 'api/income/list'
			},
			cash: {
				setting: api_root + 'api/cash/cash-setting',
				submit: api_root + 'api/cash/cash-submit',
				log: api_root + 'api/cash/cash-log',
				list: api_root + 'api/cash/list',
				detail: api_root + 'api/cash/detail'
			},
			integral_cash: {
				setting: api_root + 'api/integral-cash/cash-setting',
				submit: api_root + 'api/integral-cash/cash-submit',
				log: api_root + 'api/integral-cash/cash-log',
				list: api_root + 'api/integral-cash/list',
				detail: api_root + 'api/integral-cash/detail'
			},
			integral_transfer: {
				setting: api_root + 'api/integral-transfer/setting',
				mobileUser: api_root + 'api/integral-transfer/mobile-user',
				submit: api_root + 'api/integral-transfer/submit',
				log: api_root + 'api/integral-transfer/log',
			},
			team: {
				info: api_root + 'api/user-team/info',
				list: api_root + 'api/user-team/list',
				order: api_root + 'api/user-team/order'
			}
		},
		cloud_warehouse: {
			index: api_root + 'plugin/cloud_warehouse/api/index/index',
			log: api_root + 'plugin/cloud_warehouse/api/index/log',
			commission_total: api_root + 'plugin/cloud_warehouse/api/commission-log/total',
			commission_log: api_root + 'plugin/cloud_warehouse/api/commission-log/log',
		},
	},
	index: {
		index: api_root + 'api/index/index',
		config: api_root + 'api/mall/mall-config',
		userConfig: api_root + 'api/mall/user-center-config'
	},
	article: {
		detail: api_root + 'api/article/detail',
		list: api_root + 'api/article/list',
	},
	goods: {
		detail: api_root + 'api/goods/detail',
		comment: api_root + 'api/goods/comments-list',
		rightList: api_root + 'api/goods/list',
		recommend: api_root + 'api/goods/recommend',
		poster: api_root + 'api/goods-poster/poster' //获取商品海报
	},
	cart: {
		list: api_root + 'api/cart/index',
		modify: api_root + 'api/cart/modify',
		addCart: api_root + 'api/cart/add',
	},
	coupon: {
		receive: api_root + 'api/coupon/receive',
		list: api_root + 'api/coupon/list',
		detail: api_root + 'api/coupon/detail',
		user_coupon_list: api_root + 'api/coupon/user-coupon-list'
	},
	order: {
		submit: api_root + 'api/order/to-submit-order',
		doSubmitOrder: api_root + 'api/order/do-submit-order',
		remindSend: api_root + 'api/order/remind-send',
		deleteOrder: api_root + `api/order/delete`,
		toPay: api_root + 'api/order/to-pay',
		list: api_root + 'api/order/list',
		detail: api_root + 'api/order/detail',
		confirm: api_root + 'api/order/confirm',
		ordersales: api_root + 'api/order/order-sales',
		cancel: api_root + 'api/order/cancel',
		refundList: api_root + 'api/order/refund-list',
		refundDetail: api_root + 'api/order/refund-detail',
		refundData: api_root + 'api/order/to-refund-submit',
		refundSubmit: api_root + 'api/order/do-refund-submit',
		commentSubmit: api_root + 'api/order/comment',
		express_list: api_root + 'api/order/express-list',
		refundSend: api_root + 'api/order/refund-send',
		express_price: api_root + 'api/order/get-express-price',
		getscore: api_root + 'api/order/get-flag',
		refundOrider: api_root + 'api/order/del-refund-order',
		extendedOrider: api_root + 'api/order/order-extended-receiving-time',
		getShow: api_root + 'api/order/check-order-discount-type',

	},
	category: {
		leftlist: api_root + 'api/cat/list',
	},
	user: {
		address: api_root + 'api/user/user-address',
		addressInfo: api_root + 'api/user/address-info',
		fullAddressInfo: api_root + 'api/user/full-address-info',
		addressSave: api_root + 'api/user/user-address-save',
		addressDetail: api_root + 'api/user/user-address-detail',
		addressDelete: api_root + 'api/user/user-address-delete',
		addressDefault: api_root + 'api/user/user-address-default',
		userInfo: api_root + 'api/user/user-info',
		transactionPwd: api_root + 'api/user/set-transaction-password',
		update: api_root + 'api/user/update',
		balanceDetails: api_root + 'api/user/balance-detail',
		scoreDetails: api_root + 'api/user/score-detail',
		recharge_config: api_root + 'api/user/recharge-config',
		user_center: api_root + 'plugin/profit/api/user/center', //中心数据获取




		// 红包、积分券接口
		integral_center: api_root + 'api/integral/center', //红包、积分券管理列表
		//integral_recharge: api_root + 'api/integral/recharge',	//红包、积分券充值
		score_integral_recharge: api_root + 'plugin/integral_card/api/card/recharge', //积分券充值
		shopping_integral_recharge: api_root + 'plugin/recharge_card/api/card/recharge', //红包充值
		integral_plan: api_root + 'api/integral/plan', //红包、积分券发放计划
		integral_deduct_list: api_root + 'api/integral/deduct-list', //有效红包、积分券详情
		//integral_recharge_record: api_root + 'api/integral/recharge-record',	//红包充值卡列表
		score_integral_recharge_record: api_root + 'plugin/integral_card/api/card/recharge-record', //积分券充值卡列表
		shopping_integral_recharge_record: api_root + 'plugin/recharge_card/api/card/recharge-record', //红包充值卡列表

		my_integral_card: api_root + 'plugin/integral_card/api/card/my-integral', // 我的积分卡券
		my_shopping_card: api_root + 'plugin/recharge_card/api/card/my-recharge', // 我的购物卡券

	},
	collect: {
		add: api_root + 'api/collect/add',
		deletes: api_root + 'api/collect/delete',
		list: api_root + 'api/collect/list',
	},
	footmark: {
		list: api_root + 'api/goods-footmark/list',
		deletes: api_root + 'api/goods-footmark/delete',
	},
	payment: {
		doPay: api_root + 'api/payment/do-pay',
	},
	topic: {
		favorite: api_root + 'api/topic/favorite',
		type: api_root + 'api/topic/type',
		list: api_root + 'api/topic/list',
		detail: api_root + 'api/topic/detail',
	},
	wechat: {
		jssdk_config: api_root + 'api/wechat/jssdk-config'
	},
	express: {
		query: api_root + 'api/express/query'
	},
	income: {
		list: api_root + 'api/income/list'
	},
	district: {
		town_list: api_root + 'district/town-list'
	},
	live: {
		// getlive: api_root +  'api/live/get-access-token',
		getlive: api_root + 'api/live/get-live'
	},
	moreShop: {
		getMchBaseInfo: api_root + 'plugin/mch/api/base/info',
		subAccountMchList: api_root + 'plugin/mch/api/user-sub-account/mch-list',
		addSubAccount: api_root + 'plugin/mch/api/sub-account/add',
		subAccountList: api_root + 'plugin/mch/api/sub-account/list',
		deleteSubAccount: api_root + 'plugin/mch/api/sub-account/delete',
		apply: api_root + 'mch/api/mch-apply',
		getmchscats: api_root + 'mch/api/get-mchs-cats',
		getmchs: api_root + 'mch/api/get-mchs',
		getmchgods: api_root + 'mch/api/get-mch-goods',
		getmchgodscats: api_root + 'mch/api/get-mch-goods-cats',
		getmchstore: api_root + 'mch/api/get-mch-store',
		getexpenseMessage: api_root + 'api/order/consume-verification-qrcode',
		getpayCode: api_root + 'mch/api/checkout-order/create',
		verification: api_root + 'mch/api/consume-verification-info/use',
		verificationinfo: api_root + 'api/order/consume-verification-info',
		getpayMessage: api_root + 'mch/api/checkout-order/info',
		getpayINfo: api_root + 'mch/api/checkout-order/to-pay',
		getaccountList: api_root + 'mch/api/account-log/list',
		checkOrder: api_root + 'mch/api/checkout-order/qrcode',
		creatCheckOrder: api_root + 'mch/api/checkout-order/create',
		sharePoster: api_root + 'mch/api/share/poster',
		getCity: api_root + "district/common",
		merchanttype: api_root + "mch/api/mch-apply-more/merchant-type",
		merchantidcard: api_root + "mch/api/mch-apply-more/lawyer-info",
		merchantsettle: api_root + "mch/api/mch-apply-more/settle-info",
		progress: api_root + "mch/api/mch-apply-more/query-status",
		getTExt: api_root + "mch/api/mch-apply/setting",
		alipay: api_root + "api/efps-pay/alipay",
		wechatpay: api_root + "api/efps-pay/wechat",
		bswechatpay: api_root + "api/efps-pay/bswechat",
		balancepay: api_root + "api/efps-pay/balance",
		setShopmessage: api_root + "mch/api/mch-set/set-info",
		setShoppsaa: api_root + "mch/api/mch-set/set-account",
		setShopLogo: api_root + "mch/api/mch-set/set-pics",
		getaccountInfo: api_root + "mch/api/account/info",
		setwithdrawpwd: api_root + "mch/api/account/set-withdraw-pwd",
		paywithdraw: api_root + "mch/api/account/withdraw",
		settleMessage: api_root + "mch/api/account/set-settle-info",
		getagreement: api_root + "api/user/register-agree",
		gethotgoods: api_root + "plugin/baopin/api/goods/search",
		getpreviewOrder: api_root + "api/order/do-submit-order",
		getOrderList: api_root + "api/order/to-submit-order",
		getOrdercode: api_root + "api/order/clerk-qr-code",
		getOrdercodestatus: api_root + "api/order/query-clerk-status",
		getservice: api_root + "api/mall/ke-fu-link",
		getshopList: api_root + "plugin/baopin/api/order/closest-store",
		getresults: api_root + "api/order/order-clerk",
		getclerkList: api_root + "api/order/order-clerk-log",
		getclickNum: api_root + "plugin/mch/api/mch/add-visit",
		changeStatus: api_root + "mch/api/mch-apply-more/up-status",
		getBindUser: api_root + "api/share/inviter-info",
		validationPhone: api_root + "mch/api/mch-bind-mobile/verify",
		updatePhone: api_root + "mch/api/mch-bind-mobile/update",
		getbouns: api_root + "api/boss/bonus-details",
		getAudit: api_root + "api/shop-examine/shop-list",
		getexamineDetail: api_root + "api/shop-examine/details",
		saveMessage: api_root + "api/shop-examine/details",
		withdrawal_detail: api_root + "mch/api/withdrawal-details/mch-all-list",
		getcountList: api_root + "plugin/mch/api/price-log/list",






		getcategorylist: api_root + "plugin/mch/api/category/list",
		getlocationcity: api_root + "plugin/mch/api/mch-region/get-city-lower",
		getshoplistall: api_root + "plugin/mch/api/store/list",
		getshopnewdetail: api_root + "plugin/mch/api/store/detail",
		gethotListnew: api_root + "plugin/mch/api/goods/recommand-list",
		getgoodsnew: api_root + "plugin/mch/api/goods/list",
		downloadCode: api_root + "mch/api/checkout-order/synthetic-qr-code ",


		getHcodedetail: api_root + "api/clerkCenter/clerk/sweep-jump",


		getlifeOrderH: api_root + "mch/api/order/giftpacks",
		getcollectionlist: api_root + "mch/api/order/bill",
	},
	hotel: {
		getrecommended: api_root + 'plugin/hotel/api/hotel/simple-list',
		gethoteldetail: api_root + 'plugin/hotel/api/hotel/detail',
		searchhotel: api_root + 'plugin/hotel/api/search/prepare',
		searchfilter: api_root + 'plugin/hotel/api/search/filter',
		getpreviewOrder: api_root + 'plugin/hotel/api/order/preview',
		generateOrder: api_root + 'plugin/hotel/api/order/submit',
		getOrderList: api_root + 'plugin/hotel/api/user-center/order-list',
		cancleOrder: api_root + 'plugin/hotel/api/user-center/cancel-order',
		getOrderdetail: api_root + 'plugin/hotel/api/user-center/order-detail',
		isrefund: api_root + 'plugin/hotel/api/user-center/order-refundable',
		applyrefund: api_root + 'plugin/hotel/api/user-center/order-refund-apply',
		hotelPay: api_root + 'plugin/hotel/api/order/integral-direct-pay',
		getorderStatus: api_root + 'plugin/hotel/api/order/query-status',
		getpaywaite: api_root + 'plugin/hotel/api/order/pay-prepare',


		getcity: api_root + 'plugin/hotel/api/hotel/hotel-info',
		getcitylist: api_root + 'plugin/hotel/api/hotel/city-list',
		getimgList: api_root + 'plugin/hotel/api/hotel/hotel-diagram',
		editeroom: api_root + 'plugin/hotel/api/user-center/save-order-resident',
	},
	merchants: {
		applyEasy: api_root + 'plugin/mch/api/apply/easy',
		Fillbasic: api_root + 'plugin/mch/api/apply/basic',
		Filllicense: api_root + 'plugin/mch/api/apply/license',
		getapplyInfo: api_root + 'plugin/mch/api/apply/info',
		applyreset: api_root + 'plugin/mch/api/apply/reset',
		groupItemList: api_root + 'mch/api/group/item-list',
		grouprefuseItemList: api_root + 'mch/api/group/refuse-list',
		groupAddItem: api_root + 'mch/api/group/add-item',
	},
	morecredit: {
		creditOrder: api_root + 'plugin/addcredit/api/phone-bill/prepaid-order-submit',
		creditpay: api_root + 'plugin/addcredit/api/phone-bill/prepaid-refill',
		creditStatus: api_root + 'plugin/addcredit/api/phone-bill/recharge-record',
		creditOrderStatus: api_root + 'plugin/addcredit/api/phone-bill/query-order-status',
		paypreiew: api_root + 'plugin/addcredit/api/phone-bill/pay-prepare',
	},
	package: {
		getpackageList: api_root + 'plugin/giftpacks/api/giftpacks/list',
		packageListitem: api_root + 'plugin/giftpacks/api/giftpacks/item-list',
		getPackageDetailShare: api_root + 'plugin/giftpacks/api/giftpacks/detail-share',
		getPackageGroupDetailShare: api_root + 'plugin/giftpacks/api/giftpacks/group-detail-share',
		getgroupList: api_root + 'plugin/giftpacks/api/giftpacks/group-list',
		packageDetail: api_root + 'plugin/giftpacks/api/giftpacks/detail',
		previewOrder: api_root + 'plugin/giftpacks/api/order/preview',
		productOrder: api_root + 'plugin/giftpacks/api/order/submit',
		payMoney: api_root + 'api/payCenter/integral-pay/giftpacks',
		createspell: api_root + 'plugin/giftpacks/api/group/new-group',
		payspellMoney: api_root + 'api/payCenter/integral-pay/giftpacks-group',
		espellDetail: api_root + 'plugin/giftpacks/api/giftpacks/group-detail',
		joinespell: api_root + 'plugin/giftpacks/api/group/join',
		orderList: api_root + 'plugin/giftpacks/api/order/list',
		getorderDetail: api_root + 'plugin/giftpacks/api/order/detail',
		getserviceList: api_root + 'plugin/giftpacks/api/order/pack-item-list',
		generateQRcode: api_root + 'plugin/giftpacks/api/order/clerk-qr-code',
		getitemdetail: api_root + 'plugin/giftpacks/api/order/item-detail',
		paybalance: api_root + 'api/payCenter/balance-pay/giftpacks',
		paywechatcreated: api_root + 'api/payCenter/payment-order-prepare/giftpacks',
		paywechat: api_root + 'api/payCenter/efps-wechat-pay/giftpacks',


		payMoneybag: api_root + 'api/payCenter/integral-pay/giftpacks-group',
		paybalancebag: api_root + 'api/payCenter/balance-pay/giftpacks-group',
		paywechatcreatedbag: api_root + 'api/payCenter/payment-order-prepare/giftpacks-group',
		paywechatbag: api_root + 'api/payCenter/efps-wechat-pay/giftpacks-group',

	},
	oil: {
		getoilproduct: api_root + 'plugin/oil/api/product/list',
		getoilpreview: api_root + 'plugin/oil/api/order/submit-preview',
		submite: api_root + 'plugin/oil/api/order/do-submit',
		getpayInfo: api_root + 'plugin/oil/api/order/pay-prepare',
		getpayInfo: api_root + 'plugin/oil/api/order/pay-prepare',
		getoilOrderlist: api_root + 'plugin/oil/api/order/list',
		getoilOrderdetail: api_root + 'plugin/oil/api/order/detail',
		getoilcode: api_root + 'plugin/oil/api/order/use',
	},
	seckill: {
		getseckillList: api_root + 'plugin/seckill/api/seckill-list',
	}
};
module.exports = api;
