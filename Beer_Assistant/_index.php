<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<!--[if lt IE 9]><script src="/js/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
    
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.jqplot.min.js"></script>
    <script type="text/javascript" src="js/jqplot.dateAxisRenderer.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.jqplot.css" />
	
    
    <script type="text/javascript">
		$(document).ready(function() {
			
			setInterval(function() {
				$('#show').load('lib/data.php');
			}, 1000 );
			
			var t = 2000;
			var x = (new Date()).getTime(); // current time
			
			var n = 20;
			data = [];
			
			for(i=0; i<n; i++){  
			    data.push([x - (n-1-i)*t,0]);  
			}   
			
			var options = {      
			      axes: {   	    
			         xaxis: {   	   	   
			            numberTicks: 20,            
			            renderer:$.jqplot.DateAxisRenderer,           
			            tickOptions:{formatString:'%H:%M:%S'},            
			            min : data[0][0],           
			            max: data[data.length-1][0] 	   
			         }, 	    
			         yaxis: {
			            min: -10, 
			            max: 120,
			            numberTicks: 14,   	        
			            tickOptions:{formatString:'%1f'}  	    
			         }      
			      },      
			      seriesDefaults: {   	    
			         rendererOptions: { smooth: true}      
			      }  
			  };  
			 
			   var plot1 = $.jqplot ('myChart', [data],options); 
			 
			   $('button').click( function(){        
			      doUpdate();      
			      $(this).hide();  
			   });
			 
			   function doUpdate() {      
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

			
		});
    </script>
    
    
    <div id="myChart" style="height:400px; width:600px; "></div>
    <br><br>
    <button>Start Updates</button>
    <br>
    <!--  Chart Example 2
    <div id="chartdiv" style="height:400px; width:300px; "></div>
    <script type="text/javascript">
    $.jqplot('chartdiv',  [[[1, 2],[3,5.12],[5,13.1],[7,33.6],[9,85.9],[11,219.9]]],
	{ title:'Exponential Line',
  		axes:{yaxis:{min:-10, max:240}},
 		 series:[{color:'#5FAB78'}]
	});
    </script>
    -->
    
	<title>Mashing Temperature</title>
</head>

<body>
    <div id="show"></div>

</body>

</html>