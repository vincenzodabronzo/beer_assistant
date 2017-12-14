<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">

     <script type="text/javascript" src="js/jquery.min.js"></script>
     <script type="text/javascript" src="js/jquery-ui.min.js"></script>
     
	
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link href="css/chart_menu.css" rel="stylesheet" type="text/css">	 
	 <link href="css/jquery-ui.css" rel="stylesheet" type="text/css">
	 
	     <script type="text/javascript">

		$(document).ready(function() {
			$( "#tabs" ).tabs();
			
	});
</script>
	
	<title>Chart menu</title>
</head>

<body>

<div id="header">Chart menu</div>
<br>

<div id="maincontainer">

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Mashing</a></li>
    <li><a href="#tabs-2">Fermentation</a></li>
  </ul>
  <div id="tabs-1">
  	<p>Mashing graphs available:</p> 
  	<div>
      	<ul id="mashing_list" class="list">
          <li class="list_item">1</li>
          <li class="list_item">2</li>
          <li class="list_item">3</li>
          <li class="list_item">4</li>
          <li class="list_item">5</li>
          <li class="list_item">6</li>
    	</ul>
	</div>
	
	<div style="clear:both; float:none"></div>
 
  	
  </div>
  <div id="tabs-2">
	<p>Fermentation graphs available:</p>  
	
	  	<div>
      	<ul id="fermentation_list" class="list">
			<?php include 'lib/load_graph.php?step=fermentation'; ?>
    	</ul>
    	
	</div>
  </div>

</div>

<br><br>
<div id="footer"><a href="https://github.com/vincenzodabronzo/beer_assistant" target="_blank"><img id="logo" src="img/logo.png"></a></div>
<br><br>		

	<div class="data" id="show_data" style="display: none;" >  </div>           

</div>

</body>

</html>