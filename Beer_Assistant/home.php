<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">

     <script type="text/javascript" src="js/jquery.min.js"></script>
	
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link href="css/home.css" rel="stylesheet" type="text/css">
	 
	     <script type="text/javascript">

    	//  microsec interval
    	var t = 2000;

		$(document).ready(function() {

			function manageTab(name) {
				$('#tab_'+name).toggle( "fade" );
				$('html, body').animate({ scrollTop: $('#tab_'+name).offset().top }, 'slow');
			}

			document.getElementById("options").addEventListener("click", function(){
			    manageTab("options");
			});
			
			
	});
</script>
	
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
	
	     <div id="tab_options">
      	<div id="m_select" class="m_select">
       			<label for="max_select_group">Max</label>
        		<select id="max_select_group">
        		</select>
        		&nbsp;&nbsp;&nbsp;
        		<label for="min_select_group">Min</label>
        		<select id="min_select_group">
        		</select>
        		<button id="update_temp" data-role="button">Update Max and Min</button>
	    </div>
	
</div>


</body>

</html>