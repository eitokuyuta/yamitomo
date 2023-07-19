<?php
	session_start();
	require_once('./imgUpload.php');
	require_once('./databaseInit.php');
	require_once('./sanitize.php');
	
	
	if( !isset($_SESSION['username'])){
		echo 'solution fail';
		exit;
	}
	if(isset($_FILES['file_upload']['tmp_name'])){
		$value = file_get_contents($_FILES['file_upload']["tmp_name"]);
		// 許可するMIMETYPE
	    $allowed_types = [
	        'jpeg' => 'image/jpeg',
	        'png' => 'image/png'
	    ];
	    foreach ($allowed_types as $key => $value) {
	    	if($_FILES['file_upload']["type"] == $allowed_types[$key]){
		    
		    	$imgUp = new imgUpload('./uploadimg');
		    	$imgUrl = $imgUp->imgMove($_FILES['file_upload']);
		    }
	    }
	}else{ 
		$imgUrl = './icon/photo.jpg';
	}
	if(isset($imgUrl) && isset($_POST['roomName']) && isset($_POST['consolationText'] )){
		$data = new databaseInit();
		$result = $data->db->prepare("INSERT INTO room (roomname, roomimg, consolationText, category) VALUES (:roomname, :roomimg, :consolationText, :category)");
		$result->bindValue(':roomimg', $imgUrl);
		$result->bindValue(':roomname', sanitize($_POST['roomName']));
		$result->bindValue(':consolationText', sanitize($_POST['consolationText']));
		$result->bindValue(':category', sanitize($_POST['category']));
		$result->execute();	

		/*-----------------------------------------------------
		*	lastInsertId is a method of PDO, not PDOStatement. Therefore:
		*	$db->lastInsertId();
		------------------------------------------------------*/
		$id = $data->db->lastInsertId();

	}else{
		echo 'いろいろありません。makeroomにメッセージとともに表示してください。';
		exit;
	}
	//header('Location: ./log.php?id='.$id);
	echo $id;
	exit;

?>