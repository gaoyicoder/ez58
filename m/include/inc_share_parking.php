<?php
/**
 * Created by PhpStorm.
 * User: gaoyi
 * Date: 11/7/17
 * Time: 1:33 PM
 */
require_once MYMPS_DATA.'/info.type.inc.php';
CURSCRIPT != 'wap' && exit('FORBIDDEN');

$catid = isset($catid) ? intval($catid) : '';
$cityid = isset($cityid) ? intval($cityid) : '';
$areaid = isset($areaid) ? intval($areaid) : '';
$streetid = isset($streetid) ? intval($streetid) : '';

require_once MYMPS_DATA."/info_lasttime.php";
$authcodesettings = read_static_cache('authcodesettings');

if($action == 'post'){

    $content    = isset($content) ? textarea_post_change($content) : '';
    $result 	= verify_badwords_filter($mymps_global['cfg_if_info_verify'],$title,$content);
    $title 		= $qq." ".$result['title'];
    $content 	= $result['content'];
    $content	= preg_replace("/<a[^>]+>(.+?)<\/a>/i","",$content);
    $info_level = $result['level'];
    $mixcode     = isset($mixcode) ? trim($mixcode) : '';
    $manage_pwd  = isset($manage_pwd) ? trim($manage_pwd) : '';
    $begintime 	= $timestamp;
    $mappoint = $mappoint ? $mappoint : '';
    if ($mappoint) {
        $point = explode(",", $mappoint);
        $lng = $point[0];
        $lat = $point[1];
    }
    $lat = isset($lat) ? (float)$lat : '';
    $lng = isset($lng) ? (float)$lng : '';
    $activetime	= $endtime 	= intval($endtime);
    $endtime 	= ($endtime == 0)?0:($endtime*60)+$begintime;
    $d = $db->getRow("SELECT catname,dir_typename,modid,gid FROM `{$db_mymps}category` WHERE catid = '$catid'");
    $catname = $d['catname'];
    $dir_typename = $d['dir_typename'];
    if(!$mixcode || $mixcode != md5($cookiepre)){
        errormsg('系统判断您的来路不正确！');
    }

    $backurl = "javascript:history.back();";
    if(empty($catid)) redirectmsg('您选择发布的分类不存在!','index.php?mod=category');
//    if(!$areaid = $db -> getOne("SELECT areaid FROM `{$db_mymps}area` WHERE areaid = '$areaid'")){
//        redirectmsg("您选择发布的地区不存在!","index.php?mod=post&cityid=".$cityid."&catid=".$catid);
//    }
//    empty($areaid) && redirectmsg("请选择您要发布的地区!","index.php?mod=post&cityid=".$cityid."&catid=".$catid);
    empty($title) && redirectmsg("请输入信息标题!",$backurl);
//    empty($content) && redirectmsg("您还没有输入信息描述!",$backurl);
    empty($contact_who) && redirectmsg("联系人不能为空!",$backurl);
    empty($tel) && redirectmsg("联系电话不能为空!",$backurl);

    if($iflogin == 1){
        if($authcodesettings['memberpost'] == 1 && !$randcode = mymps_chk_randcode($checkcode)){
            redirectmsg("验证码输入错误，请返回重新输入",$backurl);
        }
    }elseif($iflogin == 0){
        if($authcodesettings['post'] == 1 && !$randcode = mymps_chk_randcode($checkcode)){
            redirectmsg("验证码输入错误，请返回重新输入",$backurl);
        }
        if(empty($manage_pwd)){
            redirectmsg("管理密码不能为空，该密码用于修改/删除该信息，请谨记!",$backurl);
        }
    }

    require_once MYMPS_INC."/upfile.fun.php";
    require_once MYMPS_DATA."/config.inc.php";

    mymps_check_upimage_wap("mymps_img_");

    if(!empty($mymps_global['cfg_disallow_post_tel']) && !empty($tel)){
        $disallow_tel = array();
        $disallow_tel = explode('=',$mymps_global['cfg_disallow_post_tel']);
        $disallow_telarray = explode(',',$disallow_tel[0]);
        if($disallow_tel[1] == -1){
            in_array($tel,$disallow_telarray) && redirectmsg("您的电话号码<b style='color:red'>".$tel."</b> 已被管理员加入黑名单!<br />如果您要继续操作，请联系客服。","index.php?mod=post&catid=".$catid."&cityid=".$cityid);
        } elseif($disallow_tel[1] == 0) {
            in_array($tel,$disallow_telarray) && $info_level = 0;
        }
        unset($disallow_tel,$disallow_telarray);
    }

    $ip = GetIP();
    //发布信息IP限制
    if(!empty($mymps_global['cfg_forbidden_post_ip'])){
        foreach(explode(",", $mymps_global['cfg_forbidden_post_ip']) as $ctrlip) {
            if(preg_match("/^(".preg_quote(($ctrlip = trim($ctrlip)), '/').")/", $ip)) {
                $ctrlip = $ctrlip.'%';
                redirectmsg("您当前的IP <b style='color:red'>".$ip."</b> 已被管理员加入黑名单，不允许发布信息！<br />如果您要继续操作，请联系客服。","index.php?mod=post&catid=".$catid."&cityid=".$cityid);
                exit;
            }
        }
    }

    $post_time = 1;
//    if(!empty($post_time)){
//        $count = mymps_count("information","WHERE ip = '$ip' AND begintime > (".$timestamp." - 60)");
//        $count >= $post_time && redirectmsg("您的发布时间太快了，休息一会儿。","index.php?mod=member&action=mypost");
//    }

    $img_count	= upload_img_num('mymps_img_');


    switch($id){
        case true:
            //修改信息
            if(!$db->getOne("SELECT COUNT(id) FROM `{$db_mymps}information` WHERE id = '$id' AND userid = '$s_uid'")){
                redirectmsg("您要修改的信息不存在或者不是您发布的！","javascript:history.back();");
                exit;
            }

            if(empty($iflogin)){
                redirectmsg("您还没有登录，不能修改信息！","javascript:history.back();");
                exit;
            }

            if(is_array($_FILES)){
                for($i=0;$i<count($_FILES);$i++){
                    $name_file = "mymps_img_".$i;
                    if($_FILES[$name_file]['name']){
                        $destination = "/information/".date('Ym')."/";
                        $mymps_image = start_upload($name_file,$destination,$mymps_global['cfg_upimg_watermark'],$mymps_mymps['cfg_information_limit']['width'],$mymps_mymps['cfg_information_limit']['height']);
                        if($row = $db -> getRow("SELECT path,prepath FROM `{$db_mymps}info_img` WHERE infoid = '$id' AND image_id = '$i'")){
                            @unlink(MYMPS_ROOT.$row['path']);
                            @unlink(MYMPS_ROOT.$row['prepath']);
                            $db->query("UPDATE `{$db_mymps}info_img` SET image_id = '$i' , path = '$mymps_image[0]' , prepath = '$mymps_image[1]' , uptime = '$timestamp' WHERE image_id = '$i' AND infoid = '$id'");
                        } else {
                            $db->query("INSERT INTO `{$db_mymps}info_img` (image_id,path,prepath,infoid,uptime) VALUES ('$i','$mymps_image[0]','$mymps_image[1]','$id','$timestamp')");
                        }
                        if($i===0) $db -> query("UPDATE `{$db_mymps}information` SET img_path = '$mymps_image[1]' WHERE id = '$id'");
                    }
                }
            }

            if(is_array($delinfoimg)){
                $img_path = $db ->getOne("SELECT img_path FROM `{$db_mymps}information` WHERE id = '$id'");
                foreach($delinfoimg as $key => $val){
                    if($val == 'on'){
                        $infoimgrow = $db -> getRow("SELECT id,path,prepath FROM `{$db_mymps}info_img` WHERE image_id = '$key' AND infoid = '$id'");
                        if($infoimgrow){
                            @unlink(MYMPS_ROOT.$infoimgrow['path']);
                            @unlink(MYMPS_ROOT.$infoimgrow['prepath']);
                            mymps_delete("info_img","WHERE id = '$infoimgrow[id]'");
                            if($infoimgrow['prepath'] == $img_path) $db->query("UPDATE `{$db_mymps}information` SET img_path = '' WHERE id = '$id'");
                        }
                        unset($infoimgrow);
                    }
                }
            }

            $sql = $k = $v = NULL;
            if(is_array($extra) && $d['modid'] > 1){
                foreach($extra as $k =>$v){
                    $sql .= is_array($v) ? "`".$k ."` = '".implode(',',$v)."',": "`".$k ."` = '$v',";
                }
                $sql = $sql ? substr($sql,0,-1) : NULL;
                if($sql){
                    $db->query("UPDATE `{$db_mymps}information_{$d[modid]}` SET {$sql} WHERE id = '$id'");
                    unset($sql);
                }
            }

            $manage_pwd = empty($manage_pwd) ? "" : "manage_pwd='".md5($manage_pwd)."',";
            $img_count 	= mymps_count("info_img","WHERE infoid = '$id'");
            $img_path	= $mymps_image[1] ? $mymps_image[1] : '';

            $sql 		= "UPDATE `{$db_mymps}information` SET {$manage_pwd} title = '$title',content = '$content',catid = '$catid',cityid='$cityid', areaid = '$areaid', streetid='$streetid',activetime = '$activetime', endtime = '$endtime' , info_level = '$info_level' , qq = '$qq' , email = '$email' , tel = '$tel' , contact_who = '$contact_who' , img_count = '$img_count' , mappoint = '$mappoint',catname='$d[catname]',dir_typename='$d[dir_typename]' WHERE id = '$id'";
            $db->query($sql);
            redirectmsg("操作成功！您已经成功修改该信息！","index.php?mod=member&action=mypost");

            break;
        case false:
            //发布信息
            if($iflogin == 1){

                //会员发布信息数量限制
                $row = $db->getRow("SELECT a.per_certify,a.com_certify,a.status,b.perday_maxpost FROM `{$db_mymps}member` AS a LEFT JOIN `{$db_mymps}member_level` AS b ON a.levelid = b.id WHERE a.userid = '$s_uid'");
                $perday_maxpost = $row['perday_maxpost'];
                if(empty($row['status'])){
                    redirectmsg('您账号当前为待审状态，暂不能发布信息！','javascipt:history.back();');
                }
//                if(!empty($perday_maxpost)){
//                    $count = mymps_count("information","WHERE userid LIKE '$s_uid' AND begintime > '".mktime(0,0,0)."'");
//                    $count >= $perday_maxpost && redirectmsg("很抱歉！您当前的会员级别每天只能发布 <b style='color:red'>".$perday_maxpost."</b> 条信息<br />如果您要继续操作，请联系客服。","javascipt:history.back();");
//                }

                if(!empty($perpost_money_cost)){
                    if($row['money_own'] < $perpost_money_cost){
                        redirectmsg('您当前金币余额不足，发布一条信息需要支付'.$perpost_money_cost.'个金币！','javascript:history.back();');
                        exit;
                    }
                }

                $userid = trim($s_uid);
                $perpost_money_cost = $mymps_global['cfg_member_perpost_consume'] ? $mymps_global['cfg_member_perpost_consume'] : 0 ;

                /*信息认证情况*/
                if($userid){
                    if($row['per_certify'] == 1 || $row['com_certify'] == 1){
                        $certify = 1;
                    }else{
                        $certify = 0;
                    }
                    unset($row);
                }

                $sql = "INSERT INTO `{$db_mymps}information` (title,content,begintime,activetime,endtime,catid,gid,catname,dir_typename,cityid,areaid,streetid,userid,ismember,info_level,qq,email,tel,contact_who,img_count,certify,ip,ip2area,mappoint,latitude,longitude) VALUES ('$title','$content','$begintime','$activetime','$endtime','$catid','$d[gid]','$catname','$dir_typename','$cityid','$areaid','$streetid','$userid','1','$info_level','$qq','$email','$tel','$contact_who','$img_count','$certify','$ip','wap','$mappoint','$lat','$lng')";
                /*积分变化*/
                $score_change = get_credit_score();
                $score_changer = $score_change['score']['rank']['information'];
                $score_changer = $score_changer == 0 ? '+0' : $score_changer;
                if($score_changer){
                    $db->query("UPDATE `{$db_mymps}member` SET score = score".$score_changer." WHERE userid = '$userid'");
                }
                $score_change = $score_changer = NULL;

                /*金币变化*/
                if(!empty($perpost_money_cost)){
                    $db->query("UPDATE `{$db_mymps}member` SET money_own = money_own-'$perpost_money_cost' WHERE userid = '$userid'");
                }

            }else{
                $manage_pwd = md5($manage_pwd);
                //游客发布信息数量限制
                if($mymps_global['cfg_if_nonmember_info'] == 1 && $mymps_global['cfg_nonmember_perday_post'] > 0){
                    $count = mymps_count("information","WHERE ip = '$ip' AND begintime > '".mktime(0,0,0)."' AND ismember = '0'");
                    $count >= $mymps_global[cfg_nonmember_perday_post] && redirectmsg("很抱歉！游客每天只能发布 <b style='color:red'>".$mymps_global[cfg_nonmember_perday_post]."</b> 条信息<br />如果您要继续操作，请联系客服。","index.php?mod=post&catid=".$catid."&cityid=".$cityid);
                }

                $sql = "INSERT INTO `{$db_mymps}information` (title,content,begintime,activetime,endtime,catid,gid,catname,dir_typename,cityid,areaid,streetid,ismember,info_level,qq,email,tel,contact_who,img_count,certify,ip,ip2area,manage_pwd,mappoint,latitude,longitude) VALUES ('$title','$content','$begintime','$activetime','$endtime','$catid','$d[gid]','$catname','$dir_typename','$cityid','$areaid','$streetid','0','$info_level','$qq','$email','$tel','$contact_who','$img_count','$certify','$ip','wap','$manage_pwd','$mappoint','$lat','$lng')";
            }

            $db -> query($sql);
            $id = $db -> insert_id();

            $k = $v = NULL;
            if(is_array($extra) && $d['modid'] > 1){
                foreach($extra as $k =>$v){
                    $v = is_array($v) ? implode(',',$v) : $v;
                    $sql1 .= ",`".$k."`";
                    $sql2 .= ",'$v'";
                }
                $sql = "(id.$sql1)VALUES('$id','','')";
                $db->query("INSERT INTO `{$db_mymps}information_{$d[modid]}` (`id`{$sql1})VALUES('$id'{$sql2})");
                unset($sql1,$sql2);
            }

            //上传图片
            if($img_count > 0){
                for($i=0;$i<$img_count;$i++){
                    $name_file = "mymps_img_".$i;
                    if($_FILES[$name_file]['name']){
                        $destination="/information/".date('Ym')."/";
                        $mymps_image = start_upload($name_file,$destination,$mymps_global['cfg_upimg_watermark'],$mymps_mymps['cfg_information_limit']['width'],$mymps_mymps['cfg_information_limit']['height']);
                        $db -> query("INSERT INTO `{$db_mymps}info_img` (image_id,path,prepath,infoid,uptime) VALUES ('$i','$mymps_image[0]','$mymps_image[1]','$id','$begintime')");
                    }
                    if($i === 0) $img_path = $mymps_image[1];
                }
                $db -> query("UPDATE `{$db_mymps}information` SET img_path = '$img_path' WHERE id = '$id'");
            }

            $msg = $info_level > 0 ? '成功发布一条信息!' : '您的信息审核通过后将显示在网站上!';

            header("Location: "."index.php?mod=parking_info&id=".$id);
//            redirectmsg($msg,'index.php?mod=parking_info&id='.$id);
            break;
    }

} else if($catid){
    $new_lat = (float)$_GET['new_lat'];
    $new_lng = (float)$_GET['new_lng'];
    if ($new_lat && $new_lng) {
        $cityid = mgetcookie('cityid');
        $new_cityid = get_latlng2cityid($new_lat,$new_lng);
        if ($new_cityid != $cityid) {
            msetcookie('cityid',$new_cityid);
            header("Location: index.php?mod=share_parking&catid=$catid");
        }
    }

    //信息填写页
    $cat = $db -> getRow("SELECT catid,catname,parentid,modid,if_upimg,gid,if_mappoint FROM `{$db_mymps}category` WHERE catid = '$catid'");
    $cat['parentname'] = $db -> getOne("SELECT catname FROM `{$db_mymps}category` WHERE catid = '$cat[parentid]'");
    if($cat['parentid'] == 0){
        //如果为根分类
        $categories = get_categories_tree($catid,'category');
        include mymps_tpl('post_cat');
    } elseif($cat['parentid'] > 0){
        //如果不是根分类
        if($iflogin != 1){
            if($mymps_global['cfg_if_nonmember_info'] != 1){
                //游客不能发布信息
                $returnurl = 'index.php?mod=post&catid='.$catid;
                $returnurl = urlencode($returnurl);
                redirectmsg("请登录后发布信息！","index.php?mod=login&returnurl=".$returnurl);
            }
        }elseif($user = $db -> getRow("SELECT qq,email,mobile,cname FROM `{$db_mymps}member` WHERE userid = '$s_uid'")){
            $info['tel'] = $user['mobile'];
            $info['contact_who'] = $s_uid;
            $info['qq'] = $user['qq'];
        }

        //如果为三级分类
        if($child = $db ->getAll("SELECT catid,catname FROM `{$db_mymps}category` WHERE parentid = '$catid'")){
            $info['catname'] = '<select name="catid" style="width:60%" onChange="location.href=\'index.php?mod=post&cityid='.$cityid.'&catid=\'+(this.options[this.selectedIndex].value)">';
            $info['catname'] .= '<option value="">请选择子分类</option>';
            foreach($child as $k => $v){
                $info['catname'] .= '<option value="'.$v[catid].'">'.$v[catname].'</option>';
            }
            $info['catname'] .= '</select>';
        }else{
            $info['catname'] = $db ->getOne("SELECT catname FROM `{$db_mymps}category` WHERE catid = '$catid'");
        }

        $return_url = 'index.php?mod=post&catid='.$catid;
        $show_mod_option = return_category_info_options($cat['modid']);
        $mixcode = md5($cookiepre);

        if($iflogin==1){
            $info['imgcode']= $authcodesettings['memberpost'] == 1 ? 1 : '';
        }else{
            $info['imgcode']= $authcodesettings['post'] == 1 ? 1 : '';
        }
        include mymps_tpl("post_parking");
    }

}



