<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">

     <script type="text/javascript" src="js/jquery.min.js"></script>
     <script type="text/javascript" src="js/jquery-ui.min.js"></script>
     <script type="text/javascript" src="js/chart.bundle.min.js"></script>
     
	
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link href="css/chart_menu.css" rel="stylesheet" type="text/css">	 
	 <link href="css/jquery-ui.css" rel="stylesheet" type="text/css">
	 
	     <script type="text/javascript">

		$(document).ready(function() {
			$( "#tabs" ).tabs();

			
			var data = null;
			
			var options = {
					maintainAspectRatio: false,
					spanGaps: false /*,
					scales: {
			            yAxes: [{
			                ticks: {
			                	max: 100,
			                    min: 0,
			                    stepSize: 0.5
			                }
			            }]
			        } */
					
				};


			var chart = new Chart('chart', {
				type: 'line',
				data: data,
				options: options
			});


			

			
	});

		function getChartData(id, step) {
			$.ajax({
				url : "lib/load_graph_data.php?id="+id+"&step="+step,
				type : "GET",
				success : function(data){
					console.log(data);

					var timestamp = [];
					var temperature = [];

					
					for(var i in data) {
						timestamp.push( data[i].timestamp.slice(5) );
						temperature.push(data[i].temperature);
					}

					data = {
						labels: timestamp,
						datasets: [
							{
								label: "Temperature \xB0C",
								fill: false,
								lineTension: 0,
								backgroundColor: "rgba(59, 89, 152, 0.75)",
								borderColor: "rgba(59, 89, 152, 1)",
								pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
								pointHoverBorderColor: "rgba(59, 89, 152, 1)",
								data: temperature
							}
						]
					};

					var ctx = $("#chart");

					var LineGraph = new Chart(ctx, {
						type: 'line',
						data: data,
						options: {
								maintainAspectRatio: false,
								spanGaps: false /*,
								scales: {
						            yAxes: [{
						                ticks: {
						                	suggestedMax: 100,
						                    suggestedMin: 0
						                }
						            }]
						        } */
						}
					});

				},
				error : function(data) {
					alert("Unable to load chart data");
				}
			});
		}
		
</script>
	
	<title>Chart menu</title>
</head>

<body>

<div id="header">Chart menu</div>
<br>

<div id="maincontainer">

	<div id="chart_container">
		<canvas id="chart"></canvas>
	</div>
	
<br><br>

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Mashing</a></li>
    <li><a href="#tabs-2">Fermentation</a></li>
  </ul>
  <div id="tabs-1">
  	<p>Mashing graphs available:</p> 
  	<div>
      	<ul id="mashing_list" class="list">
			<?php 
			 $step = "mashing";
			 include 'lib/load_graph_list.php'; ?>
    	</ul>
	</div>
	
	<div style="clear:both; float:none"></div>
 
  	
  </div>
  <div id="tabs-2">
	<p>Fermentation graphs available:</p>  
	
	  	<div>
      	<ul id="fermentation_list" class="list">
			<?php 
			 $step = "fermentation";
			 include 'lib/load_graph_list.php'; ?>
    	</ul>
        </div>
        
        <div style="clear:both; float:none"></div>
	
  </div>

</div>

<br><br>
<div id="footer"><a href="https://github.com/vincenzodabronzo/beer_assistant" target="_blank"><img id="logo" src="img/logo.png"></a></div>
<br><br>		

	<div class="data" id="show_data" style="display: none;" >  </div>           

</div>

</body>

</html>