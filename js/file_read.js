
function ratio(width, height, el){
	var w, h;

	if(width >= height){
		w = el.width;
    	h = Math.round(height * Number((w / width).toFixed(5)));
	}else if(width < height){
		h = el.height;
     	w = Math.round(width * Number((h / height).toFixed(5)));
	}
    return [w, h];
}

//----------------------------------------------------------------
//	@param	a -> input file 
//	@param	b -> img tag
//	@param	C -> canvas
//----------------------------------------------------------------
var img_preview = {
	file_read: 0,
	load_img: 0, 
	canvas: 0,
	default: 0,
	preview: (a, b, c, d = 'auto')=>{
		var self = img_preview;
		self.file_read = a;
		self.load_img = b;
		self.canvas = c;
		self.default = d;
		self.file_read.addEventListener('change', (e)=>{
			var fr = new FileReader();
			let input = e.target;
			const file = input.files[0];
			fr.onload = ()=>{
				self.load_img.setAttribute('src', fr.result);
				//imgタグに表示した画像をimageオブジェクトとして取得
	            var image = new Image();
	            image.src = self.load_img.src;
	            image.onload = ()=>{
	            	if(self.default == 'auto'){
		            	if(image.width >= image.height){
		            		self.default = image.width;
			            }else{
			            	self.default = image.height;
			            }
		            }
		            

		            //縦横比を維持した縮小サイズを取得
		            var imgWH = ratio(image.width, image.height, self.load_img);
		            self.load_img.style.width = imgWH[0];
		            self.load_img.style.height = imgWH[1];
		            var canvasWH = ratio(image.width, image.height, {'width': self.default, 'height': self.default});
		                        
		            //canvasに描画
		            var ctx = self.canvas.getContext('2d');
		            self.canvas.width = canvasWH[0];
		            self.canvas.height = canvasWH[1];
		            ctx.drawImage(image, 0, 0, canvasWH[0], canvasWH[1]);      
	            }
	            
	            
			};
			fr.readAsDataURL(file);
			
		})	
	}
}
