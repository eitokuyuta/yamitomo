<?php
	//	kari.phpのajax部分で使用
	//	chidの重複の回避関数
	//------------------------------------
	require_once('./databaseInit.php');
	require_once('./jsonEncode.php');
	$data = new databaseInit();
	$query = "SELECT max(chid) FROM storage";
	$result = $data->db->prepare($query);
	$result->execute();
	$result = $result->fetchAll(PDO::FETCH_ASSOC);
	$jsonData = new jsonEncode($result);
	$jsonData->encJson();

?>