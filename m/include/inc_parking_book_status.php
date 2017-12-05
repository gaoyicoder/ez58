<?php
/**
 * Created by PhpStorm.
 * User: gaoyi
 * Date: 12/4/17
 * Time: 11:58 PM
 */

$result = false;
$row = $db -> getRow("SELECT * FROM `{$db_mymps}information` WHERE id = '$id'");

if($row) {
    if($row['book_uid']!="") {
        $result = true;
    }
}

echo json_encode(array('result'=>$result, 'rowContent' => $row));