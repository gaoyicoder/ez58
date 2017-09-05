<?php

//decode by QQ:270656184 http://www.yunlu99.com/
define('CURSCRIPT', 'mobile');
require_once dirname(__FILE__) . '/global.php';
require_once MYMPS_INC . '/db.class.php';
require_once MYMPS_INC . '/upfile.fun.php';
if ($admin_cityid) {
	write_msg('您没有权限访问该页！');
}
$type = isset($type) ? $type : '';
if (!in_array($type, array('default', 'nav', 'nav_ico', 'restore', 'gg'))) {
	$type = 'default';
}
if (!submit_check(CURSCRIPT . '_submit')) {
	chk_admin_purview('purview_手机访问设置');
	if ($type == 'default') {
		$here = '手机版访问设置';
		$mobile = $db->getOne("SELECT value FROM `{$db_mymps}config` WHERE type='mobile' AND description = 'mobile'");
		$mobile = $mobile ? $charset == 'utf-8' ? utf8_unserialize($mobile) : unserialize($mobile) : array();
		include mymps_tpl(CURSCRIPT);
	} elseif ($type == 'nav') {
		require_once dirname(__FILE__) . '/include/ifview.inc.php';
		require_once dirname(__FILE__) . '/include/color.inc.php';
		$target = array('_self' => '当前窗口', '_blank' => '新窗口');
		$here = '手机版导航设置';
		$where = ' WHERE typeid = \'1\'';
		$rows_num = mymps_count('mobile_nav', $where);
		$param = setParam(array('type', 'typeid'));
		$url = array();
		$url = page1("SELECT * FROM {$db_mymps}mobile_nav {$where} ORDER BY displayorder ASC");
		include mymps_tpl(CURSCRIPT . '_nav');
	} elseif ($type == 'nav_ico') {
		require_once dirname(__FILE__) . '/include/ifview.inc.php';
		require_once dirname(__FILE__) . '/include/color.inc.php';
		$target = array('_self' => '当前窗口', '_blank' => '新窗口');
		$here = '手机版导航设置';
		$where = ' WHERE typeid = \'2\'';
		$rows_num = mymps_count('mobile_nav', $where);
		$param = setParam(array('type', 'typeid'));
		$url = array();
		$url = page1("SELECT * FROM {$db_mymps}mobile_nav {$where} ORDER BY displayorder ASC");
		include mymps_tpl(CURSCRIPT . '_nav_ico');
	} elseif ($type == 'restore') {
		restore_url();
		write_msg('手机图标导航已成功应用默认设置！', 'mobile.php?type=nav_ico', 'Mym_psRecord');
	} elseif ($type == 'gg') {
		$here = '手机版广告设置';
		$target = array('1' => '首页头部', '2' => '新闻首页头部', '3' => '首页中部');
		$where = $typeid ? " WHERE typeid = '{$typeid}'" : "";
		$rows_num = mymps_count('mobile_gg', $where);
		$param = setParam(array('type', 'typeid'));
		$row = page1("SELECT * FROM `{$db_mymps}mobile_gg` {$where} ORDER BY displayorder ASC");
		if ($id) {
			$edit = $db->getRow("SELECT * FROM `{$db_mymps}mobile_gg` WHERE id ='{$id}'");
			!$edit && ($id = '');
		} else {
			$edit['displayorder'] = $db->getOne("SELECT max(displayorder) FROM `{$db_mymps}mobile_gg`");
			$edit['displayorder'] = $edit['displayorder'] + 1;
		}
		include mymps_tpl(CURSCRIPT . '_gg');
	}
} else {
	if ($type == 'default') {
		$db->query("DELETE FROM `{$db_mymps}config` WHERE description = 'mobile' AND type = 'mobile'");
		$db->query("INSERT INTO `{$db_mymps}config` (description,value,type) values ('mobile','" . serialize($settings) . '\',\'mobile\')');
		clear_cache_files('mobile');
		write_msg('手机基本设置更新成功！', 'mobile.php', 'WriteRecord');
	} elseif ($type == 'nav') {
		if (is_array($navtitle)) {
			foreach ($navtitle as $key => $v) {
				$db->query("UPDATE `{$db_mymps}mobile_nav` SET title = '{$v}',displayorder = '{$displayorder[$key]}',url='{$navurl[$key]}',isview='{$isviewids[$key]}',target='{$target[$key]}',flag='{$flag[$key]}',color='{$showcolor[$key]}',typeid='1' WHERE id = " . $key);
			}
		}
		if (is_array($newtitle) && is_array($newurl)) {
			foreach ($newtitle as $key => $q) {
				$title = trim($q);
				$url = mhtmlspecialchars(trim($newurl[$key]));
				$displayorder = mhtmlspecialchars(trim($newdisplayorder[$key]));
				$isview = mhtmlspecialchars(trim($newisview[$key]));
				$target = $newtarget[$key] ? mhtmlspecialchars(trim($newtarget[$key])) : '_blank';
				$showcolor = mhtmlspecialchars(trim($newshowcolor[$key]));
				$flag = mhtmlspecialchars(trim($newflag[$key]));
				$flag = $flag ? $flag : 'outlink';
				if ($title && $url) {
					$db->query("INSERT INTO `{$db_mymps}mobile_nav` (url,title,isview,target,flag,color,displayorder,createtime,typeid) VALUES ('{$url}','{$title}','{$isview}','{$target}','{$flag}','{$showcolor}','{$displayorder}','{$timestamp}','1')");
				}
			}
		}
		if (is_array($delids)) {
			foreach ($delids as $kids => $vids) {
				mymps_delete('mobile_nav', 'WHERE id = ' . $vids);
			}
		}
		clear_cache_files('mobile_nav');
		write_msg('手机文字导航设置更新成功！', 'mobile.php?type=nav', 'MympsRecord');
	} elseif ($type == 'nav_ico') {
		if (is_array($navtitle)) {
			foreach ($navtitle as $key => $v) {
				$db->query("UPDATE `{$db_mymps}mobile_nav` SET title = '{$v}',displayorder = '{$displayorder[$key]}',url='{$navurl[$key]}',isview='{$isviewids[$key]}',target='_self',ico='{$navico[$key]}',color='{$showcolor[$key]}',typeid='2' WHERE id = " . $key);
			}
		}
		if (is_array($newtitle) && is_array($newurl)) {
			foreach ($newtitle as $key => $q) {
				$title = trim($q);
				$ico = trim($newico[$key]);
				$url = trim($newurl[$key]);
				$displayorder = trim($newdisplayorder[$key]);
				$isview = trim($newisview[$key]);
				$target = '_self';
				$showcolor = trim($newshowcolor[$key]);
				$flag = '';
				if ($title && $url) {
					$db->query("INSERT INTO `{$db_mymps}mobile_nav` (url,ico,title,isview,target,flag,color,displayorder,createtime,typeid) VALUES ('{$url}','{$ico}','{$title}','{$isview}','{$target}','{$flag}','{$showcolor}','{$displayorder}','{$timestamp}','2')");
				}
			}
		}
		if (is_array($delids)) {
			foreach ($delids as $kids => $vids) {
				mymps_delete('mobile_nav', 'WHERE id = ' . $vids);
			}
		}
		clear_cache_files('mobile_nav');
		write_msg('手机图标导航设置更新成功！', 'mobile.php?type=nav_ico', 'Mym_psRecord');
	} elseif ($type == 'gg') {
		if (is_array($delids)) {
			foreach ($delids as $kids => $vids) {
				$delrow = $db->getRow("SELECT image FROM `{$db_mymps}mobile_gg` WHERE id = '{$vids}'");
				@unlink(MYMPS_ROOT . $delrow['image']);
				mymps_delete('mobile_gg', 'WHERE id = ' . $vids);
			}
			clear_cache_files('mobile_gg');
			write_msg('幻灯片广告更新成功！', 'mobile.php?type=gg');
			die;
		}
		if (is_array($displayorder)) {
			foreach ($displayorder as $key => $val) {
				$db->query("UPDATE `{$db_mymps}mobile_gg` SET displayorder = '{$val}' WHERE id = " . $key);
			}
			clear_cache_files('mobile_gg');
			write_msg('幻灯片广告更新成功！', 'mobile.php?type=gg');
		}
		if ($id) {
			$name_file = 'mymps_focus';
			if ($_FILES[$name_file]['name']) {
				check_upimage($name_file);
				$destination = '/mobile_gg/';
				$mymps_image = start_upload($name_file, $destination, 0, '', '', $image);
				unset($limit);
				$image = $mymps_image;
			}
			$res = $db->query("UPDATE `{$db_mymps}mobile_gg` SET image='{$image}',words='{$words}',typeid='{$typeid}',url='{$url}',displayorder='{$displayorder}' WHERE id = '{$id}'");
			clear_cache_files('mobile_gg');
			write_msg('幻灯片广告更新成功！', 'mobile.php?type=gg');
		} else {
			$name_file = 'mymps_focus';
			if ($_FILES[$name_file]['name']) {
				check_upimage($name_file);
				$destination = '/mobile_gg/';
				$mymps_image = start_upload($name_file, $destination, 0);
				unset($limit);
				$db->query("INSERT INTO `{$db_mymps}mobile_gg` (id,image,words,url,pubdate,displayorder,typeid)
										VALUES('','{$mymps_image}','{$words}','{$url}','{$timestamp}','{$displayorder}','{$typeid}')");
				clear_cache_files('mobile_gg');
				write_msg('幻灯片广告上传成功！', 'mobile.php?type=gg');
			}
		}
	}
}
function get_target_options($_var_0 = '')
{
	global $target;
	foreach ($target as $_var_1 => $_var_2) {
		$_var_3 .= '<option value=' . $_var_1;
		$_var_3 .= $_var_0 == $_var_1 ? ' style = "background-color:#6EB00C;color:white" selected>' : '>';
		$_var_3 .= $_var_2 . '</option>';
	}
	return $_var_3;
}
function restore_url()
{
	global $db, $db_mymps;
	$db->query("DELETE FROM `{$db_mymps}mobile_nav` WHERE typeid = '2'");
	$_var_4 = $db->query("SELECT * FROM `{$db_mymps}category` WHERE parentid = '0'");
	while ($_var_5 = $db->fetchRow($_var_4)) {
		$_var_6[$_var_5['catid']]['catid'] = $_var_5['catid'];
		$_var_6[$_var_5['catid']]['name'] = $_var_5['catname'];
		$_var_6[$_var_5['catid']]['color'] = $_var_5['color'];
		$_var_6[$_var_5['catid']]['uri'] = 'index.php?mod=category&catid=' . $_var_5['catid'];
		$_var_6[$_var_5['catid']]['ico'] = $_var_5['icon'];
	}
	$_var_7 = 0;
	if (is_array($_var_6)) {
		foreach ($_var_6 as $_var_8 => $_var_9) {
			$_var_7 = $_var_7 + 1;
			$db->query("INSERT INTO `{$db_mymps}mobile_nav` (url,color,title,ico,typeid,isview,displayorder,createtime)VALUES('{$_var_9['uri']}','{$_var_9['color']}','{$_var_9['name']}','{$_var_9['ico']}','2','2','{$_var_7}','{$_var_10}')");
		}
	}
	clear_cache_files('mobile_nav');
}
is_object($db) && $db->Close();
$mymps_global = $db = $db_mymps = $part = NULL;