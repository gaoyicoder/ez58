<?php
/**
 * Created by PhpStorm.
 * User: gaoyi
 * Date: 11/10/17
 * Time: 1:16 PM
 */

$result = false;
if ($s_uid) {
    $info_id = $_REQUEST['id'] ? $_REQUEST['id'] : '';
    if($info_id) {
        $row = $db->getRow("SELECT * FROM `{$db_mymps}information` WHERE id = '{$info_id}'");
        if($row) {
            if($row['book_uid'] == '' ) {

                $db->query("UPDATE `{$db_mymps}member` SET book_uid='{$s_uid}',book_status=0 WHERE id = '{$info_id}'");
            }
        }
    }
}
echo json_encode(array('result'=>$result));