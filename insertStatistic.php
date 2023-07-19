<?php
	require_once('./databaseInit.php');
	session_start();	

	if( !isset($_SESSION['userid'])){
		header('Location: ./login.php');
		exit;
	}
	date_default_timezone_set('Asia/Tokyo');
	$currentTime =  date('Y-m-d H:i:s');
	$userid = $_POST['userid'];

	$data = new databaseInit();
	$result = $data->db->prepare("SELECT statisticTime FROM userid WHERE id = :userid");
	$result->bindValue(':userid', $userid);
	$result->execute();
	$result = $result->fetchAll(PDO::FETCH_ASSOC);
	try{
		foreach( $result as $col){
			if($col == NULL){ throw new Exception('時間が無効です。'); }

			$timestamp = strtotime($col['statisticTime']);		//strtotime : 秒単位のタイムスタンプに変換して差分を作る。
			$timestamp2 = strtotime($currentTime);

			if(ceil(($timestamp2 - $timestamp)/60) > 5){	//30分以上userがいないと時間が更新されないため削除
				$title = $_POST['statistic'];
				$category = $_POST['category'];

				$result = $data->db->prepare("INSERT INTO statistic (title, category) VALUES (:title, :category)");
				$result->bindValue(':title', $title);
				$result->bindValue(':category', $category);
				$result->execute();

				$result = $data->db->prepare("UPDATE userid SET statisticTime = :currentTime WHERE id = :userid");
				$result->bindValue(':currentTime', $currentTime);
				$result->bindValue(':userid', $userid, PDO::PARAM_INT);
				$result->execute();
				echo 'undefined';
			}else{
				$error_time = $timestamp2 - $timestamp;
				echo "再度投稿できるのは、5分以上間隔を開けてください。現在{$error_time}秒";
				exit;
			}
		}
	}catch(Exception $e){
		echo $e;
	}

	

?>