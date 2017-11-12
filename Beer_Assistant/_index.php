<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<!--[if lt IE 9]><script src="/js/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
    
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.jqplot.min.js"></script>
    <script type="text/javascript" src="js/jqplot.dateAxisRenderer.js"></script>
    <script type="text/javascript" src="js/jqplot.pointLabels.js"></script>
    <script type="text/javascript" src="js/jqplot.canvasAxisLabelRenderer.js"></script>
    <script type="text/javascript" src="js/jqplot.canvasTextRenderer.js"></script>
    <script type="text/javascript" src="js/jqplot.canvasAxisTickRenderer.js"></script>
    <script type="text/javascript" src="js/jqplot.canvasOverlay.js"></script>
    <script type="text/javascript" src="js/jqplot.highlighter.js"></script>
	<script type="text/javascript" src="js/jqplot.cursor.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.jqplot.css" />
	
    
    <script type="text/javascript">

    	//  microsec interval
    	var t = 1000;
    	
		
		$(document).ready(function() {

			var update_graph = false;
			
			$('#stop').hide();
			
			var x = (new Date()).getTime(); // current time
			
			var n = 20;
			data = [];
			
			for(i=0; i<n; i++){  
			    data.push([x - (n-1-i)*t, 0]);  
			}   
			
			var options = {      
			      axes: {   	    
			         xaxis: {     	   
			        	tickRenderer:$.jqplot.CanvasAxisTickRenderer,
				        numberTicks: 10,            
			            renderer:$.jqplot.DateAxisRenderer,           
			            tickOptions:{
				            	formatString:'%H:%M:%S',
				            	// //labelPosition: 'middle', 
				                angle:-30
						},            
			            min : data[0][0],           
			            max: data[data.length-1][0],
			            label:'Time'	   
					}, 	    
					yaxis: {
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
			            min: -10, 
			            max: 120,
			            numberTicks: 14,   	        
			            tickOptions:{formatString:'%.1f'},
						label:'Temperature Celsius'   
					}      
			      },      
			      seriesDefaults: {   	    
			    	  pointLabels: {
		                    show: true
		                },
				         rendererOptions: { smooth: true}      
			      },
			      highlighter: {
			          show: true,
			          sizeAdjust: 7.5
			        },
			        cursor: {
			          show: false
			        }
			  };  
			 
			   var plot1 = $.jqplot ('myChart', [data],options); 
			 
				$('#start').click( function(){
					update_graph = true;     
					doUpdate();      
					$(this).hide();
					$('#stop').show();
			   });
				$('#stop').click( function(){
					update_graph = false;       
					$(this).hide();
					$('#start').show();
				});
			   
			 
				function doUpdate() {
					if (update_graph) {

						// var loc = window.location.pathname;
						// var dir = loc.substring(0, loc.lastIndexOf('/'));
						// var path = dir + '/py/temp_mashing_nosensor_v1_3.py';
						
						<?php shell_exec("python py/temp_mashing_nosensor_v1_3.py > /dev/null 2>/dev/null &"); ?>
						
						$('#show').load('lib/data.php');
						
						if(data.length > n-1){
							data.shift();
						}

						var y = $('#mashing_temp').text();

    					// var x = (new Date()).getTime();
    					

						var ts = $('#current_timestamp').text().split(/[- :]/);
        				// Apply each element to the Date function
       					var x = ( new Date(Date.UTC(ts[0], ts[1]-1, ts[2], ts[3], ts[4], ts[5])) ).getTime();
    					
    					
    					data.push([x,y]);
    					if (plot1) {
    						plot1.destroy();
    					}
    					plot1.series[0].data = data; 
    					options.axes.xaxis.min = data[0][0];
    					options.axes.xaxis.max = data[data.length-1][0];
    					plot1 = $.jqplot ('myChart', [data],options);
    
    					setTimeout(doUpdate, t);
					}
			   }

			
		});
    </script>
    
	<title>Mashing Temperature</title>
</head>

<body>
    <div id="myChart" style="height:400px; width:100%; "></div>
    <br><br>
    <button id="start" data-role="button">Start Mashing</button>
    <button id="stop" data-role="button">Mash out</button>
    <br>

    <div id="show"></div>

</body>

</html>