<?php
CURSCRIPT != 'wap' && exit('FORBIDDEN');

$catid = isset($catid) ? intval($catid) : 0;
$areaid = isset($areaid) ? intval($areaid) : 0;
$page = isset($page) ? intval($page) : 1;

$cat_list = get_categories_tree($catid);

if(empty($catid)){
	include mymps_tpl('category_all');
	exit;
}

if(!$cat = get_cat_info($catid)){
	errormsg('您所指定的栏目不存在或者已被删除！');
}

//手机版每页显示记录
$perpage = $mobile_settings['mobiletopicperpage'] ? $mobile_settings['mobiletopicperpage'] : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : '';
$page = empty($page) ? 1 : $page;

//筛选字段
$allow_identifier = allow_identifier();
$allow_identifier = $allow_identifier[$cat['modid']]['identifier'];
$allow_identifier = is_array($allow_identifier) ? $allow_identifier : array();
$allow_identifiers = array_merge(array('mod','catid','cityid','areaid','streetid','lat','lng','distance'),$allow_identifier);

//字段筛选
$mymps_extra_model = mod_identifier();
$mymps_extra_model = $mymps_extra_model[$cat['modid']];
$mymps_extra_model = is_array($mymps_extra_model) ? $mymps_extra_model : array();

foreach($mymps_extra_model as $key => $val){
	if(is_array($val['list'])){
		foreach($val['list'] as $k => $v){
			$mymps_extra_model[$key]['list'][$k]['uri'] = 'index.php?mod=category&cityid='.$cityid;
			foreach($allow_identifiers as $keys){
				if($v['identifier'] == $keys){
					$mymps_extra_model[$key]['list'][$k]['uri'] .= $v['id'] ? '&'.$keys.'='.$v['id'] : '';
				} else {
					$mymps_extra_model[$key]['list'][$k]['uri'] .= $$keys ? '&'.$keys.'='.$$keys : '';
				}
			}
			if($v['id'] == $$v['identifier']){
				$mymps_extra_model[$key]['list'][$k]['select'] .= 1;
			} else{
				$mymps_extra_model[$key]['list'][$k]['select'] .= 0;
			}
		}
	}
}

//分类筛选
$parentcats = get_parent_cats('category',$catid);
$parentcats = is_array($parentcats) ? array_reverse($parentcats) : '';

//构造SQL
$sq = $s = '';
if($cat['modid'] > 1){
	$s = "";
	foreach ($_GET as $key => $val){
		if(in_array($key,$allow_identifier) && !empty($$key)){
			$val =explode('~',$val);
			if($val[1]){
				$sq .= " AND g.`$key` <= '$val[1]'  AND g.`$key` >= '$val[0]'";
			}elseif($val[0] && isset($val[1])){
				$sq .= " AND g.`$key` >= '$val[0]'";
			}else{
				$sq .= " AND g.`$key` LIKE '%".$val[0]."%' ";
				//$sq .= " AND g.`$key` = '$val[0]' ";
			}
			$s = " LEFT JOIN `{$db_mymps}information_{$cat[modid]}` AS g ON a.id = g.id";
		}
	}
}
$cate_limit = $cat['parentid'] > 0 ? " AND ".get_children($catid) : " AND a.gid = '$catid'";
$lat =(float)$lat;
$lng = (float)$lng;
$distance = (float)$distance;
$distance = !in_array($distance,array('0.5','1','3','5')) ? '0' : $distance;

if($distance){
	$city_limit .= " AND latitude < '".($lat+$distance)."' AND latitude > '".($lat-$distance)."' AND longitude < '".($lng+$distance)."' AND longitude > '".($lng-$distance)."'";
}else{
	$city_limit  .= empty($areaid) ? "": " AND a.areaid = '$areaid'";
	$city_limit  .= empty($streetid) ? "": " AND a.streetid = '$streetid'";
}

$orderby	= $cat['parentid'] == 0 ? " ORDER BY a.upgrade_type DESC,a.begintime DESC" : " ORDER BY a.upgrade_type_list DESC,a.begintime DESC";

$param		= setparams($allow_identifiers);

$rows_num 	= $cat['totalnum'] =  $db -> getOne("SELECT COUNT(a.id) FROM `{$db_mymps}information` AS a {$s} WHERE a.info_level > 0 {$sq}{$cate_limit}{$city_limit}");
$totalpage = ceil($rows_num/$perpage);
$num = intval($page-1)*$perpage;

