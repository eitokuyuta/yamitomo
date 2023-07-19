<?php
session_start();

require_once('./define.php');
require_once('./databaseInit.php');
require_once('./jsonEncode.php');

$data = new databaseInit();

$roomid = isset($_POST["roomid"]) ? $_POST["roomid"] : "" ;


// キャッシュを取らないように
header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

$max_chid = isset($_SESSION["max_chid"]) ? $_SESSION["max_chid"] : 0 ;

// チャットの内容の取得
$_chat = array();

$query = "SELECT * FROM log WHERE roomid = :roomid AND chid > :max_chid  ORDER BY date DESC LIMIT 30";
$result = $data->db->prepare($query);
$result->bindValue(':roomid', $roomid);
$result->bindValue(':max_chid', $max_chid);
$result->execute();
$result = $result->fetchAll(PDO::FETCH_ASSOC);	

if(count($result) == 0){
	//返り値で''をjsonにすると判定出来なかったので一時的にundefinedをstringで返している。
	echo 'undefined';
	exit;
}
foreach($result as $val){
	$_chat[$val["chid"]] = $val;
}

// 直近のID
$_SESSION["max_chid"] = count($_chat) ? max(array_keys($_chat)) : $max_chid ;
if(count($_chat) != 0){
	$json = new jsonEncode($_chat, current($_chat)['text']);
	echo $json->encJson();
}
?>

<?php
/*
mysql_fetch_assoc — 連想配列として結果の行を取得する

mysql_free_result — 結果保持用メモリを開放する

max — 最大値を返す
パラメータとして配列をひとつだけ渡した場合は、max() は配列の中で最も大きい数値を返します。
ふたつ以上のパラメータを指定した場合は、max() はそれらの中で最も大きいものを返します。

array_keys — 配列のキーすべて、あるいはその一部を返す

*/
?>