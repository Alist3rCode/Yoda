<!-- 

<html>
<head>
<meta charset="utf-8">
</head>
<div id="div1"></div>
<input type="checkbox" id="stop">Stop</button>
</html>
<script  src="https://code.jquery.com/jquery-3.2.1.js"  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="   crossorigin="anonymous"></script>
<script>
	function fileFnc(){

		$("#div1").load("readFile.php");

	}

	if(document.getElementById('stop').checked = false){
		
    	var intervalID = window.setInterval(fileFnc, 500);
    	
	}
	

    
</script> -->

<!DOCTYPE html>
<html>
<head>
<title>setInterval/clearInterval example</title>
<script  src="https://code.jquery.com/jquery-3.2.1.js"  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="   crossorigin="anonymous"></script>
<script>
var intervalID;

function start() {
  intervalID = setInterval(fileFnc, 500);

}

function fileFnc(){

		$("#div1").load("readFile.php");
		console.dir(document.getElementById('div1'))
	}

function stop() {
  clearInterval(intervalID);
}

function copy() {
  /* Get the text field */
  var copyText = document.getElementById("div1");

  /* Select the text field */
  copyText.select();

  /* Copy the text inside the text field */
  document.execCommand("Copy");

  /* Alert the copied text */
  alert("Copied the text: " + copyText.innerHTML);
}

</script>
</head>

<body onload="start();">
<textarea rows="10" cols="50" id="div1">
	
</textarea>

</div>
<button onclick="stop();">Stop</button>
<button onclick="start();">Start</button>
<button onclick="copy();">Copy</button>
</body>
</html>


