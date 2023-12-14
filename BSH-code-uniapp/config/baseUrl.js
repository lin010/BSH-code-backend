// 正式服
let domainUrl = "http://www.myrj.com/";
// let domainUrl = "http://8.129.63.124";
let baseUrl = "";
let socketUrl = "";
if (process.env.NODE_ENV === 'development') {
	// 开发环境
	baseUrl = "http://localhost/";
	// baseUrl = "http://dev.mingyuanriji.cn/";
	// socketUrl = "ws://8.129.186.35:6001/";
} else if (process.env.NODE_ENV === 'production') {
	// 生产环境
	// baseUrl = "https://jx.shuzixiangdao.com/web/index.php/";
	// baseUrl = "https://dev.mingyuanriji.cn/web/index.php/";
	baseUrl = "https://www.mingyuanriji.cn/web/index.php/";
	// socketUrl = "ws://8.129.186.35:6001/";
}
const courtConfig = {
	//微信公众号APPID
	//publicAppId: "wxaead1c4fbee8a907",
	// publicAppId: "wx8227e203ac8c4555", //兰亭appid
	publicAppId: "wx284adb952f60747b", //兰婷appid
	//域名地址
	domainUrl:domainUrl,
	//请求接口
	baseUrl: baseUrl,
	//webSocket地址
	socketUrl: socketUrl,
	//平台名称
	platformName: "兰亭名品",
	//项目logo
	logoUrl: "http://yingmlife-1302693724.cos.ap-guangzhou.myqcloud.com/uploads/images/original/20201216/15262b999e48acc5891864e3f2463cb0.jpg",
	//页面分享配置
	share: {
		title: '兰亭名品',
		// #ifdef MP-WEIXIN
		path: '/pages/home/home', //小程序分享路径
		// #endif
		// #ifdef H5 || APP-PLUS
		//公众号||APP分享
		desc: "兰亭名品", // 分享描述
		link: "http://www.mingyuanriji.cn/h5/", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
		// link: "http://dev.mingyuanriji.cn/h5/", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
		imgUrl: "http://yingmlife-1302693724.cos.ap-guangzhou.myqcloud.com/uploads/images/original/20201216/15262b999e48acc5891864e3f2463cb0.jpg", // 分享图标
		// #endif
	}
};

export default Object.assign({}, courtConfig);
