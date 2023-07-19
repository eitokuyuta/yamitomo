var xhrObject = {
	url: "",
	ajax: (func, data)=>{
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function () {
	      	var READYSTATE_COMPLETED = 4;
	      	var HTTP_STATUS_OK = 200;

	      	if (this.readyState == READYSTATE_COMPLETED && this.status == HTTP_STATUS_OK) {
	        // レスポンスの表示
	        	
	        	if(func != undefined){
	        		func(this);
	        	}else{
	        		console.log(this.responseText);
	        	}

	      	}
	    }
		xhr.open('POST', xhrObject.url);
		xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
		xhr.send( data );
	}

};