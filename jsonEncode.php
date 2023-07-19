<?php
class jsonEncode{
	function __construct($date, $test=""){
		if(is_array($date) === true){

			$arr = array();
			if(isset($test)){
				$expected_encode = mb_detect_encoding($test,  ['UTF-8', 'ASCII', 'ISO-2022-JP', 'EUC-JP', 'SJIS'], true);
			}

			if("UTF-8" == $expected_encode){
				$this->jsonArr = $date;
			}
			else{
				foreach($date as $value){
					for($i = 0; $i < count($value); $i++){
						$value[$i] = mb_convert_encoding($value[$i], 'UTF-8', $expected_encode);
						//utf8_encode($value)だとうまくいかなかった。
					}
					array_push($arr, $value);
				}
				
				$this->jsonArr = $arr;
			}
		}else{
			$expected_encode = mb_detect_encoding($date,  ['UTF-8', 'ASCII', 'ISO-2022-JP', 'EUC-JP', 'SJIS'], true);

			if("UTF-8" == $expected_encode){
				$this->jsonArr = $date;
			}
			else{
				$date = mb_convert_encoding($date, 'UTF-8', $expected_encode);
				
				$this->jsonArr = $date;
			}
		}
	}
	function ajaxJson(){
		return json_encode($this->jsonArr,  JSON_UNESCAPED_UNICODE);
	}
	function encJson(){
		echo json_encode($this->jsonArr,  JSON_UNESCAPED_UNICODE);
		// JSON_UNESCAPED_UNICODE ユニコード表記させない
	}	
}
?>