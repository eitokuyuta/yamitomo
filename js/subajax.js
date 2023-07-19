
		// xmlHttpObjectの作成
		function createXMLHttpRequest(){
			var xmlHttpObject = null;
			if(window.XMLHttpRequest){
				xmlHttpObject = new XMLHttpRequest();
			}else if(window.ActiveXObject){
				try{
					//xmlHttpObject = new ActiveXObject("Msxml2.XMLHTTP");
				}catch(e){
					try{
						xmlHttpObject = new ActiveXObject("Microsoft.XMLHTTP");
					}catch(e){
						return null;
					}
				}
			}
			return xmlHttpObject;
		}

		var xhrObject = {
			ajax: (url, func, method, data, type)=>{
				var xhr = new createXMLHttpRequest();
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
				xhr.open(method, url);
				switch(type){
					case "text":
						xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
						break;
					case "file":
					 	break;
					default:
						conosole.log('適切なcontent-typeではありません');
						break;	

				}
				xhr.send( data );
			}

		};
		