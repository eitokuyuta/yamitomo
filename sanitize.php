<?php 
function sanitize($str) {
  $str = strip_tags(htmlspecialchars($str));
  return preg_replace(array('/[~;\'\"]/', '/-/'), '', $str);
}

?>