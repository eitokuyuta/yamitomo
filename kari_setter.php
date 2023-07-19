<?php
require_once('./databaseInit.php');
require_once('./imgUpload.php');
require_once('./sanitize.php');


//	imgfileの処理
//------------------------------------------------------------------
if($_FILES['file']['tmp_name'] != ''){
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
	$imgUrl = './icon/photo.jpg';
}

//	csv,textfileの処理
//------------------------------------------------------------------
if($_FILES['filelog']['tmp_name'] != ''){
	$value = file_get_contents($_FILES['filelog']["tmp_name"]);
	// 許可するMIMETYPE
    $allowed_types = [
        'csv' => 'text/csv',
        'txt' => 'text/plain'
    ];
    foreach ($allowed_types as $key => $value) {
    	if($_FILES['filelog']["type"] == $allowed_types[$key]){
	    
	    	$file2 = new imgUpload('./csv');
	    	$file2 = $file2->imgMove($_FILES['filelog']);
	    }
    }
}else{ 
	echo"csvファイルがありません。";
	exit;
}

$data = new databaseInit();
$query = "INSERT INTO storage (chid, roomname, category, hypertext, consolationText, roomimg, commentNum, iine, csvFile) VALUES (:chid, :roomname, :category, :hypertext, :consolationText, :roomimg, :commentNum, :iine, :csvFile)";
$result = $data->db->prepare($query);
$result->bindValue(':chid', $_POST['chid']);
$result->bindValue(':roomname', $_POST['roomname']);
$result->bindValue(':category', $_POST['category']);
$result->bindValue(':hypertext', $_POST['hypertex']);
$result->bindValue(':consolationText', $_POST['consolation']);
$result->bindValue(':roomimg', $imgUrl );
$result->bindValue(':commentNum', $_POST['commentNum']);
$result->bindValue(':iine', $_POST['iine']);
$result->bindValue(':csvFile', $file2);	//DBの列名を変えていないけれどもtextファイルも入れれる。
$result->execute();

?>