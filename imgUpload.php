<?php 
class imgUpload{
	function __construct($dir){
		$this->dir = $dir;
	}
	function imgMove($files){
		$moveUrl = $this->dir.'/'.basename($files['name']);
		$moveUrl = str_replace(" ", "", $moveUrl);
		move_uploaded_file($files['tmp_name'], $moveUrl);
		return $moveUrl;
	}
}

?>