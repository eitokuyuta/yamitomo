<?php
	require_once('./databaseInit.php');
	require_once('./jsonEncode.php');
	
	$data = new databaseInit();
	
	$userid = isset($_POST['userid']) ? $_POST['userid'] : null;
	$roomid = isset($_POST['roomid']) ? $_POST['roomid'] : null;
	$type = isset($_POST['type']) ? $_POST['type'] : null;

	function responseJson($Jtext){
			$text = $Jtext;
			$jsonData = new jsonEncode($text);
			$jsonData = $jsonData->encJson();
			return $jsonData;
	}

	if($type == 'text'){
		try{
			if( $userid == null ){ throw new Exception(‘ユーザー情報がありません’); };
			if( $roomid == null ){ throw new Exception(‘部屋情報がありません’); };
			if( $type == null ){ throw new Exception(‘オプション情報がありません’); };

		}catch(Exception $e){
			echo responseJson($e->getMessage());
			exit;
		}

		//判定用のuseridテーブルのiine列のチェック
		$query = "SELECT iine FROM userid WHERE id = :userid";
		$result = $data->db->prepare($query);
		$result->bindValue(':userid', $userid);
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		
		if($result[0]['iine'] != $roomid){
			$query = "UPDATE room SET iine = iine + 1 WHERE id = :roomid";
			$result = $data->db->prepare($query);
			$result->bindValue(':roomid', $roomid);
			$result->execute();
			
			//判定用のuseridテーブルのiineにroomidを入れて更新する。
			$query = "UPDATE userid SET iine = :roomid WHERE id = :userid";
			$result = $data->db->prepare($query);
			$result->bindValue(':roomid', $roomid, PDO::PARAM_INT);
			$result->bindValue(':userid', $userid, PDO::PARAM_INT);
			$result->execute();

			echo 'undefined';
		}else if($result[0]['iine'] == $roomid){
			echo 'いいね！ボタンはすでに押しています。';
			exit;
		}else{
			echo 'whats up';
			exit;
		}

	}else if($type == 'load'){
		/*--------------------------------------------
		*	いいねをxhrで更新するためのプログラム。
		---------------------------------------------*/
		try{
			if( $roomid == null ){ throw new Exception(‘部屋情報がありません’); };
		
		}catch(Exception $e){
			echo $e->getMessage();
			exit;
		}
		$query = "SELECT iine FROM room WHERE roomid = :roomid";
		$result = $data->db->prepare($query);
		$result->bindValue(':roomid', $roomid);
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		echo $result;
	}

?>