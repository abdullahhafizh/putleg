<p>Focus this tab and press <kbd>CTRL</kbd> + <kbd>V</kbd>. The image on your clipboard will be rendered on the canvas !</p>
<canvas style="border:1px solid grey;" id="mycanvas"></canvas>
<script type="text/javascript">
	function retrieveImageFromClipboardAsBlob(pasteEvent, callback){
		if(pasteEvent.clipboardData == false){
			if(typeof(callback) == "function"){
				callback(undefined);
			}
		};

		var items = pasteEvent.clipboardData.items;

		if(items == undefined){
			if(typeof(callback) == "function"){
				callback(undefined);
			}
		};

		for (var i = 0; i < items.length; i++) {       
			if (items[i].type.indexOf("image") == -1) continue;        
			var blob = items[i].getAsFile();

			if(typeof(callback) == "function"){
				callback(blob);
			}
		}
	}

	window.addEventListener("paste", function(e){
		
		retrieveImageFromClipboardAsBlob(e, function(imageBlob){        
			if(imageBlob){
				var canvas = document.getElementById("mycanvas");
				var ctx = canvas.getContext('2d');
				
				var img = new Image();
				
				img.onload = function(){                
					canvas.width = this.width;
					canvas.height = this.height;
					
					ctx.drawImage(img, 0, 0);
				};
				
				var URLObj = window.URL || window.webkitURL;
				
				img.src = URLObj.createObjectURL(imageBlob);
			}
		});
	}, false);
</script>