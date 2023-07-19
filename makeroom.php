<?php
	session_start();
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
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!--bootstrap-->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
		<!--bootstrap end-->
		<link rel="stylesheet" type="text/css" href="./css/object.css">
		<link rel="stylesheet" type="text/css" href="./css/makeroom.css">
		
	</head>
	<body>
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

			if( !isset($_SESSION['userid'])){
				header('Location: ./index.php');
				exit;
			}
			
		?>
		<div id="middle">
			<form id="main" action="./insertRoom.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="username" value="<?php echo $_SESSION['userid']; ?>">
				<div id="conslation_box">
					<div id="img">
						<canvas id="canvas" width="400px" height="300px"></canvas>
						<img id="room_img" src="./icon/photo.jpg"> 
						<label id="read_label" class="<?php if($deviceTmp == 'pc'){echo 'btn btn-success';}else if($deviceTmp == 'phone'){echo 'phone-img-btn';}?> ">
							画像の読み込み
							<input id='file_read' type="file" value="./photo.jpg" name="file_upload" accept="image/*">
						</label>
					</div>
					<div id="conslation">
						<div class="input-group mb-3">
							<span class="input-group-text" id="basic-addon1">タイトル:</span>
							<input type="text" name="roomName" class="form-control" aria-label="title" aria-describedby="basic-addon1">
						</div>
						<div class="form-floating">
						    <textarea class="form-control" name="consolationText" placeholder="相談内容" id="floatingTextarea2"></textarea>
						    <label for="floatingTextarea2">相談内容</label>
						</div>
						<!--残り文字数を表示する-->
						<div class="form-floating">
							<select class="form-select" id="floatingSelect"  name="category" aria-label="Floating label select example"> 
								<option selected>メニューを開く</option>
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
						    <label for="floatingSelect">カテゴリーを選んでね</label>
						</div>

						<div id="button_left_label">
							<input type="button" class="btn btn-primary" onclick="return form_check.exe();" value="作る">
						</div>
					</div>
				</div>
			</form>
			<?php
				if( $deviceTmp == 'pc'){
					echo "
						<div id='side_bar' class='pc'>
							<div class='ads'> 
								<!-- admax -->
								<script src='https://adm.shinobi.jp/s/46d3c98e92e34309700f37291c7aeb07'></script>
								<!-- admax --> 
							</div>
							<div class='ads'>
								<!-- admax -->
								<script src='https://adm.shinobi.jp/s/b6b2f443cd1254605bdefc30a6bd5def'></script>
								<!-- admax -->
							</div>
							<div class='ads'>
								<!-- admax -->
								<script src='https://adm.shinobi.jp/s/a73d9c1836dd16ea06b2b578b4155385'></script>
								<!-- admax -->
							</div>
						</div>
					";
				}
			?>
		</div>			
	</body>
	<script src="./js/file_read.js"></script>
	<script type="text/javascript" src="./js/subajax.js"></script>
	<script src="./js/resize.js" type="text/javascript"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

	<script src="./js/DeviceCheck.js" type="text/javascript"></script>
	<script type="text/javascript">
		window.onload = ()=>{
			img_preview.preview(
				document.getElementById('file_read'),
				document.getElementById('room_img'),
				document.getElementById('canvas'),
				);
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

        function test(xhr){
        	var response = xhr.responseText;
        	location.href = './log.php?id='+String(response);
        }
        //フォームの送信
		function makeroomForm(obj){
			var data = new FormData();
			var file = document.getElementById('file_read').files[0];
			if(file != undefined){
				var canvas = document.getElementById('canvas');
				var IsFile = imageUpload(canvas, [file.name, file.type]);
				data.append('file_upload', IsFile);
			}
			
			data.append('roomName', obj.roomName.value);
			data.append('consolationText', obj.consolation.value);
			data.append('category',　obj.category.value);
			
			xhrObject.ajax("./insertRoom.php", test, 'POST', data, 'file'); 
		}

		var form_check = {
			roomName: document.getElementsByName('roomName')[0],
			consolation: document.getElementsByName('consolationText')[0],
			category: document.getElementsByName('category')[0],
			file: document.getElementsByName('file_upload')[0], 
			exe: ()=>{
				var self = form_check;
				try{
					if(self.roomName.value == ''){throw new Error('部屋名がありません。');}
					if(self.consolation.value == ''){throw new Error('相談の欄が記入されていません。');}
					if(self.category.value == 'カテゴリーを選んでね'){throw new Error('カテゴリーが選択されていません。');}
					makeroomForm(self);
				}catch(e){
					alert(e)
					//return false;
				}
			}
		}
	</script>
</html>
