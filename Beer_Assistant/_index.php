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
	<link rel="stylesheet" type="text/css" href="css/jquery.jqplot.css" />
	
    
    <script type="text/javascript">

    	// 1 sec interval
    	var t = 1000;
    	
		
		$(document).ready(function() {

			var update_graph = false;
			
			$('#stop').hide();

			// Old function to get Data from php library
			// setInterval(function() { }, t );
			
			var x = (new Date()).getTime(); // current time
			
			var n = 20;
			data = [];
			
			for(i=0; i<n; i++){  
			    data.push([x - (n-1-i)*t,0]);  
			}   
			
			var options = {      
			      axes: {   	    
			         xaxis: {   	   	   
			            numberTicks: 10,            
			            renderer:$.jqplot.DateAxisRenderer,           
			            tickOptions:{formatString:'%H:%M:%S'},            
			            min : data[0][0],           
			            max: data[data.length-1][0] 	   
			         }, 	    
			         yaxis: {
			        	tickOptions: { formatString: "°C%'d"  },
			            min: -10, 
			            max: 120,
			            numberTicks: 14,   	        
			            tickOptions:{formatString:'%.1f'}  	    
			         }      
			      },      
			      seriesDefaults: {   	    
			    	  pointLabels: {
		                    show: true
		                },
				         rendererOptions: { smooth: true}      
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

						$('#show').load('lib/data.php');
						
						if(data.length > n-1){
							data.shift();
						}
    					var y = Math.random()*100;
    					var x = (new Date()).getTime();
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
    <div id="myChart" style="height:400px; width:600px; "></div>
    <br><br>
    <button id="start" data-role="button">Start Mashing</button>
    <button id="stop" data-role="button">Mash out</button>
    <br>
    <div id="show"></div>

</body>

</html>