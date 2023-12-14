// #ifdef APP-PLUS
var bridge = uni.requireNativePlugin("UniBridge");
// #endif

// #ifdef (H5 || MP)
var bridge = {
	getVersion: function(param, fn){
		fn.call(this, {platform: 'android', version_code: 1, version_name: 'v1.0.0'});
	}
};
// #endif


module.exports = bridge;