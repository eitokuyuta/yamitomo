<?php

	//rankingの取得
	//------------------------------------
	$data = new databaseInit();
	$result = $data->db->prepare("SELECT count(*) FROM storage");
	$result->execute();
	$count = $result->fetchAll(PDO::FETCH_ASSOC);
	$count = $count[0]['count(*)'];
	if($count > 0){
		$count = $count > 10 ? 10 : $count;
		//ランキング総合
		//------------------------------------
		$result = $data->db->prepare("SELECT chid, roomname FROM storage WHERE lasttime BETWEEN (NOW() - INTERVAL 1 WEEK) AND NOW() ORDER BY commentNum DESC LIMIT :count");
		$result->bindValue(':count', $count, PDO::PARAM_INT);
		$result->execute();	
		$ranking3 = $result->fetchAll(PDO::FETCH_ASSOC);

	}
	$hogehoge = '';
	$hogehoge .= "<div id='otherContain'><div id='ot-title'><i class='bi bi-bookmark-heart-fill'></i>週刊ランキング</div><ul id='otherStrage'>";

		foreach($ranking3 as $col){
			$hogehoge .= "<li class='ot-ranking-piece'><a class='ot-ranking-link' href='".$_SERVER['PHP_SELF'].'?chid='.$col['chid']."'></a>".$col['roomname']."</li>";


		}


	$hogehoge .= "</ul></div>";

	echo $hogehoge;

?>