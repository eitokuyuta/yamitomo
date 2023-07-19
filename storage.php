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
		<link href="https://fonts.googleapis.com/earlyaccess/kokoro.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
		<!--bootstrap end-->
		<link rel="stylesheet" type="text/css" href="./css/reset.css">
		<link rel="stylesheet" type="text/css" href="./css/object.css">
		<link rel="stylesheet" type="text/css" href="./css/room.css">
		<link rel="stylesheet" type="text/css" href="./css/storage.css">
		<link rel="stylesheet" type="text/css" href="./css/phoneCategory.css">
	</head>
	<body>
		<?php
			//SNSリンクの作成　注意！！headerの読み込みより前に書いてください。
			//------------------------------------
			require_once('./share.php');
			$text = 'not normal 普通じゃなくてもいいって言えるように';
			$url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$hashtags = 'notnormal';
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
			require_once('./databaseInit.php');
		?>
		<?php
			//rankingの取得
			$data = new databaseInit();
			$result = $data->db->prepare("SELECT count(*) FROM storage");
			$result->execute();
			$count = $result->fetchAll(PDO::FETCH_ASSOC);
			$count = $count[0]['count(*)'];
			if($count > 0){
				$count = $count > 6 ? 6 : $count;
				$result = $data->db->prepare("SELECT * FROM storage ORDER BY commentNum DESC LIMIT :count");
				$result->bindValue(':count', $count, PDO::PARAM_INT);
				$result->execute();	
				$ranking = $result->fetchAll(PDO::FETCH_ASSOC);
			}
		?>
		
		<div id="middle">
			<div id="main">
				<div id="room_title">
					<div id="hoge">● 保管所</div>
					<?php
						if($deviceTmp == 'phone'){
							require_once('./snsView.php');	//->share.php
						}
					?>
				</div>
				
			<?php
				//広告系	
				if( $deviceTmp == "pc"){
					echo "
						<div class='addsense_long'>
							<!-- admax -->
							<script src='https://adm.shinobi.jp/s/c3bbd91acc407a64464bee77ed2d049d'></script>
							<!-- admax -->
						</div>
					";
				}else if( $deviceTmp == "xphone"){
					echo "
						<div class='addsense_middle phone'> 
							<!-- admax -->
							<script src='https://adm.shinobi.jp/s/7d2d9d5ab82f94844aeffda7752d06b0'></script>
							<!-- admax -->
						</div>
					";	
				}
				// 広告系　end
				?>
				<?php
				function ranking_content($col, $num){
					return "
					<form class='ranking_form' method='get' action='./storageLog.php' name='room'>
						<input type='hidden' name='chid' value={$col['chid']}>
						<input type='hidden' name='roomname' class='text-break' value={$col['roomname']}>
						<input type='hidden' name='category' value={$col['category']}>
						<div class='ranking-ob'>
							<img class='ranking-img'src={$col['roomimg']}>
							<div class='ranking-num'>{$num}</div>
							<div class='ranking-value'>{$col['roomname']}</div>
							<input class='resetSubmit hidden-button' type='submit' value=''>
						</div>
					</form>
					";
				}

				if( $deviceTmp == "phone"){
					echo "
						<div id='side_bar' class='phone'>
							<!--phone要素-->
							<div id='ranking'>
								<div id='rankinng_subject'><i class='bi bi-bookmark-heart-fill'></i>総合人気記事</div>
								<div class='rank_box'>
						";
										if($count>0){echo ranking_content($ranking[0], 1);}else{echo "<div class='ranking_form'>1</div>";}		
										if($count>1){echo ranking_content($ranking[1], 2);}else{echo "<div class='ranking_form'>2</div>";}
										if($count>2){echo ranking_content($ranking[2], 3);}else{echo "<div class='ranking_form'>3</div>";}
										if($count>3){echo ranking_content($ranking[3], 4);}else{echo "<div class='ranking_form'>4</div>";}
										if($count>4){echo ranking_content($ranking[4], 5);}else{echo "<div class='ranking_form'>5</div>";}
										if($count>5){echo ranking_content($ranking[5], 6);}else{echo "<div class='ranking_form'>6</div>";}
									
									
						echo "
								</div>
							</div>
						</div>
						";
				}
				?>

				<?php if($deviceTmp == 'phone'){require_once('./phoneCategory.php');}?>
				
				<?php
					$max_col = 10;													//1ページに入れる最大数
					$pageNum = isset($_POST['pageNum']) ? $_POST['pageNum'] : 0;	//初期値代入のため
					$first = $pageNum * $max_col;

					require_once('./sanitize.php');
					$data = new databaseInit();

					if(isset($_GET['indexCategory'])){
						$query = "SELECT count(*) FROM storage WHERE category = :category";
						$result = $data->db->prepare($query);
						$result->bindValue(':category', sanitize($_GET['indexCategory']));
					}else if(isset($_GET['serchWord'])){
						$query = "SELECT count(*) FROM storage WHERE roomname LIKE :serch";
						$result = $data->db->prepare($query);
						$result->bindValue(':serch', "%".sanitize($_GET['serchWord'])."%");
					}
					else{
						$query = "SELECT count(*) FROM storage";
						$result = $data->db->prepare($query);
					}
					
					$result->execute();
					$count = $result->fetchAll(PDO::FETCH_ASSOC);
					$count = $count[0]['count(*)'];

					/*
					*	@param
					*	$pageNum -> getから取得したページ数初期値は０
					*	
					*
					*/
					$button_num = ceil($count / $max_col);	//ボタンの個数
					
					$button_first = ($pageNum - 2) > 0 && $button_num > ($pageNum + 3) ? $pageNum - 2 : 0;

					if(($pageNum - 2) > 0 && $button_num >= 5){
						$button_last = $pageNum + 3;
					}else if((($pageNum - 2) >= 0 && $button_num < 5) || (($pageNum - 2) <= 0 && $button_num < 5)){
						$button_last = $button_num;
					}else if(($pageNum - 2) <= 0 && $button_num >= 5){
						$button_last = 5;
					}

					//	オーバーフロウ
					//-------------------------
					if($button_last > $button_num){
						$button_last = $button_num;
						$button_first = $button_last - 5;
					}

					//strageの取得
					$data = new databaseInit();
					if(isset($_GET['indexCategory']) && ($count >= 1)){
						//---------------------------------------
						//	カテゴリー検索で結果があった場合
						//---------------------------------------
						$query = "SELECT * FROM storage WHERE category = :category ORDER BY id DESC LIMIT :first, 10";
						$result = $data->db->prepare($query);
						$result->bindValue(':first', $first, PDO::PARAM_INT);
						$result->bindValue(':category', sanitize($_GET['indexCategory']));
						$result->execute();
						$storage = $result->fetchAll(PDO::FETCH_ASSOC);

					}else if(isset($_GET['indexCategory']) && ($count < 1)){
						//---------------------------------------
						//	カテゴリー検索で結果がなかった場合
						//---------------------------------------
						echo '<div id="serch-result">結果は０件でした。</div>';
						$storage = [];
					}else if(isset($_GET['serchWord']) && ($count >= 1)){
						//---------------------------------------
						//	カテゴリー検索で結果があった場合
						//---------------------------------------
						$query = "SELECT * FROM storage WHERE roomname LIKE :serchWord ORDER BY id DESC LIMIT :first, 10";
						$result = $data->db->prepare($query);
						$result->bindValue(':first', $first, PDO::PARAM_INT);
						$result->bindValue(':serchWord', "%".sanitize($_GET['serchWord'])."%");
						$result->execute();
						$storage = $result->fetchAll(PDO::FETCH_ASSOC);

					}else if(isset($_GET['serchWord']) && ($count < 1)){
						//---------------------------------------
						//	カテゴリー検索で結果がなかった場合
						//---------------------------------------
						echo '<div id="serch-result">結果は０件でした。</div>';
						$storage = [];
					}
					else{
						$query = "SELECT * FROM storage ORDER BY id DESC LIMIT :first, 10";
						$result = $data->db->prepare($query);
						$result->bindValue(':first', $first, PDO::PARAM_INT);
						$result->execute();
						$storage = $result->fetchAll(PDO::FETCH_ASSOC);
					}

					foreach($storage as $col){
						//var_dump($col);
						if($col['roomimg'] == ''){ $col['roomimg'] = './icon/photo.jpg'; }
						echo"
							<div class='roombox'>
								<img class='roombox_right' src='{$col['roomimg']}'>
								<div class='roombox_left'>
									<div class='roombox_left_top'>
										<div class='comment_num font_num'>コメント数:{$col['commentNum']}</div>
										<div class='view_num font_num'>閲覧数:{$col['viewNum']}</div>
									</div>
									<div class='roomname text-break'>{$col['roomname']}</div>
									<div class='category'>カテゴリー:{$col['category']}</div>
								</div>
								<form class='enter_room' method='get' action='./storageLog.php' name='room'>
									<input type='hidden' name='chid' value={$col['chid']}>
									<input type='hidden' name='roomname' value={$col['roomname']}>
									<input type='hidden' name='category' value={$col['category']}>
									<input class='btn btn-info' type='submit' value='入室'>
								</form>
							</div>
							";
					}	

				?>
				<?php
						/*---------------------------------------------
						*	ページ遷移用のボタン
						---------------------------------------------*/
						echo "
						<form class='btngr' action='./storage.php' method='post'>
							<div class='btn-toolbar' role='toolbar' aria-label='Toolbar with button groups'>
								<div class='btn-group me-2' role='group' aria-label='First group'>
						";
						for($button_first ; $button_first < $button_last; $button_first++){
							$y = $button_first + 1;
							echo "
								<button type='submit' class='btn btn-primary' name='pageNum' value='{$button_first}'>{$y}</button>
							";
						}
						echo "
								</div>
							</div>
						</form>
						";
					?>
				
			</div>
			<div class="offcanvas offcanvas-start phone" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
				<div class="offcanvas-header">
					<h5 class="offcanvas-title" id="offcanvasExampleLabel">info</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body">
				</div>
			</div>
			<!--PC要素-->
			<?php 
				if($deviceTmp == 'pc'){
					echo "
					<div id='side_bar' class='pc'>
						<div class='ranking'>
							<h5 class='ranking_subject'><i class='bi bi-bookmark-heart-fill'></i>総合人気記事</h5>
							<div class='rank_box'>
						";
							
									if($count>0){echo ranking_content($ranking[0], 1);}else{echo "<div class='ranking_form'>1</div>";}
									if($count>1){echo ranking_content($ranking[1], 2);}else{echo "<div class='ranking_form'>2</div>";}
									if($count>2){echo ranking_content($ranking[2], 3);}else{echo "<div class='ranking_form'>3</div>";}
									if($count>3){echo ranking_content($ranking[3], 4);}else{echo "<div class='ranking_form'>4</div>";}
									if($count>4){echo ranking_content($ranking[4], 5);}else{echo "<div class='ranking_form'>5</div>";}
									if($count>5){echo ranking_content($ranking[5], 6);}else{echo "<div class='ranking_form'>6</div>";}
					echo "
							</div>
						</div>
						";
						
					require_once('./snsView.php');	//->share.php
					echo "
						<div class='ad-content'>
							<!-- admax -->
							<script src='https://adm.shinobi.jp/s/46d3c98e92e34309700f37291c7aeb07'></script>
							<!-- admax -->
						</div>
						";
							
					require_once('./category.php');
				}
			?>
				</div>
			</div>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
		<!--device check-->
		<script src="./js/DeviceCheck.js" type="text/javascript"></script>
		<script type="text/javascript">
		var indexOpen = {
				exe: (el)=>{
					var self = indexOpen;
					var keySubject = el.parentNode;
					var indexChi = keySubject.children; 
					var triangle = indexChi[0].children[0];
					if(keySubject.flag == 1){
						for(var i = 1; i < indexChi.length; i++){
							indexChi[i].style.display = 'none';
							keySubject.flag = 0;
						}
						triangle.innerHTML = '▲';
					}else{
						for(var i = 1; i < indexChi.length; i++){
							indexChi[i].style.display = 'block';
							keySubject.flag = 1;
						}
						triangle.innerHTML = '▼';
					}
				}
			}
	</script>
		<script type="text/javascript">
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
		<!--device check end-->
	</body>
</html>
