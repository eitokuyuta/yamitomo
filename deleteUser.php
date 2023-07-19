<?php
/*-----------------------------------------------------
*	最終ログインから23時間が経過しているユーザーを削除する。
*	
*
------------------------------------------------------*/
require_once('./databaseInit.php');
date_default_timezone_set('Asia/Tokyo');
$currentTime =  date('Y-m-d H:i:s');

$data = new databaseInit();
$query = "DELETE FROM userid WHERE timelast < DATE_SUB(:currentTime, INTERVAL 12 HOUR)";
$result = $data->db->prepare($query);
$result->bindValue(':currentTime', $currentTime);
$result->execute();


?>