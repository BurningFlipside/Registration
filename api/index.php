<?php
header('Content-Type: application/json');
$ret = array();
$base = $_SERVER['REQUEST_URI'];
$ret['v1'] = $base.'v1';
echo json_encode($ret);
?>
