<?php 
	session_start();
	require_once('./sanitize.php');
	if(!isset($_GET['id'])){
		header('Location: ./index.php');
		exit();
	}else{
		$roomid	= sanitize($_GET['id']);	
	}
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
		<link rel="stylesheet" type="text/css" href="./css/log.css">
		<link rel="stylesheet" href="./css/kari.css">
		
	</head>
	<body>
		<div id="enter">
			<div id="enter_title">
				アカウントを作ってくだちい				
			</div>
			<div class="centering">
				<div id="enter_form">
					<div id="enter-img-list">
						<img id="enter_img" src="./icon/photo.jpg"> 
						<select class="form-select" name="iconList" aria-label="Default select example">
							<option selected>アイコンを選んでね</option>
						    <option value="monkey">猿</option>
						    <option value="kaba">カバ</option>
						    <option value="seal">イルカ</option>
						    <option value="koala">コアラ</option>
						    <option value="panda">パンダ</option>
						    <option value="Frankenstein">フランケンシュタイン</option>
							<option value="bull">雄牛</option>
						    <option value="bulldog">ブルドッグ</option>
						    <option value="Lion">ライオン</option>
						    <option value="cow">牛</option>
						    <option value="bear">熊</option>
						    <option value="hawk">鷲</option>
						    <option value="racoon">ラッコ</option>
						    <option value="cat">猫</option>
						    <option value="wolf">狼</option>
						</select>
					</div>

					<input type="text" name="username" id="entername" placeholder="名前を入れてね">
					<div id="enter_button">
						<input type="submit" class="btn btn-primary" onclick="send_enter.ini();" value="作る">
					</div>
				</div>
			</div>
		</div>
		<?php
			//snsの共有リンクを作成します。　注意！！headerの読み込みより前に書いてください。
			require_once('./share.php');
			$text = 'not normal 普通じゃなくてもいいって言えるように';
			$url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$hashtags = 'notnormal';
			$share = new share($text, $url, $hashtags);

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
		<div id="middle"><!--EOMより上に書く-->
		<?php
			if(!isset($_SESSION['username']) || !isset($_SESSION['userid']) || !isset($_SESSION['iconList'])){
				echo <<<EOM
				<script type="text/javascript">
					
					var middle = document.getElementById("middle");
					middle.style.display = "none";
					
					window.addEventListener('load', ()=>{
						var enter = document.getElementById("enter");
						enter.style.display = "block";
						enter.style.position = "fixed";
						var enter_half_w = Math.ceil(Number(enter.clientWidth) / 2);
						var enter_half_h = Math.ceil(Number(enter.clientHeight) / 2);
						var half_hight = window.innerHeight / 2;
						var half_width = window.innerWidth / 2;
						enter.style.marginTop = String(half_hight - enter_half_h);
						enter.style.marginLeft = String(half_width - enter_half_w);
					
					});


				</script>
				EOM;

			}
			$username = $_SESSION['username'];
			$userid = $_SESSION['userid'];
			$icon = $_SESSION['iconList'];
		?>
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
						/*------------------------------------------
						*	部屋の情報を取得（画像、相談事等）
						------------------------------------------*/
						$data = new databaseInit();
						$result = $data->db->prepare("SELECT * FROM room WHERE id = :roomid");
						$result->bindValue(':roomid', $roomid);
						$result->execute();
						$room = $result->fetchAll(PDO::FETCH_ASSOC);
						if(count($room) == 0){
							header_exe();
						}
						foreach( $room as $col){
							echo"
								<div id='container'>
									<div id='roomtitle'>
										<div id='img_base' class='second_parts'>
											<img id='roomImg'src='{$col['roomimg']}'>
										</div>
										<div id='xtitle'>
											<div id='roomname'><strong>{$col['roomname']}</strong></div>
											<div id='xtitle_bottom'>
												<div id='good_container'>
													<img class='message_good' src='./img/good.png'></img>
													<div class='iine_num'>{$col['iine']}</div>
												</div>
												<div id='category'>#{$col['category']}</div>
											</div>
										</div>
									</div>
									<div id='second_stage'>
										<div id='consolation' class='second_parts'>相談事:<br/>{$col['consolationText']}</div>
										<div id='exitArea'><a class='btn btn-primary' href='./room.php'>退出</a></div>
									</div>
									
								</div>
							";
							$roomname = $col['roomname'];
							$_SESSION['roomname'] = $roomname;
						}
						/*------------------------------------------
						*	入室履歴をチェック
						-------------------------------------------*/
						$query = "SELECT roomid FROM userid WHERE id =:userid";
						$result = $data->db->prepare($query);
						$result->bindValue(':userid', $userid);
						$result->execute();
						$result = $result->fetchAll(PDO::FETCH_ASSOC);
						if($result[0]["roomid"] != $roomid){

							//echo 'テスト用';
							//echo 'on'.PHP_EOL;
							//echo $roomid.PHP_EOL;
							//echo $userid.PHP_EOL;

							
							$query = "UPDATE userid SET roomid = :roomid WHERE id = :userid";
							$result = $data->db->prepare($query);
							$result->bindValue(':userid', $userid, PDO::PARAM_INT);
							$result->bindValue(':roomid', $roomid, PDO::PARAM_INT);
							$result->execute();
							/*------------------------------------------
							*	入室情報　
							*	useridにroomidが現在の部屋が登録されていなかったら、
							*	更新して、masterから入室を知らされる。
							-------------------------------------------*/
							$query = "INSERT INTO log SET username = :username, userid = :id, icon = :icon, type = :type, text = :text, roomid = :roomid";
							$result = $data->db->prepare($query);
							$result->bindValue(':username', 'master');
							$result->bindValue(':id', 0);
							$result->bindValue(':icon', 'Doctor');
							$result->bindValue(':type' , 'info');
							$result->bindValue(':text' , $username.'が一人迷い込んできました。');
							$result->bindValue(':roomid' , $roomid);
							$result->execute();
						}
					?>
					<div class="accordion phone" id="accordionExample">
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingOne">
								<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								ますたーからのメッセージ
								</button>
							</h2>
							<div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									<strong>このスレッドが良質なものだと評価されれば、保管庫へと移送されます。</strong>
									条件は、スレッドの数が200以上かつ、いいねが10以上となります。
									このスレッドを、多くの人に見てもらいタイという方はぜひ上の👍ボタンをよろしくお願いします。
								</div>
							</div>
						</div>
					</div>
					<?php
						/*------------------------------------------
						*	チャット内容の取得
						------------------------------------------*/
						$_chat = array();
						
						$result = $data->db->prepare("SELECT chid, chid, icon, username, type, date, text FROM log WHERE log.roomid = :roomid");// order by date desc limit 30
						$result->bindValue(':roomid', $roomid);
						$result->execute();

						foreach($result->fetchAll(PDO::FETCH_ASSOC) as $col){
							$_chat[$col["chid"]] = $col;
							echo"
								<li class='log'>
									<div class='message_right'>
										<img class='icon' src='./icon/{$col['icon']}.jpg'>
									</div>
									<div class='message_left'>
										<div class='other_info'>
											<span class='info_time'>{$col['date']}</span>
										</div>
										<div class='message_container'>

											<div class='name' onclick='removeToInput("."this".")'>@{$col['username']}</div>
								";
								if($col['type'] == 'text' || $col['type'] == 'info' ){
									echo 	"<div class='message'>{$col['text']}</div>";
								}else if( $col['type'] == 'img' ){
									echo 	"<img class='logs_img' src={$col['text']}>";
								}

							echo"
										</div>
									</div>
								</li>
								";

						}
						$result->closeCursor();

						// 直近のIDをセッションに登録
						$_SESSION["max_chid"] = count($_chat) ? max(array_keys($_chat)) : 0 ;

						/*
						count — 配列または Countable オブジェクトに含まれるすべての要素の数を数える
						mysql_fetch_assoc — 連想配列として結果の行を取得する
						mysql_free_result — 結果保持用メモリを開放する
						*/

					?>
				</ul>
				<div class="separate-box"></div>
				<div id="send_message">
					<div id=scrollArea onclick='logScroll.ScrollControl();'>
						<div id=scrollButton href="./room.php">
							<i id="dummy_scroll" class="fa-solid fa-angles-down scroll"></i>
						</div>
					</div>
					<div id="option_box">
						<img id="img_box" src="./img/photo.png" onclick="box.boxOpen();">
						<div id="img_container">
							<div id="explanation">送信する画像を選択してください</div> 
							<div id="img_middle">
								<canvas id="canvas" width="400px" height="300px"></canvas>
								<img id="img_top" src="./icon/photo.jpg">
								<label id="img_label">
									画像の読み込み
									<input id='file_img' type="file" name="logImgUpload" accept="image/*">
								</label>
							</div>
							<div id="img_bottom">
								<div id="img_cancel" onclick="box.boxClose();">キャンセル</div>
								<div class="resetSubmit img_send" onclick="imgTimeCheck()">送る</div>
							</div>
						</div>
					</div>
					<textarea id="insert_message" onkeypress="enterPress(event, sendChatData);" type="textarea" name="log" cols="60" rows="30"></textarea> 
					<img onclick="sendChatData();" class="resetSubmit" src="./img/paper_airplane.png">
				</div>
			</div>
			<div id="side_bar" class="pc">
				<div class="accordion" id="accordionExample">
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingOne">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							ますたーからのメッセージ
							</button>
						</h2>
						<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<strong>このスレッドが良質なものだと評価されれば、保管庫へと移送されます。</strong>
								条件は、スレッドの数が200以上かつ、いいねが100以上となります。
								このスレッドを、多くの人に見てもらいタイという方はぜひ横の👍ボタンをよろしくお願いします。
							</div>
						</div>
					</div>
				</div>
				<?php 
					require_once('./snsView.php');	//->share.php
				?>
				<div class="ad-content">
					<div class="ads"> ad1 </div>
				</div>
			</div>
		</div>			
		
	</body>
	<script type="text/javascript" src="./js/file_read.js"></script>
	<script type="text/javascript" src="./js/log_scroll.js"></script>
	<script type="text/javascript" src="./js/subajax.js"></script>
	<script type="text/javascript" src="./js/exist.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="./js/DeviceCheck.js" type="text/javascript"></script>
	<script src="./js/enterpress.js" type="text/javascript"></script>
	<script src="./js/resize.js" type="text/javascript"></script>
	<script type="text/javascript">
		//------------------------------------------------------
		//	被リンクから来られた方向けのログインフォーム
		//------------------------------------------------------
		window.addEventListener('load', ()=>{
			var enter_img = document.getElementById('enter_img');
			var iconList = document.getElementsByName('iconList')[0];
			iconList.addEventListener('change', (e)=>{
				enter_img.src = './icon/'+iconList.value+'.jpg';
			});
		});

		function reload(){
			location.reload();	
		}
		var send_enter ={
			iconList: document.getElementsByName('iconList')[0],
			username: document.getElementsByName('username')[0],
			ini:()=>{
				let self = send_enter;
				if(self.iconList.value == ''){
					alert('アイコンを選んでください');
				}else if(self.username.value == ""){
					alert('ユーザー名を入力してください');
				}else{
					self.exe();
				}

			},
			exe:()=>{
				let self = send_enter;
				var data = 'username='+self.username.value+'&icon='+self.iconList.value;
		 		xhrObject.ajax("./loginAbsorbmin.php", reload, 'POST', data, 'text');
			}

		}
	</script>
	<script type="text/javascript">
		//-------------------------------------------------------
		//	テキストエリアにユーザーの名前を入れるプログラム
		//-------------------------------------------------------
		function removeToInput(elem){
			let eltxt = elem.innerHTML;
			var inp = document.getElementById('insert_message');
			var tmp = inp.value;
			inp.value = tmp + eltxt;
		}
	</script>
	<script type="text/javascript">
		//-------------------------------------------------------
		//	デバイスごとの表示を切り替えるプログラム
		//-------------------------------------------------------
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
	<script>
		function iineReload(xhr){
			var json = JSON.parse(xhr.responseText);
			var iine = document.getElementsByClassName('iine_num');
			iine[0].textContent = json[0]['iine'];
		}

		function goodAdd(xhr){
			if(xhr.responseText != 'undefined'){
				alert(xhr.responseText);
			}
		}
		var iineLoad = {
			iine: document.getElementById('good_container'),
			iineExt:()=>{
				var self = iineLoad;
				self.iine.addEventListener('click', (e)=>{
					var evt = e.currentTarget;
					var data = "userid="+<?php echo $userid;?>+"&roomid="+<?php echo $roomid;?>+"&type=text";
					
					xhrObject.ajax("./iineAdd.php", goodAdd, 'POST', data, 'text');		
				});			
			}
		}
		window.addEventListener('load', ()=>{
			img_preview.preview(					//file_read.jsから読み込み 
				document.getElementById('file_img'),
				document.getElementById('img_top'),
				document.getElementById('canvas'),
				1000
				);
			iineLoad.iineExt();
		});

		var box = {
				option_list: document.getElementById('img_container'),
				boxOpen: ()=>{
					var self = box;
					self.option_list.style.display = 'flex';
				},
				boxClose: ()=>{
					var self = box;
					self.option_list.style.display = 'none';
				}
			}
	</script>
	<script type="text/javascript">

		// 名前か文章にカーソルをフォーカス
		if(document.getElementsByName("text")[0]) document.getElementsByName("text")[0].focus();
		if(document.getElementsByName("name")[0]) document.getElementsByName("name")[0].focus();

	//--------------------------------------------------------------------------------------------

		var columnInsert = {
			board : document.getElementById("logs_area"),
			child : '',
			Insert: (response, type)=>{
				var self = columnInsert; 
				self.child = document.createElement("li");
				self.child.classList.add('log');
				
				var right = document.createElement("div");
				right.classList.add('message_right');
				var iconimg = document.createElement("img");
				iconimg.classList.add('icon');
				iconimg.src = './icon/'+ response['icon']+'.jpg';
				
				right.appendChild(iconimg);
				
				var left = document.createElement("div");
				left.classList.add('message_left');

				var other_info = document.createElement("div");
				other_info.classList.add('other_info');	
				
				var info_time = document.createElement("span");
				info_time.classList.add('info_time');
				info_time.textContent =  response['date'];

				other_info.appendChild(info_time);
				left.appendChild(other_info);
				
				var container = document.createElement("div");
				container.classList.add('message_container');

				var name = document.createElement("div");
				name.classList.add('name');
				name.textContent = '@'+response.username;
				if( type == 'text' || type == 'info' ){
					var message = document.createElement("div");
					message.classList.add('message');
					message.textContent = response.text;
				
				}else if( type == 'img' ){
					var message = document.createElement("img");
					message.classList.add('logs_img');
					message.src = response.text;
					box.boxClose();
				}
				name.addEventListener('click', (event)=>{
					removeToInput(event.currentTarget);
				})

				container.appendChild(name);
				container.appendChild(message);
				left.appendChild(container);
	
				self.child.appendChild(right);
				self.child.appendChild(left);
				self.board.appendChild(self.child);
				if(logScroll.stopFlag == 0){
					logScroll.ScrollBottom2();
				}
			}
		}

		// 新たな書き込みがあった場合に表示する
		function displayHtml(xhr){
			//loadChatData.php にて調整のためにundefinedをstringで判定している。
			if(xhr.responseText != '' && xhr.responseText != "undefined"){
				var json = JSON.parse(xhr.responseText);
				Object.keys(json).forEach((key)=>{
					columnInsert.Insert(json[key], json[key]['type']);
				})
			}
		}
	//--------------------------------------------------------------------------------------------
		function promise_sendChatData(){
			var data = "roomid="+<?php echo $roomid;?>;
			xhrObject.ajax("./loadChatData.php", displayHtml, 'POST', data, 'text');
		}

		// チャットに書き込みをする
		function sendChatData(){
			try{
				var el = document.getElementsByName("log")[0].value;
				var tmp = el.replace(/\s+/g, "");							//空白文字対策
				if(tmp.length == 0){
					throw new Error('テキストが入力されていません。')
				}else if(tmp.length > 250){
					 throw new Error('文字数は最大250文字までです。');
				}
				var log = encodeURIComponent(el);
				var data = "userid="+<?php echo $userid;?>+"&username="+'<?php echo $username;?>'+"&icon="+'<?php echo $icon;?>'+"&roomid="+<?php echo $roomid;?>+"&type=text"+"&log="+log;
				var commentNum = "roomid="+<?php echo $roomid;?>;

				document.getElementsByName("log")[0].value = "";

				xhrObject.ajax("./sendChatData.php", promise_sendChatData, 'POST', data, 'text');
				xhrObject.ajax("./commentNumUpdate.php", undefined, 'POST', commentNum, 'text');
			}
			catch(e){
				alert(e);
			}
		}
	//--------------------------------------------------------------------------------------------
		function notExistFile(xhr){
			if(xhr.responseText == 'fail'){
				alert('fileがありません。再度、ファイルを確認してください。')
			}else{
				var data2 = "roomid="+<?php echo $roomid;?>;
				xhrObject.ajax("./loadChatData.php", displayHtml, 'POST', data2, 'text');
			}
		}
		var flag = 'pass';
		function imgTimeCheck(){
			if(flag == 'fail'){
				alert('再度画像を投稿する場合は1分後までお待ちください。');
			}else{
				flag = 'fail';
				sendChatImg();
				setTimeout(() => {
				    flag = 'pass';
				}, 60000);
			}
		}
		//チャットからのImgを送信して取得する。
		function sendChatImg(xhr){
			var data = new FormData();
			var data2 = "roomid="+<?php echo $roomid;?>;
			var file = document.getElementById('file_img').files[0];
			var canvas = document.getElementById('canvas');
			var IsFile = imageUpload(canvas, [file.name, file.type]);

			data.append('userid', <?php echo $userid;?>);
			data.append('username', '<?php echo $username;?>');
			data.append('icon', '<?php echo $icon;?>');
			data.append('roomid', <?php echo $roomid;?>);
			data.append('type', 'img');
			data.append('file', IsFile);

			xhrObject.ajax("./sendImgData.php", notExistFile, 'POST', data, 'file'); 
			xhrObject.ajax("./commentNumUpdate.php", undefined, 'POST', data2, 'text');
		}
	//--------------------------------------------------------------------------------------------

		// 2秒ごとにチャットの内容を取りに行く
		setInterval(()=>{
			var data = "roomid="+<?php echo $roomid;?>;
			xhrObject.ajax("./loadChatData.php", displayHtml, 'POST', data, 'text');
			xhrObject.ajax("./iineReload.php", iineReload, 'POST', data, 'text');
		},2000);

		//最初の一回分
		(function(){
			var data = "roomid="+<?php echo $roomid;?>;
			xhrObject.ajax("./existenceCheck.php", undefined, 'POST', data, 'text');
		}());
		
		setInterval(()=>{
			var data = "roomid="+<?php echo $roomid;?>;
			var data2 = "userid="+<?php echo $userid;?>;
			xhrObject.ajax("./existenceCheck.php", undefined, 'POST', data, 'text');
			xhrObject.ajax("./existUserCheck.php", return_existUserCheck, 'POST', data2, 'text');
		},300000);

	</script>
</html>
