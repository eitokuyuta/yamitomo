<?php
require_once('./databaseInit.php');
date_default_timezone_set('Asia/Tokyo');	//php.iniのタイムゾーンを日本時間に設定する
$roomid = $_POST['roomid'];
if(!isset($roomid)){
	echo 'error:1004: not exist roomid in $POST';
}

$data = new databaseInit();
$query = "UPDATE room SET lasttime = :time WHERE id = :roomid";
$result = $data->db->prepare($query);
$result->bindValue(':roomid',  htmlspecialchars($roomid, ENT_QUOTES, "UTF-8"));
$result->bindValue(':time', date('Y-m-d H:i:s'));
$result->execute();
?>