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
		<link rel="stylesheet" type="text/css" href="./css/statistic.css">
		
	</head>
	<body>
		<?php
			//snsの共有リンクを作成します。　注意！！headerの読み込みより前に書いてください。
			require_once('./share.php');
			$text = '自分だけ？みんなに聞いてみよう。';
			$url = 'http://localhost/public/statistic.php';
			$hashtags = '病み垢さんと繋がりたい';
			$share = new share($text, $url, $hashtags);

			//デバイスのチェックを行います。
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

			if(!isset($_SESSION['userid'])){
				header('Location: ./index.php');
				exit;
			}
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
						<div class='addsense_long'>
							<!-- admax -->
							<script src='https://adm.shinobi.jp/s/c3bbd91acc407a64464bee77ed2d049d'></script>
							<!-- admax -->
						</div>
					";
				}else if($deviceTmp == 'phone'){
					echo"
						<div class='addsense_middle phone'>
							<!-- admax -->
							<script src='https://adm.shinobi.jp/s/7d2d9d5ab82f94844aeffda7752d06b0'></script>
							<!-- admax -->
						</div>
					";
				}
				?>
				<div id='top'>
					<?php
						date_default_timezone_set('Asia/Tokyo');
						$currentTime =  date('Y-m-d H:i:s');
						
						$_stati = array();
						$max_col = 10;													//1ページに入れる最大数
						$pageNum = isset($_POST['pageNum']) ? $_POST['pageNum'] : 0;	//初期値代入のため
						$first = $pageNum * $max_col;
						
						$serchWord = '';												//初期値入力
						$indexCategory = '';											//初期値入力

						require_once('./sanitize.php');
						$data = new databaseInit();

						if(isset($_GET['indexCategory'])){
							$query = "SELECT count(*) FROM statistic WHERE category = :category";
							$result = $data->db->prepare($query);
							$result->bindValue(':category', sanitize($_GET['indexCategory']));
							$indexCategory = $_GET['indexCategory'];
						}else if(isset($_GET['serchWord'])){
							$query = "SELECT count(*) FROM statistic WHERE title LIKE :serch";
							$result = $data->db->prepare($query);
							$result->bindValue(':serch', "%".sanitize($_GET['serchWord'])."%");
							$serchWord = $_GET['serchWord'];
						}
						else{
							$query = "SELECT count(*) FROM statistic";
							$result = $data->db->prepare($query);
						}

						$result->execute();
						$count = $result->fetchAll(PDO::FETCH_ASSOC);
						$count = $count[0]['count(*)'];

						//ページネーションの初期値入力
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
						//ページネーションの初期値入力 end
						
						if(isset($_GET['indexCategory']) && ($count >= 1)){
							//カテゴリー検索で結果があった場合
							$query = "SELECT * FROM statistic WHERE category = :category ORDER BY id DESC LIMIT :first, 10";
							$result = $data->db->prepare($query);
							$result->bindValue(':first', $first, PDO::PARAM_INT);
							$result->bindValue(':category', sanitize($_GET['indexCategory']));
							$result->execute();
							$result = $result->fetchAll(PDO::FETCH_ASSOC);

						}else if(isset($_GET['indexCategory']) && ($count < 1)){
							//カテゴリー検索で結果がなかった場合
							echo '<div id="serch-result">結果は０件でした。</div>';
							$result = [];
						}else if(isset($_GET['serchWord']) && ($count >= 1)){
							//カテゴリー検索で結果があった場合
							$query = "SELECT * FROM statistic WHERE title LIKE :serchWord ORDER BY id DESC LIMIT :first, 10";
							$result = $data->db->prepare($query);
							$result->bindValue(':first', $first, PDO::PARAM_INT);
							$result->bindValue(':serchWord', "%".sanitize($_GET['serchWord'])."%");
							$result->execute();
							$result = $result->fetchAll(PDO::FETCH_ASSOC);

						}else if(isset($_GET['serchWord']) && ($count < 1)){
							//カテゴリー検索で結果がなかった場合
							echo '<div id="serch-result">結果は０件でした。</div>';
							$result = [];
						}
						else{
							$query = "SELECT * FROM statistic ORDER BY id DESC LIMIT :first, 10";
							$result = $data->db->prepare($query);
							$result->bindValue(':first', $first, PDO::PARAM_INT);
							$result->execute();
							$result = $result->fetchAll(PDO::FETCH_ASSOC);
						}

						foreach($result as $col){
							$_stati[$col["id"]] = $col;
							
							echo "
								<div class='statistic_box'>
									<div class='statistic_text text-break'>{$col['title']}</div>
									<div id='good_bad'>
										<img class='good_review' src='./img/good.png' onclick='statistic_exe.good({$col['id']});'>
										<div class='statistic'>
											<div id='dummy{$col['id']}'></div>
											<div class='red_paret'>
												<div class='red'>{$col['good']}人</div>
											</div>
											<div class='grey_paret'>
												<div class='grey'></div>
											</div>
											<div class='blue_paret'>
												<div class='blue'>{$col['bad']}人</div>
											</div>	
										</div>
										<img class='bad_review' src='./img/bad.png' onclick='statistic_exe.bad({$col['id']});'>
									</div>
									<div class='statistic_category'>#{$col['category']}</div>
								</div>
								";
						}
						//統計の最大数idを取得
						$_statiMax = count($_stati) ? max(array_keys($_stati)) : 0 ;
					?>
					<?php
						echo "
						<form class='btngr' action='./statistic.php' method='post'>
							<div class='btn-toolbar' role='toolbar' aria-label='Toolbar with button groups'>
								<div class='btn-group me-2' role='group' aria-label='First group'>
						";
						
						for($button_first = 0; $button_first  < $button_last; $button_first++){
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
				<?php
					echo "
						<div id='bottom'>
							<div id='make_statistic'>
								<div id='send_box'>
									<div id='question'><i class='bi bi-quora' style='margin-right:10px;'></i>質問をする！</div>
									<input id='meke_text' name='statistic_text' type='text' placeholder='タイトルを入れてね。'>
									<select class='form-select' name='category' aria-label='Default select example'>
									  	<option selected>カテゴリーを選んでね</option>
						";

					require_once('./healthArr.php');
					$sample = returnCategory('arr');
					for($i = 0; $i < count($sample); $i++){
						echo"
							<option value='{$sample[$i]}'>{$sample[$i]}</option>
						";
					}

									  	
					echo"
									</select>
						";

							if($deviceTmp == 'pc'){
								echo "
									<div class='btn btn-success' onclick='send_statistic();'><i class='bi bi-brush'></i>作る</div>
								";
							}
							else if($deviceTmp == 'phone'){
								echo "
									<div class='statistic-set'>
										<div class='btn btn-success' onclick='send_statistic();'><i class='bi bi-brush'></i>作る</div>
									</div>
								";
							}
					echo "		</div>
							</div>
						</div>
					";
				

				?>
			</div>
			<?php
				if($deviceTmp == 'pc'){
					echo "
						<div id='side_bar'>
							
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
	
					echo"
						</div>
			        </div>
		            ";
		        }
	        ?>
		</div>	
	</body>
	<script type="text/javascript" src="./js/subajax.js"></script>
	<!--bootstrap-->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<!--bootstrap end-->
	<!--device check-->
	<script src="./js/DeviceCheck.js" type="text/javascript"></script>
	<!--device check end-->
	<script type="text/javascript">
		//------------------------------------
		//	サイドバーカテゴリーの関数
		//------------------------------------
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
		var currentTime = '<?php echo $currentTime;?>';
		var max_count = <?php echo $_statiMax;?>;
		var first = <?php echo $first;?>;

		var indexCategory = '<?php echo $indexCategory != '' ? $indexCategory : 'undefined';?>';
		var serchWord = '<?php echo $serchWord != '' ? $serchWord : 'undefined';?>';

		var statistic_exe = {
			red: document.getElementsByClassName('red'),
			blue: document.getElementsByClassName('blue'),
			exe:()=>{
				var self = statistic_exe;
				for(var i = 0; i < self.red.length; i++){
					var good_num = self.red[i].textContent.replace('人', '');
					var bad_num = self.blue[i].textContent.replace('人', '');
					var g = Number(good_num);
					var b = Number(bad_num);

					var sum = g + b;
					if( g == 0){
						self.red[i].textContent = '';
						self.red[i].style.width = '0%';
					}else{
						self.red[i].style.width = (g/sum*100)+'%';
					}
					if( b == 0){
						self.blue[i].textContent = '';
						self.blue[i].style.width = '0%';
					}else{
						self.blue[i].style.width = (b/sum*100)+'%';
					}
					
				}				
			},
			good:(id)=>{
				var self = statistic_exe;
				try{
					
					if(isNaN(id)){id = id.target.eventParam;}
					if(id == '' || id == undefined){ throw new Error('idがありません。')};
					
					var data = 'id='+id+'&which=good';
					xhrObject.ajax("./updateStatistic.php", undefined, 'POST', data, 'text');
				}catch(e){
					alert(e);
				}
			},
			bad:(id)=>{
				var self = statistic_exe;
				try{

					if(isNaN(id)){id = id.target.eventParam;}
					if(id == '' || id == undefined){ throw new Error('idがありません。')};

					var data = 'id='+id+'&which=bad';
					xhrObject.ajax("./updateStatistic.php", undefined, 'POST', data, 'text');
				}catch(e){
					alert(e);
				}
			}

		}
		statistic_exe.exe();

		function checkSendTime(xhr){
			if(xhr.responseText != 'undefined'){
				alert(xhr.responseText);
			}
		}
		function send_statistic(){
			var statistic = document.getElementsByName("statistic_text")[0].value;
			var category = document.getElementsByName("category")[0].value;
			var data = 'statistic='+statistic+'&category='+category+'&userid='+<?php echo $_SESSION['userid'];?>;
			try{
				if(statistic.length == 0){ throw new Error('タイトルが入力されていません。'); }
				if(statistic.length > 50){ throw new Error('タイトルは50文字以内までです。'); }
				if(category == 'カテゴリーを選んでね'){ throw new Error('カテゴリが選択されていません。'); }
				
				xhrObject.ajax("./insertStatistic.php", checkSendTime, 'POST', data, 'text');
				document.getElementsByName("statistic_text")[0].value = "";
				document.getElementsByName("category")[0].value = -100;

			}catch(e){
				alert(e)
			}		
		}

		Date.prototype.toLocaleString = function()
		{
		    return [
		        this.getFullYear(),
		        this.getMonth() + 1,
		        this.getDate()
		        ].join( '/' ) + ' '
		        + this.toLocaleTimeString();
		}
		var loadExe = {
			red: 0,
			blue: 0,
			id: 0,
			ini:(id, resolve)=>{
				var self = loadExe;
				try{
					self.id = document.getElementById('dummy'+id);
					if(!self.id){throw new Error('Error:loadExe.ini')}
					self.red = self.id.nextElementSibling.children[0];
					self.blue = self.id.nextElementSibling.nextElementSibling.nextElementSibling.children[0];
					resolve();
				}catch(e){
					console.log(e);
				}

			},
			exe:(data)=>{
				var self = loadExe;
				
				var g = Number(data['good']);
				var b = Number(data['bad']);

				var sum = g + b;
				if( g == 0){
					self.red.textContent = '';
					self.red.style.width = '0%';
				}else{
					self.red.textContent = g + '人';
					self.red.style.width = (g/sum*100)+'%';
				}
				if( b == 0){
					self.blue.textContent = '';
					self.blue.style.width = '0%';
				}else{
					self.blue.textContent = b + '人';
					self.blue.style.width = (b/sum*100)+'%';
				}

					
			}
		}
		function loadStatistic(xhr){
			//更新があれば関数の時間を更新する。
			currentTime = new Date().toLocaleString('ja-JP', {era:'long'});
			//console.log('outer');
			if(xhr.responseText != ''){
				//console.log('inner');
				var json = JSON.parse(xhr.responseText);
				Object.keys(json).forEach((key)=>{
					var data = json[key]
					var id = data['id'];
					const promise = new Promise((resolve)=>{
						loadExe.ini(id, resolve);
					}).then(()=>{
						loadExe.exe(data);
					})
				});
			}
		}
		function reloadStatistic(xhr, resolve){
			if(xhr.responseText != ''){
				var json = JSON.parse(xhr.responseText);
				Object.keys(json).forEach((key)=>{
					var piece = json[key];
					
					var statistic_box = document.createElement("div");
					statistic_box.classList.add('statistic_box');
					
					var statistic_text = document.createElement("div");
					statistic_text.classList.add('statistic_text');
					statistic_text.textContent = piece['title'];
					
					var good_bad = document.createElement("div");
					good_bad.setAttribute("id","good_bad");
					
					var good_review = document.createElement("img");
					good_review.src = "./img/good.png";
					good_review.classList.add('good_review');
					good_review.addEventListener('click', (e)=>{
						statistic_exe.good(e);
					});
					good_review.eventParam = key;

					var statistic = document.createElement("div");
					statistic.classList.add('statistic');

					var dummykey = document.createElement("div"); 
					dummykey.setAttribute("id", 'dummy'+key);

					var red_paret = document.createElement("div");
					red_paret.classList.add('red_paret');
					var red = document.createElement("div");
					red.classList.add('red');
					red.textContent = '';
					red.style.width = '0%';

					red_paret.appendChild(red);

					var grey_paret = document.createElement("div");
					grey_paret.classList.add('grey_paret');
					var grey = document.createElement("div");
					grey.classList.add('grey');
					grey_paret.appendChild(grey);

					var blue_paret = document.createElement("div");
					blue_paret.classList.add('blue_paret');
					var blue = document.createElement("div");
					blue.classList.add('blue');
					blue.textContent = '';
					blue.style.width = '0%';

					blue_paret.appendChild(blue);
					
					statistic.appendChild(dummykey);
					statistic.appendChild(red_paret);
					statistic.appendChild(grey_paret);
					statistic.appendChild(blue_paret);

					var bad_review = document.createElement("img");
					bad_review.classList.add('bad_review');
					bad_review.src = "./img/bad.png";
					bad_review.addEventListener('click', (e)=>{
						statistic_exe.bad(e);
					});
					bad_review.eventParam = key;


					good_bad.appendChild(good_review);
					good_bad.appendChild(statistic);
					good_bad.appendChild(bad_review);

					var statistic_category = document.createElement("div");
					statistic_category.classList.add('statistic_category');
					statistic_category.textContent = piece['category'];
					
					statistic_box.appendChild(statistic_text);
					statistic_box.appendChild(good_bad);
					statistic_box.appendChild(statistic_category);

					var top = document.getElementById('top');
					top.prepend(statistic_box);

					max_count = key;
				});
				resolve();
			}
		}
		function doneStatisitic(){
			var statistic_box = document.getElementsByClassName('statistic_box');
			if(statistic_box.length > 10){
				var max_length = statistic_box.length - 1;
				statistic_box[max_length].remove();
			}
		}

		function reloadAbsorber(xhr){
			const promise = new Promise((resolve)=>{
				reloadStatistic(xhr, resolve);
			}).then(()=>{
				doneStatisitic();
			})
		}
		setInterval(()=>{
			if(indexCategory != 'undefined'){
				var data = "first="+first+'&currentTime='+currentTime+'&category='+indexCategory;
				var data2 = "max="+max_count+'&category='+indexCategory;
			}else if(serchWord != 'undefined'){
				var data = "first="+first+'&currentTime='+currentTime+'&serchWord='+serchWord;
				var data2 = "max="+max_count+'&serchWord='+serchWord;
			}else{
				var data = "first="+first+'&currentTime='+currentTime;
				var data2 = "max="+max_count;

			}
			
			xhrObject.ajax("./reloadStatisitc.php", reloadAbsorber, 'POST', data2, 'text');
			xhrObject.ajax("./loadStatistic.php", loadStatistic, 'POST', data, 'text');
		},1000);
	</script>

</html>
