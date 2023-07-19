<?php 

	/*------------------------------------------------------------
	*	@link	bootstrap css
				<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	*			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
	*			<link href="https://fonts.googleapis.com/earlyaccess/kokoro.css" rel="stylesheet">
	*	@link	bootstrap script
				<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	*
	*
	*
	------------------------------------------------------------*/
	require_once('./healthArr.php'); 
	echo "
	<div id='phone-form'>
		<button type='button' class='btn btn-dark' onclick='fadeout(event)'><i class='bi bi-search'></i></button>
		<div class='select-box'>
			<form id='category-phone' method='get' action='" . $_SERVER['PHP_SELF'] . "'>
				<select class='form-select' aria-label='Default select example' name='parentCategory' onchange='setSmallCategory(event)'>
					<option selected>大カテゴリー</option>
					";
						foreach( $keyArr as $value){
							echo"
								<option value='{$value}'>{$value}</option>
							";
						}
		echo "
				</select>
				<select class='form-select' aria-label='Default select example' name='indexCategory'>
					<option selected>小カテゴリー</option>
				</select>
				<input type='submit' class='btn btn-success' value='送る'>
			</form>
			<form id='serch-phone' class='input-group mb-3' method='get' action='" . $_SERVER['PHP_SELF'] . "'>
				<input type='text' class='form-control' placeholder='キーワードの入力' aria-label='Recipient's username' aria-describedby='button-addon2' name='serchWord'>
				<button class='btn btn-outline-secondary' type='submit' id='button-addon2'>送る</button>
			</form>
		</div>
	</div>
	<script type='text/javascript' src='./js/healthArr.js'></script>
	<script type='text/javascript'>
		//!phoneCate
		function setSmallCategory(arg){
			
			var sCategory = getSmallCategory(arg.currentTarget.value);
			var indexCategory = document.getElementsByName('indexCategory')[0];
			console.log(indexCategory.value);
			if(indexCategory.value){
				for(var i = (indexCategory.length - 1); 1 < indexCategory.length; i--){
					indexCategory.removeChild(indexCategory[i]);
				}
			}
			for(var i = 0; i < sCategory.length; i++){
				var option = document.createElement('option');
				option.setAttribute('value', sCategory[i]);
				option.innerHTML = sCategory[i];

				indexCategory.appendChild(option);
			}
		}
		var serchFlag = 0;
		function fadeout(e){
			var self = e.currentTarget;
			var selectBox = self.nextElementSibling;
			if( serchFlag == 0 ){
				selectBox.style.display = 'flex';
				serchFlag = 1;
			}else if( serchFlag == 1 ){
				selectBox.style.display = 'none';
				serchFlag = 0;
			}
			
		}
	</script>
	";
?>