<?php
/**
 * Created by PhpStorm.
 * User: gaoyi
 * Date: 11/12/17
 * Time: 5:36 PM
 */

$result = false;
if ($s_uid) {
    $info_id = $_REQUEST['id'] ? $_REQUEST['id'] : '';
    if($info_id) {
        $row = $db->getRow("SELECT * FROM `{$db_mymps}information` WHERE id = '{$info_id}'");
        if($row) {
            if($row['book_uid'] == $s_uid ) {
                $result = true;
                $db->query("UPDATE `{$db_mymps}information` SET book_status = 1 WHERE id = '{$info_id}'");
            }
        }
    }
}
echo json_encode(array('result'=>$result));