<?php 
define('IN_SMT', true);
define('IN_MYMPS', true);
define('CURSCRIPT','delinfo');
require_once dirname(__FILE__)."/include/global.php";
require_once dirname(__FILE__)."/data/config.php";
require_once MYMPS_DATA."/config.db.php";
require_once MYMPS_INC."/db.class.php";
$loc 		= get_location('delinfo','','修改/删除信息');
$page_title = $loc['page_title'];
globalassign();
$city=get_city_caches($cityid);
include mymps_tpl('delinfo');
is_object($db) && $db->Close();
?>