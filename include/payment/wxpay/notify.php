<?php
define('IN_MYMPS', true);
define('IN_ADMIN',true);
define('CURSCRIPT','notify');

require_once dirname(__FILE__).'/../../../include/global.php';
require_once MYMPS_DATA.'/config.php';
require_once MYMPS_DATA.'/config.db.php';
require_once MYMPS_INC.'/db.class.php';
include MYMPS_INC.'/pay.fun.php';
$payr = $db->getRow("SELECT * FROM {$db_mymps}payapi WHERE paytype='wxpay'");
$payr['paykey'] = trim($payr['paykey']);

require_once 'function.php';
$data = file_get_contents('php://input');
// 判断是否XML数据
$xml_parser = xml_parser_create();
if (! xml_parse($xml_parser, $data, true)) {
    exit();
}
xml_parser_free($xml_parser);

$xml = simplexml_load_string($data);
$param = array();
foreach ($xml as $key => $val) {
    if ($key != 'sign') {
        $param[$key] = $val;
    }
}

// 排序
ksort($param);
// 签名前拼接
$strArr = array();
foreach ($param as $key => $val) {
    array_push($strArr, $key . '=' . $val);
}
$string = implode('&', $strArr);
$sign = $string . '&key='.$payr['paykey'];
$sign = strtoupper(md5($sign));
// 校验签名
if ($sign == $xml->sign) {
    // 商户订单号
    $out_trade_no = $xml->out_trade_no;
    // 微信支付订单号
    $transaction_id = $xml->transaction_id;
    // 实际支付总金额
    $total_fee = intval($xml->total_fee) / 100;
	$paybz='支付成功';
	UpdatePayRecord($out_trade_no,$paybz);//修改支付状态
    WechatReturnSuccess();
}
?>