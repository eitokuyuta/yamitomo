<?php
	require_once('./databaseInit.php');
	require_once('./jsonEncode.php');
	$data = new databaseInit();
	$roomid = isset($_POST['roomid']) ? $_POST['roomid'] : null;

	function responseJson($Jtext){
			$text = $Jtext;
			$jsonData = new jsonEncode($text);
			$jsonData = $jsonData->encJson();
			return $jsonData;
	}

	try{
		if( $roomid == null ){ throw new Exception(‘部屋情報がありません’); };
		//判定用のuseridテーブルのiine列のチェック
		
		$query = "SELECT iine FROM room WHERE id = :roomid";
		$result = $data->db->prepare($query);
		$result->bindValue(':roomid', $roomid);
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		
		$jsonData = new jsonEncode($result, $result[0]['iine']);
		$jsonData->encJson();
		exit;

	}catch(Exception $e){
		echo $e->getMessage();
		exit;
	}

?>