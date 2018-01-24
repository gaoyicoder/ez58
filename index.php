<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 软件作者: 郑州维维信息技术有限公司
`*/
define('IN_SMT', true);
define('IN_MYMPS', true);
define('CURSCRIPT','index');

require_once dirname(__FILE__)."/include/global.php";
require_once MYMPS_DATA."/config.php";
require_once MYMPS_DATA."/config.db.php";
require_once MYMPS_INC."/db.class.php";

ifsiteopen();

if($fromuid && $mymps_global['cfg_if_affiliate'] == 1){
	msetcookie("fromuid",$fromuid,3600*24);
	msetcookie("fromip",GetIP(),3600*24);
}

if($cityid) msetcookie('cityid',$cityid,3600*24);

if(!$cityid && !is_robot()){
	if(in_array($mymps_global['cfg_redirectpage'],array('nchome','ncchangecity'))){
		$ip = GetIP();
		if(is_array($fromcity = get_ip2city($ip))){
			@header("location:".$fromcity['domain']);
			exit;
		} elseif($mymps_global['cfg_redirectpage'] == 'ncchangecity') {
			$loc					= get_location('index');
			$page_title				= $loc['page_title'];
			$seo = get_seoset();
			$seo['seo_keywords'] = str_replace('{city}','',$seo['seo_keywords']);
			$seo['seo_description'] = str_replace('{city}','',$seo['seo_description']);
			$mymps_global = array_merge($mymps_global,$seo);
			globalassign();
			include mymps_tpl('changecity');
			exit;
		}
	}elseif($mymps_global['cfg_redirectpage'] == 'changecity') {
		$loc					= get_location('index');
		$page_title				= $loc['page_title'];
		$seo = get_seoset();
		$mymps_global = array_merge($mymps_global,$seo);
		globalassign();
		$ip = GetIP();
		$fromcity = get_ip2city($ip);
		include mymps_tpl('changecity');
		exit;
	}elseif(is_numeric($mymps_global['cfg_redirectpage'])){
		$r = get_city_caches($mymps_global['cfg_redirectpage']);
		$r['domain'] = $r['domain'] ? $r['domain'] : $mymps_global['SiteUrl'];
		@header("location:".$r['domain']);
		unset($r);
		exit;
	}
}

$cache = get_cache_config();
require_once MYMPS_INC.'/cachepages.class.php';
$cachepages = new cachepages($cache['site']['time'],'index_'.$cityid);
$cachetime = $cache['site']['time'] > 0 ? true : false;
$cachepages->cacheCheck();
unset($cache);

$tpl_index = get_tpl_index();
$tpl_index = $tpl_index['tpl_set'];

$city = get_city_caches($cityid);
$city['cityid'] && $city_limit = "AND a.cityid = '$city[cityid]'";

$maincity = get_city_caches(0);
$independency = explode(',',$mymps_global['cfg_independency']);
$independency = is_array($independency) ? $independency : array();


/*友情链接*/
if(!$cityid || in_array('friendlink',$independency)){
	$friendlink = $city['flink'] ? $city['flink'] : $maincity['flink'];
}elseif($cityid){
	$friendlink = $city['flink'];
}
unset($city['flink'],$maincity['flink']);


/*焦点图*/
$focus = mymps_get_focus('index',3,$cityid);
if(!$cityid || in_array('focus',$independency)){
	$focus = $focus ? $focus : mymps_get_focus('index',3,NULL);
}

/*网站公告*/
$announce = mymps_get_announce($tpl_index['announce'],$cityid);
if(!$cityid || in_array('announce',$independency)){
	$announce = $announce ? $announce : mymps_get_announce($tpl_index['announce'],NULL);
}

/*便民电话*/
$telephone = mymps_get_telephone($tpl_index[telephone],$cityid);
if(!$cityid || in_array('telephone',$independency)){
	$telephone = $telephone ? $telephone : mymps_get_telephone($tpl_index['telephone'],NULL);
}

/*生活百宝箱*/
$lifebox = mymps_get_lifebox($tpl_index[lifebox],$cityid);
if(!$cityid || in_array('lifebox',$independency)){
	$lifebox = $lifebox ? $lifebox : mymps_get_lifebox($tpl_index['lifebox'],NULL);
}

/*广告*/
if(in_array('advertisement',$independency)){
	$city['advertisement'] = empty($city['advertisement']) ? $maincity['advertisement'] : $city['advertisement'];
}
$maincity = NULL;
$loc					= get_location('index');
$location				= $loc['location'];
$page_title				= $loc['page_title'];
unset($loc);

$seo = get_seoset();
if(!$city['cityid']){
	$city['title'] = $page_title;
	$city['keywords'] = str_replace('{city}','',$seo['seo_keywords']);
	$city['description'] = str_replace('{city}','',$seo['seo_description']);
} else {
	$city['title'] = $city['title'] ? $city['title'] : $page_title;
	$city['keywords'] = $city['keywords'] ? $city['keywords'] : str_replace('{city}',$city['cityname'],$seo['seo_keywords']);
	$city['description'] = $city['description'] ? $city['description'] : str_replace('{city}',$city['cityname'],$seo['seo_description']);
}
$mymps_global = array_merge($mymps_global,$seo);

$advertisement			= get_advertisement('index');
$adveritems				= $city['advertisement'];
$advertisement['type']	= $advertisement['all'] ? (is_array($advertisement['type']) ? array_merge($advertisement['all']['type'],$advertisement['type']) : $advertisement['all']['type']) : $advertisement['type'];

globalassign();

if($tpl_index['banmian'] == 'portal'){
	
//	$index_cat = get_categories_tree(0,'category');

    $index_cat = get_categories_tree_share(false);

	if(ifplugin('group')){
		require_once MYMPS_ROOT.'/plugin/group/include/functions.php';
		$groups = mymps_get_groups(3,NULL,$cityid);
		$groupclass = get_group_class();
	}
	if(ifplugin('goods')){
		require_once MYMPS_ROOT.'/plugin/goods/include/functions.php';
		$goods = mymps_get_goods($tpl_index['goods'],1,NULL,NULL,NULL,NULL,$cityid);
	}
	$in = $tpl_index['portal']['ershou'].','.$tpl_index['portal']['zhaopin'].','.$tpl_index['portal']['ershoufang'].','.$tpl_index['portal']['jianli'].','.$tpl_index['portal']['zufang'];
	if($query = $db->query("SELECT catid,dir_typename FROM `{$db_mymps}category` WHERE catid IN($in)")){
		while($row = $db->fetchRow($query)){
			$hd[$row['catid']]['dir_typename'] = $row['dir_typename'];
		}
	}
	
	$portaluri['moreershou'] = Rewrite('category',array('catid'=>$tpl_index['portal']['ershou'],'dir_typename'=>$hd[$tpl_index['portal']['ershou']]['dir_typename']));
	$portaluri['catidershou'] = $tpl_index['portal']['ershou'];

	$portaluri['morezufang'] = Rewrite('category',array('catid'=>$tpl_index['portal']['zufang'],'dir_typename'=>$hd[$tpl_index['portal']['zufang']]['dir_typename']));
	$portaluri['catidzufang'] = $tpl_index['portal']['zufang'];
	$portaluri['postzhaopin'] = $mymps_global['cfg_postfile'].'?catid='.$tpl_index['portal']['zhaopin'];
	$portaluri['morezhaopin'] = Rewrite('category',array('catid'=>$tpl_index['portal']['zhaopin'],'dir_typename'=>$hd[$tpl_index['portal']['zhaopin']]['dir_typename']));
	$portaluri['catidzhaopin'] = $tpl_index['portal']['zhaopin'];
	$portaluri['moreershoufang'] = Rewrite('category',array('catid'=>$tpl_index['portal']['ershoufang'],'dir_typename'=>$hd[$tpl_index['portal']['ershoufang']]['dir_typename']));
	$portaluri['catidershoufang'] = $tpl_index['portal']['ershoufang'];
	$portaluri['morejianli'] = Rewrite('category',array('catid'=>$tpl_index['portal']['jianli'],'dir_typename'=>$hd[$tpl_index['portal']['jianli']]['dir_typename']));
	$portaluri['catidjianli'] = $tpl_index['portal']['jianli'];
	$arr = '';
	$zhaopin = array();
	$query = $db -> query("SELECT a.cityid,a.id,a.title,a.ifred,a.ifbold,a.dir_typename,g.{$tpl_index[portali][company]} FROM `{$db_mymps}information` AS a LEFT JOIN `{$db_mymps}information_{$tpl_index[portal][zhaopinmod]}` AS g ON a.id = g.id WHERE ".get_children($tpl_index[portal][zhaopin])." AND (a.info_level = 1 OR a.info_level = 2) {$city_limit} ORDER BY a.begintime DESC LIMIT 0,12");
	
	while($row = $db -> fetchRow($query)){
		$arr['id']        = $row['id'];
		$arr['title']     = $row['title'];
		$arr['ifred']     = $row['ifred'];
		$arr['ifbold']    = $row['ifbold'];
		$arr[$tpl_index['portali']['company']] = $row[$tpl_index['portali']['company']];
		$arr['uri']       = Rewrite('info',array('id'=>$row['id'],'dir_typename'=>$row['dir_typename'],'cityid'=>$row['cityid']));
		$zhaopin[$row['id']]      = $arr;
	}
	$arr = '';
	$jianli = array();
	$query = $db -> query("SELECT a.id,a.title,a.ifred,a.ifbold,a.contact_who,a.dir_typename,a.cityid FROM `{$db_mymps}information` AS a WHERE ".get_children($tpl_index[portal][jianli])." AND (a.info_level = 1 OR a.info_level = 2) {$city_limit} ORDER BY a.begintime DESC LIMIT 0,4");
	if($query){
		while($row = $db -> fetchRow($query)){
			$arr['id']        = $row['id'];
			$arr['title']     = $row['title'];
			$arr['ifred']     = $row['ifred'];
			$arr['ifbold']    = $row['ifbold'];
			$arr['contact_who']    = $row['contact_who'];
			$arr['uri']       = Rewrite('info',array('id'=>$row['id'],'dir_typename'=>$row['dir_typename'],'cityid'=>$row['cityid']));
			$jianli[$row['id']]      = $arr;
		}
	}
	$arr = '';
	$esf = array();
	$query = $db -> query("SELECT a.cityid,a.id,a.title,a.ifred,a.ifbold,a.img_path,a.dir_typename,g.{$tpl_index[portali][acreage]},g.{$tpl_index[portali][prices]} FROM `{$db_mymps}information` AS a LEFT JOIN `{$db_mymps}information_{$tpl_index[portal][ershoufangmod]}` AS g ON a.id = g.id WHERE ".get_children($tpl_index[portal][ershoufang])." AND (a.info_level = 1 OR a.info_level = 2) {$city_limit} ORDER BY a.begintime DESC LIMIT 0,12");
	while($row = $db -> fetchRow($query)){
		$arr['id']        = $row['id'];
		$arr['title']     = $row['title'];
		$arr['ifred']     = $row['ifred'];
		$arr['ifbold']    = $row['ifbold'];
		$arr['img_path']   = $row['img_path'];
		$arr[$tpl_index[portali][prices]] = $row[$tpl_index[portali][prices]];
		$arr[$tpl_index[portali][acreage]]    = $row[$tpl_index[portali][acreage]];
		$arr['uri']       = Rewrite('info',array('id'=>$row['id'],'dir_typename'=>$row['dir_typename'],'cityid'=>$row['cityid']));
		$esf[$row['id']]      = $arr;
	}
	
	$arr = '';
	$czf = array();
	$query = $db -> query("SELECT a.cityid,a.id,a.title,a.ifred,a.ifbold,a.img_path,a.dir_typename FROM `{$db_mymps}information` AS a WHERE ".get_children($tpl_index[portal][zufang])." AND (a.info_level = 1 OR a.info_level = 2) {$city_limit} ORDER BY a.begintime DESC LIMIT 0,4");
	while($row = $db -> fetchRow($query)){
		$arr['id']        = $row['id'];
		$arr['title']     = $row['title'];
		$arr['ifred']     = $row['ifred'];
		$arr['ifbold']    = $row['ifbold'];
		$arr['img_path']   = $row['img_path'];
		$arr['uri']       = Rewrite('info',array('id'=>$row['id'],'dir_typename'=>$row['dir_typename'],'cityid'=>$row['cityid']));
		$czf[$row['id']]      = $arr;
	}
	
	unset($arr);
	$ershou = $ershou_img = array();
	$inershou = get_children($tpl_index[portal][ershou]);
	$query	 = $db -> query("SELECT a.cityid,a.id,a.title,a.ifred,a.ifbold,a.begintime,a.catname,a.dir_typename FROM {$db_mymps}information AS a  WHERE ".$inershou." AND (a.info_level = 1 OR a.info_level = 2) {$city_limit} ORDER BY a.begintime DESC LIMIT 0,10");
	while($row   = $db -> fetchRow($query)){
		$arr['id'] 	      = $row['id'];
		$arr['title'] 	  = $row['title'];
		$arr['begintime'] = $row['begintime'];
		$arr['catname']	  = $row['catname'];
		$arr['ifred']	  = $row['ifred'];
		$arr['ifbold']	  = $row['ifbold'];
		$arr['uri']		  = Rewrite('info',array('id'=>$row['id'],'dir_typename'=>$row['dir_typename'],'cityid'=>$row['cityid']));
		$ershou[$row['id']] = $arr;
	}
	$arr = '';
	$query = $db -> query("SELECT a.cityid,a.id,a.title,a.img_path,a.dir_typename FROM `{$db_mymps}information` AS a WHERE ".$inershou." AND (a.info_level = 1 OR a.info_level = 2) AND img_path != '' {$city_limit} ORDER BY a.begintime DESC LIMIT 0,6");
	if($query){
		while($row = $db -> fetchRow($query)){
			$arr['id']        = $row['id'];
			$arr['title']     = $row['title'];
			$arr['img_path']  = $row['img_path'];
			$arr['uri']       = Rewrite('info',array('id'=>$row['id'],'dir_typename'=>$row['dir_typename'],'cityid'=>$row['cityid']));
			$ershou_img[$row['id']]      = $arr;
		}
	}

} elseif($tpl_index['banmian'] == 'classic'){

//	$index_cat = get_categories_tree(0,'category');
    $index_cat = get_categories_tree_share(false);
	if(is_array($index_cat)){
		foreach($index_cat as $firstcatkey => $firstcatval){
			$idin = get_page_idin2("id","SELECT a.id FROM `{$db_mymps}information` AS a WHERE a.gid='$firstcatval[catid]' AND (a.info_level = 1 OR a.info_level = 2) {$city_limit} ORDER BY a.begintime DESC",$tpl_index['foreachinfo']);
			if($idin){
				$query	= $db -> query("SELECT a.cityid,a.catname,a.dir_typename,a.id,a.userid,a.catid,a.title,a.ifred,a.ifbold,a.begintime FROM `{$db_mymps}information` AS a WHERE a.id IN ($idin) ORDER BY a.begintime DESC");
				$index_cat[$firstcatval['catid']]['information'] = array();
				while($irow   = $db -> fetchRow($query)){
					$arr['id'] 	      = $irow['id'];
					$arr['title'] 	  = $irow['title'];
					$arr['begintime'] = $irow['begintime'];
					$arr['catname']	  = $irow['catname'];
					$arr['ifred']	  = $irow['ifred'];
					$arr['ifbold']	  = $irow['ifbold'];
					$arr['caturi']	  = Rewrite('category',array('dir_typename'=>$irow['dir_typename'],'catid'=>$irow['catid'],'domain'=>$city['domain']));
					$arr['uri']		  = Rewrite('info',array('id'=>$irow['id'],'dir_typename'=>$irow['dir_typename'],'cityid'=>$irow['cityid']));
					$index_cat[$firstcatval['catid']]['information'][] = $arr;
				}
			}
		}
	}
	$tpl_index[classic][cats] = $tpl_index[classic][cats] ? $tpl_index[classic][cats]+1 : '12';	
	
} elseif($tpl_index['banmian'] == 'simple'){
		
	if(ifplugin('goods')){
		require_once MYMPS_ROOT.'/plugin/goods/include/functions.php';
		$goods = mymps_get_goods($tpl_index['goods'],1,NULL,NULL,NULL,NULL,$cityid);
	}
//	$index_cat = get_categories_tree(0,'category');
    $index_cat = get_categories_tree_share(false);
	$firstcats = get_smplist_cats($tpl_index['smp_cats']['first'],$tpl_index['showstyle']);
	$secondcats = get_smplist_cats($tpl_index['smp_cats']['second'],$tpl_index['showstyle']);
	$thirdcats = get_smplist_cats($tpl_index['smp_cats']['third'],$tpl_index['showstyle']);
	$fourthcats = get_smplist_cats($tpl_index['smp_cats']['fourth'],$tpl_index['showstyle']);

}

include mymps_tpl('index_'.$tpl_index['banmian']);
is_object($db) && $db->Close();
$cachetime && $cachepages->caching();

$city = $maincity = $advertisement =NULL;
unset($city,$maincity,$advertisement);
?>