$idin = get_page_idin("id","SELECT a.id FROM `{$db_mymps}information` AS a {$s} WHERE (a.info_level = 1 OR a.info_level = 2) {$sq}{$cate_limit}{$city_limit}{$orderby}",$perpage);
if($distance){
    $id_online = array();
    $city_limit1 = "";
    $city_limit1  .= empty($areaid) ? "": " AND a.areaid = '$areaid'";
    $city_limit1 .= empty($streetid) ? "": " AND a.streetid = '$streetid'";
    $row_online = $db->getAll("SELECT a.id FROM `{$db_mymps}coords_sync` AS cs
                          INNER JOIN `{$db_mymps}information` AS a
                          ON cs.userid = a.userid
                          WHERE a.info_level > 0 {$cate_limit}{$city_limit}");
    foreach($row_online as $v) {
        $id_online[] = $v['id'];
        if (!strpos($idin, ','.$v['id'].',') && !strpos($idin, $v['id'].',') === 0) {
            $idin = $idin? $v['id'].','.$idin : $v['id'];
        }
    }
}

$idin = $idin ? " AND a.id IN (".$idin.") " : "";


$sql = "SELECT a.* FROM {$db_mymps}information AS a WHERE 1 {$idin} {$orderby}";
$infolist = $idin ? $db -> getAll($sql) : array();
foreach($infolist as $k => $row){
	$arr['id']              = $row['id'];
	$arr['title']           = $row['title'];
	$arr['hit']             = $row['hit'];
	$arr['img_path']        = $row['img_path'];
	$arr['ifred']        	= $row['ifred'];
	$arr['ifbold']        	= $row['ifbold'];
	$arr['certify']        	= $row['certify'];
	$arr['img_count']       = $row['img_count'];
	$arr['upgrade_type']	= !$cat['parentid'] ? $row['upgrade_type'] : $row['upgrade_type_list'];
	$arr['contact_who']     = $row['contact_who'];
	$arr['content']	        = $row['content'];
	$arr['begintime']       = $row['begintime'];

    if($distance) {
        $arr['info_distance'] = round(calculate_distance($lat, $lng, $row['latitude'], $row['longitude']), 2);
        if (in_array($arr['id'], $id_online)) {
            $arr['is_online'] = 1;
        }
    }

	if($row['upgrade_time'] > 0 && $row['upgrade_time'] < $timestamp) $db->query("UPDATE `{$db_mymps}information` SET upgrade_type = '1',upgrade_time = '0' WHERE id ='$row[id]'");
	if($row['upgrade_time_list'] > 0 && $row['upgrade_time_list'] < $timestamp) $db->query("UPDATE `{$db_mymps}information` SET upgrade_type_list = '1',upgrade_time_list = '0' WHERE id ='$row[id]'");
	if($row['upgrade_time_index'] > 0 && $row['upgrade_time_index'] < $timestamp) $db->query("UPDATE `{$db_mymps}information` SET upgrade_type_index = '1',upgrade_time_index = '0' WHERE id ='$row[id]'");
	$info_list[$row['id']]	= $arr;
	$ids .= $row['id'].',';
}
print_r($infolist);
if($cat['modid'] > 1 && $idin) {
	$des = get_info_option_array();
	$extra = $db ->getAll("SELECT a.* FROM `{$db_mymps}information_{$cat[modid]}` AS a WHERE 1 {$idin}"); 
	foreach($extra as $k => $v){
		unset($v['iid']);
		unset($v['content']);
		foreach($v as $u => $w){
			$g = get_info_option_titval($des[$u],$w);
			if($u != 'id' && !is_numeric($u)) $info_list[$v['id']]['extra'][$u] = $g['value'];
		}
	}
}

$pageline = NULL;
$pageview = pager();

//地区分类
if(!$city['cityid']){
	$hotcities = get_hot_cities();
}

include mymps_tpl('category_list');

function calculate_distance($lat1, $lon1, $lat2, $lon2)
{
    $R = 6371;
    $lat1 = doubleval($lat1);
    $lon1 = doubleval($lon1);
    $lat2 = doubleval($lat2);
    $lon2 = doubleval($lon2);
    $dLat = deg2rad($lat2-$lat1);  // deg2rad below
    $dLon = deg2rad($lon2-$lon1);
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $d = $R * $c * 1;
    return $d;
}
?>