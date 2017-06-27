<?php

//decode by QQ:270656184 http://www.yunlu99.com/
define('WXLOGIN', 1);
$actionkey = isset($_GET['actionkey']) ? trim(htmlspecialchars($_GET['actionkey'])) : '';
$data = '';
@(include '../../data/caches/wxlogin.php');
$appid = $data['appid'];
$appsecret = $data['appsecret'];
$callback = $data['callback'] . '?actionkey=' . $actionkey;
$scope = 'snsapi_userinfo';
$state = 'mymps';
wx_login($appid, $scope, $state, $callback);
function wx_login($_var_0, $_var_1, $_var_2, $_var_3)
{
	$_var_4 = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $_var_0 . '&redirect_uri=' . urlencode($_var_3) . '&response_type=code' . '&scope=' . $_var_1 . '&state=' . $_var_2 . '&connect_redirect=1#wechat_redirect';
	header("Location:{$_var_4}");
}