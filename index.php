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
		<link rel="stylesheet" type="text/css" href="./css/reset.css">
		<link rel="stylesheet" type="text/css" href="./css/index.css">
		<link rel="stylesheet" type="text/css" href="./css/object.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	</head>
	<body style="position:relative;">

	<?php
		require_once('./databaseInit.php');
		if(isset($_SESSION['userid'])){
			$data = new databaseInit();
			$result = $data->db->prepare("DELETE FROM userid WHERE id = :id");
			$result->bindValue(':id', $_SESSION['userid']);
			$result->execute();
		}
		session_destroy();

	?>
		<div id="login_subject">
			<img id="title-img" src="./img/yamitomo.png">
			<div id="sub">病んでる友達つくっちゃお！！</div>
		</div>
		<div id="explain">
			<div id="index_explain">#1 病んでる同士で励ましあえたら(・∀・)ｲｲﾈ!!</div>
			<div class="explain-body">
				こんにちは、管理人です。<br>
				病んでたら、いろいろ大変です。周りの人に相談しづらいし、そもそも分かってもらえない。
				だったら、病んで同士で友達作っちゃえば、分かってもらえるんじゃない？と思ってこのサイトを作りました。<br>
				注意!! <code>誹謗中傷、あらし、いやがらせ行為、ポルノコンテンツの投稿</code>はおやめください。		
			</div>
		</div>

		
		</div>
		<form id='register' action="./loginAbsorber.php" method="post">
			<label id="box">
				<label for="monkey" class="icon">
					<img class="iconImg" src="./icon/monkey.jpg">
				</label>
				<input id="monkey" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="monkey">

				<label for="kaba" class="icon">
					<img class="iconImg" src="./icon/kaba.jpg">
				</label>
				<input id="kaba" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="kaba">

				<label for="seal" class="icon">
					<img class="iconImg" src="./icon/seal.jpg">
				</label>
				<input id="seal" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="seal">

				<label for="koala" class="icon">
					<img class="iconImg" src="./icon/koala.jpg">
				</label>
				<input id="koala" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="koala">

				<label for="panda" class="icon">
					<img class="iconImg" src="./icon/panda.jpg">
				</label>
				<input id="panda" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="panda">

				<label for="Frankenstein" class="icon">
					<img class="iconImg" src="./icon/Frankenstein.jpg">
				</label>
				<input id="Frankenstein" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="Frankenstein">

				<label for="bull" class="icon">
					<img class="iconImg" src="./icon/bull.jpg">
				</label>
				<input id="bull" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="bull">

				<label for="bulldog" class="icon">
					<img class="iconImg" src="./icon/bulldog.jpg">
				</label>
				<input id="bulldog" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="bulldog">

				<label for="Lion" class="icon">
					<img class="iconImg" src="./icon/Lion.jpg">
				</label>
				<input id="Lion" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="Lion">

				<label for="cow" class="icon">
					<img class="iconImg" src="./icon/cow.jpg">
				</label>
				<input id="cow" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="cow">

				<label for="bear" class="icon">
					<img class="iconImg" src="./icon/bear.jpg">
				</label>
				<input id="bear" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="bear">

				<label for="hawk" class="icon">
					<img class="iconImg" src="./icon/hawk.jpg">
				</label>
				<input id="hawk" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="hawk">

				<label for="raccoon" class="icon">
					<img class="iconImg" src="./icon/raccoon.jpg">
				</label>
				<input id="raccoon" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="raccoon">

				<label for="cat" class="icon">
					<img class="iconImg" src="./icon/cat.jpg">
				</label>
				<input id="cat" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="cat">

				<label for="wolf" class="icon">
					<img class="iconImg" src="./icon/wolf.jpg">
				</label>
				<input id="wolf" type="radio" class="iconRadio" onclick="icon.iconBorder(this)" name="iconList" value="wolf">
			</label>
			<label id="box2">
				<input type="text" name="username" id="username" placeholder="名前を入れてね">
				<input type="submit" value="入室" class="btn btn-danger" onClick="return check();">
			</label>
		</form>
	</body>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script>
		var icon = {
			iconPos: '',
			iconBorder:(e)=>{
				var self = icon;
				var img = e.previousElementSibling.children[0];
				if(self.iconPos != ''){
					self.iconPos.style.border = '';	
				}
				img.style.border = 'solid 3px black';
				self.iconPos = img;
			}
		}
		function check(){
			var icon = document.getElementsByName('iconList')[0];
			var username = document.getElementsByName('username')[0];
			if(icon.value == ''){
				alert('アイコンを選択してください');
				return false;
			}
			if(username.value == ''){
				alert('アイコンを選択してください');
				return false;
			}
			if(icon.value != '' && username.value != ''){
				return true;
			}
		}
	</script>
</html>
