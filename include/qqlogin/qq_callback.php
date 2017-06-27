<?php 
error_reporting(E_ALL^E_NOTICE);
@header("Content-Type: text/html; charset=utf-8");
define('IN_MYMPS',true);
define('QQLOGINDIR',dirname(__FILE__));
@define('MYMPS_ROOT', ereg_replace("[/\\]{1,}",'/',substr(QQLOGINDIR,0,-15)));
define('MYMPS_DATA',MYMPS_ROOT.'/data');
define('MYMPS_INC',MYMPS_ROOT.'include');
require_once MYMPS_DATA.'/config.php';
if(function_exists('date_default_timezone_set')) date_default_timezone_set('Hongkong');
require_once MYMPS_DATA.'/config.db.php';
require_once MYMPS_INC.'/db.class.php';
require_once MYMPS_INC.'/common.fun.php';
require_once MYMPS_INC.'/openlogin.fun.php';
require_once MYMPS_INC.'/cache.fun.php';
require_once MYMPS_ROOT.'/member/include/common.func.php';

$timestamp = time();
if(!pcclient()){ 
	$_GET['mod'] = 'm';
} else{
	$_GET['mod'] = 'pc';
}
require_once("session.php");
require_once("config.php");

//QQ登录成功后的回调地址,主要保存access token
qq_callback();

//获取用户标示id
get_openid();

$openid = $_SESSION['openid'];
if(empty($openid)) write_msg('登录失败，请返回重新登陆！',$mymps_global[SiteUrl].'/include/qqlogin/qq_login.php');

$row = NULL;
$row = $db -> getRow("SELECT userid,userpwd FROM `{$db_mymps}member` WHERE openid = '$openid'");

//登录
require_once MYMPS_INC."/member.class.php";
if(is_array($row)){
	$userid = $row['userid'];
	$userpwd = $row['userpwd'];
	$db -> query("UPDATE `{$db_mymps}member` SET logintime='$timestamp' WHERE userid = '$userid' ");
	if(PASSPORT_TYPE == 'phpwind'){
		require MYMPS_ROOT.'/pw_client/uc_client.php';
		$user_login = uc_user_login($userid,$userpwd,0);
		$member_log -> in($userid,$userpwd,'off','noredirect');
		echo $user_login['synlogin'];
	} elseif(PASSPORT_TYPE == 'ucenter'){
		$member_log -> in($userid,$userpwd,'off','noredirect');
		require MYMPS_ROOT.'/uc_client/client.php';
		list($uid, $username, $password, $email) = uc_user_login($userid, $userpwd);
		echo uc_user_synlogin($uid);
	} else {
		$member_log -> in($userid,$userpwd,'off','noredirect');
	}
	
	if(!pcclient() && $view != 'pc'){
		echo mymps_goto($mymps_global['SiteUrl'].'/m/index.php?mod=member');
	} else{
		echo mymps_goto($mymps_global['SiteUrl'].'/member/index.php');
	}
} else{
	
	$qquser = get_qquser_info();

	//$_SESSION['nickname'] = $charset == 'gbk' ? iconv("UTF-8", "GBK",  $qquser['nickname']) :  $qquser['nickname'];
	//$_SESSION['nickname'] = iconv("UTF-8", "GBK",  $qquser['nickname']);
	$prelogo = $logo = $qquser['figureurl_qq_2'];
	$userid = 'qq_'.$timestamp.rand(0,100);
	$userpwd = md5(random());
	//$nickname = $_SESSION['nickname'];
	
	if($db->getOne("SELECT COUNT(id) FROM `{$db_mymps}member` WHERE userid = '$userid'") < 1){
		
		$email = $userid.'@mymps.com.cn';
		if(PASSPORT_TYPE == 'phpwind'){
			require MYMPS_ROOT.'/pw_client/uc_client.php';
			$checkuser = uc_check_username($userid);
			if($checkuser == -2){
				write_msg('用户名重复，请换一个用户名注册');
			} elseif($checkuser == -1){
				write_msg('用户名不符合规范，请换一个用户名注册');
			} elseif($checkuser == 1){
			
			} else {
				write_msg('未知错误，请换一个用户名注册');
			}
			
			if($email){
				$checkemail = uc_check_email($email);
				$checkemail == -3 && write_msg('Email格式不正确，请填写正确的Email');
				$checkemail == -4 && write_msg('该Email地址已重复，请更换一个Email地址');
			}
			
			uc_user_register($userid,md5($userpwd),$email);
			//登录
			$user_login = uc_user_login($userid,$userpwd,0);
			echo $user_login['synlogin'];
			
		} elseif(PASSPORT_TYPE == 'ucenter'){
			require MYMPS_ROOT.'/uc_client/client.php';
			if($activation && ($activeuser = uc_get_user($activation))) {
				list($uid, $userid) = $activeuser;
			} else {
				$user = $db -> getRow("SELECT id,userid FROM `{$db_mymps}member` WHERE userid = '$userid'");
				if(uc_get_user($userid) && !$user['userid']) {
					write_msg("该用户无需注册，请重新登录",$mymps_global[SiteUrl]."/".$mymps_global['cfg_member_logfile']);
				}
				$uid = uc_user_register($userid,$userpwd, $email);
				if($uid <= 0) {
					if($uid == -1) {
						write_msg('用户名不合法');
					} elseif($uid == -2) {
						write_msg( '包含不允许注册的词语');
					} elseif($uid == -3) {
						write_msg( '用户名已经存在');
					} elseif($uid == -4) {
						write_msg( 'Email 格式有误');
					} elseif($uid == -5) {
						write_msg( 'Email 不允许注册');
					} elseif($uid == -6) {
						write_msg( '该 Email 已经被注册');
					} else {
						write_msg( '未定义');
					}
				} else {
					$userid  = trim($userid);
					$userpwd = trim($userpwd);
					$email 	 = trim($email);
				}
				
				//登录
				list($uid, $username, $password, $email) = uc_user_login($userid, $userpwd);
				echo uc_user_synlogin($uid);
			}
			
		}
		
		member_reg($userid,$userpwd,'','','',$openid,'',1,'',$logo,$prelogo);
	}
	$member_log -> in($userid,$userpwd,'off','noredirect');
	if(!pcclient() && $view != 'pc'){
		echo mymps_goto($mymps_global['SiteUrl'].'/m/index.php?mod=member');
	} else{
		echo mymps_goto($mymps_global['SiteUrl'].'/member/index.php');
	}
}

