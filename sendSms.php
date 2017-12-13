<?php
/*
 * 此文件用于验证短信服务API接口，供开发时参考
 * 执行验证前请确保文件为utf-8编码，并替换相应参数为您自己的信息，并取消相关调用的注释
 * 建议验证前先执行Test.php验证PHP环境
 *
 * 2017/11/30
 */


namespace Aliyun\DySDKLite\Sms;
define('WAP',true);
define('CURSCRIPT','wap');
define('IN_MYMPS', true);
define('IN_SMT', true);
require_once "include/SignatureHelper.php";
require_once 'include/global.php';
require_once MYMPS_DATA."/config.php";
require_once MYMPS_DATA.'/config.db.php';

use Aliyun\DySDKLite\SignatureHelper;

function mgetcookie($var){
    global $cookiepre;
    $cookie_pre  = $cookiepre.'_';
    $tvar = $cookie_pre.$var;
    return $_COOKIE[$tvar];
}

function msetcookie($var,$val,$life=0){
    global $cookiepre,$cookiedomain,$cookiepath,$timestamp;
    $cookie_pre  = $cookiepre.'_';
    $cookie_path = $cookiepath ? $cookiepath : '/';
    $life = $life ? $life : 3600;
    $life = $timestamp+$life;
    setcookie($cookie_pre.$var,$val,$life,$cookie_path,$cookiedomain);
}

function sendSms($mobile) {

    $params = array ();

    // *** 需用户填写部分 ***

    // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
    $config_sms = require_once("data/config.sms.php");
    $accessKeyId = $config_sms['accessKeyId'];
    $accessKeySecret = $config_sms["accessKeySecret"];
    $code  = rand(100000,999999);

    // fixme 必填: 短信接收号码
    $params["PhoneNumbers"] = $mobile;

    // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
    $params["SignName"] = "嗨帮365";

    // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
    $params["TemplateCode"] = "SMS_116581958";

    // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
    $params['TemplateParam'] = Array (
        "code" => $code,
    );

    // fixme 可选: 设置发送短信流水号
    $params['OutId'] = "1";

    // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
//    $params['SmsUpExtendCode'] = "1234567";


    // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
    if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
        $params["TemplateParam"] = json_encode($params["TemplateParam"]);
    }

    // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
    $helper = new SignatureHelper();

    // 此处可能会抛出异常，注意catch
    try{
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        );
    }catch (\Exception $e) {
        throw $e;
    }

    $timestamp = time();
    $time = $timestamp + 300;//10分钟
    session_start();
    $_SESSION['smscode']=array('phonenum'=>$mobile,'code'=>$code,'time'=>$time);
    return $content;
}

ini_set("display_errors", "on"); // 显示错误提示，仅用于测试时排查问题
set_time_limit(0); // 防止脚本超时，仅用于测试使用，生产环境请按实际情况设置
header("Content-Type: text/plain; charset=utf-8"); // 输出为utf-8的文本格式，仅用于测试

$result = false;
$mobile = $_REQUEST['mobile'];
if ($mobile) {
    $content = sendSms($mobile);
    if($content) {
        $result = true;
    }
}
if ($result) {
    echo json_encode("success");
} else {
    echo json_encode("fail");
}

