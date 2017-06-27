<?php
error_reporting(E_ALL^E_NOTICE);
define('IN_MYMPS',true);
define(CURSCRIPT,dirname(__FILE__));
require_once CURSCRIPT."/pinyin.inc.php";
require_once CURSCRIPT."/../../data/config.db.php";
define(MYMPS_DATA,CURSCRIPT.'/../../data');
$t = $value = $elementbyid = $ishead = '';
$t = trim($_GET['t']);
$ishead = intval($_GET['ishead']);
$value = GetPinyin($t,$ishead);
$elementbyid = $ishead ? 'newdirectory' : 'newcitypy';
echo "<SCRIPT LANGUAGE=\"JavaScript\">
			<!--
			parent.document.getElementById(\"".$elementbyid."\").value='$value';
			//-->
			</SCRIPT>";
unset($value,$t,$ishead,$elementbyid);
?>