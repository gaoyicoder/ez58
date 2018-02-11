<?php
/**
 * Created by PhpStorm.
 * User: gaoyi
 * Date: 11/02/2018
 * Time: 11:23 AM
 */

$info_id = $_REQUEST['id'] ? $_REQUEST['id'] : '';
if (!$info_id) {
    echo '该信息不存在！';
} else {
    $r = $db->getRow("SELECT a.*,b.modid FROM `{$db_mymps}information` AS a LEFT JOIN `{$db_mymps}category` AS b ON a.catid = b.catid {$wherea} AND a.id =" . $info_id);
    if (!empty($r['img_path'])) {
        $del = $db->getAll("SELECT path,prepath FROM `{$db_mymps}info_img` WHERE infoid='{$info_id}'");
        foreach ($del as $k => $v) {
            if ($v['path']) {
                @unlink(MYMPS_ROOT . $v['path']);
            }
            if ($v['prepath']) {
                @unlink(MYMPS_ROOT . $v['prepath']);
            }
        }
        mymps_delete('info_img', "WHERE infoid = '{$info_id}'");
    }
    mymps_delete('comment', "WHERE type = 'information' AND typeid = '{$info_id}'");
    if ($r[modid] > 1) {
        mymps_delete('information_' . $r[modid], "WHERE id = '{$info_id}'");
    }
    $db->query("DELETE FROM `{$db_mymps}information` WHERE id = " . $info_id);
    echo 'success';
}