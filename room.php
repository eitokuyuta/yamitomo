<?php
	session_start();
	require_once('./seo.php');
	if( !isset($_SESSION['username']) || !isset($_SESSION['iconList']) || !isset($_SESSION['userid'])){
		header('Location: ./index.php');
		exit;
	}
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
		<!--bootstrap end-->
		<link rel="stylesheet" type="text/css" href="./css/reset.css">
		<link rel="stylesheet" type="text/css" href="./css/object.css">
		<link rel="stylesheet" type="text/css" href="./css/room.css">
		<link rel="stylesheet" type="text/css" href="./css/phoneCategory.css">
		
	</head>
	<body>
		<?php

			require_once('browser_deviceCheck.php');
			$deviceTmp = '';
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
			require_once('./emptyHouse.php');				//時間経過により部屋を削除か保管庫に移す
			require_once('./deleteUser.php');				

			$userid = $_SESSION['userid'];
		?>

		<div id="middle">
			<div id="main">
				<div id="room_title">
					<div id="hoge">● 広場</div>
					<?php
						if($deviceTmp == 'phone'){
							require_once('./snsView.php');	//->share.php
						}
					?>
				</div>
				<?php
				if($deviceTmp == 'pc'){
					echo "
					<div class='addsense_long pc'>
							<!-- admax -->
							<script src='https://adm.shinobi.jp/s/c3bbd91acc407a64464bee77ed2d049d'></script>
							<!-- admax -->
					</div>
					";
				}else if( $deviceTmp == 'phone'){
					echo "
					<div class='addsense_middle phone'> 
						
					</div>
					";
				}

				if($deviceTmp == "phone"){
					echo "
						<div id='user_info' class='phone'>
							<div id='user_icon'>
								<img id='icon_img' src='./icon/".$_SESSION['iconList'].".jpg'>
							</div>
							<div id='user_name' class='text-break'>".$_SESSION['username']."</div>
							
							<form id='make_room' action='./makeroom.php' method='post'>
								<input type='hidden' name='iconList' value='./icon/".$_SESSION['iconList']."jpg'>
								<input type='hidden' name='userid' value='". $_SESSION['userid']."'>
								<input type='submit' id='make_room_a' class='btn btn-danger' value='相談する'>
							</form>
						</div>
					";
				}
				?>
				<?php if($deviceTmp == 'phone'){require_once('./phoneCategory.php');}?>
				<?php

					$_chat = array();
					$max_col = 10;													//1ページに入れる最大数
					$pageNum = isset($_POST['pageNum']) ? $_POST['pageNum'] : 0;	//初期値代入のため
					$first = $pageNum * $max_col;

					require_once('./sanitize.php');
					$data = new databaseInit();


					if(isset($_GET['indexCategory'])){
						$query = "SELECT count(*) FROM room WHERE category = :category";
						$result = $data->db->prepare($query);
						$result->bindValue(':category', sanitize($_GET['indexCategory']));
					}else if(isset($_GET['serchWord'])){
						$query = "SELECT count(*) FROM room WHERE roomname LIKE :serch";
						$result = $data->db->prepare($query);
						$result->bindValue(':serch', "%".sanitize($_GET['serchWord'])."%");
					}
					else{
						$query = "SELECT count(*) FROM room";
						$result = $data->db->prepare($query);
					}


					$result->execute();
					$count = $result->fetchAll(PDO::FETCH_ASSOC);
					$count = $count[0]['count(*)'];

					$button_num = ceil($count / $max_col);	//ボタンの個数
																															//訂正：5 -> ($pageNum + 3)
					$button_first = ($pageNum - 2) > 0 && $button_num > ($pageNum + 3) ? $pageNum - 2 : 0;//0->初期最小個数
					if(($pageNum - 2) > 0 && $button_num >= 5){
						$button_last = $pageNum + 3;
					}else if((($pageNum - 2) >= 0 && $button_num < 5) || (($pageNum - 2) <= 0 && $button_num < 5)){
						$button_last = $button_num;
					}else if(($pageNum - 2) <= 0 && $button_num >= 5){
						$button_last = 5;
					}

					if($button_last > $button_num){
						$button_last = $button_num;
						$button_first = $button_last - 5;
					}
					
					if(isset($_GET['indexCategory']) && ($count >= 1)){
						//カテゴリー検索で結果があった場合
						$query = "SELECT * FROM room WHERE category = :category ORDER BY id DESC LIMIT :first, 10";
						$result = $data->db->prepare($query);
						$result->bindValue(':first', $first, PDO::PARAM_INT);
						$result->bindValue(':category', sanitize($_GET['indexCategory']));
						$result->execute();
						$room = $result->fetchAll(PDO::FETCH_ASSOC);

					}else if(isset($_GET['indexCategory']) && ($count < 1)){
						//カテゴリー検索で結果がなかった場合
						echo '<div id="serch-result">結果は０件でした。</div>';
						$room = [];
					}else if(isset($_GET['serchWord']) && ($count >= 1)){
						//カテゴリー検索で結果があった場合
						$query = "SELECT * FROM room WHERE roomname LIKE :serchWord ORDER BY id DESC LIMIT :first, 10";
						$result = $data->db->prepare($query);
						$result->bindValue(':first', $first, PDO::PARAM_INT);
						$result->bindValue(':serchWord', "%".sanitize($_GET['serchWord'])."%");
						$result->execute();
						$room = $result->fetchAll(PDO::FETCH_ASSOC);

					}else if(isset($_GET['serchWord']) && ($count < 1)){
						//カテゴリー検索で結果がなかった場合
						echo '<div id="serch-result">結果は０件でした。</div>';
						$room = [];
					}
					else{
						$query = "SELECT * FROM room ORDER BY id DESC LIMIT :first, 10";
						$result = $data->db->prepare($query);
						$result->bindValue(':first', $first, PDO::PARAM_INT);
						$result->execute();
						$room = $result->fetchAll(PDO::FETCH_ASSOC);
					}
					foreach($room as $col){
						if($col['roomimg'] == ''){ $col['roomimg'] = './icon/photo.jpg'; }
						echo"
							<div class='roombox'>
								<img class='roombox_right' src='{$col['roomimg']}'>
								<div class='roombox_left'>
									<div class='roombox_left_top'>
										<div class='comment_num font_num'>コメント数:{$col['commentNum']}</div>
									</div>
									<div class='roomname text-break'>{$col['roomname']}</div>
									<div class='category'>カテゴリー:{$col['category']}</div>
								</div>
								<form class='enter_room' method='get' action='./log.php' name='room'>
									<input type='hidden' name='id' value={$col['id']}>
									<input class='btn btn-info' type='submit' value='入室'>
								</form>
							</div>
							";
					}

				?>
				<?php

					/*-------------------------------------------------------
					*	遷移用のボタンを生成する
					*	@param button_num
					---------------------------------------------------------*/
					echo "
					<form class='btngr' action='./room.php' method='post'>
						<div class='btn-toolbar' role='toolbar' aria-label='Toolbar with button groups'>
							<div class='btn-group me-2' role='group' aria-label='First group'>
					";
					for($button_first = 0 ; $button_first  < $button_last; $button_first++){
						$y = $button_first + 1;
						echo "
							<button type='submit' class='btn btn-primary' name='pageNum' value='{$button_first }'>{$y}</button>
						";
					}
					echo "
							</div>
						</div>
					</form>
					";
				?>
			</div>

			<?php
			if( $deviceTmp == 'pc'){
				echo "
					<div id='side_bar' class='pc'>
						<div id='user_info'>
							<div id='user_icon'>
								<img id='icon_img' src='./icon/".$_SESSION['iconList'].".jpg'>
							</div>
							<div id='user_name' class='text-break'><strong>".$_SESSION['username']."</strong></div>
							<form id='make_room' action='./makeroom.php' method='post'>
								<input type='hidden' name='iconList' value='./icon/".$_SESSION['iconList'].".jpg'>
								<input type='hidden' name='userid' value='".$_SESSION['userid']."'>
								<input type='submit' id='make_room_a' class='resetSubmit' value='相談する'>
							</form>
						</div>
				";
				require_once('./snsView.php');	//->share.php

				echo"
						<div class='ad-content'>
							<!-- admax -->
							<script src='https://adm.shinobi.jp/s/46d3c98e92e34309700f37291c7aeb07'></script>
							<!-- admax -->
						</div>
					";
				require_once('./category.php');

							
				echo"
						</div>
					</div>

				";
			}
			?>

		</div>
	</body>
	<script type="text/javascript" src="./js/subajax.js"></script>
	<script type="text/javascript" src="./js/exist.js"></script>
	<script type="text/javascript" src="./js/DeviceCheck.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

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
	<script>
		setInterval(()=>{
			var data2 = "userid="+<?php echo $userid;?>;
			xhrObject.ajax("./existUserCheck.php", return_existUserCheck, 'POST', data2, 'text');
		},300000);
	</script>
</html>
