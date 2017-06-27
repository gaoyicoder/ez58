<?php
(!defined('IN_ADMIN') || !defined('IN_MYMPS')) && exit('Access Denied');

$gd_info 		  = @gd_info();
$gd_version 	  = is_array($gd_info) ? $gd_info['GD Version'] : '<font color=red>不支持GD库</font>';
$cfg_if_tpledit   = ($mymps_mymps[cfg_if_tpledit]==0)?"<font color=green>关闭</font>":"<font color=red>开启</font>";
$if_del_install   = !is_file(MYMPS_ROOT."/install/index.php") ? "<font color=green>已删除</font>":"<font color=red>未删除</font>";
$Register_Globals = ini_get('Register_Globals')?'on':'off';
$Magic_Quotes_Gpc = MAGIC_QUOTES_GPC ? 'on' : 'off';
$expose_php		  = ini_get('expose_php') ? 'on' : 'off';
$eAccelerator	  = function_exists('eAccelerator_info') ? '支持 ' : '不支持';
$eAcceleratorr	  = function_exists('eAccelerator_info') ? eAccelerator_info() : array(); 
$eAccelerator	 .= $eAcceleratorr['version'];
$cur_dir		  = getcwdOL();
$cur_dir		  = $cur_dir == '/admin' ? '<font color=red title=不建议使用admin作为目录名>/admin</font>' : '<font color=green>'.$cur_dir.'</font>';
$latestbackup	  = $db -> getOne("SELECT value FROM `{$db_mymps}config` WHERE description = 'latestbackup' AND type = 'database'");
$parttime = round(($latestbackup > 0 ? ($timestamp - $latestbackup) : 0)/(3600*24));
if(!$latestbackup){
	$message = '<font color=red>您尚未备份过Mymps系统全部数据</font>';
} else {
	if($parttime > 13){
		$message = '<font color=red>您已经超过两周没有备份Mymps系统全部数据了</font>';
	} elseif($parttime == 0) {
		$message = '<font color=green>您今天已经备份过Mymps全部数据</font>';
	} else{
		$message = '您在 <font color=green>'.$parttime.'</font> 天前备份过mymps系统数据，上次备份：<font color=green>'.GetTime($latestbackup).'</font>';
	}
}

$message .= '，<a href="database.php?part=backup" style="text-decoration:underline">点此备份系统数据</a>';

$dsxx = mymps_count("information","WHERE info_level = '0'");
$dshy = mymps_count("member","WHERE status = '0'");

$welcome = array(
	'常用操作'=>'
		<div>
		<span><input value="审核信息('.$dsxx.')" onclick="location.href=\'information.php?info_level=0\'" type="button" class="gray large"></span>
		<div><span><input value="审核会员('.$dshy.')" onclick="location.href=\'member.php?part=verify&do_action=default\'" type="button" class="gray large"></span>
		<span><input value="发布信息" onclick="window.open(\'../'.$mymps_global[cfg_postfile].'\'); target=\'_blank\'" type="button" class="gray large"></span><span><input value="清除缓存" onclick="location.href=\'config.php?part=cache_sys&return_url='.urlencode("index.php?do=manage&part=right").'\'" type="button" class="gray large"></span><span><input value="系统优化" onclick="location.href=\'optimise.php\'" type="button" class="gray large"></span></div>
			',
	'数据统计'=>$mymps_count_str,
	'快捷操作'=>'
		<div class="mainnav">
		<ul>
		<li><a href="'.$mymps_global[SiteUrl].'" target="_blank"><img border="0" src="template/images/default/home.gif" />网站首页</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'member.php\'"><img border="0" src="template/images/default/user.png" alt="审核注册" />审核注册</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'announce.php?part=add\'"><img border="0" src="template/images/default/tpc.png" alt="审核主题" />发布公告</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'information.php\'"><img border="0" src="template/images/default/post.png"/>分类信息</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'friendlink.php\'"><img border="0" src="template/images/default/share.png" />审核链接</a></li>
		</ul>
		</div>
			',
	
	'安全建议'=>'
		<span>在线编辑模板功能</span> 当前：'.$cfg_if_tpledit.'。建议您只有在十分必要的时候才开启它。请修改 /data/config.inc.php 关闭此功能<br />
<span>系统 install目录</span> 当前：'.$if_del_install.'。为防止站外人员利用，建议您安装完成后，删除该目录<br />
<span>系统管理目录</span> 当前：'.$cur_dir.'。建议您安装完成后，修改目录名（可直接修改）。<br />
<span>数据安全</span>'.$message
			 ,
	'相关说明'=>'<span>商业用户授权</span>
如果您现在还未正式购买商业用户授权，请您及时联系我们,以获得我们专业的技术支持服务！<br />
<span>技术支持服务</span><a href="http://www.mymps.com.cn/member/index.php?mod=service&action=add">提交售后工单</a>
<br>
<span>售前咨询</span> Q Q：<a href="tencent://message/?uin=1367882925&Site=www.mymps.com.cn&Menu=yes" target="_blank"><b style="color:#f30">1367882925(点击交谈)</b></a> TEL：0371-65366238
			',
	'服务器相关'=>'
		<div><span>服务器环境:</span>'.$_SERVER['SERVER_SOFTWARE'].'</div>
		<div><span>服务器系统:</span>'.PHP_OS.'</div>
		<div><span>当前时间:</span>'.GetTime($timestamp)." ".date("星期N",$timestamp).'</div>
		<div><span>PHP程式版本:</span>'.PHP_VERSION.'</div>
		<div><span>Register_Globals:</span>'.$Register_Globals.' &nbsp;&nbsp;<font color=red>[荐off]</font></div>
		<div><span>Magic_Quotes_Gpc:</span>'.$Magic_Quotes_Gpc.' &nbsp;&nbsp;<font color=red>[荐on]</font></div>
		<div><span>expose_php:</span>'.$expose_php.' &nbsp;&nbsp;<font color=red>[荐off]</font></div>
		<div><span>eAccelerator:</span>'.$eAccelerator.' &nbsp;&nbsp;</div>
		<div><span>MYSQL版本:</span>'.$db->version().'</div>
		<div><span>mymps目录: </span>'.MYMPS_ROOT.'</div>
		<div><span>使用域名: </span>'.$_SERVER["SERVER_NAME"].'</div>
		<div><span>脚本超时时间：</span>'.ini_get('max_execution_time').'</div>
		<div><span>附件上传上限</span>'.ini_get('upload_max_filesize').'</div>
		<div><span>GD库版本</span>'.$gd_version.'</div>
		<div><span>检测文件读写权限</span><a href=\'javascript:setbg("检测文件读写权限",305,380,"../box.php?part=sp_testdirs")\' class="icon_open" id="spanmymsg" >点此检测</a>		
		',
	'研发团队'=>'
		<div><span>版权所有：</span> <a href="http://www.mymps.com.cn" target="_blank">http://www.mymps.com.cn</a><br />
		<div><span>官方论坛：</span> <a href="http://bbs.mymps.com.cn" target="_blank">http://bbs.mymps.com.cn</a><br />
		<div><span>mymps版本：</span> '.MPS_VERSION.' <br />
			'
		);
?>