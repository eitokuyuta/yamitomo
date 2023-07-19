/*-----------------------------------------------------
* 	2022-5-5
*	デバイスのチェックをします。Display set Filerと併せて使用します・
*				eitoku yuta
-----------------------------------------------------*/

function device() {
  var ua = navigator.userAgent;
  if(ua.indexOf('iPhone') > 0 || ua.indexOf('iPod') > 0 || ua.indexOf('Android') > 0 && ua.indexOf('Mobile') > 0){
       //'mobile';
       return false;
  }else if(ua.indexOf('iPad') > 0 || ua.indexOf('Android') > 0){
       //'tablet';
       return false;
  }else{
  		//PC
       return true;
  }
}