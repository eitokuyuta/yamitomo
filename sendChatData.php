<?php
require_once('./databaseInit.php');
require_once('./sanitize.php');

$userid = isset($_POST["userid"]) ? $_POST["userid"] : "" ;
$roomid = isset($_POST["roomid"]) ? $_POST["roomid"] : "" ;
$log = isset($_POST["log"]) ? $_POST["log"] : "" ;
$username = isset($_POST["username"]) ? $_POST["username"] : "" ;
$icon = isset($_POST["icon"]) ? $_POST["icon"] : "" ;
$type = isset($_POST["type"]) ? $_POST["type"] : "" ;

$err = array();
if(!$userid) $err[] = "名前 を入力してください";
if(mb_strlen($userid)>10) $err[] = "名前 は10文字以内で入力してください";
if(!$roomid) $err[] = "部屋名が不確定です";
if(mb_strlen($roomid)>10) $err[] = "名前 は10文字以内で入力してください";
if(!$log) $err[] = "文章 を入力してください";
if(mb_strlen($log)==0) $err[] = "文章 は50文字以内で入力してください";
if(!$username) $err[] = "ユーザー名 を入力してください";
if(mb_strlen($username)>50) $err[] = "ユーザー名がありません";
if(!$icon) $err[] = "アイコンがありません";
if(mb_strlen($icon)>50) $err[] = "アイコンを設定しなおしてください";
if(!$type) $err[] = "文章属性がありません";
if(mb_strlen($type)>50) $err[] = "文章属性を設定しなおしてください";

if(!count($err)){
	$data = new databaseInit();
	$result = $data->db->prepare("insert into log set userid = :userid, username = :username, icon = :icon, roomid = :roomid, text = :log, type = :type");
	$result->bindValue(':userid', sanitize($userid));
	$result->bindValue(':username', sanitize($username));
	$result->bindValue(':icon', sanitize($icon));
	$result->bindValue(':roomid', sanitize($roomid));
	$result->bindValue(':type', sanitize($type));
	$result->bindValue(':log', sanitize($log));
	$flag = $result->execute();
	if($flag == false){
		echo 'e-31:データベースで返り値がfalseです。';
		exit;
	}
}else{
	var_dump($err);
	exit;
	//$msg = showerr($err);
	/*showerrは独自関数である。サイトには乗っていなかった。*/
}

?>