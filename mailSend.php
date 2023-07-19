<?php
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");

  $to = 'hameln@notnormal.site';
  $title = $_POST['subject'];
  $message = $_POST['name'].'さんからのメッセージです。'.$_POST['message'];
  $headers = "From: ".$to;
  $headers .= "rn";
  $headers .= "Content-type: text/html; charset=UTF-8";

  if(mb_send_mail($to, $title, $message, $headers))
  {
    header('Location: ./contact.php');
    exit;
  }
  else
  {
    echo "メール送信失敗です";
    exit;
  }
 ?>