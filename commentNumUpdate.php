<?php
	
	/*--------------------------------------------------
	*	emptyHouse.phpでログ数とiine数がstrageに移行されるか
	*	の判定に使われるので、下記で加算する。
	*	判定は200以上のログと100のいいね。
	---------------------------------------------------*/
	require_once('./databaseInit.php');
	$roomid = $_POST['roomid'];

	$data = new databaseInit();
	$result = $data->db->prepare("UPDATE room SET commentNum = commentNum + 1 WHERE id = :roomid");
	$result->bindValue(':roomid', htmlspecialchars($roomid, ENT_QUOTES, "UTF-8"));
	$result->execute();

?>