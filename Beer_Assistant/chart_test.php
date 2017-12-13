<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">

     <script type="text/javascript" src="js/jquery.min.js"></script>
     <script type="text/javascript" src="js/chart.bundle.min.js"></script>
	
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link href="css/home.css" rel="stylesheet" type="text/css">	 
	 
	     <script type="text/javascript">

		$(document).ready(function() {

			new Chart(document.getElementById("line-chart"),
					{"type":"line","data":{"labels":["January","February","March","April","May","June","July"],
						"datasets":[{"label":"My First Dataset","data":[65.1,59.4,80.34,81,56,55,40],"fill":false,"borderColor":"rgb(75, 192, 192)","lineTension":0.1}]},
						"options":{}});
			
			
	});
</script>
	
	<title>Chart</title>
</head>

<body>

	<canvas id="line-chart" width="800" height="450"></canvas>

</body>

</html>