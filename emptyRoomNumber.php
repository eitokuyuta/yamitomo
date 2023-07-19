<?php
	//	chidの重複の回避関数
	//------------------------------------
	$flag = 1;
	$chid = -1;			//検索する値-1の値を入れる
	function emptyNumber(){
		global $chid;
		$data = new databaseInit();
		$query = "SELECT count(*) FROM storage WHERE chid = :chid";
		$result = $data->db->prepare($query);
		$result->bindValue(':chid', $chid, PDO::PARAM_INT);
		$result->execute();
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		return $result[0]["count(*)"];
	}

	while( $flag >= 1 ){
		$chid++;
		$flag = emptyNumber();
	}
?>