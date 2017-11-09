<?php
/**
 * Created by PhpStorm.
 * User: gaoyi
 * Date: 7/12/17
 * Time: 4:35 PM
 */

$catid = $_REQUEST['catid'] ? $_REQUEST['catid'] : 0;
if($catid) {

    $row = $db->getAll("SELECT i.* as info_id, i.title  FROM `{$db_mymps}information` AS i
                          WHERE i.catid= $catid");
    include mymps_tpl('parking_map');
} else {
    header("Location: index.php");
}