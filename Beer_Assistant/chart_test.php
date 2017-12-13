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

			new Chart(document.getElementById("line-chart"), {
				  type: 'line',
				  data: {
				    labels: [1500,1600,1700,1750,1800,1850,1900,1950,1999,2050],
				    datasets: [{ 
				        data: [86,114,106,106,107,111,133,221,783,2478],
				        label: "Africa",
				        borderColor: "#3e95cd",
				        fill: true
				      }, { 
				        data: [282,350,411,502,635,809,947,1402,3700,5267],
				        label: "Asia",
				        borderColor: "#8e5ea2",
				        fill: true
				      }, { 
				        data: [168,170,178,190,203,276,408,547,675,734],
				        label: "Europe",
				        borderColor: "#3cba9f",
				        fill: true
				      }, { 
				        data: [40,20,10,16,24,38,74,167,508,784],
				        label: "Latin America",
				        borderColor: "#e8c3b9",
				        fill: true
				      }, { 
				        data: [6,3,2,2,7,26,82,172,312,433],
				        label: "North America",
				        borderColor: "#c45850",
				        fill: true
				      }
				    ]
				  },
				  options: {
				    title: {
				      display: true,
				      text: 'World population per region (in millions)'
				    }
				  }
				});
			
			
	});
</script>
	
	<title>Chart</title>
</head>

<body>

	<canvas id="line-chart" width="800" height="450"></canvas>

</body>

</html>