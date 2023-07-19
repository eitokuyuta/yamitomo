<?php
	/*----------------------------------------------------------------------
	*	meta情報を生成する。
	*	->meta.phpで使用
	*												yuta eitoku
	----------------------------------------------------------------------*/
	require_once('./healthArr.php');
	require_once('./databaseInit.php');

	if(isset($_GET['id'])){
		$roomid	= $_GET['id'];
		$data = new databaseInit();
		$result = $data->db->prepare("SELECT * FROM room WHERE id = :roomid");
		$result->bindValue(':roomid', $roomid);
		$result->execute();
		$room = $result->fetchAll(PDO::FETCH_ASSOC);
		$room = $room[0];

	}else if(isset($_GET['chid'])){
		$roomid = $_GET['chid'];
		$data = new databaseInit();
		$result = $data->db->prepare("SELECT * FROM storage WHERE chid = :chid");
		$result->bindValue(':chid', $roomid);
		$result->execute();
		$room = $result->fetchAll(PDO::FETCH_ASSOC);
		$room = $room[0];

	}
	$title = isset($room['roomname'])?$room['roomname'].'| yamitomo':'yamitomo 病んでる友達つくっちゃお！';

	$descriptionTmp = '病んでたら、いろいろ大変です。周りの人に相談しづらいし、そもそも分かってもらえない。だったら、病んで同士で友達作っちゃえば、分かってもらえるんじゃない？と思ってこのサイトを作りました。';

	$description = isset($room['consolationText'])?$room['consolationText']:$descriptionTmp;

	$keyword = '';
	if(isset($room['category'])){
		$keyword = $room['category'];
	}else{
		for($i = 0; $i < count($keyArr); $i++){

			$keyword .= $keyArr[$i];
			if($i != (count($keyArr) - 1)){
				$keyword .= ',';
			}

		}
	}

	if(basename($_SERVER['PHP_SELF']) == 'index.php'){
		$type = 'website';
	}else{
		$type = 'article';
	}
	/*
		website：サイトのトップページ
		blog：ブログのトップページ
		article：記事ページ、サイトのトップページ以外
	*/

	$image = isset($room['roomimg'])?'https://yamitomo.jp/'. $room['roomimg']:'https://yamitomo.jp/img/index.png';

	$url = 'https://yamitomo.jp/index.php';

	$site_name = 'yamitomo.jp';

	$tw_site = '@gdgdthnx';

	$tw_card = 'summary_large_image';


?>