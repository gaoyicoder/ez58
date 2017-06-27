<?php

//decode by QQ:270656184 http://www.yunlu99.com/
define('IN_MYMPS', true);
define('IN_MANAGE', true);
require_once dirname(__FILE__) . '/../include/global.php';
require_once MYMPS_DATA . '/config.php';
require_once MYMPS_DATA . '/config.inc.php';
require_once MYMPS_DATA . '/config.db.php';
require_once MYMPS_INC . '/admin.class.php';
if ($admin_cityid && in_array(CURSCRIPT, array('advertisement', 'faq', 'area', 'category', 'channel', 'config', 'advertisement', 'database', 'filemanage', 'info_type', 'jswizard', 'mail', 'member_tpl', 'passport', 'payapi', 'payrecord', 'plugin', 'seoset', 'site_about', 'record', 'city'))) {
	die('分站管理员无该栏目的操作权限...');
}
if (!$mymps_admin->mymps_admin_chk_getinfo()) {
	write_msg("", 'index.php?do=login&url=' . urlencode(GetUrl()));
} else {
	define('IN_ADMIN', true);
}
function get_member_level($_var_0 = '', $_var_1 = 'levelid')
{
	global $db, $db_mymps;
	$_var_2 = $db->getAll("SELECT id,levelname FROM `{$db_mymps}member_level`");
	$_var_3 .= '<select name="' . $_var_1 . '">';
	$_var_3 .= '<option value=\'\'>>不限组别</option>';
	foreach ($_var_2 as $_var_4 => $_var_5) {
		$_var_3 .= '<option value=' . $_var_5[id] . "";
		$_var_3 .= $_var_0 == $_var_5[id] ? ' selected style="background-color:#6EB00C;color:white"' : "";
		$_var_3 .= '>' . $_var_5[levelname] . '</option>';
	}
	$_var_3 .= '</select>';
	return $_var_3;
}
function show_message($_var_6 = '', $_var_7 = '', $_var_8 = '')
{
	global $here;
	write_admin_record($_var_7);
	$here = MPS_SOFTNAME . '操作提示窗口';
	include mymps_tpl('showmessage');
}
function admin_get_gid($_var_9 = '', $_var_10 = '')
{
	global $db, $db_mymps, $mymps_global;
	if ($_var_10 > 0) {
		$_var_11 = $db->getRow("SELECT catid,parentid FROM `{$db_mymps}category` WHERE catid = '{$_var_10}'");
		if ($_var_11['parentid'] > 0) {
			$_var_12 = $_var_11['parentid'];
		} else {
			$_var_12 = $_var_11['catid'];
		}
	} else {
		$_var_12 = !$_var_9 ? $_var_10 : $_var_9;
	}
	return $_var_12;
}
function sizeunit($_var_13)
{
	if ($_var_13 >= 1073741824) {
		$_var_13 = round($_var_13 / 1073741824 * 100) / 100 . ' GB';
	} elseif ($_var_13 >= 1048576) {
		$_var_13 = round($_var_13 / 1048576 * 100) / 100 . ' MB';
	} elseif ($_var_13 >= 1024) {
		$_var_13 = round($_var_13 / 1024 * 100) / 100 . ' KB';
	} else {
		$_var_13 = $_var_13 . ' bytes';
	}
	return $_var_13;
}
function write_file($_var_14, $_var_15, $_var_16)
{
	$_var_17 = true;
	if (!@($_var_18 = fopen($_var_15 . $_var_16, 'w+'))) {
		$_var_17 = false;
		echo '打开文件失败';
	}
	if (!@fwrite($_var_18, $_var_14)) {
		$_var_17 = false;
		echo '写文件失败';
	}
	if (!@fclose($_var_18)) {
		$_var_17 = false;
		echo '关闭文件失败';
	}
	return $_var_17;
}
function bb($_var_19)
{
	if (empty($_var_20)) {
		if (myget('HTTP_HOST')) {
			$_var_20 = myget('HTTP_HOST');
		} else {
			$_var_20 = '';
		}
	}
	$_var_19 = '.127.0.0.1|localhost|' . $_var_19;
	$_var_21 = getRd(htmlspecialchars($_var_20));
	$_var_21 = str_replace('.www.', '', $_var_21);
	if (!in_array($_var_21, explode('|', $_var_19))) {
		die;
	}
}
function myget($_var_22)
{
	if (isset($_SERVER[$_var_22])) {
		return $_SERVER[$_var_22];
	}
	if (isset($_ENV[$_var_22])) {
		return $_ENV[$_var_22];
	}
	if (getenv($_var_22)) {
		return getenv($_var_22);
	}
	if (function_exists('apache_getenv') && apache_getenv($_var_22, true)) {
		return apache_getenv($_var_22, true);
	}
	return '';
}
function getRd($_var_23)
{
	$_var_24 = array('ren', 'wang', 'ae', 'af', 'ag', 'ai', 'al', 'am', 'an', 'ao', 'aq', 'ar', 'as', 'at', 'au', 'aw', 'az', 'ba', 'bb', 'bd', 'be', 'bf', 'bg', 'bh', 'bi', 'bj', 'bm', 'bn', 'bo', 'br', 'bs', 'bt', 'bv', 'bw', 'by', 'bz', 'ca', 'cc', 'cf', 'cg', 'ch', 'ci', 'ck', 'cl', 'cm', 'cn', 'co', 'cq', 'cr', 'cu', 'cv', 'cx', 'cy', 'cz', 'de', 'dj', 'dk', 'dm', 'do', 'dz', 'ec', 'ee', 'eg', 'eh', 'es', 'et', 'ev', 'fi', 'fj', 'fk', 'fm', 'fo', 'fr', 'ga', 'gb', 'gd', 'ge', 'gf', 'gg', 'gh', 'gi', 'gl', 'gm', 'gn', 'gp', 'gr', 'gt', 'gu', 'gw', 'gy', 'hk', 'hm', 'hn', 'hr', 'ht', 'hu', 'id', 'ie', 'il', 'in', 'io', 'iq', 'ir', 'is', 'it', 'jm', 'jo', 'jp', 'ke', 'kg', 'kh', 'ki', 'km', 'kn', 'kp', 'kr', 'kw', 'ky', 'kz', 'la', 'lb', 'lc', 'li', 'lk', 'lr', 'ls', 'lt', 'lu', 'lv', 'ly', 'ma', 'mc', 'md', 'mg', 'mh', 'ml', 'mm', 'mn', 'mo', 'mp', 'mq', 'mr', 'ms', 'mt', 'mv', 'mw', 'mx', 'my', 'mz', 'na', 'nc', 'ne', 'nf', 'ng', 'ni', 'nl', 'no', 'np', 'nr', 'nt', 'nu', 'nz', 'om', 'qa', 'pa', 'pe', 'pf', 'pg', 'ph', 'pk', 'pl', 'pm', 'pn', 'pr', 'pt', 'pw', 'py', 're', 'ro', 'ru', 'rw', 'sa', 'sb', 'sc', 'sd', 'se', 'sg', 'sh', 'si', 'sj', 'sk', 'sl', 'sm', 'sn', 'so', 'sr', 'st', 'su', 'sy', 'sz', 'tc', 'td', 'tf', 'tg', 'th', 'tj', 'tk', 'tm', 'tn', 'to', 'tp', 'tr', 'tt', 'tv', 'tw', 'tz', 'ua', 'ug', 'uk', 'us', 'uy', 'va', 'vc', 've', 'vg', 'vn', 'vu', 'wf', 'ws', 'ye', 'yu', 'za', 'zm', 'zr', 'zw', 'com', 'edu', 'gov', 'int', 'mil', 'net', 'org');
	$_var_25 = explode('.', $_var_23);
	$_var_26 = count($_var_25);
	$_var_27 = 0;
	for ($_var_28 = 0; $_var_28 < $_var_26; $_var_28++) {
		if (in_array($_var_25[$_var_28], $_var_24)) {
			$_var_27 = $_var_28;
			break;
		}
	}
	$_var_29 = '';
	for ($_var_28 = $_var_27; $_var_28 < $_var_26; $_var_28++) {
		$_var_29 .= '.' . $_var_25[$_var_28];
	}
	return $_var_25[$_var_27 - 1] . $_var_29;
}
function down_file($_var_14, $_var_16)
{
	ob_end_clean();
	header('Content-Encoding: none');
	header('Content-Type: ' . (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
	header('Content-Disposition: ' . (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ') . 'filename=' . $_var_16);
	header('Content-Length: ' . strlen($_var_14));
	header('Pragma: no-cache');
	header('Expires: 0');
	echo $_var_14;
	$_var_30 = ob_get_contents();
	ob_end_clean();
}
function writeable($_var_31)
{
	if (!is_dir($_var_31)) {
		@mkdir($_var_31, 511);
	}
	if (is_dir($_var_31)) {
		if (is_writable($_var_31)) {
			$_var_32 = 1;
		} else {
			$_var_32 = 0;
		}
	}
	return $_var_32;
}
function make_header($_var_33)
{
	global $d;
	$_var_14 = 'DROP TABLE IF EXISTS ' . $_var_33 . "\n";
	$d->query('show create table ' . $_var_33);
	$d->nextrecord();
	$_var_34 = preg_replace("/\n/", "", $d->f('Create Table'));
	$_var_14 .= $_var_34 . "\n";
	return $_var_14;
}
function make_record($_var_33, $_var_35)
{
	global $d;
	$_var_36 = "";
	$_var_14 .= 'INSERT INTO ' . $_var_33 . ' VALUES(';
	for ($_var_28 = 0; $_var_28 < $_var_35; $_var_28++) {
		$_var_14 .= $_var_36 . '\'' . mysql_escape_string($d->record[$_var_28]) . '\'';
		$_var_36 = ',';
	}
	$_var_14 .= ")\n";
	return $_var_14;
}
function import($_var_37)
{
	global $d;
	$_var_38 = file($_var_37);
	foreach ($_var_38 as $_var_14) {
		str_replace("\r", "", $_var_14);
		str_replace("\n", "", $_var_14);
		$d->query(trim($_var_14));
	}
	return true;
}
function chk_admin_purview($_var_39)
{
	global $admin_uname;
	if (!$GLOBALS['admin_id']) {
		write_msg('您还没有登录，请登录后再进行后续操作！');
	}
	$_var_40 = read_static_cache('admin');
	if ($_var_40 === false) {
		$_var_40 = write_admin_cache();
	}
	$admin_uname = $_var_40[$GLOBALS['admin_id']]['uname'];
	!in_array($_var_39, explode(',', $_var_40[$GLOBALS['admin_id']]['purviews'])) && write_msg('很抱歉，您所在会员组没有 <strong><font color=red>' . str_replace('purview_', '', $_var_39) . '</font></strong> 的操作权限！', 'olmsg');
}
function get_admin_type()
{
	if (!$GLOBALS['admin_id']) {
		write_msg('您还没有登录，请登录后再进行后续操作！');
	}
	$_var_40 = read_static_cache('admin');
	if ($_var_40 === false) {
		$_var_41 = write_admin_cache();
	} else {
		$_var_41 = $_var_40;
	}
	return $_var_41[$GLOBALS['admin_id']];
}
function mymps_admin_tpl_global_head($_var_42 = '')
{
	global $here, $charset;
	include mymps_tpl('inc_head');
}
function mymps_admin_tpl_global_foot()
{
	global $mymps_starttime, $mtime, $db;
	$mtime = explode(' ', microtime());
	$_var_43 = number_format($mtime[1] + $mtime[0] - $mymps_starttime, 6);
	$_var_44 = 'Processed in ' . $_var_43 . ' second(s) , ' . $db->query_num . ' queries';
	echo '<div class="clear" style="height:10px"></div><div class="copyright">  ' . $_var_44 . ' <a href="javascript:scroll(0,0)" style="margin-left:10px;">至顶端↑</a></div></div></div></body></html>';
}
function FileImage($_var_45)
{
	$_var_46 = FileExt($_var_45);
	if ($_var_46 == 'html' || $_var_46 == 'htm') {
		$_var_47 = 'template/images/file_html.gif';
	} elseif ($_var_46 == 'gif' || $_var_46 == 'png') {
		$_var_47 = 'template/images/file_gif.gif';
	} elseif ($_var_46 == 'bmp') {
		$_var_47 = 'template/images/file_bmp.gif';
	} elseif ($_var_46 == 'jpg' || $_var_46 == 'jpeg') {
		$_var_47 = 'template/images/file_jpg.gif';
	} elseif ($_var_46 == 'swf') {
		$_var_47 = 'template/images/file_swf.gif';
	} elseif ($_var_46 == 'js') {
		$_var_47 = 'template/images/file_script.gif';
	} elseif ($_var_46 == 'css') {
		$_var_47 = 'template/images/file_css.gif';
	} elseif ($_var_46 == 'txt') {
		$_var_47 = 'template/images/file_txt.gif';
	} else {
		$_var_47 = 'template/images/file_unknow.gif';
	}
	return $_var_47;
}
function is_pic($_var_45)
{
	$_var_46 = FileExt($_var_45);
	if ($_var_46 == 'gif' || $_var_46 == 'jpg' || $_var_46 == 'jpeg' || $_var_46 == 'png' || $_var_46 == 'bmp') {
		return 'yes';
		die;
	}
	return 'no';
}
function getSize($_var_48)
{
	if ($_var_48 < 1024) {
		return $_var_48 . 'Byte';
	} elseif ($_var_48 >= 1024 && $_var_48 < 1024 * 1024) {
		return @number_format($_var_48 / 1024, 3) . ' KB';
	} elseif ($_var_48 >= 1024 * 1024 && $_var_48 < 1024 * 1024 * 1024) {
		return @number_format($_var_48 / 1024 * 1024, 3) . ' M';
	} elseif ($_var_48 >= 1024 * 1024 * 1024) {
		return @number_format($_var_48 / 1024 * 1024 * 1024, 3) . ' G';
	}
}
function mymps_admin_menu($_var_49 = 'top', $_var_50 = 'siteabout')
{
	global $admin_menu;
	$_var_28 = -1;
	foreach ($admin_menu as $_var_51 => $_var_52) {
		if ($_var_49 == 'top') {
			$_var_28 = $_var_28 + 1;
			$_var_53 = !$_var_52[url] ? '#' : $_var_52[url];
			$_var_54 = !$_var_52[url] ? 'onclick=sethighlight(\'' . $_var_28 . '\');togglemenu(\'' . $_var_51 . '\');return false;' : '$w[url]';
			$_var_55 = $_var_52[target] ? $_var_52[target] : '';
			$_var_56 .= "<li class=\"{$_var_52['style']}\"><a href=\"" . $_var_53 . '"' . $_var_54 . ' target=' . $_var_55 . '>' . $_var_52[name] . '</a></li>';
		} elseif ($_var_49 == 'left') {
			if (is_array($_var_52[group])) {
				foreach ($_var_52[group] as $_var_30 => $_var_57) {
					$_var_58 = $_var_51 != $_var_50 ? 'style="display:none"' : "";
					$_var_56 .= '<dl id="' . $_var_51 . '" ' . $_var_58 . '>';
					$_var_56 .= '<span class="wname">' . $_var_52[name] . '</span>';
					foreach ($_var_52[group][$_var_30] as $_var_57 => $_var_59) {
						if (is_array($_var_59)) {
							$_var_56 .= '<div><span>' . $_var_57 . '</span>';
							foreach ($_var_52[group][$_var_30][$_var_57] as $_var_60 => $_var_61) {
								$_var_28 = $_var_28 + 1;
								$_var_56 .= "<a  \r\n\t\t\t\t\t\t\t\t\thref=\"javascript:void(0);\" onClick=\"sethighlight('" . $_var_28 . '\');parent.framRight.location=\'' . $_var_61 . '\';"  >' . $_var_60 . '</a>';
							}
							$_var_56 .= '</div>';
						}
					}
					$_var_56 .= '</dl>';
				}
			}
		}
	}
	$_var_28 = NULL;
	return $_var_56;
}
function mymps_admin_purview($_var_39 = '')
{
	global $admin_menu;
	foreach ($admin_menu as $_var_4 => $_var_62) {
		if ($_var_4 != 'logout') {
			$_var_63 .= '<tr style="font-weight:bold; background-color:#dff6ff"><td colspan="2">' . $_var_62[name] . '</td></tr>';
			foreach ($_var_62[group][element] as $_var_64 => $_var_30) {
				if ($_var_64 != '系统帮助') {
					$_var_63 .= '<tr bgcolor="#f5fbff"><td>' . $_var_64 . '</td><td>';
					foreach ($_var_30 as $_var_52 => $_var_60) {
						$_var_63 .= '<label for="purview_' . $_var_52 . '" style="width:110px"><input type="checkbox" class="checkbox" name="purview[]" id="purview_' . $_var_52 . '" value="purview_' . $_var_52 . '"';
						$_var_63 .= is_array($_var_39) && in_array('purview_' . $_var_52, $_var_39) || empty($_var_39) ? 'checked' : "";
						$_var_63 .= '>' . $_var_52 . '</label> ';
					}
				}
			}
			$_var_63 .= '</td></tr>';
		}
	}
	return $_var_63;
}
function get_mymps_config_menu()
{
	global $admin_global_class;
	$_var_28 = 0;
	foreach ($admin_global_class as $_var_4 => $_var_5) {
		$_var_3 .= '<li><a id="i' . $_var_28 . '" href="javascript:noneblock(\'h' . $_var_28 . '\',\'i' . $_var_28 . '\')"';
		$_var_3 .= $_var_28 == 0 ? 'class="current"' : '';
		$_var_3 .= '>';
		$_var_3 .= $_var_5;
		$_var_3 .= '</a></li>';
		$_var_28++;
	}
	return $_var_3;
}
function get_waterimg_position($_var_5 = '')
{
	$_var_3 .= '<input type="radio" class="radio" name = "cfg_upimg_watermark_position"  value="0" ';
	$_var_3 .= $_var_5 == 0 ? 'checked' : '';
	$_var_3 .= ">
	          随机位置
		<table width=\"300\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
	      <tr>
	        <td width=\"33%\"><input type=\"radio\" class=\"radio\" name = \"cfg_upimg_watermark_position\"  value=\"1\"";
	$_var_3 .= $_var_5 == 1 ? 'checked' : '';
	$_var_3 .= ">
	          顶部居左</td>
	        <td width=\"33%\"><input type=\"radio\" class=\"radio\" name = \"cfg_upimg_watermark_position\"  value=\"4\"";
	$_var_3 .= $_var_5 == 4 ? 'checked' : '';
	$_var_3 .= ">
	          顶部居中</td>
	        <td><input type=\"radio\" class=\"radio\" name = \"cfg_upimg_watermark_position\"  value=\"7\"";
	$_var_3 .= $_var_5 == 7 ? 'checked' : '';
	$_var_3 .= ">
	          顶部居右</td>
	      </tr>
	      <tr>
	        <td><input type=\"radio\" class=\"radio\" name = \"cfg_upimg_watermark_position\"  value=\"2\"";
	$_var_3 .= $_var_5 == 2 ? 'checked' : '';
	$_var_3 .= ">
	          左边居中</td>
	        <td><input type=\"radio\" class=\"radio\" name = \"cfg_upimg_watermark_position\"  value=\"5\"";
	$_var_3 .= $_var_5 == 5 ? 'checked' : '';
	$_var_3 .= ">
	          图片中心</td>
	        <td><input type=\"radio\" class=\"radio\" name = \"cfg_upimg_watermark_position\"  value=\"8\"";
	$_var_3 .= $_var_5 == 8 ? 'checked' : '';
	$_var_3 .= ">
	          右边居中</td>
	      </tr>
	      <tr>
	        <td><input type=\"radio\" class=\"radio\" name = \"cfg_upimg_watermark_position\"  value=\"3\"";
	$_var_3 .= $_var_5 == 3 ? 'checked' : '';
	$_var_3 .= ">
	          底部居左</td>
	        <td><input type=\"radio\" class=\"radio\" name = \"cfg_upimg_watermark_position\"  value=\"6\"";
	$_var_3 .= $_var_5 == 6 ? 'checked' : '';
	$_var_3 .= ">
	          底部居中</td>
	        <td><input name = \"cfg_upimg_watermark_position\" type=\"radio\" class=\"radio\"   value=\"9\"";
	$_var_3 .= $_var_5 == 9 ? 'checked' : '';
	$_var_3 .= ">\r\n          底部居右</td>\r\n      </tr>\r\n    </table>";
	return $_var_3;
}
function get_mymps_config_input()
{
	global $admin_global, $admin_global_class, $config_global;
	$_var_28 = 0;
	foreach ($admin_global_class as $_var_4 => $_var_65) {
		$_var_3 .= '<div id="h' . $_var_28 . '" class="mytable"';
		$_var_3 .= $_var_28 == 0 ? ' ' : ' style=\'display:none\'';
		$_var_3 .= '><table border="0" cellspacing="0" cellpadding="0" class="vbm"><tr class="firstr"><td colspan="5"><div class="left"><a href="javascript:collapse_change(\'' . $_var_28 . '\')">' . $_var_65 . '</a></div><div class="right"><a href="javascript:collapse_change(\'' . $_var_28 . '\')"><img id="menuimg_' . $_var_28 . '" src="template/images/menu_reduce.gif"/></a></div></td></tr><tbody id="menu_' . $_var_28 . '" style="display:"><tr style="font-weight:bold; height:24px; background-color:#f1f5f8"><td>相关说明</td><td>值</td><td>模板调用代码</td></tr>';
		foreach ($admin_global as $_var_4 => $_var_64) {
			if ($_var_64['class'] == $_var_65) {
				$_var_3 .= '<tr bgcolor="#ffffff"><td style="width:35%; line-height:22px">' . $_var_64['des'] . '</td><td>';
				if (in_array($_var_4, array('SiteDescription', 'SiteStat', 'cfg_forbidden_post_ip', 'cfg_forbidden_reg_ip', 'cfg_member_regplace', 'cfg_member_reg_content', 'cfg_site_open_reason', 'cfg_disallow_post_tel', 'cfg_allow_post_area'))) {
					$_var_3 .= '<textarea name="' . $_var_4 . '" style="height:100px; width:205px">' . $config_global[$_var_4] . '</textarea>';
				} elseif ($_var_4 == 'cfg_mappoint') {
					$_var_3 .= '<input name="' . $_var_4 . '" value="' . $config_global[$_var_4] . '" class="text" id="mappoint"/>';
					$_var_3 .= '<input type="button" class="gray mini" value="我要标注" onclick="javascript:setbg(\'地图标注\',500,250,\'../map.php?action=markpoint&width=500&height=250&title=default_map_point&p=' . $_var_66['cfg_mappoint'] . '\')"/>';
				} elseif ($_var_4 == 'SiteLogo') {
					$_var_3 .= '<img src="' . $_var_66['SiteUrl'] . $config_global[$_var_4] . '" class="noborder"/><br /><br />';
					$_var_3 .= '<input name="' . $_var_4 . '" value="' . $config_global[$_var_4] . '" class="text"/>';
				} elseif ($_var_4 == 'cfg_upimg_watermark_img') {
					$_var_3 .= '<img src="' . $config_global[$_var_4] . '" class="noborder"/><br /><br />';
					$_var_3 .= '<input name="' . $_var_4 . '" value="' . $config_global[$_var_4] . '" class="text" id="imgsrc"/>';
					$_var_3 .= '<label><input type="radio" class="radio" onclick=\'document.getElementById("f' . $_var_4 . "\").style.display = \"none\";' name=\"ifout\" value=\"no\" checked=\"checked\" class=\"radio\"/>远程图片</label>
					<label><input type=\"radio\" class=\"radio\" onclick='document.getElementById(\"f" . $_var_4 . "\").style.display = \"block\";' name=\"ifout\" value=\"yes\" class=\"radio\"/>本地上传</label>
					<iframe src=\"include/upfile.php?watermark=0\" width=\"450\" frameborder=\"0\" scrolling=\"no\" onload=\"this.height=iFrame1.document.body.scrollHeight\" id=\"f" . $_var_4 . '" style="display:none; margin-top:10px"></iframe>';
				} elseif ($_var_4 == 'cfg_member_verify') {
					$_var_3 .= '<label for=\'verify1\'><input ';
					$_var_3 .= $config_global['cfg_member_verify'] == '1' ? ' checked ' : '';
					$_var_3 .= ' id=\'verify1\' type="radio" class="radio" value="1" name="cfg_member_verify">不审核</label>&nbsp;&nbsp;';
					$_var_3 .= '<label for=\'verify2\'><input ';
					$_var_3 .= $config_global['cfg_member_verify'] == '2' ? ' checked ' : '';
					$_var_3 .= ' id=\'verify2\' type="radio" class="radio" value="2" name="cfg_member_verify">人工审核</label>&nbsp;&nbsp;';
					$_var_3 .= '<label for=\'verify3\'><input ';
					$_var_3 .= $config_global['cfg_member_verify'] == '3' ? ' checked ' : '';
					$_var_3 .= ' id=\'verify3\' type="radio" class="radio" value="3" name="cfg_member_verify">邮件审核</label>&nbsp;&nbsp;';
					$_var_3 .= '<label for=\'verify4\'><input ';
					$_var_3 .= $config_global['cfg_member_verify'] == '4' ? ' checked ' : '';
					$_var_3 .= ' id=\'verify4\' type="radio" class="radio" value="4" name="cfg_member_verify">短信验证审核</label>&nbsp;&nbsp;';
				} elseif ($_var_4 == 'cfg_if_info_verify') {
					$_var_3 .= '<label for=\'verifyy1\'><input ';
					$_var_3 .= $config_global['cfg_if_info_verify'] == '0' ? ' checked ' : '';
					$_var_3 .= ' id=\'verifyy1\' type="radio" class="radio" value="0" name="cfg_if_info_verify">不审核</label>&nbsp;&nbsp;';
					$_var_3 .= '<label for=\'verifyy2\'><input ';
					$_var_3 .= $config_global['cfg_if_info_verify'] == '1' ? ' checked ' : '';
					$_var_3 .= ' id=\'verifyy2\' type="radio" class="radio" value="1" name="cfg_if_info_verify">人工审核</label>';
				} elseif ($_var_4 == 'cfg_upimg_watermark_position') {
					$_var_3 .= get_waterimg_position($config_global[$_var_4]);
				} else {
					if ($_var_64['type'] == '布尔型') {
						$_var_3 .= '<select name="' . $_var_4 . '"/>';
						$_var_3 .= '<option value="1"';
						$_var_3 .= $config_global[$_var_4] == 1 ? ' selected=\'selected\' style=\'background-color:#6eb00c; color:white!important;\'' : "";
						$_var_3 .= '>是/开启</option>';
						$_var_3 .= '<option value="0"';
						$_var_3 .= $config_global[$_var_4] == 0 ? ' selected=\'selected\' style=\'background-color:#6eb00c; color:white!important;\'' : "";
						$_var_3 .= '>否/关闭</option>';
						$_var_3 .= '</select>';
					} else {
						$_var_3 .= '<input name="' . $_var_4 . '" value="' . $config_global[$_var_4] . '" class="text"/>';
					}
				}
				$_var_3 .= $_var_64['type'] == '布尔型' ? '</td><td width=30%>&nbsp;</td></tr>' : '</td><td width=30%>{$mymps_global[' . $_var_4 . ']}</td></tr>';
			}
		}
		$_var_3 .= '</tbody></table></div>';
		$_var_28 = $_var_28 + 1;
	}
	return $_var_3;
}
function iszero($_var_67)
{
	return $_var_67 == 0 ? 1 : $_var_67;
}
function getcwdOL()
{
	$_var_68 = $_SERVER[PHP_SELF];
	$_var_45 = explode('/', $_var_68);
	$_var_45 = $_var_45[sizeof($_var_45) - 1];
	return substr($_var_68, 0, strlen($_var_68) - strlen($_var_45) - 1);
}
function fetchtablelist($_var_69 = '')
{
	global $db;
	$_var_70 = explode('.', $_var_69);
	$_var_71 = $_var_70[1] ? $_var_70[0] : '';
	$_var_69 = str_replace('_', '\\_', $_var_69);
	$_var_72 = $_var_71 ? " FROM {$_var_71} LIKE '{$_var_70['1']}%'" : "LIKE '{$_var_69}%'";
	$_var_73 = $_var_33 = array();
	$_var_74 = $db->query("SHOW TABLE STATUS {$_var_72}");
	while ($_var_33 = $db->fetch_array($_var_74)) {
		$_var_33['Name'] = ($_var_71 ? "{$_var_71}." : '') . $_var_33['Name'];
		$_var_73[] = $_var_33;
	}
	return $_var_73;
}
function get_timezone_select($_var_1 = 'cfg_timezone', $_var_5 = '')
{
	$_var_75 = array('-12' => '(GMT -12:00) 埃尼威托克岛, 夸贾林环..', '-11' => '(GMT -11:00) 中途岛, 萨摩亚群岛', '-10' => '(GMT -10:00) 夏威夷', '-9' => '(GMT -09:00) 阿拉斯加', '-8' => '(GMT -08:00) 太平洋时间(美国和加拿..', '-7' => '(GMT -07:00) 山区时间(美国和加拿大..', '-6' => '(GMT -06:00) 中部时间(美国和加拿大..', '-5' => '(GMT -05:00) 东部时间(美国和加拿大..', '-4' => '(GMT -04:00) 大西洋时间(加拿大), 加..', '-3.5' => '(GMT -03:30) 纽芬兰', '-3' => '(GMT -03:00) 巴西利亚, 布宜诺斯艾利..', '-2' => '(GMT -02:00) 中大西洋, 阿森松群岛,..', '-1' => '(GMT -01:00) 亚速群岛, 佛得角群岛 ..', '0' => '(GMT) 卡萨布兰卡, 都柏林, 爱丁堡, ..', '1' => '(GMT +01:00) 柏林, 布鲁塞尔, 哥本哈..', '2' => '(GMT +02:00) 赫尔辛基, 加里宁格勒,..', '3' => '(GMT +03:00) 巴格达, 利雅得, 莫斯科..', '3.5' => '(GMT +03:30) 德黑兰', '4' => '(GMT +04:00) 阿布扎比, 巴库, 马斯喀..', '4.5' => '(GMT +04:30) 坎布尔', '5' => '(GMT +05:00) 叶卡特琳堡, 伊斯兰堡,..', '5.5' => '(GMT +05:30) 孟买, 加尔各答, 马德拉..', '5.75' => '(GMT +05:45) 加德满都', '6' => '(GMT +06:00) 阿拉木图, 科伦坡, 达卡..', '6.5' => '(GMT +06:30) 仰光', '7' => '(GMT +07:00) 曼谷, 河内, 雅加达', '8' => '(GMT +08:00) 北京, 香港, 帕斯, 新加..', '9' => '(GMT +09:00) 大阪, 札幌, 首尔, 东京..', '9.5' => '(GMT +09:30) 阿德莱德, 达尔文', '10' => '(GMT +10:00) 堪培拉, 关岛, 墨尔本,..', '11' => '(GMT +11:00) 马加丹, 新喀里多尼亚,..', '12' => '(GMT +12:00) 奥克兰, 惠灵顿, 斐济,..');
	$_var_5 = empty($_var_5) ? '8' : $_var_5;
	$_var_76 .= '<select name=' . $_var_1 . '>';
	foreach ($_var_75 as $_var_27 => $_var_77) {
		$_var_76 .= '<option value=' . $_var_27 . ' ' . ($_var_5 == $_var_27 ? 'selected' : "") . '>';
		$_var_76 .= $_var_77;
		$_var_76 .= '</option>';
	}
	$_var_76 .= '</select>';
	return $_var_76;
}