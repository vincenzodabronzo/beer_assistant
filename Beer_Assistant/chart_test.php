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

			var ctx = $("#myChart");
			
			data: [{
		        x: 10,
		        y: 20
		    }, {
		        x: 15,
		        y: 10
		    }]
		    
			new Chart(ctx, {
			    type: 'line',
			    data: data,
			    options: {
			        elements: {
			            line: {
			                tension: 0, // disables bezier curves
			            }
			        }
			    }
			});

			
			
	});
</script>
	
	<title>Chart</title>
</head>

<body>

	<canvas id="myChart" width="400" height="400"></canvas>


	
</body>

</html>