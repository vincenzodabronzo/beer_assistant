<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">

     <script type="text/javascript" src="js/jquery.min.js"></script>
	
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link href="css/home.css" rel="stylesheet" type="text/css">
	
	<title>Main menu</title>
</head>

<body>

<div id="maincontainer">


	<div id="command">
		<br>
    	<?php 
    	   include_once 'vocabulary/en/en_general.php';
    	   echo '<b>'.$mainmenu_quote[array_rand($mainmenu_quote)].'</b>';
    	?>
		<br>
	<br><br>
		<img id="mashing" src="img/mashing_menu.jpg">
		<img id="frementation" src="img/fermentation_menu.jpg">
		<br><br>
		<img id="options" src="img/options.png">
		<img id="share" src="img/share.png">
		<img id="help" src="img/help.png">
	</div>
	
	

	<br>
	<br>
	
</div>


</body>

</html>