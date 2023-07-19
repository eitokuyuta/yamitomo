<?php
	session_start();
	require_once('./seo.php');
?>
<html lang="ja">
	<head>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-97624992-2"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-97624992-2');
        </script>
		<meta charset="UTF-8">
		<?php 
			require_once('./meta.php');
		?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!--bootstrap-->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
		<script src="https://kit.fontawesome.com/c70c76d166.js" crossorigin="anonymous"></script>
		<!--bootstrap end-->
		<link rel="stylesheet" type="text/css" href="./css/reset.css">
		<link rel="stylesheet" type="text/css" href="./css/object.css">
		<link rel="stylesheet" type="text/css" href="./css/storageLog.css">
	</head>
	<body>
		<style>
			ul#logs_area {
    			height: 100%;
    		}
		</style>
		<?php
		/*----------------------------------
		*	宣言
		----------------------------------*/
  			$category = 0;					//sidebarでカテゴリーのランキングを表示するための変数
		?>
		<?php
			if(!isset($_GET['chid'])){
				header('Location: ./storage.php');
				exit;
			}

			//SNSの読み込み
			//---------------------------------------------
			//snsの共有リンクを作成します。　注意！！headerの読み込みより前に書いてください。
			require_once('./share.php');
			$text = $_GET['roomname'];
			$url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'&chid='.$_GET['chid'];
			$hashtags = $_GET['category'];
			$share = new share($text, $url, $hashtags);

			
			//デバイスチェック
			//---------------------------------------------
			require_once('browser_deviceCheck.php');
			$browser = new browser();
			$browser_info = $browser->get_info();
			if($browser_info['platform'] == 'iPhone' || $browser_info['platform'] == 'iPod' || $browser_info['platform'] == 'iPad' || $browser_info['platform'] == 'Android' || $browser_info['platform'] == 'Windows Phone' ){
				require_once('./phoneHeader.php');
				$deviceTmp = 'phone';
			}else{
				require_once('./header.php');
				$deviceTmp = 'pc';
			}

			//ライブラリ読み込み
			//---------------------------------------------
			require_once('./databaseInit.php');

			$roomid = $_GET['chid'];
		?>
		<div id="middle">
			<div id="main">
				<ul id="logs_area">
					<div class='log-sns'>
						<?php
							if($deviceTmp == 'phone'){
								require_once('./snsView.php');	//->share.php
							}
						?>
					</div>
					<?php
						//	部屋の情報を取得（画像、相談事等）
						//------------------------------------------
						$data = new databaseInit();
						$result = $data->db->prepare("SELECT * FROM storage WHERE chid = :chid");
						$result->bindValue(':chid', $roomid);
						$result->execute();
						$room = $result->fetchAll(PDO::FETCH_ASSOC);
						if(count($room) == 0){
							header('Location: ./index.php');
							exit;
						}
						foreach( $room as $col){
							if($col['roomimg'] == NULL){
								$col['roomimg'] = './icon/photo.jpg';
							};
							$category = $col['category'];
							echo"
								<div id='container' class='storage_container'>
									<div id='roomtitle'>
										<div id='img_base' class='second_parts'>
											<img id='roomImg'src='{$col['roomimg']}'>
										</div>
										<div id='xtitle'>
											<div id='roomname' class='text-break'><strong>{$col['roomname']}</strong></div>
											<div id='xtitle_bottom'>
												<div id='good_container'>
													<img class='message_good' src='./img/good.png'></img>
													<div class='iine_num'>{$col['iine']}</div>
												</div>
												<div id='category'>#{$col['category']}</div>
											</div>
										</div>
									</div>
									<a id='separate' href='{$col['hypertext']}'>引用元: {$col['hypertext']}</a>
									<div id='second_stage'>
										<div id='consolation' class='second_parts'>相談事:<br/>{$col['consolationText']}</div>
										<div id='exitArea'><a id='exitButton' href='./storage.php'>戻る</a></div>
									</div>
								</div>
							";
							$roomname = $col['roomname'];
							$fileUrl = $col['csvFile'];

						}						
					?>
					<?php
						if($deviceTmp == 'phone'){
							require_once('./sideRank.php');
							require_once('./otherStorage.php');
							echo "<div class='separate-line'></div>";
						}
					?>
					<?php
						//	チャット内容の取得
						//------------------------------------------
						$_chat = array();
						
						//csvデータをダウンロードする
						function returnCsv($url){
							$file = fopen($url, 'r');
							$arr = array();
							while($data = fgetcsv($file)){
								array_push($arr, $data);
							}
							fclose($file);
							return $arr;
						}
						//	html か csv かの分岐
						//	DBの列名は、csvFileになっているが、ここにhtmlファイルなどを入れるようにする。
						//------------------------------------------------------------------------------
						if(preg_match('/(\.csv)/', $fileUrl)){
							if (file_exists($fileUrl)){ 
								$result = returnCsv($fileUrl);
							
								foreach($result as $col){
									$_chat[$col[0]] = $col;
									echo"
										<li class='log'>
											<div class='message_right'>
												<img class='icon' src='./icon/{$col[4]}.jpg'>
											</div>
											<div class='message_left'>
												<div class='other_info'>
													<span class='info_time'>{$col[1]}</span>
												</div>
												<div class='message_container'>

													<div class='name'>@{$col[3]}</div>
										";
										if($col[5] == 'text' || $col[5] == 'info' ){
											echo 	"<div class='message'>{$col[6]}</div>";
										}else if( $col['type'] == 'img' ){
											echo 	"<img class='logs_img' src={$col[6]}>";
										}
													
									echo"				
												</div>
											</div>
										</li>
										";
										
								}
							} 
						}else if(preg_match('/(\.txt)/', $fileUrl)){
							//	phpファイルの処理
							//------------------------------------------------------------------------------

							// ファイルポインタをオープン
							$handle = fopen($fileUrl, "r");
							$tmp = '';
							// ファイル内容を出力
							while ($line = fgets($handle)) {
							  $tmp .= $line;
							}
							//	aタグの置換
							//---------------------------------
							$tmp = preg_replace('/<iframe(.|\s)*?>/', '', $tmp);
							$tmp = preg_replace('/<\/iframe>/', '', $tmp);
							$tmp = preg_replace('/<\/a>/', '</div>', $tmp);
							$tmp = preg_replace('/<a(.|\s)*?">/', '<div style="font-size:1em;display:inline-block;">', $tmp);
							echo $tmp;
							// ファイルポインタをクローズ
							fclose($handle);
						}
						/*
						count — 配列または Countable オブジェクトに含まれるすべての要素の数を数える
						mysql_fetch_assoc — 連想配列として結果の行を取得する
						mysql_free_result — 結果保持用メモリを開放する
						*/

					?>
				</ul>
			</div>
			<?php
				if($deviceTmp == 'pc'){
					require_once('./sideRank.php');
				}
			?>
		</div>
		
	</body>
	<script type="text/javascript" src="./js/log_scroll.js"></script>
	<script src="./js/DeviceCheck.js" type="text/javascript"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script type="text/javascript">

		//正規表現で対応しきれなかったthreadのスタイルを変更する
		window.onload = ()=>{
			var thread = document.getElementsByClassName("thread")[0];
			if(thread){
				thread.style = {};
				thread.style.width = "100%";
				thread.style.height = "auto";
				thread.style.padding = "1vw";
				thread.style.display = "flex";
				thread.style.justifyContent = "center";
				thread.style.alignItems = "flex-start";
				thread.style.flexFlow = "column";
			}
		}

		var device_train = {
			pc: document.getElementsByClassName('pc'),
			phone: document.getElementsByClassName('phone'),
			exe: ()=>{
				var self = device_train;
				if(device() == true){
					Object.keys(self.phone).forEach((key)=>{
						self.phone[key].style.display = 'none';
					})
				}else if(device() == false){
					Object.keys(self.pc).forEach((key)=>{
						self.pc[key].style.display = 'none';
					})
				}
			}
		}
                device_train.exe();
	</script>
</html>
