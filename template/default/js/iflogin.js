$('#foots').html('Powered by <a class="copyrights" href="http://www.mymps.com.cn" target="_blank">mymps</a> <a class="copyversions" href="http://bbs.mymps.com.cn">5.8SE UTF8</a>');
$('#gzh img').error(function() {
	$("#gzh").hide();
	$("#sjfw").show()
});
$.ajax({
    type : "get",
    url : current_domain+"/javascript.php?part=iflogin",
    dataType : "jsonp",
    jsonp: "callback",
    jsonpCallback:"success_jsonpCallback",
    success: function(json) {
		var prints;
		var url = encodeURIComponent(window.location.href);
		if (json.login=='success'){
			prints = ''+json.s_uid+'&nbsp;&middot;&nbsp;<a href="'+current_domain+'/member/index.php">用户中心</a> &middot; <a href="'+current_domain+'/'+current_logfile+'?mod=out&url='+url+'&cityid='+current_cityid+'" >退出</a>';
		}else if (json.login=="error"){
			if(json.qqlogin=="success"){
				prints = '<ul><a href="'+current_domain+'/include/qqlogin/qq_login.php" title="用QQ帐号登录">QQ登录</a></ul> <ul class="line"><u></u></ul>';	
			}else if(json.qqlogin=="error"){
				prints = '';	
			}
			if(json.wxlogin=="success"){
				prints += '<ul><a href="'+current_domain+'/include/wxlogin/wx_login.php" title="用微信扫一扫登录">微信登录</a></ul> <ul class="line"><u></u></ul>';	
			}else if(json.qqlogin=="error"){
				prints += '';
			}
			prints+='<ul><a href="'+current_domain+'/'+current_logfile+'?mod=login&url='+url+'&cityid='+current_cityid+'">登录</a></ul><ul class="line"><u></u></ul><ul><a href="'+current_domain+'/'+current_logfile+'?mod=register&cityid='+current_cityid+'">注册</a></ul>';
		}
		$('#iflogin').html(prints);
	}
});