<?php

//decode by QQ:270656184 http://www.yunlu99.com/
function getOrderNo()
{
	global $timestamp;
	return rand(11, 99) . $timestamp . rand(11, 99);
}
function WechatReturnSuccess()
{
	echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
	die;
}
function get_url_contents($_var_0)
{
	$_var_1 = curl_init();
	curl_setopt($_var_1, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($_var_1, CURLOPT_URL, $_var_0);
	curl_setopt($_var_1, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($_var_1, CURLOPT_SSL_VERIFYHOST, false);
	$_var_2 = curl_exec($_var_1);
	curl_close($_var_1);
	return $_var_2;
}
function getAccessTokenByCode($_var_3 = '', $_var_4 = '', $_var_5 = '')
{
	$_var_0 = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $_var_4 . '&secret=' . $_var_5 . '&code=' . $_var_3 . '&grant_type=authorization_code';
	return json_decode(get_url_contents($_var_0), true);
}
function getBaseAccessToken($_var_4 = '', $_var_5 = '')
{
	$_var_0 = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $_var_4 . '&secret=' . $_var_5;
	$_var_6 = json_decode(get_url_contents($_var_0), true);
	if (isset($_var_6['access_token']) && isset($_var_6['expires_in'])) {
		return $_var_6['access_token'];
	} else {
		return false;
	}
}