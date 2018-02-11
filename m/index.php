<?php
define('WAP',true);
define('CURSCRIPT','wap');
define('IN_MYMPS', true);
define('IN_SMT', true);
require_once dirname(__FILE__).'/../include/global.php';
require_once MYMPS_DATA."/config.php";
require_once MYMPS_DATA.'/config.db.php';
require_once MYMPS_INC.'/db.class.php';

$mobile_settings = get_mobile_settings();
if($mobile_settings['allowmobile'] != 1){
	errormsg('本站手机版暂停访问！');
}

if(pcclient()) write_msg('',$mymps_global['SiteUrl']);

if($cityid) {
	if(!$city = $db->getRow("SELECT * FROM `{$db_mymps}city` WHERE cityid = '$cityid'")){
		redirectmsg('当前分站不存在，请前往选择您的分站！','index.php?mod=changecity');
		exit;
	}
	msetcookie('cityid',$cityid);
}else{
	$cityid = mgetcookie('cityid');
	$lat = isset($lat) ? (float)$lat : '';
	$lng = isset($lng) ? (float)$lng : '';
	if($lat && $lng && !$cityid) $cityid = get_latlng2cityid($lat,$lng);
    msetcookie('cityid',$cityid);
}
//if(!$cityid && $mod!='news' && $mod!='changecity' && $mod!='member') write_msg('','index.php?mod=changecity');

!in_array($mod,array('category','index','items','information','login','register','post','search','member','history','news','goods','corp','store','changecity','sync', 'sync_map', 'message', 'message_detail', 'cate_index', 'share_parking', 'find_parking', 'parking_map', 'parking_info', 'parking_book', 'parking_exchange', 'parking_exchange_pre','parking_drive_map', 'parking_book_status', 'contactus', 'parking_delete')) && $mod = 'index';

$s_uid = $iflogin = NULL;
include MYMPS_INC.'/member.class.php';
$returnurl = urlencode(GetUrl());
if($member_log->chk_in()){
	$iflogin = 1;
    $user_mobile = $db -> getOne("SELECT mobile FROM `{$db_mymps}member` WHERE userid = '$s_uid'");
} else {
	$iflogin = 0;
}

if($id && in_array($mod,array('information','news','goods'))){
}else{
	$city = get_city_caches($cityid);
	$city_limit 	= empty($city['cityid']) ? "": " AND cityid = '$city[cityid]'";
	$city_limit_a 	= empty($city['cityid']) ? "": " AND a.cityid = '$city[cityid]'";
}

include MYMPS_ROOT.'/m/include/inc_'.$mod.'.php';

is_object($db) && $db -> Close();
$parent_cats = $loginfo = $newinfo = $mod = $ac = $mymps_global = $catid = $areaid = $db = $db_mymps = $mobile_settings = $member_log = NULL;

function errormsg($error_msg){
	global $charset,$mymps_global,$cityid;
	redirectmsg($error_msg,'javascript:history.back();');
}

function redirectmsg($redirectmsg,$url){
	global $charset,$mymps_global,$cityid;
	$url = $url ? $url : 'javascript:history.back();';
	include mymps_tpl('notice_redirect');
	exit;
}

function setparams($param)
{
	foreach($param as $k =>$v){
		global ${$v};
		$params .= ${$v} ? urlencode($v).'='.${$v}.'&' : '';
	}
	return $params;
}

function pager(){
	global $page,$totalpage,$rows_num,$param;
	if($totalpage == 1 && $page == 1){
		$nav = $rows_num.'条记录';
	}else{
		if($page-1 < 1){
			$nav .= '<a href="javascript:void();" class="pageprev pagedisable">上一页</a>';
			$nav .= '<a class="pageno pagecur">'.$page.'</a>';
			$nav .= '<a href="?'.$param.'page='.($page+1).'" class="pageno">'.($page+1).'</a>';
			if($totalpage > $page+1){
				$nav .= '<a href="?'.$param.'page='.($page+2).'" class="pageno">'.($page+2).'</a>';
			}
		}else{
			$nav .= '<a href="?'.$param.'page='.($page-1 < 1 ? 1 : $page-1).'" class="pageprev">上一页</a>';
			if($totalpage == 3 && $page==3){
				$nav .= '<a href="?'.$param.'page='.($page-2).'" class="pageno">'.($page-2).'</a>';	
			}
			$nav .= '<a href="?'.$param.'page='.($page-1).'" class="pageno">'.($page-1 < 1 ? 1 : $page-1).'</a>';	
			$nav .= '<a class="pageno pagecur">'.$page.'</a>';
			if($totalpage > $page){
				$nav .= '<a href="?'.$param.'page='.($page+1).'" class="pageno">'.($page+1).'</a>';
			}
		}
		
		if($totalpage > $page){
			$nav .= '<a href="?'.$param.'page='.($page+1).'" class="pagenext">下一页</a>';
		}else{
			$nav .= '<a href="javascript:void();" class="pagenext pagedisable">下一页</a>';
		}
	}
	return $nav;
}

