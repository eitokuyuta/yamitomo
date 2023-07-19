<?php

/*-----------------------------------------------
*	空のチャットルームを削除するかstrageに移動するかを
*	判定して実行します。
*	判定方法はログの数(200)といいね数（100）です。
------------------------------------------------*/
require_once('./databaseInit.php');
date_default_timezone_set('Asia/Tokyo');
$currentTime =  date('Y-m-d H:i:s');

$data = new databaseInit();
$query = "SELECT * FROM room";
$result = $data->db->prepare($query);
$result->execute();
$result = $result->fetchAll(PDO::FETCH_ASSOC);
/*---------------------------------------------------
*	基本的にuserが部屋にいると、１分ごとにroomテーブルの
*	lasttimeへと更新されます。
*	下記はそれと、現在の時間の差分で30分以上更新されなければ
*	削除されてしまいます。
----------------------------------------------------*/
foreach( $result as $col){
	$timestamp = strtotime($col['lasttime']);		//strtotime : 秒単位のタイムスタンプに変換して差分を作る。
	$timestamp2 = strtotime($currentTime);
	
	if(ceil(($timestamp2 - $timestamp)/60) > 60){	//30分以上userがいないと時間が更新されないため削除
		if($col['commentNum'] < 200 || $col['iine'] < 10){			
			$query = "DELETE FROM room WHERE id = :id";
			$result = $data->db->prepare($query);
			$result->bindValue(':id', $col['id']);
			$result->execute();

			$query = "DELETE FROM log WHERE roomid = :id";
			$result = $data->db->prepare($query);
			$result->bindValue(':id', $col['id']);
			$result->execute();


		}else if($col['commentNum'] >=500 || $col['iine'] >= 10){			//いいねが10件以上、コメントの数が200件以上
			
			require_once('./emptyRoomNumber.php');
			//データっベースへの追加およびcsvデータ名の生成
			$fileUrl = './csv/'.(String)($chid).'_'.$col['roomname'].'.csv';
			$query = "INSERT INTO storage (chid, roomname, category, consolationText, roomimg, commentNum, iine, csvFile) VALUES (:chid, :roomname, :category, :consolationText, :roomimg, :commentNum, :iine, :csvFile)";
			$result = $data->db->prepare($query);
			$result->bindValue(':chid', $chid);
			$result->bindValue(':roomname', $col['roomname']);
			$result->bindValue(':category', $col['category']);
			$result->bindValue(':consolationText', $col['consolationText']);
			$result->bindValue(':roomimg', $col['roomimg']);
			$result->bindValue(':commentNum', $col['commentNum']);
			$result->bindValue(':iine', $col['iine']);
			$result->bindValue(':csvFile', $fileUrl);
			$result->execute();

			//保管データへのデータの移動
			$query = "DELETE FROM room WHERE id = :id";
			$result = $data->db->prepare($query);
			$result->bindValue(':id', $col['id']);
			$result->execute();
			

			//ログにある管理者からの情報の削除
			$query = "DELETE FROM log WHERE roomid = :id AND type = :type";
			$result = $data->db->prepare($query);
			$result->bindValue(':id', $col['id']);
			$result->bindValue(':type', 'info');
			$result->execute();

			//csvへの書き出し
			$query = 'SELECT * FROM log WHERE roomid = :id';
			$result = $data->db->prepare($query);
			$result->bindValue(':id', $col['id']);
			$result->execute();
			$result = $result->fetchAll(PDO::FETCH_ASSOC);

			$fp = fopen($fileUrl, 'w');
			foreach($result as $fields){
				 fputcsv($fp, $fields);
			}
			fclose($fp);

			//csvに移し終えたcsvデータの削除
			$query = "DELETE FROM log WHERE roomid = :id";
			$result = $data->db->prepare($query);
			$result->bindValue(':id', $col['id']);
			$result->execute();
		}
	}

}
?>