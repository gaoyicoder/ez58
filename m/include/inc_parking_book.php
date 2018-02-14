<?php
/**
 * Created by PhpStorm.
 * User: gaoyi
 * Date: 11/10/17
 * Time: 1:16 PM
 */

$result = false;
$status = 0;
if ($s_uid) {
    $info_id = $_REQUEST['id'] ? $_REQUEST['id'] : '';
    if($info_id) {
        $row = $db->getRow("SELECT * FROM `{$db_mymps}information` WHERE id = '{$info_id}'");
        if($row) {
            $row_user = $db->getRow("SELECT * FROM `{$db_mymps}member` WHERE userid = '{$s_uid}'");
            if ($row_user['money_own'] < $row['content']) {
                $result = false;
                $status = 1;
            } else {
                if($row['book_uid'] == '' ) {
                    $result = true;
                    $db->query("UPDATE `{$db_mymps}information` SET book_uid='{$s_uid}',book_status=0 WHERE id = '{$info_id}'");
                } else {
                    $result = false;
                    $status = 2;
                }
            }
        }
    }
}
echo json_encode(array('result'=>$result,'status' => $status));