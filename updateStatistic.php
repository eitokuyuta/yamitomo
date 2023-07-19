<?php
	session_start();	
	require_once('./databaseInit.php');
	date_default_timezone_set('Asia/Tokyo');
	$currentTime =  date('Y-m-d H:i:s');

	if( !isset($_SESSION['userid'])){
		echo 'not found userid in update----.php';
		exit;
	}

	$id = $_POST['id'];
	$which = $_POST['which'];
	
	$data = new databaseInit();
	if($which == 'good'){
		$result = $data->db->prepare("UPDATE statistic SET good = good + 1 WHERE id = :id");
	}else if($which == 'bad'){
		$result = $data->db->prepare("UPDATE statistic SET bad = bad + 1 WHERE id = :id");
	}
	$result->bindValue(':id', $id);
	$result->execute();

	$query = 'UPDATE statistic SET lasttime = :currentTime WHERE id = :id';
	$result = $data->db->prepare($query);
	$result->bindValue(':currentTime', $currentTime);
	$result->bindValue(':id', htmlspecialchars($id, ENT_QUOTES, "UTF-8"));
	$result->execute();	

?>