function get_mobile_nav($typeid=1){
	static $res;
	$data = read_static_cache('mobile_nav');
	if($data === false){
		$query = $GLOBALS['db'] -> query("SELECT * FROM `{$GLOBALS['db_mymps']}mobile_nav` WHERE isview = 2 ORDER BY displayorder ASC");
		while($row = $GLOBALS['db'] -> fetchRow($query)){
			$res[$row['typeid']][$row['id']]['title'] = $row['title'];
			$res[$row['typeid']][$row['id']]['url'] = $row['url'];
			$res[$row['typeid']][$row['id']]['color'] = $row['color'];
			$res[$row['typeid']][$row['id']]['flag'] = $row['flag'];
			$res[$row['typeid']][$row['id']]['ico'] = $row['ico'];
			$res[$row['typeid']][$row['id']]['target'] = in_array($row['target'],array('_selef','_blank'))?$row['target']:'_self';
		}
		write_static_cache('mobile_nav',$res);
	} else {
		$res = $data;
	}
	return $res[$typeid];
}

function get_mobile_info_nav($typeid=1){
    static $res;
    $data = read_static_cache('mobile_info_nav');
    if($data === false){
        $query = $GLOBALS['db'] -> query("SELECT * FROM `{$GLOBALS['db_mymps']}mobile_nav` WHERE isview = 2 AND url LIKE 'index.php?mod=category%' ORDER BY displayorder ASC");
        while($row = $GLOBALS['db'] -> fetchRow($query)){
            $res[$row['typeid']][$row['id']]['title'] = $row['title'];
            $res[$row['typeid']][$row['id']]['url'] = $row['url'];
            $res[$row['typeid']][$row['id']]['color'] = $row['color'];
            $res[$row['typeid']][$row['id']]['flag'] = $row['flag'];
            $res[$row['typeid']][$row['id']]['ico'] = $row['ico'];
            $res[$row['typeid']][$row['id']]['target'] = in_array($row['target'],array('_selef','_blank'))?$row['target']:'_self';
        }
        write_static_cache('mobile_info_nav',$res);
    } else {
        $res = $data;
    }
    return $res[$typeid];
}

function get_mobile_share_nav($typeid=1){
    static $res;
    $data = read_static_cache('mobile_share_nav');
    if($data === false){
        $query = $GLOBALS['db'] -> query("SELECT * FROM `{$GLOBALS['db_mymps']}mobile_nav` WHERE isview = 2 AND url LIKE 'index.php?mod=find_parking%' ORDER BY displayorder ASC");
        while($row = $GLOBALS['db'] -> fetchRow($query)){
            $res[$row['typeid']][$row['id']]['title'] = $row['title'];
            $res[$row['typeid']][$row['id']]['url'] = $row['url'];
            $res[$row['typeid']][$row['id']]['color'] = $row['color'];
            $res[$row['typeid']][$row['id']]['flag'] = $row['flag'];
            $res[$row['typeid']][$row['id']]['ico'] = $row['ico'];
            $res[$row['typeid']][$row['id']]['target'] = in_array($row['target'],array('_selef','_blank'))?$row['target']:'_self';
        }
        write_static_cache('mobile_share_nav',$res);
    } else {
        $res = $data;
    }
    return $res[$typeid];
}

function get_mobile_gg($typeid=1){
	static $res;
	$data = read_static_cache('mobile_gg');
	if($data === false){
		$query = $GLOBALS['db'] -> query("SELECT * FROM `{$GLOBALS['db_mymps']}mobile_gg` ORDER BY displayorder ASC");
		while($row = $GLOBALS['db'] -> fetchRow($query)){
			$res[$row['typeid']][$row['id']]['words'] = $row['words'];
			$res[$row['typeid']][$row['id']]['url'] = $row['url'];
			$res[$row['typeid']][$row['id']]['image'] = $row['image'];
		}
		write_static_cache('mobile_gg',$res);
	} else {
		$res = $data;
	}
	return $res[$typeid];
}
?>