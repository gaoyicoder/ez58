<?php
define('IN_MYMPS', true);
define('IN_ADMIN',true);
define('CURSCRIPT','payend');

require_once dirname(__FILE__).'/../../../include/global.php';
require_once MYMPS_DATA.'/config.php';
require_once MYMPS_DATA.'/config.db.php';
require_once MYMPS_INC.'/db.class.php';
require_once MYMPS_INC."/member.class.php";
if(!$member_log->chk_in()) write_msg("",$mymps_global['SiteUrl']."/".$mymps_global['cfg_member_logfile']."?url=".urlencode(GetUrl()));

$editor=1;

if(!mgetcookie('checkpaysession')){
	write_msg('非法操作！','olmsg');
}

//操作事件
$pay_type = mgetcookie('pay_type');
/*
if($pay_type=='PayToMoney')//金币充值
{
	
}else{
	write_msg('您来自的链接不存在','olmsg');
}*/

$paytype='alipay';
$payr=$db->getRow("SELECT * FROM {$db_mymps}payapi WHERE paytype='$paytype'");
$bargainor_id=$payr['payuser'];//商户号
$paykey=$payr['paykey'];//密钥

$seller_email=$payr['payemail'];//卖家支付宝帐户

//----------------------------------------------返回信息

if(!empty($_POST)){
	foreach($_POST as $key => $data){
		$_GET[$key]=$data;
	}
}

$get_seller_email=rawurldecode($_GET['seller_email']);

//支付验证
ksort($_GET);
reset($_GET);

/*
$sign='';
foreach($_GET AS $key=>$val){
	if($key!='sign'&&$key!='sign_type'&&$key!='code'){
		$sign.="$key=$val&";
	}
}

$sign=md5(substr($sign,0,-1).$paykey);

if($sign!=$_GET['sign']){
	write_msg('验证MD5签名失败.','olmsg');
}
*/