//以下不要修改
function GetInfoLastTimeParking($lasttime='',$formname='endtime',$type='pc'){
    $info_lasttime = array();

    $info_lasttime[1] 	= '1分钟';
    $info_lasttime[2] 	= '2分钟';
    $info_lasttime[3] 	= '3分钟';
    $info_lasttime[5] 	= '5分钟';
    $info_lasttime_form = "<select name='$formname' id='$formname' ".($type == 'pc'? 'class="input" require="true" datatype="limit"':'')." msg=\"请选择信息的有效期限\">";
    $info_lasttime_form .= "<option value=''>请选择有效期限</option>";
    foreach($info_lasttime as $k=>$v){
        if($k==$lasttime) $info_lasttime_form .= "<option value='$k' selected>$v</option>\r\n";
        else $info_lasttime_form .= "<option value='$k'>$v</option>\r\n";
    }
    $info_lasttime_form .= "</select>\r\n";
    return $info_lasttime_form;
}


function check_upimage_wap($file="filename")
{
    global $mymps_global;
    $size=$mymps_global['cfg_upimg_size']*1024;
    $upimg_allow = explode(',',$mymps_global['cfg_upimg_type']);
    if($_FILES[$file]['size']>$size){
        redirectmsg('上传文件应小于'.$mymps_global['cfg_upimg_size'].'KB','javascript:history.back()');
    }

    if(!in_array(FileExt($_FILES[$file]['name']),$upimg_allow)){
        redirectmsg('系统只允许上传'.$mymps_global['cfg_upimg_type'].'格式的图片！','javascript:history.back()');
    }

    if(!preg_match('/^image\//i',$_FILES[$file]['type'])){
        redirectmsg ('很抱歉，系统无法识别您上传的文件的格式，请换一张图片上传！','javascript:history.back()');
    }
    return true;
}

