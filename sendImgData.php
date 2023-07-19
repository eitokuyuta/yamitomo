<?php
	require_once('./imgUpload.php');
	require_once('./sanitize.php');
	require_once('./databaseInit.php');
	$opinion = [
		"userid",
		"username",
		"icon",
		"roomid",
		"type",
	];
	
	foreach($opinion as $key){
		if( !isset($_POST[$key])){
			echo 'error:not exist'.$key;
			exit;
		}	
	}

	$userid = $_POST['userid'];
	$username = $_POST['username'];
	$icon = $_POST['icon'];
	$roomid = $_POST['roomid'];
	$type = $_POST['type'];

	if(isset($_FILES['file'])){
		
		$value = file_get_contents($_FILES['file']["tmp_name"]);
		// 許可するMIMETYPE
	    $allowed_types = [
	        'jpeg' => 'image/jpeg',
	        'png' => 'image/png'
	    ];

	    foreach ($allowed_types as $key => $value) {
	    	if($_FILES['file']["type"] == $allowed_types[$key]){
		    
		    	$imgUp = new imgUpload('./uploadimg');
		    	$imgUrl = $imgUp->imgMove($_FILES['file']);
		    }
	    }
	}else{
		echo 'fail';
		exit;
	} 

	$data = new databaseInit();
	$result = $data->db->prepare("INSERT INTO log (userid, username, icon, type, text, roomid) VALUES (:userid, :username, :icon, :type, :text, :roomid)");
	$result->bindValue(':userid', sanitize($userid));
	$result->bindValue(':username', sanitize($username));
	$result->bindValue(':icon', sanitize($icon));
	$result->bindValue(':roomid', sanitize($roomid));
	$result->bindValue(':type', $type);
	$result->bindValue(':text', $imgUrl);
	$result->execute();	
	exit;

	//useridに画像秒数を更新
	date_default_timezone_set('Asia/Tokyo');
	$currentTime =  date('Y-m-d H:i:s');
	$query = 'UPDATE userid SET imgtime = :imgtime WHERE id = :userid' ;
	$result = $data->db->prepare($query);
	$result->bindValue(':imgtime', $currentTime);
	$result->bindValue(':userid', sanitize($userid));
	$result->execute();	

?>