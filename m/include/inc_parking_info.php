<?php
CURSCRIPT != 'wap' && exit('FORBIDDEN');

$id = isset($_GET['id']) ? intval($_GET['id']) : '';
if(!$id) errormsg('信息主题ID不能为空！');

if(!$row = $db -> getRow("SELECT * FROM `{$db_mymps}information` WHERE id = '$id' AND info_level >= 1")){
	errormsg('该信息主题未通过审核或不存在！');
}

if ($row['book_uid'] != "") {
    $row['book_mobile'] = $db -> getOne("SELECT mobile FROM `{$db_mymps}member` WHERE userid = '".$row['book_uid']."'");
}
$cityid = $row['cityid'];
$city=get_city_caches($cityid);

$db->query("UPDATE `{$db_mymps}information` SET hit = hit + 1 WHERE id = '$id'");
$row['endtime1'] = $row['endtime'];
$row['endtime']	 = get_info_life_time1($row['endtime']);

$row['contactview'] = ($row['endtime'] == '<font color=red>已过期</font>' && $mymps_global['cfg_info_if_gq'] != 1) ? 0 : 1;


$rowr = $db -> getRow("SELECT catid,parentid,catname,template_info,modid,usecoin FROM `{$db_mymps}category` WHERE catid = '$row[catid]'");
$row['catid'] = $rowr['catid'];
$row['parentid'] = $rowr['parentid'];
$row['catname'] = $rowr['catname'];
$row['template_info'] = $rowr['template_info'];
$row['modid'] = $rowr['modid'];
$row['usecoin'] = $rowr['usecoin'];
$row['image'] = $row['img_count'] > 0 ? $db -> getAll("SELECT prepath,path FROM `{$db_mymps}info_img` WHERE infoid = '$id' ORDER BY id ASC") : false;

$mayi = $db->getRow("SELECT if_corp,per_certify,com_certify FROM `{$db_mymps}member` WHERE userid = '$row[userid]'");

$viewid = mgetcookie('viewid');
if($action =='seecontact'){
	if($iflogin==1){
		$money_own = $db -> getOne("SELECT money_own FROM `{$db_mymps}member` WHERE userid = '$s_uid'");
		include MYMPS_ROOT.'/member/include/common.func.php';
		if($money_own >= $row['usecoin']){
			$db -> query("UPDATE `{$db_mymps}member` SET money_own = money_own - '$row[usecoin]' WHERE userid = '$s_uid'");
			write_money_use("查看编号为".$id."的信息联系方式","<font color=red>扣除金币 ".$row[usecoin]." </font>");
			$echo = $row[qq] ? '<li><span class="attrName">联系 Q Q：</span><span class="attrVal"> '.$row[qq].'</span></li>':'';
            $echo .= '<li>
					<span class="attrName">联系电话：</span>
					<span class="attrVal"><a class="fred" href="tel:'.$row[tel].'">'.$row[tel].'</a>&nbsp;&nbsp;'.$row[contact_who].'</span>
				</li>
				<li>
					<p class="mt10">
						<a href="tel:'.$row[tel].'" class="fangico dianhua"><i></i>拨打电话</a>
                        <a href="sms:'.$row[tel].'" class="fangico duanxin"><i></i>短信咨询</a>
					</p>
				</li>';
			echo $echo;
		} else {
			echo '余额不足';
		}
		msetcookie('viewid',$id,3600*24);
	}else{
		echo '请先登录';
	}
	exit;
}

if($rowr['modid'] > 1){
	$extr = $db ->getRow("SELECT * FROM `{$db_mymps}information_{$rowr[modid]}` WHERE id ='$id'");
	if($extr){
		$des = get_info_option_array();
		unset($extr['iid'],$extr['id'],$extr['content']);
		foreach($extr as $k =>$v){
			$val = get_info_option_titval($des[$k],$v);
			$arr['title'] = $val['title'];
			$arr['value'] = $val['value'];
			$row['extra'][]=$arr;
			$row[$k] = $v;
		}
		$des = NULL;
	}
}
include mymps_tpl('parking_info');

function get_info_life_time1($time)
{
    global $timestamp;
    $last_time = round(($time > 0 ? ($time - $timestamp) : 0)/(60));
    if($last_time >= 5){
        $last_time = "还有<font color=green>$last_time</font>分钟过期";
    }elseif($last_time > 0 && $last_time < 5){
        $last_time = "还有<font color=red>$last_time</font>分钟过期";
    }elseif($last_time == 0){
        $last_time = '<font color=green>长期有效</font>';
    }else{
        $last_time = '<font color=red>已过期</font>';
    }
    return $last_time;
}
?>