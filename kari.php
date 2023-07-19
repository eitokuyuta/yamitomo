<?php
	session_start();
?>

<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="noindex,nofollow">
		<!--bootstrap-->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
		<!--bootstrap end-->
		
	</head>
	<style>
		body {
			width: 100%;
			height: 90%;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-wrap: wrap;
			background-color: rgba(128, 128, 128, 0.51);
		}
		#xx-1 {
			width: 30vw;
			height: auto;
			justify-content: flex-start;
			align-content: center;
			flex-flow: column;
			display: flex;
			padding-top: 3vw;
			margin-right: 1vw;
		}
		.yy-1 {
			margin-bottom: 1.2vw;
		}
		#img {
			width: 100%;
			height: 10vw;
			display: flex;
			justify-content: center;
			align-items: flex-start;
			flex-flow: column;
		}
		#file_read {
			display: none;
		}
		#read_label {
			height: 10%;
			width: auto;
		}
		#room_img {
			width: auto;
			height: 90%;
		}
		#canvas {
			display: none;
		}
		#xx-4 {
			width: 30vw;
			height: 12vw;
		}
		#kari_send {
			height: auto;
			width: 100%;
			display: flex;
			justify-content: flex-end;
			align-items: center;
		}
		#generateBox {
			width: 30vw;
			height: 100%;
			display: flex;
			flex-flow: column;
			justify-content: flex-start;
			align-items: flex-start;
			padding-top: 2.5vw;
			padding-left: 1vw;
			border-left: dotted 3px black;
		}
		#pass_setter {
			width: 100%;
			height: auto;
			display: flex;
			flex-wrap: nowrap;
		}
		#pass{
			width: 15vw;
			height: auto;
		}
	</style>
	<body>
		<?php
			//テスト
			//------------------------------------
			//session_destroy(); 	
			//$_POST = array();
			//var_dump($_POST['password']);
			//var_dump($_SESSION);

			//	宣言
			//------------------------------------
			require_once('./databaseInit.php');
			

			//	ログイン
			//------------------------------------
			if(isset($_POST['password'])){
				$data = new databaseInit();
				$query = "SELECT * FROM login";
				$result = $data->db->prepare($query);
				$result->execute();
				$result = $result->fetchAll(PDO::FETCH_ASSOC);
				$pass = $result[0]['pass'];
				if(password_verify($_POST['password'], $pass)){
					$_SESSION['login'] = $_POST['password'];
				}else{
					session_destroy();
					header('Location: ./kari.php');
				}
				
			}else if(!isset($_SESSION['login'])){
				echo "	
						<form method='post' action='./kari.php'>
							<input type='text' name='password' value='' placeholder='pass'>
							<input type='submit' value='login'>
						</form>
						<script src=https://www.google.com/recaptcha/enterprise.js?render=6Ldmb2QjAAAAAJHu90WpRRinWbHfqUCd7Z6Czb_o></script>
						<script>
						grecaptcha.enterprise.ready(function() {
						    grecaptcha.enterprise.execute('6Ldmb2QjAAAAAJHu90WpRRinWbHfqUCd7Z6Czb_o', {action: 'login'}).then(function(token) {
						       ...
						    });
						});
						</script>
					"; 
				exit;

			}else{

				$_POST = array();
				$_SESSION = array();
				session_destroy(); 
				header('Location: ./kari.php');
			}

			//	パスワード生成
			//------------------------------------
			if(isset($_POST['generatePass'])){
				$hash_pass = password_hash($_POST['generatePass'], PASSWORD_DEFAULT);
			}else{
				$hash_pass = 'なし';
			}

			require_once('./emptyRoomNumber.php');
		?>
		<form id="xx-1"  method="post"  enctype="multipart/form-data">
			<h5>投稿フォーム</h5>
			<input type="hidden" name="chid" value="<?php echo $chid;?>">
			
			<input id="xx-2" class="yy-1" type="text" name="roomname" placeholder="部屋名">
			
			<input id="xx-10" class="yy-1" type="text" name="hypertex" placeholder="引用">
			
			<select id="xx3" class="yy-1" name="category">
				<option selected>----</option>
				<?php
					require_once('./healthArr.php');
					$sample = returnCategory('arr');
					for($i = 0; $i < count($sample); $i++){
						echo"
							<option value='{$sample[$i]}'>{$sample[$i]}</option>
						";
					}

				?>
			</select>

			<textarea id="xx-4" class="yy-1" name="consolationText" placeholder="相談事" cols="40" raws="50"></textarea>
			
			<div id="img" class="yy-1">
				<canvas id="canvas" width="400px" height="300px"></canvas>
				<img id="room_img" src="./icon/photo.jpg"> 
				<label id="read_label" class="<?php if($deviceTmp == 'pc'){echo 'btn btn-success';}else if($deviceTmp == 'phone'){echo 'phone-img-btn';}?> ">
					画像の読み込み
					<input id='file_read' type="file" value="./photo.jpg" name="roomimg" accept="image/*">
				</label>
			</div>

			<input id="xx-5" class="yy-1" type="number" max="10000000" min="10" step="20" name="commentNum" placeholder="コメント数">
			
			<input id="xx-6" class="yy-1" type="number" max="10000000" min="10" step="20" name="iine" placeholder="いいね数">
			
			<input id='csvup' class="yy-1" type="file" name="filelog" accept=".csv,.txt">
			

			<div id="kari_send">
				<input type="button" onclick="form_check.exe(this);" value="送る">
			</div>
		</form>
		<div id="generateBox">
			<h5>パスワード生成フォーム</h5>
			<form id="pass_setter" method="post" action="./kari.php">
				<input id="pass" type="text" value="" name="generatePass">
				<input type="submit" value="送る">
			</form>
			<div id="result"><?php echo $hash_pass; ?></div>
			<a id="storageLink" href="./storage.php" style="width:100px;">storage</a>
		</div>
	</body>
	<script src="./js/file_read.js"></script>
	<script src="./js/resize.js" type="text/javascript"></script>
	<script type="text/javascript" src="./js/subajax.js"></script>
	<script type="text/javascript">
		window.onload = ()=>{
			img_preview.preview(
				document.getElementById('file_read'),
				document.getElementById('room_img'),
				document.getElementById('canvas'),
				);
		}
		function getChid(xhr){
			if(xhr.responseText == 'fail'){
				alert('chidを取得できませんでした。')
			}else{
				var data = JSON.parse(xhr.responseText)[0]['max(chid)'];
				document.getElementsByName('chid')[0].value = (Number(data) + 1);	
			}
		}
		//フォームの送信
		function init(){
			var self = form_check;
			document.getElementsByName('chid')[0].value = '';
			document.getElementsByName('roomname')[0].value = '';
			document.getElementsByName('hypertex')[0].value = '';
			document.getElementsByName('consolationText')[0].value = '';
			document.getElementsByName('category')[0].value = '';
			document.getElementsByName('roomimg')[0].setAttribute('src', '');
			document.getElementsByName('commentNum')[0].value = '';
			document.getElementsByName('iine')[0].value = '';
			document.getElementsByName('filelog')[0].value = '';
			document.getElementById('file_read').files[0] = {};
			xhrObject.ajax("./ajaxEmptyNumber.php", getChid, 'POST', undefined, 'text');
		}
		function makeroomForm(obj){
			var data = new FormData();
			var file = document.getElementById('file_read').files[0];
			var canvas = document.getElementById('canvas');
			var IsFile = imageUpload(canvas, [file.name, file.type]);

			var fr = new FileReader();
			const file2 = obj.filelog.files[0];
			
			data.append('chid', obj.chid.value);
			data.append('roomname', obj.roomname.value);
			data.append('hypertex',　obj.hypertex.value);
			data.append('consolation', obj.consolation.value);
			data.append('category', obj.category.value);
			data.append('file', IsFile);
			data.append('commentNum', obj.commentNum.value);
			data.append('iine', obj.iine.value);
			data.append('filelog', file2);
			
			xhrObject.ajax("./kari_setter.php", init, 'POST', data, 'file'); 
			
		}

		var form_check = {
			chid: document.getElementsByName('chid')[0],
			roomname: document.getElementsByName('roomname')[0],
			hypertex: document.getElementsByName('hypertex')[0],
			consolation: document.getElementsByName('consolationText')[0],
			category: document.getElementsByName('category')[0],
			file: document.getElementsByName('roomimg')[0], 
			commentNum : document.getElementsByName('commentNum')[0],
			iine: document.getElementsByName('iine')[0],
			filelog: document.getElementsByName('filelog')[0],
			exe: (e)=>{
				var self = form_check;
				try{
					if(self.chid.value == ''){throw new Error('chidがありません。');}
					if(self.roomname.value == ''){throw new Error('部屋名が記入されていません。');}
					if(self.hypertex.value == ''){throw new Error('引用が選択されていません。');}
					if(self.consolation.value == ''){throw new Error('相談事がありません。');}
					if(self.category.value == '----'){throw new Error('カテゴリー欄が記入されていません。');}
					if(self.file.value == ''){throw new Error('画像ファイルが選択されていません。');}
					if(self.commentNum.value == ''){throw new Error('コメント数がありません。');}
					if(self.iine.value == ''){throw new Error('いいね記入されていません。');}
					if(self.filelog.value == 'カテゴリーを選んでね'){throw new Error('csvが選択されていません。');}
					makeroomForm(self);
				}catch(e){
					alert(e)
					//return false;
				}
			}
		}
	</script>
</html>
					
				
			