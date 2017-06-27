<?php
define('IN_AJAX',true);
@header('Content-Type: text/html; charset=utf-8');
!defined('IN_MYMPS') && exit('ACCESS DENIED!');
require_once 'function.php';
$payr['payuser']=trim($payr['payuser']);
$payr['paykey']=trim($payr['paykey']);
$payr['appid']=trim($payr['appid']);
$payr['appsecret']=trim($payr['appkey']);
$order_no = $ddno ? $ddno : getOrderNo();//订单编号

session_start();
$url = 'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$params['wx_appid'] = $payr['appid'];
if (!$_GET['code']) {
    if (!$_SESSION['openid'] || !$_SESSION['access_token']) {
        $request_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $params['wx_appid'] . '&redirect_uri=' . urlencode($url) . '&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
        header("location:" . $request_url); die;
    }
} else {
    $json = getAccessTokenByCode($_GET['code'],$payr['appid'],$payr['appsecret']);
    $_SESSION['openid'] = $json['openid'];
	$_SESSION['access_token'] = $json['access_token'];
}

$params = array(
    'orderno' => $order_no,
    'money' => $money
);

ToPayMoney($money,$order_no,$uid,$s_uid,'wxpay');

$config['title'] = $charset == 'gbk' ? iconv('gbk','utf-8',$productname) : $productname;
$config['code'] = $params['orderno'];
$config['money'] = $params['money'] * 100;
$config['NotifyUrl'] = $PayReturnUrlQz.'/include/payment/wxpay/notify.php';
$config['openid'] = $_SESSION['openid'];
$config['appid'] = $payr['appid'];
$config['appsecret'] = $payr['appsecret'];
$config['payuser'] = $payr['payuser'];
$config['paykey'] = $payr['paykey'];

require_once 'wechat_jspay.php';
$weixinpay = new wechat_jspay($config);
$params = $result = $weixinpay->sendPay();
if ($result === false) {
    exit('支付请求过程出错');
}
?>
<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>微信支付</title>

    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="template/css/weui.min.css">
    <script type="text/javascript">

        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest', {
                    "appId"    : "<?=$params['appId']?>",     //公众号名称，由商户传入
                    "timeStamp": "<?=$params['timeStamp']?>",         //时间戳，自1970年以来的秒数
                    "nonceStr" : "<?=$params['nonceStr']?>", //随机串
                    "package"  : "<?=$params['package']?>",
                    "signType" : "<?=$params['signType']?>",         //微信签名方式:
                    "paySign"  : "<?=$params['paySign']?>" //微信签名
                },
                function(res) {
                    if (res.err_msg == "get_brand_wcpay_request:ok") {
                        location.href = 'index.php?mod=member';
                    } else if (res.err_msg == "get_brand_wcpay_request:fail") {
                        alert('支付失败');
                    }
                }
            );
        }

        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }

    </script>
</head>
<body ontouchstart>

<div class="weui_msg">
    <div class="weui_text_area" style="margin-top: 5em;">
        <div class="hd">
            <h1 class="page_title"><?=$money?> 元</h1>
            <p class="weui_msg_desc"><?=$config['title']?></p>
        </div>
    </div>
    <div class="weui_opr_area" style="margin-top: 4em;">
        <p class="weui_btn_area">
            <a href="javascript:void(0)" onclick="callpay()" class="weui_btn weui_btn_primary">立即支付</a>
            <a href="javascript:history.back();" class="weui_btn weui_btn_default">暂不支付</a>
        </p>
    </div>
</div>

</body>
</html>