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
		<a href="manual_mashing.php"><img id="mashing" src="img/mashing_menu.jpg"></a>
		<a href="manual_fermentation.php"><img id="fermentation" src="img/fermentation_menu.jpg"></a>
		<br><br>
		<img id="options" src="img/options.png">
		<img id="share" src="img/share.png" style="display: none;">
		<img id="help" src="img/help.png" style="display: none;">
	</div>
	
	

	<br>
	<br>
	
</div>


</body>

</html>