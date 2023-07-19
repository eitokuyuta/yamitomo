<?php
/*------------------------------------------------
*	snsアイコンを表示します。
------------------------------------------------*/
//snsの共有リンクを作成します。　注意！！headerの読み込みより前に書いてください。
require_once('./share.php');
$text = '病み友｜病んでる友達つくっちゃお！';
$url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$hashtags = '病み';
$share = new share($text, $url, $hashtags);
echo "
	<div id='sns_list'>
		<a href='{$share->shareTwitter()}' id='twitter' class='sns_chi'><i class='bi bi-twitter'></i></a>
		<a href='{$share->sharefacebook()}' id='facebook' class='sns_chi'><i class='bi bi-facebook'></i></a>
		<a href='{$share->shareLine()}' id='LINE' class='sns_chi'><i class='bi bi-line'></i></a>
	</div>
";
?>