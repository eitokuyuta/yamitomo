/*------------------------------------------------
*	log.phpに被リンクから飛んできた時に使用する。
*	
*	yuta eitoku
------------------------------------------------*/
<?php
session_start();
require_once('./sanitize.php');
require_once('./databaseInit.php');

$username = sanitize($_POST['username']);
$iconList = sanitize($_POST['icon']);

$_SESSION['username'] = $username;
$_SESSION['iconList'] = $iconList;

//useridの取得
$data = new databaseInit();
$result = $data->db->prepare("INSERT INTO userid (username, icon) VALUES (:username, :icon)");
$result->bindValue(':username', $username);
$result->bindValue(':icon', $iconList);
$result->execute();	

$result = $data->db->prepare("SELECT LAST_INSERT_ID()");
$result->execute();	
$user = $result->fetchAll(PDO::FETCH_ASSOC);
$_SESSION['userid'] = $user[0]['LAST_INSERT_ID()'];
	
?>