if($_GET['trade_status']=="TRADE_FINISHED" || $_GET['trade_status']== "TRADE_SUCCESS" || $_GET['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS'|| $_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS'){
	include MYMPS_INC.'/pay.fun.php';
	
	$orderid=$_GET['trade_no'];	//支付订单
	$ddno=$_GET['out_trade_no'];	//网站的订单号
	$money=$_GET['total_fee'];
	
	if($_GET['trade_status']=="TRADE_FINISHED"){
		$paybz='支付完成';
	} elseif($_GET['trade_status']=="TRADE_SUCCESS"){
		$paybz='支付成功';
	} elseif($_GET['trade_status']=="WAIT_BUYER_CONFIRM_GOODS"){
		$paybz='等待确认收货';
	} elseif($_GET['trade_status']=="WAIT_SELLER_SEND_GOODS"){
		$paybz='等待发货';
		//构造要请求的参数数组，无需改动
		$invoice_no = '1';
		$logistics_name = '1';
		$parameter = array(
				"_input_charset"	=> trim(strtolower($charset)),
				"invoice_no"	=> $invoice_no,//物流单号
				"logistics_name"	=> $logistics_name,//物流公司名称
				"partner" => trim($bargainor_id),
				"service" => "send_goods_confirm_by_platform",
				"trade_no"	=> $orderid,
				"transport_type"	=> 'EXPRESS',//物流属性类型
				
		);
		ksort($parameter);
		reset($parameter);
		$param='';
		$sign='';
		
		foreach($parameter AS $key => $val){
			if(strlen($val)==0){
				continue;
			}
			$param.="$key=".urlencode($val)."&";
			$sign.="$key=$val&";
		}
		$param=substr($param,0,-1);
		
		$sign=md5(substr($sign,0,-1).$paykey);

		//$gotopayurl ='https://www.alipay.com/cooperate/gateway.do?'.$param.'&sign='.$sign.'&sign_type='.$sign_type;
		$gotopayurl = 'https://mapi.alipay.com/gateway.do?'.$param.'&sign='.$sign.'&sign_type=MD5';
		$parameter = array_merge($parameter,array('sign_type'=>'MD5'));
		$html_text = getHttpResponsePOST($gotopayurl,getcwd().'\\cacert.pem',$parameter,trim(strtolower($charset)));
		$paybz='充值成功';
		//注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
		//解析XML
		/*
		$doc = new DOMDocument();
		$doc->loadXML($html_text);
		if( ! empty($doc->getElementsByTagName( "alipay" )->item(0)->nodeValue) ) {
			$alipay = $doc->getElementsByTagName( "alipay" )->item(0)->nodeValue;
			echo charsetEncode($alipay,'gbk','utf-8');
			exit;
		}
		*/
		UpdatePayRecord($ddno,$paybz);//修改支付状态，等待确认收货
		write_msg("您已成功支付，请前往支付宝确认收货完成金币充值！",$mymps_global['SiteUrl']."/member/index.php?m=pay&ac=record");
	}

	UpdatePayRecord($ddno,$paybz);//修改支付状态
	write_msg("您已成功充值 ".($money*$mymps_global['cfg_coin_fee'])." 个金币",$mymps_global['SiteUrl']."/member/index.php?m=pay&ac=record");
	//PayApiPayMoney($money,$paybz,$orderid,$uid,$s_uid,$paytype);
}

is_object($db) && $db -> Close();
$mymps_global = NULL;

/**
 * 远程获取数据，POST模式
 * 注意：
 * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
 * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
 * @param $url 指定URL完整路径地址
 * @param $cacert_url 指定当前工作目录绝对路径
 * @param $para 请求的数据
 * @param $input_charset 编码格式。默认值：空值
 * return 远程输出的数据
 */
function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '') {

	if (trim($input_charset) != '') {
		$url = $url."_input_charset=".$input_charset;
	}
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
	curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
	curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
	curl_setopt($curl,CURLOPT_POST,true); // post传输数据
	curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
	$responseText = curl_exec($curl);
	//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
	curl_close($curl);
	
	return $responseText;
}

/**
 * 远程获取数据，GET模式
 * 注意：
 * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
 * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
 * @param $url 指定URL完整路径地址
 * @param $cacert_url 指定当前工作目录绝对路径
 * return 远程输出的数据
 */
function getHttpResponseGET($url,$cacert_url) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
	curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
	$responseText = curl_exec($curl);
	//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
	curl_close($curl);
	
	return $responseText;
}

/**
 * 实现多种字符编码方式
 * @param $input 需要编码的字符串
 * @param $_output_charset 输出的编码格式
 * @param $_input_charset 输入的编码格式
 * return 编码后的字符串
 */
function charsetEncode($input,$_output_charset ,$_input_charset) {
	$output = "";
	if(!isset($_output_charset) )$_output_charset  = $_input_charset;
	if($_input_charset == $_output_charset || $input ==null ) {
		$output = $input;
	} elseif (function_exists("mb_convert_encoding")) {
		$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
	} elseif(function_exists("iconv")) {
		$output = iconv($_input_charset,$_output_charset,$input);
	} else die("sorry, you have no libs support for charset change.");
	return $output;
}
/**
 * 实现多种字符解码方式
 * @param $input 需要解码的字符串
 * @param $_output_charset 输出的解码格式
 * @param $_input_charset 输入的解码格式
 * return 解码后的字符串
 */
function charsetDecode($input,$_input_charset ,$_output_charset) {
	$output = "";
	if(!isset($_input_charset) )$_input_charset  = $_input_charset ;
	if($_input_charset == $_output_charset || $input ==null ) {
		$output = $input;
	} elseif (function_exists("mb_convert_encoding")) {
		$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
	} elseif(function_exists("iconv")) {
		$output = iconv($_input_charset,$_output_charset,$input);
	} else die("sorry, you have no libs support for charset changes.");
	return $output;
}
?>