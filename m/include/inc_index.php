<?php
CURSCRIPT != 'wap' && exit('FORBIDDEN');
$has_unread = 0;
if($s_uid) {
    $row_info = $db -> getRow("SELECT * FROM `{$db_mymps}information` WHERE userid = '$s_uid' AND read_status = 0 AND book_uid != '' ORDER BY begintime DESC");
    if ($row_info) {
        $has_unread = $row_info['id'];
        $db->query("UPDATE `{$db_mymps}information` SET read_status=1 WHERE userid = '{$s_uid}'");
    }
}
include mymps_tpl('index');

?>