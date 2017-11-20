<?php
/**
 * Created by PhpStorm.
 * User: gaoyi
 * Date: 7/12/17
 * Time: 4:35 PM
 */

$info_id = $_REQUEST['infoid'] ? $_REQUEST['infoid'] : 0;
if($info_id) {

    $row = $db->getRow("SELECT * FROM `{$db_mymps}information` WHERE id = '{$info_id}'");
    include mymps_tpl('parking_drive_map');
} else {
    header("Location: index.php");
}