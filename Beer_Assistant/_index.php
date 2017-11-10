<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<!--[if lt IE 9]>
        <script src="/js/html5shiv.js"></script>
    <![endif]-->
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
		$(document).ready(function() {
			setInterval(function() {
				$('#show').load('lib/data.php');
			}, 1000 );
		});
    </script>
    
    
    <!--  Plotting graph -->
    <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
	<script type="text/javascript" src="js/jquery.jqplot.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.jqplot.css" />
    
    <div id="chartdiv" style="height:400px; width:300px; "></div>
    
    <script type="text/javascript">
    $.jqplot('chartdiv',  [[[1, 2],[3,5.12],[5,13.1],[7,33.6],[9,85.9],[11,219.9]]],
	{ title:'Exponential Line',
  		axes:{yaxis:{min:-10, max:240}},
 		 series:[{color:'#5FAB78'}]
	});
    </script>
    
    
	<title>Mashing Temperature</title>
</head>

<body>
    <div id="show"></div>
    
    <?php
    
    
    ?>

</body>

</html>