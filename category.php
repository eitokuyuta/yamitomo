<?php
	/*------------------------------------------------------
	*	検索用のフォームを生成するライブラリ
	*
	---------------------------------------------------------*/
	require_once('./healthArr.php');
	$sample = returnCategory('object');
	$flag = '';
	/*
<form method='get' class='input-group mb-3' action='". $_SERVER['PHP_SELF'] . "'>
	<input type="text" class="form-control" placeholder="検索する" aria-label="検索する" name='serchWord' aria-describedby="button-addon2">
	<input type="submit" class="btn btn-outline-secondary" id="button-addon2" value="送信">
</form>
<form method='get' id='serchBox' action='". $_SERVER['PHP_SELF'] . "'>
	<input type='text' id='serchWord' placeholder='検索する' name='serchWord'>
	<input type='submit' value='検索'>
</form>

	*/
	$adcontent = "
				<div class='ad-content'>
					<form method='get' class='input-group mb-3' action='". $_SERVER['PHP_SELF'] . "'>
						<input type='text' class='form-control' placeholder='検索する' aria-label='検索する' name='serchWord' aria-describedby='button-addon2'>
						<input type='submit' class='btn btn-outline-secondary' value='送信'>
					</form>
					<div id='categoryIndex'>カテゴリー一覧</div>
			";
	$index = "";
	foreach($sample as $key => $value){
		for($i = 0; $i < count($value); $i++){
			if( $flag != $key){
				if($index != ''){$index .= '</div>';}
				$flag = $key;
				$index .= '<div class="index"><div class="keySubject" onclick="indexOpen.exe(this)">'.$key.'<div class="triangle">▲</div></div><div class="index-chi">'.$value[$i].'<a class="arrowWisp" href="'.$_SERVER['PHP_SELF'].'?indexCategory='.$value[$i].'"></a></div>';
			}else if($flag == $key){
				$index .= '<div class="index-chi">'.$value[$i].'<a class="arrowWisp" href="'.$_SERVER['PHP_SELF'].'?indexCategory='.$value[$i].'"></a></div>';
			}
		}
	}
	$adcontent = $adcontent.$index;
	echo $adcontent;

?>