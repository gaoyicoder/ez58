<?php
/**
 * Created by PhpStorm.
 * User: gaoyi
 * Date: 7/12/17
 * Time: 4:35 PM
 */

$catid = $_REQUEST['catid'] ? $_REQUEST['catid'] : 0;
if($catid) {

    $row = $db->getAll("SELECT cs.*, i.id, i.title as info_id FROM `{$db_mymps}coords_sync` AS cs
                          INNER JOIN `{$db_mymps}information` AS i
                          ON cs.userid = i.userid
                          WHERE i.catid= $catid");
    include mymps_tpl('sync_map');
} else {
    header("Location: index.php");
}