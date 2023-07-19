/*------------------------------------------
*	onkeypressでenterキーを押下時にイベントを
*	発火させる。
*	@param
*	func callback関数
*	code keycode
------------------------------------------*/
function enterPress(event, func){
	var keyCode = window.event.keyCode;
	//enter==13
	if(keyCode == 13){
		func();
	}
}
