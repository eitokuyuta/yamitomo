<?php
require_once('./databaseInit.php');
date_default_timezone_set('Asia/Tokyo');
$currentTime =  date('Y-m-d H:i:s');

$userid = $_POST['userid'];
if(!isset($userid)){
	echo 'error:1003: not exist userid in $POST';
}
$data = new databaseInit();
$query = "UPDATE userid SET timelast = :currentTime WHERE id = :userid";
$result = $data->db->prepare($query);
$result->bindValue(':currentTime', $currentTime);
$result->bindValue(':userid',  htmlspecialchars($userid, ENT_QUOTES, "UTF-8"));
$result->execute();

?>