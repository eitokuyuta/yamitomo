<?php
/*----------------------------------------------------------------------
*	snsへ共有するurlを作り送信するクラス
*	->snsView.phpで使用。
*												yuta eitoku
----------------------------------------------------------------------*/
class share{
	function __construct($text='', $url='', $hashtags=''){
		$this->text = $text;
		$this->url = $url;
		$this->hashtags = $hashtags;									//カテゴリー

	}
	function shareTwitter(){
		$aryTwitter = [];
		$aryTwitter['text'] = $this->text; 					//シェアしたい内容
		$aryTwitter['url'] = $this->url; 					//シェアしたいURL
		$aryTwitter['hashtags'] = $this->hashtags; 			//ハッシュタグ
		$twitter_url = 'https://twitter.com/share?';
		$twitter_url .= implode('&', array_map(function($key, $value){return $key.'='.$value;}, array_keys($aryTwitter), array_values($aryTwitter)));
		return $twitter_url;
       

	}
	function sharefacebook(){
		$aryFacebook = [];
		$aryFacebook['u'] = $this->url; 					//シェアしたいURL
		$Facebook_url = 'http://www.facebook.com/share.php?';
 		$Facebook_url .= implode('&', array_map(function($key, $value){return $key.'='.$value;}, array_keys($aryFacebook), array_values($aryFacebook)));
 		return $Facebook_url;

	}
	function shareLine(){
		$aryLine = [];
		$aryLine['url'] = $this->url; 						//シェアしたいURL
		$aryLine['text'] = $this->text;  					//シェアしたい内容
		$LineLink = '//social-plugins.line.me/lineit/share?';
		$LineLink .= implode('&', array_map(function($key, $value){return $key.'='.$value;}, array_keys($aryLine), array_values($aryLine)));
		return $LineLink;
	}
}
?>