function mymps_check_upimage_wap($file="filename")
{
    if(is_array($_FILES)){
        for($i=0;$i<count($_FILES);$i++){
            if($_FILES[$file.$i]['name']){
                check_upimage_wap($file.$i);
            }
        }
    }
}

function get_upload_image_view_wap($if_upimg = 1)
{
    global $mymps_global,$db,$db_mymps;
    if($if_upimg == 1){
        $cfg_upimg_number = $mymps_global[cfg_upimg_number]?$mymps_global[cfg_upimg_number]:'3';
        for($i=0;$i<$cfg_upimg_number;$i++){;
            $mymps .= '<input class="input" style="width:210px;overflow: hidden;padding:5px 0;" type="file" name="mymps_img_'.$i.'" datatype="filter" msg="图片文件格式不正确">';
        }
    }
    return $mymps;
}

function get_upload_image_view_parking($if_upimg = 1 , $infoid = '',$number='')
{
    global $mymps_global,$db,$db_mymps;
    if($if_upimg == 1){
        $cfg_upimg_number = $number ? $number : ($mymps_global['cfg_upimg_number']?$mymps_global['cfg_upimg_number']:4);
        $mymps='<script type="text/javascript">$(function () {';
        for($i=0;$i<$cfg_upimg_number;$i++){
            $mymps.='$("#mymps_img_'.$i.'").uploadPreview({ Img: "ImgPr'.$i.'", Width: 108, Height: 108, MaxSize:'.$mymps_global['cfg_upimg_size'].' });';
        }
        $mymps .= '});</script>';
        for($i=0;$i<$cfg_upimg_number;$i++){
            $mymps.='<div class="onea_dd" style="margin: 5px 30%">
                    <div style="display: none;" class="viewarea"><img id="ImgPr'.$i.'" src="'.$mymps_global['SiteUrl'].'/template/default/images/post/defaultimg.gif"/></div>
					<div class="clearfix"></div>
					<div class="a_ddarea">
                    <input type="file" name="mymps_img_'.$i.'" id="mymps_img_'.$i.'" class="comment-pic-upd" />
                    <img src="'.$mymps_global['SiteUrl'].'/template/default/images/post/addimg.gif" alt="上传照片" title="上传图片">
                    </div>
                </div>';
        }
    }
    return $mymps;
}