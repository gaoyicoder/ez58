<?php
/**
 * Created by PhpStorm.
 * User: gaoyi
 * Date: 6/29/17
 * Time: 10:27 AM
 */

$x1 = floatval($_REQUEST['x1']);
$x2 = floatval($_REQUEST['x2']);
$x3 = floatval($_REQUEST['x3']);

if($x1 != 0 && $x2 != 0 && $x3 != 0)
{
    $status = 1;
    $result = $x1+$x2*$x3;
} else {
    $status = 0;
    $result = 0;
}

echo json_encode(array("status" => $status, "result" => $result));