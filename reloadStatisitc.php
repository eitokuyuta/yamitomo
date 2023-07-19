<?php
	session_start();
	require_once('./databaseInit.php');
	require_once('./jsonEncode.php');

	$max = $_POST['max'];
	$_chat = array();

	try{
		if($max == ''){ throw new Exception('error:reload');}
		
		//useridの取得
		$data = new databaseInit();
		if(isset($_POST['category'])){
			$query = "SELECT * FROM statistic WHERE id > :max AND category = :category ORDER BY id DESC";
			$result = $data->db->prepare($query);
			$result->bindValue(':max', $max, PDO::PARAM_INT);
			$result->bindValue(':category', $_POST['category']);

		}else if(isset($_POST['serchWord'])){
			$query = "SELECT * FROM statistic WHERE id > :max AND title LIKE :serchWord ORDER BY id DESC";
			$result = $data->db->prepare($query);
			$result->bindValue(':max', $max, PDO::PARAM_INT);
			$result->bindValue(':serchWord', '%'.$_POST['serchWord'].'%');
		}else{
			$result = $data->db->prepare("SELECT * FROM statistic WHERE id > :max ORDER BY id DESC");
			$result->bindValue(':max', $max, PDO::PARAM_INT);
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

	}catch( Exception $e ){
		echo $e;
		exit;
	}	


?>