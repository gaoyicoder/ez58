<?php 
define('IN_MYMPS',true);
include dirname(__FILE__)."../../global.php";
include MYMPS_DATA.'/config.php';
include MYMPS_DATA.'/config.db.php';
$actionkey = $timestamp.rand(0,100);
$url = base64_encode($mymps_global['SiteUrl']."/include/wxlogin/wxlogin.php?actionkey=".$actionkey);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title><?=$mymps_global['SiteName']?> - 会员登陆</title>
<script>var nowdomain="<?=$mymps_global['SiteUrl']?>";</script>
<style type="text/css">body{margin:0;padding:0;font-size:14px;background-color:#333;color:#fff;text-align:center;font-family:"微软雅黑"}.wrapper h3{font-size:20px;font-weight:400;margin:50px 0 20px}.wrapper img{background-color:#FFF;padding:0;vertical-align:top;display:block;margin:0 auto}.impowerBox{padding:10px 0;background-color:#232323;border-radius:100px;-moz-border-radius:100px;-webkit-border-radius:100px;box-shadow:inset 0 5px 10px -5px #191919,0 1px 0 0 #444;-moz-box-shadow:inset 0 5px 10px -5px #191919,0 1px 0 0 #444;-webkit-box-shadow:inset 0 5px 10px -5px #191919,0 1px 0 0 #444;width:259px;margin:20px auto 0}.txtBox{color:#ccc;font-size:14px;margin-bottom:12px}</style>
<body>
<div class="wrapper">
	<h3>微信登录</h3>
	<div class="txtBox">请使用微信扫描二维码登录&ldquo;<?=$mymps_global['SiteName']?>&rdquo;</div>
	<img src="<?=$mymps_global['SiteUrl']?>/qrcode.php?size=6&url=<?=$url?>" id="wx_default_img" alt="" />
	<div class="impowerBox" id="wx_default_msg">等待扫描</div>
</div>
</body>
<script src="<?=$mymps_global['SiteUrl']?>/template/default/js/jquery-1.10.2.min.js"></script>
<script>
function loadwxlogin(){
	var url = nowdomain+'/javascript.php?part=chk_wxlogin&actionkey=<?=$actionkey?>';
	$.get(url,function(data){
		if(data == 1){
			$('#wx_default_msg').html('登录成功，即将跳转');
			window.location.href = '<?=$mymps_global['SiteUrl']?>/';
		}else{
			$('#wx_default_msg').html('等待扫描');
		}
		window.setTimeout(function(){loadwxlogin();},1500);
	});
}
loadwxlogin();
</script>
</body>
</html>