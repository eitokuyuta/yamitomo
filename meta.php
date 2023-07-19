<?php
	/*----------------------------------------------------------------------
	*	seo.phpから先に読み込まないとならない。
	*												yuta eitoku
	----------------------------------------------------------------------*/
	echo 
	"
		<!-- site info -->
	    <title>".$title."</title>
	    <meta name='description' content='".$description."'>
	    <meta name='keywords' content='".$keyword."'>
	    <!-- site info end -->

	    <!-- ogp -->
	    <meta property='og:title' content='".$title."'>
	    <meta property='og:type' content='".$type."'>
	    <meta property='og:image' content='".$image."'>
	    <meta property='og:url' content='".$url."'>
	    <meta property='og:description' content='".$description."'>
	    <meta property='og:site_name' content='".$site_name."'>
	    <!-- ogp end -->

	    <!-- twitter -->
	    <meta name='twitter:card' content='".$tw_card."'>
	    <meta name='twitter:site' content='".$tw_site."'>
	    <meta name='twitter:title' content='".$title."'>
	    <meta name='twitter:description' content='".$description."'>
	    <meta name='twitter:image' content='".$image."'>
    	<!-- twitter end -->
    	<link rel='shortcut icon' href='./yami.ico'>
    ";
?>