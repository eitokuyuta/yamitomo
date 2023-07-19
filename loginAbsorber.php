<?php
session_start();
require_once('./databaseInit.php');
require_once('./sanitize.php');

if($_POST['username'] != '' && $_POST['iconList'] != ''){
	$_SESSION['username'] = sanitize($_POST['username']);
	$_SESSION['iconList'] = sanitize($_POST['iconList']);
	//useridの取得
	$data = new databaseInit();
	$result = $data->db->prepare("INSERT INTO userid (username, icon) VALUES (:username, :icon)");
	$result->bindValue(':username', $_SESSION['username']);
	$result->bindValue(':icon', $_SESSION['iconList']);
	$result->execute();	

	$result = $data->db->prepare("SELECT LAST_INSERT_ID()");
	$result->execute();	
	$user = $result->fetchAll(PDO::FETCH_ASSOC);
	$_SESSION['userid'] = $user[0]['LAST_INSERT_ID()'];
	
	header('Location: ./room.php');
	exit;
}else{
	header('Location: ./login.php');
	exit;
}
?>