function get_qquser_info()
{
    $get_user_info = "https://graph.qq.com/user/get_user_info?"
        . "access_token=" . $_SESSION['access_token']
        . "&oauth_consumer_key=" . $_SESSION["appid"]
        . "&openid=" . $_SESSION["openid"]
        . "&format=json";

    $info = get_url_contents($get_user_info);
    $arr = json_decode($info, true);

    return $arr;
}

function qq_callback()
{
    //debug
    //print_r($_REQUEST);
    //print_r($_SESSION);

    if($_REQUEST['state'] == $_SESSION['state']) //csrf
    {
        $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
            . "client_id=" . $_SESSION["appid"]. "&redirect_uri=" . urlencode($_SESSION["callback"])
            . "&client_secret=" . $_SESSION["appkey"]. "&code=" . $_REQUEST["code"];
		
        $response = get_url_contents($token_url);
        if (strpos($response, "callback") !== false)
        {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);
            if (isset($msg->error))
            {
                echo "<h3>error:</h3>" . $msg->error;
                echo "<h3>msg  :</h3>" . $msg->error_description;
                exit;
            }
        }

        $params = array();
        parse_str($response, $params);

        //debug
        //print_r($params);
		//exit;

        //set access token to session
        $_SESSION["access_token"] = $params["access_token"];

    }
    else 
    {
        echo("The state does not match. You may be a victim of CSRF.");
    }
}

function get_openid()
{
    $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" 
        . $_SESSION['access_token'];
		
	$str  = get_url_contents($graph_url);
	
    if (strpos($str, "callback") !== false)
    {
        $lpos = strpos($str, "(");
        $rpos = strrpos($str, ")");
        $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
    }
	
    $user = json_decode($str);
    if (isset($user->error))
    {
        echo "<h3>error:</h3>" . $user->error;
        echo "<h3>msg  :</h3>" . $user->error_description;
        exit;
    }

    //debug
    //echo("Hello " . $user->openid);

    //set openid to session
    $_SESSION["openid"] = $user->openid;
}


?>