<?php
function send_regsms($sms_user,$sms_pwd,$phonenum,$sms_regtpl=''){
	global $db,$db_mymps,$mymps_global,$timestamp;
	if(!$phonenum || !is_mobile($phonenum)){return false;exit;}
	
	$code  = rand(100000,999999);
	$time = $timestamp + 600;//10分钟
	
	$cishu = mgetcookie('sendcishu');
	$cishu = $cishu ? $cishu : 0;
	if(mgetcookie('sendedsms') == 1 || $cishu > 100){
		 return false;
	}else{
		$cishu = $cishu + 1;
		//session_save_path(MYMPS_DATA.'/sessions');
		session_start();
		$_SESSION['smscode']=array('phonenum'=>$phonenum,'code'=>$code,'time'=>$time);
		//将验证码发送给手机
		msend_regsms($sms_user,$sms_pwd,$phonenum,$code,$sms_regtpl);
		msetcookie('sendedsms',1,115);//2分钟
		msetcookie('sendcishu',$cishu,24*3600);
		return true;
	}
}

function send_pwdsms($sms_user,$sms_pwd,$phonenum,$sms_pwdtpl=''){
	global $db,$db_mymps,$mymps_global,$timestamp;
	if(!$phonenum || !is_mobile($phonenum)) return false;
	
	$code  = rand(100000,999999);
	$time = $timestamp + 600;//10分钟
	
	$cishu = mgetcookie('sendcishu');
	$cishu = $cishu ? $cishu : 0;
	if(mgetcookie('sendedsms') == 1 || $cishu > 100){
		 return false;
	}else{
		$cishu = $cishu + 1;
		//session_save_path(MYMPS_DATA.'/sessions');
		session_start();
		$_SESSION['smscode']=array('phonenum'=>$phonenum,'code'=>$code,'time'=>$time);
		//将验证码发送给手机
		msend_pwdsms($sms_user,$sms_pwd,$phonenum,$code,$sms_pwdtpl);
		msetcookie('sendedsms',1,115);//2分钟
		msetcookie('sendcishu',$cishu,24*3600);
		return true;
	}
}

//写入短信发送记录
function write_sms_sendrecord($mobile,$sms_content,$status,$sms_service){
	global $timestamp,$db,$db_mymps;
	$db -> query("INSERT INTO `{$db_mymps}sms_sendlist` (mobile,sms_content,status,sendtime,sms_service)VALUES('$mobile','$sms_content','$status','$timestamp','$sms_service')");
}

function get_sms_config(){
	//获得邮箱服务器初始配置
	global $db,$db_mymps;
	static $res = NULL;
	if($res === NULL){
		$data = read_static_cache('sms_config');
		if($data == false){
			$re = $db -> query("SELECT * FROM `{$db_mymps}config` WHERE type='sms'");
			while($row = $db -> fetchRow($re)){
				$res[$row['description']] = $row['value'];
			}
			write_static_cache('sms_config', $res);
		} else {
			$res = $data;
		}
	}
	return $res;
}
?>