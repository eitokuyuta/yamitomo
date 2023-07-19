<?php
	/*-------------------------------------------------------------------------------------
	*	good badの数値が更新されていたら、取得する。
	*	取得の仕方は、js側でcurrentTimeがこのloadStastic.phpの後に更新される。
	*	数値が更新されるのは、updateStatistic.php先であり、その更新をこのページで拾う形になるので
	*	誤差はない。												yuta eitoku
	---------------------------------------------------------------------------------------*/
	
	require_once('./databaseInit.php');
	require_once('./jsonEncode.php');
	require_once('./sanitize.php');
	
	session_start();	
	$_chat = array();

	$currentTime = $_POST['currentTime'];

	$data = new databaseInit();

	if(isset($_POST['category'])){
		$query = "SELECT * FROM statistic WHERE lastTime > :currentTime AND category = :category";
		$result = $data->db->prepare($query);
		$result->bindValue(':currentTime', $currentTime);
		$result->bindValue(':category', sanitize($_POST['category']));

	}else if(isset($_POST['serchWord'])){
		$query = "SELECT * FROM statistic WHERE lastTime > :currentTime AND title LIKE :serchWord";
		$result = $data->db->prepare($query);
		$result->bindValue(':currentTime', $currentTime);
		$result->bindValue(':serchWord', '%'. sanitize($_POST['serchWord']) .'%');
	}else{
		$query = "SELECT * FROM statistic WHERE lastTime > :currentTime";
		$result = $data->db->prepare($query);
		$result->bindValue(':currentTime', $currentTime);
	}
	
	$result->execute();
	$result = $result->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $val){
		$_chat[$val["id"]] = $val;
	}
	if(count($_chat) != 0){
		$json = new jsonEncode($_chat, current($_chat)['title']);
		echo $json->encJson();
	}
?>