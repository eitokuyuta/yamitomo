<!DOCTYPE html>
<html lang="jp">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-97624992-2"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-97624992-2');
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/earlyaccess/kokoro.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
        <!--bootstrap end-->
    <link rel="stylesheet" type="text/css" href="./css/object.css">
    <title>お問い合わせフォーム</title>
</head>
<style>
    body{
        box-sizing: border-box;
    }
</style>
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
    }else{
        require_once('./header.php');
    }
?>
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h3 class="mb-5 text-center">お問い合わせ</h3>
            <form method="post" action="./mailSend.php">
                <div class="mb-3">
                    <input type="text" class="form-control" name="name" placeholder="お名前" value="">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="subject" placeholder="件名" value="">
                </div>
                <div class="mb-4">
                    <textarea class="form-control" name="message" rows="10" placeholder="本文"></textarea>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">送信</button>
                </div>
                <script src=https://www.google.com/recaptcha/enterprise.js?render=6Ldmb2QjAAAAAJHu90WpRRinWbHfqUCd7Z6Czb_o></script>
                <script>
                grecaptcha.enterprise.ready(function() {
                    grecaptcha.enterprise.execute('6Ldmb2QjAAAAAJHu90WpRRinWbHfqUCd7Z6Czb_o', {action: 'login'}).then(function(token) {
                       ...
                    });
                });
                </script>
            </form>
        </div>
    </div>
</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
